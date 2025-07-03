<?php
// app/Models/FolderModel.php

namespace App\Models;

use CodeIgniter\Model;

/**
 * FolderModel
 *
 * Handles database operations for the folders table
 */
class FolderModel extends Model
{
    protected $table            = 'folders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'branch_id',
        'parent_folder_id',
        'name',
        'description',
        'access',
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
        'branch_id'         => 'required|integer',
        'parent_folder_id'  => 'permit_empty|integer',
        'name'              => 'required|max_length[255]',
        'description'       => 'permit_empty',
        'access'            => 'required|in_list[private,internal,public]',
        'created_by'        => 'permit_empty|integer'
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['setCreatedBy'];
    protected $beforeUpdate = ['setUpdatedBy'];

    /**
     * Set created_by field to current user ID
     */
    protected function setCreatedBy(array $data)
    {
        if (!isset($data['data']['created_by'])) {
            $data['data']['created_by'] = session()->get('user_id') ?? null;
        }

        return $data;
    }

    /**
     * Set updated_by field to current user ID
     */
    protected function setUpdatedBy(array $data)
    {
        if (!isset($data['data']['updated_by'])) {
            $data['data']['updated_by'] = session()->get('user_id') ?? null;
        }

        return $data;
    }

    /**
     * Get folders with branch and parent folder information
     *
     * @param int|null $id Folder ID (optional)
     * @return array
     */
    public function getFoldersWithBranch($id = null)
    {
        $builder = $this->db->table($this->table . ' as f');
        $builder->select('f.*, b.name as branch_name, pf.name as parent_folder_name');
        $builder->join('branches as b', 'b.id = f.branch_id', 'left');
        $builder->join($this->table . ' as pf', 'pf.id = f.parent_folder_id', 'left');

        // Add where clause for non-deleted records
        $builder->where('f.deleted_at IS NULL');

        if ($id !== null) {
            $builder->where('f.id', $id);
            return $builder->get()->getRowArray() ?? [];
        }

        return $builder->get()->getResultArray() ?? [];
    }

    /**
     * Get folders by branch ID
     *
     * @param int $branchId Branch ID
     * @return array
     */
    public function getByBranchId($branchId)
    {
        return $this->where('branch_id', $branchId)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get folders by access level
     *
     * @param string $access Access level (private, internal, public)
     * @return array
     */
    public function getByAccess($access)
    {
        return $this->where('access', $access)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get folders by parent folder ID
     *
     * @param int|null $parentFolderId Parent folder ID (null for root folders)
     * @return array
     */
    public function getByParentFolderId($parentFolderId = null)
    {
        if ($parentFolderId === null) {
            return $this->where('parent_folder_id', 0)
                        ->orWhere('parent_folder_id IS NULL')
                        ->orderBy('name', 'ASC')
                        ->findAll();
        }

        return $this->where('parent_folder_id', $parentFolderId)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get folder path (breadcrumb) from root to current folder
     *
     * @param int $folderId Folder ID
     * @return array
     */
    public function getFolderPath($folderId)
    {
        $path = [];
        $current = $this->find($folderId);

        if (!$current) {
            return $path;
        }

        $path[] = $current;

        while ($current && !empty($current['parent_folder_id'])) {
            $current = $this->find($current['parent_folder_id']);
            if ($current) {
                array_unshift($path, $current);
            }
        }

        return $path;
    }
}
