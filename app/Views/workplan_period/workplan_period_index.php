<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <a href="<?= base_url('workplan-period/new') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Workplan Period
            </a>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>User</th>
                            <th>Duty Instruction</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($workplan_periods)): ?>
                            <?php foreach ($workplan_periods as $key => $period): ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td>
                                        <strong><?= esc($period['title']) ?></strong>
                                        <?php if ($period['description']): ?>
                                            <br><small class="text-muted"><?= esc(substr($period['description'], 0, 100)) ?><?= strlen($period['description']) > 100 ? '...' : '' ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?= esc($period['user_name']) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($period['duty_instruction_title']): ?>
                                            <span class="badge bg-secondary"><?= esc($period['duty_instruction_title']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = match($period['status']) {
                                            'pending' => 'bg-warning',
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $statusClass ?>"><?= ucfirst(esc($period['status'])) ?></span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($period['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('workplan-period/' . $period['id']) ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('workplan-period/' . $period['id'] . '/kra') ?>" class="btn btn-sm btn-outline-success" title="View KRAs">
                                                <i class="fas fa-list"></i>
                                            </a>
                                            <a href="<?= base_url('workplan-period/' . $period['id'] . '/edit') ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('workplan-period/' . $period['id'] . '/delete') ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this workplan period?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No workplan periods found.</p>
                                        <a href="<?= base_url('workplan-period/new') ?>" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Create First Workplan Period
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
