<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PerformanceIndicatorsKraModel
 *
 * Handles database operations for the performance_indicators_kra table
 * which represents KRAs and Performance Indicators in a hierarchical structure
 */
class PerformanceIndicatorsKraModel extends Model
{
    protected $table            = 'performance_indicators_kra';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'workplan_period_id',
        'parent_id',
        'type',
        'code',
        'item',
        'description',
        'status_by',
        'status_at',
        'status_remarks',
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
        'workplan_period_id'    => 'required|integer',
        'parent_id'             => 'permit_empty|integer',
        'type'                  => 'required|in_list[kra,performance_indicator]',
        'code'                  => 'permit_empty|max_length[100]',
        'item'                  => 'required|max_length[255]',
        'description'           => 'permit_empty',
        'status_by'             => 'permit_empty|integer',
        'status_at'             => 'permit_empty|valid_date',
        'status_remarks'        => 'permit_empty',
        'created_by'            => 'permit_empty|integer',
        'updated_by'            => 'permit_empty|integer',
        'deleted_by'            => 'permit_empty|integer'
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
     * Get all KRAs and PIs for a specific workplan period
     *
     * @param int $workplanPeriodId
     * @return array
     */
    public function getByWorkplanPeriod($workplanPeriodId)
    {
        return $this->where('workplan_period_id', $workplanPeriodId)
                    ->orderBy('type', 'ASC')
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get items by type (kra or performance_indicator)
     *
     * @param string $type
     * @param int $performancePeriodId
     * @return array
     */
    public function getByType($type, $performancePeriodId = null)
    {
        $builder = $this->where('type', $type);
        
        if ($performancePeriodId) {
            $builder->where('workplan_period_id', $performancePeriodId);
        }
        
        return $builder->orderBy('created_at', 'ASC')->findAll();
    }

    /**
     * Get child items for a parent KRA/PI
     *
     * @param int $parentId
     * @return array
     */
    public function getChildren($parentId)
    {
        return $this->where('parent_id', $parentId)
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get root level items (no parent)
     *
     * @param int $performancePeriodId
     * @return array
     */
    public function getRootItems($performancePeriodId)
    {
        return $this->where('workplan_period_id', $performancePeriodId)
                    ->where('parent_id', null)
                    ->orderBy('type', 'ASC')
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get hierarchical tree structure for a performance period
     *
     * @param int $performancePeriodId
     * @return array
     */
    public function getHierarchicalTree($performancePeriodId)
    {
        $rootItems = $this->getRootItems($performancePeriodId);
        
        foreach ($rootItems as &$item) {
            $item['children'] = $this->buildChildrenTree($item['id']);
        }
        
        return $rootItems;
    }

    /**
     * Recursively build children tree
     *
     * @param int $parentId
     * @return array
     */
    private function buildChildrenTree($parentId)
    {
        $children = $this->getChildren($parentId);
        
        foreach ($children as &$child) {
            $child['children'] = $this->buildChildrenTree($child['id']);
        }
        
        return $children;
    }

    /**
     * Get KRA/PI with performance period and user details
     *
     * @param int $id
     * @return array|null
     */
    public function getWithDetails($id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('performance_indicators_kra kpi')
            ->select('kpi.*,
                     pp.title as performance_period_title,
                     CONCAT(u1.fname, " ", u1.lname) as user_name,
                     CONCAT(u2.fname, " ", u2.lname) as created_by_name,
                     CONCAT(u3.fname, " ", u3.lname) as updated_by_name,
                     CONCAT(u4.fname, " ", u4.lname) as status_by_name,
                     parent.item as parent_item')
            ->join('workplan_period pp', 'kpi.workplan_period_id = pp.id', 'left')
            ->join('users u1', 'pp.user_id = u1.id', 'left')
            ->join('users u2', 'kpi.created_by = u2.id', 'left')
            ->join('users u3', 'kpi.updated_by = u3.id', 'left')
            ->join('users u4', 'kpi.status_by = u4.id', 'left')
            ->join('performance_indicators_kra parent', 'kpi.parent_id = parent.id', 'left')
            ->where('kpi.id', $id)
            ->where('kpi.deleted_at', null)
            ->get();

        return $query->getRowArray();
    }

    /**
     * Get all KRAs and PIs with performance period details
     *
     * @return array
     */
    public function getAllWithDetails()
    {
        $db = \Config\Database::connect();

        $query = $db->table('performance_indicators_kra kpi')
            ->select('kpi.*,
                     pp.title as performance_period_title,
                     CONCAT(u1.fname, " ", u1.lname) as user_name,
                     CONCAT(u2.fname, " ", u2.lname) as created_by_name,
                     parent.item as parent_item')
            ->join('workplan_period pp', 'kpi.workplan_period_id = pp.id', 'left')
            ->join('users u1', 'pp.user_id = u1.id', 'left')
            ->join('users u2', 'kpi.created_by = u2.id', 'left')
            ->join('performance_indicators_kra parent', 'kpi.parent_id = parent.id', 'left')
            ->where('kpi.deleted_at', null)
            ->orderBy('pp.title', 'ASC')
            ->orderBy('kpi.type', 'ASC')
            ->orderBy('kpi.created_at', 'ASC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Search KRAs and PIs
     *
     * @param string $searchTerm
     * @return array
     */
    public function search($searchTerm)
    {
        return $this->like('item', $searchTerm)
                    ->orLike('code', $searchTerm)
                    ->orLike('description', $searchTerm)
                    ->orderBy('type', 'ASC')
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get count by type for a performance period
     *
     * @param int $performancePeriodId
     * @return array
     */
    public function getTypeCounts($performancePeriodId)
    {
        $db = \Config\Database::connect();

        $query = $db->table('performance_indicators_kra')
            ->select('type, COUNT(*) as count')
            ->where('workplan_period_id', $performancePeriodId)
            ->where('deleted_at', null)
            ->groupBy('type')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Check if item has children
     *
     * @param int $id
     * @return bool
     */
    public function hasChildren($id)
    {
        $count = $this->where('parent_id', $id)->countAllResults();
        return $count > 0;
    }

    /**
     * Get all KRAs for a performance period (root level only)
     *
     * @param int $performancePeriodId
     * @return array
     */
    public function getKRAs($performancePeriodId)
    {
        return $this->where('workplan_period_id', $performancePeriodId)
                    ->where('type', 'kra')
                    ->where('parent_id', null)
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get performance indicators for a specific KRA
     *
     * @param int $kraId
     * @return array
     */
    public function getPerformanceIndicators($kraId)
    {
        return $this->where('parent_id', $kraId)
                    ->where('type', 'performance_indicator')
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }
}
