<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans') ?>">NASP Plans</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans/apas/' . $dip['parent_id'] . '/dips') ?>">DIPs</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $dip['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?> DIP</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Display flash messages -->
    <?php if (session()->has('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $dip['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?> DIP</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/nasp-plans/apas/' . $dip['parent_id'] . '/dips') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to DIPs
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-<?= $dip['nasp_status'] == 1 ? 'warning' : 'info' ?> mb-4">
                        <h5>DIP Details</h5>
                        <p><strong>Code:</strong> <?= esc($dip['code']) ?></p>
                        <p><strong>Title:</strong> <?= esc($dip['title']) ?></p>
                        <p><strong>Current Status:</strong>
                            <span class="badge bg-<?= $dip['nasp_status'] == 1 ? 'success' : 'danger' ?>">
                                <?= $dip['nasp_status'] == 1 ? 'Active' : 'Inactive' ?>
                            </span>
                        </p>
                        <p>You are about to <strong><?= $dip['nasp_status'] == 1 ? 'deactivate' : 'activate' ?></strong> this DIP.</p>
                    </div>

                    <form method="post" action="<?= base_url('admin/nasp-plans/apas/' . $dip['parent_id'] . '/dips/' . $dip['id'] . '/toggle-status') ?>">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label for="nasp_status_remarks" class="form-label">Status Change Remarks <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="nasp_status_remarks" name="nasp_status_remarks" rows="4" required autofocus></textarea>
                            <div class="form-text">Please provide a detailed reason for changing the status of this DIP.</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= base_url('admin/nasp-plans/apas/' . $dip['parent_id'] . '/dips') ?>" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-<?= $dip['nasp_status'] == 1 ? 'danger' : 'success' ?>">
                                <?= $dip['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?> DIP
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
