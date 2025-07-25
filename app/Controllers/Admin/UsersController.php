<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\BranchesModel;
use App\Models\CommoditiesModel;

class UsersController extends BaseController
{
    protected $userModel;
    protected $branchesModel;
    protected $commoditiesModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->branchesModel = new BranchesModel();
        $this->commoditiesModel = new CommoditiesModel();
        helper(['email']);
    }

    public function index()
    {
        // Get users with branch information
        $users = $this->userModel
            ->select('users.*, branches.name as branch_name')
            ->join('branches', 'branches.id = users.branch_id', 'left')
            ->findAll();

        $data = [
            'title' => 'Users Management',
            'users' => $users
        ];

        return view('admin/users/admin_users_index', $data);
    }

    public function new()
    {
        // Get branches for dropdown
        $branches = $this->branchesModel->getBranchesForDropdown();

        // Get supervisors for dropdown (users with is_supervisor capability)
        $supervisors = $this->userModel->getUsersBySupervisorCapability();

        // Get commodities for dropdown
        $commodities = $this->commoditiesModel->getActiveCommodities();

        $data = [
            'title' => 'Add New User',
            'branches' => $branches,
            'supervisors' => $supervisors,
            'commodities' => $commodities
        ];

        return view('admin/users/admin_users_create', $data);
    }

    /**
     * Show the form for creating a new user (GET)
     */
    public function create()
    {
        // Get branches for dropdown
        $branches = $this->branchesModel->getBranchesForDropdown();

        // Get supervisors for dropdown (users with is_supervisor capability)
        $supervisors = $this->userModel->getUsersBySupervisorCapability();

        // Get commodities for dropdown
        $commodities = $this->commoditiesModel->getActiveCommodities();

        $data = [
            'title' => 'Add New User',
            'branches' => $branches,
            'supervisors' => $supervisors,
            'commodities' => $commodities
        ];

        return view('admin/users/admin_users_create', $data);
    }

    /**
     * Display a specific user (GET)
     */
    public function show($id = null)
    {
        if ($id === null) {
            return redirect()->to('/admin/users')->with('error', 'Invalid user ID');
        }

        $user = $this->userModel->find($id);
        if (empty($user)) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }

        $data = [
            'title' => 'User Details',
            'user' => $user
        ];

        return view('admin/users/admin_users_show', $data);
    }

    /**
     * Store a new user in the database (POST)
     */
    public function store()
    {
        // Debug session data
        log_message('debug', 'Session data in store: ' . json_encode(session()->get()));
        log_message('debug', 'User ID in session (store): ' . json_encode(session()->get('user_id')));

        // Get POST data
        $userData = $this->request->getPost();

        // Explicitly set created_by and updated_by
        if (empty($userData['created_by'])) {
            $userData['created_by'] = session()->get('user_id') ?? 1;
        }

        if (empty($userData['updated_by'])) {
            $userData['updated_by'] = session()->get('user_id') ?? 1;
        }

        // Use the setGlobals method to set the values so validation sees them
        $_POST['created_by'] = $userData['created_by'];
        $_POST['updated_by'] = $userData['updated_by'];

        // Log received POST data
        log_message('debug', 'POST data with IDs: ' . json_encode($userData));

        // Simple validation
        $rules = $this->userModel->getValidationRules();
        $messages = $this->userModel->getValidationMessages();

        if (!$this->validate($rules, $messages)) {
            // Log validation errors
            log_message('debug', 'Validation errors: ' . json_encode($this->validator->getErrors()));

            // Return to form with validation errors
            return redirect()->to('admin/users/create')
                   ->withInput()
                   ->with('validation', $this->validator);
        }

        // Generate user code if not provided
        if (empty($userData['ucode'])) {
            $userData['ucode'] = 'USR' . date('Ymd') . rand(1000, 9999);
        }

        // Handle checkbox fields - set to 0 if not checked
        if (!isset($userData['is_evaluator'])) {
            $userData['is_evaluator'] = '0';
        }

        // Handle commodity_id based on role
        if ($userData['role'] !== 'commodity') {
            // If role is not 'commodity', clear the commodity_id
            $userData['commodity_id'] = null;
        } else {
            // If role is 'commodity', set to null if empty
            if (empty($userData['commodity_id'])) {
                $userData['commodity_id'] = null;
            }
        }

        // Hash password explicitly to ensure it's properly hashed
        if (!empty($userData['password'])) {
            // Check if password is already hashed to avoid double hashing
            if (strlen($userData['password']) !== 60 || !preg_match('/^\$2[ayb]\$/', $userData['password'])) {
                $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
                log_message('debug', 'Password hashed in controller store method');
            } else {
                log_message('debug', 'Password already hashed in controller store method');
            }
        } else {
            unset($userData['password']); // Remove empty password field
        }

        // Save to database
        if ($this->userModel->save($userData)) {
            // Get the ID of the newly created user
            $newUserId = $this->userModel->getInsertID();
            log_message('debug', 'User created successfully with ID: ' . $newUserId);

            // Send email notification to the new user
            $this->sendCreationNotificationEmail($newUserId, $userData);

            return redirect()->to('/admin/users')
                   ->with('success', 'User added successfully');
        } else {
            log_message('debug', 'Failed to save user. Database errors: ' . json_encode($this->userModel->errors()));
            return redirect()->back()
                   ->withInput()
                   ->with('error', 'Failed to add user. Please try again.');
        }
    }

    /**
     * Display the edit form for a user (GET)
     */
    public function edit($id = null)
    {
        if ($id === null) {
            return redirect()->to('/admin/users')->with('error', 'Invalid user ID');
        }

        // Get branches for dropdown
        $branches = $this->branchesModel->getBranchesForDropdown();

        // Get supervisors for dropdown (users with is_supervisor capability)
        $supervisors = $this->userModel->getUsersBySupervisorCapability();

        // Get commodities for dropdown
        $commodities = $this->commoditiesModel->getActiveCommodities();

        $data = [
            'title' => 'Edit User',
            'user' => $this->userModel->find($id),
            'branches' => $branches,
            'supervisors' => $supervisors,
            'commodities' => $commodities
        ];

        if (empty($data['user'])) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }

        // Fetch information about the user who changed the status (if applicable)
        if (!empty($data['user']['user_status_by'])) {
            $data['statusUser'] = $this->userModel->find($data['user']['user_status_by']);
        }

        return view('admin/users/admin_users_edit', $data);
    }

    /**
     * Process the user update form submission (POST)
     */
    public function update($id = null)
    {
        if ($id === null) {
            return redirect()->to('/admin/users')->with('error', 'Invalid user ID');
        }

        $user = $this->userModel->find($id);
        if (empty($user)) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }

        $rules = $this->userModel->getValidationRules();
        $messages = $this->userModel->getValidationMessages();

        // Remove ucode from validation rules for edit
        unset($rules['ucode']);

        // Remove email uniqueness validation - we'll check manually
        $rules['email'] = 'required|valid_email';

        // If password is empty, remove it from validation
        if (empty($this->request->getPost('password'))) {
            unset($rules['password']);
        }

        // First validate the basic rules
        if ($this->validate($rules, $messages)) {
            $userData = $this->request->getPost();
            $userData['updated_by'] = session()->get('user_id');

            // Keep the original ucode
            $userData['ucode'] = $user['ucode'];

            // Handle checkbox fields - set to 0 if not checked
            if (!isset($userData['is_evaluator'])) {
                $userData['is_evaluator'] = '0';
            }

            // Handle commodity_id based on role
            if ($userData['role'] !== 'commodity') {
                // If role is not 'commodity', clear the commodity_id
                $userData['commodity_id'] = null;
            } else {
                // If role is 'commodity', set to null if empty
                if (empty($userData['commodity_id'])) {
                    $userData['commodity_id'] = null;
                }
            }

            // Manual check for email uniqueness
            $emailToCheck = $userData['email'];
            $existingUser = $this->userModel->where('email', $emailToCheck)->first();

            // If email exists and belongs to someone else (not current user)
            if ($existingUser && $existingUser['id'] != $id) {
                // Email is used by another user
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'This email is already registered to another user.');
            }

            // Email is unique or belongs to current user, continue with update

            // Handle password - hash if provided, remove if empty
            if (empty($userData['password'])) {
                unset($userData['password']);
            } else {
                // Hash the password since we're using direct database query (bypassing model callbacks)
                $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
                log_message('debug', 'Password hashed in controller update method for direct DB query');
            }

            // Log the data being sent to the database
            log_message('debug', 'User data being updated: ' . json_encode($userData));

            try {
                // Use direct database query to update the user
                $db = \Config\Database::connect();
                $builder = $db->table('users');
                $builder->where('id', $id);
                $result = $builder->update($userData);

                if ($result) {
                    // If this is the logged-in user being edited, update their session
                    if (session()->get('user_id') == $id) {
                        $this->updateUserSession($userData);
                    }

                    // Send email notification to the user
                    $this->sendUpdateNotificationEmail($id, $userData);

                    return redirect()->to('/admin/users/edit/' . $id)->with('success', 'User updated successfully');
                } else {
                    log_message('error', 'Failed to update user with direct query. DB Error: ' . $db->error()['message']);
                    return redirect()->back()->withInput()->with('error', 'Failed to update user. Database error.');
                }
            } catch (\Exception $e) {
                log_message('error', 'Exception updating user: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Failed to update user: ' . $e->getMessage());
            }
        }

        // Get branches, supervisors, and commodities for the form if validation fails
        $branches = $this->branchesModel->getBranchesForDropdown();
        $supervisors = $this->userModel->getUsersBySupervisorCapability();
        $commodities = $this->commoditiesModel->getActiveCommodities();

        return view('admin/users/admin_users_edit', [
            'title' => 'Edit User',
            'user' => $user,
            'branches' => $branches,
            'supervisors' => $supervisors,
            'commodities' => $commodities,
            'validation' => $this->validator
        ]);
    }

    /**
     * Toggle user status between active and inactive
     */
    public function toggleStatus($id = null)
    {
        if ($id === null) {
            return redirect()->to('/admin/users')->with('error', 'Invalid user ID');
        }

        // Prevent deactivating your own account
        if ($id == session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'You cannot deactivate your own account');
        }

        $user = $this->userModel->find($id);
        if (empty($user)) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }

        // Toggle the status (0 to 1, 1 to 0)
        $newStatus = $user['user_status'] == 1 ? 0 : 1;
        $statusText = $newStatus == 1 ? 'activated' : 'deactivated';

        // Check if this is a form submission with remarks
        if ($this->request->getMethod() === 'post') {
            $remarks = $this->request->getPost('remarks');
            if (empty($remarks)) {
                $remarks = 'User ' . $statusText . ' by ' . session()->get('user_name') . ' on ' . date('Y-m-d H:i:s');
            }
        } else {
            // Default remarks for GET requests (backward compatibility)
            $remarks = 'User ' . $statusText . ' by ' . session()->get('user_name') . ' on ' . date('Y-m-d H:i:s');
        }

        if ($this->userModel->updateStatus($id, $newStatus, session()->get('user_id'), $remarks)) {
            // Send email notification about status change
            $this->sendStatusChangeNotificationEmail($id, $newStatus, $remarks);

            return redirect()->to('/admin/users')->with('success', 'User ' . $statusText . ' successfully');
        }

        return redirect()->to('/admin/users')->with('error', 'Failed to update user status');
    }

    /**
     * Update user session data
     */
    protected function updateUserSession($userData)
    {
        $sessionData = [
            'user_name' => $userData['fname'] . ' ' . $userData['lname'],
            'fname' => $userData['fname'],
            'lname' => $userData['lname'],
            'email' => $userData['email'],
            'role' => $userData['role'],
            'is_evaluator' => $userData['is_evaluator'] ?? 0,
            'commodity_id' => ($userData['role'] === 'commodity') ? ($userData['commodity_id'] ?? null) : null
        ];

        session()->set($sessionData);
    }

    /**
     * Send email notification to user about account update
     *
     * @param int $userId ID of the user being updated
     * @param array $userData Updated user data
     * @return bool Success or failure
     */
    protected function sendUpdateNotificationEmail($userId, $userData)
    {
        try {
            // Get the complete user data
            $user = $this->userModel->find($userId);
            if (!$user || empty($user['email'])) {
                log_message('error', 'Cannot send update notification: User not found or no email available');
                return false;
            }

            // Get the name of the person who made the update
            $updaterName = 'System Administrator';
            $updaterId = session()->get('user_id');
            if ($updaterId) {
                $updater = $this->userModel->find($updaterId);
                if ($updater) {
                    $updaterName = $updater['fname'] . ' ' . $updater['lname'];
                }
            }

            // Get updater's email
            $updaterEmail = session()->get('email') ?? 'noreply@dakoiims.com';

            // Prepare email subject and message
            $subject = 'Your Account Information Has Been Updated';

            // Create HTML message
            $message = '
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #4CAF50; color: white; padding: 10px; text-align: center; }
                    .content { padding: 20px; border: 1px solid #ddd; }
                    .footer { font-size: 12px; text-align: center; margin-top: 20px; color: #777; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>Account Update Notification</h2>
                    </div>
                    <div class="content">
                        <p>Dear ' . $user['fname'] . ' ' . $user['lname'] . ',</p>

                        <p>This is to inform you that your account information has been updated in the AMIS system.</p>

                        <p>The update was performed by: <strong>' . $updaterName . '</strong> (' . $updaterEmail . ')</p>

                        <p>If you did not authorize this change or have any questions, please contact your system administrator.</p>

                        <p>Thank you,<br>
                        AMIS System</p>
                    </div>
                    <div class="footer">
                        <p>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </body>
            </html>';

            // Send the email
            $result = send_email($user['email'], $subject, $message);

            if (!$result) {
                log_message('error', 'Failed to send update notification email to: ' . $user['email']);
            } else {
                log_message('info', 'Update notification email sent successfully to: ' . $user['email']);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception sending update notification email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email notification to user about account creation
     *
     * @param int $userId ID of the newly created user
     * @param array $userData User data
     * @return bool Success or failure
     */
    protected function sendCreationNotificationEmail($userId, $userData)
    {
        try {
            // Get the complete user data
            $user = $this->userModel->find($userId);
            if (!$user || empty($user['email'])) {
                log_message('error', 'Cannot send creation notification: User not found or no email available');
                return false;
            }

            // Get the name of the person who created the account
            $creatorName = 'System Administrator';
            $creatorId = session()->get('user_id');
            if ($creatorId) {
                $creator = $this->userModel->find($creatorId);
                if ($creator) {
                    $creatorName = $creator['fname'] . ' ' . $creator['lname'];
                }
            }

            // Get creator's email
            $creatorEmail = session()->get('email') ?? 'noreply@dakoiims.com';

            // Prepare email subject and message
            $subject = 'Welcome to AMIS - Your Account Has Been Created';

            // Create HTML message
            $message = '
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #4CAF50; color: white; padding: 10px; text-align: center; }
                    .content { padding: 20px; border: 1px solid #ddd; }
                    .footer { font-size: 12px; text-align: center; margin-top: 20px; color: #777; }
                    .highlight { background-color: #f8f9fa; padding: 10px; border-left: 4px solid #4CAF50; margin: 15px 0; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>Welcome to AMIS System</h2>
                    </div>
                    <div class="content">
                        <p>Dear ' . $user['fname'] . ' ' . $user['lname'] . ',</p>

                        <p>Welcome to the AMIS system! Your account has been successfully created.</p>

                        <div class="highlight">
                            <p><strong>Account Details:</strong></p>
                            <p>Email: ' . $user['email'] . '</p>
                            <p>Role: ' . ucfirst($user['role']) . '</p>
                        </div>

                        <p>Your account was created by: <strong>' . $creatorName . '</strong> (' . $creatorEmail . ')</p>

                        <p>You can now log in to the system using your email address and the password provided to you.</p>

                        <p>If you have any questions or need assistance, please contact your system administrator.</p>

                        <p>Thank you,<br>
                        AMIS System</p>
                    </div>
                    <div class="footer">
                        <p>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </body>
            </html>';

            // Send the email
            $result = send_email($user['email'], $subject, $message);

            if (!$result) {
                log_message('error', 'Failed to send account creation notification email to: ' . $user['email']);
            } else {
                log_message('info', 'Account creation notification email sent successfully to: ' . $user['email']);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception sending account creation notification email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email notification to user about account status change
     *
     * @param int $userId ID of the user whose status was changed
     * @param int $newStatus New status value (1 for active, 0 for inactive)
     * @param string $remarks Remarks about the status change
     * @return bool Success or failure
     */
    protected function sendStatusChangeNotificationEmail($userId, $newStatus, $remarks)
    {
        try {
            // Get the complete user data
            $user = $this->userModel->find($userId);
            if (!$user || empty($user['email'])) {
                log_message('error', 'Cannot send status change notification: User not found or no email available');
                return false;
            }

            // Get the name of the person who changed the status
            $updaterName = 'System Administrator';
            $updaterId = session()->get('user_id');
            if ($updaterId) {
                $updater = $this->userModel->find($updaterId);
                if ($updater) {
                    $updaterName = $updater['fname'] . ' ' . $updater['lname'];
                }
            }

            // Get updater's email
            $updaterEmail = session()->get('email') ?? 'noreply@dakoiims.com';

            // Determine status text
            $statusText = $newStatus == 1 ? 'activated' : 'deactivated';

            // Prepare email subject and message
            $subject = 'Your AMIS Account Has Been ' . ucfirst($statusText);

            // Set header color based on status
            $headerColor = $newStatus == 1 ? '#4CAF50' : '#f44336';

            // Create HTML message
            $message = '
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: ' . $headerColor . '; color: white; padding: 10px; text-align: center; }
                    .content { padding: 20px; border: 1px solid #ddd; }
                    .footer { font-size: 12px; text-align: center; margin-top: 20px; color: #777; }
                    .highlight { background-color: #f8f9fa; padding: 10px; border-left: 4px solid ' . $headerColor . '; margin: 15px 0; }
                    .status { font-weight: bold; color: ' . ($newStatus == 1 ? '#4CAF50' : '#f44336') . '; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>Account Status Change</h2>
                    </div>
                    <div class="content">
                        <p>Dear ' . $user['fname'] . ' ' . $user['lname'] . ',</p>

                        <p>This is to inform you that your AMIS system account has been <span class="status">' . $statusText . '</span>.</p>

                        <div class="highlight">
                            <p><strong>Status Change Details:</strong></p>
                            <p>New Status: <span class="status">' . ($newStatus == 1 ? 'Active' : 'Inactive') . '</span></p>
                            <p>Changed By: ' . $updaterName . ' (' . $updaterEmail . ')</p>
                            <p>Remarks: ' . $remarks . '</p>
                            <p>Time: ' . date('Y-m-d H:i:s') . '</p>
                        </div>';

            // Add different messages based on status
            if ($newStatus == 1) {
                $message .= '
                        <p>Your account is now active, and you can log in to the AMIS system using your email address and password.</p>';
            } else {
                $message .= '
                        <p>Your account is now inactive, and you will not be able to log in to the AMIS system until it is reactivated.</p>';
            }

            $message .= '
                        <p>If you have any questions about this change, please contact your system administrator.</p>

                        <p>Thank you,<br>
                        AMIS System</p>
                    </div>
                    <div class="footer">
                        <p>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </body>
            </html>';

            // Send the email
            $result = send_email($user['email'], $subject, $message);

            if (!$result) {
                log_message('error', 'Failed to send status change notification email to: ' . $user['email']);
            } else {
                log_message('info', 'Status change notification email sent successfully to: ' . $user['email']);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception sending status change notification email: ' . $e->getMessage());
            return false;
        }
    }
}