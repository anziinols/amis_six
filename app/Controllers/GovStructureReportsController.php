<?php

namespace App\Controllers;

use App\Models\GovStructureModel;
use App\Models\WorkplanActivityModel;
use App\Models\ProposalModel;
use App\Helpers\PdfHelper;
use CodeIgniter\Controller;

/**
 * GovStructureReportsController
 *
 * Handles the reporting functionality for PNG government structure analytics.
 * Displays visual reports, charts, and data tables for government structure coverage,
 * activity distribution, and administrative efficiency analysis.
 *
 * @package App\Controllers
 */
class GovStructureReportsController extends Controller
{
    protected $govStructureModel;
    protected $workplanActivityModel;
    protected $proposalModel;

    public function __construct()
    {
        $this->govStructureModel = new GovStructureModel();
        $this->workplanActivityModel = new WorkplanActivityModel();
        $this->proposalModel = new ProposalModel();
    }

    /**
     * Display the Government Structure Reports dashboard
     */
    public function index()
    {
        // Get hierarchy overview data
        $hierarchyData = $this->getHierarchyOverview();
        
        // Get activity distribution data
        $activityDistribution = $this->getActivityDistribution();
        
        // Get coverage analysis data
        $coverageAnalysis = $this->getCoverageAnalysis();
        
        // Get administrative efficiency data
        $efficiencyData = $this->getAdministrativeEfficiency();
        
        // Prepare chart data
        $chartData = $this->prepareChartData($hierarchyData, $activityDistribution, $coverageAnalysis, $efficiencyData);

        $data = [
            'title' => 'Government Structure Reports',
            'hierarchyData' => $hierarchyData,
            'activityDistribution' => $activityDistribution,
            'coverageAnalysis' => $coverageAnalysis,
            'efficiencyData' => $efficiencyData,
            'chartData' => $chartData
        ];

        return view('reports_gov_structure/reports_gov_structure_index', $data);
    }

    /**
     * Get hierarchy overview statistics
     */
    private function getHierarchyOverview()
    {
        $data = [];
        
        // Count by administrative level
        $data['provinces'] = $this->govStructureModel->where('level', 'province')->countAllResults();
        $data['districts'] = $this->govStructureModel->where('level', 'district')->countAllResults();
        $data['llgs'] = $this->govStructureModel->where('level', 'llg')->countAllResults();
        $data['wards'] = $this->govStructureModel->where('level', 'ward')->countAllResults();
        $data['total'] = $data['provinces'] + $data['districts'] + $data['llgs'] + $data['wards'];
        
        // Get detailed breakdown by province
        $provinces = $this->govStructureModel->where('level', 'province')->findAll();
        $data['provinceBreakdown'] = [];
        
        foreach ($provinces as $province) {
            $districtCount = $this->govStructureModel->where('parent_id', $province['id'])->where('level', 'district')->countAllResults();
            $llgCount = $this->govStructureModel
                ->join('gov_structure as districts', 'districts.id = gov_structure.parent_id')
                ->where('districts.parent_id', $province['id'])
                ->where('gov_structure.level', 'llg')
                ->countAllResults();
            $wardCount = $this->govStructureModel
                ->join('gov_structure as llgs', 'llgs.id = gov_structure.parent_id')
                ->join('gov_structure as districts', 'districts.id = llgs.parent_id')
                ->where('districts.parent_id', $province['id'])
                ->where('gov_structure.level', 'ward')
                ->countAllResults();
                
            $data['provinceBreakdown'][] = [
                'province' => $province,
                'districts' => $districtCount,
                'llgs' => $llgCount,
                'wards' => $wardCount,
                'total' => $districtCount + $llgCount + $wardCount
            ];
        }
        
        return $data;
    }

    /**
     * Get activity distribution across administrative levels
     */
    private function getActivityDistribution()
    {
        $data = [];

        // Activities by province (through proposals)
        $data['byProvince'] = $this->proposalModel
            ->select('gov_structure.name as province_name, gov_structure.code as province_code, COUNT(proposal.id) as activity_count')
            ->join('gov_structure', 'gov_structure.id = proposal.province_id', 'left')
            ->where('gov_structure.level', 'province')
            ->groupBy('proposal.province_id')
            ->orderBy('activity_count', 'DESC')
            ->findAll();

        // Activities by district (through proposals)
        $data['byDistrict'] = $this->proposalModel
            ->select('gov_structure.name as district_name, gov_structure.code as district_code, provinces.name as province_name, COUNT(proposal.id) as activity_count')
            ->join('gov_structure', 'gov_structure.id = proposal.district_id', 'left')
            ->join('gov_structure as provinces', 'provinces.id = proposal.province_id', 'left')
            ->where('gov_structure.level', 'district')
            ->groupBy('proposal.district_id')
            ->orderBy('activity_count', 'DESC')
            ->findAll();

        // Activities by type and location (through proposals and activities)
        $data['byTypeAndLocation'] = $this->proposalModel
            ->select('workplan_activities.activity_type, provinces.name as province_name, COUNT(proposal.id) as activity_count')
            ->join('workplan_activities', 'workplan_activities.id = proposal.activity_id', 'left')
            ->join('gov_structure as provinces', 'provinces.id = proposal.province_id', 'left')
            ->groupBy(['workplan_activities.activity_type', 'proposal.province_id'])
            ->orderBy('provinces.name, workplan_activities.activity_type')
            ->findAll();

        // Total activities by type (through proposals and activities)
        $data['byType'] = $this->proposalModel
            ->select('workplan_activities.activity_type, COUNT(proposal.id) as activity_count')
            ->join('workplan_activities', 'workplan_activities.id = proposal.activity_id', 'left')
            ->groupBy('workplan_activities.activity_type')
            ->findAll();

        return $data;
    }

    /**
     * Get coverage analysis - which areas have activities vs which don't
     */
    private function getCoverageAnalysis()
    {
        $data = [];

        // Provinces with activities (through proposals)
        $provincesWithActivities = $this->govStructureModel
            ->select('gov_structure.*, COUNT(proposal.id) as activity_count')
            ->join('proposal', 'proposal.province_id = gov_structure.id', 'left')
            ->where('gov_structure.level', 'province')
            ->groupBy('gov_structure.id')
            ->findAll();

        $data['provinceCoverage'] = [
            'withActivities' => 0,
            'withoutActivities' => 0,
            'details' => []
        ];

        foreach ($provincesWithActivities as $province) {
            if ($province['activity_count'] > 0) {
                $data['provinceCoverage']['withActivities']++;
            } else {
                $data['provinceCoverage']['withoutActivities']++;
            }
            $data['provinceCoverage']['details'][] = $province;
        }

        // Districts with activities (through proposals)
        $districtsWithActivities = $this->govStructureModel
            ->select('gov_structure.*, provinces.name as province_name, COUNT(proposal.id) as activity_count')
            ->join('proposal', 'proposal.district_id = gov_structure.id', 'left')
            ->join('gov_structure as provinces', 'provinces.id = gov_structure.parent_id', 'left')
            ->where('gov_structure.level', 'district')
            ->groupBy('gov_structure.id')
            ->findAll();

        $data['districtCoverage'] = [
            'withActivities' => 0,
            'withoutActivities' => 0,
            'details' => []
        ];

        foreach ($districtsWithActivities as $district) {
            if ($district['activity_count'] > 0) {
                $data['districtCoverage']['withActivities']++;
            } else {
                $data['districtCoverage']['withoutActivities']++;
            }
            $data['districtCoverage']['details'][] = $district;
        }

        return $data;
    }

    /**
     * Get administrative efficiency metrics
     */
    private function getAdministrativeEfficiency()
    {
        $data = [];

        // Activity density per province (activities per administrative unit through proposals)
        $data['provinceEfficiency'] = $this->govStructureModel
            ->select('
                gov_structure.name as province_name,
                gov_structure.code as province_code,
                COUNT(DISTINCT districts.id) as district_count,
                COUNT(DISTINCT llgs.id) as llg_count,
                COUNT(DISTINCT wards.id) as ward_count,
                COUNT(DISTINCT proposal.id) as activity_count
            ')
            ->join('gov_structure as districts', 'districts.parent_id = gov_structure.id AND districts.level = "district"', 'left')
            ->join('gov_structure as llgs', 'llgs.parent_id = districts.id AND llgs.level = "llg"', 'left')
            ->join('gov_structure as wards', 'wards.parent_id = llgs.id AND wards.level = "ward"', 'left')
            ->join('proposal', 'proposal.province_id = gov_structure.id', 'left')
            ->where('gov_structure.level', 'province')
            ->groupBy('gov_structure.id')
            ->findAll();

        // Calculate efficiency ratios
        foreach ($data['provinceEfficiency'] as &$province) {
            $totalUnits = $province['district_count'] + $province['llg_count'] + $province['ward_count'];
            $province['total_units'] = $totalUnits;
            $province['activity_density'] = $totalUnits > 0 ? round($province['activity_count'] / $totalUnits, 2) : 0;
        }

        // Most and least active areas (through proposals)
        $data['mostActive'] = $this->proposalModel
            ->select('provinces.name as province_name, districts.name as district_name, COUNT(proposal.id) as activity_count')
            ->join('gov_structure as provinces', 'provinces.id = proposal.province_id', 'left')
            ->join('gov_structure as districts', 'districts.id = proposal.district_id', 'left')
            ->groupBy(['proposal.province_id', 'proposal.district_id'])
            ->orderBy('activity_count', 'DESC')
            ->limit(10)
            ->findAll();

        return $data;
    }

    /**
     * Prepare chart data for visualizations
     */
    private function prepareChartData($hierarchyData, $activityDistribution, $coverageAnalysis, $efficiencyData)
    {
        $chartData = [];
        
        // Hierarchy overview chart
        $chartData['hierarchy'] = [
            'labels' => ['Provinces', 'Districts', 'LLGs', 'Wards'],
            'data' => [
                $hierarchyData['provinces'],
                $hierarchyData['districts'],
                $hierarchyData['llgs'],
                $hierarchyData['wards']
            ]
        ];
        
        // Activity distribution by province chart
        $chartData['activityByProvince'] = [
            'labels' => array_column($activityDistribution['byProvince'], 'province_name'),
            'data' => array_column($activityDistribution['byProvince'], 'activity_count')
        ];
        
        // Activity type distribution chart
        $chartData['activityByType'] = [
            'labels' => array_column($activityDistribution['byType'], 'activity_type'),
            'data' => array_column($activityDistribution['byType'], 'activity_count')
        ];
        
        // Coverage analysis chart
        $chartData['provinceCoverage'] = [
            'labels' => ['With Activities', 'Without Activities'],
            'data' => [
                $coverageAnalysis['provinceCoverage']['withActivities'],
                $coverageAnalysis['provinceCoverage']['withoutActivities']
            ]
        ];
        
        $chartData['districtCoverage'] = [
            'labels' => ['With Activities', 'Without Activities'],
            'data' => [
                $coverageAnalysis['districtCoverage']['withActivities'],
                $coverageAnalysis['districtCoverage']['withoutActivities']
            ]
        ];
        
        // Efficiency chart
        $chartData['efficiency'] = [
            'labels' => array_column($efficiencyData['provinceEfficiency'], 'province_name'),
            'data' => array_column($efficiencyData['provinceEfficiency'], 'activity_density')
        ];
        
        return $chartData;
    }

    /**
     * Export Government Structure report as PDF
     */
    public function exportPdf()
    {
        try {
            // Get all data
            $hierarchyData = $this->getHierarchyOverview();
            $activityDistribution = $this->getActivityDistribution();
            $coverageAnalysis = $this->getCoverageAnalysis();
            $efficiencyData = $this->getAdministrativeEfficiency();

            $data = [
                'title' => 'Government Structure Analytics Report',
                'hierarchyData' => $hierarchyData,
                'activityDistribution' => $activityDistribution,
                'coverageAnalysis' => $coverageAnalysis,
                'efficiencyData' => $efficiencyData,
                'generated_at' => date('Y-m-d H:i:s'),
                'generated_by' => session()->get('fname') . ' ' . session()->get('lname')
            ];

            // Generate PDF using PdfHelper
            $pdfHelper = new PdfHelper('Government Structure Analytics Report', 'L'); // Landscape for better table display

            // Add title
            $pdfHelper->addTitle('Government Structure Analytics Report', 16);
            $pdfHelper->addText('Generated on: ' . $data['generated_at']);
            $pdfHelper->addText('Generated by: ' . $data['generated_by']);
            $pdfHelper->addLineBreak(10);

            // Add hierarchy overview
            $pdfHelper->addSubtitle('Administrative Hierarchy Overview', 14);
            $hierarchyHeaders = ['Level', 'Count'];
            $hierarchyTableData = [
                ['Provinces', $hierarchyData['provinces']],
                ['Districts', $hierarchyData['districts']],
                ['LLGs', $hierarchyData['llgs']],
                ['Wards', $hierarchyData['wards']],
                ['Total', $hierarchyData['total']]
            ];
            $pdfHelper->addTable($hierarchyHeaders, $hierarchyTableData, [60, 40]);

            // Add activity distribution
            $pdfHelper->addSubtitle('Activity Distribution by Province', 14);
            if (!empty($activityDistribution['byProvince'])) {
                $activityHeaders = ['Province', 'Code', 'Activities'];
                $activityTableData = [];
                foreach ($activityDistribution['byProvince'] as $province) {
                    $activityTableData[] = [
                        $province['province_name'],
                        $province['province_code'],
                        $province['activity_count']
                    ];
                }
                $pdfHelper->addTable($activityHeaders, $activityTableData, [80, 40, 30]);
            }

            // Output PDF
            $filename = 'government_structure_report_' . date('Y-m-d_H-i-s') . '.pdf';
            return $pdfHelper->output($filename, 'D'); // D = Download

        } catch (\Exception $e) {
            log_message('error', 'Government Structure PDF Export Error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to generate PDF report: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
