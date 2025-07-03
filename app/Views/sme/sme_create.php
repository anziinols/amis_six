<?php
// app/Views/sme/sme_create.php
?>
<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<!-- Add Select2 Bootstrap 5 Theme CSS in the content section -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
<style>
    /* Custom styles for Select2 dropdowns */
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 38px;
        border: 1px solid #ced4da;
    }
    .select2-container--bootstrap-5 .select2-selection--single {
        padding: 0.375rem 0.75rem;
    }
    .select2-container--bootstrap-5 .select2-selection__rendered {
        color: #212529;
    }
    .select2-container--bootstrap-5 .select2-dropdown {
        border-color: #ced4da;
    }
    .select2-container--bootstrap-5 .select2-results__option {
        padding: 0.375rem 0.75rem;
        color: #212529;
    }
    .select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected] {
        background-color: var(--primary-green);
    }
</style>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Create SME</h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('smes') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                
                <!-- Basic Information -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="sme_name">SME Name <span class="text-danger">*</span></label>
                        <input type="text" id="sme_name" name="sme_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="village_name">Village Name</label>
                        <input type="text" id="village_name" name="village_name" class="form-control">
                    </div>
                </div>

                <!-- Location Selection -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="province_id">Province <span class="text-danger">*</span></label>
                        <select id="province_id" name="province_id" class="form-select select2" required>
                            <option value="">Select Province</option>
                            <?php foreach ($provinces as $province): ?>
                                <option value="<?= $province['id'] ?>"><?= esc($province['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="district_id">District <span class="text-danger">*</span></label>
                        <select id="district_id" name="district_id" class="form-select select2" required disabled>
                            <option value="">Select District</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="llg_id">LLG <span class="text-danger">*</span></label>
                        <select id="llg_id" name="llg_id" class="form-select select2" required disabled>
                            <option value="">Select LLG</option>
                        </select>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="contact_details">Contact Details</label>
                        <textarea id="contact_details" name="contact_details" class="form-control" rows="3" 
                                placeholder="Phone numbers, email addresses, etc."></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="gps_coordinates">GPS Coordinates</label>
                        <input type="text" id="gps_coordinates" name="gps_coordinates" class="form-control" 
                               placeholder="e.g., -9.443383, 147.180891">
                        <small class="text-muted">Format: latitude, longitude</small>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label" for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4"
                            placeholder="Describe the SME's business, activities, etc."></textarea>
                </div>

                <!-- Logo Upload -->
                <div class="mb-3">
                    <label class="form-label" for="logo">Logo</label>
                    <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
                    <small class="text-muted">Maximum file size: 10MB. Accepted formats: JPG, PNG, GIF</small>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('smes') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save SME</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize Select2 with Bootstrap 5 theme
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        dropdownParent: $('.card-body')
    });

    // Handle Province Change
    $('#province_id').on('change', function() {
        const provinceId = $(this).val();
        const districtSelect = $('#district_id');
        const llgSelect = $('#llg_id');
        
        // Reset and disable dependent dropdowns
        districtSelect.empty().append('<option value="">Select District</option>').prop('disabled', true);
        llgSelect.empty().append('<option value="">Select LLG</option>').prop('disabled', true);
        
        if (provinceId) {
            // Fetch districts for selected province
            $.get(`<?= base_url('smes/districts') ?>/${provinceId}`, function(districts) {
                districtSelect.prop('disabled', false);
                districts.forEach(function(district) {
                    districtSelect.append(new Option(district.name, district.id));
                });
            });
        }
    });

    // Handle District Change
    $('#district_id').on('change', function() {
        const districtId = $(this).val();
        const llgSelect = $('#llg_id');
        
        // Reset and disable LLG dropdown
        llgSelect.empty().append('<option value="">Select LLG</option>').prop('disabled', true);
        
        if (districtId) {
            // Fetch LLGs for selected district
            $.get(`<?= base_url('smes/llgs') ?>/${districtId}`, function(llgs) {
                llgSelect.prop('disabled', false);
                llgs.forEach(function(llg) {
                    llgSelect.append(new Option(llg.name, llg.id));
                });
            });
        }
    });

    // Optional: GPS Coordinates validation
    $('#gps_coordinates').on('blur', function() {
        const coords = $(this).val();
        if (coords && !coords.match(/^-?\d+\.?\d*,\s*-?\d+\.?\d*$/)) {
            toastr.warning('Please enter GPS coordinates in the format: latitude, longitude');
        }
    });
});
</script>
<?= $this->endSection() ?> 