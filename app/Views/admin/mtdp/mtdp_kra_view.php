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
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/mtdp-plans/investments/' . $investment['id'] . '/kras') ?>">KRAs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">KRA Details</li>
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
                        <a href="<?= base_url('admin/mtdp-plans/kras/' . $kra['id'] . '/edit') ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button" class="btn btn-<?= $kra['kra_status'] == 1 ? 'danger' : 'success' ?> toggle-status-btn"
                                data-id="<?= $kra['id'] ?>" data-status="<?= $kra['kra_status'] ?>" data-bs-toggle="modal" data-bs-target="#toggleStatusModal">
                            <i class="fas fa-toggle-<?= $kra['kra_status'] == 1 ? 'off' : 'on' ?>"></i>
                            <?= $kra['kra_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                        </button>
                        <a href="<?= base_url('admin/mtdp-plans/kras/' . $kra['id'] . '/strategies') ?>" class="btn btn-primary ms-1">
                            <i class="fas fa-list-check"></i> View Strategies
                        </a>
                        <a href="<?= base_url('admin/mtdp-plans/investments/' . $investment['id'] . '/kras') ?>" class="btn btn-secondary ms-1">
                            <i class="fas fa-arrow-left"></i> Back to KRAs
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Key Performance Indicator (KPI)</h5>
                            <p><?= nl2br(esc($kra['kpi'])) ?></p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Responsible Agencies</h5>
                            <p><?= nl2br(esc($kra['responsible_agencies'] ?? 'Not specified')) ?></p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Annual Targets</h5>
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
                                            <td><?= $kra['year_one'] ?></td>
                                            <td><?= $kra['year_two'] ?></td>
                                            <td><?= $kra['year_three'] ?></td>
                                            <td><?= $kra['year_four'] ?></td>
                                            <td><?= $kra['year_five'] ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Status Information</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th style="width: 200px;">Status</th>
                                            <td>
                                                <span class="badge bg-<?= $kra['kra_status'] == 1 ? 'success' : 'danger' ?>">
                                                    <?= $kra['kra_status'] == 1 ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status Changed By</th>
                                            <td><?= $statusByName ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status Changed At</th>
                                            <td><?= $kra['kra_status_at'] ? date('F j, Y, g:i a', strtotime($kra['kra_status_at'])) : 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status Remarks</th>
                                            <td><?= nl2br(esc($kra['kra_status_remarks'] ?? 'No remarks')) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Audit Information</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th style="width: 200px;">Created By</th>
                                            <td><?= $createdByName ?></td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td><?= $kra['created_at'] ? date('F j, Y, g:i a', strtotime($kra['created_at'])) : 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated By</th>
                                            <td><?= $updatedByName ?: 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated At</th>
                                            <td><?= $kra['updated_at'] ? date('F j, Y, g:i a', strtotime($kra['updated_at'])) : 'N/A' ?></td>
                                        </tr>
                                    </tbody>
                                </table>
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
                <h5 class="modal-title" id="toggleStatusTitle">Toggle KRA Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/mtdp-plans/kras/' . $kra['id'] . '/toggle-status') ?>" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $kra['id'] ?>">

                    <div id="statusChangeMessage" class="alert alert-warning">
                        Are you sure you want to <?= $kra['kra_status'] == 1 ? 'deactivate' : 'activate' ?> this KRA?
                    </div>

                    <div class="form-group mt-3">
                        <label for="kra_status_remarks">Status Change Remarks</label>
                        <textarea class="form-control" id="kra_status_remarks" name="kra_status_remarks" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="confirmToggleBtn" class="btn btn-<?= $kra['kra_status'] == 1 ? 'danger' : 'success' ?>">
                        <?= $kra['kra_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
