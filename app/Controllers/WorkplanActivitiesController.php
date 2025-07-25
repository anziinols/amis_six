<?php
// app/Controllers/WorkplanActivitiesController.php

namespace App\Controllers;

use App\Models\WorkplanModel;
use App\Models\WorkplanActivityModel;
use App\Models\BranchesModel;
use App\Models\UserModel;
use App\Models\GovStructureModel;

class WorkplanActivitiesController extends BaseController
{
    protected $workplanModel;
    protected $workplanActivityModel;
    protected $branchModel;
    protected $userModel;
    protected $govStructureModel;
    protected $helpers = ['form', 'url', 'email']; // Load form, URL, and email helpers

    public function __construct()
    {
        $this->workplanModel = new WorkplanModel();
        $this->workplanActivityModel = new WorkplanActivityModel();
        $this->branchModel = new BranchesModel();
        $this->userModel = new UserModel();
        $this->govStructureModel = new GovStructureModel();
    }

    /**
     * Display a list of activities for a specific workplan.
     *
     * @param int|null $workplanId
     * @return mixed
     */
    public function index($workplanId = null)
    {
        $workplan = $this->workplanModel
            ->select('workplans.*, branches.name as branch_name, CONCAT(users.fname, " ", users.lname) as supervisor_name')
            ->join('branches', 'branches.id = workplans.branch_id', 'left')
            ->join('users', 'users.id = workplans.supervisor_id', 'left')
            ->find($workplanId);

        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activities = $this->workplanActivityModel
            ->select('workplan_activities.*, CONCAT(supervisors.fname, " ", supervisors.lname) as supervisor_name')
            ->join('users as supervisors', 'supervisors.id = workplan_activities.supervisor_id', 'left')
            ->where('workplan_id', $workplanId)
            ->findAll();

        // Load plan link models
        $workplanNaspLinkModel = new \App\Models\WorkplanNaspLinkModel();
        $workplanMtdpLinkModel = new \App\Models\WorkplanMtdpLinkModel();
        $workplanCorporatePlanLinkModel = new \App\Models\WorkplanCorporatePlanLinkModel();
        $workplanOthersLinkModel = new \App\Models\WorkplanOthersLinkModel();

        // Add plan link counts to each activity
        foreach ($activities as &$activity) {
            // Count NASP links
            $naspCount = $workplanNaspLinkModel
                ->where('workplan_activity_id', $activity['id'])
                ->where('deleted_at IS NULL')
                ->countAllResults();

            // Count MTDP links
            $mtdpCount = $workplanMtdpLinkModel
                ->where('workplan_activity_id', $activity['id'])
                ->where('deleted_at IS NULL')
                ->countAllResults();

            // Count Corporate Plan links
            $corporateCount = $workplanCorporatePlanLinkModel
                ->where('workplan_activity_id', $activity['id'])
                ->where('deleted_at IS NULL')
                ->countAllResults();

            // Count Others links
            $othersCount = $workplanOthersLinkModel
                ->where('workplan_activity_id', $activity['id'])
                ->where('deleted_at IS NULL')
                ->countAllResults();

            // Add plan link information to activity
            $activity['nasp_linked'] = $naspCount > 0;
            $activity['mtdp_linked'] = $mtdpCount > 0;
            $activity['corporate_linked'] = $corporateCount > 0;
            $activity['others_linked'] = $othersCount > 0;
            $activity['total_plans_linked'] = ($naspCount > 0 ? 1 : 0) + ($mtdpCount > 0 ? 1 : 0) + ($corporateCount > 0 ? 1 : 0) + ($othersCount > 0 ? 1 : 0);
        }

        $data = [
            'title' => 'Workplan Activities',
            'workplan' => $workplan,
            'activities' => $activities
        ];

        return view('workplans/workplan_activities_index', $data);
    }

    /**
     * Show the form for creating a new activity.
     *
     * @param int|null $workplanId
     * @return mixed
     */
    public function new($workplanId = null)
    {
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $data = [
            'title' => 'Create New Activity',
            'workplan' => $workplan,
            'validation' => \Config\Services::validation(),
            'branches' => $this->branchModel->findAll(),
            'supervisors' => $this->userModel->getUsersBySupervisorCapability(),
            'activityTypes' => [
                'training' => 'Training',
                'inputs' => 'Inputs',
                'infrastructure' => 'Infrastructure',
                'output' => 'Output'
            ]
        ];

        return view('workplans/workplan_activities_new', $data);
    }

    /**
     * Create a new activity record in the database.
     *
     * @param int|null $workplanId
     * @return mixed
     */
    public function create($workplanId = null)
    {
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        // Define validation rules - simplified for the required fields only
        $rules = [
            'title' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'activity_type' => 'required|in_list[training,inputs,infrastructure,output]',
            'supervisor_id' => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data for saving - simplified for the required fields only
        $data = [
            'workplan_id' => $workplanId,
            'branch_id' => $workplan['branch_id'],
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'activity_type' => $this->request->getPost('activity_type'),
            'supervisor_id' => $this->request->getPost('supervisor_id'),
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id'),
        ];

        // Use WorkplanActivityModel to insert data
        if ($this->workplanActivityModel->save($data)) {
            // Get the ID of the newly created activity
            $activityId = $this->workplanActivityModel->getInsertID();

            // Send email notification to the supervisor
            $this->sendActivityCreationNotification($workplanId, $activityId, $data);

            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/plans')->with('success', 'Activity created successfully.');
        } else {
            log_message('error', 'Failed to create activity: ' . print_r($this->workplanActivityModel->errors(), true));
            return redirect()->back()->withInput()->with('error', 'Failed to create activity. Please check logs.');
        }
    }

    /**
     * Display the specified activity.
     *
     * @param int|null $workplanId
     * @param int|null $id
     * @return mixed
     */
    public function show($workplanId = null, $id = null)
    {
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activity = $this->workplanActivityModel
            ->select('workplan_activities.*, CONCAT(supervisors.fname, " ", supervisors.lname) as supervisor_name')
            ->join('users as supervisors', 'supervisors.id = workplan_activities.supervisor_id', 'left')
            ->where('workplan_activities.id', $id)
            ->first();

        if (!$activity) {
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Activity not found.');
        }

        // Process JSON fields
        if (!empty($activity['image_paths'])) {
            $activity['image_paths'] = json_decode($activity['image_paths'], true);
        } else {
            $activity['image_paths'] = [];
        }

        if (!empty($activity['trainees'])) {
            $activity['trainees'] = json_decode($activity['trainees'], true);
        } else {
            $activity['trainees'] = [];
        }

        // Load plan link models
        $workplanNaspLinkModel = new \App\Models\WorkplanNaspLinkModel();
        $workplanMtdpLinkModel = new \App\Models\WorkplanMtdpLinkModel();
        $workplanCorporatePlanLinkModel = new \App\Models\WorkplanCorporatePlanLinkModel();

        // Get NASP links for this activity
        try {
            $naspLinks = $workplanNaspLinkModel->where('workplan_activity_id', $id)
                ->where('deleted_at IS NULL')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching NASP links: ' . $e->getMessage());
            $naspLinks = [];
        }

        // Get MTDP links for this activity
        try {
            $mtdpLinks = $workplanMtdpLinkModel->where('workplan_activity_id', $id)
                ->where('deleted_at IS NULL')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching MTDP links: ' . $e->getMessage());
            $mtdpLinks = [];
        }

        // Get Corporate Plan links for this activity
        try {
            $corporateLinks = $workplanCorporatePlanLinkModel->where('workplan_activity_id', $id)
                ->where('deleted_at IS NULL')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching Corporate Plan links: ' . $e->getMessage());
            $corporateLinks = [];
        }

        // Load additional models to get details for the links
        $naspModel = new \App\Models\NaspModel();
        $mtdpModel = new \App\Models\MtdpModel();
        $corporatePlanModel = new \App\Models\CorporatePlanModel();

        // Enhance NASP links with additional information
        foreach ($naspLinks as &$link) {
            // Get NASP plan details
            $plan = $naspModel->find($link['nasp_id']);
            $link['nasp_code'] = $plan['code'] ?? 'N/A';

            // Get output details
            if (!empty($link['output_id'])) {
                try {
                    // Try to get the output from the NASP model
                    $output = $naspModel->find($link['output_id']);
                    if ($output) {
                        $link['output_code'] = $output['code'] ?? 'N/A';
                        $link['output_title'] = $output['title'] ?? 'N/A';
                    } else {
                        // If not found, try to get from the outputs table
                        $output = $naspModel->db->table('plans_nasp_output')->where('id', $link['output_id'])->get()->getRowArray();
                        if ($output) {
                            $link['output_code'] = $output['code'] ?? 'N/A';
                            $link['output_title'] = $output['title'] ?? 'N/A';
                        } else {
                            $link['output_code'] = 'Output #' . $link['output_id'];
                            $link['output_title'] = 'Output #' . $link['output_id'];
                        }
                    }
                } catch (\Exception $e) {
                    $link['output_code'] = 'Output #' . $link['output_id'];
                    $link['output_title'] = 'Output #' . $link['output_id'];
                    log_message('error', 'Error fetching NASP output: ' . $e->getMessage());
                }
            } else {
                $link['output_code'] = 'N/A';
                $link['output_title'] = 'N/A';
            }
        }

        // Enhance MTDP links with additional information
        foreach ($mtdpLinks as &$link) {
            // Get MTDP plan details
            $plan = $mtdpModel->find($link['mtdp_id']);
            $link['mtdp_code'] = $plan['abbrev'] ?? 'N/A';

            // Load the SPA model to get SPA details
            $mtdpSpaModel = new \App\Models\MtdpSpaModel();

            // Get SPA details
            if (!empty($link['spa_id'])) {
                $spa = $mtdpSpaModel->find($link['spa_id']);
                $link['spa_code'] = $spa['code'] ?? 'N/A';
            } else {
                $link['spa_code'] = 'N/A';
            }

            // Load the DIP model to get DIP details
            $mtdpDipModel = new \App\Models\MtdpDipModel();

            // Get DIP details
            if (!empty($link['dip_id'])) {
                $dip = $mtdpDipModel->find($link['dip_id']);
                $link['dip_code'] = $dip['dip_code'] ?? 'N/A';
            } else {
                $link['dip_code'] = 'N/A';
            }

            // For strategies, use the MtdpStrategiesModel to get the correct data
            if (!empty($link['strategies_id'])) {
                try {
                    // Load the strategies model
                    $mtdpStrategiesModel = new \App\Models\MtdpStrategiesModel();

                    // Get the strategy from the correct table
                    $strategy = $mtdpStrategiesModel->find($link['strategies_id']);

                    if ($strategy) {
                        // Store both the short and full strategy text
                        $link['strategy'] = $strategy['strategy'] ?? 'N/A';
                        $link['strategy_full'] = $strategy['strategy'] ?? 'N/A';
                    } else {
                        // Try with direct database query as fallback
                        $strategy = $mtdpModel->db->table('plans_mtdp_strategies')->where('id', $link['strategies_id'])->get()->getRowArray();
                        if ($strategy) {
                            $link['strategy'] = $strategy['strategy'] ?? 'N/A';
                            $link['strategy_full'] = $strategy['strategy'] ?? 'N/A';
                        } else {
                            $link['strategy'] = 'Strategy #' . $link['strategies_id'];
                            $link['strategy_full'] = 'Strategy #' . $link['strategies_id'];
                            log_message('error', 'MTDP strategy not found with ID: ' . $link['strategies_id']);
                        }
                    }
                } catch (\Exception $e) {
                    // If there's an error, log it and use a fallback
                    $link['strategy'] = 'Strategy #' . $link['strategies_id'];
                    $link['strategy_full'] = 'Strategy #' . $link['strategies_id'];
                    log_message('error', 'Error fetching MTDP strategy: ' . $e->getMessage());
                }
            } else {
                $link['strategy'] = 'N/A';
                $link['strategy_full'] = 'N/A';
            }
        }

        // Enhance Corporate Plan links with additional information
        foreach ($corporateLinks as &$link) {
            // Get Corporate Plan details
            $plan = $corporatePlanModel->find($link['corporate_plan_id']);
            $link['plan_code'] = $plan['code'] ?? 'N/A';

            // Get objective details - Corporate Plan uses a single table with 'type' field
            if (!empty($link['objective_id'])) {
                try {
                    // Get the objective directly from the CorporatePlanModel
                    $objective = $corporatePlanModel->find($link['objective_id']);

                    if ($objective) {
                        $link['objective_code'] = $objective['code'] ?? 'N/A';
                        $link['objective_title'] = $objective['title'] ?? 'N/A';
                        $link['objective_full'] = $objective['remarks'] ?? $objective['title'] ?? 'N/A';
                    } else {
                        // Fallback to direct query
                        $objective = $corporatePlanModel->db->table('plans_corporate_plan')
                            ->where('id', $link['objective_id'])
                            ->where('type', 'objective')
                            ->get()->getRowArray();

                        if ($objective) {
                            $link['objective_code'] = $objective['code'] ?? 'N/A';
                            $link['objective_title'] = $objective['title'] ?? 'N/A';
                            $link['objective_full'] = $objective['remarks'] ?? $objective['title'] ?? 'N/A';
                        } else {
                            $link['objective_code'] = 'Objective #' . $link['objective_id'];
                            $link['objective_title'] = 'Objective #' . $link['objective_id'];
                            $link['objective_full'] = 'Objective #' . $link['objective_id'];
                            log_message('error', 'Corporate Plan objective not found with ID: ' . $link['objective_id']);
                        }
                    }
                } catch (\Exception $e) {
                    $link['objective_code'] = 'Objective #' . $link['objective_id'];
                    $link['objective_title'] = 'Objective #' . $link['objective_id'];
                    $link['objective_full'] = 'Objective #' . $link['objective_id'];
                    log_message('error', 'Error fetching Corporate Plan objective: ' . $e->getMessage());
                }
            } else {
                $link['objective_code'] = 'N/A';
                $link['objective_title'] = 'N/A';
                $link['objective_full'] = 'N/A';
            }

            // Get strategy details - Corporate Plan uses a single table with 'type' field
            if (!empty($link['strategies_id'])) {
                try {
                    // Get the strategy directly from the CorporatePlanModel
                    $strategy = $corporatePlanModel->find($link['strategies_id']);

                    if ($strategy) {
                        $link['strategy_title'] = $strategy['title'] ?? 'N/A';
                        $link['strategy_full'] = $strategy['remarks'] ?? $strategy['title'] ?? 'N/A';
                    } else {
                        // Fallback to direct query
                        $strategy = $corporatePlanModel->db->table('plans_corporate_plan')
                            ->where('id', $link['strategies_id'])
                            ->where('type', 'strategy')
                            ->get()->getRowArray();

                        if ($strategy) {
                            $link['strategy_title'] = $strategy['title'] ?? 'N/A';
                            $link['strategy_full'] = $strategy['remarks'] ?? $strategy['title'] ?? 'N/A';
                        } else {
                            $link['strategy_title'] = 'Strategy #' . $link['strategies_id'];
                            $link['strategy_full'] = 'Strategy #' . $link['strategies_id'];
                            log_message('error', 'Corporate Plan strategy not found with ID: ' . $link['strategies_id']);
                        }
                    }
                } catch (\Exception $e) {
                    $link['strategy_title'] = 'Strategy #' . $link['strategies_id'];
                    $link['strategy_full'] = 'Strategy #' . $link['strategies_id'];
                    log_message('error', 'Error fetching Corporate Plan strategy: ' . $e->getMessage());
                }
            } else {
                $link['strategy_title'] = 'N/A';
                $link['strategy_full'] = 'N/A';
            }
        }

        $data = [
            'title' => 'Activity Details',
            'workplan' => $workplan,
            'activity' => $activity,
            'naspLinks' => $naspLinks,
            'mtdpLinks' => $mtdpLinks,
            'corporateLinks' => $corporateLinks
        ];

        return view('workplans/workplan_activities_show', $data);
    }

    /**
     * Show the form for editing the specified activity.
     *
     * @param int|null $workplanId
     * @param int|null $id
     * @return mixed
     */
    public function edit($workplanId = null, $id = null)
    {
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activity = $this->workplanActivityModel->find($id);
        if (!$activity) {
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Activity not found.');
        }

        // Process JSON fields
        if (!empty($activity['image_paths'])) {
            $activity['image_paths'] = json_decode($activity['image_paths'], true);
        } else {
            $activity['image_paths'] = [];
        }

        if (!empty($activity['trainees'])) {
            $activity['trainees'] = json_decode($activity['trainees'], true);
        } else {
            $activity['trainees'] = [];
        }

        $data = [
            'title' => 'Edit Activity',
            'workplan' => $workplan,
            'activity' => $activity,
            'validation' => \Config\Services::validation(),
            'branches' => $this->branchModel->findAll(),
            'supervisors' => $this->userModel->getUsersBySupervisorCapability(),
            'activityTypes' => [
                'training' => 'Training',
                'inputs' => 'Inputs',
                'infrastructure' => 'Infrastructure',
                'output' => 'Output'
            ]
        ];

        return view('workplans/workplan_activities_edit', $data);
    }

    /**
     * Update the specified activity in the database.
     *
     * @param int|null $workplanId
     * @param int|null $id
     * @return mixed
     */
    public function update($workplanId = null, $id = null)
    {
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activity = $this->workplanActivityModel->find($id);
        if (!$activity) {
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Activity not found.');
        }

        // Define validation rules - simplified for the required fields only
        $rules = [
            'title' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'activity_type' => 'required|in_list[training,inputs,infrastructure,output]',
            'supervisor_id' => 'permit_empty|integer',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data for updating - simplified for the required fields only
        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'activity_type' => $this->request->getPost('activity_type'),
            'supervisor_id' => $this->request->getPost('supervisor_id'),
            'updated_by' => session()->get('user_id'),
        ];

        // Use WorkplanActivityModel to update data
        if ($this->workplanActivityModel->update($id, $data)) {
            // Send email notification to the supervisor
            $this->sendActivityUpdateNotification($workplanId, $id, $data);

            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('success', 'Activity updated successfully.');
        } else {
            log_message('error', 'Failed to update activity: ' . print_r($this->workplanActivityModel->errors(), true));
            return redirect()->back()->withInput()->with('error', 'Failed to update activity. Please check logs.');
        }
    }

    /**
     * Delete the specified activity from the database (soft delete).
     *
     * @param int|null $workplanId
     * @param int|null $id
     * @return mixed
     */
    public function delete($workplanId = null, $id = null)
    {
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activity = $this->workplanActivityModel->find($id);
        if (!$activity) {
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Activity not found.');
        }

        // Perform soft delete
        if ($this->workplanActivityModel->delete($id)) {
            $userId = session()->get('user_id');

            // Set 'deleted_by' field
            $this->workplanActivityModel->update($id, ['deleted_by' => $userId]);

            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('success', 'Activity deleted successfully.');
        } else {
            log_message('error', 'Failed to delete activity: ' . print_r($this->workplanActivityModel->errors(), true));
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Failed to delete activity.');
        }
    }

    /**
     * Get districts for a specific province (AJAX endpoint).
     *
     * @param int|null $provinceId
     * @return \CodeIgniter\HTTP\Response
     */
    public function getDistricts($provinceId = null)
    {
        if (!$provinceId) {
            return $this->response->setJSON(['error' => 'Province ID is required']);
        }

        $districts = $this->govStructureModel
            ->where('level', 'district')
            ->where('parent_id', $provinceId)
            ->orderBy('name', 'ASC')
            ->findAll();

        return $this->response->setJSON(['districts' => $districts]);
    }

    /**
     * Send email notification to supervisor when a new activity is created
     *
     * @param int $workplanId ID of the workplan
     * @param int $activityId ID of the newly created activity
     * @param array $activityData Activity data
     * @return bool Success or failure
     */
    protected function sendActivityCreationNotification($workplanId, $activityId, $activityData)
    {
        try {
            // Get the complete activity data
            $activity = $this->workplanActivityModel->find($activityId);
            if (!$activity) {
                log_message('error', 'Cannot send activity creation notification: Activity not found');
                return false;
            }

            // Get the workplan data
            $workplan = $this->workplanModel->find($workplanId);
            if (!$workplan) {
                log_message('error', 'Cannot send activity creation notification: Workplan not found');
                return false;
            }

            // Get the supervisor's information
            $supervisorId = $activity['supervisor_id'];
            $supervisor = $this->userModel->find($supervisorId);
            if (!$supervisor || empty($supervisor['email'])) {
                log_message('error', 'Cannot send activity creation notification: Supervisor not found or no email available');
                return false;
            }

            // Get the creator's information
            $creatorName = 'System User';
            $creatorId = session()->get('user_id');
            if ($creatorId) {
                $creator = $this->userModel->find($creatorId);
                if ($creator) {
                    $creatorName = $creator['fname'] . ' ' . $creator['lname'];
                }
            }

            // Get creator's email
            $creatorEmail = session()->get('email') ?? 'noreply@dakoiims.com';

            // Get branch information
            $branchName = 'Unknown Branch';
            if (!empty($activity['branch_id'])) {
                $branch = $this->branchModel->find($activity['branch_id']);
                if ($branch) {
                    $branchName = $branch['name'];
                }
            }

            // Prepare email subject and message
            $subject = 'New Workplan Activity Created: ' . $activity['title'];

            // Set header color
            $headerColor = '#4CAF50'; // Green for creation

            // Create HTML email message
            $message = '<!DOCTYPE html>
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
                    .highlight { background-color: #f5f5f5; padding: 15px; border-left: 4px solid #4CAF50; margin: 20px 0; }
                    .status { font-weight: bold; color: #4CAF50; }
                    h1 { margin-top: 0; font-size: 24px; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1>' . $subject . '</h1>
                    </div>
                    <div class="content">
                        <p>Dear ' . $supervisor['fname'] . ' ' . $supervisor['lname'] . ',</p>

                        <p>A new workplan activity has been created and you have been assigned as the supervisor.</p>

                        <div class="highlight">
                            <p><strong>Activity Details:</strong></p>
                            <p>Title: ' . $activity['title'] . '</p>
                            <p>Workplan: ' . $workplan['title'] . '</p>
                            <p>Branch: ' . $branchName . '</p>
                            <p>Activity Type: ' . ucfirst($activity['activity_type']) . '</p>
                        </div>

                        <p>This activity was created by: <strong>' . $creatorName . '</strong> (' . $creatorEmail . ')</p>

                        <p>You can view the complete activity details by logging into the system.</p>

                        <p>Thank you,<br>
                        AMIS System</p>
                    </div>
                    <div class="footer">
                        <p>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </body>
            </html>';

            // Send the email
            $result = send_email($supervisor['email'], $subject, $message);

            if (!$result) {
                log_message('error', 'Failed to send activity creation notification email to: ' . $supervisor['email']);
            } else {
                log_message('info', 'Activity creation notification email sent successfully to: ' . $supervisor['email']);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception sending activity creation notification email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email notification to supervisor when an activity is updated
     *
     * @param int $workplanId ID of the workplan
     * @param int $activityId ID of the updated activity
     * @param array $activityData Updated activity data
     * @return bool Success or failure
     */
    protected function sendActivityUpdateNotification($workplanId, $activityId, $activityData)
    {
        try {
            // Get the complete activity data
            $activity = $this->workplanActivityModel->find($activityId);
            if (!$activity) {
                log_message('error', 'Cannot send activity update notification: Activity not found');
                return false;
            }

            // Get the workplan data
            $workplan = $this->workplanModel->find($workplanId);
            if (!$workplan) {
                log_message('error', 'Cannot send activity update notification: Workplan not found');
                return false;
            }

            // Get the supervisor's information
            $supervisorId = $activity['supervisor_id'];
            $supervisor = $this->userModel->find($supervisorId);
            if (!$supervisor || empty($supervisor['email'])) {
                log_message('error', 'Cannot send activity update notification: Supervisor not found or no email available');
                return false;
            }

            // Get the updater's information
            $updaterName = 'System User';
            $updaterId = session()->get('user_id');
            if ($updaterId) {
                $updater = $this->userModel->find($updaterId);
                if ($updater) {
                    $updaterName = $updater['fname'] . ' ' . $updater['lname'];
                }
            }

            // Get updater's email
            $updaterEmail = session()->get('email') ?? 'noreply@dakoiims.com';

            // Get branch information
            $branchName = 'Unknown Branch';
            if (!empty($activity['branch_id'])) {
                $branch = $this->branchModel->find($activity['branch_id']);
                if ($branch) {
                    $branchName = $branch['name'];
                }
            }

            // Prepare email subject and message
            $subject = 'Workplan Activity Updated: ' . $activity['title'];

            // Set header color
            $headerColor = '#2196F3'; // Blue for updates

            // Create HTML email message
            $message = '<!DOCTYPE html>
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
                    h1 { margin-top: 0; font-size: 24px; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1>' . $subject . '</h1>
                    </div>
                    <div class="content">
                        <p>Dear ' . $supervisor['fname'] . ' ' . $supervisor['lname'] . ',</p>

                        <p>A workplan activity for which you are the supervisor has been updated.</p>

                        <div class="highlight">
                            <p><strong>Activity Details:</strong></p>
                            <p>Title: ' . $activity['title'] . '</p>
                            <p>Workplan: ' . $workplan['title'] . '</p>
                            <p>Branch: ' . $branchName . '</p>
                            <p>Activity Type: ' . ucfirst($activity['activity_type']) . '</p>
                        </div>

                        <p>This activity was updated by: <strong>' . $updaterName . '</strong> (' . $updaterEmail . ')</p>

                        <p>You can view the complete activity details by logging into the system.</p>

                        <p>Thank you,<br>
                        AMIS System</p>
                    </div>
                    <div class="footer">
                        <p>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </body>
            </html>';

            // Send the email
            $result = send_email($supervisor['email'], $subject, $message);

            if (!$result) {
                log_message('error', 'Failed to send activity update notification email to: ' . $supervisor['email']);
            } else {
                log_message('info', 'Activity update notification email sent successfully to: ' . $supervisor['email']);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception sending activity update notification email: ' . $e->getMessage());
            return false;
        }
    }
}
