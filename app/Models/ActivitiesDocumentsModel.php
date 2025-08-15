<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ActivitiesDocumentsModel
 *
 * Handles database operations for the activities_documents table
 * which represents documents related to activities
 */
class ActivitiesDocumentsModel extends Model
{
    protected $table            = 'activities_documents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'activity_id',
        'document_files',
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
        'activity_id'      => 'permit_empty|integer',
        'document_files'   => 'permit_empty',
        'remarks'          => 'required',
        'created_by'       => 'permit_empty|integer',
        'updated_by'       => 'permit_empty|integer',
        'deleted_by'       => 'permit_empty|integer'
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
        $jsonFields = ['document_files'];
        
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
        $jsonFields = ['document_files'];
        
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
     * Get documents by activity ID
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
     * Get document with activity details
     *
     * @param int $id
     * @return array|null
     */
    public function getWithActivityDetails($id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_documents ad')
            ->select('ad.*, a.activity_title, a.activity_description')
            ->join('activities a', 'ad.activity_id = a.id', 'left')
            ->where('ad.id', $id)
            ->where('ad.deleted_at', null)
            ->get();

        return $query->getRowArray();
    }

    /**
     * Get all documents with activity details
     *
     * @return array
     */
    public function getAllWithActivityDetails()
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_documents ad')
            ->select('ad.*, a.activity_title, a.activity_description,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as updated_by_name')
            ->join('activities a', 'ad.activity_id = a.id', 'left')
            ->join('users u1', 'ad.created_by = u1.id', 'left')
            ->join('users u2', 'ad.updated_by = u2.id', 'left')
            ->where('ad.deleted_at', null)
            ->orderBy('ad.created_at', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Search documents by remarks
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
     * Get documents count by activity
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
     * Get recent documents (within last N days)
     *
     * @param int $days
     * @return array
     */
    public function getRecentDocuments($days = 30)
    {
        $startDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        return $this->where('created_at >=', $startDate)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get documents by date range
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
     * Get documents by creator
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
     * Add document files to existing record
     *
     * @param int $id
     * @param array $newFiles
     * @return bool
     */
    public function addDocumentFiles($id, $newFiles)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        $existingFiles = json_decode($record['document_files'], true) ?: [];
        $updatedFiles = array_merge($existingFiles, $newFiles);

        return $this->update($id, [
            'document_files' => json_encode($updatedFiles),
            'updated_by' => session()->get('user_id')
        ]);
    }

    /**
     * Remove document file from record
     *
     * @param int $id
     * @param string $fileToRemove
     * @return bool
     */
    public function removeDocumentFile($id, $fileToRemove)
    {
        $record = $this->find($id);
        if (!$record) {
            return false;
        }

        $existingFiles = json_decode($record['document_files'], true) ?: [];
        $updatedFiles = array_filter($existingFiles, function($file) use ($fileToRemove) {
            return $file !== $fileToRemove;
        });

        return $this->update($id, [
            'document_files' => json_encode(array_values($updatedFiles)),
            'updated_by' => session()->get('user_id')
        ]);
    }
}
