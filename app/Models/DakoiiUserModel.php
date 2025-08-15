<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * DakoiiUserModel
 * 
 * Handles database operations for the dakoii_users table
 */
class DakoiiUserModel extends Model
{
    // Table name
    protected $table = 'dakoii_users';
    
    // Primary key
    protected $primaryKey = 'id';
    
    // Return type
    protected $returnType = 'array';
    
    // Use auto-increments
    protected $useAutoIncrement = true;
    
    // Use timestamps (created_at, updated_at)
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Enable soft deletes
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
    
    // Fields that can be set during save, insert, update
    protected $allowedFields = [
        'name',
        'username',
        'password',
        'role',
        'dakoii_user_status',
        'dakoii_user_status_remarks',
        'dakoii_user_status_at',
        'dakoii_user_status_by',
        'deleted_by'
    ];
    
    // Validation rules
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[255]',
        'username' => 'required|min_length[3]|max_length[255]|is_unique[dakoii_users.username,id,{id}]',
        'password' => 'required|min_length[8]|max_length[255]',
        'role' => 'required|max_length[100]'
    ];
    
    protected $validationMessages = [
        'username' => [
            'is_unique' => 'This username is already taken'
        ]
    ];
    
    protected $skipValidation = false;
    
    /**
     * Hash the password before storing (for new users only)
     *
     * @param array $data
     * @return array
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }

        return $data;
    }

    /**
     * Before insert callback
     *
     * @param array $data
     * @return array
     */
    protected function beforeInsert(array $data)
    {
        return $this->hashPassword($data);
    }
    
    /**
     * Authenticate a user
     *
     * @param string $username
     * @param string $password
     * @return array|null
     */
    public function authenticate($username, $password)
    {
        try {
            // Log authentication details
            log_message('debug', 'Starting authentication process for: ' . $username);
            
            // First check if user exists
            $user = $this->where('username', $username)->first();
            
            if (!$user) {
                log_message('warning', 'User not found: ' . $username);
                return null;
            }
            
            // Log detailed user info (excluding sensitive data)
            log_message('debug', 'User found in database: ' . json_encode([
                'id' => $user['id'],
                'name' => $user['name'],
                'status' => $user['dakoii_user_status'] ?? 'undefined',
            ]));

            // Check the status separately to identify this specific issue
            if (isset($user['dakoii_user_status']) && $user['dakoii_user_status'] != 1) {
                log_message('warning', 'User account inactive: ' . $username . ' (Status: ' . $user['dakoii_user_status'] . ')');
                return null;
            }
            
            // Check password - log the raw password hash for debugging
            log_message('debug', 'Stored password hash: ' . substr($user['password'], 0, 10) . '...');
            
            // Add password pattern check to ensure it's a valid hash
            if (strlen($user['password']) < 20 || !preg_match('/^\$2[ayb]\$.{56}$/', $user['password'])) {
                log_message('error', 'Stored password is not a valid bcrypt hash');
                
                // TEMPORARY SOLUTION: If password appears to not be hashed,
                // compare directly (only for testing/debugging)
                if ($password === $user['password']) {
                    log_message('warning', 'Using direct password comparison - NOT SECURE FOR PRODUCTION');
                    
                    // Return user but remove password
                    $userCopy = $user;
                    unset($userCopy['password']);
                    return $userCopy;
                }
            } else {
                // Normal verification for properly hashed passwords
                if (password_verify($password, $user['password'])) {
                    log_message('info', 'Password verified successfully');
                    
                    // Return user but remove password
                    $userCopy = $user;
                    unset($userCopy['password']);
                    return $userCopy;
                }
            }
            
            log_message('warning', 'Invalid password provided for: ' . $username);
            return null;
            
        } catch (\Exception $e) {
            log_message('error', 'Authentication error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }
    
    /**
     * Find user by ID
     *
     * @param int $id
     * @return array|null
     */
    public function findUserById($id)
    {
        $user = $this->find($id);
        
        if ($user) {
            unset($user['password']);
            return $user;
        }
        
        return null;
    }
    
    /**
     * Get active users
     *
     * @return array
     */
    public function getActiveUsers()
    {
        return $this->where('dakoii_user_status', 1)
                    ->findAll();
    }
    
    /**
     * Get users by role
     *
     * @param string $role
     * @return array
     */
    public function getUsersByRole($role)
    {
        return $this->where('role', $role)
                    ->where('dakoii_user_status', 1)
                    ->findAll();
    }

    /**
     * Create a new user with the specified credentials
     *
     * @param string $name
     * @param string $username
     * @param string $password
     * @param string $role
     * @return int|false User ID if successful, false if failed
     */
    public function createUser($name, $username, $password, $role = 'admin')
    {
        $userData = [
            'name' => $name,
            'username' => $username,
            'password' => $password,
            'role' => $role,
            'dakoii_user_status' => 1  // Active status
        ];

        try {
            $result = $this->insert($userData);
            if ($result) {
                log_message('info', 'User created successfully: ' . $username);
                return $this->getInsertID();
            } else {
                log_message('error', 'Failed to create user: ' . $username);
                log_message('error', 'Validation errors: ' . json_encode($this->errors()));
                return false;
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception creating user: ' . $e->getMessage());
            return false;
        }
    }
}