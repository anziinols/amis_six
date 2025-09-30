<?php
// Simple script to apply the target_output column migration using direct MySQLi connection

// Database configuration from app/Config/Database.php
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

// SQL to add the column
$sql = "ALTER TABLE `workplan_activities` ADD COLUMN `target_output` VARCHAR(255) NULL AFTER `description`";

if ($conn->query($sql) === TRUE) {
    echo "Successfully added target_output column to workplan_activities table.\n";
} else {
    echo "Error: " . $sql . "\n" . $conn->error . "\n";
}

$conn->close();