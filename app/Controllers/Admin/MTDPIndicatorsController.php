<?php
// app/Controllers/Admin/MTDPIndicatorsController.php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MtdpModel;
use App\Models\MtdpSpaModel;
use App\Models\MtdpDipModel;
use App\Models\MtdpSpecificAreaModel;
use App\Models\MtdpInvestmentsModel;
use App\Models\MtdpKraModel;
use App\Models\MtdpStrategiesModel;
use App\Models\MtdpIndicatorsModel;
use App\Models\UserModel;

class MTDPIndicatorsController extends BaseController
{
    protected $mtdpModel;
    protected $mtdpSpaModel;
    protected $mtdpDipModel;
    protected $mtdpSpecificAreaModel;
    protected $mtdpInvestmentsModel;
    protected $mtdpKraModel;
    protected $mtdpStrategiesModel;
    protected $mtdpIndicatorsModel;
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
        $this->mtdpIndicatorsModel = new MtdpIndicatorsModel();
        $this->userModel = new UserModel();
    }

    /**
     * Display the list of Indicators for a Strategy
     *
     * @param int $strategyId The Strategy ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function index($strategyId)
    {
        // Get the Strategy with related data
        $strategy = $this->mtdpStrategiesModel->find($strategyId);

        if (!$strategy) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategy not found');
        }

        // Get the KRA
        $kra = $this->mtdpKraModel->find($strategy['kra_id']);

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

        // Get all Indicators for this Strategy
        $indicators = $this->mtdpIndicatorsModel->where('strategies_id', $strategyId)->findAll();

        $data = [
            'title' => 'Indicators for Strategy',
            'strategy' => $strategy,
            'kra' => $kra,
            'investment' => $investment,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp,
            'indicators' => $indicators
        ];

        return view('admin/mtdp/mtdp_indicators_list', $data);
    }

    /**
     * Display the form to create a new Indicator
     *
     * @param int $strategyId The Strategy ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function new($strategyId)
    {
        // Get the Strategy with related data
        $strategy = $this->mtdpStrategiesModel->find($strategyId);

        if (!$strategy) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategy not found');
        }

        // Get the KRA
        $kra = $this->mtdpKraModel->find($strategy['kra_id']);

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
            'title' => 'Create New Indicator',
            'strategy' => $strategy,
            'kra' => $kra,
            'investment' => $investment,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp
        ];

        return view('admin/mtdp/mtdp_indicators_create', $data);
    }

    /**
     * Process request to create a new Indicator
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function create()
    {
        // Log all POST data for debugging
        log_message('debug', 'POST data: ' . print_r($this->request->getPost(), true));

        // Get the Strategy ID from the form
        $strategyId = $this->request->getPost('strategies_id');

        // Log the strategy ID
        log_message('debug', 'Strategy ID: ' . $strategyId);

        // Get the Strategy
        $strategy = $this->mtdpStrategiesModel->find($strategyId);

        // Log the strategy data
        log_message('debug', 'Strategy data: ' . print_r($strategy, true));

        if (!$strategy) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategy not found');
        }

        // Prepare data for database
        $data = [
            'mtdp_id' => $strategy['mtdp_id'],
            'spa_id' => $strategy['spa_id'],
            'dip_id' => $strategy['dip_id'],
            'sa_id' => $strategy['sa_id'],
            'investment_id' => $strategy['investment_id'],
            'kra_id' => $strategy['kra_id'],
            'strategies_id' => $strategyId,
            'indicator' => $this->request->getPost('indicator'),
            'source' => $this->request->getPost('source'),
            'baseline' => $this->request->getPost('baseline'),
            'year_one' => $this->request->getPost('year_one'),
            'year_two' => $this->request->getPost('year_two'),
            'year_three' => $this->request->getPost('year_three'),
            'year_four' => $this->request->getPost('year_four'),
            'year_five' => $this->request->getPost('year_five'),
            'indicators_status' => 1, // Default to active
            'indicators_status_by' => session()->get('user_id'),
            'created_by' => session()->get('user_id')
        ];

        // Log the data for debugging
        log_message('debug', 'Indicator data to be inserted: ' . print_r($data, true));

        // Insert the data
        $result = $this->mtdpIndicatorsModel->createIndicator($data);

        // Log the result and any errors
        if (!$result) {
            log_message('error', 'Failed to create indicator. Errors: ' . print_r($this->mtdpIndicatorsModel->errors(), true));
        }

        if ($result) {
            return redirect()->to('admin/mtdp-plans/strategies/' . $strategyId . '/indicators')
                ->with('success', 'Indicator created successfully');
        } else {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create indicator. Please try again.');
        }
    }

    /**
     * Display the details of a specific Indicator
     *
     * @param int $id The Indicator ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function show($id)
    {
        // Log the indicator ID
        log_message('debug', 'MTDPIndicatorsController::show - Indicator ID: ' . $id);

        // Get the Indicator
        $indicator = $this->mtdpIndicatorsModel->find($id);

        // Log the indicator data
        log_message('debug', 'MTDPIndicatorsController::show - Indicator data: ' . print_r($indicator, true));

        if (!$indicator) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Indicator not found');
        }

        // Get the Strategy
        $strategy = $this->mtdpStrategiesModel->find($indicator['strategies_id']);

        if (!$strategy) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategy not found');
        }

        // Get the KRA
        $kra = $this->mtdpKraModel->find($indicator['kra_id']);

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

        // Log the SPA data
        log_message('debug', 'MTDPIndicatorsController::show - SPA data: ' . print_r($spa, true));

        if (!$spa) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategic Priority Area not found');
        }

        // Get the MTDP plan
        $mtdp = $this->mtdpModel->find($kra['mtdp_id']);

        if (!$mtdp) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'MTDP Plan not found');
        }

        // Get user names for created/updated/status
        $createdByName = '';
        if (!empty($indicator['created_by'])) {
            $createdBy = $this->userModel->find($indicator['created_by']);
            if ($createdBy && isset($createdBy['fname']) && isset($createdBy['lname'])) {
                $createdByName = $createdBy['fname'] . ' ' . $createdBy['lname'];
            } else {
                $createdByName = 'User ID: ' . $indicator['created_by'];
            }
        }

        $updatedByName = '';
        if (!empty($indicator['updated_by'])) {
            $updatedBy = $this->userModel->find($indicator['updated_by']);
            if ($updatedBy && isset($updatedBy['fname']) && isset($updatedBy['lname'])) {
                $updatedByName = $updatedBy['fname'] . ' ' . $updatedBy['lname'];
            } else {
                $updatedByName = 'User ID: ' . $indicator['updated_by'];
            }
        }

        $statusByName = '';
        if (!empty($indicator['indicators_status_by'])) {
            $statusBy = $this->userModel->find($indicator['indicators_status_by']);
            if ($statusBy && isset($statusBy['fname']) && isset($statusBy['lname'])) {
                $statusByName = $statusBy['fname'] . ' ' . $statusBy['lname'];
            } else {
                $statusByName = 'User ID: ' . $indicator['indicators_status_by'];
            }
        }

        $data = [
            'title' => 'Indicator Details',
            'indicator' => $indicator,
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

        // Log the data being passed to the view
        log_message('debug', 'MTDPIndicatorsController::show - Data passed to view: ' . print_r($data, true));

        return view('admin/mtdp/mtdp_indicators_view', $data);
    }

    /**
     * Display the form to edit an Indicator
     *
     * @param int $id The Indicator ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function edit($id)
    {
        // Get the Indicator
        $indicator = $this->mtdpIndicatorsModel->find($id);

        if (!$indicator) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Indicator not found');
        }

        // Get the Strategy
        $strategy = $this->mtdpStrategiesModel->find($indicator['strategies_id']);

        if (!$strategy) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategy not found');
        }

        // Get the KRA
        $kra = $this->mtdpKraModel->find($indicator['kra_id']);

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
            'title' => 'Edit Indicator',
            'indicator' => $indicator,
            'strategy' => $strategy,
            'kra' => $kra,
            'investment' => $investment,
            'specificArea' => $specificArea,
            'dip' => $dip,
            'spa' => $spa,
            'mtdp' => $mtdp
        ];

        return view('admin/mtdp/mtdp_indicators_edit', $data);
    }

    /**
     * Process request to update an Indicator
     *
     * @param int $id The Indicator ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function update($id)
    {
        // Get the Indicator
        $indicator = $this->mtdpIndicatorsModel->find($id);

        if (!$indicator) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Indicator not found');
        }

        // Prepare data for database
        $data = [
            'id' => $id,
            'indicator' => $this->request->getPost('indicator'),
            'source' => $this->request->getPost('source'),
            'baseline' => $this->request->getPost('baseline'),
            'year_one' => $this->request->getPost('year_one'),
            'year_two' => $this->request->getPost('year_two'),
            'year_three' => $this->request->getPost('year_three'),
            'year_four' => $this->request->getPost('year_four'),
            'year_five' => $this->request->getPost('year_five'),
            'updated_by' => session()->get('user_id')
        ];

        // Update the data
        $result = $this->mtdpIndicatorsModel->updateIndicator($id, $data);

        if ($result) {
            return redirect()->to('admin/mtdp-plans/indicators/' . $id)
                ->with('success', 'Indicator updated successfully');
        } else {
            return redirect()->back()->withInput()
                ->with('error', 'Failed to update indicator. Please try again.');
        }
    }

    /**
     * Process request to toggle the status of an Indicator
     *
     * @param int $id The Indicator ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function toggleStatus($id)
    {
        // Get the Indicator
        $indicator = $this->mtdpIndicatorsModel->find($id);

        if (!$indicator) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Indicator not found');
        }

        // Prepare status data
        $statusData = [
            'indicators_status_by' => session()->get('user_id'),
            'indicators_status_remarks' => $this->request->getPost('indicators_status_remarks')
        ];

        // Toggle the status
        $result = $this->mtdpIndicatorsModel->toggleStatus($id, $statusData);

        if ($result) {
            return redirect()->to('admin/mtdp-plans/strategies/' . $indicator['strategies_id'] . '/indicators')
                ->with('success', 'Indicator status updated successfully');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to update indicator status. Please try again.');
        }
    }

    /**
     * Download CSV template for Indicator import
     *
     * @param int $strategyId The Strategy ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function downloadIndicatorTemplate($strategyId)
    {
        // Get the strategy with related data
        $strategy = $this->mtdpStrategiesModel->getStrategies($strategyId);

        if (!$strategy) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategy not found');
        }

        // Create CSV content
        $csvContent = "indicator,source,baseline,year_one,year_two,year_three,year_four,year_five\n";
        $csvContent .= "\"Number of farmers trained\",\"Training records\",\"0\",\"100\",\"150\",\"200\",\"250\",\"300\"\n";
        $csvContent .= "\"Hectares of land improved\",\"Field surveys\",\"0\",\"50\",\"75\",\"100\",\"125\",\"150\"\n";

        // Set headers for file download
        $filename = 'indicators_import_template_strategy_' . $strategyId . '_' . date('Y-m-d') . '.csv';

        return $this->response
            ->setHeader('Content-Type', 'text/csv')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csvContent);
    }

    /**
     * Import Indicators from CSV file
     *
     * @param int $strategyId The Strategy ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function importIndicators($strategyId)
    {
        // Get the strategy with related data
        $strategy = $this->mtdpStrategiesModel->getStrategies($strategyId);

        if (!$strategy) {
            return redirect()->to('admin/mtdp-plans')->with('error', 'Strategy not found');
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
        if (!in_array('indicator', $header)) {
            return redirect()->back()->with('error', 'CSV must contain "indicator" column');
        }

        $indicatorIndex = array_search('indicator', $header);
        $sourceIndex = array_search('source', $header);
        $baselineIndex = array_search('baseline', $header);
        $yearOneIndex = array_search('year_one', $header);
        $yearTwoIndex = array_search('year_two', $header);
        $yearThreeIndex = array_search('year_three', $header);
        $yearFourIndex = array_search('year_four', $header);
        $yearFiveIndex = array_search('year_five', $header);

        $imported = 0;
        $errors = [];

        foreach ($csvData as $rowIndex => $row) {
            if (empty($row[$indicatorIndex])) {
                $errors[] = "Row " . ($rowIndex + 2) . ": Indicator is required";
                continue;
            }

            $data = [
                'mtdp_id' => $strategy['mtdp_id'],
                'spa_id' => $strategy['spa_id'],
                'dip_id' => $strategy['dip_id'],
                'sa_id' => $strategy['sa_id'],
                'investment_id' => $strategy['investment_id'],
                'kra_id' => $strategy['kra_id'],
                'strategies_id' => $strategyId,
                'indicator' => $row[$indicatorIndex],
                'source' => $sourceIndex !== false ? ($row[$sourceIndex] ?? '') : '',
                'baseline' => $baselineIndex !== false ? ($row[$baselineIndex] ?? '') : '',
                'year_one' => $yearOneIndex !== false ? ($row[$yearOneIndex] ?? '') : '',
                'year_two' => $yearTwoIndex !== false ? ($row[$yearTwoIndex] ?? '') : '',
                'year_three' => $yearThreeIndex !== false ? ($row[$yearThreeIndex] ?? '') : '',
                'year_four' => $yearFourIndex !== false ? ($row[$yearFourIndex] ?? '') : '',
                'year_five' => $yearFiveIndex !== false ? ($row[$yearFiveIndex] ?? '') : '',
                'indicators_status' => 1,
                'indicators_status_by' => session()->get('user_id'),
                'indicators_status_at' => date('Y-m-d H:i:s'),
                'indicators_status_remarks' => '',
                'created_by' => session()->get('user_id'),
                'updated_by' => session()->get('user_id'),
            ];

            if ($this->mtdpIndicatorsModel->insert($data)) {
                $imported++;
            } else {
                $errors[] = "Row " . ($rowIndex + 2) . ": Failed to import " . $row[$indicatorIndex];
            }
        }

        $message = "Import completed. $imported indicators imported successfully.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', $errors);
        }

        return redirect()->to('admin/mtdp-plans/strategies/' . $strategyId . '/indicators')->with('success', $message);
    }
}
