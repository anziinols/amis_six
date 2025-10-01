<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Edit Others Link</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('workplans') ?>">Workplans</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('workplans/' . $workplan['id'] . '/activities') ?>"><?= esc($workplan['title']) ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/plans') ?>">Plans</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Others Link</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/plans') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Plans
            </a>
        </div>
    </div>

    <!-- Activity Information Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Activity Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 30%">Workplan:</th>
                            <td><?= esc($workplan['title']) ?></td>
                        </tr>
                        <tr>
                            <th>Activity:</th>
                            <td><?= esc($activity['title']) ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 30%">Description:</th>
                            <td><?= !empty($activity['description']) ? esc($activity['description']) : 'No description provided' ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Others Link Form -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Others Link</h5>
        </div>
        <div class="card-body">
            <form method="post" action="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/others/' . $othersLink['id']) ?>">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" value="<?= esc($othersLink['title']) ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3"><?= esc($othersLink['description'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="justification" class="form-label">Justification <span class="text-danger">*</span></label>
                            <textarea name="justification" id="justification" class="form-control" rows="3" required><?= esc($othersLink['justification']) ?></textarea>
                            <small class="text-muted">Explain why this activity is necessary and how it contributes to the overall objectives.</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="active" <?= $othersLink['status'] === 'active' || empty($othersLink['status']) ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= $othersLink['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                <option value="completed" <?= $othersLink['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="cancelled" <?= $othersLink['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="expected_outcome" class="form-label">Expected Outcome</label>
                            <textarea name="expected_outcome" id="expected_outcome" class="form-control" rows="2"><?= esc($othersLink['expected_outcome'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="target_beneficiaries" class="form-label">Target Beneficiaries</label>
                            <textarea name="target_beneficiaries" id="target_beneficiaries" class="form-control" rows="2"><?= esc($othersLink['target_beneficiaries'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="budget_estimate" class="form-label">Budget Estimate (K)</label>
                            <input type="number" step="0.01" name="budget_estimate" id="budget_estimate" class="form-control" value="<?= esc($othersLink['budget_estimate'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="<?= esc($othersLink['start_date'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="<?= esc($othersLink['end_date'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="2"><?= esc($othersLink['remarks'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-end">
                            <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/plans') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Others Link
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Link Information -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Link Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 30%">Created:</th>
                            <td><?= date('d M Y H:i', strtotime($othersLink['created_at'])) ?></td>
                        </tr>
                        <tr>
                            <th>Updated:</th>
                            <td><?= date('d M Y H:i', strtotime($othersLink['updated_at'])) ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Show success/error messages
    <?php if (session()->getFlashdata('success')): ?>
        toastr.success('<?= session()->getFlashdata('success') ?>');
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        toastr.error('<?= session()->getFlashdata('error') ?>');
    <?php endif; ?>
});
</script>
<?= $this->endSection() ?>
