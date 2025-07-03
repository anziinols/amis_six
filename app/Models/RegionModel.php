<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * RegionModel
 * 
 * Handles database operations for the regions table
 */
class RegionModel extends Model
{
    // Table configuration
    protected $table = 'regions';
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
        'name',
        'remarks',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // Validation rules
    protected $validationRules = [
        'name' => 'required|max_length[255]',
        'remarks' => 'permit_empty',
        'created_by' => 'required|integer',
        'updated_by' => 'required|integer',
        'deleted_by' => 'permit_empty|integer'
    ];

    // Validation messages
    protected $validationMessages = [
        'name' => [
            'required' => 'Region name is required',
            'max_length' => 'Region name cannot exceed 255 characters'
        ],
        'created_by' => [
            'required' => 'Creator ID is required',
            'integer' => 'Creator ID must be an integer'
        ],
        'updated_by' => [
            'required' => 'Updater ID is required',
            'integer' => 'Updater ID must be an integer'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get all regions
     */
    public function getAllRegions()
    {
        return $this->findAll();
    }

    /**
     * Get region by ID
     */
    public function getRegionById($id)
    {
        return $this->find($id);
    }

    /**
     * Get regions with province counts
     */
    public function getRegionsWithProvinceCount()
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('regions r')
            ->select('r.*, COUNT(rpl.province_id) as province_count')
            ->join('region_province_link rpl', 'r.id = rpl.region_id', 'left')
            ->where('r.deleted_at IS NULL')
            ->groupBy('r.id')
            ->get();
            
        return $query->getResultArray();
    }
} 