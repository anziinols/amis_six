<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <div class="btn-group">
                <?php if ($item['type'] === 'kra'): ?>
                    <a href="<?= base_url('performance-output/' . $item['workplan_period_id'] . '/kra') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to KRAs
                    </a>
                    <a href="<?= base_url('performance-output/kra/' . $item['id'] . '/indicators') ?>" class="btn btn-primary">
                        <i class="fas fa-list"></i> View Performance Indicators
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('performance-output/kra/' . $item['parent_id'] . '/indicators') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Indicators
                    </a>
                    <a href="<?= base_url('performance-output/indicators/' . $item['id'] . '/outputs') ?>" class="btn btn-primary">
                        <i class="fas fa-chart-bar"></i> View Performance Outputs
                    </a>
                <?php endif; ?>
                <a href="<?= base_url('performance-indicators-kra/' . $item['id'] . '/edit') ?>" class="btn btn-warning">
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
                        <h6 class="text-muted mb-2"><?= ucfirst($item['type']) === 'Kra' ? 'KRA' : 'Performance Indicator' ?> Title</h6>
                        <h4><?= esc($item['item']) ?></h4>
                    </div>

                    <?php if ($item['code']): ?>
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Code</h6>
                            <span class="badge bg-secondary fs-6"><?= esc($item['code']) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($item['description']): ?>
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Description</h6>
                            <p class="text-justify"><?= nl2br(esc($item['description'])) ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Type</h6>
                                <span class="badge bg-info fs-6"><?= $item['type'] === 'kra' ? 'Key Result Area' : 'Performance Indicator' ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="text-muted mb-2">Performance Period</h6>
                                <span class="badge bg-primary fs-6"><?= esc($item['performance_period_title']) ?></span>
                            </div>
                        </div>
                    </div>

                    <?php if ($item['parent_item']): ?>
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Parent KRA</h6>
                            <span class="badge bg-success fs-6"><?= esc($item['parent_item']) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">User</h6>
                        <span class="badge bg-info fs-6"><?= esc($item['user_name']) ?></span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-header">
                            <h6 class="mb-0">Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">Created By</small>
                                <br><span><?= esc($item['created_by_name']) ?></span>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted">Created Date</small>
                                <br><span><?= date('M d, Y H:i', strtotime($item['created_at'])) ?></span>
                            </div>

                            <?php if ($item['updated_by_name']): ?>
                                <div class="mb-3">
                                    <small class="text-muted">Last Updated By</small>
                                    <br><span><?= esc($item['updated_by_name']) ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($item['updated_at']): ?>
                                <div class="mb-3">
                                    <small class="text-muted">Last Updated</small>
                                    <br><span><?= date('M d, Y H:i', strtotime($item['updated_at'])) ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($item['status_by_name']): ?>
                                <div class="mb-3">
                                    <small class="text-muted">Status Updated By</small>
                                    <br><span><?= esc($item['status_by_name']) ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($item['status_at']): ?>
                                <div class="mb-3">
                                    <small class="text-muted">Status Date</small>
                                    <br><span><?= date('M d, Y H:i', strtotime($item['status_at'])) ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($item['status_remarks']): ?>
                                <div class="mb-0">
                                    <small class="text-muted">Status Remarks</small>
                                    <br><span><?= nl2br(esc($item['status_remarks'])) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
