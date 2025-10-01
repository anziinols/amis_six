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
                    <a href="<?= base_url('activities/new') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create New Activity
                    </a>
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

                    <?php if (empty($activities)): ?>
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
                                        <th>Activity Title</th>
                                        <th>Type</th>
                                        <th>Location</th>
                                        <th>Date Range</th>
                                        <th>Action Officer</th>
                                        <?php if (isset($isAdmin) && $isAdmin): ?>
                                        <th>Supervisor</th>
                                        <?php endif; ?>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $counter = 1; ?>
                                    <?php foreach ($activities as $activity): ?>
                                        <tr>
                                            <td><?= $counter++ ?></td>
                                            <td>
                                                <strong><?= esc($activity['activity_title']) ?></strong>
                                            </td>
                                            <td>
                                                <?php
                                                $typeClass = '';
                                                switch ($activity['type']) {
                                                    case 'trainings':
                                                        $typeClass = 'bg-info';
                                                        break;
                                                    case 'inputs':
                                                        $typeClass = 'bg-success';
                                                        break;
                                                    case 'infrastructures':
                                                        $typeClass = 'bg-warning';
                                                        break;
                                                    case 'documents':
                                                        $typeClass = 'bg-primary';
                                                        break;
                                                    case 'meetings':
                                                        $typeClass = 'bg-secondary';
                                                        break;
                                                    case 'agreements':
                                                        $typeClass = 'bg-dark';
                                                        break;
                                                    case 'outputs':
                                                        $typeClass = 'bg-danger';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?= $typeClass ?>"><?= ucfirst(esc($activity['type'])) ?></span>
                                            </td>
                                            <td>
                                                <?= esc($activity['location'] ?? 'N/A') ?><br>
                                                <small class="text-muted"><?= esc($activity['district_name'] ?? 'N/A') ?>, <?= esc($activity['province_name'] ?? 'N/A') ?></small>
                                            </td>
                                            <td>
                                                <?= date('d M Y', strtotime($activity['date_start'])) ?> -
                                                <?= date('d M Y', strtotime($activity['date_end'])) ?>
                                            </td>
                                            <td><?= esc($activity['action_officer_name'] ?? 'Not Assigned') ?></td>
                                            <?php if (isset($isAdmin) && $isAdmin): ?>
                                            <td><?= esc($activity['supervisor_name'] ?? 'Not Assigned') ?></td>
                                            <?php endif; ?>
                                            <td>
                                                <?php
                                                $statusBadgeClass = 'bg-secondary';
                                                switch ($activity['status']) {
                                                    case 'pending':
                                                        $statusBadgeClass = 'bg-warning text-dark';
                                                        break;
                                                    case 'active':
                                                        $statusBadgeClass = 'bg-success';
                                                        break;
                                                    case 'submitted':
                                                        $statusBadgeClass = 'bg-info text-dark';
                                                        break;
                                                    case 'approved':
                                                        $statusBadgeClass = 'bg-primary';
                                                        break;
                                                    case 'rated':
                                                        $statusBadgeClass = 'bg-dark';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?= $statusBadgeClass ?>"><?= ucfirst(esc($activity['status'])) ?></span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <?php if ($activity['status'] === 'submitted'): ?>
                                                        <?php
                                                        // Show supervise button only for admin or assigned supervisor
                                                        $canSupervise = (session()->get('is_admin') == 1) ||
                                                                       (session()->get('user_id') == $activity['supervisor_id']);
                                                        ?>
                                                        <?php if ($canSupervise): ?>
                                                        <a href="<?= base_url('activities/' . $activity['id'] . '/supervise') ?>" class="btn btn-outline-info" title="Supervise" style="margin-right: 5px;">
                                                            <i class="fas fa-check-circle me-1"></i> Supervise
                                                        </a>
                                                        <?php endif; ?>
                                                    <?php elseif ($activity['status'] === 'approved' || $activity['status'] === 'rated'): ?>
                                                        <a href="<?= base_url('activities/' . $activity['id'] . '/view') ?>" class="btn btn-outline-primary" title="View Activity" style="margin-right: 5px;">
                                                            <i class="fas fa-eye me-1"></i> View
                                                        </a>
                                                        <?php if (!empty($activity['rating_score'])): ?>
                                                        <span class="badge bg-success text-white">
                                                            <i class="fas fa-star me-1"></i>Rated: <?= esc($activity['rating_score']) ?>/5
                                                        </span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <?php
                                                        // Check if the current user can edit and implement this activity
                                                        // Admin can edit/implement all activities
                                                        // Action officers can edit/implement their own activities
                                                        // Supervisors can only edit/implement activities where they are the action officer
                                                        $currentUserId = session()->get('user_id');
                                                        $isAdmin = session()->get('is_admin') == 1;
                                                        $isActionOfficer = $activity['action_officer_id'] == $currentUserId;
                                                        $canEditImplement = $isAdmin || $isActionOfficer;
                                                        ?>
                                                        <?php if ($canEditImplement): ?>
                                                        <a href="<?= base_url('activities/' . $activity['id'] . '/links') ?>" class="btn btn-outline-info" title="Manage Links" style="margin-right: 5px;">
                                                            <i class="fas fa-link me-1"></i> Links
                                                        </a>
                                                        <?php endif; ?>
                                                        <a href="<?= base_url('activities/' . $activity['id']) ?>" class="btn btn-outline-primary" title="View Details" style="margin-right: 5px;">
                                                            <i class="fas fa-eye me-1"></i> View
                                                        </a>
                                                        <?php if ($canEditImplement && in_array($activity['status'], ['pending', 'active']) && isset($activity['has_links']) && $activity['has_links']): ?>
                                                        <a href="<?= base_url('activities/' . $activity['id'] . '/implement') ?>" class="btn btn-outline-success" title="Implement" style="margin-right: 5px;">
                                                            <i class="fas fa-tasks me-1"></i> Implement
                                                        </a>
                                                        <?php endif; ?>
                                                        <?php if ($canEditImplement): ?>
                                                        <a href="<?= base_url('activities/' . $activity['id'] . '/edit') ?>" class="btn btn-outline-warning" title="Edit">
                                                            <i class="fas fa-edit me-1"></i> Edit
                                                        </a>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
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
