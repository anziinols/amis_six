<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/gov-structure/provinces') ?>">Provinces</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/gov-structure/provinces/'.$province['id'].'/districts') ?>">Districts in <?= esc($province['name']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">LLGs in <?= esc($district['name']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">LLGs in <?= esc($district['name']) ?> District</h3>
                    <div class="card-tools">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLLGModal">
                                <i class="fas fa-plus"></i> Add LLG
                            </button>
                            <a href="<?= base_url('admin/gov-structure/districts/'.$district['id'].'/llgs/csv-template') ?>" class="btn btn-success">
                                <i class="fas fa-download"></i> Download CSV Template
                            </a>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importLlgModal">
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
                                <?php if (empty($llgs)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No LLGs found</td>
                                </tr>
                                <?php else: ?>
                                    <?php $counter = 1; ?>
                                    <?php foreach ($llgs as $llg): ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><?= $llg['code'] ?></td>
                                        <td><?= $llg['name'] ?></td>
                                        <td><?= $llg['map_center'] ?></td>
                                        <td><?= esc($llg['map_zoom']) ?></td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="LLG actions">
                                                <a href="<?= base_url('admin/gov-structure/llgs/'.$llg['id'].'/wards') ?>" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> View Wards
                                                </a>
                                                <button type="button" class="btn btn-warning btn-sm edit-llg-btn"
                                                        data-id="<?= $llg['id'] ?>"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editLLGModal">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <?php
                                                // Check for ward dependencies (assuming 'ward' level exists)
                                                $wardCount = model('GovStructureModel')->where('parent_id', $llg['id'])->where('level', 'ward')->countAllResults();
                                                $hasWards = $wardCount > 0;
                                                ?>
                                                <button type="button" class="btn btn-danger btn-sm delete-llg-btn"
                                                        data-id="<?= $llg['id'] ?>"
                                                        data-name="<?= esc($llg['name']) ?>"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteLLGModal"
                                                        <?= $hasWards ? 'disabled' : '' ?>
                                                        title="<?= $hasWards ? 'Cannot delete LLG with wards' : 'Delete LLG' ?>">
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

<!-- Add LLG Modal -->
<div class="modal fade" id="addLLGModal" tabindex="-1" role="dialog" aria-labelledby="addLLGModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="addLLGForm" action="<?= base_url('admin/gov-structure/districts/'.$district['id'].'/llgs') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLLGModalLabel">Add LLG to <?= esc($district['name']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label for="json_id">Select LLG from Map Data</label>
                        <select class="form-control select2-llg" id="json_id" name="json_id">
                            <option value="">-- Select LLG --</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="code">LLG Code</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">LLG Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="map_center">Map Center (Latitude,Longitude)</label>
                        <input type="text" class="form-control" id="map_center" name="map_center" placeholder="e.g. -9.4438,147.1803">
                    </div>
                    <div class="form-group mb-3">
                        <label for="map_zoom">Map Zoom Level</label>
                        <input type="text" class="form-control" id="map_zoom" name="map_zoom" placeholder="e.g. 12">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save LLG</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit LLG Modal (Single reusable modal) -->
<div class="modal fade" id="editLLGModal" tabindex="-1" role="dialog" aria-labelledby="editLLGModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editLLGForm" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLLGModalLabel">Edit LLG</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_id" name="id">
                    <!-- <input type="hidden" name="_method" value="PUT"> -->
                    <div class="form-group mb-3">
                        <label for="edit_json_id">Select LLG from Map Data</label>
                        <select class="form-control select2-llg-edit" id="edit_json_id" name="json_id">
                            <option value="">-- Select LLG --</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_code">LLG Code</label>
                        <input type="text" class="form-control" id="edit_code" name="code" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_name">LLG Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_map_center">Map Center (Latitude,Longitude)</label>
                        <input type="text" class="form-control" id="edit_map_center" name="map_center" placeholder="e.g. -9.4438,147.1803">
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_map_zoom">Map Zoom Level</label>
                        <input type="text" class="form-control" id="edit_map_zoom" name="map_zoom" placeholder="e.g. 12">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update LLG</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete LLG Modal (Single reusable modal) -->
<div class="modal fade" id="deleteLLGModal" tabindex="-1" role="dialog" aria-labelledby="deleteLLGModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLLGModalLabel">Delete LLG</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteLLGName"></strong>?</p>
                <p class="text-danger">This action cannot be undone. Any associated wards must be deleted first.</p>
                <input type="hidden" id="delete_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Import LLG CSV Modal -->
<div class="modal fade" id="importLlgModal" tabindex="-1" role="dialog" aria-labelledby="importLlgModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="importLlgForm" action="<?= base_url('admin/gov-structure/districts/'.$district['id'].'/llgs/csv-import') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="importLlgModalLabel">Import LLGs from CSV</h5>
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
                            <li>LLGs will be added to <strong><?= esc($district['name']) ?></strong> district</li>
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
                    <button type="submit" class="btn btn-info">Import LLGs</button>
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

<!-- JavaScript for LLG JSON handling -->
<script>
$(document).ready(function() {
    // Initialize Select2 for Add form
    $('.select2-llg').select2({
        width: '100%',
        dropdownParent: $('#addLLGModal')
    });

    // Initialize Select2 for Edit form
    $('.select2-llg-edit').select2({
        width: '100%',
        dropdownParent: $('#editLLGModal')
    });

    // Load LLGs from JSON file
    function loadLLGData(callback) {
        $.getJSON('<?= base_url('public/assets/gov_structure_map_json/png_llg_boundaries_2011.json') ?>', function(data) {
            // Process the data
            var llgs = [];

            // Extract LLGs from features array
            if (data && data.features) {
                data.features.forEach(function(feature, index) {
                    if (feature.properties && feature.properties.LLGNAME) {
                        llgs.push({
                            id: feature.id,
                            geocode: feature.properties.GEOCODE,
                            name: feature.properties.LLGNAME
                        });
                    }
                });

                // Sort LLGs by name
                llgs.sort(function(a, b) {
                    return a.name.localeCompare(b.name);
                });

                if (callback) callback(llgs, data);
            }
        });
    }

    // Filter LLGs by district
    function filterLLGsByDistrict(llgs, districtId) {
        // In a real application, you might have a mapping between district and LLG
        // For now, we'll show all LLGs since the JSON doesn't have a district reference
        return llgs;
    }

    // Populate dropdowns with LLG data
    loadLLGData(function(llgs, data) {
        // Filter LLGs based on the current district
        var districtId = <?= $district['id'] ?>;
        var filteredLLGs = filterLLGsByDistrict(llgs, districtId);

        // Populate Add form dropdown
        var addSelect = $('#json_id');
        filteredLLGs.forEach(function(llg) {
            addSelect.append(new Option(llg.name + ' (' + llg.geocode + ')', llg.id));
        });

        // Populate Edit form dropdown
        var editSelect = $('#edit_json_id');
        editSelect.empty().append(new Option('-- Select LLG --', '')); // Clear and add default
        filteredLLGs.forEach(function(llg) {
            editSelect.append(new Option(llg.name + ' (' + llg.geocode + ')', llg.id));
        });
        // Pre-selection happens on edit button click
    });

    // Handle LLG selection in Add form
    $('#json_id').on('change', function() {
        handleLLGSelection($(this), '#name', '#code', '#map_center', '#map_zoom');
    });

    // Handle LLG selection in Edit form
    $('#edit_json_id').on('change', function() {
        handleLLGSelection(
            $(this),
            '#edit_name',
            '#edit_code',
            '#edit_map_center',
            '#edit_map_zoom'
        );
    });

    // Common function to handle LLG selection
    function handleLLGSelection(selectElement, nameSelector, codeSelector, mapCenterSelector, mapZoomSelector) {
        var selectedId = selectElement.val();

        if (selectedId) {
            // Find the selected LLG in our data
            loadLLGData(function(llgs, data) {
                var feature = data.features.find(function(f) {
                    return f.id == selectedId;
                });

                if (feature && feature.properties) {
                    // Auto-populate name field
                    $(nameSelector).val(feature.properties.LLGNAME);

                    // Auto-populate code field with GEOCODE
                    $(codeSelector).val(feature.properties.GEOCODE);

                    // Try to extract map center from geometry if available
                    if (feature.geometry &&
                        feature.geometry.coordinates &&
                        feature.geometry.coordinates.length > 0) {
                        // Calculate center point from all coordinates
                        try {
                            var coordinates = feature.geometry.coordinates[0];
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
                                    $(mapZoomSelector).val('12'); // Default zoom level for LLGs
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

    // Add LLG Form Submission
    $('#addLLGForm').on('submit', function(e) {
        e.preventDefault();
        submitForm($(this), 'POST', function(response) {
            location.reload();
        });
    });

    // Import LLG Form - Allow normal form submission (no AJAX)
    $('#importLlgForm').on('submit', function(e) {
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

    // Edit LLG Button Click - Populate Modal
    $('.edit-llg-btn').on('click', function() {
        var llgId = $(this).data('id');
        $('#editLLGForm').attr('action', '<?= base_url('admin/gov-structure/llgs/') ?>' + llgId);
        $('#edit_id').val(llgId);

        // Fetch LLG data via AJAX
        $.ajax({
            url: '<?= base_url('admin/gov-structure/llgs/') ?>' + llgId + '/edit',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && response.llg) {
                    var llg = response.llg;
                    $('#edit_code').val(llg.code);
                    $('#edit_name').val(llg.name);
                    $('#edit_map_center').val(llg.map_center);
                    $('#edit_map_zoom').val(llg.map_zoom);

                    // Pre-select in Select2
                    var selectElement = $('#edit_json_id');
                    var optionToSelect = selectElement.find('option').filter(function() {
                        return $(this).text().includes(llg.name) || $(this).text().includes(llg.code);
                    }).val();

                    if(optionToSelect) {
                        selectElement.val(optionToSelect).trigger('change');
                    } else {
                         selectElement.val('').trigger('change');
                    }
                } else {
                    toastr.error('Could not load LLG data.');
                    $('#editLLGModal').modal('hide');
                }
            },
            error: function(xhr, status, error) {
                toastr.error('Error fetching LLG data: ' + error);
                $('#editLLGModal').modal('hide');
            }
        });
    });

    // Edit LLG Form Submission
    $('#editLLGForm').on('submit', function(e) {
        e.preventDefault();
        submitForm($(this), 'POST', function(response) { // Using POST, can change to PUT
            location.reload();
        });
    });

    // Delete LLG Button Click - Populate Modal
    $('.delete-llg-btn').on('click', function() {
        var llgId = $(this).data('id');
        var llgName = $(this).data('name');
        $('#delete_id').val(llgId);
        $('#deleteLLGName').text(llgName);
    });

    // Confirm Delete Button Click
    $('#confirmDeleteBtn').on('click', function() {
        var llgId = $('#delete_id').val();
        var url = '<?= base_url('admin/gov-structure/llgs/') ?>' + llgId;
        var $button = $(this);
        var csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name'); // Get CSRF token name
        var csrfHash = $('input[name="<?= csrf_token() ?>"]').val(); // Get CSRF token hash

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
                toastr.success(response.message || 'LLG deleted successfully.');
                $('#deleteLLGModal').modal('hide');
                location.reload();
            },
            error: function(xhr, status, error) {
                var errorMsg = 'Failed to delete LLG.';
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

    // --- Helper Function for AJAX Submission (Reusing from previous views) ---
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
