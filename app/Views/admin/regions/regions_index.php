<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Regions</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Regions</h3>
                    <div>
                        <a href="<?= base_url('admin/regions/new') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Region
                        </a>
                    </div>
                </div>
                <div class="card-body">

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
                                <?php if (empty($regions)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No regions found</td>
                                    </tr>
                                <?php else: ?>
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
                                                <a href="<?= base_url('admin/regions/' . $region['id']) ?>"
                                                   class="btn btn-outline-primary"
                                                   title="View Details"
                                                   style="margin-right: 5px;">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </a>
                                                <a href="<?= base_url('admin/regions/' . $region['id'] . '/edit') ?>"
                                                   class="btn btn-outline-warning"
                                                   title="Edit Region"
                                                   style="margin-right: 5px;">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <a href="<?= base_url('admin/regions/' . $region['id'] . '/import-provinces') ?>"
                                                   class="btn btn-outline-success"
                                                   title="Import Provinces"
                                                   style="margin-right: 5px;">
                                                    <i class="fas fa-file-import me-1"></i> Import
                                                </a>
                                                <a href="<?= base_url('admin/regions/' . $region['id'] . '/delete') ?>"
                                                   class="btn btn-outline-danger"
                                                   title="Delete Region"
                                                   onclick="return confirm('Are you sure you want to delete this region?');">
                                                    <i class="fas fa-trash me-1"></i> Delete
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

<!-- Toastr initialization -->
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

<?= $this->section('scripts') ?>
<script>
    // DataTables initialization removed to prevent errors
    // The table will function as a regular HTML table
</script>
<?= $this->endSection() ?>
