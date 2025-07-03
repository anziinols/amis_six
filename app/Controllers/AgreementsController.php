<?php
// app/Controllers/AgreementsController.php

namespace App\Controllers;

use App\Models\AgreementsModel;
use App\Models\BranchesModel; // Assuming you have a BranchesModel

class AgreementsController extends BaseController
{
    protected $agreementsModel;
    protected $branchesModel;

    public function __construct()
    {
        $this->agreementsModel = new AgreementsModel();
        $this->branchesModel = new BranchesModel(); // Initialize BranchesModel
        helper(['form', 'url', 'session']);
    }

    // List all agreements
    public function index()
    {
        $data = [
            'title' => 'Agreements',
            'agreements' => $this->agreementsModel->getAgreementsWithBranch() // Fetch with branch names
        ];

        return view('agreements/agreements_index', $data);
    }

    // Show a single agreement
    public function show($id = null)
    {
        $agreement = $this->agreementsModel->getAgreementsWithBranch($id);

        if (empty($agreement)) {
            session()->setFlashdata('error', 'Agreement not found.');
            return redirect()->to('/agreements');
        }

        $data = [
            'title' => 'View Agreement',
            'agreement' => $agreement
        ];

        return view('agreements/agreements_show', $data);
    }

    // Show the form to create a new agreement
    public function new()
    {
        $data = [
            'title' => 'Create New Agreement',
            'branches' => $this->branchesModel->findAll() // Fetch branches for dropdown
        ];

        return view('agreements/agreements_new', $data);
    }

    // Process the creation of a new agreement
    public function create()
    {
        // Get post data
        $data = $this->request->getPost();
        
        // Handle parties data (convert comma-separated string to array for JSON storage)
        if (!empty($data['parties'])) {
            $partiesArray = explode(',', $data['parties']);
            // Trim whitespace from each item
            $partiesArray = array_map('trim', $partiesArray);
            $data['parties'] = $partiesArray;
        } else {
            $data['parties'] = [];
        }
        
        // Set created_by from session
        $data['created_by'] = session()->get('user_id');
        
        // Set status if not provided
        if (empty($data['status'])) {
            $data['status'] = 'draft';
        }
        
        // Initialize attachments array
        $data['attachments'] = [];
        
        // Handle file uploads
        $files = $this->request->getFiles();
        if (!empty($files['attachments'])) {
            foreach ($files['attachments'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    // Generate a new random name to avoid filename conflicts
                    $newName = $file->getRandomName();
                    $uploadPath = './public/uploads/agreements_attachments/';
                    
                    if ($file->move($uploadPath, $newName)) {
                        // Store file information
                        $data['attachments'][] = [
                            'original_name' => $file->getName(),
                            'stored_name' => $newName,
                            'path' => $uploadPath . $newName,
                            'size' => $file->getSize(),
                            'type' => $file->getClientMimeType()
                        ];
                    } else {
                        session()->setFlashdata('error', 'Failed to upload file: ' . $file->getName());
                        return redirect()->back()->withInput();
                    }
                } elseif ($file->getError() != UPLOAD_ERR_NO_FILE) {
                    session()->setFlashdata('error', 'File upload error: ' . $file->getErrorString());
                    return redirect()->back()->withInput();
                }
            }
        }
        
        // Validate data
        if (!$this->validate($this->agreementsModel->getValidationRules())) {
            session()->setFlashdata('error', 'Please check your input.');
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        // Save to database
        if ($this->agreementsModel->save($data)) {
            session()->setFlashdata('success', 'Agreement created successfully.');
            return redirect()->to('/agreements');
        } else {
            session()->setFlashdata('error', 'Failed to create agreement. ' . implode(' ', $this->agreementsModel->errors()));
            return redirect()->back()->withInput();
        }
    }

    // Show the form to edit an existing agreement
    public function edit($id = null)
    {
        $agreement = $this->agreementsModel->find($id);

        if (empty($agreement)) {
            session()->setFlashdata('error', 'Agreement not found.');
            return redirect()->to('/agreements');
        }

        $data = [
            'title' => 'Edit Agreement',
            'agreement' => $agreement,
            'branches' => $this->branchesModel->findAll()
        ];

        return view('agreements/agreements_edit', $data);
    }

    // Process the update of an existing agreement
    public function update($id = null)
    {
        // Find the agreement
        $agreement = $this->agreementsModel->find($id);
        if (empty($agreement)) {
            session()->setFlashdata('error', 'Agreement not found.');
            return redirect()->to('/agreements');
        }
        
        // Get post data
        $data = $this->request->getPost();
        
        // Handle parties data (convert comma-separated string to array for JSON storage)
        if (!empty($data['parties'])) {
            $partiesArray = explode(',', $data['parties']);
            // Trim whitespace from each item
            $partiesArray = array_map('trim', $partiesArray);
            $data['parties'] = $partiesArray;
        } else {
            $data['parties'] = [];
        }
        
        // Set updated_by from session
        $data['updated_by'] = session()->get('user_id');
        
        // Keep existing attachments
        $existingAttachments = $agreement['attachments'];
        if (!is_array($existingAttachments)) {
            $existingAttachments = [];
        }
        
        // Handle file uploads
        $files = $this->request->getFiles();
        $newAttachments = [];
        
        if (!empty($files['attachments'])) {
            foreach ($files['attachments'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    // Generate a new random name to avoid filename conflicts
                    $newName = $file->getRandomName();
                    $uploadPath = './public/uploads/agreements_attachments/';
                    
                    if ($file->move($uploadPath, $newName)) {
                        // Store file information
                        $newAttachments[] = [
                            'original_name' => $file->getName(),
                            'stored_name' => $newName,
                            'path' => $uploadPath . $newName,
                            'size' => $file->getSize(),
                            'type' => $file->getClientMimeType()
                        ];
                    } else {
                        session()->setFlashdata('error', 'Failed to upload file: ' . $file->getName());
                        return redirect()->back()->withInput();
                    }
                } elseif ($file->getError() != UPLOAD_ERR_NO_FILE) {
                    session()->setFlashdata('error', 'File upload error: ' . $file->getErrorString());
                    return redirect()->back()->withInput();
                }
            }
        }
        
        // Merge existing and new attachments
        $data['attachments'] = array_merge($existingAttachments, $newAttachments);
        
        // Validate data
        if (!$this->validate($this->agreementsModel->getValidationRules())) {
            session()->setFlashdata('error', 'Please check your input.');
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        
        // Save to database
        if ($this->agreementsModel->update($id, $data)) {
            session()->setFlashdata('success', 'Agreement updated successfully.');
            return redirect()->to('/agreements/' . $id);
        } else {
            session()->setFlashdata('error', 'Failed to update agreement. ' . implode(' ', $this->agreementsModel->errors()));
            return redirect()->back()->withInput();
        }
    }

    // Delete an agreement (soft delete)
    public function delete($id = null)
    {
        $agreement = $this->agreementsModel->find($id);
        if (empty($agreement)) {
            session()->setFlashdata('error', 'Agreement not found.');
            return redirect()->to('/agreements');
        }

        $data = [
            'deleted_by' => session()->get('user_id'),
            'is_deleted' => 1 // Mark as deleted
        ];

        // Use update to set deleted_by and is_deleted, then call delete for soft delete timestamp
        if ($this->agreementsModel->update($id, $data) && $this->agreementsModel->delete($id)) {
            session()->setFlashdata('success', 'Agreement deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Failed to delete agreement.');
        }

        return redirect()->to('/agreements');
    }

    // Handle file uploads
    private function handleFileUploads()
    {
        $uploadedFilesInfo = [];
        $files = $this->request->getFiles();

        // Check if 'attachments' files were uploaded
        if (isset($files['attachments'])) {
            // Create upload directory if it doesn't exist
            $uploadPath = './public/uploads/agreements_attachments/'; // Relative to index.php
            if (!is_dir($uploadPath)) {
                if (!mkdir($uploadPath, 0777, true)) {
                    log_message('error', 'Failed to create upload directory: ' . $uploadPath);
                    return false; // Indicate an upload error
                }
            }

            foreach ($files['attachments'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $originalName = $file->getName();
                    $newName = $file->getRandomName();

                    try {
                         if ($file->move($uploadPath, $newName)) {
                             $uploadedFilesInfo[] = [
                                 'original_name' => $originalName,
                                 'stored_name' => $newName, // Store the unique name
                                 'path' => $uploadPath . $newName,
                                 'size' => $file->getSize(),
                                 'type' => $file->getClientMimeType()
                             ];
                         } else {
                             log_message('error', 'File move failed: ' . $file->getErrorString() . ' (' . $file->getError() . ')');
                             return false; // Indicate an upload error
                         }
                    } catch (\Exception $e) {
                         log_message('error', 'File upload exception: ' . $e->getMessage());
                         return false; // Indicate an upload error
                    }
                }
                 // Allow uploads to proceed even if some files have errors, but log them
                 elseif ($file->getError() !== UPLOAD_ERR_NO_FILE) { // Log errors other than "no file uploaded"
                    log_message('warning', 'Attachment upload error: ' . $file->getErrorString() . ' (' . $file->getError() . ') for file ' . $file->getName());
                }
            }
        }

        return $uploadedFilesInfo; // Return array of uploaded file info
    }

     // Function to delete a specific attachment (requires AJAX or separate form submission)
    public function deleteAttachment($agreementId, $attachmentIndex)
    {
        // Fetch the agreement
        $agreement = $this->agreementsModel->find($agreementId);
        if (empty($agreement)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Agreement not found.'])->setStatusCode(404);
        }

        $attachments = $agreement['attachments'] ?? [];
        if (!is_array($attachments) || !isset($attachments[$attachmentIndex])) {
             return $this->response->setJSON(['success' => false, 'message' => 'Attachment not found.'])->setStatusCode(404);
        }

        $attachmentToDelete = $attachments[$attachmentIndex];
        $filePath = $attachmentToDelete['path'];

        // Remove the attachment entry from the array
        unset($attachments[$attachmentIndex]);
        // Re-index the array to prevent JSON issues if needed, though often not strictly necessary
        $updatedAttachments = array_values($attachments);

        // Update the database
        $updateData = [
            'attachments' => $updatedAttachments,
            'updated_by' => session()->get('user_id')
        ];

        if ($this->agreementsModel->update($agreementId, $updateData)) {
            // Delete the physical file
            if (file_exists($filePath)) {
                 @unlink($filePath);
            }
            // Respond with success (for AJAX)
             return $this->response->setJSON(['success' => true, 'message' => 'Attachment deleted successfully.']);
            // Or redirect with success message for standard forms
            // session()->setFlashdata('success', 'Attachment deleted successfully.');
            // return redirect()->to('/agreements/edit/' . $agreementId);
        } else {
            // Respond with error (for AJAX)
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update agreement attachments.'])->setStatusCode(500);
            // Or redirect with error message
            // session()->setFlashdata('error', 'Failed to delete attachment.');
            // return redirect()->to('/agreements/edit/' . $agreementId);
        }
    }

    // Function to download an attachment
    public function downloadAttachment($agreementId, $attachmentIndex)
    {
        $agreement = $this->agreementsModel->find($agreementId);
        if (empty($agreement)) {
            session()->setFlashdata('error', 'Agreement not found.');
            return redirect()->to('/agreements');
        }

        $attachments = $agreement['attachments'] ?? [];
        if (!is_array($attachments) || !isset($attachments[$attachmentIndex])) {
            session()->setFlashdata('error', 'Attachment not found.');
            return redirect()->to('/agreements/' . $agreementId);
        }

        $attachment = $attachments[$attachmentIndex];
        $filePath = $attachment['path'] ?? '';
        $fileName = $attachment['original_name'] ?? 'download';

        if (!empty($filePath) && file_exists($filePath)) {
            return $this->response->download($filePath, null)->setFileName($fileName);
        } else {
            session()->setFlashdata('error', 'Attachment file not found on server.');
            return redirect()->to('/agreements/' . $agreementId);
        }
    }
}