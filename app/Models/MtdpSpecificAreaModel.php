<?php
// app/Models/MtdpSpecificAreaModel.php

namespace App\Models;

use CodeIgniter\Model;

class MtdpSpecificAreaModel extends Model
{
    protected $table            = 'plans_mtdp_specific_area';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'mtdp_id', 'spa_id', 'dip_id', 'sa_code', 'sa_title', 'sa_remarks',
        'sa_status', 'sa_status_by', 'sa_status_at', 'sa_status_remarks',
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
        'sa_code'      => 'required|max_length[20]',
        'sa_title'     => 'required|max_length[255]',
        'sa_status'    => 'required|integer',
        'sa_status_by' => 'required|integer',
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
     * Get Specific Areas with related data
     */
    public function getSpecificAreas($id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select($this->table.'.*', 'plans_mtdp_spa.title as spa_title', 'plans_mtdp.title as mtdp_title');
        $builder->join('plans_mtdp_spa', 'plans_mtdp_spa.id = '.$this->table.'.spa_id', 'left');
        $builder->join('plans_mtdp', 'plans_mtdp.id = '.$this->table.'.mtdp_id', 'left');
        
        if ($id !== null) {
            return $builder->where($this->table.'.id', $id)->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get Specific Areas by SPA ID
     */
    public function getSpecificAreasBySpaId($spaId)
    {
        return $this->where('spa_id', $spaId)->findAll();
    }
    
    /**
     * Create a new Specific Area
     */
    public function createSpecificArea(array $data)
    {
        // Default status and timestamp
        $data['sa_status']    = $data['sa_status'] ?? 1;
        $data['sa_status_at'] = date('Y-m-d H:i:s');

        // Insert using CI4's auto-model (will protect allowedFields automatically)
        return $this->insert($data);
    }
    
    /**
     * Update an existing Specific Area
     */
    public function updateSpecificArea(int $id, array $data)
    {
        try {
            // Update using CI4's model
            $success = $this->update($id, $data);
            
            if (!$success) {
                log_message('error', 'Failed to update Specific Area: ' . print_r($this->errors(), true));
                return false;
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Exception updating Specific Area: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Toggle the status of a Specific Area
     */
    public function toggleStatus($id, $statusData)
    {
        $specificArea = $this->find($id);
        
        if ($specificArea) {
            // Toggle the status
            $newStatus = ($specificArea['sa_status'] == 1) ? 0 : 1;
            
            $data = [
                'sa_status' => $newStatus,
                'sa_status_by' => $statusData['sa_status_by'],
                'sa_status_at' => date('Y-m-d H:i:s'),
                'sa_status_remarks' => $statusData['sa_status_remarks'] ?? ''
            ];
            
            return $this->update($id, $data);
        }
        
        return false;
    }
}
