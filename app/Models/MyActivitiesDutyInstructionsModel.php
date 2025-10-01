<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * MyActivitiesDutyInstructionsModel
 *
 * Handles database operations for the myactivities_duty_instructions junction table
 * which links my activities to duty instructions
 */
class MyActivitiesDutyInstructionsModel extends Model
{
    protected $table            = 'myactivities_duty_instructions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'my_activities_id',
        'duty_instructions_id',
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
        'my_activities_id'      => 'required|integer',
        'duty_instructions_id'  => 'required|integer',
        'created_by'            => 'permit_empty|integer',
        'updated_by'            => 'permit_empty|integer',
        'deleted_by'            => 'permit_empty|integer'
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get all duty instructions for a specific my activity
     *
     * @param int $myActivityId
     * @return array
     */
    public function getDutyInstructionsByMyActivity($myActivityId)
    {
        return $this->select('myactivities_duty_instructions.*,
                             duty_instructions.duty_instruction_title,
                             duty_instructions.duty_instruction_number,
                             duty_instruction_items.instruction as item')
                    ->join('duty_instruction_items', 'duty_instruction_items.id = myactivities_duty_instructions.duty_instructions_id', 'left')
                    ->join('duty_instructions', 'duty_instructions.id = duty_instruction_items.duty_instruction_id', 'left')
                    ->where('myactivities_duty_instructions.my_activities_id', $myActivityId)
                    ->findAll();
    }

    /**
     * Get all my activities for a specific duty instruction item
     *
     * @param int $dutyInstructionItemId
     * @return array
     */
    public function getMyActivitiesByDutyInstruction($dutyInstructionItemId)
    {
        return $this->select('myactivities_duty_instructions.*, activities.title, activities.description')
                    ->join('activities', 'activities.id = myactivities_duty_instructions.my_activities_id', 'left')
                    ->where('myactivities_duty_instructions.duty_instructions_id', $dutyInstructionItemId)
                    ->findAll();
    }

    /**
     * Link a my activity to a duty instruction item
     *
     * @param int $myActivityId
     * @param int $dutyInstructionItemId
     * @return bool
     */
    public function linkMyActivityToDutyInstruction($myActivityId, $dutyInstructionItemId)
    {
        $data = [
            'my_activities_id' => $myActivityId,
            'duty_instructions_id' => $dutyInstructionItemId, // Note: storing duty instruction item ID in this field
            'created_by' => session()->get('user_id') ?? null
        ];

        return $this->insert($data);
    }

    /**
     * Unlink a my activity from a duty instruction item
     *
     * @param int $myActivityId
     * @param int $dutyInstructionItemId
     * @return bool
     */
    public function unlinkMyActivityFromDutyInstruction($myActivityId, $dutyInstructionItemId)
    {
        return $this->where('my_activities_id', $myActivityId)
                    ->where('duty_instructions_id', $dutyInstructionItemId) // Note: duty instruction item ID stored in this field
                    ->delete();
    }

    /**
     * Check if a my activity is linked to a duty instruction item
     *
     * @param int $myActivityId
     * @param int $dutyInstructionItemId
     * @return bool
     */
    public function isLinked($myActivityId, $dutyInstructionItemId)
    {
        $result = $this->where('my_activities_id', $myActivityId)
                       ->where('duty_instructions_id', $dutyInstructionItemId) // Note: duty instruction item ID stored in this field
                       ->first();

        return !empty($result);
    }

    /**
     * Check if a duty instruction item has any linked my activities
     *
     * @param int $dutyInstructionItemId
     * @return bool
     */
    public function hasLinkedMyActivities($dutyInstructionItemId)
    {
        $count = $this->where('duty_instructions_id', $dutyInstructionItemId)
                      ->countAllResults();

        return $count > 0;
    }

    /**
     * Check if a duty instruction (parent) has any items with linked my activities
     *
     * @param int $dutyInstructionId
     * @return bool
     */
    public function dutyInstructionHasLinkedMyActivities($dutyInstructionId)
    {
        // Get all items for this duty instruction
        $db = \Config\Database::connect();
        $itemIds = $db->table('duty_instruction_items')
                     ->select('id')
                     ->where('duty_instruction_id', $dutyInstructionId)
                     ->where('deleted_at IS NULL')
                     ->get()
                     ->getResultArray();

        if (empty($itemIds)) {
            return false;
        }

        // Extract just the IDs
        $itemIdList = array_column($itemIds, 'id');

        // Check if any of these items have linked my activities
        $count = $this->whereIn('duty_instructions_id', $itemIdList)
                      ->countAllResults();

        return $count > 0;
    }
}
