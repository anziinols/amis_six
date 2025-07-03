<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <div>
                <a href="<?= base_url('commodity-boards/' . $production['id']) ?>" class="btn btn-info me-2">
                    <i class="fas fa-eye"></i> View
                </a>
                <a href="<?= base_url('commodity-boards') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->get('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session()->get('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('commodity-boards/' . $production['id']) ?>" method="post">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Assigned Commodity</label>
                            <div class="form-control-plaintext bg-light p-2 rounded">
                                <span class="badge bg-secondary fs-6"><?= esc($user_commodity['commodity_code']) ?></span>
                                <strong class="ms-2"><?= esc($user_commodity['commodity_name']) ?></strong>
                            </div>
                            <small class="form-text text-muted">This production record is assigned to your commodity</small>
                        </div>

                        <div class="mb-3">
                            <label for="item" class="form-label">Item/Product <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="item" name="item"
                                   value="<?= old('item', $production['item']) ?>" required maxlength="255"
                                   placeholder="Enter item or product name">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                      placeholder="Enter additional description (optional)"><?= old('description', $production['description']) ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="quantity" name="quantity"
                                           value="<?= old('quantity', $production['quantity']) ?>" required step="0.01" min="0"
                                           placeholder="Enter quantity">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="unit_of_measurement" class="form-label">Unit of Measurement</label>
                                    <input type="text" class="form-control" id="unit_of_measurement" name="unit_of_measurement"
                                           value="<?= old('unit_of_measurement', $production['unit_of_measurement']) ?>" maxlength="50"
                                           placeholder="e.g., kg, tons, bags, etc.">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_from" class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_from" name="date_from"
                                           value="<?= old('date_from', $production['date_from']) ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_to" class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_to" name="date_to"
                                           value="<?= old('date_to', $production['date_to']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Export Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_exported" name="is_exported" value="1"
                                       <?= old('is_exported', $production['is_exported']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_exported">
                                    This production is for export
                                </label>
                            </div>
                            <small class="form-text text-muted">Check this box if the production is intended for export markets</small>
                        </div>

                        <!-- Record Information -->
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-info-circle text-info"></i> Record Information
                                </h6>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Record ID:</small><br>
                                        <span class="badge bg-dark">#<?= $production['id'] ?></span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Created:</small><br>
                                        <small><?= date('M d, Y', strtotime($production['created_at'])) ?></small>
                                    </div>
                                </div>
                                <?php if (!empty($production['created_by_name'])): ?>
                                    <div class="mt-2">
                                        <small class="text-muted">Created by: <?= esc($production['created_by_name']) ?></small>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($production['updated_at']) && $production['updated_at'] != $production['created_at']): ?>
                                    <div class="mt-1">
                                        <small class="text-muted">
                                            Last updated: <?= date('M d, Y', strtotime($production['updated_at'])) ?>
                                            <?php if (!empty($production['updated_by_name'])): ?>
                                                by <?= esc($production['updated_by_name']) ?>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="<?= base_url('commodity-boards/' . $production['id']) ?>" class="btn btn-secondary me-2">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <a href="<?= base_url('commodity-boards/' . $production['id'] . '/delete') ?>"
                                   class="btn btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this production record? This action cannot be undone.')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Production Record
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Date validation
    const dateFrom = document.getElementById('date_from');
    const dateTo = document.getElementById('date_to');

    function validateDates() {
        if (dateFrom.value && dateTo.value) {
            if (new Date(dateTo.value) < new Date(dateFrom.value)) {
                dateTo.setCustomValidity('End date must be after or equal to start date');
            } else {
                dateTo.setCustomValidity('');
            }
        }
    }

    dateFrom.addEventListener('change', validateDates);
    dateTo.addEventListener('change', validateDates);

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const quantity = document.getElementById('quantity').value;
        if (parseFloat(quantity) <= 0) {
            e.preventDefault();
            alert('Quantity must be greater than 0');
            return false;
        }

        validateDates();
        if (dateTo.validationMessage) {
            e.preventDefault();
            alert(dateTo.validationMessage);
            return false;
        }
    });

    // Highlight changes
    const originalValues = {
        item: '<?= addslashes($production['item']) ?>',
        description: '<?= addslashes($production['description']) ?>',
        quantity: '<?= $production['quantity'] ?>',
        unit_of_measurement: '<?= addslashes($production['unit_of_measurement']) ?>',
        date_from: '<?= $production['date_from'] ?>',
        date_to: '<?= $production['date_to'] ?>',
        is_exported: <?= $production['is_exported'] ? 'true' : 'false' ?>
    };

    function checkForChanges() {
        let hasChanges = false;

        Object.keys(originalValues).forEach(function(field) {
            const element = document.getElementById(field);
            if (element) {
                let currentValue = element.value;
                if (element.type === 'checkbox') {
                    currentValue = element.checked;
                }

                if (currentValue != originalValues[field]) {
                    element.style.borderColor = '#ffc107';
                    element.style.backgroundColor = '#fff3cd';
                    hasChanges = true;
                } else {
                    element.style.borderColor = '';
                    element.style.backgroundColor = '';
                }
            }
        });

        // Update submit button text if there are changes
        const submitBtn = document.querySelector('button[type="submit"]');
        if (hasChanges) {
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
            submitBtn.classList.add('btn-warning');
            submitBtn.classList.remove('btn-primary');
        } else {
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Production Record';
            submitBtn.classList.add('btn-primary');
            submitBtn.classList.remove('btn-warning');
        }
    }

    // Add change listeners to all form fields
    Object.keys(originalValues).forEach(function(field) {
        const element = document.getElementById(field);
        if (element) {
            element.addEventListener('input', checkForChanges);
            element.addEventListener('change', checkForChanges);
        }
    });
});
</script>
<?= $this->endSection() ?>
