<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ActivitiesOutputModel
 *
 * Handles database operations for the activities_output table
 * which represents outputs related to activities
 */
class ActivitiesOutputModel extends Model
{
    protected $table            = 'activities_output';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'activity_id',
        'outputs',
        'output_images',
        'output_files',
        'beneficiaries',
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

    // Validation rules
    protected $validationRules = [
        'activity_id'             => 'permit_empty|integer',
        'outputs'                 => 'permit_empty',
        'output_images'           => 'permit_empty',
        'output_files'            => 'permit_empty',
        'beneficiaries'           => 'permit_empty',
        'total_value'             => 'permit_empty|decimal',
        'gps_coordinates'         => 'permit_empty|max_length[255]',
        'signing_sheet_filepath'  => 'permit_empty|max_length[255]',
        'remarks'                 => 'permit_empty',
        'created_by'              => 'permit_empty|integer',
        'updated_by'              => 'permit_empty|integer',
        'deleted_by'              => 'permit_empty|integer'
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
        $jsonFields = ['outputs', 'output_images', 'output_files', 'beneficiaries'];
        
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
        $jsonFields = ['outputs', 'output_images', 'output_files', 'beneficiaries'];
        
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
     * Get outputs by activity ID
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
     * Get output with activity details
     *
     * @param int $id
     * @return array|null
     */
    public function getWithActivityDetails($id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_output ao')
            ->select('ao.*, a.activity_title, a.activity_description')
            ->join('activities a', 'ao.activity_id = a.id', 'left')
            ->where('ao.id', $id)
            ->where('ao.deleted_at', null)
            ->get();

        return $query->getRowArray();
    }

    /**
     * Get all outputs with activity details
     *
     * @return array
     */
    public function getAllWithActivityDetails()
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_output ao')
            ->select('ao.*, a.activity_title, a.activity_description,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as updated_by_name')
            ->join('activities a', 'ao.activity_id = a.id', 'left')
            ->join('users u1', 'ao.created_by = u1.id', 'left')
            ->join('users u2', 'ao.updated_by = u2.id', 'left')
            ->where('ao.deleted_at', null)
            ->orderBy('ao.created_at', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get outputs with GPS coordinates
     *
     * @return array
     */
    public function getWithGpsCoordinates()
    {
        return $this->where('gps_coordinates IS NOT NULL')
                    ->where('gps_coordinates !=', '')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get outputs count by activity
     *
     * @param int $activityId
     * @return int
     */
    public function getCountByActivity($activityId)
    {
        return $this->where('activity_id', $activityId)
                    ->where('deleted_at', null)
                    ->countAllResults();
    }

    /**
     * Get total value by activity
     *
     * @param int $activityId
     * @return float
     */
    public function getTotalValueByActivity($activityId)
    {
        $result = $this->selectSum('total_value')
                       ->where('activity_id', $activityId)
                       ->where('deleted_at', null)
                       ->first();

        return $result['total_value'] ?? 0;
    }

    /**
     * Get recent outputs (within last N days)
     *
     * @param int $days
     * @return array
     */
    public function getRecentOutputs($days = 30)
    {
        $startDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        return $this->where('created_at >=', $startDate)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get outputs by date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getByDateRange($startDate, $endDate)
    {
        return $this->where('created_at >=', $startDate)
                    ->where('created_at <=', $endDate)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get outputs by creator
     *
     * @param int $createdBy
     * @return array
     */
    public function getByCreator($createdBy)
    {
        return $this->where('created_by', $createdBy)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get outputs by value range
     *
     * @param float $minValue
     * @param float $maxValue
     * @return array
     */
    public function getByValueRange($minValue, $maxValue)
    {
        return $this->where('total_value >=', $minValue)
                    ->where('total_value <=', $maxValue)
                    ->orderBy('total_value', 'DESC')
                    ->findAll();
    }

    /**
     * Add output images to existing record
     *
     * @param int $id
     * @param array $newImages
     * @return bool
     */
    public function addOutputImages($id, $newImages)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        $existingImages = json_decode($record['output_images'], true) ?: [];
        $updatedImages = array_merge($existingImages, $newImages);

        return $this->update($id, [
            'output_images' => json_encode($updatedImages),
            'updated_by' => session()->get('user_id')
        ]);
    }

    /**
     * Add output files to existing record
     *
     * @param int $id
     * @param array $newFiles
     * @return bool
     */
    public function addOutputFiles($id, $newFiles)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        $existingFiles = json_decode($record['output_files'], true) ?: [];
        $updatedFiles = array_merge($existingFiles, $newFiles);

        return $this->update($id, [
            'output_files' => json_encode($updatedFiles),
            'updated_by' => session()->get('user_id')
        ]);
    }

    /**
     * Add outputs to existing record
     *
     * @param int $id
     * @param array $newOutputs
     * @return bool
     */
    public function addOutputs($id, $newOutputs)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        $existingOutputs = json_decode($record['outputs'], true) ?: [];
        $updatedOutputs = array_merge($existingOutputs, $newOutputs);

        return $this->update($id, [
            'outputs' => json_encode($updatedOutputs),
            'updated_by' => session()->get('user_id')
        ]);
    }

    /**
     * Add beneficiaries to existing record
     *
     * @param int $id
     * @param array $newBeneficiaries
     * @return bool
     */
    public function addBeneficiaries($id, $newBeneficiaries)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        $existingBeneficiaries = json_decode($record['beneficiaries'], true) ?: [];
        $updatedBeneficiaries = array_merge($existingBeneficiaries, $newBeneficiaries);

        return $this->update($id, [
            'beneficiaries' => json_encode($updatedBeneficiaries),
            'updated_by' => session()->get('user_id')
        ]);
    }

    /**
     * Search outputs by remarks
     *
     * @param string $searchTerm
     * @return array
     */
    public function searchByRemarks($searchTerm)
    {
        return $this->like('remarks', $searchTerm)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get summary statistics
     *
     * @return array
     */
    public function getSummaryStats()
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_output')
            ->select('COUNT(*) as total_outputs, SUM(total_value) as total_value')
            ->where('deleted_at', null)
            ->get();

        return $query->getRowArray();
    }
}
