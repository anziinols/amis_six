<?php
// Script to properly fix the workplan_activities table structure
// This will remove old quarter columns and activity_type column

// Database configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'amis_six_db';
$port = 3306;

// Create connection
$conn = new mysqli($hostname, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Starting workplan_activities table migration...\n\n";

// List of columns to remove
$columnsToRemove = [
    'activity_type',
    'q_one_target',
    'q_two_target',
    'q_three_target',
    'q_four_target',
    'q_one_achieved',
    'q_two_achieved',
    'q_three_achieved',
    'q_four_achieved'
];

$removedCount = 0;

// Remove each column if it exists
foreach ($columnsToRemove as $column) {
    echo "Checking column: $column...\n";

    // Check if column exists
    $checkSql = "SELECT COUNT(*) as count FROM information_schema.columns
                 WHERE table_schema = '$database'
                 AND table_name = 'workplan_activities'
                 AND column_name = '$column'";

    $result = $conn->query($checkSql);
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo "  - Column '$column' exists, removing...\n";
        $dropSql = "ALTER TABLE `workplan_activities` DROP COLUMN `$column`";

        if ($conn->query($dropSql) === TRUE) {
            echo "  - Successfully removed column '$column'\n";
            $removedCount++;
        } else {
            echo "  - Error removing column '$column': " . $conn->error . "\n";
        }
    } else {
        echo "  - Column '$column' does not exist (already removed)\n";
    }
}

echo "\n";

// Check if target_output column exists and add it if needed
echo "Checking target_output column...\n";
$checkTargetSql = "SELECT COUNT(*) as count FROM information_schema.columns
                   WHERE table_schema = '$database'
                   AND table_name = 'workplan_activities'
                   AND column_name = 'target_output'";

$result = $conn->query($checkTargetSql);
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    echo "  - Adding target_output column...\n";
    $addSql = "ALTER TABLE `workplan_activities` ADD COLUMN `target_output` VARCHAR(255) NULL AFTER `description`";

    if ($conn->query($addSql) === TRUE) {
        echo "  - Successfully added target_output column\n";
    } else {
        echo "  - Error adding target_output column: " . $conn->error . "\n";
    }
} else {
    echo "  - target_output column already exists\n";
}

echo "\nMigration Summary:\n";
echo "- Removed $removedCount old columns\n";
echo "- target_output column is present\n";

// Show final table structure
echo "\nFinal workplan_activities table structure:\n";
$descSql = "DESCRIBE workplan_activities";
$result = $conn->query($descSql);

if ($result->num_rows > 0) {
    echo sprintf("%-20s %-20s %-10s %-10s %-15s %-10s\n",
                 "Field", "Type", "Null", "Key", "Default", "Extra");
    echo str_repeat("-", 90) . "\n";

    while($row = $result->fetch_assoc()) {
        echo sprintf("%-20s %-20s %-10s %-10s %-15s %-10s\n",
                     $row["Field"],
                     $row["Type"],
                     $row["Null"],
                     $row["Key"],
                     $row["Default"] ?? "NULL",
                     $row["Extra"]);
    }
}

$conn->close();
echo "\nMigration completed!\n";