<?php
// app/Views/report_nasp/report_nasp_index.php

/**
 * NASP Plans Report - Index View
 *
 * @var array $plans
 * @var array $apas
 * @var array $dips
 * @var array $specificAreas
 * @var array $outputs
 * @var array $indicators
 * @var array $chartData
 * @var string $title
 */
?>
<?= $this->extend('templates/system_template') ?>

<?= $this->section('head') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">NASP Plans Report</h4>
                            <p class="card-text mb-0">This report displays all NASP plans and their related entities with visual charts and graphs.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <strong>Filter by Date Range</strong>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?= base_url('reports/nasp') ?>" class="row g-3">
                        <div class="col-md-4">
                            <label for="date_from" class="form-label">Date From</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="<?= esc($dateFrom ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="date_to" class="form-label">Date To</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="<?= esc($dateTo ?? '') ?>">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter me-1"></i> Apply Filter
                            </button>
                            <a href="<?= base_url('reports/nasp') ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <!-- Status Distribution Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Status Distribution</strong>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyChartAsImage('statusDistributionChart')">
                        <i class="fas fa-copy"></i> Copy Chart
                    </button>
                </div>
                <div class="card-body">
                    <canvas id="statusDistributionChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Entities by Plan Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Entities by NASP Plan</strong>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyChartAsImage('entitiesByPlanChart')">
                        <i class="fas fa-copy"></i> Copy Chart
                    </button>
                </div>
                <div class="card-body">
                    <canvas id="entitiesByPlanChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Outputs by APAs Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Outputs by APAs</strong>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyChartAsImage('outputsByApasChart')">
                        <i class="fas fa-copy"></i> Copy Chart
                    </button>
                </div>
                <div class="card-body">
                    <canvas id="outputsByApasChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- DIPs by APA Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>DIPs by APA</strong>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyChartAsImage('dipsByApaChart')">
                        <i class="fas fa-copy"></i> Copy Chart
                    </button>
                </div>
                <div class="card-body">
                    <canvas id="dipsByApaChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- NASP Plans Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>NASP Plans</strong></div>
                <div class="card-body" style="overflow-x: auto;">
                    <table id="plansTable" class="table table-bordered table-striped table-sm" style="min-width: 100%; white-space: nowrap;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Title</th>
                                <th>Date From</th>
                                <th>Date To</th>
                                <th>Status</th>
                                <th>Activities</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($plans as $plan): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td><?= esc($plan['code']) ?></td>
                                <td><?= esc($plan['title']) ?></td>
                                <td><?= esc($plan['date_from']) ?></td>
                                <td><?= esc($plan['date_to']) ?></td>
                                <td><?= esc($plan['nasp_status']) == 1 ? 'Active' : 'Inactive' ?></td>
                                <td><?= $plan['activity_count'] ?? 0 ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- APAs Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Agriculture Priority Areas (APAs)</strong></div>
                <div class="card-body" style="overflow-x: auto;">
                    <table id="apasTable" class="table table-bordered table-striped table-sm" style="min-width: 100%; white-space: nowrap;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NASP Plan</th>
                                <th>Code</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Activities</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($apas as $apa): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td>
                                    <?php
                                    foreach ($plans as $plan) {
                                        if ($plan['id'] == $apa['parent_id']) {
                                            echo esc($plan['code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?= esc($apa['code']) ?></td>
                                <td><?= esc($apa['title']) ?></td>
                                <td><?= esc($apa['nasp_status']) == 1 ? 'Active' : 'Inactive' ?></td>
                                <td><?= $apa['activity_count'] ?? 0 ?></td>
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
                <div class="card-header"><strong>Deliberate Intervention Programs (DIPs)</strong></div>
                <div class="card-body" style="overflow-x: auto;">
                    <table id="dipsTable" class="table table-bordered table-striped table-sm" style="min-width: 100%; white-space: nowrap;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>APA</th>
                                <th>Code</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Activities</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($dips as $dip): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td>
                                    <?php
                                    foreach ($apas as $apa) {
                                        if ($apa['id'] == $dip['parent_id']) {
                                            echo esc($apa['code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?= esc($dip['code']) ?></td>
                                <td><?= esc($dip['title']) ?></td>
                                <td><?= esc($dip['nasp_status']) == 1 ? 'Active' : 'Inactive' ?></td>
                                <td><?= $dip['activity_count'] ?? 0 ?></td>
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
                <div class="card-body" style="overflow-x: auto;">
                    <table id="specificAreasTable" class="table table-bordered table-striped table-sm" style="min-width: 100%; white-space: nowrap;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>DIP</th>
                                <th>Code</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Activities</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($specificAreas as $sa): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td>
                                    <?php
                                    foreach ($dips as $dip) {
                                        if ($dip['id'] == $sa['parent_id']) {
                                            echo esc($dip['code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?= esc($sa['code']) ?></td>
                                <td><?= esc($sa['title']) ?></td>
                                <td><?= esc($sa['nasp_status']) == 1 ? 'Active' : 'Inactive' ?></td>
                                <td><?= $sa['activity_count'] ?? 0 ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Objectives Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Objectives</strong></div>
                <div class="card-body" style="overflow-x: auto;">
                    <table id="objectivesTable" class="table table-bordered table-striped table-sm" style="min-width: 100%; white-space: nowrap;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Specific Area</th>
                                <th>Code</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Activities</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($objectives as $objective): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td>
                                    <?php
                                    foreach ($specificAreas as $sa) {
                                        if ($sa['id'] == $objective['parent_id']) {
                                            echo esc($sa['code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?= esc($objective['code']) ?></td>
                                <td><?= esc($objective['title']) ?></td>
                                <td><?= esc($objective['nasp_status']) == 1 ? 'Active' : 'Inactive' ?></td>
                                <td><?= $objective['activity_count'] ?? 0 ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Outputs Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Outputs</strong></div>
                <div class="card-body" style="overflow-x: auto;">
                    <table id="outputsTable" class="table table-bordered table-striped table-sm" style="min-width: 100%; white-space: nowrap;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Objective</th>
                                <th>Code</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Activities</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($outputs as $output): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td>
                                    <?php
                                    foreach ($objectives as $objective) {
                                        if ($objective['id'] == $output['parent_id']) {
                                            echo esc($objective['code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?= esc($output['code']) ?></td>
                                <td><?= esc($output['title']) ?></td>
                                <td><?= esc($output['nasp_status']) == 1 ? 'Active' : 'Inactive' ?></td>
                                <td><?= $output['activity_count'] ?? 0 ?></td>
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
                <div class="card-body" style="overflow-x: auto;">
                    <table id="indicatorsTable" class="table table-bordered table-striped table-sm" style="min-width: 100%; white-space: nowrap;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Output</th>
                                <th>Code</th>
                                <th>Title</th>
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
                                    foreach ($outputs as $output) {
                                        if ($output['id'] == $indicator['parent_id']) {
                                            echo esc($output['code']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?= esc($indicator['code']) ?></td>
                                <td><?= esc($indicator['title']) ?></td>
                                <td><?= esc($indicator['nasp_status']) == 1 ? 'Active' : 'Inactive' ?></td>
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
                <div class="card-header"><strong>NASP Hierarchy (Mind Map)</strong></div>
                <div class="card-body">
                    <ul>
                        <?php foreach ($plans as $plan): ?>
                            <li>
                                <strong><?= esc($plan['title']) ?> (<?= esc($plan['code']) ?>)</strong>
                                <ul>
                                    <?php foreach ($apas as $apa): if ($apa['parent_id'] == $plan['id']): ?>
                                        <li>
                                            <strong>APA:</strong> <?= esc($apa['title']) ?> (<?= esc($apa['code']) ?>)
                                            <ul>
                                                <?php foreach ($dips as $dip): if ($dip['parent_id'] == $apa['id']): ?>
                                                    <li>
                                                        <strong>DIP:</strong> <?= esc($dip['title']) ?> (<?= esc($dip['code']) ?>)
                                                        <ul>
                                                            <?php foreach ($specificAreas as $sa): if ($sa['parent_id'] == $dip['id']): ?>
                                                                <li>
                                                                    <strong>Specific Area:</strong> <?= esc($sa['title']) ?> (<?= esc($sa['code']) ?>)
                                                                    <ul>
                                                                        <?php foreach ($objectives as $objective): if ($objective['parent_id'] == $sa['id']): ?>
                                                                            <li>
                                                                                <strong>Objective:</strong> <?= esc($objective['title']) ?> (<?= esc($objective['code']) ?>)
                                                                                <ul>
                                                                                    <?php foreach ($outputs as $output): if ($output['parent_id'] == $objective['id']): ?>
                                                                                        <li>
                                                                                            <strong>Output:</strong> <?= esc($output['title']) ?> (<?= esc($output['code']) ?>)
                                                                                            <ul>
                                                                                                <?php foreach ($indicators as $indicator): if ($indicator['parent_id'] == $output['id']): ?>
                                                                                                    <li><strong>Indicator:</strong> <?= esc($indicator['title']) ?> (<?= esc($indicator['code']) ?>)</li>
                                                                                                <?php endif; endforeach; ?>
                                                                                            </ul>
                                                                                        </li>
                                                                                    <?php endif; endforeach; ?>
                                                                                </ul>
                                                                            </li>
                                                                        <?php endif; endforeach; ?>
                                                                    </ul>
                                                                </li>
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

<!-- DataTables Libraries -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script>
    $(document).ready(function() {
        // DataTables configuration
        const dataTableConfig = {
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: ':visible'
                    },
                    customize: function(doc) {
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        doc.styles.tableHeader.fontSize = 8;
                        doc.defaultStyle.fontSize = 7;
                    }
                }
            ],
            responsive: true,
            paging: false,
            searching: true,
            ordering: true,
            info: false,
            scrollX: true,
            language: {
                search: "Search:",
                zeroRecords: "No matching records found"
            }
        };

        // Initialize DataTables for each table
        $('#plansTable').DataTable({
            ...dataTableConfig,
            buttons: [{
                ...dataTableConfig.buttons[0],
                title: 'NASP Plans Report'
            }]
        });

        $('#apasTable').DataTable({
            ...dataTableConfig,
            buttons: [{
                ...dataTableConfig.buttons[0],
                title: 'NASP APAs Report'
            }]
        });

        $('#dipsTable').DataTable({
            ...dataTableConfig,
            buttons: [{
                ...dataTableConfig.buttons[0],
                title: 'NASP DIPs Report'
            }]
        });

        $('#specificAreasTable').DataTable({
            ...dataTableConfig,
            buttons: [{
                ...dataTableConfig.buttons[0],
                title: 'NASP Specific Areas Report'
            }]
        });

        $('#objectivesTable').DataTable({
            ...dataTableConfig,
            buttons: [{
                ...dataTableConfig.buttons[0],
                title: 'NASP Objectives Report'
            }]
        });

        $('#outputsTable').DataTable({
            ...dataTableConfig,
            buttons: [{
                ...dataTableConfig.buttons[0],
                title: 'NASP Outputs Report'
            }]
        });

        $('#indicatorsTable').DataTable({
            ...dataTableConfig,
            buttons: [{
                ...dataTableConfig.buttons[0],
                title: 'NASP Indicators Report'
            }]
        });

        // Chart copy functionality
        window.copyChartAsImage = function(chartId) {
            const canvas = document.getElementById(chartId);
            if (!canvas) {
                toastr.error('Chart not found');
                return;
            }

            try {
                canvas.toBlob(function(blob) {
                    const item = new ClipboardItem({ 'image/png': blob });
                    navigator.clipboard.write([item]).then(function() {
                        toastr.success('Chart copied to clipboard!');
                    }).catch(function(error) {
                        console.error('Failed to copy chart:', error);
                        toastr.error('Failed to copy chart to clipboard');
                    });
                });
            } catch (error) {
                console.error('Chart copy error:', error);
                toastr.error('Failed to copy chart');
            }
        };

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

        // 1. Status Distribution Chart
        const statusDistributionCtx = document.getElementById('statusDistributionChart').getContext('2d');
        const statusData = <?= json_encode($chartData['statusCounts'] ?? []) ?>;

        const statusLabels = ['Plans', 'APAs', 'DIPs', 'Specific Areas', 'Objectives', 'Outputs', 'Indicators'];
        const activeData = [
            statusData.plans?.active || 0,
            statusData.apas?.active || 0,
            statusData.dips?.active || 0,
            statusData.specificAreas?.active || 0,
            statusData.objectives?.active || 0,
            statusData.outputs?.active || 0,
            statusData.indicators?.active || 0
        ];
        const inactiveData = [
            statusData.plans?.inactive || 0,
            statusData.apas?.inactive || 0,
            statusData.dips?.inactive || 0,
            statusData.specificAreas?.inactive || 0,
            statusData.objectives?.inactive || 0,
            statusData.outputs?.inactive || 0,
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

        // 2. Entities by Plan Chart
        const entitiesByPlanCtx = document.getElementById('entitiesByPlanChart').getContext('2d');
        const entitiesByPlanData = <?= json_encode($chartData['entitiesByPlan'] ?? []) ?>;

        // Convert object to arrays for Chart.js
        const planLabels = [];
        const planDatasets = [
            { label: 'APAs', data: [], backgroundColor: colors.chartColors[0] },
            { label: 'DIPs', data: [], backgroundColor: colors.chartColors[1] },
            { label: 'Specific Areas', data: [], backgroundColor: colors.chartColors[2] },
            { label: 'Objectives', data: [], backgroundColor: colors.chartColors[3] },
            { label: 'Outputs', data: [], backgroundColor: colors.chartColors[4] },
            { label: 'Indicators', data: [], backgroundColor: colors.chartColors[5] }
        ];

        Object.keys(entitiesByPlanData).forEach(key => {
            const plan = entitiesByPlanData[key];
            planLabels.push(plan.title);
            planDatasets[0].data.push(plan.apas);
            planDatasets[1].data.push(plan.dips);
            planDatasets[2].data.push(plan.specificAreas);
            planDatasets[3].data.push(plan.objectives);
            planDatasets[4].data.push(plan.outputs);
            planDatasets[5].data.push(plan.indicators);
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
                        text: 'Entity Counts by NASP Plan'
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

        // 3. Outputs by APAs Chart
        const outputsByApasCtx = document.getElementById('outputsByApasChart').getContext('2d');
        const outputsByApasData = <?= json_encode($chartData['outputsByApas'] ?? []) ?>;

        // Convert object to arrays for Chart.js
        const outputApaLabels = [];
        const outputApaValues = [];

        Object.keys(outputsByApasData).forEach(key => {
            if (outputsByApasData[key].count > 0) {
                outputApaLabels.push(outputsByApasData[key].title);
                outputApaValues.push(outputsByApasData[key].count);
            }
        });

        new Chart(outputsByApasCtx, {
            type: 'pie',
            data: {
                labels: outputApaLabels,
                datasets: [{
                    data: outputApaValues,
                    backgroundColor: colors.chartColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Outputs Distribution by APAs'
                    }
                }
            }
        });

        // 4. DIPs by APA Chart
        const dipsByApaCtx = document.getElementById('dipsByApaChart').getContext('2d');
        const dipsByApaData = <?= json_encode($chartData['dipsByApa'] ?? []) ?>;

        // Convert object to arrays for Chart.js
        const apaLabels = [];
        const apaValues = [];

        Object.keys(dipsByApaData).forEach(key => {
            if (dipsByApaData[key].count > 0) {
                apaLabels.push(dipsByApaData[key].title);
                apaValues.push(dipsByApaData[key].count);
            }
        });

        new Chart(dipsByApaCtx, {
            type: 'doughnut',
            data: {
                labels: apaLabels,
                datasets: [{
                    data: apaValues,
                    backgroundColor: colors.chartColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'DIPs Distribution by APA'
                    }
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>
