<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * WorkplanPeriodModel
 *
 * Handles database operations for the workplan_period table
 * which represents workplan periods for users
 */
class WorkplanPeriodModel extends Model
{
    protected $table            = 'workplan_period';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'user_id',
        'duty_instruction_id',
        'title',
        'description',
        'workplan_period_filepath',
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
        'user_id'                       => 'required|integer',
        'duty_instruction_id'           => 'permit_empty|integer',
        'title'                         => 'required|max_length[255]',
        'description'                   => 'permit_empty',
        'workplan_period_filepath'      => 'permit_empty|max_length[500]',
        'status'                        => 'permit_empty|max_length[50]',
        'status_by'                     => 'permit_empty|integer',
        'status_at'                     => 'permit_empty|valid_date',
        'status_remarks'                => 'permit_empty',
        'created_by'                    => 'permit_empty|integer',
        'updated_by'                    => 'permit_empty|integer',
        'deleted_by'                    => 'permit_empty|integer'
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
     * Set default status for new workplan periods
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
     * Get all workplan periods for a specific user
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
     * Get workplan periods by status
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
     * Get workplan period with user and duty instruction details
     *
     * @param int $id
     * @return array|null
     */
    public function getWorkplanPeriodWithDetails($id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('workplan_period wp')
            ->select('wp.*,
                     CONCAT(u1.fname, " ", u1.lname) as user_name,
                     CONCAT(u2.fname, " ", u2.lname) as created_by_name,
                     CONCAT(u3.fname, " ", u3.lname) as updated_by_name,
                     CONCAT(u4.fname, " ", u4.lname) as status_by_name,
                     di.duty_instruction_title,
                     di.duty_instruction_number')
            ->join('users u1', 'wp.user_id = u1.id', 'left')
            ->join('users u2', 'wp.created_by = u2.id', 'left')
            ->join('users u3', 'wp.updated_by = u3.id', 'left')
            ->join('users u4', 'wp.status_by = u4.id', 'left')
            ->join('duty_instructions di', 'wp.duty_instruction_id = di.id', 'left')
            ->where('wp.id', $id)
            ->where('wp.deleted_at', null)
            ->get();

        return $query->getRowArray();
    }

    /**
     * Get all workplan periods with user details
     *
     * @return array
     */
    public function getAllWithDetails()
    {
        $db = \Config\Database::connect();

        $query = $db->table('workplan_period wp')
            ->select('wp.*,
                     CONCAT(u1.fname, " ", u1.lname) as user_name,
                     CONCAT(u2.fname, " ", u2.lname) as created_by_name,
                     CONCAT(u3.fname, " ", u3.lname) as status_by_name,
                     di.duty_instruction_title')
            ->join('users u1', 'wp.user_id = u1.id', 'left')
            ->join('users u2', 'wp.created_by = u2.id', 'left')
            ->join('users u3', 'wp.status_by = u3.id', 'left')
            ->join('duty_instructions di', 'wp.duty_instruction_id = di.id', 'left')
            ->where('wp.deleted_at', null)
            ->orderBy('wp.created_at', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Update workplan period status
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
     * Get workplan periods count by status
     *
     * @return array
     */
    public function getStatusCounts()
    {
        $db = \Config\Database::connect();

        $query = $db->table('workplan_period')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('status')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Search workplan periods
     *
     * @param string $searchTerm
     * @return array
     */
    public function search($searchTerm)
    {
        return $this->like('title', $searchTerm)
                    ->orLike('description', $searchTerm)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get workplan periods by duty instruction
     *
     * @param int $dutyInstructionId
     * @return array
     */
    public function getByDutyInstruction($dutyInstructionId)
    {
        return $this->where('duty_instruction_id', $dutyInstructionId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
