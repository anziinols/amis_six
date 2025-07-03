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
                    <li class="breadcrumb-item active" aria-current="page">KRAs for <?= esc($investment['investment']) ?></li>
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
                        <a href="<?= base_url('admin/mtdp-plans/investments/' . $investment['id'] . '/kras/new') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add KRA
                        </a>
                        <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/investments') ?>" class="btn btn-secondary ms-1">
                            <i class="fas fa-arrow-left"></i> Back to Investments
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="krasTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Key Performance Indicator</th>
                                    <th>Year 1</th>
                                    <th>Year 2</th>
                                    <th>Year 3</th>
                                    <th>Year 4</th>
                                    <th>Year 5</th>
                                    <th>Responsible Agencies</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 1; ?>
                                <?php foreach ($kras as $kra) : ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><?= $kra['kpi'] ?></td>
                                        <td><?= $kra['year_one'] ?></td>
                                        <td><?= $kra['year_two'] ?></td>
                                        <td><?= $kra['year_three'] ?></td>
                                        <td><?= $kra['year_four'] ?></td>
                                        <td><?= $kra['year_five'] ?></td>
                                        <td><?= $kra['responsible_agencies'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $kra['kra_status'] == 1 ? 'success' : 'danger' ?>">
                                                <?= $kra['kra_status'] == 1 ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="KRA Actions">
                                                <a href="<?= base_url('admin/mtdp-plans/kras/' . $kra['id']) ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                <a href="<?= base_url('admin/mtdp-plans/kras/' . $kra['id'] . '/edit') ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-<?= $kra['kra_status'] == 1 ? 'danger' : 'success' ?> toggle-status-btn"
                                                        data-id="<?= $kra['id'] ?>" data-status="<?= $kra['kra_status'] ?>" data-bs-toggle="modal" data-bs-target="#toggleStatusModal">
                                                    <i class="fas fa-toggle-<?= $kra['kra_status'] == 1 ? 'off' : 'on' ?>"></i>
                                                    <?= $kra['kra_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                                                </button>
                                                <a href="<?= base_url('admin/mtdp-plans/kras/' . $kra['id'] . '/strategies') ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-list-check"></i> View Strategies
                                                </a>
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
                <h5 class="modal-title" id="toggleStatusTitle">Toggle KRA Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" id="toggleStatusForm">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" id="toggle_id" name="id" value="">

                    <div id="statusChangeMessage" class="alert alert-warning">
                        Are you sure you want to change the status of this KRA?
                    </div>

                    <div class="form-group mt-3">
                        <label for="kra_status_remarks">Status Change Remarks</label>
                        <textarea class="form-control" id="kra_status_remarks" name="kra_status_remarks" rows="3"></textarea>
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
    $('#krasTable').DataTable({
        responsive: true,
        order: [[0, 'asc']]
    });

    // Toggle Status button click
    $('.toggle-status-btn').on('click', function() {
        var id = $(this).data('id');
        var status = $(this).data('status');

        // Set form action
        $('#toggleStatusForm').attr('action', '<?= base_url("admin/mtdp-plans/kras") ?>/' + id + '/toggle-status');

        // Set values and update UI
        $('#toggle_id').val(id);

        if (status == 1) {
            $('#toggleStatusTitle').text('Deactivate KRA');
            $('#statusChangeMessage').html('Are you sure you want to <strong>deactivate</strong> this KRA?');
            $('#confirmToggleBtn').text('Deactivate').removeClass('btn-success').addClass('btn-danger');
        } else {
            $('#toggleStatusTitle').text('Activate KRA');
            $('#statusChangeMessage').html('Are you sure you want to <strong>activate</strong> this KRA?');
            $('#confirmToggleBtn').text('Activate').removeClass('btn-danger').addClass('btn-success');
        }
    });
});
</script>

<?= $this->endSection(); ?>
