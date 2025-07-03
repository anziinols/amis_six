<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Workplans</h5>
        <a href="<?= base_url('workplans/new') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New Workplan
        </a>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Branch</th>
                        <th>Supervisor</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($workplans)): ?>
                        <?php foreach ($workplans as $index => $workplan): ?>
                            <tr>
                                <td><?= esc($index + 1) ?></td>
                                <td><?= esc($workplan['title']) ?></td>
                                <td><?= esc($workplan['branch_name'] ?? 'N/A') ?></td>
                                <td><?= esc($workplan['supervisor_name'] ?? 'N/A') ?></td>
                                <td><?= esc(date('Y-m-d', strtotime($workplan['start_date']))) ?></td>
                                <td><?= esc(date('Y-m-d', strtotime($workplan['end_date']))) ?></td>
                                <td>
                                    <?php
                                        // Map status values to text and colors
                                        $statusMap = [
                                            'draft' => 'Draft',
                                            'in_progress' => 'In Progress',
                                            'completed' => 'Completed',
                                            'on_hold' => 'On Hold',
                                            'cancelled' => 'Cancelled'
                                        ];

                                        $statusClass = [
                                            'draft' => 'status-draft',
                                            'in_progress' => 'status-in-progress',
                                            'completed' => 'status-completed',
                                            'on_hold' => 'status-on-hold',
                                            'cancelled' => 'status-cancelled'
                                        ];

                                        $status = $workplan['status'] ?? 'draft';
                                        $statusText = $statusMap[$status] ?? ucfirst(str_replace('_', ' ', $status));
                                        $statusClassName = $statusClass[$status] ?? 'status-default';
                                    ?>
                                    <span class="status-badge <?= $statusClassName ?>"><?= esc($statusText) ?></span>
                                </td>
                                <td>
                                    <a href="<?= base_url('workplans/' . $workplan['id']) ?>" class="btn btn-info btn-sm" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities') ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-tasks"></i> View Activities
                                    </a>
                                    <a href="<?= base_url('workplans/edit/' . $workplan['id']) ?>" class="btn btn-primary btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?= form_open('workplans/delete/' . $workplan['id'], ['style' => 'display:inline-block;', 'onsubmit' => 'return confirm("Are you sure you want to delete this workplan?");']) ?>
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?= form_close() ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No workplans found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
    /* Additional styling improvements */
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }

    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    /* Status badge styles with dark text on light backgrounds */
    .status-badge {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.85em;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        border: 1px solid transparent;
    }

    .status-draft {
        color: #495057;
        background-color: #e9ecef;
        border-color: #ced4da;
    }

    .status-in-progress {
        color: #084298;
        background-color: #cfe2ff;
        border-color: #b6d4fe;
    }

    .status-completed {
        color: #0f5132;
        background-color: #d1e7dd;
        border-color: #badbcc;
    }

    .status-on-hold {
        color: #664d03;
        background-color: #fff3cd;
        border-color: #ffecb5;
    }

    .status-cancelled {
        color: #842029;
        background-color: #f8d7da;
        border-color: #f5c2c7;
    }

    .status-default {
        color: #41464b;
        background-color: #e2e3e5;
        border-color: #d3d6d8;
    }
</style>
<?= $this->endSection() ?>