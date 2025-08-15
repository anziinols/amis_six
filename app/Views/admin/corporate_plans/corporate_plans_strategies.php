<?php
// app/Views/admin/corporate_plans/corporate_plans_strategies.php
$this->extend('templates/system_template');
$this->section('content');
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <div>
                <a href="<?= base_url('admin/corporate-plans/kras/' . $parentKra['parent_id']) ?>" class="btn btn-secondary mr-2">
                    <i class="fas fa-arrow-left"></i> Back to KRAs
                </a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStrategyModal">
                    <i class="fas fa-plus"></i> Add Strategy
                </button>
            </div>
        </div>
        <div class="card-body">


            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="strategiesTable">
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
                        <?php foreach ($strategies as $strategy) : ?>
                            <tr>
                                <td><?= $strategy['code'] ?></td>
                                <td><?= $strategy['title'] ?></td>
                                <td><?= $strategy['remarks'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $strategy['corp_plan_status'] == 1 ? 'success' : 'danger' ?>">
                                        <?= $strategy['corp_plan_status'] == 1 ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group flex-wrap" role="group" aria-label="Strategy Actions">
                                        <button type="button" class="btn btn-sm btn-warning edit-strategy"
                                            data-id="<?= $strategy['id'] ?>"
                                            data-code="<?= htmlspecialchars($strategy['code']) ?>"
                                            data-title="<?= htmlspecialchars($strategy['title']) ?>"
                                            data-remarks="<?= htmlspecialchars($strategy['remarks']) ?>"
                                            data-bs-toggle="modal" data-bs-target="#editStrategyModal">
                                            <i class="fas fa-edit"></i><span class="d-none d-md-inline"> Edit</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-<?= $strategy['corp_plan_status'] == 1 ? 'danger' : 'success' ?> toggle-status"
                                            data-id="<?= $strategy['id'] ?>">
                                            <i class="fas fa-<?= $strategy['corp_plan_status'] == 1 ? 'ban' : 'check' ?>"></i>
                                            <span class="d-none d-md-inline"> <?= $strategy['corp_plan_status'] == 1 ? 'Deactivate' : 'Activate' ?></span>
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

<!-- Add Strategy Modal -->
<div class="modal fade" id="addStrategyModal" tabindex="-1" role="dialog" aria-labelledby="addStrategyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStrategyModalLabel">Add Strategy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addStrategyForm" action="<?= base_url('admin/corporate-plans/strategies') ?>" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" name="parent_id" value="<?= $parentKra['id'] ?>">
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

<!-- Edit Strategy Modal -->
<div class="modal fade" id="editStrategyModal" tabindex="-1" role="dialog" aria-labelledby="editStrategyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStrategyModalLabel">Edit Strategy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editStrategyForm" action="<?= base_url('admin/corporate-plans/strategies') ?>" method="post">
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
    $('#strategiesTable').DataTable();

    // Edit Strategy - populate form data
    $('.edit-strategy').on('click', function() {
        const id = $(this).data('id');
        const code = $(this).data('code');
        const title = $(this).data('title');
        const remarks = $(this).data('remarks');

        $('#edit_id').val(id);
        $('#edit_code').val(code);
        $('#edit_title').val(title);
        $('#edit_remarks').val(remarks);

        // Update the form action to include the ID
        $('#editStrategyForm').attr('action', '<?= base_url('admin/corporate-plans/strategies/') ?>' + id);
    });

    // Toggle Status - use form submission
    $('.toggle-status').on('click', function() {
        if (confirm('Are you sure you want to change the status of this Strategy?')) {
            const id = $(this).data('id');

            // Create a form and submit it
            const form = $('<form>', {
                'method': 'POST',
                'action': '<?= base_url('admin/corporate-plans/strategies/') ?>' + id + '/toggle-status'
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
