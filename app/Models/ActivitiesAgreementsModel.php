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
    protected $useSoftDeletes   = true;
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
    protected $beforeInsert = ['setDefaultStatus', 'encodeJsonFields'];
    protected $afterInsert  = [];
    protected $beforeUpdate = ['encodeJsonFields'];
    protected $afterUpdate  = [];
    protected $beforeDelete = [];
    protected $afterDelete  = [];
    protected $afterFind    = ['decodeJsonFields'];

    /**
     * Set default status for new agreements
     */
    protected function setDefaultStatus(array $data)
    {
        if (!isset($data['data']['status'])) {
            $data['data']['status'] = 'draft';
        }

        return $data;
    }

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
                    ->where('is_deleted', 0)
                    ->where('effective_date <=', $currentDate)
                    ->groupStart()
                        ->where('expiry_date >=', $currentDate)
                        ->orWhere('expiry_date', null)
                    ->groupEnd()
                    ->orderBy('effective_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get expired agreements
     *
     * @return array
     */
    public function getExpiredAgreements()
    {
        $currentDate = date('Y-m-d');
        
        return $this->where('expiry_date <', $currentDate)
                    ->where('is_deleted', 0)
                    ->orderBy('expiry_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get agreement statuses
     *
     * @return array
     */
    public function getAgreementStatuses()
    {
        return [
            'draft' => 'Draft',
            'active' => 'Active',
            'expired' => 'Expired',
            'terminated' => 'Terminated',
            'archived' => 'Archived'
        ];
    }

    /**
     * Soft delete an agreement
     *
     * @param int $id
     * @param int|null $deletedBy
     * @return bool
     */
    public function softDelete($id, $deletedBy = null)
    {
        $data = [
            'is_deleted' => 1,
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $deletedBy ?? session()->get('user_id')
        ];

        return $this->update($id, $data);
    }

    /**
     * Get agreement with activity details
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
}
