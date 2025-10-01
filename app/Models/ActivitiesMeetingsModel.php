<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ActivitiesMeetingsModel
 *
 * Handles database operations for the activities_meetings table
 * which represents meetings related to activities
 */
class ActivitiesMeetingsModel extends Model
{
    protected $table            = 'activities_meetings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // Using is_deleted flag instead
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'branch_id',
        'title',
        'agenda',
        'meeting_date',
        'start_time',
        'end_time',
        'location',
        'participants',
        'status',
        'minutes',
        'attachments',
        'remarks',
        'is_deleted',
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
        'branch_id'     => 'required|integer',
        'title'         => 'required|max_length[255]',
        'agenda'        => 'permit_empty',
        'meeting_date'  => 'required|valid_date',
        'start_time'    => 'permit_empty|valid_date',
        'end_time'      => 'permit_empty|valid_date',
        'location'      => 'permit_empty|max_length[255]',
        'participants'  => 'permit_empty',
        'status'        => 'permit_empty|in_list[scheduled,in_progress,completed,cancelled]',
        'minutes'       => 'permit_empty',
        'attachments'   => 'permit_empty',
        'remarks'       => 'permit_empty',
        'is_deleted'    => 'permit_empty|in_list[0,1]',
        'created_by'    => 'permit_empty|integer',
        'updated_by'    => 'permit_empty|integer',
        'deleted_by'    => 'permit_empty|integer'
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['encodeJsonFields'];
    protected $afterInsert  = [];
    protected $beforeUpdate = ['encodeJsonFields'];
    protected $afterUpdate  = [];
    protected $beforeDelete = [];
    protected $afterDelete  = [];
    protected $afterFind    = ['decodeJsonFields'];

    /**
     * Encode JSON fields before saving to database
     */
    protected function encodeJsonFields(array $data)
    {
        $jsonFields = ['participants', 'minutes', 'attachments'];
        
        foreach ($jsonFields as $field) {
            if (isset($data['data'][$field]) && is_array($data['data'][$field])) {
                $data['data'][$field] = json_encode($data['data'][$field]);
            }
        }

        return $data;
    }

    /**
     * Decode JSON fields after retrieving from database
     */
    protected function decodeJsonFields(array $data)
    {
        $jsonFields = ['participants', 'minutes', 'attachments'];
        
        if (isset($data['data'])) {
            // Single record
            foreach ($jsonFields as $field) {
                if (isset($data['data'][$field]) && is_string($data['data'][$field])) {
                    $decoded = json_decode($data['data'][$field], true);
                    $data['data'][$field] = $decoded ?: [];
                }
            }
        } else {
            // Multiple records
            foreach ($data as &$record) {
                foreach ($jsonFields as $field) {
                    if (isset($record[$field]) && is_string($record[$field])) {
                        $decoded = json_decode($record[$field], true);
                        $record[$field] = $decoded ?: [];
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Get meetings by branch ID
     *
     * @param int $branchId
     * @return array
     */
    public function getByBranchId($branchId)
    {
        return $this->where('branch_id', $branchId)
                    ->where('is_deleted', 0)
                    ->orderBy('meeting_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get meetings with branch details
     *
     * @param int $id
     * @return array|null
     */
    public function getWithBranchDetails($id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_meetings am')
            ->select('am.*, b.name as branch_name, b.abbrev as branch_abbrev')
            ->join('branches b', 'am.branch_id = b.id', 'left')
            ->where('am.id', $id)
            ->where('am.is_deleted', 0)
            ->get();

        return $query->getRowArray();
    }

    /**
     * Get all meetings with branch details
     *
     * @return array
     */
    public function getAllWithBranchDetails()
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_meetings am')
            ->select('am.*, b.name as branch_name, b.abbrev as branch_abbrev,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as updated_by_name')
            ->join('branches b', 'am.branch_id = b.id', 'left')
            ->join('users u1', 'am.created_by = u1.id', 'left')
            ->join('users u2', 'am.updated_by = u2.id', 'left')
            ->where('am.is_deleted', 0)
            ->orderBy('am.meeting_date', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get meetings by status
     *
     * @param string $status
     * @return array
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status)
                    ->where('is_deleted', 0)
                    ->orderBy('meeting_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get upcoming meetings
     *
     * @param int $days
     * @return array
     */
    public function getUpcomingMeetings($days = 30)
    {
        $startDate = date('Y-m-d H:i:s');
        $endDate = date('Y-m-d H:i:s', strtotime("+{$days} days"));

        return $this->where('meeting_date >=', $startDate)
                    ->where('meeting_date <=', $endDate)
                    ->where('is_deleted', 0)
                    ->orderBy('meeting_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get meetings by date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getByDateRange($startDate, $endDate)
    {
        return $this->where('meeting_date >=', $startDate)
                    ->where('meeting_date <=', $endDate)
                    ->where('is_deleted', 0)
                    ->orderBy('meeting_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get meetings by creator
     *
     * @param int $createdBy
     * @return array
     */
    public function getByCreator($createdBy)
    {
        return $this->where('created_by', $createdBy)
                    ->where('is_deleted', 0)
                    ->orderBy('meeting_date', 'DESC')
                    ->findAll();
    }

    /**
     * Search meetings by title
     *
     * @param string $searchTerm
     * @return array
     */
    public function searchByTitle($searchTerm)
    {
        return $this->like('title', $searchTerm)
                    ->where('is_deleted', 0)
                    ->orderBy('meeting_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get meetings count by branch
     *
     * @param int $branchId
     * @return int
     */
    public function getCountByBranch($branchId)
    {
        return $this->where('branch_id', $branchId)
                    ->where('is_deleted', 0)
                    ->countAllResults();
    }

    /**
     * Soft delete meeting
     *
     * @param int $id
     * @param int $deletedBy
     * @return bool
     */
    public function softDelete($id, $deletedBy)
    {
        return $this->update($id, [
            'is_deleted' => 1,
            'deleted_by' => $deletedBy,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get meetings summary statistics
     *
     * @return array
     */
    public function getSummaryStats()
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_meetings')
            ->select('COUNT(*) as total_meetings,
                     SUM(CASE WHEN status = "scheduled" THEN 1 ELSE 0 END) as scheduled,
                     SUM(CASE WHEN status = "in_progress" THEN 1 ELSE 0 END) as in_progress,
                     SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
                     SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled')
            ->where('is_deleted', 0)
            ->get();

        return $query->getRowArray();
    }
}

