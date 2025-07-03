<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives') ?>" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-arrow-left"></i> Back to Objectives
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
                    <h3 class="card-title">Outputs for <?= esc($objective['title']) ?> (<?= esc($objective['code']) ?>)</h3>
                    <div class="card-tools">
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
                                                <div class="btn-group" role="group" aria-label="Output actions">
                                                    <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id']) ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id'] . '/indicators') ?>" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-list"></i> Manage Indicators
                                                    </a>
                                                    <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id'] . '/edit') ?>" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $specificArea['id'] . '/objectives/' . $objective['id'] . '/outputs/' . $output['id'] . '/toggle-status') ?>" class="btn btn-<?= $output['nasp_status'] == 1 ? 'danger' : 'success' ?> btn-sm">
                                                        <i class="fas fa-<?= $output['nasp_status'] == 1 ? 'ban' : 'check-circle' ?>"></i>
                                                        <?= $output['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?>
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

<?= $this->section('scripts') ?>
<script>
// No DataTables initialization to avoid errors
</script>
<?= $this->endSection() ?>
