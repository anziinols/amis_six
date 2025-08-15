<?= $this->extend('dakoii/dakoii_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New System User</h1>
        <a href="<?= base_url('dakoii/administrators') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger"><?= session('error') ?></div>
            <?php endif; ?>

            <form action="<?= base_url('dakoii/administrators/store') ?>" method="post">
                <?= csrf_field() ?>
                
                <!-- User Information -->
                <div class="mb-4">
                    <h5 class="text-primary mb-3">User Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fname" class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="fname" name="fname" value="<?= old('fname') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lname" class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="lname" name="lname" value="<?= old('lname') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?= old('phone') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirm" class="form-label">Confirm Password *</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role">
                                <option value="user" <?= old('role') == 'user' ? 'selected' : '' ?>>User</option>
                                <option value="admin" <?= old('role') == 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="commodity" <?= old('role') == 'commodity' ? 'selected' : '' ?>>Commodity</option>
                                <option value="guest" <?= old('role') == 'guest' ? 'selected' : '' ?>>Guest</option>
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
                                <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" value="1" <?= old('is_admin') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_admin">
                                    <strong>Administrator</strong><br>
                                    <small class="text-muted">Full system access and user management</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_supervisor" name="is_supervisor" value="1" <?= old('is_supervisor') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_supervisor">
                                    <strong>Supervisor</strong><br>
                                    <small class="text-muted">Can supervise and approve work</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_evaluator" name="is_evaluator" value="1" <?= old('is_evaluator') ? 'checked' : '' ?>>
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
                        <i class="fas fa-save"></i> Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?= $this->endSection() ?> 