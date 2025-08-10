<?php
$this->extend('templates/system_template');
$this->section('content');
?>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/mtdp-plans') ?>">MTDP Plans</a></li>
                    <li class="breadcrumb-item active" aria-current="page">SPAs in <?= $plan['title'] ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                    <div class="card-tools">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importSpaModal">
                                <i class="fas fa-upload"></i> Import CSV
                            </button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSpaModal">
                                <i class="fas fa-plus"></i> Add SPA
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="spasTable">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Title</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($spas as $spa) : ?>
                                    <tr>
                                        <td><?= $spa['code'] ?></td>
                                        <td><?= $spa['title'] ?></td>
                                        <td><?= $spa['remarks'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $spa['spa_status'] == 1 ? 'success' : 'danger' ?>">
                                                <?= $spa['spa_status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="SPA Actions">
                                                <a href="<?= base_url('admin/mtdp-plans/spas/' . $spa['id'] . '/dips') ?>"
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View DIPs
                                                </a>
                                                <button type="button" class="btn btn-sm btn-warning edit-spa-btn"
                                                        data-id="<?= $spa['id'] ?>" data-bs-toggle="modal" data-bs-target="#editSpaModal">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-sm btn-<?= $spa['spa_status'] == 1 ? 'danger' : 'success' ?> toggle-status-btn"
                                                        data-id="<?= $spa['id'] ?>" data-status="<?= $spa['spa_status'] ?>" data-bs-toggle="modal" data-bs-target="#toggleStatusModal">
                                                    <i class="fas fa-toggle-<?= $spa['spa_status'] == 1 ? 'off' : 'on' ?>"></i>
                                                    <?= $spa['spa_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add SPA Modal -->
<div class="modal fade" id="addSpaModal" tabindex="-1" aria-labelledby="addSpaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSpaModalLabel">Add New SPA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSpaForm">
                <div class="modal-body">
                    <input type="hidden" name="mtdp_id" value="<?= $plan['id'] ?>" />

                    <div class="mb-3">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create SPA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit SPA Modal -->
<div class="modal fade" id="editSpaModal" tabindex="-1" aria-labelledby="editSpaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSpaModalLabel">Edit SPA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSpaForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div id="edit_status_badge_container" class="mb-3">
                                <label class="form-label">Current Status</label>
                                <div id="edit_status_badge"></div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="small text-muted">Status Last Changed</div>
                            <div id="edit_status_at"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Status Changed By</div>
                            <div id="edit_status_by"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="small text-muted">Status Remarks</div>
                            <div id="edit_status_remarks"></div>
                        </div>
                    </div>

                    <hr/>

                    <div class="mb-3">
                        <label for="edit_code" class="form-label">Code</label>
                        <input type="text" class="form-control" id="edit_code" name="code" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="edit_remarks" name="remarks" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update SPA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toggle Status Modal -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="toggleStatusModalLabel">Change SPA Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="toggleStatusForm">
                <div class="modal-body">
                    <input type="hidden" id="status_id" name="id">

                    <div class="mb-3">
                        <div id="status_message" class="alert alert-info"></div>
                    </div>

                    <div class="mb-3">
                        <label for="spa_status_remarks" class="form-label">Status Remarks</label>
                        <textarea class="form-control" id="spa_status_remarks" name="spa_status_remarks" rows="3" required></textarea>
                        <div class="form-text">Please provide a reason for changing the status.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="toggle_status_button" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import SPA CSV Modal -->
<div class="modal fade" id="importSpaModal" tabindex="-1" aria-labelledby="importSpaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="importSpaForm" action="<?= base_url('admin/mtdp-plans/' . $plan['id'] . '/spas/csv-import') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="importSpaModalLabel">Import SPAs from CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> CSV Import Instructions</h6>
                        <ul class="mb-0">
                            <li>Download the template file to see the required format</li>
                            <li>Required columns: <strong>code</strong>, <strong>title</strong></li>
                            <li>Optional columns: <strong>remarks</strong></li>
                            <li>Make sure your CSV file uses UTF-8 encoding</li>
                            <li>Do not include duplicate codes</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Select CSV File</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv" required>
                        <div class="form-text">Only CSV files are allowed</div>
                    </div>

                    <div class="mb-3">
                        <a href="<?= base_url('admin/mtdp-plans/' . $plan['id'] . '/spas/csv-template') ?>"
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload"></i> Import SPAs
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        beforeSend: function(xhr, settings) {
            // Add CSRF token to all non-GET requests
            if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="<?= csrf_token() ?>"]').attr('content'));
            }
        }
    });

    // Initialize DataTable
    $('#spasTable').DataTable();

    // Create SPA
    $('#addSpaForm').on('submit', function(e) {
        e.preventDefault();

        // Show loading indication
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        submitBtn.prop('disabled', true);

        // Get CSRF token
        const csrfToken = $('meta[name="<?= csrf_token() ?>"]').attr('content');
        const formData = $(this).serialize() + '&<?= csrf_token() ?>=' + csrfToken;

        $.ajax({
            url: '<?= base_url('admin/mtdp-plans/' . $plan['id'] . '/spas') ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Reset button
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);

                console.log('Success response:', response);

                if (response.status === 'success') {
                    // Show success message
                    toastr.success(response.message || 'SPA created successfully');

                    // Close modal
                    $('#addSpaModal').modal('hide');

                    // Reload page to show updated data
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    // Show error message
                    toastr.error(response.message || 'Failed to create SPA');
                }
            },
            error: function(xhr, status, error) {
                // Reset button
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);

                console.error('AJAX Error:', error);
                console.log('XHR Status:', xhr.status);
                console.log('XHR Response Text:', xhr.responseText);

                // Show a specific and helpful error message
                if (xhr.status === 404) {
                    toastr.error('Route not found. Please check the URL configuration.');
                } else if (xhr.status === 500) {
                    toastr.error('Server error occurred. Please check the server logs for details.');
                } else if (xhr.status === 403) {
                    toastr.error('CSRF validation failed. Please refresh the page and try again.');
                } else {
                    toastr.error('Failed to create SPA: ' + (error || 'Unknown error'));
                }
            }
        });
    });

    // Edit SPA - populate form
    $('.edit-spa-btn').on('click', function() {
        const id = $(this).data('id');
        const status = $(this).data('status');

        $('#edit_id').val(id);

        // Fetch detailed SPA information including user data
        $.ajax({
            url: '<?= base_url('admin/mtdp-plans/spa-details/') ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const spa = response.data;

                    // Set form values
                    $('#edit_code').val(spa.code);
                    $('#edit_title').val(spa.title);
                    $('#edit_remarks').val(spa.remarks);

                    // Set status badge
                    $('#edit_status_badge').html(`
                        <span class="badge bg-${spa.spa_status == 1 ? 'success' : 'danger'}">
                            ${spa.spa_status == 1 ? 'Active' : 'Inactive'}
                        </span>
                    `);

                    // Format and display status date
                    if (spa.status_at) {
                        try {
                            const statusDate = new Date(spa.status_at);
                            const formattedStatusDate = statusDate.toLocaleDateString() + ' ' + statusDate.toLocaleTimeString();
                            $('#edit_status_at').text(formattedStatusDate);
                        } catch (e) {
                            console.error('Error parsing status_at date:', e);
                            $('#edit_status_at').text(spa.status_at || 'Not available');
                        }
                    } else {
                        $('#edit_status_at').text('Not available');
                    }

                    // Set status by with name and email if available
                    if (spa.status_by_name && spa.status_by_email) {
                        $('#edit_status_by').html(`
                            <strong>${spa.status_by_name}</strong> ${spa.status_by_email ? '<br><small class="text-muted">' + spa.status_by_email + '</small>' : ''}
                        `);
                    } else if (spa.status_by_name) {
                        $('#edit_status_by').html(`<strong>${spa.status_by_name}</strong>`);
                    } else {
                        $('#edit_status_by').text(spa.status_by || 'Not available');
                    }

                    // Set status remarks
                    $('#edit_status_remarks').text(spa.status_remarks || 'No remarks available');
                } else {
                    toastr.error(response.message || 'Failed to load SPA details');
                    setDefaultStatusInfo(status);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                // Show a specific and helpful error message
                if (xhr.status === 404) {
                    toastr.error('Route not found. Please check the URL configuration.');
                } else if (xhr.status === 500) {
                    toastr.error('Server error occurred. Please check the server logs for details.');
                } else {
                    toastr.error('Failed to load SPA details: ' + (error || 'Unknown error'));
                }

                setDefaultStatusInfo(status);
            }
        });
    });

    // Helper function to set default status information
    function setDefaultStatusInfo(status) {
        // Set default values for status information
        $('#edit_status_badge').html(`
            <span class="badge bg-${status == 1 ? 'success' : 'danger'}">
                ${status == 1 ? 'Active' : 'Inactive'}
            </span>
        `);
        $('#edit_status_at').text('Not available');
        $('#edit_status_by').text('Not available');
        $('#edit_status_remarks').text('No remarks available');
    }

    // Update SPA
    $('#editSpaForm').on('submit', function(e) {
        e.preventDefault();

        // Show loading indication
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        submitBtn.prop('disabled', true);

        // Get form data
        const formData = $(this).serialize();
        const id = $('#edit_id').val();

        // Get CSRF token
        const csrfToken = $('meta[name="<?= csrf_token() ?>"]').attr('content');
        const csrfData = '&<?= csrf_token() ?>=' + csrfToken;

        $.ajax({
            url: `<?= base_url('admin/mtdp-plans/spas/') ?>` + id,
            type: 'POST',
            data: formData + csrfData,
            dataType: 'json',
            success: function(response) {
                // Reset button
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);

                console.log('Success response:', response);

                if (response.status === 'success') {
                    // Show success message
                    toastr.success(response.message || 'SPA updated successfully');

                    // Close modal
                    $('#editSpaModal').modal('hide');

                    // Reload page to show updated data
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    // Show error message
                    toastr.error(response.message || 'Failed to update SPA');
                }
            },
            error: function(xhr, status, error) {
                // Reset button
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);

                console.error('AJAX Error:', error);
                console.log('XHR Status:', xhr.status);
                console.log('XHR Response Text:', xhr.responseText);

                // Show a specific and helpful error message
                if (xhr.status === 404) {
                    toastr.error('Route not found. Please check the URL configuration.');
                } else if (xhr.status === 500) {
                    toastr.error('Server error occurred. Please check the server logs for details.');
                } else if (xhr.status === 403) {
                    toastr.error('CSRF validation failed. Please refresh the page and try again.');
                } else {
                    toastr.error('Failed to update SPA: ' + (error || 'Unknown error'));
                }
            }
        });
    });

    // Toggle Status - populate form
    $('.toggle-status-btn').on('click', function() {
        const id = $(this).data('id');
        const status = $(this).data('status');

        $('#status_id').val(id);

        // Update the status message based on current status
        if (status == 1) {
            $('#status_message').html('<i class="fas fa-info-circle"></i> You are about to <strong>deactivate</strong> this SPA.');
            $('#status_message').removeClass('alert-success').addClass('alert-warning');
            $('#toggle_status_button').removeClass('btn-success').addClass('btn-warning').text('Deactivate SPA');
        } else {
            $('#status_message').html('<i class="fas fa-info-circle"></i> You are about to <strong>activate</strong> this SPA.');
            $('#status_message').removeClass('alert-warning').addClass('alert-success');
            $('#toggle_status_button').removeClass('btn-warning').addClass('btn-success').text('Activate SPA');
        }

        // Clear any previously entered remarks
        $('#spa_status_remarks').val('');
    });

    // Toggle SPA Status
    $('#toggleStatusForm').on('submit', function(e) {
        e.preventDefault();

        // Show loading indication
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        submitBtn.prop('disabled', true);

        // Get CSRF token
        const csrfToken = $('meta[name="<?= csrf_token() ?>"]').attr('content');
        const id = $('#status_id').val();

        $.ajax({
            url: `<?= base_url('admin/mtdp-plans/spas/') ?>` + id + '/toggle-status',
            type: 'POST',
            data: {
                id: id,
                spa_status_remarks: $('#spa_status_remarks').val(),
                <?= csrf_token() ?>: csrfToken
            },
            dataType: 'json',
            success: function(response) {
                // Reset button
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);

                console.log('Success response:', response);

                if (response.status === 'success') {
                    // Show success message
                    toastr.success(response.message || 'SPA status updated successfully');

                    // Close modal
                    $('#toggleStatusModal').modal('hide');

                    // Reload page to show updated data
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    // Show error message
                    toastr.error(response.message || 'Failed to update SPA status');
                }
            },
            error: function(xhr, status, error) {
                // Reset button
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);

                console.error('AJAX Error:', error);
                console.log('XHR Status:', xhr.status);
                console.log('XHR Response Text:', xhr.responseText);

                // Show a specific and helpful error message
                if (xhr.status === 404) {
                    toastr.error('Route not found. Please check the URL configuration.');
                } else if (xhr.status === 500) {
                    toastr.error('Server error occurred. Please check the server logs for details.');
                } else if (xhr.status === 403) {
                    toastr.error('CSRF validation failed. Please refresh the page and try again.');
                } else {
                    toastr.error('Failed to update SPA status: ' + (error || 'Unknown error'));
                }
            }
        });
    });

    // Error handling for all AJAX requests
    $(document).ajaxError(function(event, jqxhr, settings, error) {
        console.error('Global AJAX Error:', error);

        // Determine appropriate error message based on status code
        let errorMessage = 'An error occurred while processing your request.';

        if (jqxhr.status === 404) {
            errorMessage = 'Route not found. Please check the URL configuration.';
        } else if (jqxhr.status === 500) {
            errorMessage = 'Server error occurred. Please check the server logs for details.';
        } else if (jqxhr.status === 403) {
            errorMessage = 'CSRF validation failed. Please refresh the page and try again.';
        } else if (jqxhr.status === 401) {
            errorMessage = 'Authentication required. Please log in again.';
        }

        // Show error notification
        toastr.error(errorMessage);
    });

    // Handle CSV import form submission
    $('#importSpaForm').on('submit', function(e) {
        const fileInput = $('#csv_file')[0];
        const file = fileInput.files[0];

        if (!file) {
            e.preventDefault();
            toastr.error('Please select a CSV file to import');
            return false;
        }

        if (file.type !== 'text/csv' && !file.name.toLowerCase().endsWith('.csv')) {
            e.preventDefault();
            toastr.error('Please select a valid CSV file');
            return false;
        }

        // Show loading indication
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Importing...');
        submitBtn.prop('disabled', true);

        // Form will submit normally since we're uploading a file
        toastr.info('Processing CSV import. Please wait...');
    });
});
</script>

<?php $this->endSection(); ?>
