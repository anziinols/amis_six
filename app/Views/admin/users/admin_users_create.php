<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create New User</h5>
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>
                <div class="card-body">
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger">
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('success')): ?>
                        <div class="alert alert-success">
                            <?= session('success') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Show validation errors if any -->
                    <?php if (session()->has('validation')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('validation')->getErrors() as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/users') ?>" method="post" class="needs-validation" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="created_by" value="<?= session()->get('user_id') ?? 1 ?>">
                        <input type="hidden" name="updated_by" value="<?= session()->get('user_id') ?? 1 ?>">
                        <input type="hidden" name="user_status" value="1">

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Basic Information</h6>


                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control <?= session()->has('validation') && session('validation')->hasError('email') ? 'is-invalid' : '' ?>"
                                           id="email" name="email" value="<?= old('email') ?>" required>
                                    <?php if (session()->has('validation') && session('validation')->hasError('email')): ?>
                                        <div class="invalid-feedback">
                                            <?= session('validation')->getError('email') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Account Activation:</strong> An activation email will be sent to the user. They will set their password during the activation process.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone">
                                </div>
                            </div>

                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Personal Information</h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fname" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="fname" name="fname" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="lname" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="lname" name="lname" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="dobirth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="dobirth" name="dobirth" value="<?= old('dobirth') ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="place_birth" class="form-label">Place of Birth</label>
                                    <input type="text" class="form-control" id="place_birth" name="place_birth">
                                </div>
                            </div>

                            <!-- Employment Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Employment Information</h6>

                                <div class="mb-3">
                                    <label for="employee_number" class="form-label">Employee Number</label>
                                    <input type="text" class="form-control" id="employee_number" name="employee_number">
                                </div>

                                <div class="mb-3">
                                    <label for="branch_id" class="form-label">Branch</label>
                                    <select class="form-select" id="branch_id" name="branch_id">
                                        <option value="">Select Branch</option>
                                        <?php foreach ($branches as $branch): ?>
                                            <option value="<?= $branch['id'] ?>"><?= esc($branch['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="designation" class="form-label">Designation</label>
                                    <input type="text" class="form-control" id="designation" name="designation">
                                </div>

                                <div class="mb-3">
                                    <label for="grade" class="form-label">Grade</label>
                                    <input type="text" class="form-control" id="grade" name="grade">
                                </div>

                                <div class="mb-3">
                                    <label for="report_to_id" class="form-label">Reports To</label>
                                    <select class="form-select" id="report_to_id" name="report_to_id">
                                        <option value="">Select Supervisor</option>
                                        <?php foreach ($supervisors as $supervisor): ?>
                                            <option value="<?= $supervisor['id'] ?>"><?= esc($supervisor['fname'] . ' ' . $supervisor['lname']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Additional Information</h6>

                                <div class="mb-3">
                                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="supervisor">Supervisor</option>
                                        <option value="user">User</option>
                                        <option value="guest">Guest</option>
                                        <option value="commodity">Commodity</option>
                                    </select>
                                </div>

                                <div class="mb-3" id="commodity_field" style="display: none;">
                                    <label for="commodity_id" class="form-label">Commodity <span class="text-danger">*</span></label>
                                    <select class="form-select" id="commodity_id" name="commodity_id">
                                        <option value="">Select Commodity</option>
                                        <?php foreach ($commodities as $commodity): ?>
                                            <option value="<?= $commodity['id'] ?>"><?= esc($commodity['commodity_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="joined_date" class="form-label">Joined Date</label>
                                    <input type="date" class="form-control" id="joined_date" name="joined_date" value="<?= old('joined_date') ?>">
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_evaluator" name="is_evaluator" value="1">
                                        <label class="form-check-label" for="is_evaluator">
                                            Is Evaluator (M&E)
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="reset" class="btn btn-secondary me-2">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // Form validation
    var form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    }, false);

    // Show/hide commodity field based on role selection
    document.getElementById('role').addEventListener('change', function() {
        const commodityField = document.getElementById('commodity_field');
        const commoditySelect = document.getElementById('commodity_id');

        if (this.value === 'commodity') {
            commodityField.style.display = 'block';
            commoditySelect.setAttribute('required', 'required');
        } else {
            commodityField.style.display = 'none';
            commoditySelect.removeAttribute('required');
            commoditySelect.value = ''; // Clear the selection when role is not commodity
        }
    });

    // Initialize the commodity field visibility on page load
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const commodityField = document.getElementById('commodity_field');
        const commoditySelect = document.getElementById('commodity_id');

        // Trigger the change event to set initial state
        if (roleSelect.value !== 'commodity') {
            commodityField.style.display = 'none';
            commoditySelect.removeAttribute('required');
            commoditySelect.value = '';
        }
    });

    // Initialize any select2 dropdowns if needed
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('#branch_id').select2({
            placeholder: 'Select Branch',
            allowClear: true
        });

        $('#report_to_id').select2({
            placeholder: 'Select Supervisor',
            allowClear: true
        });

        $('#commodity_id').select2({
            placeholder: 'Select Commodity',
            allowClear: true
        });
    }
});
</script>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Display flash messages using Toastr
    <?php if (session()->has('success')): ?>
        toastr.success('<?= session('success') ?>');
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        toastr.error('<?= session('error') ?>');
    <?php endif; ?>

    <?php if (session()->has('validation')): ?>
        <?php foreach (session('validation')->getErrors() as $error): ?>
            toastr.error('<?= esc($error) ?>');
        <?php endforeach; ?>
    <?php endif; ?>
</script>
<?= $this->endSection() ?>
