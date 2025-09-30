<?php
// Script to apply all target_output column migrations

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

// Array of migrations to apply
$migrations = [
    [
        'table' => 'activities',
        'sql' => "ALTER TABLE `activities` ADD COLUMN `target_output` VARCHAR(255) NULL AFTER `activity_description`"
    ],
    [
        'table' => 'activities_input',
        'sql' => "ALTER TABLE `activities_input` ADD COLUMN `target_output` VARCHAR(255) NULL AFTER `inputs`"
    ],
    [
        'table' => 'activities_training',
        'sql' => "ALTER TABLE `activities_training` ADD COLUMN `target_output` VARCHAR(255) NULL AFTER `trainees`"
    ],
    [
        'table' => 'activities_infrastructure',
        'sql' => "ALTER TABLE `activities_infrastructure` ADD COLUMN `target_output` VARCHAR(255) NULL AFTER `infrastructure`"
    ]
];

// Apply each migration
$successCount = 0;
foreach ($migrations as $migration) {
    echo "Applying migration to {$migration['table']}...\n";
    if ($conn->query($migration['sql']) === TRUE) {
        echo "Successfully added target_output column to {$migration['table']} table.\n";
        $successCount++;
    } else {
        if ($conn->errno == 1060) { // Column already exists
            echo "Column 'target_output' already exists in {$migration['table']} table.\n";
            $successCount++;
        } else {
            echo "Error adding column to {$migration['table']}: " . $conn->error . "\n";
        }
    }
}

echo "\nMigration summary: $successCount out of " . count($migrations) . " migrations completed.\n";

$conn->close();