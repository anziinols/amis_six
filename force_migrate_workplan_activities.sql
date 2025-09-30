-- Force Migration Script for workplan_activities table
-- This script will drop and recreate the workplan_activities table with the latest schema
-- Execute this script in phpMyAdmin or MySQL command line

-- Database: amis_six_db
USE amis_six_db;

-- Step 1: Backup existing data (if table exists)
DROP TABLE IF EXISTS workplan_activities_backup;
CREATE TABLE workplan_activities_backup AS SELECT * FROM workplan_activities WHERE 1=0;

-- Check if workplan_activities table exists and backup data
SET @table_exists = 0;
SELECT COUNT(*) INTO @table_exists 
FROM information_schema.tables 
WHERE table_schema = 'amis_six_db' 
AND table_name = 'workplan_activities';

-- If table exists, backup the data
SET @sql = IF(@table_exists > 0, 
    'INSERT INTO workplan_activities_backup SELECT * FROM workplan_activities;', 
    'SELECT "workplan_activities table does not exist, skipping backup" as message;'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Step 2: Drop the existing table
DROP TABLE IF EXISTS workplan_activities;

-- Step 3: Create the workplan_activities table with the latest schema
CREATE TABLE `workplan_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workplan_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `activity_code` varchar(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `target_output` varchar(255) DEFAULT NULL,
  `q_one` decimal(15,2) DEFAULT NULL,
  `q_two` decimal(15,2) DEFAULT NULL,
  `q_three` decimal(15,2) DEFAULT NULL,
  `q_four` decimal(15,2) DEFAULT NULL,
  `supervisor_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `status_by` int(11) DEFAULT NULL,
  `status_at` datetime DEFAULT NULL,
  `status_remarks` text DEFAULT NULL,
  `total_budget` decimal(15,2) DEFAULT NULL,
  `rated_at` datetime DEFAULT NULL,
  `rated_by` int(11) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `rating_remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `workplan_activities_workplan_id` (`workplan_id`),
  KEY `workplan_activities_branch_id` (`branch_id`),
  KEY `workplan_activities_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Step 4: Restore data from backup (if any existed)
SET @restore_sql = IF(@table_exists > 0, 
    'INSERT INTO workplan_activities (id, workplan_id, branch_id, activity_code, title, description, target_output, q_one, q_two, q_three, q_four, supervisor_id, status, status_by, status_at, status_remarks, total_budget, rated_at, rated_by, rating, rating_remarks, created_at, created_by, updated_at, updated_by, deleted_at, deleted_by) 
     SELECT id, workplan_id, branch_id, activity_code, title, description, 
            COALESCE(target_output, NULL) as target_output,
            COALESCE(q_one_target, q_one) as q_one,
            COALESCE(q_two_target, q_two) as q_two, 
            COALESCE(q_three_target, q_three) as q_three,
            COALESCE(q_four_target, q_four) as q_four,
            supervisor_id, status, status_by, status_at, status_remarks, total_budget, 
            rated_at, rated_by, rating, rating_remarks, created_at, created_by, 
            updated_at, updated_by, deleted_at, deleted_by 
     FROM workplan_activities_backup;', 
    'SELECT "No data to restore" as message;'
);
PREPARE stmt FROM @restore_sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Step 5: Update the migrations table to mark this migration as completed
-- First, check if migrations table exists
SET @migrations_exists = 0;
SELECT COUNT(*) INTO @migrations_exists 
FROM information_schema.tables 
WHERE table_schema = 'amis_six_db' 
AND table_name = 'migrations';

-- Create migrations table if it doesn't exist
SET @create_migrations = IF(@migrations_exists = 0, 
    'CREATE TABLE `migrations` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `version` varchar(255) NOT NULL,
        `class` varchar(255) NOT NULL,
        `group` varchar(255) NOT NULL,
        `namespace` varchar(255) NOT NULL,
        `time` int(11) NOT NULL,
        `batch` int(11) unsigned NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;', 
    'SELECT "migrations table already exists" as message;'
);
PREPARE stmt FROM @create_migrations;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Insert migration records
INSERT IGNORE INTO migrations (version, class, `group`, namespace, time, batch) VALUES
('2025-01-20-000008', 'App\\Database\\Migrations\\CreateWorkplanActivityTables', 'default', 'App', UNIX_TIMESTAMP(), 1),
('2025-01-29-120000', 'App\\Database\\Migrations\\UpdateWorkplanActivitiesTable', 'default', 'App', UNIX_TIMESTAMP(), 2),
('2025-09-29-120000', 'App\\Database\\Migrations\\AddTargetOutputToWorkplanActivities', 'default', 'App', UNIX_TIMESTAMP(), 3);

-- Step 6: Clean up backup table
DROP TABLE IF EXISTS workplan_activities_backup;

-- Step 7: Show final table structure
DESCRIBE workplan_activities;

-- Step 8: Show migration status
SELECT 'Migration completed successfully!' as status;
SELECT COUNT(*) as total_records FROM workplan_activities;

-- Display the migrations that have been applied
SELECT * FROM migrations WHERE class LIKE '%Workplan%' ORDER BY time;
