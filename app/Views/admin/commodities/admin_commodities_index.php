<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <a href="<?= base_url('admin/commodities/new') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Commodity
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
                <table class="table table-bordered table-striped" id="commoditiesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Color</th>
                            <th>Icon</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($commodities as $commodity): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?= esc($commodity['commodity_code']) ?></span>
                                </td>
                                <td><?= esc($commodity['commodity_name']) ?></td>
                                <td>
                                    <?php if (!empty($commodity['commodity_color_code'])): ?>
                                        <div class="d-flex align-items-center">
                                            <div class="color-preview me-2" style="width: 20px; height: 20px; background-color: <?= esc($commodity['commodity_color_code']) ?>; border: 1px solid #ccc; border-radius: 3px;"></div>
                                            <span><?= esc($commodity['commodity_color_code']) ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">No color</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($commodity['commodity_icon'])): ?>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= base_url($commodity['commodity_icon']) ?>" alt="<?= esc($commodity['commodity_name']) ?>" class="commodity-icon-small me-2" style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                                            <small class="text-muted"><?= basename($commodity['commodity_icon']) ?></small>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">No icon</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('M d, Y', strtotime($commodity['created_at'])) ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('admin/commodities/' . $commodity['id']) ?>" class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('admin/commodities/' . $commodity['id'] . '/edit') ?>" class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('admin/commodities/' . $commodity['id'] . '/delete') ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this commodity?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($commodities)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No commodities found</td>
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
