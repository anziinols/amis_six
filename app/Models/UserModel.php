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
        'updated_by'
    ];

    // Validation rules
    protected $validationRules = [
        'ucode' => 'required|max_length[200]',
        'password' => 'required|min_length[4]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
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
        'commodity_id' => 'permit_empty|integer',
        'role' => 'required|in_list[admin,supervisor,user,guest,commodity]',
        'joined_date' => 'permit_empty|valid_date',
        'id_photo_filepath' => 'permit_empty|max_length[255]',
        'user_status' => 'permit_empty|in_list[1,0]',
        'user_status_remarks' => 'permit_empty',
        'user_status_at' => 'permit_empty|valid_date[Y-m-d H:i:s]',
        'user_status_by' => 'permit_empty|integer',
        'created_by' => 'permit_empty|integer',
        'updated_by' => 'permit_empty|integer'
    ];

    // Validation messages
    protected $validationMessages = [
        'ucode' => [
            'required' => 'User code is required',
            'max_length' => 'User code cannot exceed 200 characters'
        ],
        'email' => [
            'required' => 'Email address is required',
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'This email is already registered'
        ],
        'fname' => [
            'required' => 'First name is required',
            'max_length' => 'First name cannot exceed 255 characters'
        ],
        'lname' => [
            'required' => 'Last name is required',
            'max_length' => 'Last name cannot exceed 255 characters'
        ],
        'role' => [
            'required' => 'User role is required',
            'in_list' => 'Invalid role selected'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Hash password before insert/update
     */
    protected function hashPassword(array $data)
    {
        // Handle both data formats: ['data']['password'] and ['password']
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            // Check if password is already hashed (bcrypt hashes start with $2y$ and are 60 chars long)
            if (!$this->isPasswordHashed($data['data']['password'])) {
                $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
                log_message('debug', 'Password hashed in model via data.password');
            } else {
                log_message('debug', 'Password already hashed in model via data.password');
            }
        } elseif (isset($data['password']) && !empty($data['password'])) {
            // Check if password is already hashed (bcrypt hashes start with $2y$ and are 60 chars long)
            if (!$this->isPasswordHashed($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                log_message('debug', 'Password hashed in model via password');
            } else {
                log_message('debug', 'Password already hashed in model via password');
            }
        }
        return $data;
    }

    /**
     * Check if a password is already hashed
     */
    private function isPasswordHashed($password)
    {
        // Bcrypt hashes start with $2y$ or $2a$ or $2b$ and are typically 60 characters long
        return (strlen($password) === 60 && preg_match('/^\$2[ayb]\$/', $password));
    }

    /**
     * Before insert callback
     */
    protected function beforeInsert(array $data)
    {
        log_message('debug', 'beforeInsert called with data: ' . json_encode(array_keys($data)));
        return $this->hashPassword($data);
    }

    /**
     * Before update callback
     */
    protected function beforeUpdate(array $data)
    {
        return $this->hashPassword($data);
    }

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
}