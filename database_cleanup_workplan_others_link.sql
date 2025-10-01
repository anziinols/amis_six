-- ============================================================================
-- Database Cleanup Script: Drop Fields from workplan_others_link Table
-- Created: 2025-10-01
-- Purpose: Remove unused fields from workplan_others_link table
-- ============================================================================

-- Fields to be dropped:
-- 1. workplan_id
-- 2. external_plan_name
-- 3. external_plan_type
-- 4. link_description
-- 5. alignment_notes

USE amis_six_db;

-- Drop the specified columns from workplan_others_link table
ALTER TABLE `workplan_others_link`
    DROP COLUMN `workplan_id`,
    DROP COLUMN `external_plan_name`,
    DROP COLUMN `external_plan_type`,
    DROP COLUMN `link_description`,
    DROP COLUMN `alignment_notes`;

-- Verify the changes
DESCRIBE `workplan_others_link`;

-- ============================================================================
-- Expected remaining fields after cleanup:
-- ============================================================================
-- id
-- workplan_activity_id
-- link_type
-- title
-- description
-- justification
-- category
-- priority_level
-- expected_outcome
-- target_beneficiaries
-- budget_estimate
-- duration_months
-- start_date
-- end_date
-- status
-- remarks
-- created_at
-- created_by
-- updated_at
-- updated_by
-- deleted_at
-- deleted_by
-- ============================================================================
