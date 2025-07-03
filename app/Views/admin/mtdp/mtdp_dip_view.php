<?php
$this->extend('templates/system_template');
$this->section('content');
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Deliberate Intervention Program Details: <?= $dip['dip_code'] ?></h3>
            <div class="card-tools">
                <a href="<?= base_url('admin/mtdp-plans/edit-dip-form/' . $dip['id']) ?>" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Program
                </a>
                <a href="<?= base_url('admin/mtdp-plans/spas/' . $spa['id'] . '/dips') ?>" class="btn btn-secondary ms-1">
                    <i class="fas fa-arrow-left"></i> Back to Programs List
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Basic Information -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Code:</div>
                        <div class="col-md-9"><?= $dip['dip_code'] ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Title:</div>
                        <div class="col-md-9"><?= $dip['dip_title'] ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 fw-bold">Status:</div>
                        <div class="col-md-9">
                            <span class="badge bg-<?= $dip['dip_status'] == 1 ? 'success' : 'danger' ?>">
                                <?= $dip['dip_status'] == 1 ? 'Active' : 'Inactive' ?>
                            </span>

                            <!-- Toggle Status Button -->
                            <button type="button" class="btn btn-sm btn-<?= $dip['dip_status'] == 1 ? 'danger' : 'success' ?> ms-2 toggle-status-btn"
                                    data-id="<?= $dip['id'] ?>" data-status="<?= $dip['dip_status'] ?>" data-bs-toggle="modal" data-bs-target="#toggleStatusModal">
                                <i class="fas fa-toggle-<?= $dip['dip_status'] == 1 ? 'off' : 'on' ?>"></i>
                                <?= $dip['dip_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                            </button>
                        </div>
                    </div>

                    <?php if (!empty($dip['dip_status_at']) || !empty($dip['dip_status_by'])): ?>
                    <div class="row mb-2">
                        <div class="col-md-12 small">
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-muted">Status Last Changed:</div>
                                    <div><?= !empty($dip['dip_status_at']) ? date('F j, Y \a\t g:i A', strtotime($dip['dip_status_at'])) : 'Not available' ?></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted">Status Changed By:</div>
                                    <div>
                                        <?php if (isset($dip['status_by_name'])): ?>
                                            <?= $dip['status_by_name'] ?>
                                        <?php elseif (!empty($dip['dip_status_by'])): ?>
                                            User ID: <?= $dip['dip_status_by'] ?>
                                        <?php else: ?>
                                            Not available
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php if (!empty($dip['dip_status_remarks'])): ?>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="text-muted">Status Remarks:</div>
                                    <div><?= nl2br($dip['dip_status_remarks']) ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-3 fw-bold">Remarks:</div>
                        <div class="col-md-9"><?= $dip['dip_remarks'] ?? 'No remarks available' ?></div>
                    </div>
                </div>
            </div>

            <!-- Investments Section -->
            <?php
            $investments = is_string($dip['investments']) ? json_decode($dip['investments'], true) : $dip['investments'];
            if (!empty($investments)):
                $totalAmount = 0;
                foreach ($investments as $investment) {
                    $totalAmount += (float)($investment['amount'] ?? 0);
                }
            ?>
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Investments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-end">Amount</th>
                                    <th>Year</th>
                                    <th>Funding Source</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($investments as $investment): ?>
                                <tr>
                                    <td><?= $investment['item'] ?></td>
                                    <td class="text-end">
                                        <?= number_format((float)($investment['amount'] ?? 0), 2, '.', ',') ?>
                                    </td>
                                    <td><?= $investment['year'] ?? 'N/A' ?></td>
                                    <td><?= $investment['funding'] ?? 'N/A' ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <tr class="table-secondary">
                                    <th>Total</th>
                                    <th class="text-end"><?= number_format($totalAmount, 2, '.', ',') ?></th>
                                    <th colspan="2"></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- KRAs Section -->
            <?php
            $kras = is_string($dip['kras']) ? json_decode($dip['kras'], true) : $dip['kras'];
            if (!empty($kras)):
            ?>
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Key Result Areas (KRAs)</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($kras as $index => $kra): ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-9">
                                    <?= ($index + 1) . '. ' . $kra['description'] ?>
                                </div>
                                <div class="col-md-3">
                                    <span class="badge bg-info">Period: <?= $kra['period'] ?? 'N/A' ?></span>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- Strategies Section -->
            <?php
            $strategies = is_string($dip['strategies']) ? json_decode($dip['strategies'], true) : $dip['strategies'];
            if (!empty($strategies)):
            ?>
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Strategies</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($strategies as $index => $strategy): ?>
                        <li class="list-group-item"><?= ($index + 1) . '. ' . $strategy['description'] ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <!-- Indicators Section -->
            <?php
            $indicators = is_string($dip['indicators']) ? json_decode($dip['indicators'], true) : $dip['indicators'];
            if (!empty($indicators)):
            ?>
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Performance Indicators</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Indicator</th>
                                    <th>Target</th>
                                    <th>Year</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($indicators as $indicator): ?>
                                <tr>
                                    <td><?= $indicator['name'] ?></td>
                                    <td><?= $indicator['target'] ?></td>
                                    <td><?= $indicator['year'] ?? 'N/A' ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Toggle Status Modal -->
<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="toggleStatusTitle">Toggle Program Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="toggleStatusForm">
                <div class="modal-body">
                    <div id="statusChangeMessage" class="alert alert-warning"></div>
                    <input type="hidden" id="toggle_id" name="id">
                    <input type="hidden" id="current_status" name="current_status">

                    <div class="mb-3">
                        <label for="dip_status_remarks" class="form-label">Status Remarks</label>
                        <textarea class="form-control" id="dip_status_remarks" name="dip_status_remarks" rows="3" required></textarea>
                        <div class="form-text">Please provide a reason for changing the status.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="confirmToggleBtn" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle Status - populate form
    $('.toggle-status-btn').on('click', function() {
        const id = $(this).data('id');
        const currentStatus = $(this).data('status');
        const newStatus = currentStatus == 1 ? 0 : 1;

        $('#toggle_id').val(id);
        $('#current_status').val(currentStatus);

        // Update modal text
        $('#toggleStatusTitle').text(currentStatus == 1 ? 'Deactivate Deliberate Intervention Program' : 'Activate Deliberate Intervention Program');
        $('#statusChangeMessage').html(`
            Are you sure you want to <strong>${currentStatus == 1 ? 'deactivate' : 'activate'}</strong> this Deliberate Intervention Program?
        `);

        // Update button text and class
        $('#confirmToggleBtn')
            .text(currentStatus == 1 ? 'Deactivate' : 'Activate')
            .removeClass('btn-success btn-danger')
            .addClass(currentStatus == 1 ? 'btn-danger' : 'btn-success');
    });

    // Toggle Status - submit form
    $('#toggleStatusForm').on('submit', function(e) {
        e.preventDefault();

        // Show loading indication
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        submitBtn.prop('disabled', true);

        // Get CSRF token
        const csrfToken = $('meta[name="<?= csrf_token() ?>"]').attr('content');
        const formData = {
            id: $('#toggle_id').val(),
            dip_status_remarks: $('#dip_status_remarks').val(),
            <?= csrf_token() ?>: $('meta[name="<?= csrf_token() ?>"]').attr('content')
        };

        $.ajax({
            url: '<?= base_url('admin/mtdp-plans/toggle-dip-status') ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Reset button
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);

                if (response.status === 'success') {
                    // Show success message
                    toastr.success(response.message || 'Program status toggled successfully');

                    // Close modal
                    $('#toggleStatusModal').modal('hide');

                    // Reload page to show updated data
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    // Show error message
                    toastr.error(response.message || 'Failed to toggle Program status');
                }
            },
            error: function(xhr, status, error) {
                // Reset button
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);

                // Show error message
                toastr.error(getErrorMessage(xhr, error));
            }
        });
    });

    // Get error message helper function
    function getErrorMessage(jqxhr, error) {
        let errorMessage = error || 'Unknown error';

        // Check for common HTTP status codes
        if (jqxhr.status === 404) {
            errorMessage = 'Route not found. Please check the URL configuration.';
        } else if (jqxhr.status === 500) {
            errorMessage = 'Server error occurred. Please check the server logs for details.';
        } else if (jqxhr.status === 401) {
            errorMessage = 'Authentication required. Please log in again.';
        }

        return errorMessage;
    }
</script>

<?= $this->endSection(); ?>
