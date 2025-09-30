<?php
// Script to drop performance output tables

// Include the necessary CodeIgniter bootstrap
require_once 'app/Config/Paths.php';
require_once 'system/bootstrap.php';

// Get the database connection
$db = \Config\Database::connect();

// Drop the tables in the correct order
$tables = [
    'output_duty_instruction',
    'output_workplan_activities',
    'performance_outputs'
];

foreach ($tables as $table) {
    try {
        $db->query("DROP TABLE IF EXISTS `$table`");
        echo "Successfully dropped table: $table\n";
    } catch (Exception $e) {
        echo "Error dropping table $table: " . $e->getMessage() . "\n";
    }
}

echo "All tables processed.\n";