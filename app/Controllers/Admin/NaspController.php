<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NaspModel;

class NaspController extends BaseController
{
    protected $naspModel;

    public function __construct()
    {
        $this->naspModel = new NaspModel();
    }

    /**
     * Display list of NASP Plans
     * GET /nasp-plans
     */
    public function index()
    {
        return view('admin/nasp/nasp_plans_list', [
            'title' => 'NASP Plans Management',
            'plans' => $this->naspModel->where('type', 'plans')->findAll()
        ]);
    }

    /**
     * Show form to create new NASP Plan
     * GET /nasp-plans/new
     */
    public function new()
    {
        return view('admin/nasp/nasp_plans_create', [
            'title' => 'Create NASP Plan'
        ]);
    }

    /**
     * Process NASP Plan creation
     * POST /nasp-plans
     */
    public function create()
    {
        $userId = session()->get('user_id') ?? 1;

        $data = [
            'parent_id' => 0,
            'type' => 'plans',
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'date_from' => $this->request->getPost('date_from'),
            'date_to' => $this->request->getPost('date_to'),
            'remarks' => $this->request->getPost('remarks'),
            'nasp_status' => 1,
            'nasp_status_by' => $userId,
            'created_by' => $userId
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('success', 'NASP Plan created successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to create NASP Plan: ' . implode(', ', $this->naspModel->errors()));
    }

    /**
     * Show single NASP Plan
     * GET /nasp-plans/:id
     */
    public function show($id)
    {
        $plan = $this->naspModel->find($id);
        if (!$plan) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'NASP Plan not found');
        }

        return view('admin/nasp/nasp_plans_show', [
            'title' => 'View NASP Plan',
            'plan' => $plan
        ]);
    }

    /**
     * Show form to edit NASP Plan
     * GET /nasp-plans/:id/edit
     */
    public function edit($id)
    {
        $plan = $this->naspModel->find($id);
        if (!$plan) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'NASP Plan not found');
        }

        return view('admin/nasp/nasp_plans_edit', [
            'title' => 'Edit NASP Plan',
            'plan' => $plan
        ]);
    }

    /**
     * Process NASP Plan update
     * POST /nasp-plans/:id
     */
    public function update($id)
    {
        $plan = $this->naspModel->find($id);
        if (!$plan) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'NASP Plan not found');
        }

        $data = [
            'id' => $id,
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'date_from' => $this->request->getPost('date_from'),
            'date_to' => $this->request->getPost('date_to'),
            'remarks' => $this->request->getPost('remarks'),
            'type' => $plan['type'],
            'nasp_status' => $plan['nasp_status'],
            'updated_by' => session()->get('user_id') ?? 1
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('success', 'NASP Plan updated successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to update NASP Plan: ' . implode(', ', $this->naspModel->errors()));
    }

    /**
     * Show status toggle confirmation form
     * GET /nasp-plans/:id/toggle-status
     */
    public function showToggleStatus($id)
    {
        $plan = $this->naspModel->find($id);
        if (!$plan) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'NASP Plan not found');
        }

        return view('admin/nasp/nasp_plans_toggle_status', [
            'title' => ($plan['nasp_status'] == 1 ? 'Deactivate' : 'Activate') . ' NASP Plan',
            'plan' => $plan
        ]);
    }

    /**
     * Process status toggle
     * POST /nasp-plans/:id/toggle-status
     */
    public function toggleStatus($id)
    {
        $plan = $this->naspModel->find($id);
        if (!$plan) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'NASP Plan not found');
        }

        $remarks = $this->request->getPost('nasp_status_remarks');
        if (empty($remarks)) {
            return redirect()->back()
                           ->with('error', 'Status change remarks are required');
        }

        $newStatus = $plan['nasp_status'] == 1 ? 0 : 1;
        $statusText = $newStatus == 1 ? 'activated' : 'deactivated';

        $data = [
            'id' => $id,
            'type' => $plan['type'],
            'code' => $plan['code'],
            'title' => $plan['title'],
            'nasp_status' => $newStatus,
            'nasp_status_by' => session()->get('user_id') ?? 1,
            'nasp_status_at' => date('Y-m-d H:i:s'),
            'nasp_status_remarks' => $remarks
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('success', "NASP Plan {$statusText} successfully");
        }

        return redirect()->back()
                       ->with('error', 'Failed to change status: ' . implode(', ', $this->naspModel->errors()));
    }

    /**
     * Delete NASP Plan and its children
     * POST /nasp-plans/:id/delete
     */
    public function delete($id)
    {
        $plan = $this->naspModel->find($id);
        if (!$plan) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'NASP Plan not found');
        }

        if ($this->naspModel->deletePlanAndChildren($id)) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('success', 'NASP Plan and all its children deleted successfully');
        }

        return redirect()->to('/admin/nasp-plans')
                       ->with('error', 'Failed to delete NASP Plan and its children');
    }

    // APA Methods

    /**
     * List APAs for a NASP Plan
     * GET /nasp-plans/:id/apas
     */
    public function apas($planId)
    {
        $plan = $this->naspModel->find($planId);
        if (!$plan) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'NASP Plan not found');
        }

        return view('admin/nasp/nasp_apas_list', [
            'title' => 'APAs for ' . $plan['title'],
            'plan' => $plan,
            'apas' => $this->naspModel->where('parent_id', $planId)
                                    ->where('type', 'kras')
                                    ->findAll()
        ]);
    }

    /**
     * Show form to create new APA
     * GET /nasp-plans/:id/apas/new
     */
    public function newApa($planId)
    {
        $plan = $this->naspModel->find($planId);
        if (!$plan) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'NASP Plan not found');
        }

        return view('admin/nasp/nasp_apas_create_form', [
            'title' => 'Create New APA',
            'plan' => $plan
        ]);
    }

    /**
     * Process APA creation
     * POST /nasp-plans/:id/apas
     */
    public function createApa($planId)
    {
        $plan = $this->naspModel->find($planId);
        if (!$plan) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'NASP Plan not found');
        }

        $data = [
            'parent_id' => $planId,
            'type' => 'kras',
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'nasp_status' => 1,
            'nasp_status_by' => session()->get('user_id') ?? 1,
            'created_by' => session()->get('user_id') ?? 1
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/{$planId}/apas")
                           ->with('success', 'APA created successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to create APA: ' . implode(', ', $this->naspModel->errors()));
    }

    /**
     * Show single APA
     * GET /nasp-plans/apas/:id
     */
    public function showApa($id)
    {
        $apa = $this->naspModel->find($id);
        if (!$apa) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'APA not found');
        }

        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_apas_show', [
            'title' => 'View APA',
            'apa' => $apa,
            'plan' => $plan
        ]);
    }

    /**
     * Show form to edit APA
     * GET /nasp-plans/apas/:id/edit
     */
    public function editApa($id)
    {
        $apa = $this->naspModel->find($id);
        if (!$apa) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'APA not found');
        }

        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_apas_edit', [
            'title' => 'Edit APA',
            'apa' => $apa,
            'plan' => $plan
        ]);
    }

    /**
     * Process APA update
     * POST /nasp-plans/apas/:id
     */
    public function updateApa($id)
    {
        $apa = $this->naspModel->find($id);
        if (!$apa) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'APA not found');
        }

        $data = [
            'id' => $id,
            'type' => $apa['type'],
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id') ?? 1
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/{$apa['parent_id']}/apas")
                           ->with('success', 'APA updated successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to update APA: ' . implode(', ', $this->naspModel->errors()));
    }

    /**
     * Show APA status toggle confirmation form
     * GET /nasp-plans/apas/:id/toggle-status
     */
    public function showToggleApaStatus($id)
    {
        $apa = $this->naspModel->find($id);
        if (!$apa) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'APA not found');
        }

        return view('admin/nasp/nasp_apas_toggle_status', [
            'title' => ($apa['nasp_status'] == 1 ? 'Deactivate' : 'Activate') . ' APA',
            'apa' => $apa
        ]);
    }

    /**
     * Process APA status toggle
     * POST /nasp-plans/apas/:id/toggle-status
     */
    public function toggleApaStatus($id)
    {
        $apa = $this->naspModel->find($id);
        if (!$apa) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'APA not found');
        }

        $remarks = $this->request->getPost('nasp_status_remarks');
        if (empty($remarks)) {
            return redirect()->back()
                           ->with('error', 'Status change remarks are required');
        }

        $newStatus = $apa['nasp_status'] == 1 ? 0 : 1;
        $statusText = $newStatus == 1 ? 'activated' : 'deactivated';

        $data = [
            'id' => $id,
            'type' => $apa['type'],
            'code' => $apa['code'],
            'title' => $apa['title'],
            'nasp_status' => $newStatus,
            'nasp_status_by' => session()->get('user_id') ?? 1,
            'nasp_status_at' => date('Y-m-d H:i:s'),
            'nasp_status_remarks' => $remarks
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/{$apa['parent_id']}/apas")
                           ->with('success', "APA {$statusText} successfully");
        }

        return redirect()->back()
                       ->with('error', 'Failed to change status: ' . implode(', ', $this->naspModel->errors()));
    }

    // DIP Methods

    /**
     * List DIPs for an APA
     * GET /nasp-plans/apas/:id/dips
     */
    public function dips($apaId)
    {
        $apa = $this->naspModel->find($apaId);
        if (!$apa) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'APA not found');
        }

        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_dips_list', [
            'title' => 'DIPs for ' . $apa['title'],
            'apa' => $apa,
            'plan' => $plan,
            'dips' => $this->naspModel->where('parent_id', $apaId)
                                    ->where('type', 'objectives')
                                    ->findAll()
        ]);
    }

    /**
     * Show form to create new DIP
     * GET /nasp-plans/apas/:id/dips/new
     */
    public function newDip($apaId)
    {
        $apa = $this->naspModel->find($apaId);
        if (!$apa) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'APA not found');
        }

        return view('admin/nasp/nasp_dips_create_form', [
            'title' => 'Create New DIP',
            'apa' => $apa
        ]);
    }

    /**
     * Process DIP creation
     * POST /nasp-plans/apas/:id/dips
     */
    public function createDip($apaId)
    {
        $apa = $this->naspModel->find($apaId);
        if (!$apa) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'APA not found');
        }

        $data = [
            'parent_id' => $apaId,
            'type' => 'objectives',
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'nasp_status' => 1,
            'nasp_status_by' => session()->get('user_id') ?? 1,
            'created_by' => session()->get('user_id') ?? 1
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/apas/{$apaId}/dips")
                           ->with('success', 'DIP created successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to create DIP: ' . implode(', ', $this->naspModel->errors()));
    }

    /**
     * Show single DIP
     * GET /nasp-plans/dips/:id
     */
    public function showDip($id)
    {
        $dip = $this->naspModel->find($id);
        if (!$dip) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'DIP not found');
        }

        $apa = $this->naspModel->find($dip['parent_id']);

        return view('admin/nasp/nasp_dips_show', [
            'title' => 'View DIP',
            'dip' => $dip,
            'apa' => $apa
        ]);
    }

    /**
     * Show form to edit DIP
     * GET /nasp-plans/dips/:id/edit
     */
    public function editDip($id)
    {
        $dip = $this->naspModel->find($id);
        if (!$dip) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'DIP not found');
        }

        $apa = $this->naspModel->find($dip['parent_id']);

        return view('admin/nasp/nasp_dips_edit', [
            'title' => 'Edit DIP',
            'dip' => $dip,
            'apa' => $apa
        ]);
    }

    /**
     * Process DIP update
     * POST /nasp-plans/dips/:id
     */
    public function updateDip($id)
    {
        $dip = $this->naspModel->find($id);
        if (!$dip) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'DIP not found');
        }

        $data = [
            'id' => $id,
            'type' => $dip['type'],
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id') ?? 1
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/apas/{$dip['parent_id']}/dips")
                           ->with('success', 'DIP updated successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to update DIP: ' . implode(', ', $this->naspModel->errors()));
    }

    /**
     * Show DIP status toggle confirmation form
     * GET /nasp-plans/dips/:id/toggle-status
     */
    public function showToggleDipStatus($id)
    {
        $dip = $this->naspModel->find($id);
        if (!$dip) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'DIP not found');
        }

        $apa = $this->naspModel->find($dip['parent_id']);

        return view('admin/nasp/nasp_dips_toggle_status', [
            'title' => ($dip['nasp_status'] == 1 ? 'Deactivate' : 'Activate') . ' DIP',
            'dip' => $dip,
            'apa' => $apa
        ]);
    }

    /**
     * Process DIP status toggle
     * POST /nasp-plans/dips/:id/toggle-status
     */
    public function toggleDipStatus($id)
    {
        $dip = $this->naspModel->find($id);
        if (!$dip) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'DIP not found');
        }

        $remarks = $this->request->getPost('nasp_status_remarks');
        if (empty($remarks)) {
            return redirect()->back()
                           ->with('error', 'Status change remarks are required');
        }

        $newStatus = $dip['nasp_status'] == 1 ? 0 : 1;
        $statusText = $newStatus == 1 ? 'activated' : 'deactivated';

        $data = [
            'id' => $id,
            'type' => $dip['type'],
            'code' => $dip['code'],
            'title' => $dip['title'],
            'nasp_status' => $newStatus,
            'nasp_status_by' => session()->get('user_id') ?? 1,
            'nasp_status_at' => date('Y-m-d H:i:s'),
            'nasp_status_remarks' => $remarks
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/apas/{$dip['parent_id']}/dips")
                           ->with('success', "DIP {$statusText} successfully");
        }

        return redirect()->back()
                       ->with('error', 'Failed to change status: ' . implode(', ', $this->naspModel->errors()));
    }

    // Specific Area Methods

    /**
     * List Specific Areas for a DIP
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas
     */
    public function specificAreas($dipId)
    {
        $dip = $this->naspModel->find($dipId);
        if (!$dip) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'DIP not found');
        }

        $apa = $this->naspModel->find($dip['parent_id']);
        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_sa_list', [
            'title' => 'Specific Areas for ' . $dip['title'],
            'dip' => $dip,
            'apa' => $apa,
            'plan' => $plan,
            'specificAreas' => $this->naspModel->where('parent_id', $dipId)
                                            ->where('type', 'specific_area')
                                            ->findAll()
        ]);
    }

    /**
     * Show form to create new Specific Area
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/new
     */
    public function newSpecificArea($dipId)
    {
        $dip = $this->naspModel->find($dipId);
        if (!$dip) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'DIP not found');
        }

        $apa = $this->naspModel->find($dip['parent_id']);
        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_sa_create', [
            'title' => 'Create New Specific Area',
            'dip' => $dip,
            'apa' => $apa,
            'plan' => $plan
        ]);
    }

    /**
     * Process Specific Area creation
     * POST /nasp-plans/apas/:id/dips/:id/specific-areas
     */
    public function createSpecificArea($dipId)
    {
        $dip = $this->naspModel->find($dipId);
        if (!$dip) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'DIP not found');
        }

        $data = [
            'parent_id' => $dipId,
            'type' => 'specific_area',
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'nasp_status' => 1,
            'nasp_status_by' => session()->get('user_id') ?? 1,
            'created_by' => session()->get('user_id') ?? 1
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/apas/{$dip['parent_id']}/dips/{$dipId}/specific-areas")
                           ->with('success', 'Specific Area created successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to create Specific Area: ' . implode(', ', $this->naspModel->errors()));
    }

    /**
     * Show single Specific Area
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id
     */
    public function showSpecificArea($id)
    {
        $specificArea = $this->naspModel->find($id);
        if (!$specificArea) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Specific Area not found');
        }

        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);
        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_sa_show', [
            'title' => 'View Specific Area',
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa,
            'plan' => $plan
        ]);
    }

    /**
     * Show form to edit Specific Area
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/edit
     */
    public function editSpecificArea($id)
    {
        $specificArea = $this->naspModel->find($id);
        if (!$specificArea) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Specific Area not found');
        }

        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);
        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_sa_edit', [
            'title' => 'Edit Specific Area',
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa,
            'plan' => $plan
        ]);
    }

    /**
     * Process Specific Area update
     * POST /nasp-plans/apas/:id/dips/:id/specific-areas/:id
     */
    public function updateSpecificArea($id)
    {
        $specificArea = $this->naspModel->find($id);
        if (!$specificArea) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Specific Area not found');
        }

        $dip = $this->naspModel->find($specificArea['parent_id']);

        $data = [
            'id' => $id,
            'type' => $specificArea['type'],
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id') ?? 1
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/apas/{$dip['parent_id']}/dips/{$dip['id']}/specific-areas")
                           ->with('success', 'Specific Area updated successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to update Specific Area: ' . implode(', ', $this->naspModel->errors()));
    }

    /**
     * Show Specific Area status toggle confirmation form
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/toggle-status
     */
    public function showToggleSpecificAreaStatus($id)
    {
        $specificArea = $this->naspModel->find($id);
        if (!$specificArea) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Specific Area not found');
        }

        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);

        return view('admin/nasp/nasp_sa_toggle_status', [
            'title' => ($specificArea['nasp_status'] == 1 ? 'Deactivate' : 'Activate') . ' Specific Area',
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa
        ]);
    }

    /**
     * Process Specific Area status toggle
     * POST /nasp-plans/apas/:id/dips/:id/specific-areas/:id/toggle-status
     */
    public function toggleSpecificAreaStatus($id)
    {
        $specificArea = $this->naspModel->find($id);
        if (!$specificArea) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Specific Area not found');
        }

        $dip = $this->naspModel->find($specificArea['parent_id']);

        $remarks = $this->request->getPost('nasp_status_remarks');
        if (empty($remarks)) {
            return redirect()->back()
                           ->with('error', 'Status change remarks are required');
        }

        $newStatus = $specificArea['nasp_status'] == 1 ? 0 : 1;
        $statusText = $newStatus == 1 ? 'activated' : 'deactivated';

        $data = [
            'id' => $id,
            'type' => $specificArea['type'],
            'code' => $specificArea['code'],
            'title' => $specificArea['title'],
            'nasp_status' => $newStatus,
            'nasp_status_by' => session()->get('user_id') ?? 1,
            'nasp_status_at' => date('Y-m-d H:i:s'),
            'nasp_status_remarks' => $remarks
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/apas/{$dip['parent_id']}/dips/{$dip['id']}/specific-areas")
                           ->with('success', "Specific Area {$statusText} successfully");
        }

        return redirect()->back()
                       ->with('error', 'Failed to change status: ' . implode(', ', $this->naspModel->errors()));
    }

    // Objectives Methods

    /**
     * List Objectives for a Specific Area
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives
     */
    public function objectives($specificAreaId)
    {
        $specificArea = $this->naspModel->find($specificAreaId);
        if (!$specificArea) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Specific Area not found');
        }

        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);
        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_objectives_list', [
            'title' => 'Objectives for ' . $specificArea['title'],
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa,
            'plan' => $plan,
            'objectives' => $this->naspModel->where('parent_id', $specificAreaId)
                                         ->where('type', 'objectives')
                                         ->findAll()
        ]);
    }

    /**
     * Show form to create new Objective
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/new
     */
    public function newObjective($specificAreaId)
    {
        $specificArea = $this->naspModel->find($specificAreaId);
        if (!$specificArea) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Specific Area not found');
        }

        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);
        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_objectives_create', [
            'title' => 'Create New Objective',
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa,
            'plan' => $plan
        ]);
    }

    /**
     * Process Objective creation
     * POST /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives
     */
    public function createObjective($specificAreaId)
    {
        $specificArea = $this->naspModel->find($specificAreaId);
        if (!$specificArea) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Specific Area not found');
        }

        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);

        $data = [
            'parent_id' => $specificAreaId,
            'type' => 'objectives',
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'nasp_status' => 1,
            'nasp_status_by' => session()->get('user_id') ?? 1,
            'created_by' => session()->get('user_id') ?? 1
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/apas/{$apa['id']}/dips/{$dip['id']}/specific-areas/{$specificAreaId}/objectives")
                           ->with('success', 'Objective created successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to create Objective: ' . implode(', ', $this->naspModel->errors()));
    }

    /**
     * Show single Objective
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id
     */
    public function showObjective($id)
    {
        $objective = $this->naspModel->find($id);
        if (!$objective) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Objective not found');
        }

        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);
        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_objectives_show', [
            'title' => 'View Objective',
            'objective' => $objective,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa,
            'plan' => $plan
        ]);
    }

    /**
     * Show form to edit Objective
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/edit
     */
    public function editObjective($id)
    {
        $objective = $this->naspModel->find($id);
        if (!$objective) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Objective not found');
        }

        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);
        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_objectives_edit', [
            'title' => 'Edit Objective',
            'objective' => $objective,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa,
            'plan' => $plan
        ]);
    }

    /**
     * Process Objective update
     * POST /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id
     */
    public function updateObjective($id)
    {
        $objective = $this->naspModel->find($id);
        if (!$objective) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Objective not found');
        }

        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);

        $data = [
            'id' => $id,
            'type' => $objective['type'],
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id') ?? 1
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/apas/{$apa['id']}/dips/{$dip['id']}/specific-areas/{$specificArea['id']}/objectives")
                           ->with('success', 'Objective updated successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to update Objective: ' . implode(', ', $this->naspModel->errors()));
    }

    /**
     * Show Objective status toggle confirmation form
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/toggle-status
     */
    public function showToggleObjectiveStatus($id)
    {
        $objective = $this->naspModel->find($id);
        if (!$objective) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Objective not found');
        }

        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);

        return view('admin/nasp/nasp_objectives_toggle_status', [
            'title' => ($objective['nasp_status'] == 1 ? 'Deactivate' : 'Activate') . ' Objective',
            'objective' => $objective,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa
        ]);
    }

    /**
     * Process Objective status toggle
     * POST /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/toggle-status
     */
    public function toggleObjectiveStatus($id)
    {
        $objective = $this->naspModel->find($id);
        if (!$objective) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Objective not found');
        }

        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);

        $remarks = $this->request->getPost('nasp_status_remarks');
        if (empty($remarks)) {
            return redirect()->back()
                           ->with('error', 'Status change remarks are required');
        }

        $newStatus = $objective['nasp_status'] == 1 ? 0 : 1;
        $statusText = $newStatus == 1 ? 'activated' : 'deactivated';

        $data = [
            'id' => $id,
            'type' => $objective['type'],
            'code' => $objective['code'],
            'title' => $objective['title'],
            'nasp_status' => $newStatus,
            'nasp_status_by' => session()->get('user_id') ?? 1,
            'nasp_status_at' => date('Y-m-d H:i:s'),
            'nasp_status_remarks' => $remarks
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/apas/{$apa['id']}/dips/{$dip['id']}/specific-areas/{$specificArea['id']}/objectives")
                           ->with('success', "Objective {$statusText} successfully");
        }

        return redirect()->back()
                       ->with('error', 'Failed to change status: ' . implode(', ', $this->naspModel->errors()));
    }

    // Outputs Methods

    /**
     * List Outputs for an Objective
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/outputs
     */
    public function outputs($objectiveId)
    {
        $objective = $this->naspModel->find($objectiveId);
        if (!$objective) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Objective not found');
        }

        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);
        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_outputs_list', [
            'title' => 'Outputs for ' . $objective['title'],
            'objective' => $objective,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa,
            'plan' => $plan,
            'outputs' => $this->naspModel->where('parent_id', $objectiveId)
                                      ->where('type', 'outputs')
                                      ->findAll()
        ]);
    }

    /**
     * Show form to create new Output
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/outputs/new
     */
    public function newOutput($objectiveId)
    {
        $objective = $this->naspModel->find($objectiveId);
        if (!$objective) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Objective not found');
        }

        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);
        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_outputs_create', [
            'title' => 'Create New Output',
            'objective' => $objective,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa,
            'plan' => $plan
        ]);
    }

    /**
     * Process Output creation
     * POST /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/outputs
     */
    public function createOutput($objectiveId)
    {
        $objective = $this->naspModel->find($objectiveId);
        if (!$objective) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Objective not found');
        }

        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);

        $data = [
            'parent_id' => $objectiveId,
            'type' => 'outputs',
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'nasp_status' => 1,
            'nasp_status_by' => session()->get('user_id') ?? 1,
            'created_by' => session()->get('user_id') ?? 1
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/apas/{$apa['id']}/dips/{$dip['id']}/specific-areas/{$specificArea['id']}/objectives/{$objectiveId}/outputs")
                           ->with('success', 'Output created successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to create Output: ' . implode(', ', $this->naspModel->errors()));
    }

    /**
     * Show single Output
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/outputs/:id
     */
    public function showOutput($id)
    {
        $output = $this->naspModel->find($id);
        if (!$output) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Output not found');
        }

        $objective = $this->naspModel->find($output['parent_id']);
        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);
        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_outputs_show', [
            'title' => 'View Output',
            'output' => $output,
            'objective' => $objective,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa,
            'plan' => $plan
        ]);
    }

    /**
     * Show form to edit Output
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/outputs/:id/edit
     */
    public function editOutput($id)
    {
        $output = $this->naspModel->find($id);
        if (!$output) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Output not found');
        }

        $objective = $this->naspModel->find($output['parent_id']);
        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);
        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_outputs_edit', [
            'title' => 'Edit Output',
            'output' => $output,
            'objective' => $objective,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa,
            'plan' => $plan
        ]);
    }

    /**
     * Process Output update
     * POST /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/outputs/:id
     */
    public function updateOutput($id)
    {
        $output = $this->naspModel->find($id);
        if (!$output) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Output not found');
        }

        $objective = $this->naspModel->find($output['parent_id']);
        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);

        $data = [
            'id' => $id,
            'type' => $output['type'],
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id') ?? 1
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/apas/{$apa['id']}/dips/{$dip['id']}/specific-areas/{$specificArea['id']}/objectives/{$objective['id']}/outputs")
                           ->with('success', 'Output updated successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to update Output: ' . implode(', ', $this->naspModel->errors()));
    }

    /**
     * Show Output status toggle confirmation form
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/outputs/:id/toggle-status
     */
    public function showToggleOutputStatus($id)
    {
        $output = $this->naspModel->find($id);
        if (!$output) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Output not found');
        }

        $objective = $this->naspModel->find($output['parent_id']);
        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);

        return view('admin/nasp/nasp_outputs_toggle_status', [
            'title' => ($output['nasp_status'] == 1 ? 'Deactivate' : 'Activate') . ' Output',
            'output' => $output,
            'objective' => $objective,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa
        ]);
    }

    /**
     * Process Output status toggle
     * POST /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/outputs/:id/toggle-status
     */
    public function toggleOutputStatus($id)
    {
        $output = $this->naspModel->find($id);
        if (!$output) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Output not found');
        }

        $objective = $this->naspModel->find($output['parent_id']);
        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);

        $remarks = $this->request->getPost('nasp_status_remarks');
        if (empty($remarks)) {
            return redirect()->back()
                           ->with('error', 'Status change remarks are required');
        }

        $newStatus = $output['nasp_status'] == 1 ? 0 : 1;
        $statusText = $newStatus == 1 ? 'activated' : 'deactivated';

        $data = [
            'id' => $id,
            'type' => $output['type'],
            'code' => $output['code'],
            'title' => $output['title'],
            'nasp_status' => $newStatus,
            'nasp_status_by' => session()->get('user_id') ?? 1,
            'nasp_status_at' => date('Y-m-d H:i:s'),
            'nasp_status_remarks' => $remarks
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/apas/{$apa['id']}/dips/{$dip['id']}/specific-areas/{$specificArea['id']}/objectives/{$objective['id']}/outputs")
                           ->with('success', "Output {$statusText} successfully");
        }

        return redirect()->back()
                       ->with('error', 'Failed to change status: ' . implode(', ', $this->naspModel->errors()));
    }

    // Indicators Methods

    /**
     * List Indicators for an Output
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/outputs/:id/indicators
     */
    public function indicators($outputId)
    {
        $output = $this->naspModel->find($outputId);
        if (!$output) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Output not found');
        }

        $objective = $this->naspModel->find($output['parent_id']);
        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);
        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_indicators_list', [
            'title' => 'Indicators for ' . $output['title'],
            'output' => $output,
            'objective' => $objective,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa,
            'plan' => $plan,
            'indicators' => $this->naspModel->where('parent_id', $outputId)
                                         ->where('type', 'indicators')
                                         ->findAll()
        ]);
    }

    /**
     * Show form to create new Indicator
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/outputs/:id/indicators/new
     */
    public function newIndicator($outputId)
    {
        $output = $this->naspModel->find($outputId);
        if (!$output) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Output not found');
        }

        $objective = $this->naspModel->find($output['parent_id']);
        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);
        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_indicators_create', [
            'title' => 'Create New Indicator',
            'output' => $output,
            'objective' => $objective,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa,
            'plan' => $plan
        ]);
    }

    /**
     * Process Indicator creation
     * POST /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/outputs/:id/indicators
     */
    public function createIndicator($outputId)
    {
        $output = $this->naspModel->find($outputId);
        if (!$output) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Output not found');
        }

        $objective = $this->naspModel->find($output['parent_id']);
        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);

        $data = [
            'parent_id' => $outputId,
            'type' => 'indicators',
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'nasp_status' => 1,
            'nasp_status_by' => session()->get('user_id') ?? 1,
            'created_by' => session()->get('user_id') ?? 1
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/apas/{$apa['id']}/dips/{$dip['id']}/specific-areas/{$specificArea['id']}/objectives/{$objective['id']}/outputs/{$outputId}/indicators")
                           ->with('success', 'Indicator created successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to create Indicator: ' . implode(', ', $this->naspModel->errors()));
    }

    /**
     * Show single Indicator
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/outputs/:id/indicators/:id
     */
    public function showIndicator($id)
    {
        $indicator = $this->naspModel->find($id);
        if (!$indicator) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Indicator not found');
        }

        $output = $this->naspModel->find($indicator['parent_id']);
        $objective = $this->naspModel->find($output['parent_id']);
        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);
        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_indicators_show', [
            'title' => 'View Indicator',
            'indicator' => $indicator,
            'output' => $output,
            'objective' => $objective,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa,
            'plan' => $plan
        ]);
    }

    /**
     * Show form to edit Indicator
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/outputs/:id/indicators/:id/edit
     */
    public function editIndicator($id)
    {
        $indicator = $this->naspModel->find($id);
        if (!$indicator) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Indicator not found');
        }

        $output = $this->naspModel->find($indicator['parent_id']);
        $objective = $this->naspModel->find($output['parent_id']);
        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);
        $plan = $this->naspModel->find($apa['parent_id']);

        return view('admin/nasp/nasp_indicators_edit', [
            'title' => 'Edit Indicator',
            'indicator' => $indicator,
            'output' => $output,
            'objective' => $objective,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa,
            'plan' => $plan
        ]);
    }

    /**
     * Process Indicator update
     * POST /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/outputs/:id/indicators/:id
     */
    public function updateIndicator($id)
    {
        $indicator = $this->naspModel->find($id);
        if (!$indicator) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Indicator not found');
        }

        $output = $this->naspModel->find($indicator['parent_id']);
        $objective = $this->naspModel->find($output['parent_id']);
        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);

        $data = [
            'id' => $id,
            'type' => $indicator['type'],
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id') ?? 1
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/apas/{$apa['id']}/dips/{$dip['id']}/specific-areas/{$specificArea['id']}/objectives/{$objective['id']}/outputs/{$output['id']}/indicators")
                           ->with('success', 'Indicator updated successfully');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to update Indicator: ' . implode(', ', $this->naspModel->errors()));
    }

    /**
     * Show Indicator status toggle confirmation form
     * GET /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/outputs/:id/indicators/:id/toggle-status
     */
    public function showToggleIndicatorStatus($id)
    {
        $indicator = $this->naspModel->find($id);
        if (!$indicator) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Indicator not found');
        }

        $output = $this->naspModel->find($indicator['parent_id']);
        $objective = $this->naspModel->find($output['parent_id']);
        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);

        return view('admin/nasp/nasp_indicators_toggle_status', [
            'title' => ($indicator['nasp_status'] == 1 ? 'Deactivate' : 'Activate') . ' Indicator',
            'indicator' => $indicator,
            'output' => $output,
            'objective' => $objective,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'apa' => $apa
        ]);
    }

    /**
     * Process Indicator status toggle
     * POST /nasp-plans/apas/:id/dips/:id/specific-areas/:id/objectives/:id/outputs/:id/indicators/:id/toggle-status
     */
    public function toggleIndicatorStatus($id)
    {
        $indicator = $this->naspModel->find($id);
        if (!$indicator) {
            return redirect()->to('/admin/nasp-plans')
                           ->with('error', 'Indicator not found');
        }

        $output = $this->naspModel->find($indicator['parent_id']);
        $objective = $this->naspModel->find($output['parent_id']);
        $specificArea = $this->naspModel->find($objective['parent_id']);
        $dip = $this->naspModel->find($specificArea['parent_id']);
        $apa = $this->naspModel->find($dip['parent_id']);

        $remarks = $this->request->getPost('nasp_status_remarks');
        if (empty($remarks)) {
            return redirect()->back()
                           ->with('error', 'Status change remarks are required');
        }

        $newStatus = $indicator['nasp_status'] == 1 ? 0 : 1;
        $statusText = $newStatus == 1 ? 'activated' : 'deactivated';

        $data = [
            'id' => $id,
            'type' => $indicator['type'],
            'code' => $indicator['code'],
            'title' => $indicator['title'],
            'nasp_status' => $newStatus,
            'nasp_status_by' => session()->get('user_id') ?? 1,
            'nasp_status_at' => date('Y-m-d H:i:s'),
            'nasp_status_remarks' => $remarks
        ];

        if ($this->naspModel->save($data)) {
            return redirect()->to("/admin/nasp-plans/apas/{$apa['id']}/dips/{$dip['id']}/specific-areas/{$specificArea['id']}/objectives/{$objective['id']}/outputs/{$output['id']}/indicators")
                           ->with('success', "Indicator {$statusText} successfully");
        }

        return redirect()->back()
                       ->with('error', 'Failed to change status: ' . implode(', ', $this->naspModel->errors()));
    }
}