<?= $this->extend('dakoii/dakoii_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Success/Error Messages -->
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">User Management</h5>
            <a href="<?= base_url('dakoii/users/create') ?>" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Add New User
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No users found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= esc($user['id']) ?></td>
                                    <td><?= esc($user['name']) ?></td>
                                    <td><?= esc($user['username']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'manager' ? 'warning' : 'info') ?>">
                                            <?= esc(ucfirst($user['role'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $user['dakoii_user_status'] == 1 ? 'success' : 'secondary' ?>">
                                            <?= $user['dakoii_user_status'] == 1 ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                    <td><?= date('Y-m-d', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= base_url('dakoii/users/edit/' . $user['id']) ?>" 
                                               class="btn btn-sm btn-info" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger delete-user" 
                                                    data-id="<?= $user['id'] ?>" 
                                                    data-name="<?= esc($user['name']) ?>"
                                                    title="Delete">
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
                Are you sure you want to delete user: <span id="userName" class="fw-bold"></span>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Delete user confirmation
    $('.delete-user').click(function() {
        var userId = $(this).data('id');
        var userName = $(this).data('name');
        
        $('#userName').text(userName);
        $('#confirmDelete').attr('href', '<?= base_url('dakoii/users/delete') ?>/' + userId);
        
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    });

    // Example of AJAX delete
    /*
    $('#confirmDelete').click(function(e) {
        e.preventDefault();
        var userId = $(this).attr('href').split('/').pop();
        
        $.ajax({
            url: '<?= base_url('dakoii/users/delete') ?>/' + userId,
            type: 'POST',
            data: {
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Failed to delete user');
                }
            },
            error: function() {
                alert('An error occurred while deleting the user');
            }
        });
    });
    */
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 