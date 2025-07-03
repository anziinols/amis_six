<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas') ?>" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-arrow-left"></i> Back to Specific Areas
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Specific Area Details: <?= esc($specificArea['code']) ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/edit') ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/toggle-status') ?>" class="btn btn-<?= $specificArea['nasp_status'] == 1 ? 'danger' : 'success' ?>">
                            <i class="fas fa-<?= $specificArea['nasp_status'] == 1 ? 'ban' : 'check-circle' ?>"></i>
                            <?= $specificArea['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <h5>Hierarchy</h5>
                        <p><strong>NASP Plan:</strong> <?= esc($plan['title']) ?> (<?= esc($plan['code']) ?>)</p>
                        <p><strong>APA:</strong> <?= esc($apa['title']) ?> (<?= esc($apa['code']) ?>)</p>
                        <p><strong>DIP:</strong> <?= esc($dip['title']) ?> (<?= esc($dip['code']) ?>)</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 30%">Code</th>
                                            <td><?= esc($specificArea['code']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Title</th>
                                            <td><?= esc($specificArea['title']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span class="badge bg-<?= $specificArea['nasp_status'] == 1 ? 'success' : 'danger' ?>">
                                                    <?= $specificArea['nasp_status'] == 1 ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Remarks</th>
                                            <td><?= nl2br(esc($specificArea['remarks'] ?? 'N/A')) ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">System Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 30%">Created At</th>
                                            <td><?= $specificArea['created_at'] ? date('Y-m-d H:i:s', strtotime($specificArea['created_at'])) : 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Updated At</th>
                                            <td><?= $specificArea['updated_at'] ? date('Y-m-d H:i:s', strtotime($specificArea['updated_at'])) : 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status Changed At</th>
                                            <td><?= $specificArea['nasp_status_at'] ? date('Y-m-d H:i:s', strtotime($specificArea['nasp_status_at'])) : 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status Remarks</th>
                                            <td><?= nl2br(esc($specificArea['nasp_status_remarks'] ?? 'N/A')) ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
