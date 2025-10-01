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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">NASP Plans</h3>
                    <div>
                        <a href="<?= base_url('admin/nasp-plans/new') ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Add New Plan
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
                                                <a href="<?= base_url('admin/nasp-plans/' . $plan['id']) ?>" class="btn btn-outline-primary btn-sm" style="margin-right: 5px;">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </a>
                                                <a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/apas') ?>" class="btn btn-outline-primary btn-sm" style="margin-right: 5px;">
                                                    <i class="fas fa-list me-1"></i> Manage APAs
                                                </a>
                                                <a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/edit') ?>" class="btn btn-outline-warning btn-sm" style="margin-right: 5px;">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/toggle-status') ?>" class="btn btn-outline-<?= $plan['nasp_status'] == 1 ? 'secondary' : 'success' ?> btn-sm" style="margin-right: 5px;">
                                                    <i class="fas fa-<?= $plan['nasp_status'] == 1 ? 'ban' : 'check-circle' ?> me-1"></i>
                                                    <?= $plan['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                                                </a>
                                                <form action="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/delete') ?>" method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this plan and all its children? This action cannot be undone.');">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-trash me-1"></i> Delete
                                                    </button>
                                                </form>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (session()->getFlashdata('success')): ?>
            toastr.success('<?= session()->getFlashdata('success') ?>');
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            toastr.error('<?= session()->getFlashdata('error') ?>');
        <?php endif; ?>
    });
</script>

<?= $this->endSection() ?>