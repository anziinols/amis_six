-- SQL script to remove performance_outputs related tables
-- Run this script in your MySQL database to complete the removal of the performance outputs feature

-- Drop the junction tables first (they have foreign keys to performance_outputs)
DROP TABLE IF EXISTS `output_duty_instruction`;
DROP TABLE IF EXISTS `output_workplan_activities`;

-- Drop the main performance_outputs table
DROP TABLE IF EXISTS `performance_outputs`;

-- Note: This will permanently delete all performance output data and related links
-- Make sure to backup your database before running this script if you need to preserve the data

