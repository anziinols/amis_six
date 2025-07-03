<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Update Proposal Status</h5>
                    <div>
                        <a href="<?= base_url('proposals/' . $proposal['id']) ?>" class="btn btn-outline-primary">
                            <i class="fas fa-eye me-1"></i> View
                        </a>
                        <a href="<?= base_url('proposals') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Proposals
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h5 class="alert-heading">Proposal Information</h5>
                                <p><strong>Workplan:</strong> <?= esc($proposal['workplan_title']) ?></p>
                                <p><strong>Activity:</strong> <?= esc($proposal['activity_title']) ?> <span class="badge bg-info"><?= ucfirst($proposal['activity_type']) ?></span></p>
                                <p><strong>Location:</strong> <?= esc($proposal['district_name']) ?>, <?= esc($proposal['province_name']) ?></p>
                                <p><strong>Date Range:</strong> <?= date('d M Y', strtotime($proposal['date_start'])) ?> - <?= date('d M Y', strtotime($proposal['date_end'])) ?></p>
                                <p class="mb-0"><strong>Current Status:</strong> 
                                    <?php
                                    $statusBadgeClass = 'bg-secondary';
                                    switch ($proposal['status']) {
                                        case 'pending':
                                            $statusBadgeClass = 'bg-warning text-dark';
                                            break;
                                        case 'submitted':
                                            $statusBadgeClass = 'bg-info text-dark';
                                            break;
                                        case 'approved':
                                            $statusBadgeClass = 'bg-success';
                                            break;
                                        case 'rated':
                                            $statusBadgeClass = 'bg-primary';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?= $statusBadgeClass ?>"><?= ucfirst($proposal['status']) ?></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="<?= base_url('proposals/status/' . $proposal['id']) ?>" method="post" id="statusForm">
                        <?= csrf_field() ?>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">New Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="">Select Status</option>
                                    <option value="pending" <?= old('status') == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="submitted" <?= old('status') == 'submitted' ? 'selected' : '' ?>>Submitted</option>
                                    <option value="approved" <?= old('status') == 'approved' ? 'selected' : '' ?>>Approved</option>
                                    <?php if ($proposal['status'] === 'approved'): ?>
                                        <option value="rated" <?= old('status') == 'rated' ? 'selected' : '' ?>>Rated</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="status_remarks" class="form-label">Status Remarks</label>
                                <textarea name="status_remarks" id="status_remarks" class="form-control" rows="4" placeholder="Enter any remarks about this status change"><?= old('status_remarks') ?></textarea>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="button" class="btn btn-primary" id="confirmStatusBtn">
                                    <i class="fas fa-save me-1"></i> Update Status
                                </button>
                                <a href="<?= base_url('proposals/' . $proposal['id']) ?>" class="btn btn-secondary ms-2">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize select2 for better dropdown experience
        $('#status').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        // Confirmation dialog for status update
        $('#confirmStatusBtn').click(function() {
            const status = $('#status').val();
            
            if (!status) {
                toastr.error('Please select a status');
                return;
            }
            
            const currentStatus = '<?= $proposal['status'] ?>';
            const newStatus = $('#status').val();
            const statusText = $('#status option:selected').text();
            
            if (confirm(`Are you sure you want to change the status to "${statusText}"?`)) {
                $('#statusForm').submit();
            }
        });
    });
</script>
<?= $this->endSection() ?>
