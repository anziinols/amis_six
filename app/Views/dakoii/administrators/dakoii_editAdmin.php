<?= $this->extend('dakoii/dakoii_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Administrator</h1>
        <a href="<?= base_url('dakoii/administrators') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger">
                    <?= session('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('dakoii/administrators/update/' . $admin['id']) ?>" method="post">
                <?= csrf_field() ?>
                
                <!-- Personal Information -->
                <div class="mb-4">
                    <h5 class="text-primary mb-3">Personal Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fname" class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="fname" name="fname" value="<?= esc($admin['fname']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lname" class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="lname" name="lname" value="<?= esc($admin['lname']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= esc($admin['email']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?= esc($admin['phone']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender *</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male" <?= $admin['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= $admin['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="dobirth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dobirth" name="dobirth" value="<?= esc($admin['dobirth']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="place_birth" class="form-label">Place of Birth</label>
                            <input type="text" class="form-control" id="place_birth" name="place_birth" value="<?= esc($admin['place_birth']) ?>">
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="mb-4">
                    <h5 class="text-primary mb-3">Professional Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="employee_number" class="form-label">Employee Number</label>
                            <input type="text" class="form-control" id="employee_number" name="employee_number" value="<?= esc($admin['employee_number']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="branch_id" class="form-label">Branch</label>
                            <select class="form-select" id="branch_id" name="branch_id">
                                <option value="">Select Branch</option>
                                <option value="1" <?= $admin['branch_id'] == '1' ? 'selected' : '' ?>>Headquarters</option>
                                <option value="2" <?= $admin['branch_id'] == '2' ? 'selected' : '' ?>>Branch 1</option>
                                <option value="3" <?= $admin['branch_id'] == '3' ? 'selected' : '' ?>>Branch 2</option>
                                <option value="4" <?= $admin['branch_id'] == '4' ? 'selected' : '' ?>>Branch 3</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="designation" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="designation" name="designation" value="<?= esc($admin['designation']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="grade" class="form-label">Grade</label>
                            <input type="text" class="form-control" id="grade" name="grade" value="<?= esc($admin['grade']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="joined_date" class="form-label">Joined Date</label>
                            <input type="date" class="form-control" id="joined_date" name="joined_date" value="<?= esc($admin['joined_date']) ?>">
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="mb-4">
                    <h5 class="text-primary mb-3">Account Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="text-muted">Leave blank to keep current password</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirm" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1" <?= $admin['status'] == '1' ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= $admin['status'] == '0' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mb-4">
                    <h5 class="text-primary mb-3">Additional Information</h5>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?= esc($admin['address']) ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Administrator
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('password_confirm').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    if (password && this.value !== password) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});
</script>
<?= $this->endSection() ?> 