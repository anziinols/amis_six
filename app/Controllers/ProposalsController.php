<?php
// app/Controllers/ProposalsController.php

namespace App\Controllers;

use App\Models\ProposalModel;
use App\Models\WorkplanModel;
use App\Models\WorkplanActivityModel;
use App\Models\WorkplanTrainingActivityModel;
use App\Models\WorkplanInfrastructureActivityModel;
use App\Models\WorkplanInputActivityModel;
use App\Models\WorkplanOutputActivityModel;
use App\Models\GovStructureModel;
use App\Models\UserModel;

class ProposalsController extends BaseController
{
    protected $proposalModel;
    protected $workplanModel;
    protected $workplanActivityModel;
    protected $workplanTrainingActivityModel;
    protected $workplanInfrastructureActivityModel;
    protected $workplanInputActivityModel;
    protected $workplanOutputActivityModel;
    protected $govStructureModel;
    protected $userModel;
    protected $helpers = ['form', 'url', 'email']; // Load form, URL, and email helpers

    public function __construct()
    {
        $this->proposalModel = new ProposalModel();
        $this->workplanModel = new WorkplanModel();
        $this->workplanActivityModel = new WorkplanActivityModel();
        $this->workplanTrainingActivityModel = new WorkplanTrainingActivityModel();
        $this->workplanInfrastructureActivityModel = new WorkplanInfrastructureActivityModel();
        $this->workplanInputActivityModel = new WorkplanInputActivityModel();
        $this->workplanOutputActivityModel = new WorkplanOutputActivityModel();
        $this->govStructureModel = new GovStructureModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display a list of proposals
     *
     * @return mixed
     */
    public function index()
    {
        $proposals = $this->proposalModel->getProposalWithDetails();

        $data = [
            'title' => 'Proposals',
            'proposals' => $proposals
        ];

        return view('proposals/proposals_index', $data);
    }

    /**
     * Show the form for creating a new proposal
     *
     * @return mixed
     */
    public function new()
    {
        // Get the logged-in supervisor's ID
        $supervisorId = session()->get('user_id');

        // Get activities assigned to this supervisor
        $allActivities = $this->workplanActivityModel
            ->select('workplan_activities.*, workplans.title as workplan_title')
            ->join('workplans', 'workplans.id = workplan_activities.workplan_id', 'left')
            ->where('workplan_activities.supervisor_id', $supervisorId)
            ->findAll();

        // Filter activities to only include those that are linked to plans
        $linkedActivities = [];
        $unlinkedActivities = [];

        foreach ($allActivities as $activity) {
            if (\App\Helpers\ActivityLinkingHelper::isActivityLinked($activity['id'])) {
                $linkedActivities[] = $activity;
            } else {
                $unlinkedActivities[] = $activity;
            }
        }

        // Get provinces for dropdown
        $provinces = $this->govStructureModel
            ->where('level', 'province')
            ->findAll();

        // Get action officers for dropdown
        $actionOfficers = $this->userModel
            ->select('id, CONCAT(fname, " ", lname) as full_name')
            ->where('role', 'user')
            ->where('user_status', 1)
            ->findAll();

        $data = [
            'title' => 'Create New Proposal',
            'activities' => $linkedActivities,
            'unlinkedActivities' => $unlinkedActivities,
            'provinces' => $provinces,
            'actionOfficers' => $actionOfficers,
            'validation' => \Config\Services::validation()
        ];

        return view('proposals/proposals_create', $data);
    }

    /**
     * Process the form submission to create a proposal
     *
     * @return mixed
     */
    public function create()
    {
        // Validate input
        $rules = [
            'activity_id' => 'required|integer',
            'action_officer_id' => 'permit_empty|integer',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
            'date_start' => 'required|valid_date',
            'date_end' => 'required|valid_date',
            'total_cost' => 'permit_empty|decimal',
            'location' => 'permit_empty|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Get the activity to determine the workplan_id
        $activityId = $this->request->getPost('activity_id');
        $activity = $this->workplanActivityModel->find($activityId);
        if (!$activity) {
            return redirect()->back()->withInput()->with('error', 'Selected activity not found.');
        }

        // Validate that the activity is linked to at least one plan
        if (!\App\Helpers\ActivityLinkingHelper::isActivityLinked($activityId)) {
            $errorMessage = \App\Helpers\ActivityLinkingHelper::getUnlinkedActivityMessage($activity['title']);
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        // Get the logged-in supervisor's ID
        $supervisorId = session()->get('user_id');

        // Prepare data for insertion
        $data = [
            'workplan_id' => $activity['workplan_id'],
            'activity_id' => $this->request->getPost('activity_id'),
            'supervisor_id' => $supervisorId,
            'action_officer_id' => $this->request->getPost('action_officer_id') ?: null,
            'province_id' => $this->request->getPost('province_id'),
            'district_id' => $this->request->getPost('district_id'),
            'date_start' => $this->request->getPost('date_start'),
            'date_end' => $this->request->getPost('date_end'),
            'total_cost' => $this->request->getPost('total_cost') ?: null,
            'location' => $this->request->getPost('location'),
            'status' => 'pending',
            'created_by' => $supervisorId
        ];

        // Insert the proposal
        if ($this->proposalModel->insert($data)) {
            // Get the ID of the newly created proposal
            $proposalId = $this->proposalModel->getInsertID();

            // Send email notification
            $this->sendProposalCreationNotification($proposalId, $data);

            return redirect()->to('/proposals')->with('success', 'Proposal created successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create proposal.');
        }
    }

    /**
     * Display the specified proposal
     *
     * @param int|null $id
     * @return mixed
     */
    public function show($id = null)
    {
        $proposal = $this->proposalModel->getProposalWithDetails($id);

        if (!$proposal) {
            return redirect()->to('/proposals')->with('error', 'Proposal not found.');
        }

        // Get workplan details
        $workplan = $this->workplanModel->find($proposal['workplan_id']);

        // Get workplan activity details
        $activity = $this->workplanActivityModel->find($proposal['activity_id']);

        // Get activity type specific details
        $activityTypeDetails = null;

        if ($proposal['activity_type'] === 'training') {
            $activityTypeDetails = $this->workplanTrainingActivityModel
                ->where('proposal_id', $proposal['id'])
                ->where('activity_id', $proposal['activity_id'])
                ->first();

            // Parse JSON fields if they exist
            if ($activityTypeDetails && isset($activityTypeDetails['trainees'])) {
                $activityTypeDetails['trainees'] = json_decode($activityTypeDetails['trainees'], true);
            }
            if ($activityTypeDetails && isset($activityTypeDetails['training_images'])) {
                $activityTypeDetails['training_images'] = json_decode($activityTypeDetails['training_images'], true);
            }
            if ($activityTypeDetails && isset($activityTypeDetails['training_files'])) {
                $activityTypeDetails['training_files'] = json_decode($activityTypeDetails['training_files'], true);
            }
        } elseif ($proposal['activity_type'] === 'infrastructure') {
            $activityTypeDetails = $this->workplanInfrastructureActivityModel
                ->where('proposal_id', $proposal['id'])
                ->where('activity_id', $proposal['activity_id'])
                ->first();

            // Parse JSON fields if they exist
            if ($activityTypeDetails && isset($activityTypeDetails['infrastructure_images'])) {
                $activityTypeDetails['infrastructure_images'] = json_decode($activityTypeDetails['infrastructure_images'], true);
            }
            if ($activityTypeDetails && isset($activityTypeDetails['infrastructure_files'])) {
                $activityTypeDetails['infrastructure_files'] = json_decode($activityTypeDetails['infrastructure_files'], true);
            }
        } elseif ($proposal['activity_type'] === 'inputs') {
            $activityTypeDetails = $this->workplanInputActivityModel
                ->where('proposal_id', $proposal['id'])
                ->where('activity_id', $proposal['activity_id'])
                ->first();

            // Parse JSON fields if they exist
            if ($activityTypeDetails && isset($activityTypeDetails['inputs'])) {
                $activityTypeDetails['inputs'] = json_decode($activityTypeDetails['inputs'], true);
            }
            if ($activityTypeDetails && isset($activityTypeDetails['input_images'])) {
                $activityTypeDetails['input_images'] = json_decode($activityTypeDetails['input_images'], true);
            }
            if ($activityTypeDetails && isset($activityTypeDetails['input_files'])) {
                $activityTypeDetails['input_files'] = json_decode($activityTypeDetails['input_files'], true);
            }
        } elseif ($proposal['activity_type'] === 'output') {
            $activityTypeDetails = $this->workplanOutputActivityModel
                ->where('proposal_id', $proposal['id'])
                ->where('activity_id', $proposal['activity_id'])
                ->first();

            // Parse JSON fields if they exist
            if ($activityTypeDetails && isset($activityTypeDetails['outputs'])) {
                $activityTypeDetails['outputs'] = json_decode($activityTypeDetails['outputs'], true);
            }
            if ($activityTypeDetails && isset($activityTypeDetails['beneficiaries'])) {
                $activityTypeDetails['beneficiaries'] = json_decode($activityTypeDetails['beneficiaries'], true);
            }
            if ($activityTypeDetails && isset($activityTypeDetails['output_images'])) {
                $activityTypeDetails['output_images'] = json_decode($activityTypeDetails['output_images'], true);
            }
            if ($activityTypeDetails && isset($activityTypeDetails['output_files'])) {
                $activityTypeDetails['output_files'] = json_decode($activityTypeDetails['output_files'], true);
            }
        }

        $data = [
            'title' => 'View Proposal',
            'proposal' => $proposal,
            'workplan' => $workplan,
            'activity' => $activity,
            'activityTypeDetails' => $activityTypeDetails,
            'activityType' => $proposal['activity_type']
        ];

        return view('proposals/proposals_show', $data);
    }

    /**
     * Show the form for editing the specified proposal
     *
     * @param int|null $id
     * @return mixed
     */
    public function edit($id = null)
    {
        $proposal = $this->proposalModel->find($id);

        if (!$proposal) {
            return redirect()->to('/proposals')->with('error', 'Proposal not found.');
        }

        // Get workplans for dropdown
        $workplans = $this->workplanModel->findAll();

        // Get activities for the selected workplan
        $activities = $this->workplanActivityModel
            ->where('workplan_id', $proposal['workplan_id'])
            ->findAll();

        // Get provinces for dropdown
        $provinces = $this->govStructureModel
            ->where('level', 'province')
            ->findAll();

        // Get districts for the selected province
        $districts = $this->govStructureModel
            ->where('level', 'district')
            ->where('parent_id', $proposal['province_id'])
            ->findAll();

        // Get supervisors for dropdown
        $supervisors = $this->userModel
            ->select('id, CONCAT(fname, " ", lname) as full_name')
            ->where('role', 'supervisor')
            ->where('user_status', 1)
            ->findAll();

        // Get action officers for dropdown
        $actionOfficers = $this->userModel
            ->select('id, CONCAT(fname, " ", lname) as full_name')
            ->where('role', 'user')
            ->where('user_status', 1)
            ->findAll();

        $data = [
            'title' => 'Edit Proposal',
            'proposal' => $proposal,
            'workplans' => $workplans,
            'activities' => $activities,
            'provinces' => $provinces,
            'districts' => $districts,
            'supervisors' => $supervisors,
            'actionOfficers' => $actionOfficers,
            'validation' => \Config\Services::validation()
        ];

        return view('proposals/proposals_edit', $data);
    }

    /**
     * Update the specified proposal
     *
     * @param int|null $id
     * @return mixed
     */
    public function update($id = null)
    {
        $proposal = $this->proposalModel->find($id);

        if (!$proposal) {
            return redirect()->to('/proposals')->with('error', 'Proposal not found.');
        }

        // Validate input
        $rules = [
            'workplan_id' => 'required|integer',
            'activity_id' => 'required|integer',
            'supervisor_id' => 'permit_empty|integer',
            'action_officer_id' => 'permit_empty|integer',
            'province_id' => 'required|integer',
            'district_id' => 'required|integer',
            'date_start' => 'required|valid_date',
            'date_end' => 'required|valid_date',
            'total_cost' => 'permit_empty|decimal',
            'location' => 'permit_empty|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data for updating
        $data = [
            'workplan_id' => $this->request->getPost('workplan_id'),
            'activity_id' => $this->request->getPost('activity_id'),
            'supervisor_id' => $this->request->getPost('supervisor_id') ?: null,
            'action_officer_id' => $this->request->getPost('action_officer_id') ?: null,
            'province_id' => $this->request->getPost('province_id'),
            'district_id' => $this->request->getPost('district_id'),
            'date_start' => $this->request->getPost('date_start'),
            'date_end' => $this->request->getPost('date_end'),
            'total_cost' => $this->request->getPost('total_cost') ?: null,
            'location' => $this->request->getPost('location'),
            'updated_by' => session()->get('user_id')
        ];

        // Update the proposal
        if ($this->proposalModel->update($id, $data)) {
            // Send email notification
            $this->sendProposalUpdateNotification($id, $data);

            return redirect()->to('/proposals')->with('success', 'Proposal updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update proposal.');
        }
    }

    /**
     * Show the form for updating the status of a proposal
     *
     * @param int|null $id
     * @return mixed
     */
    public function status($id = null)
    {
        $proposal = $this->proposalModel->getProposalWithDetails($id);

        if (!$proposal) {
            return redirect()->to('/proposals')->with('error', 'Proposal not found.');
        }

        $data = [
            'title' => 'Update Proposal Status',
            'proposal' => $proposal,
            'validation' => \Config\Services::validation()
        ];

        return view('proposals/proposals_status', $data);
    }

    /**
     * Update the status of the specified proposal
     *
     * @param int|null $id
     * @return mixed
     */
    public function updateStatus($id = null)
    {
        $proposal = $this->proposalModel->find($id);

        if (!$proposal) {
            return redirect()->to('/proposals')->with('error', 'Proposal not found.');
        }

        // Validate input
        $rules = [
            'status' => 'required|in_list[pending,submitted,approved,rated]',
            'status_remarks' => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $status = $this->request->getPost('status');
        $remarks = $this->request->getPost('status_remarks');
        $userId = session()->get('user_id');

        // Update the proposal status
        if ($this->proposalModel->updateStatus($id, $status, $userId, $remarks)) {
            return redirect()->to('/proposals')->with('success', 'Proposal status updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update proposal status.');
        }
    }

    /**
     * Show the form for rating a proposal
     *
     * @param int|null $id
     * @return mixed
     */
    public function rate($id = null)
    {
        $proposal = $this->proposalModel->getProposalWithDetails($id);

        if (!$proposal) {
            return redirect()->to('/proposals')->with('error', 'Proposal not found.');
        }

        $data = [
            'title' => 'Rate Proposal',
            'proposal' => $proposal,
            'validation' => \Config\Services::validation()
        ];

        return view('proposals/proposals_rate', $data);
    }

    /**
     * Process the rating of a proposal
     *
     * @param int|null $id
     * @return mixed
     */
    public function submitRating($id = null)
    {
        $proposal = $this->proposalModel->find($id);

        if (!$proposal) {
            return redirect()->to('/proposals')->with('error', 'Proposal not found.');
        }

        // Validate input
        $rules = [
            'rating_score' => 'required|decimal|greater_than[0]|less_than_equal_to[5]',
            'rate_remarks' => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $score = $this->request->getPost('rating_score');
        $remarks = $this->request->getPost('rate_remarks');
        $userId = session()->get('user_id');

        // Rate the proposal
        if ($this->proposalModel->rateProposal($id, $score, $userId, $remarks)) {
            // Send email notification to action officer
            $this->sendRatingNotificationEmail($id, $score, $remarks);

            return redirect()->to('/proposals')->with('success', 'Proposal rated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to rate proposal.');
        }
    }

    /**
     * Get activities for a workplan (AJAX)
     *
     * @return mixed
     */
    public function getActivities()
    {
        $workplanId = $this->request->getGet('workplan_id');

        if (!$workplanId) {
            return $this->response->setJSON(['error' => 'Workplan ID is required']);
        }

        $activities = $this->workplanActivityModel
            ->select('id, title, activity_type')
            ->where('workplan_id', $workplanId)
            ->findAll();

        return $this->response->setJSON(['activities' => $activities]);
    }

    /**
     * Get districts for a province (AJAX)
     *
     * @return mixed
     */
    public function getDistricts()
    {
        $provinceId = $this->request->getGet('province_id');

        if (!$provinceId) {
            return $this->response->setJSON(['error' => 'Province ID is required']);
        }

        $districts = $this->govStructureModel
            ->select('id, name')
            ->where('level', 'district')
            ->where('parent_id', $provinceId)
            ->findAll();

        return $this->response->setJSON(['districts' => $districts]);
    }

    /**
     * Show the supervision details for a proposal
     *
     * @param int|null $id
     * @return mixed
     */
    public function supervise($id = null)
    {
        $proposal = $this->proposalModel->getProposalWithDetails($id);

        if (!$proposal) {
            return redirect()->to('/proposals')->with('error', 'Proposal not found.');
        }

        // Get workplan details
        $workplan = $this->workplanModel->find($proposal['workplan_id']);

        // Get workplan activity details
        $activity = $this->workplanActivityModel->find($proposal['activity_id']);

        // Get activity type specific details
        $activityTypeDetails = null;

        if ($proposal['activity_type'] === 'training') {
            $activityTypeDetails = $this->workplanTrainingActivityModel
                ->where('proposal_id', $proposal['id'])
                ->where('activity_id', $proposal['activity_id'])
                ->first();

            // Parse JSON fields if they exist
            if ($activityTypeDetails && isset($activityTypeDetails['trainees'])) {
                $activityTypeDetails['trainees'] = json_decode($activityTypeDetails['trainees'], true);
            }
            if ($activityTypeDetails && isset($activityTypeDetails['training_images'])) {
                $activityTypeDetails['training_images'] = json_decode($activityTypeDetails['training_images'], true);
            }
            if ($activityTypeDetails && isset($activityTypeDetails['training_files'])) {
                $activityTypeDetails['training_files'] = json_decode($activityTypeDetails['training_files'], true);
            }
        } elseif ($proposal['activity_type'] === 'infrastructure') {
            $activityTypeDetails = $this->workplanInfrastructureActivityModel
                ->where('proposal_id', $proposal['id'])
                ->where('activity_id', $proposal['activity_id'])
                ->first();

            // Parse JSON fields if they exist
            if ($activityTypeDetails && isset($activityTypeDetails['infrastructure_images'])) {
                $activityTypeDetails['infrastructure_images'] = json_decode($activityTypeDetails['infrastructure_images'], true);
            }
            if ($activityTypeDetails && isset($activityTypeDetails['infrastructure_files'])) {
                $activityTypeDetails['infrastructure_files'] = json_decode($activityTypeDetails['infrastructure_files'], true);
            }
        } elseif ($proposal['activity_type'] === 'inputs') {
            $activityTypeDetails = $this->workplanInputActivityModel
                ->where('proposal_id', $proposal['id'])
                ->where('activity_id', $proposal['activity_id'])
                ->first();

            // Parse JSON fields if they exist
            if ($activityTypeDetails && isset($activityTypeDetails['inputs'])) {
                $activityTypeDetails['inputs'] = json_decode($activityTypeDetails['inputs'], true);
            }
            if ($activityTypeDetails && isset($activityTypeDetails['input_images'])) {
                $activityTypeDetails['input_images'] = json_decode($activityTypeDetails['input_images'], true);
            }
            if ($activityTypeDetails && isset($activityTypeDetails['input_files'])) {
                $activityTypeDetails['input_files'] = json_decode($activityTypeDetails['input_files'], true);
            }
        } elseif ($proposal['activity_type'] === 'output') {
            $activityTypeDetails = $this->workplanOutputActivityModel
                ->where('proposal_id', $proposal['id'])
                ->where('activity_id', $proposal['activity_id'])
                ->first();

            // Parse JSON fields if they exist
            if ($activityTypeDetails && isset($activityTypeDetails['outputs'])) {
                $activityTypeDetails['outputs'] = json_decode($activityTypeDetails['outputs'], true);
            }
            if ($activityTypeDetails && isset($activityTypeDetails['beneficiaries'])) {
                $activityTypeDetails['beneficiaries'] = json_decode($activityTypeDetails['beneficiaries'], true);
            }
            if ($activityTypeDetails && isset($activityTypeDetails['output_images'])) {
                $activityTypeDetails['output_images'] = json_decode($activityTypeDetails['output_images'], true);
            }
            if ($activityTypeDetails && isset($activityTypeDetails['output_files'])) {
                $activityTypeDetails['output_files'] = json_decode($activityTypeDetails['output_files'], true);
            }
        }

        $data = [
            'title' => 'Supervise Proposal',
            'proposal' => $proposal,
            'workplan' => $workplan,
            'activity' => $activity,
            'activityTypeDetails' => $activityTypeDetails,
            'activityType' => $proposal['activity_type']
        ];

        return view('proposals/proposals_supervise', $data);
    }

    /**
     * Resend a proposal by changing its status to pending
     *
     * @param int|null $id
     * @return mixed
     */
    public function resendProposal($id = null)
    {
        $proposal = $this->proposalModel->find($id);

        if (!$proposal) {
            return redirect()->to('/proposals')->with('error', 'Proposal not found.');
        }

        $remarks = $this->request->getPost('status_remarks') ?: 'Proposal resent for revision by supervisor.';
        $userId = session()->get('user_id');

        // Update the proposal status to pending
        if ($this->proposalModel->updateStatus($id, 'pending', $userId, $remarks)) {
            // Send email notification to action officer
            $this->sendProposalResendNotification($id, $remarks);

            return redirect()->to('/proposals/supervise/' . $id)->with('success', 'Proposal has been resent for revision.');
        } else {
            return redirect()->to('/proposals/supervise/' . $id)->with('error', 'Failed to resend proposal.');
        }
    }

    /**
     * Approve a proposal by changing its status to approved
     *
     * @param int|null $id
     * @return mixed
     */
    public function approveProposal($id = null)
    {
        $proposal = $this->proposalModel->find($id);

        if (!$proposal) {
            return redirect()->to('/proposals')->with('error', 'Proposal not found.');
        }

        $remarks = $this->request->getPost('status_remarks') ?: 'Proposal approved by supervisor.';
        $userId = session()->get('user_id');

        // Update the proposal status to approved
        if ($this->proposalModel->updateStatus($id, 'approved', $userId, $remarks)) {
            // Send email notification to action officer
            $this->sendProposalApprovalNotification($id, $remarks);

            return redirect()->to('/proposals/supervise/' . $id)->with('success', 'Proposal has been approved successfully.');
        } else {
            return redirect()->to('/proposals/supervise/' . $id)->with('error', 'Failed to approve proposal.');
        }
    }

    /**
     * Send email notification when a new proposal is created
     *
     * @param int $proposalId ID of the newly created proposal
     * @param array $proposalData Proposal data
     * @return bool Success or failure
     */
    protected function sendProposalCreationNotification($proposalId, $proposalData)
    {
        try {
            // Get the complete proposal data
            $proposal = $this->proposalModel->getProposalWithDetails($proposalId);
            if (!$proposal) {
                log_message('error', 'Cannot send proposal creation notification: Proposal not found');
                return false;
            }

            // Get the activity data
            $activity = $this->workplanActivityModel->find($proposal['activity_id']);
            if (!$activity) {
                log_message('error', 'Cannot send proposal creation notification: Activity not found');
                return false;
            }

            // Get the workplan data
            $workplan = $this->workplanModel->find($proposal['workplan_id']);
            if (!$workplan) {
                log_message('error', 'Cannot send proposal creation notification: Workplan not found');
                return false;
            }

            // Get the creator's information (supervisor)
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

            // Format dates for display
            $startDate = date('d M Y', strtotime($proposal['date_start']));
            $endDate = date('d M Y', strtotime($proposal['date_end']));

            // Check if action officer is assigned
            if (!empty($proposal['action_officer_id'])) {
                // Send email to action officer
                $actionOfficer = $this->userModel->find($proposal['action_officer_id']);
                if (!$actionOfficer || empty($actionOfficer['email'])) {
                    log_message('error', 'Cannot send proposal creation notification: Action officer not found or no email available');
                    return false;
                }

                // Prepare email subject and message for action officer
                $subject = 'New Activity Proposal Assigned: ' . $activity['title'];

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
                            <p>Dear ' . $actionOfficer['fname'] . ' ' . $actionOfficer['lname'] . ',</p>

                            <p>A new activity proposal has been created and you have been assigned as the action officer.</p>

                            <div class="highlight">
                                <p><strong>Proposal Details:</strong></p>
                                <p>Activity: ' . $activity['title'] . '</p>
                                <p>Workplan: ' . $workplan['title'] . '</p>
                                <p>Location: ' . $proposal['location'] . '</p>
                                <p>Period: ' . $startDate . ' to ' . $endDate . '</p>
                                <p>Status: <span class="status">' . ucfirst($proposal['status']) . '</span></p>
                            </div>

                            <p>This proposal was created by: <strong>' . $creatorName . '</strong> (' . $creatorEmail . ')</p>

                            <p>You can view the complete proposal details by logging into the system.</p>

                            <p>Thank you,<br>
                            AMIS System</p>
                        </div>
                        <div class="footer">
                            <p>This is an automated message. Please do not reply to this email.</p>
                        </div>
                    </div>
                </body>
                </html>';

                // Send the email to action officer
                $result = send_email($actionOfficer['email'], $subject, $message);

                if (!$result) {
                    log_message('error', 'Failed to send proposal creation notification email to action officer: ' . $actionOfficer['email']);
                } else {
                    log_message('info', 'Proposal creation notification email sent successfully to action officer: ' . $actionOfficer['email']);
                }

                return $result;
            } else {
                // No action officer assigned, send email to supervisor
                $supervisor = $this->userModel->find($proposal['supervisor_id']);
                if (!$supervisor || empty($supervisor['email'])) {
                    log_message('error', 'Cannot send proposal creation notification: Supervisor not found or no email available');
                    return false;
                }

                // Prepare email subject and message for supervisor
                $subject = 'New Activity Proposal Created Without Action Officer: ' . $activity['title'];

                // Set header color
                $headerColor = '#FFC107'; // Yellow/amber for warning

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
                        .highlight { background-color: #f5f5f5; padding: 15px; border-left: 4px solid #FFC107; margin: 20px 0; }
                        .status { font-weight: bold; color: #FFC107; }
                        h1 { margin-top: 0; font-size: 24px; }
                        .warning { color: #856404; background-color: #fff3cd; padding: 10px; border-radius: 4px; margin: 15px 0; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h1>' . $subject . '</h1>
                        </div>
                        <div class="content">
                            <p>Dear ' . $supervisor['fname'] . ' ' . $supervisor['lname'] . ',</p>

                            <div class="warning">
                                <strong>Note:</strong> This proposal was created without assigning an action officer.
                            </div>

                            <div class="highlight">
                                <p><strong>Proposal Details:</strong></p>
                                <p>Activity: ' . $activity['title'] . '</p>
                                <p>Workplan: ' . $workplan['title'] . '</p>
                                <p>Location: ' . $proposal['location'] . '</p>
                                <p>Period: ' . $startDate . ' to ' . $endDate . '</p>
                                <p>Status: <span class="status">' . ucfirst($proposal['status']) . '</span></p>
                            </div>

                            <p>Please assign an action officer to this proposal by editing it in the system.</p>

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
                $result = send_email($supervisor['email'], $subject, $message);

                if (!$result) {
                    log_message('error', 'Failed to send proposal creation notification email to supervisor: ' . $supervisor['email']);
                } else {
                    log_message('info', 'Proposal creation notification email sent successfully to supervisor: ' . $supervisor['email']);
                }

                return $result;
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception sending proposal creation notification email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email notification when a proposal is updated
     *
     * @param int $proposalId ID of the updated proposal
     * @param array $proposalData Updated proposal data
     * @return bool Success or failure
     */
    protected function sendProposalUpdateNotification($proposalId, $proposalData)
    {
        try {
            // Get the complete proposal data
            $proposal = $this->proposalModel->getProposalWithDetails($proposalId);
            if (!$proposal) {
                log_message('error', 'Cannot send proposal update notification: Proposal not found');
                return false;
            }

            // Get the activity data
            $activity = $this->workplanActivityModel->find($proposal['activity_id']);
            if (!$activity) {
                log_message('error', 'Cannot send proposal update notification: Activity not found');
                return false;
            }

            // Get the workplan data
            $workplan = $this->workplanModel->find($proposal['workplan_id']);
            if (!$workplan) {
                log_message('error', 'Cannot send proposal update notification: Workplan not found');
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

            // Format dates for display
            $startDate = date('d M Y', strtotime($proposal['date_start']));
            $endDate = date('d M Y', strtotime($proposal['date_end']));

            // Determine recipient - action officer if assigned, otherwise supervisor
            $recipient = null;
            $recipientType = '';

            if (!empty($proposal['action_officer_id'])) {
                $recipient = $this->userModel->find($proposal['action_officer_id']);
                $recipientType = 'action officer';
            } else {
                $recipient = $this->userModel->find($proposal['supervisor_id']);
                $recipientType = 'supervisor';
            }

            if (!$recipient || empty($recipient['email'])) {
                log_message('error', 'Cannot send proposal update notification: Recipient not found or no email available');
                return false;
            }

            // Prepare email subject and message
            $subject = 'Activity Proposal Updated: ' . $activity['title'];

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
                        <p>Dear ' . $recipient['fname'] . ' ' . $recipient['lname'] . ',</p>

                        <p>An activity proposal for which you are the ' . $recipientType . ' has been updated.</p>

                        <div class="highlight">
                            <p><strong>Proposal Details:</strong></p>
                            <p>Activity: ' . $activity['title'] . '</p>
                            <p>Workplan: ' . $workplan['title'] . '</p>
                            <p>Location: ' . $proposal['location'] . '</p>
                            <p>Period: ' . $startDate . ' to ' . $endDate . '</p>
                            <p>Status: <span class="status">' . ucfirst($proposal['status']) . '</span></p>
                        </div>

                        <p>This proposal was updated by: <strong>' . $updaterName . '</strong> (' . $updaterEmail . ')</p>

                        <p>You can view the complete proposal details by logging into the system.</p>

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
            $result = send_email($recipient['email'], $subject, $message);

            if (!$result) {
                log_message('error', 'Failed to send proposal update notification email to ' . $recipientType . ': ' . $recipient['email']);
            } else {
                log_message('info', 'Proposal update notification email sent successfully to ' . $recipientType . ': ' . $recipient['email']);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception sending proposal update notification email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email notification when a proposal is approved
     *
     * @param int $proposalId ID of the approved proposal
     * @param string $remarks Approval remarks
     * @return bool Success or failure
     */
    protected function sendProposalApprovalNotification($proposalId, $remarks)
    {
        try {
            // Get the complete proposal data
            $proposal = $this->proposalModel->getProposalWithDetails($proposalId);
            if (!$proposal) {
                log_message('error', 'Cannot send proposal approval notification: Proposal not found');
                return false;
            }

            // Check if there's an action officer assigned
            if (empty($proposal['action_officer_id'])) {
                log_message('error', 'Cannot send proposal approval notification: No action officer assigned');
                return false;
            }

            // Get the action officer's information
            $actionOfficer = $this->userModel->find($proposal['action_officer_id']);
            if (!$actionOfficer || empty($actionOfficer['email'])) {
                log_message('error', 'Cannot send proposal approval notification: Action officer not found or no email available');
                return false;
            }

            // Get the activity data
            $activity = $this->workplanActivityModel->find($proposal['activity_id']);
            if (!$activity) {
                log_message('error', 'Cannot send proposal approval notification: Activity not found');
                return false;
            }

            // Get the workplan data
            $workplan = $this->workplanModel->find($proposal['workplan_id']);
            if (!$workplan) {
                log_message('error', 'Cannot send proposal approval notification: Workplan not found');
                return false;
            }

            // Get the supervisor's information
            $supervisorName = 'System User';
            $supervisorId = session()->get('user_id');
            if ($supervisorId) {
                $supervisor = $this->userModel->find($supervisorId);
                if ($supervisor) {
                    $supervisorName = $supervisor['fname'] . ' ' . $supervisor['lname'];
                }
            }

            // Get supervisor's email
            $supervisorEmail = session()->get('email') ?? 'noreply@dakoiims.com';

            // Format dates for display
            $startDate = date('d M Y', strtotime($proposal['date_start']));
            $endDate = date('d M Y', strtotime($proposal['date_end']));

            // Prepare email subject and message
            $subject = 'Activity Proposal Approved: ' . $activity['title'];

            // Set header color
            $headerColor = '#4CAF50'; // Green for approval

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
                    .remarks { background-color: #e8f5e9; padding: 10px; border-radius: 4px; margin: 15px 0; }
                    h1 { margin-top: 0; font-size: 24px; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1>' . $subject . '</h1>
                    </div>
                    <div class="content">
                        <p>Dear ' . $actionOfficer['fname'] . ' ' . $actionOfficer['lname'] . ',</p>

                        <p>Your activity proposal has been <span class="status">approved</span> by the supervisor.</p>

                        <div class="highlight">
                            <p><strong>Proposal Details:</strong></p>
                            <p>Activity: ' . $activity['title'] . '</p>
                            <p>Workplan: ' . $workplan['title'] . '</p>
                            <p>Location: ' . $proposal['location'] . '</p>
                            <p>Period: ' . $startDate . ' to ' . $endDate . '</p>
                            <p>Status: <span class="status">Approved</span></p>
                        </div>

                        ' . (!empty($remarks) ? '<div class="remarks"><strong>Supervisor Remarks:</strong><br>' . nl2br(esc($remarks)) . '</div>' : '') . '

                        <p>This proposal was approved by: <strong>' . $supervisorName . '</strong> (' . $supervisorEmail . ')</p>

                        <p>You can now proceed with implementing this activity. Please log into the system to view the complete details.</p>

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
            $result = send_email($actionOfficer['email'], $subject, $message);

            if (!$result) {
                log_message('error', 'Failed to send proposal approval notification email to action officer: ' . $actionOfficer['email']);
            } else {
                log_message('info', 'Proposal approval notification email sent successfully to action officer: ' . $actionOfficer['email']);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception sending proposal approval notification email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email notification when a proposal is resent for revision
     *
     * @param int $proposalId ID of the resent proposal
     * @param string $remarks Resend remarks
     * @return bool Success or failure
     */
    protected function sendProposalResendNotification($proposalId, $remarks)
    {
        try {
            // Get the complete proposal data
            $proposal = $this->proposalModel->getProposalWithDetails($proposalId);
            if (!$proposal) {
                log_message('error', 'Cannot send proposal resend notification: Proposal not found');
                return false;
            }

            // Check if there's an action officer assigned
            if (empty($proposal['action_officer_id'])) {
                log_message('error', 'Cannot send proposal resend notification: No action officer assigned');
                return false;
            }

            // Get the action officer's information
            $actionOfficer = $this->userModel->find($proposal['action_officer_id']);
            if (!$actionOfficer || empty($actionOfficer['email'])) {
                log_message('error', 'Cannot send proposal resend notification: Action officer not found or no email available');
                return false;
            }

            // Get the activity data
            $activity = $this->workplanActivityModel->find($proposal['activity_id']);
            if (!$activity) {
                log_message('error', 'Cannot send proposal resend notification: Activity not found');
                return false;
            }

            // Get the workplan data
            $workplan = $this->workplanModel->find($proposal['workplan_id']);
            if (!$workplan) {
                log_message('error', 'Cannot send proposal resend notification: Workplan not found');
                return false;
            }

            // Get the supervisor's information
            $supervisorName = 'System User';
            $supervisorId = session()->get('user_id');
            if ($supervisorId) {
                $supervisor = $this->userModel->find($supervisorId);
                if ($supervisor) {
                    $supervisorName = $supervisor['fname'] . ' ' . $supervisor['lname'];
                }
            }

            // Get supervisor's email
            $supervisorEmail = session()->get('email') ?? 'noreply@dakoiims.com';

            // Format dates for display
            $startDate = date('d M Y', strtotime($proposal['date_start']));
            $endDate = date('d M Y', strtotime($proposal['date_end']));

            // Prepare email subject and message
            $subject = 'Activity Proposal Needs Revision: ' . $activity['title'];

            // Set header color
            $headerColor = '#FFC107'; // Yellow/amber for warning

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
                    .highlight { background-color: #f5f5f5; padding: 15px; border-left: 4px solid #FFC107; margin: 20px 0; }
                    .status { font-weight: bold; color: #FFC107; }
                    .remarks { background-color: #fff8e1; padding: 10px; border-radius: 4px; margin: 15px 0; }
                    h1 { margin-top: 0; font-size: 24px; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1>' . $subject . '</h1>
                    </div>
                    <div class="content">
                        <p>Dear ' . $actionOfficer['fname'] . ' ' . $actionOfficer['lname'] . ',</p>

                        <p>Your activity proposal has been <span class="status">returned for revision</span> by the supervisor.</p>

                        <div class="highlight">
                            <p><strong>Proposal Details:</strong></p>
                            <p>Activity: ' . $activity['title'] . '</p>
                            <p>Workplan: ' . $workplan['title'] . '</p>
                            <p>Location: ' . $proposal['location'] . '</p>
                            <p>Period: ' . $startDate . ' to ' . $endDate . '</p>
                            <p>Status: <span class="status">Pending (Requires Revision)</span></p>
                        </div>

                        ' . (!empty($remarks) ? '<div class="remarks"><strong>Supervisor Remarks:</strong><br>' . nl2br(esc($remarks)) . '</div>' : '') . '

                        <p>This proposal was returned by: <strong>' . $supervisorName . '</strong> (' . $supervisorEmail . ')</p>

                        <p>Please log into the system to make the necessary revisions to your proposal and resubmit it for approval.</p>

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
            $result = send_email($actionOfficer['email'], $subject, $message);

            if (!$result) {
                log_message('error', 'Failed to send proposal resend notification email to action officer: ' . $actionOfficer['email']);
            } else {
                log_message('info', 'Proposal resend notification email sent successfully to action officer: ' . $actionOfficer['email']);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception sending proposal resend notification email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email notification when a proposal is rated by M&E
     *
     * @param int $proposalId ID of the rated proposal
     * @param float $score Rating score
     * @param string $remarks Rating remarks
     * @return bool Success or failure
     */
    protected function sendRatingNotificationEmail($proposalId, $score, $remarks = '')
    {
        try {
            // Get the complete proposal data
            $proposal = $this->proposalModel->getProposalWithDetails($proposalId);
            if (!$proposal) {
                log_message('error', 'Cannot send rating notification: Proposal not found');
                return false;
            }

            // Check if there's an action officer assigned
            if (empty($proposal['action_officer_id'])) {
                log_message('error', 'Cannot send rating notification: No action officer assigned');
                return false;
            }

            // Get action officer data
            $actionOfficer = $this->userModel->find($proposal['action_officer_id']);
            if (!$actionOfficer || empty($actionOfficer['email'])) {
                log_message('error', 'Cannot send rating notification: Action officer not found or no email available');
                return false;
            }

            // Get evaluator data
            $evaluatorName = 'M&E Evaluator';
            $evaluatorEmail = 'noreply@dakoiims.com';
            $evaluatorId = session()->get('user_id');
            if ($evaluatorId) {
                $evaluator = $this->userModel->find($evaluatorId);
                if ($evaluator) {
                    $evaluatorName = $evaluator['fname'] . ' ' . $evaluator['lname'];
                    $evaluatorEmail = $evaluator['email'] ?? 'noreply@dakoiims.com';
                }
            }

            // Get activity data
            $activity = $this->workplanActivityModel->find($proposal['activity_id']);
            if (!$activity) {
                log_message('error', 'Cannot send rating notification: Activity not found');
                return false;
            }

            // Get the workplan data
            $workplan = $this->workplanModel->find($proposal['workplan_id']);
            if (!$workplan) {
                log_message('error', 'Cannot send rating notification: Workplan not found');
                return false;
            }

            // Convert score to text representation
            $ratingText = '';
            if ($score <= 1) {
                $ratingText = 'Poor';
            } elseif ($score <= 2) {
                $ratingText = 'Fair';
            } elseif ($score <= 3) {
                $ratingText = 'Good';
            } elseif ($score <= 4) {
                $ratingText = 'Very Good';
            } else {
                $ratingText = 'Excellent';
            }

            // Format dates for display
            $startDate = date('d M Y', strtotime($proposal['date_start']));
            $endDate = date('d M Y', strtotime($proposal['date_end']));

            // Prepare email subject and message
            $subject = 'Your Activity Has Been Rated: ' . $activity['title'];

            // Set header color
            $headerColor = '#4CAF50'; // Green for positive notification

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
                    .rating { background-color: #e8f5e9; padding: 15px; border-left: 4px solid #FFC107; margin: 20px 0; }
                    .stars { color: #FFC107; font-size: 24px; }
                    .remarks { background-color: #e8f5e9; padding: 10px; border-radius: 4px; margin: 15px 0; }
                    h1 { margin-top: 0; font-size: 24px; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1>Activity Rating Notification</h1>
                    </div>
                    <div class="content">
                        <p>Dear ' . $actionOfficer['fname'] . ' ' . $actionOfficer['lname'] . ',</p>

                        <p>Your activity has been rated by the Monitoring & Evaluation team:</p>

                        <div class="highlight">
                            <p><strong>Activity Details:</strong></p>
                            <p>Activity: ' . $activity['title'] . '</p>
                            <p>Workplan: ' . $workplan['title'] . '</p>
                            <p>Type: ' . ucfirst($activity['activity_type']) . '</p>
                            <p>Location: ' . $proposal['location'] . '</p>
                            <p>Period: ' . $startDate . ' to ' . $endDate . '</p>
                        </div>

                        <div class="rating">
                            <p><strong>Rating:</strong> ' . $ratingText . ' (' . number_format($score, 1) . '/5.0)</p>
                            <p class="stars">';

            // Add star icons based on rating
            $fullStars = floor($score);
            $halfStar = $score - $fullStars >= 0.5;
            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

            for ($i = 0; $i < $fullStars; $i++) {
                $message .= '★';
            }

            if ($halfStar) {
                $message .= '½';
            }

            for ($i = 0; $i < $emptyStars; $i++) {
                $message .= '☆';
            }

            $message .= '</p>
                        </div>

                        ' . (!empty($remarks) ? '<div class="remarks"><strong>Evaluator Remarks:</strong><br>' . nl2br(esc($remarks)) . '</div>' : '') . '

                        <p>This activity was rated by: <strong>' . $evaluatorName . '</strong> (' . $evaluatorEmail . ')</p>

                        <p>You can view the complete rating details by logging into the system.</p>

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
            $result = send_email($actionOfficer['email'], $subject, $message);

            if (!$result) {
                log_message('error', 'Failed to send rating notification email to action officer: ' . $actionOfficer['email']);
            } else {
                log_message('info', 'Rating notification email sent successfully to action officer: ' . $actionOfficer['email']);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception sending rating notification email: ' . $e->getMessage());
            return false;
        }
    }
}
