<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * CommodityProductionModel
 *
 * Handles database operations for the commodity_production table
 */
class CommodityProductionModel extends Model
{
    // Table configuration
    protected $table = 'commodity_production';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    // Use timestamps and specify the fields
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $useSoftDeletes = true;

    // Fields that can be set during save, insert, update
    protected $allowedFields = [
        'commodity_id',
        'date_from',
        'date_to',
        'item',
        'description',
        'unit_of_measurement',
        'quantity',
        'is_exported',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // Validation rules
    protected $validationRules = [
        'commodity_id' => 'required|integer',
        'date_from' => 'required|valid_date',
        'date_to' => 'required|valid_date',
        'item' => 'required|max_length[255]',
        'description' => 'permit_empty',
        'unit_of_measurement' => 'permit_empty|max_length[50]',
        'quantity' => 'required|decimal',
        'is_exported' => 'permit_empty|in_list[0,1]',
        'created_by' => 'permit_empty|max_length[100]',
        'updated_by' => 'permit_empty|max_length[100]',
        'deleted_by' => 'permit_empty|max_length[100]'
    ];

    // Validation messages
    protected $validationMessages = [
        'commodity_id' => [
            'required' => 'Commodity ID is required',
            'integer' => 'Commodity ID must be a valid integer'
        ],
        'date_from' => [
            'required' => 'Start date is required',
            'valid_date' => 'Start date must be a valid date'
        ],
        'date_to' => [
            'required' => 'End date is required',
            'valid_date' => 'End date must be a valid date'
        ],
        'item' => [
            'required' => 'Item is required',
            'max_length' => 'Item cannot exceed 255 characters'
        ],
        'unit_of_measurement' => [
            'max_length' => 'Unit of measurement cannot exceed 50 characters'
        ],
        'quantity' => [
            'required' => 'Quantity is required',
            'decimal' => 'Quantity must be a valid decimal number'
        ],
        'is_exported' => [
            'in_list' => 'Export status must be 0 or 1'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get all commodity production records
     */
    public function getAllCommodityProduction()
    {
        return $this->findAll();
    }

    /**
     * Get all commodity production records with commodity and user names
     */
    public function getAllCommodityProductionWithDetails()
    {
        $db = \Config\Database::connect();

        $query = $db->table('commodity_production cp')
            ->select('cp.*,
                     c.commodity_name,
                     c.commodity_code,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as updated_by_name')
            ->join('commodities c', 'cp.commodity_id = c.id', 'left')
            ->join('users u1', 'cp.created_by = u1.id', 'left')
            ->join('users u2', 'cp.updated_by = u2.id', 'left')
            ->where('cp.is_deleted', false)
            ->orderBy('cp.created_at', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get commodity production by ID
     */
    public function getCommodityProductionById($id)
    {
        return $this->find($id);
    }

    /**
     * Get commodity production by ID with commodity and user names
     */
    public function getCommodityProductionWithDetails($id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('commodity_production cp')
            ->select('cp.*,
                     c.commodity_name,
                     c.commodity_code,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as updated_by_name,
                     CONCAT(u3.fname, " ", u3.lname) as deleted_by_name')
            ->join('commodities c', 'cp.commodity_id = c.id', 'left')
            ->join('users u1', 'cp.created_by = u1.id', 'left')
            ->join('users u2', 'cp.updated_by = u2.id', 'left')
            ->join('users u3', 'cp.deleted_by = u3.id', 'left')
            ->where('cp.id', $id)
            ->where('cp.is_deleted', false)
            ->get();

        return $query->getRowArray();
    }

    /**
     * Get commodity production records by commodity ID
     */
    public function getProductionByCommodityId($commodityId)
    {
        return $this->where('commodity_id', $commodityId)
                    ->where('is_deleted', false)
                    ->findAll();
    }

    /**
     * Get commodity production records by date range
     */
    public function getProductionByDateRange($dateFrom, $dateTo)
    {
        return $this->where('date_from >=', $dateFrom)
                    ->where('date_to <=', $dateTo)
                    ->where('is_deleted', false)
                    ->findAll();
    }

    /**
     * Get exported commodity production records
     */
    public function getExportedProduction()
    {
        return $this->where('is_exported', true)
                    ->where('is_deleted', false)
                    ->findAll();
    }

    /**
     * Get domestic commodity production records (not exported)
     */
    public function getDomesticProduction()
    {
        return $this->where('is_exported', false)
                    ->where('is_deleted', false)
                    ->findAll();
    }

    /**
     * Get commodity production with pagination
     */
    public function getCommodityProductionPaginated($perPage = 10)
    {
        return $this->paginate($perPage);
    }

    /**
     * Search commodity production by item or description
     */
    public function searchCommodityProduction($searchTerm)
    {
        return $this->groupStart()
                    ->like('item', $searchTerm)
                    ->orLike('description', $searchTerm)
                    ->groupEnd()
                    ->where('is_deleted', false)
                    ->findAll();
    }

    /**
     * Get production summary by commodity
     */
    public function getProductionSummaryByCommodity()
    {
        $db = \Config\Database::connect();

        $query = $db->table('commodity_production cp')
            ->select('c.commodity_name,
                     c.commodity_code,
                     cp.unit_of_measurement,
                     SUM(cp.quantity) as total_quantity,
                     COUNT(cp.id) as record_count')
            ->join('commodities c', 'cp.commodity_id = c.id', 'left')
            ->where('cp.is_deleted', false)
            ->groupBy('cp.commodity_id, cp.unit_of_measurement')
            ->orderBy('c.commodity_name', 'ASC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get production summary by export status
     */
    public function getProductionSummaryByExportStatus()
    {
        $db = \Config\Database::connect();

        $query = $db->table('commodity_production cp')
            ->select('cp.is_exported,
                     COUNT(cp.id) as record_count,
                     SUM(cp.quantity) as total_quantity')
            ->where('cp.is_deleted', false)
            ->groupBy('cp.is_exported')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get active commodity production records only
     */
    public function getActiveCommodityProduction()
    {
        return $this->where('is_deleted', false)->findAll();
    }

    /**
     * Soft delete a commodity production record
     */
    public function softDelete($id, $deletedBy = null)
    {
        $data = [
            'is_deleted' => true,
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $deletedBy
        ];

        return $this->update($id, $data);
    }

    /**
     * Restore a soft deleted commodity production record
     */
    public function restore($id)
    {
        $data = [
            'is_deleted' => false,
            'deleted_at' => null,
            'deleted_by' => null
        ];

        return $this->update($id, $data);
    }

    /**
     * Custom validation to check if date_to is after date_from
     */
    public function validateDateRange($data)
    {
        if (isset($data['date_from']) && isset($data['date_to'])) {
            $dateFrom = strtotime($data['date_from']);
            $dateTo = strtotime($data['date_to']);

            if ($dateTo < $dateFrom) {
                return false;
            }
        }
        return true;
    }



    /**
     * Get production statistics for reports
     */
    public function getProductionStatistics()
    {
        $db = \Config\Database::connect();

        // Total production by month
        $monthlyQuery = $db->table('commodity_production')
            ->select('MONTH(created_at) as month, SUM(quantity) as total_quantity')
            ->where('is_deleted', false)
            ->where('YEAR(created_at)', date('Y'))
            ->groupBy('MONTH(created_at)')
            ->get();

        $monthlyStats = [];
        foreach ($monthlyQuery->getResultArray() as $row) {
            $monthlyStats[$row['month']] = $row['total_quantity'];
        }

        // Fill missing months with 0
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($monthlyStats[$i])) {
                $monthlyStats[$i] = 0;
            }
        }
        ksort($monthlyStats);

        return [
            'monthly' => array_values($monthlyStats)
        ];
    }

    /**
     * Get production trends by export status
     */
    public function getProductionTrendsByExportStatus()
    {
        $db = \Config\Database::connect();

        $query = $db->table('commodity_production')
            ->select('MONTH(created_at) as month, is_exported, SUM(quantity) as total_quantity')
            ->where('is_deleted', false)
            ->where('YEAR(created_at)', date('Y'))
            ->groupBy('MONTH(created_at), is_exported')
            ->get();

        $trends = [
            'exported' => array_fill(1, 12, 0),
            'domestic' => array_fill(1, 12, 0)
        ];

        foreach ($query->getResultArray() as $row) {
            if ($row['is_exported']) {
                $trends['exported'][$row['month']] = $row['total_quantity'];
            } else {
                $trends['domestic'][$row['month']] = $row['total_quantity'];
            }
        }

        return [
            'exported' => array_values($trends['exported']),
            'domestic' => array_values($trends['domestic'])
        ];
    }
}