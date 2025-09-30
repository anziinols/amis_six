-- SQL script to add target_output column to workplan_activities table
-- Run this in phpMyAdmin SQL tab

-- Add target_output column after description
ALTER TABLE `workplan_activities` 
ADD COLUMN `target_output` VARCHAR(255) NULL 
AFTER `description`;

-- Verify the column was added
DESCRIBE `workplan_activities`;
