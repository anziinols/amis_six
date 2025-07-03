<?php
// app/Models/MtdpSpaModel.php

namespace App\Models;

use CodeIgniter\Model;

class MtdpSpaModel extends Model
{
    protected $table            = 'plans_mtdp_spa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'mtdp_id', 'code', 'title', 'remarks',
        'spa_status', 'spa_status_by', 'spa_status_at', 'spa_status_remarks',
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
        'mtdp_id'    => 'required|integer',
        'code'       => 'required|max_length[20]',
        'title'      => 'required|max_length[255]',
        'spa_status'     => 'required|integer',
        'spa_status_by'  => 'required|integer',
        'created_by' => 'required|max_length[255]',
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
    
    // Get SPA with its parent MTDP
    public function getSpaWithMtdp($id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select($this->table.'.*', 'plans_mtdp.title as mtdp_title', 'plans_mtdp.abbrev as mtdp_abbrev');
        $builder->join('plans_mtdp', 'plans_mtdp.id = '.$this->table.'.mtdp_id', 'left');
        
        if ($id !== null) {
            return $builder->where($this->table.'.id', $id)->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get all SPAs for a specific MTDP plan
     */
    public function getSpasByMtdpId($mtdpId)
    {
        return $this->where('mtdp_id', $mtdpId)->findAll();
    }
    
    /**
     * Create a new SPA
     */
    public function createSpa($data)
    {
        // Set default status to active (1)
        if (!isset($data['spa_status'])) {
            $data['spa_status'] = 1;
        }
        
        // Set status metadata
        $data['spa_status_at'] = date('Y-m-d H:i:s');
        
        return $this->insert($data);
    }
    
    /**
     * Update an existing SPA
     */
    public function updateSpa($id, $data)
    {
        return $this->update($id, $data);
    }
    
    /**
     * Toggle the status of a SPA
     */
    public function toggleStatus($id, $statusData)
    {
        $spa = $this->find($id);
        
        if ($spa) {
            // Toggle the status
            $newStatus = ($spa['spa_status'] == 1) ? 0 : 1;
            
            $data = [
                'spa_status' => $newStatus,
                'spa_status_by' => $statusData['spa_status_by'],
                'spa_status_at' => date('Y-m-d H:i:s'),
                'spa_status_remarks' => $statusData['spa_status_remarks'] ?? ''
            ];
            
            return $this->update($id, $data);
        }
        
        return false;
    }
}