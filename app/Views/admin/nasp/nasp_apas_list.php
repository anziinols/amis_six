<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <a href="<?= base_url('admin/nasp-plans') ?>" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-arrow-left"></i> Back to NASP Plans
            </a>
        </div>
    </div>

    <!-- Display flash messages -->
    <?php if (session()->has('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">APAs in <?= esc($plan['title']) ?> (<?= esc($plan['code']) ?>)</h3>
                    <div class="card-tools">
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
                                                <div class="btn-group" role="group" aria-label="APA actions">
                                                    <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id']) ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips') ?>" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-list"></i> Manage DIPs
                                                    </a>
                                                    <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/edit') ?>" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/toggle-status') ?>" class="btn btn-<?= $apa['nasp_status'] == 1 ? 'danger' : 'success' ?> btn-sm">
                                                        <i class="fas fa-<?= $apa['nasp_status'] == 1 ? 'ban' : 'check-circle' ?>"></i>
                                                        <?= $apa['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                                                    </a>
                                                </div>
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
