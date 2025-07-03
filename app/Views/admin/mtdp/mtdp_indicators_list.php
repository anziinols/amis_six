<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/mtdp-plans/strategies/' . $strategy['id'] . '/indicators/new') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Indicator
                        </a>
                        <a href="<?= base_url('admin/mtdp-plans/kras/' . $kra['id'] . '/strategies') ?>" class="btn btn-secondary ms-1">
                            <i class="fas fa-arrow-left"></i> Back to Strategies
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Strategy Information -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Strategy Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Strategy:</div>
                                <div class="col-md-9"><?= nl2br(esc($strategy['strategy'])) ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Policy Reference:</div>
                                <div class="col-md-9"><?= esc($strategy['policy_reference']) ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="indicatorsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Indicator</th>
                                    <th>Source</th>
                                    <th>Baseline</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 1; ?>
                                <?php foreach ($indicators as $indicator) : ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><?= $indicator['indicator'] ?></td>
                                        <td><?= $indicator['source'] ?></td>
                                        <td><?= $indicator['baseline'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $indicator['indicators_status'] == 1 ? 'success' : 'danger' ?>">
                                                <?= $indicator['indicators_status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="Indicator Actions">
                                                <a href="<?= base_url('admin/mtdp-plans/indicators/' . $indicator['id']) ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a href="<?= base_url('admin/mtdp-plans/indicators/' . $indicator['id'] . '/edit') ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-<?= $indicator['indicators_status'] == 1 ? 'danger' : 'success' ?> toggle-status-btn"
                                                        data-id="<?= $indicator['id'] ?>" data-status="<?= $indicator['indicators_status'] ?>" data-bs-toggle="modal" data-bs-target="#toggleStatusModal">
                                                    <i class="fas fa-toggle-<?= $indicator['indicators_status'] == 1 ? 'off' : 'on' ?>"></i>
                                                    <?= $indicator['indicators_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($indicators)) : ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No indicators found for this strategy.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Status Modal -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" role="dialog" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="toggleStatusModalLabel">Confirm Status Change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="toggleStatusForm" action="" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <p>Are you sure you want to change the status of this indicator?</p>
                    <div class="form-group mt-3">
                        <label for="indicators_status_remarks">Status Change Remarks</label>
                        <textarea class="form-control" id="indicators_status_remarks" name="indicators_status_remarks" rows="3"></textarea>
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
    $('#indicatorsTable').DataTable({
        responsive: true,
        order: [[0, 'asc']]
    });

    // Handle toggle status button click
    $('.toggle-status-btn').on('click', function() {
        var id = $(this).data('id');
        var status = $(this).data('status');
        var statusText = status == 1 ? 'deactivate' : 'activate';

        // Update modal content
        $('#toggleStatusModalLabel').text('Confirm ' + (status == 1 ? 'Deactivation' : 'Activation'));
        $('.modal-body p').text('Are you sure you want to ' + statusText + ' this indicator?');

        // Set form action
        $('#toggleStatusForm').attr('action', '<?= base_url('admin/mtdp-plans/indicators') ?>/' + id + '/toggle-status');
    });
});
</script>

<?= $this->endSection() ?>
