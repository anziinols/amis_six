<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <a href="<?= base_url('duty-instructions/' . $duty_instruction['id']) ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Duty Instruction
            </a>
        </div>
        <div class="card-body">
            <!-- Duty Instruction Info -->
            <div class="alert alert-info">
                <strong>Adding item to:</strong> <?= esc($duty_instruction['duty_instruction_title']) ?>
                <br><small>Instruction Number: <?= esc($duty_instruction['duty_instruction_number']) ?></small>
            </div>

            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?= form_open('duty-instructions/' . $duty_instruction['id'] . '/items/create') ?>
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="instruction_number" class="form-label">Item Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="instruction_number" 
                                   name="instruction_number" value="<?= old('instruction_number', $next_number) ?>" 
                                   placeholder="e.g., 1" required>
                            <div class="form-text">Sequential number for this instruction item</div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="mb-3">
                            <label for="instruction" class="form-label">Instruction <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="instruction" name="instruction" rows="3" 
                                      placeholder="Enter the specific instruction or task" required><?= old('instruction') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="remarks" class="form-label">Remarks (Optional)</label>
                    <textarea class="form-control" id="remarks" name="remarks" rows="2" 
                              placeholder="Additional notes or comments"><?= old('remarks') ?></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="<?= base_url('duty-instructions/' . $duty_instruction['id']) ?>" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Add Instruction Item
                    </button>
                </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus on instruction textarea
    document.getElementById('instruction').focus();
    
    // Character counter for instruction
    const instructionTextarea = document.getElementById('instruction');
    const maxLength = 1000; // Reasonable limit
    
    // Create character counter element
    const counterDiv = document.createElement('div');
    counterDiv.className = 'form-text';
    counterDiv.id = 'instruction-counter';
    instructionTextarea.parentNode.appendChild(counterDiv);
    
    function updateCounter() {
        const remaining = maxLength - instructionTextarea.value.length;
        counterDiv.textContent = `${instructionTextarea.value.length}/${maxLength} characters`;
        
        if (remaining < 50) {
            counterDiv.className = 'form-text text-warning';
        } else if (remaining < 0) {
            counterDiv.className = 'form-text text-danger';
        } else {
            counterDiv.className = 'form-text text-muted';
        }
    }
    
    instructionTextarea.addEventListener('input', updateCounter);
    updateCounter(); // Initial count
});
</script>
<?= $this->endSection() ?>
