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
                                <i class="fas fa-seedling"></i> Commodity Production Reports
                            </h4>
                            <p class="card-text mb-0">Comprehensive analysis of commodity production data with visual charts, trends, and detailed statistics.</p>
                        </div>
                        <div>
                            <button onclick="AMISPdf.generateCommodityReportPDF()" class="btn btn-light">
                                <i class="fas fa-file-pdf me-1"></i> Export PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Records</h6>
                            <h3><?= count($productions) ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-database fa-2x"></i>
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
                            <h6 class="card-title">Active Commodities</h6>
                            <h3><?= count(array_unique(array_column($productions, 'commodity_id'))) ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-boxes fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Exported</h6>
                            <h3><?= count($exportedProduction) ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-shipping-fast fa-2x"></i>
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
                            <h6 class="card-title">Domestic</h6>
                            <h3><?= count($domesticProduction) ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-home fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <!-- Production by Commodity Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Production by Commodity</h6>
                </div>
                <div class="card-body">
                    <canvas id="commodityProductionChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Export vs Domestic Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Export vs Domestic Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="exportVsDomesticChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trends and Top Commodities -->
    <div class="row mb-4">
        <!-- Monthly Production Trends -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Monthly Production Trends (<?= date('Y') ?>)</h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyTrendsChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Commodities -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Top Producing Commodities</h6>
                </div>
                <div class="card-body">
                    <canvas id="topCommoditiesChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Quarterly Analysis and Unit Distribution -->
    <div class="row mb-4">
        <!-- Quarterly Production -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Quarterly Production Analysis</h6>
                </div>
                <div class="card-body">
                    <canvas id="quarterlyChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Production by Unit -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Production by Unit of Measurement</h6>
                </div>
                <div class="card-body">
                    <canvas id="unitProductionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Trends -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Export vs Domestic Production Trends</h6>
                </div>
                <div class="card-body">
                    <canvas id="exportTrendsChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Production Summary Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Production Summary by Commodity</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Commodity</th>
                                    <th>Code</th>
                                    <th>Total Quantity</th>
                                    <th>Unit</th>
                                    <th>Records</th>
                                    <th>Average per Record</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 1; ?>
                                <?php foreach ($productionSummary as $summary): ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><?= esc($summary['commodity_name']) ?></td>
                                        <td>
                                            <span class="badge bg-secondary"><?= esc($summary['commodity_code']) ?></span>
                                        </td>
                                        <td><strong><?= number_format($summary['total_quantity'], 2) ?></strong></td>
                                        <td><?= esc($summary['unit_of_measurement']) ?></td>
                                        <td><?= $summary['record_count'] ?></td>
                                        <td><?= number_format($summary['total_quantity'] / $summary['record_count'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Production Records -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Recent Production Records (Last 20)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Commodity</th>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Period</th>
                                    <th>Export Status</th>
                                    <th>Created</th>
                                    <th>Created By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 1; ?>
                                <?php foreach (array_slice($productions, 0, 20) as $production): ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td>
                                            <span class="badge bg-secondary"><?= esc($production['commodity_code']) ?></span>
                                            <br><small><?= esc($production['commodity_name']) ?></small>
                                        </td>
                                        <td>
                                            <strong><?= esc($production['item']) ?></strong>
                                            <?php if (!empty($production['description'])): ?>
                                                <br><small class="text-muted"><?= esc(substr($production['description'], 0, 50)) ?><?= strlen($production['description']) > 50 ? '...' : '' ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><strong><?= number_format($production['quantity'], 2) ?></strong></td>
                                        <td><?= esc($production['unit_of_measurement']) ?></td>
                                        <td>
                                            <small>
                                                <?= date('M d', strtotime($production['date_from'])) ?> -
                                                <?= date('M d, Y', strtotime($production['date_to'])) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php if ($production['is_exported']): ?>
                                                <span class="badge bg-success">Export</span>
                                            <?php else: ?>
                                                <span class="badge bg-info">Domestic</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small><?= date('M d, Y', strtotime($production['created_at'])) ?></small>
                                        </td>
                                        <td>
                                            <small><?= esc($production['created_by_name']) ?></small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart color scheme
    const colors = {
        primary: '#007bff',
        secondary: '#6c757d',
        success: '#28a745',
        danger: '#dc3545',
        warning: '#ffc107',
        info: '#17a2b8',
        light: '#f8f9fa',
        dark: '#343a40',
        chartColors: [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF',
            '#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56'
        ]
    };

    // Get chart data from PHP
    const chartData = <?= json_encode($chartData) ?>;

    // 1. Production by Commodity Chart (Pie)
    const commodityProductionCtx = document.getElementById('commodityProductionChart').getContext('2d');
    const commodityLabels = Object.keys(chartData.commodityProduction || {});
    const commodityValues = Object.values(chartData.commodityProduction || {});

    new Chart(commodityProductionCtx, {
        type: 'pie',
        data: {
            labels: commodityLabels,
            datasets: [{
                data: commodityValues,
                backgroundColor: colors.chartColors.slice(0, commodityLabels.length),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Total Production by Commodity'
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // 2. Export vs Domestic Chart (Doughnut)
    const exportVsDomesticCtx = document.getElementById('exportVsDomesticChart').getContext('2d');
    const exportData = chartData.exportVsDomestic || {};

    new Chart(exportVsDomesticCtx, {
        type: 'doughnut',
        data: {
            labels: ['Exported', 'Domestic'],
            datasets: [{
                data: [exportData.exported || 0, exportData.domestic || 0],
                backgroundColor: [colors.success, colors.info],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Export vs Domestic Distribution'
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // 3. Monthly Production Trends (Line)
    const monthlyTrendsCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
    const monthlyData = chartData.monthlyProduction || [];

    new Chart(monthlyTrendsCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Total Production',
                data: monthlyData,
                backgroundColor: colors.primary + '20',
                borderColor: colors.primary,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Production Trends (' + new Date().getFullYear() + ')'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat().format(value);
                        }
                    }
                }
            }
        }
    });

    // 4. Top Commodities Chart (Horizontal Bar)
    const topCommoditiesCtx = document.getElementById('topCommoditiesChart').getContext('2d');
    const topCommoditiesData = chartData.topCommodities || {};
    const topLabels = Object.keys(topCommoditiesData).slice(0, 5);
    const topValues = Object.values(topCommoditiesData).slice(0, 5);

    new Chart(topCommoditiesCtx, {
        type: 'bar',
        data: {
            labels: topLabels,
            datasets: [{
                label: 'Total Production',
                data: topValues,
                backgroundColor: colors.success,
                borderColor: colors.success,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                title: {
                    display: true,
                    text: 'Top 5 Producing Commodities'
                },
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat().format(value);
                        }
                    }
                }
            }
        }
    });

    // 5. Quarterly Production Chart (Bar)
    const quarterlyCtx = document.getElementById('quarterlyChart').getContext('2d');
    const quarterlyData = chartData.quarterlyProduction || [];

    new Chart(quarterlyCtx, {
        type: 'bar',
        data: {
            labels: ['Q1', 'Q2', 'Q3', 'Q4'],
            datasets: [{
                label: 'Production',
                data: quarterlyData,
                backgroundColor: [colors.primary, colors.success, colors.warning, colors.info],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Quarterly Production Analysis'
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat().format(value);
                        }
                    }
                }
            }
        }
    });

    // 6. Production by Unit Chart (Pie)
    const unitProductionCtx = document.getElementById('unitProductionChart').getContext('2d');
    const unitData = chartData.unitProduction || {};
    const unitLabels = Object.keys(unitData);
    const unitValues = Object.values(unitData);

    new Chart(unitProductionCtx, {
        type: 'pie',
        data: {
            labels: unitLabels,
            datasets: [{
                data: unitValues,
                backgroundColor: colors.chartColors.slice(0, unitLabels.length),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Production by Unit of Measurement'
                },
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // 7. Export Trends Chart (Multi-line)
    const exportTrendsCtx = document.getElementById('exportTrendsChart').getContext('2d');
    const exportTrendsData = chartData.exportTrends || {};

    new Chart(exportTrendsCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Exported Production',
                data: exportTrendsData.exported || [],
                backgroundColor: colors.success + '20',
                borderColor: colors.success,
                tension: 0.4,
                fill: false
            }, {
                label: 'Domestic Production',
                data: exportTrendsData.domestic || [],
                backgroundColor: colors.info + '20',
                borderColor: colors.info,
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Export vs Domestic Production Trends'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat().format(value);
                        }
                    }
                }
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
