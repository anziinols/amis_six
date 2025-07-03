<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RegionModel;
use App\Models\RegionProvinceLinkModel;
use App\Models\GovStructureModel;
use CodeIgniter\API\ResponseTrait;

/**
 * RegionsController
 * 
 * Handles CRUD operations for regions and linking provinces to regions
 */
class RegionsController extends BaseController
{
    use ResponseTrait;

    protected $regionModel;
    protected $regionProvinceLinkModel;
    protected $govStructureModel;
    protected $helpers = ['form', 'url', 'session'];

    public function __construct()
    {
        $this->regionModel = new RegionModel();
        $this->regionProvinceLinkModel = new RegionProvinceLinkModel();
        $this->govStructureModel = new GovStructureModel();
    }

    /**
     * Display a list of all regions
     * 
     * @return mixed
     */
    public function index()
    {
        $data = [
            'title' => 'Regions Management',
            'regions' => $this->regionModel->getRegionsWithProvinceCount()
        ];

        return view('admin/regions/regions_index', $data);
    }

    /**
     * Display details of a specific region
     * 
     * @param int|null $id Region ID
     * @return mixed
     */
    public function show($id = null)
    {
        $region = $this->regionModel->getRegionById($id);
        
        if (!$region) {
            session()->setFlashdata('error', 'Region not found');
            return redirect()->to(base_url('admin/regions'));
        }

        $provinces = $this->regionProvinceLinkModel->getProvincesByRegion($id);

        $data = [
            'title' => 'Region Details: ' . $region['name'],
            'region' => $region,
            'provinces' => $provinces
        ];

        return view('admin/regions/regions_show', $data);
    }

    /**
     * Show form to create a new region
     * 
     * @return mixed
     */
    public function new()
    {
        $data = [
            'title' => 'Create New Region'
        ];

        return view('admin/regions/regions_create', $data);
    }

    /**
     * Process the creation of a new region
     * 
     * @return mixed
     */
    public function create()
    {
        $rules = [
            'name' => 'required|max_length[255]',
            'remarks' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => session()->get('user_id'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->regionModel->insert($data)) {
            session()->setFlashdata('success', 'Region created successfully');
            return redirect()->to(base_url('admin/regions'));
        } else {
            session()->setFlashdata('error', 'Failed to create region');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show form to edit a region
     * 
     * @param int|null $id Region ID
     * @return mixed
     */
    public function edit($id = null)
    {
        $region = $this->regionModel->getRegionById($id);
        
        if (!$region) {
            session()->setFlashdata('error', 'Region not found');
            return redirect()->to(base_url('admin/regions'));
        }

        $data = [
            'title' => 'Edit Region: ' . $region['name'],
            'region' => $region
        ];

        return view('admin/regions/regions_edit', $data);
    }

    /**
     * Process the update of a region
     * 
     * @param int|null $id Region ID
     * @return mixed
     */
    public function update($id = null)
    {
        $region = $this->regionModel->getRegionById($id);
        
        if (!$region) {
            session()->setFlashdata('error', 'Region not found');
            return redirect()->to(base_url('admin/regions'));
        }

        $rules = [
            'name' => 'required|max_length[255]',
            'remarks' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'remarks' => $this->request->getPost('remarks'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->regionModel->update($id, $data)) {
            session()->setFlashdata('success', 'Region updated successfully');
            return redirect()->to(base_url('admin/regions'));
        } else {
            session()->setFlashdata('error', 'Failed to update region');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Process the deletion of a region
     * 
     * @param int|null $id Region ID
     * @return mixed
     */
    public function delete($id = null)
    {
        $region = $this->regionModel->getRegionById($id);
        
        if (!$region) {
            session()->setFlashdata('error', 'Region not found');
            return redirect()->to(base_url('admin/regions'));
        }

        if ($this->regionModel->delete($id)) {
            session()->setFlashdata('success', 'Region deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete region');
        }

        return redirect()->to(base_url('admin/regions'));
    }

    /**
     * Show form to import provinces into a region
     * 
     * @param int|null $id Region ID
     * @return mixed
     */
    public function importProvinces($id = null)
    {
        $region = $this->regionModel->getRegionById($id);
        
        if (!$region) {
            session()->setFlashdata('error', 'Region not found');
            return redirect()->to(base_url('admin/regions'));
        }

        // Get all provinces
        $allProvinces = $this->govStructureModel->getByLevel('province');
        
        // Get provinces already assigned to this region
        $assignedProvinces = $this->regionProvinceLinkModel->getProvincesByRegion($id);
        $assignedProvinceIds = array_column($assignedProvinces, 'id');
        
        // Filter out provinces that are already assigned to any region
        $unassignedProvinces = [];
        foreach ($allProvinces as $province) {
            if (!in_array($province['id'], $assignedProvinceIds) && 
                !$this->regionProvinceLinkModel->isProvinceAssigned($province['id'])) {
                $unassignedProvinces[] = $province;
            }
        }

        $data = [
            'title' => 'Import Provinces to Region: ' . $region['name'],
            'region' => $region,
            'assignedProvinces' => $assignedProvinces,
            'unassignedProvinces' => $unassignedProvinces
        ];

        return view('admin/regions/regions_import_provinces', $data);
    }

    /**
     * Process the import of provinces into a region
     * 
     * @param int|null $id Region ID
     * @return mixed
     */
    public function saveImportProvinces($id = null)
    {
        $region = $this->regionModel->getRegionById($id);
        
        if (!$region) {
            session()->setFlashdata('error', 'Region not found');
            return redirect()->to(base_url('admin/regions'));
        }

        $provinceIds = $this->request->getPost('province_ids');
        
        if (empty($provinceIds)) {
            session()->setFlashdata('error', 'No provinces selected');
            return redirect()->back();
        }

        $userId = session()->get('user_id');
        $success = true;

        foreach ($provinceIds as $provinceId) {
            $data = [
                'region_id' => $id,
                'province_id' => $provinceId,
                'created_by' => $userId,
                'updated_by' => $userId
            ];

            if (!$this->regionProvinceLinkModel->insert($data)) {
                $success = false;
            }
        }

        if ($success) {
            session()->setFlashdata('success', 'Provinces imported successfully');
        } else {
            session()->setFlashdata('error', 'Failed to import some provinces');
        }

        return redirect()->to(base_url('admin/regions/' . $id));
    }

    /**
     * Remove a province from a region
     * 
     * @param int|null $regionId Region ID
     * @param int|null $provinceId Province ID
     * @return mixed
     */
    public function removeProvince($regionId = null, $provinceId = null)
    {
        $region = $this->regionModel->getRegionById($regionId);
        
        if (!$region) {
            session()->setFlashdata('error', 'Region not found');
            return redirect()->to(base_url('admin/regions'));
        }

        $userId = session()->get('user_id');
        
        // Find the link record
        $link = $this->regionProvinceLinkModel
            ->where('region_id', $regionId)
            ->where('province_id', $provinceId)
            ->first();
            
        if (!$link) {
            session()->setFlashdata('error', 'Province is not linked to this region');
            return redirect()->to(base_url('admin/regions/' . $regionId));
        }
        
        // Set deleted_by and soft delete
        $this->regionProvinceLinkModel->update($link['id'], ['deleted_by' => $userId]);
        
        if ($this->regionProvinceLinkModel->delete($link['id'])) {
            session()->setFlashdata('success', 'Province removed from region successfully');
        } else {
            session()->setFlashdata('error', 'Failed to remove province from region');
        }

        return redirect()->to(base_url('admin/regions/' . $regionId));
    }
}
