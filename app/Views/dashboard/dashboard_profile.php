<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <!-- Profile Card -->
            <div class="card">
                <div class="card-body text-center">
                    <img src="<?= isset($userData['id_photo_filepath']) && !empty($userData['id_photo_filepath']) ? base_url($userData['id_photo_filepath']) : base_url('public/assets/system_images/no-users-img.png') ?>"
                         class="rounded-circle mb-3" alt="Profile Picture"
                         style="width: 150px; height: 150px; object-fit: cover;">
                    <h4 class="card-title"><?= $user['name'] ?></h4>
                    <p class="text-muted"><?= ucfirst($user['role']) ?></p>
                    <button type="button" id="changePhotoBtn" class="btn btn-outline-primary btn-sm">Change Photo</button>

                    <!-- Hidden file input for profile photo -->
                    <form id="photoForm" style="display: none;">
                        <input type="file" id="profilePhoto" name="profile_photo" accept="image/*">
                        <?= csrf_field() ?>
                    </form>
                </div>
            </div>

            <!-- Additional User Info Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-envelope me-2"></i> Email</span>
                            <span class="text-muted"><?= $userData['email'] ?? 'Not set' ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-phone me-2"></i> Phone</span>
                            <span class="text-muted"><?= $userData['phone'] ?? 'Not set' ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-calendar me-2"></i> Member Since</span>
                            <span class="text-muted"><?= isset($userData['created_at']) ? date('M d, Y', strtotime($userData['created_at'])) : 'Not available' ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-user-clock me-2"></i> Last Login</span>
                            <span class="text-muted"><?= isset($userData['last_login']) ? date('M d, Y H:i', strtotime($userData['last_login'])) : 'Not available' ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Profile Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('profile_success')): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= session()->getFlashdata('profile_success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('profile_error')): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?= session()->getFlashdata('profile_error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('dashboard/update-profile') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="fname" value="<?= esc($userData['fname'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="lname" value="<?= esc($userData['lname'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?= esc($userData['email'] ?? '') ?>" readonly>
                            <div class="form-text">Email cannot be changed. Contact administrator if needed.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" name="phone" value="<?= esc($userData['phone'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="3"><?= esc($userData['address'] ?? '') ?></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" name="dob" value="<?= esc($userData['dob'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <select class="form-select" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" <?= (isset($userData['gender']) && $userData['gender'] == 'male') ? 'selected' : '' ?>>Male</option>
                                    <option value="female" <?= (isset($userData['gender']) && $userData['gender'] == 'female') ? 'selected' : '' ?>>Female</option>
                                    <option value="other" <?= (isset($userData['gender']) && $userData['gender'] == 'other') ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Change Password</h5>
                </div>
                <div class="card-body">


                    <form action="<?= base_url('dashboard/update-password') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" name="current_password" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="new_password" required minlength="4">
                            <div class="form-text">Password must be at least 4 characters long.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" name="confirm_password" required minlength="4">
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key me-2"></i>Change Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Change Photo Button
    $('#changePhotoBtn').on('click', function() {
        $('#profilePhoto').click();
    });

    // Upload profile photo when file is selected
    $('#profilePhoto').on('change', function() {
        if (this.files && this.files[0]) {
            // Create new FormData
            const formData = new FormData();

            // Get CSRF token values
            var csrfName = '<?= csrf_token() ?>';
            var csrfHash = '<?= csrf_hash() ?>';

            // Add the file and CSRF token to FormData
            formData.append('profile_photo', this.files[0]);
            formData.append(csrfName, csrfHash);

            $.ajax({
                url: '<?= base_url('dashboard/update-profile-photo') ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                cache: false,
                beforeSend: function(xhr) {
                    // Show loading state on button
                    $('#changePhotoBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...').prop('disabled', true);
                },
                success: function(response) {
                    // Reset button state
                    $('#changePhotoBtn').html('Change Photo').prop('disabled', false);

                    if(response.success) {
                        // Update the displayed image
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('img[alt="Profile Picture"]').attr('src', e.target.result);
                        }
                        reader.readAsDataURL($('#profilePhoto')[0].files[0]);

                        // Show success message with Toastr
                        toastr.success(response.message || 'Profile photo updated successfully');

                        // Reload page after 1 second to show updated photo
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        // Show error message with Toastr
                        toastr.error(response.message || 'Error updating profile photo');
                    }
                },
                error: function(xhr, status, error) {
                    // Reset button state
                    $('#changePhotoBtn').html('Change Photo').prop('disabled', false);

                    console.error('Upload error:', xhr.responseText);
                    let errorMsg = 'Error updating profile photo';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    // Show error message with Toastr
                    toastr.error(errorMsg);
                }
            });
        }
    });
});
</script>
<?= $this->endSection() ?>