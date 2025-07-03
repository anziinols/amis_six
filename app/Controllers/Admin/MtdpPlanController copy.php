<?php
// app/Controllers/Admin/MtdpPlanController.php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MtdpModel;
use App\Models\MtdpSpaModel;
use App\Models\MtdpDipModel;
use App\Models\UserModel;

class MtdpPlanController extends BaseController
{
    protected $mtdpModel;
    protected $mtdpSpaModel;
    protected $mtdpDipModel;
    protected $userModel;

    public function __construct()
    {
        $this->mtdpModel = new MtdpModel();
        $this->mtdpSpaModel = new MtdpSpaModel();
        $this->mtdpDipModel = new MtdpDipModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display the list of MTDP plans
     */
    public function index()
    {
        $data = [
            'title' => 'MTDP Plans',
            'plans' => $this->mtdpModel->getPlans()
        ];

        return view('admin/mtdp/mtdp_list', $data);
    }

    /**
     * Process AJAX request to create a new MTDP plan
     */
    public function create()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }

        $data = [
            'abbrev' => $this->request->getPost('abbrev'),
            'title' => $this->request->getPost('title'),
            'date_from' => $this->request->getPost('date_from'),
            'date_to' => $this->request->getPost('date_to'),
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => session()->get('user_id'),
            'mtdp_status' => 1,
            'mtdp_status_by' => session()->get('user_id'),
            'mtdp_status_at' => date('Y-m-d H:i:s')
        ];

        if ($this->mtdpModel->createPlan($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'MTDP Plan created successfully'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to create MTDP Plan'
        ]);
    }

    /**
     * Process AJAX request to update an existing MTDP plan
     */
    public function update()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }

        $id = $this->request->getPost('id');

        $data = [
            'abbrev' => $this->request->getPost('abbrev'),
            'title' => $this->request->getPost('title'),
            'date_from' => $this->request->getPost('date_from'),
            'date_to' => $this->request->getPost('date_to'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->mtdpModel->updatePlan($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'MTDP Plan updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update MTDP Plan'
        ]);
    }

    /**
     * Toggle the status of an MTDP plan
     */
    public function toggleStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }

        $id = $this->request->getPost('id');
        $userId = session()->get('user_id');
        $statusRemarks = $this->request->getPost('mtdp_status_remarks');

        // Prepare data for status update
        $statusData = [
            'mtdp_status_by' => $userId,
            'mtdp_status_at' => date('Y-m-d H:i:s')
        ];

        // Add status remarks if provided
        if (!empty($statusRemarks)) {
            $statusData['mtdp_status_remarks'] = $statusRemarks;
        }

        if ($this->mtdpModel->toggleStatus($id, $userId, $statusData)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update status'
        ]);
    }

    /**
     * Get MTDP Plan details including status information
     */
    public function getPlanDetails($id)
    {
        $plan = $this->mtdpModel->find($id);

        if (!$plan) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'MTDP Plan not found'
            ]);
        }

        $userData = [];

        if (!empty($plan['mtdp_status_by'])) {
            $user = $this->userModel->find($plan['mtdp_status_by']);

            if ($user) {
                $userData = [
                    'id' => $user['id'],
                    'name' => $user['fname'] . ' ' . $user['lname'],
                    'email' => $user['email'],
                    'status_at' => $plan['mtdp_status_at'],
                    'status_remarks' => $plan['mtdp_status_remarks'] ?? ''
                ];
            }
        }

        $data = [
            'id' => $plan['id'],
            'abbrev' => $plan['abbrev'],
            'title' => $plan['title'],
            'date_from' => $plan['date_from'],
            'date_to' => $plan['date_to'],
            'remarks' => $plan['remarks'],
            'status' => $plan['mtdp_status'],
            'status_info' => $userData,
            'created_at' => $plan['created_at'],
            'updated_at' => $plan['updated_at']
        ];

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Display the list of SPAs for a specific MTDP plan
     */
    public function spas($mtdpId)
    {
        $plan = $this->mtdpModel->find($mtdpId);

        if (!$plan) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        $data = [
            'title' => 'SPAs for ' . $plan['title'],
            'plan' => $plan,
            'spas' => $this->mtdpSpaModel->getSpasByMtdpId($mtdpId)
        ];

        return view('admin/mtdp/mtdp_spas_list', $data);
    }

    /**
     * Process AJAX request to create a new SPA
     */
    public function createSpa()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }

        $data = [
            'mtdp_id' => $this->request->getPost('mtdp_id'),
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => session()->get('user_id'),
            'spa_status' => 1,
            'spa_status_by' => session()->get('user_id'),
            'spa_status_at' => date('Y-m-d H:i:s')
        ];

        if ($this->mtdpSpaModel->createSpa($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'SPA created successfully'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to create SPA'
        ]);
    }

    /**
     * Process AJAX request to update an existing SPA
     */
    public function updateSpa()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }

        $id = $this->request->getPost('id');

        $data = [
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->mtdpSpaModel->updateSpa($id, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'SPA updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update SPA'
        ]);
    }

    /**
     * Process AJAX request to toggle status of an SPA
     */
    public function toggleSpaStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }

        $id = $this->request->getPost('id');
        $userId = session()->get('user_id');
        $statusRemarks = $this->request->getPost('spa_status_remarks');

        $statusData = [
            'spa_status_by' => $userId,
            'spa_status_remarks' => $statusRemarks
        ];

        if ($this->mtdpSpaModel->toggleStatus($id, $statusData)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update status'
        ]);
    }

    /**
     * Get SPA details including status information
     */
    public function getSpaDetails($id)
    {
        $spa = $this->mtdpSpaModel->find($id);

        if (!$spa) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'SPA not found'
            ]);
        }

        $userData = [];

        if (!empty($spa['spa_status_by'])) {
            $user = $this->userModel->find($spa['spa_status_by']);

            if ($user) {
                $userData = [
                    'id' => $user['id'],
                    'name' => $user['fname'] . ' ' . $user['lname'],
                    'email' => $user['email'],
                    'status_at' => $spa['spa_status_at'],
                    'status_remarks' => $spa['spa_status_remarks'] ?? ''
                ];
            }
        }

        // Get the MTDP Plan title
        $mtdpPlan = $this->mtdpModel->find($spa['mtdp_id']);

        $data = [
            'id' => $spa['id'],
            'mtdp_id' => $spa['mtdp_id'],
            'mtdp_title' => $mtdpPlan ? $mtdpPlan['title'] : 'Unknown MTDP',
            'code' => $spa['code'],
            'title' => $spa['title'],
            'remarks' => $spa['remarks'],
            'status' => $spa['spa_status'],
            'status_info' => $userData,
            'created_at' => $spa['created_at'],
            'updated_at' => $spa['updated_at']
        ];

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Display the list of DIPs for a specific SPA
     */
    public function dips($spaId)
    {
        $spa = $this->mtdpSpaModel->find($spaId);

        if (!$spa) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'SPA not found');
        }

        $mtdp = $this->mtdpModel->find($spa['mtdp_id']);

        $data = [
            'title' => 'Deliberate Intervention Programs for ' . $spa['title'],
            'mtdp' => $mtdp,
            'spa' => $spa,
            'dips' => $this->mtdpDipModel->getDipsBySpaId($spaId)
        ];

        return view('admin/mtdp/mtdp_dips_list', $data);
    }

    /**
     * Process AJAX request to create a new DIP
     */
    public function createDip()
    {
        // Check if this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request method'
            ]);
        }

        $spaId = $this->request->getPost('spa_id');
        $mtdpId = $this->request->getPost('mtdp_id');

        if (!$spaId || !$mtdpId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'SPA ID and MTDP ID are required'
            ]);
        }

        // Get the JSON data from form fields
        $investmentsJson = $this->request->getPost('investments_json');
        $krasJson = $this->request->getPost('kras_json');
        $strategiesJson = $this->request->getPost('strategies_json');
        $indicatorsJson = $this->request->getPost('indicators_json');

        // Debug JSON data received
        log_message('debug', '[createDip] JSON data received:');
        log_message('debug', '[createDip] investments_json: ' . $investmentsJson);
        log_message('debug', '[createDip] kras_json: ' . $krasJson);
        log_message('debug', '[createDip] strategies_json: ' . $strategiesJson);
        log_message('debug', '[createDip] indicators_json: ' . $indicatorsJson);

        // Parse JSON data or use empty arrays
        $investments = [];
        if (!empty($investmentsJson)) {
            try {
                $investments = json_decode($investmentsJson, true);
                if (!is_array($investments)) {
                    log_message('error', '[createDip] Invalid investments JSON, using empty array');
                    $investments = [];
                }
            } catch (\Exception $e) {
                log_message('error', '[createDip] Error parsing investments JSON: ' . $e->getMessage());
                $investments = [];
            }
        }

        $kras = [];
        if (!empty($krasJson)) {
            try {
                $kras = json_decode($krasJson, true);
                if (!is_array($kras)) {
                    log_message('error', '[createDip] Invalid KRAs JSON, using empty array');
                    $kras = [];
                }
            } catch (\Exception $e) {
                log_message('error', '[createDip] Error parsing KRAs JSON: ' . $e->getMessage());
                $kras = [];
            }
        }

        $strategies = [];
        if (!empty($strategiesJson)) {
            try {
                $strategies = json_decode($strategiesJson, true);
                if (!is_array($strategies)) {
                    log_message('error', '[createDip] Invalid strategies JSON, using empty array');
                    $strategies = [];
                }
            } catch (\Exception $e) {
                log_message('error', '[createDip] Error parsing strategies JSON: ' . $e->getMessage());
                $strategies = [];
            }
        }

        $indicators = [];
        if (!empty($indicatorsJson)) {
            try {
                $indicators = json_decode($indicatorsJson, true);
                if (!is_array($indicators)) {
                    log_message('error', '[createDip] Invalid indicators JSON, using empty array');
                    $indicators = [];
                }
            } catch (\Exception $e) {
                log_message('error', '[createDip] Error parsing indicators JSON: ' . $e->getMessage());
                $indicators = [];
            }
        }

        // Prepare data for creation
        $data = [
            'spa_id' => $spaId,
            'mtdp_id' => $mtdpId,
            'dip_code' => $this->request->getPost('dip_code'),
            'dip_title' => $this->request->getPost('dip_title'),
            'dip_remarks' => $this->request->getPost('dip_remarks'),
            'investments' => json_encode($investments),
            'kras' => json_encode($kras),
            'strategies' => json_encode($strategies),
            'indicators' => json_encode($indicators),
            'created_by' => session()->get('user_id'),
            'dip_status' => 1,
            'dip_status_by' => session()->get('user_id'),
            'dip_status_at' => date('Y-m-d H:i:s')
        ];

        // Debug data to be saved
        log_message('debug', '[createDip] Data to be saved:');
        log_message('debug', '[createDip] investments: ' . $data['investments']);
        log_message('debug', '[createDip] kras: ' . $data['kras']);
        log_message('debug', '[createDip] strategies: ' . $data['strategies']);
        log_message('debug', '[createDip] indicators: ' . $data['indicators']);

        try {
            $result = $this->mtdpDipModel->createDip($data);
            log_message('debug', '[createDip] Creation result: ' . print_r($result, true));
            log_message('debug', '[createDip] Validation errors: ' . print_r($this->mtdpDipModel->errors(), true));

            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'DIP created successfully'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to create DIP: ' . json_encode($this->mtdpDipModel->errors())
            ]);
        } catch (\Exception $e) {
            log_message('error', '[createDip] Exception during creation: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Exception: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Process AJAX request to update an existing DIP
     */
    public function updateDip()
    {
        // Check if this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request method'
            ]);
        }

        // Get the DIP ID
        $dipId = $this->request->getPost('dip_id');

        if (!$dipId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'DIP ID is required'
            ]);
        }

        // Get the updated data
        $investments = [];

        // Use simple array for investments if individual fields are present
        $invItems = $this->request->getPost('inv_item') ?? [];
        $invAmounts = $this->request->getPost('inv_amount') ?? [];
        $invYears = $this->request->getPost('inv_year') ?? [];
        $invFundings = $this->request->getPost('inv_funding') ?? [];

        for ($i = 0; $i < count($invItems); $i++) {
            if (!empty($invItems[$i]) && isset($invAmounts[$i])) {
                $investments[] = [
                    'item' => $invItems[$i],
                    'amount' => $invAmounts[$i],
                    'year' => $invYears[$i] ?? '',
                    'funding' => $invFundings[$i] ?? ''
                ];
            }
        }

        // Get the JSON data from form fields
        $investmentsJson = $this->request->getPost('investments_json');
        $krasJson = $this->request->getPost('kras_json');
        $strategiesJson = $this->request->getPost('strategies_json');
        $indicatorsJson = $this->request->getPost('indicators_json');

        // Debug JSON data received
        log_message('debug', '[updateDip] JSON data received:');
        log_message('debug', '[updateDip] investments_json: ' . $investmentsJson);
        log_message('debug', '[updateDip] kras_json: ' . $krasJson);
        log_message('debug', '[updateDip] strategies_json: ' . $strategiesJson);
        log_message('debug', '[updateDip] indicators_json: ' . $indicatorsJson);

        // Parse JSON data or use empty arrays
        if (!empty($investmentsJson)) {
            try {
                $investments = json_decode($investmentsJson, true);
                if (!is_array($investments)) {
                    log_message('error', '[updateDip] Invalid investments JSON, using empty array');
                    $investments = [];
                }
            } catch (\Exception $e) {
                log_message('error', '[updateDip] Error parsing investments JSON: ' . $e->getMessage());
                $investments = [];
            }
        }

        $kras = [];
        if (!empty($krasJson)) {
            try {
                $kras = json_decode($krasJson, true);
                if (!is_array($kras)) {
                    log_message('error', '[updateDip] Invalid KRAs JSON, using empty array');
                    $kras = [];
                }
            } catch (\Exception $e) {
                log_message('error', '[updateDip] Error parsing KRAs JSON: ' . $e->getMessage());
                $kras = [];
            }
        }

        $strategies = [];
        if (!empty($strategiesJson)) {
            try {
                $strategies = json_decode($strategiesJson, true);
                if (!is_array($strategies)) {
                    log_message('error', '[updateDip] Invalid strategies JSON, using empty array');
                    $strategies = [];
                }
            } catch (\Exception $e) {
                log_message('error', '[updateDip] Error parsing strategies JSON: ' . $e->getMessage());
                $strategies = [];
            }
        }

        $indicators = [];
        if (!empty($indicatorsJson)) {
            try {
                $indicators = json_decode($indicatorsJson, true);
                if (!is_array($indicators)) {
                    log_message('error', '[updateDip] Invalid indicators JSON, using empty array');
                    $indicators = [];
                }
            } catch (\Exception $e) {
                log_message('error', '[updateDip] Error parsing indicators JSON: ' . $e->getMessage());
                $indicators = [];
            }
        }

        // Prepare data for update
        $data = [
            'dip_code' => $this->request->getPost('dip_code'),
            'dip_title' => $this->request->getPost('dip_title'),
            'dip_remarks' => $this->request->getPost('dip_remarks'),
            'investments' => json_encode($investments),
            'kras' => json_encode($kras),
            'strategies' => json_encode($strategies),
            'indicators' => json_encode($indicators),
            'updated_by' => session()->get('user_id'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Debug data to be saved
        log_message('debug', '[updateDip] Data to be saved:');
        log_message('debug', '[updateDip] investments: ' . $data['investments']);
        log_message('debug', '[updateDip] kras: ' . $data['kras']);
        log_message('debug', '[updateDip] strategies: ' . $data['strategies']);
        log_message('debug', '[updateDip] indicators: ' . $data['indicators']);

        try {
            $result = $this->mtdpDipModel->update($dipId, $data);
            log_message('debug', '[updateDip] Update result: ' . print_r($result, true));
            log_message('debug', '[updateDip] Validation errors: ' . print_r($this->mtdpDipModel->errors(), true));

            if ($result) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'DIP updated successfully'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to update DIP: ' . json_encode($this->mtdpDipModel->errors())
            ]);
        } catch (\Exception $e) {
            log_message('error', '[updateDip] Exception during update: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Exception: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Process AJAX request to toggle status of a DIP
     */
    public function toggleDipStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }

        $id = $this->request->getPost('id');
        $userId = session()->get('user_id');
        $statusRemarks = $this->request->getPost('dip_status_remarks');

        $statusData = [
            'dip_status_by' => $userId,
            'dip_status_remarks' => $statusRemarks
        ];

        if ($this->mtdpDipModel->toggleStatus($id, $statusData)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Status updated successfully'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update status'
        ]);
    }

    /**
     * Get DIP details including status information
     */
    public function getDipDetails($id)
    {
        $dip = $this->mtdpDipModel->find($id);

        if (!$dip) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'DIP not found'
            ]);
        }

        $userData = [];

        if (!empty($dip['dip_status_by'])) {
            $user = $this->userModel->find($dip['dip_status_by']);

            if ($user) {
                $userData = [
                    'id' => $user['id'],
                    'name' => $user['fname'] . ' ' . $user['lname'],
                    'email' => $user['email'],
                    'status_at' => $dip['dip_status_at'],
                    'status_remarks' => $dip['dip_status_remarks'] ?? ''
                ];
            }
        }

        $data = [
            'id' => $dip['id'],
            'dip_code' => $dip['dip_code'],
            'title' => $dip['dip_title'],
            'remarks' => $dip['dip_remarks'],
            'status' => $dip['dip_status'],
            'status_info' => $userData,
            'created_at' => $dip['created_at'],
            'updated_at' => $dip['updated_at']
        ];

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Display the form to create a new DIP
     */
    public function showCreateDipForm($spaId)
    {
        // Load models
        $spaModel = new MtdpSpaModel();
        $mtdpModel = new MtdpModel();

        // Get SPA details
        $spa = $spaModel->find($spaId);

        if (empty($spa)) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'SPA not found');
        }

        // Get MTDP Plan details
        $mtdp = $mtdpModel->find($spa['mtdp_id']);

        if (empty($mtdp)) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        $data = [
            'title' => 'Create New DIP',
            'spa' => $spa,
            'mtdp' => $mtdp
        ];

        return view('admin/mtdp/mtdp_dip_create', $data);
    }

    /**
     * Display the form to edit a DIP
     */
    public function showEditDipForm($dipId)
    {
        // Load models
        $dipModel = new MtdpDipModel();
        $spaModel = new MtdpSpaModel();
        $mtdpModel = new MtdpModel();
        $userModel = new UserModel();

        // Get DIP details
        $dip = $dipModel->find($dipId);

        if (empty($dip)) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'DIP not found');
        }

        // Debug DIP data
        log_message('debug', 'DIP Data from database: ' . print_r($dip, true));

        // Get SPA details
        $spa = $spaModel->find($dip['spa_id']);

        if (empty($spa)) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'SPA not found');
        }

        // Get MTDP Plan details
        $mtdp = $mtdpModel->find($spa['mtdp_id']);

        if (empty($mtdp)) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        // Get user information if status_by is set
        if (!empty($dip['dip_status_by'])) {
            $user = $userModel->find($dip['dip_status_by']);
            if ($user) {
                $dip['status_by_name'] = $user['fname'] . ' ' . $user['lname'];
                $dip['status_by_email'] = $user['email'];
            }
        }

        // Ensure JSON fields are properly decoded
        $jsonFields = ['investments', 'kras', 'strategies', 'indicators'];
        foreach ($jsonFields as $field) {
            if (isset($dip[$field]) && is_string($dip[$field])) {
                try {
                    $dip[$field] = json_decode($dip[$field], true);
                    log_message('debug', "Decoded JSON field {$field}: " . print_r($dip[$field], true));
                } catch (\Exception $e) {
                    log_message('error', "Error decoding JSON field {$field}: " . $e->getMessage());
                    $dip[$field] = [];
                }
            } else {
                log_message('debug', "Field {$field} is not a string or not set: " . (isset($dip[$field]) ? gettype($dip[$field]) : 'not set'));
            }
        }

        $data = [
            'title' => 'Edit DIP: ' . $dip['dip_code'],
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp
        ];

        return view('admin/mtdp/mtdp_dip_edit', $data);
    }

    /**
     * View DIP details in a dedicated page
     */
    public function viewDip($dipId)
    {
        // Get the DIP details
        $dip = $this->mtdpDipModel->find($dipId);

        if (!$dip) {
            return redirect()->to(base_url('admin/mtdp-plans'))->with('error', 'DIP not found');
        }

        // Get related SPA
        $spa = $this->mtdpSpaModel->find($dip['spa_id']);
        if (!$spa) {
            return redirect()->to(base_url('admin/mtdp-plans'))->with('error', 'SPA not found');
        }

        // Get the MTDP plan for breadcrumb navigation
        $mtdp = $this->mtdpModel->find($dip['mtdp_id']);

        // Get user name for status_by
        if (!empty($dip['dip_status_by'])) {
            $user = $this->userModel->find($dip['dip_status_by']);
            if ($user) {
                $dip['status_by_name'] = $user['fname'] . ' ' . $user['lname'];
            }
        }

        $data = [
            'title' => 'View DIP Details',
            'mtdp' => $mtdp,
            'spa' => $spa,
            'dip' => $dip
        ];

        return view('admin/mtdp/mtdp_dip_view', $data);
    }
}
