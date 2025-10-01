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
                    <li class="breadcrumb-item"><a href="<?= base_url('supervised-activities') ?>">Supervised Activities</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Linked Activities</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
            <a href="<?= base_url('supervised-activities') ?>" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Back to Supervised Activities
            </a>
            <?php if ($workplanActivity['status'] !== 'completed'): ?>
                <button type="button"
                        class="btn btn-success"
                        onclick="showMarkCompleteModal(<?= $workplanActivity['id'] ?>, '<?= esc($workplanActivity['title']) ?>')">
                    <i class="fas fa-check"></i> Mark as Complete
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-secondary" disabled>
                    <i class="fas fa-check-circle"></i> Completed
                </button>
            <?php endif; ?>
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

    <?php if (session()->getFlashdata('info')): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle"></i> <?= session()->getFlashdata('info') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Supervised Activity Details Card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-info-circle"></i> Supervised Activity Details
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Activity Code:</strong>
                        <span class="badge bg-primary"><?= esc($workplanActivity['activity_code'] ?? 'N/A') ?></span>
                    </p>
                    <p><strong>Title:</strong> <?= esc($workplanActivity['title']) ?></p>
                    <p><strong>Workplan:</strong> <?= esc($workplanActivity['workplan_title'] ?? 'N/A') ?></p>
                    <p><strong>Branch:</strong> <?= esc($workplanActivity['branch_name'] ?? 'N/A') ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Target Output:</strong> <?= esc($workplanActivity['target_output'] ?? 'N/A') ?></p>
                    <p><strong>Budget:</strong>
                        <?php if (!empty($workplanActivity['total_budget'])): ?>
                            <?= CURRENCY_SYMBOL ?> <?= number_format($workplanActivity['total_budget'], 2) ?>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </p>
                    <p><strong>Supervisor:</strong> <?= esc($workplanActivity['supervisor_name'] ?? 'N/A') ?></p>
                </div>
            </div>
            <?php if (!empty($workplanActivity['description'])): ?>
                <div class="row mt-2">
                    <div class="col-12">
                        <p><strong>Description:</strong></p>
                        <p class="text-muted"><?= esc($workplanActivity['description']) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Status Information -->
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="text-primary"><i class="fas fa-info-circle"></i> Status Information</h6>
                    <hr>
                </div>
                <div class="col-md-6">
                    <p><strong>Status:</strong>
                        <?php
                        $statusColors = [
                            'pending' => 'warning',
                            'active' => 'success',
                            'complete' => 'primary',
                            'on_hold' => 'secondary',
                            'cancelled' => 'danger'
                        ];
                        $statusColor = $statusColors[$workplanActivity['status'] ?? 'pending'] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $statusColor ?>">
                            <?= ucfirst(esc($workplanActivity['status'] ?? 'Pending')) ?>
                        </span>
                    </p>
                    <p><strong>Status By:</strong> <?= esc($workplanActivity['status_by_name'] ?? 'N/A') ?></p>
                    <p><strong>Status At:</strong>
                        <?php if (!empty($workplanActivity['status_at'])): ?>
                            <?= date('M d, Y h:i A', strtotime($workplanActivity['status_at'])) ?>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Status Remarks:</strong></p>
                    <?php if (!empty($workplanActivity['status_remarks'])): ?>
                        <p class="text-muted"><?= esc($workplanActivity['status_remarks']) ?></p>
                    <?php else: ?>
                        <p class="text-muted">-</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Rating Information -->
            <div class="row mt-3">
                <div class="col-12">
                    <h6 class="text-primary"><i class="fas fa-star"></i> Rating Information</h6>
                    <hr>
                </div>
                <div class="col-md-6">
                    <p><strong>Rating:</strong>
                        <?php if (!empty($workplanActivity['rating'])): ?>
                            <span class="badge bg-info"><?= esc($workplanActivity['rating']) ?>%</span>
                        <?php else: ?>
                            <span class="text-muted">Not Rated</span>
                        <?php endif; ?>
                    </p>
                    <p><strong>Rated By:</strong> <?= esc($workplanActivity['rated_by_name'] ?? 'N/A') ?></p>
                    <p><strong>Rated At:</strong>
                        <?php if (!empty($workplanActivity['rated_at'])): ?>
                            <?= date('M d, Y h:i A', strtotime($workplanActivity['rated_at'])) ?>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Rating Remarks:</strong></p>
                    <?php if (!empty($workplanActivity['reated_remarks'])): ?>
                        <p class="text-muted"><?= esc($workplanActivity['reated_remarks']) ?></p>
                    <?php else: ?>
                        <p class="text-muted">-</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Linked Activities Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-link"></i> Linked Activities (Outputs)
                <span class="badge bg-info ms-2"><?= count($linkedActivities) ?></span>
            </h5>
        </div>
        <div class="card-body">
            <?php if (!empty($linkedActivities)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="linkedActivitiesTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 3%;">#</th>
                                <th style="width: 15%;">Activity Title</th>
                                <th style="width: 8%;">Type</th>
                                <th style="width: 10%;">Location</th>
                                <th style="width: 10%;">Date Range</th>
                                <th style="width: 10%;">Action Officer</th>
                                <th style="width: 8%;">Cost</th>
                                <th style="width: 8%;">Status</th>
                                <th style="width: 10%;">Status By</th>
                                <th style="width: 8%;">Status At</th>
                                <th style="width: 10%;">Status Remarks</th>
                                <th style="width: 5%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($linkedActivities as $index => $linkedActivity): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <strong><?= esc($linkedActivity['activity_title']) ?></strong>
                                        <?php if (!empty($linkedActivity['activity_description'])): ?>
                                            <br>
                                            <small class="text-muted">
                                                <?= esc(substr($linkedActivity['activity_description'], 0, 80)) ?>
                                                <?= strlen($linkedActivity['activity_description']) > 80 ? '...' : '' ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $typeColors = [
                                            'documents' => 'primary',
                                            'trainings' => 'success',
                                            'meetings' => 'info',
                                            'agreements' => 'warning',
                                            'inputs' => 'secondary',
                                            'infrastructures' => 'dark',
                                            'outputs' => 'danger'
                                        ];
                                        $color = $typeColors[$linkedActivity['type']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $color ?>">
                                            <?= ucfirst(esc($linkedActivity['type'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= esc($linkedActivity['location'] ?? 'N/A') ?>
                                        <?php if (!empty($linkedActivity['province_name']) || !empty($linkedActivity['district_name'])): ?>
                                            <br>
                                            <small class="text-muted">
                                                <?= esc($linkedActivity['province_name'] ?? '') ?>
                                                <?= !empty($linkedActivity['district_name']) ? ', ' . esc($linkedActivity['district_name']) : '' ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($linkedActivity['date_start']) && !empty($linkedActivity['date_end'])): ?>
                                            <small>
                                                <?= date('M d, Y', strtotime($linkedActivity['date_start'])) ?><br>
                                                to<br>
                                                <?= date('M d, Y', strtotime($linkedActivity['date_end'])) ?>
                                            </small>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($linkedActivity['action_officer_name'] ?? 'N/A') ?></td>
                                    <td>
                                        <?php if (!empty($linkedActivity['total_cost'])): ?>
                                            K <?= number_format($linkedActivity['total_cost'], 2) ?>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'active' => 'info',
                                            'submitted' => 'primary',
                                            'approved' => 'success',
                                            'rated' => 'dark'
                                        ];
                                        $statusColor = $statusColors[$linkedActivity['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $statusColor ?>">
                                            <?= ucfirst(esc($linkedActivity['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= esc($linkedActivity['status_by_name'] ?? 'N/A') ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($linkedActivity['status_at'])): ?>
                                            <small>
                                                <?= date('M d, Y', strtotime($linkedActivity['status_at'])) ?><br>
                                                <?= date('h:i A', strtotime($linkedActivity['status_at'])) ?>
                                            </small>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($linkedActivity['status_remarks'])): ?>
                                            <small class="text-muted">
                                                <?= esc(substr($linkedActivity['status_remarks'], 0, 50)) ?>
                                                <?= strlen($linkedActivity['status_remarks']) > 50 ? '...' : '' ?>
                                            </small>
                                        <?php else: ?>
                                            <small class="text-muted">-</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('activities/' . $linkedActivity['activity_id']) ?>"
                                           class="btn btn-sm btn-outline-primary"
                                           title="View Activity Details"
                                           target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No activities have been linked to this supervised activity yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Mark Complete Modal -->
<div class="modal fade" id="markCompleteModal" tabindex="-1" aria-labelledby="markCompleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="markCompleteForm">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="markCompleteModalLabel">
                        <i class="fas fa-check-circle"></i> Mark Activity as Complete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to mark the following activity as complete?</p>
                    <div class="alert alert-info">
                        <strong id="activityTitle"></strong>
                    </div>
                    <div class="mb-3">
                        <label for="status_remarks" class="form-label">Remarks (Optional)</label>
                        <textarea class="form-control"
                                  id="status_remarks"
                                  name="status_remarks"
                                  rows="3"
                                  placeholder="Add any remarks about completing this activity..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Mark as Complete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize DataTables if the table exists and has data
        if ($('#linkedActivitiesTable').length > 0 && $('#linkedActivitiesTable tbody tr').length > 0) {
            try {
                $('#linkedActivitiesTable').DataTable({
                    responsive: true,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    columnDefs: [
                        { orderable: false, targets: -1 } // Disable sorting on the actions column
                    ],
                    order: [[0, 'asc']] // Default sort by number
                });
            } catch (e) {
                console.error("DataTables initialization error:", e);
            }
        }
    });

    function showMarkCompleteModal(activityId, activityTitle) {
        // Set the activity title in the modal
        $('#activityTitle').text(activityTitle);

        // Set the form action
        $('#markCompleteForm').attr('action', '<?= base_url('supervised-activities') ?>/' + activityId + '/mark-complete');

        // Clear previous remarks
        $('#status_remarks').val('');

        // Show the modal
        $('#markCompleteModal').modal('show');
    }
</script>
<?= $this->endSection() ?>

