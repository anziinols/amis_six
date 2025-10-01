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

    // Use timestamps and soft deletes
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

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
        'is_admin',
        'role',
        'joined_date',
        'id_photo_filepath',
        'user_status',
        'user_status_remarks',
        'user_status_at',
        'user_status_by',
        'created_by',
        'updated_by',
        'deleted_by',
        'activation_token',
        'activation_expires_at',
        'activated_at',
        'is_activated'
    ];

    // Simple validation rules - no complex email uniqueness check
    // Password not required for new users (activation workflow)
    // User code (ucode) not required - auto-generated in controller
    protected $validationRules = [
        'ucode' => 'permit_empty|max_length[200]',
        'email' => 'required|valid_email|max_length[255]',
        'phone' => 'permit_empty',
        'fname' => 'required|max_length[255]',
        'lname' => 'required|max_length[255]',
        'gender' => 'permit_empty|in_list[male,female]',
        'dobirth' => 'permit_empty|valid_date',
        'place_birth' => 'permit_empty|max_length[255]',
        'address' => 'permit_empty',
        'employee_number' => 'permit_empty|max_length[100]',
        'branch_id' => 'permit_empty|integer',
        'designation' => 'permit_empty|max_length[255]',
        'grade' => 'permit_empty|max_length[100]',
        'report_to_id' => 'permit_empty|integer',
        'is_evaluator' => 'permit_empty|in_list[0,1]',
        'is_supervisor' => 'permit_empty|in_list[0,1]',
        'is_admin' => 'permit_empty|in_list[0,1]',
        'role' => 'required|in_list[user,guest]',
        'joined_date' => 'permit_empty|valid_date',
        'id_photo_filepath' => 'permit_empty|max_length[255]',
        'user_status' => 'permit_empty|in_list[0,1]',
        'user_status_remarks' => 'permit_empty',
        'created_by' => 'permit_empty|integer',
        'updated_by' => 'permit_empty|integer',
        'deleted_by' => 'permit_empty|integer'
    ];

    // Simple validation messages
    protected $validationMessages = [
        'ucode' => [
            'max_length' => 'User code cannot exceed 200 characters'
        ],
        'email' => [
            'required' => 'Email address is required',
            'valid_email' => 'Please enter a valid email address',
            'max_length' => 'Email cannot exceed 255 characters'
        ],
        'fname' => [
            'required' => 'First name is required',
            'max_length' => 'First name cannot exceed 255 characters'
        ],
        'lname' => [
            'required' => 'Last name is required',
            'max_length' => 'Last name cannot exceed 255 characters'
        ],
        'gender' => [
            'in_list' => 'Gender must be either male or female'
        ],
        'role' => [
            'required' => 'User role is required',
            'in_list' => 'Role must be either user or guest'
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
     * Get users by supervisor capability
     * Returns all users who have is_supervisor = 1
     */
    public function getUsersBySupervisorCapability()
    {
        return $this->where('is_supervisor', 1)
                   ->where('user_status', 1)
                   ->findAll();
    }

    /**
     * Get users by admin capability
     * Returns all users who have is_admin = 1
     */
    public function getUsersByAdminCapability()
    {
        return $this->where('is_admin', 1)
                   ->where('user_status', 1)
                   ->findAll();
    }

    /**
     * Get users by evaluator capability
     * Returns all users who have is_evaluator = 1
     */
    public function getUsersByEvaluatorCapability()
    {
        return $this->where('is_evaluator', 1)
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

    /**
     * Generate unique user code
     */
    public function generateUniqueUserCode($prefix = 'USR'): string
    {
        do {
            $code = $prefix . '-' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while ($this->where('ucode', $code)->first());

        return $code;
    }

    /**
     * Get user by user code
     */
    public function getUserByUcode($ucode)
    {
        return $this->where('ucode', $ucode)
                   ->where('user_status', 1)
                   ->first();
    }

    /**
     * Update user capabilities (admin, supervisor, evaluator)
     */
    public function updateCapabilities($userId, $isAdmin = 0, $isSupervisor = 0, $isEvaluator = 0, $updatedBy = null)
    {
        return $this->update($userId, [
            'is_admin' => $isAdmin,
            'is_supervisor' => $isSupervisor,
            'is_evaluator' => $isEvaluator,
            'updated_by' => $updatedBy ?? session()->get('user_id')
        ]);
    }

    /**
     * Get all users with detailed information including branch and supervisor
     */
    public function getAllUsersWithDetails()
    {
        $db = \Config\Database::connect();

        $query = $db->table('users u')
            ->select('u.*,
                     b.name as branch_name,
                     CONCAT(s.fname, " ", s.lname) as supervisor_name,
                     CONCAT(cb.fname, " ", cb.lname) as created_by_name,
                     CONCAT(ub.fname, " ", ub.lname) as updated_by_name')
            ->join('branches b', 'u.branch_id = b.id', 'left')
            ->join('users s', 'u.report_to_id = s.id', 'left')
            ->join('users cb', 'u.created_by = cb.id', 'left')
            ->join('users ub', 'u.updated_by = ub.id', 'left')
            ->where('u.deleted_at', null)
            ->orderBy('u.fname', 'ASC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Soft delete user
     */
    public function softDeleteUser($userId, $deletedBy = null)
    {
        return $this->update($userId, [
            'deleted_at' => date('Y-m-d H:i:s'),
            'deleted_by' => $deletedBy ?? session()->get('user_id'),
            'user_status' => 0
        ]);
    }

    /**
     * Get user accessibility flags as formatted badges (HTML)
     * Returns HTML badges for is_admin, is_supervisor, is_evaluator
     *
     * @param array $user User data array or null to use session
     * @return string HTML string with badges
     */
    public function getUserAccessibilityBadges($user = null): string
    {
        // If no user data provided, get from session
        if ($user === null) {
            $user = [
                'is_admin' => session()->get('is_admin') ?? 0,
                'is_supervisor' => session()->get('is_supervisor') ?? 0,
                'is_evaluator' => session()->get('is_evaluator') ?? 0
            ];
        }

        $badges = [];

        if (isset($user['is_admin']) && $user['is_admin'] == 1) {
            $badges[] = '<span class="badge bg-danger"><i class="fas fa-user-shield me-1"></i>Admin</span>';
        }

        if (isset($user['is_supervisor']) && $user['is_supervisor'] == 1) {
            $badges[] = '<span class="badge bg-warning"><i class="fas fa-user-tie me-1"></i>Supervisor</span>';
        }

        if (isset($user['is_evaluator']) && $user['is_evaluator'] == 1) {
            $badges[] = '<span class="badge bg-info"><i class="fas fa-clipboard-check me-1"></i>Evaluator</span>';
        }

        return !empty($badges) ? implode(' ', $badges) : '';
    }

    /**
     * Get user accessibility flags as array
     * Returns array with boolean values for is_admin, is_supervisor, is_evaluator
     *
     * @param array $user User data array or null to use session
     * @return array Associative array with accessibility flags
     */
    public function getUserAccessibilityFlags($user = null): array
    {
        // If no user data provided, get from session
        if ($user === null) {
            return [
                'is_admin' => (bool)(session()->get('is_admin') ?? 0),
                'is_supervisor' => (bool)(session()->get('is_supervisor') ?? 0),
                'is_evaluator' => (bool)(session()->get('is_evaluator') ?? 0)
            ];
        }

        return [
            'is_admin' => (bool)($user['is_admin'] ?? 0),
            'is_supervisor' => (bool)($user['is_supervisor'] ?? 0),
            'is_evaluator' => (bool)($user['is_evaluator'] ?? 0)
        ];
    }

    /**
     * Get user role with accessibility flags as text
     * Returns formatted string like "User (Admin, Supervisor)"
     *
     * @param array $user User data array or null to use session
     * @return string Formatted role with flags
     */
    public function getRoleWithAccessibilityText($user = null): string
    {
        // If no user data provided, get from session
        if ($user === null) {
            $role = session()->get('role') ?? 'user';
            $user = [
                'is_admin' => session()->get('is_admin') ?? 0,
                'is_supervisor' => session()->get('is_supervisor') ?? 0,
                'is_evaluator' => session()->get('is_evaluator') ?? 0
            ];
        } else {
            $role = $user['role'] ?? 'user';
        }

        $flags = [];

        if (isset($user['is_admin']) && $user['is_admin'] == 1) {
            $flags[] = 'Admin';
        }

        if (isset($user['is_supervisor']) && $user['is_supervisor'] == 1) {
            $flags[] = 'Supervisor';
        }

        if (isset($user['is_evaluator']) && $user['is_evaluator'] == 1) {
            $flags[] = 'Evaluator';
        }

        $roleText = ucfirst($role);

        if (!empty($flags)) {
            $roleText .= ' (' . implode(', ', $flags) . ')';
        }

        return $roleText;
    }

    /**
     * Check if user has specific accessibility flag
     *
     * @param string $flag Flag to check (admin, supervisor, evaluator)
     * @param array $user User data array or null to use session
     * @return bool True if user has the flag
     */
    public function hasAccessibilityFlag(string $flag, $user = null): bool
    {
        $flagField = 'is_' . strtolower($flag);

        // If no user data provided, get from session
        if ($user === null) {
            return (bool)(session()->get($flagField) ?? 0);
        }

        return (bool)($user[$flagField] ?? 0);
    }
}