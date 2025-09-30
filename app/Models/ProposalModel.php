<?php
// app/Models/ProposalModel.php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ProposalModel
 * 
 * Handles database operations for the proposal table
 */
class ProposalModel extends Model
{
    protected $table            = 'proposal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'workplan_id',
        'activity_id',
        'supervisor_id',
        'action_officer_id',
        'province_id',
        'district_id',
        'date_start',
        'date_end',
        'total_cost',
        'location',
        'status',
        'status_by',
        'status_at',
        'status_remarks',
        'rating_score',
        'rated_at',
        'rated_by',
        'rate_remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
    // Validation
    protected $validationRules = [
        'workplan_id'       => 'required|integer',
        'activity_id'       => 'required|integer',
        'province_id'       => 'required|integer',
        'district_id'       => 'required|integer',
        'date_start'        => 'required|valid_date',
        'date_end'          => 'required|valid_date',
        'total_cost'        => 'permit_empty|decimal',
        'location'          => 'permit_empty|max_length[255]',
        'status'            => 'permit_empty|in_list[pending,submitted,approved,rated]',
        'rating_score'      => 'permit_empty|decimal',
        'supervisor_id'     => 'permit_empty|integer',
        'action_officer_id' => 'permit_empty|integer',
        'status_by'         => 'permit_empty|integer',
        'rated_by'          => 'permit_empty|integer',
        'created_by'        => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'workplan_id' => [
            'required' => 'Workplan is required.',
            'integer'  => 'Workplan must be a valid number.'
        ],
        'activity_id' => [
            'required' => 'Activity is required.',
            'integer'  => 'Activity must be a valid number.'
        ],
        'province_id' => [
            'required' => 'Province is required.',
            'integer'  => 'Province must be a valid number.'
        ],
        'district_id' => [
            'required' => 'District is required.',
            'integer'  => 'District must be a valid number.'
        ],
        'date_start' => [
            'required'   => 'Start date is required.',
            'valid_date' => 'Start date must be a valid date.'
        ],
        'date_end' => [
            'required'   => 'End date is required.',
            'valid_date' => 'End date must be a valid date.'
        ]
    ];

    // Skip validation
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get proposals with related data
     *
     * @return array
     */
    public function getProposalsWithDetails()
    {
        return $this->select('proposal.*, 
                             workplans.title as workplan_title,
                             workplan_activities.title as activity_title,
                             workplan_activities.activity_type,
                             provinces.name as province_name,
                             districts.name as district_name,
                             CONCAT(supervisors.fname, " ", supervisors.lname) as supervisor_name,
                             CONCAT(action_officers.fname, " ", action_officers.lname) as action_officer_name,
                             CONCAT(status_by_user.fname, " ", status_by_user.lname) as status_by_name,
                             CONCAT(rated_by_user.fname, " ", rated_by_user.lname) as rated_by_name')
                    ->join('workplans', 'workplans.id = proposal.workplan_id', 'left')
                    ->join('workplan_activities', 'workplan_activities.id = proposal.activity_id', 'left')
                    ->join('gov_structure as provinces', 'provinces.id = proposal.province_id', 'left')
                    ->join('gov_structure as districts', 'districts.id = proposal.district_id', 'left')
                    ->join('users as supervisors', 'supervisors.id = proposal.supervisor_id', 'left')
                    ->join('users as action_officers', 'action_officers.id = proposal.action_officer_id', 'left')
                    ->join('users as status_by_user', 'status_by_user.id = proposal.status_by', 'left')
                    ->join('users as rated_by_user', 'rated_by_user.id = proposal.rated_by', 'left')
                    ->where('proposal.deleted_at', null)
                    ->orderBy('proposal.created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get proposals by workplan ID
     *
     * @param int $workplanId
     * @return array
     */
    public function getByWorkplanId($workplanId)
    {
        return $this->where('workplan_id', $workplanId)
                    ->where('deleted_at', null)
                    ->findAll();
    }

    /**
     * Get proposals by activity ID
     *
     * @param int $activityId
     * @return array
     */
    public function getByActivityId($activityId)
    {
        return $this->where('activity_id', $activityId)
                    ->where('deleted_at', null)
                    ->findAll();
    }

    /**
     * Get proposals by status
     *
     * @param string $status
     * @return array
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status)
                    ->where('deleted_at', null)
                    ->findAll();
    }

    /**
     * Get proposals by supervisor
     *
     * @param int $supervisorId
     * @return array
     */
    public function getBySupervisor($supervisorId)
    {
        return $this->where('supervisor_id', $supervisorId)
                    ->where('deleted_at', null)
                    ->findAll();
    }

    /**
     * Get proposals by action officer
     *
     * @param int $actionOfficerId
     * @return array
     */
    public function getByActionOfficer($actionOfficerId)
    {
        return $this->where('action_officer_id', $actionOfficerId)
                    ->where('deleted_at', null)
                    ->findAll();
    }
}
