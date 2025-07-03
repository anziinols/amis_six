<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/mtdp-plans/indicators/' . $indicator['id'] . '/edit') ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button" class="btn btn-<?= $indicator['indicators_status'] == 1 ? 'danger' : 'success' ?> toggle-status-btn ms-1"
                                data-id="<?= $indicator['id'] ?>" data-status="<?= $indicator['indicators_status'] ?>" data-bs-toggle="modal" data-bs-target="#toggleStatusModal">
                            <i class="fas fa-toggle-<?= $indicator['indicators_status'] == 1 ? 'off' : 'on' ?>"></i>
                            <?= $indicator['indicators_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                        </button>
                        <a href="<?= base_url('admin/mtdp-plans/strategies/' . $strategy['id'] . '/indicators') ?>" class="btn btn-secondary ms-1">
                            <i class="fas fa-arrow-left"></i> Back to Indicators
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Indicator Information -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Indicator Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Indicator:</div>
                                <div class="col-md-9"><?= nl2br(esc($indicator['indicator'])) ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Source:</div>
                                <div class="col-md-9"><?= esc($indicator['source']) ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Baseline:</div>
                                <div class="col-md-9"><?= esc($indicator['baseline']) ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Status:</div>
                                <div class="col-md-9">
                                    <span class="badge bg-<?= $indicator['indicators_status'] == 1 ? 'success' : 'danger' ?>">
                                        <?= $indicator['indicators_status'] == 1 ? 'Active' : 'Inactive' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Yearly Targets -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Yearly Targets</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Year One</th>
                                            <th>Year Two</th>
                                            <th>Year Three</th>
                                            <th>Year Four</th>
                                            <th>Year Five</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?= esc($indicator['year_one']) ?></td>
                                            <td><?= esc($indicator['year_two']) ?></td>
                                            <td><?= esc($indicator['year_three']) ?></td>
                                            <td><?= esc($indicator['year_four']) ?></td>
                                            <td><?= esc($indicator['year_five']) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

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
                                    <a href="<?= base_url('admin/mtdp-plans/spas/' . $spa['id']) ?>"><?= $spa['title'] ?? $spa['code'] ?></a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Deliberate Intervention Program:</div>
                                <div class="col-md-9">
                                    <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id']) ?>"><?= $dip['dip_title'] ?></a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Specific Area:</div>
                                <div class="col-md-9">
                                    <a href="<?= base_url('admin/mtdp-plans/specific-areas/' . $specificArea['id']) ?>"><?= $specificArea['sa_title'] ?></a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Investment:</div>
                                <div class="col-md-9">
                                    <a href="<?= base_url('admin/mtdp-plans/investments/' . $investment['id']) ?>"><?= $investment['investment'] ?></a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">KRA:</div>
                                <div class="col-md-9">
                                    <a href="<?= base_url('admin/mtdp-plans/kras/' . $kra['id']) ?>"><?= $kra['kpi'] ?></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Audit Information -->
                    <div class="card mb-3">
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
                                <div class="col-md-9"><?= date('d/m/Y H:i:s', strtotime($indicator['created_at'])) ?></div>
                            </div>
                            <?php if (!empty($indicator['updated_by'])): ?>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Updated By:</div>
                                <div class="col-md-9"><?= $updatedByName ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Updated At:</div>
                                <div class="col-md-9"><?= date('d/m/Y H:i:s', strtotime($indicator['updated_at'])) ?></div>
                            </div>
                            <?php endif; ?>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Status Changed By:</div>
                                <div class="col-md-9"><?= $statusByName ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Status Changed At:</div>
                                <div class="col-md-9"><?= date('d/m/Y H:i:s', strtotime($indicator['indicators_status_at'])) ?></div>
                            </div>
                            <?php if (!empty($indicator['indicators_status_remarks'])): ?>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Status Remarks:</div>
                                <div class="col-md-9"><?= nl2br(esc($indicator['indicators_status_remarks'])) ?></div>
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
