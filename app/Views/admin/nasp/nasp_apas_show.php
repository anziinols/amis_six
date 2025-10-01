<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans') ?>">NASP Plans</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/apas') ?>">APAs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View APA</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">APA Details</h3>
                    <div>
                        <a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/apas') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back to APAs
                        </a>
                        <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips') ?>" class="btn btn-primary me-2">
                            <i class="fas fa-list"></i> Manage DIPs
                        </a>
                        <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/edit') ?>" class="btn btn-warning me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/toggle-status') ?>" class="btn btn-<?= $apa['nasp_status'] == 1 ? 'secondary' : 'success' ?>">
                            <i class="fas fa-<?= $apa['nasp_status'] == 1 ? 'ban' : 'check-circle' ?>"></i>
                            <?= $apa['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Code</th>
                                    <td><?= esc($apa['code']) ?></td>
                                </tr>
                                <tr>
                                    <th>Title</th>
                                    <td><?= esc($apa['title']) ?></td>
                                </tr>
                                <tr>
                                    <th>Remarks</th>
                                    <td><?= nl2br(esc($apa['remarks'] ?? 'N/A')) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-<?= $apa['nasp_status'] == 1 ? 'success' : 'danger' ?>">
                                            <?= $apa['nasp_status'] == 1 ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td><?= date('d M Y H:i', strtotime($apa['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Last Updated</th>
                                    <td><?= date('d M Y H:i', strtotime($apa['updated_at'])) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
