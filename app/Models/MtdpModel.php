<?php
// app/Models/MtdpModel.php

namespace App\Models;

use CodeIgniter\Model;

class MtdpModel extends Model
{
    protected $table            = 'plans_mtdp';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'abbrev', 'title', 'date_from', 'date_to', 'remarks',
        'mtdp_status', 'mtdp_status_by', 'mtdp_status_at', 'mtdp_status_remarks',
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
        'abbrev'     => 'required|max_length[20]',
        'title'      => 'required|max_length[255]',
        'mtdp_status'     => 'required|integer',
        'mtdp_status_by'  => 'required|integer',
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
    
    /**
     * Get all MTDP plans
     */
    public function getPlans()
    {
        return $this->findAll();
    }
    
    /**
     * Get MTDP plan by ID
     */
    public function getPlan($id)
    {
        return $this->find($id);
    }
    
    /**
     * Create a new MTDP plan
     */
    public function createPlan($data)
    {
        // Set default status to active (1)
        if (!isset($data['mtdp_status'])) {
            $data['mtdp_status'] = 1;
        }
        
        return $this->insert($data);
    }
    
    /**
     * Update an existing MTDP plan
     */
    public function updatePlan($id, $data)
    {
        return $this->update($id, $data);
    }
    
    /**
     * Toggle status of an MTDP plan
     */
    public function toggleStatus($id, $userId, $additionalData = [])
    {
        $plan = $this->find($id);
        if (!$plan) {
            return false;
        }
        
        $newStatus = ($plan['mtdp_status'] == 1) ? 0 : 1;
        
        $data = [
            'mtdp_status' => $newStatus,
            'mtdp_status_by' => $userId,
            'mtdp_status_at' => date('Y-m-d H:i:s')
        ];
        
        // Merge any additional data passed to the method
        if (!empty($additionalData)) {
            $data = array_merge($data, $additionalData);
        }
        
        return $this->update($id, $data);
    }
}