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
                    <li class="breadcrumb-item active" aria-current="page">Investments for <?= $specificArea['sa_title'] ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0"><?= esc($title) ?></h3>
                    <div>
                        <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back to Specific Areas
                        </a>
                        <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/investments/new') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Investment
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="investmentsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Investment Item</th>
                                    <th>Year 1</th>
                                    <th>Year 2</th>
                                    <th>Year 3</th>
                                    <th>Year 4</th>
                                    <th>Year 5</th>
                                    <th>Funding Sources</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 1; ?>
                                <?php foreach ($investments as $investment) : ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><?= $investment['investment'] ?></td>
                                        <td><?= number_format($investment['year_one'], 2) ?></td>
                                        <td><?= number_format($investment['year_two'], 2) ?></td>
                                        <td><?= number_format($investment['year_three'], 2) ?></td>
                                        <td><?= number_format($investment['year_four'], 2) ?></td>
                                        <td><?= number_format($investment['year_five'], 2) ?></td>
                                        <td><?= $investment['funding_sources'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $investment['investment_status'] == 1 ? 'success' : 'danger' ?>">
                                                <?= $investment['investment_status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/investments/' . $investment['id']) ?>" class="btn btn-outline-primary btn-sm" style="margin-right: 5px;">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                            <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/investments/' . $investment['id'] . '/edit') ?>" class="btn btn-outline-warning btn-sm" style="margin-right: 5px;">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            <a href="<?= base_url('admin/mtdp-plans/investments/' . $investment['id'] . '/kras') ?>" class="btn btn-outline-primary btn-sm" style="margin-right: 5px;">
                                                <i class="fas fa-list-check me-1"></i> View KRAs
                                            </a>
                                            <button type="button" class="btn btn-outline-<?= $investment['investment_status'] == 1 ? 'secondary' : 'success' ?> btn-sm toggle-status-btn"
                                                    data-id="<?= $investment['id'] ?>" data-status="<?= $investment['investment_status'] ?>" data-bs-toggle="modal" data-bs-target="#toggleStatusModal">
                                                <i class="fas fa-toggle-<?= $investment['investment_status'] == 1 ? 'off' : 'on' ?> me-1"></i>
                                                <?= $investment['investment_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                                            </button>
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
                <h5 class="modal-title" id="toggleStatusTitle">Toggle Investment Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="toggleStatusForm" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" id="toggle_id" name="id" value="">

                    <div id="statusChangeMessage" class="alert alert-warning">
                        Are you sure you want to change the status of this Investment?
                    </div>

                    <div class="form-group mt-3">
                        <label for="investment_status_remarks">Status Change Remarks</label>
                        <textarea class="form-control" id="investment_status_remarks" name="investment_status_remarks" rows="3"></textarea>
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
    $('#investmentsTable').DataTable({
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
            $('#toggleStatusTitle').text('Deactivate Investment');
            $('#statusChangeMessage').html('Are you sure you want to <strong>deactivate</strong> this Investment?');
            $('#confirmToggleBtn').text('Deactivate').removeClass('btn-success').addClass('btn-danger');
        } else {
            $('#toggleStatusTitle').text('Activate Investment');
            $('#statusChangeMessage').html('Are you sure you want to <strong>activate</strong> this Investment?');
            $('#confirmToggleBtn').text('Activate').removeClass('btn-danger').addClass('btn-success');
        }
    });

    // Handle form submission with AJAX
    $('#toggleStatusForm').on('submit', function(e) {
        e.preventDefault();

        var id = $('#toggle_id').val();
        var remarks = $('#investment_status_remarks').val();
        var submitBtn = $('#confirmToggleBtn');
        var originalText = submitBtn.html();

        // Disable button and show loading state
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        submitBtn.prop('disabled', true);

        // AJAX request
        $.ajax({
            url: '<?= base_url("admin/mtdp-plans/investments") ?>/' + id + '/toggle-status',
            type: 'POST',
            data: {
                id: id,
                investment_status_remarks: remarks,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                // Reset button
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);

                if (response.status === 'success') {
                    // Show success message
                    toastr.success(response.message || 'Investment status updated successfully');

                    // Close modal
                    $('#toggleStatusModal').modal('hide');

                    // Reload page to show updated data
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    // Show error message
                    toastr.error(response.message || 'Failed to update Investment status');
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
