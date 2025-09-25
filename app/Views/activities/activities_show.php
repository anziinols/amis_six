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

            <!-- Implementation Details Section -->
            <?php if ($implementationData): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tasks me-2"></i>Implementation Details
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ($activity['type'] === 'documents'): ?>
                        <!-- Documents Implementation -->
                        <div class="mb-3">
                            <strong>General Remarks:</strong>
                            <p class="text-muted"><?= nl2br(esc($implementationData['remarks'] ?? 'N/A')) ?></p>
                        </div>

                        <?php if (!empty($implementationData['document_files'])): ?>
                        <div class="mb-3">
                            <strong>Uploaded Documents (<?= count($implementationData['document_files']) ?> files):</strong>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Caption</th>
                                            <th>Original Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($implementationData['document_files'] as $index => $document): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= esc($document['caption']) ?></td>
                                            <td><?= esc($document['original_name']) ?></td>
                                            <td>
                                                <a href="<?= base_url($document['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>

                    <?php elseif ($activity['type'] === 'trainings'): ?>
                        <!-- Training Implementation -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Trainers:</strong>
                                    <p class="text-muted"><?= nl2br(esc($implementationData['trainers'] ?? 'N/A')) ?></p>
                                </div>
                                <div class="mb-3">
                                    <strong>Topics:</strong>
                                    <p class="text-muted"><?= nl2br(esc($implementationData['topics'] ?? 'N/A')) ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>GPS Coordinates:</strong>
                                    <p class="text-muted"><?= esc($implementationData['gps_coordinates'] ?? 'N/A') ?></p>
                                </div>
                                <?php if (!empty($implementationData['signing_sheet_filepath'])): ?>
                                <div class="mb-3">
                                    <strong>Signing Sheet:</strong><br>
                                    <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> Download Signing Sheet
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!empty($implementationData['trainees'])): ?>
                        <div class="mb-3">
                            <strong>Trainees (<?= count($implementationData['trainees']) ?> participants):</strong>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Gender</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($implementationData['trainees'] as $index => $trainee): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= esc($trainee['name']) ?></td>
                                            <td><?= esc($trainee['age']) ?></td>
                                            <td><?= esc($trainee['gender']) ?></td>
                                            <td><?= esc($trainee['phone']) ?></td>
                                            <td><?= esc($trainee['email']) ?></td>
                                            <td><?= esc($trainee['remarks']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($implementationData['training_images'])): ?>
                        <div class="mb-3">
                            <strong>Training Images (<?= count($implementationData['training_images']) ?> images):</strong>
                            <div class="row">
                                <?php foreach ($implementationData['training_images'] as $index => $image): ?>
                                <div class="col-md-3 mb-2">
                                    <div class="card">
                                        <img src="<?= base_url($image) ?>" class="card-img-top" style="height: 150px; object-fit: cover;" alt="Training Image" data-bs-toggle="modal" data-bs-target="#imageModal<?= $index ?>">
                                        <div class="card-body p-2">
                                            <small class="text-muted">Image <?= $index + 1 ?></small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Modal -->
                                <div class="modal fade" id="imageModal<?= $index ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Training Image <?= $index + 1 ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                <img src="<?= base_url($image) ?>" class="img-fluid" alt="Training Image">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($implementationData['training_files'])): ?>
                        <div class="mb-3">
                            <strong>Training Files (<?= count($implementationData['training_files']) ?> files):</strong>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Caption</th>
                                            <th>Original Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($implementationData['training_files'] as $index => $file): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= esc($file['caption']) ?></td>
                                            <td><?= esc($file['original_name']) ?></td>
                                            <td>
                                                <a href="<?= base_url($file['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>

                    <?php elseif ($activity['type'] === 'meetings'): ?>
                        <!-- Meeting Implementation -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Meeting Title:</strong>
                                    <p class="text-muted"><?= esc($implementationData['title'] ?? 'N/A') ?></p>
                                </div>
                                <div class="mb-3">
                                    <strong>Agenda:</strong>
                                    <p class="text-muted"><?= nl2br(esc($implementationData['agenda'] ?? 'N/A')) ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>GPS Coordinates:</strong>
                                    <p class="text-muted"><?= esc($implementationData['gps_coordinates'] ?? 'N/A') ?></p>
                                </div>
                                <?php if (!empty($implementationData['signing_sheet_filepath'])): ?>
                                <div class="mb-3">
                                    <strong>Signing Sheet:</strong><br>
                                    <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> Download Signing Sheet
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!empty($implementationData['participants'])): ?>
                        <div class="mb-3">
                            <strong>Participants (<?= count($implementationData['participants']) ?> people):</strong>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Organization</th>
                                            <th>Position</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($implementationData['participants'] as $index => $participant): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= esc($participant['name']) ?></td>
                                            <td><?= esc($participant['organization']) ?></td>
                                            <td><?= esc($participant['position']) ?></td>
                                            <td><?= esc($participant['phone']) ?></td>
                                            <td><?= esc($participant['email']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($implementationData['minutes'])): ?>
                        <div class="mb-3">
                            <strong>Meeting Minutes:</strong>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Topic</th>
                                            <th>Discussion</th>
                                            <th>Action Items</th>
                                            <th>Responsible Person</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($implementationData['minutes'] as $index => $minute): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= esc($minute['topic']) ?></td>
                                            <td><?= nl2br(esc($minute['discussion'])) ?></td>
                                            <td><?= nl2br(esc($minute['action_items'])) ?></td>
                                            <td><?= esc($minute['responsible_person']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <!-- Other Activity Types -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Implementation details for <?= ucfirst(esc($activity['type'])) ?> activities are available but the display format is being developed.
                        </div>
                    <?php endif; ?>

                    <div class="text-muted mt-3">
                        <small><i class="fas fa-clock me-1"></i>Implemented on: <?= date('d M Y H:i', strtotime($implementationData['created_at'])) ?></small>
                    </div>
                </div>
            </div>
            <?php endif; ?>

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

        // Make training images clickable for larger view
        $('.card-img-top').css('cursor', 'pointer');
    });
</script>
<?= $this->endSection() ?>
