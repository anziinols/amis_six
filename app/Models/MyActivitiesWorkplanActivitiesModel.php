<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * MyActivitiesWorkplanActivitiesModel
 *
 * Handles database operations for the myactivities_workplan_activities junction table
 * which links my activities to workplan activities
 */
class MyActivitiesWorkplanActivitiesModel extends Model
{
    protected $table            = 'myactivities_workplan_activities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'my_activities_id',
        'workplan_activities_id',
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
        'my_activities_id'          => 'required|integer',
        'workplan_activities_id'    => 'required|integer',
        'created_by'                => 'permit_empty|integer',
        'updated_by'                => 'permit_empty|integer',
        'deleted_by'                => 'permit_empty|integer'
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get all workplan activities for a specific my activity
     *
     * @param int $myActivityId
     * @return array
     */
    public function getWorkplanActivitiesByMyActivity($myActivityId)
    {
        return $this->select('myactivities_workplan_activities.*, 
                             workplan_activities.title as activity_title, 
                             workplan_activities.activity_code,
                             workplans.title as workplan_title')
                    ->join('workplan_activities', 'workplan_activities.id = myactivities_workplan_activities.workplan_activities_id', 'left')
                    ->join('workplans', 'workplans.id = workplan_activities.workplan_id', 'left')
                    ->where('myactivities_workplan_activities.my_activities_id', $myActivityId)
                    ->findAll();
    }

    /**
     * Get all my activities for a specific workplan activity
     *
     * @param int $workplanActivityId
     * @return array
     */
    public function getMyActivitiesByWorkplanActivity($workplanActivityId)
    {
        return $this->select('myactivities_workplan_activities.*, activities.title, activities.description')
                    ->join('activities', 'activities.id = myactivities_workplan_activities.my_activities_id', 'left')
                    ->where('myactivities_workplan_activities.workplan_activities_id', $workplanActivityId)
                    ->findAll();
    }

    /**
     * Link a my activity to a workplan activity
     *
     * @param int $myActivityId
     * @param int $workplanActivityId
     * @return bool
     */
    public function linkMyActivityToWorkplanActivity($myActivityId, $workplanActivityId)
    {
        $data = [
            'my_activities_id' => $myActivityId,
            'workplan_activities_id' => $workplanActivityId,
            'created_by' => session()->get('user_id') ?? null
        ];

        return $this->insert($data);
    }

    /**
     * Unlink a my activity from a workplan activity
     *
     * @param int $myActivityId
     * @param int $workplanActivityId
     * @return bool
     */
    public function unlinkMyActivityFromWorkplanActivity($myActivityId, $workplanActivityId)
    {
        return $this->where('my_activities_id', $myActivityId)
                    ->where('workplan_activities_id', $workplanActivityId)
                    ->delete();
    }

    /**
     * Check if a my activity is linked to a workplan activity
     *
     * @param int $myActivityId
     * @param int $workplanActivityId
     * @return bool
     */
    public function isLinked($myActivityId, $workplanActivityId)
    {
        $result = $this->where('my_activities_id', $myActivityId)
                       ->where('workplan_activities_id', $workplanActivityId)
                       ->first();

        return !empty($result);
    }

    /**
     * Check if a workplan activity has any linked my activities
     *
     * @param int $workplanActivityId
     * @return bool
     */
    public function hasLinkedMyActivities($workplanActivityId)
    {
        $count = $this->where('workplan_activities_id', $workplanActivityId)
                      ->countAllResults();

        return $count > 0;
    }
}
