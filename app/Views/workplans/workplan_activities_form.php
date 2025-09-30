<?php if (isset($activity['activity_code']) && !empty($activity['activity_code'])): ?>
<div class="row mb-3">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="activity_code_display" class="form-label">Activity Code</label>
            <input type="text" class="form-control" id="activity_code_display" value="<?= esc($activity['activity_code']) ?>" readonly>
            <div class="form-text">Activity code is automatically generated and cannot be changed.</div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="title" class="form-label">Activity Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control <?= session('errors.title') ? 'is-invalid' : '' ?>" id="title" name="title" value="<?= old('title', $activity['title'] ?? '') ?>" required>
            <?php if (session('errors.title')): ?>
                <div class="invalid-feedback"><?= session('errors.title') ?></div>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="target_output" class="form-label">Target Output</label>
            <input type="text" class="form-control <?= session('errors.target_output') ? 'is-invalid' : '' ?>" id="target_output" name="target_output" value="<?= old('target_output', $activity['target_output'] ?? '') ?>">
            <?php if (session('errors.target_output')): ?>
                <div class="invalid-feedback"><?= session('errors.target_output') ?></div>
            <?php endif; ?>
            <div class="form-text">Describe the expected output or deliverable for this activity.</div>
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control <?= session('errors.description') ? 'is-invalid' : '' ?>" id="description" name="description" rows="4"><?= old('description', $activity['description'] ?? '') ?></textarea>
    <?php if (session('errors.description')): ?>
        <div class="invalid-feedback"><?= session('errors.description') ?></div>
    <?php endif; ?>
</div>



<!-- Budget Section -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">Budget Information</h6>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label for="total_budget" class="form-label">Total Budget</label>
            <input type="number" step="0.01" class="form-control <?= session('errors.total_budget') ? 'is-invalid' : '' ?>" id="total_budget" name="total_budget" value="<?= old('total_budget', $activity['total_budget'] ?? '') ?>">
            <?php if (session('errors.total_budget')): ?>
                <div class="invalid-feedback"><?= session('errors.total_budget') ?></div>
            <?php endif; ?>
            <div class="form-text">Enter the total budget allocated for this activity.</div>
        </div>
    </div>
</div>

<!-- Assignment Section -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">Assignment Information</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="supervisor_id" class="form-label">Supervisor</label>
                    <select class="form-select <?= session('errors.supervisor_id') ? 'is-invalid' : '' ?>" id="supervisor_id" name="supervisor_id">
                        <option value="">Select Supervisor</option>
                        <?php foreach ($supervisors as $supervisor): ?>
                            <option value="<?= $supervisor['id'] ?>" <?= old('supervisor_id', $activity['supervisor_id'] ?? '') == $supervisor['id'] ? 'selected' : '' ?>><?= $supervisor['fname'] . ' ' . $supervisor['lname'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (session('errors.supervisor_id')): ?>
                        <div class="invalid-feedback"><?= session('errors.supervisor_id') ?></div>
                    <?php endif; ?>
                    <div class="form-text">Select the supervisor responsible for this activity (filtered by workplan branch).</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="branch_display" class="form-label">Branch</label>
                    <input type="text" class="form-control" id="branch_display" value="<?= esc($workplan['branch_name'] ?? 'Inherited from Workplan') ?>" readonly>
                    <div class="form-text">Branch is automatically inherited from the workplan.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- No hidden fields needed -->

<div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
    <a href="<?= isset($activity['id']) ? base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id']) : base_url('workplans/' . $workplan['id'] . '/activities') ?>" class="btn btn-secondary me-md-2">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Activity</button>
</div>

<!-- No JavaScript needed for this simplified form -->
