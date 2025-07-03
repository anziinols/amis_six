<?php
// app/Models/WorkplanInputActivityModel.php

namespace App\Models;

use CodeIgniter\Model;

/**
 * WorkplanInputActivityModel
 *
 * Handles database operations for the workplan_input_activities table.
 */
class WorkplanInputActivityModel extends Model
{
    protected $table            = 'workplan_input_activities';
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
        'input_images', // JSON field (stored as longtext)
        'input_files',  // JSON field (stored as longtext)
        'inputs',       // JSON field (stored as longtext)
        'gps_coordinates', // New field for GPS coordinates
        'signing_sheet_filepath', // New field for signing sheet file path
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
        'workplan_id'    => 'required|integer',
        'proposal_id'    => 'permit_empty|integer',
        'activity_id'    => 'permit_empty|integer',
        'input_images'   => 'permit_empty|valid_json', // JSON array of image paths
        'input_files'    => 'permit_empty|valid_json', // JSON array of file paths
        'inputs'         => 'permit_empty|valid_json', // JSON array of input details
        'gps_coordinates' => 'permit_empty|max_length[255]', // New validation rule for GPS coordinates
        'signing_sheet_filepath' => 'permit_empty|max_length[255]', // New validation rule for signing sheet file path
        'created_by'     => 'permit_empty|integer',
        'updated_by'     => 'permit_empty|integer',
        'deleted_by'     => 'permit_empty|integer'
    ];

    // Callbacks
    protected $beforeInsert = [];
    protected $afterInsert  = [];
    protected $beforeUpdate = [];
    protected $afterUpdate  = [];
    protected $beforeDelete = [];
    protected $afterDelete  = [];

    /**
     * Get input activity with related information
     *
     * @param int|null $id Input activity ID (optional)
     * @return array
     */
    public function getInputWithDetails($id = null)
    {
        $builder = $this->db->table($this->table . ' as i');
        $builder->select([
            'i.*',
            'w.title as workplan_title',
            'a.title as activity_title',
            'a.activity_type',
            'p.date_start',
            'p.date_end',
            'p.location',
            'p.status as proposal_status'
        ]);
        $builder->join('workplans as w', 'w.id = i.workplan_id', 'left');
        $builder->join('workplan_activities as a', 'a.id = i.activity_id', 'left');
        $builder->join('proposal as p', 'p.id = i.proposal_id', 'left');

        if ($id !== null) {
            $builder->where('i.id', $id);
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
        $jsonFields = ['input_images', 'input_files', 'inputs'];

        foreach ($jsonFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = json_decode($data[$field], true);
            }
        }

        return $data;
    }

    /**
     * Find a record by ID with JSON fields decoded
     */
    public function find($id = null, $columns = '*')
    {
        $result = parent::find($id, $columns);

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

        if ($results) {
            foreach ($results as $key => $row) {
                $results[$key] = $this->parseJsonFields($row);
            }
        }

        return $results;
    }

    /**
     * Get input activities by workplan ID
     *
     * @param int $workplanId Workplan ID
     * @return array
     */
    public function getByWorkplanId($workplanId)
    {
        $results = $this->where('workplan_id', $workplanId)->findAll();
        return $results;
    }

    /**
     * Get input activities by proposal ID
     *
     * @param int $proposalId Proposal ID
     * @return array
     */
    public function getByProposalId($proposalId)
    {
        $results = $this->where('proposal_id', $proposalId)->findAll();
        return $results;
    }

    /**
     * Get input activities by activity ID
     *
     * @param int $activityId Activity ID
     * @return array
     */
    public function getByActivityId($activityId)
    {
        $results = $this->where('activity_id', $activityId)->findAll();
        return $results;
    }
}