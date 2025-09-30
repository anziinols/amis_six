<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="mb-3">
        <a href="<?= base_url('activities') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Activities
        </a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Supervise Infrastructure Activity: <?= esc($activity['activity_title']) ?></h5>
                </div>
                <div class="card-body">
                    <!-- Activity Reference Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Activity Reference Information</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Title:</strong> <?= esc($activity['activity_title']) ?></p>
                                            <p class="mb-1"><strong>Type:</strong> <span class="badge bg-warning"><?= ucfirst(esc($activity['type'])) ?></span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Location:</strong> <?= esc($activity['location'] ?? 'N/A') ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Action Officer:</strong> <?= esc($activity['action_officer_name'] ?? 'N/A') ?></p>
                                            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-info text-dark"><?= ucfirst(esc($activity['status'])) ?></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Implementation Details -->
                    <?php if ($implementationData): ?>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Implementation Details</h6>
                                <div class="card">
                                    <div class="card-body">
                                        <p>Infrastructure activity implementation details will be displayed here.</p>
                                        <div class="text-muted">
                                            <small>Implemented on: <?= date('d M Y H:i', strtotime($implementationData['created_at'])) ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No implementation data found for this activity.
                        </div>
                    <?php endif; ?>

                    <!-- Supervision Decision Form -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="card-title mb-0">Supervision Decision</h6>
                                </div>
                                <div class="card-body">
                                    <form action="<?= base_url('activities/' . $activity['id'] . '/process-supervision') ?>" method="post">
                                        <?= csrf_field() ?>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Decision <span class="text-danger">*</span></label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="supervision_decision" id="approve" value="approve" required>
                                                <label class="form-check-label" for="approve">
                                                    <i class="fas fa-check-circle text-success me-1"></i> <strong>Approve</strong> - Activity implementation is satisfactory
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="supervision_decision" id="resend" value="resend" required>
                                                <label class="form-check-label" for="resend">
                                                    <i class="fas fa-redo text-warning me-1"></i> <strong>Resend</strong> - Activity needs to be re-implemented
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="status_remarks" class="form-label">Remarks</label>
                                            <textarea class="form-control" id="status_remarks" name="status_remarks" rows="3" placeholder="Enter your supervision remarks (optional)"></textarea>
                                            <div class="form-text">Provide feedback or reasons for your decision</div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="fas fa-paper-plane me-1"></i> Submit Decision
                                            </button>
                                            <a href="<?= base_url('activities') ?>" class="btn btn-secondary">
                                                <i class="fas fa-times me-1"></i> Cancel
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
