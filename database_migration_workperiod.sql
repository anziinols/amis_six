-- Migration script to rename workplan_period to workperiod
-- Execute this script in your MySQL database

-- Step 1: Rename the main table from workplan_period to workperiod
RENAME TABLE workplan_period TO workperiod;

-- Step 2: Rename the column workplan_period_filepath to workperiod_filepath in the workperiod table
ALTER TABLE workperiod CHANGE COLUMN workplan_period_filepath workperiod_filepath VARCHAR(500) DEFAULT NULL;

-- Step 3: Update foreign key references in other tables
-- Update activities table - rename workplan_period_id to workperiod_id
ALTER TABLE activities CHANGE COLUMN workplan_period_id workperiod_id INT(11) NOT NULL;

-- Step 4: Update performance_indicators_kra table - rename workplan_period_id to workperiod_id
ALTER TABLE performance_indicators_kra CHANGE COLUMN workplan_period_id workperiod_id BIGINT(20) UNSIGNED NOT NULL;

-- Step 5: Update the index name in performance_indicators_kra table
ALTER TABLE performance_indicators_kra DROP INDEX idx_performance_period_id;
ALTER TABLE performance_indicators_kra ADD INDEX idx_workperiod_id (workperiod_id);

-- Verification queries (run these to verify the changes)
-- DESCRIBE workperiod;
-- DESCRIBE activities;
-- DESCRIBE performance_indicators_kra;
-- SHOW INDEX FROM performance_indicators_kra;
