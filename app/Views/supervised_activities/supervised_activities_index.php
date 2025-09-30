<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2><?= esc($title) ?></h2>
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

    <!-- Activities Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-tasks"></i> Activities Assigned to Me
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
                                        <span class="badge bg-primary">
                                            <?= esc($activity['activity_code'] ?? 'N/A') ?>
                                        </span>
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
                                        <?php if (!empty($activity['workplan_start_date']) && !empty($activity['workplan_end_date'])): ?>
                                            <br>
                                            <small class="text-muted">
                                                <?= date('M d, Y', strtotime($activity['workplan_start_date'])) ?> - 
                                                <?= date('M d, Y', strtotime($activity['workplan_end_date'])) ?>
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
                                        <?php if ($activity['status'] === 'complete'): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle"></i> Complete
                                            </span>
                                            <?php if (!empty($activity['status_at'])): ?>
                                                <br>
                                                <small class="text-muted">
                                                    <?= date('M d, Y', strtotime($activity['status_at'])) ?>
                                                </small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('supervised-activities/' . $activity['id'] . '/view-outputs') ?>" 
                                               class="btn btn-sm btn-info" 
                                               title="View Linked Activities">
                                                <i class="fas fa-eye"></i> View Outputs
                                            </a>
                                            <?php if ($activity['status'] !== 'complete'): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-success" 
                                                        onclick="showMarkCompleteModal(<?= $activity['id'] ?>, '<?= esc($activity['title']) ?>')">
                                                    <i class="fas fa-check"></i> Mark Complete
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-secondary" disabled>
                                                    <i class="fas fa-check-circle"></i> Completed
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
                    <i class="fas fa-info-circle"></i> No supervised activities assigned to you at this time.
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

