<?php
// app/Controllers/Admin/MTDPStrategiesController.php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MtdpModel;
use App\Models\MtdpSpaModel;
use App\Models\MtdpDipModel;
use App\Models\MtdpSpecificAreaModel;
use App\Models\MtdpInvestmentsModel;
use App\Models\MtdpKraModel;
use App\Models\MtdpStrategiesModel;
use App\Models\UserModel;

class MTDPStrategiesController extends BaseController
{
    protected $mtdpModel;
    protected $mtdpSpaModel;
    protected $mtdpDipModel;
    protected $mtdpSpecificAreaModel;
    protected $mtdpInvestmentsModel;
    protected $mtdpKraModel;
    protected $mtdpStrategiesModel;
    protected $userModel;

    public function __construct()
    {
        $this->mtdpModel = new MtdpModel();
        $this->mtdpSpaModel = new MtdpSpaModel();
        $this->mtdpDipModel = new MtdpDipModel();
        $this->mtdpSpecificAreaModel = new MtdpSpecificAreaModel();
        $this->mtdpInvestmentsModel = new MtdpInvestmentsModel();
        $this->mtdpKraModel = new MtdpKraModel();
        $this->mtdpStrategiesModel = new MtdpStrategiesModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display the list of Strategies for a KRA
     *
     * @param int $kraId The KRA ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function index($kraId)
    {
        // Get the KRA with related data
        $kra = $this->mtdpKraModel->find($kraId);

        if (!$kra) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'KRA not found');
        }

        // Get the Investment
        $investment = $this->mtdpInvestmentsModel->find($kra['investment_id']);

        if (!$investment) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Investment not found');
        }

        // Get the Specific Area
        $specificArea = $this->mtdpSpecificAreaModel->find($kra['sa_id']);

        if (!$specificArea) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Specific Area not found');
        }

        // Get the DIP
        $dip = $this->mtdpDipModel->find($kra['dip_id']);

        if (!$dip) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Deliberate Intervention Program not found');
        }

        // Get the SPA
        $spa = $this->mtdpSpaModel->find($kra['spa_id']);

        if (!$spa) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategic Priority Area not found');
        }

        // Get the MTDP plan
        $mtdp = $this->mtdpModel->find($kra['mtdp_id']);

        if (!$mtdp) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        // Get all Strategies for this KRA
        $strategies = $this->mtdpStrategiesModel->where('kra_id', $kraId)->findAll();

        $data = [
            'title' => 'Strategies for KRA',
            'kra' => $kra,
            'investment' => $investment,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp,
            'strategies' => $strategies
        ];

        return view('admin/mtdp/mtdp_strategies_list', $data);
    }

    /**
     * Display the form to create a new Strategy
     *
     * @param int $kraId The KRA ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function new($kraId)
    {
        // Get the KRA with related data
        $kra = $this->mtdpKraModel->find($kraId);

        if (!$kra) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'KRA not found');
        }

        // Get the Investment
        $investment = $this->mtdpInvestmentsModel->find($kra['investment_id']);

        if (!$investment) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Investment not found');
        }

        // Get the Specific Area
        $specificArea = $this->mtdpSpecificAreaModel->find($kra['sa_id']);

        if (!$specificArea) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Specific Area not found');
        }

        // Get the DIP
        $dip = $this->mtdpDipModel->find($kra['dip_id']);

        if (!$dip) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Deliberate Intervention Program not found');
        }

        // Get the SPA
        $spa = $this->mtdpSpaModel->find($kra['spa_id']);

        if (!$spa) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategic Priority Area not found');
        }

        // Get the MTDP plan
        $mtdp = $this->mtdpModel->find($kra['mtdp_id']);

        if (!$mtdp) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        $data = [
            'title' => 'Create New Strategy',
            'kra' => $kra,
            'investment' => $investment,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp
        ];

        return view('admin/mtdp/mtdp_strategies_create', $data);
    }

    /**
     * Process request to create a new Strategy
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function create()
    {
        // Get the KRA ID from the form
        $kraId = $this->request->getPost('kra_id');

        // Get the KRA with related data
        $kra = $this->mtdpKraModel->find($kraId);

        if (!$kra) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'KRA not found');
        }

        // Prepare data for database
        $data = [
            'mtdp_id' => $kra['mtdp_id'],
            'spa_id' => $kra['spa_id'],
            'dip_id' => $kra['dip_id'],
            'sa_id' => $kra['sa_id'],
            'investment_id' => $kra['investment_id'],
            'kra_id' => $kraId,
            'strategy' => $this->request->getPost('strategy'),
            'policy_reference' => $this->request->getPost('policy_reference'),
            'strategies_status' => 1,
            'strategies_status_by' => session()->get('user_id'),
            'strategies_status_at' => date('Y-m-d H:i:s'),
            'created_by' => session()->get('user_id')
        ];

        // Insert the data
        $result = $this->mtdpStrategiesModel->insert($data);

        if ($result) {
            return redirect()->to('admin/mtdp-plans/kras/' . $kraId . '/strategies')
                ->with('success', 'Strategy created successfully');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to create Strategy')
                ->withInput();
        }
    }

    /**
     * Display the details of a Strategy
     *
     * @param int $id The Strategy ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function show($id)
    {
        // Get the Strategy with related data
        $strategy = $this->mtdpStrategiesModel->find($id);

        if (!$strategy) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategy not found');
        }

        // Get the KRA
        $kra = $this->mtdpKraModel->find($strategy['kra_id']);

        if (!$kra) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'KRA not found');
        }

        // Get the Investment
        $investment = $this->mtdpInvestmentsModel->find($strategy['investment_id']);

        if (!$investment) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Investment not found');
        }

        // Get the Specific Area
        $specificArea = $this->mtdpSpecificAreaModel->find($strategy['sa_id']);

        if (!$specificArea) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Specific Area not found');
        }

        // Get the DIP
        $dip = $this->mtdpDipModel->find($strategy['dip_id']);

        if (!$dip) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Deliberate Intervention Program not found');
        }

        // Get the SPA
        $spa = $this->mtdpSpaModel->find($strategy['spa_id']);

        if (!$spa) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategic Priority Area not found');
        }

        // Get the MTDP plan
        $mtdp = $this->mtdpModel->find($strategy['mtdp_id']);

        if (!$mtdp) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        // Get user names for created_by, updated_by, and status_by
        $createdByName = '';
        if (!empty($strategy['created_by'])) {
            $createdBy = $this->userModel->find($strategy['created_by']);
            if ($createdBy && isset($createdBy['fname']) && isset($createdBy['lname'])) {
                $createdByName = $createdBy['fname'] . ' ' . $createdBy['lname'];
            } else {
                $createdByName = 'User ID: ' . $strategy['created_by'];
            }
        }

        $updatedByName = '';
        if (!empty($strategy['updated_by'])) {
            $updatedBy = $this->userModel->find($strategy['updated_by']);
            if ($updatedBy && isset($updatedBy['fname']) && isset($updatedBy['lname'])) {
                $updatedByName = $updatedBy['fname'] . ' ' . $updatedBy['lname'];
            } else {
                $updatedByName = 'User ID: ' . $strategy['updated_by'];
            }
        }

        $statusByName = '';
        if (!empty($strategy['strategies_status_by'])) {
            $statusBy = $this->userModel->find($strategy['strategies_status_by']);
            if ($statusBy && isset($statusBy['fname']) && isset($statusBy['lname'])) {
                $statusByName = $statusBy['fname'] . ' ' . $statusBy['lname'];
            } else {
                $statusByName = 'User ID: ' . $strategy['strategies_status_by'];
            }
        }

        $data = [
            'title' => 'Strategy Details',
            'strategy' => $strategy,
            'kra' => $kra,
            'investment' => $investment,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp,
            'createdByName' => $createdByName,
            'updatedByName' => $updatedByName,
            'statusByName' => $statusByName
        ];

        return view('admin/mtdp/mtdp_strategies_view', $data);
    }

    /**
     * Display the form to edit a Strategy
     *
     * @param int $id The Strategy ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function edit($id)
    {
        // Get the Strategy with related data
        $strategy = $this->mtdpStrategiesModel->find($id);

        if (!$strategy) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategy not found');
        }

        // Get the KRA
        $kra = $this->mtdpKraModel->find($strategy['kra_id']);

        if (!$kra) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'KRA not found');
        }

        // Get the Investment
        $investment = $this->mtdpInvestmentsModel->find($strategy['investment_id']);

        if (!$investment) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Investment not found');
        }

        // Get the Specific Area
        $specificArea = $this->mtdpSpecificAreaModel->find($strategy['sa_id']);

        if (!$specificArea) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Specific Area not found');
        }

        // Get the DIP
        $dip = $this->mtdpDipModel->find($strategy['dip_id']);

        if (!$dip) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Deliberate Intervention Program not found');
        }

        // Get the SPA
        $spa = $this->mtdpSpaModel->find($strategy['spa_id']);

        if (!$spa) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategic Priority Area not found');
        }

        // Get the MTDP plan
        $mtdp = $this->mtdpModel->find($strategy['mtdp_id']);

        if (!$mtdp) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        $data = [
            'title' => 'Edit Strategy',
            'strategy' => $strategy,
            'kra' => $kra,
            'investment' => $investment,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp
        ];

        return view('admin/mtdp/mtdp_strategies_edit', $data);
    }

    /**
     * Process request to update a Strategy
     *
     * @param int $id The Strategy ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function update($id)
    {
        // Get the Strategy
        $strategy = $this->mtdpStrategiesModel->find($id);

        if (!$strategy) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategy not found');
        }

        // Prepare data for database
        $data = [
            'id' => $id,
            'strategy' => $this->request->getPost('strategy'),
            'policy_reference' => $this->request->getPost('policy_reference'),
            'updated_by' => session()->get('user_id')
        ];

        // Update the data
        $result = $this->mtdpStrategiesModel->update($id, $data);

        if ($result) {
            return redirect()->to('admin/mtdp-plans/strategies/' . $id)
                ->with('success', 'Strategy updated successfully');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to update Strategy')
                ->withInput();
        }
    }

    /**
     * Toggle the status of a Strategy
     *
     * @param int $id The Strategy ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function toggleStatus($id)
    {
        // Get the Strategy
        $strategy = $this->mtdpStrategiesModel->find($id);

        if (!$strategy) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategy not found');
        }

        // Toggle the status
        $newStatus = ($strategy['strategies_status'] == 1) ? 0 : 1;

        // Prepare data for database
        $data = [
            'id' => $id,
            'strategies_status' => $newStatus,
            'strategies_status_by' => session()->get('user_id'),
            'strategies_status_at' => date('Y-m-d H:i:s'),
            'strategies_status_remarks' => $this->request->getPost('strategies_status_remarks')
        ];

        // Update the data
        $result = $this->mtdpStrategiesModel->update($id, $data);

        if ($result) {
            return redirect()->to('admin/mtdp-plans/kras/' . $strategy['kra_id'] . '/strategies')
                ->with('success', 'Strategy status updated successfully');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to update Strategy status')
                ->withInput();
        }
    }
}
