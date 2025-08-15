<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\WorkplanPeriodModel;
use App\Models\DutyInstructionsModel;
use App\Models\UserModel;

/**
 * WorkplanPeriodController
 *
 * Handles CRUD operations for workplan periods
 */
class WorkplanPeriodController extends BaseController
{
    protected $workplanPeriodModel;
    protected $dutyInstructionsModel;
    protected $userModel;
    protected $helpers = ['form', 'url'];

    public function __construct()
    {
        $this->workplanPeriodModel = new WorkplanPeriodModel();
        $this->dutyInstructionsModel = new DutyInstructionsModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display list of workplan periods
     */
    public function index()
    {
        $data = [
            'title' => 'Workplan Periods',
            'workplan_periods' => $this->workplanPeriodModel->getAllWithDetails()
        ];

        return view('workplan_period/workplan_period_index', $data);
    }

    /**
     * Show form for creating new workplan period
     */
    public function new()
    {
        $data = [
            'title' => 'Create Workplan Period',
            'users' => $this->userModel->findAll(),
            'duty_instructions' => $this->dutyInstructionsModel->findAll()
        ];

        return view('workplan_period/workplan_period_create', $data);
    }

    /**
     * Create new workplan period
     */
    public function create()
    {
        $rules = [
            'user_id' => 'required|integer',
            'title' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'duty_instruction_id' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'user_id' => $this->request->getPost('user_id'),
            'duty_instruction_id' => $this->request->getPost('duty_instruction_id') ?: null,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'created_by' => session()->get('user_id')
        ];

        // Handle file upload
        $file = $this->request->getFile('workplan_period_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/workplan_periods', $newName);
            $data['workplan_period_filepath'] = 'public/uploads/workplan_periods/' . $newName;
        }

        if ($this->workplanPeriodModel->insert($data)) {
            return redirect()->to(base_url('workplan-period'))->with('success', 'Workplan period created successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create workplan period.');
        }
    }

    /**
     * Show workplan period details
     */
    public function show($id)
    {
        $workplanPeriod = $this->workplanPeriodModel->getWorkplanPeriodWithDetails($id);
        
        if (!$workplanPeriod) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Workplan period not found');
        }

        $data = [
            'title' => 'Workplan Period Details',
            'workplan_period' => $workplanPeriod
        ];

        return view('workplan_period/workplan_period_show', $data);
    }

    /**
     * Show form for editing workplan period
     */
    public function edit($id)
    {
        $workplanPeriod = $this->workplanPeriodModel->find($id);
        
        if (!$workplanPeriod) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Workplan period not found');
        }

        $data = [
            'title' => 'Edit Workplan Period',
            'workplan_period' => $workplanPeriod,
            'users' => $this->userModel->findAll(),
            'duty_instructions' => $this->dutyInstructionsModel->findAll()
        ];

        return view('workplan_period/workplan_period_edit', $data);
    }

    /**
     * Update workplan period
     */
    public function update($id)
    {
        $workplanPeriod = $this->workplanPeriodModel->find($id);
        
        if (!$workplanPeriod) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Workplan period not found');
        }

        $rules = [
            'user_id' => 'required|integer',
            'title' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'duty_instruction_id' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'user_id' => $this->request->getPost('user_id'),
            'duty_instruction_id' => $this->request->getPost('duty_instruction_id') ?: null,
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'updated_by' => session()->get('user_id')
        ];

        // Handle file upload
        $file = $this->request->getFile('workplan_period_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete old file if exists
            if (isset($workplanPeriod['workplan_period_filepath']) && $workplanPeriod['workplan_period_filepath'] && file_exists(ROOTPATH . $workplanPeriod['workplan_period_filepath'])) {
                unlink(ROOTPATH . $workplanPeriod['workplan_period_filepath']);
            }
            
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/workplan_periods', $newName);
            $data['workplan_period_filepath'] = 'public/uploads/workplan_periods/' . $newName;
        }

        if ($this->workplanPeriodModel->update($id, $data)) {
            return redirect()->to(base_url('workplan-period'))->with('success', 'Workplan period updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update workplan period.');
        }
    }

    /**
     * Delete workplan period
     */
    public function delete($id)
    {
        $workplanPeriod = $this->workplanPeriodModel->find($id);
        
        if (!$workplanPeriod) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Workplan period not found');
        }

        $data = [
            'deleted_by' => session()->get('user_id')
        ];

        if ($this->workplanPeriodModel->update($id, $data) && $this->workplanPeriodModel->delete($id)) {
            return redirect()->to(base_url('workplan-period'))->with('success', 'Workplan period deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete workplan period.');
        }
    }

    /**
     * Update workplan period status
     */
    public function updateStatus($id)
    {
        $workplanPeriod = $this->workplanPeriodModel->find($id);
        
        if (!$workplanPeriod) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Workplan period not found');
        }

        $status = $this->request->getPost('status');
        $remarks = $this->request->getPost('remarks');

        if ($this->workplanPeriodModel->updateStatus($id, $status, $remarks)) {
            return redirect()->back()->with('success', 'Workplan period status updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update workplan period status.');
        }
    }
}
