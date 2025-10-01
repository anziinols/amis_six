<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id'] . '/indicators') ?>" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left"></i> Back to Indicators
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $indicator['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?> Indicator</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id'] . '/indicators') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Indicators
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <h5>Hierarchy</h5>
                        <p><strong>NASP Plan:</strong> <?= esc($plan['title'] ?? '') ?> (<?= esc($plan['code'] ?? '') ?>)</p>
                        <p><strong>APA:</strong> <?= esc($apa['title']) ?> (<?= esc($apa['code']) ?>)</p>
                        <p><strong>DIP:</strong> <?= esc($dip['title']) ?> (<?= esc($dip['code']) ?>)</p>
                        <p><strong>Specific Area:</strong> <?= esc($specificArea['title']) ?> (<?= esc($specificArea['code']) ?>)</p>
                        <p><strong>Objective:</strong> <?= esc($objective['title']) ?> (<?= esc($objective['code']) ?>)</p>
                        <p><strong>Output:</strong> <?= esc($output['title']) ?> (<?= esc($output['code']) ?>)</p>
                    </div>
                    
                    <div class="alert alert-<?= $indicator['nasp_status'] == 1 ? 'warning' : 'info' ?> mb-4">
                        <h5>Indicator Details</h5>
                        <p><strong>Code:</strong> <?= esc($indicator['code']) ?></p>
                        <p><strong>Title:</strong> <?= esc($indicator['title']) ?></p>
                        <p><strong>Current Status:</strong>
                            <span class="badge bg-<?= $indicator['nasp_status'] == 1 ? 'success' : 'danger' ?>">
                                <?= $indicator['nasp_status'] == 1 ? 'Active' : 'Inactive' ?>
                            </span>
                        </p>
                    </div>

                    <div class="alert alert-<?= $indicator['nasp_status'] == 1 ? 'danger' : 'success' ?> mb-4">
                        <h5>Confirmation</h5>
                        <p>Are you sure you want to <strong><?= $indicator['nasp_status'] == 1 ? 'deactivate' : 'activate' ?></strong> this Indicator?</p>
                        <p>This action will <?= $indicator['nasp_status'] == 1 ? 'hide' : 'make visible' ?> this Indicator in dropdown menus and other parts of the system.</p>
                    </div>

                    <form action="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id'] . '/indicators/' . $indicator['id'] . '/toggle-status') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="form-group">
                            <label for="nasp_status_remarks">Status Change Remarks <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="nasp_status_remarks" name="nasp_status_remarks" rows="3" required></textarea>
                            <small class="form-text text-muted">Please provide a reason for this status change.</small>
                        </div>
                        
                        <div class="mt-4 text-end">
                            <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id'] . '/indicators') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-<?= $indicator['nasp_status'] == 1 ? 'danger' : 'success' ?>">
                                <?= $indicator['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?> Indicator
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
