<?php

namespace App\Controllers;

use App\Models\WorkplanInfrastructureActivityModel;
use App\Models\WorkplanInputActivityModel;
use App\Models\WorkplanTrainingActivityModel;
use App\Models\WorkplanActivityModel;
use App\Models\GovStructureModel;
use App\Models\SmeModel;
use App\Services\PdfService;

class ActivityMapsReportsController extends BaseController
{
    protected $workplanInfrastructureActivityModel;
    protected $workplanInputActivityModel;
    protected $workplanTrainingActivityModel;
    protected $workplanActivityModel;
    protected $govStructureModel;
    protected $smeModel;

    public function __construct()
    {
        $this->workplanInfrastructureActivityModel = new WorkplanInfrastructureActivityModel();
        $this->workplanInputActivityModel = new WorkplanInputActivityModel();
        $this->workplanTrainingActivityModel = new WorkplanTrainingActivityModel();
        $this->workplanActivityModel = new WorkplanActivityModel();
        $this->govStructureModel = new GovStructureModel();
        $this->smeModel = new SmeModel();
    }

    /**
     * Display the activities map page
     *
     * @return mixed
     */
    public function index()
    {
        try {
            // Get all activities with GPS coordinates
            $data = [
                'title' => 'Activities Map',
                'activities' => $this->getAllActivitiesWithCoordinates()
            ];

            return view('reports_activity_maps/reports_activity_map_index', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error in ActivityMapsReportsController::index: ' . $e->getMessage());

            // Return a view with error message
            $data = [
                'title' => 'Activities Map - Error',
                'activities' => [],
                'error' => $e->getMessage()
            ];

            return view('reports_activity_maps/reports_activity_map_index', $data);
        }
    }

    /**
     * Get all activities with GPS coordinates from all three activity models
     *
     * @return array
     */
    private function getAllActivitiesWithCoordinates()
    {
        $activities = [];

        // Get infrastructure activities with coordinates
        $infrastructureActivities = $this->getInfrastructureActivities();
        foreach ($infrastructureActivities as $activity) {
            $activities[] = [
                'id' => $activity['id'],
                'type' => 'infrastructure',
                'title' => $activity['activity_title'] ?? 'Infrastructure Activity',
                'description' => $activity['infrastructure'] ?? '',
                'coordinates' => $activity['gps_coordinates'],
                'location' => 'GPS: ' . $activity['gps_coordinates'],
                'workplan_id' => $activity['workplan_id'],
                'activity_id' => $activity['activity_id']
            ];
        }

        // Get input activities with coordinates
        $inputActivities = $this->getInputActivities();
        foreach ($inputActivities as $activity) {
            // Parse inputs JSON if available
            $inputsDescription = '';
            if (!empty($activity['inputs'])) {
                try {
                    $inputs = json_decode($activity['inputs'], true);
                    if (is_array($inputs)) {
                        $inputItems = [];
                        foreach ($inputs as $input) {
                            if (isset($input['name'])) {
                                $inputItems[] = $input['name'];
                            }
                        }
                        $inputsDescription = implode(', ', $inputItems);
                    }
                } catch (\Exception $e) {
                    $inputsDescription = $activity['inputs'];
                }
            }

            $activities[] = [
                'id' => $activity['id'],
                'type' => 'inputs',
                'title' => $activity['activity_title'] ?? 'Input Activity',
                'description' => $inputsDescription,
                'coordinates' => $activity['gps_coordinates'],
                'location' => 'GPS: ' . $activity['gps_coordinates'],
                'workplan_id' => $activity['workplan_id'],
                'activity_id' => $activity['activity_id']
            ];
        }

        // Get training activities with coordinates
        $trainingActivities = $this->getTrainingActivities();
        foreach ($trainingActivities as $activity) {
            $activities[] = [
                'id' => $activity['id'],
                'type' => 'training',
                'title' => $activity['activity_title'] ?? 'Training Activity',
                'description' => $activity['topics'] ?? '',
                'coordinates' => $activity['gps_coordinates'],
                'location' => 'GPS: ' . $activity['gps_coordinates'],
                'workplan_id' => $activity['workplan_id'],
                'activity_id' => $activity['activity_id']
            ];
        }

        // Get SME locations with coordinates
        $smeLocations = $this->getSmeLocations();
        foreach ($smeLocations as $sme) {
            $activities[] = [
                'id' => $sme['id'],
                'type' => 'sme',
                'title' => $sme['sme_name'] ?? 'SME',
                'description' => $sme['description'] ?? '',
                'coordinates' => $sme['gps_coordinates'],
                'location' => $sme['village_name'] . ', ' . $sme['llg_name'] . ', ' . $sme['district_name'] . ', ' . $sme['province_name'],
                'contact_details' => $sme['contact_details'] ?? '',
                'sme_id' => $sme['id']
            ];
        }

        return $activities;
    }

    /**
     * Get infrastructure activities with GPS coordinates
     *
     * @return array
     */
    private function getInfrastructureActivities()
    {
        $builder = $this->workplanInfrastructureActivityModel->builder();
        $builder->select('
            workplan_infrastructure_activities.id,
            workplan_infrastructure_activities.workplan_id,
            workplan_infrastructure_activities.activity_id,
            workplan_infrastructure_activities.infrastructure,
            workplan_infrastructure_activities.gps_coordinates,
            workplan_activities.title as activity_title
        ');
        $builder->join('workplan_activities', 'workplan_activities.id = workplan_infrastructure_activities.activity_id', 'left');
        $builder->where('workplan_infrastructure_activities.gps_coordinates IS NOT NULL');
        $builder->where('workplan_infrastructure_activities.gps_coordinates !=', '');
        $builder->where('workplan_infrastructure_activities.deleted_at IS NULL');

        return $builder->get()->getResultArray();
    }

    /**
     * Get input activities with GPS coordinates
     *
     * @return array
     */
    private function getInputActivities()
    {
        $builder = $this->workplanInputActivityModel->builder();
        $builder->select('
            workplan_input_activities.id,
            workplan_input_activities.workplan_id,
            workplan_input_activities.activity_id,
            workplan_input_activities.inputs,
            workplan_input_activities.gps_coordinates,
            workplan_activities.title as activity_title
        ');
        $builder->join('workplan_activities', 'workplan_activities.id = workplan_input_activities.activity_id', 'left');
        $builder->where('workplan_input_activities.gps_coordinates IS NOT NULL');
        $builder->where('workplan_input_activities.gps_coordinates !=', '');
        $builder->where('workplan_input_activities.deleted_at IS NULL');

        return $builder->get()->getResultArray();
    }

    /**
     * Get training activities with GPS coordinates
     *
     * @return array
     */
    private function getTrainingActivities()
    {
        $builder = $this->workplanTrainingActivityModel->builder();
        $builder->select('
            workplan_training_activities.id,
            workplan_training_activities.workplan_id,
            workplan_training_activities.activity_id,
            workplan_training_activities.topics,
            workplan_training_activities.gps_coordinates,
            workplan_activities.title as activity_title
        ');
        $builder->join('workplan_activities', 'workplan_activities.id = workplan_training_activities.activity_id', 'left');
        $builder->where('workplan_training_activities.gps_coordinates IS NOT NULL');
        $builder->where('workplan_training_activities.gps_coordinates !=', '');
        $builder->where('workplan_training_activities.deleted_at IS NULL');

        return $builder->get()->getResultArray();
    }

    /**
     * Get SME locations with GPS coordinates
     *
     * @return array
     */
    private function getSmeLocations()
    {
        $builder = $this->smeModel->builder();
        $builder->select('
            sme.id,
            sme.sme_name,
            sme.description,
            sme.gps_coordinates,
            sme.contact_details,
            sme.village_name,
            p.name as province_name,
            d.name as district_name,
            l.name as llg_name
        ');
        $builder->join('gov_structure as p', 'p.id = sme.province_id', 'left');
        $builder->join('gov_structure as d', 'd.id = sme.district_id', 'left');
        $builder->join('gov_structure as l', 'l.id = sme.llg_id', 'left');
        $builder->where('sme.gps_coordinates IS NOT NULL');
        $builder->where('sme.gps_coordinates !=', '');
        $builder->where('sme.deleted_at IS NULL');
        $builder->where('sme.status', 'active'); // Only show active SMEs

        return $builder->get()->getResultArray();
    }

    /**
     * Export activity maps report as PDF
     *
     * @return mixed
     */
    public function exportPdf()
    {
        try {
            // Get all activities with GPS coordinates
            $activities = $this->getAllActivitiesWithCoordinates();

            // Get SME locations
            $smeLocations = $this->getSmeLocations();

            // Prepare chart data (basic statistics)
            $chartData = [
                'totalActivities' => count($activities),
                'totalSmes' => count($smeLocations),
                'activitiesByType' => [
                    'infrastructure' => count(array_filter($activities, fn($a) => $a['type'] === 'infrastructure')),
                    'inputs' => count(array_filter($activities, fn($a) => $a['type'] === 'inputs')),
                    'training' => count(array_filter($activities, fn($a) => $a['type'] === 'training')),
                    'output' => count(array_filter($activities, fn($a) => $a['type'] === 'output'))
                ]
            ];

            // Prepare data array for PDF service
            $data = [
                'activities' => $activities,
                'smeLocations' => $smeLocations
            ];

            // Generate PDF using PdfService
            $pdfService = new PdfService();
            return $pdfService->generateReportPdf('activity-maps', $data, $chartData);

        } catch (\Exception $e) {
            log_message('error', 'Activity Maps Report PDF Export Error: ' . $e->getMessage());
            return redirect()->to('/reports/activities-map')->with('error', 'Failed to generate PDF report. Please try again.');
        }
    }
}
