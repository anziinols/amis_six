<?php
// app/Models/AgreementsModel.php

namespace App\Models;

use CodeIgniter\Model;

/**
 * AgreementsModel
 *
 * Handles database operations for the agreements table
 * which represents legal agreements and contracts
 */
class AgreementsModel extends Model
{
    protected $table            = 'agreements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'branch_id',
        'title',
        'description',
        'agreement_type',
        'parties',
        'effective_date',
        'expiry_date',
        'status',
        'terms',
        'conditions',
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

    // Validation
    protected $validationRules = [
        'branch_id'      => 'required|integer',
        'title'          => 'required|max_length[255]',
        'description'    => 'permit_empty',
        'agreement_type' => 'permit_empty|max_length[100]',
        'effective_date' => 'required|valid_date',
        'expiry_date'    => 'permit_empty|valid_date',
        'status'         => 'permit_empty|in_list[draft,active,expired,terminated,archived]',
        'terms'          => 'permit_empty',
        'conditions'     => 'permit_empty',
        'remarks'        => 'permit_empty',
        'is_deleted'     => 'permit_empty|in_list[0,1]',
        'created_by'     => 'permit_empty|integer'
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['prepareJsonFields', 'setDefaultStatus'];
    protected $afterInsert  = [];
    protected $beforeUpdate = ['prepareJsonFields'];
    protected $afterUpdate  = [];
    protected $beforeDelete = [];
    protected $afterDelete  = [];

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
     * Prepare JSON fields before saving to database
     */
    protected function prepareJsonFields(array $data)
    {
        $jsonFields = ['parties', 'attachments'];

        foreach ($jsonFields as $field) {
            if (isset($data['data'][$field])) {
                // If the field is an array or object, convert to JSON string
                if (is_array($data['data'][$field]) || is_object($data['data'][$field])) {
                    $data['data'][$field] = json_encode($data['data'][$field]);
                }
            }
        }

        return $data;
    }

    /**
     * Find an agreement by ID with JSON fields decoded
     */
    public function find($id = null, $columns = '*')
    {
        $agreement = parent::find($id, $columns);

        if (!$agreement) {
            return null;
        }

        return $this->parseJsonFields($agreement);
    }

    /**
     * Find all agreements with JSON fields decoded
     */
    public function findAll(?int $limit = null, int $offset = 0)
    {
        $data = parent::findAll($limit, $offset);

        if ($data) {
            foreach ($data as $key => $row) {
                $data[$key] = $this->parseJsonFields($row);
            }
        }

        return $data;
    }

    /**
     * Parse JSON fields in data
     */
    private function parseJsonFields($data)
    {
        $jsonFields = ['parties', 'attachments'];

        foreach ($jsonFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                try {
                    $jsonData = json_decode($data[$field], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $data[$field] = $jsonData;
                    } else {
                        log_message('error', "JSON decode error for field {$field}: " . json_last_error_msg());
                        $data[$field] = [];
                    }
                } catch (\Exception $e) {
                    log_message('error', "Exception decoding JSON for field {$field}: " . $e->getMessage());
                    $data[$field] = [];
                }
            }
        }

        return $data;
    }

    /**
     * Get agreements with branch information
     *
     * @param int|null $id Agreement ID (optional)
     * @return array
     */
    public function getAgreementsWithBranch($id = null)
    {
        $builder = $this->db->table($this->table . ' as a');
        $builder->select([
            'a.*',
            'b.name as branch_name'
        ]);
        $builder->join('branches as b', 'b.id = a.branch_id', 'left');

        // Add where clause for non-deleted records
        $builder->where('a.deleted_at IS NULL');
        $builder->where('a.is_deleted', 0);

        if ($id !== null) {
            $builder->where('a.id', $id);
            $result = $builder->get()->getRowArray();
            return $result ? $this->parseJsonFields($result) : [];
        }

        $results = $builder->get()->getResultArray() ?? [];

        // Parse JSON fields in all results
        foreach ($results as $key => $row) {
            $results[$key] = $this->parseJsonFields($row);
        }

        return $results;
    }

    /**
     * Get active agreements
     *
     * @param int $limit Number of agreements to return
     * @return array
     */
    public function getActiveAgreements($limit = 10)
    {
        $builder = $this->db->table($this->table . ' as a');
        $builder->select([
            'a.*',
            'b.name as branch_name'
        ]);
        $builder->join('branches as b', 'b.id = a.branch_id', 'left');

        // Add where clause for non-deleted records and active agreements
        $builder->where('a.deleted_at IS NULL');
        $builder->where('a.is_deleted', 0);
        $builder->where('a.status', 'active');

        // Order by effective date
        $builder->orderBy('a.effective_date', 'DESC');

        // Limit the number of results
        if ($limit > 0) {
            $builder->limit($limit);
        }

        $results = $builder->get()->getResultArray() ?? [];

        // Parse JSON fields in all results
        foreach ($results as $key => $row) {
            $results[$key] = $this->parseJsonFields($row);
        }

        return $results;
    }

    /**
     * Get agreements by branch
     *
     * @param int $branchId Branch ID
     * @return array
     */
    public function getAgreementsByBranch($branchId)
    {
        $builder = $this->db->table($this->table . ' as a');
        $builder->select([
            'a.*',
            'b.name as branch_name'
        ]);
        $builder->join('branches as b', 'b.id = a.branch_id', 'left');

        // Add where clause for non-deleted records and branch
        $builder->where('a.deleted_at IS NULL');
        $builder->where('a.is_deleted', 0);
        $builder->where('a.branch_id', $branchId);

        // Order by effective date
        $builder->orderBy('a.effective_date', 'DESC');

        $results = $builder->get()->getResultArray() ?? [];

        // Parse JSON fields in all results
        foreach ($results as $key => $row) {
            $results[$key] = $this->parseJsonFields($row);
        }

        return $results;
    }

    /**
     * Get expiring agreements
     *
     * @param int $daysThreshold Number of days to consider for expiration
     * @param int $limit Number of agreements to return
     * @return array
     */
    public function getExpiringAgreements($daysThreshold = 30, $limit = 10)
    {
        $builder = $this->db->table($this->table . ' as a');
        $builder->select([
            'a.*',
            'b.name as branch_name'
        ]);
        $builder->join('branches as b', 'b.id = a.branch_id', 'left');

        // Calculate the date threshold
        $thresholdDate = date('Y-m-d', strtotime("+{$daysThreshold} days"));

        // Add where clause for non-deleted records and active agreements
        $builder->where('a.deleted_at IS NULL');
        $builder->where('a.is_deleted', 0);
        $builder->where('a.status', 'active');
        $builder->where('a.expiry_date <=', $thresholdDate);
        $builder->where('a.expiry_date >=', date('Y-m-d'));

        // Order by expiry date
        $builder->orderBy('a.expiry_date', 'ASC');

        // Limit the number of results
        if ($limit > 0) {
            $builder->limit($limit);
        }

        $results = $builder->get()->getResultArray() ?? [];

        // Parse JSON fields in all results
        foreach ($results as $key => $row) {
            $results[$key] = $this->parseJsonFields($row);
        }

        return $results;
    }

    /**
     * Update agreement status
     *
     * @param int $id Agreement ID
     * @param string $status New status
     * @param int $userId User ID making the change
     * @return bool
     */
    public function updateStatus($id, $status, $userId)
    {
        $validStatuses = ['draft', 'active', 'expired', 'terminated', 'archived'];

        if (!in_array($status, $validStatuses)) {
            return false;
        }

        return $this->update($id, [
            'status' => $status,
            'updated_by' => $userId
        ]);
    }

    /**
     * Get the validation rules
     * 
     * @param array $options Options
     * @return array
     */
    public function getValidationRules(array $options = []): array
    {
        return $this->validationRules;
    }

    /**
     * Get validation messages
     *
     * @return array
     */
    public function getValidationMessages(): array
    {
        return $this->validationMessages;
    }
}
