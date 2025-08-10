<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Models\DakoiiUserModel;

/**
 * Home Controller
 *
 * Handles authentication and landing pages for the application
 */
class Home extends ResourceController
{
    /**
     * @var UserModel
     */
    protected $userModel;

    /**
     * @var DakoiiUserModel
     */
    protected $dakoiiUserModel;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->dakoiiUserModel = new DakoiiUserModel();
    }

    /**
     * Display the landing page
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function index()
    {
        return view('home/home_landing');
    }

    /**
     * Display login page or process login
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function login()
    {
        if ($this->request->is('post')) {
            return $this->loginProcess();
        }

        // For GET requests, show login page
        return redirect()->to(base_url());
    }

    /**
     * Process login request
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function loginProcess()
    {
        // This method only handles POST submissions
        if (!$this->request->is('post')) {
            return redirect()->to(base_url());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember') ? true : false;

        // Validate inputs
        if (empty($email) || empty($password)) {
            return redirect()->back()->with('error', 'Please enter both email and password');
        }

        // Authenticate user using the model's authenticate method
        $user = $this->userModel->authenticate($email, $password);

        if (!$user) {
            return redirect()->back()->with('error', 'Invalid email or password');
        }

        // Check if user is active
        if (isset($user['user_status']) && $user['user_status'] != 1) {
            return redirect()->back()->with('error', 'Your account is not active. Please contact support.');
        }

        // Set session data
        session()->set([
            'user_id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'fname' => $user['fname'],
            'lname' => $user['lname'],
            'user_name' => $user['fname'] . ' ' . $user['lname'],
            'user_status' => $user['user_status'],
            'id_photo' => $user['id_photo_filepath'],
            'is_evaluator' => $user['is_evaluator'] ?? 0,
            'commodity_id' => $user['commodity_id'] ?? null,
            'logged_in' => true
        ]);

        // Set remember me cookie if requested
        if ($remember) {
            $this->setRememberMeCookie($user['id']);
        }

        // Log successful login
        log_message('info', 'User logged in successfully: ' . $user['email']);

        // Redirect based on role
        return redirect()->to(base_url('dashboard'))->with('success', 'Login successful');
    }

    /**
     * Process user logout
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url())->with('message', 'You have been logged out successfully.');
    }

    /**
     * Display the Dakoii login page
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function dakoii()
    {
        // If already logged in, redirect to dashboard
        if (session()->get('dakoii_logged_in')) {
            return redirect()->to(base_url('dakoii/dashboard'));
        }

        return view('home/home_dakoii');
    }

    /**
     * Process Dakoii login
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function dakoiiLogin()
    {
        // Only accept POST requests
        if (!$this->request->is('post')) {
            return redirect()->to(base_url('dakoii'))->with('error', 'Invalid request method');
        }

        // Get form inputs
        $username = trim($this->request->getPost('username'));
        $password = trim($this->request->getPost('password'));

        // Enhanced logging for debugging
        log_message('debug', '-------------------------------------');
        log_message('debug', 'Dakoii login request received');
        log_message('debug', 'Username: ' . $username);
        log_message('debug', 'Password length: ' . strlen($password));
        log_message('info', 'Dakoii login attempt for username: ' . $username);

        // Validate required fields
        if (empty($username) || empty($password)) {
            log_message('warning', 'Empty username or password submitted');
            return redirect()->to(base_url('dakoii'))
                ->with('error', 'Username and password are required');
        }

        // Authenticate user
        log_message('debug', 'Calling authenticate method for user: ' . $username);
        $user = $this->dakoiiUserModel->authenticate($username, $password);
        log_message('debug', 'Authentication result: ' . ($user ? 'Success' : 'Failed'));

        if ($user) {
            // Set session data on successful login
            $sessionData = [
                'dakoii_user_id'  => $user['id'],
                'dakoii_name'     => $user['name'],
                'dakoii_username' => $user['username'],
                'dakoii_role'     => $user['role'],
                'dakoii_logged_in'=> true
            ];

            session()->set($sessionData);

            // Verify session data was set correctly
            log_message('debug', 'Session data set. Logged in: ' . (session()->get('dakoii_logged_in') ? 'Yes' : 'No'));
            log_message('info', 'Dakoii user logged in successfully: ' . $username);

            // Redirect to dashboard
            return redirect()->to(base_url('dakoii/dashboard'))
                ->with('success', 'Welcome back, ' . $user['name']);
        }

        // Handle failed login
        log_message('warning', 'Failed Dakoii login attempt for username: ' . $username);

        // Let's check what's in the database for this username
        try {
            $checkUser = $this->dakoiiUserModel->where('username', $username)->first();
            if ($checkUser) {
                log_message('debug', 'User exists but authentication failed. Status: ' .
                    ($checkUser['dakoii_user_status'] ?? 'undefined'));
            } else {
                log_message('debug', 'User does not exist in database');
            }
        } catch(\Exception $e) {
            log_message('error', 'Error checking user: ' . $e->getMessage());
        }

        return redirect()->to(base_url('dakoii'))
            ->with('error', 'Invalid username or password');
    }

    /**
     * Set remember-me cookie
     *
     * @param int $userId
     * @return void
     */
    private function setRememberMeCookie($userId)
    {
        $token = bin2hex(random_bytes(32));
        $expiry = time() + (30 * 24 * 60 * 60); // 30 days

        // In a real application, you should store this token in a database
        // For simplicity, we're just storing the user ID in the cookie
        set_cookie(
            'remember_token',
            $userId,
            $expiry
        );
    }

    /**
     * Display forgot password page or process forgot password request
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function forgotPassword()
    {
        if ($this->request->is('post')) {
            return $this->processForgotPassword();
        }

        // For GET requests, show forgot password page
        return view('home/forgot_password');
    }

    /**
     * Process forgot password request
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function processForgotPassword()
    {
        // This method only handles POST submissions
        if (!$this->request->is('post')) {
            return redirect()->to(base_url('forgot-password'));
        }

        $email = trim($this->request->getPost('email'));

        // Validate email
        if (empty($email)) {
            return redirect()->back()->with('error', 'Please enter your email address');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Please enter a valid email address');
        }

        // Check if user exists
        $user = $this->userModel->where('email', $email)->first();
        if (!$user) {
            // Don't reveal if email exists or not for security
            return redirect()->back()->with('success', 'If the email address is registered, you will receive a temporary password shortly.');
        }

        // Check if user is active
        if ($user['user_status'] != 1) {
            return redirect()->back()->with('error', 'Your account is not active. Please contact support.');
        }

        // Generate 4-digit temporary password
        $tempPassword = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);

        // Hash the temporary password and update user record
        $hashedTempPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

        try {
            // Update user's password with temporary password
            $updateData = [
                'id' => $user['id'],
                'password' => $hashedTempPassword,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => 1 // System user
            ];

            // Use direct database query to avoid model callbacks that might double-hash
            $db = \Config\Database::connect();
            $builder = $db->table('users');
            $builder->where('id', $user['id']);
            $result = $builder->update($updateData);

            if ($result) {
                // Send temporary password via email
                $emailSent = $this->sendTemporaryPasswordEmail($user, $tempPassword);

                if ($emailSent) {
                    log_message('info', 'Temporary password sent to: ' . $email);
                    return redirect()->back()->with('success', 'A temporary 4-digit password has been sent to your email address. Please check your email and use it to login.');
                } else {
                    log_message('error', 'Failed to send temporary password email to: ' . $email);
                    return redirect()->back()->with('error', 'Failed to send email. Please try again or contact support.');
                }
            } else {
                log_message('error', 'Failed to update user password for forgot password request: ' . $email);
                return redirect()->back()->with('error', 'Failed to process request. Please try again.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception in forgot password process: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Send temporary password email
     *
     * @param array $user User data
     * @param string $tempPassword Temporary password
     * @return bool Success or failure
     */
    private function sendTemporaryPasswordEmail($user, $tempPassword)
    {
        try {
            // Prepare email subject and message
            $subject = 'AMIS - Temporary Password';

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
                    .temp-password { background-color: #f8f9fa; padding: 15px; border-left: 4px solid #4CAF50; margin: 15px 0; text-align: center; }
                    .temp-password h3 { margin: 0; color: #4CAF50; font-size: 24px; }
                    .warning { background-color: #fff3cd; padding: 10px; border-left: 4px solid #ffc107; margin: 15px 0; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>AMIS - Temporary Password</h2>
                    </div>
                    <div class="content">
                        <p>Dear ' . $user['fname'] . ' ' . $user['lname'] . ',</p>

                        <p>You have requested a password reset for your AMIS account. Here is your temporary 4-digit password:</p>

                        <div class="temp-password">
                            <h3>' . $tempPassword . '</h3>
                        </div>

                        <div class="warning">
                            <p><strong>Important Security Instructions:</strong></p>
                            <ul>
                                <li>Use this temporary password to log in to your account</li>
                                <li>Change your password immediately after logging in</li>
                                <li>This temporary password will remain active until you change it</li>
                                <li>Do not share this password with anyone</li>
                            </ul>
                        </div>

                        <p>To log in:</p>
                        <ol>
                            <li>Go to the AMIS login page</li>
                            <li>Enter your email: <strong>' . $user['email'] . '</strong></li>
                            <li>Enter the temporary password: <strong>' . $tempPassword . '</strong></li>
                            <li>Go to your profile and change your password immediately</li>
                        </ol>

                        <p>If you did not request this password reset, please contact your system administrator immediately.</p>

                        <p>Thank you,<br>
                        AMIS System</p>
                    </div>
                    <div class="footer">
                        <p>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </body>
            </html>';

            // Send the email using the system's email helper
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
     * Check if user is logged in
     *
     * @return bool
     */
    private function isLoggedIn()
    {
        return session()->get('dakoii_logged_in') === true;
    }
}
