<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans') ?>">NASP Plans</a></li>
                    <li class="breadcrumb-item active" aria-current="page">APAs in <?= esc($plan['title']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">APAs in <?= esc($plan['title']) ?> (<?= esc($plan['code']) ?>)</h3>
                    <div>
                        <a href="<?= base_url('admin/nasp-plans') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back to NASP Plans
                        </a>
                        <a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/apas/new') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New APA
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="apasTable">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($apas)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No APAs found for this plan</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($apas as $apa): ?>
                                        <tr>
                                            <td><?= esc($apa['code']) ?></td>
                                            <td><?= esc($apa['title']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $apa['nasp_status'] == 1 ? 'success' : 'danger' ?>">
                                                    <?= $apa['nasp_status'] == 1 ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id']) ?>" class="btn btn-outline-primary btn-sm" style="margin-right: 5px;">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </a>
                                                <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips') ?>" class="btn btn-outline-primary btn-sm" style="margin-right: 5px;">
                                                    <i class="fas fa-list me-1"></i> Manage DIPs
                                                </a>
                                                <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/edit') ?>" class="btn btn-outline-warning btn-sm" style="margin-right: 5px;">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/toggle-status') ?>" class="btn btn-outline-<?= $apa['nasp_status'] == 1 ? 'secondary' : 'success' ?> btn-sm">
                                                    <i class="fas fa-<?= $apa['nasp_status'] == 1 ? 'ban' : 'check-circle' ?> me-1"></i>
                                                    <?= $apa['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
