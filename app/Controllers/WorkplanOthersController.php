<?php
// app/Controllers/WorkplanOthersController.php

namespace App\Controllers;

use App\Models\WorkplanOthersLinkModel;
use App\Models\WorkplanActivityModel;
use App\Models\WorkplanModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

/**
 * WorkplanOthersController
 * 
 * Handles CRUD operations for Others links (activities outside formal planning frameworks)
 * Uses RESTful approach with separate GET and POST methods
 */
class WorkplanOthersController extends ResourceController
{
    protected $workplanOthersLinkModel;
    protected $workplanActivityModel;
    protected $workplanModel;
    protected $userModel;

    public function __construct()
    {
        $this->workplanOthersLinkModel = new WorkplanOthersLinkModel();
        $this->workplanActivityModel = new WorkplanActivityModel();
        $this->workplanModel = new WorkplanModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display Others links for a specific workplan activity
     * GET /workplans/{workplanId}/activities/{activityId}/others
     * 
     * @param int|null $workplanId
     * @param int|null $activityId
     * @return mixed
     */
    public function index($workplanId = null, $activityId = null)
    {
        // Validate workplan and activity
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activity = $this->workplanActivityModel->find($activityId);
        if (!$activity || $activity['workplan_id'] != $workplanId) {
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Activity not found.');
        }

        // Get existing others links for this activity
        $othersLinks = $this->workplanOthersLinkModel->getOthersLinksForActivity($activityId);

        // Get recurrent activities (templates)
        $recurrentActivities = $this->workplanOthersLinkModel->getRecurrentActivities();

        $data = [
            'title' => 'Others Links - ' . $activity['title'],
            'workplan' => $workplan,
            'activity' => $activity,
            'othersLinks' => $othersLinks,
            'recurrentActivities' => $recurrentActivities
        ];

        return view('workplan_others/workplan_others_index', $data);
    }

    /**
     * Show form to create new Others link
     * GET /workplans/{workplanId}/activities/{activityId}/others/new
     * 
     * @param int|null $workplanId
     * @param int|null $activityId
     * @return mixed
     */
    public function new($workplanId = null, $activityId = null)
    {
        // Validate workplan and activity
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activity = $this->workplanActivityModel->find($activityId);
        if (!$activity || $activity['workplan_id'] != $workplanId) {
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Activity not found.');
        }

        $data = [
            'title' => 'Link to Others - ' . $activity['title'],
            'workplan' => $workplan,
            'activity' => $activity
        ];

        return view('workplan_others/workplan_others_create', $data);
    }

    /**
     * Create new Others link
     * POST /workplans/{workplanId}/activities/{activityId}/others
     * 
     * @param int|null $workplanId
     * @param int|null $activityId
     * @return mixed
     */
    public function create($workplanId = null, $activityId = null)
    {
        // Validate workplan and activity
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activity = $this->workplanActivityModel->find($activityId);
        if (!$activity || $activity['workplan_id'] != $workplanId) {
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Activity not found.');
        }

        $userId = session()->get('user_id');

        // Check if linking to a recurrent activity
        $recurrentActivityId = $this->request->getPost('recurrent_activity_id');
        
        if (!empty($recurrentActivityId)) {
            // Link to existing recurrent activity
            $recurrentActivity = $this->workplanOthersLinkModel->find($recurrentActivityId);
            if (!$recurrentActivity) {
                return redirect()->back()->withInput()->with('error', 'Selected recurrent activity not found.');
            }

            $data = [
                'workplan_activity_id' => $activityId,
                'link_type' => $recurrentActivity['link_type'],
                'title' => $recurrentActivity['title'],
                'description' => $recurrentActivity['description'],
                'justification' => $this->request->getPost('justification') ?: $recurrentActivity['justification'],
                'category' => $recurrentActivity['category'],
                'priority_level' => $this->request->getPost('priority_level') ?: $recurrentActivity['priority_level'],
                'expected_outcome' => $recurrentActivity['expected_outcome'],
                'target_beneficiaries' => $recurrentActivity['target_beneficiaries'],
                'budget_estimate' => $this->request->getPost('budget_estimate'),
                'duration_months' => $this->request->getPost('duration_months'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
                'remarks' => $this->request->getPost('remarks'),
                'created_by' => $userId,
                'updated_by' => $userId
            ];
        } else {
            // Create custom others link
            $data = [
                'workplan_activity_id' => $activityId,
                'link_type' => $this->request->getPost('link_type'),
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'justification' => $this->request->getPost('justification'),
                'category' => $this->request->getPost('category'),
                'priority_level' => $this->request->getPost('priority_level'),
                'expected_outcome' => $this->request->getPost('expected_outcome'),
                'target_beneficiaries' => $this->request->getPost('target_beneficiaries'),
                'budget_estimate' => $this->request->getPost('budget_estimate'),
                'duration_months' => $this->request->getPost('duration_months'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date'),
                'remarks' => $this->request->getPost('remarks'),
                'created_by' => $userId,
                'updated_by' => $userId
            ];
        }

        if ($this->workplanOthersLinkModel->save($data)) {
            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/plans')
                           ->with('success', 'Others link created successfully.');
        } else {
            return redirect()->back()->withInput()
                           ->with('error', 'Failed to create others link: ' . implode(', ', $this->workplanOthersLinkModel->errors()));
        }
    }

    /**
     * Show form to edit Others link
     * GET /workplans/{workplanId}/activities/{activityId}/others/{id}/edit
     * 
     * @param int|null $workplanId
     * @param int|null $activityId
     * @param int|null $id
     * @return mixed
     */
    public function edit($workplanId = null, $activityId = null, $id = null)
    {
        // Validate workplan and activity
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activity = $this->workplanActivityModel->find($activityId);
        if (!$activity || $activity['workplan_id'] != $workplanId) {
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Activity not found.');
        }

        $othersLink = $this->workplanOthersLinkModel->find($id);
        if (!$othersLink || $othersLink['workplan_activity_id'] != $activityId) {
            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/others')
                           ->with('error', 'Others link not found.');
        }

        $data = [
            'title' => 'Edit Others Link - ' . $activity['title'],
            'workplan' => $workplan,
            'activity' => $activity,
            'othersLink' => $othersLink
        ];

        return view('workplan_others/workplan_others_edit', $data);
    }

    /**
     * Update Others link
     * POST /workplans/{workplanId}/activities/{activityId}/others/{id}
     * 
     * @param int|null $workplanId
     * @param int|null $activityId
     * @param int|null $id
     * @return mixed
     */
    public function update($workplanId = null, $activityId = null, $id = null)
    {
        // Validate workplan and activity
        $workplan = $this->workplanModel->find($workplanId);
        if (!$workplan) {
            return redirect()->to('/workplans')->with('error', 'Workplan not found.');
        }

        $activity = $this->workplanActivityModel->find($activityId);
        if (!$activity || $activity['workplan_id'] != $workplanId) {
            return redirect()->to('/workplans/' . $workplanId . '/activities')->with('error', 'Activity not found.');
        }

        $othersLink = $this->workplanOthersLinkModel->find($id);
        if (!$othersLink || $othersLink['workplan_activity_id'] != $activityId) {
            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/others')
                           ->with('error', 'Others link not found.');
        }

        $userId = session()->get('user_id');

        $data = [
            'id' => $id,
            'link_type' => $this->request->getPost('link_type'),
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'justification' => $this->request->getPost('justification'),
            'category' => $this->request->getPost('category'),
            'priority_level' => $this->request->getPost('priority_level'),
            'expected_outcome' => $this->request->getPost('expected_outcome'),
            'target_beneficiaries' => $this->request->getPost('target_beneficiaries'),
            'budget_estimate' => $this->request->getPost('budget_estimate'),
            'duration_months' => $this->request->getPost('duration_months'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date' => $this->request->getPost('end_date'),
            'status' => $this->request->getPost('status'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => $userId
        ];

        if ($this->workplanOthersLinkModel->save($data)) {
            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/others/' . $id . '/edit')
                           ->with('success', 'Others link updated successfully.');
        } else {
            return redirect()->back()->withInput()
                           ->with('error', 'Failed to update others link: ' . implode(', ', $this->workplanOthersLinkModel->errors()));
        }
    }

    /**
     * Delete Others link
     * POST /workplans/{workplanId}/activities/{activityId}/others/{id}/delete
     * 
     * @param int|null $workplanId
     * @param int|null $activityId
     * @param int|null $id
     * @return mixed
     */
    public function delete($workplanId = null, $activityId = null, $id = null)
    {
        $othersLink = $this->workplanOthersLinkModel->find($id);
        if (!$othersLink || $othersLink['workplan_activity_id'] != $activityId) {
            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/others')
                           ->with('error', 'Others link not found.');
        }

        if ($this->workplanOthersLinkModel->delete($id)) {
            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/plans')
                           ->with('success', 'Others link deleted successfully.');
        } else {
            return redirect()->to('/workplans/' . $workplanId . '/activities/' . $activityId . '/others')
                           ->with('error', 'Failed to delete others link.');
        }
    }
}
