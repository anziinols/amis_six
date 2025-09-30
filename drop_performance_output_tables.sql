-- Drop Performance Output Tables
-- This script removes the performance_outputs table and its related junction tables
-- Created: 2025-09-30

-- Drop junction tables first (to avoid foreign key constraints)
DROP TABLE IF EXISTS `output_duty_instruction`;
DROP TABLE IF EXISTS `output_workplan_activities`;

-- Drop main performance_outputs table
DROP TABLE IF EXISTS `performance_outputs`;

-- Verify tables are dropped
SHOW TABLES LIKE '%output%';