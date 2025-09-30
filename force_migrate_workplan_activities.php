<?php
/**
 * Force Migration Script for workplan_activities table
 * This script will drop and recreate the workplan_activities table with the latest schema
 * Run this script from command line: php force_migrate_workplan_activities.php
 */

// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'amis_six_db';
$port = 3306;

echo "Starting force migration for workplan_activities table...\n";

try {
    // Create connection
    $conn = new mysqli($hostname, $username, $password, $database, $port);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "Connected to database successfully.\n";
    
    // Step 1: Check if table exists and backup data
    echo "Step 1: Checking if workplan_activities table exists...\n";
    
    $result = $conn->query("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = '$database' AND table_name = 'workplan_activities'");
    $row = $result->fetch_assoc();
    $tableExists = $row['count'] > 0;
    
    if ($tableExists) {
        echo "Table exists. Creating backup...\n";
        
        // Create backup table
        $conn->query("DROP TABLE IF EXISTS workplan_activities_backup");
        $conn->query("CREATE TABLE workplan_activities_backup AS SELECT * FROM workplan_activities");
        
        $result = $conn->query("SELECT COUNT(*) as count FROM workplan_activities_backup");
        $row = $result->fetch_assoc();
        echo "Backed up {$row['count']} records.\n";
    } else {
        echo "Table does not exist. No backup needed.\n";
    }
    
    // Step 2: Drop existing table
    echo "Step 2: Dropping existing workplan_activities table...\n";
    $conn->query("DROP TABLE IF EXISTS workplan_activities");
    
    // Step 3: Create new table with latest schema
    echo "Step 3: Creating workplan_activities table with latest schema...\n";
    
    $createTableSQL = "
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    if ($conn->query($createTableSQL) === TRUE) {
        echo "Table created successfully.\n";
    } else {
        throw new Exception("Error creating table: " . $conn->error);
    }
    
    // Step 4: Restore data if backup exists
    if ($tableExists) {
        echo "Step 4: Restoring data from backup...\n";
        
        $restoreSQL = "
        INSERT INTO workplan_activities (
            id, workplan_id, branch_id, activity_code, title, description, target_output,
            q_one, q_two, q_three, q_four, supervisor_id, status, status_by, status_at,
            status_remarks, total_budget, rated_at, rated_by, rating, rating_remarks,
            created_at, created_by, updated_at, updated_by, deleted_at, deleted_by
        ) 
        SELECT 
            id, workplan_id, branch_id, activity_code, title, description,
            COALESCE(target_output, NULL) as target_output,
            COALESCE(q_one_target, q_one) as q_one,
            COALESCE(q_two_target, q_two) as q_two,
            COALESCE(q_three_target, q_three) as q_three,
            COALESCE(q_four_target, q_four) as q_four,
            supervisor_id, status, status_by, status_at, status_remarks, total_budget,
            rated_at, rated_by, rating, rating_remarks, created_at, created_by,
            updated_at, updated_by, deleted_at, deleted_by
        FROM workplan_activities_backup";
        
        if ($conn->query($restoreSQL) === TRUE) {
            $result = $conn->query("SELECT COUNT(*) as count FROM workplan_activities");
            $row = $result->fetch_assoc();
            echo "Restored {$row['count']} records.\n";
        } else {
            echo "Warning: Error restoring data: " . $conn->error . "\n";
        }
    }
    
    // Step 5: Update migrations table
    echo "Step 5: Updating migrations table...\n";
    
    // Check if migrations table exists
    $result = $conn->query("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = '$database' AND table_name = 'migrations'");
    $row = $result->fetch_assoc();
    $migrationsExists = $row['count'] > 0;
    
    if (!$migrationsExists) {
        echo "Creating migrations table...\n";
        $createMigrationsSQL = "
        CREATE TABLE `migrations` (
            `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            `version` varchar(255) NOT NULL,
            `class` varchar(255) NOT NULL,
            `group` varchar(255) NOT NULL,
            `namespace` varchar(255) NOT NULL,
            `time` int(11) NOT NULL,
            `batch` int(11) unsigned NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
        
        $conn->query($createMigrationsSQL);
    }
    
    // Insert migration records
    $migrations = [
        ['2025-01-20-000008', 'App\\Database\\Migrations\\CreateWorkplanActivityTables'],
        ['2025-01-29-120000', 'App\\Database\\Migrations\\UpdateWorkplanActivitiesTable'],
        ['2025-09-29-120000', 'App\\Database\\Migrations\\AddTargetOutputToWorkplanActivities']
    ];
    
    foreach ($migrations as $index => $migration) {
        $version = $migration[0];
        $class = $migration[1];
        $batch = $index + 1;
        $time = time();
        
        $insertSQL = "INSERT IGNORE INTO migrations (version, class, `group`, namespace, time, batch) 
                     VALUES ('$version', '$class', 'default', 'App', $time, $batch)";
        $conn->query($insertSQL);
    }
    
    echo "Migration records updated.\n";
    
    // Step 6: Clean up
    echo "Step 6: Cleaning up...\n";
    $conn->query("DROP TABLE IF EXISTS workplan_activities_backup");
    
    // Step 7: Show results
    echo "\n=== MIGRATION COMPLETED SUCCESSFULLY ===\n";
    
    // Show table structure
    echo "\nTable structure:\n";
    $result = $conn->query("DESCRIBE workplan_activities");
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['Field']} ({$row['Type']})\n";
    }
    
    // Show record count
    $result = $conn->query("SELECT COUNT(*) as count FROM workplan_activities");
    $row = $result->fetch_assoc();
    echo "\nTotal records: {$row['count']}\n";
    
    // Show applied migrations
    echo "\nApplied migrations:\n";
    $result = $conn->query("SELECT * FROM migrations WHERE class LIKE '%Workplan%' ORDER BY time");
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['version']}: {$row['class']}\n";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\nForce migration completed successfully!\n";
?>
