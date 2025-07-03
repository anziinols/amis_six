<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GovStructureModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\API\ResponseTrait; // Use ResponseTrait for API-like responses if needed

class GovStructureController extends BaseController // Changed from ResourceController to BaseController for more control over view rendering
{
    use ResponseTrait; // Keep ResponseTrait for potential AJAX calls

    protected $govStructureModel;
    protected $helpers = ['form', 'url', 'session']; // Add necessary helpers

    public function __construct()
    {
        $this->govStructureModel = new GovStructureModel();
    }

    //--------------------------------------------------------------------
    // Province Methods
    //--------------------------------------------------------------------

    /**
     * Display a list of provinces.
     * Corresponds to GET /admin/provinces
     *
     * @return mixed
     */
    public function provinceIndex()
    {
        $data = [
            'title'     => 'Government Structure - Provinces',
            'provinces' => $this->govStructureModel->getByLevel('province'),
        ];

        return view('admin/gov_structure/gov_structure_provinces_list', $data);
    }

    /**
     * Show the form for creating a new province.
     * Corresponds to GET /admin/provinces/new
     * Note: This might be handled by a modal in the view, but defining the route is good practice.
     *
     * @return mixed
     */
    public function provinceNew()
    {
        // Typically, you'd load a view here, e.g., return view('admin/gov_structure/province_new');
        // For now, redirecting back or assuming modal handles it.
        session()->setFlashdata('info', 'Province creation is handled via the list view modal.');
        return redirect()->to(base_url('admin/provinces'));
    }

    /**
     * Process the creation of a new province.
     * Corresponds to POST /admin/provinces
     * Handles AJAX requests primarily based on original code.
     *
     * @return mixed
     */
    public function provinceCreate()
    {
        $rules = [
            'name' => 'required|max_length[255]',
            'code' => 'required|max_length[20]|is_unique[gov_structure.code]', // Added is_unique
            'map_center' => 'permit_empty|max_length[100]',
            'map_zoom' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'parent_id'  => 0,
            'json_id'    => $this->request->getPost('code'), // Assuming json_id is same as code for provinces
            'level'      => 'province',
            'code'       => $this->request->getPost('code'),
            'name'       => $this->request->getPost('name'),
            'flag_filepath' => '', // Handle file uploads separately if needed
            'map_center' => $this->request->getPost('map_center'),
            'map_zoom'   => $this->request->getPost('map_zoom'),
            'created_by' => session()->get('user_id') ?? 'system',
            'updated_by' => session()->get('user_id') ?? 'system',
        ];

        $provinceId = $this->govStructureModel->insert($data);

        if (!$provinceId) {
            return $this->failServerError('Failed to create province');
        }

        // Respond for AJAX
        return $this->respondCreated([
            'id'      => $provinceId,
            'message' => 'Province created successfully',
            'province' => $this->govStructureModel->find($provinceId) // Return created data
        ]);
    }

    /**
     * Show the form for editing a specific province.
     * Corresponds to GET /admin/provinces/(:num)/edit
     * Note: This might be handled by a modal in the view.
     *
     * @param int|null $id Province ID
     * @return mixed
     */
    public function provinceEdit($id = null)
    {
        $province = $this->govStructureModel->find($id);
        if (!$province || $province['level'] !== 'province') {
             if ($this->request->isAJAX()) {
                 return $this->failNotFound('Province not found');
             }
             session()->setFlashdata('error', 'Province not found.');
             return redirect()->to(base_url('admin/provinces'));
        }

        // If AJAX, return data for modal
        if ($this->request->isAJAX()) {
            return $this->respond(['province' => $province]);
        }

        // If not AJAX, load an edit view (assuming one exists or is needed)
        // return view('admin/gov_structure/province_edit', ['province' => $province, 'title' => 'Edit Province']);
        session()->setFlashdata('info', 'Province editing is handled via the list view modal.');
        return redirect()->to(base_url('admin/provinces'));
    }

    /**
     * Process the update of a specific province.
     * Corresponds to PUT or POST /admin/provinces/(:num)
     * Handles AJAX requests primarily.
     *
     * @param int|null $id Province ID
     * @return mixed
     */
    public function provinceUpdate($id = null)
    {
        $province = $this->govStructureModel->find($id);
        if (!$province || $province['level'] !== 'province') {
            return $this->failNotFound('Province not found');
        }

        $rules = [
            'name' => 'required|max_length[255]',
            // Unique check ignores the current record
            'code' => "required|max_length[20]|is_unique[gov_structure.code,id,{$id}]",
            'map_center' => 'permit_empty|max_length[100]',
            'map_zoom' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'code'       => $this->request->getVar('code'), // Use getVar to handle PUT/POST/etc.
            'name'       => $this->request->getVar('name'),
            'map_center' => $this->request->getVar('map_center'),
            'map_zoom'   => $this->request->getVar('map_zoom'),
            'updated_by' => session()->get('user_id') ?? 'system',
        ];

        if (!$this->govStructureModel->update($id, $data)) {
            // Check for specific DB errors if possible
            log_message('error', 'Failed to update province ID ' . $id . ': ' . json_encode($this->govStructureModel->errors()));
            return $this->failServerError('Failed to update province. Check logs.');
        }

        // Respond for AJAX
        return $this->respondUpdated([
            'id'      => $id,
            'message' => 'Province updated successfully',
            'province' => $this->govStructureModel->find($id) // Return updated data
        ]);
    }

    /**
     * Delete a specific province.
     * Corresponds to DELETE /admin/provinces/(:num)
     * Handles AJAX requests primarily.
     *
     * @param int|null $id Province ID
     * @return mixed
     */
    public function provinceDelete($id = null)
    {
        $province = $this->govStructureModel->find($id);
        if (!$province || $province['level'] !== 'province') {
            return $this->failNotFound('Province not found');
        }

        // Check for dependencies (districts)
        $districts = $this->govStructureModel->getByParent($id);
        if (!empty($districts)) {
            return $this->fail('Cannot delete province with districts. Delete districts first.', 400); // 400 Bad Request
        }

        if (!$this->govStructureModel->delete($id)) {
            log_message('error', 'Failed to delete province ID ' . $id . ': ' . json_encode($this->govStructureModel->errors()));
            return $this->failServerError('Failed to delete province. Check logs.');
        }

        // Respond for AJAX
        return $this->respondDeleted([
            'id'      => $id,
            'message' => 'Province deleted successfully',
        ]);
    }


    //--------------------------------------------------------------------
    // District Methods
    //--------------------------------------------------------------------

    /**
     * Display a list of districts for a specific province.
     * Corresponds to GET /admin/provinces/(:num)/districts
     *
     * @param int $provinceId
     * @return mixed
     */
    public function districtIndex($provinceId)
    {
        $province = $this->findProvinceOrFail($provinceId);

        $data = [
            'title'     => 'Districts in ' . $province['name'],
            'province'  => $province,
            'districts' => $this->govStructureModel->getByParent($provinceId, 'district'),
        ];

        return view('admin/gov_structure/gov_structure_districts_list', $data);
    }

     /**
     * Show the form for creating a new district for a province.
     * Corresponds to GET /admin/provinces/(:num)/districts/new
     *
     * @param int $provinceId
     * @return mixed
     */
    public function districtNew($provinceId)
    {
        $province = $this->findProvinceOrFail($provinceId);
        // Assuming a view exists or it's handled by a modal in districtIndex view
        // return view('admin/gov_structure/district_new', ['province' => $province, 'title' => 'Add District to ' . $province['name']]);
        session()->setFlashdata('info', 'District creation is handled via the list view modal/form.');
        return redirect()->to(base_url('admin/provinces/' . $provinceId . '/districts'));
    }

    /**
     * Process the creation of a new district for a province.
     * Corresponds to POST /admin/provinces/(:num)/districts
     *
     * @param int $provinceId
     * @return mixed
     */
    public function districtCreate($provinceId)
    {
        $province = $this->findProvinceOrFail($provinceId);

        $rules = [
            'name' => 'required|max_length[255]',
            'code' => 'required|max_length[20]|is_unique[gov_structure.code]',
            'map_center' => 'permit_empty|max_length[100]',
            'map_zoom' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            // Handle validation errors (e.g., return with errors for AJAX or redirect with flashdata)
             if ($this->request->isAJAX()) {
                 return $this->failValidationErrors($this->validator->getErrors());
             }
             session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
             return redirect()->back()->withInput();
        }

        $data = [
            'parent_id'  => $provinceId,
            'json_id'    => $this->request->getPost('code'), // Assuming json_id is same as code
            'level'      => 'district',
            'code'       => $this->request->getPost('code'),
            'name'       => $this->request->getPost('name'),
            'flag_filepath' => '',
            'map_center' => $this->request->getPost('map_center'),
            'map_zoom'   => $this->request->getPost('map_zoom'),
            'created_by' => session()->get('user_id') ?? 'system',
            'updated_by' => session()->get('user_id') ?? 'system',
        ];

        $districtId = $this->govStructureModel->insert($data);

        if (!$districtId) {
             if ($this->request->isAJAX()) {
                 return $this->failServerError('Failed to create district');
             }
             session()->setFlashdata('error', 'Failed to create district');
        } else {
             if ($this->request->isAJAX()) {
                 return $this->respondCreated([
                     'id' => $districtId,
                     'message' => 'District created successfully',
                     'district' => $this->govStructureModel->find($districtId)
                 ]);
             }
             session()->setFlashdata('success', 'District created successfully');
        }

        return redirect()->to(base_url('admin/provinces/' . $provinceId . '/districts'));
    }

    /**
     * Show the form for editing a specific district.
     * Corresponds to GET /admin/districts/(:num)/edit
     *
     * @param int $id District ID
     * @return mixed
     */
    public function districtEdit($id)
    {
        $district = $this->findDistrictOrFail($id);
        $province = $this->findProvinceOrFail($district['parent_id']);

        // Assuming a view exists or it's handled by a modal in districtIndex view
        // return view('admin/gov_structure/district_edit', ['district' => $district, 'province' => $province, 'title' => 'Edit District']);
         if ($this->request->isAJAX()) {
             return $this->respond(['district' => $district, 'province' => $province]);
         }
         session()->setFlashdata('info', 'District editing is handled via the list view modal/form.');
         return redirect()->to(base_url('admin/provinces/' . $district['parent_id'] . '/districts'));
    }

    /**
     * Process the update of a specific district.
     * Corresponds to PUT or POST /admin/districts/(:num)
     *
     * @param int $id District ID
     * @return mixed
     */
    public function districtUpdate($id)
    {
        $district = $this->findDistrictOrFail($id);

        $rules = [
            'name' => 'required|max_length[255]',
            'code' => "required|max_length[20]|is_unique[gov_structure.code,id,{$id}]",
            'map_center' => 'permit_empty|max_length[100]',
            'map_zoom' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
             if ($this->request->isAJAX()) {
                 return $this->failValidationErrors($this->validator->getErrors());
             }
             session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
             return redirect()->back()->withInput();
        }

        $data = [
            'code'       => $this->request->getVar('code'),
            'name'       => $this->request->getVar('name'),
            'map_center' => $this->request->getVar('map_center'),
            'map_zoom'   => $this->request->getVar('map_zoom'),
            'updated_by' => session()->get('user_id') ?? 'system',
        ];

        if (!$this->govStructureModel->update($id, $data)) {
             if ($this->request->isAJAX()) {
                 log_message('error', 'Failed to update district ID ' . $id . ': ' . json_encode($this->govStructureModel->errors()));
                 return $this->failServerError('Failed to update district');
             }
             session()->setFlashdata('error', 'Failed to update district');
        } else {
             if ($this->request->isAJAX()) {
                 return $this->respondUpdated([
                     'id' => $id,
                     'message' => 'District updated successfully',
                     'district' => $this->govStructureModel->find($id)
                 ]);
             }
             session()->setFlashdata('success', 'District updated successfully');
        }

        return redirect()->to(base_url('admin/provinces/' . $district['parent_id'] . '/districts'));
    }

    /**
     * Delete a specific district.
     * Corresponds to DELETE /admin/districts/(:num)
     *
     * @param int $id District ID
     * @return mixed
     */
    public function districtDelete($id)
    {
        $district = $this->findDistrictOrFail($id);
        $provinceId = $district['parent_id'];

        // Check for dependencies (LLGs)
        $llgs = $this->govStructureModel->getByParent($id, 'llg');
        if (!empty($llgs)) {
             if ($this->request->isAJAX()) {
                 return $this->fail('Cannot delete district with LLGs. Delete LLGs first.', 400);
             }
             session()->setFlashdata('error', 'Cannot delete district with LLGs. Delete LLGs first.');
             return redirect()->to(base_url('admin/provinces/' . $provinceId . '/districts'));
        }

        if (!$this->govStructureModel->delete($id)) {
             if ($this->request->isAJAX()) {
                 log_message('error', 'Failed to delete district ID ' . $id . ': ' . json_encode($this->govStructureModel->errors()));
                 return $this->failServerError('Failed to delete district');
             }
             session()->setFlashdata('error', 'Failed to delete district');
        } else {
             if ($this->request->isAJAX()) {
                 return $this->respondDeleted([
                     'id' => $id,
                     'message' => 'District deleted successfully'
                 ]);
             }
             session()->setFlashdata('success', 'District deleted successfully');
        }

        return redirect()->to(base_url('admin/provinces/' . $provinceId . '/districts'));
    }


    //--------------------------------------------------------------------
    // LLG Methods
    //--------------------------------------------------------------------

    /**
     * Display a list of LLGs for a specific district.
     * Corresponds to GET /admin/districts/(:num)/llgs
     *
     * @param int $districtId
     * @return mixed
     */
    public function llgIndex($districtId)
    {
        $district = $this->findDistrictOrFail($districtId);
        $province = $this->findProvinceOrFail($district['parent_id']);

        $data = [
            'title'    => 'LLGs in ' . $district['name'] . ' District',
            'province' => $province,
            'district' => $district,
            'llgs'     => $this->govStructureModel->getByParent($districtId, 'llg'),
        ];

        return view('admin/gov_structure/gov_structure_llgs_list', $data);
    }

     /**
     * Show the form for creating a new LLG for a district.
     * Corresponds to GET /admin/districts/(:num)/llgs/new
     *
     * @param int $districtId
     * @return mixed
     */
    public function llgNew($districtId)
    {
        $district = $this->findDistrictOrFail($districtId);
        $province = $this->findProvinceOrFail($district['parent_id']);
        // Assuming a view exists or it's handled by a modal in llgIndex view
        // return view('admin/gov_structure/llg_new', ['district' => $district, 'province' => $province, 'title' => 'Add LLG to ' . $district['name']]);
        session()->setFlashdata('info', 'LLG creation is handled via the list view modal/form.');
        return redirect()->to(base_url('admin/districts/' . $districtId . '/llgs'));
    }

    /**
     * Process the creation of a new LLG for a district.
     * Corresponds to POST /admin/districts/(:num)/llgs
     *
     * @param int $districtId
     * @return mixed
     */
    public function llgCreate($districtId)
    {
        $district = $this->findDistrictOrFail($districtId);

        $rules = [
            'name' => 'required|max_length[255]',
            'code' => 'required|max_length[20]|is_unique[gov_structure.code]',
            'map_center' => 'permit_empty|max_length[100]',
            'map_zoom' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
             if ($this->request->isAJAX()) {
                 return $this->failValidationErrors($this->validator->getErrors());
             }
             session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
             return redirect()->back()->withInput();
        }

        $data = [
            'parent_id'  => $districtId,
            'json_id'    => $this->request->getPost('code'), // Assuming json_id is same as code
            'level'      => 'llg',
            'code'       => $this->request->getPost('code'),
            'name'       => $this->request->getPost('name'),
            'flag_filepath' => '',
            'map_center' => $this->request->getPost('map_center'),
            'map_zoom'   => $this->request->getPost('map_zoom'),
            'created_by' => session()->get('user_id') ?? 'system',
            'updated_by' => session()->get('user_id') ?? 'system',
        ];

        $llgId = $this->govStructureModel->insert($data);

        if (!$llgId) {
             if ($this->request->isAJAX()) {
                 return $this->failServerError('Failed to create LLG');
             }
             session()->setFlashdata('error', 'Failed to create LLG');
        } else {
             if ($this->request->isAJAX()) {
                 return $this->respondCreated([
                     'id' => $llgId,
                     'message' => 'LLG created successfully',
                     'llg' => $this->govStructureModel->find($llgId)
                 ]);
             }
             session()->setFlashdata('success', 'LLG created successfully');
        }

        return redirect()->to(base_url('admin/districts/' . $districtId . '/llgs'));
    }

    /**
     * Show the form for editing a specific LLG.
     * Corresponds to GET /admin/llgs/(:num)/edit
     *
     * @param int $id LLG ID
     * @return mixed
     */
    public function llgEdit($id)
    {
        $llg = $this->findLlgOrFail($id);
        $district = $this->findDistrictOrFail($llg['parent_id']);
        $province = $this->findProvinceOrFail($district['parent_id']);

        // Assuming a view exists or it's handled by a modal in llgIndex view
        // return view('admin/gov_structure/llg_edit', ['llg' => $llg, 'district' => $district, 'province' => $province, 'title' => 'Edit LLG']);
         if ($this->request->isAJAX()) {
             return $this->respond(['llg' => $llg, 'district' => $district, 'province' => $province]);
         }
         session()->setFlashdata('info', 'LLG editing is handled via the list view modal/form.');
         return redirect()->to(base_url('admin/districts/' . $llg['parent_id'] . '/llgs'));
    }

    /**
     * Process the update of a specific LLG.
     * Corresponds to PUT or POST /admin/llgs/(:num)
     *
     * @param int $id LLG ID
     * @return mixed
     */
    public function llgUpdate($id)
    {
        $llg = $this->findLlgOrFail($id);
        $districtId = $llg['parent_id'];

        $rules = [
            'name' => 'required|max_length[255]',
            'code' => "required|max_length[20]|is_unique[gov_structure.code,id,{$id}]",
            'map_center' => 'permit_empty|max_length[100]',
            'map_zoom' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
             if ($this->request->isAJAX()) {
                 return $this->failValidationErrors($this->validator->getErrors());
             }
             session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
             return redirect()->back()->withInput();
        }

        $data = [
            'code'       => $this->request->getVar('code'),
            'name'       => $this->request->getVar('name'),
            'map_center' => $this->request->getVar('map_center'),
            'map_zoom'   => $this->request->getVar('map_zoom'),
            'updated_by' => session()->get('user_id') ?? 'system',
        ];

        if (!$this->govStructureModel->update($id, $data)) {
             if ($this->request->isAJAX()) {
                 log_message('error', 'Failed to update LLG ID ' . $id . ': ' . json_encode($this->govStructureModel->errors()));
                 return $this->failServerError('Failed to update LLG');
             }
             session()->setFlashdata('error', 'Failed to update LLG');
        } else {
             if ($this->request->isAJAX()) {
                 return $this->respondUpdated([
                     'id' => $id,
                     'message' => 'LLG updated successfully',
                     'llg' => $this->govStructureModel->find($id)
                 ]);
             }
             session()->setFlashdata('success', 'LLG updated successfully');
        }

        return redirect()->to(base_url('admin/districts/' . $districtId . '/llgs'));
    }

    /**
     * Delete a specific LLG.
     * Corresponds to DELETE /admin/llgs/(:num)
     *
     * @param int $id LLG ID
     * @return mixed
     */
    public function llgDelete($id)
    {
        $llg = $this->findLlgOrFail($id);
        $districtId = $llg['parent_id'];

        // Check for dependencies (wards) - Assuming 'ward' is the next level
        $wards = $this->govStructureModel->getByParent($id, 'ward'); // Adjust level if needed
        if (!empty($wards)) {
             if ($this->request->isAJAX()) {
                 return $this->fail('Cannot delete LLG with wards. Delete wards first.', 400);
             }
             session()->setFlashdata('error', 'Cannot delete LLG with wards. Delete wards first.');
             return redirect()->to(base_url('admin/districts/' . $districtId . '/llgs'));
        }

        if (!$this->govStructureModel->delete($id)) {
             if ($this->request->isAJAX()) {
                 log_message('error', 'Failed to delete LLG ID ' . $id . ': ' . json_encode($this->govStructureModel->errors()));
                 return $this->failServerError('Failed to delete LLG');
             }
             session()->setFlashdata('error', 'Failed to delete LLG');
        } else {
             if ($this->request->isAJAX()) {
                 return $this->respondDeleted([
                     'id' => $id,
                     'message' => 'LLG deleted successfully'
                 ]);
             }
             session()->setFlashdata('success', 'LLG deleted successfully');
        }

        return redirect()->to(base_url('admin/districts/' . $districtId . '/llgs'));
    }


    //--------------------------------------------------------------------
    // Ward Methods
    //--------------------------------------------------------------------

    /**
     * Display a list of wards for a specific LLG.
     * Corresponds to GET /admin/llgs/(:num)/wards
     *
     * @param int $llgId
     * @return mixed
     */
    public function wardIndex($llgId)
    {
        $llg = $this->findLlgOrFail($llgId);
        $district = $this->findDistrictOrFail($llg['parent_id']);
        $province = $this->findProvinceOrFail($district['parent_id']);

        $data = [
            'title'    => 'Wards in ' . $llg['name'] . ' LLG',
            'province' => $province,
            'district' => $district,
            'llg'      => $llg,
            'wards'    => $this->govStructureModel->getByParent($llgId, 'ward'),
        ];

        return view('admin/gov_structure/gov_structure_wards_list', $data);
    }

     /**
     * Show the form for creating a new ward for an LLG.
     * Corresponds to GET /admin/llgs/(:num)/wards/new
     *
     * @param int $llgId
     * @return mixed
     */
    public function wardNew($llgId)
    {
        $llg = $this->findLlgOrFail($llgId);
        $district = $this->findDistrictOrFail($llg['parent_id']);
        $province = $this->findProvinceOrFail($district['parent_id']);
        // Assuming a view exists or it's handled by a modal in wardIndex view
        session()->setFlashdata('info', 'Ward creation is handled via the list view modal/form.');
        return redirect()->to(base_url('admin/gov-structure/llgs/' . $llgId . '/wards'));
    }

    /**
     * Process the creation of a new ward for an LLG.
     * Corresponds to POST /admin/llgs/(:num)/wards
     *
     * @param int $llgId
     * @return mixed
     */
    public function wardCreate($llgId)
    {
        $llg = $this->findLlgOrFail($llgId);

        $rules = [
            'name' => 'required|max_length[255]',
            'code' => 'required|max_length[20]|is_unique[gov_structure.code]',
            'map_center' => 'permit_empty|max_length[100]',
            'map_zoom' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
             if ($this->request->isAJAX()) {
                 return $this->failValidationErrors($this->validator->getErrors());
             }
             session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
             return redirect()->back()->withInput();
        }

        $data = [
            'parent_id'  => $llgId,
            'json_id'    => $this->request->getPost('code'), // Using code as json_id since no JSON file
            'level'      => 'ward',
            'code'       => $this->request->getPost('code'),
            'name'       => $this->request->getPost('name'),
            'flag_filepath' => '',
            'map_center' => $this->request->getPost('map_center'),
            'map_zoom'   => $this->request->getPost('map_zoom'),
            'created_by' => session()->get('user_id') ?? 'system',
            'updated_by' => session()->get('user_id') ?? 'system',
        ];

        $wardId = $this->govStructureModel->insert($data);

        if (!$wardId) {
             if ($this->request->isAJAX()) {
                 return $this->failServerError('Failed to create ward');
             }
             session()->setFlashdata('error', 'Failed to create ward');
        } else {
             if ($this->request->isAJAX()) {
                 return $this->respondCreated([
                     'id' => $wardId,
                     'message' => 'Ward created successfully',
                     'ward' => $this->govStructureModel->find($wardId)
                 ]);
             }
             session()->setFlashdata('success', 'Ward created successfully');
        }

        return redirect()->to(base_url('admin/gov-structure/llgs/' . $llgId . '/wards'));
    }

    /**
     * Show the form for editing a specific ward.
     * Corresponds to GET /admin/wards/(:num)/edit
     *
     * @param int $id Ward ID
     * @return mixed
     */
    public function wardEdit($id)
    {
        $ward = $this->findWardOrFail($id);
        $llg = $this->findLlgOrFail($ward['parent_id']);
        $district = $this->findDistrictOrFail($llg['parent_id']);
        $province = $this->findProvinceOrFail($district['parent_id']);

        // Assuming a view exists or it's handled by a modal in wardIndex view
         if ($this->request->isAJAX()) {
             return $this->respond(['ward' => $ward, 'llg' => $llg, 'district' => $district, 'province' => $province]);
         }
         session()->setFlashdata('info', 'Ward editing is handled via the list view modal/form.');
         return redirect()->to(base_url('admin/gov-structure/llgs/' . $ward['parent_id'] . '/wards'));
    }

    /**
     * Process the update of a specific ward.
     * Corresponds to PUT or POST /admin/wards/(:num)
     *
     * @param int $id Ward ID
     * @return mixed
     */
    public function wardUpdate($id)
    {
        $ward = $this->findWardOrFail($id);
        $llgId = $ward['parent_id'];

        $rules = [
            'name' => 'required|max_length[255]',
            'code' => "required|max_length[20]|is_unique[gov_structure.code,id,{$id}]",
            'map_center' => 'permit_empty|max_length[100]',
            'map_zoom' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
             if ($this->request->isAJAX()) {
                 return $this->failValidationErrors($this->validator->getErrors());
             }
             session()->setFlashdata('error', implode('<br>', $this->validator->getErrors()));
             return redirect()->back()->withInput();
        }

        $data = [
            'code'       => $this->request->getVar('code'),
            'name'       => $this->request->getVar('name'),
            'map_center' => $this->request->getVar('map_center'),
            'map_zoom'   => $this->request->getVar('map_zoom'),
            'updated_by' => session()->get('user_id') ?? 'system',
        ];

        if (!$this->govStructureModel->update($id, $data)) {
             if ($this->request->isAJAX()) {
                 log_message('error', 'Failed to update ward ID ' . $id . ': ' . json_encode($this->govStructureModel->errors()));
                 return $this->failServerError('Failed to update ward');
             }
             session()->setFlashdata('error', 'Failed to update ward');
        } else {
             if ($this->request->isAJAX()) {
                 return $this->respondUpdated([
                     'id' => $id,
                     'message' => 'Ward updated successfully',
                     'ward' => $this->govStructureModel->find($id)
                 ]);
             }
             session()->setFlashdata('success', 'Ward updated successfully');
        }

        return redirect()->to(base_url('admin/gov-structure/llgs/' . $llgId . '/wards'));
    }

    /**
     * Delete a specific ward.
     * Corresponds to DELETE /admin/wards/(:num)
     *
     * @param int $id Ward ID
     * @return mixed
     */
    public function wardDelete($id)
    {
        $ward = $this->findWardOrFail($id);
        $llgId = $ward['parent_id'];

        if (!$this->govStructureModel->delete($id)) {
             if ($this->request->isAJAX()) {
                 log_message('error', 'Failed to delete ward ID ' . $id . ': ' . json_encode($this->govStructureModel->errors()));
                 return $this->failServerError('Failed to delete ward');
             }
             session()->setFlashdata('error', 'Failed to delete ward');
        } else {
             if ($this->request->isAJAX()) {
                 return $this->respondDeleted([
                     'id' => $id,
                     'message' => 'Ward deleted successfully'
                 ]);
             }
             session()->setFlashdata('success', 'Ward deleted successfully');
        }

        return redirect()->to(base_url('admin/gov-structure/llgs/' . $llgId . '/wards'));
    }


    //--------------------------------------------------------------------
    // CSV Import Methods
    //--------------------------------------------------------------------

    /**
     * Download CSV template for provinces
     */
    public function downloadProvinceTemplate()
    {
        $filename = 'provinces_template.csv';
        $filepath = FCPATH . 'assets/csv_templates/' . $filename;

        // Create directory if it doesn't exist
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        // Create CSV content
        $csvContent = "code,name\n";
        $csvContent .= "PROV001,Sample Province Name\n";

        // Write to file
        file_put_contents($filepath, $csvContent);

        // Force download
        return $this->response->download($filepath, null)->setFileName($filename);
    }

    /**
     * Import provinces from CSV - POST method only
     * Corresponds to POST /admin/gov-structure/provinces/csv-import
     */
    public function importProvinces()
    {
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
        if (!in_array('code', $header) || !in_array('name', $header)) {
            return redirect()->back()->with('error', 'CSV must contain "code" and "name" columns');
        }

        $codeIndex = array_search('code', $header);
        $nameIndex = array_search('name', $header);

        $imported = 0;
        $errors = [];

        foreach ($csvData as $rowIndex => $row) {
            if (empty($row[$codeIndex]) || empty($row[$nameIndex])) {
                $errors[] = "Row " . ($rowIndex + 2) . ": Code and name are required";
                continue;
            }

            $data = [
                'parent_id' => 0,
                'json_id' => $row[$codeIndex],
                'level' => 'province',
                'code' => $row[$codeIndex],
                'name' => $row[$nameIndex],
                'flag_filepath' => '',
                'map_center' => '',
                'map_zoom' => '',
                'created_by' => session()->get('user_id') ?? 'system',
                'updated_by' => session()->get('user_id') ?? 'system',
            ];

            if ($this->govStructureModel->insert($data)) {
                $imported++;
            } else {
                $errors[] = "Row " . ($rowIndex + 2) . ": Failed to import " . $row[$nameIndex];
            }
        }

        $message = "Imported {$imported} provinces successfully";
        if (!empty($errors)) {
            $message .= ". Errors: " . implode(', ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $message .= " and " . (count($errors) - 5) . " more errors";
            }
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Download CSV template for districts
     */
    public function downloadDistrictTemplate($provinceId)
    {
        $province = $this->findProvinceOrFail($provinceId);

        $filename = 'districts_template_' . $province['code'] . '.csv';
        $filepath = FCPATH . 'assets/csv_templates/' . $filename;

        // Create directory if it doesn't exist
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        // Create CSV content
        $csvContent = "code,name\n";
        $csvContent .= "DIST001,Sample District Name\n";

        // Write to file
        file_put_contents($filepath, $csvContent);

        // Force download
        return $this->response->download($filepath, null)->setFileName($filename);
    }

    /**
     * Import districts from CSV - POST method only
     * Corresponds to POST /admin/gov-structure/provinces/(:num)/districts/csv-import
     */
    public function importDistricts($provinceId)
    {
        $province = $this->findProvinceOrFail($provinceId);

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
        if (!in_array('code', $header) || !in_array('name', $header)) {
            return redirect()->back()->with('error', 'CSV must contain "code" and "name" columns');
        }

        $codeIndex = array_search('code', $header);
        $nameIndex = array_search('name', $header);

        $imported = 0;
        $errors = [];

        foreach ($csvData as $rowIndex => $row) {
            if (empty($row[$codeIndex]) || empty($row[$nameIndex])) {
                $errors[] = "Row " . ($rowIndex + 2) . ": Code and name are required";
                continue;
            }

            $data = [
                'parent_id' => $provinceId,
                'json_id' => $row[$codeIndex],
                'level' => 'district',
                'code' => $row[$codeIndex],
                'name' => $row[$nameIndex],
                'flag_filepath' => '',
                'map_center' => '',
                'map_zoom' => '',
                'created_by' => session()->get('user_id') ?? 'system',
                'updated_by' => session()->get('user_id') ?? 'system',
            ];

            if ($this->govStructureModel->insert($data)) {
                $imported++;
            } else {
                $errors[] = "Row " . ($rowIndex + 2) . ": Failed to import " . $row[$nameIndex];
            }
        }

        $message = "Imported {$imported} districts successfully";
        if (!empty($errors)) {
            $message .= ". Errors: " . implode(', ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $message .= " and " . (count($errors) - 5) . " more errors";
            }
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Download CSV template for LLGs
     */
    public function downloadLlgTemplate($districtId)
    {
        $district = $this->findDistrictOrFail($districtId);
        $province = $this->findProvinceOrFail($district['parent_id']);

        $filename = 'llgs_template_' . $district['code'] . '.csv';
        $filepath = FCPATH . 'assets/csv_templates/' . $filename;

        // Create directory if it doesn't exist
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        // Create CSV content
        $csvContent = "code,name\n";
        $csvContent .= "LLG001,Sample LLG Name\n";

        // Write to file
        file_put_contents($filepath, $csvContent);

        // Force download
        return $this->response->download($filepath, null)->setFileName($filename);
    }

    /**
     * Import LLGs from CSV - POST method only
     * Corresponds to POST /admin/gov-structure/districts/(:num)/llgs/csv-import
     */
    public function importLlgs($districtId)
    {
        $district = $this->findDistrictOrFail($districtId);

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
        if (!in_array('code', $header) || !in_array('name', $header)) {
            return redirect()->back()->with('error', 'CSV must contain "code" and "name" columns');
        }

        $codeIndex = array_search('code', $header);
        $nameIndex = array_search('name', $header);

        $imported = 0;
        $errors = [];

        foreach ($csvData as $rowIndex => $row) {
            if (empty($row[$codeIndex]) || empty($row[$nameIndex])) {
                $errors[] = "Row " . ($rowIndex + 2) . ": Code and name are required";
                continue;
            }

            $data = [
                'parent_id' => $districtId,
                'json_id' => $row[$codeIndex],
                'level' => 'llg',
                'code' => $row[$codeIndex],
                'name' => $row[$nameIndex],
                'flag_filepath' => '',
                'map_center' => '',
                'map_zoom' => '',
                'created_by' => session()->get('user_id') ?? 'system',
                'updated_by' => session()->get('user_id') ?? 'system',
            ];

            if ($this->govStructureModel->insert($data)) {
                $imported++;
            } else {
                $errors[] = "Row " . ($rowIndex + 2) . ": Failed to import " . $row[$nameIndex];
            }
        }

        $message = "Imported {$imported} LLGs successfully";
        if (!empty($errors)) {
            $message .= ". Errors: " . implode(', ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $message .= " and " . (count($errors) - 5) . " more errors";
            }
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Download CSV template for wards
     */
    public function downloadWardTemplate($llgId)
    {
        $llg = $this->findLlgOrFail($llgId);
        $district = $this->findDistrictOrFail($llg['parent_id']);

        $filename = 'wards_template_' . $llg['code'] . '.csv';
        $filepath = FCPATH . 'assets/csv_templates/' . $filename;

        // Create directory if it doesn't exist
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        // Create CSV content
        $csvContent = "code,name\n";
        $csvContent .= "WARD001,Sample Ward Name\n";

        // Write to file
        file_put_contents($filepath, $csvContent);

        // Force download
        return $this->response->download($filepath, null)->setFileName($filename);
    }

    /**
     * Import wards from CSV - POST method only
     * Corresponds to POST /admin/gov-structure/llgs/(:num)/wards/csv-import
     */
    public function importWards($llgId)
    {
        $llg = $this->findLlgOrFail($llgId);

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
        if (!in_array('code', $header) || !in_array('name', $header)) {
            return redirect()->back()->with('error', 'CSV must contain "code" and "name" columns');
        }

        $codeIndex = array_search('code', $header);
        $nameIndex = array_search('name', $header);

        $imported = 0;
        $errors = [];

        foreach ($csvData as $rowIndex => $row) {
            if (empty($row[$codeIndex]) || empty($row[$nameIndex])) {
                $errors[] = "Row " . ($rowIndex + 2) . ": Code and name are required";
                continue;
            }

            $data = [
                'parent_id' => $llgId,
                'json_id' => $row[$codeIndex],
                'level' => 'ward',
                'code' => $row[$codeIndex],
                'name' => $row[$nameIndex],
                'flag_filepath' => '',
                'map_center' => '',
                'map_zoom' => '',
                'created_by' => session()->get('user_id') ?? 'system',
                'updated_by' => session()->get('user_id') ?? 'system',
            ];

            if ($this->govStructureModel->insert($data)) {
                $imported++;
            } else {
                $errors[] = "Row " . ($rowIndex + 2) . ": Failed to import " . $row[$nameIndex];
            }
        }

        $message = "Imported {$imported} wards successfully";
        if (!empty($errors)) {
            $message .= ". Errors: " . implode(', ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $message .= " and " . (count($errors) - 5) . " more errors";
            }
        }

        return redirect()->back()->with('success', $message);
    }

    //--------------------------------------------------------------------
    // Helper Methods
    //--------------------------------------------------------------------

    /**
     * Find a province by ID or throw a 404 error.
     *
     * @param int $id Province ID
     * @return array Province data
     * @throws PageNotFoundException
     */
    protected function findProvinceOrFail(int $id): array
    {
        $province = $this->govStructureModel->find($id);
        if (!$province || $province['level'] !== 'province') {
            throw PageNotFoundException::forPageNotFound('Province not found with ID: ' . $id);
        }
        return $province;
    }

    /**
     * Find a district by ID or throw a 404 error.
     *
     * @param int $id District ID
     * @return array District data
     * @throws PageNotFoundException
     */
    protected function findDistrictOrFail(int $id): array
    {
        $district = $this->govStructureModel->find($id);
        if (!$district || $district['level'] !== 'district') {
            throw PageNotFoundException::forPageNotFound('District not found with ID: ' . $id);
        }
        return $district;
    }

    /**
     * Find an LLG by ID or throw a 404 error.
     *
     * @param int $id LLG ID
     * @return array LLG data
     * @throws PageNotFoundException
     */
    protected function findLlgOrFail(int $id): array
    {
        $llg = $this->govStructureModel->find($id);
        if (!$llg || $llg['level'] !== 'llg') {
            throw PageNotFoundException::forPageNotFound('LLG not found with ID: ' . $id);
        }
        return $llg;
    }

    /**
     * Find a ward by ID or throw a 404 error.
     *
     * @param int $id Ward ID
     * @return array Ward data
     * @throws PageNotFoundException
     */
    protected function findWardOrFail(int $id): array
    {
        $ward = $this->govStructureModel->find($id);
        if (!$ward || $ward['level'] !== 'ward') {
            throw PageNotFoundException::forPageNotFound('Ward not found with ID: ' . $id);
        }
        return $ward;
    }
}
