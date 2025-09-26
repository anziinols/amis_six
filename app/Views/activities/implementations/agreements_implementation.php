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
                    <h5 class="card-title mb-0">Implement Agreement Activity: <?= esc($activity['activity_title']) ?></h5>
                </div>
                <div class="card-body">
                    <!-- Show existing implementation data if exists -->
                    <?php if ($implementationData): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This agreement activity has already been implemented. You can view the details below or update the implementation.
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Current Implementation</h6>
                                <div class="card">
                                    <div class="card-body">
                                        <?= $this->include('activities/implementation/agreements_details') ?>
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

                        <h6 class="fw-bold mb-3">Agreement Implementation</h6>

                        <!-- Agreement Basic Information -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Agreement Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" required value="<?= old('title', $implementationData['title'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Agreement Type</label>
                                <select class="form-control" name="agreement_type">
                                    <option value="">Select Agreement Type</option>
                                    <option value="MOU" <?= old('agreement_type', $implementationData['agreement_type'] ?? '') === 'MOU' ? 'selected' : '' ?>>Memorandum of Understanding (MOU)</option>
                                    <option value="Contract" <?= old('agreement_type', $implementationData['agreement_type'] ?? '') === 'Contract' ? 'selected' : '' ?>>Contract</option>
                                    <option value="Partnership Agreement" <?= old('agreement_type', $implementationData['agreement_type'] ?? '') === 'Partnership Agreement' ? 'selected' : '' ?>>Partnership Agreement</option>
                                    <option value="Service Agreement" <?= old('agreement_type', $implementationData['agreement_type'] ?? '') === 'Service Agreement' ? 'selected' : '' ?>>Service Agreement</option>
                                    <option value="Cooperation Agreement" <?= old('agreement_type', $implementationData['agreement_type'] ?? '') === 'Cooperation Agreement' ? 'selected' : '' ?>>Cooperation Agreement</option>
                                    <option value="Other" <?= old('agreement_type', $implementationData['agreement_type'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Effective Date <span class="text-danger">*</span></label>
                                <?php
                                $effectiveDateValue = old('effective_date');
                                if (!$effectiveDateValue && !empty($implementationData['effective_date'])) {
                                    $effectiveDateValue = date('Y-m-d', strtotime($implementationData['effective_date']));
                                }
                                ?>
                                <input type="date" class="form-control" name="effective_date" required value="<?= $effectiveDateValue ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Expiry Date</label>
                                <?php
                                $expiryDateValue = old('expiry_date');
                                if (!$expiryDateValue && !empty($implementationData['expiry_date'])) {
                                    $expiryDateValue = date('Y-m-d', strtotime($implementationData['expiry_date']));
                                }
                                ?>
                                <input type="date" class="form-control" name="expiry_date" value="<?= $expiryDateValue ?>">
                                <div class="form-text">Leave blank if agreement has no expiry date</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status">
                                <option value="draft" <?= old('status', $implementationData['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="active" <?= old('status', $implementationData['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="expired" <?= old('status', $implementationData['status'] ?? '') === 'expired' ? 'selected' : '' ?>>Expired</option>
                                <option value="terminated" <?= old('status', $implementationData['status'] ?? '') === 'terminated' ? 'selected' : '' ?>>Terminated</option>
                                <option value="archived" <?= old('status', $implementationData['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archived</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Agreement Description</label>
                            <textarea class="form-control" name="description" rows="4" placeholder="Detailed description of the agreement"><?= old('description', $implementationData['description'] ?? '') ?></textarea>
                        </div>

                        <!-- Parties Section -->
                        <div class="mb-3">
                            <label class="form-label">Parties Involved</label>
                            <div id="partiesContainer">
                                <?php
                                $existingParties = old('party_name') ? array_map(null,
                                    old('party_name'), old('party_organization'), old('party_role'), old('party_contact')
                                ) : ($implementationData['parties'] ?? []);

                                if (empty($existingParties)):
                                    $existingParties = [['', '', '', '']]; // Add one empty row
                                endif;
                                ?>

                                <?php foreach ($existingParties as $index => $party): ?>
                                <div class="party-item border p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="party_name[]" placeholder="Party name" value="<?= esc($party['name'] ?? $party[0] ?? '') ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Organization</label>
                                            <input type="text" class="form-control" name="party_organization[]" placeholder="Organization" value="<?= esc($party['organization'] ?? $party[1] ?? '') ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Role</label>
                                            <input type="text" class="form-control" name="party_role[]" placeholder="e.g., Signatory, Witness" value="<?= esc($party['role'] ?? $party[2] ?? '') ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Contact</label>
                                            <input type="text" class="form-control" name="party_contact[]" placeholder="Email or phone" value="<?= esc($party['contact'] ?? $party[3] ?? '') ?>">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-party" style="display: none;">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" id="addParty" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> Add Another Party
                            </button>
                        </div>



                        <!-- Agreement Documents Section -->
                        <div class="mb-3">
                            <label class="form-label">Agreement Documents</label>

                            <!-- Display existing attachments -->
                            <?php if (!empty($implementationData['attachments'])): ?>
                            <div class="mb-3">
                                <strong>Current Documents:</strong>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>File Name</th>
                                                <th>Description</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($implementationData['attachments'] as $index => $attachment): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= esc($attachment['filename'] ?? $attachment['original_name'] ?? 'Unknown') ?></td>
                                                <td><?= esc($attachment['description'] ?? 'N/A') ?></td>
                                                <td>
                                                    <a href="<?= base_url($attachment['path'] ?? '') ?>" target="_blank" class="btn btn-sm btn-outline-primary">
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

                            <!-- Upload new documents -->
                            <div id="documentsContainer">
                                <div class="document-item border p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label class="form-label">Agreement Document</label>
                                            <input type="file" class="form-control" name="agreement_documents[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Description</label>
                                            <input type="text" class="form-control" name="attachment_descriptions[]" placeholder="Document description">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-document" style="display: none;">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                            <button type="button" id="addDocument" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> Add Another Document
                            </button>
                            <div class="form-text">Upload agreement documents, annexes, or related files</div>
                        </div>

                        <!-- Remarks -->
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea class="form-control" name="remarks" rows="3" placeholder="Any additional remarks or notes"><?= old('remarks', $implementationData['remarks'] ?? '') ?></textarea>
                        </div>

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
    // Update remove buttons visibility for parties
    function updatePartyRemoveButtons() {
        const partyItems = document.querySelectorAll('.party-item');
        partyItems.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-party');
            if (removeBtn) {
                removeBtn.style.display = partyItems.length > 1 ? 'inline-block' : 'none';
            }
        });
    }

    // Update remove buttons visibility for documents
    function updateDocumentRemoveButtons() {
        const documentItems = document.querySelectorAll('.document-item');
        documentItems.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-document');
            if (removeBtn) {
                removeBtn.style.display = documentItems.length > 1 ? 'inline-block' : 'none';
            }
        });
    }

    // Initialize remove buttons
    updatePartyRemoveButtons();
    updateDocumentRemoveButtons();

    // Add party functionality
    document.getElementById('addParty').addEventListener('click', function() {
        const container = document.getElementById('partiesContainer');
        const newItem = document.createElement('div');
        newItem.className = 'party-item border p-3 mb-3';
        newItem.innerHTML = `
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="party_name[]" placeholder="Party name">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Organization</label>
                    <input type="text" class="form-control" name="party_organization[]" placeholder="Organization">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Role</label>
                    <input type="text" class="form-control" name="party_role[]" placeholder="e.g., Signatory, Witness">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Contact</label>
                    <input type="text" class="form-control" name="party_contact[]" placeholder="Email or phone">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-danger mt-2 remove-party">
                <i class="fas fa-trash"></i> Remove
            </button>
        `;
        container.appendChild(newItem);
        updatePartyRemoveButtons();
    });

    // Remove party functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-party') || e.target.closest('.remove-party')) {
            const item = e.target.closest('.party-item');
            if (item) {
                item.remove();
                updatePartyRemoveButtons();
            }
        }
    });

    // Add document functionality
    document.getElementById('addDocument').addEventListener('click', function() {
        const container = document.getElementById('documentsContainer');
        const newItem = document.createElement('div');
        newItem.className = 'document-item border p-3 mb-3';
        newItem.innerHTML = `
            <div class="row">
                <div class="col-md-8">
                    <label class="form-label">Agreement Document</label>
                    <input type="file" class="form-control" name="agreement_documents[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="attachment_descriptions[]" placeholder="Document description">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-danger mt-2 remove-document">
                <i class="fas fa-trash"></i> Remove
            </button>
        `;
        container.appendChild(newItem);
        updateDocumentRemoveButtons();
    });

    // Remove document functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-document') || e.target.closest('.remove-document')) {
            const item = e.target.closest('.document-item');
            if (item) {
                item.remove();
                updateDocumentRemoveButtons();
            }
        }
    });
});
</script>

<?= $this->endSection() ?>
