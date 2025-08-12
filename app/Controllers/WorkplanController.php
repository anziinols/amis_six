<?php
// app/Controllers/WorkplanController.php

namespace App\Controllers;

use App\Models\WorkplanModel;
use App\Models\BranchesModel;
use App\Models\UserModel;

class WorkplanController extends BaseController
{
    protected $workplanModel;
    protected $branchModel;
    protected $userModel;
    protected $helpers = ['form', 'url']; // Load form and URL helpers

    public function __construct()
    {
        $this->workplanModel = new WorkplanModel();
        $this->branchModel = new BranchesModel();
        $this->userModel = new UserModel();
        helper(['email']);
    }

    /**
     * Display a list of workplans.
     *
     * @return mixed
     */
    public function index()
    {
        $workplans = $this->workplanModel
            ->select('workplans.*, branches.name as branch_name, CONCAT(users.fname, " ", users.lname) as supervisor_name')
            ->join('branches', 'branches.id = workplans.branch_id', 'left')
            ->join('users', 'users.id = workplans.supervisor_id', 'left')
            ->findAll(); // Fetch all non-deleted workplans

        // Add activity count to each workplan
        foreach ($workplans as &$workplan) {
            $workplan['activity_count'] = $this->workplanModel->countActivities($workplan['id']);
        }

        $data = [
            'title' => 'Workplans List',
            'workplans' => $workplans
        ];

        return view('workplans/workplan_index', $data);
    }

    /**
     * Show the form for creating a new workplan.
     *
     * @return mixed
     */
    public function new()
    {
        $data = [
            'title' => 'Create New Workplan',
            'validation' => \Config\Services::validation(), // Pass validation object
            'branches' => $this->branchModel->findAll(), // Fetch all branches
            'supervisors' => $this->userModel->getUsersBySupervisorCapability(), // Fetch all supervisors
        ];

        return view('workplans/workplan_new', $data);
    }

    /**
     * Create a new workplan record in the database.
     *
     * @return mixed
     */
    public function create()
    {
        // Define validation rules
        $rules = [
            'branch_id' => 'required|integer',
            'title' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'supervisor_id' => 'required|integer',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date',
            'status' => 'required|in_list[draft,in_progress,completed,on_hold]',
            'objectives' => 'permit_empty',
            'remarks' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            // Pass validation errors back to the form
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get objectives as plain text
        $objectives = $this->request->getPost('objectives');

        // Prepare data for saving
        $data = [
            'branch_id' => $this->request->getPost('branch_id'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'supervisor_id' => $this->request->getPost('supervisor_id'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'status' => $this->request->getPost('status'),
            'objectives' => $objectives, // Handled as plain text
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id'),
        ];

        // Use WorkplanModel to insert data (respects $allowedFields)
        if ($this->workplanModel->save($data)) {
            // Get the ID of the newly created workplan
            $workplanId = $this->workplanModel->getInsertID();

            // Send email notification to the supervisor
            $this->sendWorkplanCreationNotification($workplanId, $data);

            return redirect()->to('/workplans')->with('success', 'Workplan created successfully.');
        } else {
            log_message('error', 'Failed to create workplan: ' . print_r($this->workplanModel->errors(), true));
            return redirect()->back()->withInput()->with('error', 'Failed to create workplan. Please check logs.');
        }
    }

    /**
     * Display the specified workplan.
     *
     * @param int|null $id
     * @return mixed
     */
    public function show($id = null)
    {
        $workplan = $this->workplanModel
            ->select('workplans.*, branches.name as branch_name, CONCAT(users.fname, " ", users.lname) as supervisor_name')
            ->join('branches', 'branches.id = workplans.branch_id', 'left')
            ->join('users', 'users.id = workplans.supervisor_id', 'left')
            ->find($id);

        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        // No need to process objectives as it's handled as plain text

        $data = [
            'title' => 'Workplan Details',
            'workplan' => $workplan
        ];

        return view('workplans/workplan_show', $data);
    }

    /**
     * Show the form for editing the specified workplan.
     *
     * @param int|null $id
     * @return mixed
     */
    public function edit($id = null)
    {
        $workplan = $this->workplanModel->find($id);
        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        // No need to process objectives as it's handled as plain text

        $data = [
            'title' => 'Edit Workplan',
            'workplan' => $workplan,
            'validation' => \Config\Services::validation(),
            'branches' => $this->branchModel->findAll(),
            'supervisors' => $this->userModel->getUsersBySupervisorCapability(),
        ];

        return view('workplans/workplan_edit', $data);
    }

    /**
     * Update the specified workplan in the database.
     *
     * @param int|null $id
     * @return mixed
     */
    public function update($id = null)
    {
        $workplan = $this->workplanModel->find($id);
        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        // Define validation rules
        $rules = [
            'branch_id' => 'required|integer',
            'title' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'supervisor_id' => 'required|integer',
            'start_date' => 'required|valid_date',
            'end_date' => 'required|valid_date',
            'status' => 'required|in_list[draft,in_progress,completed,on_hold]',
            'objectives' => 'permit_empty',
            'remarks' => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            // Pass validation errors back to the form
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get objectives as plain text
        $objectives = $this->request->getPost('objectives');

        // Prepare data for updating
        $data = [
            'branch_id' => $this->request->getPost('branch_id'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'supervisor_id' => $this->request->getPost('supervisor_id'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'status' => $this->request->getPost('status'),
            'objectives' => $objectives, // Handled as plain text
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id'),
        ];

        // Use WorkplanModel to update data
        if ($this->workplanModel->update($id, $data)) {
            // Send email notification to the supervisor
            $this->sendWorkplanUpdateNotification($id, $data);

            return redirect()->to('/workplans')->with('success', 'Workplan updated successfully.');
        } else {
            log_message('error', 'Failed to update workplan: ' . print_r($this->workplanModel->errors(), true));
            return redirect()->back()->withInput()->with('error', 'Failed to update workplan. Please check logs.');
        }
    }

    /**
     * Send email notification to supervisor when a workplan is created
     *
     * @param int $workplanId ID of the newly created workplan
     * @param array $workplanData Workplan data
     * @return bool Success or failure
     */
    protected function sendWorkplanCreationNotification($workplanId, $workplanData)
    {
        try {
            // Get the complete workplan data
            $workplan = $this->workplanModel->find($workplanId);
            if (!$workplan) {
                log_message('error', 'Cannot send workplan creation notification: Workplan not found');
                return false;
            }

            // Get the supervisor's information
            $supervisorId = $workplan['supervisor_id'];
            $supervisor = $this->userModel->find($supervisorId);
            if (!$supervisor || empty($supervisor['email'])) {
                log_message('error', 'Cannot send workplan creation notification: Supervisor not found or no email available');
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
            if (!empty($workplan['branch_id'])) {
                $branch = $this->branchModel->find($workplan['branch_id']);
                if ($branch) {
                    $branchName = $branch['name'];
                }
            }

            // Format dates for display
            $startDate = date('d M Y', strtotime($workplan['start_date']));
            $endDate = date('d M Y', strtotime($workplan['end_date']));

            // Prepare email subject and message
            $subject = 'New Workplan Created: ' . $workplan['title'];

            // Create HTML message
            $message = '
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #4CAF50; color: white; padding: 10px; text-align: center; }
                    .content { padding: 20px; border: 1px solid #ddd; }
                    .footer { font-size: 12px; text-align: center; margin-top: 20px; color: #777; }
                    .highlight { background-color: #f8f9fa; padding: 10px; border-left: 4px solid #4CAF50; margin: 15px 0; }
                    .status { font-weight: bold; color: #4CAF50; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>New Workplan Created</h2>
                    </div>
                    <div class="content">
                        <p>Dear ' . $supervisor['fname'] . ' ' . $supervisor['lname'] . ',</p>

                        <p>A new workplan has been created and you have been assigned as the supervisor.</p>

                        <div class="highlight">
                            <p><strong>Workplan Details:</strong></p>
                            <p>Title: ' . $workplan['title'] . '</p>
                            <p>Branch: ' . $branchName . '</p>
                            <p>Status: <span class="status">' . ucfirst($workplan['status']) . '</span></p>
                            <p>Period: ' . $startDate . ' to ' . $endDate . '</p>
                        </div>

                        <p>This workplan was created by: <strong>' . $creatorName . '</strong> (' . $creatorEmail . ')</p>

                        <p>You can view the complete workplan details by logging into the system.</p>

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
                log_message('error', 'Failed to send workplan creation notification email to: ' . $supervisor['email']);
            } else {
                log_message('info', 'Workplan creation notification email sent successfully to: ' . $supervisor['email']);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception sending workplan creation notification email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email notification to supervisor when a workplan is updated
     *
     * @param int $workplanId ID of the updated workplan
     * @param array $workplanData Updated workplan data
     * @return bool Success or failure
     */
    protected function sendWorkplanUpdateNotification($workplanId, $workplanData)
    {
        try {
            // Get the complete workplan data
            $workplan = $this->workplanModel->find($workplanId);
            if (!$workplan) {
                log_message('error', 'Cannot send workplan update notification: Workplan not found');
                return false;
            }

            // Get the supervisor's information
            $supervisorId = $workplan['supervisor_id'];
            $supervisor = $this->userModel->find($supervisorId);
            if (!$supervisor || empty($supervisor['email'])) {
                log_message('error', 'Cannot send workplan update notification: Supervisor not found or no email available');
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
            if (!empty($workplan['branch_id'])) {
                $branch = $this->branchModel->find($workplan['branch_id']);
                if ($branch) {
                    $branchName = $branch['name'];
                }
            }

            // Format dates for display
            $startDate = date('d M Y', strtotime($workplan['start_date']));
            $endDate = date('d M Y', strtotime($workplan['end_date']));

            // Prepare email subject and message
            $subject = 'Workplan Updated: ' . $workplan['title'];

            // Create HTML message
            $message = '
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #2196F3; color: white; padding: 10px; text-align: center; }
                    .content { padding: 20px; border: 1px solid #ddd; }
                    .footer { font-size: 12px; text-align: center; margin-top: 20px; color: #777; }
                    .highlight { background-color: #f8f9fa; padding: 10px; border-left: 4px solid #2196F3; margin: 15px 0; }
                    .status { font-weight: bold; color: #2196F3; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>Workplan Updated</h2>
                    </div>
                    <div class="content">
                        <p>Dear ' . $supervisor['fname'] . ' ' . $supervisor['lname'] . ',</p>

                        <p>A workplan for which you are the supervisor has been updated.</p>

                        <div class="highlight">
                            <p><strong>Workplan Details:</strong></p>
                            <p>Title: ' . $workplan['title'] . '</p>
                            <p>Branch: ' . $branchName . '</p>
                            <p>Status: <span class="status">' . ucfirst($workplan['status']) . '</span></p>
                            <p>Period: ' . $startDate . ' to ' . $endDate . '</p>
                        </div>

                        <p>This workplan was updated by: <strong>' . $updaterName . '</strong> (' . $updaterEmail . ')</p>

                        <p>You can view the complete workplan details by logging into the system.</p>

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
                log_message('error', 'Failed to send workplan update notification email to: ' . $supervisor['email']);
            } else {
                log_message('info', 'Workplan update notification email sent successfully to: ' . $supervisor['email']);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception sending workplan update notification email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete the specified workplan from the database (soft delete).
     *
     * @param int|null $id
     * @return mixed
     */
    public function delete($id = null)
    {
        $workplan = $this->workplanModel->find($id);
        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        // Check if workplan has activities
        if ($this->workplanModel->hasActivities($id)) {
            return redirect()->to('/workplans')->with('error', 'Cannot delete workplan that has activities. Please delete all activities first.');
        }

        // Perform soft delete
        if ($this->workplanModel->delete($id)) {
            $userId = session()->get('user_id');

            // Optionally set 'deleted_by' if using soft deletes and model supports it
            $this->workplanModel->update($id, ['deleted_by' => $userId]);

            return redirect()->to('/workplans')->with('success', 'Workplan deleted successfully.');
        } else {
            log_message('error', 'Failed to delete workplan: ' . print_r($this->workplanModel->errors(), true));
            return redirect()->to('/workplans')->with('error', 'Failed to delete workplan.');
        }
    }

    /**
     * Display the NASP plan linking interface for a workplan activity.
     *
     * @param int|null $workplanId
     * @param int|null $activityId
     * @return mixed
     */
    public function activityPlans($workplanId = null, $activityId = null)
    {
        // Load required models
        $workplanActivityModel = new \App\Models\WorkplanActivityModel();
        $workplanNaspLinkModel = new \App\Models\WorkplanNaspLinkModel();
        $workplanCorporatePlanLinkModel = new \App\Models\WorkplanCorporatePlanLinkModel();
        $workplanMtdpLinkModel = new \App\Models\WorkplanMtdpLinkModel();
        $workplanOthersLinkModel = new \App\Models\WorkplanOthersLinkModel();
        $naspModel = new \App\Models\NaspModel();
        $corporatePlanModel = new \App\Models\CorporatePlanModel();
        $mtdpModel = new \App\Models\MtdpModel();
        $branchModel = new \App\Models\BranchesModel();
        $userModel = new \App\Models\UserModel();

        // Get workplan and activity
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activity = $workplanActivityModel->find($activityId);
        $branch = $activity ? $branchModel->find($activity['branch_id']) : null;
        $supervisor = $activity && !empty($activity['supervisor_id']) ? $userModel->find($activity['supervisor_id']) : null;
        $actionOfficer = $activity && !empty($activity['action_officer_id']) ? $userModel->find($activity['action_officer_id']) : null;
        if (!$activity) {
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Activity not found.');
        }

        // Get existing NASP links - ensure we only get links for this specific activity
        $naspLinks = $workplanNaspLinkModel->where('workplan_activity_id', $activityId)->findAll();

        // Get existing Corporate Plan links - ensure we only get links for this specific activity
        $corporateLinks = $workplanCorporatePlanLinkModel->where('workplan_activity_id', $activityId)->findAll();

        // Get existing MTDP links - ensure we only get links for this specific activity
        $mtdpLinks = $workplanMtdpLinkModel->where('workplan_activity_id', $activityId)->findAll();

        // Get all NASP outputs with their hierarchy information
        $naspOutputs = [];
        try {
            $response = \CodeIgniter\Config\Services::curlrequest()->get(base_url('api/nasp/all-outputs'));
            $result = json_decode($response->getBody(), true);
            if (isset($result['success']) && $result['success'] && isset($result['data'])) {
                $naspOutputs = $result['data'];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error fetching NASP outputs: ' . $e->getMessage());
        }

        // We still need the NASP plans for backward compatibility
        $naspPlans = $naspModel->where('type', 'plans')
                              ->where('nasp_status', 1)
                              ->where('deleted_at IS NULL')
                              ->findAll();

        // Get all Corporate plan strategies with their hierarchy information
        $corporateStrategies = [];
        try {
            $response = \CodeIgniter\Config\Services::curlrequest()->get(base_url('api/corporate/all-strategies'));
            $result = json_decode($response->getBody(), true);
            if (isset($result['success']) && $result['success'] && isset($result['data'])) {
                $corporateStrategies = $result['data'];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error fetching Corporate plan strategies: ' . $e->getMessage());
        }

        // We still need the Corporate plans for backward compatibility
        $corporatePlans = $corporatePlanModel->where('type', 'plans')
                                           ->where('corp_plan_status', 1)
                                           ->where('deleted_at IS NULL')
                                           ->findAll();

        // Get all MTDP strategies with their hierarchy information
        $mtdpStrategies = [];
        try {
            $response = \CodeIgniter\Config\Services::curlrequest()->get(base_url('api/mtdp/all-strategies'));
            $result = json_decode($response->getBody(), true);
            if (isset($result['success']) && $result['success'] && isset($result['data'])) {
                $mtdpStrategies = $result['data'];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error fetching MTDP strategies: ' . $e->getMessage());
        }

        // We still need the MTDP plans for backward compatibility
        $mtdpPlans = $mtdpModel->where('mtdp_status', 1)
                              ->where('deleted_at IS NULL')
                              ->findAll();

        // Prepare data for view
        $data = [
            'title' => 'Link Activity to Plans',
            'workplan' => $workplan,
            'activity' => $activity,
            'naspLinks' => $naspLinks,
            'corporateLinks' => $corporateLinks,
            'mtdpLinks' => $mtdpLinks,
            'naspPlans' => $naspPlans,
            'naspOutputs' => $naspOutputs,
            'corporatePlans' => $corporatePlans,
            'corporateStrategies' => $corporateStrategies,
            'mtdpPlans' => $mtdpPlans,
            'mtdpStrategies' => $mtdpStrategies,
            'branch' => $branch,
            'supervisor' => $supervisor,
            'actionOfficer' => $actionOfficer,
            'validation' => \Config\Services::validation()
        ];

        // Load the view
        return view('workplans/workplan_activity_plans', $data);
    }

    /**
     * Link a workplan activity to a NASP plan.
     *
     * @param int|null $workplanId
     * @param int|null $activityId
     * @return mixed
     */
    public function linkActivityPlan($workplanId = null, $activityId = null)
    {
        // Check if this is a NASP, Corporate Plan, or MTDP link
        $linkType = $this->request->getPost('link_type');

        // Use the activity ID from the form if provided, otherwise use the URL parameter
        $formActivityId = $this->request->getPost('activity_id');
        if (!empty($formActivityId)) {
            $activityId = $formActivityId;
        }

        if ($linkType === 'corporate') {
            return $this->linkActivityCorporatePlan($workplanId, $activityId);
        } else if ($linkType === 'mtdp') {
            return $this->linkActivityMtdpPlan($workplanId, $activityId);
        }

        // Default to NASP plan linking
        // Load required models
        $workplanActivityModel = new \App\Models\WorkplanActivityModel();
        $workplanNaspLinkModel = new \App\Models\WorkplanNaspLinkModel();

        // Get workplan and activity
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Workplan not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activity = $workplanActivityModel->find($activityId);
        if (!$activity) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Activity not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Activity not found.');
        }

        // Validate input - now we only need output_id
        $rules = [
            'output_id' => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Please select a NASP output.',
                    'errors' => $this->validator->getErrors(),
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get the output_id
        $outputId = $this->request->getPost('output_id');

        // Fetch the output details to backtrack and get all parent IDs
        $naspModel = new \App\Models\NaspModel();
        $output = $naspModel->find($outputId);

        if (!$output) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Selected NASP output not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Selected NASP output not found.');
        }

        // Get the objective (parent of output)
        $objective = $naspModel->find($output['parent_id']);
        if (!$objective) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Parent objective for selected output not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Parent objective for selected output not found.');
        }

        // Get the specific area (parent of objective)
        $specificArea = $naspModel->find($objective['parent_id']);
        if (!$specificArea) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Parent specific area for selected objective not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Parent specific area for selected objective not found.');
        }

        // Get the DIP (parent of specific area)
        $dip = $naspModel->find($specificArea['parent_id']);
        if (!$dip) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Parent DIP for selected specific area not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Parent DIP for selected specific area not found.');
        }

        // Get the APA (parent of DIP)
        $apa = $naspModel->find($dip['parent_id']);
        if (!$apa) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Parent APA for selected DIP not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Parent APA for selected DIP not found.');
        }

        // Get the NASP plan (parent of APA)
        $plan = $naspModel->find($apa['parent_id']);
        if (!$plan) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Parent NASP plan for selected APA not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Parent NASP plan for selected APA not found.');
        }

        // Prepare data for saving with all the IDs
        $data = [
            'workplan_activity_id' => $activityId,
            'nasp_id' => $plan['id'],
            'apa_id' => $apa['id'],
            'dip_id' => $dip['id'],
            'specific_area_id' => $specificArea['id'],
            'objective_id' => $objective['id'],
            'output_id' => $output['id'],
            'created_by' => session()->get('user_id')
        ];

        // Save the link
        if ($workplanNaspLinkModel->save($data)) {
            // Get the newly created link ID
            $linkId = $workplanNaspLinkModel->getInsertID();

            // If this is an AJAX request, return JSON response
            if ($this->request->isAJAX()) {
                // Prepare the row data for the response
                $rowData = [
                    'id' => $linkId,
                    'nasp_code' => $plan['code'],
                    'output_code' => $output['code'],
                    'output_title' => $output['title']
                ];

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Activity linked to NASP plan successfully.',
                    'link' => $rowData,
                    'csrf_hash' => csrf_hash()
                ]);
            }

            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/plans')
                           ->with('success', 'Activity linked to NASP plan successfully.');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to link activity to NASP plan.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->withInput()
                           ->with('error', 'Failed to link activity to NASP plan.');
        }
    }

    /**
     * Link a workplan activity to an MTDP plan.
     *
     * @param int|null $workplanId
     * @param int|null $activityId
     * @return mixed
     */
    private function linkActivityMtdpPlan($workplanId = null, $activityId = null)
    {
        // Load required models
        $workplanActivityModel = new \App\Models\WorkplanActivityModel();
        $workplanMtdpLinkModel = new \App\Models\WorkplanMtdpLinkModel();

        // Use the activity ID from the form if provided, otherwise use the parameter
        $formActivityId = $this->request->getPost('activity_id');
        if (!empty($formActivityId)) {
            $activityId = $formActivityId;
        }

        // Get workplan and activity
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Workplan not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activity = $workplanActivityModel->find($activityId);
        if (!$activity) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Activity not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Activity not found.');
        }

        // Validate input - now we only need strategies_id
        $rules = [
            'strategies_id' => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Please select an MTDP strategy.',
                    'errors' => $this->validator->getErrors(),
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get the strategies_id
        $strategyId = $this->request->getPost('strategies_id');

        // Load required models to fetch strategy details and backtrack parent IDs
        $mtdpStrategiesModel = new \App\Models\MtdpStrategiesModel();
        $mtdpKraModel = new \App\Models\MtdpKraModel();
        $mtdpInvestmentsModel = new \App\Models\MtdpInvestmentsModel();
        $mtdpSpecificAreaModel = new \App\Models\MtdpSpecificAreaModel();
        $mtdpDipModel = new \App\Models\MtdpDipModel();
        $mtdpSpaModel = new \App\Models\MtdpSpaModel();
        $mtdpModel = new \App\Models\MtdpModel();

        // Get the strategy
        $strategy = $mtdpStrategiesModel->find($strategyId);
        if (!$strategy) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Selected MTDP strategy not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Selected MTDP strategy not found.');
        }

        // Get the KRA (parent of strategy)
        $kra = $mtdpKraModel->find($strategy['kra_id']);
        if (!$kra) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Parent KRA for selected strategy not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Parent KRA for selected strategy not found.');
        }

        // Get the Investment (parent of KRA)
        $investment = $mtdpInvestmentsModel->find($kra['investment_id']);
        if (!$investment) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Parent Investment for selected KRA not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Parent Investment for selected KRA not found.');
        }

        // Get the Specific Area (parent of Investment)
        $specificArea = $mtdpSpecificAreaModel->find($investment['sa_id']);
        if (!$specificArea) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Parent Specific Area for selected Investment not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Parent Specific Area for selected Investment not found.');
        }

        // Get the DIP (parent of Specific Area)
        $dip = $mtdpDipModel->find($specificArea['dip_id']);
        if (!$dip) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Parent DIP for selected Specific Area not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Parent DIP for selected Specific Area not found.');
        }

        // Get the SPA (parent of DIP)
        $spa = $mtdpSpaModel->find($dip['spa_id']);
        if (!$spa) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Parent SPA for selected DIP not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Parent SPA for selected DIP not found.');
        }

        // Get the MTDP plan (parent of SPA)
        $plan = $mtdpModel->find($spa['mtdp_id']);
        if (!$plan) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Parent MTDP plan for selected SPA not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Parent MTDP plan for selected SPA not found.');
        }

        // Prepare data for saving with all the IDs
        $data = [
            'workplan_activity_id' => $activityId,
            'mtdp_id' => $plan['id'],
            'spa_id' => $spa['id'],
            'dip_id' => $dip['id'],
            'sa_id' => $specificArea['id'],
            'investment_id' => $investment['id'],
            'kra_id' => $kra['id'],
            'strategies_id' => $strategy['id'],
            'created_by' => session()->get('user_id')
        ];

        // Include indicators_id if provided
        if ($this->request->getPost('indicators_id')) {
            $data['indicators_id'] = $this->request->getPost('indicators_id');
        }

        // Save the link
        if ($workplanMtdpLinkModel->save($data)) {
            // Get the newly created link ID
            $linkId = $workplanMtdpLinkModel->getInsertID();

            // If this is an AJAX request, return JSON response
            if ($this->request->isAJAX()) {
                // Prepare the row data for the response
                $rowData = [
                    'id' => $linkId,
                    'mtdp_code' => $plan['abbrev'],
                    'spa_code' => $spa['code'],
                    'dip_code' => $dip['dip_code'],
                    'strategy' => $strategy['strategy']
                ];

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Activity linked to MTDP plan successfully.',
                    'link' => $rowData,
                    'csrf_hash' => csrf_hash()
                ]);
            }

            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/plans')
                           ->with('success', 'Activity linked to MTDP plan successfully.');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to link activity to MTDP plan.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->withInput()
                           ->with('error', 'Failed to link activity to MTDP plan.');
        }
    }

    /**
     * Link a workplan activity to a Corporate Plan.
     *
     * @param int|null $workplanId
     * @param int|null $activityId
     * @return mixed
     */
    private function linkActivityCorporatePlan($workplanId = null, $activityId = null)
    {
        // Load required models
        $workplanActivityModel = new \App\Models\WorkplanActivityModel();
        $workplanCorporatePlanLinkModel = new \App\Models\WorkplanCorporatePlanLinkModel();

        // Use the activity ID from the form if provided, otherwise use the parameter
        $formActivityId = $this->request->getPost('activity_id');
        if (!empty($formActivityId)) {
            $activityId = $formActivityId;
        }

        // Get workplan and activity
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Workplan not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activity = $workplanActivityModel->find($activityId);
        if (!$activity) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Activity not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Activity not found.');
        }

        // Validate input - now we only need strategies_id
        $rules = [
            'strategies_id' => 'required|integer'
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Please select a Corporate Plan strategy.',
                    'errors' => $this->validator->getErrors(),
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get the strategies_id
        $strategyId = $this->request->getPost('strategies_id');

        // Load required model to fetch strategy details and backtrack parent IDs
        $corporatePlanModel = new \App\Models\CorporatePlanModel();

        // Get the strategy
        $strategy = $corporatePlanModel->find($strategyId);
        if (!$strategy) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Selected Corporate Plan strategy not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Selected Corporate Plan strategy not found.');
        }

        // Get the KRA (parent of strategy)
        $kra = $corporatePlanModel->find($strategy['parent_id']);
        if (!$kra) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Parent KRA for selected strategy not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Parent KRA for selected strategy not found.');
        }

        // Get the Objective (parent of KRA)
        $objective = $corporatePlanModel->find($kra['parent_id']);
        if (!$objective) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Parent Objective for selected KRA not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Parent Objective for selected KRA not found.');
        }

        // Get the Overarching Objective (parent of Objective)
        $overarching = $corporatePlanModel->find($objective['parent_id']);
        if (!$overarching) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Parent Overarching Objective for selected Objective not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Parent Overarching Objective for selected Objective not found.');
        }

        // Get the Corporate Plan (parent of Overarching Objective)
        $plan = $corporatePlanModel->find($overarching['parent_id']);
        if (!$plan) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Parent Corporate Plan for selected Overarching Objective not found.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->with('error', 'Parent Corporate Plan for selected Overarching Objective not found.');
        }

        // Prepare data for saving with all the IDs
        $data = [
            'workplan_activity_id' => $activityId,
            'corporate_plan_id' => $plan['id'],
            'overarching_objective_id' => $overarching['id'],
            'objective_id' => $objective['id'],
            'kra_id' => $kra['id'],
            'strategies_id' => $strategy['id'],
            'created_by' => session()->get('user_id')
        ];

        // Save the link
        if ($workplanCorporatePlanLinkModel->save($data)) {
            // Get the newly created link ID
            $linkId = $workplanCorporatePlanLinkModel->getInsertID();

            // If this is an AJAX request, return JSON response
            if ($this->request->isAJAX()) {
                // Prepare the row data for the response
                $rowData = [
                    'id' => $linkId,
                    'plan_code' => $plan['code'],
                    'objective_code' => $objective['code'],
                    'strategy_title' => $strategy['title']
                ];

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Activity linked to Corporate Plan successfully.',
                    'link' => $rowData,
                    'csrf_hash' => csrf_hash()
                ]);
            }

            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/plans')
                           ->with('success', 'Activity linked to Corporate Plan successfully.');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to link activity to Corporate Plan.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->back()->withInput()
                           ->with('error', 'Failed to link activity to Corporate Plan.');
        }
    }

    /**
     * Delete a link between a workplan activity and a NASP plan.
     *
     * @param int|null $workplanId
     * @param int|null $activityId
     * @param int|null $linkId
     * @return mixed
     */
    public function deleteActivityPlan($workplanId = null, $activityId = null, $linkId = null)
    {
        // Check if this is a NASP, Corporate Plan, or MTDP link
        $linkType = $this->request->getGet('type');

        // For AJAX requests, we might get the type from POST data
        if ($this->request->isAJAX() && empty($linkType)) {
            $linkType = $this->request->getPost('type');
        }

        // Use the activity ID from the query parameter if provided, otherwise use the URL parameter
        $queryActivityId = $this->request->getGet('activity_id');
        if (!empty($queryActivityId)) {
            $activityId = $queryActivityId;
        }

        // For AJAX requests, we might get the activity_id from POST data
        if ($this->request->isAJAX() && empty($activityId)) {
            $postActivityId = $this->request->getPost('activity_id');
            if (!empty($postActivityId)) {
                $activityId = $postActivityId;
            }
        }

        // For AJAX requests, we might get the link_id from POST data
        if ($this->request->isAJAX() && empty($linkId)) {
            $postLinkId = $this->request->getPost('link_id');
            if (!empty($postLinkId)) {
                $linkId = $postLinkId;
            }
        }

        if ($linkType === 'corporate') {
            return $this->deleteActivityCorporatePlan($workplanId, $activityId, $linkId);
        } else if ($linkType === 'mtdp') {
            return $this->deleteActivityMtdpPlan($workplanId, $activityId, $linkId);
        }

        // Default to NASP plan link deletion
        // Load required models
        $workplanActivityModel = new \App\Models\WorkplanActivityModel();
        $workplanNaspLinkModel = new \App\Models\WorkplanNaspLinkModel();

        // Get workplan and activity
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Workplan not found.'
                ]);
            }
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activity = $workplanActivityModel->find($activityId);
        if (!$activity) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Activity not found.'
                ]);
            }
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Activity not found.');
        }

        // Get the link
        $link = $workplanNaspLinkModel->find($linkId);
        if (!$link) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Link not found.'
                ]);
            }
            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/plans')
                           ->with('error', 'Link not found.');
        }

        // Delete the link
        if ($workplanNaspLinkModel->delete($linkId)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'NASP plan link deleted successfully.',
                    'link_id' => $linkId,
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/plans')
                           ->with('success', 'NASP plan link deleted successfully.');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to delete NASP plan link.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/plans')
                           ->with('error', 'Failed to delete NASP plan link.');
        }
    }

    /**
     * Delete a link between a workplan activity and an MTDP plan.
     *
     * @param int|null $workplanId
     * @param int|null $activityId
     * @param int|null $linkId
     * @return mixed
     */
    private function deleteActivityMtdpPlan($workplanId = null, $activityId = null, $linkId = null)
    {
        // Load required models
        $workplanActivityModel = new \App\Models\WorkplanActivityModel();
        $workplanMtdpLinkModel = new \App\Models\WorkplanMtdpLinkModel();

        // Use the activity ID from the query parameter if provided, otherwise use the parameter
        $queryActivityId = $this->request->getGet('activity_id');
        if (!empty($queryActivityId)) {
            $activityId = $queryActivityId;
        }

        // For AJAX requests, we might get the activity_id from POST data
        if ($this->request->isAJAX() && empty($activityId)) {
            $postActivityId = $this->request->getPost('activity_id');
            if (!empty($postActivityId)) {
                $activityId = $postActivityId;
            }
        }

        // For AJAX requests, we might get the link_id from POST data
        if ($this->request->isAJAX() && empty($linkId)) {
            $postLinkId = $this->request->getPost('link_id');
            if (!empty($postLinkId)) {
                $linkId = $postLinkId;
            }
        }

        // Get workplan and activity
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Workplan not found.'
                ]);
            }
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activity = $workplanActivityModel->find($activityId);
        if (!$activity) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Activity not found.'
                ]);
            }
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Activity not found.');
        }

        // Get the link
        $link = $workplanMtdpLinkModel->find($linkId);
        if (!$link) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'MTDP plan link not found.'
                ]);
            }
            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/plans')
                           ->with('error', 'MTDP plan link not found.');
        }

        // Delete the link
        if ($workplanMtdpLinkModel->delete($linkId)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'MTDP plan link deleted successfully.',
                    'link_id' => $linkId,
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/plans')
                           ->with('success', 'MTDP plan link deleted successfully.');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to delete MTDP plan link.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/plans')
                           ->with('error', 'Failed to delete MTDP plan link.');
        }
    }

    /**
     * Delete a link between a workplan activity and a Corporate Plan.
     *
     * @param int|null $workplanId
     * @param int|null $activityId
     * @param int|null $linkId
     * @return mixed
     */
    private function deleteActivityCorporatePlan($workplanId = null, $activityId = null, $linkId = null)
    {
        // Load required models
        $workplanActivityModel = new \App\Models\WorkplanActivityModel();
        $workplanCorporatePlanLinkModel = new \App\Models\WorkplanCorporatePlanLinkModel();

        // Use the activity ID from the query parameter if provided, otherwise use the parameter
        $queryActivityId = $this->request->getGet('activity_id');
        if (!empty($queryActivityId)) {
            $activityId = $queryActivityId;
        }

        // For AJAX requests, we might get the activity_id from POST data
        if ($this->request->isAJAX() && empty($activityId)) {
            $postActivityId = $this->request->getPost('activity_id');
            if (!empty($postActivityId)) {
                $activityId = $postActivityId;
            }
        }

        // For AJAX requests, we might get the link_id from POST data
        if ($this->request->isAJAX() && empty($linkId)) {
            $postLinkId = $this->request->getPost('link_id');
            if (!empty($postLinkId)) {
                $linkId = $postLinkId;
            }
        }

        // Get workplan and activity
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Workplan not found.'
                ]);
            }
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activity = $workplanActivityModel->find($activityId);
        if (!$activity) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Activity not found.'
                ]);
            }
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Activity not found.');
        }

        // Get the link
        $link = $workplanCorporatePlanLinkModel->find($linkId);
        if (!$link) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Corporate Plan link not found.'
                ]);
            }
            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/plans')
                           ->with('error', 'Corporate Plan link not found.');
        }

        // Delete the link
        if ($workplanCorporatePlanLinkModel->delete($linkId)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Corporate Plan link deleted successfully.',
                    'link_id' => $linkId,
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/plans')
                           ->with('success', 'Corporate Plan link deleted successfully.');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to delete Corporate Plan link.',
                    'csrf_hash' => csrf_hash()
                ]);
            }
            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/plans')
                           ->with('error', 'Failed to delete Corporate Plan link.');
        }
    }
}
