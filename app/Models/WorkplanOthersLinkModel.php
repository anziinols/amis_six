<?php
// app/Models/WorkplanOthersLinkModel.php

namespace App\Models;

use CodeIgniter\Model;

/**
 * WorkplanOthersLinkModel
 * 
 * Handles database operations for the workplan_others_link table
 * which represents activities that don't fit into formal planning frameworks
 */
class WorkplanOthersLinkModel extends Model
{
    protected $table            = 'workplan_others_link';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'workplan_activity_id',
        'title',
        'description',
        'justification',
        'expected_outcome',
        'target_beneficiaries',
        'budget_estimate',
        'start_date',
        'end_date',
        'status',
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
    
    // Validation
    protected $validationRules = [
        'workplan_activity_id' => 'required|integer',
        'title'             => 'required|max_length[255]',
        'description'       => 'permit_empty',
        'justification'     => 'required',
        'expected_outcome'  => 'permit_empty',
        'target_beneficiaries' => 'permit_empty',
        'budget_estimate'   => 'permit_empty|decimal',
        'start_date'        => 'permit_empty|valid_date',
        'end_date'          => 'permit_empty|valid_date',
        'status'            => 'permit_empty|in_list[active,inactive,completed,cancelled]',
        'remarks'           => 'permit_empty',
        'created_by'        => 'permit_empty|integer',
        'updated_by'        => 'permit_empty|integer',
        'deleted_by'        => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'workplan_activity_id' => [
            'required' => 'Workplan Activity ID is required',
            'integer' => 'Workplan Activity ID must be a valid integer'
        ],
        'title' => [
            'required' => 'Title is required',
            'max_length' => 'Title cannot exceed 255 characters'
        ],
        'justification' => [
            'required' => 'Justification is required'
        ]
    ];

    /**
     * Get all others links for a specific workplan activity
     * 
     * @param int $workplanActivityId Workplan Activity ID
     * @return array
     */
    public function getOthersLinksForActivity($workplanActivityId)
    {
        return $this->where('workplan_activity_id', $workplanActivityId)
                   ->where('deleted_at', null)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }



    /**
     * Get others links with activity details
     * 
     * @param int $workplanActivityId
     * @return array
     */
    public function getOthersLinksWithDetails($workplanActivityId)
    {
        return $this->select('workplan_others_link.*, workplan_activities.title as activity_title')
                   ->join('workplan_activities', 'workplan_activities.id = workplan_others_link.workplan_activity_id', 'left')
                   ->where('workplan_others_link.workplan_activity_id', $workplanActivityId)
                   ->where('workplan_others_link.deleted_at', null)
                   ->orderBy('workplan_others_link.created_at', 'DESC')
                   ->findAll();
    }

    /**
     * Check if an activity has others links
     * 
     * @param int $workplanActivityId
     * @return bool
     */
    public function hasOthersLinks($workplanActivityId)
    {
        $count = $this->where('workplan_activity_id', $workplanActivityId)
                     ->where('deleted_at', null)
                     ->countAllResults();
        return $count > 0;
    }
}
