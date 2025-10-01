<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * DutyInstructionsModel
 *
 * Handles database operations for the duty_instructions table
 * which represents duty instructions linked to workplans
 */
class DutyInstructionsModel extends Model
{
    protected $table            = 'duty_instructions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'workplan_id',
        'user_id',
        'supervisor_id',
        'duty_instruction_number',
        'duty_instruction_title',
        'duty_instruction_description',
        'duty_instruction_filepath',
        'status',
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
        'workplan_id'                   => 'required|integer',
        'user_id'                       => 'required|integer',
        'supervisor_id'                 => 'required|integer',
        'duty_instruction_number'       => 'required|max_length[50]',
        'duty_instruction_title'        => 'required|max_length[255]',
        'duty_instruction_description'  => 'permit_empty',
        'duty_instruction_filepath'     => 'permit_empty|max_length[500]',
        'status'                        => 'permit_empty|max_length[50]',
        'status_by'                     => 'permit_empty|integer',
        'status_at'                     => 'permit_empty|valid_date',
        'status_remarks'                => 'permit_empty',
        'created_by'                    => 'permit_empty|integer',
        'updated_by'                    => 'permit_empty|integer',
        'deleted_by'                    => 'permit_empty|integer'
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
     * Set default status for new duty instructions
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
     * Get all duty instructions for a specific workplan
     *
     * @param int $workplanId
     * @return array
     */
    public function getByWorkplan($workplanId)
    {
        return $this->where('workplan_id', $workplanId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get duty instructions by status
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
     * Get duty instruction with workplan details
     *
     * @param int $id
     * @return array|null
     */
    public function getDutyInstructionWithWorkplan($id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('duty_instructions di')
            ->select('di.*,
                     w.title as workplan_title,
                     w.description as workplan_description,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as updated_by_name,
                     CONCAT(u3.fname, " ", u3.lname) as status_by_name,
                     CONCAT(u4.fname, " ", u4.lname) as user_name,
                     CONCAT(u5.fname, " ", u5.lname) as supervisor_name')
            ->join('workplans w', 'di.workplan_id = w.id', 'left')
            ->join('users u1', 'di.created_by = u1.id', 'left')
            ->join('users u2', 'di.updated_by = u2.id', 'left')
            ->join('users u3', 'di.status_by = u3.id', 'left')
            ->join('users u4', 'di.user_id = u4.id', 'left')
            ->join('users u5', 'di.supervisor_id = u5.id', 'left')
            ->where('di.id', $id)
            ->where('di.deleted_at', null)
            ->get();

        return $query->getRowArray();
    }

    /**
     * Get all duty instructions with workplan and user details
     *
     * @return array
     */
    public function getAllWithDetails()
    {
        $db = \Config\Database::connect();

        $query = $db->table('duty_instructions di')
            ->select('di.*,
                     w.title as workplan_title,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as status_by_name,
                     CONCAT(u3.fname, " ", u3.lname) as user_name,
                     CONCAT(u4.fname, " ", u4.lname) as supervisor_name')
            ->join('workplans w', 'di.workplan_id = w.id', 'left')
            ->join('users u1', 'di.created_by = u1.id', 'left')
            ->join('users u2', 'di.status_by = u2.id', 'left')
            ->join('users u3', 'di.user_id = u3.id', 'left')
            ->join('users u4', 'di.supervisor_id = u4.id', 'left')
            ->where('di.deleted_at', null)
            ->orderBy('di.created_at', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Update duty instruction status
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
            'status_remarks' => $remarks,
            'updated_by' => session()->get('user_id') ?? null
        ];

        return $this->update($id, $data);
    }

    /**
     * Get duty instructions count by status
     *
     * @return array
     */
    public function getStatusCounts()
    {
        $db = \Config\Database::connect();

        $query = $db->table('duty_instructions')
            ->select('status, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('status')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Search duty instructions
     *
     * @param string $searchTerm
     * @return array
     */
    public function search($searchTerm)
    {
        return $this->like('duty_instruction_title', $searchTerm)
                    ->orLike('duty_instruction_number', $searchTerm)
                    ->orLike('duty_instruction_description', $searchTerm)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
}
