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
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/mtdp-plans/spas/' . $mtdp['id']) ?>"><?= $mtdp['title'] ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/mtdp-plans/spas/' . $spa['id'] . '/dips') ?>"><?= $spa['title'] ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas') ?>">Specific Areas</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $specificArea['sa_code'] ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Display success or error messages -->
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success"><?= session('success') ?></div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger"><?= session('error') ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Specific Area Details: <?= $specificArea['sa_code'] ?></h3>
            <div class="card-tools">
                <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/edit') ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <button type="button" class="btn btn-<?= $specificArea['sa_status'] == 1 ? 'danger' : 'success' ?> toggle-status-btn ms-1"
                        data-id="<?= $specificArea['id'] ?>" data-status="<?= $specificArea['sa_status'] ?>" data-bs-toggle="modal" data-bs-target="#toggleStatusModal">
                    <i class="fas fa-toggle-<?= $specificArea['sa_status'] == 1 ? 'off' : 'on' ?>"></i>
                    <?= $specificArea['sa_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                </button>
                <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas') ?>" class="btn btn-secondary ms-1">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Basic Information -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Code:</div>
                        <div class="col-md-9"><?= $specificArea['sa_code'] ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Title:</div>
                        <div class="col-md-9"><?= $specificArea['sa_title'] ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Remarks:</div>
                        <div class="col-md-9"><?= $specificArea['sa_remarks'] ?: '<em>No remarks provided</em>' ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Status:</div>
                        <div class="col-md-9">
                            <span class="badge bg-<?= $specificArea['sa_status'] == 1 ? 'success' : 'danger' ?>">
                                <?= $specificArea['sa_status'] == 1 ? 'Active' : 'Inactive' ?>
                            </span>
                        </div>
                    </div>
                    <?php if (!empty($specificArea['sa_status_remarks'])): ?>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Status Remarks:</div>
                        <div class="col-md-9"><?= $specificArea['sa_status_remarks'] ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Related Information -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Related Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">MTDP Plan:</div>
                        <div class="col-md-9">
                            <a href="<?= base_url('admin/mtdp-plans/' . $mtdp['id']) ?>"><?= $mtdp['title'] ?></a>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Strategic Priority Area:</div>
                        <div class="col-md-9">
                            <a href="<?= base_url('admin/mtdp-plans/spas/' . $spa['id']) ?>"><?= $spa['title'] ?></a>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Deliberate Intervention Program:</div>
                        <div class="col-md-9">
                            <a href="<?= base_url('admin/mtdp-plans/spas/' . $spa['id'] . '/dips/' . $dip['id']) ?>"><?= $dip['dip_title'] ?></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Metadata</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Created:</div>
                        <div class="col-md-9">
                            <?= date('F j, Y, g:i a', strtotime($specificArea['created_at'])) ?>
                            by <?= $createdByName ?>
                        </div>
                    </div>
                    <?php if (!empty($specificArea['updated_at']) && $specificArea['updated_at'] != $specificArea['created_at']): ?>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Last Updated:</div>
                        <div class="col-md-9">
                            <?= date('F j, Y, g:i a', strtotime($specificArea['updated_at'])) ?>
                            by <?= $updatedByName ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Status Last Changed:</div>
                        <div class="col-md-9">
                            <?= date('F j, Y, g:i a', strtotime($specificArea['sa_status_at'])) ?>
                            by <?= $statusByName ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Status Modal -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="toggleStatusTitle">Toggle Specific Area Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="toggleStatusForm" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" id="toggle_id" name="id" value="">

                    <div id="statusChangeMessage" class="alert alert-warning">
                        Are you sure you want to change the status of this Specific Area?
                    </div>

                    <div class="form-group mt-3">
                        <label for="sa_status_remarks">Status Change Remarks</label>
                        <textarea class="form-control" id="sa_status_remarks" name="sa_status_remarks" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="confirmToggleBtn" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for page functionality -->
<script>
$(document).ready(function() {
    // Toggle Status button click
    $('.toggle-status-btn').on('click', function() {
        var id = $(this).data('id');
        var status = $(this).data('status');

        // Set values and update UI
        $('#toggle_id').val(id);

        if (status == 1) {
            $('#toggleStatusTitle').text('Deactivate Specific Area');
            $('#statusChangeMessage').html('Are you sure you want to <strong>deactivate</strong> this Specific Area?');
            $('#confirmToggleBtn').text('Deactivate').removeClass('btn-success').addClass('btn-danger');
        } else {
            $('#toggleStatusTitle').text('Activate Specific Area');
            $('#statusChangeMessage').html('Are you sure you want to <strong>activate</strong> this Specific Area?');
            $('#confirmToggleBtn').text('Activate').removeClass('btn-danger').addClass('btn-success');
        }
    });

    // Handle form submission with AJAX
    $('#toggleStatusForm').on('submit', function(e) {
        e.preventDefault();

        var id = $('#toggle_id').val();
        var remarks = $('#sa_status_remarks').val();
        var submitBtn = $('#confirmToggleBtn');
        var originalText = submitBtn.html();

        // Disable button and show loading state
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        submitBtn.prop('disabled', true);

        // AJAX request
        $.ajax({
            url: '<?= base_url("admin/mtdp-plans/specific-areas") ?>/' + id + '/toggle-status',
            type: 'POST',
            data: {
                id: id,
                sa_status_remarks: remarks,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                // Reset button
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);

                if (response.status === 'success') {
                    // Show success message
                    toastr.success(response.message || 'Specific Area status updated successfully');

                    // Close modal
                    $('#toggleStatusModal').modal('hide');

                    // Reload page to show updated data
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    // Show error message
                    toastr.error(response.message || 'Failed to update Specific Area status');
                }
            },
            error: function(xhr, status, error) {
                // Reset button
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);

                // Show error message
                toastr.error('An error occurred: ' + error);
                console.error(xhr.responseText);
            }
        });
    });
});
</script>

<?= $this->endSection(); ?>
