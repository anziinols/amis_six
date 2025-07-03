<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Edit Setting: <?= esc($setting['settings_name']) ?></h5>
                <a href="<?= base_url('admin/org-settings') ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Settings
                </a>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/org-settings/' . $setting['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label for="settings_code" class="form-label">Settings Code</label>
                        <input type="text" class="form-control" id="settings_code" value="<?= esc($setting['settings_code']) ?>" readonly disabled>
                        <small class="text-muted">The settings code cannot be changed.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="settings_name" class="form-label">Settings Name</label>
                        <input type="text" class="form-control" id="settings_name" name="settings_name" required 
                            value="<?= old('settings_name', esc($setting['settings_name'])) ?>">
                        <?php if (session()->has('errors') && isset(session('errors')['settings_name'])): ?>
                            <div class="text-danger"><?= session('errors')['settings_name'] ?></div>
                        <?php endif ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="settings" class="form-label">Settings Content</label>
                        <textarea class="form-control" id="settings" name="settings" rows="10" required><?= old('settings', esc($setting['settings'])) ?></textarea>
                        <small class="text-muted">Enter settings in JSON format or as plain text.</small>
                        <?php if (session()->has('errors') && isset(session('errors')['settings'])): ?>
                            <div class="text-danger"><?= session('errors')['settings'] ?></div>
                        <?php endif ?>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="<?= base_url('admin/org-settings') ?>" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Setting</button>
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