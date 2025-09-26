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
                    <h5 class="card-title mb-0">Implement Output Activity: <?= esc($activity['activity_title']) ?></h5>
                </div>
                <div class="card-body">
                    <!-- Show existing implementation data if exists -->
                    <?php if ($implementationData): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This output activity has already been implemented. You can view the details below or update the implementation.
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Current Implementation</h6>
                                <div class="card">
                                    <div class="card-body">
                                        <?= $this->include('activities/implementation/outputs_details') ?>
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

                        <h6 class="fw-bold mb-3">Output Implementation</h6>

                        <!-- GPS Coordinates -->
                        <div class="mb-3">
                            <label class="form-label">GPS Coordinates <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="gps_coordinates" required 
                                   placeholder="e.g., -1.2921, 36.8219" 
                                   value="<?= old('gps_coordinates', $implementationData['gps_coordinates'] ?? '') ?>">
                            <div class="form-text">Enter the GPS coordinates where the outputs were delivered</div>
                        </div>

                        <!-- Outputs Section -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Outputs Delivered</label>
                            <div id="outputsContainer">
                                <?php 
                                $existingOutputs = ($implementationData['outputs'] ?? []);
                                if (empty($existingOutputs)) {
                                    $existingOutputs = [['name' => '', 'quantity' => '', 'unit' => '', 'description' => '']];
                                }
                                ?>
                                <?php foreach ($existingOutputs as $index => $output): ?>
                                <div class="output-item border rounded p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Output Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="output_name[]" required
                                                   placeholder="e.g., Training Manual" 
                                                   value="<?= esc($output['name'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Quantity</label>
                                            <input type="text" class="form-control" name="output_quantity[]"
                                                   placeholder="e.g., 100" 
                                                   value="<?= esc($output['quantity'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Unit</label>
                                            <input type="text" class="form-control" name="output_unit[]"
                                                   placeholder="e.g., copies" 
                                                   value="<?= esc($output['unit'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="output_description[]" rows="1"
                                                      placeholder="Brief description"><?= esc($output['description'] ?? '') ?></textarea>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-output" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addOutput">
                                <i class="fas fa-plus"></i> Add Another Output
                            </button>
                        </div>

                        <!-- Beneficiaries Section -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Beneficiaries</label>
                            <div id="beneficiariesContainer">
                                <?php 
                                $existingBeneficiaries = ($implementationData['beneficiaries'] ?? []);
                                if (empty($existingBeneficiaries)) {
                                    $existingBeneficiaries = [['name' => '', 'organization' => '', 'contact' => '', 'type' => 'individual']];
                                }
                                ?>
                                <?php foreach ($existingBeneficiaries as $index => $beneficiary): ?>
                                <div class="beneficiary-item border rounded p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="beneficiary_name[]" required
                                                   placeholder="Beneficiary name" 
                                                   value="<?= esc($beneficiary['name'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Organization</label>
                                            <input type="text" class="form-control" name="beneficiary_organization[]"
                                                   placeholder="Organization/Group" 
                                                   value="<?= esc($beneficiary['organization'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Contact</label>
                                            <input type="text" class="form-control" name="beneficiary_contact[]"
                                                   placeholder="Phone/Email" 
                                                   value="<?= esc($beneficiary['contact'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Type</label>
                                            <select class="form-control" name="beneficiary_type[]">
                                                <option value="individual" <?= ($beneficiary['type'] ?? '') === 'individual' ? 'selected' : '' ?>>Individual</option>
                                                <option value="group" <?= ($beneficiary['type'] ?? '') === 'group' ? 'selected' : '' ?>>Group</option>
                                                <option value="organization" <?= ($beneficiary['type'] ?? '') === 'organization' ? 'selected' : '' ?>>Organization</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-beneficiary" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addBeneficiary">
                                <i class="fas fa-plus"></i> Add Another Beneficiary
                            </button>
                        </div>

                        <!-- Total Value -->
                        <div class="mb-3">
                            <label class="form-label">Total Value (KES)</label>
                            <input type="number" step="0.01" class="form-control" name="total_value"
                                   placeholder="e.g., 50000.00" 
                                   value="<?= old('total_value', $implementationData['total_value'] ?? '') ?>">
                            <div class="form-text">Enter the total monetary value of the outputs delivered</div>
                        </div>

                        <!-- Existing Output Images -->
                        <?php if (!empty($implementationData['output_images'])): ?>
                        <div class="mb-3">
                            <label class="form-label">Existing Output Images (check to keep):</label>
                            <div class="row">
                                <?php foreach ($implementationData['output_images'] as $index => $image): ?>
                                <div class="col-md-3 mb-2">
                                    <div class="card">
                                        <img src="<?= base_url($image) ?>" class="card-img-top" style="height: 100px; object-fit: cover;" alt="Output Image">
                                        <div class="card-body p-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="keep_output_images[]" value="<?= esc($image) ?>" id="keepOutputImage<?= $index ?>" checked>
                                                <label class="form-check-label" for="keepOutputImage<?= $index ?>">
                                                    <small>Keep this image</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Upload New Output Images -->
                        <div class="mb-3">
                            <label class="form-label">Upload New Output Images</label>
                            <input type="file" class="form-control" name="output_images[]" multiple accept="image/*">
                            <div class="form-text">Upload additional images showing the outputs delivered (JPG, PNG, GIF)</div>
                        </div>

                        <!-- Existing Output Files -->
                        <?php if (!empty($implementationData['output_files'])): ?>
                        <div class="mb-3">
                            <label class="form-label">Existing Output Files (check to keep):</label>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="50">Keep</th>
                                            <th>Description</th>
                                            <th>Original Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($implementationData['output_files'] as $index => $file): ?>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="keep_output_files[]" value="<?= $index ?>" id="keepOutputFile<?= $index ?>" checked>
                                                </div>
                                            </td>
                                            <td><?= esc($file['filename'] ?? 'Output File') ?></td>
                                            <td><?= esc($file['original_name'] ?? 'N/A') ?></td>
                                            <td>
                                                <a href="<?= base_url($file['path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Upload New Output Files -->
                        <div class="mb-3">
                            <label class="form-label">Upload New Output Files</label>
                            <input type="file" class="form-control" name="output_files[]" multiple>
                            <div class="form-text">Upload additional files related to the outputs (PDF, DOC, XLS, etc.)</div>

                            <!-- File descriptions -->
                            <div class="mt-2">
                                <label class="form-label">File Descriptions (optional)</label>
                                <input type="text" class="form-control" name="file_descriptions[]" placeholder="Description for file 1">
                                <small class="form-text text-muted">Add descriptions for each file you upload</small>
                            </div>
                        </div>

                        <!-- Signing Sheet -->
                        <div class="mb-3">
                            <label class="form-label">Signing Sheet</label>
                            <input type="file" class="form-control" name="signing_sheet" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text">Upload the signed attendance/delivery sheet</div>
                            
                            <?php if (!empty($implementationData['signing_sheet_filepath'])): ?>
                            <div class="mt-2">
                                <small class="text-muted">Current signing sheet: </small>
                                <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> Download Current
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Remarks -->
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea class="form-control" name="remarks" rows="3" 
                                      placeholder="Any additional notes or observations"><?= old('remarks', $implementationData['remarks'] ?? '') ?></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
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
    // Add/Remove Outputs functionality
    const outputsContainer = document.getElementById('outputsContainer');
    const addOutputBtn = document.getElementById('addOutput');

    addOutputBtn.addEventListener('click', function() {
        const outputItem = document.createElement('div');
        outputItem.className = 'output-item border rounded p-3 mb-3';
        outputItem.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Output Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="output_name[]" required placeholder="e.g., Training Manual">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity</label>
                    <input type="text" class="form-control" name="output_quantity[]" placeholder="e.g., 100">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit</label>
                    <input type="text" class="form-control" name="output_unit[]" placeholder="e.g., copies">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="output_description[]" rows="1" placeholder="Brief description"></textarea>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-output">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        outputsContainer.appendChild(outputItem);
        updateRemoveButtons('outputsContainer', '.output-item', '.remove-output');
    });

    // Add/Remove Beneficiaries functionality
    const beneficiariesContainer = document.getElementById('beneficiariesContainer');
    const addBeneficiaryBtn = document.getElementById('addBeneficiary');

    addBeneficiaryBtn.addEventListener('click', function() {
        const beneficiaryItem = document.createElement('div');
        beneficiaryItem.className = 'beneficiary-item border rounded p-3 mb-3';
        beneficiaryItem.innerHTML = `
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="beneficiary_name[]" required placeholder="Beneficiary name">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Organization</label>
                    <input type="text" class="form-control" name="beneficiary_organization[]" placeholder="Organization/Group">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Contact</label>
                    <input type="text" class="form-control" name="beneficiary_contact[]" placeholder="Phone/Email">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Type</label>
                    <select class="form-control" name="beneficiary_type[]">
                        <option value="individual">Individual</option>
                        <option value="group">Group</option>
                        <option value="organization">Organization</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-beneficiary">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        beneficiariesContainer.appendChild(beneficiaryItem);
        updateRemoveButtons('beneficiariesContainer', '.beneficiary-item', '.remove-beneficiary');
    });

    // Remove button functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-output')) {
            e.target.closest('.output-item').remove();
            updateRemoveButtons('outputsContainer', '.output-item', '.remove-output');
        }
        if (e.target.closest('.remove-beneficiary')) {
            e.target.closest('.beneficiary-item').remove();
            updateRemoveButtons('beneficiariesContainer', '.beneficiary-item', '.remove-beneficiary');
        }
    });

    // Update remove button visibility
    function updateRemoveButtons(containerSelector, itemSelector, removeSelector) {
        const items = document.querySelectorAll(`#${containerSelector} ${itemSelector}`);
        items.forEach((item) => {
            const removeBtn = item.querySelector(removeSelector);
            if (removeBtn) {
                removeBtn.style.display = items.length > 1 ? 'inline-block' : 'none';
            }
        });
    }

    // Initialize remove button visibility
    updateRemoveButtons('outputsContainer', '.output-item', '.remove-output');
    updateRemoveButtons('beneficiariesContainer', '.beneficiary-item', '.remove-beneficiary');
});
</script>

<?= $this->endSection() ?>
