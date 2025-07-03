<?php
// app/Models/MtdpDipModel.php

namespace App\Models;

use CodeIgniter\Model;

class MtdpDipModel extends Model
{
    protected $table            = 'plans_mtdp_dip';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'mtdp_id', 'spa_id', 'dip_code', 'dip_title', 'dip_remarks',
        'investments', 'kras', 'strategies', 'indicators',
        'dip_status', 'dip_status_by', 'dip_status_at', 'dip_status_remarks',
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
        'dip_code'     => 'required|max_length[20]',
        'dip_title'    => 'required|max_length[255]',
    ];
    
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
    
    // Callbacks
    protected $beforeInsert = ['prepareJsonFields'];
    protected $afterInsert  = [];
    protected $beforeUpdate = ['prepareJsonFields'];
    protected $afterUpdate  = [];
    protected $beforeDelete = [];
    protected $afterDelete  = [];
    
    /**
     * Get DIPs with related data
     */
    public function getDips($id = null)
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
     * Get DIPs by SPA ID
     */
    public function getDipsBySpaId($spaId)
    {
        return $this->where('spa_id', $spaId)->findAll();
    }
    
    /**
     * Create a new DIP
     */
    public function createDip(array $data)
    {
        // Encode JSON fields for DB storage
        foreach (['investments', 'kras', 'strategies', 'indicators'] as $field) {
            if (!empty($data[$field]) && is_array($data[$field])) {
                $data[$field] = json_encode($data[$field]);
            }
        }

        // Default status and timestamp
        $data['dip_status']    = $data['dip_status'] ?? 1;
        $data['dip_status_at'] = date('Y-m-d H:i:s');

        // Insert using CI4's auto-model (will protect allowedFields automatically)
        return $this->insert($data);
    }
    
    /**
     * Update an existing DIP
     */
    public function updateDip(int $id, array $data)
    {
        try {
            // Encode JSON fields for DB storage if they exist and are arrays
            foreach (['investments', 'kras', 'strategies', 'indicators'] as $field) {
                if (isset($data[$field])) {
                    // If it's already a JSON string, leave it as is
                    if (is_string($data[$field]) && $this->isJson($data[$field])) {
                        continue;
                    }
                    // If it's an array, encode it
                    if (is_array($data[$field])) {
                        $data[$field] = json_encode($data[$field]);
                    }
                }
            }

            // Remove mtdp_id and spa_id from update data if they exist
            // These shouldn't be updated after creation
            unset($data['mtdp_id'], $data['spa_id']);

            // Update using CI4's model
            $success = $this->update($id, $data);
            
            if (!$success) {
                log_message('error', 'Failed to update DIP: ' . print_r($this->errors(), true));
                return false;
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Exception updating DIP: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Toggle the status of a DIP
     */
    public function toggleStatus($id, $statusData)
    {
        $dip = $this->find($id);
        
        if ($dip) {
            // Toggle the status
            $newStatus = ($dip['dip_status'] == 1) ? 0 : 1;
            
            $data = [
                'dip_status' => $newStatus,
                'dip_status_by' => $statusData['dip_status_by'],
                'dip_status_at' => date('Y-m-d H:i:s'),
                'dip_status_remarks' => $statusData['dip_status_remarks'] ?? ''
            ];
            
            return $this->update($id, $data);
        }
        
        return false;
    }
    
    /**
     * Find a DIP by ID
     */
    public function find($id = null, $columns = '*')
    {
        $dip = parent::find($id, $columns);
        
        if (!$dip) {
            return null;
        }
        
        // Ensure JSON fields are properly decoded 
        $jsonFields = ['investments', 'kras', 'strategies', 'indicators'];
        foreach ($jsonFields as $field) {
            if (isset($dip[$field]) && is_string($dip[$field])) {
                try {
                    $jsonData = json_decode($dip[$field], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $dip[$field] = $jsonData;
                    } else {
                        log_message('error', "JSON decode error for field {$field}: " . json_last_error_msg());
                        $dip[$field] = [];
                    }
                } catch (\Exception $e) {
                    log_message('error', "Exception decoding JSON for field {$field}: " . $e->getMessage());
                    $dip[$field] = [];
                }
            }
        }
        
        log_message('debug', 'MtdpDipModel::find - Processed DIP data: ' . print_r($dip, true));
        
        return $dip;
    }
    
    /**
     * Prepare JSON fields before saving to database
     */
    protected function prepareJsonFields(array $data)
    {
        $jsonFields = ['investments', 'kras', 'strategies', 'indicators'];
        
        foreach ($jsonFields as $field) {
            if (isset($data['data'][$field])) {
                // If the field is an array or object, convert to JSON string
                if (is_array($data['data'][$field]) || is_object($data['data'][$field])) {
                    $data['data'][$field] = json_encode($data['data'][$field]);
                }
            }
        }
        
        return $data;
    }
    
    /**
     * Parse JSON fields after retrieving from database
     */
    public function findAll(?int $limit = null, int $offset = 0)
    {
        $data = parent::findAll($limit, $offset);
        
        if ($data) {
            foreach ($data as $key => $row) {
                $data[$key] = $this->parseJsonFields($row);
            }
        }
        
        return $data;
    }
    
    /**
     * Parse JSON fields in data
     */
    private function parseJsonFields($data)
    {
        $jsonFields = ['investments', 'kras', 'strategies', 'indicators'];
        
        foreach ($jsonFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $jsonData = json_decode($data[$field], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data[$field] = $jsonData;
                }
            }
        }
        
        return $data;
    }
}