<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><?= $title ?></h5>
                <small class="text-muted">KRA: <?= esc($kra['item']) ?></small>
                <br><small class="text-muted">Performance Period: <?= esc($performance_period['title']) ?></small>
            </div>
            <a href="<?= base_url('performance-output/kra/' . $kra['id'] . '/indicators') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Indicators
            </a>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('performance-output/kra/' . $kra['id'] . '/indicators/create') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="code" class="form-label">Performance Indicator Code (Optional)</label>
                            <input type="text" class="form-control" id="code" name="code" value="<?= old('code') ?>" maxlength="100" placeholder="e.g., PI-001">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="item" class="form-label">Performance Indicator <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="item" name="item" value="<?= old('item') ?>" required maxlength="255" placeholder="Enter Performance Indicator title/name">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe the Performance Indicator"><?= old('description') ?></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('performance-output/kra/' . $kra['id'] . '/indicators') ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Performance Indicator
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
