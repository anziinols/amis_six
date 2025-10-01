<?php
$this->extend('templates/system_template');
$this->section('content');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0"><?= esc($title) ?></h3>
                    <div>
                        <a href="<?= base_url('admin/mtdp-plans/investments/' . $investment['id'] . '/kras') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back to KRAs
                        </a>
                        <a href="<?= base_url('admin/mtdp-plans/kras/' . $kra['id'] . '/strategies/new') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Strategy
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="strategiesTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Strategy</th>
                                    <th>Policy Reference</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 1; ?>
                                <?php foreach ($strategies as $strategy) : ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><?= $strategy['strategy'] ?></td>
                                        <td><?= $strategy['policy_reference'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $strategy['strategies_status'] == 1 ? 'success' : 'danger' ?>">
                                                <?= $strategy['strategies_status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('admin/mtdp-plans/strategies/' . $strategy['id']) ?>" class="btn btn-outline-primary btn-sm" style="margin-right: 5px;">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                            <a href="<?= base_url('admin/mtdp-plans/strategies/' . $strategy['id'] . '/indicators') ?>" class="btn btn-outline-primary btn-sm" style="margin-right: 5px;">
                                                <i class="fas fa-chart-line me-1"></i> View Indicators
                                            </a>
                                            <a href="<?= base_url('admin/mtdp-plans/strategies/' . $strategy['id'] . '/edit') ?>" class="btn btn-outline-warning btn-sm" style="margin-right: 5px;">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-outline-<?= $strategy['strategies_status'] == 1 ? 'secondary' : 'success' ?> btn-sm toggle-status-btn"
                                                    data-id="<?= $strategy['id'] ?>" data-status="<?= $strategy['strategies_status'] ?>" data-bs-toggle="modal" data-bs-target="#toggleStatusModal">
                                                <i class="fas fa-toggle-<?= $strategy['strategies_status'] == 1 ? 'off' : 'on' ?> me-1"></i>
                                                <?= $strategy['strategies_status'] == 1 ? 'Deactivate' : 'Activate' ?>
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
                <h5 class="modal-title" id="toggleStatusTitle">Toggle Strategy Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="toggleStatusForm" action="" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" id="toggle_id" name="id" value="">

                    <div id="statusChangeMessage" class="alert alert-warning">
                        Are you sure you want to change the status of this strategy?
                    </div>

                    <div class="form-group mt-3">
                        <label for="strategies_status_remarks">Status Change Remarks</label>
                        <textarea class="form-control" id="strategies_status_remarks" name="strategies_status_remarks" rows="3"></textarea>
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
    $('#strategiesTable').DataTable({
        responsive: true,
        order: [[0, 'asc']]
    });

    // Toggle Status button click
    $('.toggle-status-btn').on('click', function() {
        var id = $(this).data('id');
        var status = $(this).data('status');

        // Set form action
        $('#toggleStatusForm').attr('action', '<?= base_url("admin/mtdp-plans/strategies") ?>/' + id + '/toggle-status');

        // Set values and update UI
        $('#toggle_id').val(id);

        // Update message based on current status
        var statusText = status == 1 ? 'deactivate' : 'activate';
        $('#statusChangeMessage').html('Are you sure you want to ' + statusText + ' this strategy?');

        // Update button text and class
        $('#confirmToggleBtn').text(status == 1 ? 'Deactivate' : 'Activate');
        $('#confirmToggleBtn').removeClass('btn-success btn-danger').addClass(status == 1 ? 'btn-danger' : 'btn-success');
    });
});
</script>

<?= $this->endSection(); ?>
