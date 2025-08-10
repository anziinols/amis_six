<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UserModel
 *
 * Handles database operations for the users table
 */
class UserModel extends Model
{
    // Table configuration
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    // Use timestamps and specify the fields
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Fields that can be set during save, insert, update
    protected $allowedFields = [
        'ucode',
        'password',
        'email',
        'phone',
        'fname',
        'lname',
        'gender',
        'dobirth',
        'place_birth',
        'address',
        'employee_number',
        'branch_id',
        'designation',
        'grade',
        'report_to_id',
        'is_evaluator',
        'is_supervisor',
        'commodity_id',
        'role',
        'joined_date',
        'id_photo_filepath',
        'user_status',
        'user_status_remarks',
        'user_status_at',
        'user_status_by',
        'created_by',
        'updated_by',
        'activation_token',
        'activation_expires_at',
        'activated_at',
        'is_activated'
    ];

    // Simple validation rules - no complex email uniqueness check
    // Password not required for new users (activation workflow)
    protected $validationRules = [
        'email' => 'required|valid_email',
        'fname' => 'required|max_length[255]',
        'lname' => 'required|max_length[255]',
        'role' => 'required|in_list[admin,supervisor,user,guest,commodity]'
    ];

    // Simple validation messages
    protected $validationMessages = [
        'email' => [
            'required' => 'Email address is required',
            'valid_email' => 'Please enter a valid email address'
        ],
        'fname' => [
            'required' => 'First name is required'
        ],
        'lname' => [
            'required' => 'Last name is required'
        ],
        'role' => [
            'required' => 'User role is required'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // No automatic password hashing - handle explicitly in controller

    /**
     * Before validate callback
     */
    protected function beforeValidate(array $data)
    {
        // Set default values for created_by and updated_by if they don't exist
        if (!isset($data['data']['created_by'])) {
            $data['data']['created_by'] = 1; // Default to admin user
        }

        if (!isset($data['data']['updated_by'])) {
            $data['data']['updated_by'] = 1; // Default to admin user
        }

        return $data;
    }

    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)
                   ->where('user_status', 1)
                   ->first();
    }

    /**
     * Get user by employee number
     */
    public function getUserByEmployeeNumber($employeeNumber)
    {
        return $this->where('employee_number', $employeeNumber)
                   ->where('user_status', 1)
                   ->first();
    }

    /**
     * Get users by branch
     */
    public function getUsersByBranch($branchId)
    {
        return $this->where('branch_id', $branchId)
                   ->where('user_status', 1)
                   ->findAll();
    }

    /**
     * Get users by role
     */
    public function getUsersByRole($role)
    {
        return $this->where('role', $role)
                   ->where('user_status', 1)
                   ->findAll();
    }

    /**
     * Get users by supervisor capability
     * Returns all users who have supervisor role (role = 'supervisor')
     */
    public function getUsersBySupervisorCapability()
    {
        return $this->where('role', 'supervisor')
                   ->where('user_status', 1)
                   ->findAll();
    }

    /**
     * Get subordinates (users reporting to a specific user)
     */
    public function getSubordinates($userId)
    {
        return $this->where('report_to_id', $userId)
                   ->where('user_status', 1)
                   ->findAll();
    }

    /**
     * Get supervisor (user that a specific user reports to)
     */
    public function getSupervisor($userId)
    {
        $user = $this->find($userId);
        if ($user && $user['report_to_id']) {
            return $this->find($user['report_to_id']);
        }
        return null;
    }

    /**
     * Authenticate user
     */
    public function authenticate($email, $password)
    {
        $user = $this->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }

        return null;
    }

    /**
     * Get all users with branch information
     */
    public function getAllUsersWithBranches()
    {
        $builder = $this->db->table($this->table . ' as u');
        $builder->select([
            'u.*',
            'b.name as branch_name'
        ]);
        $builder->join('branches as b', 'b.id = u.branch_id', 'left');
        $builder->where('u.user_status', 1);
        $builder->orderBy('u.fname', 'ASC');

        return $builder->get()->getResultArray() ?? [];
    }

    /**
     * Get user's full name
     */
    public function getFullName($userId)
    {
        $user = $this->find($userId);
        if ($user) {
            return trim($user['fname'] . ' ' . $user['lname']);
        }
        return '';
    }

    /**
     * Update user status
     *
     * @param int $userId User ID
     * @param int $status New status (1 for active, 0 for inactive)
     * @param int $updatedBy ID of the user making the change
     * @param string $remarks Optional remarks about the status change
     * @return bool Success or failure
     */
    public function updateStatus($userId, $status, $updatedBy, $remarks = '')
    {
        return $this->update($userId, [
            'user_status' => $status,
            'user_status_remarks' => $remarks,
            'user_status_at' => date('Y-m-d H:i:s'),
            'user_status_by' => $updatedBy,
            'updated_by' => $updatedBy
        ]);
    }

    /**
     * Update user profile photo
     */
    public function updateProfilePhoto($userId, $filepath, $updatedBy)
    {
        return $this->update($userId, [
            'id_photo_filepath' => $filepath,
            'updated_by' => $updatedBy
        ]);
    }

    /**
     * Generate a secure activation token
     */
    public function generateActivationToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Set activation token and expiration for a user
     */
    public function setActivationToken(int $userId, string $token, int $hoursValid = 48): bool
    {
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$hoursValid} hours"));

        return $this->update($userId, [
            'activation_token' => hash('sha256', $token),
            'activation_expires_at' => $expiresAt,
            'is_activated' => 0,
            'user_status' => 0  // Set to inactive until activated
        ]);
    }

    /**
     * Validate activation token and return user if valid
     */
    public function validateActivationToken(string $token): ?array
    {
        $hashedToken = hash('sha256', $token);

        $user = $this->where('activation_token', $hashedToken)
                     ->where('activation_expires_at >', date('Y-m-d H:i:s'))
                     ->where('is_activated', 0)
                     ->first();

        return $user ?: null;
    }

    /**
     * Activate user account
     */
    public function activateUser(int $userId, string $tempPassword): bool
    {
        $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

        return $this->update($userId, [
            'is_activated' => 1,
            'activated_at' => date('Y-m-d H:i:s'),
            'user_status' => 1,  // Set to active
            'password' => $hashedPassword,
            'activation_token' => null,
            'activation_expires_at' => null
        ]);
    }

    /**
     * Check if user needs activation
     */
    public function needsActivation(int $userId): bool
    {
        $user = $this->find($userId);
        return $user && $user['is_activated'] == 0;
    }

    /**
     * Get users pending activation
     */
    public function getPendingActivationUsers(): array
    {
        return $this->where('is_activated', 0)
                    ->where('activation_token IS NOT NULL')
                    ->findAll();
    }

    /**
     * Check if user was created within specified hours (for delete permission)
     */
    public function isRecentlyCreated(int $userId, int $hours = 24): bool
    {
        $user = $this->find($userId);
        if (!$user) return false;

        $createdTime = strtotime($user['created_at']);
        $cutoffTime = time() - ($hours * 3600);

        return $createdTime > $cutoffTime;
    }
}