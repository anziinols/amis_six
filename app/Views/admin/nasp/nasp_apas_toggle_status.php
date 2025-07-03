<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans') ?>">NASP Plans</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans/' . $apa['parent_id'] . '/apas') ?>">APAs</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $apa['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?> APA</li>
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
                    <h3 class="card-title"><?= $apa['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?> APA</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/nasp-plans/' . $apa['parent_id'] . '/apas') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to APAs
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-<?= $apa['nasp_status'] == 1 ? 'warning' : 'info' ?> mb-4">
                        <h5>APA Details</h5>
                        <p><strong>Code:</strong> <?= esc($apa['code']) ?></p>
                        <p><strong>Title:</strong> <?= esc($apa['title']) ?></p>
                        <p><strong>Current Status:</strong>
                            <span class="badge bg-<?= $apa['nasp_status'] == 1 ? 'success' : 'danger' ?>">
                                <?= $apa['nasp_status'] == 1 ? 'Active' : 'Inactive' ?>
                            </span>
                        </p>
                        <p>You are about to <strong><?= $apa['nasp_status'] == 1 ? 'deactivate' : 'activate' ?></strong> this APA.</p>
                    </div>

                    <form method="post" action="">
                        <?= csrf_field() ?>

                        <div class="mb-4">
                            <label for="nasp_status_remarks" class="form-label">Status Change Remarks <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="nasp_status_remarks" name="nasp_status_remarks" rows="4" required autofocus></textarea>
                            <div class="form-text">Please provide a detailed reason for changing the status of this APA.</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= base_url('admin/nasp-plans/' . $apa['parent_id'] . '/apas') ?>" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-<?= $apa['nasp_status'] == 1 ? 'danger' : 'success' ?>">
                                <?= $apa['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?> APA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
