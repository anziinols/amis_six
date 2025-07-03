<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <div>
                <a href="<?= base_url('admin/regions/' . $region['id'] . '/import-provinces') ?>" class="btn btn-success">
                    <i class="fas fa-file-import"></i> Import Provinces
                </a>
                <a href="<?= base_url('admin/regions/' . $region['id'] . '/edit') ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="<?= base_url('admin/regions') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Regions
                </a>
            </div>
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

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Region Details</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">Name</th>
                            <td><?= esc($region['name']) ?></td>
                        </tr>
                        <tr>
                            <th>Remarks</th>
                            <td><?= esc($region['remarks']) ?></td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td><?= date('d-m-Y H:i', strtotime($region['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td><?= date('d-m-Y H:i', strtotime($region['updated_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <h6 class="text-muted">Provinces in this Region</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="provincesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter = 1; ?>
                        <?php foreach ($provinces as $province): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td><?= esc($province['code']) ?></td>
                                <td><?= esc($province['name']) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/regions/' . $region['id'] . '/remove-province/' . $province['id']) ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to remove this province from the region?');">
                                        <i class="fas fa-unlink"></i> Remove
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($provinces)): ?>
                            <tr>
                                <td colspan="4" class="text-center">No provinces assigned to this region</td>
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
