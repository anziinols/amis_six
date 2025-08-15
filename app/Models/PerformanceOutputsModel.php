<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PerformanceOutputsModel
 *
 * Handles database operations for the performance_outputs table
 * which represents various types of outputs/deliverables for KRAs and Performance Indicators
 */
class PerformanceOutputsModel extends Model
{
    protected $table            = 'performance_outputs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'kra_performance_indicator_id',
        'user_id',
        'output',
        'description',
        'quantity',
        'unit_of_measurement',
        'status',
        'status_by',
        'status_at',
        'status_remarks',
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

    // Validation rules
    protected $validationRules = [
        'kra_performance_indicator_id' => 'required|integer',
        'user_id'                      => 'required|integer',
        'output'                       => 'required|max_length[255]',
        'description'                  => 'permit_empty',
        'quantity'                     => 'required|max_length[20]',
        'unit_of_measurement'          => 'required|max_length[255]',
        'status'                       => 'permit_empty|max_length[50]',
        'status_by'                    => 'permit_empty|integer',
        'status_at'                    => 'permit_empty|valid_date',
        'status_remarks'               => 'permit_empty',
        'created_by'                   => 'permit_empty|integer',
        'updated_by'                   => 'permit_empty|integer',
        'deleted_by'                   => 'permit_empty|integer'
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
     * Set default status for new performance outputs
     */
    protected function setDefaultStatus(array $data)
    {
        if (!isset($data['data']['status'])) {
            $data['data']['status'] = 'active';
            $data['data']['status_at'] = date('Y-m-d H:i:s');
            $data['data']['status_by'] = session()->get('user_id') ?? null;
        }

        return $data;
    }

    /**
     * Get all outputs for a specific KRA/PI
     *
     * @param int $kraPerformanceIndicatorId
     * @return array
     */
    public function getByKraPerformanceIndicator($kraPerformanceIndicatorId)
    {
        return $this->where('kra_performance_indicator_id', $kraPerformanceIndicatorId)
                    ->orderBy('output', 'ASC')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }



    /**
     * Get outputs by user
     *
     * @param int $userId
     * @return array
     */
    public function getByUser($userId)
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get outputs by status
     *
     * @param string $status
     * @return array
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get output with KRA/PI and user details
     *
     * @param int $id
     * @return array|null
     */
    public function getOutputWithDetails($id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('performance_outputs po')
            ->select('po.*,
                     kpi.item as kra_pi_item,
                     kpi.type as kra_pi_type,
                     kpi.code as kra_pi_code,
                     wp.title as performance_period_title,
                     CONCAT(u1.fname, " ", u1.lname) as user_name,
                     CONCAT(u2.fname, " ", u2.lname) as created_by_name,
                     CONCAT(u3.fname, " ", u3.lname) as updated_by_name,
                     CONCAT(u4.fname, " ", u4.lname) as status_by_name')
            ->join('performance_indicators_kra kpi', 'po.kra_performance_indicator_id = kpi.id', 'left')
            ->join('workplan_period wp', 'kpi.workplan_period_id = wp.id', 'left')
            ->join('users u1', 'po.user_id = u1.id', 'left')
            ->join('users u2', 'po.created_by = u2.id', 'left')
            ->join('users u3', 'po.updated_by = u3.id', 'left')
            ->join('users u4', 'po.status_by = u4.id', 'left')
            ->where('po.id', $id)
            ->where('po.deleted_at', null)
            ->get();

        return $query->getRowArray();
    }

    /**
     * Get all outputs with KRA/PI and user details
     *
     * @return array
     */
    public function getAllWithDetails()
    {
        $db = \Config\Database::connect();

        $query = $db->table('performance_outputs po')
            ->select('po.*,
                     kpi.item as kra_pi_item,
                     kpi.type as kra_pi_type,
                     wp.title as performance_period_title,
                     CONCAT(u1.fname, " ", u1.lname) as user_name,
                     CONCAT(u2.fname, " ", u2.lname) as created_by_name,
                     CONCAT(u3.fname, " ", u3.lname) as status_by_name')
            ->join('performance_indicators_kra kpi', 'po.kra_performance_indicator_id = kpi.id', 'left')
            ->join('workplan_period wp', 'kpi.workplan_period_id = wp.id', 'left')
            ->join('users u1', 'po.user_id = u1.id', 'left')
            ->join('users u2', 'po.created_by = u2.id', 'left')
            ->join('users u3', 'po.status_by = u3.id', 'left')
            ->where('po.deleted_at', null)
            ->orderBy('wp.title', 'ASC')
            ->orderBy('po.output', 'ASC')
            ->orderBy('po.created_at', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Update output status
     *
     * @param int $id
     * @param string $status
     * @param string $remarks
     * @return bool
     */
    public function updateStatus($id, $status, $remarks = '')
    {
        $data = [
            'status' => $status,
            'status_at' => date('Y-m-d H:i:s'),
            'status_by' => session()->get('user_id') ?? null,
            'status_remarks' => $remarks,
            'updated_by' => session()->get('user_id') ?? null
        ];

        return $this->update($id, $data);
    }

    /**
     * Get outputs count by status
     *
     * @return array
     */
    public function getStatusCounts()
    {
        $db = \Config\Database::connect();

        $query = $db->table('performance_outputs')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('status')
            ->get();

        return $query->getResultArray();
    }



    /**
     * Search outputs
     *
     * @param string $searchTerm
     * @return array
     */
    public function search($searchTerm)
    {
        return $this->like('output', $searchTerm)
                    ->orLike('description', $searchTerm)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get outputs for a specific performance period
     *
     * @param int $performancePeriodId
     * @return array
     */
    public function getByPerformancePeriod($performancePeriodId)
    {
        $db = \Config\Database::connect();

        $query = $db->table('performance_outputs po')
            ->select('po.*')
            ->join('performance_indicators_kra kpi', 'po.kra_performance_indicator_id = kpi.id')
            ->where('kpi.workplan_period_id', $performancePeriodId)
            ->where('po.deleted_at', null)
            ->orderBy('po.output', 'ASC')
            ->orderBy('po.created_at', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get outputs summary by status for a user
     *
     * @param int $userId
     * @return array
     */
    public function getUserOutputsSummary($userId)
    {
        $db = \Config\Database::connect();

        $query = $db->table('performance_outputs')
            ->select('status, COUNT(*) as count')
            ->where('user_id', $userId)
            ->where('deleted_at', null)
            ->groupBy('status')
            ->orderBy('status', 'ASC')
            ->get();

        return $query->getResultArray();
    }


}
