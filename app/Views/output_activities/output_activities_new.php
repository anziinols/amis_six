<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800"><?= esc($title) ?></h1>
                    <p class="mb-0 text-muted">Create a new output activity with deliverables</p>
                </div>
                <div>
                    <a href="<?= base_url('output-activities') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>

            <!-- Create Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>New Output Activity
                    </h5>
                </div>
                <div class="card-body">
                    <?= form_open_multipart('output-activities/create', ['class' => 'needs-validation', 'novalidate' => true]) ?>
                    
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="workplan_id" class="form-label">Workplan <span class="text-danger">*</span></label>
                            <select class="form-select" id="workplan_id" name="workplan_id" required>
                                <option value="">Select Workplan</option>
                                <?php foreach ($workplans as $workplan): ?>
                                    <option value="<?= $workplan['id'] ?>" <?= old('workplan_id') == $workplan['id'] ? 'selected' : '' ?>>
                                        <?= esc($workplan['title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please select a workplan.</div>
                            <?php if (isset($validation) && $validation->hasError('workplan_id')): ?>
                                <div class="text-danger"><?= $validation->getError('workplan_id') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="proposal_id" class="form-label">Related Proposal</label>
                            <select class="form-select" id="proposal_id" name="proposal_id">
                                <option value="">Select Proposal (Optional)</option>
                                <?php foreach ($proposals as $proposal): ?>
                                    <option value="<?= $proposal['id'] ?>" <?= old('proposal_id') == $proposal['id'] ? 'selected' : '' ?>>
                                        Proposal #<?= $proposal['id'] ?> - <?= esc($proposal['date_start'] ?? 'N/A') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="activity_id" class="form-label">Related Activity</label>
                            <select class="form-select" id="activity_id" name="activity_id">
                                <option value="">Select Activity (Optional)</option>
                                <?php foreach ($activities as $activity): ?>
                                    <option value="<?= $activity['id'] ?>" <?= old('activity_id') == $activity['id'] ? 'selected' : '' ?>>
                                        <?= esc($activity['title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="delivery_date" class="form-label">Delivery Date</label>
                            <input type="date" class="form-control" id="delivery_date" name="delivery_date" 
                                   value="<?= old('delivery_date') ?>">
                            <?php if (isset($validation) && $validation->hasError('delivery_date')): ?>
                                <div class="text-danger"><?= $validation->getError('delivery_date') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="delivery_location" class="form-label">Delivery Location</label>
                            <input type="text" class="form-control" id="delivery_location" name="delivery_location" 
                                   value="<?= old('delivery_location') ?>" placeholder="Enter delivery location">
                            <?php if (isset($validation) && $validation->hasError('delivery_location')): ?>
                                <div class="text-danger"><?= $validation->getError('delivery_location') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label for="total_value" class="form-label">Total Value (<?= CURRENCY_SYMBOL ?>)</label>
                            <input type="number" class="form-control" id="total_value" name="total_value"
                                   step="0.01" min="0" value="<?= old('total_value') ?>" placeholder="0.00">
                            <?php if (isset($validation) && $validation->hasError('total_value')): ?>
                                <div class="text-danger"><?= $validation->getError('total_value') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Outputs Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Output Items</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addOutputItem()">
                                <i class="fas fa-plus me-1"></i>Add Item
                            </button>
                        </div>
                        <div id="outputItems">
                            <div class="output-item border rounded p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Item Name</label>
                                        <input type="text" class="form-control" name="output_items[]" placeholder="Item name">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Description</label>
                                        <input type="text" class="form-control" name="output_descriptions[]" placeholder="Description">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Quantity</label>
                                        <input type="text" class="form-control" name="output_quantities[]" placeholder="Qty">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Unit</label>
                                        <input type="text" class="form-control" name="output_units[]" placeholder="Unit">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Action</label>
                                        <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeOutputItem(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <label class="form-label">Specifications</label>
                                        <textarea class="form-control" name="output_specifications[]" rows="2" placeholder="Technical specifications or additional details"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Beneficiaries Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Beneficiaries</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addBeneficiary()">
                                <i class="fas fa-plus me-1"></i>Add Beneficiary
                            </button>
                        </div>
                        <div id="beneficiaries">
                            <div class="beneficiary-item border rounded p-3 mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label">Organization/Group Name</label>
                                        <input type="text" class="form-control" name="beneficiary_names[]" placeholder="Organization name">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Contact Person</label>
                                        <input type="text" class="form-control" name="beneficiary_contacts[]" placeholder="Contact person">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="beneficiary_phones[]" placeholder="Phone number">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Members</label>
                                        <input type="number" class="form-control" name="beneficiary_members[]" min="0" placeholder="0">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Action</label>
                                        <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeBeneficiary(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- File Uploads -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="output_images" class="form-label">Output Images</label>
                            <input type="file" class="form-control" id="output_images" name="output_images[]" 
                                   multiple accept="image/*">
                            <small class="form-text text-muted">Upload images of the outputs (JPG, PNG, GIF)</small>
                        </div>
                        <div class="col-md-6">
                            <label for="output_files" class="form-label">Output Documents</label>
                            <input type="file" class="form-control" id="output_files" name="output_files[]" 
                                   multiple accept=".pdf,.doc,.docx,.xls,.xlsx">
                            <small class="form-text text-muted">Upload related documents (PDF, DOC, XLS)</small>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="signing_sheet" class="form-label">Signing Sheet</label>
                            <input type="file" class="form-control" id="signing_sheet" name="signing_sheet" 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small class="form-text text-muted">Upload delivery confirmation document</small>
                        </div>
                        <div class="col-md-6">
                            <label for="gps_coordinates" class="form-label">GPS Coordinates</label>
                            <input type="text" class="form-control" id="gps_coordinates" name="gps_coordinates" 
                                   value="<?= old('gps_coordinates') ?>" placeholder="e.g., -6.314993, 143.95555">
                            <small class="form-text text-muted">Format: latitude, longitude</small>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="mb-4">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="4" 
                                  placeholder="Additional notes or comments"><?= old('remarks') ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('remarks')): ?>
                            <div class="text-danger"><?= $validation->getError('remarks') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= base_url('output-activities') ?>" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Output Activity
                        </button>
                    </div>

                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function addOutputItem() {
    const outputItems = document.getElementById('outputItems');
    const newItem = document.createElement('div');
    newItem.className = 'output-item border rounded p-3 mb-3';
    newItem.innerHTML = `
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">Item Name</label>
                <input type="text" class="form-control" name="output_items[]" placeholder="Item name">
            </div>
            <div class="col-md-3">
                <label class="form-label">Description</label>
                <input type="text" class="form-control" name="output_descriptions[]" placeholder="Description">
            </div>
            <div class="col-md-2">
                <label class="form-label">Quantity</label>
                <input type="text" class="form-control" name="output_quantities[]" placeholder="Qty">
            </div>
            <div class="col-md-2">
                <label class="form-label">Unit</label>
                <input type="text" class="form-control" name="output_units[]" placeholder="Unit">
            </div>
            <div class="col-md-2">
                <label class="form-label">Action</label>
                <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeOutputItem(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <label class="form-label">Specifications</label>
                <textarea class="form-control" name="output_specifications[]" rows="2" placeholder="Technical specifications or additional details"></textarea>
            </div>
        </div>
    `;
    outputItems.appendChild(newItem);
}

function removeOutputItem(button) {
    const outputItems = document.getElementById('outputItems');
    if (outputItems.children.length > 1) {
        button.closest('.output-item').remove();
    } else {
        toastr.warning('At least one output item is required.');
    }
}

function addBeneficiary() {
    const beneficiaries = document.getElementById('beneficiaries');
    const newBeneficiary = document.createElement('div');
    newBeneficiary.className = 'beneficiary-item border rounded p-3 mb-3';
    newBeneficiary.innerHTML = `
        <div class="row">
            <div class="col-md-3">
                <label class="form-label">Organization/Group Name</label>
                <input type="text" class="form-control" name="beneficiary_names[]" placeholder="Organization name">
            </div>
            <div class="col-md-3">
                <label class="form-label">Contact Person</label>
                <input type="text" class="form-control" name="beneficiary_contacts[]" placeholder="Contact person">
            </div>
            <div class="col-md-2">
                <label class="form-label">Phone</label>
                <input type="text" class="form-control" name="beneficiary_phones[]" placeholder="Phone number">
            </div>
            <div class="col-md-2">
                <label class="form-label">Members</label>
                <input type="number" class="form-control" name="beneficiary_members[]" min="0" placeholder="0">
            </div>
            <div class="col-md-2">
                <label class="form-label">Action</label>
                <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeBeneficiary(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    beneficiaries.appendChild(newBeneficiary);
}

function removeBeneficiary(button) {
    const beneficiaries = document.getElementById('beneficiaries');
    if (beneficiaries.children.length > 1) {
        button.closest('.beneficiary-item').remove();
    } else {
        toastr.warning('At least one beneficiary is required.');
    }
}

// Display flash messages
<?php if (session()->getFlashdata('success')): ?>
    toastr.success('<?= session()->getFlashdata('success') ?>');
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    toastr.error('<?= session()->getFlashdata('error') ?>');
<?php endif; ?>

<?php if (session()->getFlashdata('errors')): ?>
    <?php foreach (session()->getFlashdata('errors') as $error): ?>
        toastr.error('<?= $error ?>');
    <?php endforeach; ?>
<?php endif; ?>
</script>
