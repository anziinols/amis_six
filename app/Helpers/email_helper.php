<?php

/**
 * Email Helper Functions
 *
 * This file contains helper functions for sending emails using CodeIgniter's built-in Email library
 */

use Config\Services;

if (!function_exists('send_email')) {
    /**
     * Send an email using the application's email configuration
     *
     * @param string|array $to Recipient email address(es)
     * @param string $subject Email subject
     * @param string $message Email message (HTML or plain text)
     * @param array $options Additional options (from, cc, bcc, attachments, etc.)
     * @return bool Success or failure
     */
    function send_email($to, string $subject, string $message, array $options = []): bool
    {
        // Get email service with configuration from app/Config/Email.php
        $email = Services::email();

        // Set recipients
        $email->setTo($to);

        // Set subject
        $email->setSubject($subject);

        // Set message
        $email->setMessage($message);

        // Set optional parameters
        if (!empty($options['from'])) {
            $fromName = $options['from_name'] ?? 'AMIS System';
            $email->setFrom($options['from'], $fromName);
        }

        if (!empty($options['cc'])) {
            $email->setCC($options['cc']);
        }

        if (!empty($options['bcc'])) {
            $email->setBCC($options['bcc']);
        }

        // Add attachments if provided
        if (!empty($options['attachments'])) {
            foreach ($options['attachments'] as $attachment) {
                $email->attach($attachment);
            }
        }

        // Send email
        $result = $email->send();

        if (!$result) {
            log_message('error', 'Failed to send email: ' . $email->printDebugger(['headers']));
        }

        return $result;
    }
}


