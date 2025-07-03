<?php
// app/Controllers/ActivitiesController.php

namespace App\Controllers;

use App\Models\WorkplanActivityModel;
use App\Models\WorkplanModel;
use App\Models\BranchesModel;
use App\Models\UserModel;
use App\Models\GovStructureModel;
use App\Models\ProposalModel;
use App\Models\WorkplanTrainingActivityModel;
use App\Models\WorkplanInputActivityModel;
use App\Models\WorkplanInfrastructureActivityModel;
use App\Models\WorkplanOutputActivityModel;
use App\Services\PdfService;
use CodeIgniter\RESTful\ResourceController;

class ActivitiesController extends ResourceController
{
    protected $workplanActivityModel;
    protected $workplanModel;
    protected $branchModel;
    protected $userModel;
    protected $govStructureModel;
    protected $proposalModel;
    protected $workplanTrainingActivityModel;
    protected $workplanInputActivityModel;
    protected $workplanInfrastructureActivityModel;
    protected $workplanOutputActivityModel;
    protected $helpers = ['form', 'url', 'file', 'text', 'email'];

    public function __construct()
    {
        $this->workplanActivityModel = new WorkplanActivityModel();
        $this->workplanModel = new WorkplanModel();
        $this->branchModel = new BranchesModel();
        $this->userModel = new UserModel();
        $this->govStructureModel = new GovStructureModel();
        $this->proposalModel = new ProposalModel();
        $this->workplanTrainingActivityModel = new WorkplanTrainingActivityModel();
        $this->workplanInputActivityModel = new WorkplanInputActivityModel();
        $this->workplanInfrastructureActivityModel = new WorkplanInfrastructureActivityModel();
        $this->workplanOutputActivityModel = new WorkplanOutputActivityModel();

        // Create upload directories if they don't exist
        $this->createUploadDirectories();
    }

    /**
     * Create upload directories if they don't exist
     */
    private function createUploadDirectories()
    {
        $directories = [
            ROOTPATH . 'public/uploads/training',
            ROOTPATH . 'public/uploads/inputs',
            ROOTPATH . 'public/uploads/infrastructure',
            ROOTPATH . 'public/uploads/signing_sheets'
        ];

        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
        }
    }

    /**
     * Display a listing of activities assigned to the logged-in user or all activities for admin
     *
     * @return mixed
     */
    public function index()
    {
        $userId = session()->get('user_id');
        $userRole = session()->get('role');

        // Build the base query
        $query = $this->proposalModel
            ->select('
                proposal.*,
                workplans.title as workplan_title,
                workplan_activities.title as activity_title,
                workplan_activities.activity_type,
                workplan_activities.description,
                provinces.name as province_name,
                districts.name as district_name,
                CONCAT(supervisors.fname, " ", supervisors.lname) as supervisor_name,
                CONCAT(officers.fname, " ", officers.lname) as officer_name
            ')
            ->join('workplans', 'workplans.id = proposal.workplan_id', 'left')
            ->join('workplan_activities', 'workplan_activities.id = proposal.activity_id', 'left')
            ->join('gov_structure as provinces', 'provinces.id = proposal.province_id', 'left')
            ->join('gov_structure as districts', 'districts.id = proposal.district_id', 'left')
            ->join('users as supervisors', 'supervisors.id = proposal.supervisor_id', 'left')
            ->join('users as officers', 'officers.id = proposal.action_officer_id', 'left');

        // If user is admin, show all activities; otherwise, show only assigned activities
        if ($userRole === 'admin') {
            $proposals = $query->findAll();
            $title = 'All Activities';
        } else {
            $proposals = $query->where('proposal.action_officer_id', $userId)->findAll();
            $title = 'My Assigned Activities';
        }

        $data = [
            'title' => $title,
            'proposals' => $proposals,
            'isAdmin' => ($userRole === 'admin')
        ];

        return view('activities/activities_index', $data);
    }

    /**
     * Display the specified activity details
     *
     * @param int $id Proposal ID
     * @return mixed
     */
    public function show($id = null)
    {
        $userId = session()->get('user_id');

        // Get the proposal with all related details
        $proposal = $this->proposalModel
            ->select('
                proposal.*,
                workplans.title as workplan_title,
                workplan_activities.title as activity_title,
                workplan_activities.activity_type,
                workplan_activities.description,
                provinces.name as province_name,
                districts.name as district_name,
                CONCAT(supervisors.fname, " ", supervisors.lname) as supervisor_name,
                CONCAT(officers.fname, " ", officers.lname) as officer_name,
                branches.name as branch_name
            ')
            ->join('workplans', 'workplans.id = proposal.workplan_id', 'left')
            ->join('workplan_activities', 'workplan_activities.id = proposal.activity_id', 'left')
            ->join('gov_structure as provinces', 'provinces.id = proposal.province_id', 'left')
            ->join('gov_structure as districts', 'districts.id = proposal.district_id', 'left')
            ->join('users as supervisors', 'supervisors.id = proposal.supervisor_id', 'left')
            ->join('users as officers', 'officers.id = proposal.action_officer_id', 'left')
            ->join('branches', 'branches.id = workplan_activities.branch_id', 'left')
            ->find($id);

        if (!$proposal) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the user is authorized to view this activity (admin can view all activities)
        $userRole = session()->get('role');
        if ($userRole !== 'admin' && $proposal['action_officer_id'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to view this activity.');
        }

        // Check if there's already an implementation record based on activity type
        $implementationData = null;

        if ($proposal['activity_type'] === 'training') {
            $implementationData = $this->workplanTrainingActivityModel
                ->where('proposal_id', $proposal['id'])
                ->where('activity_id', $proposal['activity_id'])
                ->first();

            if ($implementationData) {
                // Decode JSON fields
                if (isset($implementationData['trainees'])) {
                    $implementationData['trainees'] = is_string($implementationData['trainees'])
                        ? json_decode($implementationData['trainees'], true)
                        : $implementationData['trainees'];
                }

                if (isset($implementationData['training_images'])) {
                    $implementationData['training_images'] = is_string($implementationData['training_images'])
                        ? json_decode($implementationData['training_images'], true)
                        : $implementationData['training_images'];
                }
            }
        } elseif ($proposal['activity_type'] === 'inputs') {
            $implementationData = $this->workplanInputActivityModel
                ->where('proposal_id', $proposal['id'])
                ->where('activity_id', $proposal['activity_id'])
                ->first();

            if ($implementationData) {
                // Decode JSON fields
                if (isset($implementationData['inputs'])) {
                    $implementationData['inputs'] = is_string($implementationData['inputs'])
                        ? json_decode($implementationData['inputs'], true)
                        : $implementationData['inputs'];
                }

                if (isset($implementationData['input_images'])) {
                    $implementationData['input_images'] = is_string($implementationData['input_images'])
                        ? json_decode($implementationData['input_images'], true)
                        : $implementationData['input_images'];
                }
            }
        } elseif ($proposal['activity_type'] === 'infrastructure') {
            $implementationData = $this->workplanInfrastructureActivityModel
                ->where('proposal_id', $proposal['id'])
                ->where('activity_id', $proposal['activity_id'])
                ->first();

            if ($implementationData && isset($implementationData['infrastructure_images'])) {
                $implementationData['infrastructure_images'] = is_string($implementationData['infrastructure_images'])
                    ? json_decode($implementationData['infrastructure_images'], true)
                    : $implementationData['infrastructure_images'];
            }
        }

        $data = [
            'title' => 'Activity Details',
            'proposal' => $proposal,
            'implementationData' => $implementationData
        ];

        return view('activities/activities_show', $data);
    }

    /**
     * Show the form for implementing an activity
     *
     * @param int $id Proposal ID
     * @return mixed
     */
    public function implement($id = null)
    {
        $userId = session()->get('user_id');

        // Get the proposal with all related details
        $proposal = $this->proposalModel
            ->select('
                proposal.*,
                workplans.title as workplan_title,
                workplan_activities.title as activity_title,
                workplan_activities.activity_type,
                workplan_activities.description,
                provinces.name as province_name,
                districts.name as district_name,
                CONCAT(supervisors.fname, " ", supervisors.lname) as supervisor_name
            ')
            ->join('workplans', 'workplans.id = proposal.workplan_id', 'left')
            ->join('workplan_activities', 'workplan_activities.id = proposal.activity_id', 'left')
            ->join('gov_structure as provinces', 'provinces.id = proposal.province_id', 'left')
            ->join('gov_structure as districts', 'districts.id = proposal.district_id', 'left')
            ->join('users as supervisors', 'supervisors.id = proposal.supervisor_id', 'left')
            ->find($id);

        if (!$proposal) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the user is authorized to implement this activity (admin can implement all activities)
        $userRole = session()->get('role');
        if ($userRole !== 'admin' && $proposal['action_officer_id'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to implement this activity.');
        }

        // Check if the proposal status is pending
        if ($proposal['status'] !== 'pending') {
            return redirect()->to('/activities/' . $id)->with('error', 'Only activities with pending status can be implemented.');
        }

        // Check if there's already an implementation record based on activity type
        $implementationData = null;

        if ($proposal['activity_type'] === 'training') {
            $implementationData = $this->workplanTrainingActivityModel
                ->where('proposal_id', $proposal['id'])
                ->where('activity_id', $proposal['activity_id'])
                ->first();

            if ($implementationData) {
                // Decode JSON fields
                if (isset($implementationData['trainees'])) {
                    $implementationData['trainees'] = is_string($implementationData['trainees'])
                        ? json_decode($implementationData['trainees'], true)
                        : $implementationData['trainees'];
                }

                if (isset($implementationData['training_images'])) {
                    $implementationData['training_images'] = is_string($implementationData['training_images'])
                        ? json_decode($implementationData['training_images'], true)
                        : $implementationData['training_images'];
                }
            }
        } elseif ($proposal['activity_type'] === 'inputs') {
            $implementationData = $this->workplanInputActivityModel
                ->where('proposal_id', $proposal['id'])
                ->where('activity_id', $proposal['activity_id'])
                ->first();

            if ($implementationData) {
                // Decode JSON fields
                if (isset($implementationData['inputs'])) {
                    $implementationData['inputs'] = is_string($implementationData['inputs'])
                        ? json_decode($implementationData['inputs'], true)
                        : $implementationData['inputs'];
                }

                if (isset($implementationData['input_images'])) {
                    $implementationData['input_images'] = is_string($implementationData['input_images'])
                        ? json_decode($implementationData['input_images'], true)
                        : $implementationData['input_images'];
                }
            }
        } elseif ($proposal['activity_type'] === 'infrastructure') {
            $implementationData = $this->workplanInfrastructureActivityModel
                ->where('proposal_id', $proposal['id'])
                ->where('activity_id', $proposal['activity_id'])
                ->first();

            if ($implementationData && isset($implementationData['infrastructure_images'])) {
                $implementationData['infrastructure_images'] = is_string($implementationData['infrastructure_images'])
                    ? json_decode($implementationData['infrastructure_images'], true)
                    : $implementationData['infrastructure_images'];
            }
        } elseif ($proposal['activity_type'] === 'output') {
            $implementationData = $this->workplanOutputActivityModel
                ->where('proposal_id', $proposal['id'])
                ->where('activity_id', $proposal['activity_id'])
                ->first();

            if ($implementationData) {
                // Decode JSON fields
                if (isset($implementationData['outputs'])) {
                    $implementationData['outputs'] = is_string($implementationData['outputs'])
                        ? json_decode($implementationData['outputs'], true)
                        : $implementationData['outputs'];
                }

                if (isset($implementationData['beneficiaries'])) {
                    $implementationData['beneficiaries'] = is_string($implementationData['beneficiaries'])
                        ? json_decode($implementationData['beneficiaries'], true)
                        : $implementationData['beneficiaries'];
                }

                if (isset($implementationData['output_images'])) {
                    $implementationData['output_images'] = is_string($implementationData['output_images'])
                        ? json_decode($implementationData['output_images'], true)
                        : $implementationData['output_images'];
                }
            }
        }

        $data = [
            'title' => 'Implement Activity',
            'proposal' => $proposal,
            'implementationData' => $implementationData,
            'validation' => \Config\Services::validation()
        ];

        return view('activities/activities_implement', $data);
    }

    /**
     * Process the implementation form submission
     *
     * @param int $id Proposal ID
     * @return mixed
     */
    public function saveImplementation($id = null)
    {
        $userId = session()->get('user_id');

        // Get the proposal
        $proposal = $this->proposalModel->find($id);

        if (!$proposal) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the user is authorized to implement this activity (admin can implement all activities)
        $userRole = session()->get('role');
        if ($userRole !== 'admin' && $proposal['action_officer_id'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to implement this activity.');
        }

        // Check if the proposal status is pending
        if ($proposal['status'] !== 'pending') {
            return redirect()->to('/activities/' . $id)->with('error', 'Only activities with pending status can be implemented.');
        }

        // Get the activity details
        $activity = $this->workplanActivityModel->find($proposal['activity_id']);

        if (!$activity) {
            return redirect()->to('/activities')->with('error', 'Activity details not found.');
        }

        // Process based on activity type
        if ($activity['activity_type'] === 'training') {
            return $this->saveTrainingImplementation($proposal, $activity);
        } elseif ($activity['activity_type'] === 'inputs') {
            return $this->saveInputImplementation($proposal, $activity);
        } elseif ($activity['activity_type'] === 'infrastructure') {
            return $this->saveInfrastructureImplementation($proposal, $activity);
        } elseif ($activity['activity_type'] === 'output') {
            return $this->saveOutputImplementation($proposal, $activity);
        }

        return redirect()->to('/activities')->with('error', 'Invalid activity type.');
    }

    /**
     * Save training implementation data
     *
     * @param array $proposal
     * @param array $activity
     * @return mixed
     */
    private function saveTrainingImplementation($proposal, $activity)
    {
        $userId = session()->get('user_id');

        // Validation rules
        $rules = [
            'trainers' => 'required|string',
            'topics' => 'required|string',
            'gps_coordinates' => 'required|string',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Process trainees data
        $trainees = [];
        $traineeNames = $this->request->getPost('trainee_name') ?? [];
        $traineeAges = $this->request->getPost('trainee_age') ?? [];
        $traineeGenders = $this->request->getPost('trainee_gender') ?? [];
        $traineePhones = $this->request->getPost('trainee_phone') ?? [];
        $traineeEmails = $this->request->getPost('trainee_email') ?? [];
        $traineeRemarks = $this->request->getPost('trainee_remarks') ?? [];

        foreach ($traineeNames as $index => $name) {
            if (!empty($name)) {
                $trainees[] = [
                    'name' => $name,
                    'age' => $traineeAges[$index] ?? '',
                    'gender' => $traineeGenders[$index] ?? '',
                    'phone' => $traineePhones[$index] ?? '',
                    'email' => $traineeEmails[$index] ?? '',
                    'remarks' => $traineeRemarks[$index] ?? ''
                ];
            }
        }

        // Handle existing and new training images
        $trainingImages = [];
        $files = $this->request->getFiles();

        // Keep existing images if selected
        $keepTrainingImages = $this->request->getPost('keep_training_images') ?? [];
        if (!empty($keepTrainingImages)) {
            $trainingImages = array_merge($trainingImages, $keepTrainingImages);
        }

        // Add new uploaded images
        if (isset($files['training_images'])) {
            foreach ($files['training_images'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH . 'public/uploads/training', $newName);
                    $trainingImages[] = 'public/uploads/training/' . $newName;
                }
            }
        }

        // Handle signing sheet file upload
        $signingSheetFilepath = null;
        $signingSheetFile = $this->request->getFile('signing_sheet');

        // Check if there's an existing record to get the current signing sheet filepath
        $existingRecord = $this->workplanTrainingActivityModel
            ->where('proposal_id', $proposal['id'])
            ->where('activity_id', $activity['id'])
            ->first();

        if ($existingRecord && !empty($existingRecord['signing_sheet_filepath'])) {
            $signingSheetFilepath = $existingRecord['signing_sheet_filepath'];
        }

        // Process new signing sheet file if uploaded
        if ($signingSheetFile && $signingSheetFile->isValid() && !$signingSheetFile->hasMoved()) {
            $newName = $signingSheetFile->getRandomName();
            $signingSheetFile->move(ROOTPATH . 'public/uploads/signing_sheets', $newName);
            $signingSheetFilepath = 'public/uploads/signing_sheets/' . $newName;
        }

        // Prepare data for saving
        $data = [
            'workplan_id' => $proposal['workplan_id'],
            'proposal_id' => $proposal['id'],
            'activity_id' => $activity['id'],
            'trainers' => $this->request->getPost('trainers'),
            'topics' => $this->request->getPost('topics'),
            'trainees' => json_encode($trainees),
            'training_images' => json_encode($trainingImages),
            'training_files' => json_encode([]), // No files for now
            'gps_coordinates' => $this->request->getPost('gps_coordinates'),
            'signing_sheet_filepath' => $signingSheetFilepath,
            'created_by' => $userId,
            'updated_by' => $userId
        ];

        // Check if record already exists
        $existingRecord = $this->workplanTrainingActivityModel
            ->where('proposal_id', $proposal['id'])
            ->where('activity_id', $activity['id'])
            ->first();

        if ($existingRecord) {
            $data['id'] = $existingRecord['id'];
        }

        // Save the data
        if ($this->workplanTrainingActivityModel->save($data)) {
            return redirect()->to('/activities/' . $proposal['id'])->with('success', 'Training activity implementation saved successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to save training activity implementation: ' . implode(', ', $this->workplanTrainingActivityModel->errors()));
        }
    }

    /**
     * Save input implementation data
     *
     * @param array $proposal
     * @param array $activity
     * @return mixed
     */
    private function saveInputImplementation($proposal, $activity)
    {
        $userId = session()->get('user_id');

        // Validation rules
        $rules = [
            'gps_coordinates' => 'required|string',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Process inputs data
        $inputs = [];
        $inputNames = $this->request->getPost('input_name') ?? [];
        $inputQuantities = $this->request->getPost('input_quantity') ?? [];
        $inputUnits = $this->request->getPost('input_unit') ?? [];
        $inputRemarks = $this->request->getPost('input_remarks') ?? [];

        foreach ($inputNames as $index => $name) {
            if (!empty($name)) {
                $inputs[] = [
                    'name' => $name,
                    'quantity' => $inputQuantities[$index] ?? '',
                    'unit' => $inputUnits[$index] ?? '',
                    'remarks' => $inputRemarks[$index] ?? ''
                ];
            }
        }

        if (empty($inputs)) {
            return redirect()->back()->withInput()->with('error', 'At least one input item is required.');
        }

        // Handle existing and new input images
        $inputImages = [];
        $files = $this->request->getFiles();

        // Keep existing images if selected
        $keepInputImages = $this->request->getPost('keep_input_images') ?? [];
        if (!empty($keepInputImages)) {
            $inputImages = array_merge($inputImages, $keepInputImages);
        }

        // Add new uploaded images
        if (isset($files['input_images'])) {
            foreach ($files['input_images'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH . 'public/uploads/inputs', $newName);
                    $inputImages[] = 'public/uploads/inputs/' . $newName;
                }
            }
        }

        // Handle signing sheet file upload
        $signingSheetFilepath = null;
        $signingSheetFile = $this->request->getFile('signing_sheet');

        // Check if there's an existing record to get the current signing sheet filepath
        $existingRecord = $this->workplanInputActivityModel
            ->where('proposal_id', $proposal['id'])
            ->where('activity_id', $activity['id'])
            ->first();

        if ($existingRecord && !empty($existingRecord['signing_sheet_filepath'])) {
            $signingSheetFilepath = $existingRecord['signing_sheet_filepath'];
        }

        // Process new signing sheet file if uploaded
        if ($signingSheetFile && $signingSheetFile->isValid() && !$signingSheetFile->hasMoved()) {
            $newName = $signingSheetFile->getRandomName();
            $signingSheetFile->move(ROOTPATH . 'public/uploads/signing_sheets', $newName);
            $signingSheetFilepath = 'public/uploads/signing_sheets/' . $newName;
        }

        // Prepare data for saving
        $data = [
            'workplan_id' => $proposal['workplan_id'],
            'proposal_id' => $proposal['id'],
            'activity_id' => $activity['id'],
            'inputs' => json_encode($inputs),
            'input_images' => json_encode($inputImages),
            'input_files' => json_encode([]), // No files for now
            'gps_coordinates' => $this->request->getPost('gps_coordinates'),
            'signing_sheet_filepath' => $signingSheetFilepath,
            'created_by' => $userId,
            'updated_by' => $userId
        ];

        // Check if record already exists
        $existingRecord = $this->workplanInputActivityModel
            ->where('proposal_id', $proposal['id'])
            ->where('activity_id', $activity['id'])
            ->first();

        if ($existingRecord) {
            $data['id'] = $existingRecord['id'];
        }

        // Save the data
        if ($this->workplanInputActivityModel->save($data)) {
            return redirect()->to('/activities/' . $proposal['id'])->with('success', 'Input activity implementation saved successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to save input activity implementation: ' . implode(', ', $this->workplanInputActivityModel->errors()));
        }
    }

    /**
     * Save infrastructure implementation data
     *
     * @param array $proposal
     * @param array $activity
     * @return mixed
     */
    private function saveInfrastructureImplementation($proposal, $activity)
    {
        $userId = session()->get('user_id');

        // Validation rules
        $rules = [
            'infrastructure' => 'required|string',
            'gps_coordinates' => 'required|string',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle existing and new infrastructure images
        $infrastructureImages = [];
        $files = $this->request->getFiles();

        // Keep existing images if selected
        $keepInfrastructureImages = $this->request->getPost('keep_infrastructure_images') ?? [];
        if (!empty($keepInfrastructureImages)) {
            $infrastructureImages = array_merge($infrastructureImages, $keepInfrastructureImages);
        }

        // Add new uploaded images
        if (isset($files['infrastructure_images'])) {
            foreach ($files['infrastructure_images'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH . 'public/uploads/infrastructure', $newName);
                    $infrastructureImages[] = 'public/uploads/infrastructure/' . $newName;
                }
            }
        }

        // Handle signing sheet file upload
        $signingSheetFilepath = null;
        $signingSheetFile = $this->request->getFile('signing_sheet');

        // Check if there's an existing record to get the current signing sheet filepath
        $existingRecord = $this->workplanInfrastructureActivityModel
            ->where('proposal_id', $proposal['id'])
            ->where('activity_id', $activity['id'])
            ->first();

        if ($existingRecord && !empty($existingRecord['signing_sheet_filepath'])) {
            $signingSheetFilepath = $existingRecord['signing_sheet_filepath'];
        }

        // Process new signing sheet file if uploaded
        if ($signingSheetFile && $signingSheetFile->isValid() && !$signingSheetFile->hasMoved()) {
            $newName = $signingSheetFile->getRandomName();
            $signingSheetFile->move(ROOTPATH . 'public/uploads/signing_sheets', $newName);
            $signingSheetFilepath = 'public/uploads/signing_sheets/' . $newName;
        }

        // Prepare data for saving
        $data = [
            'workplan_id' => $proposal['workplan_id'],
            'proposal_id' => $proposal['id'],
            'activity_id' => $activity['id'],
            'infrastructure' => $this->request->getPost('infrastructure'),
            'gps_coordinates' => $this->request->getPost('gps_coordinates'),
            'infrastructure_images' => json_encode($infrastructureImages),
            'infrastructure_files' => json_encode([]), // No files for now
            'signing_sheet_filepath' => $signingSheetFilepath,
            'created_by' => $userId,
            'updated_by' => $userId
        ];

        // Check if record already exists
        $existingRecord = $this->workplanInfrastructureActivityModel
            ->where('proposal_id', $proposal['id'])
            ->where('activity_id', $activity['id'])
            ->first();

        if ($existingRecord) {
            $data['id'] = $existingRecord['id'];
        }

        // Save the data
        if ($this->workplanInfrastructureActivityModel->save($data)) {
            return redirect()->to('/activities/' . $proposal['id'])->with('success', 'Infrastructure activity implementation saved successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to save infrastructure activity implementation: ' . implode(', ', $this->workplanInfrastructureActivityModel->errors()));
        }
    }

    /**
     * Save output activity implementation
     *
     * @param array $proposal
     * @param array $activity
     * @return mixed
     */
    private function saveOutputImplementation($proposal, $activity)
    {
        $userId = session()->get('user_id');

        // Handle file uploads
        $outputImages = [];
        $outputFiles = [];
        $signingSheetFilepath = '';

        // Handle output images upload
        $outputImageFiles = $this->request->getFiles();
        if (isset($outputImageFiles['output_images'])) {
            foreach ($outputImageFiles['output_images'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH . 'public/uploads/output_images', $newName);
                    $outputImages[] = 'public/uploads/output_images/' . $newName;
                }
            }
        }

        // Handle existing output images (keep selected ones)
        $keepOutputImages = $this->request->getPost('keep_output_images') ?? [];
        if (!empty($keepOutputImages)) {
            $outputImages = array_merge($outputImages, $keepOutputImages);
        }

        // Handle signing sheet upload
        $signingSheetFile = $this->request->getFile('signing_sheet');
        if ($signingSheetFile && $signingSheetFile->isValid() && !$signingSheetFile->hasMoved()) {
            $newName = $signingSheetFile->getRandomName();
            $signingSheetFile->move(ROOTPATH . 'public/uploads/signing_sheets', $newName);
            $signingSheetFilepath = 'public/uploads/signing_sheets/' . $newName;
        }

        // Process outputs data
        $outputNames = $this->request->getPost('output_name') ?? [];
        $outputQuantities = $this->request->getPost('output_quantity') ?? [];
        $outputUnits = $this->request->getPost('output_unit') ?? [];
        $outputRemarks = $this->request->getPost('output_remarks') ?? [];

        $outputs = [];
        for ($i = 0; $i < count($outputNames); $i++) {
            if (!empty($outputNames[$i])) {
                $outputs[] = [
                    'name' => $outputNames[$i],
                    'quantity' => $outputQuantities[$i] ?? '',
                    'unit' => $outputUnits[$i] ?? '',
                    'remarks' => $outputRemarks[$i] ?? ''
                ];
            }
        }

        // Process beneficiaries data
        $beneficiaryNames = $this->request->getPost('beneficiary_name') ?? [];
        $beneficiaryAges = $this->request->getPost('beneficiary_age') ?? [];
        $beneficiaryGenders = $this->request->getPost('beneficiary_gender') ?? [];
        $beneficiaryPhones = $this->request->getPost('beneficiary_phone') ?? [];
        $beneficiaryEmails = $this->request->getPost('beneficiary_email') ?? [];
        $beneficiaryRemarksArray = $this->request->getPost('beneficiary_remarks') ?? [];

        $beneficiaries = [];
        for ($i = 0; $i < count($beneficiaryNames); $i++) {
            if (!empty($beneficiaryNames[$i])) {
                $beneficiaries[] = [
                    'name' => $beneficiaryNames[$i],
                    'age' => $beneficiaryAges[$i] ?? '',
                    'gender' => $beneficiaryGenders[$i] ?? '',
                    'phone' => $beneficiaryPhones[$i] ?? '',
                    'email' => $beneficiaryEmails[$i] ?? '',
                    'remarks' => $beneficiaryRemarksArray[$i] ?? ''
                ];
            }
        }

        // Prepare data for saving
        $data = [
            'workplan_id' => $proposal['workplan_id'],
            'proposal_id' => $proposal['id'],
            'activity_id' => $activity['id'],
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
            'created_by' => $userId,
            'updated_by' => $userId
        ];

        // Check if record already exists
        $existingRecord = $this->workplanOutputActivityModel
            ->where('proposal_id', $proposal['id'])
            ->where('activity_id', $activity['id'])
            ->first();

        if ($existingRecord) {
            $data['id'] = $existingRecord['id'];
        }

        // Save the data
        if ($this->workplanOutputActivityModel->save($data)) {
            return redirect()->to('/activities/' . $proposal['id'])->with('success', 'Output activity implementation saved successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to save output activity implementation: ' . implode(', ', $this->workplanOutputActivityModel->errors()));
        }
    }

    /**
     * Get districts by province ID (AJAX)
     *
     * @param int $provinceId
     * @return mixed
     */
    public function getDistricts($provinceId = null)
    {
        $districts = $this->govStructureModel
            ->where('level', 'district')
            ->where('parent_id', $provinceId)
            ->orderBy('name', 'ASC')
            ->findAll();

        return $this->response->setJSON($districts);
    }

    /**
     * Submit an activity for supervision
     *
     * @param int $id Proposal ID
     * @return mixed
     */
    public function submitForSupervision($id = null)
    {
        $userId = session()->get('user_id');

        // Get the proposal
        $proposal = $this->proposalModel->find($id);

        if (!$proposal) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the user is authorized to submit this activity (admin can submit all activities)
        $userRole = session()->get('role');
        if ($userRole !== 'admin' && $proposal['action_officer_id'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to submit this activity.');
        }

        // Update the proposal status to "submitted"
        $remarks = 'Activity submitted for supervision by action officer.';
        $result = $this->proposalModel->updateStatus($id, 'submitted', $userId, $remarks);

        if ($result) {
            // Send email notification to supervisor and action officer
            $this->sendActivitySubmissionNotification($id, $remarks);

            return redirect()->to('/activities/' . $id)->with('success', 'Activity has been submitted for supervision successfully.');
        } else {
            return redirect()->to('/activities/' . $id)->with('error', 'Failed to submit activity for supervision.');
        }
    }

    /**
     * Export activity details as PDF
     *
     * @param int $id Activity ID
     * @return mixed
     */
    public function exportPdf($id = null)
    {
        $userId = session()->get('user_id');
        $userRole = session()->get('role');

        // Get the proposal to check authorization
        $proposal = $this->proposalModel->where('id', $id)->first();

        if (!$proposal) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the user is authorized to view this activity (admin can view all activities)
        if ($userRole !== 'admin' && $proposal['action_officer_id'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to export this activity.');
        }

        try {
            // Use PdfService to generate the PDF
            $pdfService = new PdfService();
            return $pdfService->generateActivityPdf($proposal['activity_id']);
        } catch (\Exception $e) {
            log_message('error', 'PDF Export Error: ' . $e->getMessage());
            return redirect()->to('/activities/' . $id)->with('error', 'Failed to generate PDF. Please try again.');
        }
    }

    /**
     * Send email notification when an activity is submitted for supervision
     *
     * @param int $proposalId ID of the proposal
     * @param string $remarks Submission remarks
     * @return bool Success or failure
     */
    protected function sendActivitySubmissionNotification($proposalId, $remarks)
    {
        try {
            // Get the complete proposal data with related details
            $proposal = $this->proposalModel
                ->select('
                    proposal.*,
                    workplans.title as workplan_title,
                    workplan_activities.title as activity_title,
                    workplan_activities.activity_type,
                    workplan_activities.description,
                    provinces.name as province_name,
                    districts.name as district_name,
                    CONCAT(supervisors.fname, " ", supervisors.lname) as supervisor_name,
                    CONCAT(officers.fname, " ", officers.lname) as officer_name,
                    supervisors.email as supervisor_email,
                    officers.email as officer_email,
                    branches.name as branch_name
                ')
                ->join('workplans', 'workplans.id = proposal.workplan_id', 'left')
                ->join('workplan_activities', 'workplan_activities.id = proposal.activity_id', 'left')
                ->join('gov_structure as provinces', 'provinces.id = proposal.province_id', 'left')
                ->join('gov_structure as districts', 'districts.id = proposal.district_id', 'left')
                ->join('users as supervisors', 'supervisors.id = proposal.supervisor_id', 'left')
                ->join('users as officers', 'officers.id = proposal.action_officer_id', 'left')
                ->join('branches', 'branches.id = workplan_activities.branch_id', 'left')
                ->find($proposalId);

            if (!$proposal) {
                log_message('error', 'Cannot send activity submission notification: Proposal not found');
                return false;
            }

            // Check if supervisor and action officer emails are available
            if (empty($proposal['supervisor_email'])) {
                log_message('error', 'Cannot send activity submission notification: Supervisor email not found');
                return false;
            }

            if (empty($proposal['officer_email'])) {
                log_message('error', 'Cannot send activity submission notification: Action officer email not found');
                return false;
            }

            // Get the submitter's information
            $submitterName = $proposal['officer_name'] ?? 'System User';
            $submitterEmail = $proposal['officer_email'] ?? 'noreply@dakoiims.com';

            // Format dates for display
            $startDate = date('d M Y', strtotime($proposal['date_start']));
            $endDate = date('d M Y', strtotime($proposal['date_end']));

            // Prepare email subject and message
            $subject = 'Activity Submitted for Supervision: ' . $proposal['activity_title'];

            // Set header color
            $headerColor = '#2196F3'; // Blue for submission

            // Create HTML email message for supervisor
            $supervisorMessage = '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>' . $subject . '</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: ' . $headerColor . '; color: white; padding: 15px; text-align: center; }
                    .content { padding: 20px; background-color: #f9f9f9; }
                    .footer { font-size: 12px; color: #777; padding: 10px; text-align: center; }
                    .highlight { background-color: #f5f5f5; padding: 15px; border-left: 4px solid #2196F3; margin: 20px 0; }
                    .status { font-weight: bold; color: #2196F3; }
                    .remarks { background-color: #e3f2fd; padding: 10px; border-radius: 4px; margin: 15px 0; }
                    h1 { margin-top: 0; font-size: 24px; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1>' . $subject . '</h1>
                    </div>
                    <div class="content">
                        <p>Dear ' . $proposal['supervisor_name'] . ',</p>

                        <p>An activity has been <span class="status">submitted for your supervision</span>.</p>

                        <div class="highlight">
                            <p><strong>Activity Details:</strong></p>
                            <p>Activity: ' . $proposal['activity_title'] . '</p>
                            <p>Workplan: ' . $proposal['workplan_title'] . '</p>
                            <p>Type: ' . ucfirst($proposal['activity_type']) . '</p>
                            <p>Location: ' . $proposal['location'] . ' (' . $proposal['province_name'] . (!empty($proposal['district_name']) ? ', ' . $proposal['district_name'] : '') . ')</p>
                            <p>Period: ' . $startDate . ' to ' . $endDate . '</p>
                            <p>Status: <span class="status">Submitted for Supervision</span></p>
                        </div>

                        ' . (!empty($remarks) ? '<div class="remarks"><strong>Submission Remarks:</strong><br>' . nl2br(esc($remarks)) . '</div>' : '') . '

                        <p>This activity was submitted by: <strong>' . $submitterName . '</strong> (' . $submitterEmail . ')</p>

                        <p>Please log into the system to review and supervise this activity.</p>

                        <p>Thank you,<br>
                        AMIS System</p>
                    </div>
                    <div class="footer">
                        <p>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </body>
            </html>';

            // Create HTML email message for action officer
            $officerMessage = '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>' . $subject . '</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: ' . $headerColor . '; color: white; padding: 15px; text-align: center; }
                    .content { padding: 20px; background-color: #f9f9f9; }
                    .footer { font-size: 12px; color: #777; padding: 10px; text-align: center; }
                    .highlight { background-color: #f5f5f5; padding: 15px; border-left: 4px solid #2196F3; margin: 20px 0; }
                    .status { font-weight: bold; color: #2196F3; }
                    .remarks { background-color: #e3f2fd; padding: 10px; border-radius: 4px; margin: 15px 0; }
                    h1 { margin-top: 0; font-size: 24px; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1>' . $subject . '</h1>
                    </div>
                    <div class="content">
                        <p>Dear ' . $proposal['officer_name'] . ',</p>

                        <p>Your activity has been <span class="status">submitted for supervision</span>.</p>

                        <div class="highlight">
                            <p><strong>Activity Details:</strong></p>
                            <p>Activity: ' . $proposal['activity_title'] . '</p>
                            <p>Workplan: ' . $proposal['workplan_title'] . '</p>
                            <p>Type: ' . ucfirst($proposal['activity_type']) . '</p>
                            <p>Location: ' . $proposal['location'] . ' (' . $proposal['province_name'] . (!empty($proposal['district_name']) ? ', ' . $proposal['district_name'] : '') . ')</p>
                            <p>Period: ' . $startDate . ' to ' . $endDate . '</p>
                            <p>Status: <span class="status">Submitted for Supervision</span></p>
                            <p>Supervisor: ' . $proposal['supervisor_name'] . '</p>
                        </div>

                        ' . (!empty($remarks) ? '<div class="remarks"><strong>Submission Remarks:</strong><br>' . nl2br(esc($remarks)) . '</div>' : '') . '

                        <p>Your activity has been submitted to your supervisor for review. You will be notified when the supervisor takes action.</p>

                        <p>Thank you,<br>
                        AMIS System</p>
                    </div>
                    <div class="footer">
                        <p>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </body>
            </html>';

            // Send the email to supervisor
            $supervisorResult = send_email($proposal['supervisor_email'], $subject, $supervisorMessage);

            if (!$supervisorResult) {
                log_message('error', 'Failed to send activity submission notification email to supervisor: ' . $proposal['supervisor_email']);
            } else {
                log_message('info', 'Activity submission notification email sent successfully to supervisor: ' . $proposal['supervisor_email']);
            }

            // Send the email to action officer
            $officerResult = send_email($proposal['officer_email'], $subject, $officerMessage);

            if (!$officerResult) {
                log_message('error', 'Failed to send activity submission notification email to action officer: ' . $proposal['officer_email']);
            } else {
                log_message('info', 'Activity submission notification email sent successfully to action officer: ' . $proposal['officer_email']);
            }

            return $supervisorResult && $officerResult;
        } catch (\Exception $e) {
            log_message('error', 'Exception sending activity submission notification email: ' . $e->getMessage());
            return false;
        }
    }
}
