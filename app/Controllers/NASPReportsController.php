<?php
// app/Controllers/NASPReportsController.php

namespace App\Controllers;

use App\Models\NaspModel;
use App\Services\PdfService;
use CodeIgniter\Controller;

class NASPReportsController extends Controller
{
    protected $naspModel;

    public function __construct()
    {
        $this->naspModel = new NaspModel();
    }

    /**
     * Display the NASP Plans Report (Read-only)
     */
    public function index()
    {
        // Get all NASP data by type
        $plans = $this->naspModel->where('type', 'plans')->findAll();
        $apas = $this->naspModel->where('type', 'kras')->findAll();
        $dips = $this->naspModel->where('type', 'objectives')->findAll();
        $specificAreas = $this->naspModel->where('type', 'specific_areas')->findAll();
        $outputs = $this->naspModel->where('type', 'outputs')->findAll();
        $indicators = $this->naspModel->where('type', 'indicators')->findAll();

        // Prepare data for charts
        $chartData = $this->prepareChartData($plans, $apas, $dips, $specificAreas, $outputs, $indicators);

        // Pass all data to the view
        return view('report_nasp/report_nasp_index', [
            'title' => 'NASP Plans Report',
            'plans' => $plans,
            'apas' => $apas,
            'dips' => $dips,
            'specificAreas' => $specificAreas,
            'outputs' => $outputs,
            'indicators' => $indicators,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Prepare data for charts and graphs
     */
    private function prepareChartData($plans, $apas, $dips, $specificAreas, $outputs, $indicators)
    {
        $chartData = [];

        // 1. Status distribution
        $statusCounts = [
            'plans' => ['active' => 0, 'inactive' => 0],
            'apas' => ['active' => 0, 'inactive' => 0],
            'dips' => ['active' => 0, 'inactive' => 0],
            'specificAreas' => ['active' => 0, 'inactive' => 0],
            'outputs' => ['active' => 0, 'inactive' => 0],
            'indicators' => ['active' => 0, 'inactive' => 0]
        ];

        foreach ($plans as $plan) {
            $statusCounts['plans'][$plan['nasp_status'] == 1 ? 'active' : 'inactive']++;
        }

        foreach ($apas as $apa) {
            $statusCounts['apas'][$apa['nasp_status'] == 1 ? 'active' : 'inactive']++;
        }

        foreach ($dips as $dip) {
            $statusCounts['dips'][$dip['nasp_status'] == 1 ? 'active' : 'inactive']++;
        }

        foreach ($specificAreas as $sa) {
            $statusCounts['specificAreas'][$sa['nasp_status'] == 1 ? 'active' : 'inactive']++;
        }

        foreach ($outputs as $output) {
            $statusCounts['outputs'][$output['nasp_status'] == 1 ? 'active' : 'inactive']++;
        }

        foreach ($indicators as $indicator) {
            $statusCounts['indicators'][$indicator['nasp_status'] == 1 ? 'active' : 'inactive']++;
        }

        $chartData['statusCounts'] = $statusCounts;

        // 2. Count entities by NASP plan
        $entitiesByPlan = [];
        foreach ($plans as $plan) {
            $entitiesByPlan[$plan['id']] = [
                'title' => $plan['title'],
                'apas' => 0,
                'dips' => 0,
                'specificAreas' => 0,
                'outputs' => 0,
                'indicators' => 0
            ];
        }

        // Count APAs by plan
        foreach ($apas as $apa) {
            if (isset($entitiesByPlan[$apa['parent_id']])) {
                $entitiesByPlan[$apa['parent_id']]['apas']++;
            }
        }

        // Count DIPs by APA and associate with plan
        foreach ($dips as $dip) {
            foreach ($apas as $apa) {
                if ($dip['parent_id'] == $apa['id'] && isset($entitiesByPlan[$apa['parent_id']])) {
                    $entitiesByPlan[$apa['parent_id']]['dips']++;
                    break;
                }
            }
        }

        // Count Specific Areas and associate with plan
        foreach ($specificAreas as $sa) {
            foreach ($dips as $dip) {
                if ($sa['parent_id'] == $dip['id']) {
                    foreach ($apas as $apa) {
                        if ($dip['parent_id'] == $apa['id'] && isset($entitiesByPlan[$apa['parent_id']])) {
                            $entitiesByPlan[$apa['parent_id']]['specificAreas']++;
                            break 2;
                        }
                    }
                }
            }
        }

        // Count Outputs and associate with plan
        foreach ($outputs as $output) {
            foreach ($specificAreas as $sa) {
                if ($output['parent_id'] == $sa['id']) {
                    foreach ($dips as $dip) {
                        if ($sa['parent_id'] == $dip['id']) {
                            foreach ($apas as $apa) {
                                if ($dip['parent_id'] == $apa['id'] && isset($entitiesByPlan[$apa['parent_id']])) {
                                    $entitiesByPlan[$apa['parent_id']]['outputs']++;
                                    break 3;
                                }
                            }
                        }
                    }
                }
            }
        }

        // Count Indicators and associate with plan
        foreach ($indicators as $indicator) {
            foreach ($outputs as $output) {
                if ($indicator['parent_id'] == $output['id']) {
                    foreach ($specificAreas as $sa) {
                        if ($output['parent_id'] == $sa['id']) {
                            foreach ($dips as $dip) {
                                if ($sa['parent_id'] == $dip['id']) {
                                    foreach ($apas as $apa) {
                                        if ($dip['parent_id'] == $apa['id'] && isset($entitiesByPlan[$apa['parent_id']])) {
                                            $entitiesByPlan[$apa['parent_id']]['indicators']++;
                                            break 4;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $chartData['entitiesByPlan'] = $entitiesByPlan;

        // 3. Distribution of APAs by plan
        $apasByPlan = [];
        foreach ($plans as $plan) {
            $apasByPlan[$plan['id']] = [
                'title' => $plan['title'],
                'count' => 0
            ];
        }

        foreach ($apas as $apa) {
            if (isset($apasByPlan[$apa['parent_id']])) {
                $apasByPlan[$apa['parent_id']]['count']++;
            }
        }

        $chartData['apasByPlan'] = $apasByPlan;

        // 4. Distribution of DIPs by APA
        $dipsByApa = [];
        foreach ($apas as $apa) {
            $dipsByApa[$apa['id']] = [
                'title' => $apa['title'],
                'count' => 0
            ];
        }

        foreach ($dips as $dip) {
            if (isset($dipsByApa[$dip['parent_id']])) {
                $dipsByApa[$dip['parent_id']]['count']++;
            }
        }

        $chartData['dipsByApa'] = $dipsByApa;

        return $chartData;
    }

    /**
     * Export NASP report as PDF
     *
     * @return mixed
     */
    public function exportPdf()
    {
        try {
            // Get all NASP data by type
            $plans = $this->naspModel->where('type', 'plans')->findAll();
            $apas = $this->naspModel->where('type', 'kras')->findAll();
            $dips = $this->naspModel->where('type', 'objectives')->findAll();
            $specificAreas = $this->naspModel->where('type', 'specific_areas')->findAll();
            $outputs = $this->naspModel->where('type', 'outputs')->findAll();
            $indicators = $this->naspModel->where('type', 'indicators')->findAll();

            // Prepare chart data
            $chartData = $this->prepareChartData($plans, $apas, $dips, $specificAreas, $outputs, $indicators);

            // Prepare data array for PDF service
            $data = [
                'plans' => $plans,
                'apas' => $apas,
                'dips' => $dips,
                'specificAreas' => $specificAreas,
                'outputs' => $outputs,
                'indicators' => $indicators
            ];

            // Generate PDF using PdfService
            $pdfService = new PdfService();
            return $pdfService->generateReportPdf('nasp', $data, $chartData);

        } catch (\Exception $e) {
            log_message('error', 'NASP Report PDF Export Error: ' . $e->getMessage());
            return redirect()->to('/reports/nasp')->with('error', 'Failed to generate PDF report. Please try again.');
        }
    }
}
