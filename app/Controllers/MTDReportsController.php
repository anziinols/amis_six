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
use App\Models\WorkplanMtdpLinkModel;
use App\Models\ProposalModel;
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
        $workplanLinkModel = new WorkplanMtdpLinkModel();
        $proposalModel = new ProposalModel();

        // Get date filters from request
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        // Get all MTDP data with new filtering logic
        $plans = $mtdpModel->findAll();
        $spas = $this->getFilteredMtdpData($spaModel, $plans, $dateFrom, $dateTo);
        $dips = $this->getFilteredMtdpData($dipModel, $plans, $dateFrom, $dateTo);
        $kras = $this->getFilteredMtdpData($kraModel, $plans, $dateFrom, $dateTo);
        $specific_areas = $this->getFilteredMtdpData($saModel, $plans, $dateFrom, $dateTo);
        $investments = $this->getFilteredMtdpData($investmentsModel, $plans, $dateFrom, $dateTo);
        $strategies = $this->getFilteredMtdpData($strategiesModel, $plans, $dateFrom, $dateTo);
        $indicators = $this->getFilteredMtdpData($indicatorsModel, $plans, $dateFrom, $dateTo);

        // Get workplan counts based on completed proposals within date range
        $workplanCounts = $this->getWorkplanCountsByCompletedProposals($workplanLinkModel, $proposalModel, $dateFrom, $dateTo);

        // Prepare chart data
        $chartData = $this->prepareChartData($plans, $spas, $dips, $kras, $specific_areas, $investments, $strategies, $indicators, $dateFrom, $dateTo);

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
            'workplanCounts' => $workplanCounts,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }

    /**
     * Prepare data for charts and graphs
     */
    private function prepareChartData($plans, $spas, $dips, $kras, $specific_areas, $investments, $strategies, $indicators, $dateFrom = null, $dateTo = null)
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
     * Get filtered MTDP data based on plan year ranges and date filter
     */
    private function getFilteredMtdpData($model, $plans, $dateFrom = null, $dateTo = null)
    {
        // If no date filter is provided, return all data
        if (!$dateFrom && !$dateTo) {
            return $model->findAll();
        }

        // Get all data first
        $allData = $model->findAll();
        $filteredData = [];

        foreach ($allData as $item) {
            // Find the corresponding MTDP plan
            $mtdpPlan = null;
            foreach ($plans as $plan) {
                if ($plan['id'] == $item['mtdp_id']) {
                    $mtdpPlan = $plan;
                    break;
                }
            }

            if (!$mtdpPlan || !$mtdpPlan['date_from'] || !$mtdpPlan['date_to']) {
                continue;
            }

            // Calculate year ranges based on MTDP plan dates
            $planStartYear = (int)date('Y', strtotime($mtdpPlan['date_from']));
            $planEndYear = (int)date('Y', strtotime($mtdpPlan['date_to']));

            // Check if the filter date range overlaps with any of the plan years
            if ($this->dateRangeOverlapsWithPlanYears($dateFrom, $dateTo, $planStartYear, $planEndYear)) {
                $filteredData[] = $item;
            }
        }

        return $filteredData;
    }

    /**
     * Check if date range overlaps with MTDP plan years
     */
    private function dateRangeOverlapsWithPlanYears($dateFrom, $dateTo, $planStartYear, $planEndYear)
    {
        $filterStartYear = $dateFrom ? (int)date('Y', strtotime($dateFrom)) : $planStartYear;
        $filterEndYear = $dateTo ? (int)date('Y', strtotime($dateTo)) : $planEndYear;

        // Check if there's any overlap between filter years and plan years
        return !($filterEndYear < $planStartYear || $filterStartYear > $planEndYear);
    }

    /**
     * Get workplan counts based on completed proposals within date range
     */
    private function getWorkplanCountsByCompletedProposals($workplanLinkModel, $proposalModel, $dateFrom = null, $dateTo = null)
    {
        $counts = [
            'strategies' => [],
            'kras' => [],
            'dips' => [],
            'spas' => [],
            'specific_areas' => [],
            'investments' => [],
            'mtdp_plans' => []
        ];

        // If no date filter, use the old method for backward compatibility
        if (!$dateFrom && !$dateTo) {
            return $this->getWorkplanCountsLegacy($workplanLinkModel);
        }

        // Get completed proposals within the date range
        // Completed activities are those with status 'approved' or 'rated'
        $proposalBuilder = $proposalModel->builder();
        $proposalBuilder->select('workplan_id, activity_id, status_at, rated_at')
                       ->whereIn('status', ['approved', 'rated']); // Include both approved and rated as completed

        if ($dateFrom && $dateTo) {
            // Use status_at for approved proposals and rated_at for rated proposals
            $proposalBuilder->groupStart()
                           ->where('(status = "approved" AND status_at >=', $dateFrom)
                           ->where('status_at <=', $dateTo . ' 23:59:59)')
                           ->orGroupStart()
                           ->where('status = "rated" AND rated_at >=', $dateFrom)
                           ->where('rated_at <=', $dateTo . ' 23:59:59')
                           ->groupEnd()
                           ->groupEnd();
        } elseif ($dateFrom) {
            $proposalBuilder->groupStart()
                           ->where('(status = "approved" AND status_at >=', $dateFrom)
                           ->orWhere('(status = "rated" AND rated_at >=', $dateFrom)
                           ->groupEnd();
        } elseif ($dateTo) {
            $proposalBuilder->groupStart()
                           ->where('(status = "approved" AND status_at <=', $dateTo . ' 23:59:59)')
                           ->orWhere('(status = "rated" AND rated_at <=', $dateTo . ' 23:59:59)')
                           ->groupEnd();
        }

        $completedProposals = $proposalBuilder->get()->getResultArray();

        // Extract workplan activity IDs from completed proposals
        $completedActivityIds = array_column($completedProposals, 'activity_id');

        if (empty($completedActivityIds)) {
            return $counts; // Return empty counts if no completed proposals
        }

        // Get all workplan links for these completed activities with full hierarchy information
        $linkBuilder = $workplanLinkModel->builder();
        $linkBuilder->select('workplan_activity_id, mtdp_id, spa_id, dip_id, sa_id, investment_id, kra_id, strategies_id')
                   ->whereIn('workplan_activity_id', $completedActivityIds);
        $allLinks = $linkBuilder->get()->getResultArray();

        // Step 1: Count activities directly linked to strategies (bottom level)
        foreach ($allLinks as $link) {
            if (!empty($link['strategies_id'])) {
                if (!isset($counts['strategies'][$link['strategies_id']])) {
                    $counts['strategies'][$link['strategies_id']] = 0;
                }
                $counts['strategies'][$link['strategies_id']]++;
            }
        }

        // Step 2: Cumulative count for KRAs (sum from all linked strategies)
        $strategiesModel = new \App\Models\MtdpStrategiesModel();
        $allStrategies = $strategiesModel->findAll();

        foreach ($allStrategies as $strategy) {
            if (isset($counts['strategies'][$strategy['id']]) && !empty($strategy['kra_id'])) {
                if (!isset($counts['kras'][$strategy['kra_id']])) {
                    $counts['kras'][$strategy['kra_id']] = 0;
                }
                $counts['kras'][$strategy['kra_id']] += $counts['strategies'][$strategy['id']];
            }
        }

        // Step 3: Cumulative count for Investments (sum from all linked KRAs)
        $kraModel = new \App\Models\MtdpKraModel();
        $allKras = $kraModel->findAll();

        foreach ($allKras as $kra) {
            if (isset($counts['kras'][$kra['id']]) && !empty($kra['investment_id'])) {
                if (!isset($counts['investments'][$kra['investment_id']])) {
                    $counts['investments'][$kra['investment_id']] = 0;
                }
                $counts['investments'][$kra['investment_id']] += $counts['kras'][$kra['id']];
            }
        }

        // Step 4: Cumulative count for Specific Areas (sum from all linked Investments)
        $investmentsModel = new \App\Models\MtdpInvestmentsModel();
        $allInvestments = $investmentsModel->findAll();

        foreach ($allInvestments as $investment) {
            if (isset($counts['investments'][$investment['id']]) && !empty($investment['sa_id'])) {
                if (!isset($counts['specific_areas'][$investment['sa_id']])) {
                    $counts['specific_areas'][$investment['sa_id']] = 0;
                }
                $counts['specific_areas'][$investment['sa_id']] += $counts['investments'][$investment['id']];
            }
        }

        // Step 5: Cumulative count for DIPs (sum from all linked Specific Areas)
        $saModel = new \App\Models\MtdpSpecificAreaModel();
        $allSpecificAreas = $saModel->findAll();

        foreach ($allSpecificAreas as $sa) {
            if (isset($counts['specific_areas'][$sa['id']]) && !empty($sa['dip_id'])) {
                if (!isset($counts['dips'][$sa['dip_id']])) {
                    $counts['dips'][$sa['dip_id']] = 0;
                }
                $counts['dips'][$sa['dip_id']] += $counts['specific_areas'][$sa['id']];
            }
        }

        // Step 6: Cumulative count for SPAs (sum from all linked DIPs)
        $dipModel = new \App\Models\MtdpDipModel();
        $allDips = $dipModel->findAll();

        foreach ($allDips as $dip) {
            if (isset($counts['dips'][$dip['id']]) && !empty($dip['spa_id'])) {
                if (!isset($counts['spas'][$dip['spa_id']])) {
                    $counts['spas'][$dip['spa_id']] = 0;
                }
                $counts['spas'][$dip['spa_id']] += $counts['dips'][$dip['id']];
            }
        }

        // Step 7: Cumulative count for MTDP Plans (sum from all linked SPAs)
        $spaModel = new \App\Models\MtdpSpaModel();
        $allSpas = $spaModel->findAll();

        foreach ($allSpas as $spa) {
            if (isset($counts['spas'][$spa['id']]) && !empty($spa['mtdp_id'])) {
                if (!isset($counts['mtdp_plans'][$spa['mtdp_id']])) {
                    $counts['mtdp_plans'][$spa['mtdp_id']] = 0;
                }
                $counts['mtdp_plans'][$spa['mtdp_id']] += $counts['spas'][$spa['id']];
            }
        }

        return $counts;
    }

    /**
     * Legacy workplan counts method (for backward compatibility when no date filter)
     */
    private function getWorkplanCountsLegacy($workplanLinkModel)
    {
        $counts = [
            'strategies' => [],
            'kras' => [],
            'dips' => [],
            'spas' => [],
            'specific_areas' => [],
            'investments' => [],
            'mtdp_plans' => []
        ];

        // Get all workplan links with full hierarchy information
        $allLinks = $workplanLinkModel->builder()
                                     ->select('workplan_activity_id, mtdp_id, spa_id, dip_id, sa_id, investment_id, kra_id, strategies_id')
                                     ->get()->getResultArray();

        // Step 1: Count activities directly linked to strategies (bottom level)
        foreach ($allLinks as $link) {
            if (!empty($link['strategies_id'])) {
                if (!isset($counts['strategies'][$link['strategies_id']])) {
                    $counts['strategies'][$link['strategies_id']] = 0;
                }
                $counts['strategies'][$link['strategies_id']]++;
            }
        }

        // Step 2: Cumulative count for KRAs (sum from all linked strategies)
        $strategiesModel = new \App\Models\MtdpStrategiesModel();
        $allStrategies = $strategiesModel->findAll();

        foreach ($allStrategies as $strategy) {
            if (isset($counts['strategies'][$strategy['id']]) && !empty($strategy['kra_id'])) {
                if (!isset($counts['kras'][$strategy['kra_id']])) {
                    $counts['kras'][$strategy['kra_id']] = 0;
                }
                $counts['kras'][$strategy['kra_id']] += $counts['strategies'][$strategy['id']];
            }
        }

        // Step 3: Cumulative count for Investments (sum from all linked KRAs)
        $kraModel = new \App\Models\MtdpKraModel();
        $allKras = $kraModel->findAll();

        foreach ($allKras as $kra) {
            if (isset($counts['kras'][$kra['id']]) && !empty($kra['investment_id'])) {
                if (!isset($counts['investments'][$kra['investment_id']])) {
                    $counts['investments'][$kra['investment_id']] = 0;
                }
                $counts['investments'][$kra['investment_id']] += $counts['kras'][$kra['id']];
            }
        }

        // Step 4: Cumulative count for Specific Areas (sum from all linked Investments)
        $investmentsModel = new \App\Models\MtdpInvestmentsModel();
        $allInvestments = $investmentsModel->findAll();

        foreach ($allInvestments as $investment) {
            if (isset($counts['investments'][$investment['id']]) && !empty($investment['sa_id'])) {
                if (!isset($counts['specific_areas'][$investment['sa_id']])) {
                    $counts['specific_areas'][$investment['sa_id']] = 0;
                }
                $counts['specific_areas'][$investment['sa_id']] += $counts['investments'][$investment['id']];
            }
        }

        // Step 5: Cumulative count for DIPs (sum from all linked Specific Areas)
        $saModel = new \App\Models\MtdpSpecificAreaModel();
        $allSpecificAreas = $saModel->findAll();

        foreach ($allSpecificAreas as $sa) {
            if (isset($counts['specific_areas'][$sa['id']]) && !empty($sa['dip_id'])) {
                if (!isset($counts['dips'][$sa['dip_id']])) {
                    $counts['dips'][$sa['dip_id']] = 0;
                }
                $counts['dips'][$sa['dip_id']] += $counts['specific_areas'][$sa['id']];
            }
        }

        // Step 6: Cumulative count for SPAs (sum from all linked DIPs)
        $dipModel = new \App\Models\MtdpDipModel();
        $allDips = $dipModel->findAll();

        foreach ($allDips as $dip) {
            if (isset($counts['dips'][$dip['id']]) && !empty($dip['spa_id'])) {
                if (!isset($counts['spas'][$dip['spa_id']])) {
                    $counts['spas'][$dip['spa_id']] = 0;
                }
                $counts['spas'][$dip['spa_id']] += $counts['dips'][$dip['id']];
            }
        }

        // Step 7: Cumulative count for MTDP Plans (sum from all linked SPAs)
        $spaModel = new \App\Models\MtdpSpaModel();
        $allSpas = $spaModel->findAll();

        foreach ($allSpas as $spa) {
            if (isset($counts['spas'][$spa['id']]) && !empty($spa['mtdp_id'])) {
                if (!isset($counts['mtdp_plans'][$spa['mtdp_id']])) {
                    $counts['mtdp_plans'][$spa['mtdp_id']] = 0;
                }
                $counts['mtdp_plans'][$spa['mtdp_id']] += $counts['spas'][$spa['id']];
            }
        }

        return $counts;
    }


}