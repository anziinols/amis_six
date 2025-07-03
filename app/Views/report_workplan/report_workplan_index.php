<?php
// app/Views/report_workplan/report_workplan_index.php

/**
 * Workplan Reports - Index View
 *
 * @var array $workplans
 * @var array $activities
 * @var array $proposals
 * @var array $branches
 * @var array $users
 * @var array $mtdpLinks
 * @var array $naspLinks
 * @var array $corporatePlanLinks
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
                            <h4 class="card-title">Workplan Reports</h4>
                            <p class="card-text mb-0">This report displays all workplans and their activities with visual charts and graphs.</p>
                        </div>
                        <div>
                            <button onclick="AMISPdf.generateWorkplanReportPDF()" class="btn btn-light">
                                <i class="fas fa-file-pdf me-1"></i> Export PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cost Summary Cards Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-title">Total Budget Allocation</h5>
                            <p class="card-text">Total cost of all approved proposals</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <h2><?= number_format($chartData['totalCost'] ?? 0, 2) ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="card-title">Average Proposal Rating</h5>
                            <p class="card-text"><?= $chartData['ratedProposalsCount'] ?? 0 ?> proposals rated</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <h2><?= number_format($chartData['averageRatingScore'] ?? 0, 1) ?>/5</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="row mb-4">
        <!-- Workplan Status Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header"><strong>Workplan Status Distribution</strong></div>
                <div class="card-body">
                    <canvas id="workplanStatusChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Activity Status Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header"><strong>Activity Status Distribution</strong></div>
                <div class="card-body">
                    <canvas id="activityStatusChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Proposal Status Chart - New -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header"><strong>Proposal Status Distribution</strong></div>
                <div class="card-body">
                    <canvas id="proposalStatusChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Activity Type Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header"><strong>Activity Type Distribution</strong></div>
                <div class="card-body">
                    <canvas id="activityTypeChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Costs by Province Chart - New -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header"><strong>Costs by Province</strong></div>
                <div class="card-body">
                    <canvas id="costsByProvinceChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Costs by Activity Type Chart - New -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header"><strong>Costs by Activity Type</strong></div>
                <div class="card-body">
                    <canvas id="costsByActivityTypeChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Workplans by Branch Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header"><strong>Workplans by Branch</strong></div>
                <div class="card-body">
                    <canvas id="workplansByBranchChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Activities by Plan Type Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header"><strong>Activities by Plan Type</strong></div>
                <div class="card-body">
                    <canvas id="activitiesByPlanTypeChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Monthly Activity Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header"><strong>Monthly Activity Distribution (<?= date('Y') ?>)</strong></div>
                <div class="card-body">
                    <canvas id="monthlyActivityChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Workplans Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Workplans</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Branch</th>
                                <th>Supervisor</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($workplans as $workplan): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td><?= esc($workplan['title']) ?></td>
                                <td>
                                    <?php 
                                    foreach ($branches as $branch) {
                                        if ($branch['id'] == $workplan['branch_id']) {
                                            echo esc($branch['name']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    if (!empty($workplan['supervisor_id'])) {
                                        foreach ($users as $user) {
                                            if ($user['id'] == $workplan['supervisor_id']) {
                                                echo esc($user['fname'] . ' ' . $user['lname']);
                                                break;
                                            }
                                        }
                                    } else {
                                        echo 'Not assigned';
                                    }
                                    ?>
                                </td>
                                <td><?= esc($workplan['start_date']) ?></td>
                                <td><?= esc($workplan['end_date']) ?></td>
                                <td>
                                    <span class="badge <?= getStatusBadgeClass($workplan['status']) ?>">
                                        <?= ucfirst(str_replace('_', ' ', $workplan['status'])) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Activities Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Activities</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Workplan</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Supervisor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($activities as $activity): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td>
                                    <?php 
                                    foreach ($workplans as $workplan) {
                                        if ($workplan['id'] == $activity['workplan_id']) {
                                            echo esc($workplan['title']);
                                            break;
                                        }
                                    }
                                    ?>
                                </td>
                                <td><?= esc($activity['title']) ?></td>
                                <td><?= isset($activity['activity_type']) ? ucfirst($activity['activity_type']) : 'Not specified' ?></td>
                                <td><?= isset($activity['location']) ? esc($activity['location']) : 'Not specified' ?></td>
                                <td>
                                    <?php 
                                    if (!empty($activity['supervisor_id'])) {
                                        foreach ($users as $user) {
                                            if ($user['id'] == $activity['supervisor_id']) {
                                                echo esc($user['fname'] . ' ' . $user['lname']);
                                                break;
                                            }
                                        }
                                    } else {
                                        echo 'Not assigned';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php if (isset($activity['status']) && !empty($activity['status'])): ?>
                                        <span class="badge <?= getActivityStatusBadgeClass($activity['status']) ?>">
                                            <?= ucfirst($activity['status']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            Pending
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Proposals Table - New -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Proposals</strong></div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Workplan</th>
                                <th>Activity</th>
                                <th>Type</th>
                                <th>Province</th>
                                <th>District</th>
                                <th>Date Range</th>
                                <th>Total Cost</th>
                                <th>Status</th>
                                <th>Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($proposals as $proposal): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td><?= isset($proposal['workplan_title']) ? esc($proposal['workplan_title']) : 'Not specified' ?></td>
                                <td><?= isset($proposal['activity_title']) ? esc($proposal['activity_title']) : 'Not specified' ?></td>
                                <td><?= isset($proposal['activity_type']) ? ucfirst($proposal['activity_type']) : 'Not specified' ?></td>
                                <td><?= isset($proposal['province_name']) ? esc($proposal['province_name']) : 'Not specified' ?></td>
                                <td><?= isset($proposal['district_name']) ? esc($proposal['district_name']) : 'Not specified' ?></td>
                                <td>
                                    <?php if (isset($proposal['date_start']) && isset($proposal['date_end'])): ?>
                                        <?= date('d M Y', strtotime($proposal['date_start'])) ?> - 
                                        <?= date('d M Y', strtotime($proposal['date_end'])) ?>
                                    <?php else: ?>
                                        Not specified
                                    <?php endif; ?>
                                </td>
                                <td class="text-end"><?= isset($proposal['total_cost']) ? number_format($proposal['total_cost'], 2) : '0.00' ?></td>
                                <td>
                                    <?php if (isset($proposal['status']) && !empty($proposal['status'])): ?>
                                        <span class="badge <?= getProposalStatusBadgeClass($proposal['status']) ?>">
                                            <?= ucfirst($proposal['status']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            Pending
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isset($proposal['rating_score']) && $proposal['rating_score'] > 0): ?>
                                        <div class="rating">
                                            <?= number_format($proposal['rating_score'], 1) ?>/5
                                            <span class="rating-stars">
                                                <?php 
                                                    $score = round($proposal['rating_score']); 
                                                    echo str_repeat('★', $score) . str_repeat('☆', 5 - $score);
                                                ?>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        Not rated
                                    <?php endif; ?>
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

<?php
// Helper function to get badge class for workplan status
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'draft':
            return 'bg-secondary';
        case 'in_progress':
            return 'bg-primary';
        case 'completed':
            return 'bg-success';
        case 'on_hold':
            return 'bg-warning';
        case 'cancelled':
            return 'bg-danger';
        default:
            return 'bg-secondary';
    }
}

// Helper function to get badge class for activity status
function getActivityStatusBadgeClass($status) {
    if (!isset($status) || empty($status)) {
        return 'bg-secondary';
    }
    
    switch ($status) {
        case 'pending':
            return 'bg-warning';
        case 'submitted':
            return 'bg-info';
        case 'approved':
            return 'bg-success';
        case 'rated':
            return 'bg-primary';
        default:
            return 'bg-secondary';
    }
}

// Helper function to get badge class for proposal status
function getProposalStatusBadgeClass($status) {
    if (!isset($status) || empty($status)) {
        return 'bg-secondary';
    }
    
    switch ($status) {
        case 'pending':
            return 'bg-warning';
        case 'submitted':
            return 'bg-info';
        case 'approved':
            return 'bg-success';
        case 'rated':
            return 'bg-primary';
        default:
            return 'bg-secondary';
    }
}
?>
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

        // 1. Workplan Status Chart
        const workplanStatusCtx = document.getElementById('workplanStatusChart').getContext('2d');
        const workplanStatusData = <?= json_encode($chartData['workplanStatusCounts'] ?? []) ?>;
        
        new Chart(workplanStatusCtx, {
            type: 'pie',
            data: {
                labels: ['Draft', 'In Progress', 'Completed', 'On Hold', 'Cancelled'],
                datasets: [{
                    data: [
                        workplanStatusData.draft || 0,
                        workplanStatusData.in_progress || 0,
                        workplanStatusData.completed || 0,
                        workplanStatusData.on_hold || 0,
                        workplanStatusData.cancelled || 0
                    ],
                    backgroundColor: [
                        colors.secondary,
                        colors.primary,
                        colors.success,
                        colors.warning,
                        colors.danger
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Workplan Status Distribution'
                    }
                }
            }
        });

        // 2. Activity Status Chart
        const activityStatusCtx = document.getElementById('activityStatusChart').getContext('2d');
        const activityStatusData = <?= json_encode($chartData['activityStatusCounts'] ?? []) ?>;
        
        new Chart(activityStatusCtx, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Submitted', 'Approved', 'Rated', 'Other'],
                datasets: [{
                    data: [
                        activityStatusData.pending || 0,
                        activityStatusData.submitted || 0,
                        activityStatusData.approved || 0,
                        activityStatusData.rated || 0,
                        activityStatusData.other || 0
                    ],
                    backgroundColor: [
                        colors.warning,
                        colors.info,
                        colors.success,
                        colors.primary,
                        colors.secondary
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Activity Status Distribution'
                    }
                }
            }
        });
        
        // 2.1 Proposal Status Chart (New)
        const proposalStatusCtx = document.getElementById('proposalStatusChart').getContext('2d');
        const proposalStatusData = <?= json_encode($chartData['proposalStatusCounts'] ?? []) ?>;
        
        new Chart(proposalStatusCtx, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Submitted', 'Approved', 'Rated', 'Other'],
                datasets: [{
                    data: [
                        proposalStatusData.pending || 0,
                        proposalStatusData.submitted || 0,
                        proposalStatusData.approved || 0,
                        proposalStatusData.rated || 0,
                        proposalStatusData.other || 0
                    ],
                    backgroundColor: [
                        colors.warning,
                        colors.info,
                        colors.success,
                        colors.primary,
                        colors.secondary
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Proposal Status Distribution'
                    }
                }
            }
        });

        // 3. Activity Type Chart
        const activityTypeCtx = document.getElementById('activityTypeChart').getContext('2d');
        const activityTypeData = <?= json_encode($chartData['activityTypeCounts'] ?? []) ?>;
        
        new Chart(activityTypeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Training', 'Inputs', 'Infrastructure', 'Other'],
                datasets: [{
                    data: [
                        activityTypeData.training || 0,
                        activityTypeData.inputs || 0,
                        activityTypeData.infrastructure || 0,
                        activityTypeData.other || 0
                    ],
                    backgroundColor: [
                        colors.primary,
                        colors.warning,
                        colors.info,
                        colors.secondary
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Activity Type Distribution'
                    }
                }
            }
        });
        
        // 3.1 Costs by Province Chart (New)
        const costsByProvinceCtx = document.getElementById('costsByProvinceChart').getContext('2d');
        const costsByProvinceData = <?= json_encode($chartData['costsByProvince'] ?? []) ?>;
        
        // Convert object to arrays for Chart.js
        const provinceLabels = Object.keys(costsByProvinceData);
        const provinceValues = Object.values(costsByProvinceData);
        
        new Chart(costsByProvinceCtx, {
            type: 'bar',
            data: {
                labels: provinceLabels,
                datasets: [{
                    label: 'Total Cost',
                    data: provinceValues,
                    backgroundColor: colors.success,
                    borderColor: colors.success,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Total Costs by Province'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('en-US', { 
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }).format(value);
                            }
                        }
                    }
                }
            }
        });
        
        // 3.2 Costs by Activity Type Chart (New)
        const costsByActivityTypeCtx = document.getElementById('costsByActivityTypeChart').getContext('2d');
        const costsByActivityTypeData = <?= json_encode($chartData['costsByActivityType'] ?? []) ?>;
        
        new Chart(costsByActivityTypeCtx, {
            type: 'pie',
            data: {
                labels: ['Training', 'Inputs', 'Infrastructure', 'Other'],
                datasets: [{
                    data: [
                        costsByActivityTypeData.training || 0,
                        costsByActivityTypeData.inputs || 0,
                        costsByActivityTypeData.infrastructure || 0,
                        costsByActivityTypeData.other || 0
                    ],
                    backgroundColor: [
                        colors.primary,
                        colors.warning,
                        colors.info,
                        colors.secondary
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Budget Allocation by Activity Type'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                const value = context.raw || 0;
                                label += new Intl.NumberFormat('en-US', { 
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }).format(value);
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // 4. Workplans by Branch Chart
        const workplansByBranchCtx = document.getElementById('workplansByBranchChart').getContext('2d');
        const workplansByBranchData = <?= json_encode($chartData['workplansByBranch'] ?? []) ?>;
        
        // Convert object to arrays for Chart.js
        const branchLabels = [];
        const branchValues = [];
        
        Object.keys(workplansByBranchData).forEach(key => {
            branchLabels.push(workplansByBranchData[key].name);
            branchValues.push(workplansByBranchData[key].count);
        });
        
        new Chart(workplansByBranchCtx, {
            type: 'bar',
            data: {
                labels: branchLabels,
                datasets: [{
                    label: 'Number of Workplans',
                    data: branchValues,
                    backgroundColor: colors.primary,
                    borderColor: colors.primary,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Workplans by Branch'
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

        // 5. Activities by Plan Type Chart
        const activitiesByPlanTypeCtx = document.getElementById('activitiesByPlanTypeChart').getContext('2d');
        const activitiesByPlanTypeData = <?= json_encode($chartData['activitiesByPlanType'] ?? []) ?>;
        
        new Chart(activitiesByPlanTypeCtx, {
            type: 'pie',
            data: {
                labels: ['MTDP', 'NASP', 'Corporate Plan', 'No Link'],
                datasets: [{
                    data: [
                        activitiesByPlanTypeData.mtdp || 0,
                        activitiesByPlanTypeData.nasp || 0,
                        activitiesByPlanTypeData.corporate_plan || 0,
                        activitiesByPlanTypeData.no_link || 0
                    ],
                    backgroundColor: colors.chartColors.slice(0, 4),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Activities by Plan Type'
                    }
                }
            }
        });

        // 6. Monthly Activity Chart
        const monthlyActivityCtx = document.getElementById('monthlyActivityChart').getContext('2d');
        const monthlyActivityData = <?= json_encode($chartData['monthlyActivityCounts'] ?? []) ?>;
        
        new Chart(monthlyActivityCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Number of Activities',
                    data: monthlyActivityData,
                    backgroundColor: colors.primary,
                    borderColor: colors.primary,
                    tension: 0.1,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Activity Distribution (' + new Date().getFullYear() + ')'
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
    });
</script>

<style>
    .rating-stars {
        margin-left: 5px;
        color: #ffc107;
    }
</style>
<?= $this->endSection() ?>
