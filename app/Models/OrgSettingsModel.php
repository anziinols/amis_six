<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * OrgSettingsModel
 * 
 * Handles database operations for the org_settings table
 * which stores organizational configuration settings
 */
class OrgSettingsModel extends Model
{
    // Table configuration
    protected $table = 'org_settings';
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
        'settings_code',
        'settings_name',
        'settings',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // Validation rules
    protected $validationRules = [
        'settings_code' => 'required|max_length[100]|is_unique[org_settings.settings_code,id,{id}]',
        'settings_name' => 'required|max_length[255]',
        'settings' => 'required',
        'created_by' => 'required|integer',
        'updated_by' => 'required|integer',
        'deleted_by' => 'permit_empty|integer'
    ];

    // Validation messages
    protected $validationMessages = [
        'settings_code' => [
            'required' => 'Settings code is required',
            'max_length' => 'Settings code cannot exceed 100 characters',
            'is_unique' => 'This settings code already exists'
        ],
        'settings_name' => [
            'required' => 'Settings name is required',
            'max_length' => 'Settings name cannot exceed 255 characters'
        ],
        'settings' => [
            'required' => 'Settings content is required'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get settings by code
     * 
     * @param string $code The settings code to retrieve
     * @param bool $asJson Whether to decode the settings as JSON
     * @return mixed The settings data
     */
    public function getSettingsByCode($code, $asJson = true)
    {
        $setting = $this->where('settings_code', $code)
                        ->where('deleted_at IS NULL')
                        ->first();
        
        if (!$setting) {
            return null;
        }
        
        if ($asJson && !empty($setting['settings'])) {
            $setting['settings'] = json_decode($setting['settings'], true);
        }
        
        return $setting;
    }

    /**
     * Save settings by code
     * 
     * @param string $code The settings code
     * @param string $name The settings name
     * @param mixed $settings The settings data (array or object will be JSON encoded)
     * @param int $userId The user making the change
     * @return bool Success or failure
     */
    public function saveSettingsByCode($code, $name, $settings, $userId)
    {
        // Convert array/object to JSON string if needed
        if (is_array($settings) || is_object($settings)) {
            $settings = json_encode($settings);
        }
        
        // Check if settings exist
        $existing = $this->where('settings_code', $code)->first();
        
        if ($existing) {
            // Update existing
            return $this->update($existing['id'], [
                'settings_name' => $name,
                'settings' => $settings,
                'updated_by' => $userId
            ]);
        } else {
            // Create new
            return $this->insert([
                'settings_code' => $code,
                'settings_name' => $name,
                'settings' => $settings,
                'created_by' => $userId,
                'updated_by' => $userId
            ]) !== false;
        }
    }

    /**
     * Delete settings by code
     * 
     * @param string $code The settings code to delete
     * @param int $userId The user making the change
     * @return bool Success or failure
     */
    public function deleteSettingsByCode($code, $userId)
    {
        $setting = $this->where('settings_code', $code)->first();
        
        if ($setting) {
            return $this->update($setting['id'], [
                'deleted_by' => $userId
            ]) && $this->delete($setting['id']);
        }
        
        return false;
    }
} 