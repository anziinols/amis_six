<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Others Links</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('workplans') ?>">Workplans</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('workplans/' . $workplan['id'] . '/activities') ?>"><?= esc($workplan['title']) ?></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/plans') ?>">Plans</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Others Links</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/plans') ?>" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Back to Plans
            </a>
            <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/others/new') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Others Link
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

    <!-- Others Links Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Others Links</h5>
        </div>
        <div class="card-body">
            <?php if (empty($othersLinks)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No others links have been created for this activity yet.
                    <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/others/new') ?>" class="alert-link">Create one now</a>.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($othersLinks as $index => $link): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <?php
                                    $typeClass = '';
                                    switch ($link['link_type']) {
                                        case 'recurrent':
                                            $typeClass = 'bg-primary';
                                            break;
                                        case 'special_project':
                                            $typeClass = 'bg-success';
                                            break;
                                        case 'emergency':
                                            $typeClass = 'bg-danger';
                                            break;
                                        default:
                                            $typeClass = 'bg-secondary';
                                    }
                                    ?>
                                    <span class="badge <?= $typeClass ?>"><?= ucfirst(str_replace('_', ' ', esc($link['link_type']))) ?></span>
                                </td>
                                <td>
                                    <strong><?= esc($link['title']) ?></strong>
                                    <?php if (!empty($link['description'])): ?>
                                        <br><small class="text-muted"><?= esc(substr($link['description'], 0, 100)) ?><?= strlen($link['description']) > 100 ? '...' : '' ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($link['category'] ?? 'N/A') ?></td>
                                <td>
                                    <?php
                                    $priorityClass = '';
                                    switch ($link['priority_level']) {
                                        case 'critical':
                                            $priorityClass = 'bg-danger';
                                            break;
                                        case 'high':
                                            $priorityClass = 'bg-warning';
                                            break;
                                        case 'medium':
                                            $priorityClass = 'bg-info';
                                            break;
                                        default:
                                            $priorityClass = 'bg-secondary';
                                    }
                                    ?>
                                    <span class="badge <?= $priorityClass ?>"><?= ucfirst(esc($link['priority_level'] ?? 'medium')) ?></span>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = '';
                                    switch ($link['status']) {
                                        case 'active':
                                            $statusClass = 'bg-success';
                                            break;
                                        case 'completed':
                                            $statusClass = 'bg-primary';
                                            break;
                                        case 'cancelled':
                                            $statusClass = 'bg-danger';
                                            break;
                                        default:
                                            $statusClass = 'bg-secondary';
                                    }
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= ucfirst(esc($link['status'] ?? 'active')) ?></span>
                                </td>
                                <td><?= date('d M Y', strtotime($link['created_at'])) ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/others/' . $link['id'] . '/edit') ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="post" action="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/others/' . $link['id'] . '/delete') ?>" 
                                              style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this others link?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recurrent Activities Reference -->
    <?php if (!empty($recurrentActivities)): ?>
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Available Recurrent Activities</h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">These are predefined recurrent activities that can be linked to your activity:</p>
            <div class="row">
                <?php foreach ($recurrentActivities as $recurrent): ?>
                <div class="col-md-6 mb-3">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="card-title"><?= esc($recurrent['title']) ?></h6>
                            <p class="card-text text-muted small"><?= esc($recurrent['description']) ?></p>
                            <span class="badge bg-secondary"><?= esc($recurrent['category']) ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
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
