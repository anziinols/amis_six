<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Organization Settings</h5>
                <a href="<?= base_url('admin/org-settings/new') ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New Setting
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($settings)): ?>
                    <div class="alert alert-info">
                        No organization settings found. Click "Add New Setting" to create one.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="settingsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Created</th>
                                    <th>Updated</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($settings as $setting): ?>
                                    <tr>
                                        <td><?= $setting['id'] ?></td>
                                        <td><?= esc($setting['settings_code']) ?></td>
                                        <td><?= esc($setting['settings_name']) ?></td>
                                        <td><?= date('Y-m-d H:i', strtotime($setting['created_at'])) ?></td>
                                        <td><?= date('Y-m-d H:i', strtotime($setting['updated_at'])) ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('admin/org-settings/' . $setting['id']) ?>" class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('admin/org-settings/' . $setting['id'] . '/edit') ?>" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" onclick="confirmDelete('<?= $setting['id'] ?>')" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this setting? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="deleteLink" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#settingsTable').DataTable({
            order: [[0, 'desc']]
        });
    });

    function confirmDelete(id) {
        var deleteLink = document.getElementById('deleteLink');
        deleteLink.href = '<?= base_url('admin/org-settings') ?>/' + id + '/delete';
        
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>
<?= $this->endSection() ?> 