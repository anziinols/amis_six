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
                    <h5 class="card-title mb-0">Evaluate Input Activity: <?= esc($activity['activity_title']) ?></h5>
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
                                            <p class="mb-1"><strong>Type:</strong> <span class="badge bg-success"><?= ucfirst(esc($activity['type'])) ?></span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Performance Output:</strong> <?= esc($activity['performance_output_title'] ?? 'N/A') ?></p>
                                            <p class="mb-1"><strong>Location:</strong> <?= esc($activity['location'] ?? 'N/A') ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Action Officer:</strong> <?= esc($activity['action_officer_name'] ?? 'N/A') ?></p>
                                            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-success"><?= ucfirst(esc($activity['status'])) ?></span></p>
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
                                        <p>Input activity implementation details will be displayed here.</p>
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

                    <!-- Evaluation Form -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-star me-2"></i>Activity Evaluation
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <form action="<?= base_url('activities/' . $activity['id'] . '/process-evaluation') ?>" method="post">
                                        <?= csrf_field() ?>
                                        
                                        <div class="mb-3">
                                            <label for="rating_score" class="form-label">Rating Score <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="rating_score" name="rating_score" 
                                                       min="0" max="10" step="0.1" required 
                                                       placeholder="Enter score (0-10)" 
                                                       value="<?= old('rating_score', $activity['rating_score'] ?? '') ?>">
                                                <span class="input-group-text">/10</span>
                                            </div>
                                            <div class="form-text">
                                                Rate the activity implementation quality from 0 to 10 (decimals allowed, e.g., 8.5)
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="rate_remarks" class="form-label">Evaluation Remarks</label>
                                            <textarea class="form-control" id="rate_remarks" name="rate_remarks" rows="4" 
                                                      placeholder="Enter your evaluation comments and feedback (optional)"><?= old('rate_remarks', $activity['rate_remarks'] ?? '') ?></textarea>
                                            <div class="form-text">Provide detailed feedback on the activity implementation</div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="fas fa-star me-1"></i> Submit Rating
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
