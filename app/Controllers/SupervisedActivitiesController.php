<?php

namespace App\Controllers;

use App\Models\WorkplanActivityModel;
use App\Models\WorkplanModel;
use App\Models\MyActivitiesWorkplanActivitiesModel;
use App\Models\ActivitiesModel;
use CodeIgniter\Controller;

/**
 * SupervisedActivitiesController
 * 
 * Handles supervised activities for supervisor users
 */
class SupervisedActivitiesController extends Controller
{
    protected $workplanActivityModel;
    protected $workplanModel;
    protected $myActivitiesWorkplanModel;
    protected $activitiesModel;

    public function __construct()
    {
        $this->workplanActivityModel = new WorkplanActivityModel();
        $this->workplanModel = new WorkplanModel();
        $this->myActivitiesWorkplanModel = new MyActivitiesWorkplanActivitiesModel();
        $this->activitiesModel = new ActivitiesModel();
    }

    /**
     * Display list of supervised activities assigned to the current supervisor
     * 
     * @return mixed
     */
    public function index()
    {
        // Get current user ID from session
        $currentUserId = session()->get('user_id');

        if (!$currentUserId) {
            return redirect()->to(base_url('login'))->with('error', 'Please login to continue');
        }

        // Get activities assigned to this supervisor with related details
        $activities = $this->workplanActivityModel
            ->select('workplan_activities.*, 
                     workplans.title as workplan_title,
                     workplans.start_date as workplan_start_date,
                     workplans.end_date as workplan_end_date,
                     branches.name as branch_name')
            ->join('workplans', 'workplans.id = workplan_activities.workplan_id', 'left')
            ->join('branches', 'branches.id = workplan_activities.branch_id', 'left')
            ->where('workplan_activities.supervisor_id', $currentUserId)
            ->orderBy('workplan_activities.created_at', 'DESC')
            ->findAll();

        $data = [
            'title' => 'My Supervised Activities',
            'activities' => $activities
        ];

        return view('supervised_activities/supervised_activities_index', $data);
    }

    /**
     * Mark an activity as complete
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

        // Get current user ID from session
        $currentUserId = session()->get('user_id');

        if (!$currentUserId) {
            return redirect()->to(base_url('login'))->with('error', 'Please login to continue');
        }

        // Get the activity
        $activity = $this->workplanActivityModel->find($id);

        if (!$activity) {
            return redirect()->to(base_url('supervised-activities'))
                ->with('error', 'Activity not found');
        }

        // Verify that the current user is the supervisor of this activity
        if ($activity['supervisor_id'] != $currentUserId) {
            return redirect()->to(base_url('supervised-activities'))
                ->with('error', 'You are not authorized to update this activity');
        }

        // Check if already completed
        if ($activity['status'] === 'complete') {
            return redirect()->to(base_url('supervised-activities'))
                ->with('info', 'This activity is already marked as complete');
        }

        // Get optional remarks from POST data
        $remarks = $this->request->getPost('status_remarks');

        // Prepare data for update
        $updateData = [
            'id' => $id,
            'status' => 'complete',
            'status_by' => $currentUserId,
            'status_at' => date('Y-m-d H:i:s'),
            'status_remarks' => $remarks ?? 'Marked as complete by supervisor',
            'updated_by' => $currentUserId
        ];

        // Update the activity
        if ($this->workplanActivityModel->save($updateData)) {
            return redirect()->to(base_url('supervised-activities'))
                ->with('success', 'Activity marked as complete successfully');
        } else {
            return redirect()->to(base_url('supervised-activities'))
                ->with('error', 'Failed to update activity status');
        }
    }

    /**
     * View outputs/activities linked to a supervised activity
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

        // Get current user ID from session
        $currentUserId = session()->get('user_id');

        if (!$currentUserId) {
            return redirect()->to(base_url('login'))->with('error', 'Please login to continue');
        }

        // Get the workplan activity with details
        $workplanActivity = $this->workplanActivityModel
            ->select('workplan_activities.*, 
                     workplans.title as workplan_title,
                     branches.name as branch_name')
            ->join('workplans', 'workplans.id = workplan_activities.workplan_id', 'left')
            ->join('branches', 'branches.id = workplan_activities.branch_id', 'left')
            ->find($id);

        if (!$workplanActivity) {
            return redirect()->to(base_url('supervised-activities'))
                ->with('error', 'Supervised activity not found');
        }

        // Verify that the current user is the supervisor of this activity
        if ($workplanActivity['supervisor_id'] != $currentUserId) {
            return redirect()->to(base_url('supervised-activities'))
                ->with('error', 'You are not authorized to view this activity');
        }

        // Get all activities linked to this workplan activity
        $linkedActivities = $this->myActivitiesWorkplanModel
            ->select('myactivities_workplan_activities.*, 
                     activities.id as activity_id,
                     activities.activity_title,
                     activities.activity_description,
                     activities.type,
                     activities.date_start,
                     activities.date_end,
                     activities.location,
                     activities.total_cost,
                     activities.status,
                     activities.status_at,
                     p.name as province_name,
                     d.name as district_name,
                     CONCAT(ao.fname, " ", ao.lname) as action_officer_name')
            ->join('activities', 'activities.id = myactivities_workplan_activities.my_activities_id', 'left')
            ->join('gov_structure p', 'activities.province_id = p.id AND p.level = "province"', 'left')
            ->join('gov_structure d', 'activities.district_id = d.id AND d.level = "district"', 'left')
            ->join('users ao', 'activities.action_officer_id = ao.id', 'left')
            ->where('myactivities_workplan_activities.workplan_activities_id', $id)
            ->findAll();

        $data = [
            'title' => 'Linked Activities - ' . $workplanActivity['title'],
            'workplanActivity' => $workplanActivity,
            'linkedActivities' => $linkedActivities
        ];

        return view('supervised_activities/supervised_activities_outputs', $data);
    }
}

