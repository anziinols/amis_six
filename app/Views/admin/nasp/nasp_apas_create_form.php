<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>
            <a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/apas') ?>" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-arrow-left"></i>
            </a>
            Add New APA for: <?= esc($plan['title']) ?> (<?= esc($plan['code']) ?>)
        </h5>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/apas') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
            
            <div class="mb-3">
                <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="code" name="code" value="<?= old('code') ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="<?= old('title') ?>" required>
            </div>
            
            <div class="mb-3">
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

<?= $this->endSection() ?>
