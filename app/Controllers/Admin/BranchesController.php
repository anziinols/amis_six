<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BranchesModel;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Branches Controller
 * 
 * Follows RESTful resource pattern for branches management:
 * - index: list all branches
 * - show: display specific branch details
 * - new: show form for new branch (handled by view)
 * - create: process new branch submission
 * - edit: show form for editing (handled by view)
 * - update: process branch updates
 * - delete: process branch deletion/deactivation
 */
class BranchesController extends BaseController
{
    protected $branchesModel;
    
    public function __construct()
    {
        $this->branchesModel = new BranchesModel();
    }

    /**
     * Display list of all branches
     *
     * @return mixed
     */
    public function index()
    {
        $data = [
            'title' => 'Manage Branches',
            'branches' => $this->branchesModel->getAllBranches(),
            'branchOptions' => $this->branchesModel->getBranchesForDropdown()
        ];
        
        return view('admin/branches/branches_list', $data);
    }
    
    /**
     * Display specific branch details
     *
     * @param int|null $id Branch ID
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid branch ID'
            ])->setStatusCode(400);
        }
        
        $branch = $this->branchesModel->find($id);
        
        if (!$branch) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Branch not found'
            ])->setStatusCode(404);
        }
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $branch
        ]);
    }
    
    /**
     * Show form for creating a new branch
     * (This would be handled via the view/frontend)
     *
     * @return mixed
     */
    public function new()
    {
        // This method is included for RESTful completeness
        // In practice, the form is typically shown via the index method
        // and handled via JavaScript/AJAX
        
        $data = [
            'title' => 'Add New Branch',
            'branchOptions' => $this->branchesModel->getBranchesForDropdown()
        ];
        
        return view('admin/branches/branch_form', $data);
    }
    
    /**
     * Process the creation of a new branch
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'abbrev' => 'required|min_length[1]|max_length[20]',
            'remarks' => 'required',
        ];
        
        $validation->setRules($rules);
        
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $validation->getErrors(),
                'csrf_token' => csrf_hash(),
                'csrf_token_name' => csrf_token()
            ])->setStatusCode(400);
        }
        
        $data = [
            'parent_id' => $this->request->getPost('parent_id') ?: 0,
            'name' => $this->request->getPost('name'),
            'abbrev' => $this->request->getPost('abbrev'),
            'remarks' => $this->request->getPost('remarks'),
            'branch_status' => 1, // Active by default
            'branch_status_by' => session()->get('id') ?: 1, // Fallback to default user if session ID not available
            'branch_status_at' => date('Y-m-d H:i:s'),
            'branch_status_remarks' => 'Initial activation',
            'created_by' => session()->get('user_name') ?: 'System',
            'updated_by' => session()->get('user_name') ?: 'System'
        ];
        
        if ($this->branchesModel->save($data)) {
            // Generate new CSRF token
            $csrf = csrf_hash();
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Branch added successfully',
                'data' => $data,
                'csrf_token' => $csrf,
                'csrf_token_name' => csrf_token()
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to add branch'
            ])->setStatusCode(500);
        }
    }
    
    /**
     * Show branch edit form
     * 
     * @param int|null $id Branch ID
     * @return ResponseInterface
     */
    public function edit($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid branch ID'
            ])->setStatusCode(400);
        }
        
        $branch = $this->branchesModel->find($id);
        
        if (!$branch) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Branch not found'
            ])->setStatusCode(404);
        }
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $branch
        ]);
    }
    
    /**
     * Process branch update
     *
     * @param int|null $id Branch ID
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid branch ID',
                'csrf_token' => csrf_hash(),
                'csrf_token_name' => csrf_token()
            ])->setStatusCode(400);
        }
        
        // Verify branch exists
        $branch = $this->branchesModel->find($id);
        if (!$branch) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Branch not found',
                'csrf_token' => csrf_hash(),
                'csrf_token_name' => csrf_token()
            ])->setStatusCode(404);
        }
        
        $validation = \Config\Services::validation();
        
        $rules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'abbrev' => 'required|min_length[1]|max_length[20]',
            'remarks' => 'required',
        ];
        
        $validation->setRules($rules);
        
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $validation->getErrors(),
                'csrf_token' => csrf_hash(),
                'csrf_token_name' => csrf_token()
            ])->setStatusCode(400);
        }
        
        $data = [
            'id' => $id, // Necessary for update
            'parent_id' => $this->request->getPost('parent_id') ?: 0,
            'name' => $this->request->getPost('name'),
            'abbrev' => $this->request->getPost('abbrev'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_name') ?: 'System'
        ];
        
        if ($this->branchesModel->save($data)) {
            // Generate new CSRF token
            $csrf = csrf_hash();
            
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Branch updated successfully',
                'data' => $data,
                'csrf_token' => $csrf,
                'csrf_token_name' => csrf_token()
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to update branch'
            ])->setStatusCode(500);
        }
    }
    
    /**
     * Process branch deletion (soft delete via status toggle)
     *
     * @param int|null $id Branch ID
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid branch ID',
                'csrf_token' => csrf_hash(),
                'csrf_token_name' => csrf_token()
            ])->setStatusCode(400);
        }
        
        $branch = $this->branchesModel->find($id);
        
        if (!$branch) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Branch not found',
                'csrf_token' => csrf_hash(),
                'csrf_token_name' => csrf_token()
            ])->setStatusCode(404);
        }
        
        // Since this is a soft delete, deactivate the branch
        if ($branch['branch_status'] == 1) {
            // Get status remarks from request
            $statusRemarks = $this->request->getPost('branch_status_remarks');
            
            if (empty($statusRemarks)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Status remarks are required for deactivation',
                    'csrf_token' => csrf_hash(),
                    'csrf_token_name' => csrf_token()
                ])->setStatusCode(400);
            }
            
            // Check if branch has active children before deactivating
            $activeChildren = $this->branchesModel
                ->where('parent_id', $id)
                ->where('branch_status', 1)
                ->countAllResults();
                
            if ($activeChildren > 0) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Cannot deactivate branch with active child branches. Please deactivate child branches first.',
                    'csrf_token' => csrf_hash(),
                    'csrf_token_name' => csrf_token()
                ])->setStatusCode(400);
            }
            
            // Fallback to 1 if session ID is not available
            $userId = session()->get('id') ?: 1;
            
            try {
                $result = $this->branchesModel->toggleStatus(
                    $id, 
                    0, // Set status to inactive 
                    $userId,
                    $statusRemarks
                );
                
                if ($result) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'message' => 'Branch deactivated successfully',
                        'csrf_token' => csrf_hash(),
                        'csrf_token_name' => csrf_token()
                    ]);
                } else {
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Failed to deactivate branch'
                    ])->setStatusCode(500);
                }
            } catch (\Exception $e) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Exception occurred: ' . $e->getMessage()
                ])->setStatusCode(500);
            }
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Branch is already inactive',
                'csrf_token' => csrf_hash(),
                'csrf_token_name' => csrf_token()
            ])->setStatusCode(400);
        }
    }
    
    /**
     * Toggle branch status (activate/deactivate)
     *
     * @param int|null $id Branch ID
     * @return ResponseInterface
     */
    public function toggleStatus($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid branch ID',
                'csrf_token' => csrf_hash(),
                'csrf_token_name' => csrf_token()
            ])->setStatusCode(400);
        }
        
        $branch = $this->branchesModel->find($id);
        
        if (!$branch) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Branch not found',
                'csrf_token' => csrf_hash(),
                'csrf_token_name' => csrf_token()
            ])->setStatusCode(404);
        }
        
        // Get status remarks from request
        $statusRemarks = $this->request->getPost('branch_status_remarks');
        
        if (empty($statusRemarks)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Status remarks are required',
                'csrf_token' => csrf_hash(),
                'csrf_token_name' => csrf_token()
            ])->setStatusCode(400);
        }
        
        // Check if branch has active children before deactivating
        if ($branch['branch_status'] == 1) {
            $activeChildren = $this->branchesModel
                ->where('parent_id', $id)
                ->where('branch_status', 1)
                ->countAllResults();
                
            if ($activeChildren > 0) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Cannot deactivate branch with active child branches. Please deactivate child branches first.',
                    'csrf_token' => csrf_hash(),
                    'csrf_token_name' => csrf_token()
                ])->setStatusCode(400);
            }
        }
        
        $newStatus = $branch['branch_status'] == 1 ? 0 : 1;
        
        // Fallback to 1 if session ID is not available
        $userId = session()->get('id');
        if (empty($userId)) {
            $userId = 1; // Default system user ID
        }
        
        try {
            $result = $this->branchesModel->toggleStatus(
                $id, 
                $newStatus, 
                $userId,
                $statusRemarks
            );
            
            if ($result) {
                // Generate new CSRF token
                $csrf = csrf_hash();
                
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => $newStatus == 1 ? 'Branch activated successfully' : 'Branch deactivated successfully',
                    'new_status' => $newStatus,
                    'csrf_token' => $csrf,
                    'csrf_token_name' => csrf_token()
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update branch status: Database error'
                ])->setStatusCode(500);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Exception occurred: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
    
    /**
     * Get branch options for dropdown lists
     *
     * @return ResponseInterface
     */
    public function getOptions()
    {
        $branches = $this->branchesModel->getBranchesForDropdown();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $branches
        ]);
    }
}