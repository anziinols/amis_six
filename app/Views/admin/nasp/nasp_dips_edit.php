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
        <h5>Edit DIP: <?= esc($dip['code']) ?></h5>
        <a href="<?= base_url('admin/nasp-plans/apas/' . $dip['parent_id'] . '/dips') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to DIPs
        </a>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/nasp-plans/apas/' . $dip['parent_id'] . '/dips/' . $dip['id']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="code" name="code" value="<?= old('code', $dip['code']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="<?= old('title', $dip['title']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="3"><?= old('remarks', $dip['remarks']) ?></textarea>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="<?= base_url('admin/nasp-plans/apas/' . $dip['parent_id'] . '/dips') ?>" class="btn btn-secondary me-md-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update DIP</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
