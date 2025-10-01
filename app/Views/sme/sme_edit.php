<?php
// app/Views/sme/sme_edit.php
?>
<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<!-- Add Select2 Bootstrap 5 Theme CSS and Leaflet CSS in the content section -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
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
    /* Map container */
    #map {
        height: 300px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
</style>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('smes') ?>">SMEs</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('smes/' . $sme['id']) ?>"><?= esc($sme['sme_name']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Edit SME</h3>
                    <a href="<?= base_url('smes/' . $sme['id']) ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to SME
                    </a>
                </div>
        <div class="card-body">
            <form action="<?= base_url('smes/' . $sme['id']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <!-- Basic Information -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="sme_name">SME Name <span class="text-danger">*</span></label>
                        <input type="text" id="sme_name" name="sme_name" class="form-control" value="<?= esc($sme['sme_name']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="village_name">Village Name</label>
                        <input type="text" id="village_name" name="village_name" class="form-control" value="<?= esc($sme['village_name']) ?>">
                    </div>
                </div>

                <!-- Location Selection -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="province_id">Province <span class="text-danger">*</span></label>
                        <select id="province_id" name="province_id" class="form-select select2" required>
                            <option value="">Select Province</option>
                            <?php foreach ($provinces as $province): ?>
                                <option value="<?= $province['id'] ?>" <?= ($province['id'] == $sme['province_id']) ? 'selected' : '' ?>>
                                    <?= esc($province['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="district_id">District <span class="text-danger">*</span></label>
                        <select id="district_id" name="district_id" class="form-select select2" required <?= empty($districts) ? 'disabled' : '' ?>>
                            <option value="">Select District</option>
                            <?php foreach ($districts as $district): ?>
                                <option value="<?= $district['id'] ?>" <?= ($district['id'] == $sme['district_id']) ? 'selected' : '' ?>>
                                    <?= esc($district['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="llg_id">LLG <span class="text-danger">*</span></label>
                        <select id="llg_id" name="llg_id" class="form-select select2" required <?= empty($llgs) ? 'disabled' : '' ?>>
                            <option value="">Select LLG</option>
                            <?php foreach ($llgs as $llg): ?>
                                <option value="<?= $llg['id'] ?>" <?= ($llg['id'] == $sme['llg_id']) ? 'selected' : '' ?>>
                                    <?= esc($llg['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="contact_details">Contact Details</label>
                        <textarea id="contact_details" name="contact_details" class="form-control" rows="3"
                                placeholder="Phone numbers, email addresses, etc."><?= esc($sme['contact_details']) ?></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="gps_coordinates">GPS Coordinates</label>
                        <input type="text" id="gps_coordinates" name="gps_coordinates" class="form-control"
                               placeholder="e.g., -9.443383, 147.180891" value="<?= esc($sme['gps_coordinates']) ?>">
                        <small class="text-muted">Format: latitude, longitude</small>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label class="form-label" for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="4"
                            placeholder="Describe the SME's business, activities, etc."><?= esc($sme['description']) ?></textarea>
                </div>

                <!-- Logo and Map Section -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <!-- Logo Upload -->
                        <div class="mb-3">
                            <label class="form-label" for="logo">Logo</label>
                            <?php if (!empty($sme['logo_filepath'])): ?>
                                <div class="mb-3 text-center">
                                    <img src="<?= base_url($sme['logo_filepath']) ?>" alt="Current Logo" class="img-thumbnail" style="max-height: 200px;">
                                    <p class="small text-muted mt-2">Current logo</p>
                                </div>
                            <?php endif; ?>
                            <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
                            <small class="text-muted">Maximum file size: 10MB. Accepted formats: JPG, PNG, GIF</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Map Display -->
                        <?php if (!empty($sme['gps_coordinates'])): ?>
                            <div class="mb-3">
                                <label class="form-label">Location Map</label>
                                <div id="map"></div>
                                <small class="text-muted">Map based on current GPS coordinates</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('smes/' . $sme['id']) ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update SME</button>
                </div>
            </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (session()->getFlashdata('success')): ?>
            toastr.success('<?= session()->getFlashdata('success') ?>');
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            toastr.error('<?= session()->getFlashdata('error') ?>');
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                toastr.error('<?= esc($error) ?>');
            <?php endforeach; ?>
        <?php endif; ?>
    });
</script>
<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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

    // GPS Coordinates validation and map update
    $('#gps_coordinates').on('blur', function() {
        const coords = $(this).val();
        if (coords && !coords.match(/^-?\d+\.?\d*,\s*-?\d+\.?\d*$/)) {
            toastr.warning('Please enter GPS coordinates in the format: latitude, longitude');
        } else if (coords) {
            // Update map with new coordinates
            updateMap(coords);
        }
    });

    // Initialize map if coordinates exist
    <?php if (!empty($sme['gps_coordinates'])): ?>
    initMap("<?= $sme['gps_coordinates'] ?>", "<?= esc($sme['sme_name']) ?>", "<?= esc($sme['village_name']) ?>");
    <?php endif; ?>

    // Function to initialize map
    function initMap(coordsStr, smeName, villageName) {
        if (document.getElementById('map')) {
            // Parse coordinates
            const coordinates = coordsStr.split(',').map(coord => parseFloat(coord.trim()));
            const lat = coordinates[0];
            const lng = coordinates[1];

            // Initialize map
            window.smeMap = L.map('map').setView([lat, lng], 15);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(window.smeMap);

            // Add marker
            window.smeMarker = L.marker([lat, lng]).addTo(window.smeMap);
            window.smeMarker.bindPopup("<b>" + smeName + "</b><br>" + villageName).openPopup();

            // Improve map display
            setTimeout(() => {
                window.smeMap.invalidateSize();
            }, 100);
        }
    }

    // Function to update map with new coordinates
    function updateMap(coordsStr) {
        if (window.smeMap) {
            // Parse coordinates
            const coordinates = coordsStr.split(',').map(coord => parseFloat(coord.trim()));
            const lat = coordinates[0];
            const lng = coordinates[1];

            // Update map view
            window.smeMap.setView([lat, lng], 15);

            // Update marker position
            if (window.smeMarker) {
                window.smeMarker.setLatLng([lat, lng]);
            } else {
                window.smeMarker = L.marker([lat, lng]).addTo(window.smeMap);
            }

            // Update popup content
            window.smeMarker.bindPopup("<b>" + $('#sme_name').val() + "</b><br>" + $('#village_name').val()).openPopup();
        } else {
            // Initialize map if it doesn't exist
            initMap(coordsStr, $('#sme_name').val(), $('#village_name').val());
        }
    }
});
</script>
<?= $this->endSection() ?>