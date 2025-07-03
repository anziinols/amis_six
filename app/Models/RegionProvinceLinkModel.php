<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * RegionProvinceLinkModel
 *
 * Handles database operations for the region_province_link table
 * which maps regions to provinces
 */
class RegionProvinceLinkModel extends Model
{
    // Table configuration
    protected $table = 'region_province_link';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    // Use timestamps and specify the fields
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $useSoftDeletes = true;

    // Fields that can be set during save, insert, update
    protected $allowedFields = [
        'region_id',
        'province_id',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // Validation rules
    protected $validationRules = [
        'region_id' => 'required|integer',
        'province_id' => 'required|integer',
        'created_by' => 'required|integer',
        'updated_by' => 'required|integer',
        'deleted_by' => 'permit_empty|integer'
    ];

    // Validation messages
    protected $validationMessages = [
        'region_id' => [
            'required' => 'Region ID is required',
            'integer' => 'Region ID must be an integer'
        ],
        'province_id' => [
            'required' => 'Province ID is required',
            'integer' => 'Province ID must be an integer'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get provinces by region ID
     */
    public function getProvincesByRegion($regionId)
    {
        $db = \Config\Database::connect();

        $query = $db->table('region_province_link rpl')
            ->select('g.*')
            ->join('gov_structure g', 'rpl.province_id = g.id', 'inner')
            ->where('rpl.region_id', $regionId)
            ->where('rpl.deleted_at IS NULL')
            ->where('g.level', 'province')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get regions by province ID
     */
    public function getRegionsByProvince($provinceId)
    {
        $db = \Config\Database::connect();

        $query = $db->table('region_province_link rpl')
            ->select('r.*')
            ->join('regions r', 'rpl.region_id = r.id', 'inner')
            ->where('rpl.province_id', $provinceId)
            ->where('rpl.deleted_at IS NULL')
            ->where('r.deleted_at IS NULL')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Check if province is assigned to a region
     */
    public function isProvinceAssigned($provinceId)
    {
        return $this->where('province_id', $provinceId)
                    ->where('deleted_at IS NULL')
                    ->countAllResults() > 0;
    }

    /**
     * Remove province from all regions
     */
    public function removeProvinceFromAllRegions($provinceId, $userId)
    {
        return $this->where('province_id', $provinceId)
                    ->set('deleted_by', $userId)
                    ->delete();
    }

    /**
     * Get provinces that are not assigned to any region
     */
    public function getUnassignedProvinces()
    {
        $db = \Config\Database::connect();

        // Get all provinces
        $subQuery = $db->table('region_province_link')
                      ->select('province_id')
                      ->where('deleted_at IS NULL');

        $query = $db->table('gov_structure')
                   ->select('*')
                   ->where('level', 'province')
                   ->where('deleted_at IS NULL')
                   ->whereNotIn('id', $subQuery)
                   ->orderBy('name', 'ASC')
                   ->get();

        return $query->getResultArray();
    }
}