<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <a href="<?= base_url('duty-instructions/' . $duty_instruction['id']) ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Details
            </a>
        </div>
        <div class="card-body">
            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?= form_open_multipart('duty-instructions/' . $duty_instruction['id'] . '/update') ?>
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="workplan_id" class="form-label">Workplan <span class="text-danger">*</span></label>
                            <select class="form-select" id="workplan_id" name="workplan_id" required>
                                <option value="">Select Workplan</option>
                                <?php foreach ($workplans as $workplan): ?>
                                    <option value="<?= $workplan['id'] ?>"
                                            <?= (old('workplan_id', $duty_instruction['workplan_id']) == $workplan['id']) ? 'selected' : '' ?>>
                                        <?= esc($workplan['title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="duty_instruction_number" class="form-label">Instruction Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="duty_instruction_number"
                                   name="duty_instruction_number"
                                   value="<?= old('duty_instruction_number', $duty_instruction['duty_instruction_number']) ?>"
                                   placeholder="e.g., DI-001" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Assigned User <span class="text-danger">*</span></label>
                            <select class="form-select" id="user_id" name="user_id" required>
                                <option value="">Select User</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>"
                                            <?= (old('user_id', $duty_instruction['user_id']) == $user['id']) ? 'selected' : '' ?>>
                                        <?= esc($user['fname'] . ' ' . $user['lname']) ?> (<?= esc($user['designation']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="supervisor_id" class="form-label">Supervisor <span class="text-danger">*</span></label>
                            <select class="form-select" id="supervisor_id" name="supervisor_id" required>
                                <option value="">Select Supervisor</option>
                                <?php foreach ($supervisors as $supervisor): ?>
                                    <option value="<?= $supervisor['id'] ?>"
                                            <?= (old('supervisor_id', $duty_instruction['supervisor_id']) == $supervisor['id']) ? 'selected' : '' ?>>
                                        <?= esc($supervisor['fname'] . ' ' . $supervisor['lname']) ?> (<?= esc($supervisor['designation']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="duty_instruction_title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="duty_instruction_title" 
                           name="duty_instruction_title" 
                           value="<?= old('duty_instruction_title', $duty_instruction['duty_instruction_title']) ?>" 
                           placeholder="Enter duty instruction title" required>
                </div>

                <div class="mb-3">
                    <label for="duty_instruction_description" class="form-label">Description</label>
                    <textarea class="form-control" id="duty_instruction_description" 
                              name="duty_instruction_description" rows="4" 
                              placeholder="Enter detailed description of the duty instruction"><?= old('duty_instruction_description', $duty_instruction['duty_instruction_description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="duty_instruction_file" class="form-label">Attachment (Optional)</label>
                    <?php if (!empty($duty_instruction['duty_instruction_filepath'])): ?>
                        <div class="mb-2">
                            <small class="text-muted">Current file: </small>
                            <a href="<?= base_url($duty_instruction['duty_instruction_filepath']) ?>" 
                               class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-download"></i> Download Current File
                            </a>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="duty_instruction_file" 
                           name="duty_instruction_file" accept=".pdf,.doc,.docx,.txt">
                    <div class="form-text">Supported formats: PDF, DOC, DOCX, TXT (Max: 5MB). Leave empty to keep current file.</div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="<?= base_url('duty-instructions/' . $duty_instruction['id']) ?>" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Duty Instruction
                    </button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // File size validation
    const fileInput = document.getElementById('duty_instruction_file');
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file && file.size > 5 * 1024 * 1024) { // 5MB
            alert('File size must be less than 5MB');
            this.value = '';
        }
    });
});
</script>
<?= $this->endSection() ?>
