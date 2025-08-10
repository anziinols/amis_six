<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?= esc($title) ?></h1>
            <p class="mb-0 text-muted">Evaluate and review approved or rated activities and proposals</p>
        </div>
    </div>

    <!-- Flash Messages -->
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

    <!-- Activities Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Activities for Evaluation - Approved or Rated</h5>
            <small class="text-muted">Only activities with approved or rated proposals are shown here</small>
        </div>
        <div class="card-body">
            <?php if (empty($activities)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No approved or rated activities found. Activities will appear here once they have approved proposals or proposals with ratings.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="evaluationTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Activity Code</th>
                                <th>Workplan</th>
                                <th>Activity Title</th>
                                <th>Type</th>
                                <th>Branch</th>
                                <th>Supervisor</th>
                                <th>Rating Status</th>
                                <th>Approved or Rated Proposals</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $counter = 1; ?>
                            <?php foreach ($activities as $activity): ?>
                                <tr>
                                    <td><?= $counter++ ?></td>
                                    <td>
                                        <span class="badge bg-primary"><?= esc($activity['activity_code'] ?? 'N/A') ?></span>
                                    </td>
                                    <td>
                                        <strong><?= esc($activity['workplan_title']) ?></strong>
                                        <br><small class="text-muted">
                                            <?php if (!empty($activity['workplan_start_date'])): ?>
                                                <?= date('Y', strtotime($activity['workplan_start_date'])) ?>
                                                <?php if (!empty($activity['workplan_end_date']) && date('Y', strtotime($activity['workplan_start_date'])) != date('Y', strtotime($activity['workplan_end_date']))): ?>
                                                    - <?= date('Y', strtotime($activity['workplan_end_date'])) ?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </small>
                                    </td>
                                    <td>
                                        <strong><?= esc($activity['title']) ?></strong>
                                        <?php if (!empty($activity['description'])): ?>
                                            <br><small class="text-muted"><?= esc(substr($activity['description'], 0, 100)) ?><?= strlen($activity['description']) > 100 ? '...' : '' ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $activity['activity_type'] === 'training' ? 'primary' : ($activity['activity_type'] === 'inputs' ? 'success' : ($activity['activity_type'] === 'infrastructure' ? 'warning' : 'info')) ?>">
                                            <?= ucfirst(esc($activity['activity_type'])) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($activity['branch_name'] ?? 'N/A') ?></td>
                                    <td><?= esc($activity['supervisor_name'] ?? 'N/A') ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($activity['rating']) && $activity['rating'] > 0): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-star"></i> Rated (<?= $activity['rating'] ?>/5)
                                            </span>
                                            <?php if (!empty($activity['rated_at'])): ?>
                                                <br><small class="text-muted"><?= date('M d, Y', strtotime($activity['rated_at'])) ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i> Not Rated
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success fs-6">
                                            <?= $activity['approved_rated_proposals_count'] ?? 0 ?>
                                        </span>
                                        <br><small class="text-muted">Proposals</small>
                                    </td>
                                    <td>
                                        <small><?= date('M d, Y', strtotime($activity['created_at'])) ?></small>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('evaluation/' . $activity['id']) ?>"
                                           class="btn btn-sm btn-primary" title="View Full Evaluation">
                                            <i class="fas fa-clipboard-check"></i> Evaluate
                                        </a>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize DataTables if the table exists and has data
        if ($('#evaluationTable').length > 0 && $('#evaluationTable tbody tr').length > 0) {
            try {
                $('#evaluationTable').DataTable({
                    responsive: true,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    columnDefs: [
                        { orderable: false, targets: -1 } // Disable sorting on the actions column
                    ],
                    order: [[1, 'asc']] // Default sort by activity code
                });
            } catch (e) {
                console.error("DataTables initialization error:", e);
                // If DataTables fails to initialize, we can still use the table without it
            }
        }
    });
</script>
<?= $this->endSection() ?>
