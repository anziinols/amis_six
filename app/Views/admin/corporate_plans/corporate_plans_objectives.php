<?php
// app/Views/admin/corporate_plans/corporate_plans_objectives.php
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
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addObjectiveModal">
                    <i class="fas fa-plus"></i> Add Objective
                </button>
            </div>
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

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="objectivesTable">
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
                                    <span class="badge bg-<?= $objective['corp_plan_status'] == 1 ? 'success' : 'danger' ?>">
                                        <?= $objective['corp_plan_status'] == 1 ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Objective Actions">
                                        <a href="<?= base_url('admin/corporate-plans/kras/' . $objective['id']) ?>"
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i><span class="d-none d-md-inline"> View KRAs</span>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-warning edit-objective"
                                            data-id="<?= $objective['id'] ?>"
                                            data-code="<?= htmlspecialchars($objective['code']) ?>"
                                            data-title="<?= htmlspecialchars($objective['title']) ?>"
                                            data-remarks="<?= htmlspecialchars($objective['remarks']) ?>"
                                            data-bs-toggle="modal" data-bs-target="#editObjectiveModal">
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

<!-- Add Objective Modal -->
<div class="modal fade" id="addObjectiveModal" tabindex="-1" role="dialog" aria-labelledby="addObjectiveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addObjectiveModalLabel">Add Objective</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addObjectiveForm" action="<?= base_url('admin/corporate-plans/objectives') ?>" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" name="parent_id" value="<?= $parentPlan['id'] ?>">

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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Objective Modal -->
<div class="modal fade" id="editObjectiveModal" tabindex="-1" role="dialog" aria-labelledby="editObjectiveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editObjectiveModalLabel">Edit Objective</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editObjectiveForm" action="<?= base_url('admin/corporate-plans/objectives') ?>" method="post">
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#objectivesTable').DataTable({
        responsive: true,
        "order": [[ 0, "asc" ]]
    });

    // Edit Objective - populate form data
    $('.edit-objective').on('click', function() {
        const id = $(this).data('id');
        const code = $(this).data('code');
        const title = $(this).data('title');
        const remarks = $(this).data('remarks');

        $('#edit_id').val(id);
        $('#edit_code').val(code);
        $('#edit_title').val(title);
        $('#edit_remarks').val(remarks);

        // Update the form action to include the ID
        $('#editObjectiveForm').attr('action', '<?= base_url('admin/corporate-plans/objectives/') ?>' + id);
    });

    // Toggle Status - use form submission
    $('.toggle-status').on('click', function() {
        if (confirm('Are you sure you want to change the status of this Objective?')) {
            const id = $(this).data('id');

            // Create a form and submit it
            const form = $('<form>', {
                'method': 'POST',
                'action': '<?= base_url('admin/corporate-plans/objectives/') ?>' + id + '/toggle-status'
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
<?php $this->endSection(); ?>