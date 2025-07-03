<?php
// app/Controllers/Admin/MTDPSpecificAreasController.php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MtdpModel;
use App\Models\MtdpSpaModel;
use App\Models\MtdpDipModel;
use App\Models\MtdpSpecificAreaModel;
use App\Models\UserModel;

class MTDPSpecificAreasController extends BaseController
{
    protected $mtdpModel;
    protected $mtdpSpaModel;
    protected $mtdpDipModel;
    protected $mtdpSpecificAreaModel;
    protected $userModel;

    public function __construct()
    {
        $this->mtdpModel = new MtdpModel();
        $this->mtdpSpaModel = new MtdpSpaModel();
        $this->mtdpDipModel = new MtdpDipModel();
        $this->mtdpSpecificAreaModel = new MtdpSpecificAreaModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display the list of Specific Areas for a DIP
     *
     * @param int $dipId The DIP ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function index($dipId)
    {
        // Get the DIP with related data
        $dip = $this->mtdpDipModel->getDips($dipId);

        if (!$dip) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Deliberate Intervention Program not found');
        }

        // Get the SPA
        $spa = $this->mtdpSpaModel->find($dip['spa_id']);

        if (!$spa) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategic Priority Area not found');
        }

        // Get the MTDP plan
        $mtdp = $this->mtdpModel->find($dip['mtdp_id']);

        if (!$mtdp) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        // Get all Specific Areas for this DIP
        $specificAreas = $this->mtdpSpecificAreaModel->where('dip_id', $dipId)->findAll();

        $data = [
            'title' => 'Specific Areas for ' . $dip['dip_title'],
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp,
            'specificAreas' => $specificAreas
        ];

        return view('admin/mtdp/mtdp_sa_list', $data);
    }

    /**
     * Display the form to create a new Specific Area
     *
     * @param int $dipId The DIP ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function new($dipId)
    {
        // Get the DIP with related data
        $dip = $this->mtdpDipModel->getDips($dipId);

        if (!$dip) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Deliberate Intervention Program not found');
        }

        // Get the SPA
        $spa = $this->mtdpSpaModel->find($dip['spa_id']);

        if (!$spa) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategic Priority Area not found');
        }

        // Get the MTDP plan
        $mtdp = $this->mtdpModel->find($dip['mtdp_id']);

        if (!$mtdp) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        $data = [
            'title' => 'Create New Specific Area',
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp
        ];

        return view('admin/mtdp/mtdp_sa_create', $data);
    }

    /**
     * Process request to create a new Specific Area
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function create()
    {
        $dipId = $this->request->getPost('dip_id');

        // Get DIP details to get mtdp_id and spa_id
        $dip = $this->mtdpDipModel->find($dipId);

        if (!$dip) {
            return redirect()->back()->with('error', 'Deliberate Intervention Program not found');
        }

        // Prepare data for database
        $data = [
            'mtdp_id' => $dip['mtdp_id'],
            'spa_id' => $dip['spa_id'],
            'dip_id' => $dipId,
            'sa_code' => $this->request->getPost('sa_code'),
            'sa_title' => $this->request->getPost('sa_title'),
            'sa_remarks' => $this->request->getPost('sa_remarks'),
            'sa_status' => 1,
            'sa_status_by' => session()->get('user_id'),
            'sa_status_at' => date('Y-m-d H:i:s'),
            'created_by' => session()->get('user_id')
        ];

        if ($this->mtdpSpecificAreaModel->createSpecificArea($data)) {
            return redirect()->to('admin/mtdp-plans/dips/' . $dipId . '/specific-areas')
                ->with('success', 'Specific Area created successfully');
        } else {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create Specific Area');
        }
    }

    /**
     * Display a specific Specific Area
     *
     * @param int $id The Specific Area ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function show($id)
    {
        // Get the Specific Area with related data
        $specificArea = $this->mtdpSpecificAreaModel->getSpecificAreas($id);

        if (!$specificArea) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Specific Area not found');
        }

        // Get the DIP
        $dip = $this->mtdpDipModel->find($specificArea['dip_id']);

        if (!$dip) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Deliberate Intervention Program not found');
        }

        // Get the SPA
        $spa = $this->mtdpSpaModel->find($specificArea['spa_id']);

        if (!$spa) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategic Priority Area not found');
        }

        // Get the MTDP plan
        $mtdp = $this->mtdpModel->find($specificArea['mtdp_id']);

        if (!$mtdp) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        // Get user information for created_by, updated_by, and sa_status_by
        $createdByUser = $this->userModel->find($specificArea['created_by']);
        $updatedByUser = !empty($specificArea['updated_by']) ? $this->userModel->find($specificArea['updated_by']) : null;
        $statusByUser = $this->userModel->find($specificArea['sa_status_by']);

        // Format user names
        $createdByName = $createdByUser ? ($createdByUser['fname'] . ' ' . $createdByUser['lname']) : 'Unknown';
        $updatedByName = $updatedByUser ? ($updatedByUser['fname'] . ' ' . $updatedByUser['lname']) : 'Unknown';
        $statusByName = $statusByUser ? ($statusByUser['fname'] . ' ' . $statusByUser['lname']) : 'Unknown';

        $data = [
            'title' => 'Specific Area Details: ' . $specificArea['sa_code'],
            'specificArea' => $specificArea,
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp,
            'createdByName' => $createdByName,
            'updatedByName' => $updatedByName,
            'statusByName' => $statusByName
        ];

        return view('admin/mtdp/mtdp_sa_view', $data);
    }

    /**
     * Display the form to edit a Specific Area
     *
     * @param int $id The Specific Area ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function edit($id)
    {
        // Get the Specific Area with related data
        $specificArea = $this->mtdpSpecificAreaModel->getSpecificAreas($id);

        if (!$specificArea) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Specific Area not found');
        }

        // Get the DIP
        $dip = $this->mtdpDipModel->find($specificArea['dip_id']);

        if (!$dip) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Deliberate Intervention Program not found');
        }

        // Get the SPA
        $spa = $this->mtdpSpaModel->find($specificArea['spa_id']);

        if (!$spa) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategic Priority Area not found');
        }

        // Get the MTDP plan
        $mtdp = $this->mtdpModel->find($specificArea['mtdp_id']);

        if (!$mtdp) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        $data = [
            'title' => 'Edit Specific Area: ' . $specificArea['sa_code'],
            'specificArea' => $specificArea,
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp
        ];

        return view('admin/mtdp/mtdp_sa_edit', $data);
    }

    /**
     * Process request to update a Specific Area
     *
     * @param int $id The Specific Area ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function update($id)
    {
        // Get the Specific Area
        $specificArea = $this->mtdpSpecificAreaModel->find($id);

        if (!$specificArea) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Specific Area not found');
        }

        // Prepare data for database
        $data = [
            'sa_code' => $this->request->getPost('sa_code'),
            'sa_title' => $this->request->getPost('sa_title'),
            'sa_remarks' => $this->request->getPost('sa_remarks'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->mtdpSpecificAreaModel->updateSpecificArea($id, $data)) {
            return redirect()->to('admin/mtdp-plans/dips/' . $specificArea['dip_id'] . '/specific-areas/' . $id)
                ->with('success', 'Specific Area updated successfully');
        } else {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to update Specific Area');
        }
    }

    /**
     * Process request to toggle the status of a Specific Area
     *
     * @param int $id The Specific Area ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function toggleStatus($id)
    {
        // Get the Specific Area
        $specificArea = $this->mtdpSpecificAreaModel->find($id);

        if (!$specificArea) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Specific Area not found'
                ]);
            }
            return redirect()->to('admin/mtdp-plans')->with('error', 'Specific Area not found');
        }

        $statusData = [
            'sa_status_by' => session()->get('user_id'),
            'sa_status_remarks' => $this->request->getPost('sa_status_remarks')
        ];

        if ($this->mtdpSpecificAreaModel->toggleStatus($id, $statusData)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Specific Area status updated successfully'
                ]);
            }
            return redirect()->to('admin/mtdp-plans/dips/' . $specificArea['dip_id'] . '/specific-areas')
                ->with('success', 'Specific Area status updated successfully');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update Specific Area status'
                ]);
            }
            return redirect()->back()
                ->with('error', 'Failed to update Specific Area status');
        }
    }
}
