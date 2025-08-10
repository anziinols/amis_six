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
            <label for="activity_type" class="form-label">Activity Type <span class="text-danger">*</span></label>
            <select class="form-select <?= session('errors.activity_type') ? 'is-invalid' : '' ?>" id="activity_type" name="activity_type" required>
                <option value="">Select Activity Type</option>
                <?php foreach ($activityTypes as $value => $label): ?>
                    <option value="<?= $value ?>" <?= old('activity_type', $activity['activity_type'] ?? '') == $value ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (session('errors.activity_type')): ?>
                <div class="invalid-feedback"><?= session('errors.activity_type') ?></div>
            <?php endif; ?>
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

<!-- Quarterly Targets Section -->
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">Quarterly Targets</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="q_one_target" class="form-label">Quarter One Target</label>
                    <input type="number" step="0.01" class="form-control <?= session('errors.q_one_target') ? 'is-invalid' : '' ?>" id="q_one_target" name="q_one_target" value="<?= old('q_one_target', $activity['q_one_target'] ?? '') ?>">
                    <?php if (session('errors.q_one_target')): ?>
                        <div class="invalid-feedback"><?= session('errors.q_one_target') ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="q_two_target" class="form-label">Quarter Two Target</label>
                    <input type="number" step="0.01" class="form-control <?= session('errors.q_two_target') ? 'is-invalid' : '' ?>" id="q_two_target" name="q_two_target" value="<?= old('q_two_target', $activity['q_two_target'] ?? '') ?>">
                    <?php if (session('errors.q_two_target')): ?>
                        <div class="invalid-feedback"><?= session('errors.q_two_target') ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="q_three_target" class="form-label">Quarter Three Target</label>
                    <input type="number" step="0.01" class="form-control <?= session('errors.q_three_target') ? 'is-invalid' : '' ?>" id="q_three_target" name="q_three_target" value="<?= old('q_three_target', $activity['q_three_target'] ?? '') ?>">
                    <?php if (session('errors.q_three_target')): ?>
                        <div class="invalid-feedback"><?= session('errors.q_three_target') ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="q_four_target" class="form-label">Quarter Four Target</label>
                    <input type="number" step="0.01" class="form-control <?= session('errors.q_four_target') ? 'is-invalid' : '' ?>" id="q_four_target" name="q_four_target" value="<?= old('q_four_target', $activity['q_four_target'] ?? '') ?>">
                    <?php if (session('errors.q_four_target')): ?>
                        <div class="invalid-feedback"><?= session('errors.q_four_target') ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
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
                    <div class="form-text">Select the supervisor responsible for this activity.</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="branch_id" class="form-label">Branch</label>
                    <select class="form-select <?= session('errors.branch_id') ? 'is-invalid' : '' ?>" id="branch_id" name="branch_id">
                        <option value="">Select Branch</option>
                        <?php if (isset($branches)): ?>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?= $branch['id'] ?>" <?= old('branch_id', $activity['branch_id'] ?? $workplan['branch_id'] ?? '') == $branch['id'] ? 'selected' : '' ?>><?= $branch['name'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (session('errors.branch_id')): ?>
                        <div class="invalid-feedback"><?= session('errors.branch_id') ?></div>
                    <?php endif; ?>
                    <div class="form-text">Select the branch responsible for this activity.</div>
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
