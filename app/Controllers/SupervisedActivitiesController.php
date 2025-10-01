<?php

namespace App\Controllers;

use App\Models\WorkplanActivityModel;
use App\Models\MyActivitiesWorkplanActivitiesModel;
use App\Models\ActivitiesModel;
use CodeIgniter\Controller;

/**
 * SupervisedActivitiesController
 *
 * Handles supervised activities for supervisor users
 * Uses WorkplanActivityModel to fetch workplan activities assigned to supervisors
 */
class SupervisedActivitiesController extends Controller
{
    protected $workplanActivityModel;
    protected $myActivitiesWorkplanActivitiesModel;
    protected $activitiesModel;

    public function __construct()
    {
        $this->workplanActivityModel = new WorkplanActivityModel();
        $this->myActivitiesWorkplanActivitiesModel = new MyActivitiesWorkplanActivitiesModel();
        $this->activitiesModel = new ActivitiesModel();
    }

    /**
     * Display list of supervised activities assigned to the current supervisor
     * For admin users, display all supervised activities from all organizations
     * Sorted by: 3 upcoming activities (date_start >= today) in ASC order, then past activities in DESC order
     *
     * @return mixed
     */
    public function index()
    {
        // Get current user ID and admin status from session
        $currentUserId = session()->get('user_id');
        $isAdmin = session()->get('is_admin');

        if (!$currentUserId) {
            return redirect()->to(base_url('login'))->with('error', 'Please login to continue');
        }

        // Get workplan activities assigned to this supervisor
        if ($isAdmin == 1) {
            // Admin users: see all workplan activities
            $activities = $this->workplanActivityModel->getActivitiesWithDetails();
            $title = 'All Workplan Activities';
        } else {
            // Regular users: only see workplan activities they supervise
            $activities = $this->workplanActivityModel
                ->select('workplan_activities.*,
                         workplans.title as workplan_title,
                         branches.name as branch_name,
                         CONCAT(supervisors.fname, " ", supervisors.lname) as supervisor_name')
                ->join('workplans', 'workplans.id = workplan_activities.workplan_id', 'left')
                ->join('branches', 'branches.id = workplan_activities.branch_id', 'left')
                ->join('users as supervisors', 'supervisors.id = workplan_activities.supervisor_id', 'left')
                ->where('workplan_activities.supervisor_id', $currentUserId)
                ->orderBy('workplan_activities.activity_code', 'ASC')
                ->findAll();
            $title = 'My Supervised Workplan Activities';
        }

        $data = [
            'title' => $title,
            'activities' => $activities,
            'isAdmin' => ($isAdmin == 1)
        ];

        return view('supervised_activities/supervised_activities_index', $data);
    }

    /**
     * Mark an activity as approved (supervised activities use 'approved' status)
     * Admin users can mark any activity as approved, regular users only their supervised activities
     *
     * @param int|null $id
     * @return mixed
     */
    public function markComplete($id = null)
    {
        if ($id === null) {
            return redirect()->to(base_url('supervised-activities'))
                ->with('error', 'Activity ID is required');
        }

        // Get current user ID and admin status from session
        $currentUserId = session()->get('user_id');
        $isAdmin = session()->get('is_admin');

        if (!$currentUserId) {
            return redirect()->to(base_url('login'))->with('error', 'Please login to continue');
        }

        // Get the workplan activity
        $activity = $this->workplanActivityModel->find($id);

        if (!$activity) {
            return redirect()->to(base_url('supervised-activities'))
                ->with('error', 'Workplan activity not found');
        }

        // Verify authorization: admin users can update all, regular users only their supervised activities
        if ($isAdmin != 1 && $activity['supervisor_id'] != $currentUserId) {
            return redirect()->to(base_url('supervised-activities'))
                ->with('error', 'You are not authorized to update this activity');
        }

        // Check if already completed
        if ($activity['status'] === 'completed') {
            return redirect()->to(base_url('supervised-activities'))
                ->with('info', 'This activity is already completed');
        }

        // Get optional remarks from POST data
        $remarks = $this->request->getPost('status_remarks');

        // Update the workplan activity status
        $data = [
            'status' => 'completed',
            'status_by' => $currentUserId,
            'status_at' => date('Y-m-d H:i:s'),
            'status_remarks' => $remarks ?? 'Activity marked as completed by supervisor',
            'updated_by' => $currentUserId
        ];

        if ($this->workplanActivityModel->update($id, $data)) {
            return redirect()->to(base_url('supervised-activities'))
                ->with('success', 'Workplan activity marked as completed successfully');
        } else {
            return redirect()->to(base_url('supervised-activities'))
                ->with('error', 'Failed to update activity status');
        }
    }

    /**
     * View implementation details of a supervised activity
     * Admin users can view any activity, regular users only their supervised activities
     *
     * @param int|null $id
     * @return mixed
     */
    public function viewOutputs($id = null)
    {
        if ($id === null) {
            return redirect()->to(base_url('supervised-activities'))
                ->with('error', 'Activity ID is required');
        }

        // Get current user ID and admin status from session
        $currentUserId = session()->get('user_id');
        $isAdmin = session()->get('is_admin');

        if (!$currentUserId) {
            return redirect()->to(base_url('login'))->with('error', 'Please login to continue');
        }

        // Get the workplan activity with details
        $activity = $this->workplanActivityModel
            ->select('workplan_activities.*,
                     workplans.title as workplan_title,
                     branches.name as branch_name,
                     CONCAT(supervisors.fname, " ", supervisors.lname) as supervisor_name,
                     CONCAT(status_by_user.fname, " ", status_by_user.lname) as status_by_name,
                     CONCAT(rated_by_user.fname, " ", rated_by_user.lname) as rated_by_name')
            ->join('workplans', 'workplans.id = workplan_activities.workplan_id', 'left')
            ->join('branches', 'branches.id = workplan_activities.branch_id', 'left')
            ->join('users as supervisors', 'supervisors.id = workplan_activities.supervisor_id', 'left')
            ->join('users as status_by_user', 'status_by_user.id = workplan_activities.status_by', 'left')
            ->join('users as rated_by_user', 'rated_by_user.id = workplan_activities.rated_by', 'left')
            ->where('workplan_activities.id', $id)
            ->first();

        if (!$activity) {
            return redirect()->to(base_url('supervised-activities'))
                ->with('error', 'Workplan activity not found');
        }

        // Verify authorization: admin users can view all, regular users only their supervised activities
        if ($isAdmin != 1 && $activity['supervisor_id'] != $currentUserId) {
            return redirect()->to(base_url('supervised-activities'))
                ->with('error', 'You are not authorized to view this activity');
        }

        // Get all linked activities (outputs) for this workplan activity
        $linkedActivities = $this->myActivitiesWorkplanActivitiesModel
            ->select('myactivities_workplan_activities.*,
                     activities.id as activity_id,
                     activities.activity_title,
                     activities.activity_description,
                     activities.type,
                     activities.location,
                     activities.date_start,
                     activities.date_end,
                     activities.total_cost,
                     activities.status,
                     activities.status_at,
                     activities.status_remarks,
                     CONCAT(action_officer.fname, " ", action_officer.lname) as action_officer_name,
                     CONCAT(status_by_user.fname, " ", status_by_user.lname) as status_by_name,
                     province.name as province_name,
                     district.name as district_name')
            ->join('activities', 'activities.id = myactivities_workplan_activities.my_activities_id', 'left')
            ->join('users as action_officer', 'action_officer.id = activities.action_officer_id', 'left')
            ->join('users as status_by_user', 'status_by_user.id = activities.status_by', 'left')
            ->join('gov_structure as province', 'province.id = activities.province_id AND province.level = "province"', 'left')
            ->join('gov_structure as district', 'district.id = activities.district_id AND district.level = "district"', 'left')
            ->where('myactivities_workplan_activities.workplan_activities_id', $id)
            ->orderBy('activities.date_start', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Linked Activities (Outputs)',
            'workplanActivity' => $activity,
            'linkedActivities' => $linkedActivities
        ];

        return view('supervised_activities/supervised_activities_outputs', $data);
    }
}

