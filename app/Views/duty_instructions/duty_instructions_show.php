<?= $this->extend('templates/system_template') ?>

<?= $this->section('head') ?>
<style>
/* Mobile responsive styles for Instruction Items table */
@media (max-width: 767px) {
    /* Hide table headers on mobile */
    #itemsTable thead {
        display: none;
    }
    
    /* Stack table rows as cards on mobile */
    #itemsTable, #itemsTable tbody, #itemsTable tr, #itemsTable td {
        display: block;
        width: 100%;
    }
    
    #itemsTable tr {
        border: 1px solid #ddd;
        border-radius: 8px;
        margin-bottom: 15px;
        padding: 15px;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    #itemsTable td {
        border: none;
        padding: 8px 0;
        text-align: left;
        position: relative;
        padding-left: 50%;
        word-wrap: break-word;
    }
    
    /* Add labels before content on mobile */
    #itemsTable td:before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 45%;
        font-weight: bold;
        color: #333;
        padding-right: 10px;
    }
    
    #itemsTable td:nth-child(1):before {
        content: "Item #: ";
    }
    
    #itemsTable td:nth-child(2):before {
        content: "Instruction: ";
    }
    
    #itemsTable td:nth-child(3):before {
        content: "Status: ";
    }
    
    #itemsTable td:nth-child(4):before {
        content: "Remarks: ";
    }
    
    #itemsTable td:nth-child(5):before {
        content: "Actions: ";
    }
    
    /* Special styling for form inputs in mobile view */
    #itemsTable td input,
    #itemsTable td textarea,
    #itemsTable td select {
        width: 100% !important;
        max-width: 100%;
        box-sizing: border-box;
    }
    
    /* Action buttons styling for mobile */
    #itemsTable td:nth-child(5) {
        padding-left: 0;
    }
    
    #itemsTable td:nth-child(5):before {
        display: none;
    }
    
    #itemsTable td .btn {
        margin: 2px 5px 2px 0;
        font-size: 0.9rem;
        padding: 8px 16px;
    }
    
    /* No items row styling */
    #itemsTable tr#noItemsRow td {
        padding-left: 0;
        text-align: center;
    }
    
    #itemsTable tr#noItemsRow td:before {
        display: none;
    }
    
    /* Add item row special styling */
    #itemsTable tr#addItemRow {
        background-color: #f8f9fa;
        border-color: #28a745;
    }
}

/* Tablet responsive adjustments */
@media (max-width: 991px) and (min-width: 768px) {
    #itemsTable td input,
    #itemsTable td textarea,
    #itemsTable td select {
        font-size: 0.9rem;
    }
    
    #itemsTable td .btn {
        font-size: 0.9rem;
        padding: 8px 14px;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?= esc($duty_instruction['duty_instruction_title']) ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('duty-instructions') ?>">Duty Instructions</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= esc($duty_instruction['duty_instruction_number']) ?></li>
                </ol>
            </nav>
        </div>
        <div>
            <?php if (!isset($hasMyActivitiesLinks) || !$hasMyActivitiesLinks): ?>
            <a href="<?= base_url('duty-instructions/' . $duty_instruction['id'] . '/edit') ?>"
               class="btn btn-warning"
               style="margin-right: 5px;">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <?php else: ?>
            <button class="btn btn-secondary"
                    title="Cannot edit - Duty instruction items are linked to My Activities"
                    disabled
                    style="margin-right: 5px;">
                <i class="fas fa-edit me-1"></i> Edit
            </button>
            <?php endif; ?>
            <a href="<?= base_url('duty-instructions') ?>" class="btn btn-info">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Duty Instruction Details -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Instruction Details</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Instruction Number:</strong></div>
                        <div class="col-sm-9">
                            <span class="badge bg-secondary"><?= esc($duty_instruction['duty_instruction_number']) ?></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Title:</strong></div>
                        <div class="col-sm-9"><?= esc($duty_instruction['duty_instruction_title']) ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Workplan:</strong></div>
                        <div class="col-sm-9">
                            <?php if (!empty($duty_instruction['workplan_title'])): ?>
                                <span class="badge bg-info"><?= esc($duty_instruction['workplan_title']) ?></span>
                            <?php else: ?>
                                <span class="text-muted">No workplan assigned</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Assigned User:</strong></div>
                        <div class="col-sm-9">
                            <?php if (!empty($duty_instruction['user_name'])): ?>
                                <span class="badge bg-primary"><?= esc($duty_instruction['user_name']) ?></span>
                            <?php else: ?>
                                <span class="text-muted">No user assigned</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Supervisor:</strong></div>
                        <div class="col-sm-9">
                            <?php if (!empty($duty_instruction['supervisor_name'])): ?>
                                <span class="badge bg-success"><?= esc($duty_instruction['supervisor_name']) ?></span>
                            <?php else: ?>
                                <span class="text-muted">No supervisor assigned</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($duty_instruction['duty_instruction_description'])): ?>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Description:</strong></div>
                            <div class="col-sm-9"><?= nl2br(esc($duty_instruction['duty_instruction_description'])) ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($duty_instruction['duty_instruction_filepath'])): ?>
                        <div class="row mb-3">
                            <div class="col-sm-3"><strong>Attachment:</strong></div>
                            <div class="col-sm-9">
                                <a href="<?= base_url($duty_instruction['duty_instruction_filepath']) ?>" 
                                   class="btn btn-outline-primary" 
                                   target="_blank" 
                                   style="margin-right: 5px;">
                                    <i class="fas fa-download me-1"></i> Download File
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Status and Actions -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Status & Actions</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Current Status:</strong><br>
                        <?php
                        $statusClass = match($duty_instruction['status']) {
                            'pending' => 'bg-warning',
                            'approved' => 'bg-success',
                            'rejected' => 'bg-danger',
                            'completed' => 'bg-primary',
                            default => 'bg-secondary'
                        };
                        ?>
                        <span class="badge <?= $statusClass ?> fs-6"><?= esc(ucfirst($duty_instruction['status'])) ?></span>
                    </div>

                    <!-- Status Update Form -->
                    <?= form_open('duty-instructions/' . $duty_instruction['id'] . '/status') ?>
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label for="status" class="form-label">Update Status:</label>
                            <select class="form-select form-select-sm" name="status" id="status">
                                <option value="pending" <?= $duty_instruction['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $duty_instruction['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= $duty_instruction['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                <option value="completed" <?= $duty_instruction['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status_remarks" class="form-label">Remarks:</label>
                            <textarea class="form-control form-control-sm" name="status_remarks" id="status_remarks" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Update Status
                        </button>
                    <?= form_close() ?>

                    <hr>
                    <div class="text-muted small">
                        <div><strong>Created:</strong> <?= date('M d, Y H:i', strtotime($duty_instruction['created_at'])) ?></div>
                        <div><strong>By:</strong> <?= esc($duty_instruction['created_by_name'] ?? 'Unknown') ?></div>
                        <?php if (!empty($duty_instruction['updated_at'])): ?>
                            <div><strong>Updated:</strong> <?= date('M d, Y H:i', strtotime($duty_instruction['updated_at'])) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instruction Items -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Instruction Items</h6>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                <i class="fas fa-plus me-1"></i> Add Item
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm" id="itemsTable">
                    <thead>
                        <tr>
                            <th>Item #</th>
                            <th>Instruction</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="itemsTableBody">
                        <!-- Existing Items -->
                        <?php if (!empty($duty_items)): ?>
                            <?php foreach ($duty_items as $item): ?>
                                <tr data-item-id="<?= $item['id'] ?>">
                                    <td><span class="badge bg-light text-dark"><?= esc($item['instruction_number']) ?></span></td>
                                    <td><?= esc($item['instruction']) ?></td>
                                    <td>
                                        <?php
                                        $itemStatusClass = match($item['status']) {
                                            'active' => 'bg-success',
                                            'inactive' => 'bg-secondary',
                                            'completed' => 'bg-primary',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $itemStatusClass ?>"><?= esc(ucfirst($item['status'])) ?></span>
                                    </td>
                                    <td><?= esc($item['remarks'] ?? '-') ?></td>
                                    <td>
                                        <?php if (!isset($item['has_myactivities_links']) || !$item['has_myactivities_links']): ?>
                                        <button class="btn btn-outline-warning btn-sm edit-item-btn"
                                                data-item-id="<?= $item['id'] ?>"
                                                data-item-number="<?= esc($item['instruction_number']) ?>"
                                                data-instruction="<?= esc($item['instruction']) ?>"
                                                data-status="<?= esc($item['status']) ?>"
                                                data-remarks="<?= esc($item['remarks'] ?? '') ?>"
                                                title="Edit Item">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm delete-item-btn"
                                                data-item-id="<?= $item['id'] ?>" title="Delete Item">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                        <?php else: ?>
                                        <button class="btn btn-outline-secondary btn-sm"
                                                title="Cannot edit - Item is linked to My Activities"
                                                disabled>
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm"
                                                title="Cannot delete - Item is linked to My Activities"
                                                disabled>
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr id="noItemsRow">
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-list fa-2x mb-3"></i>
                                    <br>No instruction items added yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Add New Instruction Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addItemForm">
                    <div class="alert alert-info">
                        <strong>Adding item to:</strong> <?= esc($duty_instruction['duty_instruction_title']) ?>
                        <br><small>Instruction Number: <?= esc($duty_instruction['duty_instruction_number']) ?></small>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="addItemNumber" class="form-label">Item Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="addItemNumber" required>
                                <div class="form-text">Sequential number</div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="mb-3">
                                <label for="addInstruction" class="form-label">Instruction <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="addInstruction" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="addStatus" class="form-label">Status</label>
                        <select class="form-select" id="addStatus">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="addRemarks" class="form-label">Remarks (Optional)</label>
                        <textarea class="form-control" id="addRemarks" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveNewItemBtn">
                    <i class="fas fa-save me-1"></i> Save Item
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">Edit Instruction Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editItemForm">
                    <input type="hidden" id="editItemId">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="editItemNumber" class="form-label">Item Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editItemNumber" required>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="mb-3">
                                <label for="editInstruction" class="form-label">Instruction <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="editInstruction" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Status</label>
                        <select class="form-select" id="editStatus">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="editRemarks" class="form-label">Remarks (Optional)</label>
                        <textarea class="form-control" id="editRemarks" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveEditItemBtn">
                    <i class="fas fa-save me-1"></i> Update Item
                </button>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    const dutyInstructionId = <?= $duty_instruction['id'] ?>;
    const csrfToken = '<?= csrf_hash() ?>';
    const csrfName = '<?= csrf_token() ?>';

    // Initialize modals
    const addItemModal = new bootstrap.Modal(document.getElementById('addItemModal'));
    const editItemModal = new bootstrap.Modal(document.getElementById('editItemModal'));

    // When Add Item modal is shown, auto-generate next item number
    $('#addItemModal').on('shown.bs.modal', function() {
        const existingItems = document.querySelectorAll('[data-item-id]');
        const nextNumber = existingItems.length + 1;
        $('#addItemNumber').val(nextNumber);
        $('#addInstruction').focus();
    });

    // Clear form when modal is hidden
    $('#addItemModal').on('hidden.bs.modal', function() {
        $('#addItemForm')[0].reset();
    });

    // Save new item
    $('#saveNewItemBtn').on('click', function() {
        const itemNumber = $('#addItemNumber').val().trim();
        const instruction = $('#addInstruction').val().trim();
        const status = $('#addStatus').val();
        const remarks = $('#addRemarks').val().trim();

        // Validation
        if (!itemNumber || !instruction) {
            toastr.error('Please fill in the required fields (Item # and Instruction)');
            return;
        }

        // Show loading state
        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...').prop('disabled', true);

        // AJAX request
        $.ajax({
            url: `<?= base_url('duty-instructions/') ?>${dutyInstructionId}/items/create`,
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            data: JSON.stringify({
                instruction_number: itemNumber,
                instruction: instruction,
                status: status,
                remarks: remarks,
                [csrfName]: csrfToken
            }),
            success: function(response) {
                if (response.success) {
                    toastr.success('Instruction item added successfully!');
                    addItemModal.hide();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message || 'Failed to add instruction item');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                toastr.error('An error occurred while adding the item');
            },
            complete: function() {
                $btn.html(originalHtml).prop('disabled', false);
            }
        });
    });

    // Edit item button click
    $(document).on('click', '.edit-item-btn', function() {
        const $btn = $(this);
        const itemId = $btn.data('item-id');
        const itemNumber = $btn.data('item-number');
        const instruction = $btn.data('instruction');
        const status = $btn.data('status');
        const remarks = $btn.data('remarks');

        // Populate edit form
        $('#editItemId').val(itemId);
        $('#editItemNumber').val(itemNumber);
        $('#editInstruction').val(instruction);
        $('#editStatus').val(status);
        $('#editRemarks').val(remarks);

        // Show modal
        editItemModal.show();
    });

    // Save edited item
    $('#saveEditItemBtn').on('click', function() {
        const itemId = $('#editItemId').val();
        const itemNumber = $('#editItemNumber').val().trim();
        const instruction = $('#editInstruction').val().trim();
        const status = $('#editStatus').val();
        const remarks = $('#editRemarks').val().trim();

        // Validation
        if (!itemNumber || !instruction) {
            toastr.error('Please fill in the required fields (Item # and Instruction)');
            return;
        }

        // Show loading state
        const $btn = $(this);
        const originalHtml = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Updating...').prop('disabled', true);

        // AJAX request
        $.ajax({
            url: `<?= base_url('duty-instructions/items/') ?>${itemId}/update`,
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            data: JSON.stringify({
                instruction_number: itemNumber,
                instruction: instruction,
                status: status,
                remarks: remarks,
                [csrfName]: csrfToken
            }),
            success: function(response) {
                if (response.success) {
                    toastr.success('Item updated successfully!');
                    editItemModal.hide();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message || 'Failed to update item');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                toastr.error('An error occurred while updating the item');
            },
            complete: function() {
                $btn.html(originalHtml).prop('disabled', false);
            }
        });
    });

    // Delete item button click
    $(document).on('click', '.delete-item-btn', function() {
        const $btn = $(this);
        const itemId = $btn.data('item-id');

        if (!confirm('Are you sure you want to delete this instruction item?')) {
            return;
        }

        // Show loading state
        $btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

        // AJAX request
        $.ajax({
            url: `<?= base_url('duty-instructions/items/') ?>${itemId}/delete`,
            method: 'POST',
            contentType: 'application/json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            data: JSON.stringify({
                [csrfName]: csrfToken
            }),
            success: function(response) {
                if (response.success) {
                    toastr.success('Item deleted successfully!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message || 'Failed to delete item');
                    $btn.html('<i class="fas fa-trash"></i> Delete').prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                toastr.error('An error occurred while deleting the item');
                $btn.html('<i class="fas fa-trash"></i> Delete').prop('disabled', false);
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
