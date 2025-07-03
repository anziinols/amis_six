<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs') ?>" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-arrow-left"></i> Back to Outputs
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Output Details: <?= esc($output['code']) ?></h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id'] . '/edit') ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id'] . '/toggle-status') ?>" class="btn btn-<?= $output['nasp_status'] == 1 ? 'danger' : 'success' ?>">
                            <i class="fas fa-<?= $output['nasp_status'] == 1 ? 'ban' : 'check-circle' ?>"></i>
                            <?= $output['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <h5>Hierarchy</h5>
                        <p><strong>NASP Plan:</strong> <?= esc($plan['title']) ?> (<?= esc($plan['code']) ?>)</p>
                        <p><strong>APA:</strong> <?= esc($apa['title']) ?> (<?= esc($apa['code']) ?>)</p>
                        <p><strong>DIP:</strong> <?= esc($dip['title']) ?> (<?= esc($dip['code']) ?>)</p>
                        <p><strong>Specific Area:</strong> <?= esc($specificArea['title']) ?> (<?= esc($specificArea['code']) ?>)</p>
                        <p><strong>Objective:</strong> <?= esc($objective['title']) ?> (<?= esc($objective['code']) ?>)</p>
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
                                            <td><?= esc($output['code']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Title</th>
                                            <td><?= esc($output['title']) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                <span class="badge bg-<?= $output['nasp_status'] == 1 ? 'success' : 'danger' ?>">
                                                    <?= $output['nasp_status'] == 1 ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Remarks</th>
                                            <td><?= nl2br(esc($output['remarks'] ?? 'N/A')) ?></td>
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
                                            <td><?= $output['created_at'] ? date('Y-m-d H:i:s', strtotime($output['created_at'])) : 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Updated At</th>
                                            <td><?= $output['updated_at'] ? date('Y-m-d H:i:s', strtotime($output['updated_at'])) : 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status Changed At</th>
                                            <td><?= $output['nasp_status_at'] ? date('Y-m-d H:i:s', strtotime($output['nasp_status_at'])) : 'N/A' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status Remarks</th>
                                            <td><?= nl2br(esc($output['nasp_status_remarks'] ?? 'N/A')) ?></td>
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
