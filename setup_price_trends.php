<?php
/**
 * Setup script for Price Trends feature
 * 
 * This script creates the commodity_prices table and adds sample data
 * Run this script from the command line: php setup_price_trends.php
 */

// Load the database configuration
require 'app/Config/Database.php';

// Create a database connection
$config = new Config\Database();
$db = \Config\Database::connect();

echo "Setting up Price Trends feature...\n";

// Check if the commodity_prices table exists
$tableExists = false;
$tables = $db->listTables();
foreach ($tables as $table) {
    if ($table === 'commodity_prices') {
        $tableExists = true;
        break;
    }
}

// Create the commodity_prices table if it doesn't exist
if (!$tableExists) {
    echo "Creating commodity_prices table...\n";
    
    $sql = "CREATE TABLE IF NOT EXISTS commodity_prices (
        id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        commodity_id INT(11) UNSIGNED NOT NULL,
        price_date DATE NOT NULL,
        market_type ENUM('local','export','wholesale','retail') DEFAULT 'local',
        price_per_unit DECIMAL(15,2) NOT NULL,
        unit_of_measurement VARCHAR(50) NULL,
        currency VARCHAR(10) DEFAULT 'PGK',
        location VARCHAR(255) NULL,
        source VARCHAR(255) NULL,
        notes TEXT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        created_by VARCHAR(100) NULL,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        updated_by VARCHAR(100) NULL,
        deleted_at DATETIME NULL,
        deleted_by VARCHAR(100) NULL,
        is_deleted TINYINT(1) DEFAULT 0,
        PRIMARY KEY (id),
        KEY commodity_id (commodity_id),
        KEY price_date (price_date),
        KEY market_type (market_type),
        KEY commodity_id_price_date_market_type (commodity_id, price_date, market_type),
        CONSTRAINT commodity_prices_commodity_id_foreign FOREIGN KEY (commodity_id) REFERENCES commodities(id) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    
    try {
        $db->query($sql);
        echo "Table created successfully.\n";
    } catch (\Exception $e) {
        echo "Error creating table: " . $e->getMessage() . "\n";
        exit(1);
    }
} else {
    echo "Table commodity_prices already exists.\n";
}

// Check if we have commodities to work with
$query = $db->query("SELECT * FROM commodities WHERE is_deleted = 0 LIMIT 5");
$commodities = $query->getResultArray();

if (empty($commodities)) {
    echo "No commodities found. Please add commodities first.\n";
    exit(1);
}

// Check if we already have price data
$query = $db->query("SELECT COUNT(*) as count FROM commodity_prices");
$result = $query->getRow();
if ($result->count > 0) {
    echo "Price data already exists ({$result->count} records). Skipping sample data creation.\n";
    exit(0);
}

// Add sample price data
echo "Adding sample price data...\n";

$marketTypes = ['local', 'export', 'wholesale', 'retail'];
$priceData = [];

// Generate sample price data for the last 12 months
foreach ($commodities as $commodity) {
    $basePrice = rand(50, 500); // Base price between 50-500 PGK
    
    foreach ($marketTypes as $marketType) {
        // Adjust base price for market type
        $marketMultiplier = 1.0;
        switch ($marketType) {
            case 'export':
                $marketMultiplier = 1.3; // Export prices 30% higher
                break;
            case 'wholesale':
                $marketMultiplier = 0.8; // Wholesale prices 20% lower
                break;
            case 'retail':
                $marketMultiplier = 1.2; // Retail prices 20% higher
                break;
            case 'local':
            default:
                $marketMultiplier = 1.0; // Base price
                break;
        }
        
        $adjustedBasePrice = $basePrice * $marketMultiplier;
        
        // Generate monthly data for the last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} months"));
            
            // Add some random variation (±20%)
            $variation = (rand(80, 120) / 100);
            $price = round($adjustedBasePrice * $variation, 2);
            
            $sql = "INSERT INTO commodity_prices (
                commodity_id, 
                price_date, 
                market_type, 
                price_per_unit, 
                unit_of_measurement, 
                currency, 
                location, 
                source, 
                notes, 
                created_by, 
                created_at, 
                updated_at, 
                is_deleted
            ) VALUES (
                {$commodity['id']},
                '{$date}',
                '{$marketType}',
                {$price},
                'kg',
                'PGK',
                'Port Moresby',
                'Market Survey',
                'Sample data for testing',
                '1',
                NOW(),
                NOW(),
                0
            )";
            
            try {
                $db->query($sql);
                echo ".";
            } catch (\Exception $e) {
                echo "\nError inserting price data: " . $e->getMessage() . "\n";
                // Continue with other records
            }
        }
    }
}

echo "\nSample data added successfully.\n";
echo "Price Trends feature is now ready to use!\n";
echo "Visit http://localhost/amis_five/reports/commodity/price-trends to see the price trends dashboard.\n";
