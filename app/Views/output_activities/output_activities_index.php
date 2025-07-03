<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<style>
    .status-pending {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeaa7;
    }
    
    .status-approved {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    
    .status-rejected {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    
    .status-default {
        color: #41464b;
        background-color: #e2e3e5;
        border-color: #d3d6d8;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800"><?= esc($title) ?></h1>
                    <p class="mb-0 text-muted">Manage output activities and deliverables</p>
                </div>
                <div>
                    <a href="<?= base_url('output-activities/new') ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create New Output Activity
                    </a>
                </div>
            </div>

            <!-- Output Activities Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-box me-2"></i>Output Activities
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($outputActivities)): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="outputActivitiesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Workplan</th>
                                        <th>Activity</th>
                                        <th>Delivery Date</th>
                                        <th>Delivery Location</th>
                                        <th>Total Value</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($outputActivities as $index => $output): ?>
                                        <tr>
                                            <td><?= esc($index + 1) ?></td>
                                            <td>
                                                <strong><?= esc($output['workplan_title'] ?? 'N/A') ?></strong>
                                            </td>
                                            <td>
                                                <?= esc($output['activity_title'] ?? 'N/A') ?>
                                                <?php if (!empty($output['activity_type'])): ?>
                                                    <br><small class="text-muted"><?= ucfirst(esc($output['activity_type'])) ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($output['delivery_date'])): ?>
                                                    <?= date('M d, Y', strtotime($output['delivery_date'])) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Not set</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($output['delivery_location'] ?? 'N/A') ?></td>
                                            <td>
                                                <?php if (!empty($output['total_value'])): ?>
                                                    <strong>K <?= number_format($output['total_value'], 2) ?></strong>
                                                <?php else: ?>
                                                    <span class="text-muted">N/A</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $status = $output['proposal_status'] ?? 'draft';
                                                $statusClass = 'status-default';
                                                switch ($status) {
                                                    case 'pending':
                                                        $statusClass = 'status-pending';
                                                        break;
                                                    case 'approved':
                                                        $statusClass = 'status-approved';
                                                        break;
                                                    case 'rejected':
                                                        $statusClass = 'status-rejected';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?= $statusClass ?>"><?= ucfirst(esc($status)) ?></span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= base_url('output-activities/' . $output['id']) ?>" 
                                                       class="btn btn-sm btn-outline-info" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?= base_url('output-activities/' . $output['id'] . '/edit') ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="confirmDelete(<?= $output['id'] ?>)" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-box fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Output Activities Found</h5>
                            <p class="text-muted">Start by creating your first output activity.</p>
                            <a href="<?= base_url('output-activities/new') ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Output Activity
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
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
                Are you sure you want to delete this output activity? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#outputActivitiesTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[3, 'desc']], // Sort by delivery date descending
        columnDefs: [
            { orderable: false, targets: [7] } // Disable sorting on Actions column
        ]
    });
});

function confirmDelete(id) {
    $('#deleteForm').attr('action', '<?= base_url('output-activities') ?>/' + id + '/delete');
    $('#deleteModal').modal('show');
}

// Display flash messages
<?php if (session()->getFlashdata('success')): ?>
    toastr.success('<?= session()->getFlashdata('success') ?>');
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    toastr.error('<?= session()->getFlashdata('error') ?>');
<?php endif; ?>
</script>

<?= $this->endSection() ?>
