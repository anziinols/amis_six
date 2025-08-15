<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><?= $title ?></h5>
                <small class="text-muted">Performance Indicator: <?= esc($indicator['item']) ?></small>
                <br><small class="text-muted">Performance Period: <?= esc($performance_period['title']) ?></small>
            </div>
            <a href="<?= base_url('performance-outputs/' . $output['id']) ?>" class="btn btn-secondary">
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

            <form action="<?= base_url('performance-outputs/' . $output['id'] . '/update') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label class="form-label">Performance Indicator</label>
                    <input type="text" class="form-control" value="<?= esc($indicator['item']) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="output" class="form-label">Output/Deliverable <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="output" name="output" value="<?= old('output') ?? esc($output['output']) ?>" required maxlength="255" placeholder="Enter the output/deliverable name">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="quantity" name="quantity" value="<?= old('quantity') ?? esc($output['quantity']) ?>" required maxlength="20" placeholder="e.g., 5, 100, 1">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="unit_of_measurement" class="form-label">Unit of Measurement <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="unit_of_measurement" name="unit_of_measurement" value="<?= old('unit_of_measurement') ?? esc($output['unit_of_measurement']) ?>" required maxlength="255" placeholder="e.g., pieces, documents, trainings">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe the performance output"><?= old('description') ?? esc($output['description']) ?></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('performance-outputs/' . $output['id']) ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Performance Output
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
