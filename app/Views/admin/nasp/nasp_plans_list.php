<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">NASP Plans</li>
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
                    <h3 class="card-title">NASP Plans</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/nasp-plans/new') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Plan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="plansTable">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Title</th>
                                    <th>Period</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($plans)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No NASP plans found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($plans as $plan): ?>
                                        <tr>
                                            <td><?= esc($plan['code']) ?></td>
                                            <td><?= esc($plan['title']) ?></td>
                                            <td>
                                                <?php if (!empty($plan['date_from']) && !empty($plan['date_to'])): ?>
                                                    <?= date('M Y', strtotime($plan['date_from'])) ?> -
                                                    <?= date('M Y', strtotime($plan['date_to'])) ?>
                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $plan['nasp_status'] == 1 ? 'success' : 'danger' ?>">
                                                    <?= $plan['nasp_status'] == 1 ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Plan actions">
                                                    <a href="<?= base_url('admin/nasp-plans/' . $plan['id']) ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/apas') ?>" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-list"></i> Manage APAs
                                                    </a>
                                                    <a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/edit') ?>" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/toggle-status') ?>" class="btn btn-<?= $plan['nasp_status'] == 1 ? 'danger' : 'success' ?> btn-sm">
                                                        <i class="fas fa-<?= $plan['nasp_status'] == 1 ? 'ban' : 'check-circle' ?>"></i>
                                                        <?= $plan['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                                                    </a>
                                                    <form action="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/delete') ?>" method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this plan and all its children? This action cannot be undone.');">
                                                        <?= csrf_field() ?>
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
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