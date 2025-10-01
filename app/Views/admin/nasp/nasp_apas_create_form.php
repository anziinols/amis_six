<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans') ?>">NASP Plans</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/apas') ?>">APAs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New APA</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Add New APA for: <?= esc($plan['title']) ?> (<?= esc($plan['code']) ?>)</h3>
                    <div>
                        <a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/apas') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to APAs
                        </a>
                    </div>
                </div>
    <div class="card-body">
        <form action="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/apas') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
            
            <div class="form-group mb-3">
                <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="code" name="code" value="<?= old('code') ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="<?= old('title') ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="3"><?= old('remarks') ?></textarea>
            </div>
            
            <div class="d-flex justify-content-end">
                <a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/apas') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Create APA</button>
            </div>
        </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
