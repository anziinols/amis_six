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
                    <li class="breadcrumb-item"><a href="<?= base_url('evaluation/workplan/' . $workplanActivity['workplan_id'] . '/activities') ?>">Workplan Activities</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Linked Activities</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
            <a href="<?= base_url('evaluation/workplan/' . $workplanActivity['workplan_id'] . '/activities') ?>" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Back to Activities
            </a>
            <?php if (empty($workplanActivity['rating']) || $workplanActivity['rating'] === null || $workplanActivity['rating'] === 0): ?>
                <button type="button"
                        class="btn btn-warning"
                        onclick="showRateActivityModal(<?= $workplanActivity['id'] ?>, '<?= esc($workplanActivity['title']) ?>', <?= $workplanActivity['rating'] ?? 0 ?>, '<?= esc($workplanActivity['reated_remarks'] ?? '') ?>')">
                    <i class="fas fa-star"></i> Rate Activity
                </button>
            <?php else: ?>
                <button type="button"
                        class="btn btn-success"
                        onclick="showRateActivityModal(<?= $workplanActivity['id'] ?>, '<?= esc($workplanActivity['title']) ?>', <?= $workplanActivity['rating'] ?? 0 ?>, '<?= esc($workplanActivity['reated_remarks'] ?? '') ?>')">
                    <i class="fas fa-edit"></i> Update Rating
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

    <!-- Workplan Activity Details Card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-info-circle"></i> Workplan Activity Details
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
                </div>
                <div class="col-md-6">
                    <p><strong>Branch:</strong> <?= esc($workplanActivity['branch_name'] ?? 'N/A') ?></p>
                    <p><strong>Target Output:</strong> <?= esc($workplanActivity['target_output'] ?? 'N/A') ?></p>
                    <p><strong>Budget:</strong> 
                        <?php if (!empty($workplanActivity['total_budget'])): ?>
                            K <?= number_format($workplanActivity['total_budget'], 2) ?>
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </p>
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
            
            <!-- Rating Information -->
            <?php if (isset($workplanActivity['rating']) && $workplanActivity['rating'] !== null && $workplanActivity['rating'] !== ''): ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-success">
                            <h6><i class="fas fa-star"></i> Current Rating</h6>
                            <p><strong>Rating:</strong> <span class="badge bg-success"><?= esc($workplanActivity['rating']) ?>%</span></p>
                            <?php if (!empty($workplanActivity['reated_remarks'])): ?>
                                <p><strong>Remarks:</strong> <?= esc($workplanActivity['reated_remarks']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($workplanActivity['rated_by_name'])): ?>
                                <p class="mb-0">
                                    <small class="text-muted">
                                        Rated by <?= esc($workplanActivity['rated_by_name']) ?>
                                        <?php if (!empty($workplanActivity['rated_at'])): ?>
                                            on <?= date('M d, Y \a\t h:i A', strtotime($workplanActivity['rated_at'])) ?>
                                        <?php endif; ?>
                                    </small>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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
                                <th style="width: 5%;">#</th>
                                <th style="width: 20%;">Activity Title</th>
                                <th style="width: 10%;">Type</th>
                                <th style="width: 15%;">Location</th>
                                <th style="width: 12%;">Date Range</th>
                                <th style="width: 12%;">Action Officer</th>
                                <th style="width: 10%;">Cost</th>
                                <th style="width: 10%;">Status</th>
                                <th style="width: 6%;">Actions</th>
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
                                            <?= CURRENCY_SYMBOL ?> <?= number_format($linkedActivity['total_cost'], 2) ?>
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
                                        <?php if (!empty($linkedActivity['status_at'])): ?>
                                            <br>
                                            <small class="text-muted">
                                                <?= date('M d, Y', strtotime($linkedActivity['status_at'])) ?>
                                            </small>
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
                    <i class="fas fa-info-circle"></i> No activities have been linked to this workplan activity yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Rate Activity Modal -->
<div class="modal fade" id="rateActivityModal" tabindex="-1" aria-labelledby="rateActivityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="rateActivityForm">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="rateActivityModalLabel">
                        <i class="fas fa-star"></i> Rate Activity
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Rate the following activity:</p>
                    <div class="alert alert-info">
                        <strong id="activityTitle"></strong>
                    </div>
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating (%) <span class="text-danger">*</span></label>
                        <select class="form-select" id="rating" name="rating" required>
                            <option value="">Select Rating</option>
                            <option value="0">0%</option>
                            <option value="10">10%</option>
                            <option value="20">20%</option>
                            <option value="30">30%</option>
                            <option value="40">40%</option>
                            <option value="50">50%</option>
                            <option value="60">60%</option>
                            <option value="70">70%</option>
                            <option value="80">80%</option>
                            <option value="90">90%</option>
                            <option value="100">100%</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="rating_remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" 
                                  id="rating_remarks" 
                                  name="rating_remarks" 
                                  rows="4" 
                                  placeholder="Add your evaluation remarks and comments..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-star"></i> Submit Rating
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

    function showRateActivityModal(activityId, activityTitle, currentRating, currentRemarks) {
        // Set the activity title in the modal
        $('#activityTitle').text(activityTitle);
        
        // Set the form action
        $('#rateActivityForm').attr('action', '<?= base_url('evaluation/rate-activity') ?>/' + activityId);
        
        // Set current rating if exists
        if (currentRating > 0) {
            $('#rating').val(currentRating);
        } else {
            $('#rating').val('');
        }
        
        // Set current remarks if exists
        $('#rating_remarks').val(currentRemarks);
        
        // Show the modal
        $('#rateActivityModal').modal('show');
    }
</script>
<?= $this->endSection() ?>

