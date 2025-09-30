<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Page Header with Back Button -->
    <div class="row mb-3">
        <div class="col-md-8">
            <h2><?= esc($title) ?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('evaluation') ?>">Evaluation</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Workplan Activities</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
            <a href="<?= base_url('evaluation') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Workplans
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Workplan Details Card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-info-circle"></i> Workplan Details
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Title:</strong> <?= esc($workplan['title']) ?></p>
                    <p><strong>Branch:</strong> <?= esc($workplan['branch_name'] ?? 'N/A') ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Date Range:</strong> 
                        <?php if (!empty($workplan['start_date']) && !empty($workplan['end_date'])): ?>
                            <?= date('M d, Y', strtotime($workplan['start_date'])) ?> - 
                            <?= date('M d, Y', strtotime($workplan['end_date'])) ?>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-success"><?= ucfirst(esc($workplan['status'])) ?></span>
                    </p>
                </div>
            </div>
            <?php if (!empty($workplan['description'])): ?>
                <div class="row mt-2">
                    <div class="col-12">
                        <p><strong>Description:</strong></p>
                        <p class="text-muted"><?= esc($workplan['description']) ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Activities Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-tasks"></i> Workplan Activities
                <span class="badge bg-info ms-2"><?= count($activities) ?></span>
            </h5>
        </div>
        <div class="card-body">
            <?php if (!empty($activities)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="activitiesTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 10%;">Activity Code</th>
                                <th style="width: 20%;">Title</th>
                                <th style="width: 10%;">Branch</th>
                                <th style="width: 10%;">Target Output</th>
                                <th style="width: 10%;">Budget</th>
                                <th style="width: 10%;">Status</th>
                                <th style="width: 10%;">Rating</th>
                                <th style="width: 15%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($activities as $index => $activity): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?= esc($activity['activity_code'] ?? 'N/A') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong><?= esc($activity['title']) ?></strong>
                                        <?php if (!empty($activity['description'])): ?>
                                            <br>
                                            <small class="text-muted">
                                                <?= esc(substr($activity['description'], 0, 80)) ?>
                                                <?= strlen($activity['description']) > 80 ? '...' : '' ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($activity['branch_name'] ?? 'N/A') ?></td>
                                    <td><?= esc($activity['target_output'] ?? 'N/A') ?></td>
                                    <td>
                                        <?php if (!empty($activity['total_budget'])): ?>
                                            K <?= number_format($activity['total_budget'], 2) ?>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'complete' => 'success',
                                            'rated' => 'primary'
                                        ];
                                        $statusColor = $statusColors[$activity['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $statusColor ?>">
                                            <?= ucfirst(esc($activity['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($activity['rating'])): ?>
                                            <span class="badge bg-success">
                                                <?= esc($activity['rating']) ?>%
                                            </span>
                                            <?php if (!empty($activity['rated_at'])): ?>
                                                <br>
                                                <small class="text-muted">
                                                    <?= date('M d, Y', strtotime($activity['rated_at'])) ?>
                                                </small>
                                            <?php endif; ?>
                                            <?php if (!empty($activity['rated_by_name'])): ?>
                                                <br>
                                                <small class="text-muted">
                                                    by <?= esc($activity['rated_by_name']) ?>
                                                </small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Not Rated</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('evaluation/workplan-activity/' . $activity['id'] . '/outputs') ?>" 
                                           class="btn btn-sm btn-info" 
                                           title="View Linked Activities">
                                            <i class="fas fa-eye"></i> View Outputs
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No activities found for this workplan.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize DataTables if the table exists and has data
        if ($('#activitiesTable').length > 0 && $('#activitiesTable tbody tr').length > 0) {
            try {
                $('#activitiesTable').DataTable({
                    responsive: true,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    columnDefs: [
                        { orderable: false, targets: -1 } // Disable sorting on the actions column
                    ],
                    order: [[1, 'asc']] // Default sort by activity code
                });
            } catch (e) {
                console.error("DataTables initialization error:", e);
            }
        }
    });
</script>
<?= $this->endSection() ?>

