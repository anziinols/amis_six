<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <a href="<?= base_url('workplan-period/' . $workplan_period['id']) ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Details
            </a>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('workplan-period/' . $workplan_period['id'] . '/update') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="user_id" class="form-label">User <span class="text-danger">*</span></label>
                    <select class="form-select" id="user_id" name="user_id" required>
                        <option value="">Select User</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>" <?= (old('user_id') ?? $workplan_period['user_id']) == $user['id'] ? 'selected' : '' ?>>
                                <?= esc($user['fname'] . ' ' . $user['lname']) ?> (<?= esc($user['email']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="duty_instruction_id" class="form-label">Duty Instruction (Optional)</label>
                    <select class="form-select" id="duty_instruction_id" name="duty_instruction_id">
                        <option value="">Select Duty Instruction</option>
                        <?php foreach ($duty_instructions as $instruction): ?>
                            <option value="<?= $instruction['id'] ?>" <?= (old('duty_instruction_id') ?? $workplan_period['duty_instruction_id']) == $instruction['id'] ? 'selected' : '' ?>>
                                <?= esc($instruction['duty_instruction_title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= old('title') ?? esc($workplan_period['title']) ?>" required maxlength="255">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4"><?= old('description') ?? esc($workplan_period['description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="workplan_period_file" class="form-label">Workplan Period File (Optional)</label>
                    <?php if (isset($workplan_period['workplan_period_filepath']) && $workplan_period['workplan_period_filepath']): ?>
                        <div class="mb-2">
                            <small class="text-muted">Current file:</small>
                            <a href="<?= base_url($workplan_period['workplan_period_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file"></i> View Current File
                            </a>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="workplan_period_file" name="workplan_period_file" accept=".pdf,.doc,.docx,.xls,.xlsx">
                    <div class="form-text">
                        Accepted formats: PDF, DOC, DOCX, XLS, XLSX
                        <?php if (isset($workplan_period['workplan_period_filepath']) && $workplan_period['workplan_period_filepath']): ?>
                            <br><strong>Note:</strong> Uploading a new file will replace the current file.
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('workplan-period/' . $workplan_period['id']) ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Workplan Period
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
