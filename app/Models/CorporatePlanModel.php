<?php
// app/Models/CorporatePlanModel.php

namespace App\Models;

use CodeIgniter\Model;

class CorporatePlanModel extends Model
{
    protected $table            = 'plans_corporate_plan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        //ytypes are: plans, corporate objectives, kra, strategies
        'parent_id', 'type', 'code', 'title', 'date_from', 'date_to', 'remarks',
        'corp_plan_status', 'corp_plan_status_by', 'corp_plan_status_at', 'corp_plan_status_remarks',
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
        'type'       => 'required|max_length[100]',
        'code'       => 'required|max_length[20]',
        'title'      => 'required',
        'corp_plan_status'  => 'required|integer',
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
    
    // Get corporate plan with its parent
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
    
    // Get corporate plans by parent
    public function getByParentId($parentId)
    {
        return $this->where('parent_id', $parentId)->findAll();
    }
    
    // Get corporate plans by type
    public function getByType($type)
    {
        return $this->where('type', $type)->findAll();
    }
    
    // Create a new corporate plan item
    public function createItem($data)
    {
        // Set defaults
        $data['parent_id'] = $data['parent_id'] ?? 0;
        $data['corp_plan_status'] = $data['corp_plan_status'] ?? 1;
        $data['created_by'] = $data['created_by'] ?? 1;
        $data['updated_by'] = $data['updated_by'] ?? 1;

        return $this->insert($data);
    }
    
    // Update a corporate plan item
    public function updateItem($id, $data)
    {
        return $this->update($id, $data);
    }
    
    // Toggle status of a corporate plan item
    public function toggleStatus($id, $userId)
    {
        $item = $this->find($id);
        if (!$item) {
            return false;
        }
        
        $newStatus = ($item['corp_plan_status'] == 1) ? 0 : 1;
        
        $data = [
            'corp_plan_status' => $newStatus,
            'corp_plan_status_by' => $userId,
            'corp_plan_status_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($id, $data);
    }
    
    // Get corporate plans with specific type and no parent
    public function getPlans()
    {
        return $this->where('type', 'plans')
                    ->where('parent_id', 0)
                    ->findAll();
    }
    
    // Get corporate plans with specific type and parent
    public function getItemsByTypeAndParent($type, $parentId)
    {
        return $this->where('type', $type)
                    ->where('parent_id', $parentId)
                    ->findAll();
    }

    public function deletePlanAndChildren($planId)
    {
        $this->db->transStart();

        // Define the hierarchy of types to delete in order
        // This ensures that children are deleted before their parents within the plan structure.
        // 'plans' type itself is handled last.
        $childTypes = [
            'strategy',              // Children of KRAs
            'kra',                   // Children of Objectives
            'objective',             // Children of Overarching Objectives
            'overarching_objective'  // Children of Plans
        ];

        // Start deleting from the deepest children upwards for the given planId
        $this->deleteChildrenRecursive($planId, $childTypes);

        // Finally, delete the main plan itself
        $this->delete($planId); // This uses soft delete as per model config

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    protected function deleteChildrenRecursive($parentId, array $childTypesHierarchy)
    {
        // Get all immediate children of any type for the current parentId
        $children = $this->where('parent_id', $parentId)->findAll();

        foreach ($children as $child) {
            // Recursively delete children of this child first
            $this->deleteChildrenRecursive($child['id'], $childTypesHierarchy);
            // Then delete this child
            $this->delete($child['id']); // Uses soft delete
        }
    }
} 