<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PerformanceIndicatorsKraModel;
use App\Models\WorkplanPeriodModel;

/**
 * PerformanceIndicatorsKraController
 *
 * Handles CRUD operations for KRAs and Performance Indicators
 */
class PerformanceIndicatorsKraController extends BaseController
{
    protected $performanceIndicatorsKraModel;
    protected $workplanPeriodModel;
    protected $helpers = ['form', 'url'];

    public function __construct()
    {
        $this->performanceIndicatorsKraModel = new PerformanceIndicatorsKraModel();
        $this->workplanPeriodModel = new WorkplanPeriodModel();
    }

    /**
     * Display list of KRAs for a performance period
     */
    public function indexKra($performancePeriodId)
    {
        $performancePeriod = $this->workplanPeriodModel->find($performancePeriodId);
        
        if (!$performancePeriod) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Performance period not found');
        }

        $data = [
            'title' => 'Key Result Areas (KRAs)',
            'performance_period' => $performancePeriod,
            'kras' => $this->performanceIndicatorsKraModel->getKRAs($performancePeriodId)
        ];

        return view('performance_indicators_kra/performance_indicators_kra_index_kra', $data);
    }

    /**
     * Show form for creating new KRA
     */
    public function newKra($performancePeriodId)
    {
        $performancePeriod = $this->workplanPeriodModel->find($performancePeriodId);
        
        if (!$performancePeriod) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Performance period not found');
        }

        $data = [
            'title' => 'Create KRA',
            'performance_period' => $performancePeriod
        ];

        return view('performance_indicators_kra/performance_indicators_kra_create_kra', $data);
    }

    /**
     * Create new KRA
     */
    public function createKra($performancePeriodId)
    {
        $rules = [
            'code' => 'permit_empty|max_length[100]',
            'item' => 'required|max_length[255]',
            'description' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'workplan_period_id' => $performancePeriodId,
            'type' => 'kra',
            'code' => $this->request->getPost('code'),
            'item' => $this->request->getPost('item'),
            'description' => $this->request->getPost('description'),
            'created_by' => session()->get('user_id')
        ];

        if ($this->performanceIndicatorsKraModel->insert($data)) {
            return redirect()->to(base_url("performance-output/{$performancePeriodId}/kra"))->with('success', 'KRA created successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create KRA.');
        }
    }

    /**
     * Display list of Performance Indicators for a KRA
     */
    public function indexIndicators($kraId)
    {
        $kra = $this->performanceIndicatorsKraModel->find($kraId);
        
        if (!$kra || $kra['type'] !== 'kra') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('KRA not found');
        }

        $performancePeriod = $this->workplanPeriodModel->find($kra['workplan_period_id']);

        $data = [
            'title' => 'Performance Indicators',
            'kra' => $kra,
            'performance_period' => $performancePeriod,
            'indicators' => $this->performanceIndicatorsKraModel->getPerformanceIndicators($kraId)
        ];

        return view('performance_indicators_kra/performance_indicators_kra_index_indicators', $data);
    }

    /**
     * Show form for creating new Performance Indicator
     */
    public function newIndicator($kraId)
    {
        $kra = $this->performanceIndicatorsKraModel->find($kraId);
        
        if (!$kra || $kra['type'] !== 'kra') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('KRA not found');
        }

        $performancePeriod = $this->workplanPeriodModel->find($kra['workplan_period_id']);

        $data = [
            'title' => 'Create Performance Indicator',
            'kra' => $kra,
            'performance_period' => $performancePeriod
        ];

        return view('performance_indicators_kra/performance_indicators_kra_create_indicator', $data);
    }

    /**
     * Create new Performance Indicator
     */
    public function createIndicator($kraId)
    {
        $kra = $this->performanceIndicatorsKraModel->find($kraId);
        
        if (!$kra || $kra['type'] !== 'kra') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('KRA not found');
        }

        $rules = [
            'code' => 'permit_empty|max_length[100]',
            'item' => 'required|max_length[255]',
            'description' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'workplan_period_id' => $kra['workplan_period_id'],
            'parent_id' => $kraId,
            'type' => 'performance_indicator',
            'code' => $this->request->getPost('code'),
            'item' => $this->request->getPost('item'),
            'description' => $this->request->getPost('description'),
            'created_by' => session()->get('user_id')
        ];

        if ($this->performanceIndicatorsKraModel->insert($data)) {
            return redirect()->to(base_url("performance-output/kra/{$kraId}/indicators"))->with('success', 'Performance Indicator created successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create Performance Indicator.');
        }
    }

    /**
     * Show specific KRA/PI
     */
    public function show($id)
    {
        $item = $this->performanceIndicatorsKraModel->getWithDetails($id);
        
        if (!$item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Item not found');
        }

        $data = [
            'title' => ucfirst($item['type']) . ' Details',
            'item' => $item
        ];

        return view('performance_indicators_kra/performance_indicators_kra_show', $data);
    }

    /**
     * Show form for editing KRA/PI
     */
    public function edit($id)
    {
        $item = $this->performanceIndicatorsKraModel->find($id);
        
        if (!$item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Item not found');
        }

        $data = [
            'title' => 'Edit ' . ucfirst($item['type']),
            'item' => $item
        ];

        return view('performance_indicators_kra/performance_indicators_kra_edit', $data);
    }

    /**
     * Update KRA/PI
     */
    public function update($id)
    {
        $item = $this->performanceIndicatorsKraModel->find($id);
        
        if (!$item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Item not found');
        }

        $rules = [
            'code' => 'permit_empty|max_length[100]',
            'item' => 'required|max_length[255]',
            'description' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'code' => $this->request->getPost('code'),
            'item' => $this->request->getPost('item'),
            'description' => $this->request->getPost('description'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->performanceIndicatorsKraModel->update($id, $data)) {
            $redirectUrl = $item['type'] === 'kra'
                ? base_url("performance-output/{$item['workplan_period_id']}/kra")
                : base_url("performance-output/kra/{$item['parent_id']}/indicators");
            
            return redirect()->to($redirectUrl)->with('success', ucfirst($item['type']) . ' updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update ' . $item['type'] . '.');
        }
    }

    /**
     * Delete KRA/PI
     */
    public function delete($id)
    {
        $item = $this->performanceIndicatorsKraModel->find($id);
        
        if (!$item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Item not found');
        }

        $data = [
            'deleted_by' => session()->get('user_id')
        ];

        if ($this->performanceIndicatorsKraModel->update($id, $data) && $this->performanceIndicatorsKraModel->delete($id)) {
            $redirectUrl = $item['type'] === 'kra'
                ? base_url("performance-output/{$item['workplan_period_id']}/kra")
                : base_url("performance-output/kra/{$item['parent_id']}/indicators");
            
            return redirect()->to($redirectUrl)->with('success', ucfirst($item['type']) . ' deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete ' . $item['type'] . '.');
        }
    }
}
