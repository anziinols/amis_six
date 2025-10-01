<?php 
// app/Views/workplans/workplan_form.php 
helper('form'); 

// Determine if we are editing or creating
$isEdit = isset($workplan);
$workplanData = $workplan ?? [];

// Status options
$statusOptions = [
    'draft' => 'Draft',
    'in_progress' => 'In Progress',
    'completed' => 'Completed',
    'on_hold' => 'On Hold'
];

?>

<div class="card-body">
    <!-- Display validation errors -->
    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row g-3">
        <div class="col-md-6">
            <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
            <select name="branch_id" id="branch_id" class="form-select select2" required <?= (isset($isAdmin) && !$isAdmin) ? 'readonly' : '' ?>>
                <?php if (empty($branches ?? [])): ?>
                    <option value="">No branches available</option>
                <?php else: ?>
                    <?php if (isset($isAdmin) && $isAdmin): ?>
                        <option value="">Select Branch</option>
                    <?php endif; ?>
                    <?php foreach($branches ?? [] as $branch): ?>
                        <?php
                        // Auto-select the logged-in user's branch if not admin, otherwise use existing selection
                        $selectedBranchId = $workplanData['branch_id'] ?? (isset($loggedInUserBranchId) ? $loggedInUserBranchId : '');
                        ?>
                        <option value="<?= $branch['id'] ?>" <?= (set_value('branch_id', $selectedBranchId) == $branch['id']) ? 'selected' : '' ?>><?= esc($branch['name']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="supervisor_id" class="form-label">Supervisor <span class="text-danger">*</span></label>
            <select name="supervisor_id" id="supervisor_id" class="form-select select2" required <?= (isset($isAdmin) && !$isAdmin) ? 'readonly' : '' ?>>
                <?php if (empty($supervisors ?? [])): ?>
                    <option value="">No supervisors available</option>
                <?php else: ?>
                    <?php if (isset($isAdmin) && $isAdmin): ?>
                        <option value="">Select Supervisor</option>
                    <?php endif; ?>
                    <?php foreach($supervisors ?? [] as $supervisor): ?>
                        <?php
                        // Auto-select the logged-in user if not admin, otherwise use existing selection
                        $selectedUserId = $workplanData['supervisor_id'] ?? (isset($loggedInUserId) ? $loggedInUserId : '');
                        ?>
                        <option value="<?= $supervisor['id'] ?>" <?= (set_value('supervisor_id', $selectedUserId) == $supervisor['id']) ? 'selected' : '' ?>><?= esc($supervisor['fname'] . ' ' . $supervisor['lname']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-12">
            <label for="title" class="form-label">Workplan Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="title" name="title" value="<?= set_value('title', $workplanData['title'] ?? '') ?>" required>
        </div>

        <div class="col-12">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?= set_value('description', $workplanData['description'] ?? '') ?></textarea>
        </div>

        <div class="col-12">
            <label for="objectives" class="form-label">Objectives</label>
            <textarea class="form-control" id="objectives" name="objectives" rows="3"><?= set_value('objectives', $workplanData['objectives'] ?? '') ?></textarea>
        </div>

        <div class="col-md-6">
            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= set_value('start_date', !empty($workplanData['start_date']) ? date('Y-m-d', strtotime($workplanData['start_date'])) : '') ?>" required>
        </div>

        <div class="col-md-6">
            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= set_value('end_date', !empty($workplanData['end_date']) ? date('Y-m-d', strtotime($workplanData['end_date'])) : '') ?>" required>
        </div>

        <div class="col-md-6">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" required>
                <?php foreach($statusOptions as $value => $label): ?>
                    <option value="<?= $value ?>" <?= (set_value('status', $workplanData['status'] ?? 'in_progress') == $value) ? 'selected' : '' ?>><?= esc($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="remarks" class="form-label">Remarks</label>
            <input type="text" class="form-control" id="remarks" name="remarks" value="<?= set_value('remarks', $workplanData['remarks'] ?? '') ?>">
        </div>
    </div>
</div>
<!-- /.card-body -->

<div class="card-footer">
    <button type="submit" class="btn btn-primary"> <?= $isEdit ? 'Update Workplan' : 'Create Workplan' ?> </button>
    <a href="<?= base_url('workplans') ?>" class="btn btn-secondary">Cancel</a>
</div>
