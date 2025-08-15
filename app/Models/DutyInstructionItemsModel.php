<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * DutyInstructionItemsModel
 *
 * Handles database operations for the duty_instruction_items table
 * which represents individual instruction items within duty instructions
 */
class DutyInstructionItemsModel extends Model
{
    protected $table            = 'duty_instruction_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'duty_instruction_id',
        'user_id',
        'instruction_number',
        'instruction',
        'status',
        'status_by',
        'status_at',
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
        'duty_instruction_id'   => 'required|integer',
        'user_id'               => 'required|integer',
        'instruction_number'    => 'required|max_length[50]',
        'instruction'           => 'required',
        'status'                => 'permit_empty|max_length[50]',
        'status_by'             => 'permit_empty|integer',
        'status_at'             => 'permit_empty|valid_date',
        'remarks'               => 'permit_empty',
        'created_by'            => 'permit_empty|integer',
        'updated_by'            => 'permit_empty|integer',
        'deleted_by'            => 'permit_empty|integer'
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['setDefaultStatus'];
    protected $afterInsert  = [];
    protected $beforeUpdate = [];
    protected $afterUpdate  = [];
    protected $beforeDelete = [];
    protected $afterDelete  = [];

    /**
     * Set default status for new duty instruction items
     */
    protected function setDefaultStatus(array $data)
    {
        if (!isset($data['data']['status'])) {
            $data['data']['status'] = 'active';
            $data['data']['status_at'] = date('Y-m-d H:i:s');
            $data['data']['status_by'] = session()->get('user_id') ?? null;
        }

        return $data;
    }

    /**
     * Get all items for a specific duty instruction
     *
     * @param int $dutyInstructionId
     * @return array
     */
    public function getByDutyInstruction($dutyInstructionId)
    {
        return $this->where('duty_instruction_id', $dutyInstructionId)
                    ->orderBy('instruction_number', 'ASC')
                    ->findAll();
    }

    /**
     * Get items by status
     *
     * @param string $status
     * @return array
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get duty instruction item with parent duty instruction details
     *
     * @param int $id
     * @return array|null
     */
    public function getItemWithDutyInstruction($id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('duty_instruction_items dii')
            ->select('dii.*,
                     di.duty_instruction_number,
                     di.duty_instruction_title,
                     di.workplan_id,
                     w.title as workplan_title,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as updated_by_name,
                     CONCAT(u3.fname, " ", u3.lname) as status_by_name,
                     CONCAT(u4.fname, " ", u4.lname) as user_name')
            ->join('duty_instructions di', 'dii.duty_instruction_id = di.id', 'left')
            ->join('workplans w', 'di.workplan_id = w.id', 'left')
            ->join('users u1', 'dii.created_by = u1.id', 'left')
            ->join('users u2', 'dii.updated_by = u2.id', 'left')
            ->join('users u3', 'dii.status_by = u3.id', 'left')
            ->join('users u4', 'dii.user_id = u4.id', 'left')
            ->where('dii.id', $id)
            ->where('dii.deleted_at', null)
            ->get();

        return $query->getRowArray();
    }

    /**
     * Get all duty instruction items with parent details
     *
     * @return array
     */
    public function getAllWithDetails()
    {
        $db = \Config\Database::connect();

        $query = $db->table('duty_instruction_items dii')
            ->select('dii.*,
                     di.duty_instruction_number,
                     di.duty_instruction_title,
                     w.title as workplan_title,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as status_by_name,
                     CONCAT(u3.fname, " ", u3.lname) as user_name')
            ->join('duty_instructions di', 'dii.duty_instruction_id = di.id', 'left')
            ->join('workplans w', 'di.workplan_id = w.id', 'left')
            ->join('users u1', 'dii.created_by = u1.id', 'left')
            ->join('users u2', 'dii.status_by = u2.id', 'left')
            ->join('users u3', 'dii.user_id = u3.id', 'left')
            ->where('dii.deleted_at', null)
            ->orderBy('di.duty_instruction_number', 'ASC')
            ->orderBy('dii.instruction_number', 'ASC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Update duty instruction item status
     *
     * @param int $id
     * @param string $status
     * @param string $remarks
     * @return bool
     */
    public function updateStatus($id, $status, $remarks = '')
    {
        $data = [
            'status' => $status,
            'status_at' => date('Y-m-d H:i:s'),
            'status_by' => session()->get('user_id') ?? null,
            'remarks' => $remarks,
            'updated_by' => session()->get('user_id') ?? null
        ];

        return $this->update($id, $data);
    }

    /**
     * Get items count by status for a specific duty instruction
     *
     * @param int $dutyInstructionId
     * @return array
     */
    public function getStatusCountsByDutyInstruction($dutyInstructionId)
    {
        $db = \Config\Database::connect();

        $query = $db->table('duty_instruction_items')
            ->select('status, COUNT(*) as count')
            ->where('duty_instruction_id', $dutyInstructionId)
            ->where('deleted_at', null)
            ->groupBy('status')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get overall status counts
     *
     * @return array
     */
    public function getStatusCounts()
    {
        $db = \Config\Database::connect();

        $query = $db->table('duty_instruction_items')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('status')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Search duty instruction items
     *
     * @param string $searchTerm
     * @return array
     */
    public function search($searchTerm)
    {
        return $this->like('instruction_number', $searchTerm)
                    ->orLike('instruction', $searchTerm)
                    ->orLike('remarks', $searchTerm)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get next instruction number for a duty instruction
     *
     * @param int $dutyInstructionId
     * @return string
     */
    public function getNextInstructionNumber($dutyInstructionId)
    {
        $lastItem = $this->where('duty_instruction_id', $dutyInstructionId)
                         ->orderBy('instruction_number', 'DESC')
                         ->first();

        if (!$lastItem) {
            return '1';
        }

        // Extract numeric part and increment
        $lastNumber = (int) filter_var($lastItem['instruction_number'], FILTER_SANITIZE_NUMBER_INT);
        return (string) ($lastNumber + 1);
    }

    /**
     * Reorder instruction items for a duty instruction
     *
     * @param int $dutyInstructionId
     * @param array $itemIds Array of item IDs in new order
     * @return bool
     */
    public function reorderItems($dutyInstructionId, $itemIds)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($itemIds as $index => $itemId) {
            $newNumber = $index + 1;
            $db->table('duty_instruction_items')
               ->where('id', $itemId)
               ->where('duty_instruction_id', $dutyInstructionId)
               ->update([
                   'instruction_number' => (string) $newNumber,
                   'updated_by' => session()->get('user_id') ?? null,
                   'updated_at' => date('Y-m-d H:i:s')
               ]);
        }

        $db->transComplete();
        return $db->transStatus();
    }
}
