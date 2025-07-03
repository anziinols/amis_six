<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * CommoditiesModel
 *
 * Handles database operations for the commodities table
 */
class CommoditiesModel extends Model
{
    // Table configuration
    protected $table = 'commodities';
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
        'commodity_code',
        'commodity_name',
        'commodity_icon',
        'commodity_color_code',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // Validation rules
    protected $validationRules = [
        'commodity_code' => 'required|max_length[50]|is_unique[commodities.commodity_code,id,{id}]',
        'commodity_name' => 'required|max_length[255]',
        'commodity_icon' => 'permit_empty',
        'commodity_color_code' => 'permit_empty|max_length[10]',
        'created_by' => 'permit_empty|max_length[100]',
        'updated_by' => 'permit_empty|max_length[100]',
        'deleted_by' => 'permit_empty|max_length[100]'
    ];



    // Validation messages
    protected $validationMessages = [
        'commodity_code' => [
            'required' => 'Commodity code is required',
            'max_length' => 'Commodity code cannot exceed 50 characters',
            'is_unique' => 'This commodity code already exists'
        ],
        'commodity_name' => [
            'required' => 'Commodity name is required',
            'max_length' => 'Commodity name cannot exceed 255 characters'
        ],
        'commodity_color_code' => [
            'max_length' => 'Color code cannot exceed 10 characters'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get all commodities
     */
    public function getAllCommodities()
    {
        return $this->findAll();
    }

    /**
     * Get all commodities with user names for audit fields
     */
    public function getAllCommoditiesWithUserNames()
    {
        $db = \Config\Database::connect();

        $query = $db->table('commodities c')
            ->select('c.*,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as updated_by_name')
            ->join('users u1', 'c.created_by = u1.id', 'left')
            ->join('users u2', 'c.updated_by = u2.id', 'left')
            ->where('c.is_deleted', false)
            ->orderBy('c.created_at', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get commodity by ID
     */
    public function getCommodityById($id)
    {
        return $this->find($id);
    }

    /**
     * Get commodity by ID with user names for audit fields
     */
    public function getCommodityWithUserNames($id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('commodities c')
            ->select('c.*,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as updated_by_name,
                     CONCAT(u3.fname, " ", u3.lname) as deleted_by_name')
            ->join('users u1', 'c.created_by = u1.id', 'left')
            ->join('users u2', 'c.updated_by = u2.id', 'left')
            ->join('users u3', 'c.deleted_by = u3.id', 'left')
            ->where('c.id', $id)
            ->where('c.is_deleted', false)
            ->get();

        return $query->getRowArray();
    }

    /**
     * Get commodity by code
     */
    public function getCommodityByCode($code)
    {
        return $this->where('commodity_code', $code)->first();
    }

    /**
     * Get commodities with pagination
     */
    public function getCommoditiesPaginated($perPage = 10)
    {
        return $this->paginate($perPage);
    }

    /**
     * Search commodities by name or code
     */
    public function searchCommodities($searchTerm)
    {
        return $this->groupStart()
                    ->like('commodity_name', $searchTerm)
                    ->orLike('commodity_code', $searchTerm)
                    ->groupEnd()
                    ->findAll();
    }

    /**
     * Get active commodities only
     */
    public function getActiveCommodities()
    {
        return $this->where('is_deleted', false)->findAll();
    }

    /**
     * Soft delete a commodity
     */
    public function softDelete($id, $deletedBy = null)
    {
        $data = [
            'is_deleted' => true,
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $deletedBy
        ];

        return $this->update($id, $data);
    }

    /**
     * Restore a soft deleted commodity
     */
    public function restore($id)
    {
        $data = [
            'is_deleted' => false,
            'deleted_at' => null,
            'deleted_by' => null
        ];

        return $this->update($id, $data);
    }


}
