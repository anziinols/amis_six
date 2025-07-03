<?php
// app/Controllers/WorkplanReportsController.php

/**
 * WorkplanReportsController
 * 
 * Handles the reporting functionality for workplans and activities.
 * Displays visual reports and data tables for workplans, activities, and their relationships.
 * 
 * @package App\Controllers
 */

namespace App\Controllers;

use App\Models\WorkplanModel;
use App\Models\WorkplanActivityModel;
use App\Models\WorkplanMtdpLinkModel;
use App\Models\WorkplanNaspLinkModel;
use App\Models\WorkplanCorporatePlanLinkModel;
use App\Models\BranchesModel;
use App\Models\UserModel;
use App\Models\MtdpModel;
use App\Models\NaspModel;
use App\Models\CorporatePlanModel;
use App\Models\ProposalModel;
use App\Services\PdfService;
use CodeIgniter\Controller;

class WorkplanReportsController extends Controller
{
    protected $workplanModel;
    protected $workplanActivityModel;
    protected $workplanMtdpLinkModel;
    protected $workplanNaspLinkModel;
    protected $workplanCorporatePlanLinkModel;
    protected $branchesModel;
    protected $userModel;
    protected $mtdpModel;
    protected $naspModel;
    protected $corporatePlanModel;
    protected $proposalModel;

    public function __construct()
    {
        $this->workplanModel = new WorkplanModel();
        $this->workplanActivityModel = new WorkplanActivityModel();
        $this->workplanMtdpLinkModel = new WorkplanMtdpLinkModel();
        $this->workplanNaspLinkModel = new WorkplanNaspLinkModel();
        $this->workplanCorporatePlanLinkModel = new WorkplanCorporatePlanLinkModel();
        $this->branchesModel = new BranchesModel();
        $this->userModel = new UserModel();
        $this->mtdpModel = new MtdpModel();
        $this->naspModel = new NaspModel();
        $this->corporatePlanModel = new CorporatePlanModel();
        $this->proposalModel = new ProposalModel();
    }

    /**
     * Display the Workplan Reports (Read-only)
     */
    public function index()
    {
        // Get all workplans and activities
        $workplans = $this->workplanModel->findAll();
        $activities = $this->workplanActivityModel->findAll();
        
        // Get proposals with details
        $proposals = $this->proposalModel->getProposalWithDetails();
        
        // Get branch and user data for display
        $branches = $this->branchesModel->findAll();
        $users = $this->userModel->findAll();
        
        // Get plan links
        $mtdpLinks = $this->workplanMtdpLinkModel->findAll();
        $naspLinks = $this->workplanNaspLinkModel->findAll();
        $corporatePlanLinks = $this->workplanCorporatePlanLinkModel->findAll();
        
        // Prepare data for charts
        $chartData = $this->prepareChartData($workplans, $activities, $proposals, $mtdpLinks, $naspLinks, $corporatePlanLinks);

        // Pass all data to the view
        return view('report_workplan/report_workplan_index', [
            'title' => 'Workplan Reports',
            'workplans' => $workplans,
            'activities' => $activities,
            'proposals' => $proposals,
            'branches' => $branches,
            'users' => $users,
            'mtdpLinks' => $mtdpLinks,
            'naspLinks' => $naspLinks,
            'corporatePlanLinks' => $corporatePlanLinks,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Prepare data for charts and graphs
     */
    private function prepareChartData($workplans, $activities, $proposals, $mtdpLinks, $naspLinks, $corporatePlanLinks)
    {
        $chartData = [];

        // 1. Workplan Status Distribution
        $workplanStatusCounts = [
            'draft' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'on_hold' => 0,
            'cancelled' => 0
        ];

        foreach ($workplans as $workplan) {
            if (isset($workplan['status']) && isset($workplanStatusCounts[$workplan['status']])) {
                $workplanStatusCounts[$workplan['status']]++;
            }
        }

        $chartData['workplanStatusCounts'] = $workplanStatusCounts;

        // 2. Activity Status Distribution
        $activityStatusCounts = [
            'pending' => 0,
            'submitted' => 0,
            'approved' => 0,
            'rated' => 0,
            'other' => 0 // Added to capture any other statuses
        ];

        foreach ($activities as $activity) {
            if (isset($activity['status'])) {
                if (isset($activityStatusCounts[$activity['status']])) {
                    $activityStatusCounts[$activity['status']]++;
                } else {
                    $activityStatusCounts['other']++;
                }
            } else {
                // Count activities with no status as 'pending'
                $activityStatusCounts['pending']++;
            }
        }

        $chartData['activityStatusCounts'] = $activityStatusCounts;

        // 3. Activity Type Distribution
        $activityTypeCounts = [
            'training' => 0,
            'inputs' => 0,
            'infrastructure' => 0,
            'other' => 0 // Added to capture any other activity types
        ];

        foreach ($activities as $activity) {
            if (isset($activity['activity_type'])) {
                if (isset($activityTypeCounts[$activity['activity_type']])) {
                    $activityTypeCounts[$activity['activity_type']]++;
                } else {
                    $activityTypeCounts['other']++;
                }
            }
        }

        $chartData['activityTypeCounts'] = $activityTypeCounts;

        // 4. Workplans by Branch
        $workplansByBranch = [];
        foreach ($workplans as $workplan) {
            if (isset($workplan['branch_id']) && !empty($workplan['branch_id'])) {
                $branchId = $workplan['branch_id'];
                if (!isset($workplansByBranch[$branchId])) {
                    $branch = $this->branchesModel->find($branchId);
                    $branchName = ($branch && isset($branch['name'])) ? $branch['name'] : 'Unknown';
                    $workplansByBranch[$branchId] = [
                        'name' => $branchName,
                        'count' => 0
                    ];
                }
                $workplansByBranch[$branchId]['count']++;
            } else {
                // Handle workplans with no branch assigned
                if (!isset($workplansByBranch['unassigned'])) {
                    $workplansByBranch['unassigned'] = [
                        'name' => 'Unassigned',
                        'count' => 0
                    ];
                }
                $workplansByBranch['unassigned']['count']++;
            }
        }

        $chartData['workplansByBranch'] = $workplansByBranch;

        // 5. Activities by Plan Link Type
        // Get unique activity IDs for each plan type to avoid double counting
        $mtdpActivityIds = [];
        $naspActivityIds = [];
        $corpPlanActivityIds = [];
        
        foreach ($mtdpLinks as $link) {
            if (isset($link['workplan_activity_id'])) {
                $mtdpActivityIds[$link['workplan_activity_id']] = true;
            }
        }
        
        foreach ($naspLinks as $link) {
            if (isset($link['workplan_activity_id'])) {
                $naspActivityIds[$link['workplan_activity_id']] = true;
            }
        }
        
        foreach ($corporatePlanLinks as $link) {
            if (isset($link['workplan_activity_id'])) {
                $corpPlanActivityIds[$link['workplan_activity_id']] = true;
            }
        }
        
        // Count total activities that have at least one link
        $linkedActivityIds = array_merge(
            array_keys($mtdpActivityIds), 
            array_keys($naspActivityIds), 
            array_keys($corpPlanActivityIds)
        );
        $uniqueLinkedActivityIds = array_unique($linkedActivityIds);
        
        // Count activities with no links
        $totalActivities = count($activities);
        $totalLinkedActivities = count($uniqueLinkedActivityIds);
        $noLinkActivities = $totalActivities - $totalLinkedActivities;
        
        $activitiesByPlanType = [
            'mtdp' => count($mtdpActivityIds),
            'nasp' => count($naspActivityIds),
            'corporate_plan' => count($corpPlanActivityIds),
            'no_link' => $noLinkActivities > 0 ? $noLinkActivities : 0
        ];

        $chartData['activitiesByPlanType'] = $activitiesByPlanType;

        // 6. Monthly Activity Distribution (based on workplan start date since activities don't have dates)
        $monthlyActivityCounts = array_fill(0, 12, 0);
        $currentYear = date('Y');

        // Use workplan dates as a proxy for activity dates since activities don't have their own dates
        $workplanActivitiesMap = [];
        foreach ($activities as $activity) {
            if (isset($activity['workplan_id'])) {
                if (!isset($workplanActivitiesMap[$activity['workplan_id']])) {
                    $workplanActivitiesMap[$activity['workplan_id']] = 0;
                }
                $workplanActivitiesMap[$activity['workplan_id']]++;
            }
        }
        
        foreach ($workplans as $workplan) {
            if (isset($workplan['start_date']) && !empty($workplan['start_date']) && isset($workplanActivitiesMap[$workplan['id']])) {
                try {
                    $startDate = new \DateTime($workplan['start_date']);
                    if ($startDate->format('Y') == $currentYear) {
                        $month = (int)$startDate->format('m') - 1; // 0-based index
                        // Add the count of activities for this workplan to the month
                        $monthlyActivityCounts[$month] += $workplanActivitiesMap[$workplan['id']];
                    }
                } catch (\Exception $e) {
                    // Skip invalid dates
                    continue;
                }
            }
        }

        $chartData['monthlyActivityCounts'] = $monthlyActivityCounts;
        
        // 7. Proposal Status Distribution
        $proposalStatusCounts = [
            'pending' => 0,
            'submitted' => 0,
            'approved' => 0,
            'rated' => 0,
            'other' => 0
        ];
        
        foreach ($proposals as $proposal) {
            if (isset($proposal['status'])) {
                if (isset($proposalStatusCounts[$proposal['status']])) {
                    $proposalStatusCounts[$proposal['status']]++;
                } else {
                    $proposalStatusCounts['other']++;
                }
            } else {
                // Count proposals with no status as 'pending'
                $proposalStatusCounts['pending']++;
            }
        }
        
        $chartData['proposalStatusCounts'] = $proposalStatusCounts;

        // 8. Total Costs by Province
        $costsByProvince = [];
        
        foreach ($proposals as $proposal) {
            if (isset($proposal['province_name']) && isset($proposal['total_cost']) && $proposal['total_cost'] > 0) {
                $provinceName = $proposal['province_name'];
                
                if (!isset($costsByProvince[$provinceName])) {
                    $costsByProvince[$provinceName] = 0;
                }
                
                $costsByProvince[$provinceName] += (float)$proposal['total_cost'];
            }
        }
        
        $chartData['costsByProvince'] = $costsByProvince;
        
        // 9. Total Costs by Activity Type
        $costsByActivityType = [
            'training' => 0,
            'inputs' => 0,
            'infrastructure' => 0,
            'other' => 0
        ];

        foreach ($proposals as $proposal) {
            if (isset($proposal['activity_type']) && isset($proposal['total_cost']) && $proposal['total_cost'] > 0) {
                $activityType = $proposal['activity_type'];
                
                if (isset($costsByActivityType[$activityType])) {
                    $costsByActivityType[$activityType] += (float)$proposal['total_cost'];
                } else {
                    $costsByActivityType['other'] += (float)$proposal['total_cost'];
                }
            }
        }
        
        $chartData['costsByActivityType'] = $costsByActivityType;
        
        // 10. Calculate Total Cost
        $totalCost = 0;
        foreach ($proposals as $proposal) {
            if (isset($proposal['total_cost']) && $proposal['total_cost'] > 0) {
                $totalCost += (float)$proposal['total_cost'];
            }
        }
        
        $chartData['totalCost'] = $totalCost;

        // 11. Calculate Average Rating Score
        $ratingScores = 0;
        $ratedProposalsCount = 0;
        
        foreach ($proposals as $proposal) {
            if (isset($proposal['rating_score']) && $proposal['rating_score'] > 0) {
                $ratingScores += (float)$proposal['rating_score'];
                $ratedProposalsCount++;
            }
        }
        
        $chartData['averageRatingScore'] = $ratedProposalsCount > 0 ? $ratingScores / $ratedProposalsCount : 0;
        $chartData['ratedProposalsCount'] = $ratedProposalsCount;

        return $chartData;
    }

    /**
     * Export workplan report as PDF
     *
     * @return mixed
     */
    public function exportPdf()
    {
        try {
            // Get all the same data as the index method
            $workplans = $this->workplanModel->getWorkplansWithDetails();
            $activities = $this->workplanActivityModel->getActivitiesWithDetails();
            $proposals = $this->proposalModel->getProposalsWithDetails();
            $mtdpLinks = $this->workplanMtdpLinkModel->getLinksWithDetails();
            $naspLinks = $this->workplanNaspLinkModel->getLinksWithDetails();
            $corporatePlanLinks = $this->workplanCorporatePlanLinkModel->getLinksWithDetails();

            // Prepare chart data
            $chartData = $this->prepareChartData($workplans, $activities, $proposals, $mtdpLinks, $naspLinks, $corporatePlanLinks);

            // Prepare data array for PDF service
            $data = [
                'workplans' => $workplans,
                'activities' => $activities,
                'proposals' => $proposals,
                'mtdpLinks' => $mtdpLinks,
                'naspLinks' => $naspLinks,
                'corporatePlanLinks' => $corporatePlanLinks
            ];

            // Generate PDF using PdfService
            $pdfService = new PdfService();
            return $pdfService->generateReportPdf('workplan', $data, $chartData);

        } catch (\Exception $e) {
            log_message('error', 'Workplan Report PDF Export Error: ' . $e->getMessage());
            return redirect()->to('/reports/workplan')->with('error', 'Failed to generate PDF report. Please try again.');
        }
    }
}
