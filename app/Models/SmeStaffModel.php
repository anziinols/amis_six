<?php
// app/Models/SmeStaffModel.php

namespace App\Models;

use CodeIgniter\Model;

class SmeStaffModel extends Model
{
    protected $table            = 'sme_staff';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'sme_id', 'fname', 'lname', 'gender', 'dobirth', 'designation', 'contacts',
        'remarks', 'id_photo_path',
        'status', 'status_at', 'status_by', 'status_remarks',
        'created_by', 'updated_by', 'deleted_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'sme_id'         => 'required|integer',
        'fname'          => 'required|max_length[100]',
        'lname'          => 'required|max_length[100]',
        'gender'         => 'required|in_list[male,female,other]',
        'dobirth'        => 'permit_empty|valid_date',
        'designation'    => 'permit_empty|max_length[100]',
        'contacts'       => 'permit_empty',
        'remarks'        => 'permit_empty',
        'id_photo_path'  => 'permit_empty|max_length[255]',
        'status'         => 'permit_empty|max_length[50]',
        'created_by'     => 'permit_empty|integer'
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['setDefaultStatus'];
    protected $afterInsert  = [];
    protected $beforeUpdate = [];
    protected $afterUpdate  = [];
    protected $beforeDelete = [];
    protected $afterDelete  = [];

    /**
     * Set default status for new SME staff
     */
    protected function setDefaultStatus(array $data)
    {
        if (!isset($data['data']['status'])) {
            $data['data']['status'] = 'active';
            $data['data']['status_at'] = date('Y-m-d H:i:s');
            $data['data']['status_by'] = session()->get('user_id') ?? 1;
        }

        return $data;
    }

    /**
     * Get staff with SME details
     *
     * @param int|null $id Staff ID (optional)
     * @return array
     */
    public function getStaffWithSme($id = null)
    {
        $builder = $this->db->table($this->table . ' as staff');
        $builder->select(
            'staff.*',
            'sme.sme_name',
            'sme.village_name',
            'p.name as province_name',
            'd.name as district_name',
            'l.name as llg_name'
        );
        $builder->join('sme', 'sme.id = staff.sme_id', 'left');
        $builder->join('gov_structure as p', 'p.id = sme.province_id', 'left');
        $builder->join('gov_structure as d', 'd.id = sme.district_id', 'left');
        $builder->join('gov_structure as l', 'l.id = sme.llg_id', 'left');

        if ($id !== null) {
            return $builder->where('staff.id', $id)->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get staff by SME ID
     *
     * @param int $smeId SME ID
     * @return array
     */
    public function getStaffBySme($smeId)
    {
        return $this->where('sme_id', $smeId)->findAll();
    }

    /**
     * Toggle status of a staff member
     *
     * @param int $id Staff ID
     * @param int $userId User ID making the change
     * @param string $remarks Status change remarks (optional)
     * @return bool
     */
    public function toggleStatus($id, $userId, $remarks = '')
    {
        $staff = $this->find($id);
        if (!$staff) {
            return false;
        }

        $newStatus = ($staff['status'] == 'active') ? 'inactive' : 'active';

        $data = [
            'status' => $newStatus,
            'status_by' => $userId,
            'status_at' => date('Y-m-d H:i:s')
        ];

        if (!empty($remarks)) {
            $data['status_remarks'] = $remarks;
        }

        return $this->update($id, $data);
    }

    /**
     * Get full name of staff member
     *
     * @param array $staff Staff data
     * @return string
     */
    public function getFullName($staff)
    {
        return $staff['fname'] . ' ' . $staff['lname'];
    }
}
