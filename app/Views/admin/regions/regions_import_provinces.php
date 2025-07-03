<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <a href="<?= base_url('admin/regions/' . $region['id']) ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Region
            </a>
        </div>
        <div class="card-body">
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
                    </table>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Currently Assigned Provinces</h6>
                    <?php if (empty($assignedProvinces)): ?>
                        <div class="alert alert-info">
                            No provinces are currently assigned to this region.
                        </div>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($assignedProvinces as $province): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= esc($province['name']) ?>
                                    <span class="badge bg-primary rounded-pill"><?= esc($province['code']) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <form action="<?= base_url('admin/regions/' . $region['id'] . '/import-provinces') ?>" method="post">
                <?= csrf_field() ?>

                <h6 class="text-muted">Available Provinces for Import</h6>
                <?php if (empty($unassignedProvinces)): ?>
                    <div class="alert alert-warning">
                        No provinces are available for import. All provinces have been assigned to regions.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="availableProvincesTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                    </th>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 1; ?>
                                <?php foreach ($unassignedProvinces as $province): ?>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input province-checkbox" type="checkbox" name="province_ids[]" value="<?= $province['id'] ?>">
                                            </div>
                                        </td>
                                        <td><?= $counter++ ?></td>
                                        <td><?= esc($province['code']) ?></td>
                                        <td><?= esc($province['name']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                        <button type="submit" class="btn btn-primary" id="importButton" disabled>
                            <i class="fas fa-file-import"></i> Import Selected Provinces
                        </button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // DataTables initialization removed to prevent errors
        // The table will function as a regular HTML table

        // Handle select all checkbox
        $('#selectAll').change(function() {
            $('.province-checkbox').prop('checked', $(this).prop('checked'));
            updateImportButton();
        });

        // Handle individual checkboxes
        $(document).on('change', '.province-checkbox', function() {
            updateImportButton();

            // Update select all checkbox
            if ($('.province-checkbox:checked').length === $('.province-checkbox').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
        });

        // Enable/disable import button based on selection
        function updateImportButton() {
            if ($('.province-checkbox:checked').length > 0) {
                $('#importButton').prop('disabled', false);
            } else {
                $('#importButton').prop('disabled', true);
            }
        }
    });
</script>
<?= $this->endSection() ?>
