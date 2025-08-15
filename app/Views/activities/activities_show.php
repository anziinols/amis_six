<?php
// app/Views/activities/activities_show.php
?>
<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="mb-3">
        <a href="<?= base_url('activities') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Activities
        </a>
    </div>

    <?php if (session()->has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Activity Details</h5>
                    <div>
                        <?php if (in_array($activity['status'], ['pending', 'active'])): ?>
                            <a href="<?= base_url('activities/' . $activity['id'] . '/implement') ?>" class="btn btn-primary">
                                <i class="fas fa-tasks me-1"></i> Implement
                            </a>
                            <?php if (!empty($implementationData)): ?>
                            <button type="button" id="submitForSupervision" class="btn btn-warning me-2">
                                <i class="fas fa-paper-plane me-1"></i> Submit for Supervision
                            </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Workplan Information</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Performance Output</th>
                                    <td><?= esc($activity['performance_output_title'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Activity Title</th>
                                    <td><?= esc($activity['activity_title']) ?></td>
                                </tr>
                                <tr>
                                    <th>Activity Type</th>
                                    <td>
                                        <?php
                                        $typeClass = '';
                                        switch ($activity['type']) {
                                            case 'trainings':
                                                $typeClass = 'bg-info';
                                                break;
                                            case 'inputs':
                                                $typeClass = 'bg-success';
                                                break;
                                            case 'infrastructures':
                                                $typeClass = 'bg-warning';
                                                break;
                                            case 'documents':
                                                $typeClass = 'bg-primary';
                                                break;
                                            case 'meetings':
                                                $typeClass = 'bg-secondary';
                                                break;
                                            case 'agreements':
                                                $typeClass = 'bg-dark';
                                                break;
                                            case 'outputs':
                                                $typeClass = 'bg-danger';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?= $typeClass ?>"><?= ucfirst(esc($activity['type'])) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?= nl2br(esc($activity['activity_description'])) ?></td>
                                </tr>
                                <?php if (isset($implementationData['gps_coordinates'])): ?>
                                <tr>
                                    <th>GPS Coordinates</th>
                                    <td><?= esc($implementationData['gps_coordinates'] ?? 'N/A') ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if (isset($implementationData['signing_sheet_filepath']) && !empty($implementationData['signing_sheet_filepath'])): ?>
                                <tr>
                                    <th>Signing Sheet</th>
                                    <td>
                                        <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-file-signature me-1"></i> View Signing Sheet
                                        </a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th>Total Cost</th>
                                    <td><?= !empty($activity['total_cost']) ? 'USD ' . number_format($activity['total_cost'], 2) : 'N/A' ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Activity Information</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Location</th>
                                    <td>
                                        <?= esc($activity['location'] ?? 'N/A') ?><br>
                                        <small class="text-muted"><?= esc($activity['district_name'] ?? 'N/A') ?>, <?= esc($activity['province_name'] ?? 'N/A') ?></small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date Range</th>
                                    <td>
                                        <?= date('d M Y', strtotime($activity['date_start'])) ?> -
                                        <?= date('d M Y', strtotime($activity['date_end'])) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Cost</th>
                                    <td><?= !empty($activity['total_cost']) ? 'USD ' . number_format($activity['total_cost'], 2) : 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <th>Supervisor</th>
                                    <td><?= esc($activity['supervisor_name'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Action Officer</th>
                                    <td><?= esc($activity['action_officer_name'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <?php
                                        $statusBadgeClass = 'bg-secondary';
                                        switch ($activity['status']) {
                                            case 'pending':
                                                $statusBadgeClass = 'bg-warning text-dark';
                                                break;
                                            case 'active':
                                                $statusBadgeClass = 'bg-success';
                                                break;
                                            case 'submitted':
                                                $statusBadgeClass = 'bg-info text-dark';
                                                break;
                                            case 'approved':
                                                $statusBadgeClass = 'bg-primary';
                                                break;
                                            case 'rated':
                                                $statusBadgeClass = 'bg-dark';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?= $statusBadgeClass ?>"><?= ucfirst(esc($activity['status'])) ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>




                </div>
            </div>
        </div>
    </div>
</div>


<!-- Submit for Supervision Modal -->
<div class="modal fade" id="supervisionModal" tabindex="-1" aria-labelledby="supervisionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="supervisionModalLabel">Submit for Supervision</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> <strong>Warning:</strong> If you submit this activity for supervision, it will no longer be editable and the implement button will no longer be displayed.
                </div>
                <p>Are you sure you want to proceed?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmSubmitForSupervision">
                    <i class="fas fa-paper-plane me-1"></i> Submit for Supervision
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {

        // Submit for Supervision button click - Show modal
        $('#submitForSupervision').click(function(e) {
            e.preventDefault();
            $('#supervisionModal').modal('show');
        });

        // Confirm Submit for Supervision - Handle form submission
        $('#confirmSubmitForSupervision').click(function() {
            // Create a form to submit
            var form = $('<form></form>');
            form.attr('method', 'post');
            form.attr('action', '<?= base_url('activities/' . $activity['id'] . '/submit-for-supervision') ?>');

            // Add CSRF token
            form.append($('<input>').attr({
                type: 'hidden',
                name: '<?= csrf_token() ?>',
                value: '<?= csrf_hash() ?>'
            }));

            // Append form to body and submit
            $('body').append(form);
            form.submit();
        });
    });
</script>
<?= $this->endSection() ?>
