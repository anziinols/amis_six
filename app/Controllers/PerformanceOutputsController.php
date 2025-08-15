<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PerformanceOutputsModel;
use App\Models\PerformanceIndicatorsKraModel;
use App\Models\WorkplanPeriodModel;
use App\Models\UserModel;

/**
 * PerformanceOutputsController
 *
 * Handles CRUD operations for performance outputs
 */
class PerformanceOutputsController extends BaseController
{
    protected $performanceOutputsModel;
    protected $performanceIndicatorsKraModel;
    protected $workplanPeriodModel;
    protected $userModel;
    protected $helpers = ['form', 'url'];

    public function __construct()
    {
        $this->performanceOutputsModel = new PerformanceOutputsModel();
        $this->performanceIndicatorsKraModel = new PerformanceIndicatorsKraModel();
        $this->workplanPeriodModel = new WorkplanPeriodModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display list of outputs for a performance indicator
     */
    public function indexByIndicator($indicatorId)
    {
        $indicator = $this->performanceIndicatorsKraModel->find($indicatorId);
        
        if (!$indicator || $indicator['type'] !== 'performance_indicator') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Performance Indicator not found');
        }

        $kra = $this->performanceIndicatorsKraModel->find($indicator['parent_id']);

        $data = [
            'title' => 'Performance Outputs',
            'indicator' => $indicator,
            'kra' => $kra,
            'outputs' => $this->performanceOutputsModel->getByKraPerformanceIndicator($indicatorId)
        ];

        return view('performance_outputs/performance_outputs_index', $data);
    }

    /**
     * Show form for creating new output
     */
    public function newByIndicator($indicatorId)
    {
        $indicator = $this->performanceIndicatorsKraModel->find($indicatorId);

        if (!$indicator || $indicator['type'] !== 'performance_indicator') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Performance Indicator not found');
        }

        $kra = $this->performanceIndicatorsKraModel->find($indicator['parent_id']);

        // Get the performance period to access user_id
        $performancePeriod = $this->workplanPeriodModel->find($indicator['workplan_period_id']);

        $data = [
            'title' => 'Create Performance Output',
            'indicator' => $indicator,
            'kra' => $kra,
            'performance_period' => $performancePeriod
        ];

        return view('performance_outputs/performance_outputs_create', $data);
    }

    /**
     * Create new output
     */
    public function createByIndicator($indicatorId)
    {
        $indicator = $this->performanceIndicatorsKraModel->find($indicatorId);

        if (!$indicator || $indicator['type'] !== 'performance_indicator') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Performance Indicator not found');
        }

        // Get the performance period to access user_id
        $performancePeriod = $this->workplanPeriodModel->find($indicator['workplan_period_id']);

        if (!$performancePeriod) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Performance Period not found');
        }

        $rules = [
            'output' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'quantity' => 'required|max_length[20]',
            'unit_of_measurement' => 'required|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'kra_performance_indicator_id' => $indicatorId,
            'user_id' => $performancePeriod['user_id'], // Automatically get user_id from performance period
            'output' => $this->request->getPost('output'),
            'description' => $this->request->getPost('description'),
            'quantity' => $this->request->getPost('quantity'),
            'unit_of_measurement' => $this->request->getPost('unit_of_measurement'),
            'created_by' => session()->get('user_id')
        ];

        if ($this->performanceOutputsModel->insert($data)) {
            return redirect()->to(base_url("performance-output/indicators/{$indicatorId}/outputs"))->with('success', 'Performance output created successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create performance output.');
        }
    }

    /**
     * Show specific output
     */
    public function show($id)
    {
        $output = $this->performanceOutputsModel->getOutputWithDetails($id);
        
        if (!$output) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Performance output not found');
        }

        $data = [
            'title' => 'Performance Output Details',
            'output' => $output
        ];

        return view('performance_outputs/performance_outputs_show', $data);
    }

    /**
     * Show form for editing output
     */
    public function edit($id)
    {
        $output = $this->performanceOutputsModel->find($id);

        if (!$output) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Performance output not found');
        }

        $indicator = $this->performanceIndicatorsKraModel->find($output['kra_performance_indicator_id']);

        // Get the performance period to access user_id
        $performancePeriod = $this->workplanPeriodModel->find($indicator['workplan_period_id']);

        $data = [
            'title' => 'Edit Performance Output',
            'output' => $output,
            'indicator' => $indicator,
            'performance_period' => $performancePeriod
        ];

        return view('performance_outputs/performance_outputs_edit', $data);
    }

    /**
     * Update output
     */
    public function update($id)
    {
        $output = $this->performanceOutputsModel->find($id);

        if (!$output) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Performance output not found');
        }

        $indicator = $this->performanceIndicatorsKraModel->find($output['kra_performance_indicator_id']);

        // Get the performance period to access user_id
        $performancePeriod = $this->workplanPeriodModel->find($indicator['workplan_period_id']);

        if (!$performancePeriod) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Performance Period not found');
        }

        $rules = [
            'output' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'quantity' => 'required|max_length[20]',
            'unit_of_measurement' => 'required|max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'user_id' => $performancePeriod['user_id'], // Automatically get user_id from performance period
            'output' => $this->request->getPost('output'),
            'description' => $this->request->getPost('description'),
            'quantity' => $this->request->getPost('quantity'),
            'unit_of_measurement' => $this->request->getPost('unit_of_measurement'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->performanceOutputsModel->update($id, $data)) {
            return redirect()->to(base_url("performance-output/indicators/{$output['kra_performance_indicator_id']}/outputs"))->with('success', 'Performance output updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update performance output.');
        }
    }

    /**
     * Delete output
     */
    public function delete($id)
    {
        $output = $this->performanceOutputsModel->find($id);
        
        if (!$output) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Performance output not found');
        }

        $data = [
            'deleted_by' => session()->get('user_id')
        ];

        if ($this->performanceOutputsModel->update($id, $data) && $this->performanceOutputsModel->delete($id)) {
            return redirect()->to(base_url("performance-output/indicators/{$output['kra_performance_indicator_id']}/outputs"))->with('success', 'Performance output deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete performance output.');
        }
    }

    /**
     * Update output status
     */
    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        $remarks = $this->request->getPost('status_remarks');

        if ($this->performanceOutputsModel->updateStatus($id, $status, $remarks)) {
            return redirect()->back()->with('success', 'Status updated successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update status.');
        }
    }
}
