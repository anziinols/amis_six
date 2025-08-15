<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <div class="btn-group">
                <a href="<?= base_url('workplan-period') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <a href="<?= base_url('workplan-period/' . $workplan_period['id'] . '/kra') ?>" class="btn btn-primary">
                    <i class="fas fa-list"></i> View KRAs
                </a>
                <a href="<?= base_url('workplan-period/' . $workplan_period['id'] . '/edit') ?>" class="btn btn-warning">
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

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <h6 class="text-muted mb-2">Title</h6>
                    <p class="mb-4"><?= esc($workplan_period['title']) ?></p>

                    <?php if ($workplan_period['description']): ?>
                        <h6 class="text-muted mb-2">Description</h6>
                        <p class="mb-4"><?= nl2br(esc($workplan_period['description'])) ?></p>
                    <?php endif; ?>

                    <h6 class="text-muted mb-2">User</h6>
                    <p class="mb-4">
                        <span class="badge bg-info"><?= esc($workplan_period['user_name']) ?></span>
                    </p>

                    <?php if ($workplan_period['duty_instruction_title']): ?>
                        <h6 class="text-muted mb-2">Duty Instruction</h6>
                        <p class="mb-4">
                            <span class="badge bg-secondary"><?= esc($workplan_period['duty_instruction_title']) ?></span>
                            <?php if ($workplan_period['duty_instruction_number']): ?>
                                <br><small class="text-muted">Number: <?= esc($workplan_period['duty_instruction_number']) ?></small>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>

                    <?php if (isset($workplan_period['workplan_period_filepath']) && $workplan_period['workplan_period_filepath']): ?>
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Attached File</h6>
                            <a href="<?= base_url($workplan_period['workplan_period_filepath']) ?>" target="_blank" class="btn btn-outline-primary">
                                <i class="fas fa-file"></i> View Workplan Period File
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Status Information</h6>
                            
                            <div class="mb-3">
                                <small class="text-muted">Current Status</small><br>
                                <?php
                                $statusClass = match($workplan_period['status']) {
                                    'pending' => 'bg-warning',
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                                ?>
                                <span class="badge <?= $statusClass ?>"><?= ucfirst(esc($workplan_period['status'])) ?></span>
                            </div>

                            <?php if ($workplan_period['status_by_name']): ?>
                                <div class="mb-3">
                                    <small class="text-muted">Status Updated By</small><br>
                                    <span><?= esc($workplan_period['status_by_name']) ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($workplan_period['status_at']): ?>
                                <div class="mb-3">
                                    <small class="text-muted">Status Date</small><br>
                                    <span><?= date('M d, Y H:i', strtotime($workplan_period['status_at'])) ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($workplan_period['status_remarks']): ?>
                                <div class="mb-3">
                                    <small class="text-muted">Status Remarks</small><br>
                                    <span><?= nl2br(esc($workplan_period['status_remarks'])) ?></span>
                                </div>
                            <?php endif; ?>

                            <hr>

                            <div class="mb-2">
                                <small class="text-muted">Created</small><br>
                                <span><?= date('M d, Y H:i', strtotime($workplan_period['created_at'])) ?></span>
                                <?php if ($workplan_period['created_by_name']): ?>
                                    <br><small>by <?= esc($workplan_period['created_by_name']) ?></small>
                                <?php endif; ?>
                            </div>

                            <?php if ($workplan_period['updated_at'] && $workplan_period['updated_at'] != $workplan_period['created_at']): ?>
                                <div class="mb-2">
                                    <small class="text-muted">Last Updated</small><br>
                                    <span><?= date('M d, Y H:i', strtotime($workplan_period['updated_at'])) ?></span>
                                    <?php if ($workplan_period['updated_by_name']): ?>
                                        <br><small>by <?= esc($workplan_period['updated_by_name']) ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Status Update Form -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <h6 class="card-title">Update Status</h6>
                            <form action="<?= base_url('workplan-period/' . $workplan_period['id'] . '/status') ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="mb-3">
                                    <select class="form-select form-select-sm" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="pending" <?= $workplan_period['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="approved" <?= $workplan_period['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                        <option value="rejected" <?= $workplan_period['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control form-control-sm" name="remarks" placeholder="Status remarks (optional)" rows="2"></textarea>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary w-100">
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
