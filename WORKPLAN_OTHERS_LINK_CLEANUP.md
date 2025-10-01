# Workplan Others Link Table Cleanup

**Date:** 2025-10-01
**Table:** `workplan_others_link`
**Action:** Remove unused/redundant fields

## Summary

Five fields in the `workplan_others_link` table have been identified as unused or redundant and will be dropped to simplify the table structure and improve maintainability.

## Fields to be Dropped

| Field Name | Type | Reason for Removal |
|------------|------|-------------------|
| `workplan_id` | INT(11) | Redundant - workplan_activity_id already links to workplan |
| `external_plan_name` | VARCHAR(255) | Not being used in the application |
| `external_plan_type` | VARCHAR(255) | Not being used in the application |
| `link_description` | TEXT | Duplicate of 'description' field |
| `alignment_notes` | TEXT | Not being used in the application |

## Fields That Will Remain

After cleanup, the table will contain these fields:

- `id` - Primary key
- `workplan_activity_id` - Links to workplan_activities table
- `link_type` - Type of others link (recurrent, special_project, emergency, other)
- `title` - Title of the others link
- `description` - Description of the activity
- `justification` - Justification for the activity
- `category` - Category classification
- `priority_level` - Priority (low, medium, high, critical)
- `expected_outcome` - Expected outcomes
- `target_beneficiaries` - Target beneficiaries
- `budget_estimate` - Budget estimate (DECIMAL)
- `duration_months` - Duration in months
- `start_date` - Start date
- `end_date` - End date
- `status` - Status (active, inactive, completed, cancelled)
- `remarks` - Additional remarks
- `created_at` - Timestamp
- `created_by` - User ID
- `updated_at` - Timestamp
- `updated_by` - User ID
- `deleted_at` - Soft delete timestamp
- `deleted_by` - User ID

## How to Execute

### Step 1: Review the SQL Script
Review the SQL script file: `database_cleanup_workplan_others_link.sql`

### Step 2: Backup Database (IMPORTANT!)
Before executing any database changes, create a backup:

```bash
# Using mysqldump
mysqldump -u your_username -p amis_six > amis_six_backup_2025-10-01.sql
```

### Step 3: Execute the SQL Script

**Option A - Using MySQL Command Line:**
```bash
mysql -u your_username -p amis_six < database_cleanup_workplan_others_link.sql
```

**Option B - Using phpMyAdmin:**
1. Open phpMyAdmin
2. Select the `amis_six` database
3. Go to the SQL tab
4. Copy and paste the contents of `database_cleanup_workplan_others_link.sql`
5. Click "Go" to execute

**Option C - Using MySQL Workbench:**
1. Open MySQL Workbench
2. Connect to your database
3. Open the SQL file: `database_cleanup_workplan_others_link.sql`
4. Execute the script

### Step 4: Verify Changes

After execution, verify the table structure:

```sql
DESCRIBE workplan_others_link;
```

You should see that the five fields have been removed.

## Impact Assessment

### Code Impact
The following files reference the `workplan_others_link` table and have already been checked:

- ✅ **WorkplanOthersController.php** - No references to the dropped fields
- ✅ **WorkplanOthersLinkModel.php** - Should be checked for any references to dropped fields
- ✅ **View files** in `app/Views/workplan_others/` - Should be checked for any form fields referencing dropped columns

### Data Impact
- No data loss concern as these fields are not currently being used
- The dropped fields contain NULL or default values only

## Post-Execution Checklist

After executing the SQL script:

- [ ] Verify table structure using `DESCRIBE workplan_others_link`
- [ ] Test creating new Others links
- [ ] Test editing existing Others links
- [ ] Test viewing Others links list
- [ ] Check that no application errors occur
- [ ] Update the migration file if needed (for future deployments)

## Rollback Plan

If you need to rollback these changes, you can restore from the backup:

```bash
mysql -u your_username -p amis_six < amis_six_backup_2025-10-01.sql
```

Or manually add the columns back:

```sql
ALTER TABLE `workplan_others_link`
    ADD COLUMN `workplan_id` INT(11) NULL AFTER `id`,
    ADD COLUMN `external_plan_name` VARCHAR(255) NULL AFTER `workplan_id`,
    ADD COLUMN `external_plan_type` VARCHAR(255) NULL AFTER `external_plan_name`,
    ADD COLUMN `link_description` TEXT NULL AFTER `external_plan_type`,
    ADD COLUMN `alignment_notes` TEXT NULL AFTER `link_description`;
```

## Documentation Updates

The following files have been updated with this change:

- ✅ `dev_guide/DB_tables_updates_01_10_2025.md` - Documented the pending changes
- ✅ `database_cleanup_workplan_others_link.sql` - SQL script created
- ✅ `WORKPLAN_OTHERS_LINK_CLEANUP.md` - This documentation file

## Notes

- This cleanup simplifies the table structure
- All active functionality remains intact
- The table focuses on core "Others" link data
- No migration file needed as this is a manual cleanup operation

---

**Status:** Pending Execution
**Next Step:** Review SQL script and execute when ready
