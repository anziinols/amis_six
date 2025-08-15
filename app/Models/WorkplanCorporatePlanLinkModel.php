<?php
// app/Models/WorkplanCorporatePlanLinkModel.php

namespace App\Models;

use CodeIgniter\Model;

/**
 * WorkplanCorporatePlanLinkModel
 * 
 * Handles database operations for the workplan_corporate_plan_link table
 * which represents the relationship between workplans and corporate plan KRAs
 */
class WorkplanCorporatePlanLinkModel extends Model
{
    protected $table            = 'workplan_corporate_plan_link';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'workplan_activity_id',
        'corporate_plan_id',
        'objective_id',
        'kra_id',
        'strategies_id',
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
        'workplan_activity_id'       => 'required|integer',
        'corporate_plan_id' => 'required|integer',
        'objective_id'      => 'permit_empty|integer',
        'kra_id'            => 'permit_empty|integer',
        'strategies_id'     => 'permit_empty|integer',
        'created_by'        => 'permit_empty|integer',
        'updated_by'        => 'permit_empty|integer',
        'deleted_by'        => 'permit_empty|integer'
    ];

    // Note: The functions getLinkedCorporateKras, getLinkedWorkplans, and deleteWorkplanLinks
    // will likely need significant updates to reflect the new table structure and relationships,
    // particularly how overarching_objective_id, objective_id, kra_id, and strategies_id 
    // are handled in joins and data retrieval.
    
    /**
     * Get all corporate plan KRAs linked to a specific workplan
     * 
     * @param int $workplanId Workplan ID
     * @return array
     */
    public function getLinkedCorporateKras($workplanId)
    {
        $builder = $this->db->table($this->table . ' as link');
        $builder->select([
            'link.*',
            'cp.title as corporate_plan_title',
            'cp.code as corporate_plan_code',
            'cp.type as corporate_plan_type'
        ]);
        $builder->join('plans_corporate as cp', 'cp.id = link.corporate_plan_id', 'left');
        $builder->where('link.workplan_activity_id', $workplanId);
        $builder->where('link.deleted_at IS NULL');
        
        return $builder->get()->getResultArray() ?? [];
    }
    
    /**
     * Get all workplans linked to a specific corporate plan KRA
     * 
     * @param int $corporatePlanId Corporate plan KRA ID
     * @return array
     */
    public function getLinkedWorkplans($corporatePlanId)
    {
        $builder = $this->db->table($this->table . ' as link');
        $builder->select([
            'link.*',
            'w.title as workplan_title',
            'w.description as workplan_description',
            'w.status as workplan_status'
        ]);
        $builder->join('workplans as w', 'w.id = link.workplan_activity_id', 'left');
        $builder->where('link.corporate_plan_id', $corporatePlanId);
        $builder->where('link.deleted_at IS NULL');
        $builder->where('w.deleted_at IS NULL');
        
        return $builder->get()->getResultArray() ?? [];
    }
    
    /**
     * Delete all links for a specific workplan
     * 
     * @param int $workplanId Workplan ID
     * @param int $deletedBy User ID who performed the deletion
     * @return bool
     */
    public function deleteWorkplanLinks($workplanActivityId, $deletedBy = null)
    {
        // Using soft delete
        $data = ['deleted_by' => $deletedBy];
        return $this->where('workplan_activity_id', $workplanActivityId)->delete(null, true, $data);
    }

    /**
     * Get Corporate Plan links with detailed information
     *
     * @return array
     */
    public function getLinksWithDetails()
    {
        $builder = $this->db->table($this->table . ' as wcl');
        $builder->select([
            'wcl.*',
            'wa.title as activity_title',
            'wa.activity_type',
            'w.title as workplan_title',
            'cp.title as corporate_plan_title',
            'cp.code as corporate_plan_code'
        ]);
        $builder->join('workplan_activities as wa', 'wa.id = wcl.workplan_activity_id', 'left');
        $builder->join('workplans as w', 'w.id = wa.workplan_id', 'left');
        $builder->join('corporate_plan as cp', 'cp.id = wcl.corporate_plan_id', 'left');
        $builder->where('wcl.deleted_at IS NULL');
        $builder->orderBy('wcl.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }
}
