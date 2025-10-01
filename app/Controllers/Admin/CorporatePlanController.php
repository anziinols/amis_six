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
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function create()
    {
        $rules = [
            'code' => 'required|max_length[20]',
            'title' => 'required',
            'date_from' => 'required|valid_date',
            'date_to' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
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
            return redirect()->to('admin/corporate-plans')->with('success', 'Corporate Plan created successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create Corporate Plan');
    }
    
    /**
     * Display a specific corporate plan
     *
     * @param int|null $id The corporate plan ID
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function show($id = null)
    {
        // Redirect to objectives view since that's how we display a plan
        return redirect()->to("admin/corporate-plans/objectives/$id");
    }

    /**
     * Display form for editing a corporate plan
     *
     * @param int|null $id The corporate plan ID
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function edit($id = null)
    {
        $plan = $this->corporatePlanModel->find($id);
        if (!$plan) {
            return redirect()->to('admin/corporate-plans')->with('error', 'Corporate Plan not found');
        }

        $data = [
            'title' => 'Edit Corporate Plan',
            'plan' => $plan
        ];

        return view('admin/corporate_plans/corporate_plans_edit', $data);
    }
    
    /**
     * Process request to update a corporate plan
     *
     * @param int|null $id The corporate plan ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update($id = null)
    {
        if ($id === null) {
            $id = $this->request->getPost('id');
        }

        $rules = [
            'code' => 'required|max_length[20]',
            'title' => 'required',
            'date_from' => 'required|valid_date',
            'date_to' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
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
            return redirect()->to('admin/corporate-plans')->with('success', 'Corporate Plan updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update Corporate Plan');
    }
    
    /**
     * Process request to delete a corporate plan
     *
     * @param int|null $id The corporate plan ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($id = null)
    {
        $plan = $this->corporatePlanModel->find($id);
        if (!$plan) {
            return redirect()->to('admin/corporate-plans')->with('error', 'Corporate Plan not found');
        }

        if ($this->corporatePlanModel->deletePlanAndChildren($id)) {
            return redirect()->to('admin/corporate-plans')->with('success', 'Corporate Plan and all its children deleted successfully');
        }

        return redirect()->to('admin/corporate-plans')->with('error', 'Failed to delete Corporate Plan and its children');
    }
    
    /**
     * Toggle the status of a corporate plan
     *
     * @param int|null $id The corporate plan ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function toggleStatus($id = null)
    {
        if ($id === null) {
            $id = $this->request->getPost('id');
        }

        $statusRemarks = $this->request->getPost('corp_plan_status_remarks');
        $userId = session()->get('user_id');

        if ($this->corporatePlanModel->toggleStatus($id, $userId, $statusRemarks)) {
            return redirect()->to('admin/corporate-plans')->with('success', 'Status updated successfully');
        }

        return redirect()->to('admin/corporate-plans')->with('error', 'Failed to update status');
    }

    /**
     * Display the list of objectives for a corporate plan
     *
     * @param int $corporatePlanId The corporate plan ID
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function objectives($corporatePlanId)
    {
        // Get the parent plan
        $parentPlan = $this->corporatePlanModel->find($corporatePlanId);

        if (!$parentPlan) {
            return redirect()->to('admin/corporate-plans')->with('error', 'Corporate Plan not found');
        }

        $data = [
            'title' => 'Objectives for ' . $parentPlan['title'],
            'corporatePlan' => $parentPlan,
            'parentPlan' => $parentPlan,
            'objectives' => $this->corporatePlanModel->getItemsByTypeAndParent('objective', $corporatePlanId)
        ];

        return view('admin/corporate_plans/corporate_plans_objectives', $data);
    }
    
    /**
     * Process request to create a new objective
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function createObjective()
    {
        // Debug: Log what we received
        log_message('info', 'Creating objective with data: ' . json_encode($this->request->getPost()));

        // Simple validation
        if (empty($this->request->getPost('code')) || empty($this->request->getPost('title'))) {
            return redirect()->back()->with('error', 'Code and Title are required');
        }

        $data = [
            'parent_id' => (int)$this->request->getPost('parent_id'),
            'type' => 'objective',
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks') ?? '',
            'corp_plan_status' => 1,
            'created_by' => session()->get('user_id') ?? 1,
            'updated_by' => session()->get('user_id') ?? 1
        ];

        log_message('info', 'Inserting objective data: ' . json_encode($data));

        try {
            // Disable validation temporarily for debugging
            $this->corporatePlanModel->skipValidation(true);
            $result = $this->corporatePlanModel->insert($data);
            log_message('info', 'Insert result: ' . ($result ? 'Success - ID: ' . $result : 'Failed'));

            if ($result) {
                return redirect()->back()->with('success', 'Objective created successfully with ID: ' . $result);
            } else {
                $errors = $this->corporatePlanModel->errors();
                log_message('error', 'Model errors: ' . json_encode($errors));
                return redirect()->back()->with('error', 'Failed to create Objective - Database error: ' . json_encode($errors));
            }
        } catch (\Exception $e) {
            log_message('error', 'Error creating objective: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create Objective: ' . $e->getMessage());
        }
    }
    
    /**
     * Process request to update an existing objective
     *
     * @param int|null $id The objective ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function updateObjective($id = null)
    {
        if ($id === null) {
            $id = $this->request->getPost('id');
        }

        // Simple validation
        if (empty($this->request->getPost('code')) || empty($this->request->getPost('title'))) {
            return redirect()->back()->with('error', 'Code and Title are required');
        }

        $data = [
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks') ?? '',
            'updated_by' => session()->get('user_id') ?? 1
        ];

        try {
            $result = $this->corporatePlanModel->update($id, $data);
            if ($result) {
                return redirect()->back()->with('success', 'Objective updated successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to update Objective');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error updating objective: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update Objective: ' . $e->getMessage());
        }
    }
    

    
    /**
     * Toggle the status of an objective
     *
     * @param int $id The Objective ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function toggleObjectiveStatus($id)
    {
        $userId = session()->get('user_id');

        if ($this->corporatePlanModel->toggleStatus($id, $userId)) {
            return redirect()->back()->with('success', 'Status updated successfully');
        }

        return redirect()->back()->with('error', 'Failed to update status');
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

        // Get the corporate plan to create breadcrumb navigation
        $corporatePlan = $this->corporatePlanModel->find($parentObj['parent_id']);

        $data = [
            'title' => 'KRAs for ' . $parentObj['title'],
            'parentObj' => $parentObj,
            'corporatePlan' => $corporatePlan,
            'kras' => $this->corporatePlanModel->getItemsByTypeAndParent('kra', $objectiveId)
        ];
        
        return view('admin/corporate_plans/corporate_plans_kras', $data);
    }
    
    /**
     * Process request to create a new KRA
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function createKra()
    {
        $rules = [
            'parent_id' => 'required|integer',
            'code' => 'required|max_length[20]',
            'title' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
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
            return redirect()->back()->with('success', 'KRA created successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create KRA');
    }
    
    /**
     * Process request to update an existing KRA
     *
     * @param int|null $id The KRA ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function updateKra($id = null)
    {
        if ($id === null) {
            $id = $this->request->getPost('id');
        }

        $rules = [
            'code' => 'required|max_length[20]',
            'title' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->corporatePlanModel->updateItem($id, $data)) {
            return redirect()->back()->with('success', 'KRA updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update KRA');
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
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function toggleKraStatus($id)
    {
        $userId = session()->get('user_id');

        if ($this->corporatePlanModel->toggleStatus($id, $userId)) {
            return redirect()->back()->with('success', 'Status updated successfully');
        }

        return redirect()->back()->with('error', 'Failed to update status');
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

        // Get the corporate plan to create breadcrumb navigation
        $corporatePlan = $this->corporatePlanModel->find($objective['parent_id']);

        $data = [
            'title' => 'Strategies for ' . $parentKra['title'],
            'parentKra' => $parentKra,
            'objective' => $objective,
            'corporatePlan' => $corporatePlan,
            'strategies' => $this->corporatePlanModel->getItemsByTypeAndParent('strategy', $kraId)
        ];
        
        return view('admin/corporate_plans/corporate_plans_strategies', $data);
    }
    
    /**
     * Process request to create a new strategy
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function createStrategy()
    {
        $rules = [
            'parent_id' => 'required|integer',
            'code' => 'required|max_length[20]',
            'title' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
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
            return redirect()->back()->with('success', 'Strategy created successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create Strategy');
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
     * @param int|null $id The strategy ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function updateStrategy($id = null)
    {
        if ($id === null) {
            $id = $this->request->getPost('id');
        }

        $rules = [
            'code' => 'required|max_length[20]',
            'title' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->corporatePlanModel->updateItem($id, $data)) {
            return redirect()->back()->with('success', 'Strategy updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update Strategy');
    }
    
    /**
     * Toggle the status of a strategy
     *
     * @param int $id The strategy ID
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function toggleStrategyStatus($id)
    {
        $userId = session()->get('user_id');

        if ($this->corporatePlanModel->toggleStatus($id, $userId)) {
            return redirect()->back()->with('success', 'Status updated successfully');
        }

        return redirect()->back()->with('error', 'Failed to update status');
    }
}
