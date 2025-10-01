<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <a href="<?= base_url('duty-instructions/new') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Duty Instruction
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
                            <th>Instruction Number</th>
                            <th>Title</th>
                            <th>Workplan</th>
                            <th>Assigned User</th>
                            <th>Supervisor</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($duty_instructions)): ?>
                            <?php foreach ($duty_instructions as $key => $instruction): ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?= esc($instruction['duty_instruction_number']) ?></span>
                                    </td>
                                    <td>
                                        <strong><?= esc($instruction['duty_instruction_title']) ?></strong>
                                        <?php if (!empty($instruction['duty_instruction_description'])): ?>
                                            <br><small class="text-muted"><?= esc(substr($instruction['duty_instruction_description'], 0, 100)) ?>...</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($instruction['workplan_title'])): ?>
                                            <span class="badge bg-info"><?= esc($instruction['workplan_title']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">No workplan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($instruction['user_name'])): ?>
                                            <span class="badge bg-primary"><?= esc($instruction['user_name']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Unassigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($instruction['supervisor_name'])): ?>
                                            <span class="badge bg-success"><?= esc($instruction['supervisor_name']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">No supervisor</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = match($instruction['status']) {
                                            'pending' => 'bg-warning',
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            'completed' => 'bg-primary',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $statusClass ?>"><?= esc(ucfirst($instruction['status'])) ?></span>
                                    </td>
                                    <td>
                                        <?= date('M d, Y', strtotime($instruction['created_at'])) ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('duty-instructions/' . $instruction['id']) ?>"
                                               class="btn btn-outline-primary"
                                               title="View Details"
                                               style="margin-right: 5px;">
                                                <i class="fas fa-eye me-1"></i> View Duty Items
                                            </a>
                                            <?php if (!isset($instruction['has_myactivities_links']) || !$instruction['has_myactivities_links']): ?>
                                            <a href="<?= base_url('duty-instructions/' . $instruction['id'] . '/edit') ?>"
                                               class="btn btn-outline-warning"
                                               title="Edit"
                                               style="margin-right: 5px;">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            <a href="<?= base_url('duty-instructions/' . $instruction['id'] . '/delete') ?>"
                                               class="btn btn-outline-danger"
                                               title="Delete"
                                               onclick="return confirm('Are you sure you want to delete this duty instruction?')">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </a>
                                            <?php else: ?>
                                            <button class="btn btn-outline-secondary"
                                                    title="Cannot edit - Duty instruction items are linked to My Activities"
                                                    disabled
                                                    style="margin-right: 5px;">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </button>
                                            <button class="btn btn-outline-secondary"
                                                    title="Cannot delete - Duty instruction items are linked to My Activities"
                                                    disabled>
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                                    <br>No duty instructions found.
                                    <br><a href="<?= base_url('duty-instructions/new') ?>" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus"></i> Create First Duty Instruction
                                    </a>
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
