<?php
// app/Controllers/Admin/MTDPInvestmentsController.php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MtdpModel;
use App\Models\MtdpSpaModel;
use App\Models\MtdpDipModel;
use App\Models\MtdpSpecificAreaModel;
use App\Models\MtdpInvestmentsModel;
use App\Models\UserModel;

class MTDPInvestmentsController extends BaseController
{
    protected $mtdpModel;
    protected $mtdpSpaModel;
    protected $mtdpDipModel;
    protected $mtdpSpecificAreaModel;
    protected $mtdpInvestmentsModel;
    protected $userModel;

    public function __construct()
    {
        $this->mtdpModel = new MtdpModel();
        $this->mtdpSpaModel = new MtdpSpaModel();
        $this->mtdpDipModel = new MtdpDipModel();
        $this->mtdpSpecificAreaModel = new MtdpSpecificAreaModel();
        $this->mtdpInvestmentsModel = new MtdpInvestmentsModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display the list of Investments for a Specific Area
     *
     * @param int $dipId The DIP ID
     * @param int $saId The Specific Area ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function index($dipId, $saId)
    {
        // Get the Specific Area with related data
        $specificArea = $this->mtdpSpecificAreaModel->find($saId);

        if (!$specificArea) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Specific Area not found');
        }

        // Get the DIP
        $dip = $this->mtdpDipModel->find($dipId);

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

        // Get all Investments for this Specific Area
        $investments = $this->mtdpInvestmentsModel->where('sa_id', $saId)->findAll();

        $data = [
            'title' => 'Investments for ' . $specificArea['sa_title'],
            'specificArea' => $specificArea,
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp,
            'investments' => $investments
        ];

        return view('admin/mtdp/mtdp_investments_list', $data);
    }

    /**
     * Display the form to create a new Investment
     *
     * @param int $dipId The DIP ID
     * @param int $saId The Specific Area ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function new($dipId, $saId)
    {
        // Get the Specific Area with related data
        $specificArea = $this->mtdpSpecificAreaModel->find($saId);

        if (!$specificArea) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Specific Area not found');
        }

        // Get the DIP
        $dip = $this->mtdpDipModel->find($dipId);

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

        // Get all active DIPs for the dropdown, filtered by the current MTDP
        $allDips = $this->mtdpDipModel
            ->where('mtdp_id', $mtdp['id'])
            ->where('dip_status', 1)
            ->where('deleted_at IS NULL')
            ->findAll();

        $data = [
            'title' => 'Create New Investment',
            'specificArea' => $specificArea,
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp,
            'allDips' => $allDips
        ];

        return view('admin/mtdp/mtdp_investments_create', $data);
    }

    /**
     * Process request to create a new Investment
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function create()
    {
        $dipId = $this->request->getPost('dip_id');
        $saId = $this->request->getPost('sa_id');

        // Get Specific Area details to get mtdp_id and spa_id
        $specificArea = $this->mtdpSpecificAreaModel->find($saId);

        if (!$specificArea) {
            return redirect()->back()->with('error', 'Specific Area not found');
        }

        // Prepare data for database
        $data = [
            'mtdp_id' => $specificArea['mtdp_id'],
            'spa_id' => $specificArea['spa_id'],
            'dip_id' => $dipId,
            'sa_id' => $saId,
            'dip_link_dip_id' => $this->request->getPost('dip_link_dip_id') ?? 0,
            'investment' => $this->request->getPost('investment'),
            'year_one' => $this->request->getPost('year_one') ?? 0,
            'year_two' => $this->request->getPost('year_two') ?? 0,
            'year_three' => $this->request->getPost('year_three') ?? 0,
            'year_four' => $this->request->getPost('year_four') ?? 0,
            'year_five' => $this->request->getPost('year_five') ?? 0,
            'funding_sources' => $this->request->getPost('funding_sources'),
            'investment_status' => 1,
            'investment_status_by' => session()->get('user_id'),
            'investment_status_at' => date('Y-m-d H:i:s'),
            'created_by' => session()->get('user_id')
        ];

        if ($this->mtdpInvestmentsModel->createInvestment($data)) {
            return redirect()->to('admin/mtdp-plans/dips/' . $dipId . '/specific-areas/' . $saId . '/investments')
                ->with('success', 'Investment created successfully');
        } else {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create Investment');
        }
    }

    /**
     * Display a specific Investment
     *
     * @param int $id The Investment ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function show($id)
    {
        // Get the Investment with related data
        $investment = $this->mtdpInvestmentsModel->getInvestments($id);

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

        // Get all active DIPs for the linked DIP display, filtered by the current MTDP
        $allDips = $this->mtdpDipModel
            ->where('mtdp_id', $mtdp['id'])
            ->where('dip_status', 1)
            ->where('deleted_at IS NULL')
            ->findAll();

        // Get user names for audit fields
        $createdByName = '';
        if (!empty($investment['created_by'])) {
            $createdBy = $this->userModel->find($investment['created_by']);
            if ($createdBy && isset($createdBy['fname']) && isset($createdBy['lname'])) {
                $createdByName = $createdBy['fname'] . ' ' . $createdBy['lname'];
            } else {
                $createdByName = 'User ID: ' . $investment['created_by'];
            }
        }

        $updatedByName = '';
        if (!empty($investment['updated_by'])) {
            $updatedBy = $this->userModel->find($investment['updated_by']);
            if ($updatedBy && isset($updatedBy['fname']) && isset($updatedBy['lname'])) {
                $updatedByName = $updatedBy['fname'] . ' ' . $updatedBy['lname'];
            } else {
                $updatedByName = 'User ID: ' . $investment['updated_by'];
            }
        }

        $statusByName = '';
        if (!empty($investment['investment_status_by'])) {
            $statusBy = $this->userModel->find($investment['investment_status_by']);
            if ($statusBy && isset($statusBy['fname']) && isset($statusBy['lname'])) {
                $statusByName = $statusBy['fname'] . ' ' . $statusBy['lname'];
            } else {
                $statusByName = 'User ID: ' . $investment['investment_status_by'];
            }
        }

        $data = [
            'title' => 'Investment Details',
            'investment' => $investment,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp,
            'allDips' => $allDips,
            'createdByName' => $createdByName,
            'updatedByName' => $updatedByName,
            'statusByName' => $statusByName
        ];

        return view('admin/mtdp/mtdp_investments_view', $data);
    }

    /**
     * Display the form to edit an Investment
     *
     * @param int $id The Investment ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function edit($id)
    {
        // Get the Investment with related data
        $investment = $this->mtdpInvestmentsModel->getInvestments($id);

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

        // Get all active DIPs for the dropdown, filtered by the current MTDP
        $allDips = $this->mtdpDipModel
            ->where('mtdp_id', $mtdp['id'])
            ->where('dip_status', 1)
            ->where('deleted_at IS NULL')
            ->findAll();

        $data = [
            'title' => 'Edit Investment',
            'investment' => $investment,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp,
            'allDips' => $allDips
        ];

        return view('admin/mtdp/mtdp_investments_edit', $data);
    }

    /**
     * Process request to update an Investment
     *
     * @param int $id The Investment ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function update($id)
    {
        // Get the Investment
        $investment = $this->mtdpInvestmentsModel->find($id);

        if (!$investment) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Investment not found');
        }

        // Prepare data for database
        $data = [
            'investment' => $this->request->getPost('investment'),
            'year_one' => $this->request->getPost('year_one') ?? 0,
            'year_two' => $this->request->getPost('year_two') ?? 0,
            'year_three' => $this->request->getPost('year_three') ?? 0,
            'year_four' => $this->request->getPost('year_four') ?? 0,
            'year_five' => $this->request->getPost('year_five') ?? 0,
            'funding_sources' => $this->request->getPost('funding_sources'),
            'dip_link_dip_id' => $this->request->getPost('dip_link_dip_id') ?? 0,
            'updated_by' => session()->get('user_id')
        ];

        if ($this->mtdpInvestmentsModel->updateInvestment($id, $data)) {
            return redirect()->to('admin/mtdp-plans/dips/' . $investment['dip_id'] . '/specific-areas/' . $investment['sa_id'] . '/investments/' . $id)
                ->with('success', 'Investment updated successfully');
        } else {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to update Investment');
        }
    }

    /**
     * Process request to toggle the status of an Investment
     *
     * @param int $id The Investment ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function toggleStatus($id)
    {
        // Get the Investment
        $investment = $this->mtdpInvestmentsModel->find($id);

        if (!$investment) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Investment not found'
                ]);
            }
            return redirect()->to('admin/mtdp-plans')->with('error', 'Investment not found');
        }

        $statusData = [
            'investment_status_by' => session()->get('user_id'),
            'investment_status_remarks' => $this->request->getPost('investment_status_remarks')
        ];

        if ($this->mtdpInvestmentsModel->toggleStatus($id, $statusData)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Investment status updated successfully'
                ]);
            }
            return redirect()->to('admin/mtdp-plans/dips/' . $investment['dip_id'] . '/specific-areas/' . $investment['sa_id'] . '/investments')
                ->with('success', 'Investment status updated successfully');
        } else {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Failed to update Investment status'
                ]);
            }
            return redirect()->back()
                ->with('error', 'Failed to update Investment status');
        }
    }

    /**
     * Download CSV template for Investment import
     *
     * @param int $dipId The DIP ID
     * @param int $saId The Specific Area ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function downloadInvestmentTemplate($dipId, $saId)
    {
        // Get the specific area with related data
        $specificArea = $this->mtdpSpecificAreaModel->getSpecificAreas($saId);

        if (!$specificArea) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Specific Area not found');
        }

        // Create CSV content
        $csvContent = "investment,policy_reference\n";
        $csvContent .= "\"Agricultural Training Centers\",\"National Agricultural Development Policy 2023\"\n";
        $csvContent .= "\"Irrigation Infrastructure\",\"Water Resource Management Strategy 2023\"\n";

        // Set headers for file download
        $filename = 'investments_import_template_sa_' . $saId . '_' . date('Y-m-d') . '.csv';

        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csvContent);
    }

    /**
     * Import Investments from CSV file
     *
     * @param int $dipId The DIP ID
     * @param int $saId The Specific Area ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function importInvestments($dipId, $saId)
    {
        // Get the specific area with related data
        $specificArea = $this->mtdpSpecificAreaModel->getSpecificAreas($saId);

        if (!$specificArea) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Specific Area not found');
        }

        // Validate file upload
        $file = $this->request->getFile('csv_file');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Please select a valid CSV file');
        }

        if ($file->getExtension() !== 'csv') {
            return redirect()->back()->with('error', 'Please upload a CSV file');
        }

        $csvData = array_map('str_getcsv', file($file->getTempName()));
        $header = array_shift($csvData);

        // Validate header
        if (!in_array('investment', $header)) {
            return redirect()->back()->with('error', 'CSV must contain "investment" column');
        }

        $investmentIndex = array_search('investment', $header);
        $policyRefIndex = array_search('policy_reference', $header);

        $imported = 0;
        $errors = [];

        foreach ($csvData as $rowIndex => $row) {
            if (empty($row[$investmentIndex])) {
                $errors[] = "Row " . ($rowIndex + 2) . ": Investment is required";
                continue;
            }

            $data = [
                'mtdp_id' => $specificArea['mtdp_id'],
                'spa_id' => $specificArea['spa_id'],
                'dip_id' => $dipId,
                'sa_id' => $saId,
                'investment' => $row[$investmentIndex],
                'policy_reference' => $policyRefIndex !== false ? ($row[$policyRefIndex] ?? '') : '',
                'investment_status' => 1,
                'investment_status_by' => session()->get('user_id'),
                'investment_status_at' => date('Y-m-d H:i:s'),
                'investment_status_remarks' => '',
                'created_by' => session()->get('user_id'),
                'updated_by' => session()->get('user_id'),
            ];

            if ($this->mtdpInvestmentsModel->insert($data)) {
                $imported++;
            } else {
                $errors[] = "Row " . ($rowIndex + 2) . ": Failed to import " . $row[$investmentIndex];
            }
        }

        $message = "Import completed. $imported investments imported successfully.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', $errors);
        }

        return redirect()->to('admin/mtdp-plans/dips/' . $dipId . '/specific-areas/' . $saId . '/investments')->with('success', $message);
    }
}
