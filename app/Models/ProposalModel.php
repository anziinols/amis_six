<?php
// app/Models/ProposalModel.php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ProposalModel
 *
 * Handles database operations for the proposal table.
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
        'supervisor_id'     => 'permit_empty|integer',
        'action_officer_id' => 'permit_empty|integer',
        'province_id'       => 'required|integer',
        'district_id'       => 'required|integer',
        'date_start'        => 'required|valid_date',
        'date_end'          => 'required|valid_date',
        'total_cost'        => 'permit_empty|decimal',
        'location'          => 'permit_empty|max_length[255]',
        'status'            => 'required|in_list[pending,submitted,approved,rated]',
        'status_by'         => 'permit_empty|integer',
        'status_at'         => 'permit_empty|valid_date',
        'status_remarks'    => 'permit_empty|string',
        'rating_score'      => 'permit_empty|decimal',
        'rated_at'          => 'permit_empty|valid_date',
        'rated_by'          => 'permit_empty|integer',
        'rate_remarks'      => 'permit_empty|string',
        'created_by'        => 'permit_empty|integer',
        'updated_by'        => 'permit_empty|integer',
        'deleted_by'        => 'permit_empty|integer'
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['setDefaultStatus'];
    protected $afterInsert  = [];
    protected $beforeUpdate = [];
    protected $afterUpdate  = [];
    protected $beforeDelete = [];
    protected $afterDelete  = [];

    /**
     * Set default status for new proposals
     */
    protected function setDefaultStatus(array $data)
    {
        if (!isset($data['data']['status'])) {
            $data['data']['status'] = 'pending';
            $data['data']['status_at'] = date('Y-m-d H:i:s');
            $data['data']['status_by'] = session()->get('user_id') ?? null;
        }

        return $data;
    }

    /**
     * Get proposal with related information
     *
     * @param int|null $id Proposal ID (optional)
     * @return array
     */
    public function getProposalWithDetails($id = null)
    {
        $builder = $this->db->table($this->table . ' as p');
        $builder->select([
            'p.*',
            'w.title as workplan_title',
            'a.title as activity_title',
            'a.activity_type',
            'prov.name as province_name',
            'dist.name as district_name',
            'CONCAT(s.fname, " ", s.lname) as supervisor_name',
            'CONCAT(ao.fname, " ", ao.lname) as action_officer_name'
        ]);
        $builder->join('workplans as w', 'w.id = p.workplan_id', 'left');
        $builder->join('workplan_activities as a', 'a.id = p.activity_id', 'left');
        $builder->join('gov_structure as prov', 'prov.id = p.province_id', 'left');
        $builder->join('gov_structure as dist', 'dist.id = p.district_id', 'left');
        $builder->join('users as s', 's.id = p.supervisor_id', 'left');
        $builder->join('users as ao', 'ao.id = p.action_officer_id', 'left');

        if ($id !== null) {
            $builder->where('p.id', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get proposals with detailed information
     *
     * @return array
     */
    public function getProposalsWithDetails()
    {
        $builder = $this->db->table($this->table . ' as p');
        $builder->select([
            'p.*',
            'w.title as workplan_title',
            'wa.title as activity_title',
            'wa.activity_type',
            'prov.name as province_name',
            'dist.name as district_name',
            'CONCAT(s.fname, " ", s.lname) as supervisor_name',
            'CONCAT(ao.fname, " ", ao.lname) as action_officer_name',
            'b.name as branch_name'
        ]);
        $builder->join('workplans as w', 'w.id = p.workplan_id', 'left');
        $builder->join('workplan_activities as wa', 'wa.id = p.activity_id', 'left');
        $builder->join('gov_structure as prov', 'prov.id = p.province_id', 'left');
        $builder->join('gov_structure as dist', 'dist.id = p.district_id', 'left');
        $builder->join('users as s', 's.id = p.supervisor_id', 'left');
        $builder->join('users as ao', 'ao.id = p.action_officer_id', 'left');
        $builder->join('branches as b', 'b.id = wa.branch_id', 'left');
        $builder->where('p.deleted_at IS NULL');
        $builder->orderBy('p.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Update proposal status
     *
     * @param int $id Proposal ID
     * @param string $status New status
     * @param int $userId User ID making the change
     * @param string $remarks Status change remarks
     * @return bool
     */
    public function updateStatus($id, $status, $userId, $remarks = '')
    {
        $validStatuses = ['pending', 'submitted', 'approved', 'rated'];

        if (!in_array($status, $validStatuses)) {
            return false;
        }

        return $this->update($id, [
            'status' => $status,
            'status_by' => $userId,
            'status_at' => date('Y-m-d H:i:s'),
            'status_remarks' => $remarks,
            'updated_by' => $userId
        ]);
    }

    /**
     * Rate a proposal
     *
     * @param int $id Proposal ID
     * @param float $score Rating score
     * @param int $userId User ID making the rating
     * @param string $remarks Rating remarks
     * @return bool
     */
    public function rateProposal($id, $score, $userId, $remarks = '')
    {
        return $this->update($id, [
            'rating_score' => $score,
            'rated_by' => $userId,
            'rated_at' => date('Y-m-d H:i:s'),
            'rate_remarks' => $remarks,
            'status' => 'rated',
            'status_by' => $userId,
            'status_at' => date('Y-m-d H:i:s'),
            'updated_by' => $userId
        ]);
    }
}
