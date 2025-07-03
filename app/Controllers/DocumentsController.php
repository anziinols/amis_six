<?php
// app/Controllers/DocumentsController.php

namespace App\Controllers;

use App\Models\FolderModel;
use App\Models\DocumentModel;

/**
 * DocumentsController
 *
 * Controller for managing folders and documents
 */
class DocumentsController extends BaseController
{
    protected $folderModel;
    protected $documentModel;
    protected $uploadPath = 'public/uploads/documents/';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->folderModel = new FolderModel();
        $this->documentModel = new DocumentModel();
    }

    /**
     * Display the folders list
     */
    public function index()
    {
        // Get parent folder ID from URL if present
        $parentFolderId = $this->request->getVar('parent_id') ?? null;

        // Get current folder details if we're in a subfolder
        $currentFolder = null;
        if ($parentFolderId) {
            $currentFolder = $this->folderModel->find($parentFolderId);
            if (!$currentFolder) {
                return redirect()->to('documents')->with('error', 'Folder not found');
            }
        }

        // Get folder path for breadcrumbs
        $folderPath = [];
        if ($parentFolderId) {
            $folderPath = $this->folderModel->getFolderPath($parentFolderId);
        }

        // Get folders for current level
        $folders = $this->folderModel->getByParentFolderId($parentFolderId);

        // Get document counts for each folder
        foreach ($folders as &$folder) {
            // Get count of documents in this folder
            $folder['document_count'] = count($this->getDocumentsByFolderId($folder['id']));

            // Get count of subfolders
            $folder['subfolder_count'] = count($this->folderModel->getByParentFolderId($folder['id']));
        }

        // Documents for the current folder
        $documents = [];
        if ($parentFolderId) {
            $documents = $this->getDocumentsByFolderId($parentFolderId);
        }

        $data = [
            'title' => 'Document Management',
            'folders' => $folders,
            'documents' => $documents,
            'current_folder' => $currentFolder,
            'folder_path' => $folderPath,
            'parent_id' => $parentFolderId
        ];

        return view('documents/documents_folders_list', $data);
    }

    /**
     * Show the form to create a new folder
     */
    public function new()
    {
        // Get parent folder ID from URL if present
        $parentFolderId = $this->request->getVar('parent_id') ?? null;

        // Get current folder details if we're in a subfolder
        $currentFolder = null;
        if ($parentFolderId) {
            $currentFolder = $this->folderModel->find($parentFolderId);
        }

        // Get folder path for breadcrumbs
        $folderPath = [];
        if ($parentFolderId) {
            $folderPath = $this->folderModel->getFolderPath($parentFolderId);
        }

        // Get branches for dropdown
        $db = \Config\Database::connect();
        $branchesModel = $db->table('branches');
        $branches = $branchesModel->get()->getResultArray();

        $data = [
            'title' => 'Create New Folder',
            'current_folder' => $currentFolder,
            'folder_path' => $folderPath,
            'parent_id' => $parentFolderId,
            'branches' => $branches,
            'validation' => \Config\Services::validation()
        ];

        return view('documents/documents_folder_create', $data);
    }

    /**
     * Create a new folder
     */
    public function create()
    {
        // Validate form
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[255]',
            'branch_id' => 'required|integer',
            'description' => 'permit_empty',
            'access' => 'required|in_list[private,internal,public]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Prepare folder data
        $data = [
            'branch_id' => $this->request->getPost('branch_id'),
            'parent_folder_id' => $this->request->getPost('parent_id') ?: null,
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'access' => $this->request->getPost('access'),
            'created_by' => session()->get('user_id')
        ];

        // Insert folder
        $this->folderModel->insert($data);

        // Redirect based on parent ID
        $redirectUrl = 'documents';
        if ($this->request->getPost('parent_id')) {
            $redirectUrl .= '?parent_id=' . $this->request->getPost('parent_id');
        }

        return redirect()->to($redirectUrl)->with('success', 'Folder created successfully');
    }

    /**
     * Show the form to edit a folder
     */
    public function edit($id = null)
    {
        // Find folder
        $folder = $this->folderModel->find($id);
        if (!$folder) {
            return redirect()->to('documents')->with('error', 'Folder not found');
        }

        // Get folder path for breadcrumbs
        $folderPath = $this->folderModel->getFolderPath($id);

        // Get branches for dropdown
        $db = \Config\Database::connect();
        $branchesModel = $db->table('branches');
        $branches = $branchesModel->get()->getResultArray();

        $data = [
            'title' => 'Edit Folder',
            'folder' => $folder,
            'folder_path' => $folderPath,
            'branches' => $branches,
            'validation' => \Config\Services::validation()
        ];

        return view('documents/documents_folder_edit', $data);
    }

    /**
     * Update a folder
     */
    public function update($id = null)
    {
        // Find folder
        $folder = $this->folderModel->find($id);
        if (!$folder) {
            return redirect()->to('documents')->with('error', 'Folder not found');
        }

        // Validate form
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[255]',
            'branch_id' => 'required|integer',
            'description' => 'permit_empty',
            'access' => 'required|in_list[private,internal,public]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Prepare folder data
        $data = [
            'branch_id' => $this->request->getPost('branch_id'),
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'access' => $this->request->getPost('access'),
            'updated_by' => session()->get('user_id')
        ];

        // Update folder
        $this->folderModel->update($id, $data);

        // Redirect based on parent ID
        $redirectUrl = 'documents';
        if ($folder['parent_folder_id']) {
            $redirectUrl .= '?parent_id=' . $folder['parent_folder_id'];
        }

        return redirect()->to($redirectUrl)->with('success', 'Folder updated successfully');
    }

    /**
     * Delete a folder
     */
    public function delete($id = null)
    {
        // Find folder
        $folder = $this->folderModel->find($id);
        if (!$folder) {
            return redirect()->to('documents')->with('error', 'Folder not found');
        }

        // Check if folder has subfolders
        $subfolders = $this->folderModel->getByParentFolderId($id);
        if (count($subfolders) > 0) {
            return redirect()->back()->with('error', 'Cannot delete folder that contains subfolders.');
        }

        // Check if folder has documents
        $documents = $this->getDocumentsByFolderId($id);
        if (count($documents) > 0) {
            return redirect()->back()->with('error', 'Cannot delete folder that contains documents.');
        }

        // Delete folder
        $this->folderModel->delete($id);

        // Redirect based on parent ID
        $redirectUrl = 'documents';
        if ($folder['parent_folder_id']) {
            $redirectUrl .= '?parent_id=' . $folder['parent_folder_id'];
        }

        return redirect()->to($redirectUrl)->with('success', 'Folder deleted successfully');
    }

    /**
     * Show form to upload a document
     */
    public function newDocument($folderId = null)
    {
        // Check if folder exists
        $folder = $this->folderModel->find($folderId);
        if (!$folder) {
            return redirect()->to('documents')->with('error', 'Folder not found');
        }

        // Get folder path for breadcrumbs
        $folderPath = $this->folderModel->getFolderPath($folderId);

        // Get branches for dropdown
        $db = \Config\Database::connect();
        $branchesModel = $db->table('branches');
        $branches = $branchesModel->get()->getResultArray();

        $data = [
            'title' => 'Upload Document',
            'folder' => $folder,
            'folder_path' => $folderPath,
            'branches' => $branches,
            'validation' => \Config\Services::validation()
        ];

        return view('documents/documents_file_upload', $data);
    }

    /**
     * Upload a document to a folder
     */
    public function createDocument()
    {
        // Check if folder exists
        $folderId = $this->request->getPost('folder_id');
        $folder = $this->folderModel->find($folderId);
        if (!$folder) {
            return redirect()->to('documents')->with('error', 'Folder not found');
        }

        // Validate form
        $validation = \Config\Services::validation();
        $validation->setRules([
            'title' => 'required|min_length[3]|max_length[255]',
            'branch_id' => 'required|integer',
            'classification' => 'required|in_list[private,internal,public]',
            'doc_date' => 'permit_empty|valid_date[Y-m-d]',
            'authors' => 'permit_empty',
            'description' => 'permit_empty',
            'document_file' => 'uploaded[document_file]|max_size[document_file,10240]|ext_in[document_file,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Handle file upload
        $file = $this->request->getFile('document_file');
        if (!$file->isValid()) {
            return redirect()->back()->withInput()->with('error', 'Invalid file');
        }

        // Generate a unique file name
        $newName = $file->getRandomName();

        // Create upload folder if not exists
        $uploadDir = $this->uploadPath . $folderId;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Move file to upload directory
        $file->move($uploadDir, $newName);

        // Prepare document data
        $data = [
            'branch_id' => $this->request->getPost('branch_id'),
            'folder_id' => $folderId, // Add folder_id to the data array
            'classification' => $this->request->getPost('classification'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'doc_date' => $this->request->getPost('doc_date') ?: null,
            'authors' => $this->request->getPost('authors'),
            'file_path' => 'public/' . $this->uploadPath . $folderId . '/' . $newName, // Add file_path with public/ prefix
            'file_type' => $file->getClientMimeType(), // Add file_type
            'file_size' => $file->getSize(), // Add file_size
            'created_by' => session()->get('user_id')
        ];

        // Begin transaction
        $this->documentModel->db->transBegin();

        try {
            // Insert document record
            $documentId = $this->documentModel->insert($data);

            // Store file information in session for later use
            session()->set('document_file_' . $documentId, [
                'folder_id' => $folderId,
                'file_name' => $newName,
                'original_name' => $file->getClientName(),
                'file_size' => $file->getSize(),
                'file_type' => $file->getClientMimeType()
            ]);

            // Commit transaction
            $this->documentModel->db->transCommit();

            return redirect()->to('documents?parent_id=' . $folderId)
                            ->with('success', 'Document uploaded successfully');
        } catch (\Exception $e) {
            // Rollback transaction
            $this->documentModel->db->transRollback();

            // If file was uploaded but database insert failed, remove the file
            if (file_exists($uploadDir . '/' . $newName)) {
                unlink($uploadDir . '/' . $newName);
            }

            return redirect()->back()->withInput()
                            ->with('error', 'Failed to upload document: ' . $e->getMessage());
        }
    }

    /**
     * View document details
     */
    public function viewDocument($id = null)
    {
        // Get document details
        $document = $this->documentModel->find($id);

        if (!$document) {
            return redirect()->to('documents')->with('error', 'Document not found');
        }

        // Check if we have file_path in the document
        if (isset($document['file_path']) && !empty($document['file_path'])) {
            // Extract file information from file_path
            $document['file_name'] = basename($document['file_path']);
            $document['original_name'] = $document['file_name']; // Use file_name as original_name

            // If file_type and file_size are not already in the database record, get them from the file
            if (!isset($document['file_type']) || empty($document['file_type']) ||
                !isset($document['file_size']) || empty($document['file_size'])) {

                // Get file size and type if the file exists
                $filePath = str_replace('public/', '', $document['file_path']); // Remove 'public/' prefix for file operations
                if (file_exists($filePath)) {
                    if (!isset($document['file_size']) || empty($document['file_size'])) {
                        $document['file_size'] = filesize($filePath);
                    }
                    if (!isset($document['file_type']) || empty($document['file_type'])) {
                        $document['file_type'] = mime_content_type($filePath);
                    }
                }
            }
        } else {
            // Fallback to session data for backward compatibility
            $fileInfo = session()->get('document_file_' . $id);
            if (!$fileInfo) {
                return redirect()->to('documents')->with('error', 'Document file information not found');
            }

            // Add file information to document array
            $document['file_name'] = $fileInfo['file_name'];
            $document['original_name'] = $fileInfo['original_name'];

            // Use database values if available, otherwise use session data
            if (!isset($document['file_size']) || empty($document['file_size'])) {
                $document['file_size'] = $fileInfo['file_size'];
            }
            if (!isset($document['file_type']) || empty($document['file_type'])) {
                $document['file_type'] = $fileInfo['file_type'];
            }

            // Set folder_id if not already set
            if (!isset($document['folder_id']) || empty($document['folder_id'])) {
                $document['folder_id'] = $fileInfo['folder_id'];
            }
        }

        // Get folder details
        $folder = $this->folderModel->find($document['folder_id']);
        if (!$folder) {
            return redirect()->to('documents')->with('error', 'Associated folder not found');
        }

        // Get branch name
        $db = \Config\Database::connect();
        $branch = $db->table('branches')->where('id', $document['branch_id'])->get()->getRowArray();

        // Add folder and branch names to document array
        $document['folder_name'] = $folder['name'];
        $document['branch_name'] = $branch ? $branch['name'] : '';

        // Get folder path for breadcrumbs
        $folderPath = $this->folderModel->getFolderPath($document['folder_id']);

        $data = [
            'title' => 'Document Details',
            'document' => $document,
            'folder_path' => $folderPath
        ];

        return view('documents/documents_file_view', $data);
    }

    /**
     * Download a document
     */
    public function downloadDocument($id = null)
    {
        // Get document details
        $document = $this->documentModel->find($id);

        if (!$document) {
            return redirect()->to('documents')->with('error', 'Document not found');
        }

        // Check if we have file_path in the document
        if (isset($document['file_path']) && !empty($document['file_path'])) {
            // Get file path
            $filePath = str_replace('public/', '', $document['file_path']); // Remove 'public/' prefix for file operations
            $fileName = basename($document['file_path']);

            // Check if file exists
            if (!file_exists($filePath)) {
                return redirect()->to('documents')->with('error', 'File not found on server');
            }

            // Force download
            return $this->response->download($filePath, null)
                                  ->setFileName($fileName);
        } else {
            // Fallback to session data for backward compatibility
            $fileInfo = session()->get('document_file_' . $id);
            if (!$fileInfo) {
                return redirect()->to('documents')->with('error', 'Document file information not found');
            }

            // Check if file exists
            $filePath = $this->uploadPath . $fileInfo['folder_id'] . '/' . $fileInfo['file_name'];
            if (!file_exists($filePath)) {
                return redirect()->to('documents')->with('error', 'File not found on server');
            }

            // Force download
            return $this->response->download($filePath, null)
                                  ->setFileName($fileInfo['original_name']);
        }
    }

    /**
     * Delete a document
     */
    public function deleteDocument($id = null)
    {
        // Get document details
        $document = $this->documentModel->find($id);

        if (!$document) {
            return redirect()->to('documents')->with('error', 'Document not found');
        }

        $folderId = $document['folder_id']; // Store folder_id for redirect

        // Check if we have file_path in the document
        if (isset($document['file_path']) && !empty($document['file_path'])) {
            // Get file path
            $filePath = str_replace('public/', '', $document['file_path']); // Remove 'public/' prefix for file operations

            // Check if file exists and delete it
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        } else {
            // Fallback to session data for backward compatibility
            $fileInfo = session()->get('document_file_' . $id);
            if (!$fileInfo) {
                return redirect()->to('documents')->with('error', 'Document file information not found');
            }

            // Store folder_id for redirect if not already set
            if (!$folderId) {
                $folderId = $fileInfo['folder_id'];
            }

            // Check if file exists and delete it
            $filePath = $this->uploadPath . $fileInfo['folder_id'] . '/' . $fileInfo['file_name'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        try {
            // Delete document record
            $this->documentModel->delete($id);

            // Remove file information from session if it exists
            if (session()->has('document_file_' . $id)) {
                session()->remove('document_file_' . $id);
            }

            return redirect()->to('documents?parent_id=' . $folderId)
                            ->with('success', 'Document deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Failed to delete document: ' . $e->getMessage());
        }
    }

    /**
     * Helper method to get documents by folder ID
     */
    private function getDocumentsByFolderId($folderId)
    {
        // Get documents by folder ID directly from the model
        $documents = $this->documentModel->getByFolderId($folderId);

        // Add file information from session to each document for backward compatibility
        foreach ($documents as &$document) {
            $fileInfo = session()->get('document_file_' . $document['id']);
            if ($fileInfo) {
                // Add file information to document array if not already present
                if (!isset($document['file_name'])) {
                    $document['file_name'] = $fileInfo['file_name'];
                }
                if (!isset($document['original_name'])) {
                    $document['original_name'] = $fileInfo['original_name'];
                }
                if (!isset($document['file_size'])) {
                    $document['file_size'] = $fileInfo['file_size'];
                }
                if (!isset($document['file_type'])) {
                    $document['file_type'] = $fileInfo['file_type'];
                }
            }

            // Extract file name from file_path if available
            if (isset($document['file_path']) && !empty($document['file_path'])) {
                $document['file_name'] = basename($document['file_path']);
                // Set original_name to file_name if not set
                if (!isset($document['original_name'])) {
                    $document['original_name'] = $document['file_name'];
                }
            }
        }

        return $documents;
    }
}