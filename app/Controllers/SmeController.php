<?php
// app/Controllers/SmeController.php

namespace App\Controllers;

use App\Models\SmeModel;
use App\Models\SmeStaffModel;
use App\Models\GovStructureModel;
use CodeIgniter\HTTP\ResponseInterface;

class SmeController extends BaseController
{
    protected SmeModel $smeModel;
    protected SmeStaffModel $staffModel;
    protected GovStructureModel $govModel;

    public function __construct()
    {
        $this->smeModel   = new SmeModel();
        $this->staffModel = new SmeStaffModel();
        $this->govModel   = new GovStructureModel();
    }

    /* ----------------------------------------------------------------
     | SME CRUD
     *----------------------------------------------------------------*/
    public function index(): string
    {
        $data = [
            'title' => 'SME List',
            'smes'  => $this->smeModel->getSmeWithLocation(),
        ];
        return view('sme/sme_index', $data);
    }

    public function show(int $id): string|ResponseInterface
    {
        $sme = $this->smeModel->getSmeWithLocation($id);
        if (!$sme) {
            return redirect()->back()->with('error', 'SME not found.');
        }
        return view('sme/sme_show', ['title' => 'View SME', 'sme' => $sme]);
    }

    public function new(): string
    {
        $data = [
            'title'     => 'Create SME',
            'provinces' => $this->govModel->getByLevel('province')
        ];
        return view('sme/sme_create', $data);
    }

    public function create(): ResponseInterface
    {
        $data = $this->request->getPost();

        // Handle logo upload if a file was submitted
        $logo = $this->request->getFile('logo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            // Create upload directory if it doesn't exist
            $uploadPath = ROOTPATH . 'public/uploads/sme_logos';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Generate a new name for the file to avoid conflicts
            $newName = $logo->getRandomName();

            // Move the file to the upload directory
            if ($logo->move($uploadPath, $newName)) {
                // Add the file path to the data array (store relative path for web access)
                $data['logo_filepath'] = 'public/uploads/sme_logos/' . $newName;
            } else {
                // If file upload failed, set error message
                return redirect()->back()->withInput()->with('error', 'Failed to upload logo: ' . $logo->getErrorString());
            }
        }

        if (!$this->smeModel->save($data)) {
            return redirect()->back()->withInput()->with('error', $this->smeModel->errors());
        }

        return redirect()->to(base_url('smes'))->with('success', 'SME created successfully.');
    }

    public function edit(int $id): string|ResponseInterface
    {
        $sme = $this->smeModel->find($id);
        if (!$sme) {
            return redirect()->back()->with('error', 'SME not found.');
        }

        // Get provinces for dropdown
        $provinces = $this->govModel->getByLevel('province');

        // Get districts for the selected province
        $districts = [];
        if (!empty($sme['province_id'])) {
            $districts = $this->govModel->getByParent($sme['province_id']);
        }

        // Get LLGs for the selected district
        $llgs = [];
        if (!empty($sme['district_id'])) {
            $llgs = $this->govModel->getByParent($sme['district_id']);
        }

        return view('sme/sme_edit', [
            'title' => 'Edit SME',
            'sme' => $sme,
            'provinces' => $provinces,
            'districts' => $districts,
            'llgs' => $llgs
        ]);
    }

    public function update(int $id): ResponseInterface
    {
        $data = $this->request->getPost();

        // Handle logo upload if a file was submitted
        $logo = $this->request->getFile('logo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            // Create upload directory if it doesn't exist
            $uploadPath = ROOTPATH . 'public/uploads/sme_logos';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Generate a new name for the file to avoid conflicts
            $newName = $logo->getRandomName();

            // Move the file to the upload directory
            if ($logo->move($uploadPath, $newName)) {
                // Add the file path to the data array (store relative path for web access)
                $data['logo_filepath'] = 'public/uploads/sme_logos/' . $newName;

                // Delete old logo if it exists
                $sme = $this->smeModel->find($id);
                $oldFilePath = ROOTPATH . $sme['logo_filepath'];
                if (!empty($sme['logo_filepath']) && file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            } else {
                // If file upload failed, set error message
                return redirect()->back()->withInput()->with('error', 'Failed to upload logo: ' . $logo->getErrorString());
            }
        }

        if (!$this->smeModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('error', $this->smeModel->errors());
        }

        return redirect()->to(base_url('smes/' . $id))->with('success', 'SME updated successfully.');
    }

    public function delete(int $id): ResponseInterface
    {
        $this->smeModel->delete($id);
        return redirect()->to(base_url('smes'))->with('success', 'SME deleted successfully.');
    }

    // Toggle status modal submission
    public function toggleStatus(int $id): ResponseInterface
    {
        $remarks = $this->request->getPost('status_remarks');
        $userId  = session()->get('user_id') ?? 1;
        $this->smeModel->toggleStatus($id, $userId, $remarks);
        return redirect()->to(base_url('smes'))->with('success', 'Status updated successfully.');
    }

    /* ----------------------------------------------------------------
     | SME STAFF CRUD (nested in SME)
     *----------------------------------------------------------------*/
    public function staff_index(int $smeId): string
    {
        $sme   = $this->smeModel->find($smeId);
        $staff = $this->staffModel->getStaffBySme($smeId);
        return view('sme/sme_staff_index', [
            'title' => 'SME Staff',
            'sme'   => $sme,
            'staff' => $staff,
        ]);
    }

    public function staff_new(int $smeId): string
    {
        return view('sme/sme_staff_create', [
            'title'  => 'Add SME Staff',
            'sme_id' => $smeId,
        ]);
    }

    public function staff_create(int $smeId): ResponseInterface
    {
        $data = $this->request->getPost();
        $data['sme_id'] = $smeId;

        // Handle ID photo upload if a file was submitted
        $idPhoto = $this->request->getFile('id_photo');
        if ($idPhoto && $idPhoto->isValid() && !$idPhoto->hasMoved()) {
            // Create upload directory if it doesn't exist
            $uploadPath = ROOTPATH . 'public/uploads/sme_staff_photos';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Generate a new name for the file to avoid conflicts
            $newName = $idPhoto->getRandomName();

            // Move the file to the upload directory
            if ($idPhoto->move($uploadPath, $newName)) {
                // Add the file path to the data array (store relative path for web access)
                $data['id_photo_path'] = 'public/uploads/sme_staff_photos/' . $newName;
            } else {
                // If file upload failed, set error message
                return redirect()->back()->withInput()->with('error', 'Failed to upload ID photo: ' . $idPhoto->getErrorString());
            }
        }

        // Set default status
        $data['status'] = 'active';
        $data['status_at'] = date('Y-m-d H:i:s');
        $data['status_by'] = session()->get('user_id') ?? 1;

        if (!$this->staffModel->save($data)) {
            return redirect()->back()->withInput()->with('error', $this->staffModel->errors());
        }

        return redirect()->to(base_url("smes/staff/$smeId"))->with('success', 'Staff member added successfully.');
    }

    public function staff_edit(int $smeId, int $staffId): string|ResponseInterface
    {
        $staff = $this->staffModel->find($staffId);
        if (!$staff) {
            return redirect()->back()->with('error', 'Staff not found.');
        }
        return view('sme/sme_staff_edit', [
            'title'  => 'Edit Staff',
            'sme_id' => $smeId,
            'staff'  => $staff,
        ]);
    }

    public function staff_update(int $smeId, int $staffId): ResponseInterface
    {
        $data = $this->request->getPost();

        // Handle ID photo upload if a file was submitted
        $idPhoto = $this->request->getFile('id_photo');
        if ($idPhoto && $idPhoto->isValid() && !$idPhoto->hasMoved()) {
            // Create upload directory if it doesn't exist
            $uploadPath = ROOTPATH . 'public/uploads/sme_staff_photos';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Generate a new name for the file to avoid conflicts
            $newName = $idPhoto->getRandomName();

            // Move the file to the upload directory
            if ($idPhoto->move($uploadPath, $newName)) {
                // Add the file path to the data array (store relative path for web access)
                $data['id_photo_path'] = 'public/uploads/sme_staff_photos/' . $newName;

                // Delete old photo if it exists
                $staff = $this->staffModel->find($staffId);
                $oldFilePath = ROOTPATH . $staff['id_photo_path'];
                if (!empty($staff['id_photo_path']) && file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            } else {
                // If file upload failed, set error message
                return redirect()->back()->withInput()->with('error', 'Failed to upload ID photo: ' . $idPhoto->getErrorString());
            }
        }

        if (!$this->staffModel->update($staffId, $data)) {
            return redirect()->back()->withInput()->with('error', $this->staffModel->errors());
        }

        return redirect()->to(base_url("smes/staff/$smeId"))->with('success', 'Staff member updated successfully.');
    }

    public function staff_delete(int $smeId, int $staffId): ResponseInterface
    {
        $this->staffModel->delete($staffId);
        return redirect()->to(base_url("smes/staff/$smeId"))->with('success', 'Staff deleted successfully.');
    }

    /**
     * Get districts by province ID (for AJAX)
     */
    public function getDistricts(int $provinceId): ResponseInterface
    {
        $districts = $this->govModel->getByParent($provinceId);
        return $this->response->setJSON($districts);
    }

    /**
     * Get LLGs by district ID (for AJAX)
     */
    public function getLlgs(int $districtId): ResponseInterface
    {
        $llgs = $this->govModel->getByParent($districtId);
        return $this->response->setJSON($llgs);
    }
}