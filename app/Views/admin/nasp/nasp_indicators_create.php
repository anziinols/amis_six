<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>
            <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id'] . '/indicators') ?>" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-arrow-left"></i>
            </a>
            Add New Indicator for Output: <?= esc($output['title']) ?> (<?= esc($output['code']) ?>)
        </h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info mb-3">
            <strong>NASP Plan:</strong> <?= esc($plan['title']) ?> (<?= esc($plan['code']) ?>)<br>
            <strong>APA:</strong> <?= esc($apa['title']) ?> (<?= esc($apa['code']) ?>)<br>
            <strong>DIP:</strong> <?= esc($dip['title']) ?> (<?= esc($dip['code']) ?>)<br>
            <strong>Specific Area:</strong> <?= esc($specificArea['title']) ?> (<?= esc($specificArea['code']) ?>)<br>
            <strong>Objective:</strong> <?= esc($objective['title']) ?> (<?= esc($objective['code']) ?>)<br>
            <strong>Output:</strong> <?= esc($output['title']) ?> (<?= esc($output['code']) ?>)
        </div>
        
        <form action="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id'] . '/indicators') ?>" method="post">
            <?= csrf_field() ?>

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
                <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id'] . '/indicators') ?>" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Indicator</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
