<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * GovStructureModel
 *
 * Handles database operations for the gov_structure table
 * which represents the hierarchical government structure (provinces, districts, LLGs, wards)
 */
class GovStructureModel extends Model
{
    // Table configuration
    protected $table = 'gov_structure';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    // Use timestamps and specify the fields
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Fields that can be set during save, insert, update
    protected $allowedFields = [
        'parent_id',
        'json_id',
        'level',
        'code',
        'name',
        'flag_filepath',
        'map_center',
        'map_zoom',
        'created_by',
        'updated_by'
    ];

    // Validation rules
    protected $validationRules = [
        'parent_id' => 'required|integer',
        'json_id' => 'required|max_length[255]',
        'level' => 'required|in_list[province,district,llg,ward]',
        'code' => 'required|max_length[20]',
        'name' => 'required|max_length[255]',
        'flag_filepath' => 'permit_empty|max_length[255]',
        'map_center' => 'permit_empty|max_length[100]',
        'map_zoom' => 'permit_empty|max_length[11]',
        'created_by' => 'permit_empty|max_length[255]',
        'updated_by' => 'permit_empty|max_length[255]'
    ];

    // Validation messages
    protected $validationMessages = [
        'parent_id' => [
            'required' => 'Parent ID is required',
            'integer' => 'Parent ID must be an integer'
        ],
        'json_id' => [
            'required' => 'JSON ID is required',
            'max_length' => 'JSON ID cannot exceed 255 characters'
        ],
        'level' => [
            'required' => 'Level is required',
            'in_list' => 'Level must be one of: province, district, llg, ward'
        ],
        'code' => [
            'required' => 'Code is required',
            'max_length' => 'Code cannot exceed 20 characters'
        ],
        'name' => [
            'required' => 'Name is required',
            'max_length' => 'Name cannot exceed 255 characters'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Before validate callback
     */
    protected function beforeValidate(array $data)
    {
        // Set default values for created_by and updated_by if they don't exist
        if (!isset($data['data']['created_by'])) {
            $data['data']['created_by'] = session()->get('user_id') ?? 'system';
        }

        if (!isset($data['data']['updated_by'])) {
            $data['data']['updated_by'] = session()->get('user_id') ?? 'system';
        }

        return $data;
    }

    /**
     * Get structure by level
     *
     * @param string $level Level (province, district, llg, ward)
     * @return array
     */
    public function getByLevel($level)
    {
        return $this->where('level', $level)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get structure by parent
     *
     * @param int $parentId Parent ID
     * @param string|null $level Optional level filter
     * @return array
     */
    public function getByParent($parentId, $level = null)
    {
        $builder = $this->where('parent_id', $parentId);

        if ($level !== null) {
            $builder->where('level', $level);
        }

        return $builder->orderBy('name', 'ASC')->findAll();
    }

    /**
     * Get children of a specific structure item
     *
     * @param int $id Structure ID
     * @return array
     */
    public function getChildren($id)
    {
        return $this->where('parent_id', $id)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get the complete path of a structure item from root to the item
     *
     * @param int $id Structure ID
     * @return array
     */
    public function getPath($id)
    {
        $path = [];
        $current = $this->find($id);

        if (!$current) {
            return $path;
        }

        $path[] = $current;

        while ($current && $current['parent_id'] > 0) {
            $current = $this->find($current['parent_id']);
            if ($current) {
                array_unshift($path, $current);
            }
        }

        return $path;
    }

    /**
     * Update the flag file path
     *
     * @param int $id Structure ID
     * @param string $filepath Path to the flag image
     * @param string $updatedBy User who made the update
     * @return bool
     */
    public function updateFlag($id, $filepath, $updatedBy)
    {
        return $this->update($id, [
            'flag_filepath' => $filepath,
            'updated_by' => $updatedBy
        ]);
    }

    /**
     * Update the map settings
     *
     * @param int $id Structure ID
     * @param string $mapCenter Map center coordinates
     * @param string $mapZoom Map zoom level
     * @param string $updatedBy User who made the update
     * @return bool
     */
    public function updateMapSettings($id, $mapCenter, $mapZoom, $updatedBy)
    {
        return $this->update($id, [
            'map_center' => $mapCenter,
            'map_zoom' => $mapZoom,
            'updated_by' => $updatedBy
        ]);
    }
}
