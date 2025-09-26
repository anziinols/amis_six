<?php
// app/Controllers/ActivitiesController.php

namespace App\Controllers;

use App\Models\WorkplanActivityModel;
use App\Models\WorkplanModel;
use App\Models\BranchesModel;
use App\Models\UserModel;
use App\Models\GovStructureModel;
use App\Models\ActivitiesModel;
use App\Models\ActivitiesDocumentsModel;
use App\Models\ActivitiesTrainingModel;
use App\Models\WorkplanPeriodModel;
use App\Models\PerformanceOutputsModel;
use App\Models\WorkplanTrainingActivityModel;

use App\Models\WorkplanInfrastructureActivityModel;
use App\Models\WorkplanOutputActivityModel;
use App\Models\ActivitiesMeetingsModel;
use App\Models\ActivitiesOutputModel;
use App\Models\ActivitiesAgreementsModel;
use App\Models\ActivitiesInputModel;
use App\Models\ActivitiesInfrastructureModel;
use App\Services\PdfService;
use CodeIgniter\RESTful\ResourceController;

class ActivitiesController extends ResourceController
{
    protected $workplanActivityModel;
    protected $workplanModel;
    protected $branchModel;
    protected $userModel;
    protected $govStructureModel;
    protected $activitiesModel;
    protected $workplanPeriodModel;
    protected $performanceOutputsModel;
    protected $activitiesDocumentsModel;
    protected $activitiesTrainingModel;
    protected $workplanTrainingActivityModel;

    protected $workplanInfrastructureActivityModel;
    protected $workplanOutputActivityModel;
    protected $activitiesMeetingsModel;
    protected $activitiesOutputModel;
    protected $activitiesAgreementsModel;
    protected $activitiesInputModel;
    protected $activitiesInfrastructureModel;
    protected $helpers = ['form', 'url', 'file', 'text', 'email'];

    public function __construct()
    {
        $this->workplanActivityModel = new WorkplanActivityModel();
        $this->workplanModel = new WorkplanModel();
        $this->branchModel = new BranchesModel();
        $this->userModel = new UserModel();
        $this->govStructureModel = new GovStructureModel();
        $this->activitiesModel = new ActivitiesModel();
        $this->workplanPeriodModel = new WorkplanPeriodModel();
        $this->performanceOutputsModel = new PerformanceOutputsModel();
        $this->activitiesDocumentsModel = new ActivitiesDocumentsModel();
        $this->activitiesTrainingModel = new ActivitiesTrainingModel();
        $this->workplanTrainingActivityModel = new WorkplanTrainingActivityModel();

        $this->workplanInfrastructureActivityModel = new WorkplanInfrastructureActivityModel();
        $this->workplanOutputActivityModel = new WorkplanOutputActivityModel();
        $this->activitiesMeetingsModel = new ActivitiesMeetingsModel();
        $this->activitiesOutputModel = new ActivitiesOutputModel();
        $this->activitiesAgreementsModel = new ActivitiesAgreementsModel();
        $this->activitiesInputModel = new ActivitiesInputModel();
        $this->activitiesInfrastructureModel = new ActivitiesInfrastructureModel();

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
            ROOTPATH . 'public/uploads/infrastructure_images',
            ROOTPATH . 'public/uploads/infrastructure_files',
            ROOTPATH . 'public/uploads/documents',
            ROOTPATH . 'public/uploads/signing_sheets',
            ROOTPATH . 'public/uploads/agreement_attachments',
            ROOTPATH . 'public/uploads/output_images',
            ROOTPATH . 'public/uploads/output_files'
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

        // Filter activities based on user capabilities
        $isAdmin = session()->get('is_admin');
        $isSupervisor = session()->get('is_supervisor');

        if ($isAdmin == 1) {
            // Admin capability can see all activities
            $activities = $this->activitiesModel->getAllWithDetails();
            $title = 'All Activities';
        } elseif ($isSupervisor == 1) {
            // Supervisor capability can see activities they supervise
            $activities = $this->activitiesModel->getBySupervisor($userId);
            $title = 'Activities I Supervise';
        } else {
            // Regular users can see only their assigned activities (as action officers)
            $activities = $this->activitiesModel->getByActionOfficer($userId);
            $title = 'My Assigned Activities';
        }

        $data = [
            'title' => $title,
            'activities' => $activities,
            'isAdmin' => (session()->get('is_admin') == 1)
        ];

        return view('activities/activities_index', $data);
    }

    /**
     * Show form for creating new activity
     *
     * @return mixed
     */
    public function new()
    {
        // Get dropdown data
        $workplanPeriods = $this->workplanPeriodModel->getByStatus('approved');
        $provinces = $this->govStructureModel->getByLevel('province');
        $supervisors = $this->userModel->where('is_supervisor', 1)->findAll();
        $users = $this->userModel->findAll();
        $activityTypes = $this->activitiesModel->getActivityTypes();

        $data = [
            'title' => 'Create New Activity',
            'workplan_periods' => $workplanPeriods,
            'provinces' => $provinces,
            'supervisors' => $supervisors,
            'users' => $users,
            'activity_types' => $activityTypes,
            'validation' => \Config\Services::validation()
        ];

        return view('activities/activities_create', $data);
    }

    /**
     * Store new activity
     *
     * @return mixed
     */
    public function create()
    {
        $userId = session()->get('user_id');

        // Validation rules
        $rules = [
            'workplan_period_id' => 'required|integer',
            'performance_output_id' => 'required|integer',
            'activity_title' => 'required|max_length[500]',
            'activity_description' => 'required',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
            'date_start' => 'required|valid_date',
            'date_end' => 'required|valid_date',
            'type' => 'required|in_list[documents,trainings,meetings,agreements,inputs,infrastructures,outputs]',
            'total_cost' => 'permit_empty|decimal',
            'location' => 'permit_empty|max_length[255]',
            'supervisor_id' => 'permit_empty|integer',
            'action_officer_id' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data for saving
        $data = [
            'workplan_period_id' => $this->request->getPost('workplan_period_id'),
            'performance_output_id' => $this->request->getPost('performance_output_id'),
            'supervisor_id' => $this->request->getPost('supervisor_id') ?: null,
            'action_officer_id' => $this->request->getPost('action_officer_id') ?: null,
            'activity_title' => $this->request->getPost('activity_title'),
            'activity_description' => $this->request->getPost('activity_description'),
            'province_id' => $this->request->getPost('province_id'),
            'district_id' => $this->request->getPost('district_id'),
            'date_start' => $this->request->getPost('date_start'),
            'date_end' => $this->request->getPost('date_end'),
            'total_cost' => $this->request->getPost('total_cost') ?: null,
            'location' => $this->request->getPost('location'),
            'type' => $this->request->getPost('type'),
            'created_by' => $userId
        ];

        // Save the activity
        if ($this->activitiesModel->save($data)) {
            return redirect()->to('/activities')->with('success', 'Activity created successfully.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->activitiesModel->errors());
        }
    }

    /**
     * Display the specified activity details
     *
     * @param int $id Activity ID
     * @return mixed
     */
    public function show($id = null)
    {
        $userId = session()->get('user_id');

        // Get the activity with all related details
        $activity = $this->activitiesModel->getActivityWithDetails($id);

        if (!$activity) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the user is authorized to view this activity
        $isAdmin = session()->get('is_admin');
        if ($isAdmin != 1 &&
            $activity['action_officer_id'] != $userId &&
            $activity['supervisor_id'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to view this activity.');
        }

        // Check if there's already an implementation record based on activity type
        $implementationData = null;

        if ($activity['type'] === 'documents') {
            $implementationData = $this->activitiesDocumentsModel
                ->where('activity_id', $activity['id'])
                ->first();

            if ($implementationData) {
                // Decode JSON fields - document_files should already be decoded by model
                if (isset($implementationData['document_files']) && is_string($implementationData['document_files'])) {
                    $implementationData['document_files'] = json_decode($implementationData['document_files'], true);
                }

                // Get created by user name
                if ($implementationData['created_by']) {
                    $createdByUser = $this->userModel->find($implementationData['created_by']);
                    $implementationData['created_by_name'] = $createdByUser ? $createdByUser['fname'] . ' ' . $createdByUser['lname'] : 'N/A';
                }
            }
        } elseif ($activity['type'] === 'trainings') {
            $implementationData = $this->activitiesTrainingModel
                ->where('activity_id', $activity['id'])
                ->first();

            // ActivitiesTrainingModel automatically decodes JSON fields via afterFind callback
            // No manual JSON decoding needed
        } elseif ($activity['type'] === 'inputs') {
            $implementationData = $this->activitiesInputModel
                ->where('activity_id', $activity['id'])
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
        } elseif ($activity['type'] === 'infrastructures') {
            $implementationData = $this->activitiesInfrastructureModel
                ->where('activity_id', $activity['id'])
                ->first();

            // ActivitiesInfrastructureModel automatically decodes JSON fields via afterFind callback
            // No manual JSON decoding needed
        } elseif ($activity['type'] === 'meetings') {
            $implementationData = $this->activitiesMeetingsModel
                ->where('activity_id', $activity['id'])
                ->first();

            // ActivitiesMeetingsModel automatically decodes JSON fields via afterFind callback
            // No manual JSON decoding needed
        } elseif ($activity['type'] === 'outputs') {
            $implementationData = $this->activitiesOutputModel
                ->where('activity_id', $activity['id'])
                ->first();

            // ActivitiesOutputModel automatically decodes JSON fields via afterFind callback
            // No manual JSON decoding needed
        } elseif ($activity['type'] === 'agreements') {
            $implementationData = $this->activitiesAgreementsModel
                ->where('activity_id', $activity['id'])
                ->first();

            // ActivitiesAgreementsModel automatically decodes JSON fields via afterFind callback
            // No manual JSON decoding needed
        }

        $data = [
            'title' => 'Activity Details',
            'activity' => $activity,
            'implementationData' => $implementationData
        ];

        return view('activities/activities_show', $data);
    }

    /**
     * Show form for editing activity
     *
     * @param int $id Activity ID
     * @return mixed
     */
    public function edit($id = null)
    {
        $userId = session()->get('user_id');

        // Get the activity
        $activity = $this->activitiesModel->find($id);

        if (!$activity) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the user is authorized to edit this activity
        $isAdmin = session()->get('is_admin');
        if ($isAdmin != 1 && $activity['created_by'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to edit this activity.');
        }

        // Get dropdown data
        $workplanPeriods = $this->workplanPeriodModel->getByStatus('approved');
        $performanceOutputs = $this->performanceOutputsModel->getByPerformancePeriod($activity['workplan_period_id']);
        $provinces = $this->govStructureModel->getByLevel('province');
        $districts = $this->govStructureModel->where('level', 'district')
                                            ->where('parent_id', $activity['province_id'])
                                            ->findAll();
        $supervisors = $this->userModel->where('is_supervisor', 1)->findAll();
        $users = $this->userModel->findAll();
        $activityTypes = $this->activitiesModel->getActivityTypes();

        $data = [
            'title' => 'Edit Activity',
            'activity' => $activity,
            'workplan_periods' => $workplanPeriods,
            'performance_outputs' => $performanceOutputs,
            'provinces' => $provinces,
            'districts' => $districts,
            'supervisors' => $supervisors,
            'users' => $users,
            'activity_types' => $activityTypes,
            'validation' => \Config\Services::validation()
        ];

        return view('activities/activities_edit', $data);
    }

    /**
     * Update activity
     *
     * @param int $id Activity ID
     * @return mixed
     */
    public function update($id = null)
    {
        $userId = session()->get('user_id');

        // Get the activity
        $activity = $this->activitiesModel->find($id);

        if (!$activity) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the user is authorized to update this activity
        $isAdmin = session()->get('is_admin');
        if ($isAdmin != 1 && $activity['created_by'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to update this activity.');
        }

        // Validation rules
        $rules = [
            'workplan_period_id' => 'required|integer',
            'performance_output_id' => 'required|integer',
            'activity_title' => 'required|max_length[500]',
            'activity_description' => 'required',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
            'date_start' => 'required|valid_date',
            'date_end' => 'required|valid_date',
            'type' => 'required|in_list[documents,trainings,meetings,agreements,inputs,infrastructures,outputs]',
            'total_cost' => 'permit_empty|decimal',
            'location' => 'permit_empty|max_length[255]',
            'supervisor_id' => 'permit_empty|integer',
            'action_officer_id' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data for updating
        $data = [
            'id' => $id,
            'workplan_period_id' => $this->request->getPost('workplan_period_id'),
            'performance_output_id' => $this->request->getPost('performance_output_id'),
            'supervisor_id' => $this->request->getPost('supervisor_id') ?: null,
            'action_officer_id' => $this->request->getPost('action_officer_id') ?: null,
            'activity_title' => $this->request->getPost('activity_title'),
            'activity_description' => $this->request->getPost('activity_description'),
            'province_id' => $this->request->getPost('province_id'),
            'district_id' => $this->request->getPost('district_id'),
            'date_start' => $this->request->getPost('date_start'),
            'date_end' => $this->request->getPost('date_end'),
            'total_cost' => $this->request->getPost('total_cost') ?: null,
            'location' => $this->request->getPost('location'),
            'type' => $this->request->getPost('type'),
            'updated_by' => $userId
        ];

        // Update the activity
        if ($this->activitiesModel->save($data)) {
            return redirect()->to('/activities/' . $id)->with('success', 'Activity updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->activitiesModel->errors());
        }
    }

    /**
     * Delete activity
     *
     * @param int $id Activity ID
     * @return mixed
     */
    public function delete($id = null)
    {
        $userId = session()->get('user_id');

        // Get the activity
        $activity = $this->activitiesModel->find($id);

        if (!$activity) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the user is authorized to delete this activity
        $isAdmin = session()->get('is_admin');
        if ($isAdmin != 1 && $activity['created_by'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to delete this activity.');
        }

        // Soft delete the activity
        if ($this->activitiesModel->delete($id)) {
            return redirect()->to('/activities')->with('success', 'Activity deleted successfully.');
        } else {
            return redirect()->to('/activities')->with('error', 'Failed to delete activity.');
        }
    }

    /**
     * Show the form for implementing an activity
     *
     * @param int $id Activity ID
     * @return mixed
     */
    public function implement($id = null)
    {
        $userId = session()->get('user_id');

        // Get the activity with all related details
        $activity = $this->activitiesModel->getActivityWithDetails($id);

        if (!$activity) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the user is authorized to implement this activity
        // Only action officers can implement activities (admin can implement all)
        $userRole = session()->get('role');
        if ($userRole !== 'admin' && $activity['action_officer_id'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to implement this activity.');
        }

        // Check if the activity status is pending or active
        if (!in_array($activity['status'], ['pending', 'active'])) {
            return redirect()->to('/activities/' . $id)->with('error', 'Only activities with pending or active status can be implemented.');
        }

        // Check if there's already an implementation record based on activity type
        $implementationData = null;

        if ($activity['type'] === 'documents') {
            $implementationData = $this->activitiesDocumentsModel
                ->where('activity_id', $activity['id'])
                ->first();

            if ($implementationData) {
                // document_files is already decoded by the model's afterFind callback
                // No additional decoding needed
            }
        } elseif ($activity['type'] === 'trainings') {
            $implementationData = $this->activitiesTrainingModel
                ->where('activity_id', $activity['id'])
                ->first();

            // ActivitiesTrainingModel automatically decodes JSON fields via afterFind callback
            // No manual JSON decoding needed
        } elseif ($activity['type'] === 'inputs') {
            $implementationData = $this->activitiesInputModel
                ->where('activity_id', $activity['id'])
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
        } elseif ($activity['type'] === 'infrastructures') {
            $implementationData = $this->activitiesInfrastructureModel
                ->where('activity_id', $activity['id'])
                ->first();

            // ActivitiesInfrastructureModel automatically decodes JSON fields via afterFind callback
            // No manual JSON decoding needed
        } elseif ($activity['type'] === 'outputs') {
            $implementationData = $this->activitiesOutputModel
                ->where('activity_id', $activity['id'])
                ->first();

            // ActivitiesOutputModel automatically decodes JSON fields via afterFind callback
            // No manual JSON decoding needed
        } elseif ($activity['type'] === 'meetings') {
            $implementationData = $this->activitiesMeetingsModel
                ->where('activity_id', $activity['id'])
                ->first();

            // ActivitiesMeetingsModel automatically decodes JSON fields via afterFind callback
            // No manual JSON decoding needed
        } elseif ($activity['type'] === 'agreements') {
            $implementationData = $this->activitiesAgreementsModel
                ->where('activity_id', $activity['id'])
                ->first();

            // ActivitiesAgreementsModel automatically decodes JSON fields via afterFind callback
            // No manual JSON decoding needed
        }

        $data = [
            'title' => 'Implement Activity',
            'activity' => $activity,
            'implementationData' => $implementationData,
            'validation' => \Config\Services::validation()
        ];

        // Route to specific implementation view based on activity type
        $viewMap = [
            'documents' => 'activities/implementations/documents_implementation',
            'trainings' => 'activities/implementations/trainings_implementation',
            'inputs' => 'activities/implementations/inputs_implementation',
            'infrastructures' => 'activities/implementations/infrastructures_implementation',
            'meetings' => 'activities/implementations/meetings_implementation',
            'agreements' => 'activities/implementations/agreements_implementation',
            'outputs' => 'activities/implementations/outputs_implementation'
        ];

        $viewFile = $viewMap[$activity['type']] ?? 'activities/activities_implement';

        return view($viewFile, $data);
    }

    /**
     * Process the implementation form submission
     *
     * @param int $id Activity ID
     * @return mixed
     */
    public function saveImplementation($id = null)
    {
        $userId = session()->get('user_id');

        // Get the activity
        $activity = $this->activitiesModel->find($id);

        if (!$activity) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the user is authorized to implement this activity
        // Only action officers can implement activities (admin can implement all)
        $userRole = session()->get('role');
        if ($userRole !== 'admin' && $activity['action_officer_id'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to implement this activity.');
        }

        // Check if the activity status is pending or active
        if (!in_array($activity['status'], ['pending', 'active'])) {
            return redirect()->to('/activities/' . $id)->with('error', 'Only activities with pending or active status can be implemented.');
        }

        // Process based on activity type
        if ($activity['type'] === 'documents') {
            return $this->saveDocumentImplementation($activity);
        } elseif ($activity['type'] === 'trainings') {
            return $this->saveTrainingImplementation($activity);
        } elseif ($activity['type'] === 'inputs') {
            return $this->saveInputImplementation($activity);
        } elseif ($activity['type'] === 'infrastructures') {
            return $this->saveInfrastructureImplementation($activity);
        } elseif ($activity['type'] === 'outputs') {
            return $this->saveOutputImplementation($activity);
        } elseif ($activity['type'] === 'meetings') {
            return $this->saveMeetingImplementation($activity);
        } elseif ($activity['type'] === 'agreements') {
            return $this->saveAgreementImplementation($activity);
        }

        return redirect()->to('/activities')->with('error', 'Invalid activity type.');
    }

    /**
     * Save training implementation data
     *
     * @param array $activity
     * @return mixed
     */
    private function saveTrainingImplementation($activity)
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

        // Check if there's an existing record
        $existingRecord = $this->activitiesTrainingModel
            ->where('activity_id', $activity['id'])
            ->first();

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

        // Handle training files
        $trainingFiles = [];

        // Get existing training files if updating
        if ($existingRecord) {
            $trainingFiles = $existingRecord['training_files'] ?? [];

            // Handle removals
            $filesToRemove = json_decode($this->request->getPost('training_files_to_remove') ?? '[]', true) ?? [];
            foreach ($filesToRemove as $indexToRemove) {
                unset($trainingFiles[$indexToRemove]);
            }
            $trainingFiles = array_values($trainingFiles); // Re-index array

            // Handle caption updates
            $filesToUpdate = json_decode($this->request->getPost('training_files_to_update') ?? '{}', true) ?? [];
            foreach ($filesToUpdate as $index => $updates) {
                if (isset($trainingFiles[$index]) && isset($updates['caption'])) {
                    $trainingFiles[$index]['caption'] = $updates['caption'];
                }
            }
        }

        // Add new uploaded files
        if (isset($files['training_files'])) {
            $captions = $this->request->getPost('training_file_captions') ?? [];
            foreach ($files['training_files'] as $index => $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(ROOTPATH . 'public/uploads/training_files', $newName);
                    $trainingFiles[] = [
                        'caption' => $captions[$index] ?? '',
                        'original_name' => $file->getClientName(),
                        'file_path' => 'public/uploads/training_files/' . $newName
                    ];
                }
            }
        }

        // Handle signing sheet file upload
        $signingSheetFilepath = null;
        $signingSheetFile = $this->request->getFile('signing_sheet');



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
            'activity_id' => $activity['id'],
            'trainers' => $this->request->getPost('trainers'),
            'topics' => $this->request->getPost('topics'),
            'trainees' => $trainees,
            'training_images' => $trainingImages,
            'training_files' => $trainingFiles,
            'gps_coordinates' => $this->request->getPost('gps_coordinates'),
            'signing_sheet_filepath' => $signingSheetFilepath,
            'created_by' => $userId,
            'updated_by' => $userId
        ];

        // Check if record already exists
        if ($existingRecord) {
            $data['id'] = $existingRecord['id'];
        }

        // Save the data
        if ($this->activitiesTrainingModel->save($data)) {
            return redirect()->to('/activities/' . $activity['id'])->with('success', 'Training activity implementation saved successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to save training activity implementation: ' . implode(', ', $this->activitiesTrainingModel->errors()));
        }
    }

    /**
     * Save input implementation data
     *
     * @param array $activity Activity data
     * @return mixed
     */
    private function saveInputImplementation($activity)
    {
        $userId = session()->get('user_id');

        // Validation rules
        $validationRules = [
            'gps_coordinates' => 'required|max_length[255]',
            'remarks' => 'permit_empty',
            'input_images.*' => 'permit_empty|uploaded[input_images]|max_size[input_images,5120]|is_image[input_images]',
            'input_files.*' => 'permit_empty|uploaded[input_files]|max_size[input_files,5120]',
            'signing_sheet' => 'permit_empty|uploaded[signing_sheet]|max_size[signing_sheet,5120]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Check for existing record
        $existingRecord = $this->activitiesInputModel
            ->where('activity_id', $activity['id'])
            ->first();

        // Process input items
        $inputs = [];
        $inputNames = $this->request->getPost('input_name') ?: [];
        $inputQuantities = $this->request->getPost('input_quantity') ?: [];
        $inputUnits = $this->request->getPost('input_unit') ?: [];
        $inputRemarks = $this->request->getPost('input_remarks') ?: [];

        foreach ($inputNames as $index => $name) {
            if (!empty(trim($name))) {
                $inputs[] = [
                    'name' => trim($name),
                    'quantity' => trim($inputQuantities[$index] ?? ''),
                    'unit' => trim($inputUnits[$index] ?? ''),
                    'remarks' => trim($inputRemarks[$index] ?? '')
                ];
            }
        }

        // Process file uploads
        $inputImages = [];
        $inputFiles = [];
        $signingSheetFilepath = $existingRecord['signing_sheet_filepath'] ?? null;

        // Handle input images
        $imageFiles = $this->request->getFiles();
        if (isset($imageFiles['input_images'])) {
            foreach ($imageFiles['input_images'] as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $uploadPath = ROOTPATH . 'public/uploads/input_images';

                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $file->move($uploadPath, $newName);
                    $inputImages[] = 'public/uploads/input_images/' . $newName;
                }
            }
        }

        // Handle input files
        if (isset($imageFiles['input_files'])) {
            $fileCaptions = $this->request->getPost('file_captions') ?: [];

            foreach ($imageFiles['input_files'] as $index => $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $uploadPath = ROOTPATH . 'public/uploads/input_files';

                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $file->move($uploadPath, $newName);

                    $inputFiles[] = [
                        'caption' => $fileCaptions[$index] ?? $file->getClientName(),
                        'original_name' => $file->getClientName(),
                        'file_path' => 'public/uploads/input_files/' . $newName
                    ];
                }
            }
        }

        // Handle signing sheet
        $signingSheetFile = $this->request->getFile('signing_sheet');
        if ($signingSheetFile && $signingSheetFile->isValid() && !$signingSheetFile->hasMoved()) {
            $newName = $signingSheetFile->getRandomName();
            $uploadPath = ROOTPATH . 'public/uploads/signing_sheets';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $signingSheetFile->move($uploadPath, $newName);
            $signingSheetFilepath = 'public/uploads/signing_sheets/' . $newName;
        }

        // Prepare data
        $data = [
            'activity_id' => $activity['id'],
            'inputs' => $inputs,
            'input_images' => $inputImages,
            'input_files' => $inputFiles,
            'gps_coordinates' => $this->request->getPost('gps_coordinates'),
            'signing_sheet_filepath' => $signingSheetFilepath,
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => $userId,
            'updated_by' => $userId
        ];

        if ($existingRecord) {
            $data['id'] = $existingRecord['id'];
        }

        // Save data
        if ($this->activitiesInputModel->save($data)) {
            // Update activity status to 'active'
            $this->activitiesModel->update($activity['id'], [
                'status' => 'active',
                'updated_by' => $userId
            ]);

            return redirect()->to('/activities/' . $activity['id'])
                ->with('success', 'Input implementation saved successfully.');
        } else {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to save input implementation: ' .
                    implode(', ', $this->activitiesInputModel->errors()));
        }
    }

    /**
     * Save infrastructure implementation data
     *
     * @param array $activity
     * @return mixed
     */
    private function saveInfrastructureImplementation($activity)
    {
        $userId = session()->get('user_id');

        // Validation rules
        $validationRules = [
            'infrastructure' => 'required|max_length[255]',
            'gps_coordinates' => 'permit_empty|max_length[100]',
            'signing_sheet' => 'permit_empty|uploaded[signing_sheet]|max_size[signing_sheet,5120]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Check for existing record
        $existingRecord = $this->activitiesInfrastructureModel
            ->where('activity_id', $activity['id'])
            ->first();

        // Handle infrastructure images
        $infrastructureImages = [];
        if ($existingRecord && !empty($existingRecord['infrastructure_images'])) {
            $infrastructureImages = is_array($existingRecord['infrastructure_images']) ?
                $existingRecord['infrastructure_images'] :
                (json_decode($existingRecord['infrastructure_images'], true) ?: []);
        }

        $imageFiles = $this->request->getFiles();
        if (isset($imageFiles['infrastructure_images'])) {
            foreach ($imageFiles['infrastructure_images'] as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $uploadPath = ROOTPATH . 'public/uploads/infrastructure_images';

                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $file->move($uploadPath, $newName);
                    $infrastructureImages[] = 'public/uploads/infrastructure_images/' . $newName;
                }
            }
        }

        // Handle infrastructure files
        $infrastructureFiles = [];
        if ($existingRecord && !empty($existingRecord['infrastructure_files'])) {
            $infrastructureFiles = is_array($existingRecord['infrastructure_files']) ?
                $existingRecord['infrastructure_files'] :
                (json_decode($existingRecord['infrastructure_files'], true) ?: []);
        }

        $documentFiles = $this->request->getFiles();
        if (isset($documentFiles['infrastructure_files'])) {
            $fileDescriptions = $this->request->getPost('file_descriptions') ?: [];

            foreach ($documentFiles['infrastructure_files'] as $index => $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $uploadPath = ROOTPATH . 'public/uploads/infrastructure_files';

                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $file->move($uploadPath, $newName);

                    $infrastructureFiles[] = [
                        'filename' => $fileDescriptions[$index] ?? $file->getClientName(),
                        'original_name' => $file->getClientName(),
                        'path' => 'public/uploads/infrastructure_files/' . $newName
                    ];
                }
            }
        }

        // Handle signing sheet
        $signingSheetFilepath = $existingRecord['signing_scheet_filepath'] ?? null;
        $signingSheetFile = $this->request->getFile('signing_sheet');
        if ($signingSheetFile && $signingSheetFile->isValid() && !$signingSheetFile->hasMoved()) {
            $newName = $signingSheetFile->getRandomName();
            $uploadPath = ROOTPATH . 'public/uploads/signing_sheets';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $signingSheetFile->move($uploadPath, $newName);
            $signingSheetFilepath = 'public/uploads/signing_sheets/' . $newName;
        }

        // Prepare data
        $data = [
            'activity_id' => $activity['id'],
            'infrastructure' => $this->request->getPost('infrastructure'),
            'gps_coordinates' => $this->request->getPost('gps_coordinates'),
            'infrastructure_images' => $infrastructureImages,
            'infrastructure_files' => $infrastructureFiles,
            'signing_scheet_filepath' => $signingSheetFilepath,
            'created_by' => $userId,
            'updated_by' => $userId
        ];

        if ($existingRecord) {
            $data['id'] = $existingRecord['id'];
        }

        // Save data
        if ($this->activitiesInfrastructureModel->save($data)) {
            $this->activitiesModel->update($activity['id'], [
                'status' => 'active',
                'updated_by' => $userId
            ]);

            return redirect()->to('/activities/' . $activity['id'])
                ->with('success', 'Infrastructure implementation saved successfully.');
        } else {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to save infrastructure implementation: ' .
                    implode(', ', $this->activitiesInfrastructureModel->errors()));
        }
    }

    /**
     * Save output activity implementation
     *
     * @param array $activity
     * @return mixed
     */
    private function saveOutputImplementation($activity)
    {
        $userId = session()->get('user_id');

        // Get file for validation
        $signingSheetFile = $this->request->getFile('signing_sheet');

        // Validation rules
        $validationRules = [
            'gps_coordinates' => 'required|max_length[255]',
            'total_value' => 'permit_empty|decimal',
            'remarks' => 'permit_empty'
        ];

        // Only validate signing sheet if it's uploaded
        if ($signingSheetFile && $signingSheetFile->isValid()) {
            $validationRules['signing_sheet'] = 'uploaded[signing_sheet]|max_size[signing_sheet,5120]';
        }

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Check for existing record
        $existingRecord = $this->activitiesOutputModel
            ->where('activity_id', $activity['id'])
            ->first();

        // Process outputs
        $outputs = [];
        $outputNames = $this->request->getPost('output_name') ?: [];
        $outputQuantities = $this->request->getPost('output_quantity') ?: [];
        $outputUnits = $this->request->getPost('output_unit') ?: [];
        $outputDescriptions = $this->request->getPost('output_description') ?: [];

        foreach ($outputNames as $index => $name) {
            if (!empty(trim($name))) {
                $outputs[] = [
                    'name' => trim($name),
                    'quantity' => trim($outputQuantities[$index] ?? ''),
                    'unit' => trim($outputUnits[$index] ?? ''),
                    'description' => trim($outputDescriptions[$index] ?? '')
                ];
            }
        }

        // Process beneficiaries
        $beneficiaries = [];
        $beneficiaryNames = $this->request->getPost('beneficiary_name') ?: [];
        $beneficiaryOrganizations = $this->request->getPost('beneficiary_organization') ?: [];
        $beneficiaryContacts = $this->request->getPost('beneficiary_contact') ?: [];
        $beneficiaryTypes = $this->request->getPost('beneficiary_type') ?: [];

        foreach ($beneficiaryNames as $index => $name) {
            if (!empty(trim($name))) {
                $beneficiaries[] = [
                    'name' => trim($name),
                    'organization' => trim($beneficiaryOrganizations[$index] ?? ''),
                    'contact' => trim($beneficiaryContacts[$index] ?? ''),
                    'type' => trim($beneficiaryTypes[$index] ?? 'individual')
                ];
            }
        }

        // Handle output images - start with selected existing images
        $outputImages = [];

        // Get existing images for cleanup comparison
        $existingImages = [];
        if ($existingRecord && !empty($existingRecord['output_images'])) {
            $existingImages = is_array($existingRecord['output_images']) ?
                $existingRecord['output_images'] :
                (json_decode($existingRecord['output_images'], true) ?: []);
        }

        // Keep existing images if selected
        $keepOutputImages = $this->request->getPost('keep_output_images') ?? [];
        if (!empty($keepOutputImages)) {
            $outputImages = array_merge($outputImages, $keepOutputImages);
        }

        // Clean up unselected images from filesystem
        if (!empty($existingImages)) {
            foreach ($existingImages as $existingImage) {
                if (!in_array($existingImage, $keepOutputImages)) {
                    // Remove unselected image file
                    $filePath = ROOTPATH . $existingImage;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                        log_message('info', 'Removed output image file: ' . $filePath);
                    }
                }
            }
        }

        // Handle new output images
        $imageFiles = $this->request->getFiles();
        if (isset($imageFiles['output_images'])) {
            foreach ($imageFiles['output_images'] as $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $uploadPath = ROOTPATH . 'public/uploads/output_images';

                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $file->move($uploadPath, $newName);
                    $outputImages[] = 'public/uploads/output_images/' . $newName;
                }
            }
        }

        // Handle output files - start with selected existing files
        $outputFiles = [];

        // Get existing files for cleanup comparison
        $existingFiles = [];
        if ($existingRecord && !empty($existingRecord['output_files'])) {
            $existingFiles = is_array($existingRecord['output_files']) ?
                $existingRecord['output_files'] :
                (json_decode($existingRecord['output_files'], true) ?: []);
        }

        // Keep existing files if selected
        $keepOutputFiles = $this->request->getPost('keep_output_files') ?? [];
        if (!empty($keepOutputFiles) && !empty($existingFiles)) {
            foreach ($keepOutputFiles as $fileIndex) {
                if (isset($existingFiles[$fileIndex])) {
                    $outputFiles[] = $existingFiles[$fileIndex];
                }
            }
        }

        // Clean up unselected files from filesystem
        if (!empty($existingFiles)) {
            foreach ($existingFiles as $index => $existingFile) {
                if (!in_array($index, $keepOutputFiles)) {
                    // Remove unselected file
                    $filePath = ROOTPATH . $existingFile['path'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                        log_message('info', 'Removed output file: ' . $filePath);
                    }
                }
            }
        }

        // Handle new output files
        if (isset($imageFiles['output_files'])) {
            $fileDescriptions = $this->request->getPost('file_descriptions') ?: [];

            foreach ($imageFiles['output_files'] as $index => $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $uploadPath = ROOTPATH . 'public/uploads/output_files';

                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $file->move($uploadPath, $newName);

                    $outputFiles[] = [
                        'filename' => $fileDescriptions[$index] ?? $file->getClientName(),
                        'original_name' => $file->getClientName(),
                        'path' => 'public/uploads/output_files/' . $newName
                    ];
                }
            }
        }

        // Handle signing sheet
        $signingSheetFilepath = $existingRecord['signing_sheet_filepath'] ?? null;
        if ($signingSheetFile && $signingSheetFile->isValid() && !$signingSheetFile->hasMoved()) {
            $newName = $signingSheetFile->getRandomName();
            $signingSheetFile->move(ROOTPATH . 'public/uploads/signing_sheets', $newName);
            $signingSheetFilepath = 'public/uploads/signing_sheets/' . $newName;
        }

        // Ensure we have valid arrays before encoding
        $outputs = is_array($outputs) ? $outputs : [];
        $outputImages = is_array($outputImages) ? $outputImages : [];
        $outputFiles = is_array($outputFiles) ? $outputFiles : [];
        $beneficiaries = is_array($beneficiaries) ? $beneficiaries : [];

        // Prepare data - manually encode JSON fields since model callbacks expect different data structure
        $data = [
            'activity_id' => $activity['id'],
            'outputs' => json_encode($outputs),
            'output_images' => json_encode($outputImages),
            'output_files' => json_encode($outputFiles),
            'beneficiaries' => json_encode($beneficiaries),
            'total_value' => $this->request->getPost('total_value') ?: null,
            'gps_coordinates' => $this->request->getPost('gps_coordinates'),
            'signing_sheet_filepath' => $signingSheetFilepath,
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => $userId,
            'updated_by' => $userId
        ];

        if ($existingRecord) {
            $data['id'] = $existingRecord['id'];
        }

        // Save data
        try {
            if ($this->activitiesOutputModel->save($data)) {
                $this->activitiesModel->update($activity['id'], [
                    'status' => 'active',
                    'updated_by' => $userId
                ]);

                return redirect()->to('/activities/' . $activity['id'])
                    ->with('success', 'Output implementation saved successfully.');
            } else {
                $errors = $this->activitiesOutputModel->errors();
                log_message('error', 'Output implementation save failed: ' . print_r($errors, true));
                log_message('error', 'Data being saved: ' . print_r($data, true));

                return redirect()->back()->withInput()
                    ->with('error', 'Failed to save output implementation: ' .
                        implode(', ', $errors));
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception in saveOutputImplementation: ' . $e->getMessage());
            log_message('error', 'Data being saved: ' . print_r($data, true));

            return redirect()->back()->withInput()
                ->with('error', 'An error occurred while saving: ' . $e->getMessage());
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
     * Get performance outputs by workplan period ID (AJAX)
     *
     * @param int $workplanPeriodId
     * @return mixed
     */
    public function getPerformanceOutputs($workplanPeriodId = null)
    {
        $performanceOutputs = $this->performanceOutputsModel->getByPerformancePeriod($workplanPeriodId);

        return $this->response->setJSON($performanceOutputs);
    }

    /**
     * Submit an activity for supervision
     *
     * @param int $id Activity ID
     * @return mixed
     */
    public function submitForSupervision($id = null)
    {
        $userId = session()->get('user_id');

        // Get the activity
        $activity = $this->activitiesModel->find($id);

        if (!$activity) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the user is authorized to submit this activity
        // Only action officers can submit activities (admin can submit all)
        $isAdmin = session()->get('is_admin');
        if ($isAdmin != 1 && $activity['action_officer_id'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to submit this activity.');
        }

        // Update the activity status to "submitted"
        $remarks = 'Activity submitted for supervision by action officer.';
        $result = $this->activitiesModel->updateStatus($id, 'submitted', $remarks);

        if ($result) {
            // Send email notification to supervisor and action officer
            $this->sendActivitySubmissionNotification($id, $remarks);

            return redirect()->to('/activities/' . $id)->with('success', 'Activity has been submitted for supervision successfully.');
        } else {
            return redirect()->to('/activities/' . $id)->with('error', 'Failed to submit activity for supervision.');
        }
    }

    /**
     * Show supervision view for an activity
     *
     * @param int $id Activity ID
     * @return mixed
     */
    public function supervise($id = null)
    {
        $userId = session()->get('user_id');

        // Get the activity with all related details
        $activity = $this->activitiesModel->getActivityWithDetails($id);

        if (!$activity) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the activity is in submitted status
        if ($activity['status'] !== 'submitted') {
            return redirect()->to('/activities')->with('error', 'Activity is not in submitted status and cannot be supervised.');
        }

        // Check if the user is authorized to supervise this activity
        // Only supervisors assigned to this activity or admin can supervise
        $isAdmin = session()->get('is_admin');
        if ($isAdmin != 1 && $activity['supervisor_id'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to supervise this activity.');
        }

        // Get implementation data based on activity type
        $implementationData = null;
        if ($activity['type'] === 'documents') {
            $implementationData = $this->activitiesDocumentsModel
                ->where('activity_id', $activity['id'])
                ->first();

            if ($implementationData) {
                // Decode JSON fields - document_files should already be decoded by model
                if (isset($implementationData['document_files']) && is_string($implementationData['document_files'])) {
                    $implementationData['document_files'] = json_decode($implementationData['document_files'], true);
                }
            }
        } elseif ($activity['type'] === 'trainings') {
            $implementationData = $this->activitiesTrainingModel
                ->where('activity_id', $activity['id'])
                ->first();
            // ActivitiesTrainingModel automatically decodes JSON fields via afterFind callback
        } elseif ($activity['type'] === 'inputs') {
            $implementationData = $this->activitiesInputModel
                ->where('activity_id', $activity['id'])
                ->first();
        } elseif ($activity['type'] === 'infrastructures') {
            $implementationData = $this->activitiesInfrastructureModel
                ->where('activity_id', $activity['id'])
                ->first();

            // ActivitiesInfrastructureModel automatically decodes JSON fields via afterFind callback
            // No manual JSON decoding needed
        } elseif ($activity['type'] === 'outputs') {
            $implementationData = $this->activitiesOutputModel
                ->where('activity_id', $activity['id'])
                ->first();
        } elseif ($activity['type'] === 'meetings') {
            $implementationData = $this->activitiesMeetingsModel
                ->where('activity_id', $activity['id'])
                ->first();
        }

        $data = [
            'title' => 'Supervise Activity',
            'activity' => $activity,
            'implementationData' => $implementationData,
            'validation' => \Config\Services::validation()
        ];

        // Route to specific supervision view based on activity type
        $viewMap = [
            'documents' => 'activities/supervisions/documents_supervise',
            'trainings' => 'activities/supervisions/trainings_supervise',
            'inputs' => 'activities/supervisions/inputs_supervise',
            'infrastructures' => 'activities/supervisions/infrastructures_supervise',
            'meetings' => 'activities/supervisions/meetings_supervise',
            'agreements' => 'activities/supervisions/agreements_supervise',
            'outputs' => 'activities/supervisions/outputs_supervise'
        ];

        $view = $viewMap[$activity['type']] ?? 'activities/activities_show';
        return view($view, $data);
    }

    /**
     * Process supervision decision (approve or resend)
     *
     * @param int $id Activity ID
     * @return mixed
     */
    public function processSupervision($id = null)
    {
        $userId = session()->get('user_id');

        // Get the activity
        $activity = $this->activitiesModel->find($id);

        if (!$activity) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the activity is in submitted status
        if ($activity['status'] !== 'submitted') {
            return redirect()->to('/activities')->with('error', 'Activity is not in submitted status and cannot be supervised.');
        }

        // Check if the user is authorized to supervise this activity
        $isAdmin = session()->get('is_admin');
        if ($isAdmin != 1 && $activity['supervisor_id'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to supervise this activity.');
        }

        // Validate input
        $rules = [
            'supervision_decision' => 'required|in_list[approve,resend]',
            'status_remarks' => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $decision = $this->request->getPost('supervision_decision');
        $remarks = $this->request->getPost('status_remarks') ?? '';

        // Determine new status based on decision
        $newStatus = ($decision === 'approve') ? 'approved' : 'active';
        $defaultRemarks = ($decision === 'approve')
            ? 'Activity approved by supervisor.'
            : 'Activity resent for re-implementation by supervisor.';

        $finalRemarks = !empty($remarks) ? $remarks : $defaultRemarks;

        // Update the activity status
        $result = $this->activitiesModel->updateStatus($id, $newStatus, $finalRemarks);

        if ($result) {
            $message = ($decision === 'approve')
                ? 'Activity has been approved successfully.'
                : 'Activity has been resent for re-implementation.';
            return redirect()->to('/activities')->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'Failed to process supervision decision.');
        }
    }

    /**
     * View approved activity with implementation details
     *
     * @param int $id Activity ID
     * @return mixed
     */
    public function viewActivity($id = null)
    {
        $userId = session()->get('user_id');

        // Get the activity with all related details
        $activity = $this->activitiesModel->getActivityWithDetails($id);

        if (!$activity) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the user is authorized to view this activity
        $isAdmin = session()->get('is_admin');
        if ($isAdmin != 1 &&
            $activity['action_officer_id'] != $userId &&
            $activity['supervisor_id'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to view this activity.');
        }

        // Get implementation data based on activity type
        $implementationData = null;
        if ($activity['type'] === 'documents') {
            $implementationData = $this->activitiesDocumentsModel
                ->where('activity_id', $activity['id'])
                ->first();

            if ($implementationData) {
                // Decode JSON fields - document_files should already be decoded by model
                if (isset($implementationData['document_files']) && is_string($implementationData['document_files'])) {
                    $implementationData['document_files'] = json_decode($implementationData['document_files'], true);
                }
            }
        } elseif ($activity['type'] === 'trainings') {
            $implementationData = $this->activitiesTrainingModel
                ->where('activity_id', $activity['id'])
                ->first();
            // ActivitiesTrainingModel automatically decodes JSON fields via afterFind callback
        } elseif ($activity['type'] === 'inputs') {
            $implementationData = $this->activitiesInputModel
                ->where('activity_id', $activity['id'])
                ->first();
        } elseif ($activity['type'] === 'infrastructures') {
            $implementationData = $this->activitiesInfrastructureModel
                ->where('activity_id', $activity['id'])
                ->first();

            // ActivitiesInfrastructureModel automatically decodes JSON fields via afterFind callback
            // No manual JSON decoding needed
        } elseif ($activity['type'] === 'outputs') {
            $implementationData = $this->workplanOutputActivityModel
                ->where('activity_id', $activity['id'])
                ->first();
        } elseif ($activity['type'] === 'meetings') {
            $implementationData = $this->activitiesMeetingsModel
                ->where('activity_id', $activity['id'])
                ->first();
        } elseif ($activity['type'] === 'inputs') {
            $implementationData = $this->activitiesInputModel
                ->where('activity_id', $activity['id'])
                ->first();
        }

        $data = [
            'title' => 'View Activity',
            'activity' => $activity,
            'implementationData' => $implementationData
        ];

        // Route to specific view based on activity type
        $viewMap = [
            'documents' => 'activities/views/documents_view',
            'trainings' => 'activities/views/trainings_view',
            'inputs' => 'activities/views/inputs_view',
            'infrastructures' => 'activities/views/infrastructures_view',
            'meetings' => 'activities/views/meetings_view',
            'agreements' => 'activities/views/agreements_view',
            'outputs' => 'activities/views/outputs_view'
        ];

        $view = $viewMap[$activity['type']] ?? 'activities/activities_show';
        return view($view, $data);
    }

    /**
     * Show evaluation view for an approved activity
     *
     * @param int $id Activity ID
     * @return mixed
     */
    public function evaluate($id = null)
    {
        $userId = session()->get('user_id');

        // Get the activity with all related details
        $activity = $this->activitiesModel->getActivityWithDetails($id);

        if (!$activity) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the activity is in approved status
        if ($activity['status'] !== 'approved') {
            return redirect()->to('/activities')->with('error', 'Only approved activities can be evaluated.');
        }

        // Check if the user is authorized to evaluate this activity
        // Only supervisors assigned to this activity or admin can evaluate
        $isAdmin = session()->get('is_admin');
        if ($isAdmin != 1 && $activity['supervisor_id'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to evaluate this activity.');
        }

        // Get implementation data based on activity type
        $implementationData = null;
        if ($activity['type'] === 'documents') {
            $implementationData = $this->activitiesDocumentsModel
                ->where('activity_id', $activity['id'])
                ->first();

            if ($implementationData) {
                // Decode JSON fields - document_files should already be decoded by model
                if (isset($implementationData['document_files']) && is_string($implementationData['document_files'])) {
                    $implementationData['document_files'] = json_decode($implementationData['document_files'], true);
                }
            }
        } elseif ($activity['type'] === 'trainings') {
            $implementationData = $this->activitiesTrainingModel
                ->where('activity_id', $activity['id'])
                ->first();
            // ActivitiesTrainingModel automatically decodes JSON fields via afterFind callback
        } elseif ($activity['type'] === 'inputs') {
            $implementationData = $this->activitiesInputModel
                ->where('activity_id', $activity['id'])
                ->first();
        } elseif ($activity['type'] === 'infrastructures') {
            $implementationData = $this->activitiesInfrastructureModel
                ->where('activity_id', $activity['id'])
                ->first();

            // ActivitiesInfrastructureModel automatically decodes JSON fields via afterFind callback
            // No manual JSON decoding needed
        } elseif ($activity['type'] === 'outputs') {
            $implementationData = $this->workplanOutputActivityModel
                ->where('activity_id', $activity['id'])
                ->first();
        } elseif ($activity['type'] === 'meetings') {
            $implementationData = $this->activitiesMeetingsModel
                ->where('activity_id', $activity['id'])
                ->first();
        }

        $data = [
            'title' => 'Evaluate Activity',
            'activity' => $activity,
            'implementationData' => $implementationData,
            'validation' => \Config\Services::validation()
        ];

        // Route to specific evaluation view based on activity type
        $viewMap = [
            'documents' => 'activities/evaluations/documents_evaluate',
            'trainings' => 'activities/evaluations/trainings_evaluate',
            'inputs' => 'activities/evaluations/inputs_evaluate',
            'infrastructures' => 'activities/evaluations/infrastructures_evaluate',
            'meetings' => 'activities/evaluations/meetings_evaluate',
            'agreements' => 'activities/evaluations/agreements_evaluate',
            'outputs' => 'activities/evaluations/outputs_evaluate'
        ];

        $view = $viewMap[$activity['type']] ?? 'activities/activities_show';
        return view($view, $data);
    }

    /**
     * Process evaluation (rate the activity)
     *
     * @param int $id Activity ID
     * @return mixed
     */
    public function processEvaluation($id = null)
    {
        $userId = session()->get('user_id');

        // Get the activity
        $activity = $this->activitiesModel->find($id);

        if (!$activity) {
            return redirect()->to('/activities')->with('error', 'Activity not found.');
        }

        // Check if the activity is in approved status
        if ($activity['status'] !== 'approved') {
            return redirect()->to('/activities')->with('error', 'Only approved activities can be evaluated.');
        }

        // Check if the user is authorized to evaluate this activity
        $isAdmin = session()->get('is_admin');
        if ($isAdmin != 1 && $activity['supervisor_id'] != $userId) {
            return redirect()->to('/activities')->with('error', 'You are not authorized to evaluate this activity.');
        }

        // Validate input
        $rules = [
            'rating_score' => 'required|decimal|greater_than[0]|less_than_equal_to[10]',
            'rate_remarks' => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $ratingScore = $this->request->getPost('rating_score');
        $remarks = $this->request->getPost('rate_remarks') ?? '';

        // Update the activity with rating information
        $data = [
            'status' => 'rated',
            'rating_score' => $ratingScore,
            'rated_at' => date('Y-m-d H:i:s'),
            'rated_by' => $userId,
            'rate_remarks' => $remarks,
            'updated_by' => $userId
        ];

        $result = $this->activitiesModel->update($id, $data);

        if ($result) {
            return redirect()->to('/activities')->with('success', 'Activity has been rated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to rate activity.');
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

        // Check if the user is authorized to view this activity
        if ($userRole !== 'admin' &&
            $proposal['action_officer_id'] != $userId &&
            $proposal['supervisor_id'] != $userId) {
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

    /**
     * Save document implementation data
     *
     * @param array $activity
     * @return mixed
     */
    private function saveDocumentImplementation($activity)
    {
        $userId = session()->get('user_id');

        // Check if this is an update to existing implementation
        $existingImplementation = $this->activitiesDocumentsModel
            ->where('activity_id', $activity['id'])
            ->first();

        // Validation rules - different for new vs update
        if ($existingImplementation) {
            // For updates, files and captions are optional (only if adding new documents)
            $rules = [
                'remarks' => 'required'
            ];

            // Only validate new files/captions if they are being uploaded
            $uploadedFiles = $this->request->getFiles();
            if (isset($uploadedFiles['document_files']) && !empty($uploadedFiles['document_files'][0]->getName())) {
                $rules['document_files'] = 'uploaded[document_files]';
                $rules['document_captions.*'] = 'required';
            }
        } else {
            // For new implementations, files and captions are required
            $rules = [
                'remarks' => 'required',
                'document_files' => 'uploaded[document_files]',
                'document_captions.*' => 'required'
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $documentsData = [];

        // Handle existing documents updates and removals
        if ($existingImplementation) {
            // document_files is already decoded by the model's afterFind callback
            $existingDocuments = $existingImplementation['document_files'] ?? [];
            $documentsToRemove = json_decode($this->request->getPost('documents_to_remove') ?? '[]', true) ?? [];
            $documentsToUpdate = json_decode($this->request->getPost('documents_to_update') ?? '{}', true) ?? [];

            // Process existing documents (keep, update, or remove)
            foreach ($existingDocuments as $index => $document) {
                if (!in_array($index, $documentsToRemove)) {
                    // Keep this document, but check for caption updates
                    if (isset($documentsToUpdate[$index])) {
                        $document['caption'] = $documentsToUpdate[$index]['caption'];
                    }
                    $documentsData[] = $document;
                }
            }
        }

        // Handle new file uploads with captions
        $uploadedFiles = $this->request->getFiles();
        $captions = $this->request->getPost('document_captions');

        if (isset($uploadedFiles['document_files']) && is_array($captions)) {
            foreach ($uploadedFiles['document_files'] as $index => $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(WRITEPATH . '../public/uploads/documents', $newName);
                    $documentsData[] = [
                        'file_path' => 'public/uploads/documents/' . $newName,
                        'caption' => $captions[$index] ?? '',
                        'original_name' => $file->getClientName()
                    ];
                }
            }
        }

        // Prepare data for ActivitiesDocumentsModel
        $documentData = [
            'activity_id' => $activity['id'],
            'document_files' => $documentsData, // This will be JSON encoded by the model
            'remarks' => $this->request->getPost('remarks')
        ];

        // Save or update to activities_documents table
        if ($existingImplementation) {
            // Update existing implementation
            $documentData['updated_by'] = $userId;
            if (!$this->activitiesDocumentsModel->update($existingImplementation['id'], $documentData)) {
                return redirect()->back()->withInput()->with('error', 'Failed to update document implementation.');
            }
        } else {
            // Create new implementation
            $documentData['created_by'] = $userId;
            if (!$this->activitiesDocumentsModel->insert($documentData)) {
                return redirect()->back()->withInput()->with('error', 'Failed to save document implementation.');
            }
        }

        // Update activity status to 'active'
        $this->activitiesModel->update($activity['id'], [
            'status' => 'active',
            'updated_by' => $userId
        ]);

        return redirect()->to('/activities/' . $activity['id'])->with('success', 'Document implementation saved successfully.');
    }

    /**
     * Save meeting implementation data
     *
     * @param array $activity Activity data
     * @return mixed
     */
    private function saveMeetingImplementation($activity)
    {
        $userId = session()->get('user_id');

        // Validation rules
        $validationRules = [
            'title' => 'required|max_length[255]',
            'agenda' => 'required',
            'meeting_date' => 'required|valid_date',
            'start_time' => 'permit_empty',
            'end_time' => 'permit_empty',
            'location' => 'permit_empty|max_length[255]',
            'gps_coordinates' => 'permit_empty|max_length[255]',
            'remarks' => 'permit_empty',
            'signing_sheet' => 'permit_empty|uploaded[signing_sheet]|max_size[signing_sheet,5120]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Check if there's an existing record
        $existingRecord = $this->activitiesMeetingsModel
            ->where('activity_id', $activity['id'])
            ->first();

        // Process participants (simplified JSON format)
        $participants = [];
        $participantNames = $this->request->getPost('participant_name') ?: [];
        $participantOrganizations = $this->request->getPost('participant_organization') ?: [];

        foreach ($participantNames as $index => $name) {
            if (!empty(trim($name))) {
                $participants[] = [
                    'name' => trim($name),
                    'organization' => trim($participantOrganizations[$index] ?? '')
                ];
            }
        }

        // Process meeting minutes (simplified JSON format)
        $minutes = [];
        $minuteTopics = $this->request->getPost('minute_topic') ?: [];
        $minuteDiscussions = $this->request->getPost('minute_discussion') ?: [];

        foreach ($minuteTopics as $index => $topic) {
            if (!empty(trim($topic))) {
                $minutes[] = [
                    'topic' => trim($topic),
                    'discussion' => trim($minuteDiscussions[$index] ?? '')
                ];
            }
        }

        // Handle signing sheet upload (following training pattern)
        $signingSheetFilepath = null;

        // Keep existing signing sheet if updating
        if ($existingRecord && !empty($existingRecord['signing_sheet_filepath'])) {
            $signingSheetFilepath = $existingRecord['signing_sheet_filepath'];
        }

        // Process new signing sheet file if uploaded
        $signingSheetFile = $this->request->getFile('signing_sheet');
        if ($signingSheetFile && $signingSheetFile->isValid() && !$signingSheetFile->hasMoved()) {
            $newName = $signingSheetFile->getRandomName();
            $signingSheetFile->move(ROOTPATH . 'public/uploads/signing_sheets', $newName);
            $signingSheetFilepath = 'public/uploads/signing_sheets/' . $newName;
        }

        // Handle meeting attachments upload
        $attachments = [];

        // Keep existing attachments if updating
        if ($existingRecord && !empty($existingRecord['attachments'])) {
            $attachments = is_array($existingRecord['attachments']) ? $existingRecord['attachments'] : (json_decode($existingRecord['attachments'], true) ?: []);
        }

        // Process new attachment files if uploaded
        $attachmentFiles = $this->request->getFiles();
        if (isset($attachmentFiles['meeting_attachments'])) {
            $attachmentDescriptions = $this->request->getPost('attachment_descriptions') ?: [];

            foreach ($attachmentFiles['meeting_attachments'] as $index => $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $uploadPath = ROOTPATH . 'public/uploads/meeting_attachments';

                    // Create directory if it doesn't exist
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $file->move($uploadPath, $newName);

                    $attachments[] = [
                        'filename' => $attachmentDescriptions[$index] ?? $file->getClientName(),
                        'original_name' => $file->getClientName(),
                        'path' => 'public/uploads/meeting_attachments/' . $newName
                    ];
                }
            }
        }

        // Process meeting date and times properly
        $meetingDate = $this->request->getPost('meeting_date');
        $startTime = $this->request->getPost('start_time');
        $endTime = $this->request->getPost('end_time');

        // Format start_time and end_time as datetime (combining date with time)
        $formattedStartTime = null;
        $formattedEndTime = null;

        if (!empty($startTime)) {
            $formattedStartTime = date('Y-m-d H:i:s', strtotime("$meetingDate $startTime"));
        }

        if (!empty($endTime)) {
            $formattedEndTime = date('Y-m-d H:i:s', strtotime("$meetingDate $endTime"));
        }

        // Prepare data for saving (following activities_meetings table structure)
        $data = [
            'activity_id' => $activity['id'],
            'title' => $this->request->getPost('title'),
            'agenda' => $this->request->getPost('agenda'),
            'meeting_date' => $meetingDate,
            'start_time' => $formattedStartTime,
            'end_time' => $formattedEndTime,
            'location' => $this->request->getPost('location'),
            'participants' => $participants,
            'minutes' => $minutes,
            'attachments' => $attachments,
            'gps_coordinates' => $this->request->getPost('gps_coordinates'),
            'signing_sheet_filepath' => $signingSheetFilepath,
            'remarks' => $this->request->getPost('remarks'),
            'status' => 'completed',
            'created_by' => $userId,
            'updated_by' => $userId
        ];

        // Check if record already exists (already done above)
        if ($existingRecord) {
            $data['id'] = $existingRecord['id'];
        }

        // Save the data
        if ($this->activitiesMeetingsModel->save($data)) {
            // Update activity status to 'active'
            $this->activitiesModel->update($activity['id'], [
                'status' => 'active',
                'updated_by' => $userId
            ]);

            return redirect()->to('/activities/' . $activity['id'])->with('success', 'Meeting implementation saved successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to save meeting implementation: ' . implode(', ', $this->activitiesMeetingsModel->errors()));
        }
    }

    /**
     * Save agreement implementation data
     *
     * @param array $activity Activity data
     * @return mixed
     */
    private function saveAgreementImplementation($activity)
    {
        $userId = session()->get('user_id');

        // Validation rules
        $validationRules = [
            'title' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'agreement_type' => 'permit_empty|max_length[100]',
            'effective_date' => 'required|valid_date',
            'expiry_date' => 'permit_empty|valid_date',
            'status' => 'permit_empty|in_list[draft,active,expired,terminated,archived]',
            'remarks' => 'permit_empty',
            'agreement_documents' => 'permit_empty|uploaded[agreement_documents]|max_size[agreement_documents,5120]',
            'signing_sheet' => 'permit_empty|uploaded[signing_sheet]|max_size[signing_sheet,5120]'
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Check if there's an existing record
        $existingRecord = $this->activitiesAgreementsModel
            ->where('activity_id', $activity['id'])
            ->first();

        // Process parties (JSON format)
        $parties = [];
        $partyNames = $this->request->getPost('party_name') ?: [];
        $partyOrganizations = $this->request->getPost('party_organization') ?: [];
        $partyRoles = $this->request->getPost('party_role') ?: [];
        $partyContacts = $this->request->getPost('party_contact') ?: [];

        foreach ($partyNames as $index => $name) {
            if (!empty(trim($name))) {
                $parties[] = [
                    'name' => trim($name),
                    'organization' => trim($partyOrganizations[$index] ?? ''),
                    'role' => trim($partyRoles[$index] ?? ''),
                    'contact' => trim($partyContacts[$index] ?? '')
                ];
            }
        }

        // Note: Signing sheet functionality removed as the field doesn't exist in activities_agreements table
        // This can be added later if the database schema is updated

        // Handle agreement documents attachments
        $attachments = [];
        if ($existingRecord && !empty($existingRecord['attachments'])) {
            $attachments = is_array($existingRecord['attachments']) ?
                $existingRecord['attachments'] :
                (json_decode($existingRecord['attachments'], true) ?: []);
        }

        $attachmentFiles = $this->request->getFiles();
        if (isset($attachmentFiles['agreement_documents'])) {
            $attachmentDescriptions = $this->request->getPost('attachment_descriptions') ?: [];

            foreach ($attachmentFiles['agreement_documents'] as $index => $file) {
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $uploadPath = ROOTPATH . 'public/uploads/agreement_attachments';

                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $file->move($uploadPath, $newName);

                    $attachments[] = [
                        'filename' => $attachmentDescriptions[$index] ?? $file->getClientName(),
                        'original_name' => $file->getClientName(),
                        'path' => 'public/uploads/agreement_attachments/' . $newName,
                        'description' => $attachmentDescriptions[$index] ?? ''
                    ];
                }
            }
        }

        // Prepare data for saving (following activities_agreements table structure)
        $data = [
            'activity_id' => $activity['id'],
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'agreement_type' => $this->request->getPost('agreement_type'),
            'parties' => $parties,
            'effective_date' => $this->request->getPost('effective_date'),
            'expiry_date' => $this->request->getPost('expiry_date'),
            'status' => $this->request->getPost('status') ?: 'draft',
            'attachments' => $attachments,
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => $userId,
            'updated_by' => $userId
        ];

        // Check if record already exists
        if ($existingRecord) {
            $data['id'] = $existingRecord['id'];
        }

        // Save the data
        if ($this->activitiesAgreementsModel->save($data)) {
            // Update activity status to 'active'
            $this->activitiesModel->update($activity['id'], [
                'status' => 'active',
                'updated_by' => $userId
            ]);

            return redirect()->to('/activities/' . $activity['id'])->with('success', 'Agreement implementation saved successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to save agreement implementation: ' . implode(', ', $this->activitiesAgreementsModel->errors()));
        }
    }
}
