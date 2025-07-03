<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Supervise Proposal</h5>
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
                        <?php if ($proposal['status'] === 'approved'): ?>
                            <a href="<?= base_url('proposals/rate/' . $proposal['id']) ?>" class="btn btn-success">
                                <i class="fas fa-star me-1"></i> Rate
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

                                    <?php if ($proposal['status'] === 'submitted'): ?>
                                    <div>
                                        <button type="button" class="btn btn-warning" id="resendBtn">
                                            <i class="fas fa-undo me-1"></i> Resend
                                        </button>
                                        <button type="button" class="btn btn-success" id="approveBtn">
                                            <i class="fas fa-check me-1"></i> Approve
                                        </button>
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
                                                    <td><?= !empty($proposal['total_cost']) ? '$' . number_format($proposal['total_cost'], 2) : 'Not specified' ?></td>
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
                    <?php elseif ($activityType === 'output' && $activityTypeDetails): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">Output Activity Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <h6>Delivery Information</h6>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th style="width: 40%">Delivery Date</th>
                                                    <td><?= !empty($activityTypeDetails['delivery_date']) ? date('d M Y', strtotime($activityTypeDetails['delivery_date'])) : 'Not specified' ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Delivery Location</th>
                                                    <td><?= esc($activityTypeDetails['delivery_location'] ?? 'Not specified') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Total Value</th>
                                                    <td><?= !empty($activityTypeDetails['total_value']) ? 'K ' . number_format($activityTypeDetails['total_value'], 2) : 'Not specified' ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Location & Documentation</h6>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th style="width: 40%">GPS Coordinates</th>
                                                    <td><?= !empty($activityTypeDetails['gps_coordinates']) ? esc($activityTypeDetails['gps_coordinates']) : 'Not specified' ?></td>
                                                </tr>
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
                                                <tr>
                                                    <th>Remarks</th>
                                                    <td><?= !empty($activityTypeDetails['remarks']) ? nl2br(esc($activityTypeDetails['remarks'])) : 'No remarks' ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <?php if (!empty($activityTypeDetails['outputs'])): ?>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <h6>Outputs</h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Output Name</th>
                                                            <th>Unit</th>
                                                            <th>Quantity</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $counter = 1; ?>
                                                        <?php foreach ($activityTypeDetails['outputs'] as $output): ?>
                                                        <tr>
                                                            <td><?= $counter++ ?></td>
                                                            <td><?= esc($output['name'] ?? 'N/A') ?></td>
                                                            <td><?= esc($output['unit'] ?? 'N/A') ?></td>
                                                            <td><?= esc($output['quantity'] ?? 'N/A') ?></td>
                                                            <td><?= esc($output['remarks'] ?? '') ?></td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <div class="alert alert-info">No outputs have been added yet.</div>
                                    <?php endif; ?>

                                    <?php if (!empty($activityTypeDetails['beneficiaries'])): ?>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <h6>Beneficiaries</h6>
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
                                                        <?php foreach ($activityTypeDetails['beneficiaries'] as $beneficiary): ?>
                                                        <tr>
                                                            <td><?= $counter++ ?></td>
                                                            <td><?= esc($beneficiary['name'] ?? 'N/A') ?></td>
                                                            <td><?= esc($beneficiary['age'] ?? 'N/A') ?></td>
                                                            <td><?= esc($beneficiary['gender'] ?? 'N/A') ?></td>
                                                            <td><?= esc($beneficiary['phone'] ?? 'N/A') ?></td>
                                                            <td><?= esc($beneficiary['email'] ?? 'N/A') ?></td>
                                                            <td><?= esc($beneficiary['remarks'] ?? '') ?></td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <div class="alert alert-info">No beneficiaries have been added yet.</div>
                                    <?php endif; ?>

                                    <?php if (!empty($activityTypeDetails['output_images'])): ?>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <h6>Output Images</h6>
                                            <div class="row">
                                                <?php foreach ($activityTypeDetails['output_images'] as $image): ?>
                                                <div class="col-md-3 mb-3">
                                                    <img src="<?= base_url($image) ?>" class="img-fluid img-thumbnail" alt="Output Image">
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (!empty($activityTypeDetails['output_files'])): ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6>Output Files</h6>
                                            <ul class="list-group">
                                                <?php foreach ($activityTypeDetails['output_files'] as $file): ?>
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
                    <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> No specific details available for this activity type.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resend Modal -->
<div class="modal fade" id="resendModal" tabindex="-1" aria-labelledby="resendModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="resendModalLabel">Resend Proposal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('proposals/resend/' . $proposal['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> Are you sure you want to resend this proposal for revision? This will change the status back to "pending".
                    </div>
                    <div class="mb-3">
                        <label for="status_remarks" class="form-label">Remarks (Optional)</label>
                        <textarea class="form-control" id="status_remarks" name="status_remarks" rows="3" placeholder="Enter any remarks about why you're resending this proposal"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Resend Proposal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="approveModalLabel">Approve Proposal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('proposals/approve/' . $proposal['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i> Are you sure you want to approve this proposal? This will change the status to "approved".
                    </div>
                    <div class="mb-3">
                        <label for="status_remarks" class="form-label">Remarks (Optional)</label>
                        <textarea class="form-control" id="approve_status_remarks" name="status_remarks" rows="3" placeholder="Enter any remarks about approving this proposal"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Proposal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize resend button
        $('#resendBtn').click(function() {
            $('#resendModal').modal('show');
        });

        // Initialize approve button
        $('#approveBtn').click(function() {
            $('#approveModal').modal('show');
        });
    });
</script>
<?= $this->endSection() ?>
