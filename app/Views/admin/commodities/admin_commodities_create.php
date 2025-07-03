<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <a href="<?= base_url('admin/commodities') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Commodities
            </a>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('admin/commodities') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="commodity_code" class="form-label">Commodity Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="commodity_code" name="commodity_code" value="<?= old('commodity_code') ?>" required maxlength="50" placeholder="e.g., RICE001">
                            <div class="form-text">Unique identifier for the commodity (max 50 characters)</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="commodity_name" class="form-label">Commodity Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="commodity_name" name="commodity_name" value="<?= old('commodity_name') ?>" required maxlength="255" placeholder="e.g., Rice">
                            <div class="form-text">Full name of the commodity (max 255 characters)</div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="commodity_color_code" class="form-label">Color Code</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="commodity_color_code" name="commodity_color_code" value="<?= old('commodity_color_code', '#007bff') ?>" title="Choose color">
                                <input type="text" class="form-control" id="color_text" value="<?= old('commodity_color_code', '#007bff') ?>" maxlength="10" placeholder="#007bff">
                            </div>
                            <div class="form-text">Color to represent this commodity in charts and displays</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="commodity_icon_file" class="form-label">Icon Image</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="commodity_icon_file" name="commodity_icon_file" accept="image/*">
                                <label class="input-group-text" for="commodity_icon_file">
                                    <i class="fas fa-upload"></i>
                                </label>
                            </div>
                            <div class="form-text">Upload an image file (optional)</div>
                            <div id="icon_preview_container" class="mt-2" style="display: none;">
                                <img id="icon_preview" src="" alt="Icon Preview" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Commodity
                    </button>
                    <a href="<?= base_url('admin/commodities') ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Sync color picker with text input
    document.getElementById('commodity_color_code').addEventListener('input', function() {
        document.getElementById('color_text').value = this.value;
    });

    document.getElementById('color_text').addEventListener('input', function() {
        const colorValue = this.value;
        if (/^#[0-9A-F]{6}$/i.test(colorValue)) {
            document.getElementById('commodity_color_code').value = colorValue;
        }
    });

    // Update icon preview for file upload
    document.getElementById('commodity_icon_file').addEventListener('change', function() {
        const file = this.files[0];
        const previewContainer = document.getElementById('icon_preview_container');
        const previewImg = document.getElementById('icon_preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    });
</script>
<?= $this->endSection() ?>
