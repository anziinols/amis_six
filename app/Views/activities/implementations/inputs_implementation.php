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
                    <h5 class="card-title mb-0">Implement Input Activity: <?= esc($activity['activity_title']) ?></h5>
                </div>
                <div class="card-body">
                    <!-- Show existing implementation data if exists -->
                    <?php if ($implementationData): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This input activity has already been implemented. You can view the details below or update the implementation.
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Current Implementation</h6>
                                <div class="card">
                                    <div class="card-body">
                                        <?= $this->include('activities/implementation/inputs_details') ?>
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

                        <h6 class="fw-bold mb-3">Input Implementation</h6>

                        <!-- GPS Coordinates -->
                        <div class="mb-3">
                            <label class="form-label">GPS Coordinates <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="gps_coordinates" required 
                                   placeholder="e.g., -1.2921, 36.8219" 
                                   value="<?= old('gps_coordinates', $implementationData['gps_coordinates'] ?? '') ?>">
                            <div class="form-text">GPS coordinates of the input delivery/distribution location</div>
                        </div>

                        <!-- Dynamic Input Items Section -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Input Items</h6>
                            <div id="inputItemsContainer">
                                <?php if (!empty($implementationData['inputs'])): ?>
                                    <?php foreach ($implementationData['inputs'] as $index => $input): ?>
                                        <div class="input-item-row border rounded p-3 mb-3">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label class="form-label">Input Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="input_name[]" 
                                                           value="<?= esc($input['name']) ?>" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Quantity</label>
                                                    <input type="text" class="form-control" name="input_quantity[]" 
                                                           value="<?= esc($input['quantity']) ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Unit</label>
                                                    <input type="text" class="form-control" name="input_unit[]" 
                                                           value="<?= esc($input['unit']) ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Remarks</label>
                                                    <input type="text" class="form-control" name="input_remarks[]" 
                                                           value="<?= esc($input['remarks']) ?>">
                                                </div>
                                                <div class="col-md-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-input-item">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="input-item-row border rounded p-3 mb-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">Input Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="input_name[]" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Quantity</label>
                                                <input type="text" class="form-control" name="input_quantity[]">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Unit</label>
                                                <input type="text" class="form-control" name="input_unit[]">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Remarks</label>
                                                <input type="text" class="form-control" name="input_remarks[]">
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger btn-sm remove-input-item">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addInputItem">
                                <i class="fas fa-plus"></i> Add Input Item
                            </button>
                        </div>

                        <!-- Input Images Upload -->
                        <div class="mb-3">
                            <label class="form-label">Input Images</label>
                            <input type="file" class="form-control" name="input_images[]" multiple accept="image/*">
                            <div class="form-text">Upload photos of the inputs (JPG, PNG, GIF - Max 5MB each)</div>
                        </div>

                        <!-- Input Files Upload -->
                        <div class="mb-3">
                            <label class="form-label">Input Documents</label>
                            <input type="file" class="form-control" name="input_files[]" multiple>
                            <div class="form-text">Upload related documents (PDF, DOC, XLS - Max 5MB each)</div>
                        </div>

                        <!-- Signing Sheet Upload -->
                        <div class="mb-3">
                            <label class="form-label">Signing Sheet</label>
                            <input type="file" class="form-control" name="signing_sheet" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <div class="form-text">Upload the signed attendance/distribution sheet</div>
                        </div>

                        <!-- Remarks -->
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea class="form-control" name="remarks" rows="3" 
                                      placeholder="Additional notes or observations"><?= old('remarks', $implementationData['remarks'] ?? '') ?></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('activities/' . $activity['id']) ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Implementation
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
    // Add input item functionality
    document.getElementById('addInputItem').addEventListener('click', function() {
        const container = document.getElementById('inputItemsContainer');
        const newRow = document.createElement('div');
        newRow.className = 'input-item-row border rounded p-3 mb-3';
        newRow.innerHTML = `
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Input Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="input_name[]" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity</label>
                    <input type="text" class="form-control" name="input_quantity[]">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit</label>
                    <input type="text" class="form-control" name="input_unit[]">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Remarks</label>
                    <input type="text" class="form-control" name="input_remarks[]">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-input-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newRow);
        updateRemoveButtons();
    });

    // Remove input item functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-input-item')) {
            e.target.closest('.input-item-row').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const items = document.querySelectorAll('.input-item-row');
        items.forEach((item) => {
            const removeBtn = item.querySelector('.remove-input-item');
            if (removeBtn) {
                removeBtn.style.display = items.length > 1 ? 'inline-block' : 'none';
            }
        });
    }

    // Initialize remove buttons
    updateRemoveButtons();
});
</script>

<?= $this->endSection() ?>
