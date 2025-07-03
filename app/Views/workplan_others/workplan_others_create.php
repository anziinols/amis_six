<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Create Others Link</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('workplans') ?>">Workplans</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('workplans/' . $workplan['id'] . '/activities') ?>"><?= esc($workplan['title']) ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/plans') ?>">Plans</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Others Link</li>
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
                        <tr>
                            <th>Type:</th>
                            <td>
                                <span class="badge bg-info"><?= ucfirst(esc($activity['activity_type'])) ?></span>
                            </td>
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

    <!-- Create Others Link Form -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Link to Others</h5>
        </div>
        <div class="card-body">
            <form method="post" action="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/others') ?>">
                <?= csrf_field() ?>

                <!-- Hidden field for link_type -->
                <input type="hidden" name="link_type" value="other">

                <!-- Title Field -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" value="<?= old('title') ?>" required placeholder="Enter the title for this link">
                        </div>
                    </div>
                </div>

                <!-- Description Field -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Provide a brief description (optional)"><?= old('description') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Justification Field -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="justification" class="form-label">Justification <span class="text-danger">*</span></label>
                            <textarea name="justification" id="justification" class="form-control" rows="3" required placeholder="Explain why this link is necessary"><?= old('justification') ?></textarea>
                            <small class="text-muted">Explain why this link is necessary and how it contributes to the overall objectives.</small>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-end">
                            <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/plans') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Link to Others
                            </button>
                        </div>
                    </div>
                </div>
            </form>
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
