<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit User</h5>
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
                    <?php if (isset($validation)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($validation->getErrors() as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/users/' . $user['id']) ?>" method="post" class="needs-validation" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="created_by" value="<?= $user['created_by'] ?>">
                        <input type="hidden" name="updated_by" value="<?= session()->get('user_id') ?? 1 ?>">

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Basic Information</h6>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>"
                                           id="email" name="email" value="<?= old('email', $user['email']) ?>" required>
                                    <?php if (isset($validation) && $validation->hasError('email')): ?>
                                        <div class="invalid-feedback">
                                            <?= $validation->getError('email') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="<?= old('phone', $user['phone']) ?>">
                                </div>

                                <div class="mb-3">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Password Management:</strong> Users manage their own passwords through the activation workflow. Contact support for password resets.
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Personal Information</h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fname" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('fname') ? 'is-invalid' : '' ?>"
                                               id="fname" name="fname" value="<?= old('fname', $user['fname']) ?>" required>
                                        <?php if (isset($validation) && $validation->hasError('fname')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('fname') ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="lname" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('lname') ? 'is-invalid' : '' ?>"
                                               id="lname" name="lname" value="<?= old('lname', $user['lname']) ?>" required>
                                        <?php if (isset($validation) && $validation->hasError('lname')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('lname') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male" <?= old('gender', $user['gender']) == 'male' ? 'selected' : '' ?>>Male</option>
                                        <option value="female" <?= old('gender', $user['gender']) == 'female' ? 'selected' : '' ?>>Female</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="dobirth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="dobirth" name="dobirth" value="<?= old('dobirth', $user['dobirth'] == '0000-00-00' ? '' : $user['dobirth']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="place_birth" class="form-label">Place of Birth</label>
                                    <input type="text" class="form-control" id="place_birth" name="place_birth" value="<?= old('place_birth', $user['place_birth']) ?>">
                                </div>
                            </div>

                            <!-- Employment Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Employment Information</h6>

                                <div class="mb-3">
                                    <label for="employee_number" class="form-label">Employee Number</label>
                                    <input type="text" class="form-control" id="employee_number" name="employee_number" value="<?= old('employee_number', $user['employee_number']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="branch_id" class="form-label">Branch</label>
                                    <select class="form-select" id="branch_id" name="branch_id">
                                        <option value="">Select Branch</option>
                                        <?php foreach ($branches as $branch): ?>
                                            <option value="<?= $branch['id'] ?>" <?= old('branch_id', $user['branch_id']) == $branch['id'] ? 'selected' : '' ?>>
                                                <?= esc($branch['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="designation" class="form-label">Designation</label>
                                    <input type="text" class="form-control" id="designation" name="designation" value="<?= old('designation', $user['designation']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="grade" class="form-label">Grade</label>
                                    <input type="text" class="form-control" id="grade" name="grade" value="<?= old('grade', $user['grade']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="report_to_id" class="form-label">Reports To</label>
                                    <select class="form-select" id="report_to_id" name="report_to_id">
                                        <option value="">Select Supervisor</option>
                                        <?php foreach ($supervisors as $supervisor): ?>
                                            <option value="<?= $supervisor['id'] ?>" <?= old('report_to_id', $user['report_to_id']) == $supervisor['id'] ? 'selected' : '' ?>>
                                                <?= esc($supervisor['fname'] . ' ' . $supervisor['lname']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3 text-primary">Additional Information</h6>

                                <div class="mb-3">
                                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select <?= isset($validation) && $validation->hasError('role') ? 'is-invalid' : '' ?>"
                                            id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="admin" <?= old('role', $user['role']) == 'admin' ? 'selected' : '' ?>>Admin</option>
                                        <option value="supervisor" <?= old('role', $user['role']) == 'supervisor' ? 'selected' : '' ?>>Supervisor</option>
                                        <option value="user" <?= old('role', $user['role']) == 'user' ? 'selected' : '' ?>>User</option>
                                        <option value="guest" <?= old('role', $user['role']) == 'guest' ? 'selected' : '' ?>>Guest</option>
                                        <option value="commodity" <?= old('role', $user['role']) == 'commodity' ? 'selected' : '' ?>>Commodity</option>
                                    </select>
                                    <?php if (isset($validation) && $validation->hasError('role')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('role') ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3" id="commodity_field" style="display: <?= old('role', $user['role']) == 'commodity' ? 'block' : 'none' ?>;">
                                    <label for="commodity_id" class="form-label">Commodity <span class="text-danger">*</span></label>
                                    <select class="form-select" id="commodity_id" name="commodity_id" <?= old('role', $user['role']) == 'commodity' ? 'required' : '' ?>>
                                        <option value="">Select Commodity</option>
                                        <?php foreach ($commodities as $commodity): ?>
                                            <option value="<?= $commodity['id'] ?>" <?= old('commodity_id', $user['commodity_id']) == $commodity['id'] ? 'selected' : '' ?>>
                                                <?= esc($commodity['commodity_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="joined_date" class="form-label">Joined Date</label>
                                    <input type="date" class="form-control" id="joined_date" name="joined_date" value="<?= old('joined_date', $user['joined_date'] == '0000-00-00' ? '' : $user['joined_date']) ?>">
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_evaluator" name="is_evaluator" value="1" <?= old('is_evaluator', $user['is_evaluator']) == '1' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_evaluator">
                                            Is Evaluator (M&E)
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"><?= old('address', $user['address']) ?></textarea>
                                </div>

                                <!-- Status Information (Read-only) -->
                                <div class="border p-3 rounded bg-light mb-3">
                                    <h6 class="mb-3 text-secondary">Status Information</h6>
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <label class="form-label fw-bold mb-0">Current Status:</label>
                                            <div>
                                                <?php if($user['user_status'] == '1'): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Inactive</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <label class="form-label fw-bold mb-0">Last Status Change:</label>
                                            <div>
                                                <?= !empty($user['user_status_at']) ? date('M d, Y h:i A', strtotime($user['user_status_at'])) : 'Not available' ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold mb-0">Changed By:</label>
                                            <div>
                                                <?php if(!empty($user['user_status_by'])): ?>
                                                    <?php if(isset($statusUser) && $statusUser): ?>
                                                        <?= esc($statusUser['fname'] . ' ' . $statusUser['lname']) ?> (<?= esc($statusUser['email']) ?>)
                                                    <?php else: ?>
                                                        User ID: <?= $user['user_status_by'] ?>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    Not available
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label class="form-label fw-bold mb-0">Status Remarks:</label>
                                            <div>
                                                <?php if(!empty($user['user_status_remarks'])): ?>
                                                    <p class="text-muted mb-0"><?= esc($user['user_status_remarks']) ?></p>
                                                <?php else: ?>
                                                    <p class="text-muted mb-0">No remarks available</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i> Status can only be changed from the users list page.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="reset" class="btn btn-secondary me-2">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update User
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
        toastr.success(<?= json_encode(session('success')) ?>);
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        toastr.error(<?= json_encode(session('error')) ?>);
    <?php endif; ?>

    <?php if (isset($validation)): ?>
        <?php foreach ($validation->getErrors() as $field => $error): ?>
            toastr.error(<?= json_encode($error) ?>);
        <?php endforeach; ?>
    <?php endif; ?>
</script>
<?= $this->endSection() ?>