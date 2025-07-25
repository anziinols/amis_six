<?php

namespace App\Controllers;

use App\Models\CommodityProductionModel;
use App\Models\CommoditiesModel;
use App\Models\CommodityPricesModel;
use App\Helpers\PdfHelper;
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
    protected $commodityPricesModel;

    /**
     * Constructor initializes models
     */
    public function __construct()
    {
        $this->commodityProductionModel = new CommodityProductionModel();
        $this->commoditiesModel = new CommoditiesModel();
        $this->commodityPricesModel = new CommodityPricesModel();
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

    /**
     * Display Price Trends Dashboard
     */
    public function priceTrends()
    {
        // Get all commodities for filtering
        $commodities = $this->commoditiesModel->getAllCommodities();

        // Get latest prices for all commodities
        $latestPrices = $this->commodityPricesModel->getLatestPrices();

        // Get average prices by market type (last 6 months)
        $averagePrices = $this->commodityPricesModel->getAveragePricesByMarketType(6);

        // Get price volatility analysis
        $volatilityAnalysis = $this->commodityPricesModel->getPriceVolatilityAnalysis(null, 12);

        // Get monthly price trends for charts
        $monthlyTrends = $this->commodityPricesModel->getMonthlyPriceTrends(null, 12);

        // Prepare chart data for price trends
        $chartData = $this->preparePriceTrendsChartData($monthlyTrends, $volatilityAnalysis, $averagePrices);

        $data = [
            'title' => 'Commodity Price Trends & Market Analysis',
            'commodities' => $commodities,
            'latestPrices' => $latestPrices,
            'averagePrices' => $averagePrices,
            'volatilityAnalysis' => $volatilityAnalysis,
            'monthlyTrends' => $monthlyTrends,
            'chartData' => $chartData
        ];

        return view('reports_commodity/reports_commodity_price_trends', $data);
    }

    /**
     * Get price trends for a specific commodity (AJAX)
     */
    public function getCommodityPriceTrends($commodityId)
    {
        try {
            // Get price trends for the commodity
            $priceTrends = $this->commodityPricesModel->getPriceTrendsByCommodity($commodityId, 12);

            // Get market type comparison
            $marketComparison = $this->commodityPricesModel->getMarketTypeComparison($commodityId, 6);

            // Get price forecast
            $forecastData = $this->commodityPricesModel->getPriceForecastData($commodityId, 'local', 12);

            return $this->response->setJSON([
                'success' => true,
                'priceTrends' => $priceTrends,
                'marketComparison' => $marketComparison,
                'forecastData' => $forecastData
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Price Trends Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load price trends data'
            ]);
        }
    }

    /**
     * Market Analysis Dashboard
     */
    public function marketAnalysis()
    {
        // Get all commodities
        $commodities = $this->commoditiesModel->getAllCommodities();

        // Get comprehensive volatility analysis
        $volatilityAnalysis = $this->commodityPricesModel->getPriceVolatilityAnalysis(null, 12);

        // Get market type comparisons for all commodities
        $marketAnalysisData = [];
        foreach ($commodities as $commodity) {
            $marketComparison = $this->commodityPricesModel->getMarketTypeComparison($commodity['id'], 6);
            if (!empty($marketComparison)) {
                $marketAnalysisData[$commodity['id']] = [
                    'commodity' => $commodity,
                    'market_comparison' => $marketComparison
                ];
            }
        }

        // Get monthly trends for market intelligence
        $monthlyTrends = $this->commodityPricesModel->getMonthlyPriceTrends(null, 12);

        // Prepare market analysis chart data
        $marketChartData = $this->prepareMarketAnalysisChartData($volatilityAnalysis, $marketAnalysisData, $monthlyTrends);

        $data = [
            'title' => 'Commodity Market Analysis & Intelligence',
            'commodities' => $commodities,
            'volatilityAnalysis' => $volatilityAnalysis,
            'marketAnalysisData' => $marketAnalysisData,
            'monthlyTrends' => $monthlyTrends,
            'chartData' => $marketChartData
        ];

        return view('reports_commodity/reports_commodity_market_analysis', $data);
    }

    /**
     * Prepare chart data for price trends
     */
    private function preparePriceTrendsChartData($monthlyTrends, $volatilityAnalysis, $averagePrices)
    {
        $chartData = [];

        // Monthly price trends chart
        $trendsByMarket = [];
        foreach ($monthlyTrends as $trend) {
            $key = $trend['commodity_name'] . ' - ' . ucfirst($trend['market_type']);
            $monthKey = $trend['year'] . '-' . str_pad($trend['month'], 2, '0', STR_PAD_LEFT);

            if (!isset($trendsByMarket[$key])) {
                $trendsByMarket[$key] = [];
            }
            $trendsByMarket[$key][$monthKey] = floatval($trend['avg_price']);
        }

        $chartData['monthlyTrends'] = $trendsByMarket;

        // Volatility analysis chart
        $volatilityLabels = [];
        $volatilityData = [];
        foreach ($volatilityAnalysis as $analysis) {
            $label = $analysis['commodity_name'] . ' (' . ucfirst($analysis['market_type']) . ')';
            $volatilityLabels[] = $label;
            $volatilityData[] = floatval($analysis['volatility_percent']);
        }

        $chartData['volatility'] = [
            'labels' => $volatilityLabels,
            'data' => $volatilityData
        ];

        // Average prices by market type
        $marketTypeData = [];
        foreach ($averagePrices as $price) {
            $marketType = ucfirst($price['market_type']);
            if (!isset($marketTypeData[$marketType])) {
                $marketTypeData[$marketType] = [];
            }
            $marketTypeData[$marketType][] = [
                'commodity' => $price['commodity_name'],
                'price' => floatval($price['avg_price'])
            ];
        }

        $chartData['marketTypes'] = $marketTypeData;

        return $chartData;
    }

    /**
     * Prepare chart data for market analysis
     */
    private function prepareMarketAnalysisChartData($volatilityAnalysis, $marketAnalysisData, $monthlyTrends)
    {
        $chartData = [];

        // Price range analysis
        $priceRangeLabels = [];
        $priceRangeData = [];
        foreach ($volatilityAnalysis as $analysis) {
            $label = $analysis['commodity_name'];
            $priceRangeLabels[] = $label;
            $priceRangeData[] = floatval($analysis['price_range_percent']);
        }

        $chartData['priceRange'] = [
            'labels' => $priceRangeLabels,
            'data' => $priceRangeData
        ];

        // Market type comparison
        $marketComparisonData = [];
        foreach ($marketAnalysisData as $data) {
            $commodity = $data['commodity']['commodity_name'];
            foreach ($data['market_comparison'] as $market) {
                $marketType = ucfirst($market['market_type']);
                if (!isset($marketComparisonData[$marketType])) {
                    $marketComparisonData[$marketType] = [];
                }
                $marketComparisonData[$marketType][] = [
                    'commodity' => $commodity,
                    'avg_price' => floatval($market['avg_price'])
                ];
            }
        }

        $chartData['marketComparison'] = $marketComparisonData;

        return $chartData;
    }

    /**
     * Export Price Trends report as PDF
     */
    public function exportPriceTrendsPdf()
    {
        try {
            // Get all data for PDF
            $latestPrices = $this->commodityPricesModel->getLatestPrices();
            $averagePrices = $this->commodityPricesModel->getAveragePricesByMarketType(6);
            $volatilityAnalysis = $this->commodityPricesModel->getPriceVolatilityAnalysis(null, 12);

            $data = [
                'title' => 'Commodity Price Trends Report',
                'latestPrices' => $latestPrices,
                'averagePrices' => $averagePrices,
                'volatilityAnalysis' => $volatilityAnalysis,
                'generated_at' => date('Y-m-d H:i:s'),
                'generated_by' => session()->get('fname') . ' ' . session()->get('lname')
            ];

            // Generate PDF using PdfHelper
            $pdfHelper = new PdfHelper('Commodity Price Trends Report', 'L');

            // Add title
            $pdfHelper->addTitle('Commodity Price Trends Report', 16);
            $pdfHelper->addText('Generated on: ' . $data['generated_at']);
            $pdfHelper->addText('Generated by: ' . $data['generated_by']);
            $pdfHelper->addLineBreak(10);

            // Add latest prices
            $pdfHelper->addSubtitle('Latest Commodity Prices', 14);
            if (!empty($latestPrices)) {
                $priceHeaders = ['Commodity', 'Market Type', 'Price', 'Unit', 'Date'];
                $priceTableData = [];
                foreach ($latestPrices as $price) {
                    $priceTableData[] = [
                        $price['commodity_name'],
                        ucfirst($price['market_type']),
                        $price['currency'] . ' ' . number_format($price['price_per_unit'], 2),
                        $price['unit_of_measurement'],
                        date('Y-m-d', strtotime($price['price_date']))
                    ];
                }
                $pdfHelper->addTable($priceHeaders, $priceTableData, [50, 30, 30, 25, 30]);
            }

            // Add volatility analysis
            $pdfHelper->addSubtitle('Price Volatility Analysis', 14);
            if (!empty($volatilityAnalysis)) {
                $volatilityHeaders = ['Commodity', 'Market', 'Avg Price', 'Min Price', 'Max Price', 'Volatility %'];
                $volatilityTableData = [];
                foreach ($volatilityAnalysis as $analysis) {
                    $volatilityTableData[] = [
                        $analysis['commodity_name'],
                        ucfirst($analysis['market_type']),
                        $analysis['currency'] . ' ' . number_format($analysis['avg_price'], 2),
                        $analysis['currency'] . ' ' . number_format($analysis['min_price'], 2),
                        $analysis['currency'] . ' ' . number_format($analysis['max_price'], 2),
                        $analysis['volatility_percent'] . '%'
                    ];
                }
                $pdfHelper->addTable($volatilityHeaders, $volatilityTableData, [40, 25, 30, 30, 30, 25]);
            }

            // Output PDF
            $filename = 'commodity_price_trends_' . date('Y-m-d_H-i-s') . '.pdf';
            return $pdfHelper->output($filename, 'D');

        } catch (\Exception $e) {
            log_message('error', 'Price Trends PDF Export Error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to generate PDF report: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
