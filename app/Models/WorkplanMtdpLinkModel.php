<?php
// app/Models/WorkplanMtdpLinkModel.php

namespace App\Models;

use CodeIgniter\Model;

/**
 * WorkplanMtdpLinkModel
 * 
 * Handles database operations for the workplan_mtdp_link table
 * which represents the relationship between workplan activities and various MTDP components.
 */
class WorkplanMtdpLinkModel extends Model
{
    protected $table            = 'workplan_mtdp_link';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'workplan_activity_id',
        'mtdp_id',
        'spa_id',
        'dip_id',
        'sa_id',
        'investment_id',
        'kra_id',
        'strategies_id',
        'indicators_id', // Note: This seems to be a specific field for linking to a table of indicators, distinct from 'indicator_id'
        'indicator_id', // This might be a direct link to a specific indicator if 'indicators_id' is a linking table ID
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
        'mtdp_id'           => 'permit_empty|integer',
        'spa_id'            => 'permit_empty|integer',
        'dip_id'            => 'permit_empty|integer',
        'sa_id'             => 'permit_empty|integer',
        'investment_id'     => 'permit_empty|integer',
        'kra_id'            => 'permit_empty|integer',
        'strategies_id'     => 'permit_empty|integer',
        'indicators_id'     => 'permit_empty|integer',
        'indicator_id'      => 'permit_empty|integer', // Changed to permit_empty as per new table structure, assuming not always required
        'created_by'        => 'permit_empty|integer',
        'updated_by'        => 'permit_empty|integer',
        'deleted_by'        => 'permit_empty|integer'
    ];

    // Note: The functions getLinkedIndicators, getLinkedWorkplanActivities, and deleteWorkplanActivityLinks
    // will likely need significant updates to reflect the new table structure and relationships.
    // This update focuses on the core model properties based on the provided table structure.
    
    /**
     * Get all MTDP links for a specific workplan activity
     * 
     * @param int $workplanActivityId Workplan Activity ID
     * @return array
     */
    public function getMtdpLinksForActivity($workplanActivityId)
    {
        // This function needs to be rewritten to correctly join with the new MTDP related tables
        // based on mtdp_id, spa_id, dip_id, etc. The current joins are for the old structure.
        // For now, returning all direct links for the activity.
        return $this->where('workplan_activity_id', $workplanActivityId)
                    ->where('deleted_at IS NULL')
                    ->findAll();
    }

    /**
     * Get all workplan activities linked to a specific MTDP component (e.g., mtdp_id, spa_id, indicator_id)
     *
     * @param string $componentType (e.g., 'mtdp_id', 'spa_id', 'indicator_id')
     * @param int $componentId ID of the MTDP component
     * @return array
     */
    public function getLinkedWorkplanActivitiesByComponent($componentType, $componentId)
    {
        // This function needs to be rewritten to correctly join with workplan_activities table
        // and filter by the specified componentType and componentId.
        // For now, returning all links for the given component.
        $builder = $this->db->table($this->table . ' as link');
        $builder->select([
            'link.*',
            'wa.title as activity_title', // Assuming 'title' field in workplan_activities
            'wa.description as activity_description',
            'wa.status as activity_status'
        ]);
        $builder->join('workplan_activities as wa', 'wa.id = link.workplan_activity_id', 'left');
        $builder->where('link.' . $componentType, $componentId);
        $builder->where('link.deleted_at IS NULL');
        $builder->where('wa.deleted_at IS NULL');

        return $builder->get()->getResultArray() ?? [];
    }

    /*
    // Original getLinkedIndicators - kept for reference, will need to be adapted or removed
    public function getLinkedIndicators($workplanActivityId)
    {
        $builder = $this->db->table($this->table . ' as link');
        $builder->select([
            'link.*',
            'indicator.indicator_title', // Assuming indicator table has 'indicator_title'
            'indicator.indicator_code', // Assuming indicator table has 'indicator_code'
            'spa.title as spa_title',
            'mtdp.title as mtdp_title'
        ]);
        // IMPORTANT: Adjust the join table name and columns if they differ
        $builder->join('plans_mtdp_indicators as indicator', 'indicator.id = link.indicator_id', 'left'); 
        $builder->join('plans_mtdp_spas as spa', 'spa.id = indicator.spa_id', 'left'); // Assuming indicators link to SPAs
        $builder->join('plans_mtdp as mtdp', 'mtdp.id = spa.mtdp_id', 'left');
        $builder->where('link.workplan_activity_id', $workplanActivityId);
        $builder->where('link.deleted_at IS NULL');
        
        return $builder->get()->getResultArray() ?? [];
    }
    */
    
    /**
     * Get all workplan activities linked to a specific MTDP Indicator
     * 
     * @param int $indicatorId MTDP Indicator ID
     * @return array
     */
    public function getLinkedWorkplanActivities($indicatorId) // This function might be redundant or need renaming if getLinkedWorkplanActivitiesByComponent is used
    {
        return $this->getLinkedWorkplanActivitiesByComponent('indicator_id', $indicatorId);
    }
    
    /**
     * Delete all links for a specific workplan
     * 
     * @param int $workplanId Workplan ID
     * @param int $deletedBy User ID who performed the deletion
     * @return bool
     */
    public function deleteWorkplanActivityLinks($workplanActivityId, $deletedBy = null)
    {
        // Using soft delete
        $data = ['deleted_by' => $deletedBy];
        return $this->where('workplan_activity_id', $workplanActivityId)->delete(null, true, $data);
    }

    /**
     * Get MTDP links with detailed information
     *
     * @return array
     */
    public function getLinksWithDetails()
    {
        $builder = $this->db->table($this->table . ' as wml');
        $builder->select([
            'wml.*',
            'wa.title as activity_title',
            'wa.activity_type',
            'w.title as workplan_title',
            'mtdp.title as mtdp_title',
            'mtdp.code as mtdp_code'
        ]);
        $builder->join('workplan_activities as wa', 'wa.id = wml.workplan_activity_id', 'left');
        $builder->join('workplans as w', 'w.id = wa.workplan_id', 'left');
        $builder->join('plans_mtdp as mtdp', 'mtdp.id = wml.mtdp_id', 'left');
        $builder->where('wml.deleted_at IS NULL');
        $builder->orderBy('wml.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }
}
