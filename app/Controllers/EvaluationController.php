<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\WorkplanActivityModel;
use App\Models\WorkplanModel;
use App\Models\BranchesModel;
use App\Models\UserModel;
use App\Models\ProposalModel;
use App\Models\WorkplanTrainingActivityModel;
use App\Models\WorkplanInputActivityModel;
use App\Models\WorkplanInfrastructureActivityModel;
use App\Models\WorkplanOutputActivityModel;
use App\Models\GovStructureModel;

class EvaluationController extends BaseController
{
    protected $workplanActivityModel;
    protected $workplanModel;
    protected $branchesModel;
    protected $userModel;
    protected $proposalModel;
    protected $workplanTrainingActivityModel;
    protected $workplanInputActivityModel;
    protected $workplanInfrastructureActivityModel;
    protected $workplanOutputActivityModel;
    protected $govStructureModel;

    public function __construct()
    {
        $this->workplanActivityModel = new WorkplanActivityModel();
        $this->workplanModel = new WorkplanModel();
        $this->branchesModel = new BranchesModel();
        $this->userModel = new UserModel();
        $this->proposalModel = new ProposalModel();
        $this->workplanTrainingActivityModel = new WorkplanTrainingActivityModel();
        $this->workplanInputActivityModel = new WorkplanInputActivityModel();
        $this->workplanInfrastructureActivityModel = new WorkplanInfrastructureActivityModel();
        $this->workplanOutputActivityModel = new WorkplanOutputActivityModel();
        $this->govStructureModel = new GovStructureModel();

        // Check if user has access to evaluation module
        $this->checkEvaluatorAccess();
    }

    /**
     * Check if user has evaluator access
     * Accessible to admin users OR users with is_evaluator = 1
     */
    private function checkEvaluatorAccess()
    {
        $userRole = session()->get('role');
        $isEvaluator = session()->get('is_evaluator');

        if ($userRole !== 'admin' && $isEvaluator != 1) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied. Evaluation module requires admin role or evaluator privileges.');
        }
    }

    /**
     * Display a list of approved or rated activities for evaluation
     *
     * @return mixed
     */
    public function index()
    {
        // Get only activities that have approved or rated proposals
        $activities = $this->getApprovedOrRatedActivities();

        $data = [
            'title' => 'Activity Evaluation - Approved or Rated Activities',
            'activities' => $activities
        ];

        return view('evaluation/evaluation_index', $data);
    }

    /**
     * Display the specified activity for evaluation with all proposals and implementations
     *
     * @param int $id
     * @return mixed
     */
    public function show($id)
    {
        // Get the activity details
        $activity = $this->workplanActivityModel->find($id);

        if (!$activity) {
            return redirect()->to('evaluation')->with('error', 'Activity not found.');
        }

        // Get supervisor information
        if (!empty($activity['supervisor_id'])) {
            $supervisor = $this->userModel->find($activity['supervisor_id']);
            $activity['supervisor_name'] = ($supervisor['fname'] ?? '') . ' ' . ($supervisor['lname'] ?? '');
        } else {
            $activity['supervisor_name'] = 'Not assigned';
        }

        // Get the workplan information
        $workplan = $this->workplanModel->find($activity['workplan_id']);

        // Get the branch information
        $branch = $this->branchesModel->find($activity['branch_id']);

        // Get ALL proposals for summary
        $allProposals = $this->proposalModel->where('activity_id', $id)->findAll();

        // Get approved OR rated proposals for detailed display
        $approvedOrRatedProposals = $this->proposalModel
            ->where('activity_id', $id)
            ->groupStart()
                ->where('status', 'approved')
                ->orGroupStart()
                    ->where('rating_score IS NOT NULL')
                    ->where('rating_score >', 0)
                ->groupEnd()
            ->groupEnd()
            ->findAll();

        // Calculate summary statistics
        $proposalSummary = [
            'total' => count($allProposals),
            'approved' => 0,
            'pending' => 0,
            'rejected' => 0,
            'rated' => 0,
            'total_cost' => 0
        ];

        foreach ($allProposals as $proposal) {
            $proposalSummary['total_cost'] += $proposal['total_cost'] ?? 0;

            switch ($proposal['status']) {
                case 'approved':
                    $proposalSummary['approved']++;
                    break;
                case 'pending':
                    $proposalSummary['pending']++;
                    break;
                case 'rejected':
                    $proposalSummary['rejected']++;
                    break;
            }

            if (!empty($proposal['rating_score']) && $proposal['rating_score'] > 0) {
                $proposalSummary['rated']++;
            }
        }

        // Enhance each approved or rated proposal with detailed information
        foreach ($approvedOrRatedProposals as &$proposal) {
            // Get supervisor and action officer information
            if (!empty($proposal['supervisor_id'])) {
                $supervisor = $this->userModel->find($proposal['supervisor_id']);
                $proposal['supervisor_name'] = ($supervisor['fname'] ?? '') . ' ' . ($supervisor['lname'] ?? '');
            }

            if (!empty($proposal['action_officer_id'])) {
                $officer = $this->userModel->find($proposal['action_officer_id']);
                $proposal['action_officer_name'] = ($officer['fname'] ?? '') . ' ' . ($officer['lname'] ?? '');
            }

            // Get location information
            if (!empty($proposal['province_id'])) {
                $province = $this->govStructureModel->find($proposal['province_id']);
                $proposal['province_name'] = $province['name'] ?? 'N/A';
            }

            if (!empty($proposal['district_id'])) {
                $district = $this->govStructureModel->find($proposal['district_id']);
                $proposal['district_name'] = $district['name'] ?? 'N/A';
            }

            // Get implementation activities for this proposal
            $proposal['implementations'] = $this->getImplementationActivities($proposal['id'], $id);
        }

        $data = [
            'title' => 'Activity Evaluation - ' . ucfirst($activity['activity_type']) . ' Implementation Details',
            'activity' => $activity,
            'workplan' => $workplan,
            'branch' => $branch,
            'proposalSummary' => $proposalSummary,
            'proposals' => $approvedOrRatedProposals
        ];

        // Route to specific view based on activity type
        $activityType = strtolower($activity['activity_type']);

        // Map activity types to view names
        $viewMapping = [
            'training' => 'evaluation/evaluation_training',
            'inputs' => 'evaluation/evaluation_inputs',
            'infrastructure' => 'evaluation/evaluation_infrastructure',
            'output' => 'evaluation/evaluation_output',
            'outputs' => 'evaluation/evaluation_output' // Handle plural form
        ];

        // Get the appropriate view name or fallback to general view
        $viewName = $viewMapping[$activityType] ?? 'evaluation/evaluation_show';

        return view($viewName, $data);
    }



    /**
     * Get all implementation activities for a proposal
     *
     * @param int $proposalId
     * @param int $activityId
     * @return array
     */
    private function getImplementationActivities($proposalId, $activityId)
    {
        $implementations = [];

        // Get training activities
        $trainingActivities = $this->workplanTrainingActivityModel
            ->where('proposal_id', $proposalId)
            ->where('activity_id', $activityId)
            ->findAll();

        foreach ($trainingActivities as $training) {
            $training['type'] = 'Training';
            // Parse JSON fields for training
            if (!empty($training['trainees']) && is_string($training['trainees'])) {
                $training['trainees'] = json_decode($training['trainees'], true);
            }
            if (!empty($training['training_images']) && is_string($training['training_images'])) {
                $training['training_images'] = json_decode($training['training_images'], true);
            }
            if (!empty($training['training_files']) && is_string($training['training_files'])) {
                $training['training_files'] = json_decode($training['training_files'], true);
            }
            $implementations[] = $training;
        }

        // Get input activities
        $inputActivities = $this->workplanInputActivityModel
            ->where('proposal_id', $proposalId)
            ->where('activity_id', $activityId)
            ->findAll();

        foreach ($inputActivities as $input) {
            $input['type'] = 'Input';
            // Parse JSON fields for inputs
            if (!empty($input['inputs']) && is_string($input['inputs'])) {
                $input['inputs'] = json_decode($input['inputs'], true);
            }
            if (!empty($input['input_images']) && is_string($input['input_images'])) {
                $input['input_images'] = json_decode($input['input_images'], true);
            }
            if (!empty($input['input_files']) && is_string($input['input_files'])) {
                $input['input_files'] = json_decode($input['input_files'], true);
            }
            $implementations[] = $input;
        }

        // Get infrastructure activities
        $infrastructureActivities = $this->workplanInfrastructureActivityModel
            ->where('proposal_id', $proposalId)
            ->where('activity_id', $activityId)
            ->findAll();

        foreach ($infrastructureActivities as $infrastructure) {
            $infrastructure['type'] = 'Infrastructure';
            // Parse JSON fields for infrastructure
            if (!empty($infrastructure['infrastructure_images']) && is_string($infrastructure['infrastructure_images'])) {
                $infrastructure['infrastructure_images'] = json_decode($infrastructure['infrastructure_images'], true);
            }
            if (!empty($infrastructure['infrastructure_files']) && is_string($infrastructure['infrastructure_files'])) {
                $infrastructure['infrastructure_files'] = json_decode($infrastructure['infrastructure_files'], true);
            }
            $implementations[] = $infrastructure;
        }

        // Get output activities
        $outputActivities = $this->workplanOutputActivityModel
            ->where('proposal_id', $proposalId)
            ->where('activity_id', $activityId)
            ->findAll();

        foreach ($outputActivities as $output) {
            $output['type'] = 'Output';
            // Parse JSON fields for outputs
            if (!empty($output['outputs']) && is_string($output['outputs'])) {
                $output['outputs'] = json_decode($output['outputs'], true);
            }
            if (!empty($output['beneficiaries']) && is_string($output['beneficiaries'])) {
                $output['beneficiaries'] = json_decode($output['beneficiaries'], true);
            }
            if (!empty($output['output_images']) && is_string($output['output_images'])) {
                $output['output_images'] = json_decode($output['output_images'], true);
            }
            if (!empty($output['output_files']) && is_string($output['output_files'])) {
                $output['output_files'] = json_decode($output['output_files'], true);
            }
            $implementations[] = $output;
        }

        return $implementations;
    }

    /**
     * Get activities that have approved or rated proposals
     *
     * @return array
     */
    private function getApprovedOrRatedActivities()
    {
        // Get all activities
        $allActivities = $this->workplanActivityModel->orderBy('activity_code', 'ASC')->findAll();

        // Load related models
        $workplanModel = new \App\Models\WorkplanModel();
        $branchesModel = new \App\Models\BranchesModel();
        $userModel = new \App\Models\UserModel();

        $approvedOrRatedActivities = [];

        foreach ($allActivities as $activity) {
            // Check if this activity has any approved OR rated proposals
            $approvedOrRatedProposals = $this->proposalModel
                ->where('activity_id', $activity['id'])
                ->groupStart()
                    ->where('status', 'approved')
                    ->orGroupStart()
                        ->where('rating_score IS NOT NULL')
                        ->where('rating_score >', 0)
                    ->groupEnd()
                ->groupEnd()
                ->countAllResults();

            // Only include activities that have approved OR rated proposals
            if ($approvedOrRatedProposals > 0) {
                // Get workplan information
                if (!empty($activity['workplan_id'])) {
                    $workplan = $workplanModel->find($activity['workplan_id']);
                    $activity['workplan_title'] = $workplan['title'] ?? 'N/A';
                    $activity['workplan_start_date'] = $workplan['start_date'] ?? null;
                    $activity['workplan_end_date'] = $workplan['end_date'] ?? null;
                } else {
                    $activity['workplan_title'] = 'N/A';
                    $activity['workplan_start_date'] = null;
                    $activity['workplan_end_date'] = null;
                }

                // Get branch information
                if (!empty($activity['branch_id'])) {
                    $branch = $branchesModel->find($activity['branch_id']);
                    $activity['branch_name'] = $branch['name'] ?? 'N/A';
                } else {
                    $activity['branch_name'] = 'N/A';
                }

                // Get supervisor information
                if (!empty($activity['supervisor_id'])) {
                    $supervisor = $userModel->find($activity['supervisor_id']);
                    $activity['supervisor_name'] = ($supervisor['fname'] ?? '') . ' ' . ($supervisor['lname'] ?? '');
                } else {
                    $activity['supervisor_name'] = 'N/A';
                }

                // Get created by information
                if (!empty($activity['created_by'])) {
                    $creator = $userModel->find($activity['created_by']);
                    $activity['created_by_name'] = ($creator['fname'] ?? '') . ' ' . ($creator['lname'] ?? '');
                } else {
                    $activity['created_by_name'] = 'N/A';
                }

                // Add count of approved or rated proposals
                $activity['approved_rated_proposals_count'] = $approvedOrRatedProposals;

                $approvedOrRatedActivities[] = $activity;
            }
        }

        return $approvedOrRatedActivities;
    }

    /**
     * Show evaluation and rating form for workplan activity
     */
    public function rate($id)
    {
        // Get activity details with related information
        $activity = $this->workplanActivityModel
            ->select('workplan_activities.*,
                     workplans.title as workplan_title,
                     branches.name as branch_name,
                     CONCAT(supervisors.fname, " ", supervisors.lname) as supervisor_name')
            ->join('workplans', 'workplans.id = workplan_activities.workplan_id', 'left')
            ->join('branches', 'branches.id = workplan_activities.branch_id', 'left')
            ->join('users as supervisors', 'supervisors.id = workplan_activities.supervisor_id', 'left')
            ->where('workplan_activities.id', $id)
            ->first();

        if (!$activity) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Activity not found');
        }

        $data = [
            'title' => 'Evaluate and Rate Activity - ' . $activity['title'],
            'activity' => $activity
        ];

        return view('evaluation/evaluation_rate', $data);
    }

    /**
     * Update activity rating and quarterly achievements
     */
    public function updateRating($id)
    {
        // Get activity
        $activity = $this->workplanActivityModel->find($id);
        if (!$activity) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Activity not found');
        }

        // Validation rules
        $rules = [
            'q_one_achieved' => 'permit_empty|decimal',
            'q_two_achieved' => 'permit_empty|decimal',
            'q_three_achieved' => 'permit_empty|decimal',
            'q_four_achieved' => 'permit_empty|decimal',
            'rating' => 'required|integer|greater_than[0]|less_than_equal_to[5]',
            'reated_remarks' => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data for update
        $data = [
            'q_one_achieved' => $this->request->getPost('q_one_achieved') ?: null,
            'q_two_achieved' => $this->request->getPost('q_two_achieved') ?: null,
            'q_three_achieved' => $this->request->getPost('q_three_achieved') ?: null,
            'q_four_achieved' => $this->request->getPost('q_four_achieved') ?: null,
            'rating' => $this->request->getPost('rating'),
            'reated_remarks' => $this->request->getPost('reated_remarks'),
            'rated_at' => date('Y-m-d H:i:s'),
            'rated_by' => session()->get('user_id'),
            'status' => 'rated',
            'status_by' => session()->get('user_id'),
            'status_at' => date('Y-m-d H:i:s'),
            'updated_by' => session()->get('user_id')
        ];

        // Update the activity
        if ($this->workplanActivityModel->update($id, $data)) {
            session()->setFlashdata('success', 'Activity evaluation and rating updated successfully!');
            return redirect()->to(base_url('evaluation/' . $id));
        } else {
            session()->setFlashdata('error', 'Failed to update activity evaluation. Please try again.');
            return redirect()->back()->withInput();
        }
    }
}
