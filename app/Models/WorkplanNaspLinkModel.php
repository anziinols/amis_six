<?php
// app/Models/WorkplanNaspLinkModel.php

namespace App\Models;

use CodeIgniter\Model;

/**
 * WorkplanNaspLinkModel
 *
 * Handles database operations for the workplan_nasp_link table
 * which represents the relationship between workplans and NASP objectives
 */
class WorkplanNaspLinkModel extends Model
{
    protected $table            = 'workplan_nasp_link';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'workplan_activity_id',
        'nasp_id',
        'apa_id',
        'dip_id',
        'specific_area_id',
        'objective_id',
        'output_id',
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
        'nasp_id'           => 'required|integer',
        'apa_id'            => 'permit_empty|integer',
        'dip_id'            => 'permit_empty|integer',
        'specific_area_id'  => 'permit_empty|integer',
        'objective_id'      => 'permit_empty|integer',
        'output_id'         => 'permit_empty|integer',
        'created_by'        => 'permit_empty|integer',
        'updated_by'        => 'permit_empty|integer',
        'deleted_by'        => 'permit_empty|integer'
    ];

    // Note: The functions getLinkedNaspObjectives, getLinkedWorkplans, and deleteWorkplanLinks
    // will likely need significant updates to reflect the new table structure and relationships,
    // particularly how specific_area_id, objective_id, and output_id are handled in joins and data retrieval.



    /**
     * Get all NASP objectives linked to a specific workplan
     *
     * @param int $workplanId Workplan ID
     * @return array
     */
    public function getLinkedNaspObjectives($workplanId)
    {
        $builder = $this->db->table($this->table . ' as link');
        $builder->select([
            'link.*',
            'nasp.title as nasp_title',
            'nasp.code as nasp_code',
            'nasp.type as nasp_type'
        ]);
        $builder->join('plans_nasp as nasp', 'nasp.id = link.nasp_id', 'left');
        $builder->where('link.workplan_activity_id', $workplanId);
        $builder->where('link.deleted_at IS NULL');

        return $builder->get()->getResultArray() ?? [];
    }

    /**
     * Get all workplans linked to a specific NASP objective
     *
     * @param int $naspId NASP objective ID
     * @return array
     */
    public function getLinkedWorkplans($naspId)
    {
        $builder = $this->db->table($this->table . ' as link');
        $builder->select([
            'link.*',
            'w.title as workplan_title',
            'w.description as workplan_description',
            'w.status as workplan_status'
        ]);
        $builder->join('workplans as w', 'w.id = link.workplan_activity_id', 'left');
        $builder->where('link.nasp_id', $naspId);
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
     * Get NASP links with detailed information
     *
     * @return array
     */
    public function getLinksWithDetails()
    {
        $builder = $this->db->table($this->table . ' as wnl');
        $builder->select([
            'wnl.*',
            'wa.title as activity_title',
            'wa.activity_type',
            'w.title as workplan_title',
            'nasp.title as nasp_title',
            'nasp.code as nasp_code'
        ]);
        $builder->join('workplan_activities as wa', 'wa.id = wnl.workplan_activity_id', 'left');
        $builder->join('workplans as w', 'w.id = wa.workplan_id', 'left');
        $builder->join('plans_nasp as nasp', 'nasp.id = wnl.nasp_id', 'left');
        $builder->where('wnl.deleted_at IS NULL');
        $builder->orderBy('wnl.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }
}
