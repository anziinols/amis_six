-- ============================================================================
-- Database Cleanup Script - Commodities Feature Removal
-- ============================================================================
-- Date: January 30, 2025
-- Purpose: Remove commodity-related tables and fields from the database
-- 
-- IMPORTANT: 
-- - Backup your database before running this script!
-- - This action is PERMANENT and cannot be undone
-- - All commodity data will be lost
-- ============================================================================

-- Step 1: Backup existing data (optional - uncomment if needed)
-- CREATE TABLE commodities_backup AS SELECT * FROM commodities;
-- CREATE TABLE commodity_production_backup AS SELECT * FROM commodity_production;
-- CREATE TABLE commodity_prices_backup AS SELECT * FROM commodity_prices;

-- ============================================================================
-- Step 2: Drop Foreign Key Constraints (if any exist)
-- ============================================================================

-- Check for foreign keys on commodity_production table
-- ALTER TABLE commodity_production DROP FOREIGN KEY IF EXISTS fk_commodity_production_commodity;

-- Check for foreign keys on commodity_prices table
-- ALTER TABLE commodity_prices DROP FOREIGN KEY IF EXISTS fk_commodity_prices_commodity;

-- Check for foreign keys on users table
-- ALTER TABLE users DROP FOREIGN KEY IF EXISTS fk_users_commodity;

-- ============================================================================
-- Step 3: Drop Commodity-Related Tables
-- ============================================================================

-- Drop commodity_prices table (depends on commodities)
DROP TABLE IF EXISTS `commodity_prices`;

-- Drop commodity_production table (depends on commodities)
DROP TABLE IF EXISTS `commodity_production`;

-- Drop commodities table (main table)
DROP TABLE IF EXISTS `commodities`;

-- ============================================================================
-- Step 4: Remove commodity_id field from users table (Optional)
-- ============================================================================

-- Check if column exists before dropping
SET @dbname = DATABASE();
SET @tablename = 'users';
SET @columnname = 'commodity_id';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_SCHEMA = @dbname)
      AND (TABLE_NAME = @tablename)
      AND (COLUMN_NAME = @columnname)
  ) > 0,
  'ALTER TABLE users DROP COLUMN commodity_id;',
  'SELECT 1;'
));

PREPARE alterIfExists FROM @preparedStatement;
EXECUTE alterIfExists;
DEALLOCATE PREPARE alterIfExists;

-- ============================================================================
-- Step 5: Verification Queries
-- ============================================================================

-- Verify tables are dropped
SELECT 
    'Tables Dropped Successfully' AS Status,
    COUNT(*) AS RemainingCommodityTables
FROM information_schema.tables 
WHERE table_schema = DATABASE() 
AND table_name IN ('commodities', 'commodity_production', 'commodity_prices');

-- Verify commodity_id column is removed from users table
SELECT 
    'Users Table Updated' AS Status,
    COUNT(*) AS CommodityIdColumnExists
FROM information_schema.columns 
WHERE table_schema = DATABASE() 
AND table_name = 'users' 
AND column_name = 'commodity_id';

-- ============================================================================
-- Expected Results:
-- ============================================================================
-- Query 1: RemainingCommodityTables should be 0
-- Query 2: CommodityIdColumnExists should be 0
-- ============================================================================

-- ============================================================================
-- Rollback Instructions (if backups were created):
-- ============================================================================
-- If you need to restore the data:
-- 
-- CREATE TABLE commodities AS SELECT * FROM commodities_backup;
-- CREATE TABLE commodity_production AS SELECT * FROM commodity_production_backup;
-- CREATE TABLE commodity_prices AS SELECT * FROM commodity_prices_backup;
-- 
-- ALTER TABLE users ADD COLUMN commodity_id INT(11) DEFAULT NULL;
-- 
-- DROP TABLE commodities_backup;
-- DROP TABLE commodity_production_backup;
-- DROP TABLE commodity_prices_backup;
-- ============================================================================

-- ============================================================================
-- Additional Cleanup (Optional)
-- ============================================================================

-- Remove uploaded commodity icon files manually:
-- Directory: public/uploads/commodities/icons/
-- You can delete this directory from the file system

-- ============================================================================
-- Notes:
-- ============================================================================
-- 1. The application code has been updated to handle the missing tables
-- 2. All routes, controllers, models, and views have been removed
-- 3. Navigation menus have been updated
-- 4. User management will continue to work normally
-- 5. No breaking changes expected in the application
-- ============================================================================

