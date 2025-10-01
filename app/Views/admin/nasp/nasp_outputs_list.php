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
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips') ?>"><?= esc($apa['title']) ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas') ?>"><?= esc($dip['title']) ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives') ?>"><?= esc($specificArea['title']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Outputs in <?= esc($objective['title']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Outputs for <?= esc($objective['title']) ?> (<?= esc($objective['code']) ?>)</h3>
                    <div>
                        <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives') ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back to Objectives
                        </a>
                        <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/new') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Output
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <strong>NASP Plan:</strong> <?= esc($plan['title']) ?> (<?= esc($plan['code']) ?>)<br>
                        <strong>APA:</strong> <?= esc($apa['title']) ?> (<?= esc($apa['code']) ?>)<br>
                        <strong>DIP:</strong> <?= esc($dip['title']) ?> (<?= esc($dip['code']) ?>)<br>
                        <strong>Specific Area:</strong> <?= esc($specificArea['title']) ?> (<?= esc($specificArea['code']) ?>)<br>
                        <strong>Objective:</strong> <?= esc($objective['title']) ?> (<?= esc($objective['code']) ?>)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($outputs)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No Outputs found for this Objective</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($outputs as $index => $output): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= esc($output['code']) ?></td>
                                            <td><?= esc($output['title']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $output['nasp_status'] == 1 ? 'success' : 'danger' ?>">
                                                    <?= $output['nasp_status'] == 1 ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id']) ?>" class="btn btn-outline-primary btn-sm" style="margin-right: 5px;">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </a>
                                                <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id'] . '/indicators') ?>" class="btn btn-outline-primary btn-sm" style="margin-right: 5px;">
                                                    <i class="fas fa-list me-1"></i> Manage Indicators
                                                </a>
                                                <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id'] . '/edit') ?>" class="btn btn-outline-warning btn-sm" style="margin-right: 5px;">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id'] . '/toggle-status') ?>" class="btn btn-outline-<?= $output['nasp_status'] == 1 ? 'secondary' : 'success' ?> btn-sm">
                                                    <i class="fas fa-<?= $output['nasp_status'] == 1 ? 'ban' : 'check-circle' ?> me-1"></i>
                                                    <?= $output['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?>
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

<?= $this->section('scripts') ?>
<script>
// No DataTables initialization to avoid errors
</script>
<?= $this->endSection() ?>
