<?= $this->extend('dakoii/dakoii_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit System User</h1>
        <a href="<?= base_url('dakoii/administrators') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="<?= base_url('dakoii/administrators/' . $admin['id'] . '/update') ?>" method="post">
                <?= csrf_field() ?>
                
                <!-- User Information -->
                <div class="mb-4">
                    <h5 class="text-primary mb-3">User Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fname" class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="fname" name="fname" value="<?= old('fname', $admin['fname']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lname" class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="lname" name="lname" value="<?= old('lname', $admin['lname']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $admin['email']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone *</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?= old('phone', $admin['phone']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="user" <?= old('role', $admin['role']) == 'user' ? 'selected' : '' ?>>User</option>
                                <option value="guest" <?= old('role', $admin['role']) == 'guest' ? 'selected' : '' ?>>Guest</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="user_status" class="form-label">Status</label>
                            <select class="form-select" id="user_status" name="user_status">
                                <option value="1" <?= old('user_status', $admin['user_status']) == 1 ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= old('user_status', $admin['user_status']) == 0 ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Capabilities -->
                <div class="mb-4">
                    <h5 class="text-primary mb-3">User Capabilities</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" value="1"
                                       <?= old('is_admin', $admin['is_admin']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_admin">
                                    <strong>Administrator</strong><br>
                                    <small class="text-muted">Full system access and user management</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_supervisor" name="is_supervisor" value="1"
                                       <?= old('is_supervisor', $admin['is_supervisor']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_supervisor">
                                    <strong>Supervisor</strong><br>
                                    <small class="text-muted">Can supervise and approve work</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_evaluator" name="is_evaluator" value="1"
                                       <?= old('is_evaluator', $admin['is_evaluator']) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_evaluator">
                                    <strong>Evaluator</strong><br>
                                    <small class="text-muted">Can evaluate and assess performance</small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?= $this->endSection() ?>