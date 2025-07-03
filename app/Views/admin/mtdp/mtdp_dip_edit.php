<?php
$this->extend('templates/system_template');
$this->section('content');
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Deliberate Intervention Program</h3>
            <div class="card-tools">
                <a href="<?= base_url('admin/mtdp-plans/spas/' . $spa['id'] . '/dips') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Programs
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="<?= base_url('admin/mtdp-plans/update-dip') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $dip['id'] ?>">
                <input type="hidden" name="spa_id" value="<?= $spa['id'] ?>">
                <input type="hidden" name="mtdp_id" value="<?= $mtdp['id'] ?>">

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="dip_code" class="form-label">DIP Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="dip_code" name="dip_code" value="<?= esc($dip['dip_code'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-8">
                        <label for="dip_title" class="form-label">DIP Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="dip_title" name="dip_title" value="<?= esc($dip['dip_title'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="dip_remarks" class="form-label">Remarks</label>
                    <textarea class="form-control" id="dip_remarks" name="dip_remarks" rows="3"><?= esc($dip['dip_remarks'] ?? '') ?></textarea>
                </div>

                <!-- Status Information -->
                <div class="mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Status Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Current Status</label>
                                    <div id="edit_status_badge">
                                        <span class="badge bg-<?= $dip['dip_status'] == 1 ? 'success' : 'danger' ?>">
                                            <?= $dip['dip_status'] == 1 ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Last Status Change</label>
                                    <div id="edit_status_at">
                                        <?php if ($dip['dip_status_at']) : ?>
                                            <?= date('F j, Y \a\t g:i A', strtotime($dip['dip_status_at'])) ?>
                                        <?php else : ?>
                                            Not available
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Status Changed By</label>
                                    <div id="edit_status_by">
                                        <?php if (isset($dip['status_by_name'])) : ?>
                                            <?= $dip['status_by_name'] ?>
                                        <?php elseif ($dip['dip_status_by']) : ?>
                                            User ID: <?= $dip['dip_status_by'] ?>
                                        <?php else : ?>
                                            Not available
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="form-label">Status Remarks</label>
                                <div id="edit_status_remarks" class="p-2 bg-light rounded">
                                    <?= $dip['dip_status_remarks'] ?: 'No remarks available' ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden JSON fields with empty arrays -->
                <input type="hidden" name="investments_json" id="investments_json" value='[]'>
                <input type="hidden" name="kras_json" id="kras_json" value='[]'>
                <input type="hidden" name="strategies_json" id="strategies_json" value='[]'>
                <input type="hidden" name="indicators_json" id="indicators_json" value='[]'>

                <div class="d-flex justify-content-end mt-4">
                    <a href="<?= base_url('admin/mtdp-plans/spas/' . $spa['id'] . '/dips') ?>" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update DIP</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // No special initialization needed since we're not using the JSON fields anymore
    });
</script>

<?= $this->endSection(); ?>
