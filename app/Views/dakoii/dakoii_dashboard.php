<?= $this->extend('dakoii/dakoii_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Success/Error Messages -->
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Welcome Card -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4>Welcome, <?= esc($user['name'] ?? 'User') ?>!</h4>
                            <p>You are logged in as <span class="badge bg-primary"><?= esc($user['role'] ?? 'Guest') ?></span></p>
                            <p>This is the Dakoii Portal dashboard for the Agriculture Management Information System. From here, you can manage users and their roles.</p>
                            <button class="btn btn-primary mt-2">
                                <i class="fas fa-book-open me-2"></i> View User Guide
                            </button>
                        </div>
                        <div class="col-md-4 text-center">
                            <img src="<?= base_url('public/assets/system_images/dakoii_logo.png') ?>" alt="Dakoii Logo" class="img-fluid" style="max-height: 150px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Users</h6>
                            <h2 class="mt-2 mb-0"><?= $total_users ?? 0 ?></h2>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Active Users</h6>
                            <h2 class="mt-2 mb-0"><?= isset($active_users) ? count($active_users) : 0 ?></h2>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">User Roles</h6>
                            <h2 class="mt-2 mb-0"><?= isset($roles) ? count($roles) : 3 ?></h2>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-user-tag fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="<?= base_url('dakoii/users/create') ?>" class="btn btn-primary w-100">
                                <i class="fas fa-user-plus me-2"></i> Add New User
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= base_url('dakoii/users') ?>" class="btn btn-info w-100">
                                <i class="fas fa-users me-2"></i> View All Users
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= base_url('dakoii/users/roles') ?>" class="btn btn-success w-100">
                                <i class="fas fa-user-tag me-2"></i> Manage Roles
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= base_url('dakoii/profile') ?>" class="btn btn-secondary w-100">
                                <i class="fas fa-user-cog me-2"></i> My Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 