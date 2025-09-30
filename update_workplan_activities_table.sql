-- SQL script to update workplan_activities table structure
-- Run this in phpMyAdmin or MySQL command line

-- First, check if columns exist before dropping them
SET @sql = '';

-- Drop activity_type column if it exists
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'amis_six_db' 
AND table_name = 'workplan_activities' 
AND column_name = 'activity_type';

SET @sql = IF(@col_exists > 0, 'ALTER TABLE workplan_activities DROP COLUMN activity_type;', 'SELECT "activity_type column does not exist" as message;');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop q_one_target column if it exists
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'amis_six_db' 
AND table_name = 'workplan_activities' 
AND column_name = 'q_one_target';

SET @sql = IF(@col_exists > 0, 'ALTER TABLE workplan_activities DROP COLUMN q_one_target;', 'SELECT "q_one_target column does not exist" as message;');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop q_two_target column if it exists
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'amis_six_db' 
AND table_name = 'workplan_activities' 
AND column_name = 'q_two_target';

SET @sql = IF(@col_exists > 0, 'ALTER TABLE workplan_activities DROP COLUMN q_two_target;', 'SELECT "q_two_target column does not exist" as message;');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop q_three_target column if it exists
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'amis_six_db' 
AND table_name = 'workplan_activities' 
AND column_name = 'q_three_target';

SET @sql = IF(@col_exists > 0, 'ALTER TABLE workplan_activities DROP COLUMN q_three_target;', 'SELECT "q_three_target column does not exist" as message;');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop q_four_target column if it exists
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'amis_six_db' 
AND table_name = 'workplan_activities' 
AND column_name = 'q_four_target';

SET @sql = IF(@col_exists > 0, 'ALTER TABLE workplan_activities DROP COLUMN q_four_target;', 'SELECT "q_four_target column does not exist" as message;');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop q_one_achieved column if it exists
SELECT COUNT(*) INTO @col_exists
FROM information_schema.columns
WHERE table_schema = 'amis_six_db'
AND table_name = 'workplan_activities'
AND column_name = 'q_one_achieved';

SET @sql = IF(@col_exists > 0, 'ALTER TABLE workplan_activities DROP COLUMN q_one_achieved;', 'SELECT "q_one_achieved column does not exist" as message;');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop q_two_achieved column if it exists
SELECT COUNT(*) INTO @col_exists
FROM information_schema.columns
WHERE table_schema = 'amis_six_db'
AND table_name = 'workplan_activities'
AND column_name = 'q_two_achieved';

SET @sql = IF(@col_exists > 0, 'ALTER TABLE workplan_activities DROP COLUMN q_two_achieved;', 'SELECT "q_two_achieved column does not exist" as message;');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop q_three_achieved column if it exists
SELECT COUNT(*) INTO @col_exists
FROM information_schema.columns
WHERE table_schema = 'amis_six_db'
AND table_name = 'workplan_activities'
AND column_name = 'q_three_achieved';

SET @sql = IF(@col_exists > 0, 'ALTER TABLE workplan_activities DROP COLUMN q_three_achieved;', 'SELECT "q_three_achieved column does not exist" as message;');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop q_four_achieved column if it exists
SELECT COUNT(*) INTO @col_exists
FROM information_schema.columns
WHERE table_schema = 'amis_six_db'
AND table_name = 'workplan_activities'
AND column_name = 'q_four_achieved';

SET @sql = IF(@col_exists > 0, 'ALTER TABLE workplan_activities DROP COLUMN q_four_achieved;', 'SELECT "q_four_achieved column does not exist" as message;');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add target_output column if it doesn't exist
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = 'amis_six_db' 
AND table_name = 'workplan_activities' 
AND column_name = 'target_output';

SET @sql = IF(@col_exists = 0, 'ALTER TABLE workplan_activities ADD COLUMN target_output VARCHAR(255) NULL AFTER description;', 'SELECT "target_output column already exists" as message;');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Show final table structure
DESCRIBE workplan_activities;
