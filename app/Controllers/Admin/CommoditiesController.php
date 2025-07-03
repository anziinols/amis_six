<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CommoditiesModel;
use CodeIgniter\API\ResponseTrait;

/**
 * CommoditiesController
 *
 * Handles CRUD operations for commodities management
 */
class CommoditiesController extends BaseController
{
    use ResponseTrait;

    protected $commoditiesModel;

    /**
     * Constructor initializes model
     */
    public function __construct()
    {
        $this->commoditiesModel = new CommoditiesModel();
    }

    /**
     * Display the list of commodities
     *
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function index()
    {
        $data = [
            'title' => 'Commodities Management',
            'commodities' => $this->commoditiesModel->getAllCommoditiesWithUserNames()
        ];

        return view('admin/commodities/admin_commodities_index', $data);
    }

    /**
     * Display form for creating a new commodity
     *
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function new()
    {
        $data = [
            'title' => 'Add New Commodity'
        ];

        return view('admin/commodities/admin_commodities_create', $data);
    }

    /**
     * Process the creation of a new commodity
     *
     * @return mixed
     */
    public function create()
    {
        $rules = [
            'commodity_code' => 'required|max_length[50]|is_unique[commodities.commodity_code]',
            'commodity_name' => 'required|max_length[255]',
            'commodity_color_code' => 'permit_empty|max_length[10]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Handle file upload - simple approach
        $iconPath = null;
        $file = $this->request->getFile('commodity_icon_file');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Create upload directory if it doesn't exist
            $uploadPath = FCPATH . 'uploads/commodities/icons/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Generate unique filename
            $newName = 'commodity_' . time() . '_' . $file->getRandomName();

            if ($file->move($uploadPath, $newName)) {
                $iconPath = 'public/uploads/commodities/icons/' . $newName;
            }
        }

        $data = [
            'commodity_code' => $this->request->getPost('commodity_code'),
            'commodity_name' => $this->request->getPost('commodity_name'),
            'commodity_icon' => $iconPath,
            'commodity_color_code' => $this->request->getPost('commodity_color_code'),
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id')
        ];

        // Debug: Log the data being inserted
        log_message('debug', 'Creating commodity with data: ' . json_encode($data));

        $insertResult = $this->commoditiesModel->insert($data);

        // Debug: Log the result and any errors
        log_message('debug', 'Insert result: ' . ($insertResult ? 'true' : 'false'));

        if (!$insertResult) {
            $errors = $this->commoditiesModel->errors();
            log_message('error', 'Model validation errors: ' . json_encode($errors));

            // Check if there are validation errors
            if (!empty($errors)) {
                session()->setFlashdata('error', 'Validation failed: ' . implode(', ', $errors));
            } else {
                session()->setFlashdata('error', 'Failed to create commodity - Database error');
            }
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Commodity created successfully');
        return redirect()->to(base_url('admin/commodities'));
    }

    /**
     * Display a specific commodity
     *
     * @param int|null $id The commodity ID
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function show($id = null)
    {
        $commodity = $this->commoditiesModel->getCommodityWithUserNames($id);

        if (!$commodity) {
            session()->setFlashdata('error', 'Commodity not found');
            return redirect()->to(base_url('admin/commodities'));
        }

        $data = [
            'title' => 'Commodity Details',
            'commodity' => $commodity
        ];

        return view('admin/commodities/admin_commodities_show', $data);
    }

    /**
     * Display form for editing a commodity
     *
     * @param int|null $id The commodity ID
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function edit($id = null)
    {
        $commodity = $this->commoditiesModel->getCommodityWithUserNames($id);

        if (!$commodity) {
            session()->setFlashdata('error', 'Commodity not found');
            return redirect()->to(base_url('admin/commodities'));
        }

        $data = [
            'title' => 'Edit Commodity',
            'commodity' => $commodity
        ];

        return view('admin/commodities/admin_commodities_edit', $data);
    }

    /**
     * Process the update of a commodity
     *
     * @param int|null $id The commodity ID
     * @return mixed
     */
    public function update($id = null)
    {
        $commodity = $this->commoditiesModel->getCommodityById($id);

        if (!$commodity) {
            session()->setFlashdata('error', 'Commodity not found');
            return redirect()->to(base_url('admin/commodities'));
        }

        $rules = [
            'commodity_code' => "required|max_length[50]|is_unique[commodities.commodity_code,id,{$id}]",
            'commodity_name' => 'required|max_length[255]',
            'commodity_color_code' => 'permit_empty|max_length[10]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Handle file upload - simple approach
        $iconPath = $commodity['commodity_icon']; // Keep existing icon by default
        $file = $this->request->getFile('commodity_icon_file');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Create upload directory if it doesn't exist
            $uploadPath = FCPATH . 'uploads/commodities/icons/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Delete old icon file if it exists
            if (!empty($commodity['commodity_icon']) && file_exists(FCPATH . $commodity['commodity_icon'])) {
                unlink(FCPATH . $commodity['commodity_icon']);
            }

            // Generate unique filename
            $newName = 'commodity_' . time() . '_' . $file->getRandomName();

            if ($file->move($uploadPath, $newName)) {
                $iconPath = 'public/uploads/commodities/icons/' . $newName;
            }
        }

        $data = [
            'commodity_code' => $this->request->getPost('commodity_code'),
            'commodity_name' => $this->request->getPost('commodity_name'),
            'commodity_icon' => $iconPath,
            'commodity_color_code' => $this->request->getPost('commodity_color_code'),
            'updated_by' => session()->get('user_id')
        ];

        // Debug: Log the data being updated
        log_message('debug', 'Updating commodity ID: ' . $id);
        log_message('debug', 'Update data: ' . json_encode($data));

        // Debug: Try updating without validation first to isolate the issue
        $updateResult = $this->commoditiesModel->skipValidation(true)->update($id, $data);

        // If that works, then it's a validation issue
        if (!$updateResult) {
            // Try with validation to get the specific error
            $updateResult = $this->commoditiesModel->skipValidation(false)->update($id, $data);
        }

        // Debug: Log the result and any errors
        log_message('debug', 'Update result: ' . ($updateResult ? 'true' : 'false'));

        if (!$updateResult) {
            $errors = $this->commoditiesModel->errors();
            log_message('error', 'Model validation errors: ' . json_encode($errors));

            // Debug: Get database error if any
            $db = \Config\Database::connect();
            $dbError = $db->error();
            log_message('error', 'Database error: ' . json_encode($dbError));

            // Check if there are validation errors
            if (!empty($errors)) {
                $errorMsg = 'Validation failed: ' . implode(', ', $errors);
            } elseif (!empty($dbError['message'])) {
                $errorMsg = 'Database error: ' . $dbError['message'] . ' (Code: ' . $dbError['code'] . ')';
            } else {
                $errorMsg = 'Failed to update commodity - Unknown error. Check logs for details.';
            }

            session()->setFlashdata('error', $errorMsg);
            return redirect()->back()->withInput();
        }

        session()->setFlashdata('success', 'Commodity updated successfully');
        return redirect()->to(base_url('admin/commodities'));
    }

    /**
     * Delete a commodity (soft delete)
     *
     * @param int|null $id The commodity ID
     * @return mixed
     */
    public function delete($id = null)
    {
        $commodity = $this->commoditiesModel->getCommodityById($id);

        if (!$commodity) {
            session()->setFlashdata('error', 'Commodity not found');
            return redirect()->to(base_url('admin/commodities'));
        }

        if ($this->commoditiesModel->softDelete($id, session()->get('user_id'))) {
            // Note: We don't delete the file on soft delete to allow for restoration
            // File will be deleted only on hard delete if implemented
            session()->setFlashdata('success', 'Commodity deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete commodity');
        }

        return redirect()->to(base_url('admin/commodities'));
    }

    /**
     * Helper method to delete uploaded file
     *
     * @param string $filePath
     * @return bool
     */
    private function deleteUploadedFile($filePath)
    {
        if (!empty($filePath) && file_exists(ROOTPATH . $filePath)) {
            return unlink(ROOTPATH . $filePath);
        }
        return true;
    }

    /**
     * Temporary debug method to check logs
     */
    public function debug()
    {
        $logPath = WRITEPATH . 'logs/log-' . date('Y-m-d') . '.log';

        if (file_exists($logPath)) {
            $logs = file_get_contents($logPath);
            // Get last 50 lines
            $lines = explode("\n", $logs);
            $lastLines = array_slice($lines, -50);

            echo "<h3>Last 50 log entries:</h3>";
            echo "<pre style='background: #f4f4f4; padding: 10px; font-size: 12px;'>";
            echo htmlspecialchars(implode("\n", $lastLines));
            echo "</pre>";
        } else {
            echo "No log file found for today.";
        }

        echo "<br><a href='" . base_url('admin/commodities') . "'>Back to Commodities</a>";
    }
}
