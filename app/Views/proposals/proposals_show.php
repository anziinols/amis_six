<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">View Proposal</h5>
                    <div>
                        <a href="<?= base_url('proposals/' . $proposal['id']) ?>" class="btn btn-info">
                            <i class="fas fa-eye me-1"></i> View
                        </a>
                        <?php if ($proposal['status'] === 'pending'): ?>
                        <a href="<?= base_url('proposals/edit/' . $proposal['id']) ?>" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <?php endif; ?>
                        <?php if ($proposal['status'] === 'submitted'): ?>
                        <a href="<?= base_url('proposals/supervise/' . $proposal['id']) ?>" class="btn btn-primary">
                            <i class="fas fa-clipboard-check me-1"></i> Supervise
                        </a>
                        <?php endif; ?>

                        <a href="<?= base_url('proposals') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="alert-heading">Proposal Status</h5>
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
                                        <span class="badge <?= $statusBadgeClass ?> fs-6"><?= ucfirst($proposal['status']) ?></span>
                                        <?php if (!empty($proposal['status_remarks'])): ?>
                                            <p class="mt-2 mb-0"><?= esc($proposal['status_remarks']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($proposal['status'] === 'rated' || $proposal['status'] === 'approved'): ?>
                                        <div class="text-center">
                                            <h5>Rating</h5>
                                            <div class="fs-4">
                                                <?php if (!empty($proposal['rating_score'])): ?>
                                                    <?php
                                                    $fullStars = floor($proposal['rating_score']);
                                                    $halfStar = $proposal['rating_score'] - $fullStars >= 0.5;
                                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

                                                    for ($i = 0; $i < $fullStars; $i++) {
                                                        echo '<i class="fas fa-star text-warning"></i>';
                                                    }

                                                    if ($halfStar) {
                                                        echo '<i class="fas fa-star-half-alt text-warning"></i>';
                                                    }

                                                    for ($i = 0; $i < $emptyStars; $i++) {
                                                        echo '<i class="far fa-star text-warning"></i>';
                                                    }
                                                    ?>
                                                    <span class="ms-2"><?= number_format($proposal['rating_score'], 1) ?>/5.0</span>
                                                <?php else: ?>
                                                    <span class="text-muted">Not rated yet</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Workplan Details Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Workplan Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th style="width: 30%">Title</th>
                                                    <td><?= esc($workplan['title']) ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Description</th>
                                                    <td><?= nl2br(esc($workplan['description'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Objectives</th>
                                                    <td><?= nl2br(esc($workplan['objectives'])) ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th style="width: 30%">Start Date</th>
                                                    <td><?= date('d M Y', strtotime($workplan['start_date'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <th>End Date</th>
                                                    <td><?= date('d M Y', strtotime($workplan['end_date'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td><span class="badge bg-info"><?= ucfirst($workplan['status']) ?></span></td>
                                                </tr>
                                                <?php if (!empty($workplan['remarks'])): ?>
                                                <tr>
                                                    <th>Remarks</th>
                                                    <td><?= nl2br(esc($workplan['remarks'])) ?></td>
                                                </tr>
                                                <?php endif; ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Details Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Activity Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th style="width: 20%">Title</th>
                                                    <td><?= esc($activity['title']) ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Description</th>
                                                    <td><?= nl2br(esc($activity['description'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Activity Type</th>
                                                    <td><span class="badge bg-info"><?= ucfirst($activity['activity_type']) ?></span></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Proposal Details Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">Proposal Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th style="width: 30%">Supervisor</th>
                                                    <td><?= !empty($proposal['supervisor_name']) ? esc($proposal['supervisor_name']) : 'Not assigned' ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Action Officer</th>
                                                    <td><?= !empty($proposal['action_officer_name']) ? esc($proposal['action_officer_name']) : 'Not assigned' ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Total Cost</th>
                                                    <td><?= !empty($proposal['total_cost']) ? CURRENCY_SYMBOL . ' ' . number_format($proposal['total_cost'], 2) : 'Not specified' ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th style="width: 30%">Location</th>
                                                    <td>
                                                        <?= esc($proposal['location']) ?><br>
                                                        <small class="text-muted"><?= esc($proposal['district_name']) ?>, <?= esc($proposal['province_name']) ?></small>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Date Range</th>
                                                    <td>
                                                        <?= date('d M Y', strtotime($proposal['date_start'])) ?> -
                                                        <?= date('d M Y', strtotime($proposal['date_end'])) ?>
                                                        (<?php
                                                        $start = new DateTime($proposal['date_start']);
                                                        $end = new DateTime($proposal['date_end']);
                                                        $interval = $start->diff($end);
                                                        echo $interval->days + 1 . ' day(s)';
                                                        ?>)
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Type Specific Details Section -->
                    <?php if ($activityType === 'training' && $activityTypeDetails): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">Training Activity Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <h6>Trainers</h6>
                                            <p><?= nl2br(esc($activityTypeDetails['trainers'] ?? 'No trainers specified')) ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Topics</h6>
                                            <p><?= nl2br(esc($activityTypeDetails['topics'] ?? 'No topics specified')) ?></p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <h6>GPS Coordinates</h6>
                                            <p><?= !empty($activityTypeDetails['gps_coordinates']) ? esc($activityTypeDetails['gps_coordinates']) : 'Not specified' ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Signing Sheet</h6>
                                            <?php if (!empty($activityTypeDetails['signing_sheet_filepath'])): ?>
                                                <a href="<?= base_url($activityTypeDetails['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-file-signature me-1"></i> View Signing Sheet
                                                </a>
                                            <?php else: ?>
                                                <p class="text-muted">No signing sheet uploaded</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if (!empty($activityTypeDetails['trainees'])): ?>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <h6>Trainees</h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Full Name</th>
                                                            <th>Age</th>
                                                            <th>Gender</th>
                                                            <th>Phone</th>
                                                            <th>Email</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $counter = 1; ?>
                                                        <?php foreach ($activityTypeDetails['trainees'] as $trainee): ?>
                                                        <tr>
                                                            <td><?= $counter++ ?></td>
                                                            <td><?= esc($trainee['name'] ?? 'N/A') ?></td>
                                                            <td><?= esc($trainee['age'] ?? 'N/A') ?></td>
                                                            <td><?= esc($trainee['gender'] ?? 'N/A') ?></td>
                                                            <td><?= esc($trainee['phone'] ?? 'N/A') ?></td>
                                                            <td><?= esc($trainee['email'] ?? 'N/A') ?></td>
                                                            <td><?= esc($trainee['remarks'] ?? '') ?></td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <div class="alert alert-info">No trainees have been added yet.</div>
                                    <?php endif; ?>

                                    <?php if (!empty($activityTypeDetails['training_images'])): ?>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <h6>Training Images</h6>
                                            <div class="row">
                                                <?php foreach ($activityTypeDetails['training_images'] as $image): ?>
                                                <div class="col-md-3 mb-3">
                                                    <img src="<?= base_url($image) ?>" class="img-fluid img-thumbnail" alt="Training Image">
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (!empty($activityTypeDetails['training_files'])): ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6>Training Files</h6>
                                            <ul class="list-group">
                                                <?php foreach ($activityTypeDetails['training_files'] as $file): ?>
                                                <li class="list-group-item">
                                                    <a href="<?= base_url($file) ?>" target="_blank">
                                                        <i class="fas fa-file me-2"></i> <?= basename($file) ?>
                                                    </a>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php elseif ($activityType === 'infrastructure' && $activityTypeDetails): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">Infrastructure Activity Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th style="width: 30%">Infrastructure</th>
                                                    <td><?= esc($activityTypeDetails['infrastructure'] ?? 'Not specified') ?></td>
                                                </tr>
                                                <?php if (!empty($activityTypeDetails['gps_coordinates'])): ?>
                                                <tr>
                                                    <th>GPS Coordinates</th>
                                                    <td><?= esc($activityTypeDetails['gps_coordinates']) ?></td>
                                                </tr>
                                                <?php endif; ?>
                                                <tr>
                                                    <th>Signing Sheet</th>
                                                    <td>
                                                        <?php if (!empty($activityTypeDetails['signing_sheet_filepath'])): ?>
                                                            <a href="<?= base_url($activityTypeDetails['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-file-signature me-1"></i> View Signing Sheet
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted">No signing sheet uploaded</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <?php if (!empty($activityTypeDetails['infrastructure_images'])): ?>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <h6>Infrastructure Images</h6>
                                            <div class="row">
                                                <?php foreach ($activityTypeDetails['infrastructure_images'] as $image): ?>
                                                <div class="col-md-3 mb-3">
                                                    <img src="<?= base_url($image) ?>" class="img-fluid img-thumbnail" alt="Infrastructure Image">
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (!empty($activityTypeDetails['infrastructure_files'])): ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6>Infrastructure Files</h6>
                                            <ul class="list-group">
                                                <?php foreach ($activityTypeDetails['infrastructure_files'] as $file): ?>
                                                <li class="list-group-item">
                                                    <a href="<?= base_url($file) ?>" target="_blank">
                                                        <i class="fas fa-file me-2"></i> <?= basename($file) ?>
                                                    </a>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php elseif ($activityType === 'inputs' && $activityTypeDetails): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">Inputs Activity Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <h6>GPS Coordinates</h6>
                                            <p><?= !empty($activityTypeDetails['gps_coordinates']) ? esc($activityTypeDetails['gps_coordinates']) : 'Not specified' ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Signing Sheet</h6>
                                            <?php if (!empty($activityTypeDetails['signing_sheet_filepath'])): ?>
                                                <a href="<?= base_url($activityTypeDetails['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-file-signature me-1"></i> View Signing Sheet
                                                </a>
                                            <?php else: ?>
                                                <p class="text-muted">No signing sheet uploaded</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if (!empty($activityTypeDetails['inputs'])): ?>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <h6>Inputs</h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Input Name</th>
                                                            <th>Unit</th>
                                                            <th>Quantity</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $counter = 1; ?>
                                                        <?php foreach ($activityTypeDetails['inputs'] as $input): ?>
                                                        <tr>
                                                            <td><?= $counter++ ?></td>
                                                            <td><?= esc($input['name'] ?? 'N/A') ?></td>
                                                            <td><?= esc($input['unit'] ?? 'N/A') ?></td>
                                                            <td><?= esc($input['quantity'] ?? 'N/A') ?></td>
                                                            <td><?= esc($input['remarks'] ?? '') ?></td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <div class="alert alert-info">No inputs have been added yet.</div>
                                    <?php endif; ?>

                                    <?php if (!empty($activityTypeDetails['input_images'])): ?>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <h6>Input Images</h6>
                                            <div class="row">
                                                <?php foreach ($activityTypeDetails['input_images'] as $image): ?>
                                                <div class="col-md-3 mb-3">
                                                    <img src="<?= base_url($image) ?>" class="img-fluid img-thumbnail" alt="Input Image">
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (!empty($activityTypeDetails['input_files'])): ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6>Input Files</h6>
                                            <ul class="list-group">
                                                <?php foreach ($activityTypeDetails['input_files'] as $file): ?>
                                                <li class="list-group-item">
                                                    <a href="<?= base_url($file) ?>" target="_blank">
                                                        <i class="fas fa-file me-2"></i> <?= basename($file) ?>
                                                    </a>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php elseif ($activityTypeDetails): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> No specific details available for this activity type.
                    </div>
                    <?php endif; ?>

                    <?php if ($proposal['status'] === 'rated' && !empty($proposal['rate_remarks'])): ?>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Rating Remarks</h5>
                                    <p class="card-text"><?= nl2br(esc($proposal['rate_remarks'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Audit Information</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Created:</strong> <?= date('d M Y H:i', strtotime($proposal['created_at'])) ?></p>
                                            <p><strong>Last Updated:</strong> <?= date('d M Y H:i', strtotime($proposal['updated_at'])) ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Status Last Changed:</strong> <?= !empty($proposal['status_at']) ? date('d M Y H:i', strtotime($proposal['status_at'])) : 'N/A' ?></p>
                                            <p><strong>Rated On:</strong> <?= !empty($proposal['rated_at']) ? date('d M Y H:i', strtotime($proposal['rated_at'])) : 'N/A' ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
