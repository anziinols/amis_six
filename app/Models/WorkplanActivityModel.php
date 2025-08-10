<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkplanActivityModel extends Model
{
    protected $table            = 'workplan_activities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'workplan_id',
        'branch_id',
        'activity_code',
        'title',
        'description',
        'activity_type',
        'q_one_target',
        'q_two_target',
        'q_three_target',
        'q_four_target',
        'q_one_achieved',
        'q_two_achieved',
        'q_three_achieved',
        'q_four_achieved',
        'total_budget',
        'rated_at',
        'rated_by',
        'rating',
        'reated_remarks',
        'supervisor_id',
        'status',
        'status_by',
        'status_at',
        'status_remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'workplan_id'   => 'required',
        'title'         => 'required|max_length[255]',
        'activity_type' => 'required|in_list[training,inputs,infrastructure,output]',
        'rating'        => 'permit_empty|integer|greater_than[0]|less_than_equal_to[5]'
    ];

    protected $validationMessages = [
        'workplan_id' => [
            'required' => 'Workplan is required'
        ],
        'title' => [
            'required' => 'Activity title is required',
            'max_length' => 'Title cannot exceed 255 characters'
        ],
        'activity_type' => [
            'required' => 'Activity type is required'
        ],
        'rating' => [
            'integer' => 'Rating must be a valid number',
            'greater_than' => 'Rating must be at least 1 star',
            'less_than_equal_to' => 'Rating cannot exceed 5 stars'
        ]
    ];

    public function getActivitiesWithWorkplan($workplanId = null)
    {
        $builder = $this->select('workplan_activities.*, workplans.title as workplan_title')
                        ->join('workplans', 'workplans.id = workplan_activities.workplan_id', 'left');
        
        if ($workplanId) {
            $builder->where('workplan_activities.workplan_id', $workplanId);
        }
        
        return $builder->findAll();
    }

    public function getActivitiesWithDetails()
    {
        return $this->select('workplan_activities.*, 
                             workplans.title as workplan_title,
                             branches.name as branch_name,
                             CONCAT(supervisors.fname, " ", supervisors.lname) as supervisor_name')
                    ->join('workplans', 'workplans.id = workplan_activities.workplan_id', 'left')
                    ->join('branches', 'branches.id = workplan_activities.branch_id', 'left')
                    ->join('users as supervisors', 'supervisors.id = workplan_activities.supervisor_id', 'left')
                    ->orderBy('workplan_activities.activity_code', 'ASC')
                    ->findAll();
    }

    public function generateActivityCode()
    {
        $yearSuffix = date('y');
        $prefix = 'ACT' . $yearSuffix;

        $builder = $this->db->table($this->table);
        $builder->select('activity_code');
        $builder->like('activity_code', $prefix, 'after');
        $builder->orderBy('activity_code', 'DESC');
        $builder->limit(1);

        $result = $builder->get()->getRowArray();

        if ($result && !empty($result['activity_code'])) {
            $lastCode = $result['activity_code'];
            $numericPart = (int) substr($lastCode, -3);
            $newIncrement = $numericPart + 1;
        } else {
            $newIncrement = 1;
        }

        return $prefix . str_pad($newIncrement, 3, '0', STR_PAD_LEFT);
    }

    public function insert($data = null, bool $returnID = true)
    {
        if (is_array($data) && !isset($data['activity_code'])) {
            $data['activity_code'] = $this->generateActivityCode();
        }

        return parent::insert($data, $returnID);
    }

    public function save($data): bool
    {
        if (is_array($data) && (!isset($data['id']) || empty($data['id'])) && !isset($data['activity_code'])) {
            $data['activity_code'] = $this->generateActivityCode();
        }

        return parent::save($data);
    }
}
