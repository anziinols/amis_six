<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Agreement: <?= esc($agreement['title']) ?></h5>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?= form_open_multipart('agreements/update/' . $agreement['id']) ?>
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="POST"> <!-- Method spoofing for UPDATE -->

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="<?= old('title', esc($agreement['title'])) ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                <select class="form-select" id="branch_id" name="branch_id" required>
                    <option value="">Select Branch</option>
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?= $branch['id'] ?>" <?= old('branch_id', $agreement['branch_id']) == $branch['id'] ? 'selected' : '' ?>><?= esc($branch['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?= old('description', esc($agreement['description'])) ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="agreement_type" class="form-label">Agreement Type</label>
                <input type="text" class="form-control" id="agreement_type" name="agreement_type" value="<?= old('agreement_type', esc($agreement['agreement_type'])) ?>">
                 <div class="form-text">E.g., MOU, Contract, SLA</div>
           </div>
            <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="draft" <?= old('status', $agreement['status']) == 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="active" <?= old('status', $agreement['status']) == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="expired" <?= old('status', $agreement['status']) == 'expired' ? 'selected' : '' ?>>Expired</option>
                    <option value="terminated" <?= old('status', $agreement['status']) == 'terminated' ? 'selected' : '' ?>>Terminated</option>
                    <option value="archived" <?= old('status', $agreement['status']) == 'archived' ? 'selected' : '' ?>>Archived</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="parties" class="form-label">Parties Involved</label>
            <?php 
                // Decode parties if it's JSON, otherwise use as is
                $partiesValue = $agreement['parties'];
                if (is_array($partiesValue)) {
                    $partiesValue = implode(', ', $partiesValue);
                }
            ?>
            <input type="text" class="form-control" id="parties" name="parties" value="<?= old('parties', esc($partiesValue)) ?>">
            <div class="form-text">Enter names or entities involved, separated by commas.</div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="effective_date" class="form-label">Effective Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="effective_date" name="effective_date" value="<?= old('effective_date', date('Y-m-d', strtotime($agreement['effective_date']))) ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="expiry_date" class="form-label">Expiry Date</label>
                <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="<?= old('expiry_date', !empty($agreement['expiry_date']) ? date('Y-m-d', strtotime($agreement['expiry_date'])) : '') ?>">
            </div>
        </div>

        <div class="mb-3">
            <label for="terms" class="form-label">Terms</label>
            <textarea class="form-control" id="terms" name="terms" rows="3"><?= old('terms', esc($agreement['terms'])) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="conditions" class="form-label">Conditions</label>
            <textarea class="form-control" id="conditions" name="conditions" rows="3"><?= old('conditions', esc($agreement['conditions'])) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Attachments</label>
            <?php if (!empty($agreement['attachments']) && is_array($agreement['attachments'])): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($agreement['attachments'] as $index => $attachment): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="<?= base_url('agreements/download/' . $agreement['id'] . '/' . $index) ?>" target="_blank">
                                <i class="fas fa-file-alt me-2"></i><?= esc($attachment['original_name']) ?>
                            </a>
                             <!-- Add a delete button/link here if needed -->
                             <!-- Example: Use a form for each delete to handle standard submission -->
                             <!--
                             <form action="<?= base_url('agreements/delete_attachment/' . $agreement['id'] . '/' . $index) ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this attachment?');" style="display:inline;">
                                 <?= csrf_field() ?>
                                 <input type="hidden" name="_method" value="POST"> 
                                 <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                             </form>
                             -->
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No attachments found.</p>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="attachments" class="form-label">Add New Attachments</label>
            <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
            <div class="form-text">Upload new files to add them to the agreement.</div>
        </div>

        <div class="mb-3">
            <label for="remarks" class="form-label">Remarks</label>
            <textarea class="form-control" id="remarks" name="remarks" rows="2"><?= old('remarks', esc($agreement['remarks'])) ?></textarea>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update Agreement</button>
            <a href="<?= base_url('agreements/' . $agreement['id']) ?>" class="btn btn-secondary">Cancel</a>
        </div>

        <?= form_close() ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Add any specific JS for this form if needed
    $(document).ready(function() {
        // Example: Confirmation for attachment deletion if using standard forms
        // $('.delete-attachment-form').on('submit', function() {
        //     return confirm('Are you sure you want to delete this attachment?');
        // });
    });
</script>
<?= $this->endSection() ?> 