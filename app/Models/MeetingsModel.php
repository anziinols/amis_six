<?php
// app/Models/MeetingsModel.php

namespace App\Models;

use CodeIgniter\Model;

/**
 * MeetingsModel
 * 
 * Handles database operations for the meetings table
 */
class MeetingsModel extends Model
{
    protected $table            = 'meetings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
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
        'recurrence_rule',
        'remarks',
        'is_deleted',
        'created_by',
        'updated_by',
        'deleted_by',
        'access_type'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
    // Validation
    protected $validationRules = [
        'branch_id'      => 'required|integer',
        'title'          => 'required|max_length[255]',
        'agenda'         => 'permit_empty',
        'meeting_date'   => 'required|valid_date',
        'start_time'     => 'permit_empty|valid_date',
        'end_time'       => 'permit_empty|valid_date',
        'location'       => 'permit_empty|max_length[255]',
        'status'         => 'permit_empty|in_list[scheduled,in_progress,completed,cancelled]',
        'recurrence_rule'=> 'permit_empty|max_length[255]',
        'remarks'        => 'permit_empty',
        'is_deleted'     => 'permit_empty|in_list[0,1]',
        'created_by'     => 'permit_empty|integer',
        'access_type'    => 'permit_empty|in_list[private,internal,public]'
    ];
    
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
    
    // Callbacks
    protected $beforeInsert = ['prepareJsonFields', 'setDefaultStatus'];
    protected $afterInsert  = [];
    protected $beforeUpdate = ['prepareJsonFields'];
    protected $afterUpdate  = [];
    protected $beforeDelete = [];
    protected $afterDelete  = [];
    
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
     * Prepare JSON fields before saving to database
     */
    protected function prepareJsonFields(array $data)
    {
        $jsonFields = ['participants', 'minutes', 'attachments'];
        
        foreach ($jsonFields as $field) {
            if (isset($data['data'][$field])) {
                // If the field is an array or object, convert to JSON string
                if (is_array($data['data'][$field]) || is_object($data['data'][$field])) {
                    $data['data'][$field] = json_encode($data['data'][$field]);
                }
            }
        }
        
        return $data;
    }
    
    /**
     * Find a meeting by ID with JSON fields decoded
     */
    public function find($id = null, $columns = '*')
    {
        $meeting = parent::find($id, $columns);
        
        if (!$meeting) {
            return null;
        }
        
        return $this->parseJsonFields($meeting);
    }
    
    /**
     * Find all meetings with JSON fields decoded
     */
    public function findAll(?int $limit = null, int $offset = 0)
    {
        $data = parent::findAll($limit, $offset);
        
        if ($data) {
            foreach ($data as $key => $row) {
                $data[$key] = $this->parseJsonFields($row);
            }
        }
        
        return $data;
    }
    
    /**
     * Parse JSON fields in data
     */
    private function parseJsonFields($data)
    {
        $jsonFields = ['participants', 'minutes', 'attachments'];
        
        foreach ($jsonFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                try {
                    $jsonData = json_decode($data[$field], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $data[$field] = $jsonData;
                    } else {
                        log_message('error', "JSON decode error for field {$field}: " . json_last_error_msg());
                        $data[$field] = [];
                    }
                } catch (\Exception $e) {
                    log_message('error', "Exception decoding JSON for field {$field}: " . $e->getMessage());
                    $data[$field] = [];
                }
            }
        }
        
        return $data;
    }
    
    /**
     * Get meetings with branch information
     * 
     * @param int|null $id Meeting ID (optional)
     * @return array
     */
    public function getMeetingsWithBranch($id = null)
    {
        $builder = $this->db->table($this->table . ' as m');
        $builder->select([
            'm.*',
            'b.name as branch_name'
        ]);
        $builder->join('branches as b', 'b.id = m.branch_id', 'left');
        
        // Add where clause for non-deleted records
        $builder->where('m.deleted_at IS NULL');
        $builder->where('m.is_deleted', 0);
        
        if ($id !== null) {
            $builder->where('m.id', $id);
            return $builder->get()->getRowArray() ?? [];
        }
        
        return $builder->get()->getResultArray() ?? [];
    }
    
    /**
     * Get upcoming meetings
     * 
     * @param int $limit Number of meetings to return
     * @return array
     */
    public function getUpcomingMeetings($limit = 5)
    {
        $builder = $this->db->table($this->table . ' as m');
        $builder->select([
            'm.*',
            'b.name as branch_name'
        ]);
        $builder->join('branches as b', 'b.id = m.branch_id', 'left');
        
        // Add where clause for non-deleted records and upcoming meetings
        $builder->where('m.deleted_at IS NULL');
        $builder->where('m.is_deleted', 0);
        $builder->where('m.status', 'scheduled');
        $builder->where('m.meeting_date >=', date('Y-m-d H:i:s'));
        
        // Order by meeting date
        $builder->orderBy('m.meeting_date', 'ASC');
        
        // Limit the number of results
        $builder->limit($limit);
        
        return $builder->get()->getResultArray() ?? [];
    }
    
    /**
     * Get meetings by branch
     * 
     * @param int $branchId Branch ID
     * @return array
     */
    public function getMeetingsByBranch($branchId)
    {
        $builder = $this->db->table($this->table . ' as m');
        $builder->select([
            'm.*',
            'b.name as branch_name'
        ]);
        $builder->join('branches as b', 'b.id = m.branch_id', 'left');
        
        // Add where clause for non-deleted records and branch
        $builder->where('m.deleted_at IS NULL');
        $builder->where('m.is_deleted', 0);
        $builder->where('m.branch_id', $branchId);
        
        // Order by meeting date
        $builder->orderBy('m.meeting_date', 'DESC');
        
        return $builder->get()->getResultArray() ?? [];
    }
    
    /**
     * Update meeting status
     * 
     * @param int $id Meeting ID
     * @param string $status New status
     * @param int $userId User ID making the change
     * @return bool
     */
    public function updateStatus($id, $status, $userId)
    {
        $validStatuses = ['scheduled', 'in_progress', 'completed', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            return false;
        }
        
        return $this->update($id, [
            'status' => $status,
            'updated_by' => $userId
        ]);
    }
}
