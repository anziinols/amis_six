<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="mb-3">
        <a href="<?= base_url('activities/' . $activity['id']) ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Activity Details
        </a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Implement Infrastructure Activity: <?= esc($activity['activity_title']) ?></h5>
                </div>
                <div class="card-body">
                    <!-- Show existing implementation data if exists -->
                    <?php if ($implementationData): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This infrastructure activity has already been implemented. You can view the details below or update the implementation.
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Current Implementation</h6>
                                <div class="card">
                                    <div class="card-body">
                                        <?= $this->include('activities/implementation/infrastructures_details') ?>
                                        <div class="text-muted">
                                            <small>Implemented on: <?= date('d M Y H:i', strtotime($implementationData['created_at'])) ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Implementation Form -->
                    <form action="<?= base_url('activities/' . $activity['id'] . '/save-implementation') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <h6 class="fw-bold mb-3">Infrastructure Implementation</h6>

                        <!-- Infrastructure Description -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Infrastructure Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="infrastructure" rows="3" required><?= old('infrastructure', $implementationData['infrastructure'] ?? '') ?></textarea>
                                <div class="form-text">Provide detailed description of the infrastructure implemented</div>
                            </div>
                        </div>

                        <!-- GPS Coordinates -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">GPS Coordinates</label>
                                <input type="text" class="form-control" name="gps_coordinates" 
                                       value="<?= old('gps_coordinates', $implementationData['gps_coordinates'] ?? '') ?>"
                                       placeholder="e.g., -1.2921, 36.8219">
                                <div class="form-text">Optional: Latitude, Longitude coordinates</div>
                            </div>
                        </div>

                        <!-- Infrastructure Images -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Infrastructure Images</label>
                                <input type="file" class="form-control" name="infrastructure_images[]" multiple accept="image/*">
                                <div class="form-text">Upload multiple images of the infrastructure (JPG, PNG, GIF)</div>
                                
                                <!-- Show existing images -->
                                <?php if (!empty($implementationData['infrastructure_images'])): ?>
                                <div class="mt-2">
                                    <small class="text-muted">Existing images:</small>
                                    <div class="row">
                                        <?php foreach ($implementationData['infrastructure_images'] as $index => $image): ?>
                                        <div class="col-md-2 mb-2">
                                            <img src="<?= base_url($image) ?>" class="img-thumbnail" style="height: 80px; object-fit: cover;">
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Infrastructure Files -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Infrastructure Documents</label>
                                <div id="infrastructureFilesContainer">
                                    <div class="infrastructure-file-item mb-2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="file" class="form-control" name="infrastructure_files[]" accept=".pdf,.doc,.docx,.xls,.xlsx">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="file_descriptions[]" placeholder="File description">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-outline-danger remove-file-btn" style="display: none;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="addInfrastructureFileBtn">
                                    <i class="fas fa-plus"></i> Add Another File
                                </button>
                                
                                <!-- Show existing files -->
                                <?php if (!empty($implementationData['infrastructure_files'])): ?>
                                <div class="mt-2">
                                    <small class="text-muted">Existing files:</small>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($implementationData['infrastructure_files'] as $file): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= esc($file['filename']) ?>
                                            <a href="<?= base_url($file['path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Signing Sheet -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Signing Sheet</label>
                                <input type="file" class="form-control" name="signing_sheet" accept=".pdf,.jpg,.jpeg,.png">
                                <div class="form-text">Upload signed attendance/completion sheet</div>
                                
                                <!-- Show existing signing sheet -->
                                <?php if (!empty($implementationData['signing_scheet_filepath'])): ?>
                                <div class="mt-2">
                                    <small class="text-muted">Current signing sheet:</small>
                                    <a href="<?= base_url($implementationData['signing_scheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Infrastructure Implementation
                                </button>
                                <a href="<?= base_url('activities/' . $activity['id']) ?>" class="btn btn-secondary ms-2">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add infrastructure file functionality
    const addFileBtn = document.getElementById('addInfrastructureFileBtn');
    const filesContainer = document.getElementById('infrastructureFilesContainer');

    addFileBtn.addEventListener('click', function() {
        const fileItem = document.createElement('div');
        fileItem.className = 'infrastructure-file-item mb-2';
        fileItem.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <input type="file" class="form-control" name="infrastructure_files[]" accept=".pdf,.doc,.docx,.xls,.xlsx">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="file_descriptions[]" placeholder="File description">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger remove-file-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        filesContainer.appendChild(fileItem);
        updateRemoveFileButtons();
    });

    // Remove file functionality
    filesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-file-btn')) {
            e.target.closest('.infrastructure-file-item').remove();
            updateRemoveFileButtons();
        }
    });

    function updateRemoveFileButtons() {
        const fileItems = document.querySelectorAll('.infrastructure-file-item');
        fileItems.forEach((item) => {
            const removeBtn = item.querySelector('.remove-file-btn');
            if (removeBtn) {
                removeBtn.style.display = fileItems.length > 1 ? 'inline-block' : 'none';
            }
        });
    }

    // Initialize remove buttons
    updateRemoveFileButtons();
});
</script>

<?= $this->endSection() ?>
