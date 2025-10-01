<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans') ?>">NASP Plans</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/apas') ?>"><?= esc($plan['title']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">DIPs in <?= esc($apa['title']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">DIPs in <?= esc($apa['title']) ?> (<?= esc($apa['code']) ?>)</h3>
                    <div>
                        <a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/apas') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back to APAs
                        </a>
                        <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/new') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New DIP
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <strong>NASP Plan:</strong> <?= esc($plan['title']) ?> (<?= esc($plan['code']) ?>)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dipsTable">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($dips)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No DIPs found for this APA</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($dips as $dip): ?>
                                        <tr>
                                            <td><?= esc($dip['code']) ?></td>
                                            <td><?= esc($dip['title']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $dip['nasp_status'] == 1 ? 'success' : 'danger' ?>">
                                                    <?= $dip['nasp_status'] == 1 ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id']) ?>" class="btn btn-outline-primary btn-sm" style="margin-right: 5px;">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </a>
                                                <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas') ?>" class="btn btn-outline-primary btn-sm" style="margin-right: 5px;">
                                                    <i class="fas fa-list me-1"></i> Manage Specific Areas
                                                </a>
                                                <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/edit') ?>" class="btn btn-outline-warning btn-sm" style="margin-right: 5px;">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/toggle-status') ?>" class="btn btn-outline-<?= $dip['nasp_status'] == 1 ? 'secondary' : 'success' ?> btn-sm">
                                                    <i class="fas fa-<?= $dip['nasp_status'] == 1 ? 'ban' : 'check-circle' ?> me-1"></i>
                                                    <?= $dip['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?>
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
