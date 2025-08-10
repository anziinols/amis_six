<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">
                                <i class="fas fa-chart-area"></i> Commodity Market Analysis & Intelligence
                            </h4>
                            <p class="card-text mb-0">Comprehensive market intelligence, price forecasting, and strategic insights for commodity board decision-making.</p>
                        </div>
                        <div>
                            <a href="<?= base_url('reports/commodity/price-trends') ?>" class="btn btn-outline-light">
                                <i class="fas fa-chart-line"></i> Price Trends
                            </a>
                            <a href="<?= base_url('reports/commodity') ?>" class="btn btn-light">
                                <i class="fas fa-seedling"></i> Production Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Market Intelligence Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Active Markets</h6>
                            <h3><?= count($marketAnalysisData) ?></h3>
                            <small>Commodities with price data</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-store fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">High Volatility</h6>
                            <h3><?= count(array_filter($volatilityAnalysis, function($v) { return $v['volatility_percent'] > 15; })) ?></h3>
                            <small>Markets >15% volatility</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Stable Markets</h6>
                            <h3><?= count(array_filter($volatilityAnalysis, function($v) { return $v['volatility_percent'] <= 10; })) ?></h3>
                            <small>Markets ≤10% volatility</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Price Range</h6>
                            <h3><?= !empty($volatilityAnalysis) ? number_format(max(array_column($volatilityAnalysis, 'price_range_percent')), 0) : 0 ?>%</h3>
                            <small>Maximum price range</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-arrows-alt-v fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row mb-4">
        <!-- Price Range Analysis -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-bar"></i> Price Range Analysis
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="priceRangeChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Market Type Distribution -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-pie"></i> Market Type Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="marketDistributionChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Market Intelligence Tables -->
    <div class="row mb-4">
        <!-- Volatility Analysis Table -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-table"></i> Market Volatility Intelligence
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Commodity</th>
                                    <th>Market</th>
                                    <th>Risk Level</th>
                                    <th>Volatility</th>
                                    <th>Price Range</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($volatilityAnalysis as $analysis): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($analysis['commodity_name']) ?></strong>
                                        <br><small class="text-muted"><?= esc($analysis['commodity_code']) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= ucfirst(esc($analysis['market_type'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        $riskLevel = 'Low';
                                        $riskColor = 'success';
                                        if ($analysis['volatility_percent'] > 20) {
                                            $riskLevel = 'High';
                                            $riskColor = 'danger';
                                        } elseif ($analysis['volatility_percent'] > 10) {
                                            $riskLevel = 'Medium';
                                            $riskColor = 'warning';
                                        }
                                        ?>
                                        <span class="badge bg-<?= $riskColor ?>"><?= $riskLevel ?></span>
                                    </td>
                                    <td><?= number_format($analysis['volatility_percent'], 1) ?>%</td>
                                    <td><?= number_format($analysis['price_range_percent'], 1) ?>%</td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($volatilityAnalysis)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No volatility data available</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Market Comparison Table -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-table"></i> Market Type Comparison
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Commodity</th>
                                    <th>Local</th>
                                    <th>Export</th>
                                    <th>Wholesale</th>
                                    <th>Retail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($marketAnalysisData as $data): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($data['commodity']['commodity_name']) ?></strong>
                                    </td>
                                    <?php 
                                    $marketPrices = [];
                                    foreach ($data['market_comparison'] as $market) {
                                        $marketPrices[$market['market_type']] = $market['avg_price'];
                                    }
                                    ?>
                                    <td><?= isset($marketPrices['local']) ? CURRENCY_SYMBOL . ' ' . number_format($marketPrices['local'], 2) : '-' ?></td>
                                    <td><?= isset($marketPrices['export']) ? CURRENCY_SYMBOL . ' ' . number_format($marketPrices['export'], 2) : '-' ?></td>
                                    <td><?= isset($marketPrices['wholesale']) ? CURRENCY_SYMBOL . ' ' . number_format($marketPrices['wholesale'], 2) : '-' ?></td>
                                    <td><?= isset($marketPrices['retail']) ? CURRENCY_SYMBOL . ' ' . number_format($marketPrices['retail'], 2) : '-' ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($marketAnalysisData)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No market comparison data available</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Market Intelligence Insights -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-lightbulb"></i> Market Intelligence Insights
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Market Stability</h6>
                                <p class="mb-0">
                                    <?php 
                                    $stableMarkets = count(array_filter($volatilityAnalysis, function($v) { return $v['volatility_percent'] <= 10; }));
                                    $totalMarkets = count($volatilityAnalysis);
                                    $stabilityPercent = $totalMarkets > 0 ? round(($stableMarkets / $totalMarkets) * 100) : 0;
                                    ?>
                                    <strong><?= $stabilityPercent ?>%</strong> of markets show low volatility (≤10%), indicating stable pricing conditions.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-exclamation-triangle"></i> Risk Assessment</h6>
                                <p class="mb-0">
                                    <?php 
                                    $highRiskMarkets = count(array_filter($volatilityAnalysis, function($v) { return $v['volatility_percent'] > 20; }));
                                    $riskPercent = $totalMarkets > 0 ? round(($highRiskMarkets / $totalMarkets) * 100) : 0;
                                    ?>
                                    <strong><?= $riskPercent ?>%</strong> of markets show high volatility (>20%), requiring careful monitoring and risk management.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-success">
                                <h6><i class="fas fa-chart-line"></i> Market Opportunities</h6>
                                <p class="mb-0">
                                    Export markets generally show higher prices compared to local markets, indicating potential for increased export focus.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-recommendations"></i> Strategic Recommendations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-shield-alt text-primary"></i> Risk Management</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> Monitor high-volatility commodities closely</li>
                                <li><i class="fas fa-check text-success"></i> Implement price stabilization mechanisms</li>
                                <li><i class="fas fa-check text-success"></i> Diversify commodity portfolio to reduce risk</li>
                                <li><i class="fas fa-check text-success"></i> Establish price alert systems for volatile markets</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-chart-line text-success"></i> Market Development</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> Focus on export market development</li>
                                <li><i class="fas fa-check text-success"></i> Strengthen value chain integration</li>
                                <li><i class="fas fa-check text-success"></i> Improve market information systems</li>
                                <li><i class="fas fa-check text-success"></i> Enhance quality standards for premium pricing</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart data from PHP
    const chartData = <?= json_encode($chartData) ?>;
    
    // Price Range Chart
    if (chartData.priceRange && chartData.priceRange.labels.length > 0) {
        const priceRangeCtx = document.getElementById('priceRangeChart').getContext('2d');
        new Chart(priceRangeCtx, {
            type: 'bar',
            data: {
                labels: chartData.priceRange.labels,
                datasets: [{
                    label: 'Price Range %',
                    data: chartData.priceRange.data,
                    backgroundColor: chartData.priceRange.data.map(v => v > 50 ? '#dc3545' : v > 25 ? '#ffc107' : '#28a745'),
                    borderColor: chartData.priceRange.data.map(v => v > 50 ? '#c82333' : v > 25 ? '#e0a800' : '#1e7e34'),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Price Range Percentage (%)'
                        }
                    }
                }
            }
        });
    }
    
    // Market Distribution Chart
    if (chartData.marketComparison && Object.keys(chartData.marketComparison).length > 0) {
        const marketDistributionCtx = document.getElementById('marketDistributionChart').getContext('2d');
        const marketLabels = Object.keys(chartData.marketComparison);
        const marketData = marketLabels.map(market => chartData.marketComparison[market].length);
        
        new Chart(marketDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: marketLabels,
                datasets: [{
                    data: marketData,
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>

<?= $this->endSection() ?>
