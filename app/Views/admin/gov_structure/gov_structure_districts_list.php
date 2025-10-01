<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/gov-structure/provinces') ?>">Provinces</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Districts in <?= esc($province['name']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Districts in <?= esc($province['name']) ?></h3>
                    <div>
                        <a href="<?= base_url('admin/gov-structure/provinces') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back to Provinces
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDistrictModal">
                            <i class="fas fa-plus"></i> Add District
                        </button>
                        <a href="<?= base_url('admin/gov-structure/provinces/'.$province['id'].'/districts/csv-template') ?>" class="btn btn-success">
                            <i class="fas fa-download"></i> Download CSV Template
                        </a>
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importDistrictModal">
                            <i class="fas fa-upload"></i> Import CSV
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Map Center</th>
                                    <th>Map Zoom</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($districts)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No districts found</td>
                                </tr>
                                <?php else: ?>
                                    <?php $counter = 1; ?>
                                    <?php foreach ($districts as $district): ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><?= $district['code'] ?></td>
                                        <td><?= $district['name'] ?></td>
                                        <td><?= $district['map_center'] ?></td>
                                        <td><?= esc($district['map_zoom']) ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/gov-structure/districts/'.$district['id'].'/llgs') ?>" class="btn btn-outline-primary" title="View LLGs" style="margin-right: 5px;">
                                                <i class="fas fa-eye me-1"></i> View LLGs
                                            </a>
                                            <button type="button" class="btn btn-outline-warning edit-district-btn"
                                                    data-id="<?= $district['id'] ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editDistrictModal"
                                                    title="Edit"
                                                    style="margin-right: 5px;">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </button>
                                            <?php
                                            // Consider fetching this count more efficiently if performance is an issue
                                            $llgCount = model('GovStructureModel')->where('parent_id', $district['id'])->where('level', 'llg')->countAllResults();
                                            $hasLlgs = $llgCount > 0;
                                            ?>
                                            <button type="button" class="btn btn-outline-danger delete-district-btn"
                                                    data-id="<?= $district['id'] ?>"
                                                    data-name="<?= esc($district['name']) ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteDistrictModal"
                                                    <?= $hasLlgs ? 'disabled' : '' ?>
                                                    title="<?= $hasLlgs ? 'Cannot delete district with LLGs' : 'Delete district' ?>">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add District Modal -->
<div class="modal fade" id="addDistrictModal" tabindex="-1" role="dialog" aria-labelledby="addDistrictModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="addDistrictForm" action="<?= base_url('admin/gov-structure/provinces/'.$province['id'].'/districts') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDistrictModalLabel">Add District to <?= esc($province['name']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label for="json_id">Select District from Map Data</label>
                        <select class="form-control select2-district" id="json_id" name="json_id">
                            <option value="">-- Select District --</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="code">District Code</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">District Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="map_center">Map Center (Latitude,Longitude)</label>
                        <input type="text" class="form-control" id="map_center" name="map_center" placeholder="e.g. -9.4438,147.1803">
                    </div>
                    <div class="form-group mb-3">
                        <label for="map_zoom">Map Zoom Level</label>
                        <input type="text" class="form-control" id="map_zoom" name="map_zoom" placeholder="e.g. 10">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save District</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit District Modal (Single reusable modal) -->
<div class="modal fade" id="editDistrictModal" tabindex="-1" role="dialog" aria-labelledby="editDistrictModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editDistrictForm" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDistrictModalLabel">Edit District</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_id" name="id">
                    <!-- <input type="hidden" name="_method" value="PUT"> -->
                    <div class="form-group mb-3">
                        <label for="edit_json_id">Select District from Map Data</label>
                        <select class="form-control select2-district-edit" id="edit_json_id" name="json_id">
                            <option value="">-- Select District --</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_code">District Code</label>
                        <input type="text" class="form-control" id="edit_code" name="code" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_name">District Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_map_center">Map Center (Latitude,Longitude)</label>
                        <input type="text" class="form-control" id="edit_map_center" name="map_center" placeholder="e.g. -9.4438,147.1803">
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_map_zoom">Map Zoom Level</label>
                        <input type="text" class="form-control" id="edit_map_zoom" name="map_zoom" placeholder="e.g. 10">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update District</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete District Modal (Single reusable modal) -->
<div class="modal fade" id="deleteDistrictModal" tabindex="-1" role="dialog" aria-labelledby="deleteDistrictModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDistrictModalLabel">Delete District</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteDistrictName"></strong>?</p>
                <p class="text-danger">This action cannot be undone. All LLGs associated with this district must be deleted first.</p>
                <input type="hidden" id="delete_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Import District CSV Modal -->
<div class="modal fade" id="importDistrictModal" tabindex="-1" role="dialog" aria-labelledby="importDistrictModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="importDistrictForm" action="<?= base_url('admin/gov-structure/provinces/'.$province['id'].'/districts/csv-import') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="importDistrictModalLabel">Import Districts from CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Instructions:</h6>
                        <ul class="mb-0">
                            <li>Download the CSV template first</li>
                            <li>Fill in the <strong>code</strong> and <strong>name</strong> columns</li>
                            <li>Districts will be added to <strong><?= esc($province['name']) ?></strong> province</li>
                            <li>Save the file as CSV format</li>
                            <li>Upload the completed file below</li>
                        </ul>
                    </div>
                    <div class="form-group mb-3">
                        <label for="csv_file">Select CSV File</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                        <small class="form-text text-muted">Only CSV files are allowed</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Import Districts</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toastr initialization -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (session()->getFlashdata('success')): ?>
            toastr.success('<?= session()->getFlashdata('success') ?>');
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            toastr.error('<?= session()->getFlashdata('error') ?>');
        <?php endif; ?>
    });
</script>

<!-- JavaScript for District JSON handling -->
<script>
$(document).ready(function() {
    // Initialize Select2 for Add form
    $('.select2-district').select2({
        width: '100%',
        dropdownParent: $('#addDistrictModal')
    });

    // Initialize Select2 for Edit form
    $('.select2-district-edit').select2({
        width: '100%',
        dropdownParent: $('#editDistrictModal')
    });

    // Load districts from JSON file
    function loadDistrictData(callback) {
        $.getJSON('<?= base_url('public/assets/gov_structure_map_json/png_dist_boundaries_2011.json') ?>', function(data) {
            // Process the data
            var districts = [];

            // Extract districts from features array
            if (data && data.features) {
                data.features.forEach(function(feature, index) {
                    if (feature.properties && feature.properties.DISTNAME) {
                        districts.push({
                            id: feature.id,
                            geocode: feature.properties.GEOCODE,
                            name: feature.properties.DISTNAME
                        });
                    }
                });

                // Sort districts by name
                districts.sort(function(a, b) {
                    return a.name.localeCompare(b.name);
                });

                if (callback) callback(districts, data);
            }
        });
    }

    // Filter districts by province
    function filterDistrictsByProvince(districts, provinceId) {
        // In a real application, you might have a mapping between province and district
        // For now, we'll show all districts since the JSON doesn't have a province reference
        return districts;
    }

    // Populate dropdowns with district data
    loadDistrictData(function(districts, data) {
        // Filter districts based on the current province
        var provinceId = <?= $province['id'] ?>;
        var filteredDistricts = filterDistrictsByProvince(districts, provinceId);

        // Populate Add form dropdown
        var addSelect = $('#json_id');
        filteredDistricts.forEach(function(district) {
            addSelect.append(new Option(district.name + ' (' + district.geocode + ')', district.id));
        });

        // Populate Edit form dropdown
        var editSelect = $('#edit_json_id');
        editSelect.empty().append(new Option('-- Select District --', '')); // Clear and add default
        filteredDistricts.forEach(function(district) {
            editSelect.append(new Option(district.name + ' (' + district.geocode + ')', district.id));
        });
        // Pre-selection happens on edit button click
    });

    // Handle district selection in Add form
    $('#json_id').on('change', function() {
        handleDistrictSelection($(this), '#name', '#code', '#map_center', '#map_zoom');
    });

    // Handle district selection in Edit form
    $('#edit_json_id').on('change', function() {
        handleDistrictSelection(
            $(this),
            '#edit_name',
            '#edit_code',
            '#edit_map_center',
            '#edit_map_zoom'
        );
    });

    // Common function to handle district selection
    function handleDistrictSelection(selectElement, nameSelector, codeSelector, mapCenterSelector, mapZoomSelector) {
        var selectedId = selectElement.val();

        if (selectedId) {
            // Clear existing value first to avoid showing previous values during loading
            $(mapCenterSelector).val('');

            // Find the selected district in our data
            loadDistrictData(function(districts, data) {
                var feature = data.features.find(function(f) {
                    return f.id == selectedId;
                });

                if (feature && feature.properties) {
                    // Auto-populate name field
                    $(nameSelector).val(feature.properties.DISTNAME);

                    // Auto-populate code field with GEOCODE
                    $(codeSelector).val(feature.properties.GEOCODE);

                    // Try to extract map center from geometry if available
                    if (feature.geometry &&
                        feature.geometry.coordinates &&
                        feature.geometry.coordinates.length > 0) {
                        // Calculate center point from all coordinates
                        try {
                            // The structure seems to need a bounding box approach
                            var coordinates = feature.geometry.coordinates[0];
                            if (coordinates && coordinates.length > 0) {
                                // Use bounding box method for more accurate center calculation
                                var minLng = Number.POSITIVE_INFINITY;
                                var maxLng = Number.NEGATIVE_INFINITY;
                                var minLat = Number.POSITIVE_INFINITY;
                                var maxLat = Number.NEGATIVE_INFINITY;
                                var validCoordinatesFound = false;

                                // Find the min/max bounds of the polygon
                                coordinates.forEach(function(coord) {
                                    if (Array.isArray(coord) && coord.length >= 2) {
                                        // In GeoJSON, the first coordinate is longitude, second is latitude
                                        var lng = parseFloat(coord[0]);
                                        var lat = parseFloat(coord[1]);

                                        // PNG coordinates should be approximately:
                                        // Latitude: between -12 and 0 (south of equator)
                                        // Longitude: between 140 and 160 (east)
                                        if (!isNaN(lng) && !isNaN(lat)) {
                                            // Ensure values are in the correct range for PNG
                                            if (lng >= 140 && lng <= 160 && lat >= -12 && lat <= 0) {
                                                validCoordinatesFound = true;
                                                minLng = Math.min(minLng, lng);
                                                maxLng = Math.max(maxLng, lng);
                                                minLat = Math.min(minLat, lat);
                                                maxLat = Math.max(maxLat, lat);
                                            }
                                        }
                                    }
                                });

                                if (validCoordinatesFound) {
                                    // Calculate center as the middle of the bounding box
                                    var centerLat = (minLat + maxLat) / 2;
                                    var centerLng = (minLng + maxLng) / 2;

                                    // Final check to prevent NaN values and ensure lat/lng are in correct format
                                    if (!isNaN(centerLat) && !isNaN(centerLng)) {
                                        // This is specifically for Papua New Guinea districts
                                        // Valid range check - strict check for PNG districts
                                        if (centerLat >= -12 && centerLat <= 0 &&
                                            centerLng >= 140 && centerLng <= 160) {
                                            // Format correctly as latitude,longitude (y,x)
                                            $(mapCenterSelector).val(centerLat.toFixed(6) + ',' + centerLng.toFixed(6));
                                            $(mapZoomSelector).val('10'); // Default zoom level for districts
                                            console.log('Center calculated: ' + centerLat.toFixed(6) + ',' + centerLng.toFixed(6));
                                        } else {
                                            console.log('Calculated center outside PNG range: ' +
                                                       centerLat.toFixed(6) + ',' + centerLng.toFixed(6));
                                            $(mapCenterSelector).val('');
                                        }
                                    } else {
                                        console.log('Invalid center calculation resulted in NaN');
                                        $(mapCenterSelector).val(''); // Set to empty instead of NaN
                                    }
                                } else {
                                    // No valid points found
                                    console.log('No valid coordinates found in geometry');
                                    $(mapCenterSelector).val('');
                                }
                            } else {
                                // No coordinates available
                                console.log('No coordinates array found in geometry');
                                $(mapCenterSelector).val('');
                            }
                        } catch (e) {
                            console.log('Error calculating center coordinates:', e);
                            $(mapCenterSelector).val(''); // Clear the field on error
                        }
                    } else {
                        // No geometry available
                        console.log('No geometry found for the selected district');
                        $(mapCenterSelector).val('');
                    }
                }
            });
        } else {
            // Clear fields if no selection
            $(nameSelector).val('');
            $(codeSelector).val('');
            $(mapCenterSelector).val('');
            $(mapZoomSelector).val('');
        }
    }

    // --- AJAX Form Submissions ---

    // Add District Form Submission
    $('#addDistrictForm').on('submit', function(e) {
        e.preventDefault();
        submitForm($(this), 'POST', function(response) {
            location.reload();
        });
    });

    // Import District Form - Allow normal form submission (no AJAX)
    $('#importDistrictForm').on('submit', function(e) {
        // Do not prevent default - allow normal form submission
        // This ensures the file upload works correctly
        var fileInput = $(this).find('input[type="file"]');
        if (!fileInput.val()) {
            e.preventDefault();
            toastr.error('Please select a CSV file to import');
            return false;
        }
        // Form will submit normally to the POST route
    });

    // Edit District Button Click - Populate Modal
    $('.edit-district-btn').on('click', function() {
        var districtId = $(this).data('id');
        $('#editDistrictForm').attr('action', '<?= base_url('admin/gov-structure/districts/') ?>' + districtId);
        $('#edit_id').val(districtId);

        // Fetch district data via AJAX
        $.ajax({
            url: '<?= base_url('admin/gov-structure/districts/') ?>' + districtId + '/edit',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && response.district) {
                    var district = response.district;
                    $('#edit_code').val(district.code);
                    $('#edit_name').val(district.name);
                    $('#edit_map_center').val(district.map_center);
                    $('#edit_map_zoom').val(district.map_zoom);

                    // Pre-select in Select2
                    var selectElement = $('#edit_json_id');
                    var optionToSelect = selectElement.find('option').filter(function() {
                        return $(this).text().includes(district.name) || $(this).text().includes(district.code);
                    }).val();

                    if(optionToSelect) {
                        selectElement.val(optionToSelect).trigger('change');
                    } else {
                         selectElement.val('').trigger('change');
                    }
                } else {
                    toastr.error('Could not load district data.');
                    $('#editDistrictModal').modal('hide');
                }
            },
            error: function(xhr, status, error) {
                toastr.error('Error fetching district data: ' + error);
                $('#editDistrictModal').modal('hide');
            }
        });
    });

    // Edit District Form Submission
    $('#editDistrictForm').on('submit', function(e) {
        e.preventDefault();
        submitForm($(this), 'POST', function(response) { // Using POST, can change to PUT
            location.reload();
        });
    });

    // Delete District Button Click - Populate Modal
    $('.delete-district-btn').on('click', function() {
        var districtId = $(this).data('id');
        var districtName = $(this).data('name');
        $('#delete_id').val(districtId);
        $('#deleteDistrictName').text(districtName);
    });

    // Confirm Delete Button Click
    $('#confirmDeleteBtn').on('click', function() {
        var districtId = $('#delete_id').val();
        var url = '<?= base_url('admin/gov-structure/districts/') ?>' + districtId;
        var $button = $(this);
        var csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name'); // Get CSRF token name from any form
        var csrfHash = $('input[name="<?= csrf_token() ?>"]').val(); // Get CSRF token hash from any form

        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');

        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            data: { // Send CSRF token in data
                [csrfName]: csrfHash
            },
            // headers: { // Fallback if needed
            //    'X-CSRF-TOKEN': csrfHash
            // },
            success: function(response) {
                 // Update CSRF token value after successful request
                 $('input[name="<?= csrf_token() ?>"]').val(response.csrf_hash || csrfHash);
                toastr.success(response.message || 'District deleted successfully.');
                $('#deleteDistrictModal').modal('hide');
                location.reload();
            },
            error: function(xhr, status, error) {
                var errorMsg = 'Failed to delete district.';
                 // Update CSRF token value even on error if provided
                 if(xhr.responseJSON && xhr.responseJSON.csrf_hash) {
                     $('input[name="<?= csrf_token() ?>"]').val(xhr.responseJSON.csrf_hash);
                 }
                if (xhr.responseJSON && xhr.responseJSON.messages && xhr.responseJSON.messages.error) {
                    errorMsg = xhr.responseJSON.messages.error;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                     errorMsg = xhr.responseJSON.message;
                } else if (xhr.status === 403) {
                     errorMsg = 'Permission denied. Please check CSRF token or user permissions.';
                }
                toastr.error(errorMsg);
                $button.prop('disabled', false).text('Delete');
            },
            complete: function(xhr) {
                 // Update CSRF token from header if present in response
                 var newCsrfHash = xhr.getResponseHeader('X-CSRF-TOKEN');
                 if (newCsrfHash) {
                     $('input[name="<?= csrf_token() ?>"]').val(newCsrfHash);
                 }
                 $button.prop('disabled', false).text('Delete');
            }
        });
    });

    // --- Helper Function for AJAX Submission (Reusing from province view) ---
    function submitForm($form, method, successCallback) {
        var url = $form.attr('action');
        var formData = $form.serializeArray(); // Use serializeArray
        var $submitButton = $form.find('button[type="submit"]');
        var originalButtonText = $submitButton.html();
        var csrfName = $form.find('input[name="<?= csrf_token() ?>"]').attr('name');
        var csrfHash = $form.find('input[name="<?= csrf_token() ?>"]').val();

        // Add CSRF token to form data
        formData.push({ name: csrfName, value: csrfHash });

        $submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            url: url,
            type: method, // Should be POST for forms
            data: $.param(formData), // Convert array back to query string
            dataType: 'json',
            // headers: { // Not needed if sending in data
            //     'X-CSRF-TOKEN': csrfHash
            // },
            success: function(response) {
                 // Update CSRF token value after successful request
                 var newCsrfHash = response.csrf_hash || csrfHash;
                 $form.find('input[name="<?= csrf_token() ?>"]').val(newCsrfHash);
                 $('input[name="<?= csrf_token() ?>"]').val(newCsrfHash); // Update other forms too

                toastr.success(response.message || 'Operation successful.');
                $form.closest('.modal').modal('hide');
                if (successCallback) {
                    successCallback(response);
                }
            },
            error: function(xhr, status, error) {
                var errorMsg = 'An error occurred.';
                 // Update CSRF token value even on error if provided
                 if(xhr.responseJSON && xhr.responseJSON.csrf_hash) {
                     $form.find('input[name="<?= csrf_token() ?>"]').val(xhr.responseJSON.csrf_hash);
                     $('input[name="<?= csrf_token() ?>"]').val(xhr.responseJSON.csrf_hash);
                 }
                 if (xhr.responseJSON) {
                    if (xhr.responseJSON.messages) {
                        var errors = xhr.responseJSON.messages;
                         if (typeof errors === 'object') {
                             errorMsg = 'Validation errors:<br>' + Object.values(errors).map(function(msg) { return '- ' + msg; }).join('<br>');
                         } else {
                             errorMsg = errors;
                         }
                    } else if (xhr.responseJSON.message) {
                         errorMsg = xhr.responseJSON.message;
                    }
                } else if (xhr.status === 403) {
                     errorMsg = 'Permission denied. Please check CSRF token or user permissions.';
                }
                toastr.error(errorMsg, 'Error', { closeButton: true, timeOut: 0 });
            },
            complete: function(xhr) {
                 // Update CSRF token from header if present in response
                 var newCsrfHash = xhr.getResponseHeader('X-CSRF-TOKEN');
                 if (newCsrfHash) {
                     $form.find('input[name="<?= csrf_token() ?>"]').val(newCsrfHash);
                     $('input[name="<?= csrf_token() ?>"]').val(newCsrfHash);
                 }
                $submitButton.prop('disabled', false).html(originalButtonText);
            }
        });
    }
});
</script>

<?= $this->endSection() ?>
