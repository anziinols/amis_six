<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CommodityProductionModel;
use App\Models\CommoditiesModel;
use CodeIgniter\API\ResponseTrait;

/**
 * CommodityProductionController
 *
 * Handles CRUD operations for commodity production management
 * Only accessible by users with role='commodity'
 */
class CommodityProductionController extends BaseController
{
    use ResponseTrait;

    protected $commodityProductionModel;
    protected $commoditiesModel;

    /**
     * Constructor initializes models
     */
    public function __construct()
    {
        $this->commodityProductionModel = new CommodityProductionModel();
        $this->commoditiesModel = new CommoditiesModel();
    }

    /**
     * Check if user has admin capability to access commodity management
     */
    private function checkCommodityAccess()
    {
        $isAdmin = session()->get('is_admin');
        if ($isAdmin != 1) {
            session()->setFlashdata('error', 'Access denied. This section requires administrator privileges.');
            return redirect()->to(base_url('dashboard'));
        }

        return true;
    }

    /**
     * Display the commodity production dashboard (index)
     *
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function index()
    {
        // Check access
        $accessCheck = $this->checkCommodityAccess();
        if ($accessCheck !== true) {
            return $accessCheck;
        }

        // Get current user's commodity ID (required for access)
        $userCommodityId = session()->get('commodity_id');

        // Get production data for user's commodity only
        $productions = $this->commodityProductionModel->getProductionByCommodityId($userCommodityId);

        // Get detailed production data with joins
        $productionsWithDetails = [];
        foreach ($productions as $production) {
            $productionDetail = $this->commodityProductionModel->getCommodityProductionWithDetails($production['id']);
            if ($productionDetail) {
                $productionsWithDetails[] = $productionDetail;
            }
        }

        // Get production summary for user's commodity only
        $allSummary = $this->commodityProductionModel->getProductionSummaryByCommodity();
        $summary = array_filter($allSummary, function($item) use ($userCommodityId) {
            // Check if the summary item belongs to user's commodity
            $commodityInfo = $this->commoditiesModel->getCommodityById($userCommodityId);
            return $commodityInfo && $item['commodity_name'] == $commodityInfo['commodity_name'];
        });

        // Get user's commodity information
        $userCommodity = $this->commoditiesModel->getCommodityById($userCommodityId);

        $data = [
            'title' => 'Commodity Production Dashboard - ' . ($userCommodity['commodity_name'] ?? 'Unknown Commodity'),
            'productions' => $productionsWithDetails,
            'summary' => $summary,
            'user_commodity_id' => $userCommodityId,
            'user_commodity' => $userCommodity
        ];

        return view('commodities/commodities_index', $data);
    }

    /**
     * Display form for creating a new production record
     *
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function new()
    {
        // Check access
        $accessCheck = $this->checkCommodityAccess();
        if ($accessCheck !== true) {
            return $accessCheck;
        }

        // Get current user's commodity ID (required for access)
        $userCommodityId = session()->get('commodity_id');

        // Get user's commodity information
        $userCommodity = $this->commoditiesModel->getCommodityById($userCommodityId);

        $data = [
            'title' => 'Add New Production Record - ' . ($userCommodity['commodity_name'] ?? 'Unknown Commodity'),
            'user_commodity_id' => $userCommodityId,
            'user_commodity' => $userCommodity
        ];

        return view('commodities/commodities_create', $data);
    }

    /**
     * Process the creation of a new production record
     *
     * @return mixed
     */
    public function create()
    {
        // Check access
        $accessCheck = $this->checkCommodityAccess();
        if ($accessCheck !== true) {
            return $accessCheck;
        }

        $rules = [
            'date_from' => 'required|valid_date',
            'date_to' => 'required|valid_date',
            'item' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'unit_of_measurement' => 'permit_empty|max_length[50]',
            'quantity' => 'required|decimal',
            'is_exported' => 'permit_empty|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Get current user's commodity ID (automatically assigned)
        $userCommodityId = session()->get('commodity_id');

        // Get form data
        $data = [
            'commodity_id' => $userCommodityId, // Automatically use user's assigned commodity
            'date_from' => $this->request->getPost('date_from'),
            'date_to' => $this->request->getPost('date_to'),
            'item' => $this->request->getPost('item'),
            'description' => $this->request->getPost('description'),
            'unit_of_measurement' => $this->request->getPost('unit_of_measurement'),
            'quantity' => $this->request->getPost('quantity'),
            'is_exported' => $this->request->getPost('is_exported') ? 1 : 0,
            'created_by' => session()->get('user_id')
        ];

        // Validate date range
        if (!$this->commodityProductionModel->validateDateRange($data)) {
            session()->setFlashdata('error', 'End date must be after start date');
            return redirect()->back()->withInput();
        }

        $insertResult = $this->commodityProductionModel->insert($data);

        if (!$insertResult) {
            $errors = $this->commodityProductionModel->errors();
            if (!empty($errors)) {
                session()->setFlashdata('error', 'Validation failed: ' . implode(', ', $errors));
            } else {
                session()->setFlashdata('error', 'Failed to create production record - Database error');
            }
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Production record created successfully');
        return redirect()->to(base_url('commodity-boards'));
    }

    /**
     * Display a specific production record
     *
     * @param int|null $id The production record ID
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function show($id = null)
    {
        // Check access
        $accessCheck = $this->checkCommodityAccess();
        if ($accessCheck !== true) {
            return $accessCheck;
        }

        $production = $this->commodityProductionModel->getCommodityProductionWithDetails($id);

        if (!$production) {
            session()->setFlashdata('error', 'Production record not found');
            return redirect()->to(base_url('commodity-boards'));
        }

        // Check if user can view this record (if they have assigned commodity)
        $userCommodityId = session()->get('commodity_id');
        if ($userCommodityId && $production['commodity_id'] != $userCommodityId) {
            session()->setFlashdata('error', 'Access denied. You can only view records for your assigned commodity');
            return redirect()->to(base_url('commodity-boards'));
        }

        $data = [
            'title' => 'Production Record Details',
            'production' => $production
        ];

        return view('commodities/commodities_show', $data);
    }

    /**
     * Display form for editing a production record
     *
     * @param int|null $id The production record ID
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function edit($id = null)
    {
        // Check access
        $accessCheck = $this->checkCommodityAccess();
        if ($accessCheck !== true) {
            return $accessCheck;
        }

        $production = $this->commodityProductionModel->getCommodityProductionWithDetails($id);

        if (!$production) {
            session()->setFlashdata('error', 'Production record not found');
            return redirect()->to(base_url('commodity-boards'));
        }

        // Check if user can edit this record
        $userCommodityId = session()->get('commodity_id');
        if ($userCommodityId && $production['commodity_id'] != $userCommodityId) {
            session()->setFlashdata('error', 'Access denied. You can only edit records for your assigned commodity');
            return redirect()->to(base_url('commodity-boards'));
        }

        // Get user's commodity information
        $userCommodity = $this->commoditiesModel->getCommodityById($userCommodityId);

        $data = [
            'title' => 'Edit Production Record - ' . ($userCommodity['commodity_name'] ?? 'Unknown Commodity'),
            'production' => $production,
            'user_commodity_id' => $userCommodityId,
            'user_commodity' => $userCommodity
        ];

        return view('commodities/commodities_edit', $data);
    }

    /**
     * Process the update of a production record
     *
     * @param int|null $id The production record ID
     * @return mixed
     */
    public function update($id = null)
    {
        // Check access
        $accessCheck = $this->checkCommodityAccess();
        if ($accessCheck !== true) {
            return $accessCheck;
        }

        $production = $this->commodityProductionModel->getCommodityProductionById($id);

        if (!$production) {
            session()->setFlashdata('error', 'Production record not found');
            return redirect()->to(base_url('commodity-boards'));
        }

        // Check if user can edit this record
        $userCommodityId = session()->get('commodity_id');
        if ($userCommodityId && $production['commodity_id'] != $userCommodityId) {
            session()->setFlashdata('error', 'Access denied. You can only edit records for your assigned commodity');
            return redirect()->to(base_url('commodity-boards'));
        }

        $rules = [
            'date_from' => 'required|valid_date',
            'date_to' => 'required|valid_date',
            'item' => 'required|max_length[255]',
            'description' => 'permit_empty',
            'unit_of_measurement' => 'permit_empty|max_length[50]',
            'quantity' => 'required|decimal',
            'is_exported' => 'permit_empty|in_list[0,1]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Get form data
        $data = [
            'commodity_id' => $userCommodityId, // Automatically use user's assigned commodity
            'date_from' => $this->request->getPost('date_from'),
            'date_to' => $this->request->getPost('date_to'),
            'item' => $this->request->getPost('item'),
            'description' => $this->request->getPost('description'),
            'unit_of_measurement' => $this->request->getPost('unit_of_measurement'),
            'quantity' => $this->request->getPost('quantity'),
            'is_exported' => $this->request->getPost('is_exported') ? 1 : 0,
            'updated_by' => session()->get('user_id')
        ];

        // Validate date range
        if (!$this->commodityProductionModel->validateDateRange($data)) {
            session()->setFlashdata('error', 'End date must be after start date');
            return redirect()->back()->withInput();
        }

        $updateResult = $this->commodityProductionModel->update($id, $data);

        if (!$updateResult) {
            $errors = $this->commodityProductionModel->errors();
            if (!empty($errors)) {
                session()->setFlashdata('error', 'Validation failed: ' . implode(', ', $errors));
            } else {
                session()->setFlashdata('error', 'Failed to update production record - Database error');
            }
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Production record updated successfully');
        return redirect()->to(base_url('commodity-boards/' . $id . '/edit'));
    }

    /**
     * Delete a production record (soft delete)
     *
     * @param int|null $id The production record ID
     * @return mixed
     */
    public function delete($id = null)
    {
        // Check access
        $accessCheck = $this->checkCommodityAccess();
        if ($accessCheck !== true) {
            return $accessCheck;
        }

        $production = $this->commodityProductionModel->getCommodityProductionById($id);

        if (!$production) {
            session()->setFlashdata('error', 'Production record not found');
            return redirect()->to(base_url('commodity-boards'));
        }

        // Check if user can delete this record
        $userCommodityId = session()->get('commodity_id');
        if ($userCommodityId && $production['commodity_id'] != $userCommodityId) {
            session()->setFlashdata('error', 'Access denied. You can only delete records for your assigned commodity');
            return redirect()->to(base_url('commodity-boards'));
        }

        $deleteResult = $this->commodityProductionModel->softDelete($id, session()->get('user_id'));

        if (!$deleteResult) {
            session()->setFlashdata('error', 'Failed to delete production record');
            return redirect()->to(base_url('commodity-boards'));
        }

        session()->setFlashdata('success', 'Production record deleted successfully');
        return redirect()->to(base_url('commodity-boards'));
    }
}
