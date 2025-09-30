-- Drop Duty Instructions, Performance Indicators KRA, and Workplan Period Tables
-- This script removes these tables completely
-- Created: 2025-09-30

-- Drop tables
DROP TABLE IF EXISTS `duty_instructions_corporate_plan_link`;
DROP TABLE IF EXISTS `performance_indicators_kra`;
DROP TABLE IF EXISTS `workplan_period`;

-- Verify tables are dropped
SELECT 'Tables dropped successfully' AS status;