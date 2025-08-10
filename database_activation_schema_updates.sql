-- =====================================================
-- AMIS Five - User Activation Workflow Database Updates
-- =====================================================
-- 
-- This script adds the necessary fields to support the 
-- email-based user activation workflow.
--
-- Execute these statements in your MySQL database to add
-- the required activation fields to the users table.
-- =====================================================

-- Add activation workflow fields to users table
ALTER TABLE `users` 
ADD COLUMN `activation_token` VARCHAR(255) NULL COMMENT 'Secure token for account activation',
ADD COLUMN `activation_expires_at` DATETIME NULL COMMENT 'Expiration timestamp for activation token',
ADD COLUMN `activated_at` DATETIME NULL COMMENT 'Timestamp when user completed activation',
ADD COLUMN `is_activated` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Activation status flag (0=pending, 1=activated)';

-- Add indexes for performance optimization
ALTER TABLE `users`
ADD INDEX `idx_activation_token` (`activation_token`),
ADD INDEX `idx_is_activated` (`is_activated`),
ADD INDEX `idx_activation_expires` (`activation_expires_at`);

-- =====================================================
-- Verification Queries
-- =====================================================
-- Run these queries to verify the schema changes:

-- 1. Check if columns were added successfully
-- DESCRIBE users;

-- 2. Verify indexes were created
-- SHOW INDEX FROM users WHERE Key_name LIKE 'idx_activation%';

-- 3. Check existing users (they should have is_activated = 0 by default)
-- SELECT id, email, is_activated, activation_token, activated_at FROM users LIMIT 5;

-- =====================================================
-- Optional: Update existing users to activated status
-- =====================================================
-- If you want to mark all existing users as already activated
-- (since they were created before the activation workflow):

-- UPDATE users 
-- SET is_activated = 1, 
--     activated_at = created_at 
-- WHERE is_activated = 0 AND password IS NOT NULL AND password != '';

-- =====================================================
-- Rollback Script (if needed)
-- =====================================================
-- If you need to rollback these changes, run:

-- ALTER TABLE `users` 
-- DROP INDEX `idx_activation_token`,
-- DROP INDEX `idx_is_activated`, 
-- DROP INDEX `idx_activation_expires`,
-- DROP COLUMN `activation_token`,
-- DROP COLUMN `activation_expires_at`,
-- DROP COLUMN `activated_at`,
-- DROP COLUMN `is_activated`;
