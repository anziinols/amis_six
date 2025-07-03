<?php
// app/Models/NaspModel.php

namespace App\Models;

use CodeIgniter\Model;

class NaspModel extends Model
{
    protected $table            = 'plans_nasp';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'parent_id', 'type', 'code', 'title', 'date_from', 'date_to', 'remarks',
        'nasp_status', 'nasp_status_by', 'nasp_status_at', 'nasp_status_remarks',
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
        'type'       => 'required|max_length[20]',
        'code'       => 'required|max_length[20]',
        'title'      => 'required|max_length[255]',
        'nasp_status' => 'permit_empty|integer', 
        'created_by' => 'permit_empty|max_length[255]',
    ];
    
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
    
    // Callbacks
    protected $beforeInsert = [];
    protected $afterInsert  = [];
    protected $beforeUpdate = ['logUpdateAttempt'];
    protected $afterUpdate  = [];
    protected $beforeDelete = [];
    protected $afterDelete  = [];
    
    // Log update attempt for debugging
    protected function logUpdateAttempt(array $data)
    {
        log_message('debug', 'NaspModel update attempt: ' . json_encode($data));
        
        if (isset($data['data']['id'])) {
            log_message('debug', 'Current record: ' . json_encode($this->find($data['data']['id'])));
        }
        
        // Allow the update to proceed
        return $data;
    }
    
    // Get NASP with its parent
    public function getWithParent($id = null)
    {
        $builder = $this->db->table($this->table . ' as child');
        $builder->select(
            'child.*', 
            'parent.title as parent_title', 
            'parent.code as parent_code',
            'parent.type as parent_type'
        );
        $builder->join($this->table . ' as parent', 'parent.id = child.parent_id', 'left');
        
        if ($id !== null) {
            return $builder->where('child.id', $id)->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }
    
    // Get NASP by parent ID
    public function getByParentId($parentId)
    {
        return $this->where('parent_id', $parentId)->findAll();
    }
    
    // Get NASP by type
    public function getByType($type)
    {
        return $this->where('type', $type)->findAll();
    }
    
    // Get hierarchy structure of NASP plans
    public function getHierarchy($rootId = null) 
    {
        // If rootId is null, get all top-level items
        $condition = $rootId === null ? 'parent_id = 0 OR parent_id IS NULL' : ['parent_id' => $rootId];
        
        $items = $this->where($condition)->findAll();
        
        foreach ($items as &$item) {
            $item['children'] = $this->getHierarchy($item['id']);
        }
        
        return $items;
    }

    public function deletePlanAndChildren($planId)
    {
        $this->db->transStart();

        // Recursively delete children
        $this->deleteChildrenRecursive($planId);

        // Delete the main plan itself
        $this->delete($planId);

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    protected function deleteChildrenRecursive($parentId)
    {
        $children = $this->where('parent_id', $parentId)->findAll();

        foreach ($children as $child) {
            // Recursively delete grandchildren
            $this->deleteChildrenRecursive($child['id']);
            // Delete the child
            $this->delete($child['id']);
        }
    }
}