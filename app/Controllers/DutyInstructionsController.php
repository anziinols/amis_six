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
        $data = [
            'title' => 'Duty Instructions',
            'duty_instructions' => $this->dutyInstructionsModel->getAllWithDetails()
        ];

        return view('duty_instructions/duty_instructions_index', $data);
    }

    /**
     * Show form for creating new duty instruction
     */
    public function new()
    {
        $data = [
            'title' => 'Create New Duty Instruction',
            'workplans' => $this->workplanModel->findAll(),
            'users' => $this->userModel->where('user_status', 1)->findAll(),
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
            'user_id' => 'required|integer',
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
            'user_id' => $this->request->getPost('user_id'),
            'supervisor_id' => $this->request->getPost('supervisor_id'),
            'duty_instruction_number' => $this->request->getPost('duty_instruction_number'),
            'duty_instruction_title' => $this->request->getPost('duty_instruction_title'),
            'duty_instruction_description' => $this->request->getPost('duty_instruction_description'),
            'created_by' => session()->get('user_id')
        ];

        // Handle file upload if present
        $file = $this->request->getFile('duty_instruction_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/duty_instructions/', $newName);
            $data['duty_instruction_filepath'] = 'uploads/duty_instructions/' . $newName;
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
        $dutyInstruction = $this->dutyInstructionsModel->getDutyInstructionWithWorkplan($id);
        
        if (!$dutyInstruction) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Duty instruction not found');
        }

        $data = [
            'title' => 'Duty Instruction Details',
            'duty_instruction' => $dutyInstruction,
            'duty_items' => $this->dutyInstructionItemsModel->getByDutyInstruction($id)
        ];

        return view('duty_instructions/duty_instructions_show', $data);
    }

    /**
     * Show form for editing duty instruction
     */
    public function edit($id)
    {
        $dutyInstruction = $this->dutyInstructionsModel->find($id);
        
        if (!$dutyInstruction) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Duty instruction not found');
        }

        $data = [
            'title' => 'Edit Duty Instruction',
            'duty_instruction' => $dutyInstruction,
            'workplans' => $this->workplanModel->findAll(),
            'users' => $this->userModel->where('user_status', 1)->findAll(),
            'supervisors' => $this->userModel->getUsersBySupervisorCapability()
        ];

        return view('duty_instructions/duty_instructions_edit', $data);
    }

    /**
     * Update duty instruction
     */
    public function update($id)
    {
        $dutyInstruction = $this->dutyInstructionsModel->find($id);
        
        if (!$dutyInstruction) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Duty instruction not found');
        }

        $rules = [
            'workplan_id' => 'required|integer',
            'user_id' => 'required|integer',
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
            'user_id' => $this->request->getPost('user_id'),
            'supervisor_id' => $this->request->getPost('supervisor_id'),
            'duty_instruction_number' => $this->request->getPost('duty_instruction_number'),
            'duty_instruction_title' => $this->request->getPost('duty_instruction_title'),
            'duty_instruction_description' => $this->request->getPost('duty_instruction_description'),
            'updated_by' => session()->get('user_id')
        ];

        // Handle file upload if present
        $file = $this->request->getFile('duty_instruction_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/duty_instructions/', $newName);
            $data['duty_instruction_filepath'] = 'uploads/duty_instructions/' . $newName;
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
        $dutyInstruction = $this->dutyInstructionsModel->find($id);
        
        if (!$dutyInstruction) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Duty instruction not found');
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
