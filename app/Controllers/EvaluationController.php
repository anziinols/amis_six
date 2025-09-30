<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\WorkplanModel;
use App\Models\WorkplanActivityModel;
use App\Models\MyActivitiesWorkplanActivitiesModel;
use App\Models\ActivitiesModel;

/**
 * EvaluationController
 * 
 * Handles evaluation features for M&E evaluators to access and rate workplan activities
 */
class EvaluationController extends BaseController
{
    protected $workplanModel;
    protected $workplanActivityModel;
    protected $myActivitiesWorkplanModel;
    protected $activitiesModel;

    public function __construct()
    {
        $this->workplanModel = new WorkplanModel();
        $this->workplanActivityModel = new WorkplanActivityModel();
        $this->myActivitiesWorkplanModel = new MyActivitiesWorkplanActivitiesModel();
        $this->activitiesModel = new ActivitiesModel();

        // Check if user has evaluator access
        $this->checkEvaluatorAccess();
    }

    /**
     * Check if user has evaluator access
     * Accessible to users with admin capability OR users with is_evaluator = 1
     */
    private function checkEvaluatorAccess()
    {
        $isAdmin = session()->get('is_admin');
        $isEvaluator = session()->get('is_evaluator');

        if ($isAdmin != 1 && $isEvaluator != 1) {
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Access denied. Evaluation module requires admin capability or evaluator privileges.');
        }
    }

    /**
     * Display list of all workplans for evaluation
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

        // Get all workplans with details
        $workplans = $this->workplanModel
            ->select('workplans.*, 
                     branches.name as branch_name,
                     CONCAT(u.fname, " ", u.lname) as created_by_name')
            ->join('branches', 'branches.id = workplans.branch_id', 'left')
            ->join('users u', 'u.id = workplans.created_by', 'left')
            ->orderBy('workplans.created_at', 'DESC')
            ->findAll();

        // Add activity count for each workplan
        foreach ($workplans as &$workplan) {
            $workplan['activities_count'] = $this->workplanModel->countActivities($workplan['id']);
        }

        $data = [
            'title' => 'Evaluation - Workplans',
            'workplans' => $workplans
        ];

        return view('evaluation/evaluation_index', $data);
    }

    /**
     * Display workplan activities for a specific workplan
     * 
     * @param int|null $workplanId
     * @return mixed
     */
    public function workplanActivities($workplanId = null)
    {
        if ($workplanId === null) {
            return redirect()->to(base_url('evaluation'))
                ->with('error', 'Workplan ID is required');
        }

        // Get current user ID from session
        $currentUserId = session()->get('user_id');

        if (!$currentUserId) {
            return redirect()->to(base_url('login'))->with('error', 'Please login to continue');
        }

        // Get the workplan details
        $workplan = $this->workplanModel
            ->select('workplans.*, branches.name as branch_name')
            ->join('branches', 'branches.id = workplans.branch_id', 'left')
            ->find($workplanId);

        if (!$workplan) {
            return redirect()->to(base_url('evaluation'))
                ->with('error', 'Workplan not found');
        }

        // Get all activities for this workplan with details
        $activities = $this->workplanActivityModel
            ->select('workplan_activities.*, 
                     workplans.title as workplan_title,
                     branches.name as branch_name,
                     CONCAT(supervisors.fname, " ", supervisors.lname) as supervisor_name,
                     CONCAT(raters.fname, " ", raters.lname) as rated_by_name')
            ->join('workplans', 'workplans.id = workplan_activities.workplan_id', 'left')
            ->join('branches', 'branches.id = workplan_activities.branch_id', 'left')
            ->join('users as supervisors', 'supervisors.id = workplan_activities.supervisor_id', 'left')
            ->join('users as raters', 'raters.id = workplan_activities.rated_by', 'left')
            ->where('workplan_activities.workplan_id', $workplanId)
            ->orderBy('workplan_activities.activity_code', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Evaluation - Workplan Activities: ' . $workplan['title'],
            'workplan' => $workplan,
            'activities' => $activities
        ];

        return view('evaluation/evaluation_workplan_activities', $data);
    }

    /**
     * View outputs/activities linked to a workplan activity
     * 
     * @param int|null $activityId
     * @return mixed
     */
    public function viewOutputs($activityId = null)
    {
        if ($activityId === null) {
            return redirect()->to(base_url('evaluation'))
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
                     workplans.id as workplan_id,
                     branches.name as branch_name,
                     CONCAT(raters.fname, " ", raters.lname) as rated_by_name')
            ->join('workplans', 'workplans.id = workplan_activities.workplan_id', 'left')
            ->join('branches', 'branches.id = workplan_activities.branch_id', 'left')
            ->join('users as raters', 'raters.id = workplan_activities.rated_by', 'left')
            ->find($activityId);

        if (!$workplanActivity) {
            return redirect()->to(base_url('evaluation'))
                ->with('error', 'Workplan activity not found');
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
            ->where('myactivities_workplan_activities.workplan_activities_id', $activityId)
            ->findAll();

        $data = [
            'title' => 'Evaluation - Linked Activities: ' . $workplanActivity['title'],
            'workplanActivity' => $workplanActivity,
            'linkedActivities' => $linkedActivities
        ];

        return view('evaluation/evaluation_outputs', $data);
    }

    /**
     * Rate a workplan activity
     * 
     * @param int|null $activityId
     * @return mixed
     */
    public function rateActivity($activityId = null)
    {
        if ($activityId === null) {
            return redirect()->to(base_url('evaluation'))
                ->with('error', 'Activity ID is required');
        }

        // Get current user ID from session
        $currentUserId = session()->get('user_id');

        if (!$currentUserId) {
            return redirect()->to(base_url('login'))->with('error', 'Please login to continue');
        }

        // Get the activity
        $activity = $this->workplanActivityModel->find($activityId);

        if (!$activity) {
            return redirect()->to(base_url('evaluation'))
                ->with('error', 'Activity not found');
        }

        // Get rating and remarks from POST data
        $rating = $this->request->getPost('rating');
        $remarks = $this->request->getPost('rating_remarks');

        // Validate rating
        if ($rating === null || $rating === '') {
            return redirect()->back()
                ->with('error', 'Rating is required');
        }

        // Prepare data for update
        $updateData = [
            'id' => $activityId,
            'rating' => $rating,
            'reated_remarks' => $remarks ?? '', // Note: field name has typo in database
            'rated_by' => $currentUserId,
            'rated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $currentUserId
        ];

        // Update the activity
        if ($this->workplanActivityModel->save($updateData)) {
            // Redirect back to the outputs page
            return redirect()->to(base_url('evaluation/workplan-activity/' . $activityId . '/outputs'))
                ->with('success', 'Activity rated successfully');
        } else {
            // Get validation errors if any
            $errors = $this->workplanActivityModel->errors();
            $errorMessage = 'Failed to rate activity';

            if (!empty($errors)) {
                $errorMessage .= ': ' . implode(', ', $errors);
            }

            return redirect()->back()
                ->with('error', $errorMessage);
        }
    }
}

