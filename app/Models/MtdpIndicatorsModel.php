<?php
// app/Models/MtdpIndicatorsModel.php

namespace App\Models;

use CodeIgniter\Model;

class MtdpIndicatorsModel extends Model
{
    protected $table            = 'plans_mtdp_indicators';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'mtdp_id', 'spa_id', 'dip_id', 'sa_id', 'investment_id', 'kra_id', 'strategies_id',
        'indicator', 'source', 'baseline',
        'year_one', 'year_two', 'year_three', 'year_four', 'year_five',
        'indicators_status', 'indicators_status_by', 'indicators_status_at', 'indicators_status_remarks',
        'created_by', 'updated_by', 'deleted_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'mtdp_id'      => 'required|integer',
        'spa_id'       => 'required|integer',
        'dip_id'       => 'required|integer',
        'indicator'    => 'required',
        'indicators_status' => 'required|integer',
        'indicators_status_by' => 'required|integer',
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = [];
    protected $afterInsert  = [];
    protected $beforeUpdate = [];
    protected $afterUpdate  = [];
    protected $beforeDelete = [];
    protected $afterDelete  = [];

    /**
     * Get indicators with related data
     */
    public function getIndicators($id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select($this->table.'.*', 'plans_mtdp_dip.dip_title', 'plans_mtdp_spa.title as spa_title', 'plans_mtdp.title as mtdp_title');
        $builder->join('plans_mtdp_dip', 'plans_mtdp_dip.id = '.$this->table.'.dip_id', 'left');
        $builder->join('plans_mtdp_spa', 'plans_mtdp_spa.id = '.$this->table.'.spa_id', 'left');
        $builder->join('plans_mtdp', 'plans_mtdp.id = '.$this->table.'.mtdp_id', 'left');

        if ($id !== null) {
            return $builder->where($this->table.'.id', $id)->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get indicators by DIP ID
     */
    public function getIndicatorsByDipId($dipId)
    {
        return $this->where('dip_id', $dipId)->findAll();
    }

    /**
     * Create a new indicator
     */
    public function createIndicator(array $data)
    {
        // Default status and timestamp
        $data['indicators_status']    = $data['indicators_status'] ?? 1;
        $data['indicators_status_at'] = date('Y-m-d H:i:s');

        // Log the data being inserted
        log_message('debug', 'MtdpIndicatorsModel::createIndicator - Data to insert: ' . print_r($data, true));
        log_message('debug', 'MtdpIndicatorsModel::createIndicator - Allowed fields: ' . print_r($this->allowedFields, true));

        try {
            // Insert using CI4's auto-model (will protect allowedFields automatically)
            $result = $this->insert($data);

            if (!$result) {
                log_message('error', 'MtdpIndicatorsModel::createIndicator - Insert failed. Errors: ' . print_r($this->errors(), true));
            } else {
                log_message('debug', 'MtdpIndicatorsModel::createIndicator - Insert successful. ID: ' . $result);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'MtdpIndicatorsModel::createIndicator - Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update an existing indicator
     */
    public function updateIndicator(int $id, array $data)
    {
        try {
            // Update using CI4's model
            $success = $this->update($id, $data);

            if (!$success) {
                log_message('error', 'Failed to update indicator: ' . print_r($this->errors(), true));
                return false;
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Exception updating indicator: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggle the status of an indicator
     */
    public function toggleStatus($id, $statusData)
    {
        $indicator = $this->find($id);

        if ($indicator) {
            // Toggle the status
            $newStatus = ($indicator['indicators_status'] == 1) ? 0 : 1;

            $data = [
                'indicators_status' => $newStatus,
                'indicators_status_by' => $statusData['indicators_status_by'],
                'indicators_status_at' => date('Y-m-d H:i:s'),
                'indicators_status_remarks' => $statusData['indicators_status_remarks'] ?? ''
            ];

            return $this->update($id, $data);
        }

        return false;
    }
}
