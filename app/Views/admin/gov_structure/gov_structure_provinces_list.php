<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Provinces</h3>
                    <div class="card-tools">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProvinceModal">
                                <i class="fas fa-plus"></i> Add Province
                            </button>
                            <a href="<?= base_url('admin/gov-structure/provinces/csv-template') ?>" class="btn btn-success">
                                <i class="fas fa-download"></i> Download CSV Template
                            </a>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importProvinceModal">
                                <i class="fas fa-upload"></i> Import CSV
                            </button>
                        </div>
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
                                <?php if (empty($provinces)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No provinces found</td>
                                </tr>
                                <?php else: ?>
                                    <?php $counter = 1; ?>
                                    <?php foreach ($provinces as $province): ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><?= $province['code'] ?></td>
                                        <td><?= $province['name'] ?></td>
                                        <td><?= $province['map_center'] ?></td>
                                        <td><?= $province['map_zoom'] ?></td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Province actions">
                                                <a href="<?= base_url('admin/gov-structure/provinces/'.$province['id'].'/districts') ?>" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> View Districts
                                                </a>
                                                <button type="button" class="btn btn-warning btn-sm edit-province-btn"
                                                        data-id="<?= $province['id'] ?>"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editProvinceModal">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <?php
                                                // Consider fetching this count more efficiently if performance is an issue
                                                $districtCount = model('GovStructureModel')->where('parent_id', $province['id'])->where('level', 'district')->countAllResults();
                                                $hasDistricts = $districtCount > 0;
                                                ?>
                                                <button type="button" class="btn btn-danger btn-sm delete-province-btn"
                                                        data-id="<?= $province['id'] ?>"
                                                        data-name="<?= esc($province['name']) ?>"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteProvinceModal"
                                                        <?= $hasDistricts ? 'disabled' : '' ?>
                                                        title="<?= $hasDistricts ? 'Cannot delete province with districts' : 'Delete province' ?>">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
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

<!-- Add Province Modal -->
<div class="modal fade" id="addProvinceModal" tabindex="-1" role="dialog" aria-labelledby="addProvinceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="addProvinceForm" action="<?= base_url('admin/gov-structure/provinces') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProvinceModalLabel">Add Province</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label for="json_id">Select Province from Map Data</label>
                        <select class="form-control select2-province" id="json_id" name="json_id">
                            <option value="">-- Select Province --</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="code">Province Code</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">Province Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="map_center">Map Center (Latitude,Longitude)</label>
                        <input type="text" class="form-control" id="map_center" name="map_center" placeholder="e.g. -9.4438,147.1803">
                    </div>
                    <div class="form-group mb-3">
                        <label for="map_zoom">Map Zoom Level</label>
                        <input type="text" class="form-control" id="map_zoom" name="map_zoom" placeholder="e.g. 8">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Province</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Province Modal (Single reusable modal) -->
<div class="modal fade" id="editProvinceModal" tabindex="-1" role="dialog" aria-labelledby="editProvinceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editProvinceForm" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProvinceModalLabel">Edit Province</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_id" name="id">
                    <!-- Add _method field for PUT request if desired, though POST works -->
                    <!-- <input type="hidden" name="_method" value="PUT"> -->
                    <div class="form-group mb-3">
                        <label for="edit_json_id">Select Province from Map Data</label>
                        <select class="form-control select2-province-edit" id="edit_json_id" name="json_id">
                            <option value="">-- Select Province --</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_code">Province Code</label>
                        <input type="text" class="form-control" id="edit_code" name="code" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_name">Province Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_map_center">Map Center (Latitude,Longitude)</label>
                        <input type="text" class="form-control" id="edit_map_center" name="map_center" placeholder="e.g. -9.4438,147.1803">
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_map_zoom">Map Zoom Level</label>
                        <input type="text" class="form-control" id="edit_map_zoom" name="map_zoom" placeholder="e.g. 8">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Province</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Province Modal (Single reusable modal) -->
<div class="modal fade" id="deleteProvinceModal" tabindex="-1" role="dialog" aria-labelledby="deleteProvinceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProvinceModalLabel">Delete Province</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                 </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteProvinceName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
                <input type="hidden" id="delete_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Import Province CSV Modal -->
<div class="modal fade" id="importProvinceModal" tabindex="-1" role="dialog" aria-labelledby="importProvinceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="importProvinceForm" action="<?= base_url('admin/gov-structure/provinces/csv-import') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="importProvinceModalLabel">Import Provinces from CSV</h5>
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
                    <button type="submit" class="btn btn-info">Import Provinces</button>
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

<!-- JavaScript for Province JSON handling -->
<script>
$(document).ready(function() {
    // Initialize Select2 for Add form
    $('.select2-province').select2({
        width: '100%',
        dropdownParent: $('#addProvinceModal')
    });

    // Initialize Select2 for Edit form (now single modal)
    $('.select2-province-edit').select2({
        width: '100%',
        dropdownParent: $('#editProvinceModal') // Target the single edit modal
    });

    // Load provinces from JSON file
    function loadProvinceData(callback) {
        $.getJSON('<?= base_url('public/assets/gov_structure_map_json/png_prov_boundaries_2011.json') ?>', function(data) {
            // Process the data
            var provinces = [];

            // Extract provinces from features array
            if (data && data.features) {
                data.features.forEach(function(feature, index) {
                    if (feature.properties && feature.properties.PROVNAME) {
                        provinces.push({
                            id: feature.id,
                            provid: feature.properties.PROVID,
                            name: feature.properties.PROVNAME
                        });
                    }
                });

                // Sort provinces by name
                provinces.sort(function(a, b) {
                    return a.name.localeCompare(b.name);
                });

                if (callback) callback(provinces, data);
            }
        });
    }

    // Populate dropdowns with province data
    loadProvinceData(function(provinces, data) {
        // Populate Add form dropdown
        var addSelect = $('#json_id');
        provinces.forEach(function(province) {
            addSelect.append(new Option(province.name + ' (' + province.provid + ')', province.id));
        });

        // Populate Edit form dropdown (now single modal)
        var editSelect = $('#edit_json_id');
        editSelect.empty().append(new Option('-- Select Province --', '')); // Clear and add default
        provinces.forEach(function(province) {
            editSelect.append(new Option(province.name + ' (' + province.provid + ')', province.id));
        });
        // Pre-selection logic will happen when the edit button is clicked
    });

    // Handle province selection in Add form
    $('#json_id').on('change', function() {
        handleProvinceSelection($(this), '#name', '#code', '#map_center', '#map_zoom');
    });

    // Handle province selection in Edit form (now single modal)
    $('#edit_json_id').on('change', function() {
        handleProvinceSelection(
            $(this),
            '#edit_name',
            '#edit_code',
            '#edit_map_center',
            '#edit_map_zoom'
        );
    });

    // Common function to handle province selection
    function handleProvinceSelection(selectElement, nameSelector, codeSelector, mapCenterSelector, mapZoomSelector) {
        var selectedId = selectElement.val();

        if (selectedId) {
            // Find the selected province in our data
            loadProvinceData(function(provinces, data) {
                var feature = data.features.find(function(f) {
                    return f.id == selectedId;
                });

                if (feature && feature.properties) {
                    // Auto-populate name field
                    $(nameSelector).val(feature.properties.PROVNAME);

                    // Auto-populate code field with PROVID
                    $(codeSelector).val(feature.properties.PROVID);

                    // Try to extract map center from geometry if available
                    if (feature.geometry &&
                        feature.geometry.coordinates &&
                        feature.geometry.coordinates.length > 0) {
                        // Calculate center point from all coordinates
                        try {
                            var coordinates = feature.geometry.coordinates[0][0];
                            if (coordinates && coordinates.length > 0) {
                                // Calculate the center of the polygon by averaging all points
                                var latSum = 0, lngSum = 0, pointCount = 0;

                                // Loop through all coordinate points
                                coordinates.forEach(function(coord) {
                                    if (Array.isArray(coord) && coord.length >= 2) {
                                        // In GeoJSON, the first coordinate is longitude, second is latitude
                                        lngSum += coord[0];
                                        latSum += coord[1];
                                        pointCount++;
                                    }
                                });

                                if (pointCount > 0) {
                                    // Calculate average to find the center
                                    var centerLng = lngSum / pointCount;
                                    var centerLat = latSum / pointCount;
                                    $(mapCenterSelector).val(centerLat.toFixed(6) + ',' + centerLng.toFixed(6));
                                    $(mapZoomSelector).val('8'); // Default zoom level for provinces
                                }
                            }
                        } catch (e) {
                            console.log('Error calculating center coordinates:', e);
                        }
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

    // Add Province Form Submission
    $('#addProvinceForm').on('submit', function(e) {
        e.preventDefault();
        submitForm($(this), 'POST', function(response) {
            // Optional: Add row dynamically instead of reloading
            location.reload();
        });
    });

    // Import Province Form - Allow normal form submission (no AJAX)
    $('#importProvinceForm').on('submit', function(e) {
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

    // Edit Province Button Click - Populate Modal
    $('.edit-province-btn').on('click', function() {
        var provinceId = $(this).data('id');
        $('#editProvinceForm').attr('action', '<?= base_url('admin/gov-structure/provinces/') ?>' + provinceId);
        $('#edit_id').val(provinceId);

        // Fetch province data via AJAX to populate the form
        $.ajax({
            url: '<?= base_url('admin/gov-structure/provinces/') ?>' + provinceId + '/edit', // Use the edit route to get data
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && response.province) {
                    var province = response.province;
                    $('#edit_code').val(province.code);
                    $('#edit_name').val(province.name);
                    $('#edit_map_center').val(province.map_center);
                    $('#edit_map_zoom').val(province.map_zoom);

                    // Pre-select in Select2 (might need adjustment based on how JSON data is loaded)
                    // This assumes the select2 options are already populated
                    // Find the option where the text contains the province name or code
                    var selectElement = $('#edit_json_id');
                    var optionToSelect = selectElement.find('option').filter(function() {
                        // Adjust matching logic if needed (e.g., match by ID if available in options)
                        return $(this).text().includes(province.name) || $(this).text().includes(province.code);
                    }).val();

                    if(optionToSelect) {
                        selectElement.val(optionToSelect).trigger('change');
                    } else {
                         selectElement.val('').trigger('change'); // Reset if no match found
                    }

                } else {
                    toastr.error('Could not load province data.');
                    $('#editProvinceModal').modal('hide');
                }
            },
            error: function(xhr, status, error) {
                toastr.error('Error fetching province data: ' + error);
                $('#editProvinceModal').modal('hide');
            }
        });
    });

    // Edit Province Form Submission
    $('#editProvinceForm').on('submit', function(e) {
        e.preventDefault();
        submitForm($(this), 'POST', function(response) { // Using POST, can change to PUT if _method is added
             // Optional: Update row dynamically instead of reloading
            location.reload();
        });
    });

    // Delete Province Button Click - Populate Modal
    $('.delete-province-btn').on('click', function() {
        var provinceId = $(this).data('id');
        var provinceName = $(this).data('name');
        $('#delete_id').val(provinceId);
        $('#deleteProvinceName').text(provinceName);
    });

    // Confirm Delete Button Click
    $('#confirmDeleteBtn').on('click', function() {
        var provinceId = $('#delete_id').val();
        var url = '<?= base_url('admin/gov-structure/provinces/') ?>' + provinceId;
        var $button = $(this);
        var csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name'); // Get CSRF token name
        var csrfHash = $('input[name="<?= csrf_token() ?>"]').val(); // Get CSRF token hash

        // Disable button
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');

        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            data: { // Send CSRF token in data
                [csrfName]: csrfHash
            },
            // headers: { // Keep header as fallback if needed, but data is more reliable
            //    'X-CSRF-TOKEN': csrfHash
            // },
            success: function(response) {
                 // Update CSRF token value after successful request if needed for subsequent requests
                 $('input[name="<?= csrf_token() ?>"]').val(response.csrf_hash || csrfHash); // CI4 might return a new hash
                if (response.message) {
                    toastr.success(response.message);
                } else {
                    toastr.success('Province deleted successfully.');
                }
                $('#deleteProvinceModal').modal('hide');
                // Optional: Remove row dynamically instead of reloading
                location.reload();
            },
            error: function(xhr, status, error) {
                var errorMsg = 'Failed to delete province.';
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
                $button.prop('disabled', false).text('Delete'); // Re-enable button
            },
            complete: function(xhr) {
                 // Update CSRF token from header if present in response (alternative method)
                 var newCsrfHash = xhr.getResponseHeader('X-CSRF-TOKEN');
                 if (newCsrfHash) {
                     $('input[name="<?= csrf_token() ?>"]').val(newCsrfHash);
                 }
                 $button.prop('disabled', false).text('Delete'); // Ensure button is re-enabled
            }
        });
    });

    // --- Helper Function for AJAX Submission ---
    function submitForm($form, method, successCallback) {
        var url = $form.attr('action');
        var formData = $form.serializeArray(); // Use serializeArray to easily add CSRF
        var $submitButton = $form.find('button[type="submit"]');
        var originalButtonText = $submitButton.html();
        var csrfName = $form.find('input[name="<?= csrf_token() ?>"]').attr('name');
        var csrfHash = $form.find('input[name="<?= csrf_token() ?>"]').val();

        // Add CSRF token to form data
        formData.push({ name: csrfName, value: csrfHash });

        // Disable button and show loading state
        $submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            url: url,
            type: method, // Should be POST for forms, PUT/PATCH might need _method field
            data: $.param(formData), // Convert array back to query string
            dataType: 'json',
            // No need for header if sending in data
            // headers: {
            //     'X-CSRF-TOKEN': csrfHash
            // },
            success: function(response) {
                 // Update CSRF token value after successful request
                 var newCsrfHash = response.csrf_hash || csrfHash; // Get new hash from response if available
                 $form.find('input[name="<?= csrf_token() ?>"]').val(newCsrfHash);
                 // Also update other forms on the page if necessary
                 $('input[name="<?= csrf_token() ?>"]').val(newCsrfHash);

                if (response.message) {
                    toastr.success(response.message);
                } else {
                     toastr.success('Operation successful.');
                }
                $form.closest('.modal').modal('hide'); // Close modal on success
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
                        // Handle CodeIgniter validation errors
                        var errors = xhr.responseJSON.messages;
                         if (typeof errors === 'object') {
                             // Format validation errors nicely
                             errorMsg = 'Validation errors:<br>' + Object.values(errors).map(function(msg) { return '- ' + msg; }).join('<br>');
                         } else {
                             errorMsg = errors; // Use the message directly if it's a string
                         }
                    } else if (xhr.responseJSON.message) {
                         errorMsg = xhr.responseJSON.message; // Handle general error messages
                    }
                } else if (xhr.status === 403) {
                     errorMsg = 'Permission denied. Please check CSRF token or user permissions.';
                }
                toastr.error(errorMsg, 'Error', { closeButton: true, timeOut: 0 }); // Keep error message until closed
            },
            complete: function(xhr) {
                 // Update CSRF token from header if present in response (alternative method)
                 var newCsrfHash = xhr.getResponseHeader('X-CSRF-TOKEN');
                 if (newCsrfHash) {
                     $form.find('input[name="<?= csrf_token() ?>"]').val(newCsrfHash);
                     $('input[name="<?= csrf_token() ?>"]').val(newCsrfHash);
                 }
                // Re-enable button and restore original text
                $submitButton.prop('disabled', false).html(originalButtonText);
            }
        });
    }

});
</script>

<?= $this->endSection() ?>
