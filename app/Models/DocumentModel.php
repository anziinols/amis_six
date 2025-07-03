<?php
// app/Models/DocumentModel.php

namespace App\Models;

use CodeIgniter\Model;

/**
 * DocumentModel
 *
 * Handles database operations for the documents table
 */
class DocumentModel extends Model
{
    protected $table            = 'documents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'branch_id',
        'folder_id',
        'classification',
        'title',
        'description',
        'doc_date',
        'authors',
        'file_path',
        'file_type',
        'file_size',
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
        'branch_id'      => 'required|integer',
        'folder_id'      => 'required|integer',
        'classification' => 'required|in_list[private,internal,public]',
        'title'          => 'required|max_length[255]',
        'description'    => 'permit_empty',
        'doc_date'       => 'permit_empty|valid_date',
        'authors'        => 'permit_empty',
        'file_path'      => 'required|max_length[520]',
        'file_type'      => 'required|max_length[100]',
        'file_size'      => 'required|max_length[100]',
        'created_by'     => 'permit_empty|integer'
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
     * Get documents with branch information
     *
     * @param int|null $id Document ID (optional)
     * @return array
     */
    public function getDocumentsWithBranch($id = null)
    {
        $builder = $this->db->table($this->table . ' as d');
        $builder->select('d.*, b.name as branch_name');
        $builder->join('branches as b', 'b.id = d.branch_id', 'left');

        // Add where clause for non-deleted records
        $builder->where('d.deleted_at IS NULL');

        if ($id !== null) {
            $builder->where('d.id', $id);
            return $builder->get()->getRowArray() ?? [];
        }

        return $builder->get()->getResultArray() ?? [];
    }

    /**
     * Get documents by branch ID
     *
     * @param int $branchId Branch ID
     * @return array
     */
    public function getByBranchId($branchId)
    {
        return $this->where('branch_id', $branchId)
                    ->orderBy('title', 'ASC')
                    ->findAll();
    }

    /**
     * Get documents by classification
     *
     * @param string $classification Classification level (private, internal, public)
     * @return array
     */
    public function getByClassification($classification)
    {
        return $this->where('classification', $classification)
                    ->orderBy('title', 'ASC')
                    ->findAll();
    }

    /**
     * Search documents by title or description
     *
     * @param string $searchTerm Search term
     * @return array
     */
    public function searchDocuments($searchTerm)
    {
        return $this->like('title', $searchTerm)
                    ->orLike('description', $searchTerm)
                    ->orLike('authors', $searchTerm)
                    ->findAll();
    }

    /**
     * Get documents by folder ID
     *
     * @param int $folderId Folder ID
     * @return array
     */
    public function getByFolderId($folderId)
    {
        return $this->where('folder_id', $folderId)
                    ->where('deleted_at IS NULL')
                    ->orderBy('title', 'ASC')
                    ->findAll();
    }

}
