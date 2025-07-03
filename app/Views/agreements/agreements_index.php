<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manage Agreements</h5>
        <a href="<?= base_url('agreements/new') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add New Agreement
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
            <table class="table table-striped table-bordered" id="agreementsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Branch</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Effective Date</th>
                        <th>Expiry Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($agreements)): ?>
                        <?php $counter = 1; ?>
                        <?php foreach ($agreements as $agreement): ?>
                            <tr>
                                <td><?= $counter++ ?></td>
                                <td><?= esc($agreement['title']) ?></td>
                                <td><?= esc($agreement['branch_name'] ?? 'N/A') ?></td>
                                <td><?= esc(ucfirst($agreement['agreement_type'] ?? 'N/A')) ?></td>
                                <td><span class="badge bg-<?= $agreement['status'] == 'active' ? 'success' : ($agreement['status'] == 'draft' ? 'secondary' : 'warning') ?>"><?= esc(ucfirst($agreement['status'])) ?></span></td>
                                <td><?= esc(date('Y-m-d', strtotime($agreement['effective_date']))) ?></td>
                                <td><?= !empty($agreement['expiry_date']) ? esc(date('Y-m-d', strtotime($agreement['expiry_date']))) : 'N/A' ?></td>
                                <td>
                                    <a href="<?= base_url('agreements/' . $agreement['id']) ?>" class="btn btn-info btn-sm" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('agreements/edit/' . $agreement['id']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm delete-agreement-btn" data-id="<?= $agreement['id'] ?>" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No agreements found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this agreement? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteAgreementForm" method="post" style="display: inline;">
                     <?= csrf_field() ?>
                     <input type="hidden" name="_method" value="DELETE"> <!-- Use method spoofing for DELETE request -->
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Handle delete button click
        $('.delete-agreement-btn').on('click', function() {
            var agreementId = $(this).data('id');
            var formAction = '<?= base_url("agreements/delete/") ?>' + agreementId;
            $('#deleteAgreementForm').attr('action', formAction);
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            deleteModal.show();
        });
    });
</script>
<?= $this->endSection() ?> 