<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips') ?>" class="btn btn-sm btn-outline-secondary me-2">
                <i class="fas fa-arrow-left"></i> Back to DIPs
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
                    <h3 class="card-title">Specific Areas for <?= esc($dip['title']) ?> (<?= esc($dip['code']) ?>)</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/new') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Specific Area
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <strong>NASP Plan:</strong> <?= esc($plan['title']) ?> (<?= esc($plan['code']) ?>)<br>
                        <strong>APA:</strong> <?= esc($apa['title']) ?> (<?= esc($apa['code']) ?>)<br>
                        <strong>DIP:</strong> <?= esc($dip['title']) ?> (<?= esc($dip['code']) ?>)
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
                                <?php if (empty($specificAreas)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No Specific Areas found for this DIP</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($specificAreas as $index => $sa): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= esc($sa['code']) ?></td>
                                            <td><?= esc($sa['title']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $sa['nasp_status'] == 1 ? 'success' : 'danger' ?>">
                                                    <?= $sa['nasp_status'] == 1 ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Specific Area actions">
                                                    <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $sa['id']) ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $sa['id'] . '/objectives') ?>" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-list"></i> Manage Objectives
                                                    </a>
                                                    <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $sa['id'] . '/edit') ?>" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas/' . $sa['id'] . '/toggle-status') ?>" class="btn btn-<?= $sa['nasp_status'] == 1 ? 'danger' : 'success' ?> btn-sm">
                                                        <i class="fas fa-<?= $sa['nasp_status'] == 1 ? 'ban' : 'check-circle' ?>"></i>
                                                        <?= $sa['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?>
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
