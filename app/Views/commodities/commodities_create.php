<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <a href="<?= base_url('commodity-boards') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        <div class="card-body">
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

            <form action="<?= base_url('commodity-boards') ?>" method="post">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Assigned Commodity</label>
                            <div class="form-control-plaintext bg-light p-2 rounded">
                                <span class="badge bg-secondary fs-6"><?= esc($user_commodity['commodity_code']) ?></span>
                                <strong class="ms-2"><?= esc($user_commodity['commodity_name']) ?></strong>
                            </div>
                            <small class="form-text text-muted">This production record will be automatically assigned to your commodity</small>
                        </div>

                        <div class="mb-3">
                            <label for="item" class="form-label">Item/Product <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="item" name="item"
                                   value="<?= old('item') ?>" required maxlength="255"
                                   placeholder="Enter item or product name">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                      placeholder="Enter additional description (optional)"><?= old('description') ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="quantity" name="quantity"
                                           value="<?= old('quantity') ?>" required step="0.01" min="0"
                                           placeholder="Enter quantity">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="unit_of_measurement" class="form-label">Unit of Measurement</label>
                                    <input type="text" class="form-control" id="unit_of_measurement" name="unit_of_measurement"
                                           value="<?= old('unit_of_measurement') ?>" maxlength="50"
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
                                           value="<?= old('date_from') ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_to" class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="date_to" name="date_to"
                                           value="<?= old('date_to') ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Export Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_exported" name="is_exported" value="1"
                                       <?= old('is_exported') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_exported">
                                    This production is for export
                                </label>
                            </div>
                            <small class="form-text text-muted">Check this box if the production is intended for export markets</small>
                        </div>

                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-info-circle text-info"></i> Production Record Guidelines
                                </h6>
                                <ul class="mb-0 small">
                                    <li>Ensure all required fields are filled accurately</li>
                                    <li>End date must be after or equal to start date</li>
                                    <li>Quantity should be entered in the specified unit of measurement</li>
                                    <li>Use clear and descriptive item names</li>
                                    <li>Mark export status appropriately for proper tracking</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('commodity-boards') ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Production Record
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

    // Auto-set end date when start date is selected
    dateFrom.addEventListener('change', function() {
        if (this.value && !dateTo.value) {
            dateTo.value = this.value;
        }
    });

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
});
</script>
<?= $this->endSection() ?>
