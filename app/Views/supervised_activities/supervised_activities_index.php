<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<style>
    /* Status badge styles with dark text on light backgrounds */
    .status-badge {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.85em;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        border: 1px solid transparent;
    }

    .status-pending {
        color: #664d03;
        background-color: #fff3cd;
        border-color: #ffecb5;
    }

    .status-active {
        color: #084298;
        background-color: #cfe2ff;
        border-color: #b6d4fe;
    }

    .status-completed {
        color: #0f5132;
        background-color: #d1e7dd;
        border-color: #badbcc;
    }

    .status-default {
        color: #41464b;
        background-color: #e2e3e5;
        border-color: #d3d6d8;
    }

    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    .card-header {
        background-color: #fff;
        border-bottom: 2px solid #dee2e6;
    }

    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
</style>

<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Supervised Activities</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('info')): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i><?= session()->getFlashdata('info') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Activities Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-tasks me-2"></i><?= $isAdmin ? 'All Workplan Activities' : 'My Supervised Workplan Activities' ?>
            </h3>
            <div>
                <a href="<?= base_url('dashboard') ?>"
                   class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($activities)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="activitiesTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 15%;">Activity Code</th>
                                <th style="width: 20%;">Title</th>
                                <th style="width: 15%;">Workplan</th>
                                <th style="width: 10%;">Branch</th>
                                <th style="width: 10%;">Target Output</th>
                                <th style="width: 10%;">Budget</th>
                                <th style="width: 10%;">Status</th>
                                <th style="width: 10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($activities as $index => $activity): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <strong><?= esc($activity['activity_code']) ?></strong>
                                    </td>
                                    <td>
                                        <strong><?= esc($activity['title']) ?></strong>
                                        <?php if (!empty($activity['description'])): ?>
                                            <br>
                                            <small class="text-muted">
                                                <?= esc(substr($activity['description'], 0, 100)) ?>
                                                <?= strlen($activity['description']) > 100 ? '...' : '' ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= esc($activity['workplan_title'] ?? 'N/A') ?>
                                    </td>
                                    <td>
                                        <?= esc($activity['branch_name'] ?? 'N/A') ?>
                                    </td>
                                    <td>
                                        <?= esc($activity['target_output'] ?? 'N/A') ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($activity['total_budget'])): ?>
                                            K <?= number_format($activity['total_budget'], 2) ?>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = 'status-default';
                                        $statusText = $activity['status'] ?? 'pending';
                                        switch ($statusText) {
                                            case 'pending':
                                                $statusClass = 'status-pending';
                                                break;
                                            case 'active':
                                                $statusClass = 'status-active';
                                                break;
                                            case 'completed':
                                                $statusClass = 'status-completed';
                                                break;
                                        }
                                        ?>
                                        <span class="status-badge <?= $statusClass ?>"><?= ucfirst(esc($statusText)) ?></span>
                                        <?php if (!empty($activity['status_at'])): ?>
                                            <br>
                                            <small class="text-muted">
                                                <?= date('M d, Y', strtotime($activity['status_at'])) ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('supervised-activities/' . $activity['id'] . '/view-outputs') ?>"
                                               class="btn btn-sm btn-outline-primary"
                                               title="View Activity Details"
                                               style="margin-right: 5px;">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                            <?php if ($activity['status'] !== 'completed'): ?>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-success"
                                                        title="Mark as Completed"
                                                        onclick="showMarkCompleteModal(<?= $activity['id'] ?>, '<?= esc($activity['title']) ?>')">
                                                    <i class="fas fa-check me-1"></i> Complete
                                                </button>
                                            <?php else: ?>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-success"
                                                        disabled
                                                        title="Already Completed">
                                                    <i class="fas fa-check-circle me-1"></i> Completed
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> <?= $isAdmin ? 'No workplan activities found in the system.' : 'No workplan activities assigned to you at this time.' ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Mark Complete Modal -->
<div class="modal fade" id="markCompleteModal" tabindex="-1" aria-labelledby="markCompleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" id="markCompleteForm">
                <?= csrf_field() ?>
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="markCompleteModalLabel">
                        <i class="fas fa-check-circle me-2"></i>Mark Activity as Completed
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Are you sure you want to mark the following activity as completed?</p>
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i><strong id="activityTitle"></strong>
                    </div>
                    <div class="mb-3">
                        <label for="status_remarks" class="form-label fw-bold">
                            <i class="fas fa-comment me-1"></i>Remarks (Optional)
                        </label>
                        <textarea class="form-control"
                                  id="status_remarks"
                                  name="status_remarks"
                                  rows="3"
                                  placeholder="Add any remarks about completing this activity..."></textarea>
                        <small class="form-text text-muted">Provide any additional notes or comments about the completion of this activity.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i> Confirm Completion
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

