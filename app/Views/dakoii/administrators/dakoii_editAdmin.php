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

            <form action="<?= base_url('dakoii/administrators/' . $admin['id'] . '/update') ?>" method="post">
                <?= csrf_field() ?>
                
                <!-- Administrator Information -->
                <div class="mb-4">
                    <h5 class="text-primary mb-3">Administrator Information</h5>
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
                            <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" id="password" name="password">
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


<?= $this->endSection() ?> 