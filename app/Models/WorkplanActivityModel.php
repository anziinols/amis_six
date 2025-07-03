<?php
// app/Models/WorkplanActivityModel.php

namespace App\Models;

use CodeIgniter\Model;

/**
 * WorkplanActivityModel
 *
 * Handles database operations for the workplan_activities table.
 * This model has been updated to match the current database structure.
 */
class WorkplanActivityModel extends Model
{
    protected $table            = 'workplan_activities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'workplan_id',
        'branch_id',
        'province_id',
        'district_id',
        'location',
        'gps_coordinates',
        'title',
        'description',
        'activity_type',
        'supervisor_id',
        'status',
        'status_by',
        'status_at',
        'status_remarks',
        'total_cost',
        'image_paths',
        'trainers',
        'trainees',
        'unit',
        'quantity',
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
        'workplan_id'      => 'required|integer',
        'branch_id'        => 'permit_empty|integer',
        'province_id'      => 'permit_empty|integer',
        'district_id'      => 'permit_empty|integer',
        'location'         => 'permit_empty|max_length[255]',
        'gps_coordinates'  => 'permit_empty|max_length[100]',
        'title'            => 'required|max_length[255]',
        'description'      => 'permit_empty|string',
        'activity_type'    => 'required|in_list[training,inputs,infrastructure,output]',
        'supervisor_id'    => 'permit_empty|integer',
        'status'           => 'permit_empty|max_length[50]',
        'status_by'        => 'permit_empty|integer',
        'status_at'        => 'permit_empty|valid_date',
        'status_remarks'   => 'permit_empty|string',
        'total_cost'       => 'permit_empty|decimal',
        'image_paths'      => 'permit_empty|string',
        'trainers'         => 'permit_empty|string',
        'trainees'         => 'permit_empty|string',
        'unit'             => 'permit_empty|max_length[100]',
        'quantity'         => 'permit_empty|integer',
        'created_by'       => 'permit_empty|integer',
        'updated_by'       => 'permit_empty|integer',
        'deleted_by'       => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'workplan_id' => [
            'required' => 'Workplan ID is required',
            'integer' => 'Workplan ID must be a valid integer'
        ],
        'title' => [
            'required' => 'Activity title is required',
            'max_length' => 'Title cannot exceed 255 characters'
        ],
        'activity_type' => [
            'required' => 'Activity type is required',
            'in_list' => 'Activity type must be either training, inputs, infrastructure, or output'
        ]
    ];

    /**
     * Get activities with their related workplan information
     *
     * @param array $conditions Optional conditions for filtering
     * @return array
     */
    public function getActivitiesWithWorkplan($conditions = [])
    {
        $builder = $this->db->table($this->table);
        $builder->select($this->table . '.*, workplans.title as workplan_title');
        $builder->join('workplans', 'workplans.id = ' . $this->table . '.workplan_id');

        if (!empty($conditions)) {
            $builder->where($conditions);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get activities by branch
     *
     * @param int $branchId
     * @return array
     */
    public function getActivitiesByBranch($branchId)
    {
        return $this->where('branch_id', $branchId)
                    ->where('deleted_at IS NULL')
                    ->findAll();
    }

    /**
     * Get activities by supervisor
     *
     * @param int $supervisorId
     * @return array
     */
    public function getActivitiesBySupervisor($supervisorId)
    {
        return $this->where('supervisor_id', $supervisorId)
                    ->where('deleted_at IS NULL')
                    ->findAll();
    }

    /**
     * Get activities with detailed information
     *
     * @return array
     */
    public function getActivitiesWithDetails()
    {
        $builder = $this->db->table($this->table . ' as wa');
        $builder->select([
            'wa.*',
            'w.title as workplan_title',
            'w.year as workplan_year',
            'b.name as branch_name',
            'CONCAT(s.fname, " ", s.lname) as supervisor_name',
            'CONCAT(u.fname, " ", u.lname) as created_by_name'
        ]);
        $builder->join('workplans as w', 'w.id = wa.workplan_id', 'left');
        $builder->join('branches as b', 'b.id = wa.branch_id', 'left');
        $builder->join('users as s', 's.id = wa.supervisor_id', 'left');
        $builder->join('users as u', 'u.id = wa.created_by', 'left');
        $builder->where('wa.deleted_at IS NULL');
        $builder->orderBy('wa.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }
}