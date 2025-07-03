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
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/investments') ?>">Investments</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Investment Details</li>
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
                        <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/investments/' . $investment['id'] . '/edit') ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button" class="btn btn-<?= $investment['investment_status'] == 1 ? 'danger' : 'success' ?> toggle-status-btn ms-1"
                                data-id="<?= $investment['id'] ?>" data-status="<?= $investment['investment_status'] ?>" data-bs-toggle="modal" data-bs-target="#toggleStatusModal">
                            <i class="fas fa-toggle-<?= $investment['investment_status'] == 1 ? 'off' : 'on' ?>"></i>
                            <?= $investment['investment_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                        </button>
                        <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/investments') ?>" class="btn btn-secondary ms-1">
                            <i class="fas fa-arrow-left"></i> Back to Investments
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Investment Information -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Investment Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Investment Item:</div>
                                <div class="col-md-9"><?= $investment['investment'] ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Funding Sources:</div>
                                <div class="col-md-9"><?= $investment['funding_sources'] ?: 'Not specified' ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Status:</div>
                                <div class="col-md-9">
                                    <span class="badge bg-<?= $investment['investment_status'] == 1 ? 'success' : 'danger' ?>">
                                        <?= $investment['investment_status'] == 1 ? 'Active' : 'Inactive' ?>
                                    </span>
                                </div>
                            </div>
                            <?php if (!empty($investment['dip_link_dip_id'])) :
                                $linkedDip = null;
                                foreach ($allDips as $dipItem) {
                                    if ($dipItem['id'] == $investment['dip_link_dip_id']) {
                                        $linkedDip = $dipItem;
                                        break;
                                    }
                                }
                            ?>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Linked DIP:</div>
                                <div class="col-md-9">
                                    <?php if ($linkedDip): ?>
                                        <?= $linkedDip['dip_code'] ?> - <?= $linkedDip['dip_title'] ?>
                                    <?php else: ?>
                                        DIP ID: <?= $investment['dip_link_dip_id'] ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Yearly Breakdown -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Yearly Investment Breakdown</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Year 1</th>
                                            <th>Year 2</th>
                                            <th>Year 3</th>
                                            <th>Year 4</th>
                                            <th>Year 5</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?= number_format($investment['year_one'], 2) ?></td>
                                            <td><?= number_format($investment['year_two'], 2) ?></td>
                                            <td><?= number_format($investment['year_three'], 2) ?></td>
                                            <td><?= number_format($investment['year_four'], 2) ?></td>
                                            <td><?= number_format($investment['year_five'], 2) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Audit Information -->
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Audit Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Created By:</div>
                                <div class="col-md-9"><?= $createdByName ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Created At:</div>
                                <div class="col-md-9"><?= date('d/m/Y H:i:s', strtotime($investment['created_at'])) ?></div>
                            </div>
                            <?php if (!empty($investment['updated_at']) && $investment['updated_at'] != $investment['created_at']) : ?>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Last Updated By:</div>
                                <div class="col-md-9"><?= $updatedByName ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Last Updated At:</div>
                                <div class="col-md-9"><?= date('d/m/Y H:i:s', strtotime($investment['updated_at'])) ?></div>
                            </div>
                            <?php endif; ?>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Status Changed By:</div>
                                <div class="col-md-9"><?= $statusByName ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Status Changed At:</div>
                                <div class="col-md-9"><?= date('d/m/Y H:i:s', strtotime($investment['investment_status_at'])) ?></div>
                            </div>
                            <?php if (!empty($investment['investment_status_remarks'])) : ?>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Status Change Remarks:</div>
                                <div class="col-md-9"><?= $investment['investment_status_remarks'] ?></div>
                            </div>
                            <?php endif; ?>
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
                <h5 class="modal-title" id="toggleStatusTitle">Toggle Investment Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="toggleStatusForm" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" id="toggle_id" name="id" value="<?= $investment['id'] ?>">

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
    // Toggle Status button click
    $('.toggle-status-btn').on('click', function() {
        var status = $(this).data('status');

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
