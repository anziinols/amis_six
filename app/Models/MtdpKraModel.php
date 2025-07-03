<?php
// app/Models/MtdpKraModel.php

namespace App\Models;

use CodeIgniter\Model;

class MtdpKraModel extends Model
{
    protected $table            = 'plans_mtdp_kra';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'mtdp_id', 'spa_id', 'dip_id', 'sa_id', 'investment_id',
        'kpi', 
        'year_one', 'year_two', 'year_three', 'year_four', 'year_five',
        'responsible_agencies',
        'kra_status', 'kra_status_by', 'kra_status_at', 'kra_status_remarks',
        'created_by', 'updated_by', 'deleted_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
    // Validation
    protected $validationRules      = [
        'mtdp_id'      => 'required|integer',
        'spa_id'       => 'required|integer',
        'dip_id'       => 'required|integer',
        'kpi'          => 'required',
        'kra_status'   => 'required|integer',
        'kra_status_by' => 'required|integer',
    ];
    
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
    
    // Callbacks
    protected $beforeInsert = [];
    protected $afterInsert  = [];
    protected $beforeUpdate = [];
    protected $afterUpdate  = [];
    protected $beforeDelete = [];
    protected $afterDelete  = [];
    
    /**
     * Get KRAs with related data
     */
    public function getKras($id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select($this->table.'.*', 'plans_mtdp_dip.dip_title', 'plans_mtdp_spa.title as spa_title', 'plans_mtdp.title as mtdp_title');
        $builder->join('plans_mtdp_dip', 'plans_mtdp_dip.id = '.$this->table.'.dip_id', 'left');
        $builder->join('plans_mtdp_spa', 'plans_mtdp_spa.id = '.$this->table.'.spa_id', 'left');
        $builder->join('plans_mtdp', 'plans_mtdp.id = '.$this->table.'.mtdp_id', 'left');
        
        if ($id !== null) {
            return $builder->where($this->table.'.id', $id)->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get KRAs by DIP ID
     */
    public function getKrasByDipId($dipId)
    {
        return $this->where('dip_id', $dipId)->findAll();
    }
    
    /**
     * Create a new KRA
     */
    public function createKra(array $data)
    {
        // Default status and timestamp
        $data['kra_status']    = $data['kra_status'] ?? 1;
        $data['kra_status_at'] = date('Y-m-d H:i:s');

        // Insert using CI4's auto-model (will protect allowedFields automatically)
        return $this->insert($data);
    }
    
    /**
     * Update an existing KRA
     */
    public function updateKra(int $id, array $data)
    {
        try {
            // Update using CI4's model
            $success = $this->update($id, $data);
            
            if (!$success) {
                log_message('error', 'Failed to update KRA: ' . print_r($this->errors(), true));
                return false;
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Exception updating KRA: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Toggle the status of a KRA
     */
    public function toggleStatus($id, $statusData)
    {
        $kra = $this->find($id);
        
        if ($kra) {
            // Toggle the status
            $newStatus = ($kra['kra_status'] == 1) ? 0 : 1;
            
            $data = [
                'kra_status' => $newStatus,
                'kra_status_by' => $statusData['kra_status_by'],
                'kra_status_at' => date('Y-m-d H:i:s'),
                'kra_status_remarks' => $statusData['kra_status_remarks'] ?? ''
            ];
            
            return $this->update($id, $data);
        }
        
        return false;
    }
}
