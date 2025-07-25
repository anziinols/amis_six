<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">
                                <i class="fas fa-sitemap"></i> Government Structure Reports Dashboard
                            </h4>
                            <p class="card-text mb-0">Comprehensive analytics on PNG administrative hierarchy, structure utilization, activity distribution, and administrative efficiency.</p>
                        </div>
                        <div>
                            <a href="<?= base_url('reports/gov-structure/export-pdf') ?>" class="btn btn-light">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hierarchy Overview Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Provinces</h6>
                            <h3><?= $hierarchyData['provinces'] ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-map fa-2x"></i>
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
                            <h6 class="card-title">Districts</h6>
                            <h3><?= $hierarchyData['districts'] ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-map-marked-alt fa-2x"></i>
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
                            <h6 class="card-title">LLGs</h6>
                            <h3><?= $hierarchyData['llgs'] ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-map-marker-alt fa-2x"></i>
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
                            <h6 class="card-title">Wards</h6>
                            <h3><?= $hierarchyData['wards'] ?></h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-location-arrow fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row mb-4">
        <!-- Hierarchy Overview Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-bar"></i> Administrative Hierarchy Overview
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="hierarchyChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Activity Distribution by Type -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-pie"></i> Activity Distribution by Type
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="activityTypeChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row mb-4">
        <!-- Province Coverage Analysis -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-doughnut"></i> Province Activity Coverage
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="provinceCoverageChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- District Coverage Analysis -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-doughnut"></i> District Activity Coverage
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="districtCoverageChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Distribution by Province Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-line"></i> Activity Distribution by Province
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="activityProvinceChart" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Administrative Efficiency Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-chart-area"></i> Administrative Efficiency (Activity Density per Province)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="efficiencyChart" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="row mb-4">
        <!-- Province Breakdown Table -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-table"></i> Province Administrative Breakdown
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Province</th>
                                    <th>Districts</th>
                                    <th>LLGs</th>
                                    <th>Wards</th>
                                    <th>Total Units</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 1; ?>
                                <?php foreach ($hierarchyData['provinceBreakdown'] as $province): ?>
                                <tr>
                                    <td><?= $counter++ ?></td>
                                    <td><?= esc($province['province']['name']) ?></td>
                                    <td><span class="badge bg-primary"><?= $province['districts'] ?></span></td>
                                    <td><span class="badge bg-success"><?= $province['llgs'] ?></span></td>
                                    <td><span class="badge bg-warning"><?= $province['wards'] ?></span></td>
                                    <td><strong><?= $province['total'] ?></strong></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Distribution by Province Table -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-table"></i> Activities by Province
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Province</th>
                                    <th>Code</th>
                                    <th>Activities</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 1; ?>
                                <?php foreach ($activityDistribution['byProvince'] as $province): ?>
                                <tr>
                                    <td><?= $counter++ ?></td>
                                    <td><?= esc($province['province_name']) ?></td>
                                    <td><code><?= esc($province['province_code']) ?></code></td>
                                    <td><span class="badge bg-info"><?= $province['activity_count'] ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Most Active Areas Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-trophy"></i> Most Active Areas (Top 10)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Province</th>
                                    <th>District</th>
                                    <th>Activity Count</th>
                                    <th>Activity Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $rank = 1; ?>
                                <?php foreach ($efficiencyData['mostActive'] as $area): ?>
                                <tr>
                                    <td>
                                        <?php if ($rank <= 3): ?>
                                            <span class="badge bg-warning"><?= $rank ?></span>
                                        <?php else: ?>
                                            <?= $rank ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($area['province_name']) ?></td>
                                    <td><?= esc($area['district_name']) ?></td>
                                    <td><span class="badge bg-success"><?= $area['activity_count'] ?></span></td>
                                    <td>
                                        <?php if ($area['activity_count'] >= 10): ?>
                                            <span class="badge bg-success">Very High</span>
                                        <?php elseif ($area['activity_count'] >= 5): ?>
                                            <span class="badge bg-warning">High</span>
                                        <?php elseif ($area['activity_count'] >= 2): ?>
                                            <span class="badge bg-info">Medium</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Low</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $rank++; ?>
                                <?php endforeach; ?>
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
    
    // Hierarchy Overview Chart
    const hierarchyCtx = document.getElementById('hierarchyChart').getContext('2d');
    new Chart(hierarchyCtx, {
        type: 'bar',
        data: {
            labels: chartData.hierarchy.labels,
            datasets: [{
                label: 'Administrative Units',
                data: chartData.hierarchy.data,
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
                borderColor: ['#0056b3', '#1e7e34', '#e0a800', '#c82333'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Activity Type Distribution Chart
    const activityTypeCtx = document.getElementById('activityTypeChart').getContext('2d');
    new Chart(activityTypeCtx, {
        type: 'pie',
        data: {
            labels: chartData.activityByType.labels,
            datasets: [{
                data: chartData.activityByType.data,
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
    
    // Province Coverage Chart
    const provinceCoverageCtx = document.getElementById('provinceCoverageChart').getContext('2d');
    new Chart(provinceCoverageCtx, {
        type: 'doughnut',
        data: {
            labels: chartData.provinceCoverage.labels,
            datasets: [{
                data: chartData.provinceCoverage.data,
                backgroundColor: ['#28a745', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
    
    // District Coverage Chart
    const districtCoverageCtx = document.getElementById('districtCoverageChart').getContext('2d');
    new Chart(districtCoverageCtx, {
        type: 'doughnut',
        data: {
            labels: chartData.districtCoverage.labels,
            datasets: [{
                data: chartData.districtCoverage.data,
                backgroundColor: ['#28a745', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
    
    // Activity by Province Chart
    const activityProvinceCtx = document.getElementById('activityProvinceChart').getContext('2d');
    new Chart(activityProvinceCtx, {
        type: 'line',
        data: {
            labels: chartData.activityByProvince.labels,
            datasets: [{
                label: 'Activities',
                data: chartData.activityByProvince.data,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Efficiency Chart
    const efficiencyCtx = document.getElementById('efficiencyChart').getContext('2d');
    new Chart(efficiencyCtx, {
        type: 'bar',
        data: {
            labels: chartData.efficiency.labels,
            datasets: [{
                label: 'Activity Density',
                data: chartData.efficiency.data,
                backgroundColor: 'rgba(40, 167, 69, 0.8)',
                borderColor: '#28a745',
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
                        text: 'Activities per Administrative Unit'
                    }
                }
            }
        }
    });
});
</script>

<?= $this->endSection() ?>
