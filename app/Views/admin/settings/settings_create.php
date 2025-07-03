<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Create New Setting</h5>
                <a href="<?= base_url('admin/org-settings') ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Settings
                </a>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/org-settings') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="settings_code" class="form-label">Settings Code</label>
                        <input type="text" class="form-control" id="settings_code" name="settings_code" required 
                            value="<?= old('settings_code') ?>" placeholder="Enter a unique code (e.g., app_config, email_settings)">
                        <small class="text-muted">This code must be unique and will be used to retrieve settings programmatically.</small>
                        <?php if (session()->has('errors') && isset(session('errors')['settings_code'])): ?>
                            <div class="text-danger"><?= session('errors')['settings_code'] ?></div>
                        <?php endif ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="settings_name" class="form-label">Settings Name</label>
                        <input type="text" class="form-control" id="settings_name" name="settings_name" required 
                            value="<?= old('settings_name') ?>" placeholder="Enter a descriptive name">
                        <small class="text-muted">A human-readable name for these settings.</small>
                        <?php if (session()->has('errors') && isset(session('errors')['settings_name'])): ?>
                            <div class="text-danger"><?= session('errors')['settings_name'] ?></div>
                        <?php endif ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="settings" class="form-label">Settings Content</label>
                        <textarea class="form-control" id="settings" name="settings" rows="10" required 
                            placeholder="Enter JSON content or settings text"><?= old('settings') ?></textarea>
                        <small class="text-muted">Enter settings in JSON format or as plain text.</small>
                        <?php if (session()->has('errors') && isset(session('errors')['settings'])): ?>
                            <div class="text-danger"><?= session('errors')['settings'] ?></div>
                        <?php endif ?>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="reset" class="btn btn-light">Reset</button>
                        <button type="submit" class="btn btn-primary">Save Setting</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Optional: Add JSON validation if needed
    document.getElementById('settings').addEventListener('blur', function() {
        try {
            // Try to parse as JSON, but don't require it
            const value = this.value.trim();
            if (value.startsWith('{') || value.startsWith('[')) {
                JSON.parse(value);
            }
        } catch (e) {
            // If it fails parsing, show an error message
            alert('Warning: The settings content is not valid JSON. Please check your format.');
        }
    });
</script>
<?= $this->endSection() ?> 