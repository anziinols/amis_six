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

    .info-table {
        margin-bottom: 0;
    }

    .info-table td {
        padding: 0.75rem;
        vertical-align: top;
    }

    .info-table td:first-child {
        font-weight: 600;
        width: 35%;
        background-color: #f8f9fa;
    }
</style>

<div class="container-fluid">
    <!-- Breadcrumb and Back Button -->
    <div class="row mb-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('supervised-activities') ?>">Supervised Activities</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Activity Details</li>
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

    <!-- Activity Details Card -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-tasks me-2"></i>Workplan Activity Details
            </h3>
            <div>
                <a href="<?= base_url('supervised-activities') ?>"
                   class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <?php if ($activity['status'] !== 'completed'): ?>
                    <button type="button"
                            class="btn btn-success"
                            onclick="showMarkCompleteModal(<?= $activity['id'] ?>, '<?= esc($activity['title']) ?>')">
                        <i class="fas fa-check"></i> Mark as Completed
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered info-table">
                        <tr>
                            <td>Activity Code</td>
                            <td><strong><?= esc($activity['activity_code']) ?></strong></td>
                        </tr>
                        <tr>
                            <td>Title</td>
                            <td><strong><?= esc($activity['title']) ?></strong></td>
                        </tr>
                        <tr>
                            <td>Workplan</td>
                            <td><?= esc($activity['workplan_title'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td>Branch</td>
                            <td><?= esc($activity['branch_name'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td>Supervisor</td>
                            <td><?= esc($activity['supervisor_name'] ?? 'Not Assigned') ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered info-table">
                        <tr>
                            <td>Target Output</td>
                            <td><?= esc($activity['target_output'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td>Total Budget</td>
                            <td>
                                <?php if (!empty($activity['total_budget'])): ?>
                                    <strong>K <?= number_format($activity['total_budget'], 2) ?></strong>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Status</td>
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
                            </td>
                        </tr>
                        <tr>
                            <td>Status Date</td>
                            <td>
                                <?php if (!empty($activity['status_at'])): ?>
                                    <?= date('M d, Y H:i', strtotime($activity['status_at'])) ?>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Status Remarks</td>
                            <td><?= esc($activity['status_remarks'] ?? 'N/A') ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php if (!empty($activity['description'])): ?>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title"><i class="fas fa-info-circle me-2"></i>Description</h6>
                                <p class="card-text mb-0"><?= nl2br(esc($activity['description'])) ?></p>
                            </div>
                        </div>
                    </div>
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