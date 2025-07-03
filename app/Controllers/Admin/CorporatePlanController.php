<?php
// app/Controllers/Admin/CorporatePlanController.php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CorporatePlanModel;

class CorporatePlanController extends BaseController
{
    protected $corporatePlanModel;
    
    /**
     * Constructor initializes model
     */
    public function __construct()
    {
        $this->corporatePlanModel = new CorporatePlanModel();
    }
    
    /**
     * Display the list of corporate plans
     *
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function index()
    {
        $data = [
            'title' => 'Corporate Plans',
            'plans' => $this->corporatePlanModel->getPlans()
        ];
        
        return view('admin/corporate_plans/corporate_plans_list', $data);
    }

    /**
     * Display form for creating a new corporate plan
     *
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function new()
    {
        // This functionality is handled by the modal in the view
        return redirect()->to('admin/corporate-plans');
    }
    
    /**
     * Process request to create a new corporate plan
     *
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function create()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
        $data = [
            'type' => 'plans',
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'date_from' => $this->request->getPost('date_from'),
            'date_to' => $this->request->getPost('date_to'),
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => session()->get('user_id')
        ];
        
        if ($this->corporatePlanModel->createItem($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Corporate Plan created successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to create Corporate Plan'
        ]);
    }
    
    /**
     * Display a specific corporate plan
     *
     * @param int|null $id The corporate plan ID
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function show($id = null)
    {
        // Redirect to overarching objectives view since that's how we display a plan
        return redirect()->to("admin/corporate-plans/overarching-objectives/$id");
    }

    /**
     * Display form for editing a corporate plan
     *
     * @param int|null $id The corporate plan ID
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function edit($id = null)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('admin/corporate-plans');
        }
        
        $plan = $this->corporatePlanModel->find($id);
        if (!$plan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Corporate Plan not found']);
        }
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $plan
        ]);
    }
    
    /**
     * Process request to update a corporate plan
     *
     * @param int|null $id The corporate plan ID
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function update($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
        // If no ID provided in URL, get it from POST data
        if ($id === null) {
            $id = $this->request->getPost('id');
        }
        
        $data = [
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'date_from' => $this->request->getPost('date_from'),
            'date_to' => $this->request->getPost('date_to'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id')
        ];
        
        if ($this->corporatePlanModel->updateItem($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Corporate Plan updated successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update Corporate Plan'
        ]);
    }
    
    /**
     * Process request to delete a corporate plan
     *
     * @param int|null $id The corporate plan ID
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function delete($id = null)
    {
        $plan = $this->corporatePlanModel->find($id);
        if (!$plan) {
            // If AJAX, return JSON error, else redirect with flash message
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Corporate Plan not found']);
            }
            return redirect()->to('admin/corporate-plans')->with('error', 'Corporate Plan not found');
        }

        if ($this->corporatePlanModel->deletePlanAndChildren($id)) {
            // If AJAX, return JSON success, else redirect with flash message
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Corporate Plan and all its children deleted successfully']);
            }
            return redirect()->to('admin/corporate-plans')->with('success', 'Corporate Plan and all its children deleted successfully');
        }

        // If AJAX, return JSON error, else redirect with flash message
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete Corporate Plan and its children']);
        }
        return redirect()->to('admin/corporate-plans')->with('error', 'Failed to delete Corporate Plan and its children');
    }
    
    /**
     * Toggle the status of a corporate plan
     *
     * @param int|null $id The corporate plan ID
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function toggleStatus($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
        // If no ID provided in URL, get it from POST data
        if ($id === null) {
            $id = $this->request->getPost('id');
        }
        
        $statusRemarks = $this->request->getPost('corp_plan_status_remarks');
        $userId = session()->get('user_id');
        
        if ($this->corporatePlanModel->toggleStatus($id, $userId, $statusRemarks)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update status'
        ]);
    }
    
    /**
     * Display the list of overarching objectives for a corporate plan
     *
     * @param int|null $corporatePlanId The corporate plan ID
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function overarchingObjectives($corporatePlanId)
    {
        // Get the parent plan
        $parentPlan = $this->corporatePlanModel->find($corporatePlanId);
        
        if (!$parentPlan) {
            return redirect()->to('admin/corporate-plans')->with('error', 'Corporate Plan not found');
        }
        
        $data = [
            'title' => 'Overarching Objectives for ' . $parentPlan['title'],
            'parentPlan' => $parentPlan,
            'objectives' => $this->corporatePlanModel->getItemsByTypeAndParent('overarching_objective', $corporatePlanId)
        ];
        
        return view('admin/corporate_plans/corporate_plans_overarching_objectives', $data);
    }
    
    /**
     * Create a new Overarching Objective using standard form submission
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function createOverarchingObjective()
    {
        // Validation rules
        $rules = [
            'corporate_plan_id' => 'required|numeric',
            'code' => 'required|max_length[20]',
            'title' => 'required'
        ];

        // Validate input
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data for insertion
        $data = [
            'parent_id' => $this->request->getPost('corporate_plan_id'), // Map corporate_plan_id to parent_id
            'type' => 'overarching_objective', // Use 'type' instead of 'corp_plan_type'
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'corp_plan_status' => 1, // Default to active
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id')
        ];

        // Insert data
        try {
            // Use createItem method to properly handle defaults
            $result = $this->corporatePlanModel->createItem($data);
            if ($result) {
                return redirect()->back()->with('success', 'Overarching Objective created successfully');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to create Overarching Objective');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error creating overarching objective: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create Overarching Objective: ' . $e->getMessage());
        }
    }
    
    /**
     * Process request to create a new overarching objective
     *
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function createOverarchingObjectiveOld()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
        $data = [
            'parent_id' => $this->request->getPost('parent_id'),
            'type' => 'overarching_objective',
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => session()->get('user_id')
        ];
        
        if ($this->corporatePlanModel->createItem($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Overarching Objective created successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to create Overarching Objective'
        ]);
    }
    
    /**
     * Process request to update an existing overarching objective
     *
     * @param int|null $id The overarching objective ID
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function updateOverarchingObjective()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
        $id = $this->request->getPost('id');
        
        $data = [
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id')
        ];
        
        if ($this->corporatePlanModel->updateItem($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Overarching Objective updated successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update Overarching Objective'
        ]);
    }
    
    /**
     * Edit Overarching Objective - Fetch Overarching Objective data for editing
     *
     * @param int $id The Overarching Objective ID
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function editOverarchingObjective($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('admin/corporate-plans');
        }
        
        $overarchingObjective = $this->corporatePlanModel->find($id);
        
        if (!$overarchingObjective) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Overarching Objective not found'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $overarchingObjective
        ]);
    }
    
    /**
     * Toggle the status of an overarching objective
     *
     * @param int $id The Overarching Objective ID
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function toggleOverarchingObjectiveStatus($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
        $userId = session()->get('user_id');
        
        if ($this->corporatePlanModel->toggleStatus($id, $userId)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update status'
        ]);
    }
    
    /**
     * Display the list of objectives for an overarching objective
     *
     * @param int $overarchingObjectiveId The overarching objective ID
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function objectives($overarchingObjectiveId)
    {
        // Get the parent objective
        $parentObj = $this->corporatePlanModel->find($overarchingObjectiveId);
        
        if (!$parentObj) {
            return redirect()->to('admin/corporate-plans')->with('error', 'Overarching Objective not found');
        }
        
        // Get the corporate plan to create breadcrumb navigation
        $corporatePlan = $this->corporatePlanModel->find($parentObj['parent_id']);
        
        $data = [
            'title' => 'Objectives for ' . $parentObj['title'],
            'parentObj' => $parentObj,
            'corporatePlan' => $corporatePlan,
            'objectives' => $this->corporatePlanModel->getItemsByTypeAndParent('objective', $overarchingObjectiveId)
        ];
        
        return view('admin/corporate_plans/corporate_plans_objectives', $data);
    }
    
    /**
     * Process request to create a new objective
     *
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function createObjective()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
        $data = [
            'parent_id' => $this->request->getPost('parent_id'),
            'type' => 'objective',
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => session()->get('user_id')
        ];
        
        if ($this->corporatePlanModel->createItem($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Objective created successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to create Objective'
        ]);
    }
    
    /**
     * Process request to update an existing objective
     *
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function updateObjective()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
        $id = $this->request->getPost('id');
        
        $data = [
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id')
        ];
        
        if ($this->corporatePlanModel->updateItem($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Objective updated successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update Objective'
        ]);
    }
    
    /**
     * Edit Objective - Fetch Objective data for editing
     *
     * @param int $id The Objective ID
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function editObjective($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('admin/corporate-plans');
        }
        
        $objective = $this->corporatePlanModel->find($id);
        
        if (!$objective) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Objective not found'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $objective
        ]);
    }
    
    /**
     * Toggle the status of an objective
     *
     * @param int $id The Objective ID
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function toggleObjectiveStatus($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
        $userId = session()->get('user_id');
        
        if ($this->corporatePlanModel->toggleStatus($id, $userId)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update status'
        ]);
    }
    
    /**
     * Display the list of KRAs for an objective
     *
     * @param int $objectiveId The objective ID
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function kras($objectiveId)
    {
        // Get the parent objective
        $parentObj = $this->corporatePlanModel->find($objectiveId);
        
        if (!$parentObj) {
            return redirect()->to('admin/corporate-plans')->with('error', 'Objective not found');
        }
        
        // Get the overarching objective
        $overarchingObj = $this->corporatePlanModel->find($parentObj['parent_id']);
        
        // Get the corporate plan to create breadcrumb navigation
        $corporatePlan = $this->corporatePlanModel->find($overarchingObj['parent_id']);
        
        $data = [
            'title' => 'KRAs for ' . $parentObj['title'],
            'parentObj' => $parentObj,
            'overarchingObj' => $overarchingObj,
            'corporatePlan' => $corporatePlan,
            'kras' => $this->corporatePlanModel->getItemsByTypeAndParent('kra', $objectiveId)
        ];
        
        return view('admin/corporate_plans/corporate_plans_kras', $data);
    }
    
    /**
     * Process request to create a new KRA
     *
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function createKra()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
        $data = [
            'parent_id' => $this->request->getPost('parent_id'),
            'type' => 'kra',
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => session()->get('user_id')
        ];
        
        if ($this->corporatePlanModel->createItem($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'KRA created successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to create KRA'
        ]);
    }
    
    /**
     * Process request to update an existing KRA
     *
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function updateKra()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
        $id = $this->request->getPost('id');
        
        $data = [
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id')
        ];
        
        if ($this->corporatePlanModel->updateItem($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'KRA updated successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update KRA'
        ]);
    }
    
    /**
     * Edit KRA - Fetch KRA data for editing
     *
     * @param int $id The KRA ID
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function editKra($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('admin/corporate-plans');
        }
        
        $kra = $this->corporatePlanModel->find($id);
        
        if (!$kra) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'KRA not found'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $kra
        ]);
    }
    
    /**
     * Toggle the status of a KRA
     *
     * @param int $id The KRA ID
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function toggleKraStatus($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
        $userId = session()->get('user_id');
        
        if ($this->corporatePlanModel->toggleStatus($id, $userId)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update status'
        ]);
    }
    
    /**
     * Display the list of strategies for a KRA
     *
     * @param int $kraId The KRA ID
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function strategies($kraId)
    {
        // Get the parent KRA
        $parentKra = $this->corporatePlanModel->find($kraId);
        
        if (!$parentKra) {
            return redirect()->to('admin/corporate-plans')->with('error', 'KRA not found');
        }
        
        // Get the objective
        $objective = $this->corporatePlanModel->find($parentKra['parent_id']);
        
        // Get the overarching objective
        $overarchingObj = $this->corporatePlanModel->find($objective['parent_id']);
        
        // Get the corporate plan to create breadcrumb navigation
        $corporatePlan = $this->corporatePlanModel->find($overarchingObj['parent_id']);
        
        $data = [
            'title' => 'Strategies for ' . $parentKra['title'],
            'parentKra' => $parentKra,
            'objective' => $objective,
            'overarchingObj' => $overarchingObj,
            'corporatePlan' => $corporatePlan,
            'strategies' => $this->corporatePlanModel->getItemsByTypeAndParent('strategy', $kraId)
        ];
        
        return view('admin/corporate_plans/corporate_plans_strategies', $data);
    }
    
    /**
     * Process request to create a new strategy
     *
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function createStrategy()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
        $data = [
            'type' => 'strategy',
            'parent_id' => $this->request->getPost('parent_id'),
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => session()->get('user_id')
        ];
        
        if ($this->corporatePlanModel->createItem($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Strategy created successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to create Strategy'
        ]);
    }
    
    /**
     * Get strategy data for editing
     *
     * @param int $id The strategy ID
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function editStrategy($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
        $strategy = $this->corporatePlanModel->find($id);
        if (!$strategy) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Strategy not found']);
        }
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $strategy
        ]);
    }
    
    /**
     * Process request to update an existing strategy
     *
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function updateStrategy()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
        $id = $this->request->getPost('id');
        
        $data = [
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id')
        ];
        
        if ($this->corporatePlanModel->updateItem($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Strategy updated successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update Strategy'
        ]);
    }
    
    /**
     * Toggle the status of a strategy
     *
     * @param int $id The strategy ID
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     */
    public function toggleStrategyStatus($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }
        
        $userId = session()->get('user_id');
        
        if ($this->corporatePlanModel->toggleStatus($id, $userId)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update status'
        ]);
    }
}
