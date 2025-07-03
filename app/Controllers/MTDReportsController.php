<?php
// app/Controllers/MTDReportsController.php

namespace App\Controllers;

use App\Models\MtdpModel;
use App\Models\MtdpSpaModel;
use App\Models\MtdpDipModel;
use App\Models\MtdpKraModel;
use App\Models\MtdpSpecificAreaModel;
use App\Models\MtdpInvestmentsModel;
use App\Models\MtdpStrategiesModel;
use App\Models\MtdpIndicatorsModel;
use App\Services\PdfService;
use CodeIgniter\Controller;

class MTDReportsController extends Controller
{
    /**
     * Display the MTDP Plans Report (Read-only)
     */
    public function index()
    {
        $mtdpModel = new MtdpModel();
        $spaModel = new MtdpSpaModel();
        $dipModel = new MtdpDipModel();
        $kraModel = new MtdpKraModel();
        $saModel = new MtdpSpecificAreaModel();
        $investmentsModel = new MtdpInvestmentsModel();
        $strategiesModel = new MtdpStrategiesModel();
        $indicatorsModel = new MtdpIndicatorsModel();

        // Fetch all data (simple, straight forward)
        $plans = $mtdpModel->findAll();
        $spas = $spaModel->findAll();
        $dips = $dipModel->findAll();
        $kras = $kraModel->findAll();
        $specific_areas = $saModel->findAll();
        $investments = $investmentsModel->findAll();
        $strategies = $strategiesModel->findAll();
        $indicators = $indicatorsModel->findAll();

        // Prepare data for charts
        $chartData = $this->prepareChartData($plans, $spas, $dips, $kras, $specific_areas, $investments, $strategies, $indicators);

        // Pass all data to the view
        return view('reports_mtdp/reports_mtdp_index', [
            'title' => 'MTDP Plans Report',
            'plans' => $plans,
            'spas' => $spas,
            'dips' => $dips,
            'kras' => $kras,
            'specific_areas' => $specific_areas,
            'investments' => $investments,
            'strategies' => $strategies,
            'indicators' => $indicators,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Prepare data for charts and graphs
     */
    private function prepareChartData($plans, $spas, $dips, $kras, $specific_areas, $investments, $strategies, $indicators)
    {
        $chartData = [];

        // 1. Investment distribution by year
        $yearlyInvestments = [
            'year_one' => 0,
            'year_two' => 0,
            'year_three' => 0,
            'year_four' => 0,
            'year_five' => 0
        ];

        foreach ($investments as $investment) {
            $yearlyInvestments['year_one'] += (float)($investment['year_one'] ?? 0);
            $yearlyInvestments['year_two'] += (float)($investment['year_two'] ?? 0);
            $yearlyInvestments['year_three'] += (float)($investment['year_three'] ?? 0);
            $yearlyInvestments['year_four'] += (float)($investment['year_four'] ?? 0);
            $yearlyInvestments['year_five'] += (float)($investment['year_five'] ?? 0);
        }

        $chartData['yearlyInvestments'] = $yearlyInvestments;

        // 2. Investment distribution by DIP
        $dipInvestments = [];
        foreach ($dips as $dip) {
            $dipInvestments[$dip['id']] = [
                'title' => $dip['dip_title'],
                'total' => 0
            ];
        }

        foreach ($investments as $investment) {
            if (isset($dipInvestments[$investment['dip_id']])) {
                $total = (float)($investment['year_one'] ?? 0) +
                         (float)($investment['year_two'] ?? 0) +
                         (float)($investment['year_three'] ?? 0) +
                         (float)($investment['year_four'] ?? 0) +
                         (float)($investment['year_five'] ?? 0);
                $dipInvestments[$investment['dip_id']]['total'] += $total;
            }
        }

        $chartData['dipInvestments'] = $dipInvestments;

        // 3. Status distribution
        $statusCounts = [
            'dips' => ['active' => 0, 'inactive' => 0],
            'kras' => ['active' => 0, 'inactive' => 0],
            'specific_areas' => ['active' => 0, 'inactive' => 0],
            'strategies' => ['active' => 0, 'inactive' => 0],
            'indicators' => ['active' => 0, 'inactive' => 0]
        ];

        foreach ($dips as $dip) {
            $statusCounts['dips'][$dip['dip_status'] == 1 ? 'active' : 'inactive']++;
        }

        foreach ($kras as $kra) {
            $statusCounts['kras'][$kra['kra_status'] == 1 ? 'active' : 'inactive']++;
        }

        foreach ($specific_areas as $sa) {
            $statusCounts['specific_areas'][$sa['sa_status'] == 1 ? 'active' : 'inactive']++;
        }

        foreach ($strategies as $strategy) {
            $statusCounts['strategies'][$strategy['strategies_status'] == 1 ? 'active' : 'inactive']++;
        }

        foreach ($indicators as $indicator) {
            $statusCounts['indicators'][$indicator['indicators_status'] == 1 ? 'active' : 'inactive']++;
        }

        $chartData['statusCounts'] = $statusCounts;

        // 4. Count entities by MTDP plan
        $entitiesByPlan = [];
        foreach ($plans as $plan) {
            $entitiesByPlan[$plan['id']] = [
                'title' => $plan['title'],
                'spas' => 0,
                'dips' => 0,
                'kras' => 0,
                'specific_areas' => 0,
                'investments' => 0,
                'strategies' => 0,
                'indicators' => 0
            ];
        }

        foreach ($spas as $spa) {
            if (isset($entitiesByPlan[$spa['mtdp_id']])) {
                $entitiesByPlan[$spa['mtdp_id']]['spas']++;
            }
        }

        foreach ($dips as $dip) {
            if (isset($entitiesByPlan[$dip['mtdp_id']])) {
                $entitiesByPlan[$dip['mtdp_id']]['dips']++;
            }
        }

        foreach ($kras as $kra) {
            if (isset($entitiesByPlan[$kra['mtdp_id']])) {
                $entitiesByPlan[$kra['mtdp_id']]['kras']++;
            }
        }

        foreach ($specific_areas as $sa) {
            if (isset($entitiesByPlan[$sa['mtdp_id']])) {
                $entitiesByPlan[$sa['mtdp_id']]['specific_areas']++;
            }
        }

        foreach ($investments as $investment) {
            if (isset($entitiesByPlan[$investment['mtdp_id']])) {
                $entitiesByPlan[$investment['mtdp_id']]['investments']++;
            }
        }

        foreach ($strategies as $strategy) {
            if (isset($entitiesByPlan[$strategy['mtdp_id']])) {
                $entitiesByPlan[$strategy['mtdp_id']]['strategies']++;
            }
        }

        foreach ($indicators as $indicator) {
            if (isset($entitiesByPlan[$indicator['mtdp_id']])) {
                $entitiesByPlan[$indicator['mtdp_id']]['indicators']++;
            }
        }

        $chartData['entitiesByPlan'] = $entitiesByPlan;

        return $chartData;
    }

    /**
     * Export MTDP report as PDF
     *
     * @return mixed
     */
    public function exportPdf()
    {
        try {
            $mtdpModel = new MtdpModel();
            $spaModel = new MtdpSpaModel();
            $dipModel = new MtdpDipModel();
            $kraModel = new MtdpKraModel();
            $saModel = new MtdpSpecificAreaModel();
            $investmentsModel = new MtdpInvestmentsModel();
            $strategiesModel = new MtdpStrategiesModel();
            $indicatorsModel = new MtdpIndicatorsModel();

            // Get all MTDP data
            $plans = $mtdpModel->findAll();
            $spas = $spaModel->findAll();
            $dips = $dipModel->findAll();
            $kras = $kraModel->findAll();
            $specific_areas = $saModel->findAll();
            $investments = $investmentsModel->findAll();
            $strategies = $strategiesModel->findAll();
            $indicators = $indicatorsModel->findAll();

            // Prepare chart data
            $chartData = $this->prepareChartData($plans, $spas, $dips, $kras, $specific_areas, $investments, $strategies, $indicators);

            // Prepare data array for PDF service
            $data = [
                'plans' => $plans,
                'spas' => $spas,
                'dips' => $dips,
                'kras' => $kras,
                'specific_areas' => $specific_areas,
                'investments' => $investments,
                'strategies' => $strategies,
                'indicators' => $indicators
            ];

            // Generate PDF using PdfService
            $pdfService = new PdfService();
            return $pdfService->generateReportPdf('mtdp', $data, $chartData);

        } catch (\Exception $e) {
            log_message('error', 'MTDP Report PDF Export Error: ' . $e->getMessage());
            return redirect()->to('/reports/mtdp')->with('error', 'Failed to generate PDF report. Please try again.');
        }
    }
}