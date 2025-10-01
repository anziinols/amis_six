<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/gov-structure/provinces') ?>">Provinces</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/gov-structure/provinces/'.$province['id'].'/districts') ?>">Districts in <?= esc($province['name']) ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/gov-structure/districts/'.$district['id'].'/llgs') ?>">LLGs in <?= esc($district['name']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Wards in <?= esc($llg['name']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Wards in <?= esc($llg['name']) ?> LLG</h3>
                    <div>
                        <a href="<?= base_url('admin/gov-structure/districts/'.$district['id'].'/llgs') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back to LLGs
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWardModal">
                            <i class="fas fa-plus"></i> Add Ward
                        </button>
                        <a href="<?= base_url('admin/gov-structure/llgs/'.$llg['id'].'/wards/csv-template') ?>" class="btn btn-success">
                            <i class="fas fa-download"></i> Download CSV Template
                        </a>
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#importWardModal">
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
                                <?php if (empty($wards)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No wards found</td>
                                </tr>
                                <?php else: ?>
                                    <?php $counter = 1; ?>
                                    <?php foreach ($wards as $ward): ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><?= $ward['code'] ?></td>
                                        <td><?= $ward['name'] ?></td>
                                        <td><?= $ward['map_center'] ?></td>
                                        <td><?= esc($ward['map_zoom']) ?></td>
                                        <td>
                                            <button type="button" class="btn btn-outline-warning edit-ward-btn"
                                                    data-id="<?= $ward['id'] ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editWardModal"
                                                    title="Edit"
                                                    style="margin-right: 5px;">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-outline-danger delete-ward-btn"
                                                    data-id="<?= $ward['id'] ?>"
                                                    data-name="<?= esc($ward['name']) ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteWardModal"
                                                    title="Delete">
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

<!-- Add Ward Modal -->
<div class="modal fade" id="addWardModal" tabindex="-1" role="dialog" aria-labelledby="addWardModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="addWardForm" action="<?= base_url('admin/gov-structure/llgs/'.$llg['id'].'/wards') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addWardModalLabel">Add Ward to <?= esc($llg['name']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label for="code">Ward Code</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">Ward Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="map_center">Map Center (Latitude,Longitude)</label>
                        <input type="text" class="form-control" id="map_center" name="map_center" placeholder="e.g. -9.4438,147.1803">
                    </div>
                    <div class="form-group mb-3">
                        <label for="map_zoom">Map Zoom Level</label>
                        <input type="text" class="form-control" id="map_zoom" name="map_zoom" placeholder="e.g. 14">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Ward</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Ward Modal -->
<div class="modal fade" id="editWardModal" tabindex="-1" role="dialog" aria-labelledby="editWardModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editWardForm" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="editWardModalLabel">Edit Ward</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group mb-3">
                        <label for="edit_code">Ward Code</label>
                        <input type="text" class="form-control" id="edit_code" name="code" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_name">Ward Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_map_center">Map Center (Latitude,Longitude)</label>
                        <input type="text" class="form-control" id="edit_map_center" name="map_center" placeholder="e.g. -9.4438,147.1803">
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_map_zoom">Map Zoom Level</label>
                        <input type="text" class="form-control" id="edit_map_zoom" name="map_zoom" placeholder="e.g. 14">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Ward</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Ward Modal -->
<div class="modal fade" id="deleteWardModal" tabindex="-1" role="dialog" aria-labelledby="deleteWardModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteWardModalLabel">Delete Ward</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteWardName"></strong>?</p>
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

<!-- Import Ward CSV Modal -->
<div class="modal fade" id="importWardModal" tabindex="-1" role="dialog" aria-labelledby="importWardModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="importWardForm" action="<?= base_url('admin/gov-structure/llgs/'.$llg['id'].'/wards/csv-import') ?>" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="importWardModalLabel">Import Wards from CSV</h5>
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
                            <li>Wards will be added to <strong><?= esc($llg['name']) ?></strong> LLG</li>
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
                    <button type="submit" class="btn btn-info">Import Wards</button>
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

<!-- JavaScript for Ward handling -->
<script>
$(document).ready(function() {
    // Add Ward Form Submission
    $('#addWardForm').on('submit', function(e) {
        e.preventDefault();
        submitForm($(this), 'POST', function(response) {
            location.reload();
        });
    });

    // Import Ward Form - Allow normal form submission (no AJAX)
    $('#importWardForm').on('submit', function(e) {
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

    // Edit Ward Button Click - Populate Modal
    $('.edit-ward-btn').on('click', function() {
        var wardId = $(this).data('id');
        $('#editWardForm').attr('action', '<?= base_url('admin/gov-structure/wards/') ?>' + wardId);
        $('#edit_id').val(wardId);

        // Fetch Ward data via AJAX
        $.ajax({
            url: '<?= base_url('admin/gov-structure/wards/') ?>' + wardId + '/edit',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && response.ward) {
                    var ward = response.ward;
                    $('#edit_code').val(ward.code);
                    $('#edit_name').val(ward.name);
                    $('#edit_map_center').val(ward.map_center);
                    $('#edit_map_zoom').val(ward.map_zoom);
                } else {
                    toastr.error('Could not load ward data.');
                    $('#editWardModal').modal('hide');
                }
            },
            error: function(xhr, status, error) {
                toastr.error('Error fetching ward data: ' + error);
                $('#editWardModal').modal('hide');
            }
        });
    });

    // Edit Ward Form Submission
    $('#editWardForm').on('submit', function(e) {
        e.preventDefault();
        submitForm($(this), 'POST', function(response) {
            location.reload();
        });
    });

    // Delete Ward Button Click - Populate Modal
    $('.delete-ward-btn').on('click', function() {
        var wardId = $(this).data('id');
        var wardName = $(this).data('name');
        $('#delete_id').val(wardId);
        $('#deleteWardName').text(wardName);
    });

    // Confirm Delete Button Click
    $('#confirmDeleteBtn').on('click', function() {
        var wardId = $('#delete_id').val();
        var url = '<?= base_url('admin/gov-structure/wards/') ?>' + wardId;
        var $button = $(this);
        var csrfName = $('input[name="<?= csrf_token() ?>"]').attr('name');
        var csrfHash = $('input[name="<?= csrf_token() ?>"]').val();

        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');

        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            data: {
                [csrfName]: csrfHash
            },
            success: function(response) {
                $('input[name="<?= csrf_token() ?>"]').val(response.csrf_hash || csrfHash);
                toastr.success(response.message || 'Ward deleted successfully.');
                $('#deleteWardModal').modal('hide');
                location.reload();
            },
            error: function(xhr, status, error) {
                var errorMsg = 'Failed to delete ward.';
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
                var newCsrfHash = xhr.getResponseHeader('X-CSRF-TOKEN');
                if (newCsrfHash) {
                    $('input[name="<?= csrf_token() ?>"]').val(newCsrfHash);
                }
                $button.prop('disabled', false).text('Delete');
            }
        });
    });

    // Helper Function for AJAX Submission
    function submitForm($form, method, successCallback) {
        var url = $form.attr('action');
        var formData = $form.serializeArray();
        var $submitButton = $form.find('button[type="submit"]');
        var originalButtonText = $submitButton.html();
        var csrfName = $form.find('input[name="<?= csrf_token() ?>"]').attr('name');
        var csrfHash = $form.find('input[name="<?= csrf_token() ?>"]').val();

        formData.push({ name: csrfName, value: csrfHash });

        $submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            url: url,
            type: method,
            data: $.param(formData),
            dataType: 'json',
            success: function(response) {
                var newCsrfHash = response.csrf_hash || csrfHash;
                $form.find('input[name="<?= csrf_token() ?>"]').val(newCsrfHash);
                $('input[name="<?= csrf_token() ?>"]').val(newCsrfHash);

                toastr.success(response.message || 'Operation successful.');
                $form.closest('.modal').modal('hide');
                if (successCallback) {
                    successCallback(response);
                }
            },
            error: function(xhr, status, error) {
                var errorMsg = 'An error occurred.';
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
