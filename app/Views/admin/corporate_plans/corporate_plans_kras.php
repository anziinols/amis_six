<?php
// app/Views/admin/corporate-plans/corporate_plans_kras.php
$this->extend('templates/system_template');
$this->section('content');
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <div>
                <a href="<?= base_url('admin/corporate-plans/objectives/' . $parentObj['parent_id']) ?>" class="btn btn-secondary mr-2">
                    <i class="fas fa-arrow-left"></i> Back to Objectives
                </a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKraModal">
                    <i class="fas fa-plus"></i> Add KRA
                </button>
            </div>
        </div>
        <div class="card-body">


            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="krasTable">
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
                        <?php foreach ($kras as $kra) : ?>
                            <tr>
                                <td><?= $kra['code'] ?></td>
                                <td><?= $kra['title'] ?></td>
                                <td><?= $kra['remarks'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $kra['corp_plan_status'] == 1 ? 'success' : 'danger' ?>">
                                        <?= $kra['corp_plan_status'] == 1 ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group flex-wrap" role="group" aria-label="KRA Actions">
                                        <a href="<?= base_url('admin/corporate-plans/strategies/' . $kra['id']) ?>"
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i><span class="d-none d-md-inline"> View Strategies</span>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-warning edit-kra"
                                            data-id="<?= $kra['id'] ?>"
                                            data-bs-toggle="modal" data-bs-target="#editKraModal">
                                            <i class="fas fa-edit"></i><span class="d-none d-md-inline"> Edit</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-<?= $kra['corp_plan_status'] == 1 ? 'danger' : 'success' ?> toggle-status"
                                            data-id="<?= $kra['id'] ?>">
                                            <i class="fas fa-<?= $kra['corp_plan_status'] == 1 ? 'ban' : 'check' ?>"></i>
                                            <span class="d-none d-md-inline"> <?= $kra['corp_plan_status'] == 1 ? 'Deactivate' : 'Activate' ?></span>
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

<!-- Add KRA Modal -->
<div class="modal fade" id="addKraModal" tabindex="-1" role="dialog" aria-labelledby="addKraModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKraModalLabel">Add KRA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addKraForm" action="<?= base_url('admin/corporate-plans/kras') ?>">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" name="parent_id" value="<?= $parentObj['id'] ?>">
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

<!-- Edit KRA Modal -->
<div class="modal fade" id="editKraModal" tabindex="-1" role="dialog" aria-labelledby="editKraModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editKraModalLabel">Edit KRA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editKraForm" action="<?= base_url('admin/corporate-plans/kras') ?>">
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
    $('#krasTable').DataTable();

    // Add KRA
    $('#addKraForm').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: '<?= base_url('admin/corporate-plans/kras') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Show success message
                    toastr.success(response.message);
                    // Close modal
                    var modal = bootstrap.Modal.getInstance(document.getElementById('addKraModal'));
                    modal.hide();
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

    // Edit KRA - fetch data via AJAX
    $('.edit-kra').on('click', function() {
        const id = $(this).data('id');

        // Fetch the latest data from the server
        $.ajax({
            url: '<?= base_url('admin/corporate-plans/kras/') ?>' + id + '/edit',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const kra = response.data;

                    $('#edit_id').val(kra.id);
                    $('#edit_code').val(kra.code);
                    $('#edit_title').val(kra.title);
                    $('#edit_remarks').val(kra.remarks);

                    // Update the form action to include the ID
                    $('#editKraForm').attr('action',
                        '<?= base_url('admin/corporate-plans/kras/') ?>' + kra.id);
                } else {
                    toastr.error(response.message || 'Failed to load KRA data');
                }
            },
            error: function() {
                toastr.error('An error occurred while retrieving KRA data.');
            }
        });
    });

    // Update KRA
    $('#editKraForm').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit_id').val();

        $.ajax({
            url: '<?= base_url('admin/corporate-plans/kras/') ?>' + id,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Show success message
                    toastr.success(response.message);
                    // Close modal
                    var modal = bootstrap.Modal.getInstance(document.getElementById('editKraModal'));
                    modal.hide();
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
        if (confirm('Are you sure you want to change the status of this KRA?')) {
            const id = $(this).data('id');

            $.ajax({
                url: '<?= base_url('admin/corporate-plans/kras/') ?>' + id + '/toggle-status',
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
