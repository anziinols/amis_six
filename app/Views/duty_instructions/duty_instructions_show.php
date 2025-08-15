<?= $this->extend('templates/system_template') ?>

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
            <a href="<?= base_url('duty-instructions/' . $duty_instruction['id'] . '/edit') ?>" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="<?= base_url('duty-instructions') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
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
                                   class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-download"></i> Download File
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
                        <button type="submit" class="btn btn-sm btn-primary">Update Status</button>
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
            <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                <i class="fas fa-plus"></i> Add Item
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
                    <tbody>
                        <!-- Add Item Form Row (Initially Hidden) -->
                        <tr id="addItemRow" style="display: none;">
                            <td>
                                <input type="text" class="form-control form-control-sm" id="newItemNumber"
                                       placeholder="Item #" style="width: 80px;">
                            </td>
                            <td>
                                <textarea class="form-control form-control-sm" id="newInstruction"
                                          placeholder="Enter instruction" rows="2" required></textarea>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" id="newStatus">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </td>
                            <td>
                                <textarea class="form-control form-control-sm" id="newRemarks"
                                          placeholder="Remarks" rows="2"></textarea>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-success" id="saveItemBtn">
                                    <i class="fas fa-save"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" id="cancelItemBtn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </td>
                        </tr>

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
                                        <button class="btn btn-sm btn-outline-secondary edit-item-btn"
                                                data-item-id="<?= $item['id'] ?>" title="Edit Item">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger delete-item-btn"
                                                data-item-id="<?= $item['id'] ?>" title="Delete Item">
                                            <i class="fas fa-trash"></i>
                                        </button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addItemBtn = document.getElementById('addItemBtn');
    const addItemRow = document.getElementById('addItemRow');
    const saveItemBtn = document.getElementById('saveItemBtn');
    const cancelItemBtn = document.getElementById('cancelItemBtn');
    const noItemsRow = document.getElementById('noItemsRow');

    // Show add item form
    addItemBtn.addEventListener('click', function() {
        addItemRow.style.display = 'table-row';
        addItemBtn.style.display = 'none';

        // Auto-generate next item number
        const existingItems = document.querySelectorAll('[data-item-id]');
        const nextNumber = existingItems.length + 1;
        document.getElementById('newItemNumber').value = nextNumber;

        // Focus on instruction field
        document.getElementById('newInstruction').focus();
    });

    // Cancel add item
    cancelItemBtn.addEventListener('click', function() {
        addItemRow.style.display = 'none';
        addItemBtn.style.display = 'inline-block';
        clearForm();
    });

    // Save new item via AJAX
    saveItemBtn.addEventListener('click', function() {
        const instruction = document.getElementById('newInstruction').value.trim();
        const itemNumber = document.getElementById('newItemNumber').value.trim();
        const status = document.getElementById('newStatus').value;
        const remarks = document.getElementById('newRemarks').value.trim();

        if (!instruction || !itemNumber) {
            alert('Please fill in the required fields (Item # and Instruction)');
            return;
        }

        // Show loading state
        saveItemBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        saveItemBtn.disabled = true;

        // AJAX request
        fetch('<?= base_url('duty-instructions/' . $duty_instruction['id'] . '/items/create') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            },
            body: JSON.stringify({
                instruction_number: itemNumber,
                instruction: instruction,
                status: status,
                remarks: remarks,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message and reload page
                showAlert('success', 'Instruction item added successfully!');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showAlert('danger', data.message || 'Failed to add instruction item');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'An error occurred while adding the item');
        })
        .finally(() => {
            // Reset button state
            saveItemBtn.innerHTML = '<i class="fas fa-save"></i>';
            saveItemBtn.disabled = false;
        });
    });

    // Edit item functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-item-btn')) {
            const btn = e.target.closest('.edit-item-btn');
            const itemId = btn.getAttribute('data-item-id');
            const row = btn.closest('tr');

            // Get current values
            const itemNumber = row.cells[0].textContent.trim();
            const instruction = row.cells[1].textContent.trim();
            const currentStatus = row.cells[2].querySelector('.badge').textContent.toLowerCase().trim();
            const remarks = row.cells[3].textContent.trim();

            // Replace row content with edit form
            row.innerHTML = `
                <td>
                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(itemNumber)}"
                           id="editItemNumber_${itemId}" style="width: 80px;">
                </td>
                <td>
                    <textarea class="form-control form-control-sm" id="editInstruction_${itemId}"
                              rows="2" required>${escapeHtml(instruction)}</textarea>
                </td>
                <td>
                    <select class="form-select form-select-sm" id="editStatus_${itemId}">
                        <option value="active" ${currentStatus === 'active' ? 'selected' : ''}>Active</option>
                        <option value="inactive" ${currentStatus === 'inactive' ? 'selected' : ''}>Inactive</option>
                        <option value="completed" ${currentStatus === 'completed' ? 'selected' : ''}>Completed</option>
                    </select>
                </td>
                <td>
                    <textarea class="form-control form-control-sm" id="editRemarks_${itemId}"
                              rows="2">${escapeHtml(remarks === '-' ? '' : remarks)}</textarea>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-success save-edit-btn" data-item-id="${itemId}">
                        <i class="fas fa-save"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary cancel-edit-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            `;
        }

        // Save edit
        if (e.target.closest('.save-edit-btn')) {
            const btn = e.target.closest('.save-edit-btn');
            const itemId = btn.getAttribute('data-item-id');

            const itemNumber = document.getElementById(`editItemNumber_${itemId}`).value.trim();
            const instruction = document.getElementById(`editInstruction_${itemId}`).value.trim();
            const status = document.getElementById(`editStatus_${itemId}`).value;
            const remarks = document.getElementById(`editRemarks_${itemId}`).value.trim();

            if (!instruction || !itemNumber) {
                alert('Please fill in the required fields (Item # and Instruction)');
                return;
            }

            // Show loading state
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;

            // AJAX request to update item
            fetch(`<?= base_url('duty-instructions/items/') ?>${itemId}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify({
                    instruction_number: itemNumber,
                    instruction: instruction,
                    status: status,
                    remarks: remarks,
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 'Item updated successfully!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert('danger', data.message || 'Failed to update item');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'An error occurred while updating the item');
            })
            .finally(() => {
                btn.innerHTML = '<i class="fas fa-save"></i>';
                btn.disabled = false;
            });
        }

        // Cancel edit
        if (e.target.closest('.cancel-edit-btn')) {
            window.location.reload();
        }

        // Delete item
        if (e.target.closest('.delete-item-btn')) {
            const btn = e.target.closest('.delete-item-btn');
            const itemId = btn.getAttribute('data-item-id');

            if (confirm('Are you sure you want to delete this instruction item?')) {
                // AJAX request to delete item
                fetch(`<?= base_url('duty-instructions/items/') ?>${itemId}/delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    },
                    body: JSON.stringify({
                        <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', 'Item deleted successfully!');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showAlert('danger', data.message || 'Failed to delete item');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'An error occurred while deleting the item');
                });
            }
        }
    });

    // Clear form fields
    function clearForm() {
        document.getElementById('newItemNumber').value = '';
        document.getElementById('newInstruction').value = '';
        document.getElementById('newStatus').value = 'active';
        document.getElementById('newRemarks').value = '';
    }

    // Add new item row to table
    function addItemToTable(item) {
        const tbody = document.querySelector('#itemsTable tbody');
        const statusClass = getStatusClass(item.status);

        const newRow = document.createElement('tr');
        newRow.setAttribute('data-item-id', item.id);
        newRow.innerHTML = `
            <td><span class="badge bg-light text-dark">${escapeHtml(item.instruction_number)}</span></td>
            <td>${escapeHtml(item.instruction)}</td>
            <td><span class="badge ${statusClass}">${capitalizeFirst(item.status)}</span></td>
            <td>${escapeHtml(item.remarks || '-')}</td>
            <td>
                <button class="btn btn-sm btn-outline-secondary edit-item-btn"
                        data-item-id="${item.id}" title="Edit Item">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-outline-danger delete-item-btn"
                        data-item-id="${item.id}" title="Delete Item">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        // Insert before the add item row
        tbody.insertBefore(newRow, addItemRow);
    }

    // Helper functions
    function getStatusClass(status) {
        switch(status) {
            case 'active': return 'bg-success';
            case 'inactive': return 'bg-secondary';
            case 'completed': return 'bg-primary';
            default: return 'bg-secondary';
        }
    }

    function capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        // Insert at the top of the card body
        const cardBody = document.querySelector('.card-body');
        cardBody.insertBefore(alertDiv, cardBody.firstChild);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
});
</script>
<?= $this->endSection() ?>
