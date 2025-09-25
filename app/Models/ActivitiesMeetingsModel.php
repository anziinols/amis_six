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
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'activity_id',
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
        'gps_coordinates',
        'signing_sheet_filepath',
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
        'activity_id'               => 'permit_empty|integer',
        'branch_id'                 => 'permit_empty|integer',
        'title'                     => 'required|max_length[255]',
        'agenda'                    => 'permit_empty',
        'meeting_date'              => 'required|valid_date',
        'start_time'                => 'permit_empty',
        'end_time'                  => 'permit_empty',
        'location'                  => 'permit_empty|max_length[255]',
        'participants'              => 'permit_empty',
        'status'                    => 'permit_empty|in_list[scheduled,in_progress,completed,cancelled]',
        'minutes'                   => 'permit_empty',
        'attachments'               => 'permit_empty',
        'gps_coordinates'           => 'permit_empty|max_length[255]',
        'signing_sheet_filepath'    => 'permit_empty|max_length[500]',
        'remarks'                   => 'permit_empty',
        'is_deleted'                => 'permit_empty|in_list[0,1]',
        'created_by'                => 'permit_empty|integer',
        'updated_by'                => 'permit_empty|integer',
        'deleted_by'                => 'permit_empty|integer'
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['setDefaultStatus', 'encodeJsonFields'];
    protected $afterInsert  = [];
    protected $beforeUpdate = ['encodeJsonFields'];
    protected $afterUpdate  = [];
    protected $beforeDelete = [];
    protected $afterDelete  = [];
    protected $afterFind    = ['decodeJsonFields'];

    /**
     * Set default status for new meetings
     */
    protected function setDefaultStatus(array $data)
    {
        if (!isset($data['data']['status'])) {
            $data['data']['status'] = 'scheduled';
        }

        return $data;
    }

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
     * @param int $days Number of days ahead to look
     * @return array
     */
    public function getUpcomingMeetings($days = 30)
    {
        $currentDate = date('Y-m-d H:i:s');
        $endDate = date('Y-m-d H:i:s', strtotime("+{$days} days"));
        
        return $this->where('meeting_date >=', $currentDate)
                    ->where('meeting_date <=', $endDate)
                    ->where('status !=', 'cancelled')
                    ->where('is_deleted', 0)
                    ->orderBy('meeting_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get past meetings
     *
     * @return array
     */
    public function getPastMeetings()
    {
        $currentDate = date('Y-m-d H:i:s');
        
        return $this->where('meeting_date <', $currentDate)
                    ->where('is_deleted', 0)
                    ->orderBy('meeting_date', 'DESC')
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
                    ->orderBy('meeting_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get meeting with branch details
     *
     * @param int $id
     * @return array|null
     */
    public function getWithBranchDetails($id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_meetings am')
            ->select('am.*, b.name as branch_name')
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
            ->select('am.*, b.name as branch_name,
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
     * Get meeting statuses
     *
     * @return array
     */
    public function getMeetingStatuses()
    {
        return [
            'scheduled' => 'Scheduled',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];
    }

    /**
     * Update meeting status
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus($id, $status)
    {
        return $this->update($id, [
            'status' => $status,
            'updated_by' => session()->get('user_id')
        ]);
    }

    /**
     * Add participants to meeting
     *
     * @param int $id
     * @param array $newParticipants
     * @return bool
     */
    public function addParticipants($id, $newParticipants)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        $existingParticipants = json_decode($record['participants'], true) ?: [];
        $updatedParticipants = array_merge($existingParticipants, $newParticipants);

        return $this->update($id, [
            'participants' => json_encode($updatedParticipants),
            'updated_by' => session()->get('user_id')
        ]);
    }

    /**
     * Add meeting minutes
     *
     * @param int $id
     * @param array $minutes
     * @return bool
     */
    public function addMinutes($id, $minutes)
    {
        return $this->update($id, [
            'minutes' => json_encode($minutes),
            'updated_by' => session()->get('user_id')
        ]);
    }

    /**
     * Add attachments to meeting
     *
     * @param int $id
     * @param array $newAttachments
     * @return bool
     */
    public function addAttachments($id, $newAttachments)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        $existingAttachments = json_decode($record['attachments'], true) ?: [];
        $updatedAttachments = array_merge($existingAttachments, $newAttachments);

        return $this->update($id, [
            'attachments' => json_encode($updatedAttachments),
            'updated_by' => session()->get('user_id')
        ]);
    }

    /**
     * Soft delete a meeting
     *
     * @param int $id
     * @param int|null $deletedBy
     * @return bool
     */
    public function softDelete($id, $deletedBy = null)
    {
        $data = [
            'is_deleted' => 1,
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $deletedBy ?? session()->get('user_id')
        ];

        return $this->update($id, $data);
    }

    /**
     * Search meetings by title or agenda
     *
     * @param string $searchTerm
     * @return array
     */
    public function searchMeetings($searchTerm)
    {
        return $this->groupStart()
                        ->like('title', $searchTerm)
                        ->orLike('agenda', $searchTerm)
                        ->orLike('location', $searchTerm)
                    ->groupEnd()
                    ->where('is_deleted', 0)
                    ->orderBy('meeting_date', 'DESC')
                    ->findAll();
    }
}
