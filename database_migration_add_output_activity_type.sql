-- Migration to add 'output' to the activity_type enum in workplan_activities table
-- This will allow output activities to be properly stored and displayed

-- First, let's check the current structure
-- DESCRIBE workplan_activities;

-- Add 'output' to the activity_type enum
ALTER TABLE `workplan_activities` 
MODIFY COLUMN `activity_type` ENUM('training','inputs','infrastructure','output') NOT NULL;

-- Verify the change
-- SHOW COLUMNS FROM workplan_activities WHERE Field = 'activity_type';
