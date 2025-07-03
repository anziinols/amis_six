<?php

namespace App\Models;

use CodeIgniter\Model;

class BranchesModel extends Model
{
    protected $table      = 'branches';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'parent_id', 'abbrev', 'name', 'remarks', 
        'branch_status', 'branch_status_by', 'branch_status_at', 'branch_status_remarks',
        'created_by', 'updated_by', 'deleted_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
    // Get all branches with parent name info
    public function getAllBranches()
    {
        $builder = $this->db->table('branches b');
        $builder->select('b.*, p.name as parent_name');
        $builder->join('branches p', 'p.id = b.parent_id', 'left');
        $builder->orderBy('b.name', 'ASC');
        return $builder->get()->getResultArray();
    }
    
    // Get branches for dropdown
    public function getBranchesForDropdown()
    {
        return $this->select('id, name, parent_id')
                    ->where('branch_status', 1)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }
    
    // Toggle branch status
    public function toggleStatus($id, $status, $userId, $remarks = '')
    {
        $data = [
            'id' => $id,
            'branch_status' => $status,
            'branch_status_by' => $userId,
            'branch_status_at' => date('Y-m-d H:i:s'),
            'branch_status_remarks' => $remarks
        ];
        
        return $this->save($data);
    }
} 