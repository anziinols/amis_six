<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GovStructureModel;

class GovStructureController extends BaseController
{
    protected $govStructureModel;
    
    public function __construct()
    {
        $this->govStructureModel = new GovStructureModel();
    }
    
    /**
     * Display list of provinces
     */
    public function index()
    {
        $data = [
            'title' => 'Government Structure - Provinces',
            'provinces' => $this->govStructureModel->getByLevel('province')
        ];
        
        return view('admin/gov_structure/gov_structure_provinces_list', $data);
    }
    
    /**
     * Create a new province
     */
    public function createProvince()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|max_length[255]',
                'code' => 'required|max_length[20]'
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'parent_id' => 0, // Provinces are top level
                    'json_id' => $this->request->getPost('code'),
                    'level' => 'province',
                    'code' => $this->request->getPost('code'),
                    'name' => $this->request->getPost('name'),
                    'flag_filepath' => '',
                    'map_center' => $this->request->getPost('map_center'),
                    'map_zoom' => $this->request->getPost('map_zoom'),
                    'created_by' => session()->get('user_id') ?? 'system',
                    'updated_by' => session()->get('user_id') ?? 'system'
                ];
                
                if ($this->govStructureModel->save($data)) {
                    session()->setFlashdata('success', 'Province created successfully');
                } else {
                    session()->setFlashdata('error', 'Failed to create province');
                }
            } else {
                session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            }
        }
        
        return redirect()->to(base_url('admin/gov-structure'));
    }
    
    /**
     * Update an existing province
     */
    public function updateProvince($id = null)
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|max_length[255]',
                'code' => 'required|max_length[20]'
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'id' => $id,
                    'code' => $this->request->getPost('code'),
                    'name' => $this->request->getPost('name'),
                    'map_center' => $this->request->getPost('map_center'),
                    'map_zoom' => $this->request->getPost('map_zoom'),
                    'updated_by' => session()->get('user_id') ?? 'system'
                ];
                
                if ($this->govStructureModel->save($data)) {
                    session()->setFlashdata('success', 'Province updated successfully');
                } else {
                    session()->setFlashdata('error', 'Failed to update province');
                }
            } else {
                session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            }
        }
        
        return redirect()->to(base_url('admin/gov-structure'));
    }
    
    /**
     * Delete a province
     */
    public function deleteProvince($id = null)
    {
        // Check if province has districts
        $districts = $this->govStructureModel->getByParent($id);
        if (count($districts) > 0) {
            session()->setFlashdata('error', 'Cannot delete province with districts. Delete districts first.');
            return redirect()->to(base_url('admin/gov-structure'));
        }
        
        if ($this->govStructureModel->delete($id)) {
            session()->setFlashdata('success', 'Province deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete province');
        }
        
        return redirect()->to(base_url('admin/gov-structure'));
    }
    
    /**
     * Display list of districts for a province
     */
    public function viewDistricts($provinceId = null)
    {
        $province = $this->govStructureModel->find($provinceId);
        if (!$province || $province['level'] !== 'province') {
            session()->setFlashdata('error', 'Invalid province selected');
            return redirect()->to(base_url('admin/gov-structure'));
        }
        
        $data = [
            'title' => 'Districts in ' . $province['name'],
            'province' => $province,
            'districts' => $this->govStructureModel->getByParent($provinceId)
        ];
        
        return view('admin/gov_structure/gov_structure_districts_list', $data);
    }
    
    /**
     * Create a new district
     */
    public function createDistrict($provinceId = null)
    {
        $province = $this->govStructureModel->find($provinceId);
        if (!$province || $province['level'] !== 'province') {
            session()->setFlashdata('error', 'Invalid province selected');
            return redirect()->to(base_url('admin/gov-structure'));
        }
        
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|max_length[255]',
                'code' => 'required|max_length[20]'
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'parent_id' => $provinceId,
                    'json_id' => $this->request->getPost('code'),
                    'level' => 'district',
                    'code' => $this->request->getPost('code'),
                    'name' => $this->request->getPost('name'),
                    'flag_filepath' => '',
                    'map_center' => $this->request->getPost('map_center'),
                    'map_zoom' => $this->request->getPost('map_zoom'),
                    'created_by' => session()->get('user_id') ?? 'system',
                    'updated_by' => session()->get('user_id') ?? 'system'
                ];
                
                if ($this->govStructureModel->save($data)) {
                    session()->setFlashdata('success', 'District created successfully');
                } else {
                    session()->setFlashdata('error', 'Failed to create district');
                }
            } else {
                session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            }
        }
        
        return redirect()->to(base_url('admin/view-districts/' . $provinceId));
    }
    
    /**
     * Update an existing district
     */
    public function updateDistrict($id = null)
    {
        $district = $this->govStructureModel->find($id);
        if (!$district || $district['level'] !== 'district') {
            session()->setFlashdata('error', 'Invalid district selected');
            return redirect()->to(base_url('admin/gov-structure'));
        }
        
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|max_length[255]',
                'code' => 'required|max_length[20]'
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'id' => $id,
                    'code' => $this->request->getPost('code'),
                    'name' => $this->request->getPost('name'),
                    'map_center' => $this->request->getPost('map_center'),
                    'map_zoom' => $this->request->getPost('map_zoom'),
                    'updated_by' => session()->get('user_id') ?? 'system'
                ];
                
                if ($this->govStructureModel->save($data)) {
                    session()->setFlashdata('success', 'District updated successfully');
                } else {
                    session()->setFlashdata('error', 'Failed to update district');
                }
            } else {
                session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            }
        }
        
        $provinceId = $district['parent_id'];
        return redirect()->to(base_url('admin/view-districts/' . $provinceId));
    }
    
    /**
     * Delete a district
     */
    public function deleteDistrict($id = null)
    {
        $district = $this->govStructureModel->find($id);
        if (!$district || $district['level'] !== 'district') {
            session()->setFlashdata('error', 'Invalid district selected');
            return redirect()->to(base_url('admin/gov-structure'));
        }
        
        // Check if district has LLGs
        $llgs = $this->govStructureModel->getByParent($id);
        if (count($llgs) > 0) {
            session()->setFlashdata('error', 'Cannot delete district with LLGs. Delete LLGs first.');
            return redirect()->to(base_url('admin/view-districts/' . $district['parent_id']));
        }
        
        $provinceId = $district['parent_id'];
        
        if ($this->govStructureModel->delete($id)) {
            session()->setFlashdata('success', 'District deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete district');
        }
        
        return redirect()->to(base_url('admin/view-districts/' . $provinceId));
    }
    
    /**
     * Display list of LLGs for a district
     */
    public function viewLLGs($districtId = null)
    {
        $district = $this->govStructureModel->find($districtId);
        if (!$district || $district['level'] !== 'district') {
            session()->setFlashdata('error', 'Invalid district selected');
            return redirect()->to(base_url('admin/gov-structure'));
        }
        
        $province = $this->govStructureModel->find($district['parent_id']);
        
        $data = [
            'title' => 'LLGs in ' . $district['name'] . ' District',
            'province' => $province,
            'district' => $district,
            'llgs' => $this->govStructureModel->getByParent($districtId)
        ];
        
        return view('admin/gov_structure/gov_structure_llgs_list', $data);
    }
    
    /**
     * Create a new LLG
     */
    public function createLLG($districtId = null)
    {
        $district = $this->govStructureModel->find($districtId);
        if (!$district || $district['level'] !== 'district') {
            session()->setFlashdata('error', 'Invalid district selected');
            return redirect()->to(base_url('admin/gov-structure'));
        }
        
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|max_length[255]',
                'code' => 'required|max_length[20]'
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'parent_id' => $districtId,
                    'json_id' => $this->request->getPost('code'),
                    'level' => 'llg',
                    'code' => $this->request->getPost('code'),
                    'name' => $this->request->getPost('name'),
                    'flag_filepath' => '',
                    'map_center' => $this->request->getPost('map_center'),
                    'map_zoom' => $this->request->getPost('map_zoom'),
                    'created_by' => session()->get('user_id') ?? 'system',
                    'updated_by' => session()->get('user_id') ?? 'system'
                ];
                
                if ($this->govStructureModel->save($data)) {
                    session()->setFlashdata('success', 'LLG created successfully');
                } else {
                    session()->setFlashdata('error', 'Failed to create LLG');
                }
            } else {
                session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            }
        }
        
        return redirect()->to(base_url('admin/view-llgs/' . $districtId));
    }
    
    /**
     * Update an existing LLG
     */
    public function updateLLG($id = null)
    {
        $llg = $this->govStructureModel->find($id);
        if (!$llg || $llg['level'] !== 'llg') {
            session()->setFlashdata('error', 'Invalid LLG selected');
            return redirect()->to(base_url('admin/gov-structure'));
        }
        
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'name' => 'required|max_length[255]',
                'code' => 'required|max_length[20]'
            ];
            
            if ($this->validate($rules)) {
                $data = [
                    'id' => $id,
                    'code' => $this->request->getPost('code'),
                    'name' => $this->request->getPost('name'),
                    'map_center' => $this->request->getPost('map_center'),
                    'map_zoom' => $this->request->getPost('map_zoom'),
                    'updated_by' => session()->get('user_id') ?? 'system'
                ];
                
                if ($this->govStructureModel->save($data)) {
                    session()->setFlashdata('success', 'LLG updated successfully');
                } else {
                    session()->setFlashdata('error', 'Failed to update LLG');
                }
            } else {
                session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
            }
        }
        
        $districtId = $llg['parent_id'];
        return redirect()->to(base_url('admin/view-llgs/' . $districtId));
    }
    
    /**
     * Delete an LLG
     */
    public function deleteLLG($id = null)
    {
        $llg = $this->govStructureModel->find($id);
        if (!$llg || $llg['level'] !== 'llg') {
            session()->setFlashdata('error', 'Invalid LLG selected');
            return redirect()->to(base_url('admin/gov-structure'));
        }
        
        // Check if LLG has wards
        $wards = $this->govStructureModel->getByParent($id);
        if (count($wards) > 0) {
            session()->setFlashdata('error', 'Cannot delete LLG with wards. Delete wards first.');
            return redirect()->to(base_url('admin/view-llgs/' . $llg['parent_id']));
        }
        
        $districtId = $llg['parent_id'];
        
        if ($this->govStructureModel->delete($id)) {
            session()->setFlashdata('success', 'LLG deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete LLG');
        }
        
        return redirect()->to(base_url('admin/view-llgs/' . $districtId));
    }
}
