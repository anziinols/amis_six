<?php
// app/Views/admin/mtdp/mtdp_list.php
$this->extend('templates/system_template');
$this->section('content');
?>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">MTDP Plans</li>
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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMtdpPlanModal">
                            <i class="fas fa-plus"></i> Add MTDP Plan
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="mtdpPlansTable">
                            <thead>
                                <tr>
                                    <th>Abbreviation</th>
                                    <th>Title</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($plans as $plan) : ?>
                                    <tr>
                                        <td><?= $plan['abbrev'] ?></td>
                                        <td><?= $plan['title'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($plan['date_from'])) ?></td>
                                        <td><?= date('d/m/Y', strtotime($plan['date_to'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $plan['mtdp_status'] == 1 ? 'success' : 'danger' ?>">
                                                <?= $plan['mtdp_status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="MTDP Plan Actions">
                                                <a href="<?= base_url('admin/mtdp-plans/spas/' . $plan['id']) ?>"
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View SPAs
                                                </a>

                                                <button type="button" class="btn btn-sm btn-warning edit-plan"
                                                    data-id="<?= $plan['id'] ?>"
                                                    data-abbrev="<?= $plan['abbrev'] ?>"
                                                    data-title="<?= $plan['title'] ?>"
                                                    data-date-from="<?= $plan['date_from'] ?>"
                                                    data-date-to="<?= $plan['date_to'] ?>"
                                                    data-remarks="<?= $plan['remarks'] ?>"
                                                    data-mtdp-status="<?= $plan['mtdp_status'] ?>"
                                                    data-mtdp-status-by="<?= $plan['mtdp_status_by'] ?>"
                                                    data-mtdp-status-at="<?= $plan['mtdp_status_at'] ?>"
                                                    data-mtdp-status-remarks="<?= $plan['mtdp_status_remarks'] ?>"
                                                    data-bs-toggle="modal" data-bs-target="#editMtdpPlanModal">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>

                                                <button type="button" class="btn btn-sm btn-<?= $plan['mtdp_status'] == 1 ? 'danger' : 'success' ?> toggle-status"
                                                    data-id="<?= $plan['id'] ?>"
                                                    data-mtdp-status="<?= $plan['mtdp_status'] ?>">
                                                    <i class="fas fa-<?= $plan['mtdp_status'] == 1 ? 'ban' : 'check' ?>"></i>
                                                    <?= $plan['mtdp_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                                                </button>

                                                <button type="button" class="btn btn-sm btn-danger delete-plan"
                                                    data-id="<?= $plan['id'] ?>"
                                                    data-title="<?= $plan['title'] ?>">
                                                    <i class="fas fa-trash"></i> Delete
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

<!-- Add MTDP Plan Modal -->
<div class="modal fade" id="addMtdpPlanModal" tabindex="-1" role="dialog" aria-labelledby="addMtdpPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMtdpPlanModalLabel">Add MTDP Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addMtdpPlanForm">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label for="abbrev">Abbreviation</label>
                        <input type="text" class="form-control" id="abbrev" name="abbrev" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="date_from">Start Date</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="date_to">End Date</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit MTDP Plan Modal -->
<div class="modal fade" id="editMtdpPlanModal" tabindex="-1" role="dialog" aria-labelledby="editMtdpPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMtdpPlanModalLabel">Edit MTDP Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editMtdpPlanForm">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group mb-3">
                        <label for="edit_abbrev">Abbreviation</label>
                        <input type="text" class="form-control" id="edit_abbrev" name="abbrev" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_title">Title</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_date_from">Start Date</label>
                        <input type="date" class="form-control" id="edit_date_from" name="date_from" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_date_to">End Date</label>
                        <input type="date" class="form-control" id="edit_date_to" name="date_to" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_remarks">Remarks</label>
                        <textarea class="form-control" id="edit_remarks" name="remarks" rows="3"></textarea>
                    </div>

                    <!-- Status Information Section -->
                    <hr>
                    <h6 class="mb-3">Status Information</h6>
                    <div class="mb-3">
                        <label class="fw-bold">Current Status:</label>
                        <div id="edit_status_badge" class="mt-1"></div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Last Status Change:</label>
                        <div id="edit_status_at" class="mt-1"></div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Status Changed By:</label>
                        <div id="edit_status_by" class="mt-1"></div>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Status Remarks:</label>
                        <div id="edit_status_remarks" class="mt-1"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toggle Status Modal -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" role="dialog" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="toggleStatusModalLabel">Change MTDP Plan Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="toggleStatusForm">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" id="status_id" name="id">
                    <input type="hidden" id="current_status" name="current_status">

                    <div class="alert" id="status-message">
                        <p>Are you sure you want to <span id="status-action"></span> this MTDP Plan?</p>
                    </div>

                    <div class="form-group mb-3">
                        <label for="mtdp_status_remarks">Status Remarks</label>
                        <textarea class="form-control" id="mtdp_status_remarks" name="mtdp_status_remarks" rows="3" placeholder="Enter remarks about this status change"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete MTDP Plan Modal -->
<div class="modal fade" id="deletePlanModal" tabindex="-1" role="dialog" aria-labelledby="deletePlanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deletePlanModalLabel">Delete MTDP Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deletePlanForm">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" id="delete_id" name="id">

                    <div class="alert alert-danger">
                        <h5><i class="fas fa-exclamation-triangle"></i> Warning!</h5>
                        <p>You are about to delete the MTDP Plan: <strong id="delete-plan-title"></strong></p>
                        <p>This will also delete all related data including:</p>
                        <ul>
                            <li>Strategic Priority Areas (SPAs)</li>
                            <li>Deliberate Intervention Programs (DIPs)</li>
                            <li>Specific Areas</li>
                            <li>Key Result Areas (KRAs)</li>
                            <li>Strategies</li>
                            <li>Indicators</li>
                            <li>Investments</li>
                        </ul>
                        <p class="mb-0"><strong>This action cannot be undone!</strong></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Permanently</button>
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
    $('#mtdpPlansTable').DataTable();

    // Create MTDP Plan
    $('#addMtdpPlanForm').on('submit', function(e) {
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
            url: '<?= base_url('admin/mtdp-plans') ?>',
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
                    toastr.success(response.message || 'MTDP Plan created successfully');

                    // Close modal
                    $('#addMtdpPlanModal').modal('hide');

                    // Reload page to show updated data
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    // Show error message
                    toastr.error(response.message || 'Failed to create MTDP plan');
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
                    toastr.error('Failed to create MTDP plan: ' + (error || 'Unknown error'));
                }
            }
        });
    });

    // Edit MTDP Plan - populate form
    $('.edit-plan').on('click', function() {
        const id = $(this).data('id');

        // First load the basic data
        const abbrev = $(this).data('abbrev');
        const title = $(this).data('title');
        const dateFrom = $(this).data('date-from');
        const dateTo = $(this).data('date-to');
        const remarks = $(this).data('remarks');
        const status = $(this).data('mtdp-status');

        // Set the basic form values
        $('#edit_id').val(id);
        $('#edit_abbrev').val(abbrev);
        $('#edit_title').val(title);
        $('#edit_remarks').val(remarks);

        // Format dates for input fields (YYYY-MM-DD) with proper error handling
        try {
            if (dateFrom) {
                // Try to parse the date with different formats
                let date = null;
                if (dateFrom.includes('-')) {
                    // Already in YYYY-MM-DD format
                    date = dateFrom;
                } else {
                    // Try to convert from different format
                    date = new Date(dateFrom);
                    date = date.getFullYear() + '-' +
                        String(date.getMonth() + 1).padStart(2, '0') + '-' +
                        String(date.getDate()).padStart(2, '0');
                }
                $('#edit_date_from').val(date);
            }
        } catch (e) {
            console.error('Error parsing date_from:', e);
            $('#edit_date_from').val('');
        }

        try {
            if (dateTo) {
                // Try to parse the date with different formats
                let date = null;
                if (dateTo.includes('-')) {
                    // Already in YYYY-MM-DD format
                    date = dateTo;
                } else {
                    // Try to convert from different format
                    date = new Date(dateTo);
                    date = date.getFullYear() + '-' +
                        String(date.getMonth() + 1).padStart(2, '0') + '-' +
                        String(date.getDate()).padStart(2, '0');
                }
                $('#edit_date_to').val(date);
            }
        } catch (e) {
            console.error('Error parsing date_to:', e);
            $('#edit_date_to').val('');
        }

        // Fetch detailed plan information including user data
        $.ajax({
            url: '<?= base_url('admin/mtdp-plans/details/') ?>' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const plan = response.data;

                    // Set status badge
                    $('#edit_status_badge').html(`
                        <span class="badge bg-${plan.mtdp_status == 1 ? 'success' : 'danger'}">
                            ${plan.mtdp_status == 1 ? 'Active' : 'Inactive'}
                        </span>
                    `);

                    // Format and display status date
                    if (plan.status_at) {
                        try {
                            const statusDate = new Date(plan.status_at);
                            const formattedStatusDate = statusDate.toLocaleDateString() + ' ' + statusDate.toLocaleTimeString();
                            $('#edit_status_at').text(formattedStatusDate);
                        } catch (e) {
                            console.error('Error parsing status_at date:', e);
                            $('#edit_status_at').text(plan.status_at || 'Not available');
                        }
                    } else {
                        $('#edit_status_at').text('Not available');
                    }

                    // Set status by with name and email if available
                    if (plan.status_by_name && plan.status_by_email) {
                        $('#edit_status_by').html(`
                            <strong>${plan.status_by_name}</strong> ${plan.status_by_email ? '<br><small class="text-muted">' + plan.status_by_email + '</small>' : ''}
                        `);
                    } else if (plan.status_by_name) {
                        $('#edit_status_by').html(`<strong>${plan.status_by_name}</strong>`);
                    } else {
                        $('#edit_status_by').text(plan.status_by || 'Not available');
                    }

                    // Set status remarks
                    $('#edit_status_remarks').text(plan.status_remarks || 'No remarks available');
                } else {
                    toastr.error(response.message || 'Failed to load plan details');
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
                    toastr.error('Failed to load plan details: ' + (error || 'Unknown error'));
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

    // Update MTDP Plan
    $('#editMtdpPlanForm').on('submit', function(e) {
        e.preventDefault();

        // Show loading indication
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        submitBtn.prop('disabled', true);

        const formData = $(this).serialize();
        const id = $('#edit_id').val();

        // Get CSRF token
        const csrfToken = $('meta[name="<?= csrf_token() ?>"]').attr('content');

        $.ajax({
            url: `<?= base_url('admin/mtdp-plans/') ?>` + id,
            type: 'POST',
            data: formData + '&<?= csrf_token() ?>=' + csrfToken,
            dataType: 'json',
            success: function(response) {
                // Reset button
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);

                console.log('Success response:', response);

                if (response.status === 'success') {
                    // Show success message
                    toastr.success(response.message || 'MTDP Plan updated successfully');

                    // Close modal
                    $('#editMtdpPlanModal').modal('hide');

                    // Reload page to show updated data
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    // Show error message
                    toastr.error(response.message || 'Failed to update MTDP plan');
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
                    toastr.error('Failed to update MTDP plan: ' + (error || 'Unknown error'));
                }
            }
        });
    });

    // Toggle Status - Show Modal with Details
    $('.toggle-status').on('click', function() {
        const id = $(this).data('id');
        const status = $(this).data('mtdp-status');

        $('#status_id').val(id);
        $('#current_status').val(status);

        // Set the message based on current status
        if (status == 1) {
            $('#status-message').removeClass('alert-success').addClass('alert-warning');
            $('#status-action').text('deactivate');
        } else {
            $('#status-message').removeClass('alert-warning').addClass('alert-success');
            $('#status-action').text('activate');
        }

        // Clear any previous remarks
        $('#mtdp_status_remarks').val('');

        // Show the modal
        $('#toggleStatusModal').modal('show');
    });

    // Submit Status Change
    $('#toggleStatusForm').on('submit', function(e) {
        e.preventDefault();

        // Show loading indication
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        submitBtn.prop('disabled', true);

        const id = $('#status_id').val();
        const statusRemarks = $('#mtdp_status_remarks').val();

        // Get CSRF token
        const csrfToken = $('meta[name="<?= csrf_token() ?>"]').attr('content');

        $.ajax({
            url: `<?= base_url('admin/mtdp-plans/') ?>` + id + '/toggle-status',
            type: 'POST',
            data: {
                id: id,
                mtdp_status_remarks: statusRemarks,
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
                    toastr.success(response.message || 'MTDP Plan status updated successfully');

                    // Close modal
                    $('#toggleStatusModal').modal('hide');

                    // Reload page to show updated data
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    // Show error message
                    toastr.error(response.message || 'Failed to update MTDP plan status');
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
                    toastr.error('Failed to update MTDP plan status: ' + (error || 'Unknown error'));
                }
            }
        });
    });

    // Delete MTDP Plan - Show confirmation modal
    $('.delete-plan').on('click', function() {
        const id = $(this).data('id');
        const title = $(this).data('title');

        $('#delete_id').val(id);
        $('#delete-plan-title').text(title);

        // Show the modal
        $('#deletePlanModal').modal('show');
    });

    // Submit Delete Plan Form
    $('#deletePlanForm').on('submit', function(e) {
        e.preventDefault();

        // Show loading indication
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        submitBtn.prop('disabled', true);

        const id = $('#delete_id').val();

        // Get CSRF token
        const csrfToken = $('meta[name="<?= csrf_token() ?>"]').attr('content');

        $.ajax({
            url: `<?= base_url('admin/mtdp-plans/') ?>` + id,
            type: 'POST', // Using POST with _method parameter for better compatibility
            data: {
                id: id,
                _method: 'DELETE', // Simulate DELETE method
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
                    toastr.success(response.message || 'MTDP Plan deleted successfully');

                    // Close modal
                    $('#deletePlanModal').modal('hide');

                    // Reload page to show updated data
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    // Show error message
                    toastr.error(response.message || 'Failed to delete MTDP Plan');
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
                    toastr.error('Failed to delete MTDP Plan: ' + (error || 'Unknown error'));
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
        } else if (jqxhr.status === 401) {
            errorMessage = 'Authentication required. Please log in again.';
        }

        // Show error notification
        toastr.error(errorMessage);
    });
});
</script>

<?php $this->endSection() ?>
