<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ActivitiesAgreementsModel
 *
 * Handles database operations for the activities_agreements table
 * which represents agreements related to activities
 */
class ActivitiesAgreementsModel extends Model
{
    protected $table            = 'activities_agreements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // Using is_deleted flag instead
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'activity_id',
        'title',
        'description',
        'agreement_type',
        'parties',
        'effective_date',
        'expiry_date',
        'status',
        'attachments',
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
        'activity_id'      => 'required|integer',
        'title'            => 'required|max_length[255]',
        'description'      => 'permit_empty',
        'agreement_type'   => 'permit_empty|max_length[100]',
        'parties'          => 'permit_empty',
        'effective_date'   => 'permit_empty|valid_date',
        'expiry_date'      => 'permit_empty|valid_date',
        'status'           => 'permit_empty|in_list[draft,active,expired,terminated,archived]',
        'attachments'      => 'permit_empty',
        'remarks'          => 'permit_empty',
        'is_deleted'       => 'permit_empty|in_list[0,1]',
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
        $jsonFields = ['parties', 'attachments'];
        
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
        $jsonFields = ['parties', 'attachments'];
        
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
     * Get agreements by activity ID
     *
     * @param int $activityId
     * @return array
     */
    public function getByActivityId($activityId)
    {
        return $this->where('activity_id', $activityId)
                    ->where('is_deleted', 0)
                    ->orderBy('effective_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get agreements with activity details
     *
     * @param int $id
     * @return array|null
     */
    public function getWithActivityDetails($id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_agreements aa')
            ->select('aa.*, a.activity_title, a.activity_description')
            ->join('activities a', 'aa.activity_id = a.id', 'left')
            ->where('aa.id', $id)
            ->where('aa.is_deleted', 0)
            ->get();

        return $query->getRowArray();
    }

    /**
     * Get all agreements with activity details
     *
     * @return array
     */
    public function getAllWithActivityDetails()
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_agreements aa')
            ->select('aa.*, a.activity_title, a.activity_description,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as updated_by_name')
            ->join('activities a', 'aa.activity_id = a.id', 'left')
            ->join('users u1', 'aa.created_by = u1.id', 'left')
            ->join('users u2', 'aa.updated_by = u2.id', 'left')
            ->where('aa.is_deleted', 0)
            ->orderBy('aa.effective_date', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get agreements by status
     *
     * @param string $status
     * @return array
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status)
                    ->where('is_deleted', 0)
                    ->orderBy('effective_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get active agreements
     *
     * @return array
     */
    public function getActiveAgreements()
    {
        $currentDate = date('Y-m-d');
        
        return $this->where('status', 'active')
                    ->where('effective_date <=', $currentDate)
                    ->groupStart()
                        ->where('expiry_date >=', $currentDate)
                        ->orWhere('expiry_date', null)
                    ->groupEnd()
                    ->where('is_deleted', 0)
                    ->orderBy('effective_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get expiring agreements (within N days)
     *
     * @param int $days
     * @return array
     */
    public function getExpiringAgreements($days = 30)
    {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+{$days} days"));

        return $this->where('expiry_date >=', $startDate)
                    ->where('expiry_date <=', $endDate)
                    ->where('status', 'active')
                    ->where('is_deleted', 0)
                    ->orderBy('expiry_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get agreements by date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getByDateRange($startDate, $endDate)
    {
        return $this->where('effective_date >=', $startDate)
                    ->where('effective_date <=', $endDate)
                    ->where('is_deleted', 0)
                    ->orderBy('effective_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get agreements by type
     *
     * @param string $agreementType
     * @return array
     */
    public function getByType($agreementType)
    {
        return $this->where('agreement_type', $agreementType)
                    ->where('is_deleted', 0)
                    ->orderBy('effective_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get agreements by creator
     *
     * @param int $createdBy
     * @return array
     */
    public function getByCreator($createdBy)
    {
        return $this->where('created_by', $createdBy)
                    ->where('is_deleted', 0)
                    ->orderBy('effective_date', 'DESC')
                    ->findAll();
    }

    /**
     * Search agreements by title
     *
     * @param string $searchTerm
     * @return array
     */
    public function searchByTitle($searchTerm)
    {
        return $this->like('title', $searchTerm)
                    ->where('is_deleted', 0)
                    ->orderBy('effective_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get agreements count by activity
     *
     * @param int $activityId
     * @return int
     */
    public function getCountByActivity($activityId)
    {
        return $this->where('activity_id', $activityId)
                    ->where('is_deleted', 0)
                    ->countAllResults();
    }

    /**
     * Soft delete agreement
     *
     * @param int $id
     * @param int $deletedBy
     * @return bool
     */
    public function softDelete($id, $deletedBy)
    {
        return $this->update($id, [
            'is_deleted' => 1,
            'deleted_by' => $deletedBy,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get agreements summary statistics
     *
     * @return array
     */
    public function getSummaryStats()
    {
        $db = \Config\Database::connect();

        $query = $db->table('activities_agreements')
            ->select('COUNT(*) as total_agreements,
                     SUM(CASE WHEN status = "draft" THEN 1 ELSE 0 END) as draft,
                     SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active,
                     SUM(CASE WHEN status = "expired" THEN 1 ELSE 0 END) as expired,
                     SUM(CASE WHEN status = "terminated" THEN 1 ELSE 0 END) as terminated,
                     SUM(CASE WHEN status = "archived" THEN 1 ELSE 0 END) as archived')
            ->where('is_deleted', 0)
            ->get();

        return $query->getRowArray();
    }
}

