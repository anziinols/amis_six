<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <a href="<?= base_url('activities') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Activities
            </a>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?= form_open('activities/create', ['class' => 'needs-validation', 'novalidate' => true]) ?>
                <div class="row">
                    <!-- Activity Title -->
                    <div class="col-md-12 mb-3">
                        <label for="activity_title" class="form-label">Activity Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= $validation->hasError('activity_title') ? 'is-invalid' : '' ?>" 
                               id="activity_title" name="activity_title" value="<?= old('activity_title') ?>" 
                               maxlength="500" required>
                        <div class="invalid-feedback">
                            <?= $validation->getError('activity_title') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Activity Description -->
                    <div class="col-md-12 mb-3">
                        <label for="activity_description" class="form-label">Activity Description <span class="text-danger">*</span></label>
                        <textarea class="form-control <?= $validation->hasError('activity_description') ? 'is-invalid' : '' ?>" 
                                  id="activity_description" name="activity_description" rows="4" required><?= old('activity_description') ?></textarea>
                        <div class="invalid-feedback">
                            <?= $validation->getError('activity_description') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Activity Type -->
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">Activity Type <span class="text-danger">*</span></label>
                        <select class="form-select <?= $validation->hasError('type') ? 'is-invalid' : '' ?>" 
                                id="type" name="type" required>
                            <option value="">Select Activity Type</option>
                            <?php foreach ($activity_types as $key => $type): ?>
                                <option value="<?= $key ?>" <?= old('type') == $key ? 'selected' : '' ?>>
                                    <?= esc($type) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?= $validation->getError('type') ?>
                        </div>
                    </div>

                    <!-- Total Cost -->
                    <div class="col-md-6 mb-3">
                        <label for="total_cost" class="form-label">Total Cost</label>
                        <input type="number" step="0.01" class="form-control <?= $validation->hasError('total_cost') ? 'is-invalid' : '' ?>" 
                               id="total_cost" name="total_cost" value="<?= old('total_cost') ?>">
                        <div class="invalid-feedback">
                            <?= $validation->getError('total_cost') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Province -->
                    <div class="col-md-6 mb-3">
                        <label for="province_id" class="form-label">Province <span class="text-danger">*</span></label>
                        <select class="form-select <?= $validation->hasError('province_id') ? 'is-invalid' : '' ?>" 
                                id="province_id" name="province_id" required>
                            <option value="">Select Province</option>
                            <?php foreach ($provinces as $province): ?>
                                <option value="<?= $province['id'] ?>" <?= old('province_id') == $province['id'] ? 'selected' : '' ?>>
                                    <?= esc($province['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?= $validation->getError('province_id') ?>
                        </div>
                    </div>

                    <!-- District -->
                    <div class="col-md-6 mb-3">
                        <label for="district_id" class="form-label">District <span class="text-danger">*</span></label>
                        <select class="form-select <?= $validation->hasError('district_id') ? 'is-invalid' : '' ?>" 
                                id="district_id" name="district_id" required>
                            <option value="">Select District</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= $validation->getError('district_id') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Location -->
                    <div class="col-md-12 mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" class="form-control <?= $validation->hasError('location') ? 'is-invalid' : '' ?>" 
                               id="location" name="location" value="<?= old('location') ?>" maxlength="255">
                        <div class="invalid-feedback">
                            <?= $validation->getError('location') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Start Date -->
                    <div class="col-md-6 mb-3">
                        <label for="date_start" class="form-label">Start Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control <?= $validation->hasError('date_start') ? 'is-invalid' : '' ?>" 
                               id="date_start" name="date_start" value="<?= old('date_start') ?>" required>
                        <div class="invalid-feedback">
                            <?= $validation->getError('date_start') ?>
                        </div>
                    </div>

                    <!-- End Date -->
                    <div class="col-md-6 mb-3">
                        <label for="date_end" class="form-label">End Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control <?= $validation->hasError('date_end') ? 'is-invalid' : '' ?>" 
                               id="date_end" name="date_end" value="<?= old('date_end') ?>" required>
                        <div class="invalid-feedback">
                            <?= $validation->getError('date_end') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Supervisor -->
                    <div class="col-md-12 mb-3">
                        <label for="supervisor_id" class="form-label">Supervisor</label>
                        <select class="form-select <?= $validation->hasError('supervisor_id') ? 'is-invalid' : '' ?>" 
                                id="supervisor_id" name="supervisor_id">
                            <option value="">Select Supervisor</option>
                            <?php foreach ($supervisors as $supervisor): ?>
                                <option value="<?= $supervisor['id'] ?>" <?= old('supervisor_id') == $supervisor['id'] ? 'selected' : '' ?>>
                                    <?= esc($supervisor['fname'] . ' ' . $supervisor['lname']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?= $validation->getError('supervisor_id') ?>
                        </div>
                    </div>

                    <!-- Action Officer (Hidden - automatically set to logged in user) -->
                    <input type="hidden" name="action_officer_id" value="<?= session()->get('user_id') ?>">
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Activity
                        </button>
                        <a href="<?= base_url('activities') ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Handle province change to populate districts
    $('#province_id').change(function() {
        var provinceId = $(this).val();
        var districtSelect = $('#district_id');
        
        // Clear districts
        districtSelect.html('<option value="">Select District</option>');
        
        if (provinceId) {
            $.get('<?= base_url('activities/get-districts') ?>/' + provinceId)
                .done(function(data) {
                    if (data && data.length > 0) {
                        $.each(data, function(index, district) {
                            districtSelect.append(
                                '<option value="' + district.id + '">' + district.name + '</option>'
                            );
                        });
                    }
                })
                .fail(function() {
                    console.error('Failed to load districts');
                });
        }
    });

    // Form validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
});
</script>
<?= $this->endSection() ?>
