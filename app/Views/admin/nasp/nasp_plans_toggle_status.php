<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans') ?>">NASP Plans</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $plan['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?> Plan</li>
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
                    <h3 class="card-title"><?= $plan['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?> NASP Plan</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/nasp-plans') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Plans
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-<?= $plan['nasp_status'] == 1 ? 'warning' : 'info' ?> mb-4">
                        <h5>Plan Details</h5>
                        <p><strong>Code:</strong> <?= esc($plan['code']) ?></p>
                        <p><strong>Title:</strong> <?= esc($plan['title']) ?></p>
                        <p><strong>Current Status:</strong>
                            <span class="badge bg-<?= $plan['nasp_status'] == 1 ? 'success' : 'danger' ?>">
                                <?= $plan['nasp_status'] == 1 ? 'Active' : 'Inactive' ?>
                            </span>
                        </p>
                        <p>You are about to <strong><?= $plan['nasp_status'] == 1 ? 'deactivate' : 'activate' ?></strong> this NASP plan.</p>
                    </div>

                    <form method="post" action="">
                        <?= csrf_field() ?>
                        <input type="hidden" name="form_submitted" value="yes">
                        <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">

                        <div class="mb-4">
                            <label for="nasp_status_remarks" class="form-label">Status Change Remarks <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="nasp_status_remarks" name="nasp_status_remarks" rows="4" required autofocus></textarea>
                            <div class="form-text">Please provide a detailed reason for changing the status of this NASP plan.</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= base_url('admin/nasp-plans') ?>" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" name="submit_toggle" value="1" class="btn btn-<?= $plan['nasp_status'] == 1 ? 'danger' : 'success' ?>">
                                <?= $plan['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?> Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>