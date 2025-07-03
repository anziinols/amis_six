<?php
// app/Models/MtdpInvestmentsModel.php

namespace App\Models;

use CodeIgniter\Model;

class MtdpInvestmentsModel extends Model
{
    protected $table            = 'plans_mtdp_investments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'mtdp_id', 'spa_id', 'sa_id', 'dip_id', 'dip_link_dip_id',
        'investment',
        'year_one', 'year_two', 'year_three', 'year_four', 'year_five',
        'funding_sources',
        'investment_status', 'investment_status_by', 'investment_status_at', 'investment_status_remarks',
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
        'investment'   => 'required',
        'investment_status' => 'required|integer',
        'investment_status_by' => 'required|integer',
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
     * Get investments with related data
     */
    public function getInvestments($id = null)
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
     * Get investments by DIP ID
     */
    public function getInvestmentsByDipId($dipId)
    {
        return $this->where('dip_id', $dipId)->findAll();
    }

    /**
     * Get investments by SPA ID
     */
    public function getInvestmentsBySpaId($spaId)
    {
        return $this->where('spa_id', $spaId)->findAll();
    }

    /**
     * Create a new investment
     */
    public function createInvestment(array $data)
    {
        // Default status and timestamp
        $data['investment_status']    = $data['investment_status'] ?? 1;
        $data['investment_status_at'] = date('Y-m-d H:i:s');

        // Insert using CI4's auto-model (will protect allowedFields automatically)
        return $this->insert($data);
    }

    /**
     * Update an existing investment
     */
    public function updateInvestment(int $id, array $data)
    {
        try {
            // Update using CI4's model
            $success = $this->update($id, $data);

            if (!$success) {
                log_message('error', 'Failed to update investment: ' . print_r($this->errors(), true));
                return false;
            }

            return true;
        } catch (\Exception $e) {
            log_message('error', 'Exception updating investment: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggle the status of an investment
     */
    public function toggleStatus($id, $statusData)
    {
        $investment = $this->find($id);

        if ($investment) {
            // Toggle the status
            $newStatus = ($investment['investment_status'] == 1) ? 0 : 1;

            $data = [
                'investment_status' => $newStatus,
                'investment_status_by' => $statusData['investment_status_by'],
                'investment_status_at' => date('Y-m-d H:i:s'),
                'investment_status_remarks' => $statusData['investment_status_remarks'] ?? ''
            ];

            return $this->update($id, $data);
        }

        return false;
    }

    /**
     * Calculate total investment for a DIP
     */
    public function calculateTotalInvestmentForDip($dipId)
    {
        $investments = $this->where('dip_id', $dipId)->findAll();
        $total = 0;

        foreach ($investments as $investment) {
            // Sum up the yearly amounts instead of using the investment field
            $total += (float)$investment['year_one'] +
                     (float)$investment['year_two'] +
                     (float)$investment['year_three'] +
                     (float)$investment['year_four'] +
                     (float)$investment['year_five'];
        }

        return $total;
    }
}
