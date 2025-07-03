<?= $this->extend('dakoii/dakoii_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Administrator</h1>
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

            <form action="<?= base_url('dakoii/administrators/store') ?>" method="post">
                <?= csrf_field() ?>
                
                <!-- Personal Information -->
                <div class="mb-4">
                    <h5 class="text-primary mb-3">Personal Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fname" class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="fname" name="fname" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lname" class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="lname" name="lname" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender *</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="dobirth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dobirth" name="dobirth">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="place_birth" class="form-label">Place of Birth</label>
                            <input type="text" class="form-control" id="place_birth" name="place_birth">
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="mb-4">
                    <h5 class="text-primary mb-3">Professional Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="employee_number" class="form-label">Employee Number</label>
                            <input type="text" class="form-control" id="employee_number" name="employee_number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="branch_id" class="form-label">Branch</label>
                            <select class="form-select" id="branch_id" name="branch_id">
                                <option value="">Select Branch</option>
                                <option value="1">Headquarters</option>
                                <option value="2">Branch 1</option>
                                <option value="3">Branch 2</option>
                                <option value="4">Branch 3</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="designation" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="designation" name="designation">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="grade" class="form-label">Grade</label>
                            <input type="text" class="form-control" id="grade" name="grade">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="joined_date" class="form-label">Joined Date</label>
                            <input type="date" class="form-control" id="joined_date" name="joined_date">
                        </div>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="mb-4">
                    <h5 class="text-primary mb-3">Account Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password *</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirm" class="form-label">Confirm Password *</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
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
                            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Administrator
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('password_confirm').addEventListener('input', function() {
    if (this.value !== document.getElementById('password').value) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});
</script>
<?= $this->endSection() ?> 