<?= $this->extend('dakoii/dakoii_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">System Administrators</h4>
        <a href="<?= base_url('dakoii/administrators/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Administrator
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Employee Number</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($administrators)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No administrators found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($administrators as $admin): ?>
                                <tr>
                                    <td><?= esc($admin['fname'] . ' ' . $admin['lname']) ?></td>
                                    <td><?= esc($admin['email']) ?></td>
                                    <td><?= esc($admin['phone']) ?></td>
                                    <td><?= esc($admin['employee_number']) ?></td>
                                    <td>
                                        <span class="badge <?= $admin['user_status'] ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $admin['user_status'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= base_url('dakoii/administrators/' . $admin['id'] . '/edit') ?>"
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete(<?= $admin['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this administrator?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="post" style="display: inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmDelete(id) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = '<?= base_url('dakoii/administrators/') ?>' + id + '/delete';
    modal.show();
}
</script>
<?= $this->endSection() ?> 