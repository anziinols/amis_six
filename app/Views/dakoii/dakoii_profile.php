<?= $this->extend('dakoii/dakoii_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">


    <?php if (!isset($user) || empty($user)): ?>
        <div class="alert alert-danger">
            No user data available. Please try logging in again.
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">My Profile</h5>
                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'manager' ? 'warning' : 'info') ?>">
                            <?= esc(ucfirst($user['role'])) ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('dakoii/profile/update') ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="mb-4">
                                <div class="text-center mb-4">
                                    <img src="<?= base_url('public/assets/system_images/no-users-img.png') ?>" 
                                         alt="Profile Picture" 
                                         class="rounded-circle"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= esc($user['name']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?= esc($user['username']) ?>" required>
                            </div>

                            <div class="mb-4">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                                <small class="text-muted">Required to save changes</small>
                            </div>

                            <hr class="my-4">

                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                                <small class="text-muted">Leave blank if you don't want to change password</small>
                            </div>

                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('dakoii/dashboard') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Account Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Account Status:</strong></p>
                                <span class="badge bg-<?= ($user['dakoii_user_status'] == 1) ? 'success' : 'secondary' ?>">
                                    <?= ($user['dakoii_user_status'] == 1) ? 'Active' : 'Inactive' ?>
                                </span>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Member Since:</strong></p>
                                <p><?= date('F j, Y', strtotime($user['created_at'])) ?></p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Last Updated:</strong></p>
                                <p><?= date('F j, Y g:i A', strtotime($user['updated_at'])) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>User ID:</strong></p>
                                <p>#<?= str_pad($user['id'], 5, '0', STR_PAD_LEFT) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Password confirmation validation
    $('form').on('submit', function(e) {
        console.log('Form submission started');

        var name = $('#name').val();
        var username = $('#username').val();
        var currentPassword = $('#current_password').val();
        var newPassword = $('#new_password').val();
        var confirmPassword = $('#confirm_password').val();

        console.log('Form data:', {
            name: name,
            username: username,
            currentPassword: currentPassword ? 'provided' : 'empty',
            newPassword: newPassword ? 'provided' : 'empty',
            confirmPassword: confirmPassword ? 'provided' : 'empty'
        });

        // Check required fields
        if (!name || !username || !currentPassword) {
            e.preventDefault();
            alert('DEBUG: Missing required fields - Name: ' + (name ? 'OK' : 'EMPTY') + ', Username: ' + (username ? 'OK' : 'EMPTY') + ', Current Password: ' + (currentPassword ? 'OK' : 'EMPTY'));
            return false;
        }

        if (newPassword && newPassword !== confirmPassword) {
            e.preventDefault();
            alert('DEBUG: New passwords do not match!');
            return false;
        }

        if (!currentPassword) {
            e.preventDefault();
            alert('DEBUG: Please enter your current password to save changes');
            return false;
        }

        console.log('Form validation passed, submitting...');
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 