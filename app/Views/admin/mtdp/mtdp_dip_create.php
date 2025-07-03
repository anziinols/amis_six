<?php
$this->extend('templates/system_template');
$this->section('content');
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Create New Deliberate Intervention Program</h3>
            <div class="card-tools">
                <a href="<?= base_url('admin/mtdp-plans/spas/' . $spa['id'] . '/dips') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to DIPs
                </a>
            </div>
        </div>
        <div class="card-body">
            <form id="addDipForm">
                <?= csrf_field() ?>
                <input type="hidden" name="mtdp_id" value="<?= $mtdp['id'] ?>" />
                <input type="hidden" name="spa_id" value="<?= $spa['id'] ?>" />

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="dip_code" class="form-label">DIP Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="dip_code" name="dip_code" required>
                    </div>
                    <div class="col-md-8">
                        <label for="dip_title" class="form-label">DIP Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="dip_title" name="dip_title" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="dip_remarks" class="form-label">Remarks</label>
                    <textarea class="form-control" id="dip_remarks" name="dip_remarks" rows="3"></textarea>
                </div>

                <!-- Hidden JSON fields with empty arrays -->
                <input type="hidden" name="investments_json" id="investments_json" value='[]'>
                <input type="hidden" name="kras_json" id="kras_json" value='[]'>
                <input type="hidden" name="strategies_json" id="strategies_json" value='[]'>
                <input type="hidden" name="indicators_json" id="indicators_json" value='[]'>

                <div class="d-flex justify-content-end mt-4">
                    <a href="<?= base_url('admin/mtdp-plans/spas/' . $spa['id'] . '/dips') ?>" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save DIP</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Create DIP
        $('#addDipForm').on('submit', function(e) {
            e.preventDefault();

            // Show loading indication
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
            submitBtn.prop('disabled', true);

            // Get CSRF token
            const csrfToken = $('meta[name="<?= csrf_token() ?>"]').attr('content');
            const formData = $(this).serialize() + '&<?= csrf_token() ?>=' + csrfToken;

            $.ajax({
                url: '<?= base_url('admin/mtdp-plans/create-dip') ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    // Reset button
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);

                    if (response.status === 'success') {
                        // Show success message
                        toastr.success(response.message || 'DIP created successfully');

                        // Redirect to DIPs list
                        setTimeout(function() {
                            window.location.href = '<?= base_url('admin/mtdp-plans/spas/' . $spa['id'] . '/dips') ?>';
                        }, 1000);
                    } else {
                        // Show error message
                        toastr.error(response.message || 'Failed to create DIP');
                    }
                },
                error: function(xhr, status, error) {
                    // Reset button
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);

                    console.error('AJAX Error:', error);

                    // Show a specific and helpful error message
                    if (xhr.status === 404) {
                        toastr.error('Route not found. Please check the URL configuration.');
                    } else if (xhr.status === 500) {
                        toastr.error('Server error occurred. Please check the server logs for details.');
                    } else if (xhr.status === 403) {
                        toastr.error('CSRF validation failed. Please refresh the page and try again.');
                    } else {
                        toastr.error('Failed to create DIP: ' + (error || 'Unknown error'));
                    }
                }
            });
        });
    });
</script>

<?= $this->endSection(); ?>
