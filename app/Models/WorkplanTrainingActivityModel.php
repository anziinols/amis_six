<?php
// app/Models/WorkplanTrainingActivityModel.php

namespace App\Models;

use CodeIgniter\Model;

/**
 * WorkplanTrainingActivityModel
 *
 * Handles database operations for the workplan_training_activities table.
 */
class WorkplanTrainingActivityModel extends Model
{
    protected $table            = 'workplan_training_activities';
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
        'trainers',
        'topics',
        'trainees', // JSON field
        'training_images', // JSON field
        'training_files', // JSON field
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
        'workplan_id'     => 'required|integer',
        'proposal_id'     => 'required|integer',
        'activity_id'     => 'required|integer',
        'trainers'        => 'permit_empty|string',
        'topics'          => 'permit_empty|string',
        'trainees'        => 'permit_empty|valid_json',
        'training_images' => 'permit_empty|valid_json',
        'training_files'  => 'permit_empty|valid_json',
        'gps_coordinates' => 'permit_empty|max_length[255]', // New validation rule for GPS coordinates
        'signing_sheet_filepath' => 'permit_empty|max_length[255]', // New validation rule for signing sheet file path
        'created_by'      => 'permit_empty|integer',
        'updated_by'      => 'permit_empty|integer',
        'deleted_by'      => 'permit_empty|integer'
    ];

    /**
     * Get training activity with related information
     *
     * @param int|null $id Training activity ID (optional)
     * @return array
     */
    public function getTrainingWithDetails($id = null)
    {
        $builder = $this->db->table($this->table . ' as t');
        $builder->select([
            't.*',
            'w.title as workplan_title',
            'a.title as activity_title',
            'p.date_start',
            'p.date_end',
            'p.location',
            'p.status as proposal_status'
        ]);
        $builder->join('workplans as w', 'w.id = t.workplan_id', 'left');
        $builder->join('workplan_activities as a', 'a.id = t.activity_id', 'left');
        $builder->join('proposal as p', 'p.id = t.proposal_id', 'left');

        if ($id !== null) {
            $builder->where('t.id', $id);
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
        $jsonFields = ['trainees', 'training_images', 'training_files'];

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

        if ($results) {
            foreach ($results as $key => $row) {
                $results[$key] = $this->parseJsonFields($row);
            }
        }

        return $results;
    }
}