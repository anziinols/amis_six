# Force Migration Guide for workplan_activities Table

This guide provides multiple methods to perform a force migration for the `workplan_activities` table in the AMIS Six database.

## Overview

The force migration will:
1. Backup existing data (if table exists)
2. Drop the current `workplan_activities` table
3. Recreate the table with the latest schema
4. Restore backed up data (with column mapping)
5. Update the migrations table to mark migrations as completed

## Latest Schema Changes

The new `workplan_activities` table includes:
- Removed: `activity_type`, `q_one_target`, `q_two_target`, `q_three_target`, `q_four_target`, `q_one_achieved`, `q_two_achieved`, `q_three_achieved`, `q_four_achieved`
- Added: `target_output` column (VARCHAR 255)
- Renamed: Quarter target columns to `q_one`, `q_two`, `q_three`, `q_four`

## Method 1: Using PHP Script (Recommended)

### Prerequisites
- PHP installed and accessible
- MySQL/MariaDB running
- Database credentials configured

### Steps
1. Open Command Prompt or Terminal
2. Navigate to the project directory:
   ```bash
   cd C:\xampp\htdocs\amis_six
   ```
3. Run the PHP migration script:
   ```bash
   php force_migrate_workplan_activities.php
   ```

### Alternative PHP Paths (for XAMPP)
If `php` is not in your PATH, try:
```bash
C:\xampp\php\php.exe force_migrate_workplan_activities.php
```

Or double-click the `run_force_migration.bat` file.

## Method 2: Using phpMyAdmin (Web Interface)

### Steps
1. Open phpMyAdmin in your browser (usually http://localhost/phpmyadmin)
2. Select the `amis_six_db` database
3. Click on the "SQL" tab
4. Copy and paste the contents of `force_migrate_workplan_activities.sql`
5. Click "Go" to execute the script

## Method 3: Using MySQL Command Line

### Steps
1. Open Command Prompt
2. Navigate to the project directory:
   ```bash
   cd C:\xampp\htdocs\amis_six
   ```
3. Run the SQL script:
   ```bash
   mysql -u root -p amis_six_db < force_migrate_workplan_activities.sql
   ```
4. Enter your MySQL password when prompted

### Alternative MySQL Paths (for XAMPP)
```bash
C:\xampp\mysql\bin\mysql.exe -u root -p amis_six_db < force_migrate_workplan_activities.sql
```

## Method 4: Using CodeIgniter Spark (if available)

If you can access the CodeIgniter Spark command:
```bash
php spark migrate:refresh
```

Or to run specific migrations:
```bash
php spark migrate
```

## Verification

After running the migration, verify the results:

### Check Table Structure
```sql
DESCRIBE workplan_activities;
```

### Check Record Count
```sql
SELECT COUNT(*) FROM workplan_activities;
```

### Check Applied Migrations
```sql
SELECT * FROM migrations WHERE class LIKE '%Workplan%' ORDER BY time;
```

## Expected Results

The `workplan_activities` table should have the following structure:
- `id` (Primary Key)
- `workplan_id`
- `branch_id`
- `activity_code`
- `title`
- `description`
- `target_output` (NEW)
- `q_one`, `q_two`, `q_three`, `q_four`
- `supervisor_id`
- `status`, `status_by`, `status_at`, `status_remarks`
- `total_budget`
- `rated_at`, `rated_by`, `rating`, `rating_remarks`
- `created_at`, `created_by`, `updated_at`, `updated_by`
- `deleted_at`, `deleted_by`

## Troubleshooting

### Common Issues

1. **"Table doesn't exist" error**: This is normal if it's the first time creating the table.

2. **"Column doesn't exist" error during restore**: The script handles this with COALESCE functions.

3. **Permission denied**: Ensure your MySQL user has CREATE, DROP, and INSERT privileges.

4. **Connection failed**: Check your database credentials in the PHP script or verify MySQL is running.

### Rollback (if needed)

If something goes wrong, you can restore from the backup table (if it exists):
```sql
DROP TABLE workplan_activities;
RENAME TABLE workplan_activities_backup TO workplan_activities;
```

## Files Created

- `force_migrate_workplan_activities.sql` - SQL script for direct execution
- `force_migrate_workplan_activities.php` - PHP script with error handling
- `run_force_migration.bat` - Windows batch file for easy execution
- `FORCE_MIGRATION_GUIDE.md` - This guide

## Support

If you encounter issues:
1. Check the MySQL error log
2. Verify database connection settings
3. Ensure all required privileges are granted
4. Check that the database `amis_six_db` exists

The migration scripts include comprehensive error handling and will provide detailed output about what's happening at each step.
