<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <a href="<?= base_url('performance-indicators-kra/' . $item['id']) ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Details
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

            <form action="<?= base_url('performance-indicators-kra/' . $item['id'] . '/update') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <input type="text" class="form-control" value="<?= $item['type'] === 'kra' ? 'Key Result Area (KRA)' : 'Performance Indicator' ?>" readonly>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="code" class="form-label"><?= $item['type'] === 'kra' ? 'KRA' : 'Performance Indicator' ?> Code (Optional)</label>
                            <input type="text" class="form-control" id="code" name="code" value="<?= old('code') ?? esc($item['code']) ?>" maxlength="100" placeholder="e.g., <?= $item['type'] === 'kra' ? 'KRA-001' : 'PI-001' ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="item" class="form-label"><?= $item['type'] === 'kra' ? 'KRA' : 'Performance Indicator' ?> Item <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="item" name="item" value="<?= old('item') ?? esc($item['item']) ?>" required maxlength="255" placeholder="Enter <?= $item['type'] === 'kra' ? 'KRA' : 'Performance Indicator' ?> title/name">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe the <?= $item['type'] === 'kra' ? 'Key Result Area' : 'Performance Indicator' ?>"><?= old('description') ?? esc($item['description']) ?></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('performance-indicators-kra/' . $item['id']) ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update <?= $item['type'] === 'kra' ? 'KRA' : 'Performance Indicator' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
