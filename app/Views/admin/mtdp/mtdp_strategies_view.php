<?php
$this->extend('templates/system_template');
$this->section('content');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/mtdp-plans/strategies/' . $strategy['id'] . '/indicators') ?>" class="btn btn-primary">
                            <i class="fas fa-chart-line"></i> View Indicators
                        </a>
                        <a href="<?= base_url('admin/mtdp-plans/strategies/' . $strategy['id'] . '/edit') ?>" class="btn btn-warning ms-1">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button" class="btn btn-<?= $strategy['strategies_status'] == 1 ? 'danger' : 'success' ?> toggle-status-btn ms-1"
                                data-id="<?= $strategy['id'] ?>" data-status="<?= $strategy['strategies_status'] ?>" data-bs-toggle="modal" data-bs-target="#toggleStatusModal">
                            <i class="fas fa-toggle-<?= $strategy['strategies_status'] == 1 ? 'off' : 'on' ?>"></i>
                            <?= $strategy['strategies_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                        </button>
                        <a href="<?= base_url('admin/mtdp-plans/kras/' . $strategy['kra_id'] . '/strategies') ?>" class="btn btn-secondary ms-1">
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
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Status:</div>
                                <div class="col-md-9">
                                    <span class="badge bg-<?= $strategy['strategies_status'] == 1 ? 'success' : 'danger' ?>">
                                        <?= $strategy['strategies_status'] == 1 ? 'Active' : 'Inactive' ?>
                                    </span>
                                </div>
                            </div>
                            <?php if (!empty($strategy['strategies_status_remarks'])) : ?>
                                <div class="row mb-2">
                                    <div class="col-md-3 fw-bold">Status Remarks:</div>
                                    <div class="col-md-9"><?= nl2br(esc($strategy['strategies_status_remarks'])) ?></div>
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
                                    <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id']) ?>"><?= $dip['dip_title'] ?></a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Specific Area:</div>
                                <div class="col-md-9">
                                    <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id']) ?>"><?= $specificArea['sa_title'] ?></a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Investment:</div>
                                <div class="col-md-9">
                                    <a href="<?= base_url('admin/mtdp-plans/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/investments/' . $investment['id']) ?>"><?= $investment['investment'] ?></a>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Key Result Area:</div>
                                <div class="col-md-9">
                                    <a href="<?= base_url('admin/mtdp-plans/kras/' . $kra['id']) ?>"><?= $kra['kpi'] ?></a>
                                </div>
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
                                <div class="col-md-9"><?= date('d/m/Y H:i:s', strtotime($strategy['created_at'])) ?></div>
                            </div>
                            <?php if (!empty($strategy['updated_at']) && $strategy['updated_at'] != $strategy['created_at']) : ?>
                                <div class="row mb-2">
                                    <div class="col-md-3 fw-bold">Updated By:</div>
                                    <div class="col-md-9"><?= $updatedByName ?></div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-3 fw-bold">Updated At:</div>
                                    <div class="col-md-9"><?= date('d/m/Y H:i:s', strtotime($strategy['updated_at'])) ?></div>
                                </div>
                            <?php endif; ?>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Status Changed By:</div>
                                <div class="col-md-9"><?= $statusByName ?></div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-3 fw-bold">Status Changed At:</div>
                                <div class="col-md-9"><?= date('d/m/Y H:i:s', strtotime($strategy['strategies_status_at'])) ?></div>
                            </div>
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
                <h5 class="modal-title" id="toggleStatusTitle">Toggle Strategy Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/mtdp-plans/strategies/' . $strategy['id'] . '/toggle-status') ?>" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $strategy['id'] ?>">

                    <div id="statusChangeMessage" class="alert alert-warning">
                        Are you sure you want to <?= $strategy['strategies_status'] == 1 ? 'deactivate' : 'activate' ?> this strategy?
                    </div>

                    <div class="form-group mt-3">
                        <label for="strategies_status_remarks">Status Change Remarks</label>
                        <textarea class="form-control" id="strategies_status_remarks" name="strategies_status_remarks" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="confirmToggleBtn" class="btn btn-<?= $strategy['strategies_status'] == 1 ? 'danger' : 'success' ?>">
                        <?= $strategy['strategies_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
