<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ActivitiesTrainingModel
 *
 * Handles database operations for the activities_training table
 * which represents training related to activities
 */
class ActivitiesTrainingModel extends Model
{
    protected $table            = 'activities_training';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'activity_id',
        'trainers',
        'topics',
        'trainees',
        'training_images',
        'training_files',
        'gps_coordinates',
        'signing_sheet_filepath',
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
        'activity_id'             => 'required|integer',
        'trainers'                => 'permit_empty',
        'topics'                  => 'permit_empty',
        'trainees'                => 'permit_empty',
        'training_images'         => 'permit_empty',
        'training_files'          => 'permit_empty',
        'gps_coordinates'         => 'permit_empty|max_length[255]',
        'signing_sheet_filepath'  => 'permit_empty|max_length[255]',
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
        $jsonFields = ['trainees', 'training_images', 'training_files'];
        
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
        $jsonFields = ['trainees', 'training_images', 'training_files'];
        
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
     * Get training by activity ID
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
     * Get training with activity details
     *
     * @param int $id
     * @return array|null
     */
    public function getWithActivityDetails($id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_training at')
            ->select('at.*, a.activity_title, a.activity_description')
            ->join('activities a', 'at.activity_id = a.id', 'left')
            ->where('at.id', $id)
            ->where('at.deleted_at', null)
            ->get();

        return $query->getRowArray();
    }

    /**
     * Get all training with activity details
     *
     * @return array
     */
    public function getAllWithActivityDetails()
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_training at')
            ->select('at.*, a.activity_title, a.activity_description,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as updated_by_name')
            ->join('activities a', 'at.activity_id = a.id', 'left')
            ->join('users u1', 'at.created_by = u1.id', 'left')
            ->join('users u2', 'at.updated_by = u2.id', 'left')
            ->where('at.deleted_at', null)
            ->orderBy('at.created_at', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get training with GPS coordinates
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
     * Get training count by activity
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
     * Get recent training (within last N days)
     *
     * @param int $days
     * @return array
     */
    public function getRecentTraining($days = 30)
    {
        $startDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        return $this->where('created_at >=', $startDate)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get training by date range
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
     * Get training by creator
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
     * Search training by trainers
     *
     * @param string $searchTerm
     * @return array
     */
    public function searchByTrainers($searchTerm)
    {
        return $this->like('trainers', $searchTerm)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Search training by topics
     *
     * @param string $searchTerm
     * @return array
     */
    public function searchByTopics($searchTerm)
    {
        return $this->like('topics', $searchTerm)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get training count by trainer
     *
     * @param string $trainer
     * @return int
     */
    public function getCountByTrainer($trainer)
    {
        return $this->like('trainers', $trainer)
                    ->where('deleted_at', null)
                    ->countAllResults();
    }

    /**
     * Add training images to existing record
     *
     * @param int $id
     * @param array $newImages
     * @return bool
     */
    public function addTrainingImages($id, $newImages)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        $existingImages = json_decode($record['training_images'], true) ?: [];
        $updatedImages = array_merge($existingImages, $newImages);

        return $this->update($id, [
            'training_images' => json_encode($updatedImages),
            'updated_by' => session()->get('user_id')
        ]);
    }

    /**
     * Add training files to existing record
     *
     * @param int $id
     * @param array $newFiles
     * @return bool
     */
    public function addTrainingFiles($id, $newFiles)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        $existingFiles = json_decode($record['training_files'], true) ?: [];
        $updatedFiles = array_merge($existingFiles, $newFiles);

        return $this->update($id, [
            'training_files' => json_encode($updatedFiles),
            'updated_by' => session()->get('user_id')
        ]);
    }

    /**
     * Add trainees to existing record
     *
     * @param int $id
     * @param array $newTrainees
     * @return bool
     */
    public function addTrainees($id, $newTrainees)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        $existingTrainees = json_decode($record['trainees'], true) ?: [];
        $updatedTrainees = array_merge($existingTrainees, $newTrainees);

        return $this->update($id, [
            'trainees' => json_encode($updatedTrainees),
            'updated_by' => session()->get('user_id')
        ]);
    }

    /**
     * Remove training image from record
     *
     * @param int $id
     * @param string $imageToRemove
     * @return bool
     */
    public function removeTrainingImage($id, $imageToRemove)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        $existingImages = json_decode($record['training_images'], true) ?: [];
        $updatedImages = array_filter($existingImages, function($image) use ($imageToRemove) {
            return $image !== $imageToRemove;
        });

        return $this->update($id, [
            'training_images' => json_encode(array_values($updatedImages)),
            'updated_by' => session()->get('user_id')
        ]);
    }

    /**
     * Remove training file from record
     *
     * @param int $id
     * @param string $fileToRemove
     * @return bool
     */
    public function removeTrainingFile($id, $fileToRemove)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        $existingFiles = json_decode($record['training_files'], true) ?: [];
        $updatedFiles = array_filter($existingFiles, function($file) use ($fileToRemove) {
            return $file !== $fileToRemove;
        });

        return $this->update($id, [
            'training_files' => json_encode(array_values($updatedFiles)),
            'updated_by' => session()->get('user_id')
        ]);
    }

    /**
     * Get training summary statistics
     *
     * @return array
     */
    public function getSummaryStats()
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_training')
            ->select('COUNT(*) as total_training')
            ->where('deleted_at', null)
            ->get();

        return $query->getRowArray();
    }
}
