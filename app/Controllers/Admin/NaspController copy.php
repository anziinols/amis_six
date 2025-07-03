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
    
    // NASP Plans (Main level)
    public function index()
    {
        $data = [
            'title' => 'NASP Plans Management',
            'plans' => $this->naspModel->where('type', 'plans')->findAll()
        ];
        
        return view('admin/nasp/nasp_plans_list', $data);
    }
    
    // Create NASP Plan
    public function create()
    {
        if ($this->request->getMethod() === 'post') {
            // Get user ID from session or use default for testing
            $userId = session()->get('user_id') ?? 1;
            
            $data = [
                'parent_id' => 0, // Top level
                'type' => 'plans',
                'code' => $this->request->getPost('code'),
                'title' => $this->request->getPost('title'),
                'date_from' => $this->request->getPost('date_from'),
                'date_to' => $this->request->getPost('date_to'),
                'remarks' => $this->request->getPost('remarks'),
                'nasp_status' => 1, // Active by default
                'nasp_status_by' => $userId,
                'created_by' => $userId
            ];
            
            // Log the data being saved
            log_message('debug', 'Creating NASP Plan with data: ' . json_encode($data));
            
            if ($this->naspModel->save($data)) {
                log_message('debug', 'NASP Plan created successfully');
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'NASP Plan created successfully'
                ]);
            } else {
                log_message('error', 'Failed to create NASP Plan. Errors: ' . json_encode($this->naspModel->errors()));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to create NASP Plan',
                    'errors' => $this->naspModel->errors()
                ]);
            }
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
    }
    
    // Update NASP Plan
    public function update($id = null)
    {
        if ($id === null) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No ID provided']);
        }
        
        $plan = $this->naspModel->find($id);
        if (!$plan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NASP Plan not found']);
        }
        
        if ($this->request->getMethod() === 'post') {
            // Get user ID from session or use default for testing
            $userId = session()->get('user_id') ?? 1;
            
            $data = [
                'id' => $id,
                'code' => $this->request->getPost('code'),
                'title' => $this->request->getPost('title'),
                'date_from' => $this->request->getPost('date_from'),
                'date_to' => $this->request->getPost('date_to'),
                'remarks' => $this->request->getPost('remarks'),
                'updated_by' => $userId
            ];
            
            // Log the data being saved
            log_message('debug', 'Updating NASP Plan with data: ' . json_encode($data));
            
            if ($this->naspModel->save($data)) {
                log_message('debug', 'NASP Plan updated successfully');
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'NASP Plan updated successfully'
                ]);
            } else {
                log_message('error', 'Failed to update NASP Plan. Errors: ' . json_encode($this->naspModel->errors()));
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update NASP Plan',
                    'errors' => $this->naspModel->errors()
                ]);
            }
        }
        
        return $this->response->setJSON(['plan' => $plan]);
    }
    
    // Toggle NASP Plan status
    public function toggleStatus($id = null)
    {
        if ($id === null) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No ID provided']);
        }
        
        $plan = $this->naspModel->find($id);
        if (!$plan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'NASP Plan not found']);
        }
        
        $newStatus = $plan['nasp_status'] == 1 ? 0 : 1;
        $statusText = $newStatus == 1 ? 'activated' : 'deactivated';
        
        $data = [
            'id' => $id,
            'nasp_status' => $newStatus,
            'nasp_status_by' => session()->get('user_id'),
            'nasp_status_at' => date('Y-m-d H:i:s'),
            'nasp_status_remarks' => $this->request->getPost('nasp_status_remarks') ?? 'Status changed via admin panel'
        ];
        
        if ($this->naspModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => "NASP Plan {$statusText} successfully",
                'newStatus' => $newStatus
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to change status',
                'errors' => $this->naspModel->errors()
            ]);
        }
    }
    
    // KRAs Section (Second level)
    public function kras($planId = null)
    {
        if ($planId === null) {
            return redirect()->to('/admin/nasp')->with('error', 'No Plan ID provided');
        }
        
        $plan = $this->naspModel->find($planId);
        if (!$plan) {
            return redirect()->to('/admin/nasp')->with('error', 'NASP Plan not found');
        }
        
        $data = [
            'title' => 'KRAs for ' . $plan['title'],
            'plan' => $plan,
            'kras' => $this->naspModel->where('parent_id', $planId)
                                     ->where('type', 'kras')
                                     ->findAll()
        ];
        
        return view('admin/nasp/nasp_kras_list', $data);
    }
    
    // Create KRA
    public function createKra()
    {
        if ($this->request->getMethod() === 'post') {
            $planId = $this->request->getPost('plan_id');
            
            $data = [
                'parent_id' => $planId,
                'type' => 'kras',
                'code' => $this->request->getPost('code'),
                'title' => $this->request->getPost('title'),
                'remarks' => $this->request->getPost('remarks'),
                'nasp_status' => 1, // Active by default
                'nasp_status_by' => session()->get('user_id'),
                'created_by' => session()->get('user_id')
            ];
            
            if ($this->naspModel->save($data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'KRA created successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to create KRA',
                    'errors' => $this->naspModel->errors()
                ]);
            }
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
    }
    
    // Update KRA
    public function updateKra($id = null)
    {
        if ($id === null) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No ID provided']);
        }
        
        $kra = $this->naspModel->find($id);
        if (!$kra) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'KRA not found']);
        }
        
        if ($this->request->getMethod() === 'post') {
            $data = [
                'id' => $id,
                'code' => $this->request->getPost('code'),
                'title' => $this->request->getPost('title'),
                'remarks' => $this->request->getPost('remarks'),
                'updated_by' => session()->get('user_id')
            ];
            
            if ($this->naspModel->save($data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'KRA updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update KRA',
                    'errors' => $this->naspModel->errors()
                ]);
            }
        }
        
        return $this->response->setJSON(['kra' => $kra]);
    }
    
    // Toggle KRA status
    public function toggleKraStatus($id = null)
    {
        if ($id === null) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No ID provided']);
        }
        
        $kra = $this->naspModel->find($id);
        if (!$kra) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'KRA not found']);
        }
        
        $newStatus = $kra['nasp_status'] == 1 ? 0 : 1;
        $statusText = $newStatus == 1 ? 'activated' : 'deactivated';
        
        $data = [
            'id' => $id,
            'nasp_status' => $newStatus,
            'nasp_status_by' => session()->get('user_id'),
            'nasp_status_at' => date('Y-m-d H:i:s'),
            'nasp_status_remarks' => $this->request->getPost('nasp_status_remarks') ?? 'Status changed via admin panel'
        ];
        
        if ($this->naspModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => "KRA {$statusText} successfully",
                'newStatus' => $newStatus
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to change status',
                'errors' => $this->naspModel->errors()
            ]);
        }
    }
    
    // Objectives Section (Third level)
    public function objectives($kraId = null)
    {
        if ($kraId === null) {
            return redirect()->to('/admin/nasp')->with('error', 'No KRA ID provided');
        }
        
        $kra = $this->naspModel->find($kraId);
        if (!$kra) {
            return redirect()->to('/admin/nasp')->with('error', 'KRA not found');
        }
        
        // Get the parent plan for breadcrumb
        $plan = $this->naspModel->find($kra['parent_id']);
        
        $data = [
            'title' => 'Objectives for ' . $kra['title'],
            'kra' => $kra,
            'plan' => $plan,
            'objectives' => $this->naspModel->where('parent_id', $kraId)
                                          ->where('type', 'objectives')
                                          ->findAll()
        ];
        
        return view('admin/nasp/nasp_objectives_list', $data);
    }
    
    // Create Objective
    public function createObjective()
    {
        if ($this->request->getMethod() === 'post') {
            $kraId = $this->request->getPost('kra_id');
            
            $data = [
                'parent_id' => $kraId,
                'type' => 'objectives',
                'code' => $this->request->getPost('code'),
                'title' => $this->request->getPost('title'),
                'remarks' => $this->request->getPost('remarks'),
                'nasp_status' => 1, // Active by default
                'nasp_status_by' => session()->get('user_id'),
                'created_by' => session()->get('user_id')
            ];
            
            if ($this->naspModel->save($data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Objective created successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to create Objective',
                    'errors' => $this->naspModel->errors()
                ]);
            }
        }
        
        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
    }
    
    // Update Objective
    public function updateObjective($id = null)
    {
        if ($id === null) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No ID provided']);
        }
        
        $objective = $this->naspModel->find($id);
        if (!$objective) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Objective not found']);
        }
        
        if ($this->request->getMethod() === 'post') {
            $data = [
                'id' => $id,
                'code' => $this->request->getPost('code'),
                'title' => $this->request->getPost('title'),
                'remarks' => $this->request->getPost('remarks'),
                'updated_by' => session()->get('user_id')
            ];
            
            if ($this->naspModel->save($data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Objective updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update Objective',
                    'errors' => $this->naspModel->errors()
                ]);
            }
        }
        
        return $this->response->setJSON(['objective' => $objective]);
    }
    
    // Toggle Objective status
    public function toggleObjectiveStatus($id = null)
    {
        if ($id === null) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No ID provided']);
        }
        
        $objective = $this->naspModel->find($id);
        if (!$objective) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Objective not found']);
        }
        
        $newStatus = $objective['nasp_status'] == 1 ? 0 : 1;
        $statusText = $newStatus == 1 ? 'activated' : 'deactivated';
        
        $data = [
            'id' => $id,
            'nasp_status' => $newStatus,
            'nasp_status_by' => session()->get('user_id'),
            'nasp_status_at' => date('Y-m-d H:i:s'),
            'nasp_status_remarks' => $this->request->getPost('nasp_status_remarks') ?? 'Status changed via admin panel'
        ];
        
        if ($this->naspModel->save($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => "Objective {$statusText} successfully",
                'newStatus' => $newStatus
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to change status',
                'errors' => $this->naspModel->errors()
            ]);
        }
    }
}