<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * WorkplanMeetingActivityModel
 *
 * Handles database operations for the workplan_meeting_activities table
 * which represents meeting implementation data for workplan activities
 */
class WorkplanMeetingActivityModel extends Model
{
    protected $table            = 'workplan_meeting_activities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'workplan_id',
        'proposal_id',
        'activity_id',
        'meeting_title',
        'agenda',
        'meeting_date',
        'start_time',
        'end_time',
        'location',
        'participants',        // JSON field
        'meeting_minutes',     // JSON field
        'meeting_images',      // JSON field
        'meeting_files',       // JSON field
        'gps_coordinates',
        'signing_sheet_filepath',
        'remarks',
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
        'workplan_id'           => 'required|integer',
        'proposal_id'           => 'permit_empty|integer',
        'activity_id'           => 'permit_empty|integer',
        'meeting_title'         => 'required|max_length[255]',
        'agenda'                => 'permit_empty',
        'meeting_date'          => 'required|valid_date',
        'start_time'            => 'permit_empty',
        'end_time'              => 'permit_empty',
        'location'              => 'permit_empty|max_length[255]',
        'participants'          => 'permit_empty|valid_json',
        'meeting_minutes'       => 'permit_empty|valid_json',
        'meeting_images'        => 'permit_empty|valid_json',
        'meeting_files'         => 'permit_empty|valid_json',
        'gps_coordinates'       => 'permit_empty|max_length[255]',
        'signing_sheet_filepath'=> 'permit_empty|max_length[255]',
        'remarks'               => 'permit_empty',
        'created_by'            => 'permit_empty|integer',
        'updated_by'            => 'permit_empty|integer',
        'deleted_by'            => 'permit_empty|integer'
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
        $jsonFields = ['participants', 'meeting_minutes', 'meeting_images', 'meeting_files'];
        
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
        $jsonFields = ['participants', 'meeting_minutes', 'meeting_images', 'meeting_files'];
        
        if (isset($data['data'])) {
            // Single record
            foreach ($jsonFields as $field) {
                if (isset($data['data'][$field]) && is_string($data['data'][$field])) {
                    $data['data'][$field] = json_decode($data['data'][$field], true) ?: [];
                }
            }
        } else {
            // Multiple records
            foreach ($data as $key => $record) {
                if (is_array($record)) {
                    foreach ($jsonFields as $field) {
                        if (isset($record[$field]) && is_string($record[$field])) {
                            $data[$key][$field] = json_decode($record[$field], true) ?: [];
                        }
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Get meeting activity with related information
     *
     * @param int|null $id Meeting activity ID (optional)
     * @return array
     */
    public function getMeetingWithDetails($id = null)
    {
        $builder = $this->db->table($this->table . ' as m');
        $builder->select([
            'm.*',
            'w.title as workplan_title',
            'a.title as activity_title',
            'a.activity_type',
            'p.date_start',
            'p.date_end',
            'p.location as proposal_location',
            'p.status as proposal_status'
        ]);
        $builder->join('workplans as w', 'w.id = m.workplan_id', 'left');
        $builder->join('workplan_activities as a', 'a.id = m.activity_id', 'left');
        $builder->join('proposal as p', 'p.id = m.proposal_id', 'left');

        if ($id !== null) {
            $builder->where('m.id', $id);
            return $builder->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Parse JSON fields in the result
     *
     * @param array $data The data to parse
     * @return array The data with JSON fields parsed
     */
    protected function parseJsonFields($data)
    {
        $jsonFields = ['participants', 'meeting_minutes', 'meeting_images', 'meeting_files'];

        foreach ($jsonFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = json_decode($data[$field], true) ?: [];
            }
        }

        return $data;
    }

    /**
     * Find a record by ID with JSON fields decoded
     */
    public function find($id = null)
    {
        $result = parent::find($id);

        if (!$result) {
            return null;
        }

        return $this->parseJsonFields($result);
    }

    /**
     * Find all records with JSON fields decoded
     */
    public function findAll(?int $limit = null, int $offset = 0)
    {
        $results = parent::findAll($limit, $offset);

        if (!$results) {
            return [];
        }

        foreach ($results as $key => $result) {
            $results[$key] = $this->parseJsonFields($result);
        }

        return $results;
    }

    /**
     * Get meeting activities by activity ID
     *
     * @param int $activityId
     * @return array
     */
    public function getByActivityId($activityId)
    {
        return $this->where('activity_id', $activityId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get meeting activities by workplan ID
     *
     * @param int $workplanId
     * @return array
     */
    public function getByWorkplanId($workplanId)
    {
        return $this->where('workplan_id', $workplanId)
                    ->orderBy('meeting_date', 'DESC')
                    ->findAll();
    }
}
