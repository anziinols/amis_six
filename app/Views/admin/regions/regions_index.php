<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <a href="<?= base_url('admin/regions/new') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Region
            </a>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="regionsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Provinces</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($regions as $region): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td><?= esc($region['name']) ?></td>
                                <td>
                                    <span class="badge bg-info"><?= $region['province_count'] ?? 0 ?> provinces</span>
                                </td>
                                <td><?= esc($region['remarks']) ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('admin/regions/' . $region['id']) ?>" class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('admin/regions/' . $region['id'] . '/edit') ?>" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('admin/regions/' . $region['id'] . '/import-provinces') ?>" class="btn btn-sm btn-success" title="Import Provinces">
                                            <i class="fas fa-file-import"></i>
                                        </a>
                                        <a href="<?= base_url('admin/regions/' . $region['id'] . '/delete') ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this region?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($regions)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No regions found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // DataTables initialization removed to prevent errors
    // The table will function as a regular HTML table
</script>
<?= $this->endSection() ?>
