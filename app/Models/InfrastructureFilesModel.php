<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * InfrastructureFilesModel
 *
 * Handles database operations for the infrastructure_files table
 * which represents files associated with infrastructure records
 */
class InfrastructureFilesModel extends Model
{
    protected $table            = 'infrastructure_files';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    // Fields that can be set during save/insert/update
    protected $allowedFields    = [
        'infrastructure_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'description',
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
        'infrastructure_id' => 'required|integer',
        'file_path'         => 'required|max_length[500]',
        'file_name'         => 'required|max_length[255]',
        'file_type'         => 'permit_empty|max_length[100]',
        'file_size'         => 'permit_empty|integer',
        'description'       => 'permit_empty',
        'created_by'        => 'required|integer',
        'updated_by'        => 'permit_empty|integer',
        'deleted_by'        => 'permit_empty|integer'
    ];

    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['setCreatedBy'];
    protected $afterInsert  = [];
    protected $beforeUpdate = ['setUpdatedBy'];
    protected $afterUpdate  = [];
    protected $beforeDelete = ['setDeletedBy'];
    protected $afterDelete  = [];

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
     * Set deleted_by field to current user ID
     */
    protected function setDeletedBy(array $data)
    {
        if (!isset($data['data']['deleted_by'])) {
            $data['data']['deleted_by'] = session()->get('user_id') ?? null;
        }

        return $data;
    }

    /**
     * Get all files for a specific infrastructure
     *
     * @param int $infrastructureId
     * @return array
     */
    public function getByInfrastructure($infrastructureId)
    {
        return $this->where('infrastructure_id', $infrastructureId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get files by type
     *
     * @param string $fileType
     * @param int $infrastructureId
     * @return array
     */
    public function getByFileType($fileType, $infrastructureId = null)
    {
        $builder = $this->where('file_type', $fileType);
        
        if ($infrastructureId) {
            $builder->where('infrastructure_id', $infrastructureId);
        }
        
        return $builder->orderBy('created_at', 'DESC')->findAll();
    }

    /**
     * Get file with detailed information
     *
     * @param int $id
     * @return array|null
     */
    public function getFileWithDetails($id)
    {
        $db = \Config\Database::connect();

        $query = $db->table('infrastructure_files if')
            ->select('if.*,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as updated_by_name,
                     CONCAT(u3.fname, " ", u3.lname) as deleted_by_name')
            ->join('users u1', 'if.created_by = u1.id', 'left')
            ->join('users u2', 'if.updated_by = u2.id', 'left')
            ->join('users u3', 'if.deleted_by = u3.id', 'left')
            ->where('if.id', $id)
            ->where('if.deleted_at', null)
            ->get();

        return $query->getRowArray();
    }

    /**
     * Get all files with detailed information
     *
     * @return array
     */
    public function getAllWithDetails()
    {
        $db = \Config\Database::connect();

        $query = $db->table('infrastructure_files if')
            ->select('if.*,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as updated_by_name,
                     CONCAT(u3.fname, " ", u3.lname) as deleted_by_name')
            ->join('users u1', 'if.created_by = u1.id', 'left')
            ->join('users u2', 'if.updated_by = u2.id', 'left')
            ->join('users u3', 'if.deleted_by = u3.id', 'left')
            ->where('if.deleted_at', null)
            ->orderBy('if.created_at', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get files with infrastructure details
     *
     * @param int $infrastructureId
     * @return array
     */
    public function getFilesWithInfrastructureDetails($infrastructureId = null)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('infrastructure_files if')
            ->select('if.*,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as updated_by_name')
            ->join('users u1', 'if.created_by = u1.id', 'left')
            ->join('users u2', 'if.updated_by = u2.id', 'left')
            ->where('if.deleted_at', null);

        if ($infrastructureId) {
            $builder->where('if.infrastructure_id', $infrastructureId);
        }

        $query = $builder->orderBy('if.created_at', 'DESC')->get();

        return $query->getResultArray();
    }

    /**
     * Get common file types
     *
     * @return array
     */
    public function getCommonFileTypes()
    {
        return [
            'pdf' => 'PDF Document',
            'doc' => 'Word Document',
            'docx' => 'Word Document (DOCX)',
            'xls' => 'Excel Spreadsheet',
            'xlsx' => 'Excel Spreadsheet (XLSX)',
            'ppt' => 'PowerPoint Presentation',
            'pptx' => 'PowerPoint Presentation (PPTX)',
            'jpg' => 'JPEG Image',
            'jpeg' => 'JPEG Image',
            'png' => 'PNG Image',
            'gif' => 'GIF Image',
            'txt' => 'Text File',
            'csv' => 'CSV File',
            'zip' => 'ZIP Archive',
            'rar' => 'RAR Archive'
        ];
    }

    /**
     * Get file extension from file name
     *
     * @param string $fileName
     * @return string
     */
    public function getFileExtension($fileName)
    {
        return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    }

    /**
     * Format file size for display
     *
     * @param int $bytes
     * @return string
     */
    public function formatFileSize($bytes)
    {
        if ($bytes == 0) return '0 Bytes';

        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes) / log($k));

        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }

    /**
     * Check if file exists on disk
     *
     * @param string $filePath
     * @return bool
     */
    public function fileExists($filePath)
    {
        // Remove 'public/' prefix if present for file_exists check
        $actualPath = str_replace('public/', '', $filePath);
        return file_exists(FCPATH . $actualPath);
    }

    /**
     * Get total file size for an infrastructure
     *
     * @param int $infrastructureId
     * @return int
     */
    public function getTotalFileSizeByInfrastructure($infrastructureId)
    {
        $result = $this->selectSum('file_size')
                       ->where('infrastructure_id', $infrastructureId)
                       ->where('deleted_at', null)
                       ->first();

        return $result['file_size'] ?? 0;
    }

    /**
     * Get file count by type
     *
     * @return array
     */
    public function getFileCountByType()
    {
        $db = \Config\Database::connect();

        $query = $db->table('infrastructure_files')
            ->select('file_type, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('file_type')
            ->orderBy('count', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Search files
     *
     * @param string $searchTerm
     * @return array
     */
    public function search($searchTerm)
    {
        return $this->like('file_name', $searchTerm)
                    ->orLike('description', $searchTerm)
                    ->orLike('file_type', $searchTerm)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get files by date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getByDateRange($startDate, $endDate)
    {
        return $this->where('created_at >=', $startDate)
                    ->where('created_at <=', $endDate)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get files by user
     *
     * @param int $userId
     * @return array
     */
    public function getByUser($userId)
    {
        return $this->where('created_by', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get recent files
     *
     * @param int $limit
     * @return array
     */
    public function getRecentFiles($limit = 10)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get large files (above specified size in MB)
     *
     * @param int $sizeMB
     * @return array
     */
    public function getLargeFiles($sizeMB = 10)
    {
        $sizeBytes = $sizeMB * 1024 * 1024; // Convert MB to bytes

        return $this->where('file_size >', $sizeBytes)
                    ->orderBy('file_size', 'DESC')
                    ->findAll();
    }

    /**
     * Get files without description
     *
     * @return array
     */
    public function getFilesWithoutDescription()
    {
        return $this->where('(description IS NULL OR description = "")')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Validate file upload
     *
     * @param array $fileData
     * @return array
     */
    public function validateFileUpload($fileData)
    {
        $errors = [];

        // Check if file was uploaded
        if (!isset($fileData['tmp_name']) || empty($fileData['tmp_name'])) {
            $errors[] = 'No file was uploaded.';
            return $errors;
        }

        // Check file size (max 50MB)
        $maxSize = 50 * 1024 * 1024; // 50MB in bytes
        if ($fileData['size'] > $maxSize) {
            $errors[] = 'File size exceeds maximum allowed size of 50MB.';
        }

        // Check allowed file types
        $allowedTypes = [
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
            'jpg', 'jpeg', 'png', 'gif', 'txt', 'csv', 'zip', 'rar'
        ];

        $fileExtension = $this->getFileExtension($fileData['name']);
        if (!in_array($fileExtension, $allowedTypes)) {
            $errors[] = 'File type not allowed. Allowed types: ' . implode(', ', $allowedTypes);
        }

        return $errors;
    }

    /**
     * Generate unique file name to prevent conflicts
     *
     * @param string $originalName
     * @return string
     */
    public function generateUniqueFileName($originalName)
    {
        $extension = $this->getFileExtension($originalName);
        $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);

        // Clean the filename
        $cleanName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $nameWithoutExt);

        // Add timestamp to make it unique
        $timestamp = date('Y-m-d_H-i-s');
        $randomString = substr(md5(uniqid()), 0, 8);

        return $cleanName . '_' . $timestamp . '_' . $randomString . '.' . $extension;
    }

    /**
     * Get storage statistics
     *
     * @return array
     */
    public function getStorageStatistics()
    {
        $db = \Config\Database::connect();

        // Total files and size
        $totalQuery = $db->table('infrastructure_files')
            ->select('COUNT(*) as total_files, SUM(file_size) as total_size')
            ->where('deleted_at', null)
            ->get();

        $totalStats = $totalQuery->getRowArray();

        // Files by type
        $typeQuery = $db->table('infrastructure_files')
            ->select('file_type, COUNT(*) as count, SUM(file_size) as size')
            ->where('deleted_at', null)
            ->groupBy('file_type')
            ->orderBy('count', 'DESC')
            ->get();

        $typeStats = $typeQuery->getResultArray();

        return [
            'total_files' => $totalStats['total_files'] ?? 0,
            'total_size' => $totalStats['total_size'] ?? 0,
            'total_size_formatted' => $this->formatFileSize($totalStats['total_size'] ?? 0),
            'by_type' => $typeStats
        ];
    }

    /**
     * Clean up orphaned files (files without valid infrastructure reference)
     *
     * @return int Number of files cleaned up
     */
    public function cleanupOrphanedFiles()
    {
        $db = \Config\Database::connect();

        // This would require the infrastructure table to exist
        // For now, just return 0 as we don't have FK constraints
        return 0;
    }

    /**
     * Bulk delete files by infrastructure ID
     *
     * @param int $infrastructureId
     * @return bool
     */
    public function deleteByInfrastructure($infrastructureId)
    {
        $files = $this->where('infrastructure_id', $infrastructureId)->findAll();

        foreach ($files as $file) {
            $this->delete($file['id']);
        }

        return true;
    }

    /**
     * Get duplicate files (same name and size)
     *
     * @return array
     */
    public function getDuplicateFiles()
    {
        $db = \Config\Database::connect();

        $query = $db->table('infrastructure_files')
            ->select('file_name, file_size, COUNT(*) as count')
            ->where('deleted_at', null)
            ->groupBy('file_name, file_size')
            ->having('COUNT(*) > 1')
            ->get();

        return $query->getResultArray();
    }
}
