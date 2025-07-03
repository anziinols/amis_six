<?php
// app/Models/MtdpStrategiesModel.php

namespace App\Models;

use CodeIgniter\Model;

class MtdpStrategiesModel extends Model
{
    protected $table            = 'plans_mtdp_strategies';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'mtdp_id', 'spa_id', 'dip_id', 'sa_id', 'investment_id', 'kra_id',
        'strategy', 'policy_reference',
        'strategies_status', 'strategies_status_by', 'strategies_status_at', 'strategies_status_remarks',
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
        'strategy'     => 'required',
        'strategies_status' => 'required|integer',
        'strategies_status_by' => 'required|integer',
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
     * Get Strategies with related data
     */
    public function getStrategies($id = null)
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
     * Get Strategies by DIP ID
     */
    public function getStrategiesByDipId($dipId)
    {
        return $this->where('dip_id', $dipId)->findAll();
    }
    
    /**
     * Get Strategies by KRA ID
     */
    public function getStrategiesByKraId($kraId)
    {
        return $this->where('kra_id', $kraId)->findAll();
    }
    
    /**
     * Create a new Strategy
     */
    public function createStrategy(array $data)
    {
        // Default status and timestamp
        $data['strategies_status']    = $data['strategies_status'] ?? 1;
        $data['strategies_status_at'] = date('Y-m-d H:i:s');

        // Insert using CI4's auto-model (will protect allowedFields automatically)
        return $this->insert($data);
    }
    
    /**
     * Update an existing Strategy
     */
    public function updateStrategy(int $id, array $data)
    {
        try {
            // Update using CI4's model
            $success = $this->update($id, $data);
            
            if (!$success) {
                log_message('error', 'Failed to update Strategy: ' . print_r($this->errors(), true));
                return false;
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Exception updating Strategy: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Toggle the status of a Strategy
     */
    public function toggleStatus($id, $statusData)
    {
        $strategy = $this->find($id);
        
        if ($strategy) {
            // Toggle the status
            $newStatus = ($strategy['strategies_status'] == 1) ? 0 : 1;
            
            $data = [
                'strategies_status' => $newStatus,
                'strategies_status_by' => $statusData['strategies_status_by'],
                'strategies_status_at' => date('Y-m-d H:i:s'),
                'strategies_status_remarks' => $statusData['strategies_status_remarks'] ?? ''
            ];
            
            return $this->update($id, $data);
        }
        
        return false;
    }
}
