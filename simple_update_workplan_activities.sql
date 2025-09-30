-- Simple SQL script to update workplan_activities table
-- Execute these commands one by one in phpMyAdmin

-- Remove activity_type column (ignore error if column doesn't exist)
ALTER TABLE `workplan_activities` DROP COLUMN `activity_type`;

-- Remove quarter target columns (ignore errors if columns don't exist)
ALTER TABLE `workplan_activities` DROP COLUMN `q_one_target`;
ALTER TABLE `workplan_activities` DROP COLUMN `q_two_target`;
ALTER TABLE `workplan_activities` DROP COLUMN `q_three_target`;
ALTER TABLE `workplan_activities` DROP COLUMN `q_four_target`;

-- Remove quarter achieved columns (ignore errors if columns don't exist)
ALTER TABLE `workplan_activities` DROP COLUMN `q_one_achieved`;
ALTER TABLE `workplan_activities` DROP COLUMN `q_two_achieved`;
ALTER TABLE `workplan_activities` DROP COLUMN `q_three_achieved`;
ALTER TABLE `workplan_activities` DROP COLUMN `q_four_achieved`;

-- Add target_output column after description
ALTER TABLE `workplan_activities` ADD COLUMN `target_output` VARCHAR(255) NULL AFTER `description`;

-- Verify the changes
DESCRIBE `workplan_activities`;
