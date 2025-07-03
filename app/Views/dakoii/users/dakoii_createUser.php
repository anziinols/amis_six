<?= $this->extend('dakoii/dakoii_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Add New User</h5>
                </div>
                <div class="card-body">
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>

                    <form action="<?= base_url('dakoii/users/store') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= old('username') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="orgcode" class="form-label">Organization Code</label>
                            <input type="text" class="form-control" id="orgcode" name="orgcode" value="<?= old('orgcode') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select bg-dark text-white" id="role" name="role" required>
                                <option value="" class="bg-dark text-white">Select Role</option>
                                <option value="admin" class="bg-dark text-white" <?= old('role') == 'admin' ? 'selected' : '' ?>>Administrator</option>
                                <option value="manager" class="bg-dark text-white" <?= old('role') == 'manager' ? 'selected' : '' ?>>Manager</option>
                                <option value="user" class="bg-dark text-white" <?= old('role') == 'user' ? 'selected' : '' ?>>Regular User</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('dakoii/users') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
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

<?= $this->section('styles') ?>
<style>
/* Custom styles for the select dropdown */
.form-select {
    background-color: var(--dk-bg-secondary) !important;
    color: var(--dk-text) !important;
    border-color: var(--dk-border);
}

.form-select option {
    background-color: var(--dk-bg-secondary) !important;
    color: var(--dk-text) !important;
}

/* Ensure dropdown items are visible on hover */
.form-select option:hover,
.form-select option:focus,
.form-select option:active,
.form-select option:checked {
    background-color: var(--dk-bg-hover) !important;
    color: var(--dk-text) !important;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Example of AJAX form submission
    /*
    $('form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    window.location.href = '<?= base_url('dakoii/users') ?>';
                } else {
                    // Handle validation errors
                    let errorHtml = '<div class="alert alert-danger"><ul class="mb-0">';
                    $.each(response.errors, function(key, value) {
                        errorHtml += '<li>' + value + '</li>';
                    });
                    errorHtml += '</ul></div>';
                    $('.card-body').prepend(errorHtml);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
    */
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 