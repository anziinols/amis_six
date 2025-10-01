<?php
// app/Views/admin/corporate_plans/corporate_plans_list.php
$this->extend('templates/system_template');
$this->section('content');
?>

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Corporate Plans</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0"><?= esc($title) ?></h3>
                    <div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCorporatePlanModal">
                            <i class="fas fa-plus"></i> Add Corporate Plan
                        </button>
                    </div>
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
                                <td><?= esc($plan['code']) ?></td>
                                <td><?= esc($plan['title']) ?></td>
                                <td><?= date('d-m-Y', strtotime($plan['date_from'])) ?></td>
                                <td><?= date('d-m-Y', strtotime($plan['date_to'])) ?></td>
                                <td>
                                    <span class="badge bg-<?= $plan['corp_plan_status'] == 1 ? 'success' : 'danger' ?>">
                                        <?= $plan['corp_plan_status'] == 1 ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/corporate-plans/objectives/' . $plan['id']) ?>"
                                       class="btn btn-outline-primary btn-sm" style="margin-right: 5px;">
                                        <i class="fas fa-eye me-1"></i> View Objectives
                                    </a>
                                    <button type="button" class="btn btn-outline-warning btn-sm edit-plan"
                                        data-id="<?= $plan['id'] ?>"
                                        data-code="<?= esc($plan['code']) ?>"
                                        data-title="<?= esc($plan['title']) ?>"
                                        data-date-from="<?= $plan['date_from'] ?>"
                                        data-date-to="<?= $plan['date_to'] ?>"
                                        data-remarks="<?= esc($plan['remarks']) ?>"
                                        data-bs-toggle="modal" data-bs-target="#editCorporatePlanModal"
                                        style="margin-right: 5px;">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-outline-<?= $plan['corp_plan_status'] == 1 ? 'secondary' : 'success' ?> btn-sm toggle-status"
                                        data-id="<?= $plan['id'] ?>"
                                        style="margin-right: 5px;">
                                        <i class="fas fa-<?= $plan['corp_plan_status'] == 1 ? 'ban' : 'check' ?> me-1"></i>
                                        <?= $plan['corp_plan_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                                    </button>
                                    <form action="<?= base_url('admin/corporate-plans/' . $plan['id'] . '/delete') ?>" method="post" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this Corporate Plan and all its children? This action cannot be undone.');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
                </div>
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
            <form id="addCorporatePlanForm" action="<?= base_url('admin/corporate-plans') ?>" method="post">
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
            <form id="editCorporatePlanForm" action="<?= base_url('admin/corporate-plans') ?>" method="post">
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

    // Edit Corporate Plan - populate form data
    $('.edit-plan').on('click', function() {
        const id = $(this).data('id');
        const code = $(this).data('code');
        const title = $(this).data('title');
        const dateFrom = $(this).data('date-from');
        const dateTo = $(this).data('date-to');
        const remarks = $(this).data('remarks');

        $('#edit_id').val(id);
        $('#edit_code').val(code);
        $('#edit_title').val(title);
        $('#edit_date_from').val(dateFrom);
        $('#edit_date_to').val(dateTo);
        $('#edit_remarks').val(remarks);

        // Update the form action to include the ID
        $('#editCorporatePlanForm').attr('action', '<?= base_url('admin/corporate-plans/') ?>' + id);
    });

    // Toggle Status - use form submission
    $('.toggle-status').on('click', function() {
        if (confirm('Are you sure you want to change the status of this Corporate Plan?')) {
            const id = $(this).data('id');

            // Create a form and submit it
            const form = $('<form>', {
                'method': 'POST',
                'action': '<?= base_url('admin/corporate-plans/') ?>' + id + '/toggle-status'
            });

            form.append($('<input>', {
                'type': 'hidden',
                'name': '<?= csrf_token() ?>',
                'value': '<?= csrf_hash() ?>'
            }));

            $('body').append(form);
            form.submit();
        }
    });
});
</script>
<?php $this->endSection() ?>

<?php $this->endSection() ?>
