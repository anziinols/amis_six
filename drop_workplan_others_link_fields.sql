-- Drop the specified fields from workplan_others_link table
ALTER TABLE `workplan_others_link` 
DROP COLUMN `link_type`,
DROP COLUMN `category`, 
DROP COLUMN `priority_level`,
DROP COLUMN `duration_months`;