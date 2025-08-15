<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <div class="btn-group">
                <a href="<?= base_url('performance-output') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <a href="<?= base_url('performance-output/' . $performance_period['id'] . '/kra') ?>" class="btn btn-primary">
                    <i class="fas fa-list"></i> View KRAs
                </a>
                <a href="<?= base_url('performance-output/' . $performance_period['id'] . '/edit') ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Performance Period Title</h6>
                        <h4><?= esc($performance_period['title']) ?></h4>
                    </div>

                    <?php if ($performance_period['description']): ?>
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Description</h6>
                            <p class="text-justify"><?= nl2br(esc($performance_period['description'])) ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Assigned User</h6>
                                <span class="badge bg-info fs-6"><?= esc($performance_period['user_name']) ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Duty Instruction</h6>
                                <?php if ($performance_period['duty_instruction_title']): ?>
                                    <span class="badge bg-secondary fs-6"><?= esc($performance_period['duty_instruction_title']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">Not linked to any duty instruction</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if ($performance_period['performance_period_filepath']): ?>
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Attached File</h6>
                            <a href="<?= base_url($performance_period['performance_period_filepath']) ?>" target="_blank" class="btn btn-outline-primary">
                                <i class="fas fa-file"></i> View Performance Period File
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">Status Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Current Status</small>
                                <?php
                                $statusClass = match($performance_period['status']) {
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    'pending' => 'bg-warning',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <br><span class="badge <?= $statusClass ?> fs-6"><?= ucfirst(esc($performance_period['status'])) ?></span>
                            </div>

                            <?php if ($performance_period['status_by_name']): ?>
                                <div class="mb-3">
                                    <small class="text-muted">Status Updated By</small>
                                    <br><span><?= esc($performance_period['status_by_name']) ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($performance_period['status_at']): ?>
                                <div class="mb-3">
                                    <small class="text-muted">Status Date</small>
                                    <br><span><?= date('M d, Y H:i', strtotime($performance_period['status_at'])) ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($performance_period['status_remarks']): ?>
                                <div class="mb-3">
                                    <small class="text-muted">Status Remarks</small>
                                    <br><span><?= nl2br(esc($performance_period['status_remarks'])) ?></span>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <small class="text-muted">Created By</small>
                                <br><span><?= esc($performance_period['created_by_name']) ?></span>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Created Date</small>
                                <br><span><?= date('M d, Y H:i', strtotime($performance_period['created_at'])) ?></span>
                            </div>

                            <?php if ($performance_period['updated_by_name']): ?>
                                <div class="mb-3">
                                    <small class="text-muted">Last Updated By</small>
                                    <br><span><?= esc($performance_period['updated_by_name']) ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($performance_period['updated_at']): ?>
                                <div class="mb-0">
                                    <small class="text-muted">Last Updated</small>
                                    <br><span><?= date('M d, Y H:i', strtotime($performance_period['updated_at'])) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Status Update Form -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">Update Status</h6>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url('performance-output/' . $performance_period['id'] . '/status') ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <select class="form-select" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="pending" <?= $performance_period['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="approved" <?= $performance_period['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                        <option value="rejected" <?= $performance_period['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control" name="status_remarks" placeholder="Status remarks (optional)" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-save"></i> Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
