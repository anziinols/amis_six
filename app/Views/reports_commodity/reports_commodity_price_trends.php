<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">
                                <i class="fas fa-chart-line"></i> Commodity Price Trends & Market Analysis
                            </h4>
                            <p class="card-text mb-0">Track commodity price movements, analyze market trends, and forecast future price patterns for informed decision-making.</p>
                        </div>
                        <div>
                            <a href="<?= base_url('reports/commodity/price-trends/export-pdf') ?>" class="btn btn-light">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <a href="<?= base_url('reports/commodity/market-analysis') ?>" class="btn btn-outline-light">
                                <i class="fas fa-chart-area"></i> Market Analysis
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Commodities</h6>
                            <h3><?= count($commodities) ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-seedling fa-2x"></i>
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
                            <h6 class="card-title">Price Records</h6>
                            <h3><?= count($latestPrices) ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-database fa-2x"></i>
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
                            <h6 class="card-title">Market Types</h6>
                            <h3>4</h3>
                            <small>Local, Export, Wholesale, Retail</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-store fa-2x"></i>
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
                            <h6 class="card-title">High Volatility</h6>
                            <h3><?= count(array_filter($volatilityAnalysis, function($v) { return $v['volatility_percent'] > 20; })) ?></h3>
                            <small>Commodities >20% volatility</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row mb-4">
        <!-- Price Volatility Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-bar"></i> Price Volatility Analysis
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="volatilityChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Market Type Comparison -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-pie"></i> Average Prices by Market Type
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="marketTypeChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Price Trends Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-line"></i> Monthly Price Trends (Last 12 Months)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyTrendsChart" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="row mb-4">
        <!-- Latest Prices Table -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-table"></i> Latest Commodity Prices
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Commodity</th>
                                    <th>Market Type</th>
                                    <th>Price</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 1; ?>
                                <?php foreach ($latestPrices as $price): ?>
                                <tr>
                                    <td><?= $counter++ ?></td>
                                    <td>
                                        <strong><?= esc($price['commodity_name']) ?></strong>
                                        <br><small class="text-muted"><?= esc($price['commodity_code']) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $price['market_type'] == 'export' ? 'success' : ($price['market_type'] == 'wholesale' ? 'warning' : 'info') ?>">
                                            <?= ucfirst(esc($price['market_type'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong><?= esc($price['currency']) ?> <?= number_format($price['price_per_unit'], 2) ?></strong>
                                        <br><small class="text-muted">per <?= esc($price['unit_of_measurement']) ?></small>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($price['price_date'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($latestPrices)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No price data available</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Volatility Analysis Table -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-table"></i> Price Volatility Analysis
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Commodity</th>
                                    <th>Market</th>
                                    <th>Avg Price</th>
                                    <th>Volatility</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 1; ?>
                                <?php foreach ($volatilityAnalysis as $analysis): ?>
                                <tr>
                                    <td><?= $counter++ ?></td>
                                    <td><?= esc($analysis['commodity_name']) ?></td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= ucfirst(esc($analysis['market_type'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= esc($analysis['currency']) ?> <?= number_format($analysis['avg_price'], 2) ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $analysis['volatility_percent'] > 20 ? 'danger' : ($analysis['volatility_percent'] > 10 ? 'warning' : 'success') ?>">
                                            <?= number_format($analysis['volatility_percent'], 1) ?>%
                                        </span>
                                    </td>
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
    </div>

    <!-- Average Prices by Market Type Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-table"></i> Average Prices by Market Type (Last 6 Months)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Commodity</th>
                                    <th>Code</th>
                                    <th>Market Type</th>
                                    <th>Average Price</th>
                                    <th>Unit</th>
                                    <th>Price Records</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($averagePrices as $price): ?>
                                <tr>
                                    <td><strong><?= esc($price['commodity_name']) ?></strong></td>
                                    <td><code><?= esc($price['commodity_code']) ?></code></td>
                                    <td>
                                        <span class="badge bg-<?= $price['market_type'] == 'export' ? 'success' : ($price['market_type'] == 'wholesale' ? 'warning' : 'info') ?>">
                                            <?= ucfirst(esc($price['market_type'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong><?= esc($price['currency']) ?> <?= number_format($price['avg_price'], 2) ?></strong>
                                    </td>
                                    <td><?= esc($price['unit_of_measurement']) ?></td>
                                    <td><span class="badge bg-info"><?= $price['price_records'] ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($averagePrices)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No average price data available</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
    
    // Volatility Chart
    const volatilityCtx = document.getElementById('volatilityChart').getContext('2d');
    new Chart(volatilityCtx, {
        type: 'bar',
        data: {
            labels: chartData.volatility.labels,
            datasets: [{
                label: 'Volatility %',
                data: chartData.volatility.data,
                backgroundColor: chartData.volatility.data.map(v => v > 20 ? '#dc3545' : v > 10 ? '#ffc107' : '#28a745'),
                borderColor: chartData.volatility.data.map(v => v > 20 ? '#c82333' : v > 10 ? '#e0a800' : '#1e7e34'),
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
                        text: 'Volatility Percentage (%)'
                    }
                }
            }
        }
    });
    
    // Market Type Chart (if data exists)
    if (chartData.marketTypes && Object.keys(chartData.marketTypes).length > 0) {
        const marketTypeCtx = document.getElementById('marketTypeChart').getContext('2d');
        const marketTypeLabels = [];
        const marketTypeData = [];
        const marketTypeColors = ['#007bff', '#28a745', '#ffc107', '#dc3545'];
        
        Object.keys(chartData.marketTypes).forEach((marketType, index) => {
            marketTypeLabels.push(marketType);
            const avgPrice = chartData.marketTypes[marketType].reduce((sum, item) => sum + item.price, 0) / chartData.marketTypes[marketType].length;
            marketTypeData.push(avgPrice);
        });
        
        new Chart(marketTypeCtx, {
            type: 'doughnut',
            data: {
                labels: marketTypeLabels,
                datasets: [{
                    data: marketTypeData,
                    backgroundColor: marketTypeColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true
            }
        });
    }
    
    // Monthly Trends Chart (if data exists)
    if (chartData.monthlyTrends && Object.keys(chartData.monthlyTrends).length > 0) {
        const monthlyTrendsCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
        const datasets = [];
        const colors = ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14'];
        let colorIndex = 0;
        
        Object.keys(chartData.monthlyTrends).forEach(commodity => {
            const data = chartData.monthlyTrends[commodity];
            const sortedMonths = Object.keys(data).sort();
            const values = sortedMonths.map(month => data[month]);
            
            datasets.push({
                label: commodity,
                data: values,
                borderColor: colors[colorIndex % colors.length],
                backgroundColor: colors[colorIndex % colors.length] + '20',
                tension: 0.1
            });
            colorIndex++;
        });
        
        new Chart(monthlyTrendsCtx, {
            type: 'line',
            data: {
                labels: Object.keys(chartData.monthlyTrends[Object.keys(chartData.monthlyTrends)[0]] || {}).sort(),
                datasets: datasets
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Price (<?= CURRENCY_SYMBOL ?>)'
                        }
                    }
                }
            }
        });
    }
});
</script>

<?= $this->endSection() ?>
