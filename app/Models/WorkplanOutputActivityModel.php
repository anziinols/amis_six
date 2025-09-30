<?php
// app/Models/WorkplanOutputActivityModel.php

namespace App\Models;

use CodeIgniter\Model;

/**
 * WorkplanOutputActivityModel
 *
 * Handles database operations for the workplan_output_activities table
 * which represents output activities with deliverables and beneficiaries
 */
class WorkplanOutputActivityModel extends Model
{
    protected $table            = 'workplan_output_activities';
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
        'outputs',              // JSON field (stored as longtext)
        'output_images',        // JSON field (stored as longtext)
        'output_files',         // JSON field (stored as longtext)
        'delivery_date',
        'delivery_location',
        'beneficiaries',        // JSON field (stored as longtext)
        'total_value',
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
        'outputs'               => 'permit_empty|valid_json', // JSON array of output details
        'output_images'         => 'permit_empty|valid_json', // JSON array of image paths
        'output_files'          => 'permit_empty|valid_json', // JSON array of file paths
        'delivery_date'         => 'permit_empty|valid_date',
        'delivery_location'     => 'permit_empty|max_length[255]',
        'beneficiaries'         => 'permit_empty|valid_json', // JSON array of beneficiary details
        'total_value'           => 'permit_empty|decimal',
        'gps_coordinates'       => 'permit_empty|max_length[255]',
        'signing_sheet_filepath' => 'permit_empty|max_length[255]',
        'remarks'               => 'permit_empty|string',
        'created_by'            => 'permit_empty|integer',
        'updated_by'            => 'permit_empty|integer',
        'deleted_by'            => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'workplan_id' => [
            'required' => 'Workplan ID is required',
            'integer' => 'Workplan ID must be a valid integer'
        ],
        'outputs' => [
            'valid_json' => 'Outputs must be valid JSON format'
        ],
        'output_images' => [
            'valid_json' => 'Output images must be valid JSON format'
        ],
        'output_files' => [
            'valid_json' => 'Output files must be valid JSON format'
        ],
        'beneficiaries' => [
            'valid_json' => 'Beneficiaries must be valid JSON format'
        ],
        'delivery_date' => [
            'valid_date' => 'Delivery date must be a valid date'
        ],
        'total_value' => [
            'decimal' => 'Total value must be a valid decimal number'
        ]
    ];

    /**
     * Get output activity with related information
     *
     * @param int|null $id Output activity ID (optional)
     * @return array
     */
    public function getOutputWithDetails($id = null)
    {
        $builder = $this->db->table($this->table . ' as o');
        $builder->select([
            'o.*',
            'w.title as workplan_title',
            'a.title as activity_title',
            'a.target_output',
            'p.date_start',
            'p.date_end',
            'p.location',
            'p.status as proposal_status'
        ]);
        $builder->join('workplans as w', 'w.id = o.workplan_id', 'left');
        $builder->join('workplan_activities as a', 'a.id = o.activity_id', 'left');
        $builder->join('proposal as p', 'p.id = o.proposal_id', 'left');

        if ($id !== null) {
            $builder->where('o.id', $id);
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
        $jsonFields = ['outputs', 'output_images', 'output_files', 'beneficiaries'];

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

    /**
     * Get output activities by workplan ID
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
     * Get output activities by proposal ID
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
     * Get output activities by activity ID
     *
     * @param int $activityId Activity ID
     * @return array
     */
    public function getByActivityId($activityId)
    {
        $results = $this->where('activity_id', $activityId)->findAll();
        return $results;
    }

    /**
     * Get output activities with total value statistics
     *
     * @param array $conditions Optional conditions for filtering
     * @return array
     */
    public function getOutputsWithStats($conditions = [])
    {
        $builder = $this->db->table($this->table);
        $builder->select($this->table . '.*, workplans.title as workplan_title');
        $builder->selectSum('total_value', 'total_outputs_value');
        $builder->join('workplans', 'workplans.id = ' . $this->table . '.workplan_id');

        if (!empty($conditions)) {
            $builder->where($conditions);
        }

        $builder->where($this->table . '.deleted_at IS NULL');
        $builder->groupBy($this->table . '.id');

        return $builder->get()->getResultArray();
    }

    /**
     * Get outputs by delivery date range
     *
     * @param string $startDate Start date (Y-m-d format)
     * @param string $endDate End date (Y-m-d format)
     * @return array
     */
    public function getByDeliveryDateRange($startDate, $endDate)
    {
        return $this->where('delivery_date >=', $startDate)
                    ->where('delivery_date <=', $endDate)
                    ->where('deleted_at IS NULL')
                    ->findAll();
    }

    /**
     * Get outputs by delivery location
     *
     * @param string $location Delivery location
     * @return array
     */
    public function getByDeliveryLocation($location)
    {
        return $this->like('delivery_location', $location)
                    ->where('deleted_at IS NULL')
                    ->findAll();
    }
}
