<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<style>
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
<div class="mb-3">
    <a href="<?= base_url('workplans') ?>" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left"></i> Back to Workplan List
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Workplan Details</h5>
        <div>
            <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities') ?>" class="btn btn-success btn-sm">
                <i class="fas fa-tasks"></i> View Activities
            </a>
            <a href="<?= base_url('workplans/edit/' . $workplan['id']) ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fas fa-trash"></i> Delete
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">Title</th>
                        <td><?= esc($workplan['title']) ?></td>
                    </tr>
                    <tr>
                        <th>Branch</th>
                        <td><?= esc($workplan['branch_name'] ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <th>Supervisor</th>
                        <td><?= esc($workplan['supervisor_name'] ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
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
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">Start Date</th>
                        <td><?= date('d M Y', strtotime($workplan['start_date'])) ?></td>
                    </tr>
                    <tr>
                        <th>End Date</th>
                        <td><?= !empty($workplan['end_date']) ? date('d M Y', strtotime($workplan['end_date'])) : 'N/A' ?></td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td><?= date('d M Y H:i', strtotime($workplan['created_at'])) ?></td>
                    </tr>
                    <tr>
                        <th>Last Updated</th>
                        <td><?= !empty($workplan['updated_at']) ? date('d M Y H:i', strtotime($workplan['updated_at'])) : 'N/A' ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <h6>Description</h6>
            <div class="p-3 bg-light rounded">
                <?= nl2br(esc($workplan['description'] ?? 'No description provided.')) ?>
            </div>
        </div>

        <div class="mt-4">
            <h6>Objectives</h6>
            <div class="p-3 bg-light rounded">
                <?= nl2br(esc($workplan['objectives'] ?? 'No objectives provided.')) ?>
            </div>
        </div>

        <?php if (!empty($workplan['remarks'])): ?>
        <div class="mt-4">
            <h6>Remarks</h6>
            <div class="p-3 bg-light rounded">
                <?= nl2br(esc($workplan['remarks'])) ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this workplan? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="<?= base_url('workplans/delete/' . $workplan['id']) ?>" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
