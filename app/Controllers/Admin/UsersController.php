<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\BranchesModel;


class UsersController extends BaseController
{
    protected $userModel;
    protected $branchesModel;


    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->branchesModel = new BranchesModel();

        helper(['email']);
    }

    public function index()
    {
        // Get users with branch information and activation status
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



        $data = [
            'title' => 'Add New User',
            'branches' => $branches,
            'supervisors' => $supervisors
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

        $data = [
            'title' => 'Add New User',
            'branches' => $branches,
            'supervisors' => $supervisors
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

        // Always auto-generate user code on create to ensure consistency
        $userData['ucode'] = $this->generateUniqueUserCode();

        // Handle checkbox fields - set to 0 if not checked
        // If role is 'guest', force all capabilities to 0
        if (isset($userData['role']) && $userData['role'] === 'guest') {
            $userData['is_evaluator'] = '0';
            $userData['is_supervisor'] = '0';
            $userData['is_admin'] = '0';
        } else {
            // For 'user' role, check if capabilities are set
            if (!isset($userData['is_evaluator'])) {
                $userData['is_evaluator'] = '0';
            }
            if (!isset($userData['is_supervisor'])) {
                $userData['is_supervisor'] = '0';
            }
            if (!isset($userData['is_admin'])) {
                $userData['is_admin'] = '0';
            }
        }

        // Handle file upload for ID photo
        $file = $this->request->getFile('id_photo_filepath');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Create directory if it doesn't exist
            $uploadPath = ROOTPATH . 'public/uploads/user_photos/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);
            // Store path with public/ prefix for correct URL construction
            $userData['id_photo_filepath'] = 'public/uploads/user_photos/' . $newName;
        }

        // Generate activation token for email-based activation
        $activationToken = $this->userModel->generateActivationToken();

        // Set user as inactive and pending activation
        $userData['user_status'] = 0;  // Inactive until activated
        $userData['is_activated'] = 0;
        $userData['password'] = null;  // No password until activation

        // Save to database
        if ($this->userModel->save($userData)) {
            // Get the ID of the newly created user
            $newUserId = $this->userModel->getInsertID();
            log_message('debug', 'User created successfully with ID: ' . $newUserId);

            // Set activation token with 48-hour expiration
            if ($this->userModel->setActivationToken($newUserId, $activationToken, 48)) {
                // Send activation email to the new user
                $emailSent = $this->sendActivationEmail($newUserId, $activationToken);

                if ($emailSent) {
                    return redirect()->to('/admin/users')
                           ->with('success', 'User created successfully. An activation email has been sent to ' . $userData['email']);
                } else {
                    log_message('error', 'Failed to send activation email to: ' . $userData['email']);
                    return redirect()->to('/admin/users')
                           ->with('warning', 'User created but failed to send activation email. Please resend activation from the users list.');
                }
            } else {
                log_message('error', 'Failed to set activation token for user ID: ' . $newUserId);
                return redirect()->back()
                       ->withInput()
                       ->with('error', 'Failed to set up user activation. Please try again.');
            }
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

        $data = [
            'title' => 'Edit User',
            'user' => $this->userModel->find($id),
            'branches' => $branches,
            'supervisors' => $supervisors
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

        // Remove password validation (not handled in edit form)
        unset($rules['password']);

        // First validate the basic rules
        if ($this->validate($rules, $messages)) {
            $userData = $this->request->getPost();
            $userData['updated_by'] = session()->get('user_id');

            // Remove _method field if present (used for PUT/PATCH routing)
            unset($userData['_method']);

            // Keep the original ucode and activation fields
            $userData['ucode'] = $user['ucode'];
            $userData['activation_token'] = $user['activation_token'];
            $userData['activation_expires_at'] = $user['activation_expires_at'];
            $userData['activated_at'] = $user['activated_at'];
            $userData['is_activated'] = $user['is_activated'];

            // Handle checkbox fields - set to 0 if not checked
            if (!isset($userData['is_evaluator'])) {
                $userData['is_evaluator'] = '0';
            }
            if (!isset($userData['is_supervisor'])) {
                $userData['is_supervisor'] = '0';
            }
            if (!isset($userData['is_admin'])) {
                $userData['is_admin'] = '0';
            }

            // Handle file upload for ID photo
            $file = $this->request->getFile('id_photo_filepath');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Create directory if it doesn't exist
                $uploadPath = ROOTPATH . 'public/uploads/user_photos/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $newName = $file->getRandomName();
                $file->move($uploadPath, $newName);
                // Store path with public/ prefix for correct URL construction
                $userData['id_photo_filepath'] = 'public/uploads/user_photos/' . $newName;
            } else {
                // Keep existing photo if no new file uploaded
                $userData['id_photo_filepath'] = $user['id_photo_filepath'];
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

            // Remove password field - not handled in edit form (use activation workflow)
            unset($userData['password']);

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

                    return redirect()->to('/admin/users/' . $id . '/edit')->with('success', 'User updated successfully');
                } else {
                    log_message('error', 'Failed to update user with direct query. DB Error: ' . $db->error()['message']);
                    return redirect()->back()->withInput()->with('error', 'Failed to update user. Database error.');
                }
            } catch (\Exception $e) {
                log_message('error', 'Exception updating user: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Failed to update user: ' . $e->getMessage());
            }
        }

        // Get branches and supervisors for the form if validation fails
        $branches = $this->branchesModel->getBranchesForDropdown();
        $supervisors = $this->userModel->getUsersBySupervisorCapability();

        return view('admin/users/admin_users_edit', [
            'title' => 'Edit User',
            'user' => $user,
            'branches' => $branches,
            'supervisors' => $supervisors,
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
            'is_admin' => $userData['is_admin'] ?? 0,
            'is_supervisor' => $userData['is_supervisor'] ?? 0,
            'is_evaluator' => $userData['is_evaluator'] ?? 0
        ];

        session()->set($sessionData);
    }

    /**
     * Resend activation email to user
     */
    public function resendActivation($id = null)
    {
        if ($id === null) {
            return redirect()->to('/admin/users')->with('error', 'Invalid user ID');
        }

        $user = $this->userModel->find($id);
        if (empty($user)) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }

        // Check if user needs activation
        if ($user['is_activated'] == 1) {
            return redirect()->to('/admin/users')->with('error', 'User is already activated');
        }

        // Generate new activation token
        $activationToken = $this->userModel->generateActivationToken();

        // Set new activation token with 48-hour expiration
        if ($this->userModel->setActivationToken($id, $activationToken, 48)) {
            // Send activation email
            $emailSent = $this->sendActivationEmail($id, $activationToken);

            if ($emailSent) {
                return redirect()->to('/admin/users')
                       ->with('success', 'Activation email has been resent to ' . $user['email']);
            } else {
                return redirect()->to('/admin/users')
                       ->with('error', 'Failed to send activation email. Please try again.');
            }
        } else {
            return redirect()->to('/admin/users')
                   ->with('error', 'Failed to generate new activation token. Please try again.');
        }
    }

    /**
     * Delete user (only allowed within 24 hours of creation)
     */
    public function delete($id = null)
    {
        if ($id === null) {
            return redirect()->to('/admin/users')->with('error', 'Invalid user ID');
        }

        // Prevent deleting your own account
        if ($id == session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'You cannot delete your own account');
        }

        $user = $this->userModel->find($id);
        if (empty($user)) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }

        // Check if user was created within the last 24 hours
        if (!$this->userModel->isRecentlyCreated($id, 24)) {
            return redirect()->to('/admin/users')
                   ->with('error', 'Users can only be deleted within 24 hours of creation');
        }

        // Perform the deletion
        if ($this->userModel->delete($id)) {
            log_message('info', 'User deleted by admin: ' . $user['email'] . ' (ID: ' . $id . ')');
            return redirect()->to('/admin/users')
                   ->with('success', 'User ' . $user['fname'] . ' ' . $user['lname'] . ' has been deleted successfully');
        } else {
            return redirect()->to('/admin/users')
                   ->with('error', 'Failed to delete user. Please try again.');
        }
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
     * Send activation email to newly created user
     *
     * @param int $userId ID of the newly created user
     * @param string $activationToken Plain text activation token
     * @return bool Success or failure
     */
    protected function sendActivationEmail($userId, $activationToken): bool
    {
        try {
            // Get the complete user data
            $user = $this->userModel->find($userId);
            if (!$user || empty($user['email'])) {
                log_message('error', 'Cannot send activation email: User not found or no email available');
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

            // Create activation link
            $activationLink = base_url('activate/' . $activationToken);

            // Prepare email subject and message
            $subject = 'Activate Your AMIS Account';

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
                    .button { display: inline-block; padding: 12px 24px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px; margin: 10px 0; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>Welcome to AMIS System</h2>
                    </div>
                    <div class="content">
                        <p>Dear ' . $user['fname'] . ' ' . $user['lname'] . ',</p>

                        <p>Your AMIS account has been created by <strong>' . $creatorName . '</strong>. To complete your account setup, please activate your account by clicking the link below:</p>

                        <div class="highlight">
                            <p style="text-align: center;">
                                <a href="' . $activationLink . '" class="button">Activate My Account</a>
                            </p>
                            <p><small>Or copy and paste this link into your browser:<br>' . $activationLink . '</small></p>
                        </div>

                        <p><strong>Important:</strong></p>
                        <ul>
                            <li>This activation link will expire in 48 hours</li>
                            <li>After activation, you will receive a temporary password via email</li>
                            <li>You can change your password after your first login</li>
                        </ul>

                        <p>If you did not expect this account creation or have any questions, please contact your system administrator.</p>

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
                log_message('error', 'Failed to send activation email to: ' . $user['email']);
            } else {
                log_message('info', 'Activation email sent successfully to: ' . $user['email']);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception sending activation email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send temporary password email after successful activation
     *
     * @param int $userId ID of the activated user
     * @param string $tempPassword Plain text temporary password
     * @return bool Success or failure
     */
    protected function sendTemporaryPasswordEmail($userId, $tempPassword): bool
    {
        try {
            // Get the complete user data
            $user = $this->userModel->find($userId);
            if (!$user || empty($user['email'])) {
                log_message('error', 'Cannot send temporary password email: User not found or no email available');
                return false;
            }

            // Prepare email subject and message
            $subject = 'Your AMIS Account is Now Active - Temporary Password';

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
                    .password { font-size: 24px; font-weight: bold; color: #4CAF50; text-align: center; padding: 10px; background: #f0f0f0; border-radius: 4px; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>Account Activated Successfully</h2>
                    </div>
                    <div class="content">
                        <p>Dear ' . $user['fname'] . ' ' . $user['lname'] . ',</p>

                        <p>Congratulations! Your AMIS account has been successfully activated.</p>

                        <div class="highlight">
                            <p><strong>Your temporary password is:</strong></p>
                            <div class="password">' . $tempPassword . '</div>
                        </div>

                        <p><strong>To log in:</strong></p>
                        <ol>
                            <li>Go to the AMIS login page: <a href="' . base_url('login') . '">' . base_url('login') . '</a></li>
                            <li>Enter your email: <strong>' . $user['email'] . '</strong></li>
                            <li>Enter the temporary password above</li>
                            <li>Change your password immediately after logging in</li>
                        </ol>

                        <p><strong>Important Security Notes:</strong></p>
                        <ul>
                            <li>This is a temporary password - please change it after your first login</li>
                            <li>Do not share this password with anyone</li>
                            <li>If you did not activate this account, contact support immediately</li>
                        </ul>

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
                log_message('error', 'Failed to send temporary password email to: ' . $user['email']);
            } else {
                log_message('info', 'Temporary password email sent successfully to: ' . $user['email']);
            }

            return $result;
        } catch (\Exception $e) {
            log_message('error', 'Exception sending temporary password email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email notification to user about account creation (DEPRECATED - replaced by activation workflow)
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

    /**
     * Generate a unique user code following the system pattern USRYYYYMMDD####
     */
    protected function generateUniqueUserCode(): string
    {
        $prefix = 'USR';
        $datePart = date('Ymd');
        $attempts = 0;
        $code = '';

        do {
            $randomPart = str_pad((string)rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $code = $prefix . $datePart . $randomPart;
            $exists = $this->userModel->where('ucode', $code)->first();
            $attempts++;
        } while ($exists && $attempts < 5);

        if ($exists) {
            // Fallback to a timestamp-based unique code if collisions persist
            $code = $prefix . date('YmdHis') . str_pad((string)rand(0, 999), 3, '0', STR_PAD_LEFT);
        }

        return $code;
    }

}