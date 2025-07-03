<?php
// app/Controllers/Admin/MtdpPlanController.php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MtdpModel;
use App\Models\MtdpSpaModel;
use App\Models\MtdpDipModel;
use App\Models\MtdpSpecificAreaModel;
use App\Models\MtdpKraModel;
use App\Models\MtdpStrategiesModel;
use App\Models\MtdpIndicatorsModel;
use App\Models\MtdpInvestmentsModel;
use App\Models\UserModel;

class MtdpPlanController extends BaseController
{
    protected $mtdpModel;
    protected $mtdpSpaModel;
    protected $mtdpDipModel;
    protected $mtdpSpecificAreaModel;
    protected $mtdpKraModel;
    protected $mtdpStrategiesModel;
    protected $mtdpIndicatorsModel;
    protected $mtdpInvestmentsModel;
    protected $userModel;

    public function __construct()
    {
        $this->mtdpModel = new MtdpModel();
        $this->mtdpSpaModel = new MtdpSpaModel();
        $this->mtdpDipModel = new MtdpDipModel();
        $this->mtdpSpecificAreaModel = new MtdpSpecificAreaModel();
        $this->mtdpKraModel = new MtdpKraModel();
        $this->mtdpStrategiesModel = new MtdpStrategiesModel();
        $this->mtdpIndicatorsModel = new MtdpIndicatorsModel();
        $this->mtdpInvestmentsModel = new MtdpInvestmentsModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display the list of MTDP plans
     *
     * @return \CodeIgniter\HTTP\Response
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
     * Display the form to create a new MTDP plan
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function new()
    {
        $data = [
            'title' => 'Create New MTDP Plan'
        ];

        return view('admin/mtdp/mtdp_create', $data);
    }

    /**
     * Process request to create a new MTDP plan
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function create()
    {
        if ($this->request->isAJAX()) {
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

        // Standard form submission for non-AJAX requests
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
            return redirect()->to('admin/mtdp-plans')->with('success', 'MTDP Plan created successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create MTDP Plan');
        }
    }

    /**
     * Display the details of a specific MTDP plan
     *
     * @param int $id The MTDP plan ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function show($id)
    {
        $plan = $this->mtdpModel->find($id);

        if (empty($plan)) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        // Get user information if status_by is set
        if (!empty($plan['mtdp_status_by'])) {
            $user = $this->userModel->find($plan['mtdp_status_by']);
            if ($user) {
                $plan['status_by_name'] = $user['fname'] . ' ' . $user['lname'];
                $plan['status_by_email'] = $user['email'];
            }
        }

        $data = [
            'title' => 'MTDP Plan Details: ' . $plan['title'],
            'plan' => $plan
        ];

        return view('admin/mtdp/mtdp_show', $data);
    }

    /**
     * Display the form to edit an MTDP plan
     *
     * @param int $id The MTDP plan ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function edit($id)
    {
        $plan = $this->mtdpModel->find($id);

        if (empty($plan)) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        $data = [
            'title' => 'Edit MTDP Plan: ' . $plan['title'],
            'plan' => $plan
        ];

        return view('admin/mtdp/mtdp_edit', $data);
    }

    /**
     * Process request to update an existing MTDP plan
     *
     * @param int $id The MTDP plan ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function update($id = null)
    {
        if ($this->request->isAJAX()) {
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

        // Standard form submission for non-AJAX requests
        $data = [
            'abbrev' => $this->request->getPost('abbrev'),
            'title' => $this->request->getPost('title'),
            'date_from' => $this->request->getPost('date_from'),
            'date_to' => $this->request->getPost('date_to'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->mtdpModel->updatePlan($id, $data)) {
            return redirect()->to('admin/mtdp-plans')->with('success', 'MTDP Plan updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update MTDP Plan');
        }
    }

    /**
     * Process request to delete an MTDP plan and all related data
     *
     * @param int $id The MTDP plan ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function delete($id = null)
    {
        // Get the ID from POST if this is an AJAX request
        if ($this->request->isAJAX() && !$id) {
            $id = $this->request->getPost('id');

            // Check if this is a simulated DELETE request via POST with _method parameter
            $method = $this->request->getPost('_method');
            if ($method && strtoupper($method) === 'DELETE') {
                // This is a simulated DELETE request
                log_message('debug', 'Handling simulated DELETE request via POST for MTDP Plan ID: ' . $id);
            }
        }

        // Verify the MTDP plan exists
        $plan = $this->mtdpModel->find($id);
        if (!$plan) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'MTDP Plan not found'
                ]);
            }
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        // Start a database transaction
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // 1. Get all SPAs for this MTDP plan
            $spas = $this->mtdpSpaModel->where('mtdp_id', $id)->findAll();

            foreach ($spas as $spa) {
                $spaId = $spa['id'];

                // 2. Get all DIPs for this SPA
                $dips = $this->mtdpDipModel->where('spa_id', $spaId)->findAll();

                foreach ($dips as $dip) {
                    $dipId = $dip['id'];

                    // 3. Get all Specific Areas for this DIP
                    $specificAreas = $this->mtdpSpecificAreaModel->where('dip_id', $dipId)->findAll();

                    foreach ($specificAreas as $sa) {
                        $saId = $sa['id'];

                        // 4. Delete all Investments for this Specific Area
                        $this->mtdpInvestmentsModel->where('sa_id', $saId)->delete();
                    }

                    // 5. Delete all Specific Areas for this DIP
                    $this->mtdpSpecificAreaModel->where('dip_id', $dipId)->delete();

                    // 6. Delete all KRAs for this DIP
                    $this->mtdpKraModel->where('dip_id', $dipId)->delete();

                    // 7. Delete all Strategies for this DIP
                    $this->mtdpStrategiesModel->where('dip_id', $dipId)->delete();

                    // 8. Delete all Indicators for this DIP
                    $this->mtdpIndicatorsModel->where('dip_id', $dipId)->delete();

                    // 9. Delete all Investments directly linked to this DIP
                    $this->mtdpInvestmentsModel->where('dip_id', $dipId)->delete();
                }

                // 10. Delete all DIPs for this SPA
                $this->mtdpDipModel->where('spa_id', $spaId)->delete();
            }

            // 11. Delete all SPAs for this MTDP plan
            $this->mtdpSpaModel->where('mtdp_id', $id)->delete();

            // 12. Finally, delete the MTDP plan itself
            $this->mtdpModel->delete($id);

            // Commit the transaction if everything was successful
            $db->transCommit();

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'MTDP Plan and all related data deleted successfully'
                ]);
            }

            return redirect()->to('admin/mtdp-plans')->with('success', 'MTDP Plan and all related data deleted successfully');

        } catch (\Exception $e) {
            // Rollback the transaction if any error occurred
            $db->transRollback();

            log_message('error', 'Error deleting MTDP Plan: ' . $e->getMessage());

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to delete MTDP Plan: ' . $e->getMessage()
                ]);
            }

            return redirect()->back()->with('error', 'Failed to delete MTDP Plan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the status of an MTDP plan
     *
     * @return \CodeIgniter\HTTP\Response
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
            $plan = $this->mtdpModel->find($id);
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'MTDP Plan status updated successfully',
                'current_status' => $plan['mtdp_status'],
                'status_text' => ($plan['mtdp_status'] == 1) ? 'Active' : 'Inactive'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update MTDP Plan status'
        ]);
    }

    /**
     * Get MTDP Plan details including status information
     *
     * @param int $id The MTDP plan ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function getPlanDetails($id)
    {
        // Fetch the plan
        $plan = $this->mtdpModel->find($id);

        if (!$plan) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'MTDP Plan not found'
            ]);
        }

        // Get user information if status_by is set
        if (!empty($plan['mtdp_status_by'])) {
            $user = $this->userModel->find($plan['mtdp_status_by']);
            if ($user) {
                $plan['status_by_name'] = $user['fname'] . ' ' . $user['lname'];
                $plan['status_by_email'] = $user['email'];
            }
        }

        // Get count of SPAs
        $plan['spa_count'] = $this->mtdpSpaModel->where('mtdp_id', $id)->countAllResults();

        // Get count of DIPs
        $plan['dip_count'] = $this->mtdpDipModel->where('mtdp_id', $id)->countAllResults();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $plan
        ]);
    }

    /**
     * Display the list of SPAs for a specific MTDP plan
     *
     * @param int $mtdpId The MTDP plan ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function spas($mtdpId)
    {
        // Get the MTDP plan
        $plan = $this->mtdpModel->find($mtdpId);

        if (!$plan) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        // Get all SPAs for this plan
        $spas = $this->mtdpSpaModel->where('mtdp_id', $mtdpId)->findAll();

        $data = [
            'title' => 'Strategic Priority Areas: ' . $plan['title'],
            'plan' => $plan,
            'spas' => $spas
        ];

        return view('admin/mtdp/mtdp_spas_list', $data);
    }

    /**
     * Display the form to create a new SPA
     *
     * @param int $mtdpId The MTDP plan ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function newSpa($mtdpId)
    {
        // Get the MTDP plan
        $plan = $this->mtdpModel->find($mtdpId);

        if (!$plan) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        $data = [
            'title' => 'Create New Strategic Priority Area',
            'plan' => $plan
        ];

        return view('admin/mtdp/mtdp_spa_create', $data);
    }

    /**
     * Process request to create a new SPA
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function createSpa()
    {
        if ($this->request->isAJAX()) {
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

            if ($this->mtdpSpaModel->save($data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Strategic Priority Area created successfully'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to create Strategic Priority Area'
            ]);
        }

        // Standard form submission for non-AJAX requests
        $mtdpId = $this->request->getPost('mtdp_id');
        $data = [
            'mtdp_id' => $mtdpId,
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => session()->get('user_id'),
            'spa_status' => 1,
            'spa_status_by' => session()->get('user_id'),
            'spa_status_at' => date('Y-m-d H:i:s')
        ];

        if ($this->mtdpSpaModel->save($data)) {
            return redirect()->to('admin/mtdp-plans/spas/' . $mtdpId)->with('success', 'Strategic Priority Area created successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create Strategic Priority Area');
        }
    }

    /**
     * Display the form to edit an SPA
     *
     * @param int $id The SPA ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function editSpa($id)
    {
        // Get the SPA
        $spa = $this->mtdpSpaModel->find($id);

        if (!$spa) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategic Priority Area not found');
        }

        // Get the MTDP plan
        $plan = $this->mtdpModel->find($spa['mtdp_id']);

        if (!$plan) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        $data = [
            'title' => 'Edit Strategic Priority Area',
            'spa' => $spa,
            'plan' => $plan
        ];

        return view('admin/mtdp/mtdp_spa_edit', $data);
    }

    /**
     * Process request to update an existing SPA
     *
     * @param int $id The SPA ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function updateSpa($id = null)
    {
        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $mtdpId = $this->request->getPost('mtdp_id');

            $data = [
                'id' => $id,
                'code' => $this->request->getPost('code'),
                'title' => $this->request->getPost('title'),
                'remarks' => $this->request->getPost('remarks'),
                'updated_by' => session()->get('user_id')
            ];

            if ($this->mtdpSpaModel->save($data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Strategic Priority Area updated successfully'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to update Strategic Priority Area'
            ]);
        }

        // Standard form submission for non-AJAX requests
        $mtdpId = $this->request->getPost('mtdp_id');
        $data = [
            'id' => $id,
            'code' => $this->request->getPost('code'),
            'title' => $this->request->getPost('title'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->mtdpSpaModel->save($data)) {
            return redirect()->to('admin/mtdp-plans/spas/' . $mtdpId)->with('success', 'Strategic Priority Area updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update Strategic Priority Area');
        }
    }

    /**
     * Process request to delete an SPA
     *
     * @param int $id The SPA ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function deleteSpa($id = null)
    {
        // Get the SPA to get the mtdp_id for redirection
        $spa = $this->mtdpSpaModel->find($id);

        if (!$spa) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategic Priority Area not found');
        }

        $mtdpId = $spa['mtdp_id'];

        // Check if we have an AJAX request
        if ($this->request->isAJAX()) {
            if ($this->mtdpSpaModel->delete($id)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Strategic Priority Area deleted successfully'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete Strategic Priority Area'
            ]);
        }

        // Standard request handling for non-AJAX requests
        if ($this->mtdpSpaModel->delete($id)) {
            return redirect()->to('admin/mtdp-plans/spas/' . $mtdpId)->with('success', 'Strategic Priority Area deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete Strategic Priority Area');
        }
    }

    /**
     * Toggle the status of an SPA
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function toggleSpaStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }

        $id = $this->request->getPost('id');
        $userId = session()->get('user_id');
        $statusRemarks = $this->request->getPost('spa_status_remarks');

        // Get current SPA
        $spa = $this->mtdpSpaModel->find($id);

        if (!$spa) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Strategic Priority Area not found']);
        }

        // Toggle status
        $newStatus = $spa['spa_status'] == 1 ? 0 : 1;

        // Prepare data for status update
        $statusData = [
            'id' => $id,
            'spa_status' => $newStatus,
            'spa_status_by' => $userId,
            'spa_status_at' => date('Y-m-d H:i:s')
        ];

        // Add status remarks if provided
        if (!empty($statusRemarks)) {
            $statusData['spa_status_remarks'] = $statusRemarks;
        }

        if ($this->mtdpSpaModel->save($statusData)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Strategic Priority Area status updated successfully',
                'current_status' => $newStatus,
                'status_text' => ($newStatus == 1) ? 'Active' : 'Inactive'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update Strategic Priority Area status'
        ]);
    }

    /**
     * Get SPA details including status information
     *
     * @param int $id The SPA ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function getSpaDetails($id)
    {
        // Fetch the SPA
        $spa = $this->mtdpSpaModel->find($id);

        if (!$spa) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Strategic Priority Area not found'
            ]);
        }

        // Get the MTDP plan
        $plan = $this->mtdpModel->find($spa['mtdp_id']);

        if (!$plan) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'MTDP Plan not found'
            ]);
        }

        // Get user information if status_by is set
        if (!empty($spa['spa_status_by'])) {
            $user = $this->userModel->find($spa['spa_status_by']);
            if ($user) {
                $spa['status_by_name'] = $user['fname'] . ' ' . $user['lname'];
                $spa['status_by_email'] = $user['email'];
            }
        }

        // Get count of DIPs
        $spa['dip_count'] = $this->mtdpDipModel->where('spa_id', $id)->countAllResults();

        // Add MTDP plan title
        $spa['mtdp_title'] = $plan['title'];

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $spa
        ]);
    }

    /**
     * Display the list of DIPs for a specific SPA
     *
     * @param int $spaId The SPA ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function dips($spaId)
    {
        // Get the SPA with MTDP data
        $spa = $this->mtdpSpaModel->getSpaWithMtdp($spaId);

        if (!$spa) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategic Priority Area not found');
        }

        // Get the MTDP plan
        $mtdp = $this->mtdpModel->find($spa['mtdp_id']);

        if (!$mtdp) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        // Get all DIPs for this SPA
        $dips = $this->mtdpDipModel->where('spa_id', $spaId)->findAll();

        $data = [
            'title' => 'Deliberate Intervention Programs for ' . $spa['title'],
            'spa' => $spa,
            'mtdp' => $mtdp,
            'dips' => $dips
        ];

        return view('admin/mtdp/mtdp_dips_list', $data);
    }

    /**
     * Display the form to create a new DIP
     *
     * @param int $spaId The SPA ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function newDip($spaId)
    {
        // Load models
        $spaModel = $this->mtdpSpaModel;
        $mtdpModel = $this->mtdpModel;

        // Get SPA details
        $spa = $spaModel->find($spaId);

        if (empty($spa)) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategic Priority Area not found');
        }

        // Get MTDP Plan details
        $mtdp = $mtdpModel->find($spa['mtdp_id']);

        if (empty($mtdp)) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        $data = [
            'title' => 'Create New Deliberate Intervention Program',
            'spa' => $spa,
            'mtdp' => $mtdp
        ];

        return view('admin/mtdp/mtdp_dip_create', $data);
    }

    /**
     * Process request to create a new DIP
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function createDip()
    {
        // Process AJAX request
        if ($this->request->isAJAX()) {
            // Collecting form data
            $spaId = $this->request->getPost('spa_id');

            // Get SPA details to get mtdp_id
            $spa = $this->mtdpSpaModel->find($spaId);

            if (!$spa) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Strategic Priority Area not found'
                ]);
            }

            // Handle JSON fields from hidden *_json inputs
            $investments = $this->parseJsonField('investments_json');
            $kras = $this->parseJsonField('kras_json');
            $strategies = $this->parseJsonField('strategies_json');
            $indicators = $this->parseJsonField('indicators_json');

            // Prepare data for database
            $data = [
                'mtdp_id' => $spa['mtdp_id'],
                'spa_id' => $spaId,
                'dip_code' => $this->request->getPost('dip_code'),
                'dip_title' => $this->request->getPost('dip_title'),
                'dip_remarks' => $this->request->getPost('dip_remarks'),
                'investments' => $investments,
                'total_investment' => $this->request->getPost('total_investment'),
                'funding_source' => $this->request->getPost('funding_source'),
                'implementing_agency' => $this->request->getPost('implementing_agency'),
                'implementation_period' => $this->request->getPost('implementation_period'),
                'kras' => $kras,
                'strategies' => $strategies,
                'indicators' => $indicators,
                'created_by' => session()->get('user_id'),
                'dip_status' => 1,
                'dip_status_by' => session()->get('user_id'),
                'dip_status_at' => date('Y-m-d H:i:s')
            ];

            try {
                if ($this->mtdpDipModel->insert($data)) {
                    return $this->response->setJSON([
                        'status' => 'success',
                        'message' => 'Development Investment Plan created successfully'
                    ]);
                } else {
                    $errors = $this->mtdpDipModel->errors();
                    $errorString = implode(', ', $errors);
                    return $this->response->setJSON([
                        'status' => 'error',
                        'message' => 'Failed to create Development Investment Plan: ' . $errorString
                    ]);
                }
            } catch (\Exception $e) {
                log_message('error', 'Error creating DIP: ' . $e->getMessage());
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'System error creating Development Investment Plan: ' . $e->getMessage()
                ]);
            }
        }

        // Process standard form submission
        $spaId = $this->request->getPost('spa_id');

        // Get SPA details with MTDP data
        $spa = $this->mtdpSpaModel->getSpaWithMtdp($spaId);

        if (!$spa) {
            return redirect()->back()->with('error', 'Strategic Priority Area not found');
        }

        // Handle JSON fields from hidden *_json inputs
        $investments = $this->parseJsonField('investments_json');
        $kras = $this->parseJsonField('kras_json');
        $strategies = $this->parseJsonField('strategies_json');
        $indicators = $this->parseJsonField('indicators_json');

        // Prepare data for database
        $data = [
            'mtdp_id' => $spa['mtdp_id'],
            'spa_id' => $spaId,
            'dip_code' => $this->request->getPost('dip_code'),
            'dip_title' => $this->request->getPost('dip_title'),
            'dip_remarks' => $this->request->getPost('dip_remarks'),
            'investments' => $investments,
            'total_investment' => $this->request->getPost('total_investment'),
            'funding_source' => $this->request->getPost('funding_source'),
            'implementing_agency' => $this->request->getPost('implementing_agency'),
            'implementation_period' => $this->request->getPost('implementation_period'),
            'kras' => $kras,
            'strategies' => $strategies,
            'indicators' => $indicators,
            'created_by' => session()->get('user_id'),
            'dip_status' => 1,
            'dip_status_by' => session()->get('user_id'),
            'dip_status_at' => date('Y-m-d H:i:s')
        ];

        try {
            if ($this->mtdpDipModel->insert($data)) {
                return redirect()->to('admin/mtdp-plans/spas/' . $spaId . '/dips')->with('success', 'Deliberate Intervention Program created successfully');
            } else {
                $errors = $this->mtdpDipModel->errors();
                return redirect()->back()->withInput()->with('error', 'Failed to create Deliberate Intervention Program: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            log_message('error', 'Error creating DIP: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'System error creating Deliberate Intervention Program');
        }
    }

    /**
     * Display the form to edit a DIP
     *
     * @param int $id The DIP ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function editDip($id)
    {
        // Load models
        $dipModel = $this->mtdpDipModel;
        $spaModel = $this->mtdpSpaModel;
        $mtdpModel = $this->mtdpModel;
        $userModel = $this->userModel;

        // Get DIP details
        $dip = $dipModel->find($id);

        if (empty($dip)) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Development Investment Plan not found');
        }

        // Debug DIP data
        log_message('debug', 'DIP Data from database: ' . print_r($dip, true));

        // Get SPA details
        $spa = $spaModel->find($dip['spa_id']);

        if (empty($spa)) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategic Priority Area not found');
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
            'title' => 'Edit Development Investment Plan: ' . $dip['dip_code'],
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp
        ];

        return view('admin/mtdp/mtdp_dip_edit', $data);
    }

    /**
     * Process request to update an existing DIP
     *
     * @param int $id The DIP ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function updateDip($id = null)
    {
        // Get the ID if it's from a form submission
        if (!$id) {
            $id = $this->request->getPost('id');
        }

        // Get DIP details
        $dip = $this->mtdpDipModel->find($id);

        if (!$dip) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Development Investment Plan not found');
        }

        // Load required JSON fields
        $investments = json_decode($this->request->getPost('investments_json'), true);
        $kras = json_decode($this->request->getPost('kras_json'), true);
        $strategies = json_decode($this->request->getPost('strategies_json'), true);
        $indicators = json_decode($this->request->getPost('indicators_json'), true);

        // Ensure we have arrays, not null values
        $investments = $investments ?? [];
        $kras = $kras ?? [];
        $strategies = $strategies ?? [];
        $indicators = $indicators ?? [];

        // Log the JSON data for debugging
        log_message('debug', 'Investments JSON before update: ' . json_encode($investments));
        log_message('debug', 'KRAs JSON before update: ' . json_encode($kras));
        log_message('debug', 'Strategies JSON before update: ' . json_encode($strategies));
        log_message('debug', 'Indicators JSON before update: ' . json_encode($indicators));

        // Prepare data for database
        $data = [
            'dip_code' => $this->request->getPost('dip_code'),
            'dip_title' => $this->request->getPost('dip_title'),
            'dip_remarks' => $this->request->getPost('dip_remarks'),
            'investments' => json_encode($investments),
            'kras' => json_encode($kras),
            'strategies' => json_encode($strategies),
            'indicators' => json_encode($indicators),
            'total_investment' => $this->request->getPost('total_investment'),
            'funding_source' => $this->request->getPost('funding_source'),
            'implementing_agency' => $this->request->getPost('implementing_agency'),
            'implementation_period' => $this->request->getPost('implementation_period'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id')
        ];

        // Log the full update data
        log_message('debug', 'Full DIP update data: ' . print_r($data, true));

        try {
            // Use the model directly instead of updateDip
            if ($this->mtdpDipModel->update($id, $data)) {
                return redirect()->to('admin/mtdp-plans/spas/' . $dip['spa_id'] . '/dips/' . $id)
                               ->with('success', 'Deliberate Intervention Program updated successfully');
            }

            log_message('error', 'Failed to update DIP with errors: ' . print_r($this->mtdpDipModel->errors(), true));
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to update Deliberate Intervention Program');
        } catch (\Exception $e) {
            log_message('error', 'Exception updating DIP: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'System error updating Deliberate Intervention Program: ' . $e->getMessage());
        }
    }

    /**
     * Process request to delete a DIP
     *
     * @param int $id The DIP ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function deleteDip($id = null)
    {
        // Get the DIP to get the spa_id for redirection
        $dip = $this->mtdpDipModel->find($id);

        if (!$dip) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Development Investment Plan not found');
        }

        $spaId = $dip['spa_id'];

        // Check if we have an AJAX request
        if ($this->request->isAJAX()) {
            if ($this->mtdpDipModel->delete($id)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Development Investment Plan deleted successfully'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete Development Investment Plan'
            ]);
        }

        // Standard request handling for non-AJAX requests
        if ($this->mtdpDipModel->delete($id)) {
            return redirect()->to('admin/mtdp-plans/spas/' . $spaId . '/dips')->with('success', 'Development Investment Plan deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete Development Investment Plan');
        }
    }

    /**
     * Toggle the status of a DIP
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function toggleDipStatus()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request method']);
        }

        $id = $this->request->getPost('id');
        $userId = session()->get('user_id');
        $statusRemarks = $this->request->getPost('dip_status_remarks');

        // Get current DIP
        $dip = $this->mtdpDipModel->find($id);

        if (!$dip) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Development Investment Plan not found']);
        }

        // Toggle status
        $newStatus = $dip['dip_status'] == 1 ? 0 : 1;

        // Prepare data for status update
        $statusData = [
            'id' => $id,
            'dip_status' => $newStatus,
            'dip_status_by' => $userId,
            'dip_status_at' => date('Y-m-d H:i:s')
        ];

        // Add status remarks if provided
        if (!empty($statusRemarks)) {
            $statusData['dip_status_remarks'] = $statusRemarks;
        }

        if ($this->mtdpDipModel->save($statusData)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Development Investment Plan status updated successfully',
                'current_status' => $newStatus,
                'status_text' => ($newStatus == 1) ? 'Active' : 'Inactive'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update Development Investment Plan status'
        ]);
    }

    /**
     * Get DIP details including status information
     *
     * @param int $id The DIP ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function getDipDetails($id)
    {
        // Fetch the DIP
        $dip = $this->mtdpDipModel->find($id);

        if (!$dip) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Deliberate Intervention Program not found'
            ]);
        }

        // Get the SPA information
        $spa = $this->mtdpSpaModel->find($dip['spa_id']);

        if (!$spa) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Strategic Priority Area not found'
            ]);
        }

        // Get the MTDP plan information
        $mtdp = $this->mtdpModel->find($dip['mtdp_id']);

        if (!$mtdp) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'MTDP Plan not found'
            ]);
        }

        // Get user information if status_by is set
        if (!empty($dip['dip_status_by'])) {
            $user = $this->userModel->find($dip['dip_status_by']);
            if ($user) {
                $dip['status_by_name'] = $user['fname'] . ' ' . $user['lname'];
                $dip['status_by_email'] = $user['email'];
            }
        }

        // Add related information
        $dip['spa_title'] = $spa['title'];
        $dip['mtdp_title'] = $mtdp['title'];

        // Ensure JSON fields are properly decoded
        $jsonFields = ['investments', 'kras', 'strategies', 'indicators'];
        foreach ($jsonFields as $field) {
            if (isset($dip[$field]) && is_string($dip[$field])) {
                try {
                    $dip[$field] = json_decode($dip[$field], true);
                } catch (\Exception $e) {
                    log_message('error', "Error decoding JSON field {$field}: " . $e->getMessage());
                    $dip[$field] = [];
                }
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $dip
        ]);
    }

    /**
     * Display full details of a DIP in a view
     *
     * @param int $dipId The DIP ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function showDip($dipId)
    {
        // Get the DIP details
        $dip = $this->mtdpDipModel->find($dipId);

        if (!$dip) {
            return redirect()->to(base_url('admin/mtdp-plans'))->with('error', 'Development Investment Plan not found');
        }

        // Get related SPA
        $spa = $this->mtdpSpaModel->find($dip['spa_id']);
        if (!$spa) {
            return redirect()->to(base_url('admin/mtdp-plans'))->with('error', 'Strategic Priority Area not found');
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

        // Ensure JSON fields are properly decoded
        $jsonFields = ['investments', 'kras', 'strategies', 'indicators'];
        foreach ($jsonFields as $field) {
            if (isset($dip[$field]) && is_string($dip[$field])) {
                try {
                    $dip[$field] = json_decode($dip[$field], true);
                } catch (\Exception $e) {
                    log_message('error', "Error decoding JSON field {$field}: " . $e->getMessage());
                    $dip[$field] = [];
                }
            }
        }

        $data = [
            'title' => 'View Development Investment Plan Details',
            'mtdp' => $mtdp,
            'spa' => $spa,
            'dip' => $dip
        ];

        return view('admin/mtdp/mtdp_dip_view', $data);
    }

    /**
     * Helper method to parse JSON fields from form inputs
     *
     * @param string $fieldName The name of the field to parse
     * @return string JSON encoded string
     */
    private function parseJsonField($fieldName)
    {
        $data = $this->request->getPost($fieldName);

        if (empty($data)) {
            return json_encode([]);
        }

        // If data is already a JSON string, return it as is
        if (is_string($data) && $this->isJson($data)) {
            return $data;
        }

        // If data is an array, encode it to JSON
        if (is_array($data)) {
            return json_encode($data);
        }

        // Otherwise, assume it's a serialized form data that needs to be parsed
        return json_encode($data);
    }

    /**
     * Helper method to check if a string is valid JSON
     *
     * @param string $string The string to check
     * @return bool True if the string is valid JSON, false otherwise
     */
    private function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }

        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
