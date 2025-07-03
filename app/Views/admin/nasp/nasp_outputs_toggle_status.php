<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs') ?>" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-arrow-left"></i> Back to Outputs
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $output['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?> Output</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Outputs
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
                    </div>
                    
                    <div class="alert alert-<?= $output['nasp_status'] == 1 ? 'warning' : 'info' ?> mb-4">
                        <h5>Output Details</h5>
                        <p><strong>Code:</strong> <?= esc($output['code']) ?></p>
                        <p><strong>Title:</strong> <?= esc($output['title']) ?></p>
                        <p><strong>Current Status:</strong>
                            <span class="badge bg-<?= $output['nasp_status'] == 1 ? 'success' : 'danger' ?>">
                                <?= $output['nasp_status'] == 1 ? 'Active' : 'Inactive' ?>
                            </span>
                        </p>
                    </div>

                    <div class="alert alert-<?= $output['nasp_status'] == 1 ? 'danger' : 'success' ?> mb-4">
                        <h5>Confirmation</h5>
                        <p>Are you sure you want to <strong><?= $output['nasp_status'] == 1 ? 'deactivate' : 'activate' ?></strong> this Output?</p>
                        <p>This action will <?= $output['nasp_status'] == 1 ? 'hide' : 'make visible' ?> this Output in dropdown menus and other parts of the system.</p>
                    </div>

                    <form action="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id'] . '/toggle-status') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="form-group">
                            <label for="nasp_status_remarks">Status Change Remarks <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="nasp_status_remarks" name="nasp_status_remarks" rows="3" required></textarea>
                            <small class="form-text text-muted">Please provide a reason for this status change.</small>
                        </div>
                        
                        <div class="mt-4 text-end">
                            <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-<?= $output['nasp_status'] == 1 ? 'danger' : 'success' ?>">
                                <?= $output['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?> Output
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
