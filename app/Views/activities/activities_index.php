<?php
// app/Views/activities/activities_index.php
?>
<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><?= esc($title) ?></h5>
                </div>
                <div class="card-body">
                    <?php if (session()->has('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> <?= session('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> <?= session('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($proposals)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <?= isset($isAdmin) && $isAdmin ? 'No activities found in the system.' : 'No activities assigned to you yet.' ?>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="activitiesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Workplan</th>
                                        <th>Activity</th>
                                        <th>Type</th>
                                        <th>Location</th>
                                        <th>Date Range</th>
                                        <?php if (isset($isAdmin) && $isAdmin): ?>
                                        <th>Action Officer</th>
                                        <?php endif; ?>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $counter = 1; ?>
                                    <?php foreach ($proposals as $proposal): ?>
                                        <tr>
                                            <td><?= $counter++ ?></td>
                                            <td><?= esc($proposal['workplan_title']) ?></td>
                                            <td><?= esc($proposal['activity_title']) ?></td>
                                            <td>
                                                <?php
                                                $typeClass = '';
                                                switch ($proposal['activity_type']) {
                                                    case 'training':
                                                        $typeClass = 'bg-info';
                                                        break;
                                                    case 'inputs':
                                                        $typeClass = 'bg-success';
                                                        break;
                                                    case 'infrastructure':
                                                        $typeClass = 'bg-warning';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?= $typeClass ?>"><?= ucfirst(esc($proposal['activity_type'])) ?></span>
                                            </td>
                                            <td>
                                                <?= esc($proposal['location']) ?><br>
                                                <small class="text-muted"><?= esc($proposal['district_name']) ?>, <?= esc($proposal['province_name']) ?></small>
                                            </td>
                                            <td>
                                                <?= date('d M Y', strtotime($proposal['date_start'])) ?> -
                                                <?= date('d M Y', strtotime($proposal['date_end'])) ?>
                                            </td>
                                            <?php if (isset($isAdmin) && $isAdmin): ?>
                                            <td><?= esc($proposal['officer_name'] ?? 'Not Assigned') ?></td>
                                            <?php endif; ?>
                                            <td>
                                                <?php
                                                $statusBadgeClass = 'bg-secondary';
                                                switch ($proposal['status']) {
                                                    case 'pending':
                                                        $statusBadgeClass = 'bg-warning text-dark';
                                                        break;
                                                    case 'submitted':
                                                        $statusBadgeClass = 'bg-info text-dark';
                                                        break;
                                                    case 'approved':
                                                        $statusBadgeClass = 'bg-success';
                                                        break;
                                                    case 'rated':
                                                        $statusBadgeClass = 'bg-primary';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?= $statusBadgeClass ?>"><?= ucfirst(esc($proposal['status'])) ?></span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('activities/' . $proposal['id']) ?>" class="btn btn-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($proposal['status'] === 'pending'): ?>
                                                <a href="<?= base_url('activities/' . $proposal['id'] . '/implement') ?>" class="btn btn-primary btn-sm" title="Implement">
                                                    Implement <i class="fas fa-tasks"></i>
                                                </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
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
                    ]
                });
            } catch (e) {
                console.error("DataTables initialization error:", e);
                // If DataTables fails to initialize, we can still use the table without it
            }
        }
    });
</script>
<?= $this->endSection() ?>
