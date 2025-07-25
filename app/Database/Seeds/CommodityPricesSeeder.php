<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CommodityPricesSeeder extends Seeder
{
    public function run()
    {
        // Get existing commodities
        $commodities = $this->db->table('commodities')->where('is_deleted', false)->get()->getResultArray();
        
        if (empty($commodities)) {
            echo "No commodities found. Please add commodities first.\n";
            return;
        }

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
                    
                    $priceData[] = [
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
                }
            }
        }

        // Insert the data
        if (!empty($priceData)) {
            $this->db->table('commodity_prices')->insertBatch($priceData);
            echo "Inserted " . count($priceData) . " price records.\n";
        }
    }
}
