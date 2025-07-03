<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<!-- Display flash messages -->
<?php if (session()->has('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Edit NASP Plan: <?= esc($plan['code']) ?></h5>
        <a href="<?= base_url('admin/nasp-plans') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Plans
        </a>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/nasp-plans/' . $plan['id']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="code" name="code" value="<?= old('code', $plan['code']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="<?= old('title', $plan['title']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="date_from" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="<?= old('date_from', $plan['date_from']) ?>">
            </div>

            <div class="mb-3">
                <label for="date_to" class="form-label">End Date</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="<?= old('date_to', $plan['date_to']) ?>">
            </div>

            <div class="mb-3">
                <label for="remarks" class="form-label">Brief Description</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="3"><?= old('remarks', $plan['remarks']) ?></textarea>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="<?= base_url('admin/nasp-plans') ?>" class="btn btn-secondary me-md-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Plan</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>