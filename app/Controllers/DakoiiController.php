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
            'role' => $this->request->getPost('role')
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
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }

        $userId = session()->get('dakoii_user_id');
        $user = $this->dakoiiUserModel->find($userId);

        if (!$user) {
            return redirect()->to('dakoii/profile')
                           ->with('error', 'User not found');
        }

        // Get form data
        $name = $this->request->getPost('name');
        $username = $this->request->getPost('username');
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Validate current password
        if (!password_verify($currentPassword, $user['password'])) {
            return redirect()->to('dakoii/profile')
                           ->with('error', 'Current password is incorrect');
        }

        // Prepare update data
        $updateData = [
            'name' => $name,
            'username' => $username
        ];

        // Handle password update if provided
        if (!empty($newPassword)) {
            if ($newPassword !== $confirmPassword) {
                return redirect()->to('dakoii/profile')
                               ->with('error', 'New passwords do not match');
            }

            if (strlen($newPassword) < 8) {
                return redirect()->to('dakoii/profile')
                               ->with('error', 'New password must be at least 8 characters long');
            }

            $updateData['password'] = $newPassword; // Will be hashed by model
        }

        try {
            $this->dakoiiUserModel->update($userId, $updateData);

            // Update session data
            session()->set([
                'dakoii_name' => $name,
                'dakoii_username' => $username
            ]);

            return redirect()->to('dakoii/profile')
                           ->with('success', 'Profile updated successfully');
        } catch (\Exception $e) {
            return redirect()->to('dakoii/profile')
                           ->with('error', 'Failed to update profile. Please try again.');
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
     * List all system administrators
     */
    public function administrators()
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }

        // Check if current user is admin
        if (session()->get('dakoii_role') !== 'admin') {
            return redirect()->to('dakoii/dashboard')
                           ->with('error', 'Access denied. Admin privileges required.');
        }
        
        $data = [
            'title' => 'System Administrators',
            'administrators' => $this->userModel->where('role', 'admin')
                                              ->findAll()
        ];
        
        return view('dakoii/administrators/dakoii_adminList', $data);
    }

    /**
     * Create Administrator Form
     */
    public function createAdministrator()
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }

        // Check if current user is admin
        if (session()->get('dakoii_role') !== 'admin') {
            return redirect()->to('dakoii/dashboard')
                           ->with('error', 'Access denied. Admin privileges required.');
        }
        
        $data = [
            'title' => 'Add New Administrator',
            'validation' => \Config\Services::validation()
        ];
        
        return view('dakoii/administrators/dakoii_createAdmin', $data);
    }

    /**
     * Store Administrator Method
     */
    public function storeAdministrator()
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }
        
        // Check if current user is admin
        if (session()->get('dakoii_role') !== 'admin') {
            return redirect()->to('dakoii/dashboard')
                           ->with('error', 'Access denied. Admin privileges required.');
        }

        // Get POST data
        $data = [
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'fname' => $this->request->getPost('fname'),
            'lname' => $this->request->getPost('lname'),
            'role' => 'admin',
            'user_status' => 1, // Set status as active
            'created_by' => session()->get('dakoii_user_id')
        ];
        
        // Validate required fields
        if (empty($data['email']) || empty($data['password']) || empty($data['fname']) || empty($data['lname'])) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Please fill in all required fields');
        }

        // Validate password strength
        if (strlen($data['password']) < 4) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Password must be at least 4 characters long');
        }
        
        try {
            $this->userModel->insert($data);
            return redirect()->to('dakoii/administrators')
                           ->with('success', 'Administrator created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to create administrator. Please try again.');
        }
    }

    /**
     * Edit Administrator Form
     */
    public function editAdministrator($id = null)
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }

        // Check if current user is admin
        if (session()->get('dakoii_role') !== 'admin') {
            return redirect()->to('dakoii/dashboard')
                           ->with('error', 'Access denied. Admin privileges required.');
        }
        
        $admin = $this->userModel->where('role', 'admin')
                               ->find($id);
        
        if (!$admin) {
            return redirect()->to('dakoii/administrators')
                           ->with('error', 'Administrator not found');
        }

        $data = [
            'title' => 'Edit Administrator',
            'admin' => $admin,
            'validation' => \Config\Services::validation()
        ];
        
        return view('dakoii/administrators/dakoii_editAdmin', $data);
    }

    /**
     * Update Administrator Method
     */
    public function updateAdministrator($id = null)
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }

        // Check if current user is admin
        if (session()->get('dakoii_role') !== 'admin') {
            return redirect()->to('dakoii/dashboard')
                           ->with('error', 'Access denied. Admin privileges required.');
        }

        // Get POST data
        $data = [
            'email' => $this->request->getPost('email'),
            'fname' => $this->request->getPost('fname'),
            'lname' => $this->request->getPost('lname'),
            'role' => 'admin',
            'user_status' => 1,
            'updated_by' => session()->get('dakoii_user_id')
        ];

        // Validate required fields
        if (empty($data['email']) || empty($data['fname']) || empty($data['lname'])) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Please fill in all required fields');
        }

        // Handle password update if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            if (strlen($password) < 4) {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Password must be at least 4 characters long');
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        try {
            $db = \Config\Database::connect();
            $builder = $db->table('users');
            $builder->where('id', $id);
            $builder->where('role', 'admin');
            $builder->update($data);

            return redirect()->to('dakoii/administrators')
                           ->with('success', 'Administrator updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to update administrator. Please try again.');
        }
    }

    /**
     * Delete Administrator Method
     */
    public function deleteAdministrator($id = null)
    {
        if ($this->verifyLoggedIn() !== true) {
            return $this->verifyLoggedIn();
        }

        // Check if current user is admin
        if (session()->get('dakoii_role') !== 'admin') {
            return redirect()->to('dakoii/dashboard')
                           ->with('error', 'Access denied. Admin privileges required.');
        }
        
        $admin = $this->userModel->where('role', 'admin')
                               ->find($id);
        
        if (!$admin) {
            return redirect()->to('dakoii/administrators')
                           ->with('error', 'Administrator not found');
        }

        try {
            $this->userModel->delete($id);
            return redirect()->to('dakoii/administrators')
                           ->with('success', 'Administrator deleted successfully');
        } catch (\Exception $e) {
            return redirect()->to('dakoii/administrators')
                           ->with('error', 'Failed to delete administrator');
        }
    }
} 