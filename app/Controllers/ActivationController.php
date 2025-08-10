<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ActivationController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['email']);
    }

    /**
     * Handle user activation from email link
     *
     * @param string $token Activation token from email
     * @return \CodeIgniter\HTTP\Response
     */
    public function activate($token = null)
    {
        if (empty($token)) {
            return redirect()->to(base_url('login'))
                   ->with('error', 'Invalid activation link. Please contact support.');
        }

        // Validate the activation token
        $user = $this->userModel->validateActivationToken($token);
        
        if (!$user) {
            return redirect()->to(base_url('login'))
                   ->with('error', 'Invalid or expired activation link. Please contact support or request a new activation email.');
        }

        // Generate a secure 4-digit temporary password
        $tempPassword = str_pad((string)rand(1000, 9999), 4, '0', STR_PAD_LEFT);

        // Activate the user account
        if ($this->userModel->activateUser($user['id'], $tempPassword)) {
            // Send temporary password email
            $emailSent = $this->sendTemporaryPasswordEmail($user['id'], $tempPassword);
            
            if ($emailSent) {
                log_message('info', 'User activated successfully: ' . $user['email']);
                return redirect()->to(base_url('login'))
                       ->with('success', 'Your account has been activated successfully! A temporary password has been sent to your email. Please check your email and use it to log in.');
            } else {
                log_message('error', 'User activated but failed to send temporary password email: ' . $user['email']);
                return redirect()->to(base_url('login'))
                       ->with('warning', 'Your account has been activated but we could not send the temporary password email. Please contact support.');
            }
        } else {
            log_message('error', 'Failed to activate user account for: ' . $user['email']);
            return redirect()->to(base_url('login'))
                   ->with('error', 'Failed to activate your account. Please try again or contact support.');
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
}
