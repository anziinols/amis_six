<?php
// app/Controllers/NASPReportsController.php

namespace App\Controllers;

use App\Models\NaspModel;
use App\Models\WorkplanNaspLinkModel;
use App\Models\ProposalModel;
use App\Services\PdfService;
use CodeIgniter\Controller;

class NASPReportsController extends Controller
{
    protected $naspModel;
    protected $workplanNaspLinkModel;
    protected $proposalModel;

    public function __construct()
    {
        $this->naspModel = new NaspModel();
        $this->workplanNaspLinkModel = new WorkplanNaspLinkModel();
        $this->proposalModel = new ProposalModel();
    }

    /**
     * Display the NASP Plans Report (Read-only)
     */
    public function index()
    {
        // Get date filter parameters
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        // Get all NASP data by type
        $plans = $this->naspModel->where('type', 'plans')->findAll();
        $apas = $this->naspModel->where('type', 'apas')->findAll();
        $dips = $this->naspModel->where('type', 'dips')->findAll();
        $specificAreas = $this->naspModel->where('type', 'specific_areas')->findAll();
        $objectives = $this->naspModel->where('type', 'objectives')->findAll();
        $outputs = $this->naspModel->where('type', 'outputs')->findAll();
        $indicators = $this->naspModel->where('type', 'indicators')->findAll();

        // Get activity counts for each entity
        $activityCounts = $this->getActivityCounts($dateFrom, $dateTo);

        // Add activity counts to each entity
        foreach ($plans as &$plan) {
            $plan['activity_count'] = $activityCounts['plans'][$plan['id']] ?? 0;
        }
        foreach ($apas as &$apa) {
            $apa['activity_count'] = $activityCounts['apas'][$apa['id']] ?? 0;
        }
        foreach ($dips as &$dip) {
            $dip['activity_count'] = $activityCounts['dips'][$dip['id']] ?? 0;
        }
        foreach ($specificAreas as &$sa) {
            $sa['activity_count'] = $activityCounts['specific_areas'][$sa['id']] ?? 0;
        }
        foreach ($objectives as &$objective) {
            $objective['activity_count'] = $activityCounts['objectives'][$objective['id']] ?? 0;
        }
        foreach ($outputs as &$output) {
            $output['activity_count'] = $activityCounts['outputs'][$output['id']] ?? 0;
        }

        // Prepare data for charts
        $chartData = $this->prepareChartData($plans, $apas, $dips, $specificAreas, $objectives, $outputs, $indicators);

        // Pass all data to the view
        return view('report_nasp/report_nasp_index', [
            'title' => 'NASP Plans Report',
            'plans' => $plans,
            'apas' => $apas,
            'dips' => $dips,
            'specificAreas' => $specificAreas,
            'objectives' => $objectives,
            'outputs' => $outputs,
            'indicators' => $indicators,
            'chartData' => $chartData,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }

    /**
     * Get cumulative activity counts for each NASP entity
     */
    private function getActivityCounts($dateFrom = null, $dateTo = null)
    {
        $counts = [
            'plans' => [],
            'apas' => [],
            'dips' => [],
            'specific_areas' => [],
            'objectives' => [],
            'outputs' => []
        ];

        // Get completed activities based on proposals
        $proposalModel = new \App\Models\ProposalModel();
        $proposalBuilder = $proposalModel->builder();
        $proposalBuilder->select('workplan_id, activity_id, status_at, rated_at')
                       ->whereIn('status', ['approved', 'rated']); // Include both approved and rated as completed

        // Apply date filtering for completed activities
        if ($dateFrom && $dateTo) {
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
        $completedActivityIds = array_column($completedProposals, 'activity_id');

        if (empty($completedActivityIds)) {
            return $counts; // Return empty counts if no completed proposals
        }

        // Get all NASP links for these completed activities with full hierarchy information
        $linkBuilder = $this->workplanNaspLinkModel->builder();
        $linkBuilder->select('workplan_activity_id, nasp_id, apa_id, dip_id, specific_area_id, objective_id, output_id')
                   ->whereIn('workplan_activity_id', $completedActivityIds);
        $allLinks = $linkBuilder->get()->getResultArray();

        // Step 1: Count activities directly linked to outputs (bottom level)
        foreach ($allLinks as $link) {
            if (!empty($link['output_id'])) {
                if (!isset($counts['outputs'][$link['output_id']])) {
                    $counts['outputs'][$link['output_id']] = 0;
                }
                $counts['outputs'][$link['output_id']]++;
            }
        }

        // Step 2: Cumulative count for Objectives (sum from all linked outputs)
        $allOutputs = $this->naspModel->where('type', 'outputs')->findAll();

        foreach ($allOutputs as $output) {
            if (isset($counts['outputs'][$output['id']]) && !empty($output['parent_id'])) {
                if (!isset($counts['objectives'][$output['parent_id']])) {
                    $counts['objectives'][$output['parent_id']] = 0;
                }
                $counts['objectives'][$output['parent_id']] += $counts['outputs'][$output['id']];
            }
        }

        // Step 3: Cumulative count for Specific Areas (sum from all linked objectives)
        $allObjectives = $this->naspModel->where('type', 'objectives')->findAll();

        foreach ($allObjectives as $objective) {
            if (isset($counts['objectives'][$objective['id']]) && !empty($objective['parent_id'])) {
                if (!isset($counts['specific_areas'][$objective['parent_id']])) {
                    $counts['specific_areas'][$objective['parent_id']] = 0;
                }
                $counts['specific_areas'][$objective['parent_id']] += $counts['objectives'][$objective['id']];
            }
        }

        // Step 4: Cumulative count for DIPs (sum from all linked specific areas)
        $allSpecificAreas = $this->naspModel->where('type', 'specific_areas')->findAll();

        foreach ($allSpecificAreas as $sa) {
            if (isset($counts['specific_areas'][$sa['id']]) && !empty($sa['parent_id'])) {
                if (!isset($counts['dips'][$sa['parent_id']])) {
                    $counts['dips'][$sa['parent_id']] = 0;
                }
                $counts['dips'][$sa['parent_id']] += $counts['specific_areas'][$sa['id']];
            }
        }

        // Step 5: Cumulative count for APAs (sum from all linked DIPs)
        $allDips = $this->naspModel->where('type', 'dips')->findAll();

        foreach ($allDips as $dip) {
            if (isset($counts['dips'][$dip['id']]) && !empty($dip['parent_id'])) {
                if (!isset($counts['apas'][$dip['parent_id']])) {
                    $counts['apas'][$dip['parent_id']] = 0;
                }
                $counts['apas'][$dip['parent_id']] += $counts['dips'][$dip['id']];
            }
        }

        // Step 6: Cumulative count for NASP Plans (sum from all linked APAs)
        $allApas = $this->naspModel->where('type', 'apas')->findAll();

        foreach ($allApas as $apa) {
            if (isset($counts['apas'][$apa['id']]) && !empty($apa['parent_id'])) {
                if (!isset($counts['plans'][$apa['parent_id']])) {
                    $counts['plans'][$apa['parent_id']] = 0;
                }
                $counts['plans'][$apa['parent_id']] += $counts['apas'][$apa['id']];
            }
        }

        return $counts;
    }

    /**
     * Prepare data for charts and graphs
     */
    private function prepareChartData($plans, $apas, $dips, $specificAreas, $objectives, $outputs, $indicators)
    {
        $chartData = [];

        // 1. Status distribution
        $statusCounts = [
            'plans' => ['active' => 0, 'inactive' => 0],
            'apas' => ['active' => 0, 'inactive' => 0],
            'dips' => ['active' => 0, 'inactive' => 0],
            'specificAreas' => ['active' => 0, 'inactive' => 0],
            'objectives' => ['active' => 0, 'inactive' => 0],
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

        foreach ($objectives as $objective) {
            $statusCounts['objectives'][$objective['nasp_status'] == 1 ? 'active' : 'inactive']++;
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
                'objectives' => 0,
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

        // Count Objectives and associate with plan
        foreach ($objectives as $objective) {
            foreach ($specificAreas as $sa) {
                if ($objective['parent_id'] == $sa['id']) {
                    foreach ($dips as $dip) {
                        if ($sa['parent_id'] == $dip['id']) {
                            foreach ($apas as $apa) {
                                if ($dip['parent_id'] == $apa['id'] && isset($entitiesByPlan[$apa['parent_id']])) {
                                    $entitiesByPlan[$apa['parent_id']]['objectives']++;
                                    break 3;
                                }
                            }
                        }
                    }
                }
            }
        }

        // Count Outputs and associate with plan
        foreach ($outputs as $output) {
            foreach ($objectives as $objective) {
                if ($output['parent_id'] == $objective['id']) {
                    foreach ($specificAreas as $sa) {
                        if ($objective['parent_id'] == $sa['id']) {
                            foreach ($dips as $dip) {
                                if ($sa['parent_id'] == $dip['id']) {
                                    foreach ($apas as $apa) {
                                        if ($dip['parent_id'] == $apa['id'] && isset($entitiesByPlan[$apa['parent_id']])) {
                                            $entitiesByPlan[$apa['parent_id']]['outputs']++;
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

        // Count Indicators and associate with plan
        foreach ($indicators as $indicator) {
            foreach ($outputs as $output) {
                if ($indicator['parent_id'] == $output['id']) {
                    foreach ($objectives as $objective) {
                        if ($output['parent_id'] == $objective['id']) {
                            foreach ($specificAreas as $sa) {
                                if ($objective['parent_id'] == $sa['id']) {
                                    foreach ($dips as $dip) {
                                        if ($sa['parent_id'] == $dip['id']) {
                                            foreach ($apas as $apa) {
                                                if ($dip['parent_id'] == $apa['id'] && isset($entitiesByPlan[$apa['parent_id']])) {
                                                    $entitiesByPlan[$apa['parent_id']]['indicators']++;
                                                    break 5;
                                                }
                                            }
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

        // 3. Distribution of Outputs by APAs
        $outputsByApas = [];
        foreach ($apas as $apa) {
            $outputsByApas[$apa['id']] = [
                'title' => $apa['title'],
                'count' => 0
            ];
        }

        // Count outputs for each APA by traversing the hierarchy
        foreach ($outputs as $output) {
            // Find the objective this output belongs to
            foreach ($objectives as $objective) {
                if ($objective['id'] == $output['parent_id']) {
                    // Find the specific area this objective belongs to
                    foreach ($specificAreas as $sa) {
                        if ($sa['id'] == $objective['parent_id']) {
                            // Find the DIP this specific area belongs to
                            foreach ($dips as $dip) {
                                if ($dip['id'] == $sa['parent_id']) {
                                    // Find the APA this DIP belongs to
                                    if (isset($outputsByApas[$dip['parent_id']])) {
                                        $outputsByApas[$dip['parent_id']]['count']++;
                                    }
                                    break;
                                }
                            }
                            break;
                        }
                    }
                    break;
                }
            }
        }

        $chartData['outputsByApas'] = $outputsByApas;

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
     * Get filter data for hierarchical dropdowns
     */
    public function getFilterData()
    {
        $type = $this->request->getGet('type');
        $planId = $this->request->getGet('plan_id');
        $apaId = $this->request->getGet('apa_id');
        $dipId = $this->request->getGet('dip_id');

        $data = [];

        switch ($type) {
            case 'apa':
                if ($planId) {
                    $data = $this->naspModel->where('type', 'apas')
                                          ->where('parent_id', $planId)
                                          ->findAll();
                }
                break;

            case 'dip':
                if ($apaId) {
                    $data = $this->naspModel->where('type', 'dips')
                                          ->where('parent_id', $apaId)
                                          ->findAll();
                }
                break;

            case 'specific_area':
                if ($dipId) {
                    $data = $this->naspModel->where('type', 'specific_areas')
                                          ->where('parent_id', $dipId)
                                          ->findAll();
                }
                break;
        }

        return $this->response->setJSON($data);
    }

    /**
     * Get filtered NASP data based on applied filters
     */
    public function getFilteredData()
    {
        $filters = [
            'nasp_plan' => $this->request->getGet('nasp_plan'),
            'apa' => $this->request->getGet('apa'),
            'nasp_dip' => $this->request->getGet('nasp_dip'),
            'nasp_specific_area' => $this->request->getGet('nasp_specific_area'),
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to'),
            'status' => $this->request->getGet('status')
        ];

        // Apply filters to get filtered data
        $filteredData = $this->applyNaspFilters($filters);

        // Prepare chart data with filtered results
        $chartData = $this->prepareChartData(
            $filteredData['plans'],
            $filteredData['apas'],
            $filteredData['dips'],
            $filteredData['specificAreas'],
            $filteredData['outputs'],
            $filteredData['indicators']
        );

        // Calculate counts
        $counts = [
            'total' => count($filteredData['plans']) + count($filteredData['apas']) +
                      count($filteredData['dips']) + count($filteredData['specificAreas']) +
                      count($filteredData['outputs']) + count($filteredData['indicators']),
            'plans' => count($filteredData['plans']),
            'apas' => count($filteredData['apas']),
            'dips' => count($filteredData['dips']),
            'specificAreas' => count($filteredData['specificAreas']),
            'outputs' => count($filteredData['outputs']),
            'indicators' => count($filteredData['indicators'])
        ];

        return $this->response->setJSON([
            'data' => $filteredData,
            'chartData' => $chartData,
            'counts' => $counts
        ]);
    }

    /**
     * Apply filters to NASP data
     */
    private function applyNaspFilters($filters)
    {
        // Start with base queries
        $plansQuery = $this->naspModel->where('type', 'plans');
        $apasQuery = $this->naspModel->where('type', 'apas');
        $dipsQuery = $this->naspModel->where('type', 'dips');
        $specificAreasQuery = $this->naspModel->where('type', 'specific_areas');
        $outputsQuery = $this->naspModel->where('type', 'outputs');
        $indicatorsQuery = $this->naspModel->where('type', 'indicators');

        // Apply NASP Plan filter
        if (!empty($filters['nasp_plan'])) {
            $plansQuery = $plansQuery->where('id', $filters['nasp_plan']);
            $apasQuery = $apasQuery->where('parent_id', $filters['nasp_plan']);

            // Get APAs for this plan to filter further down
            $planApas = $this->naspModel->where('type', 'apas')
                                      ->where('parent_id', $filters['nasp_plan'])
                                      ->findAll();
            $apaIds = array_column($planApas, 'id');

            if (!empty($apaIds)) {
                $dipsQuery = $dipsQuery->whereIn('parent_id', $apaIds);

                // Get DIPs for these APAs
                $apaDips = $this->naspModel->where('type', 'dips')
                                         ->whereIn('parent_id', $apaIds)
                                         ->findAll();
                $dipIds = array_column($apaDips, 'id');

                if (!empty($dipIds)) {
                    $specificAreasQuery = $specificAreasQuery->whereIn('parent_id', $dipIds);

                    // Get Specific Areas for these DIPs
                    $dipSpecificAreas = $this->naspModel->where('type', 'specific_areas')
                                                      ->whereIn('parent_id', $dipIds)
                                                      ->findAll();
                    $saIds = array_column($dipSpecificAreas, 'id');

                    if (!empty($saIds)) {
                        $outputsQuery = $outputsQuery->whereIn('parent_id', $saIds);

                        // Get Outputs for these Specific Areas
                        $saOutputs = $this->naspModel->where('type', 'outputs')
                                                   ->whereIn('parent_id', $saIds)
                                                   ->findAll();
                        $outputIds = array_column($saOutputs, 'id');

                        if (!empty($outputIds)) {
                            $indicatorsQuery = $indicatorsQuery->whereIn('parent_id', $outputIds);
                        }
                    }
                }
            }
        }

        // Apply APA filter
        if (!empty($filters['apa'])) {
            $apasQuery = $apasQuery->where('id', $filters['apa']);
            $dipsQuery = $dipsQuery->where('parent_id', $filters['apa']);

            // Continue filtering down the hierarchy
            $apaDips = $this->naspModel->where('type', 'dips')
                                     ->where('parent_id', $filters['apa'])
                                     ->findAll();
            $dipIds = array_column($apaDips, 'id');

            if (!empty($dipIds)) {
                $specificAreasQuery = $specificAreasQuery->whereIn('parent_id', $dipIds);

                $dipSpecificAreas = $this->naspModel->where('type', 'specific_areas')
                                                  ->whereIn('parent_id', $dipIds)
                                                  ->findAll();
                $saIds = array_column($dipSpecificAreas, 'id');

                if (!empty($saIds)) {
                    $outputsQuery = $outputsQuery->whereIn('parent_id', $saIds);

                    $saOutputs = $this->naspModel->where('type', 'outputs')
                                               ->whereIn('parent_id', $saIds)
                                               ->findAll();
                    $outputIds = array_column($saOutputs, 'id');

                    if (!empty($outputIds)) {
                        $indicatorsQuery = $indicatorsQuery->whereIn('parent_id', $outputIds);
                    }
                }
            }
        }

        // Apply DIP filter
        if (!empty($filters['nasp_dip'])) {
            $dipsQuery = $dipsQuery->where('id', $filters['nasp_dip']);
            $specificAreasQuery = $specificAreasQuery->where('parent_id', $filters['nasp_dip']);

            $dipSpecificAreas = $this->naspModel->where('type', 'specific_areas')
                                              ->where('parent_id', $filters['nasp_dip'])
                                              ->findAll();
            $saIds = array_column($dipSpecificAreas, 'id');

            if (!empty($saIds)) {
                $outputsQuery = $outputsQuery->whereIn('parent_id', $saIds);

                $saOutputs = $this->naspModel->where('type', 'outputs')
                                           ->whereIn('parent_id', $saIds)
                                           ->findAll();
                $outputIds = array_column($saOutputs, 'id');

                if (!empty($outputIds)) {
                    $indicatorsQuery = $indicatorsQuery->whereIn('parent_id', $outputIds);
                }
            }
        }

        // Apply Specific Area filter
        if (!empty($filters['nasp_specific_area'])) {
            $specificAreasQuery = $specificAreasQuery->where('id', $filters['nasp_specific_area']);
            $outputsQuery = $outputsQuery->where('parent_id', $filters['nasp_specific_area']);

            $saOutputs = $this->naspModel->where('type', 'outputs')
                                       ->where('parent_id', $filters['nasp_specific_area'])
                                       ->findAll();
            $outputIds = array_column($saOutputs, 'id');

            if (!empty($outputIds)) {
                $indicatorsQuery = $indicatorsQuery->whereIn('parent_id', $outputIds);
            }
        }

        // Apply date filters
        if (!empty($filters['date_from'])) {
            $plansQuery = $plansQuery->where('date_from >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $plansQuery = $plansQuery->where('date_to <=', $filters['date_to']);
        }

        // Apply status filter
        if ($filters['status'] !== '' && $filters['status'] !== null) {
            $plansQuery = $plansQuery->where('nasp_status', $filters['status']);
            $apasQuery = $apasQuery->where('nasp_status', $filters['status']);
            $dipsQuery = $dipsQuery->where('nasp_status', $filters['status']);
            $specificAreasQuery = $specificAreasQuery->where('nasp_status', $filters['status']);
            $outputsQuery = $outputsQuery->where('nasp_status', $filters['status']);
            $indicatorsQuery = $indicatorsQuery->where('nasp_status', $filters['status']);
        }

        // Execute queries
        return [
            'plans' => $plansQuery->findAll(),
            'apas' => $apasQuery->findAll(),
            'dips' => $dipsQuery->findAll(),
            'specificAreas' => $specificAreasQuery->findAll(),
            'outputs' => $outputsQuery->findAll(),
            'indicators' => $indicatorsQuery->findAll()
        ];
    }


}
