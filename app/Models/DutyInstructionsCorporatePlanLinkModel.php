<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * DutyInstructionsCorporatePlanLinkModel
 *
 * Handles database operations for the duty_instructions_corporate_plan_link table
 * which represents the relationship between duty instruction items and corporate plan strategies
 */
class DutyInstructionsCorporatePlanLinkModel extends Model
{
    protected $table            = 'duty_instructions_corporate_plan_link';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'duty_items_id',
        'corp_strategies_id',
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
        'duty_items_id'      => 'required|integer',
        'corp_strategies_id' => 'required|integer',
        'created_by'         => 'permit_empty|integer',
        'updated_by'         => 'permit_empty|integer',
        'deleted_by'         => 'permit_empty|integer'
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
     * Get all links for a specific duty instruction item
     *
     * @param int $dutyItemId
     * @return array
     */
    public function getByDutyItem($dutyItemId)
    {
        return $this->where('duty_items_id', $dutyItemId)
                    ->findAll();
    }

    /**
     * Get all links for a specific corporate strategy
     *
     * @param int $corpStrategyId
     * @return array
     */
    public function getByCorporateStrategy($corpStrategyId)
    {
        return $this->where('corp_strategies_id', $corpStrategyId)
                    ->findAll();
    }

    /**
     * Get duty item links with corporate strategy details
     *
     * @param int $dutyItemId
     * @return array
     */
    public function getDutyItemWithStrategies($dutyItemId)
    {
        $db = \Config\Database::connect();

        $query = $db->table('duty_instructions_corporate_plan_link dicpl')
            ->select('dicpl.*,
                     cs.strategy_title,
                     cs.strategy_code,
                     cp.title as corporate_plan_title,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name')
            ->join('corporate_strategies cs', 'dicpl.corp_strategies_id = cs.id', 'left')
            ->join('corporate_plans cp', 'cs.corporate_plan_id = cp.id', 'left')
            ->join('users u1', 'dicpl.created_by = u1.id', 'left')
            ->where('dicpl.duty_items_id', $dutyItemId)
            ->where('dicpl.deleted_at', null)
            ->orderBy('dicpl.created_at', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get corporate strategy links with duty item details
     *
     * @param int $corpStrategyId
     * @return array
     */
    public function getCorporateStrategyWithDutyItems($corpStrategyId)
    {
        $db = \Config\Database::connect();

        $query = $db->table('duty_instructions_corporate_plan_link dicpl')
            ->select('dicpl.*,
                     dii.instruction_number,
                     dii.instruction,
                     di.duty_instruction_title,
                     di.duty_instruction_number,
                     w.title as workplan_title,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name')
            ->join('duty_instruction_items dii', 'dicpl.duty_items_id = dii.id', 'left')
            ->join('duty_instructions di', 'dii.duty_instruction_id = di.id', 'left')
            ->join('workplans w', 'di.workplan_id = w.id', 'left')
            ->join('users u1', 'dicpl.created_by = u1.id', 'left')
            ->where('dicpl.corp_strategies_id', $corpStrategyId)
            ->where('dicpl.deleted_at', null)
            ->orderBy('dicpl.created_at', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get all links with full details
     *
     * @return array
     */
    public function getAllWithDetails()
    {
        $db = \Config\Database::connect();

        $query = $db->table('duty_instructions_corporate_plan_link dicpl')
            ->select('dicpl.*,
                     dii.instruction_number,
                     dii.instruction,
                     di.duty_instruction_title,
                     di.duty_instruction_number,
                     w.title as workplan_title,
                     cs.strategy_title,
                     cs.strategy_code,
                     cp.title as corporate_plan_title,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name')
            ->join('duty_instruction_items dii', 'dicpl.duty_items_id = dii.id', 'left')
            ->join('duty_instructions di', 'dii.duty_instruction_id = di.id', 'left')
            ->join('workplans w', 'di.workplan_id = w.id', 'left')
            ->join('corporate_strategies cs', 'dicpl.corp_strategies_id = cs.id', 'left')
            ->join('corporate_plans cp', 'cs.corporate_plan_id = cp.id', 'left')
            ->join('users u1', 'dicpl.created_by = u1.id', 'left')
            ->where('dicpl.deleted_at', null)
            ->orderBy('dicpl.created_at', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Check if a link already exists
     *
     * @param int $dutyItemId
     * @param int $corpStrategyId
     * @return bool
     */
    public function linkExists($dutyItemId, $corpStrategyId)
    {
        $existing = $this->where('duty_items_id', $dutyItemId)
                         ->where('corp_strategies_id', $corpStrategyId)
                         ->first();

        return !empty($existing);
    }

    /**
     * Create a new link if it doesn't exist
     *
     * @param int $dutyItemId
     * @param int $corpStrategyId
     * @param int|null $createdBy
     * @return bool|int
     */
    public function createLink($dutyItemId, $corpStrategyId, $createdBy = null)
    {
        // Check if link already exists
        if ($this->linkExists($dutyItemId, $corpStrategyId)) {
            return false; // Link already exists
        }

        $data = [
            'duty_items_id' => $dutyItemId,
            'corp_strategies_id' => $corpStrategyId,
            'created_by' => $createdBy ?? session()->get('user_id')
        ];

        return $this->save($data);
    }

    /**
     * Remove a specific link
     *
     * @param int $dutyItemId
     * @param int $corpStrategyId
     * @return bool
     */
    public function removeLink($dutyItemId, $corpStrategyId)
    {
        $link = $this->where('duty_items_id', $dutyItemId)
                     ->where('corp_strategies_id', $corpStrategyId)
                     ->first();

        if ($link) {
            return $this->delete($link['id']);
        }

        return false;
    }

    /**
     * Remove all links for a duty item
     *
     * @param int $dutyItemId
     * @return bool
     */
    public function removeAllLinksForDutyItem($dutyItemId)
    {
        return $this->where('duty_items_id', $dutyItemId)->delete();
    }

    /**
     * Remove all links for a corporate strategy
     *
     * @param int $corpStrategyId
     * @return bool
     */
    public function removeAllLinksForCorporateStrategy($corpStrategyId)
    {
        return $this->where('corp_strategies_id', $corpStrategyId)->delete();
    }

    /**
     * Get link statistics
     *
     * @return array
     */
    public function getLinkStatistics()
    {
        $db = \Config\Database::connect();

        $stats = [];

        // Total links
        $stats['total_links'] = $this->countAllResults();

        // Links by corporate plan
        $query = $db->table('duty_instructions_corporate_plan_link dicpl')
            ->select('cp.title as corporate_plan_title, COUNT(*) as link_count')
            ->join('corporate_strategies cs', 'dicpl.corp_strategies_id = cs.id', 'left')
            ->join('corporate_plans cp', 'cs.corporate_plan_id = cp.id', 'left')
            ->where('dicpl.deleted_at', null)
            ->groupBy('cp.id')
            ->orderBy('link_count', 'DESC')
            ->get();

        $stats['by_corporate_plan'] = $query->getResultArray();

        return $stats;
    }

    /**
     * Bulk create links for a duty item
     *
     * @param int $dutyItemId
     * @param array $corpStrategyIds
     * @param int|null $createdBy
     * @return array Results array with success/failure info
     */
    public function bulkCreateLinks($dutyItemId, $corpStrategyIds, $createdBy = null)
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'already_exists' => 0
        ];

        foreach ($corpStrategyIds as $corpStrategyId) {
            if ($this->linkExists($dutyItemId, $corpStrategyId)) {
                $results['already_exists']++;
            } else {
                if ($this->createLink($dutyItemId, $corpStrategyId, $createdBy)) {
                    $results['success']++;
                } else {
                    $results['failed']++;
                }
            }
        }

        return $results;
    }
}
