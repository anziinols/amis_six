<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkplanModel extends Model
{
    protected $table            = 'workplans';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array'; // Or 'object' depending on preference
    protected $useSoftDeletes   = true; // Corresponds to is_deleted and deleted_at
    protected $protectFields    = true; // Default, good practice

    // Fields allowed for mass assignment
    protected $allowedFields    = [
        'branch_id',
        'title',
        'description',
        'supervisor_id',
        'start_date',
        'end_date',
        'status',
        'objectives', // Handled as plain text
        'remarks',
        'is_deleted', // Managed by useSoftDeletes
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // Dates
    protected $useTimestamps = true; // Handles created_at and updated_at
    protected $dateFormat    = 'datetime'; // Or 'int', 'date', etc. Adjust if needed.
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at'; // Field used for soft deletes

    // Validation (Optional but recommended)
    // protected $validationRules      = [];
    // protected $validationMessages   = [];
    // protected $skipValidation       = false;
    // protected $cleanValidationRules = true;

    // Callbacks (Optional)
    // protected $allowCallbacks = true;
    // protected $beforeInsert   = [];
    // protected $afterInsert    = [];
    // protected $beforeUpdate   = [];
    // protected $afterUpdate    = [];
    // protected $beforeFind     = [];
    // protected $afterFind      = [];
    // protected $beforeDelete   = [];
    // protected $afterDelete    = [];

    // Note: Relationships (e.g., belongsTo Branch, User for supervisor) would be defined here
    // public function branch()
    // {
    //     return $this->belongsTo(BranchModel::class, 'branch_id');
    // }
    // public function supervisor()
    // {
    //     return $this->belongsTo(UserModel::class, 'supervisor_id');
    // }
    // ... other relationships

    /**
     * Get workplans by branch
     *
     * @param int $branchId
     * @return array
     */
    public function getWorkplansByBranch($branchId)
    {
        return $this->where('branch_id', $branchId)
                    ->where('deleted_at IS NULL')
                    ->findAll();
    }

    /**
     * Get workplans with detailed information
     *
     * @return array
     */
    public function getWorkplansWithDetails()
    {
        $builder = $this->db->table($this->table . ' as w');
        $builder->select([
            'w.*',
            'b.name as branch_name',
            'CONCAT(u.fname, " ", u.lname) as created_by_name'
        ]);
        $builder->join('branches as b', 'b.id = w.branch_id', 'left');
        $builder->join('users as u', 'u.id = w.created_by', 'left');
        $builder->where('w.deleted_at IS NULL');
        $builder->orderBy('w.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }
}
