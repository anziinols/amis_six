<?php
// app/Views/admin/corporate_plans/corporate_plans_overarching_objectives.php
$this->extend('templates/system_template');
$this->section('content');
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <div>
                <a href="<?= base_url('admin/corporate-plans') ?>" class="btn btn-secondary mr-2">
                    <i class="fas fa-arrow-left"></i> Back to Corporate Plans
                </a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOverarchingObjectiveModal">
                    <i class="fas fa-plus"></i> Add Overarching Objective
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Flash Messages -->
            <?php if (session()->has('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('errors')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                    <?php foreach (session('errors') as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="overarchingObjectivesTable">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Title</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($objectives as $objective) : ?>
                            <tr>
                                <td><?= $objective['code'] ?></td>
                                <td><?= $objective['title'] ?></td>
                                <td><?= $objective['remarks'] ?></td>
                                <td>
                                    <span class="badge badge-<?= $objective['corp_plan_status'] == 1 ? 'success' : 'danger' ?>">
                                        <?= $objective['corp_plan_status'] == 1 ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group flex-wrap" role="group" aria-label="Objective Actions">
                                        <a href="<?= base_url('admin/corporate-plans/objectives/' . $objective['id']) ?>"
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i><span class="d-none d-md-inline"> View</span>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-warning edit-overarching-objective"
                                            data-id="<?= $objective['id'] ?>"
                                            data-bs-toggle="modal" data-bs-target="#editOverarchingObjectiveModal">
                                            <i class="fas fa-edit"></i><span class="d-none d-md-inline"> Edit</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-<?= $objective['corp_plan_status'] == 1 ? 'danger' : 'success' ?> toggle-status"
                                            data-id="<?= $objective['id'] ?>">
                                            <i class="fas fa-<?= $objective['corp_plan_status'] == 1 ? 'ban' : 'check' ?>"></i>
                                            <span class="d-none d-md-inline"> <?= $objective['corp_plan_status'] == 1 ? 'Deactivate' : 'Activate' ?></span>
                                        </button>
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

<!-- Add Overarching Objective Modal -->
<div class="modal fade" id="addOverarchingObjectiveModal" tabindex="-1" role="dialog" aria-labelledby="addOverarchingObjectiveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOverarchingObjectiveModalLabel">Add Overarching Objective</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addOverarchingObjectiveForm" action="<?= base_url('admin/corporate-plans/overarching-objectives') ?>" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" name="corporate_plan_id" value="<?= $parentPlan['id'] ?>">
                    <div class="form-group mb-3">
                        <label for="code">Code</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="remarks">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Overarching Objective Modal -->
<div class="modal fade" id="editOverarchingObjectiveModal" tabindex="-1" role="dialog" aria-labelledby="editOverarchingObjectiveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editOverarchingObjectiveModalLabel">Edit Overarching Objective</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editOverarchingObjectiveForm" action="<?= base_url('admin/corporate-plans/overarching-objectives') ?>">
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
    $('#overarchingObjectivesTable').DataTable({
        "responsive": true,
        "order": [[0, "asc"]],
        "language": {
            "search": "Search Objectives:"
        }
    });

    // Add Overarching Objective - Disabled AJAX handling to use standard form submission
    /*
    $('#addOverarchingObjectiveForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Show success message
                    toastr.success(response.message);
                    // Close modal
                    var modal = bootstrap.Modal.getInstance(document.getElementById('addOverarchingObjectiveModal'));
                    modal.hide();
                    // Add delay before reload to allow toastr to display
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('An error occurred. Please try again.');
            }
        });
    });
    */

    // Edit Overarching Objective - fetch data via AJAX
    $('.edit-overarching-objective').on('click', function() {
        const id = $(this).data('id');

        // Fetch the latest data from the server
        $.ajax({
            url: '<?= base_url('admin/corporate-plans/overarching-objectives/') ?>' + id + '/edit',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const objective = response.data;

                    $('#edit_id').val(objective.id);
                    $('#edit_code').val(objective.code);
                    $('#edit_title').val(objective.title);
                    $('#edit_remarks').val(objective.remarks);

                    // Update the form action to include the ID
                    $('#editOverarchingObjectiveForm').attr('action',
                        '<?= base_url('admin/corporate-plans/overarching-objectives/') ?>' + objective.id);
                } else {
                    toastr.error(response.message || 'Failed to load objective data');
                }
            },
            error: function() {
                toastr.error('An error occurred while retrieving objective data.');
            }
        });
    });

    // Edit Overarching Objective - AJAX form submission
    $('#editOverarchingObjectiveForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit_id').val();

        $.ajax({
            url: '<?= base_url('admin/corporate-plans/overarching-objectives/') ?>' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Show success message
                    toastr.success(response.message);
                    // Close modal
                    var modal = bootstrap.Modal.getInstance(document.getElementById('editOverarchingObjectiveModal'));
                    modal.hide();
                    // Add delay before reload to allow toastr to display
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('An error occurred. Please try again.');
            }
        });
    });

    // Toggle Status
    $('.toggle-status').on('click', function() {
        if (confirm('Are you sure you want to change the status of this Overarching Objective?')) {
            const id = $(this).data('id');

            $.ajax({
                url: '<?= base_url('admin/corporate-plans/overarching-objectives/') ?>' + id + '/toggle-status',
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
                        }, 1500);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred. Please try again.');
                }
            });
        }
    });
});
</script>
<?php $this->endSection() ?>

<?php $this->endSection() ?>
