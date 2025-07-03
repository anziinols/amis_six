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

<div class="row">
    <div class="col-md-12">
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
        </div>
    </div>
</div>

<!-- No hidden fields needed -->

<div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
    <a href="<?= isset($activity['id']) ? base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id']) : base_url('workplans/' . $workplan['id'] . '/activities') ?>" class="btn btn-secondary me-md-2">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Activity</button>
</div>

<!-- No JavaScript needed for this simplified form -->
