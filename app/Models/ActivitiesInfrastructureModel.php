<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ActivitiesInfrastructureModel
 *
 * Handles database operations for the activities_infrastructure table
 * which represents infrastructure related to activities
 */
class ActivitiesInfrastructureModel extends Model
{
    protected $table            = 'activities_infrastructure';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'activity_id',
        'infrastructure',
        'gps_coordinates',
        'infrastructure_images',
        'infrastructure_files',
        'signing_scheet_filepath',
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
        'activity_id'              => 'permit_empty|integer',
        'infrastructure'           => 'required|max_length[255]',
        'gps_coordinates'          => 'permit_empty|max_length[100]',
        'infrastructure_images'    => 'permit_empty',
        'infrastructure_files'     => 'permit_empty',
        'signing_scheet_filepath'  => 'permit_empty|max_length[500]',
        'created_by'               => 'permit_empty|integer',
        'updated_by'               => 'permit_empty|integer',
        'deleted_by'               => 'permit_empty|integer'
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
        $jsonFields = ['infrastructure_images', 'infrastructure_files'];
        
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
        $jsonFields = ['infrastructure_images', 'infrastructure_files'];
        
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
     * Get infrastructure by activity ID
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
     * Get infrastructure with activity details
     *
     * @param int $id
     * @return array|null
     */
    public function getWithActivityDetails($id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_infrastructure ai')
            ->select('ai.*, a.activity_title, a.activity_description')
            ->join('activities a', 'ai.activity_id = a.id', 'left')
            ->where('ai.id', $id)
            ->where('ai.deleted_at', null)
            ->get();

        return $query->getRowArray();
    }

    /**
     * Get all infrastructure with activity details
     *
     * @return array
     */
    public function getAllWithActivityDetails()
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_infrastructure ai')
            ->select('ai.*, a.activity_title, a.activity_description,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as updated_by_name')
            ->join('activities a', 'ai.activity_id = a.id', 'left')
            ->join('users u1', 'ai.created_by = u1.id', 'left')
            ->join('users u2', 'ai.updated_by = u2.id', 'left')
            ->where('ai.deleted_at', null)
            ->orderBy('ai.created_at', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Search infrastructure by name
     *
     * @param string $searchTerm
     * @return array
     */
    public function searchByInfrastructure($searchTerm)
    {
        return $this->like('infrastructure', $searchTerm)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get infrastructure with GPS coordinates
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
     * Get infrastructure count by activity
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
     * Get recent infrastructure (within last N days)
     *
     * @param int $days
     * @return array
     */
    public function getRecentInfrastructure($days = 30)
    {
        $startDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        return $this->where('created_at >=', $startDate)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get infrastructure by date range
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
     * Get infrastructure by creator
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
     * Add infrastructure images to existing record
     *
     * @param int $id
     * @param array $newImages
     * @return bool
     */
    public function addInfrastructureImages($id, $newImages)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        $existingImages = json_decode($record['infrastructure_images'], true) ?: [];
        $updatedImages = array_merge($existingImages, $newImages);

        return $this->update($id, [
            'infrastructure_images' => json_encode($updatedImages),
            'updated_by' => session()->get('user_id')
        ]);
    }

    /**
     * Add infrastructure files to existing record
     *
     * @param int $id
     * @param array $newFiles
     * @return bool
     */
    public function addInfrastructureFiles($id, $newFiles)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        $existingFiles = json_decode($record['infrastructure_files'], true) ?: [];
        $updatedFiles = array_merge($existingFiles, $newFiles);

        return $this->update($id, [
            'infrastructure_files' => json_encode($updatedFiles),
            'updated_by' => session()->get('user_id')
        ]);
    }

    /**
     * Remove infrastructure image from record
     *
     * @param int $id
     * @param string $imageToRemove
     * @return bool
     */
    public function removeInfrastructureImage($id, $imageToRemove)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        $existingImages = json_decode($record['infrastructure_images'], true) ?: [];
        $updatedImages = array_filter($existingImages, function($image) use ($imageToRemove) {
            return $image !== $imageToRemove;
        });

        return $this->update($id, [
            'infrastructure_images' => json_encode(array_values($updatedImages)),
            'updated_by' => session()->get('user_id')
        ]);
    }
}
