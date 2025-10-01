<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Users Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Users Management</h3>
                    <div>
                        <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New User
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Branch</th>
                                    <th>Role</th>
                                    <th>Access Levels</th>
                                    <th>Status</th>
                                    <th>Activation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td><?= esc($user['fname'] . ' ' . $user['lname']) ?></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td><?= $user['branch_name'] ?? '<span class="text-muted">No Branch</span>' ?></td>
                                        <td><?= ucfirst($user['role']) ?></td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <?php if ($user['is_admin'] == 1): ?>
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-user-shield me-1"></i>Admin
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($user['is_supervisor'] == 1): ?>
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-users me-1"></i>Supervisor
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($user['is_evaluator'] == 1): ?>
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-chart-line me-1"></i>M&E
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ($user['is_admin'] != 1 && $user['is_supervisor'] != 1 && $user['is_evaluator'] != 1): ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $user['user_status'] ? 'success' : 'danger' ?>">
                                                <?= $user['user_status'] ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (isset($user['is_activated'])): ?>
                                                <?php if ($user['is_activated']): ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle"></i> Activated
                                                    </span>
                                                    <?php if (!empty($user['activated_at'])): ?>
                                                        <br><small class="text-muted"><?= date('M d, Y', strtotime($user['activated_at'])) ?></small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-clock"></i> Pending
                                                    </span>
                                                    <?php if (!empty($user['activation_expires_at'])): ?>
                                                        <br><small class="text-muted">Expires: <?= date('M d, H:i', strtotime($user['activation_expires_at'])) ?></small>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Legacy</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('admin/users/' . $user['id'] . '/edit') ?>"
                                               class="btn btn-outline-warning"
                                               title="Edit User"
                                               style="margin-right: 5px;">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>

                                            <?php if (isset($user['is_activated']) && !$user['is_activated']): ?>
                                                <!-- Resend Activation Button -->
                                                <form method="post" action="<?= base_url('admin/users/' . $user['id'] . '/resend-activation') ?>" style="display: inline;">
                                                    <?= csrf_field() ?>
                                                    <button type="submit"
                                                            class="btn btn-outline-info"
                                                            title="Resend Activation Email"
                                                            style="margin-right: 5px;"
                                                            onclick="return confirm('Resend activation email to <?= esc($user['email']) ?>?')">
                                                        <i class="fas fa-envelope me-1"></i> Resend
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <?php
                                            // Check if user was created within 24 hours for delete permission
                                            $createdTime = strtotime($user['created_at']);
                                            $canDelete = (time() - $createdTime) < (24 * 3600);
                                            ?>

                                            <?php if ($canDelete && $user['id'] != session()->get('user_id')): ?>
                                                <!-- Delete Button (24-hour window) -->
                                                <form method="post" action="<?= base_url('admin/users/' . $user['id']) ?>" style="display: inline;">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit"
                                                            class="btn btn-outline-danger"
                                                            title="Delete User (24hr window)"
                                                            onclick="return confirm('Are you sure you want to delete <?= esc($user['fname'] . ' ' . $user['lname']) ?>? This action cannot be undone.')">
                                                        <i class="fas fa-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <!-- Status Toggle Button -->
                                            <button type="button"
                                                    class="btn btn-outline-<?= $user['user_status'] ? 'secondary' : 'success' ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#statusModal<?= $user['id'] ?>"
                                                    data-userid="<?= $user['id'] ?>"
                                                    data-username="<?= esc($user['fname'] . ' ' . $user['lname']) ?>"
                                                    data-status="<?= $user['user_status'] ?>"
                                                    data-action="<?= $user['user_status'] ? 'deactivate' : 'activate' ?>"
                                                    title="<?= $user['user_status'] ? 'Deactivate' : 'Activate' ?> User">
                                                <i class="fas <?= $user['user_status'] ? 'fa-user-slash' : 'fa-user-check' ?> me-1"></i>
                                                <?= $user['user_status'] ? 'Deactivate' : 'Activate' ?>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
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
                            <?= $user['user_status'] ? 'Deactivate' : 'Activate' ?> User: <?= esc($user['fname'] . ' ' . $user['lname']) ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-<?= $user['user_status'] ? 'warning' : 'success' ?>">
                            <p>Are you sure you want to <?= $user['user_status'] ? 'deactivate' : 'activate' ?> this user?</p>
                        </div>
                        <div class="form-group mb-3">
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
    document.addEventListener('DOMContentLoaded', function() {
        // Display flash messages using Toastr
        <?php if (session()->getFlashdata('success')): ?>
            toastr.success('<?= session()->getFlashdata('success') ?>');
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            toastr.error('<?= session()->getFlashdata('error') ?>');
        <?php endif; ?>
    });
</script>
<?= $this->endSection() ?>