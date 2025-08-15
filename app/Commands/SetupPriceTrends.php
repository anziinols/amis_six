<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SetupPriceTrends extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'setup:price-trends';
    protected $description = 'Setup the Price Trends feature by creating tables and sample data';

    public function run(array $params)
    {
        CLI::write('Setting up Price Trends feature...', 'green');

        $db = \Config\Database::connect();

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
            CLI::write('Creating commodity_prices table...', 'yellow');
            
            $sql = "CREATE TABLE IF NOT EXISTS commodity_prices (
                id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                commodity_id INT(11) UNSIGNED NOT NULL,
                price_date DATE NOT NULL,
                market_type ENUM('local','export','wholesale','retail') DEFAULT 'local',
                price_per_unit DECIMAL(15,2) NOT NULL,
                unit_of_measurement VARCHAR(50) NULL,
                currency VARCHAR(10) DEFAULT '" . CURRENCY_CODE . "',
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
                KEY commodity_id_price_date_market_type (commodity_id, price_date, market_type)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            
            try {
                $db->query($sql);
                CLI::write('Table created successfully.', 'green');
            } catch (\Exception $e) {
                CLI::write('Error creating table: ' . $e->getMessage(), 'red');
                return;
            }
        } else {
            CLI::write('Table commodity_prices already exists.', 'yellow');
        }

        // Check if we have commodities to work with
        $query = $db->query("SELECT * FROM commodities WHERE is_deleted = 0 LIMIT 5");
        $commodities = $query->getResultArray();

        if (empty($commodities)) {
            CLI::write('No commodities found. Please add commodities first.', 'red');
            return;
        }

        // Check if we already have price data
        $query = $db->query("SELECT COUNT(*) as count FROM commodity_prices");
        $result = $query->getRow();
        if ($result->count > 0) {
            CLI::write("Price data already exists ({$result->count} records). Skipping sample data creation.", 'yellow');
            CLI::write('Price Trends feature is ready to use!', 'green');
            CLI::write('Visit http://localhost/amis_six/reports/commodity/price-trends to see the dashboard.', 'cyan');
            return;
        }

        // Add sample price data
        CLI::write('Adding sample price data...', 'yellow');

        $marketTypes = ['local', 'export', 'wholesale', 'retail'];
        $recordsAdded = 0;

        // Generate sample price data for the last 12 months
        foreach ($commodities as $commodity) {
            $basePrice = rand(50, 500); // Base price between 50-500 " . CURRENCY_SYMBOL
            
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
                    
                    $data = [
                        'commodity_id' => $commodity['id'],
                        'price_date' => $date,
                        'market_type' => $marketType,
                        'price_per_unit' => $price,
                        'unit_of_measurement' => 'kg',
                        'currency' => 'PGK',
                        'location' => 'Port Moresby',
                        'source' => 'Market Survey',
                        'notes' => 'Sample data for testing',
                        'created_by' => '1',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'is_deleted' => 0
                    ];
                    
                    try {
                        $db->table('commodity_prices')->insert($data);
                        $recordsAdded++;
                        if ($recordsAdded % 10 == 0) {
                            CLI::write('.', 'green', false);
                        }
                    } catch (\Exception $e) {
                        CLI::write("\nError inserting price data: " . $e->getMessage(), 'red');
                        // Continue with other records
                    }
                }
            }
        }

        CLI::write("\nSample data added successfully! ({$recordsAdded} records)", 'green');
        CLI::write('Price Trends feature is now ready to use!', 'green');
        CLI::write('Visit http://localhost/amis_six/reports/commodity/price-trends to see the price trends dashboard.', 'cyan');
    }
}
