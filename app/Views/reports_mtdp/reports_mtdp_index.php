<?php
// app/Views/reports_mtdp/reports_mtdp_index.php

/**
 * MTDP Plans Report - Index View
 *
 * @var array $plans
 * @var array $spas
 * @var array $dips
 * @var array $kras
 * @var array $specific_areas
 * @var array $investments
 * @var array $strategies
 * @var array $indicators
 * @var array $chartData
 * @var string $title
 */
?>
<?= $this->extend('templates/system_template') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">MTDP Plans Report</h4>
                            <p class="card-text mb-0">This report displays all MTDP plans and their related entities with visual charts and graphs.</p>
                        </div>
                        <div>
                            <button onclick="AMISPdf.generateMTDPReportPDF()" class="btn btn-light">
                                <i class="fas fa-file-pdf me-1"></i> Export PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <!-- Yearly Investment Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header"><strong>Yearly Investment Distribution</strong></div>
                <div class="card-body">
                    <canvas id="yearlyInvestmentChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Status Distribution Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header"><strong>Status Distribution</strong></div>
                <div class="card-body">
                    <canvas id="statusDistributionChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- DIP Investment Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header"><strong>Investment by DIP</strong></div>
                <div class="card-body">
                    <canvas id="dipInvestmentChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Entities by Plan Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header"><strong>Entities by MTDP Plan</strong></div>
                <div class="card-body">
                    <canvas id="entitiesByPlanChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- MTDP Plans Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>MTDP Plans</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Abbreviation</th>
                                <th>Title</th>
                                <th>Date From</th>
                                <th>Date To</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($plans as $plan): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td><?= esc($plan['abbrev']) ?></td>
                                <td><?= esc($plan['title']) ?></td>
                                <td><?= esc($plan['date_from']) ?></td>
                                <td><?= esc($plan['date_to']) ?></td>
                                <td><?= esc($plan['mtdp_status']) == 1 ? 'Active' : 'Inactive' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SPAs Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Strategic Priority Areas (SPAs)</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>MTDP</th>
                                <th>Code</th>
                                <th>Title</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($spas as $spa): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td>
                                    <?php
                                    foreach ($plans as $plan) {
                                        if ($plan['id'] == $spa['mtdp_id']) {
                                            echo esc($plan['abbrev']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?= esc($spa['code']) ?></td>
                                <td><?= esc($spa['title']) ?></td>
                                <td><?= esc($spa['spa_status']) == 1 ? 'Active' : 'Inactive' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- DIPs Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Development Investment Plans (DIPs)</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>MTDP</th>
                                <th>SPA</th>
                                <th>Code</th>
                                <th>Title</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($dips as $dip): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td>
                                    <?php
                                    foreach ($plans as $plan) {
                                        if ($plan['id'] == $dip['mtdp_id']) {
                                            echo esc($plan['abbrev']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    foreach ($spas as $spa) {
                                        if ($spa['id'] == $dip['spa_id']) {
                                            echo esc($spa['code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?= esc($dip['dip_code']) ?></td>
                                <td><?= esc($dip['dip_title']) ?></td>
                                <td><?= esc($dip['dip_status']) == 1 ? 'Active' : 'Inactive' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- KRAs Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Key Result Areas (KRAs)</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>MTDP</th>
                                <th>SPA</th>
                                <th>DIP</th>
                                <th>KPI</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($kras as $kra): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td>
                                    <?php
                                    foreach ($plans as $plan) {
                                        if ($plan['id'] == $kra['mtdp_id']) {
                                            echo esc($plan['abbrev']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    foreach ($spas as $spa) {
                                        if ($spa['id'] == $kra['spa_id']) {
                                            echo esc($spa['code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    foreach ($dips as $dip) {
                                        if ($dip['id'] == $kra['dip_id']) {
                                            echo esc($dip['dip_code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?= esc($kra['kpi']) ?></td>
                                <td><?= esc($kra['kra_status']) == 1 ? 'Active' : 'Inactive' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Specific Areas Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Specific Areas</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>MTDP</th>
                                <th>SPA</th>
                                <th>DIP</th>
                                <th>Code</th>
                                <th>Title</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($specific_areas as $sa): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td>
                                    <?php
                                    foreach ($plans as $plan) {
                                        if ($plan['id'] == $sa['mtdp_id']) {
                                            echo esc($plan['abbrev']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    foreach ($spas as $spa) {
                                        if ($spa['id'] == $sa['spa_id']) {
                                            echo esc($spa['code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    foreach ($dips as $dip) {
                                        if ($dip['id'] == $sa['dip_id']) {
                                            echo esc($dip['dip_code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?= esc($sa['sa_code']) ?></td>
                                <td><?= esc($sa['sa_title']) ?></td>
                                <td><?= esc($sa['sa_status']) == 1 ? 'Active' : 'Inactive' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Investments Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Investments</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>MTDP</th>
                                <th>SPA</th>
                                <th>DIP</th>
                                <th>Investment</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($investments as $inv): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td>
                                    <?php
                                    foreach ($plans as $plan) {
                                        if ($plan['id'] == $inv['mtdp_id']) {
                                            echo esc($plan['abbrev']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    foreach ($spas as $spa) {
                                        if ($spa['id'] == $inv['spa_id']) {
                                            echo esc($spa['code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    foreach ($dips as $dip) {
                                        if ($dip['id'] == $inv['dip_id']) {
                                            echo esc($dip['dip_code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?= esc($inv['investment']) ?></td>
                                <td>
                                    <?php
                                    $total = (float)($inv['year_one'] ?? 0) +
                                             (float)($inv['year_two'] ?? 0) +
                                             (float)($inv['year_three'] ?? 0) +
                                             (float)($inv['year_four'] ?? 0) +
                                             (float)($inv['year_five'] ?? 0);
                                    echo number_format($total, 2);
                                    ?>
                                </td>
                                <td><?= esc($inv['investment_status']) == 1 ? 'Active' : 'Inactive' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Strategies Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Strategies</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>MTDP</th>
                                <th>SPA</th>
                                <th>DIP</th>
                                <th>KRA</th>
                                <th>Strategy</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($strategies as $strategy): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td>
                                    <?php
                                    foreach ($plans as $plan) {
                                        if ($plan['id'] == $strategy['mtdp_id']) {
                                            echo esc($plan['abbrev']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    foreach ($spas as $spa) {
                                        if ($spa['id'] == $strategy['spa_id']) {
                                            echo esc($spa['code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    foreach ($dips as $dip) {
                                        if ($dip['id'] == $strategy['dip_id']) {
                                            echo esc($dip['dip_code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    foreach ($kras as $kra) {
                                        if ($kra['id'] == $strategy['kra_id']) {
                                            // Display a shortened version of the KPI if it's too long
                                            $kpi = $kra['kpi'];
                                            echo strlen($kpi) > 30 ? substr($kpi, 0, 30) . '...' : $kpi;
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?= esc($strategy['strategy']) ?></td>
                                <td><?= esc($strategy['strategies_status']) == 1 ? 'Active' : 'Inactive' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicators Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Indicators</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>MTDP</th>
                                <th>SPA</th>
                                <th>DIP</th>
                                <th>KRA</th>
                                <th>Indicator</th>
                                <th>Baseline</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($indicators as $indicator): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td>
                                    <?php
                                    foreach ($plans as $plan) {
                                        if ($plan['id'] == $indicator['mtdp_id']) {
                                            echo esc($plan['abbrev']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    foreach ($spas as $spa) {
                                        if ($spa['id'] == $indicator['spa_id']) {
                                            echo esc($spa['code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    foreach ($dips as $dip) {
                                        if ($dip['id'] == $indicator['dip_id']) {
                                            echo esc($dip['dip_code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if (!empty($indicator['kra_id'])) {
                                        foreach ($kras as $kra) {
                                            if ($kra['id'] == $indicator['kra_id']) {
                                                // Display a shortened version of the KPI if it's too long
                                                $kpi = $kra['kpi'];
                                                echo strlen($kpi) > 30 ? substr($kpi, 0, 30) . '...' : $kpi;
                                                break;
                                            }
                                        }
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td><?= esc($indicator['indicator']) ?></td>
                                <td><?= esc($indicator['baseline'] ?? '-') ?></td>
                                <td><?= esc($indicator['indicators_status']) == 1 ? 'Active' : 'Inactive' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Hierarchy/Mind Map Chart (Simple Nested List) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>MTDP Hierarchy (Mind Map)</strong></div>
                <div class="card-body">
                    <ul>
                        <?php foreach ($plans as $plan): ?>
                            <li>
                                <strong><?= esc($plan['title']) ?></strong>
                                <ul>
                                    <?php foreach ($spas as $spa): if ($spa['mtdp_id'] == $plan['id']): ?>
                                        <li>
                                            <strong>SPA:</strong> <?= esc($spa['title']) ?>
                                            <ul>
                                                <?php foreach ($dips as $dip): if ($dip['spa_id'] == $spa['id']): ?>
                                                    <li>
                                                        <strong>DIP:</strong> <?= esc($dip['dip_title']) ?>
                                                        <ul>
                                                            <?php foreach ($kras as $kra): if ($kra['dip_id'] == $dip['id']): ?>
                                                                <li>
                                                                    <strong>KRA:</strong> <?= esc($kra['kpi']) ?>
                                                                    <ul>
                                                                        <?php foreach ($strategies as $strategy): if ($strategy['kra_id'] == $kra['id']): ?>
                                                                            <li><strong>Strategy:</strong> <?= esc($strategy['strategy']) ?></li>
                                                                        <?php endif; endforeach; ?>
                                                                        <?php foreach ($indicators as $indicator): if (($indicator['kra_id'] ?? null) == $kra['id']): ?>
                                                                            <li><strong>Indicator:</strong> <?= esc($indicator['indicator']) ?></li>
                                                                        <?php endif; endforeach; ?>
                                                                    </ul>
                                                                </li>
                                                            <?php endif; endforeach; ?>
                                                            <?php foreach ($specific_areas as $sa): if ($sa['dip_id'] == $dip['id']): ?>
                                                                <li><strong>Specific Area:</strong> <?= esc($sa['sa_title']) ?></li>
                                                            <?php endif; endforeach; ?>
                                                            <?php foreach ($investments as $inv): if ($inv['dip_id'] == $dip['id']): ?>
                                                                <li><strong>Investment:</strong> <?= esc($inv['investment']) ?></li>
                                                            <?php endif; endforeach; ?>
                                                        </ul>
                                                    </li>
                                                <?php endif; endforeach; ?>
                                            </ul>
                                        </li>
                                    <?php endif; endforeach; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
    $(document).ready(function() {
        // Chart color palette
        const colors = {
            primary: '#6ba84f',
            secondary: '#1a237e',
            success: '#43a047',
            warning: '#fdd835',
            danger: '#e53935',
            info: '#29b6f6',
            light: '#f5f7fa',
            dark: '#2c3e50',
            chartColors: [
                '#6ba84f', '#1a237e', '#43a047', '#fdd835',
                '#e53935', '#29b6f6', '#9c27b0', '#ff9800',
                '#795548', '#607d8b', '#3f51b5', '#009688'
            ]
        };

        // 1. Yearly Investment Chart
        const yearlyInvestmentCtx = document.getElementById('yearlyInvestmentChart').getContext('2d');
        const yearlyInvestmentData = <?= json_encode($chartData['yearlyInvestments'] ?? []) ?>;

        new Chart(yearlyInvestmentCtx, {
            type: 'bar',
            data: {
                labels: ['Year 1', 'Year 2', 'Year 3', 'Year 4', 'Year 5'],
                datasets: [{
                    label: 'Investment Amount',
                    data: [
                        yearlyInvestmentData.year_one || 0,
                        yearlyInvestmentData.year_two || 0,
                        yearlyInvestmentData.year_three || 0,
                        yearlyInvestmentData.year_four || 0,
                        yearlyInvestmentData.year_five || 0
                    ],
                    backgroundColor: colors.chartColors.slice(0, 5),
                    borderColor: colors.chartColors.slice(0, 5),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Investment Distribution by Year'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('en-US', {
                                        style: 'currency',
                                        currency: 'USD',
                                        minimumFractionDigits: 2
                                    }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: 'USD',
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                }).format(value);
                            }
                        }
                    }
                }
            }
        });

        // 2. Status Distribution Chart
        const statusDistributionCtx = document.getElementById('statusDistributionChart').getContext('2d');
        const statusData = <?= json_encode($chartData['statusCounts'] ?? []) ?>;

        const statusLabels = ['DIPs', 'KRAs', 'Specific Areas', 'Strategies', 'Indicators'];
        const activeData = [
            statusData.dips?.active || 0,
            statusData.kras?.active || 0,
            statusData.specific_areas?.active || 0,
            statusData.strategies?.active || 0,
            statusData.indicators?.active || 0
        ];
        const inactiveData = [
            statusData.dips?.inactive || 0,
            statusData.kras?.inactive || 0,
            statusData.specific_areas?.inactive || 0,
            statusData.strategies?.inactive || 0,
            statusData.indicators?.inactive || 0
        ];

        new Chart(statusDistributionCtx, {
            type: 'bar',
            data: {
                labels: statusLabels,
                datasets: [
                    {
                        label: 'Active',
                        data: activeData,
                        backgroundColor: colors.success,
                        borderColor: colors.success,
                        borderWidth: 1
                    },
                    {
                        label: 'Inactive',
                        data: inactiveData,
                        backgroundColor: colors.danger,
                        borderColor: colors.danger,
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Status Distribution by Entity Type'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // 3. DIP Investment Chart
        const dipInvestmentCtx = document.getElementById('dipInvestmentChart').getContext('2d');
        const dipInvestmentData = <?= json_encode($chartData['dipInvestments'] ?? []) ?>;

        // Convert object to arrays for Chart.js
        const dipLabels = [];
        const dipValues = [];

        Object.keys(dipInvestmentData).forEach(key => {
            if (dipInvestmentData[key].total > 0) {
                dipLabels.push(dipInvestmentData[key].title);
                dipValues.push(dipInvestmentData[key].total);
            }
        });

        new Chart(dipInvestmentCtx, {
            type: 'pie',
            data: {
                labels: dipLabels,
                datasets: [{
                    data: dipValues,
                    backgroundColor: colors.chartColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Investment Distribution by DIP'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.raw !== null) {
                                    label += new Intl.NumberFormat('en-US', {
                                        style: 'currency',
                                        currency: 'USD',
                                        minimumFractionDigits: 2
                                    }).format(context.raw);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // 4. Entities by Plan Chart
        const entitiesByPlanCtx = document.getElementById('entitiesByPlanChart').getContext('2d');
        const entitiesByPlanData = <?= json_encode($chartData['entitiesByPlan'] ?? []) ?>;

        // Convert object to arrays for Chart.js
        const planLabels = [];
        const planDatasets = [
            { label: 'SPAs', data: [], backgroundColor: colors.chartColors[0] },
            { label: 'DIPs', data: [], backgroundColor: colors.chartColors[1] },
            { label: 'KRAs', data: [], backgroundColor: colors.chartColors[2] },
            { label: 'Specific Areas', data: [], backgroundColor: colors.chartColors[3] },
            { label: 'Investments', data: [], backgroundColor: colors.chartColors[4] },
            { label: 'Strategies', data: [], backgroundColor: colors.chartColors[5] },
            { label: 'Indicators', data: [], backgroundColor: colors.chartColors[6] }
        ];

        Object.keys(entitiesByPlanData).forEach(key => {
            const plan = entitiesByPlanData[key];
            planLabels.push(plan.title);
            planDatasets[0].data.push(plan.spas);
            planDatasets[1].data.push(plan.dips);
            planDatasets[2].data.push(plan.kras);
            planDatasets[3].data.push(plan.specific_areas);
            planDatasets[4].data.push(plan.investments);
            planDatasets[5].data.push(plan.strategies);
            planDatasets[6].data.push(plan.indicators);
        });

        new Chart(entitiesByPlanCtx, {
            type: 'bar',
            data: {
                labels: planLabels,
                datasets: planDatasets
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Entity Counts by MTDP Plan'
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>