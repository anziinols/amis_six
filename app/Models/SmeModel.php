<?php
// app/Models/SmeModel.php

namespace App\Models;

use CodeIgniter\Model;

class SmeModel extends Model
{
    protected $table            = 'sme';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'province_id', 'district_id', 'llg_id', 'village_name', 'sme_name',
        'description', 'gps_coordinates', 'contact_details', 'logo_filepath',
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
        'province_id'      => 'required|integer',
        'district_id'      => 'required|integer',
        'llg_id'           => 'required|integer',
        'sme_name'         => 'required|max_length[255]',
        'village_name'     => 'permit_empty|max_length[255]',
        'description'      => 'permit_empty',
        'gps_coordinates'  => 'permit_empty|max_length[100]',
        'contact_details'  => 'permit_empty',
        'logo_filepath'    => 'permit_empty|max_length[255]',
        'status'           => 'permit_empty|max_length[50]',
        'created_by'       => 'permit_empty|integer'
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
     * Set default status for new SMEs
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
     * Get SME with location details
     * 
     * @param int|null $id SME ID (optional)
     * @return array
     */
    public function getSmeWithLocation($id = null)
    {
        $builder = $this->db->table($this->table . ' as s');
        $builder->select([
            's.*',
            'p.name as province_name',
            'd.name as district_name',
            'l.name as llg_name'
        ]);
        $builder->join('gov_structure as p', 'p.id = s.province_id', 'left');
        $builder->join('gov_structure as d', 'd.id = s.district_id', 'left');
        $builder->join('gov_structure as l', 'l.id = s.llg_id', 'left');
        
        // Add where clause for non-deleted records
        $builder->where('s.deleted_at IS NULL');
        
        if ($id !== null) {
            $builder->where('s.id', $id);
            return $builder->get()->getRowArray() ?? [];
        }
        
        return $builder->get()->getResultArray() ?? [];
    }
    
    /**
     * Get SMEs by location
     * 
     * @param string $level Level (province, district, llg)
     * @param int $locationId Location ID
     * @return array
     */
    public function getSmesByLocation($level, $locationId)
    {
        $field = $level . '_id';
        return $this->where($field, $locationId)->findAll();
    }
    
    /**
     * Toggle status of an SME
     * 
     * @param int $id SME ID
     * @param int $userId User ID making the change
     * @param string $remarks Status change remarks (optional)
     * @return bool
     */
    public function toggleStatus($id, $userId, $remarks = '')
    {
        $sme = $this->find($id);
        if (!$sme) {
            return false;
        }
        
        $newStatus = ($sme['status'] == 'active') ? 'inactive' : 'active';
        
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
}
