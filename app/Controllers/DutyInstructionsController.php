<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DutyInstructionsModel;
use App\Models\DutyInstructionItemsModel;
use App\Models\WorkplanModel;
use App\Models\UserModel;

/**
 * DutyInstructionsController
 *
 * Handles CRUD operations for duty instructions and their items
 */
class DutyInstructionsController extends BaseController
{
    protected $dutyInstructionsModel;
    protected $dutyInstructionItemsModel;
    protected $workplanModel;
    protected $userModel;
    protected $helpers = ['form', 'url'];

    public function __construct()
    {
        $this->dutyInstructionsModel = new DutyInstructionsModel();
        $this->dutyInstructionItemsModel = new DutyInstructionItemsModel();
        $this->workplanModel = new WorkplanModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display list of duty instructions
     */
    public function index()
    {
        // Get the logged-in user ID
        $loggedInUserId = session()->get('user_id');

        // Get all duty instructions with details, then filter by user_id or supervisor_id
        $dutyInstructions = $this->dutyInstructionsModel
            ->select('duty_instructions.*,
                      workplans.title as workplan_title,
                      CONCAT(u_assigned.fname, " ", u_assigned.lname) as user_name,
                      CONCAT(u_supervisor.fname, " ", u_supervisor.lname) as supervisor_name')
            ->join('workplans', 'workplans.id = duty_instructions.workplan_id', 'left')
            ->join('users u_assigned', 'u_assigned.id = duty_instructions.user_id', 'left')
            ->join('users u_supervisor', 'u_supervisor.id = duty_instructions.supervisor_id', 'left')
            ->groupStart()
                ->where('duty_instructions.user_id', $loggedInUserId) // Duty instructions assigned to this user
                ->orWhere('duty_instructions.supervisor_id', $loggedInUserId) // OR where this user is the supervisor
            ->groupEnd()
            ->findAll();

        // Check if each duty instruction has linked my activities
        $myActivitiesDutyInstructionsModel = new \App\Models\MyActivitiesDutyInstructionsModel();

        foreach ($dutyInstructions as &$instruction) {
            $instruction['has_myactivities_links'] = $myActivitiesDutyInstructionsModel->dutyInstructionHasLinkedMyActivities($instruction['id']);
        }

        $data = [
            'title' => 'Duty Instructions',
            'duty_instructions' => $dutyInstructions
        ];

        return view('duty_instructions/duty_instructions_index', $data);
    }

    /**
     * Show form for creating new duty instruction
     */
    public function new()
    {
        // Get logged-in user's branch_id
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        $userBranchId = $user['branch_id'] ?? null;

        // Get workplans filtered by status 'in_progress' and user's branch
        $workplans = $this->workplanModel
            ->where('status', 'in_progress')
            ->where('branch_id', $userBranchId)
            ->where('deleted_at IS NULL')
            ->orderBy('title', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Create New Duty Instruction',
            'workplans' => $workplans,
            'supervisors' => $this->userModel->getUsersBySupervisorCapability()
        ];

        return view('duty_instructions/duty_instructions_create', $data);
    }

    /**
     * Store new duty instruction
     */
    public function create()
    {
        $rules = [
            'workplan_id' => 'required|integer',
            'supervisor_id' => 'required|integer',
            'duty_instruction_number' => 'required|max_length[50]',
            'duty_instruction_title' => 'required|max_length[255]',
            'duty_instruction_description' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'workplan_id' => $this->request->getPost('workplan_id'),
            'user_id' => session()->get('user_id'), // Automatically assign logged-in user
            'supervisor_id' => $this->request->getPost('supervisor_id'),
            'duty_instruction_number' => $this->request->getPost('duty_instruction_number'),
            'duty_instruction_title' => $this->request->getPost('duty_instruction_title'),
            'duty_instruction_description' => $this->request->getPost('duty_instruction_description'),
            'status' => 'active', // Set default status to 'active'
            'created_by' => session()->get('user_id')
        ];

        // Handle file upload if present
        $file = $this->request->getFile('duty_instruction_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Create directory if it doesn't exist
            $uploadPath = ROOTPATH . 'public/uploads/duty_instructions/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            // Store path with public/ prefix for correct URL construction
            $data['duty_instruction_filepath'] = 'public/uploads/duty_instructions/' . $newName;
        }

        if ($this->dutyInstructionsModel->save($data)) {
            session()->setFlashdata('success', 'Duty instruction created successfully.');
            return redirect()->to('/duty-instructions');
        } else {
            session()->setFlashdata('error', 'Failed to create duty instruction.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show specific duty instruction with its items
     */
    public function show($id)
    {
        // Get the logged-in user ID
        $loggedInUserId = session()->get('user_id');

        // Get duty instruction with filtering
        $dutyInstruction = $this->dutyInstructionsModel
            ->select('duty_instructions.*,
                      workplans.title as workplan_title,
                      CONCAT(u_assigned.fname, " ", u_assigned.lname) as user_name,
                      CONCAT(u_supervisor.fname, " ", u_supervisor.lname) as supervisor_name,
                      CONCAT(u_creator.fname, " ", u_creator.lname) as created_by_name')
            ->join('workplans', 'workplans.id = duty_instructions.workplan_id', 'left')
            ->join('users u_assigned', 'u_assigned.id = duty_instructions.user_id', 'left')
            ->join('users u_supervisor', 'u_supervisor.id = duty_instructions.supervisor_id', 'left')
            ->join('users u_creator', 'u_creator.id = duty_instructions.created_by', 'left')
            ->groupStart()
                ->where('duty_instructions.user_id', $loggedInUserId)
                ->orWhere('duty_instructions.supervisor_id', $loggedInUserId)
            ->groupEnd()
            ->find($id);

        if (!$dutyInstruction) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Duty instruction not found or access denied');
        }

        $dutyItems = $this->dutyInstructionItemsModel->getByDutyInstruction($id);

        // Check if duty instruction has linked my activities
        $myActivitiesDutyInstructionsModel = new \App\Models\MyActivitiesDutyInstructionsModel();
        $hasMyActivitiesLinks = $myActivitiesDutyInstructionsModel->dutyInstructionHasLinkedMyActivities($id);

        // Check each duty item for linked my activities
        foreach ($dutyItems as &$item) {
            $item['has_myactivities_links'] = $myActivitiesDutyInstructionsModel->hasLinkedMyActivities($item['id']);
        }

        $data = [
            'title' => 'Duty Instruction Details',
            'duty_instruction' => $dutyInstruction,
            'duty_items' => $dutyItems,
            'hasMyActivitiesLinks' => $hasMyActivitiesLinks
        ];

        return view('duty_instructions/duty_instructions_show', $data);
    }

    /**
     * Show form for editing duty instruction
     */
    public function edit($id)
    {
        // Get the logged-in user ID
        $loggedInUserId = session()->get('user_id');

        // Get duty instruction with access control
        $dutyInstruction = $this->dutyInstructionsModel
            ->groupStart()
                ->where('user_id', $loggedInUserId)
                ->orWhere('supervisor_id', $loggedInUserId)
            ->groupEnd()
            ->find($id);

        if (!$dutyInstruction) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Duty instruction not found or access denied');
        }

        // Get logged-in user's branch_id
        $user = $this->userModel->find($loggedInUserId);
        $userBranchId = $user['branch_id'] ?? null;

        // Get workplans filtered by status 'in_progress' and user's branch
        $workplans = $this->workplanModel
            ->where('status', 'in_progress')
            ->where('branch_id', $userBranchId)
            ->where('deleted_at IS NULL')
            ->orderBy('title', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Edit Duty Instruction',
            'duty_instruction' => $dutyInstruction,
            'workplans' => $workplans,
            'supervisors' => $this->userModel->getUsersBySupervisorCapability()
        ];

        return view('duty_instructions/duty_instructions_edit', $data);
    }

    /**
     * Update duty instruction
     */
    public function update($id)
    {
        // Get the logged-in user ID
        $loggedInUserId = session()->get('user_id');

        // Get duty instruction with access control
        $dutyInstruction = $this->dutyInstructionsModel
            ->groupStart()
                ->where('user_id', $loggedInUserId)
                ->orWhere('supervisor_id', $loggedInUserId)
            ->groupEnd()
            ->find($id);

        if (!$dutyInstruction) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Duty instruction not found or access denied');
        }

        $rules = [
            'workplan_id' => 'required|integer',
            'supervisor_id' => 'required|integer',
            'duty_instruction_number' => 'required|max_length[50]',
            'duty_instruction_title' => 'required|max_length[255]',
            'duty_instruction_description' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'workplan_id' => $this->request->getPost('workplan_id'),
            'user_id' => session()->get('user_id'), // Automatically assign logged-in user
            'supervisor_id' => $this->request->getPost('supervisor_id'),
            'duty_instruction_number' => $this->request->getPost('duty_instruction_number'),
            'duty_instruction_title' => $this->request->getPost('duty_instruction_title'),
            'duty_instruction_description' => $this->request->getPost('duty_instruction_description'),
            'updated_by' => session()->get('user_id')
        ];

        // Handle file upload if present
        $file = $this->request->getFile('duty_instruction_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Create directory if it doesn't exist
            $uploadPath = ROOTPATH . 'public/uploads/duty_instructions/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            // Store path with public/ prefix for correct URL construction
            $data['duty_instruction_filepath'] = 'public/uploads/duty_instructions/' . $newName;
        }

        if ($this->dutyInstructionsModel->update($id, $data)) {
            session()->setFlashdata('success', 'Duty instruction updated successfully.');
            return redirect()->to('/duty-instructions/' . $id);
        } else {
            session()->setFlashdata('error', 'Failed to update duty instruction.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete duty instruction
     */
    public function delete($id)
    {
        // Get the logged-in user ID
        $loggedInUserId = session()->get('user_id');

        // Get duty instruction with access control
        $dutyInstruction = $this->dutyInstructionsModel
            ->groupStart()
                ->where('user_id', $loggedInUserId)
                ->orWhere('supervisor_id', $loggedInUserId)
            ->groupEnd()
            ->find($id);

        if (!$dutyInstruction) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Duty instruction not found or access denied');
        }

        if ($this->dutyInstructionsModel->delete($id)) {
            session()->setFlashdata('success', 'Duty instruction deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Failed to delete duty instruction.');
        }

        return redirect()->to('/duty-instructions');
    }

    /**
     * Show form for creating new duty instruction item
     */
    public function newItem($dutyInstructionId)
    {
        $dutyInstruction = $this->dutyInstructionsModel->find($dutyInstructionId);
        
        if (!$dutyInstruction) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Duty instruction not found');
        }

        $data = [
            'title' => 'Add New Instruction Item',
            'duty_instruction' => $dutyInstruction,
            'next_number' => $this->dutyInstructionItemsModel->getNextInstructionNumber($dutyInstructionId)
        ];

        return view('duty_instructions/duty_instructions_item_create', $data);
    }

    /**
     * Store new duty instruction item
     */
    public function createItem($dutyInstructionId)
    {
        $dutyInstruction = $this->dutyInstructionsModel->find($dutyInstructionId);

        if (!$dutyInstruction) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Duty instruction not found']);
            }
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Duty instruction not found');
        }

        // Handle AJAX request
        if ($this->request->isAJAX()) {
            $json = $this->request->getJSON(true);

            $rules = [
                'instruction_number' => 'required|max_length[50]',
                'instruction' => 'required'
            ];

            $validation = \Config\Services::validation();
            $validation->setRules($rules);

            if (!$validation->run($json)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->getErrors()
                ]);
            }

            $data = [
                'duty_instruction_id' => $dutyInstructionId,
                'user_id' => $dutyInstruction['user_id'], // Use the user_id from the parent duty instruction
                'instruction_number' => $json['instruction_number'],
                'instruction' => $json['instruction'],
                'status' => $json['status'] ?? 'active',
                'remarks' => $json['remarks'] ?? '',
                'created_by' => session()->get('user_id')
            ];

            if ($this->dutyInstructionItemsModel->save($data)) {
                $itemId = $this->dutyInstructionItemsModel->getInsertID();
                $newItem = $this->dutyInstructionItemsModel->find($itemId);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Instruction item added successfully',
                    'item' => $newItem
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to add instruction item'
                ]);
            }
        }

        // Handle regular form submission (fallback)
        $rules = [
            'instruction_number' => 'required|max_length[50]',
            'instruction' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'duty_instruction_id' => $dutyInstructionId,
            'user_id' => $dutyInstruction['user_id'], // Use the user_id from the parent duty instruction
            'instruction_number' => $this->request->getPost('instruction_number'),
            'instruction' => $this->request->getPost('instruction'),
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => session()->get('user_id')
        ];

        if ($this->dutyInstructionItemsModel->save($data)) {
            session()->setFlashdata('success', 'Instruction item added successfully.');
            return redirect()->to('/duty-instructions/' . $dutyInstructionId);
        } else {
            session()->setFlashdata('error', 'Failed to add instruction item.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update duty instruction status
     */
    public function updateStatus($id)
    {
        $dutyInstruction = $this->dutyInstructionsModel->find($id);
        
        if (!$dutyInstruction) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Duty instruction not found');
        }

        $status = $this->request->getPost('status');
        $remarks = $this->request->getPost('status_remarks');

        if ($this->dutyInstructionsModel->updateStatus($id, $status, $remarks)) {
            session()->setFlashdata('success', 'Status updated successfully.');
        } else {
            session()->setFlashdata('error', 'Failed to update status.');
        }

        return redirect()->to('/duty-instructions/' . $id);
    }

    /**
     * Update duty instruction item
     */
    public function updateItem($itemId)
    {
        $item = $this->dutyInstructionItemsModel->find($itemId);

        if (!$item) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Item not found']);
            }
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Item not found');
        }

        // Handle AJAX request
        if ($this->request->isAJAX()) {
            $json = $this->request->getJSON(true);

            $rules = [
                'instruction_number' => 'required|max_length[50]',
                'instruction' => 'required'
            ];

            $validation = \Config\Services::validation();
            $validation->setRules($rules);

            if (!$validation->run($json)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validation->getErrors()
                ]);
            }

            $data = [
                'instruction_number' => $json['instruction_number'],
                'instruction' => $json['instruction'],
                'status' => $json['status'] ?? 'active',
                'remarks' => $json['remarks'] ?? '',
                'updated_by' => session()->get('user_id')
            ];

            if ($this->dutyInstructionItemsModel->update($itemId, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Item updated successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update item'
                ]);
            }
        }

        return $this->response->setStatusCode(404);
    }

    /**
     * Delete duty instruction item
     */
    public function deleteItem($itemId)
    {
        $item = $this->dutyInstructionItemsModel->find($itemId);

        if (!$item) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Item not found']);
            }
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Item not found');
        }

        // Handle AJAX request
        if ($this->request->isAJAX()) {
            if ($this->dutyInstructionItemsModel->delete($itemId)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Item deleted successfully'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete item'
                ]);
            }
        }

        return $this->response->setStatusCode(404);
    }
}
