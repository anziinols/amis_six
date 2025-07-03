<?php

namespace App\Controllers;

use App\Models\CommodityProductionModel;
use App\Models\CommoditiesModel;
use App\Services\PdfService;
use CodeIgniter\Controller;

/**
 * CommodityReportsController
 * 
 * Handles the reporting functionality for commodity production.
 * Displays visual reports, charts, graphs, trends and data tables for commodity production analysis.
 * 
 * @package App\Controllers
 */
class CommodityReportsController extends Controller
{
    protected $commodityProductionModel;
    protected $commoditiesModel;

    /**
     * Constructor initializes models
     */
    public function __construct()
    {
        $this->commodityProductionModel = new CommodityProductionModel();
        $this->commoditiesModel = new CommoditiesModel();
    }

    /**
     * Display the Commodity Production Reports (Read-only)
     */
    public function index()
    {
        // Get all commodity production data with details
        $productions = $this->commodityProductionModel->getAllCommodityProductionWithDetails();
        
        // Get all commodities
        $commodities = $this->commoditiesModel->getAllCommodities();
        
        // Get production summary by commodity
        $productionSummary = $this->commodityProductionModel->getProductionSummaryByCommodity();
        
        // Get exported vs domestic production
        $exportedProduction = $this->commodityProductionModel->getExportedProduction();
        $domesticProduction = $this->commodityProductionModel->getDomesticProduction();
        
        // Prepare data for charts
        $chartData = $this->prepareChartData($productions, $commodities, $productionSummary, $exportedProduction, $domesticProduction);

        // Pass all data to the view
        return view('reports_commodity/reports_commodity_index', [
            'title' => 'Commodity Production Reports',
            'productions' => $productions,
            'commodities' => $commodities,
            'productionSummary' => $productionSummary,
            'exportedProduction' => $exportedProduction,
            'domesticProduction' => $domesticProduction,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Prepare chart data for visualization
     */
    private function prepareChartData($productions, $commodities, $productionSummary, $exportedProduction, $domesticProduction)
    {
        $chartData = [];

        // 1. Production by Commodity (Pie Chart)
        $commodityProduction = [];
        foreach ($productionSummary as $summary) {
            $commodityProduction[$summary['commodity_name']] = $summary['total_quantity'];
        }
        $chartData['commodityProduction'] = $commodityProduction;

        // 2. Export vs Domestic Distribution (Pie Chart)
        $exportVsDomestic = [
            'exported' => count($exportedProduction),
            'domestic' => count($domesticProduction)
        ];
        $chartData['exportVsDomestic'] = $exportVsDomestic;

        // 3. Monthly Production Trends (Line Chart)
        $monthlyProduction = array_fill(0, 12, 0);
        foreach ($productions as $production) {
            $month = date('n', strtotime($production['created_at'])) - 1; // 0-based index
            $monthlyProduction[$month] += $production['quantity'];
        }
        $chartData['monthlyProduction'] = $monthlyProduction;

        // 4. Production by Unit of Measurement (Bar Chart)
        $unitProduction = [];
        foreach ($productionSummary as $summary) {
            $unit = $summary['unit_of_measurement'] ?: 'Not Specified';
            if (!isset($unitProduction[$unit])) {
                $unitProduction[$unit] = 0;
            }
            $unitProduction[$unit] += $summary['total_quantity'];
        }
        $chartData['unitProduction'] = $unitProduction;

        // 5. Top Producing Commodities (Bar Chart)
        $topCommodities = [];
        foreach ($productionSummary as $summary) {
            $topCommodities[$summary['commodity_name']] = $summary['total_quantity'];
        }
        arsort($topCommodities);
        $topCommodities = array_slice($topCommodities, 0, 10, true); // Top 10
        $chartData['topCommodities'] = $topCommodities;

        // 6. Production Records Count by Commodity
        $recordCounts = [];
        foreach ($productionSummary as $summary) {
            $recordCounts[$summary['commodity_name']] = $summary['record_count'];
        }
        $chartData['recordCounts'] = $recordCounts;

        // 7. Quarterly Production Analysis
        $quarterlyProduction = [0, 0, 0, 0]; // Q1, Q2, Q3, Q4
        foreach ($productions as $production) {
            $month = date('n', strtotime($production['created_at']));
            $quarter = ceil($month / 3) - 1; // 0-based index
            $quarterlyProduction[$quarter] += $production['quantity'];
        }
        $chartData['quarterlyProduction'] = $quarterlyProduction;

        // 8. Average Production per Record by Commodity
        $avgProduction = [];
        foreach ($productionSummary as $summary) {
            if ($summary['record_count'] > 0) {
                $avgProduction[$summary['commodity_name']] = $summary['total_quantity'] / $summary['record_count'];
            }
        }
        $chartData['avgProduction'] = $avgProduction;

        // 9. Production Trends by Export Status
        $exportTrends = ['exported' => array_fill(0, 12, 0), 'domestic' => array_fill(0, 12, 0)];
        foreach ($productions as $production) {
            $month = date('n', strtotime($production['created_at'])) - 1;
            if ($production['is_exported']) {
                $exportTrends['exported'][$month] += $production['quantity'];
            } else {
                $exportTrends['domestic'][$month] += $production['quantity'];
            }
        }
        $chartData['exportTrends'] = $exportTrends;

        // 10. Recent Activity (Last 30 days)
        $recentActivity = [];
        $thirtyDaysAgo = date('Y-m-d', strtotime('-30 days'));
        foreach ($productions as $production) {
            if ($production['created_at'] >= $thirtyDaysAgo) {
                $date = date('M d', strtotime($production['created_at']));
                if (!isset($recentActivity[$date])) {
                    $recentActivity[$date] = 0;
                }
                $recentActivity[$date] += $production['quantity'];
            }
        }
        $chartData['recentActivity'] = $recentActivity;

        return $chartData;
    }

    /**
     * Get production statistics
     */
    private function getProductionStatistics($productions, $commodities)
    {
        $stats = [];
        
        // Total production records
        $stats['totalRecords'] = count($productions);
        
        // Total commodities with production
        $commoditiesWithProduction = array_unique(array_column($productions, 'commodity_id'));
        $stats['activeCommodities'] = count($commoditiesWithProduction);
        
        // Total quantity produced
        $stats['totalQuantity'] = array_sum(array_column($productions, 'quantity'));
        
        // Export vs Domestic ratio
        $exportedCount = count(array_filter($productions, function($p) { return $p['is_exported']; }));
        $stats['exportedCount'] = $exportedCount;
        $stats['domesticCount'] = $stats['totalRecords'] - $exportedCount;
        
        // Average production per record
        $stats['avgProduction'] = $stats['totalRecords'] > 0 ? $stats['totalQuantity'] / $stats['totalRecords'] : 0;
        
        // Most productive commodity
        $commodityTotals = [];
        foreach ($productions as $production) {
            $commodityName = $production['commodity_name'];
            if (!isset($commodityTotals[$commodityName])) {
                $commodityTotals[$commodityName] = 0;
            }
            $commodityTotals[$commodityName] += $production['quantity'];
        }
        
        if (!empty($commodityTotals)) {
            arsort($commodityTotals);
            $stats['topCommodity'] = array_key_first($commodityTotals);
            $stats['topCommodityQuantity'] = reset($commodityTotals);
        } else {
            $stats['topCommodity'] = 'N/A';
            $stats['topCommodityQuantity'] = 0;
        }
        
        // Current month production
        $currentMonth = date('Y-m');
        $currentMonthProduction = array_filter($productions, function($p) use ($currentMonth) {
            return date('Y-m', strtotime($p['created_at'])) === $currentMonth;
        });
        $stats['currentMonthRecords'] = count($currentMonthProduction);
        $stats['currentMonthQuantity'] = array_sum(array_column($currentMonthProduction, 'quantity'));
        
        return $stats;
    }

    /**
     * Export commodity report as PDF
     *
     * @return mixed
     */
    public function exportPdf()
    {
        try {
            // Get all commodity production data with details
            $productions = $this->commodityProductionModel->getAllCommodityProductionWithDetails();

            // Get all commodities
            $commodities = $this->commoditiesModel->getAllCommodities();

            // Get production summary by commodity
            $productionSummary = $this->commodityProductionModel->getProductionSummaryByCommodity();

            // Get exported vs domestic production
            $exportedProduction = $this->commodityProductionModel->getExportedProduction();
            $domesticProduction = $this->commodityProductionModel->getDomesticProduction();

            // Prepare chart data
            $chartData = $this->prepareChartData($productions, $commodities, $productionSummary, $exportedProduction, $domesticProduction);

            // Prepare data array for PDF service
            $data = [
                'productions' => $productions,
                'commodities' => $commodities,
                'productionSummary' => $productionSummary,
                'exportedProduction' => $exportedProduction,
                'domesticProduction' => $domesticProduction
            ];

            // Generate PDF using PdfService
            $pdfService = new PdfService();
            return $pdfService->generateReportPdf('commodity', $data, $chartData);

        } catch (\Exception $e) {
            log_message('error', 'Commodity Report PDF Export Error: ' . $e->getMessage());
            return redirect()->to('/reports/commodity')->with('error', 'Failed to generate PDF report. Please try again.');
        }
    }
}
