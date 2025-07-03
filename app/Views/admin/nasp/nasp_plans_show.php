<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans') ?>">NASP Plans</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View NASP Plan</li>
                </ol>
            </nav>
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
                    <h3 class="card-title">NASP Plan Details</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/nasp-plans') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to NASP Plans
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Code</th>
                                    <td><?= esc($plan['code']) ?></td>
                                </tr>
                                <tr>
                                    <th>Title</th>
                                    <td><?= esc($plan['title']) ?></td>
                                </tr>
                                <tr>
                                    <th>Period</th>
                                    <td>
                                        <?php if (!empty($plan['date_from']) && !empty($plan['date_to'])): ?>
                                            <?= date('M Y', strtotime($plan['date_from'])) ?> -
                                            <?= date('M Y', strtotime($plan['date_to'])) ?>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Remarks</th>
                                    <td><?= nl2br(esc($plan['remarks'] ?? 'N/A')) ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge bg-<?= $plan['nasp_status'] == 1 ? 'success' : 'danger' ?>">
                                            <?= $plan['nasp_status'] == 1 ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td><?= date('d M Y H:i', strtotime($plan['created_at'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Last Updated</th>
                                    <td><?= date('d M Y H:i', strtotime($plan['updated_at'])) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/apas') ?>" class="btn btn-primary">
                            <i class="fas fa-list"></i> Manage APAs
                        </a>
                        <a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/edit') ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/toggle-status') ?>" class="btn btn-<?= $plan['nasp_status'] == 1 ? 'danger' : 'success' ?>">
                            <i class="fas fa-<?= $plan['nasp_status'] == 1 ? 'ban' : 'check-circle' ?>"></i>
                            <?= $plan['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
