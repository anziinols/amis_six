<?php

namespace App\Controllers;

use App\Models\DakoiiUserModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

/**
 * DakoiiController
 * 
 * Handles all Dakoii portal related functionality
 */
class DakoiiController extends ResourceController
{
    protected $dakoiiUserModel;
    protected $userModel;
    
    public function __construct()
    {
        $this->dakoiiUserModel = new DakoiiUserModel();
        $this->userModel = new UserModel();
    }
    
    /**
     * Verify if user is logged in, redirect to login if not
     *
     * @return bool
     */
    private function verifyLoggedIn()
    {
        $session = session();
        if (!$session->get('dakoii_logged_in')) {
            return redirect()->to(base_url('dakoii'))->with('error', 'Please login to access the dashboard');
        }
        
        return true;
    }
    
    /**
     * Dashboard landing page
     */
    public function dashboard()
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }
        
        // Get user data from session
        $data = [
            'title' => 'Dashboard',
            'user' => [
                'name' => session()->get('dakoii_name'),
                'role' => session()->get('dakoii_role'),
                'id' => session()->get('dakoii_user_id')
            ],
            'total_users' => $this->dakoiiUserModel->countAllResults()
        ];
        
        return view('dakoii/dakoii_dashboard', $data);
    }
    
    /**
     * User List Method
     */
    public function userList()
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }
        
        $data = [
            'title' => 'User Management',
            'users' => $this->dakoiiUserModel->findAll()
        ];
        
        return view('dakoii/users/dakoii_userList', $data);
    }
    
    /**
     * Create User Form
     */
    public function createUser()
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }
        
        $data = [
            'title' => 'Add New User',
            'validation' => \Config\Services::validation()
        ];
        
        return view('dakoii/users/dakoii_createUser', $data);
    }
    
    /**
     * Store User Method
     */
    public function storeUser()
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }
        
        // Validation rules are already in the model
        if (!$this->validate($this->dakoiiUserModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $password = $this->request->getPost('password');

        // Hash password explicitly to ensure it's properly hashed
        if (!empty($password)) {
            // Check if password is already hashed to avoid double hashing
            if (strlen($password) !== 60 || !preg_match('/^\$2[ayb]\$/', $password)) {
                $password = password_hash($password, PASSWORD_DEFAULT);
                log_message('debug', 'Password hashed in DakoiiController storeUser method');
            } else {
                log_message('debug', 'Password already hashed in DakoiiController storeUser method');
            }
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'password' => $password,
            'role' => $this->request->getPost('role'),
            'dakoii_user_status' => 1,
            'dakoii_user_status_at' => date('Y-m-d H:i:s'),
            'dakoii_user_status_by' => session()->get('dakoii_user_id')
        ];

        try {
            $this->dakoiiUserModel->insert($data);
            return redirect()->to('dakoii/users')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create user. Please try again.');
        }
    }
    
    /**
     * Edit User Form
     */
    public function editUser($id = null)
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }
        
        $user = $this->dakoiiUserModel->find($id);
        
        if (!$user) {
            return redirect()->to('dakoii/users')->with('error', 'User not found');
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'validation' => \Config\Services::validation()
        ];
        
        return view('dakoii/users/dakoii_editUser', $data);
    }
    
    /**
     * Update User Method
     */
    public function updateUser($id = null)
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }
        
        $user = $this->dakoiiUserModel->find($id);
        
        if (!$user) {
            return redirect()->to('dakoii/users')->with('error', 'User not found');
        }

        // Modify validation rules for update
        $rules = $this->dakoiiUserModel->validationRules;
        if (empty($this->request->getPost('password'))) {
            unset($rules['password']); // Don't validate password if not being updated
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'role' => $this->request->getPost('role'),
            'dakoii_user_status' => $this->request->getPost('dakoii_user_status')
        ];

        // Only update password if provided and hash it
        if ($this->request->getPost('password')) {
            $password = $this->request->getPost('password');
            // Check if password is already hashed to avoid double hashing
            if (strlen($password) !== 60 || !preg_match('/^\$2[ayb]\$/', $password)) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
                log_message('debug', 'Password hashed in DakoiiController updateUser method');
            } else {
                $data['password'] = $password;
                log_message('debug', 'Password already hashed in DakoiiController updateUser method');
            }
        }

        try {
            $this->dakoiiUserModel->update($id, $data);
            return redirect()->to('dakoii/users')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update user. Please try again.');
        }
    }
    
    /**
     * Delete User Method
     */
    public function deleteUser($id = null)
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }
        
        try {
            $this->dakoiiUserModel->delete($id);
            return redirect()->to('dakoii/users')->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return redirect()->to('dakoii/users')->with('error', 'Failed to delete user');
        }
    }
    
    /**
     * User Roles Method
     */
    public function userRoles()
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }
        
        $data = [
            'title' => 'User Roles',
            'users_by_role' => $this->dakoiiUserModel->select('role, COUNT(*) as count')
                                              ->groupBy('role')
                                              ->findAll()
        ];
        
        return view('dakoii/users/dakoii_userRoles', $data);
    }
    
    /**
     * User profile page
     */
    public function profile()
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }
        
        $userId = session()->get('dakoii_user_id');
        $user = $this->dakoiiUserModel->find($userId);
        
        if (!$user) {
            return redirect()->to('dakoii/dashboard')
                           ->with('error', 'User profile not found');
        }

        $data = [
            'title' => 'My Profile',
            'user' => $user,
            'validation' => \Config\Services::validation()
        ];
        
        return view('dakoii/dakoii_profile', $data);
    }

    /**
     * Update user profile
     */
    public function updateProfile()
    {
        log_message('debug', '=== updateProfile method called ===');
        log_message('debug', 'Request method: ' . $this->request->getMethod());
        log_message('debug', 'Request URI: ' . $this->request->getUri());
        log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));

        // Check if this is actually a POST request
        if (!$this->request->getMethod() === 'post') {
            log_message('error', 'updateProfile called with non-POST method: ' . $this->request->getMethod());
            return redirect()->to('dakoii/profile')->with('error', 'DEBUG: Invalid request method: ' . $this->request->getMethod());
        }

        if ($this->verifyLoggedIn() !== true) {
            log_message('debug', 'User not logged in');
            return $this->verifyLoggedIn();
        }

        $userId = session()->get('dakoii_user_id');
        log_message('debug', 'User ID from session: ' . $userId);

        $user = $this->dakoiiUserModel->find($userId);
        log_message('debug', 'User found: ' . ($user ? 'Yes' : 'No'));

        if (!$user) {
            log_message('error', 'User not found for ID: ' . $userId);
            return redirect()->to('dakoii/profile')->with('error', 'DEBUG: User not found for ID: ' . $userId);
        }

        // Get form data
        $name = $this->request->getPost('name');
        $username = $this->request->getPost('username');
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        log_message('debug', 'Form data received - Name: ' . $name . ', Username: ' . $username . ', Current Password: ' . ($currentPassword ? 'provided' : 'empty') . ', New Password: ' . ($newPassword ? 'provided' : 'empty'));

        // Check if form data is empty
        if (empty($name) || empty($username) || empty($currentPassword)) {
            log_message('error', 'Required form data missing');
            return redirect()->to('dakoii/profile')->with('error', 'DEBUG: Required fields missing - Name: ' . ($name ? 'OK' : 'EMPTY') . ', Username: ' . ($username ? 'OK' : 'EMPTY') . ', Current Password: ' . ($currentPassword ? 'OK' : 'EMPTY'));
        }

        // Validate current password (simple check)
        log_message('debug', 'Stored password: ' . $user['password'] . ', Provided password: ' . $currentPassword);
        if ($currentPassword !== $user['password']) {
            log_message('error', 'Current password incorrect');
            return redirect()->to('dakoii/profile')->with('error', 'DEBUG: Current password is incorrect. Stored: ' . substr($user['password'], 0, 10) . '..., Provided: ' . substr($currentPassword, 0, 10) . '...');
        }

        // Prepare update data
        $updateData = [
            'name' => $name,
            'username' => $username
        ];

        // Handle password update if provided
        if (!empty($newPassword)) {
            log_message('debug', 'New password provided, validating...');

            if ($newPassword !== $confirmPassword) {
                log_message('error', 'New passwords do not match');
                return redirect()->to('dakoii/profile')->with('error', 'DEBUG: New passwords do not match');
            }

            if (strlen($newPassword) < 4) {
                log_message('error', 'New password too short');
                return redirect()->to('dakoii/profile')->with('error', 'DEBUG: New password must be at least 4 characters long');
            }

            // Hash the password directly here
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateData['password'] = $hashedPassword;
            log_message('debug', 'Password hashed successfully: ' . substr($hashedPassword, 0, 20) . '...');
        }

        log_message('debug', 'Update data prepared: ' . json_encode($updateData));

        // Set custom validation rules to exclude current user from username uniqueness check
        $validationRules = [
            'name' => 'required|min_length[3]|max_length[255]',
            'username' => 'required|min_length[3]|max_length[255]|is_unique[dakoii_users.username,id,' . $userId . ']'
        ];

        // Validate the data
        if (!$this->validate($validationRules)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'Validation errors: ' . json_encode($errors));
            return redirect()->to('dakoii/profile')->with('error', 'DEBUG: Validation errors: ' . implode(', ', $errors));
        }

        // Temporarily disable model validation since we already validated in controller
        $this->dakoiiUserModel->skipValidation(true);

        // Update using the model
        $result = $this->dakoiiUserModel->update($userId, $updateData);
        log_message('debug', 'Model update result: ' . ($result ? 'TRUE' : 'FALSE'));

        // Re-enable model validation for future operations
        $this->dakoiiUserModel->skipValidation(false);

        // Check for model errors
        $errors = $this->dakoiiUserModel->errors();
        if (!empty($errors)) {
            log_message('error', 'Model validation errors: ' . json_encode($errors));
            return redirect()->to('dakoii/profile')->with('error', 'DEBUG: Model validation errors: ' . implode(', ', $errors));
        }

        if ($result) {
            log_message('debug', 'Profile updated successfully');

            // Update session data
            session()->set([
                'dakoii_name' => $name,
                'dakoii_username' => $username
            ]);

            return redirect()->to('dakoii/profile')->with('success', 'Profile updated successfully');
        } else {
            log_message('error', 'Model update returned false');
            return redirect()->to('dakoii/profile')->with('error', 'DEBUG: Model update returned false. Check database connection and field validation.');
        }
    }

    /**
     * Logout method
     */
    public function logout()
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }
        
        session()->destroy();
        return redirect()->to(base_url('dakoii'))->with('success', 'Logged out successfully');
    }

    /**
     * List all system users
     */
    public function administrators()
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }

        // Check if current user has admin role in Dakoii system
        if (session()->get('dakoii_role') !== 'admin') {
            return redirect()->to('dakoii/dashboard')
                           ->with('error', 'Access denied. Administrator role required.');
        }

        $data = [
            'title' => 'System Users Management',
            'administrators' => $this->userModel->findAll()
        ];

        return view('dakoii/administrators/dakoii_adminList', $data);
    }

    /**
     * Show create user form
     */
    public function create()
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }

        // Check if current user has admin role in Dakoii system
        if (session()->get('dakoii_role') !== 'admin') {
            return redirect()->to('dakoii/dashboard')
                           ->with('error', 'Access denied. Administrator role required.');
        }

        $data = [
            'title' => 'Add New System User',
            'validation' => \Config\Services::validation()
        ];

        return view('dakoii/administrators/dakoii_createAdmin', $data);
    }

    /**
     * Store new administrator - Simple and straightforward
     */
    public function store()
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }

        // Check if current user has admin role in Dakoii system
        if (session()->get('dakoii_role') !== 'admin') {
            return redirect()->to('dakoii/dashboard')
                           ->with('error', 'Access denied. Administrator role required.');
        }

        // Prepare data with capability fields
        $password = $this->request->getPost('password');
        $data = [
            'ucode' => $this->request->getPost('ucode') ?: uniqid(),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'phone' => $this->request->getPost('phone'),
            'fname' => $this->request->getPost('fname'),
            'lname' => $this->request->getPost('lname'),
            'role' => $this->request->getPost('role') ?: 'user',
            'is_admin' => $this->request->getPost('is_admin') ? 1 : 0,
            'is_supervisor' => $this->request->getPost('is_supervisor') ? 1 : 0,
            'is_evaluator' => $this->request->getPost('is_evaluator') ? 1 : 0,
            'user_status' => 1,
            'created_by' => session()->get('dakoii_user_id')
        ];

        // Simple validation
        if (empty($data['email']) || empty($data['password']) || empty($data['fname']) || empty($data['lname'])) {
            return redirect()->back()->withInput()->with('error', 'Please fill in all required fields');
        }

        // Check email uniqueness for new users
        $existingUser = $this->userModel->where('email', $data['email'])->first();
        if ($existingUser) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'This email address is already registered');
        }

        // Insert with model
        if ($this->userModel->insert($data)) {
            return redirect()->to('dakoii/administrators')
                           ->with('success', 'System user created successfully');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to create user. Please try again.');
        }
    }

    /**
     * Show edit administrator form
     */
    public function edit($id = null)
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }

        // Check if current user has admin role in Dakoii system
        if (session()->get('dakoii_role') !== 'admin') {
            return redirect()->to('dakoii/dashboard')
                           ->with('error', 'Access denied. Administrator role required.');
        }

        $admin = $this->userModel->find($id);

        if (!$admin) {
            return redirect()->to('dakoii/administrators')
                           ->with('error', 'User not found');
        }

        $data = [
            'title' => 'Edit System User',
            'admin' => $admin,
            'validation' => \Config\Services::validation()
        ];

        return view('dakoii/administrators/dakoii_editAdmin', $data);
    }

    /**
     * Update administrator - Simple and straightforward
     */
    public function update($id = null)
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }

        // Check if current user has admin role in Dakoii system
        if (session()->get('dakoii_role') !== 'admin') {
            return redirect()->to('dakoii/dashboard')
                           ->with('error', 'Access denied. Administrator role required.');
        }

        // Check if user exists
        $admin = $this->userModel->find($id);
        if (!$admin) {
            return redirect()->to('dakoii/administrators')
                           ->with('error', 'User not found');
        }

        // Simple data preparation
        $data = [
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'fname' => $this->request->getPost('fname'),
            'lname' => $this->request->getPost('lname'),
            'role' => $this->request->getPost('role'),
            'is_admin' => $this->request->getPost('is_admin') ? 1 : 0,
            'is_supervisor' => $this->request->getPost('is_supervisor') ? 1 : 0,
            'is_evaluator' => $this->request->getPost('is_evaluator') ? 1 : 0,
            'user_status' => $this->request->getPost('user_status')
        ];

        if (!empty($this->request->getPost('password'))) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        // Direct database update - bypass model validation
        $db = \Config\Database::connect();
        if ($db->table('users')->where('id', $id)->update($data)) {
            return redirect()->to('dakoii/administrators')->with('success', 'User updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update user. Please try again.');
        }
    }

    /**
     * Delete administrator
     */
    public function delete($id = null)
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }

        // Check if current user has admin role in Dakoii system
        if (session()->get('dakoii_role') !== 'admin') {
            return redirect()->to('dakoii/dashboard')
                           ->with('error', 'Access denied. Administrator role required.');
        }

        $admin = $this->userModel->find($id);

        if (!$admin) {
            return redirect()->to('dakoii/administrators')
                           ->with('error', 'User not found');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('dakoii/administrators')
                           ->with('success', 'Administrator deleted successfully');
        } else {
            return redirect()->to('dakoii/administrators')
                           ->with('error', 'Failed to delete administrator');
        }
    }
} 