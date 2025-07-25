<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * CommodityPricesModel
 *
 * Handles database operations for the commodity_prices table
 * Manages price data, trends, and market analysis for commodities
 */
class CommodityPricesModel extends Model
{
    // Table configuration
    protected $table = 'commodity_prices';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    // Use timestamps and specify the fields
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $useSoftDeletes = true;

    // Fields that can be set during save, insert, update
    protected $allowedFields = [
        'commodity_id',
        'price_date',
        'market_type',
        'price_per_unit',
        'unit_of_measurement',
        'currency',
        'location',
        'source',
        'notes',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // Validation rules
    protected $validationRules = [
        'commodity_id' => 'required|integer',
        'price_date' => 'required|valid_date',
        'market_type' => 'required|in_list[local,export,wholesale,retail]',
        'price_per_unit' => 'required|decimal',
        'unit_of_measurement' => 'permit_empty|max_length[50]',
        'currency' => 'permit_empty|max_length[10]',
        'location' => 'permit_empty|max_length[255]',
        'source' => 'permit_empty|max_length[255]',
        'created_by' => 'permit_empty|max_length[100]',
        'updated_by' => 'permit_empty|max_length[100]',
        'deleted_by' => 'permit_empty|max_length[100]'
    ];

    // Validation messages
    protected $validationMessages = [
        'commodity_id' => [
            'required' => 'Commodity ID is required',
            'integer' => 'Commodity ID must be a valid integer'
        ],
        'price_date' => [
            'required' => 'Price date is required',
            'valid_date' => 'Price date must be a valid date'
        ],
        'market_type' => [
            'required' => 'Market type is required',
            'in_list' => 'Market type must be one of: local, export, wholesale, retail'
        ],
        'price_per_unit' => [
            'required' => 'Price per unit is required',
            'decimal' => 'Price per unit must be a valid decimal number'
        ]
    ];

    /**
     * Get all commodity prices with commodity details
     */
    public function getAllPricesWithDetails()
    {
        $db = \Config\Database::connect();

        $query = $db->table('commodity_prices cp')
            ->select('cp.*,
                     c.commodity_name,
                     c.commodity_code,
                     CONCAT(u1.fname, " ", u1.lname) as created_by_name,
                     CONCAT(u2.fname, " ", u2.lname) as updated_by_name')
            ->join('commodities c', 'cp.commodity_id = c.id', 'left')
            ->join('users u1', 'cp.created_by = u1.id', 'left')
            ->join('users u2', 'cp.updated_by = u2.id', 'left')
            ->where('cp.is_deleted', false)
            ->orderBy('cp.price_date', 'DESC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get price trends for a specific commodity
     */
    public function getPriceTrendsByCommodity($commodityId, $months = 12)
    {
        $db = \Config\Database::connect();
        
        $startDate = date('Y-m-d', strtotime("-{$months} months"));
        
        $query = $db->table('commodity_prices cp')
            ->select('cp.price_date, cp.market_type, cp.price_per_unit, cp.unit_of_measurement, c.commodity_name')
            ->join('commodities c', 'cp.commodity_id = c.id', 'left')
            ->where('cp.commodity_id', $commodityId)
            ->where('cp.price_date >=', $startDate)
            ->where('cp.is_deleted', false)
            ->orderBy('cp.price_date', 'ASC')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get average prices by market type for all commodities
     */
    public function getAveragePricesByMarketType($months = 6)
    {
        $db = \Config\Database::connect();
        
        $startDate = date('Y-m-d', strtotime("-{$months} months"));
        
        $query = $db->table('commodity_prices cp')
            ->select('c.commodity_name, c.commodity_code, cp.market_type, 
                     AVG(cp.price_per_unit) as avg_price, 
                     cp.unit_of_measurement, cp.currency,
                     COUNT(cp.id) as price_records')
            ->join('commodities c', 'cp.commodity_id = c.id', 'left')
            ->where('cp.price_date >=', $startDate)
            ->where('cp.is_deleted', false)
            ->groupBy('cp.commodity_id, cp.market_type, cp.unit_of_measurement')
            ->orderBy('c.commodity_name, cp.market_type')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get price volatility analysis
     */
    public function getPriceVolatilityAnalysis($commodityId = null, $months = 12)
    {
        $db = \Config\Database::connect();
        
        $startDate = date('Y-m-d', strtotime("-{$months} months"));
        
        $builder = $db->table('commodity_prices cp')
            ->select('c.commodity_name, c.commodity_code, cp.market_type,
                     MIN(cp.price_per_unit) as min_price,
                     MAX(cp.price_per_unit) as max_price,
                     AVG(cp.price_per_unit) as avg_price,
                     STDDEV(cp.price_per_unit) as price_stddev,
                     COUNT(cp.id) as price_records,
                     cp.unit_of_measurement, cp.currency')
            ->join('commodities c', 'cp.commodity_id = c.id', 'left')
            ->where('cp.price_date >=', $startDate)
            ->where('cp.is_deleted', false);
            
        if ($commodityId) {
            $builder->where('cp.commodity_id', $commodityId);
        }
        
        $query = $builder->groupBy('cp.commodity_id, cp.market_type, cp.unit_of_measurement')
            ->orderBy('c.commodity_name, cp.market_type')
            ->get();

        $results = $query->getResultArray();
        
        // Calculate volatility percentage
        foreach ($results as &$result) {
            if ($result['avg_price'] > 0) {
                $result['volatility_percent'] = round(($result['price_stddev'] / $result['avg_price']) * 100, 2);
                $result['price_range_percent'] = round((($result['max_price'] - $result['min_price']) / $result['avg_price']) * 100, 2);
            } else {
                $result['volatility_percent'] = 0;
                $result['price_range_percent'] = 0;
            }
        }

        return $results;
    }

    /**
     * Get monthly price trends for charts
     */
    public function getMonthlyPriceTrends($commodityId = null, $months = 12)
    {
        $db = \Config\Database::connect();
        
        $startDate = date('Y-m-d', strtotime("-{$months} months"));
        
        $builder = $db->table('commodity_prices cp')
            ->select('YEAR(cp.price_date) as year, MONTH(cp.price_date) as month,
                     c.commodity_name, cp.market_type,
                     AVG(cp.price_per_unit) as avg_price,
                     cp.unit_of_measurement, cp.currency')
            ->join('commodities c', 'cp.commodity_id = c.id', 'left')
            ->where('cp.price_date >=', $startDate)
            ->where('cp.is_deleted', false);
            
        if ($commodityId) {
            $builder->where('cp.commodity_id', $commodityId);
        }
        
        $query = $builder->groupBy('YEAR(cp.price_date), MONTH(cp.price_date), cp.commodity_id, cp.market_type')
            ->orderBy('year, month, c.commodity_name, cp.market_type')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get latest prices for all commodities
     */
    public function getLatestPrices()
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('commodity_prices cp1')
            ->select('cp1.*, c.commodity_name, c.commodity_code')
            ->join('commodities c', 'cp1.commodity_id = c.id', 'left')
            ->join('commodity_prices cp2', 'cp1.commodity_id = cp2.commodity_id AND cp1.market_type = cp2.market_type AND cp1.price_date < cp2.price_date', 'left')
            ->where('cp2.id IS NULL')
            ->where('cp1.is_deleted', false)
            ->orderBy('c.commodity_name, cp1.market_type')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get price comparison between market types
     */
    public function getMarketTypeComparison($commodityId, $months = 6)
    {
        $db = \Config\Database::connect();
        
        $startDate = date('Y-m-d', strtotime("-{$months} months"));
        
        $query = $db->table('commodity_prices cp')
            ->select('cp.market_type, 
                     AVG(cp.price_per_unit) as avg_price,
                     MIN(cp.price_per_unit) as min_price,
                     MAX(cp.price_per_unit) as max_price,
                     COUNT(cp.id) as price_records,
                     cp.unit_of_measurement, cp.currency')
            ->where('cp.commodity_id', $commodityId)
            ->where('cp.price_date >=', $startDate)
            ->where('cp.is_deleted', false)
            ->groupBy('cp.market_type, cp.unit_of_measurement')
            ->orderBy('cp.market_type')
            ->get();

        return $query->getResultArray();
    }

    /**
     * Get price forecasting data (simple trend analysis)
     */
    public function getPriceForecastData($commodityId, $marketType = 'local', $months = 12)
    {
        $db = \Config\Database::connect();
        
        $startDate = date('Y-m-d', strtotime("-{$months} months"));
        
        $query = $db->table('commodity_prices')
            ->select('price_date, price_per_unit')
            ->where('commodity_id', $commodityId)
            ->where('market_type', $marketType)
            ->where('price_date >=', $startDate)
            ->where('is_deleted', false)
            ->orderBy('price_date', 'ASC')
            ->get();

        $data = $query->getResultArray();
        
        // Simple linear trend calculation
        if (count($data) >= 2) {
            $n = count($data);
            $sumX = 0;
            $sumY = 0;
            $sumXY = 0;
            $sumX2 = 0;
            
            foreach ($data as $i => $point) {
                $x = $i + 1; // Sequential numbering
                $y = floatval($point['price_per_unit']);
                
                $sumX += $x;
                $sumY += $y;
                $sumXY += $x * $y;
                $sumX2 += $x * $x;
            }
            
            $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
            $intercept = ($sumY - $slope * $sumX) / $n;
            
            // Forecast next 3 months
            $forecasts = [];
            for ($i = 1; $i <= 3; $i++) {
                $nextX = $n + $i;
                $forecastPrice = $slope * $nextX + $intercept;
                $forecastDate = date('Y-m-d', strtotime("+{$i} months"));
                
                $forecasts[] = [
                    'forecast_date' => $forecastDate,
                    'forecast_price' => round($forecastPrice, 2),
                    'trend_direction' => $slope > 0 ? 'increasing' : ($slope < 0 ? 'decreasing' : 'stable')
                ];
            }
            
            return [
                'historical_data' => $data,
                'trend_slope' => $slope,
                'trend_intercept' => $intercept,
                'forecasts' => $forecasts
            ];
        }
        
        return [
            'historical_data' => $data,
            'forecasts' => [],
            'message' => 'Insufficient data for forecasting'
        ];
    }
}
