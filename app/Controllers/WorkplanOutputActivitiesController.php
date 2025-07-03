<?php
// app/Controllers/WorkplanOutputActivitiesController.php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\WorkplanOutputActivityModel;
use App\Models\WorkplanModel;
use App\Models\WorkplanActivityModel;
use App\Models\ProposalModel;
use App\Models\BranchesModel;
use App\Models\UserModel;

/**
 * WorkplanOutputActivitiesController
 * 
 * Handles CRUD operations for workplan output activities
 * Uses RESTful approach with separate GET and POST methods
 */
class WorkplanOutputActivitiesController extends BaseController
{
    protected $workplanOutputActivityModel;
    protected $workplanModel;
    protected $workplanActivityModel;
    protected $proposalModel;
    protected $branchModel;
    protected $userModel;
    protected $helpers = ['form', 'url', 'session'];

    public function __construct()
    {
        $this->workplanOutputActivityModel = new WorkplanOutputActivityModel();
        $this->workplanModel = new WorkplanModel();
        $this->workplanActivityModel = new WorkplanActivityModel();
        $this->proposalModel = new ProposalModel();
        $this->branchModel = new BranchesModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display a list of output activities
     * GET /output-activities
     *
     * @return mixed
     */
    public function index()
    {
        $outputActivities = $this->workplanOutputActivityModel->getOutputWithDetails();

        $data = [
            'title' => 'Output Activities',
            'outputActivities' => $outputActivities
        ];

        return view('output_activities/output_activities_index', $data);
    }

    /**
     * Show the form for creating a new output activity
     * GET /output-activities/new
     *
     * @return mixed
     */
    public function new()
    {
        $data = [
            'title' => 'Create New Output Activity',
            'validation' => \Config\Services::validation(),
            'workplans' => $this->workplanModel->findAll(),
            'proposals' => $this->proposalModel->findAll(),
            'activities' => $this->workplanActivityModel->where('activity_type', 'output')->findAll()
        ];

        return view('output_activities/output_activities_new', $data);
    }

    /**
     * Create a new output activity record in the database
     * POST /output-activities/create
     *
     * @return mixed
     */
    public function create()
    {
        // Define validation rules
        $rules = [
            'workplan_id' => 'required|integer',
            'proposal_id' => 'permit_empty|integer',
            'activity_id' => 'permit_empty|integer',
            'delivery_date' => 'permit_empty|valid_date',
            'delivery_location' => 'permit_empty|max_length[255]',
            'total_value' => 'permit_empty|decimal',
            'gps_coordinates' => 'permit_empty|max_length[255]',
            'remarks' => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Process outputs data
        $outputs = [];
        $outputItems = $this->request->getPost('output_items') ?? [];
        $outputDescriptions = $this->request->getPost('output_descriptions') ?? [];
        $outputQuantities = $this->request->getPost('output_quantities') ?? [];
        $outputUnits = $this->request->getPost('output_units') ?? [];
        $outputSpecifications = $this->request->getPost('output_specifications') ?? [];

        for ($i = 0; $i < count($outputItems); $i++) {
            if (!empty($outputItems[$i])) {
                $outputs[] = [
                    'item' => $outputItems[$i],
                    'description' => $outputDescriptions[$i] ?? '',
                    'quantity' => $outputQuantities[$i] ?? '',
                    'unit' => $outputUnits[$i] ?? '',
                    'specifications' => $outputSpecifications[$i] ?? ''
                ];
            }
        }

        // Process beneficiaries data
        $beneficiaries = [];
        $beneficiaryNames = $this->request->getPost('beneficiary_names') ?? [];
        $beneficiaryContacts = $this->request->getPost('beneficiary_contacts') ?? [];
        $beneficiaryPhones = $this->request->getPost('beneficiary_phones') ?? [];
        $beneficiaryMembers = $this->request->getPost('beneficiary_members') ?? [];

        for ($i = 0; $i < count($beneficiaryNames); $i++) {
            if (!empty($beneficiaryNames[$i])) {
                $beneficiaries[] = [
                    'name' => $beneficiaryNames[$i],
                    'contact' => $beneficiaryContacts[$i] ?? '',
                    'phone' => $beneficiaryPhones[$i] ?? '',
                    'members' => $beneficiaryMembers[$i] ?? 0
                ];
            }
        }

        // Handle file uploads
        $outputImages = $this->handleFileUploads('output_images', 'outputs');
        $outputFiles = $this->handleFileUploads('output_files', 'outputs');
        $signingSheetFilepath = $this->handleSingleFileUpload('signing_sheet', 'signing_sheets');

        // Prepare data for saving
        $data = [
            'workplan_id' => $this->request->getPost('workplan_id'),
            'proposal_id' => $this->request->getPost('proposal_id') ?: null,
            'activity_id' => $this->request->getPost('activity_id') ?: null,
            'outputs' => json_encode($outputs),
            'output_images' => json_encode($outputImages),
            'output_files' => json_encode($outputFiles),
            'delivery_date' => $this->request->getPost('delivery_date') ?: null,
            'delivery_location' => $this->request->getPost('delivery_location'),
            'beneficiaries' => json_encode($beneficiaries),
            'total_value' => $this->request->getPost('total_value') ?: null,
            'gps_coordinates' => $this->request->getPost('gps_coordinates'),
            'signing_sheet_filepath' => $signingSheetFilepath,
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->workplanOutputActivityModel->save($data)) {
            return redirect()->to('/output-activities')->with('success', 'Output activity created successfully.');
        } else {
            log_message('error', 'Failed to create output activity: ' . print_r($this->workplanOutputActivityModel->errors(), true));
            return redirect()->back()->withInput()->with('error', 'Failed to create output activity. Please check logs.');
        }
    }

    /**
     * Display the specified output activity
     * GET /output-activities/{id}
     *
     * @param int|null $id
     * @return mixed
     */
    public function show($id = null)
    {
        $outputActivity = $this->workplanOutputActivityModel->getOutputWithDetails($id);

        if (!$outputActivity) {
            return redirect()->to('/output-activities')->with('error', 'Output activity not found.');
        }

        $data = [
            'title' => 'Output Activity Details',
            'outputActivity' => $outputActivity
        ];

        return view('output_activities/output_activities_show', $data);
    }

    /**
     * Show the form for editing the specified output activity
     * GET /output-activities/{id}/edit
     *
     * @param int|null $id
     * @return mixed
     */
    public function edit($id = null)
    {
        $outputActivity = $this->workplanOutputActivityModel->find($id);

        if (!$outputActivity) {
            return redirect()->to('/output-activities')->with('error', 'Output activity not found.');
        }

        $data = [
            'title' => 'Edit Output Activity',
            'outputActivity' => $outputActivity,
            'validation' => \Config\Services::validation(),
            'workplans' => $this->workplanModel->findAll(),
            'proposals' => $this->proposalModel->findAll(),
            'activities' => $this->workplanActivityModel->where('activity_type', 'output')->findAll()
        ];

        return view('output_activities/output_activities_edit', $data);
    }

    /**
     * Update the specified output activity in the database
     * POST /output-activities/{id}/update
     *
     * @param int|null $id
     * @return mixed
     */
    public function update($id = null)
    {
        $outputActivity = $this->workplanOutputActivityModel->find($id);

        if (!$outputActivity) {
            return redirect()->to('/output-activities')->with('error', 'Output activity not found.');
        }

        // Define validation rules
        $rules = [
            'workplan_id' => 'required|integer',
            'proposal_id' => 'permit_empty|integer',
            'activity_id' => 'permit_empty|integer',
            'delivery_date' => 'permit_empty|valid_date',
            'delivery_location' => 'permit_empty|max_length[255]',
            'total_value' => 'permit_empty|decimal',
            'gps_coordinates' => 'permit_empty|max_length[255]',
            'remarks' => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Process outputs data (same as create method)
        $outputs = [];
        $outputItems = $this->request->getPost('output_items') ?? [];
        $outputDescriptions = $this->request->getPost('output_descriptions') ?? [];
        $outputQuantities = $this->request->getPost('output_quantities') ?? [];
        $outputUnits = $this->request->getPost('output_units') ?? [];
        $outputSpecifications = $this->request->getPost('output_specifications') ?? [];

        for ($i = 0; $i < count($outputItems); $i++) {
            if (!empty($outputItems[$i])) {
                $outputs[] = [
                    'item' => $outputItems[$i],
                    'description' => $outputDescriptions[$i] ?? '',
                    'quantity' => $outputQuantities[$i] ?? '',
                    'unit' => $outputUnits[$i] ?? '',
                    'specifications' => $outputSpecifications[$i] ?? ''
                ];
            }
        }

        // Process beneficiaries data (same as create method)
        $beneficiaries = [];
        $beneficiaryNames = $this->request->getPost('beneficiary_names') ?? [];
        $beneficiaryContacts = $this->request->getPost('beneficiary_contacts') ?? [];
        $beneficiaryPhones = $this->request->getPost('beneficiary_phones') ?? [];
        $beneficiaryMembers = $this->request->getPost('beneficiary_members') ?? [];

        for ($i = 0; $i < count($beneficiaryNames); $i++) {
            if (!empty($beneficiaryNames[$i])) {
                $beneficiaries[] = [
                    'name' => $beneficiaryNames[$i],
                    'contact' => $beneficiaryContacts[$i] ?? '',
                    'phone' => $beneficiaryPhones[$i] ?? '',
                    'members' => $beneficiaryMembers[$i] ?? 0
                ];
            }
        }

        // Handle file uploads (keep existing files if no new ones uploaded)
        $outputImages = $this->handleFileUploads('output_images', 'outputs', $outputActivity['output_images'] ?? []);
        $outputFiles = $this->handleFileUploads('output_files', 'outputs', $outputActivity['output_files'] ?? []);
        $signingSheetFilepath = $this->handleSingleFileUpload('signing_sheet', 'signing_sheets', $outputActivity['signing_sheet_filepath'] ?? null);

        // Prepare data for updating
        $data = [
            'workplan_id' => $this->request->getPost('workplan_id'),
            'proposal_id' => $this->request->getPost('proposal_id') ?: null,
            'activity_id' => $this->request->getPost('activity_id') ?: null,
            'outputs' => json_encode($outputs),
            'output_images' => json_encode($outputImages),
            'output_files' => json_encode($outputFiles),
            'delivery_date' => $this->request->getPost('delivery_date') ?: null,
            'delivery_location' => $this->request->getPost('delivery_location'),
            'beneficiaries' => json_encode($beneficiaries),
            'total_value' => $this->request->getPost('total_value') ?: null,
            'gps_coordinates' => $this->request->getPost('gps_coordinates'),
            'signing_sheet_filepath' => $signingSheetFilepath,
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->workplanOutputActivityModel->update($id, $data)) {
            return redirect()->to('/output-activities')->with('success', 'Output activity updated successfully.');
        } else {
            log_message('error', 'Failed to update output activity: ' . print_r($this->workplanOutputActivityModel->errors(), true));
            return redirect()->back()->withInput()->with('error', 'Failed to update output activity. Please check logs.');
        }
    }

    /**
     * Delete the specified output activity
     * POST /output-activities/{id}/delete
     *
     * @param int|null $id
     * @return mixed
     */
    public function delete($id = null)
    {
        $outputActivity = $this->workplanOutputActivityModel->find($id);

        if (!$outputActivity) {
            return redirect()->to('/output-activities')->with('error', 'Output activity not found.');
        }

        // Soft delete the record
        if ($this->workplanOutputActivityModel->delete($id)) {
            return redirect()->to('/output-activities')->with('success', 'Output activity deleted successfully.');
        } else {
            return redirect()->to('/output-activities')->with('error', 'Failed to delete output activity.');
        }
    }

    /**
     * Handle multiple file uploads
     *
     * @param string $fieldName The form field name
     * @param string $folder The upload folder name
     * @param array $existingFiles Existing files to keep
     * @return array Array of file paths
     */
    private function handleFileUploads($fieldName, $folder, $existingFiles = [])
    {
        $uploadedFiles = $existingFiles;
        $files = $this->request->getFiles();

        if (isset($files[$fieldName])) {
            foreach ($files[$fieldName] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $uploadPath = ROOTPATH . 'public/uploads/' . $folder . '/';

                    // Create directory if it doesn't exist
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    if ($file->move($uploadPath, $newName)) {
                        $uploadedFiles[] = 'public/uploads/' . $folder . '/' . $newName;
                    }
                }
            }
        }

        return $uploadedFiles;
    }

    /**
     * Handle single file upload
     *
     * @param string $fieldName The form field name
     * @param string $folder The upload folder name
     * @param string|null $existingFile Existing file to keep if no new file
     * @return string|null File path or null
     */
    private function handleSingleFileUpload($fieldName, $folder, $existingFile = null)
    {
        $file = $this->request->getFile($fieldName);

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $uploadPath = ROOTPATH . 'public/uploads/' . $folder . '/';

            // Create directory if it doesn't exist
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if ($file->move($uploadPath, $newName)) {
                return 'public/uploads/' . $folder . '/' . $newName;
            }
        }

        return $existingFile;
    }

    /**
     * Get output activities by workplan ID (AJAX endpoint)
     * GET /output-activities/by-workplan/{workplanId}
     *
     * @param int $workplanId
     * @return mixed
     */
    public function getByWorkplan($workplanId)
    {
        $outputActivities = $this->workplanOutputActivityModel->getByWorkplanId($workplanId);
        return $this->response->setJSON($outputActivities);
    }

    /**
     * Get output activities by proposal ID (AJAX endpoint)
     * GET /output-activities/by-proposal/{proposalId}
     *
     * @param int $proposalId
     * @return mixed
     */
    public function getByProposal($proposalId)
    {
        $outputActivities = $this->workplanOutputActivityModel->getByProposalId($proposalId);
        return $this->response->setJSON($outputActivities);
    }
}
