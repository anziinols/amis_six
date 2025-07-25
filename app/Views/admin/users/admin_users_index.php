<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Users Management</h1>
        <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New User
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Branch</th>
                            <th>Role</th>
                            <th>Is M&E</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= $user['fname'] . ' ' . $user['lname'] ?></td>
                                <td><?= $user['email'] ?></td>
                                <td><?= $user['branch_name'] ?? '<span class="text-muted">No Branch</span>' ?></td>
                                <td><?= ucfirst($user['role']) ?></td>
                                <td>
                                    <?php if ($user['is_evaluator'] == 1): ?>
                                        <span class="badge bg-info">
                                            <i class="fas fa-chart-line me-1"></i>M&E
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $user['user_status'] ? 'success' : 'danger' ?>">
                                        <?= $user['user_status'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm <?= $user['user_status'] ? 'btn-warning' : 'btn-success' ?>" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#statusModal<?= $user['id'] ?>"
                                            data-userid="<?= $user['id'] ?>"
                                            data-username="<?= $user['fname'] . ' ' . $user['lname'] ?>"
                                            data-status="<?= $user['user_status'] ?>"
                                            data-action="<?= $user['user_status'] ? 'deactivate' : 'activate' ?>">
                                        <i class="fas <?= $user['user_status'] ? 'fa-user-slash' : 'fa-user-check' ?>"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Status Modals -->
    <?php foreach ($users as $user): ?>
    <div class="modal fade" id="statusModal<?= $user['id'] ?>" tabindex="-1" aria-labelledby="statusModalLabel<?= $user['id'] ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= base_url('admin/users/toggle-status/' . $user['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusModalLabel<?= $user['id'] ?>">
                            <?= $user['user_status'] ? 'Deactivate' : 'Activate' ?> User: <?= $user['fname'] . ' ' . $user['lname'] ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-<?= $user['user_status'] ? 'warning' : 'success' ?>">
                            <p>Are you sure you want to <?= $user['user_status'] ? 'deactivate' : 'activate' ?> this user?</p>
                        </div>
                        <div class="mb-3">
                            <label for="remarks<?= $user['id'] ?>" class="form-label">Status Change Remarks</label>
                            <textarea class="form-control" id="remarks<?= $user['id'] ?>" name="remarks" rows="3" 
                                placeholder="Enter remarks for this status change"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-<?= $user['user_status'] ? 'warning' : 'success' ?>">
                            <?= $user['user_status'] ? 'Deactivate' : 'Activate' ?> User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Display flash messages using Toastr
    <?php if (session()->getFlashdata('success')): ?>
        toastr.success('<?= session()->getFlashdata('success') ?>');
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        toastr.error('<?= session()->getFlashdata('error') ?>');
    <?php endif; ?>
</script>
<?= $this->endSection() ?>