<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\MtdpModel;
use App\Models\MtdpSpaModel;
use App\Models\MtdpDipModel;
use App\Models\NaspModel;
use App\Models\CorporatePlanModel;

class AjaxController extends Controller
{
    protected $mtdpModel;
    protected $mtdpSpaModel;
    protected $mtdpDipModel;
    protected $mtdpSpecificAreaModel;
    protected $mtdpInvestmentsModel;
    protected $mtdpKraModel;
    protected $mtdpStrategiesModel;
    protected $mtdpIndicatorsModel;
    protected $naspModel;
    protected $corporatePlanModel;

    public function __construct()
    {
        $this->mtdpModel = new MtdpModel();
        $this->mtdpSpaModel = new MtdpSpaModel();
        $this->mtdpDipModel = new MtdpDipModel();
        $this->mtdpSpecificAreaModel = new \App\Models\MtdpSpecificAreaModel();
        $this->mtdpInvestmentsModel = new \App\Models\MtdpInvestmentsModel();
        $this->mtdpKraModel = new \App\Models\MtdpKraModel();
        $this->mtdpStrategiesModel = new \App\Models\MtdpStrategiesModel();
        $this->mtdpIndicatorsModel = new \App\Models\MtdpIndicatorsModel();
        $this->naspModel = new NaspModel();
        $this->corporatePlanModel = new CorporatePlanModel();
    }

    /**
     * Helper method to ensure clean JSON output
     */
    private function cleanOutput($data, $status = 200)
    {
        // Disable debug output
        $this->response->noCache();

        // Turn off output buffering to prevent any HTML debug output
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // Set content type header
        $this->response->setHeader('Content-Type', 'application/json');

        // Return JSON response
        return $this->response->setStatusCode($status)->setJSON($data);
    }

    /**
     * Get all MTDP plans
     */
    public function getMtdpPlans()
    {
        try {
            $plans = $this->mtdpModel
                ->where('mtdp_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $plans,
                'message' => 'MTDP plans retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching MTDP plans: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve MTDP plans: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get SPAs for a specific MTDP plan
     */
    public function getMtdpSpas($mtdpId = null)
    {
        if (!$mtdpId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'MTDP Plan ID is required'
            ], 400);
        }

        try {
            $spas = $this->mtdpSpaModel
                ->where('mtdp_id', $mtdpId)
                ->where('spa_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $spas,
                'message' => 'SPAs retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching SPAs: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve SPAs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get DIPs for a specific SPA
     */
    public function getMtdpDips($spaId = null)
    {
        if (!$spaId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'SPA ID is required'
            ], 400);
        }

        try {
            $dips = $this->mtdpDipModel
                ->where('spa_id', $spaId)
                ->where('dip_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $dips,
                'message' => 'DIPs retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching DIPs: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve DIPs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get NASP APAs for a specific NASP plan
     */
    public function getNaspApas($naspId = null)
    {
        if (!$naspId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'NASP Plan ID is required'
            ], 400);
        }

        try {
            $apas = $this->naspModel
                ->where('parent_id', $naspId)
                ->where('type', 'kras')
                ->where('nasp_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $apas,
                'message' => 'NASP APAs retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching NASP APAs: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve NASP APAs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get NASP DIPs for a specific APA
     */
    public function getNaspDips($apaId = null)
    {
        if (!$apaId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'APA ID is required'
            ], 400);
        }

        try {
            $dips = $this->naspModel
                ->where('parent_id', $apaId)
                ->where('type', 'objectives')
                ->where('nasp_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $dips,
                'message' => 'NASP DIPs retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching NASP DIPs: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve NASP DIPs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get NASP Specific Areas for a specific DIP
     */
    public function getNaspSpecificAreas($dipId = null)
    {
        if (!$dipId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'DIP ID is required'
            ], 400);
        }

        try {
            $specificAreas = $this->naspModel
                ->where('parent_id', $dipId)
                ->where('type', 'specific_area')
                ->where('nasp_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $specificAreas,
                'message' => 'NASP Specific Areas retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching NASP Specific Areas: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve NASP Specific Areas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get NASP Objectives for a specific Specific Area
     */
    public function getNaspObjectives($specificAreaId = null)
    {
        if (!$specificAreaId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'Specific Area ID is required'
            ], 400);
        }

        try {
            $objectives = $this->naspModel
                ->where('parent_id', $specificAreaId)
                ->where('type', 'objectives')
                ->where('nasp_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $objectives,
                'message' => 'NASP Objectives retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching NASP Objectives: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve NASP Objectives: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get NASP Outputs for a specific Objective
     */
    public function getNaspOutputs($objectiveId = null)
    {
        if (!$objectiveId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'Objective ID is required'
            ], 400);
        }

        try {
            $outputs = $this->naspModel
                ->where('parent_id', $objectiveId)
                ->where('type', 'outputs')
                ->where('nasp_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $outputs,
                'message' => 'NASP Outputs retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching NASP Outputs: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve NASP Outputs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get NASP Output hierarchy information for a specific output
     */
    public function getNaspOutputHierarchy($outputId = null)
    {
        if (!$outputId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'Output ID is required'
            ], 400);
        }

        try {
            $db = \Config\Database::connect();
            $builder = $db->table('plans_nasp as outputs');
            $builder->select([
                'outputs.id as output_id',
                'outputs.code as output_code',
                'outputs.title as output_title',
                'objectives.id as objective_id',
                'objectives.code as objective_code',
                'objectives.title as objective_title',
                'specific_areas.id as specific_area_id',
                'specific_areas.code as specific_area_code',
                'specific_areas.title as specific_area_title',
                'dips.id as dip_id',
                'dips.code as dip_code',
                'dips.title as dip_title',
                'apas.id as apa_id',
                'apas.code as apa_code',
                'apas.title as apa_title',
                'plans.id as nasp_id',
                'plans.code as nasp_code',
                'plans.title as nasp_title'
            ]);
            $builder->join('plans_nasp as objectives', 'objectives.id = outputs.parent_id', 'left');
            $builder->join('plans_nasp as specific_areas', 'specific_areas.id = objectives.parent_id', 'left');
            $builder->join('plans_nasp as dips', 'dips.id = specific_areas.parent_id', 'left');
            $builder->join('plans_nasp as apas', 'apas.id = dips.parent_id', 'left');
            $builder->join('plans_nasp as plans', 'plans.id = apas.parent_id', 'left');
            $builder->where('outputs.id', $outputId);
            $builder->where('outputs.type', 'outputs');
            $builder->where('outputs.nasp_status', 1);
            $builder->where('outputs.deleted_at IS NULL');

            $result = $builder->get()->getRowArray();

            if (!$result) {
                return $this->cleanOutput([
                    'success' => false,
                    'message' => 'Output not found or inactive'
                ], 404);
            }

            return $this->cleanOutput([
                'success' => true,
                'data' => $result,
                'message' => 'Output hierarchy retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching NASP Output hierarchy: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve output hierarchy: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all active NASP Outputs with their hierarchy information
     */
    public function getAllNaspOutputs()
    {
        try {
            $db = \Config\Database::connect();
            $builder = $db->table('plans_nasp as outputs');
            $builder->select([
                'outputs.id as output_id',
                'outputs.code as output_code',
                'outputs.title as output_title',
                'objectives.id as objective_id',
                'objectives.code as objective_code',
                'objectives.title as objective_title',
                'specific_areas.id as specific_area_id',
                'specific_areas.code as specific_area_code',
                'dips.id as dip_id',
                'dips.code as dip_code',
                'apas.id as apa_id',
                'apas.code as apa_code',
                'plans.id as nasp_id',
                'plans.code as nasp_code',
                'plans.title as nasp_title'
            ]);
            $builder->join('plans_nasp as objectives', 'objectives.id = outputs.parent_id', 'left');
            $builder->join('plans_nasp as specific_areas', 'specific_areas.id = objectives.parent_id', 'left');
            $builder->join('plans_nasp as dips', 'dips.id = specific_areas.parent_id', 'left');
            $builder->join('plans_nasp as apas', 'apas.id = dips.parent_id', 'left');
            $builder->join('plans_nasp as plans', 'plans.id = apas.parent_id', 'left');
            $builder->where('outputs.type', 'outputs');
            $builder->where('outputs.nasp_status', 1);
            $builder->where('outputs.deleted_at IS NULL');
            $builder->where('plans.nasp_status', 1);
            $builder->where('plans.deleted_at IS NULL');

            $outputs = $builder->get()->getResultArray();

            return $this->cleanOutput([
                'success' => true,
                'data' => $outputs,
                'message' => 'All NASP Outputs retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching all NASP Outputs: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve all NASP Outputs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Corporate Plan Overarching Objectives
     */
    public function getCorporateOverarching($planId = null)
    {
        if (!$planId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'Corporate Plan ID is required'
            ], 400);
        }

        try {
            $overarching = $this->corporatePlanModel
                ->where('parent_id', $planId)
                ->where('type', 'overarching_objective')
                ->where('corp_plan_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $overarching,
                'message' => 'Corporate Plan Overarching Objectives retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching Corporate Plan Overarching Objectives: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve Corporate Plan Overarching Objectives: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Corporate Plan Objectives
     */
    public function getCorporateObjectives($overarchingId = null)
    {
        if (!$overarchingId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'Overarching Objective ID is required'
            ], 400);
        }

        try {
            $objectives = $this->corporatePlanModel
                ->where('parent_id', $overarchingId)
                ->where('type', 'objective')
                ->where('corp_plan_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $objectives,
                'message' => 'Corporate Plan Objectives retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching Corporate Plan Objectives: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve Corporate Plan Objectives: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Corporate Plan KRAs
     */
    public function getCorporateKras($objectiveId = null)
    {
        if (!$objectiveId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'Objective ID is required'
            ], 400);
        }

        try {
            $kras = $this->corporatePlanModel
                ->where('parent_id', $objectiveId)
                ->where('type', 'kra')
                ->where('corp_plan_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $kras,
                'message' => 'Corporate Plan KRAs retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching Corporate Plan KRAs: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve Corporate Plan KRAs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Corporate Plan Strategies
     */
    public function getCorporateStrategies($kraId = null)
    {
        if (!$kraId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'KRA ID is required'
            ], 400);
        }

        try {
            $strategies = $this->corporatePlanModel
                ->where('parent_id', $kraId)
                ->where('type', 'strategy')
                ->where('corp_plan_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $strategies,
                'message' => 'Corporate Plan Strategies retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching Corporate Plan Strategies: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve Corporate Plan Strategies: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get Corporate Plan Strategy hierarchy information for a specific strategy
     */
    public function getCorporateStrategyHierarchy($strategyId = null)
    {
        if (!$strategyId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'Strategy ID is required'
            ], 400);
        }

        try {
            $db = \Config\Database::connect();
            $builder = $db->table('plans_corporate_plan as strategies');
            $builder->select([
                'strategies.id as strategy_id',
                'strategies.code as strategy_code',
                'strategies.title as strategy_title',
                'kra.id as kra_id',
                'kra.code as kra_code',
                'kra.title as kra_title',
                'objective.id as objective_id',
                'objective.code as objective_code',
                'objective.title as objective_title',
                'overarching.id as overarching_id',
                'overarching.code as overarching_code',
                'overarching.title as overarching_title',
                'plan.id as plan_id',
                'plan.code as plan_code',
                'plan.title as plan_title'
            ]);
            $builder->join('plans_corporate_plan as kra', 'kra.id = strategies.parent_id', 'left');
            $builder->join('plans_corporate_plan as objective', 'objective.id = kra.parent_id', 'left');
            $builder->join('plans_corporate_plan as overarching', 'overarching.id = objective.parent_id', 'left');
            $builder->join('plans_corporate_plan as plan', 'plan.id = overarching.parent_id', 'left');
            $builder->where('strategies.id', $strategyId);
            $builder->where('strategies.type', 'strategy');
            $builder->where('strategies.corp_plan_status', 1);
            $builder->where('strategies.deleted_at IS NULL');

            $result = $builder->get()->getRowArray();

            if (!$result) {
                return $this->cleanOutput([
                    'success' => false,
                    'message' => 'Strategy not found or inactive'
                ], 404);
            }

            return $this->cleanOutput([
                'success' => true,
                'data' => $result,
                'message' => 'Corporate Plan Strategy hierarchy retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching Corporate Plan Strategy hierarchy: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve strategy hierarchy: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all active Corporate Plan Strategies with their hierarchy information
     */
    public function getAllCorporateStrategies()
    {
        try {
            $db = \Config\Database::connect();
            $builder = $db->table('plans_corporate_plan as strategies');
            $builder->select([
                'strategies.id as strategy_id',
                'strategies.code as strategy_code',
                'strategies.title as strategy_title',
                'kra.id as kra_id',
                'kra.code as kra_code',
                'kra.title as kra_title',
                'objective.id as objective_id',
                'objective.code as objective_code',
                'objective.title as objective_title',
                'overarching.id as overarching_id',
                'overarching.code as overarching_code',
                'overarching.title as overarching_title',
                'plan.id as plan_id',
                'plan.code as plan_code',
                'plan.title as plan_title'
            ]);
            $builder->join('plans_corporate_plan as kra', 'kra.id = strategies.parent_id', 'left');
            $builder->join('plans_corporate_plan as objective', 'objective.id = kra.parent_id', 'left');
            $builder->join('plans_corporate_plan as overarching', 'overarching.id = objective.parent_id', 'left');
            $builder->join('plans_corporate_plan as plan', 'plan.id = overarching.parent_id', 'left');
            $builder->where('strategies.type', 'strategy');
            $builder->where('strategies.corp_plan_status', 1);
            $builder->where('strategies.deleted_at IS NULL');
            $builder->where('plan.corp_plan_status', 1);
            $builder->where('plan.deleted_at IS NULL');

            $strategies = $builder->get()->getResultArray();

            return $this->cleanOutput([
                'success' => true,
                'data' => $strategies,
                'message' => 'All Corporate Plan Strategies retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching all Corporate Plan Strategies: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve all Corporate Plan Strategies: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get MTDP Specific Areas for a specific DIP
     */
    public function getMtdpSpecificAreas($dipId = null)
    {
        if (!$dipId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'DIP ID is required'
            ], 400);
        }

        try {
            $specificAreas = $this->mtdpSpecificAreaModel
                ->where('dip_id', $dipId)
                ->where('sa_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $specificAreas,
                'message' => 'MTDP Specific Areas retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching MTDP Specific Areas: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve MTDP Specific Areas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get MTDP Investments for a specific Specific Area
     */
    public function getMtdpInvestments($saId = null)
    {
        if (!$saId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'Specific Area ID is required'
            ], 400);
        }

        try {
            $investments = $this->mtdpInvestmentsModel
                ->where('sa_id', $saId)
                ->where('investment_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $investments,
                'message' => 'MTDP Investments retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching MTDP Investments: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve MTDP Investments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get MTDP KRAs for a specific Investment
     */
    public function getMtdpKras($investmentId = null)
    {
        if (!$investmentId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'Investment ID is required'
            ], 400);
        }

        try {
            $kras = $this->mtdpKraModel
                ->where('investment_id', $investmentId)
                ->where('kra_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $kras,
                'message' => 'MTDP KRAs retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching MTDP KRAs: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve MTDP KRAs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get MTDP Strategies for a specific KRA
     */
    public function getMtdpStrategies($kraId = null)
    {
        if (!$kraId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'KRA ID is required'
            ], 400);
        }

        try {
            $strategies = $this->mtdpStrategiesModel
                ->where('kra_id', $kraId)
                ->where('strategies_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $strategies,
                'message' => 'MTDP Strategies retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching MTDP Strategies: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve MTDP Strategies: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get MTDP Indicators for a specific Strategy
     */
    public function getMtdpIndicators($strategyId = null)
    {
        if (!$strategyId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'Strategy ID is required'
            ], 400);
        }

        try {
            $indicators = $this->mtdpIndicatorsModel
                ->where('strategies_id', $strategyId)
                ->where('indicators_status', 1)
                ->where('deleted_at IS NULL')
                ->findAll();

            return $this->cleanOutput([
                'success' => true,
                'data' => $indicators,
                'message' => 'MTDP Indicators retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching MTDP Indicators: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve MTDP Indicators: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get MTDP Strategy hierarchy information for a specific strategy
     */
    public function getMtdpStrategyHierarchy($strategyId = null)
    {
        if (!$strategyId) {
            return $this->cleanOutput([
                'success' => false,
                'message' => 'Strategy ID is required'
            ], 400);
        }

        try {
            $db = \Config\Database::connect();
            $builder = $db->table('plans_mtdp_strategies as strategies');
            $builder->select([
                'strategies.id as strategy_id',
                'strategies.strategy',
                'strategies.policy_reference',
                'kra.id as kra_id',
                'kra.kpi as kra_kpi',
                'investments.id as investment_id',
                'investments.investment',
                'sa.id as sa_id',
                'sa.sa_title',
                'sa.sa_code',
                'dip.id as dip_id',
                'dip.dip_title',
                'dip.dip_code',
                'spa.id as spa_id',
                'spa.title as spa_title',
                'spa.code as spa_code',
                'mtdp.id as mtdp_id',
                'mtdp.title as mtdp_title',
                'mtdp.abbrev as mtdp_code'
            ]);
            $builder->join('plans_mtdp_kra as kra', 'kra.id = strategies.kra_id', 'left');
            $builder->join('plans_mtdp_investments as investments', 'investments.id = kra.investment_id', 'left');
            $builder->join('plans_mtdp_specific_area as sa', 'sa.id = investments.sa_id', 'left');
            $builder->join('plans_mtdp_dip as dip', 'dip.id = sa.dip_id', 'left');
            $builder->join('plans_mtdp_spa as spa', 'spa.id = dip.spa_id', 'left');
            $builder->join('plans_mtdp as mtdp', 'mtdp.id = spa.mtdp_id', 'left');
            $builder->where('strategies.id', $strategyId);
            $builder->where('strategies.strategies_status', 1);
            $builder->where('strategies.deleted_at IS NULL');

            $result = $builder->get()->getRowArray();

            if (!$result) {
                return $this->cleanOutput([
                    'success' => false,
                    'message' => 'Strategy not found or inactive'
                ], 404);
            }

            return $this->cleanOutput([
                'success' => true,
                'data' => $result,
                'message' => 'MTDP Strategy hierarchy retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching MTDP Strategy hierarchy: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve strategy hierarchy: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all active MTDP Strategies with their hierarchy information
     */
    public function getAllMtdpStrategies()
    {
        try {
            $db = \Config\Database::connect();
            $builder = $db->table('plans_mtdp_strategies as strategies');
            $builder->select([
                'strategies.id as strategy_id',
                'strategies.strategy',
                'strategies.policy_reference',
                'kra.id as kra_id',
                'kra.kpi as kra_kpi',
                'investments.id as investment_id',
                'investments.investment',
                'sa.id as sa_id',
                'sa.sa_title',
                'sa.sa_code',
                'dip.id as dip_id',
                'dip.dip_title',
                'dip.dip_code',
                'spa.id as spa_id',
                'spa.title as spa_title',
                'spa.code as spa_code',
                'mtdp.id as mtdp_id',
                'mtdp.title as mtdp_title',
                'mtdp.abbrev as mtdp_code'
            ]);
            $builder->join('plans_mtdp_kra as kra', 'kra.id = strategies.kra_id', 'left');
            $builder->join('plans_mtdp_investments as investments', 'investments.id = kra.investment_id', 'left');
            $builder->join('plans_mtdp_specific_area as sa', 'sa.id = investments.sa_id', 'left');
            $builder->join('plans_mtdp_dip as dip', 'dip.id = sa.dip_id', 'left');
            $builder->join('plans_mtdp_spa as spa', 'spa.id = dip.spa_id', 'left');
            $builder->join('plans_mtdp as mtdp', 'mtdp.id = spa.mtdp_id', 'left');
            $builder->where('strategies.strategies_status', 1);
            $builder->where('strategies.deleted_at IS NULL');
            $builder->where('mtdp.mtdp_status', 1);
            $builder->where('mtdp.deleted_at IS NULL');

            $strategies = $builder->get()->getResultArray();

            return $this->cleanOutput([
                'success' => true,
                'data' => $strategies,
                'message' => 'All MTDP Strategies retrieved successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching all MTDP Strategies: ' . $e->getMessage());

            return $this->cleanOutput([
                'success' => false,
                'message' => 'Failed to retrieve all MTDP Strategies: ' . $e->getMessage()
            ], 500);
        }
    }
}
