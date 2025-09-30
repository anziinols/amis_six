<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <a href="<?= base_url('duty-instructions') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
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

            <?= form_open_multipart('duty-instructions/create') ?>
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="workplan_id" class="form-label">Workplan <span class="text-danger">*</span></label>
                            <select class="form-select" id="workplan_id" name="workplan_id" required>
                                <option value="">Select Workplan</option>
                                <?php foreach ($workplans as $workplan): ?>
                                    <option value="<?= $workplan['id'] ?>" <?= old('workplan_id') == $workplan['id'] ? 'selected' : '' ?>>
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
                                   name="duty_instruction_number" value="<?= old('duty_instruction_number') ?>"
                                   placeholder="e.g., DI-001" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="assigned_user" class="form-label">Assigned User <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="assigned_user" 
                                   value="<?= esc(session()->get('fname') . ' ' . session()->get('lname')) ?>" 
                                   readonly disabled>
                            <input type="hidden" name="user_id" value="<?= session()->get('user_id') ?>">
                            <div class="form-text">You are automatically assigned to this duty instruction</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="supervisor_id" class="form-label">Supervisor <span class="text-danger">*</span></label>
                            <select class="form-select" id="supervisor_id" name="supervisor_id" required>
                                <option value="">Select Supervisor</option>
                                <?php foreach ($supervisors as $supervisor): ?>
                                    <option value="<?= $supervisor['id'] ?>" <?= old('supervisor_id') == $supervisor['id'] ? 'selected' : '' ?>>
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
                           name="duty_instruction_title" value="<?= old('duty_instruction_title') ?>" 
                           placeholder="Enter duty instruction title" required>
                </div>

                <div class="mb-3">
                    <label for="duty_instruction_description" class="form-label">Description</label>
                    <textarea class="form-control" id="duty_instruction_description" 
                              name="duty_instruction_description" rows="4" 
                              placeholder="Enter detailed description of the duty instruction"><?= old('duty_instruction_description') ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="duty_instruction_file" class="form-label">Attachment (Optional)</label>
                    <input type="file" class="form-control" id="duty_instruction_file" 
                           name="duty_instruction_file" accept=".pdf,.doc,.docx,.txt">
                    <div class="form-text">Supported formats: PDF, DOC, DOCX, TXT (Max: 5MB)</div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="<?= base_url('duty-instructions') ?>" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Duty Instruction
                    </button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate instruction number based on workplan selection
    const workplanSelect = document.getElementById('workplan_id');
    const instructionNumberInput = document.getElementById('duty_instruction_number');
    
    workplanSelect.addEventListener('change', function() {
        if (this.value && !instructionNumberInput.value) {
            // Generate a simple instruction number
            const timestamp = Date.now().toString().slice(-6);
            instructionNumberInput.value = 'DI-' + timestamp;
        }
    });

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
