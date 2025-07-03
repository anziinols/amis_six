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
                    <li class="breadcrumb-item active" aria-current="page">Specific Areas in <?= $dip['dip_title'] ?></li>
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
                        <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/new') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Specific Area
                        </a>
                        <a href="<?= base_url('admin/mtdp-plans/spas/' . $spa['id'] . '/dips') ?>" class="btn btn-secondary ms-1">
                            <i class="fas fa-arrow-left"></i> Back to Programs
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="specificAreasTable">
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
                                <?php foreach ($specificAreas as $sa) : ?>
                                    <tr>
                                        <td><?= $sa['sa_code'] ?></td>
                                        <td><?= $sa['sa_title'] ?></td>
                                        <td><?= $sa['sa_remarks'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $sa['sa_status'] == 1 ? 'success' : 'danger' ?>">
                                                <?= $sa['sa_status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Specific Area Actions">
                                                <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/' . $sa['id']) ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/' . $sa['id'] . '/investments') ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-dollar-sign"></i> View Investments
                                                </a>
                                                <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/' . $sa['id'] . '/edit') ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-<?= $sa['sa_status'] == 1 ? 'danger' : 'success' ?> toggle-status-btn"
                                                        data-id="<?= $sa['id'] ?>" data-status="<?= $sa['sa_status'] ?>" data-bs-toggle="modal" data-bs-target="#toggleStatusModal">
                                                    <i class="fas fa-toggle-<?= $sa['sa_status'] == 1 ? 'off' : 'on' ?>"></i>
                                                    <?= $sa['sa_status'] == 1 ? 'Deactivate' : 'Activate' ?>
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
    // Initialize DataTable
    $('#specificAreasTable').DataTable({
        responsive: true,
        order: [[0, 'asc']]
    });

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
