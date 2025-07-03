<?php
// app/Views/admin/corporate_plans/corporate_plans_list.php
$this->extend('templates/system_template');
$this->section('content');
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCorporatePlanModal">
                <i class="fas fa-plus"></i> Add Corporate Plan
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="corporatePlansTable">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Title</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($plans as $plan) : ?>
                            <tr>
                                <td><?= $plan['code'] ?></td>
                                <td><?= $plan['title'] ?></td>
                                <td><?= date('d-m-Y', strtotime($plan['date_from'])) ?></td>
                                <td><?= date('d-m-Y', strtotime($plan['date_to'])) ?></td>
                                <td>
                                    <span class="badge bg-<?= $plan['corp_plan_status'] == 1 ? 'success' : 'danger' ?>">
                                        <?= $plan['corp_plan_status'] == 1 ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group flex-wrap" role="group" aria-label="Plan Actions">
                                        <a href="<?= base_url('admin/corporate-plans/overarching-objectives/' . $plan['id']) ?>"
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i><span class="d-none d-md-inline"> View Overarching Objectives</span>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-warning edit-plan"
                                            data-id="<?= $plan['id'] ?>"
                                            data-bs-toggle="modal" data-bs-target="#editCorporatePlanModal">
                                            <i class="fas fa-edit"></i><span class="d-none d-md-inline"> Edit</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-<?= $plan['corp_plan_status'] == 1 ? 'danger' : 'success' ?> toggle-status"
                                            data-id="<?= $plan['id'] ?>">
                                            <i class="fas fa-<?= $plan['corp_plan_status'] == 1 ? 'ban' : 'check' ?>"></i>
                                            <span class="d-none d-md-inline"><?= $plan['corp_plan_status'] == 1 ? 'Deactivate' : 'Activate' ?></span>
                                        </button>
                                        <form action="<?= base_url('admin/corporate-plans/' . $plan['id'] . '/delete') ?>" method="post" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this Corporate Plan and all its children? This action cannot be undone.');">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i><span class="d-none d-md-inline"> Delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Corporate Plan Modal -->
<div class="modal fade" id="addCorporatePlanModal" tabindex="-1" role="dialog" aria-labelledby="addCorporatePlanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCorporatePlanModalLabel">Add Corporate Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCorporatePlanForm">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label for="code">Code</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="date_from">Start Date</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="date_to">End Date</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Corporate Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Corporate Plan Modal -->
<div class="modal fade" id="editCorporatePlanModal" tabindex="-1" role="dialog" aria-labelledby="editCorporatePlanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCorporatePlanModalLabel">Edit Corporate Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCorporatePlanForm">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group mb-3">
                        <label for="edit_code">Code</label>
                        <input type="text" class="form-control" id="edit_code" name="code" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_title">Title</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_date_from">Start Date</label>
                        <input type="date" class="form-control" id="edit_date_from" name="date_from" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_date_to">End Date</label>
                        <input type="date" class="form-control" id="edit_date_to" name="date_to" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_remarks">Remarks</label>
                        <textarea class="form-control" id="edit_remarks" name="remarks" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#corporatePlansTable').DataTable({
        "responsive": true,
        "order": [[0, "asc"]],
        "language": {
            "search": "Search Corporate Plans:"
        }
    });

    // Add Corporate Plan
    $('#addCorporatePlanForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '<?= base_url('admin/corporate-plans') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Show success message
                    toastr.success(response.message);
                    // Close modal
                    $('#addCorporatePlanModal').modal('hide');
                    // Add delay before reload to allow toastr to display
                    setTimeout(function() {
                        location.reload();
                    }, 2000); // 2 second delay
                } else {
                    // Show error message
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('An error occurred while processing your request.');
            }
        });
    });

    // Edit Corporate Plan - fetch data via AJAX instead of data attributes
    $('.edit-plan').on('click', function() {
        const id = $(this).data('id');

        // Fetch the latest data from the server
        $.ajax({
            url: '<?= base_url('admin/corporate-plans/') ?>' + id + '/edit',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const plan = response.data;

                    // Format dates for input field (YYYY-MM-DD)
                    const formattedDateFrom = new Date(plan.date_from).toISOString().split('T')[0];
                    const formattedDateTo = new Date(plan.date_to).toISOString().split('T')[0];

                    $('#edit_id').val(plan.id);
                    $('#edit_code').val(plan.code);
                    $('#edit_title').val(plan.title);
                    $('#edit_date_from').val(formattedDateFrom);
                    $('#edit_date_to').val(formattedDateTo);
                    $('#edit_remarks').val(plan.remarks);
                } else {
                    toastr.error(response.message || 'Failed to load corporate plan data');
                }
            },
            error: function() {
                toastr.error('An error occurred while retrieving the corporate plan data.');
            }
        });
    });

    // Update Corporate Plan
    $('#editCorporatePlanForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit_id').val();

        $.ajax({
            url: '<?= base_url('admin/corporate-plans/') ?>' + id,
            type: 'POST', // Using POST as a fallback for better form compatibility
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Show success message
                    toastr.success(response.message);
                    // Close modal
                    $('#editCorporatePlanModal').modal('hide');
                    // Add delay before reload to allow toastr to display
                    setTimeout(function() {
                        location.reload();
                    }, 2000); // 2 second delay
                } else {
                    // Show error message
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('An error occurred while processing your request.');
            }
        });
    });

    // Toggle Status
    $('.toggle-status').on('click', function() {
        if (confirm('Are you sure you want to change the status of this Corporate Plan?')) {
            const id = $(this).data('id');

            $.ajax({
                url: '<?= base_url('admin/corporate-plans/') ?>' + id + '/toggle-status',
                type: 'POST',
                data: { <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Show success message
                        toastr.success(response.message);
                        // Add delay before reload to allow toastr to display
                        setTimeout(function() {
                            location.reload();
                        }, 2000); // 2 second delay
                    } else {
                        // Show error message
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while processing your request.');
                }
            });
        }
    });
});
</script>
<?php $this->endSection() ?>

<?php $this->endSection() ?>
