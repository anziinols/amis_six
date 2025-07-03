<?php
// app/Controllers/Admin/MTDPKRAsController.php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MtdpModel;
use App\Models\MtdpSpaModel;
use App\Models\MtdpDipModel;
use App\Models\MtdpSpecificAreaModel;
use App\Models\MtdpInvestmentsModel;
use App\Models\MtdpKraModel;
use App\Models\UserModel;

class MTDPKRAsController extends BaseController
{
    protected $mtdpModel;
    protected $mtdpSpaModel;
    protected $mtdpDipModel;
    protected $mtdpSpecificAreaModel;
    protected $mtdpInvestmentsModel;
    protected $mtdpKraModel;
    protected $userModel;

    public function __construct()
    {
        $this->mtdpModel = new MtdpModel();
        $this->mtdpSpaModel = new MtdpSpaModel();
        $this->mtdpDipModel = new MtdpDipModel();
        $this->mtdpSpecificAreaModel = new MtdpSpecificAreaModel();
        $this->mtdpInvestmentsModel = new MtdpInvestmentsModel();
        $this->mtdpKraModel = new MtdpKraModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display the list of KRAs for an Investment
     *
     * @param int $investmentId The Investment ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function index($investmentId)
    {
        // Get the Investment with related data
        $investment = $this->mtdpInvestmentsModel->find($investmentId);

        if (!$investment) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Investment not found');
        }

        // Get the Specific Area
        $specificArea = $this->mtdpSpecificAreaModel->find($investment['sa_id']);

        if (!$specificArea) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Specific Area not found');
        }

        // Get the DIP
        $dip = $this->mtdpDipModel->find($investment['dip_id']);

        if (!$dip) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Deliberate Intervention Program not found');
        }

        // Get the SPA
        $spa = $this->mtdpSpaModel->find($investment['spa_id']);

        if (!$spa) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategic Priority Area not found');
        }

        // Get the MTDP plan
        $mtdp = $this->mtdpModel->find($investment['mtdp_id']);

        if (!$mtdp) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        // Get all KRAs for this Investment
        $kras = $this->mtdpKraModel->where('investment_id', $investmentId)->findAll();

        $data = [
            'title' => 'KRAs for ' . $investment['investment'],
            'investment' => $investment,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp,
            'kras' => $kras
        ];

        return view('admin/mtdp/mtdp_kra_list', $data);
    }

    /**
     * Display the form to create a new KRA
     *
     * @param int $investmentId The Investment ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function new($investmentId)
    {
        // Get the Investment with related data
        $investment = $this->mtdpInvestmentsModel->find($investmentId);

        if (!$investment) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Investment not found');
        }

        // Get the Specific Area
        $specificArea = $this->mtdpSpecificAreaModel->find($investment['sa_id']);

        if (!$specificArea) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Specific Area not found');
        }

        // Get the DIP
        $dip = $this->mtdpDipModel->find($investment['dip_id']);

        if (!$dip) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Deliberate Intervention Program not found');
        }

        // Get the SPA
        $spa = $this->mtdpSpaModel->find($investment['spa_id']);

        if (!$spa) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategic Priority Area not found');
        }

        // Get the MTDP plan
        $mtdp = $this->mtdpModel->find($investment['mtdp_id']);

        if (!$mtdp) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        $data = [
            'title' => 'Create New KRA',
            'investment' => $investment,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp
        ];

        return view('admin/mtdp/mtdp_kra_create', $data);
    }

    /**
     * Process the creation of a new KRA
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function create()
    {
        $investmentId = $this->request->getPost('investment_id');
        
        // Get the Investment with related data
        $investment = $this->mtdpInvestmentsModel->find($investmentId);

        if (!$investment) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Investment not found');
        }

        // Prepare data for database
        $data = [
            'mtdp_id' => $investment['mtdp_id'],
            'spa_id' => $investment['spa_id'],
            'dip_id' => $investment['dip_id'],
            'sa_id' => $investment['sa_id'],
            'investment_id' => $investmentId,
            'kpi' => $this->request->getPost('kpi'),
            'year_one' => $this->request->getPost('year_one') ?? 0,
            'year_two' => $this->request->getPost('year_two') ?? 0,
            'year_three' => $this->request->getPost('year_three') ?? 0,
            'year_four' => $this->request->getPost('year_four') ?? 0,
            'year_five' => $this->request->getPost('year_five') ?? 0,
            'responsible_agencies' => $this->request->getPost('responsible_agencies'),
            'kra_status' => 1,
            'kra_status_by' => session()->get('user_id'),
            'kra_status_at' => date('Y-m-d H:i:s'),
            'created_by' => session()->get('user_id')
        ];

        if ($this->mtdpKraModel->createKra($data)) {
            return redirect()->to('admin/mtdp-plans/investments/' . $investmentId . '/kras')
                ->with('success', 'KRA created successfully');
        } else {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create KRA');
        }
    }

    /**
     * Display a single KRA
     *
     * @param int $id The KRA ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function show($id)
    {
        // Get the KRA
        $kra = $this->mtdpKraModel->find($id);

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

        // Get user names for created_by, updated_by, and status_by
        $createdByName = '';
        if (!empty($kra['created_by'])) {
            $createdBy = $this->userModel->find($kra['created_by']);
            if ($createdBy && isset($createdBy['fname']) && isset($createdBy['lname'])) {
                $createdByName = $createdBy['fname'] . ' ' . $createdBy['lname'];
            } else {
                $createdByName = 'User ID: ' . $kra['created_by'];
            }
        }

        $updatedByName = '';
        if (!empty($kra['updated_by'])) {
            $updatedBy = $this->userModel->find($kra['updated_by']);
            if ($updatedBy && isset($updatedBy['fname']) && isset($updatedBy['lname'])) {
                $updatedByName = $updatedBy['fname'] . ' ' . $updatedBy['lname'];
            } else {
                $updatedByName = 'User ID: ' . $kra['updated_by'];
            }
        }

        $statusByName = '';
        if (!empty($kra['kra_status_by'])) {
            $statusBy = $this->userModel->find($kra['kra_status_by']);
            if ($statusBy && isset($statusBy['fname']) && isset($statusBy['lname'])) {
                $statusByName = $statusBy['fname'] . ' ' . $statusBy['lname'];
            } else {
                $statusByName = 'User ID: ' . $kra['kra_status_by'];
            }
        }

        $data = [
            'title' => 'KRA Details',
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

        return view('admin/mtdp/mtdp_kra_view', $data);
    }

    /**
     * Display the form to edit a KRA
     *
     * @param int $id The KRA ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function edit($id)
    {
        // Get the KRA
        $kra = $this->mtdpKraModel->find($id);

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
            'title' => 'Edit KRA',
            'kra' => $kra,
            'investment' => $investment,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp
        ];

        return view('admin/mtdp/mtdp_kra_edit', $data);
    }

    /**
     * Process the update of a KRA
     *
     * @param int $id The KRA ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function update($id)
    {
        // Get the KRA
        $kra = $this->mtdpKraModel->find($id);

        if (!$kra) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'KRA not found');
        }

        // Prepare data for database
        $data = [
            'id' => $id,
            'kpi' => $this->request->getPost('kpi'),
            'year_one' => $this->request->getPost('year_one') ?? 0,
            'year_two' => $this->request->getPost('year_two') ?? 0,
            'year_three' => $this->request->getPost('year_three') ?? 0,
            'year_four' => $this->request->getPost('year_four') ?? 0,
            'year_five' => $this->request->getPost('year_five') ?? 0,
            'responsible_agencies' => $this->request->getPost('responsible_agencies'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->mtdpKraModel->save($data)) {
            return redirect()->to('admin/mtdp-plans/investments/' . $kra['investment_id'] . '/kras')
                ->with('success', 'KRA updated successfully');
        } else {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to update KRA');
        }
    }

    /**
     * Toggle the status of a KRA
     *
     * @param int $id The KRA ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function toggleStatus($id)
    {
        // Get the KRA
        $kra = $this->mtdpKraModel->find($id);

        if (!$kra) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'KRA not found');
        }

        $statusData = [
            'kra_status_by' => session()->get('user_id'),
            'kra_status_remarks' => $this->request->getPost('kra_status_remarks')
        ];

        if ($this->mtdpKraModel->toggleStatus($id, $statusData)) {
            return redirect()->to('admin/mtdp-plans/investments/' . $kra['investment_id'] . '/kras')
                ->with('success', 'KRA status updated successfully');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to update KRA status');
        }
    }
}
