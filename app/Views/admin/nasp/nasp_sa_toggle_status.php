<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas') ?>" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-arrow-left"></i> Back to Specific Areas
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $specificArea['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?> Specific Area</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Specific Areas
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-<?= $specificArea['nasp_status'] == 1 ? 'warning' : 'info' ?> mb-4">
                        <h5>Specific Area Details</h5>
                        <p><strong>Code:</strong> <?= esc($specificArea['code']) ?></p>
                        <p><strong>Title:</strong> <?= esc($specificArea['title']) ?></p>
                        <p><strong>Current Status:</strong>
                            <span class="badge bg-<?= $specificArea['nasp_status'] == 1 ? 'success' : 'danger' ?>">
                                <?= $specificArea['nasp_status'] == 1 ? 'Active' : 'Inactive' ?>
                            </span>
                        </p>
                    </div>

                    <div class="alert alert-<?= $specificArea['nasp_status'] == 1 ? 'danger' : 'success' ?> mb-4">
                        <h5>Confirmation</h5>
                        <p>Are you sure you want to <strong><?= $specificArea['nasp_status'] == 1 ? 'deactivate' : 'activate' ?></strong> this Specific Area?</p>
                        <p>This action will <?= $specificArea['nasp_status'] == 1 ? 'hide' : 'make visible' ?> this Specific Area in dropdown menus and other parts of the system.</p>
                    </div>

                    <form action="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/toggle-status') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="form-group">
                            <label for="nasp_status_remarks">Status Change Remarks <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="nasp_status_remarks" name="nasp_status_remarks" rows="3" required></textarea>
                            <small class="form-text text-muted">Please provide a reason for this status change.</small>
                        </div>
                        
                        <div class="mt-4 text-end">
                            <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-<?= $specificArea['nasp_status'] == 1 ? 'danger' : 'success' ?>">
                                <?= $specificArea['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?> Specific Area
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
