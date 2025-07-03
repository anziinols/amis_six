<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">List of Branches</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBranchModal">
                    <i class="fas fa-plus"></i> Add Branch
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="branchesTable">
                        <thead>
                            <tr>
                                <th>Branch Name</th>
                                <th>Abbreviation</th>
                                <th>Parent Branch</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Updated By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($branches as $branch): ?>
                            <tr>
                                <td><?= $branch['name'] ?></td>
                                <td><?= $branch['abbrev'] ?></td>
                                <td><?= $branch['parent_name'] ?? 'Top Level' ?></td>
                                <td><?= $branch['remarks'] ?></td>
                                <td>
                                    <?php if ($branch['branch_status'] == 1): ?>
                                    <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                    <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $branch['created_by'] ?><br><small class="text-muted"><?= $branch['created_at'] ?></small></td>
                                <td>
                                    <?php if ($branch['updated_by']): ?>
                                    <?= $branch['updated_by'] ?><br><small class="text-muted"><?= $branch['updated_at'] ?></small>
                                    <?php else: ?>
                                    -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info edit-branch" data-id="<?= $branch['id'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php if ($branch['branch_status'] == 1): ?>
                                        <button type="button" class="btn btn-sm btn-warning toggle-status" data-id="<?= $branch['id'] ?>" data-branch_status="<?= $branch['branch_status'] ?>" title="Deactivate">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                        <?php else: ?>
                                        <button type="button" class="btn btn-sm btn-success toggle-status" data-id="<?= $branch['id'] ?>" data-branch_status="<?= $branch['branch_status'] ?>" title="Activate">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <?php endif; ?>
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

<!-- Add Branch Modal -->
<div class="modal fade" id="addBranchModal" tabindex="-1" aria-labelledby="addBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBranchModalLabel">Add New Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addBranchForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="parentBranch" class="form-label">Parent Branch</label>
                        <select class="form-select" id="parentBranch" name="parent_id">
                            <option value="">-- Select Parent Branch (optional) --</option>
                            <?php foreach ($branchOptions as $option): ?>
                            <option value="<?= $option['id'] ?>"><?= $option['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="branchName" class="form-label">Branch Name</label>
                        <input type="text" class="form-control" id="branchName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="branchAbbrev" class="form-label">Abbreviation</label>
                        <input type="text" class="form-control" id="branchAbbrev" name="abbrev" required>
                    </div>
                    <div class="mb-3">
                        <label for="branchRemarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="branchRemarks" name="remarks" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Branch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Branch Modal -->
<div class="modal fade" id="editBranchModal" tabindex="-1" aria-labelledby="editBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBranchModalLabel">Edit Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editBranchForm">
                <?= csrf_field() ?>
                <input type="hidden" id="editBranchId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editParentBranch" class="form-label">Parent Branch</label>
                        <select class="form-select" id="editParentBranch" name="parent_id">
                            <option value="">-- Select Parent Branch (optional) --</option>
                            <?php foreach ($branchOptions as $option): ?>
                            <option value="<?= $option['id'] ?>"><?= $option['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editBranchName" class="form-label">Branch Name</label>
                        <input type="text" class="form-control" id="editBranchName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editBranchAbbrev" class="form-label">Abbreviation</label>
                        <input type="text" class="form-control" id="editBranchAbbrev" name="abbrev" required>
                    </div>
                    <div class="mb-3">
                        <label for="editBranchRemarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="editBranchRemarks" name="remarks" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Branch</button>
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
                <h5 class="modal-title" id="toggleStatusModalLabel">Confirm Status Change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="toggleStatusForm">
                <?= csrf_field() ?>
                <input type="hidden" id="toggleBranchId" name="id">
                <input type="hidden" id="toggleCurrentStatus" name="current_status">
                <div class="modal-body">
                    <p id="toggleStatusMessage"></p>
                    <div class="mb-3">
                        <label for="statusRemarks" class="form-label">Status Change Remarks</label>
                        <textarea class="form-control" id="statusRemarks" name="branch_status_remarks" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="confirmToggleStatus">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#branchesTable').DataTable({
        responsive: true,
        order: [[0, 'asc']]
    });
    
    // Function to update CSRF token
    function updateCsrfToken(token, tokenName) {
        $('input[name="<?= csrf_token() ?>"]').val(token);
        if (tokenName) {
            $('input[name="<?= csrf_token() ?>"]').attr('name', tokenName);
        }
    }
    
    // Add Branch Form Submission
    $('#addBranchForm').submit(function(e) {
        e.preventDefault();
        
        // Disable submit button to prevent multiple submissions
        $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        
        $.ajax({
            url: '<?= base_url('admin/branches') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                // Re-enable button
                $('#addBranchForm').find('button[type="submit"]').prop('disabled', false).text('Add Branch');
                
                if (response.status === 'success') {
                    // Update CSRF token
                    if (response.csrf_token && response.csrf_token_name) {
                        updateCsrfToken(response.csrf_token, response.csrf_token_name);
                    }
                    
                    // Close modal and show success message
                    $('#addBranchModal').modal('hide');
                    toastr.success(response.message);
                    // Reload page after a short delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    // Show error message
                    toastr.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                // Re-enable button
                $('#addBranchForm').find('button[type="submit"]').prop('disabled', false).text('Add Branch');
                
                // Log error details to console for debugging
                console.log('XHR:', xhr);
                console.log('Status:', status);
                console.log('Error:', error);
                
                try {
                    var response = JSON.parse(xhr.responseText);
                    
                    // Check if response contains new CSRF token
                    if (response.csrf_token && response.csrf_token_name) {
                        updateCsrfToken(response.csrf_token, response.csrf_token_name);
                    }
                    
                    if (response && response.message) {
                        if (typeof response.message === 'object') {
                            // Handle validation errors
                            var errorMessages = Object.values(response.message).join('<br>');
                            toastr.error(errorMessages);
                        } else {
                            // Show specific error message from server
                            toastr.error(response.message);
                        }
                    } else {
                        toastr.error('An error occurred while adding the branch. Please try again.');
                    }
                } catch (e) {
                    // If response isn't valid JSON
                    toastr.error('An error occurred while processing your request. Please try again later.');
                }
            }
        });
    });
    
    // Load Branch Data for Editing
    $('.edit-branch').click(function() {
        var branchId = $(this).data('id');
        
        $.ajax({
            url: '<?= base_url('admin/branches') ?>/' + branchId + '/edit',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Populate form with branch data
                    $('#editBranchId').val(response.data.id);
                    $('#editBranchName').val(response.data.name);
                    $('#editBranchAbbrev').val(response.data.abbrev);
                    $('#editBranchRemarks').val(response.data.remarks);
                    $('#editParentBranch').val(response.data.parent_id || '').trigger('change');
                    
                    // Show modal
                    $('#editBranchModal').modal('show');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                toastr.error('Failed to load branch data. Please try again.');
            }
        });
    });
    
    // Edit Branch Form Submission
    $('#editBranchForm').submit(function(e) {
        e.preventDefault();
        
        var branchId = $('#editBranchId').val();
        
        // Disable submit button to prevent multiple submissions
        $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        
        $.ajax({
            url: '<?= base_url('admin/branches') ?>/' + branchId,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                // Re-enable button
                $('#editBranchForm').find('button[type="submit"]').prop('disabled', false).text('Update Branch');
                
                if (response.status === 'success') {
                    // Update CSRF token
                    if (response.csrf_token && response.csrf_token_name) {
                        updateCsrfToken(response.csrf_token, response.csrf_token_name);
                    }
                    
                    // Close modal and show success message
                    $('#editBranchModal').modal('hide');
                    toastr.success(response.message);
                    // Reload page after a short delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    // Show error message
                    toastr.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                // Re-enable button
                $('#editBranchForm').find('button[type="submit"]').prop('disabled', false).text('Update Branch');
                
                // Log error details to console for debugging
                console.log('XHR:', xhr);
                console.log('Status:', status);
                console.log('Error:', error);
                
                try {
                    var response = JSON.parse(xhr.responseText);
                    
                    // Check if response contains new CSRF token
                    if (response.csrf_token && response.csrf_token_name) {
                        updateCsrfToken(response.csrf_token, response.csrf_token_name);
                    }
                    
                    if (response && response.message) {
                        if (typeof response.message === 'object') {
                            // Handle validation errors
                            var errorMessages = Object.values(response.message).join('<br>');
                            toastr.error(errorMessages);
                        } else {
                            // Show specific error message from server
                            toastr.error(response.message);
                        }
                    } else {
                        toastr.error('An error occurred while updating the branch. Please try again.');
                    }
                } catch (e) {
                    // If response isn't valid JSON
                    toastr.error('An error occurred while processing your request. Please try again later.');
                }
            }
        });
    });
    
    // Open Toggle Status Modal
    $('.toggle-status').click(function() {
        var branchId = $(this).data('id');
        var currentStatus = $(this).data('branch_status');
        var actionText = currentStatus == 1 ? 'deactivate' : 'activate';
        
        $('#toggleBranchId').val(branchId);
        $('#toggleCurrentStatus').val(currentStatus);
        $('#toggleStatusMessage').text('Are you sure you want to ' + actionText + ' this branch?');
        
        $('#toggleStatusModal').modal('show');
    });
    
    // Toggle Status Form Submission
    $('#toggleStatusForm').submit(function(e) {
        e.preventDefault();
        
        var id = $('#toggleBranchId').val();
        var currentStatus = $('#toggleCurrentStatus').val();
        
        // Validate status remarks before submission
        var statusRemarks = $('#statusRemarks').val();
        if (!statusRemarks || statusRemarks.trim() === '') {
            toastr.error('Please provide status remarks');
            return false;
        }
        
        // Disable submit button to prevent multiple submissions
        $('#confirmToggleStatus').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        
        $.ajax({
            url: '<?= base_url('admin/branches') ?>/' + id + '/toggle-status',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                // Re-enable button
                $('#confirmToggleStatus').prop('disabled', false).text('Confirm');
                
                if (response.status === 'success') {
                    // Update CSRF token
                    if (response.csrf_token && response.csrf_token_name) {
                        updateCsrfToken(response.csrf_token, response.csrf_token_name);
                    }
                    
                    // Close modal and show success message
                    $('#toggleStatusModal').modal('hide');
                    toastr.success(response.message);
                    // Reload page after a short delay
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    // Show error message
                    toastr.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                // Re-enable button
                $('#confirmToggleStatus').prop('disabled', false).text('Confirm');
                
                // Log error details to console for debugging
                console.log('XHR:', xhr);
                console.log('Status:', status);
                console.log('Error:', error);
                
                try {
                    var response = JSON.parse(xhr.responseText);
                    
                    // Check if response contains new CSRF token
                    if (response.csrf_token && response.csrf_token_name) {
                        updateCsrfToken(response.csrf_token, response.csrf_token_name);
                    }
                    
                    if (response && response.message) {
                        // Show specific error message from server
                        toastr.error(response.message);
                    } else {
                        toastr.error('An error occurred while processing your request.');
                    }
                } catch (e) {
                    // If response isn't valid JSON
                    if (xhr.status === 400) {
                        // Handle common 400 errors
                        toastr.error('Invalid request. Please check your input and try again.');
                    } else if (xhr.status === 404) {
                        toastr.error('Branch not found. It may have been deleted or moved.');
                    } else if (xhr.status === 403) {
                        toastr.error('Your session may have expired. Please refresh the page and try again.');
                    } else {
                        toastr.error('An error occurred while processing your request. Please try again later.');
                    }
                }
            }
        });
    });
    
    // Reset forms when modals are closed
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        if ($(this).attr('id') === 'addBranchModal') {
            $('#parentBranch').val('').trigger('change');
        } else if ($(this).attr('id') === 'editBranchModal') {
            $('#editParentBranch').val('').trigger('change');
        }
    });
});
</script>
<?= $this->endSection() ?>