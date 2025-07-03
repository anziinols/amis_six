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
                        <button onclick="AMISPdf.generateActivityPDF(<?= $proposal['activity_id'] ?>)" class="btn btn-outline-danger me-2">
                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                        </button>
                        <?php if ($proposal['status'] === 'pending'): ?>
                        <a href="<?= base_url('activities/' . $proposal['id'] . '/implement') ?>" class="btn btn-primary">
                            <i class="fas fa-tasks me-1"></i> Implement
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Workplan Information</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 30%">Workplan Title</th>
                                    <td><?= esc($proposal['workplan_title']) ?></td>
                                </tr>
                                <tr>
                                    <th>Activity Title</th>
                                    <td><?= esc($proposal['activity_title']) ?></td>
                                </tr>
                                <tr>
                                    <th>Activity Type</th>
                                    <td>
                                        <?php
                                        $typeClass = '';
                                        switch ($proposal['activity_type']) {
                                            case 'training':
                                                $typeClass = 'bg-info';
                                                break;
                                            case 'inputs':
                                                $typeClass = 'bg-success';
                                                break;
                                            case 'infrastructure':
                                                $typeClass = 'bg-warning';
                                                break;
                                        }
                                        ?>
                                        <span class="badge <?= $typeClass ?>"><?= ucfirst(esc($proposal['activity_type'])) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?= nl2br(esc($proposal['description'])) ?></td>
                                </tr>
                                <tr>
                                    <th>Branch</th>
                                    <td><?= esc($proposal['branch_name'] ?? 'N/A') ?></td>
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
                                    <td><?= !empty($proposal['total_cost']) ? number_format($proposal['total_cost'], 2) : 'N/A' ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Proposal Information</h6>
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
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Cost</th>
                                    <td><?= !empty($proposal['total_cost']) ? number_format($proposal['total_cost'], 2) : 'N/A' ?></td>
                                </tr>
                                <tr>
                                    <th>Supervisor</th>
                                    <td><?= esc($proposal['supervisor_name'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Action Officer</th>
                                    <td><?= esc($proposal['officer_name'] ?? 'N/A') ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
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
                                        <span class="badge <?= $statusBadgeClass ?>"><?= ucfirst(esc($proposal['status'])) ?></span>
                                    </td>
                                </tr>
                                <?php if (!empty($proposal['status_remarks'])): ?>
                                <tr>
                                    <th>Status Remarks</th>
                                    <td><?= nl2br(esc($proposal['status_remarks'])) ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>

                    <?php if ($implementationData): ?>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="fw-bold">Implementation Details</h6>

                            <?php if ($proposal['activity_type'] === 'training'): ?>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0">Training Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <p><strong>Trainers:</strong> <?= esc($implementationData['trainers']) ?></p>
                                                <p><strong>Topics:</strong> <?= esc($implementationData['topics']) ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>GPS Coordinates:</strong> <?= esc($implementationData['gps_coordinates'] ?? 'N/A') ?></p>
                                                <?php if (!empty($implementationData['signing_sheet_filepath'])): ?>
                                                <p><strong>Signing Sheet:</strong> <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-file-signature me-1"></i> View Signing Sheet</a></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <?php if (!empty($implementationData['trainees'])): ?>
                                        <div class="table-responsive">
                                            <h6>Trainees</h6>
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
                                                    <?php foreach ($implementationData['trainees'] as $trainee): ?>
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
                                        <?php endif; ?>

                                        <?php if (!empty($implementationData['training_images'])): ?>
                                        <div class="mt-4">
                                            <h6>Training Images</h6>
                                            <div class="row">
                                                <?php
                                                $trainingImages = is_string($implementationData['training_images'])
                                                    ? json_decode($implementationData['training_images'], true)
                                                    : $implementationData['training_images'];

                                                foreach ($trainingImages as $image):
                                                ?>
                                                <div class="col-md-3 mb-3">
                                                    <div class="card">
                                                        <img src="<?= base_url($image) ?>" class="card-img-top img-fluid" alt="Training Image">
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php elseif ($proposal['activity_type'] === 'inputs'): ?>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0">Inputs Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <p><strong>GPS Coordinates:</strong> <?= esc($implementationData['gps_coordinates'] ?? 'N/A') ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <?php if (!empty($implementationData['signing_sheet_filepath'])): ?>
                                                <p><strong>Signing Sheet:</strong> <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-file-signature me-1"></i> View Signing Sheet</a></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <?php if (!empty($implementationData['inputs'])): ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Input Name</th>
                                                        <th>Quantity</th>
                                                        <th>Unit</th>
                                                        <th>Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $counter = 1; ?>
                                                    <?php foreach ($implementationData['inputs'] as $input): ?>
                                                    <tr>
                                                        <td><?= $counter++ ?></td>
                                                        <td><?= esc($input['name'] ?? 'N/A') ?></td>
                                                        <td><?= esc($input['quantity'] ?? 'N/A') ?></td>
                                                        <td><?= esc($input['unit'] ?? 'N/A') ?></td>
                                                        <td><?= esc($input['remarks'] ?? '') ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php endif; ?>

                                        <?php if (!empty($implementationData['input_images'])): ?>
                                        <div class="mt-4">
                                            <h6>Input Images</h6>
                                            <div class="row">
                                                <?php
                                                $inputImages = is_string($implementationData['input_images'])
                                                    ? json_decode($implementationData['input_images'], true)
                                                    : $implementationData['input_images'];

                                                foreach ($inputImages as $image):
                                                ?>
                                                <div class="col-md-3 mb-3">
                                                    <div class="card">
                                                        <img src="<?= base_url($image) ?>" class="card-img-top img-fluid" alt="Input Image">
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php elseif ($proposal['activity_type'] === 'infrastructure'): ?>
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0">Infrastructure Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <p><strong>Infrastructure:</strong> <?= esc($implementationData['infrastructure']) ?></p>
                                                <p><strong>GPS Coordinates:</strong> <?= esc($implementationData['gps_coordinates'] ?? 'N/A') ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <?php if (!empty($implementationData['signing_sheet_filepath'])): ?>
                                                <p><strong>Signing Sheet:</strong> <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-file-signature me-1"></i> View Signing Sheet</a></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <?php if (!empty($implementationData['infrastructure_images'])): ?>
                                        <div class="mt-4">
                                            <h6>Infrastructure Images</h6>
                                            <div class="row">
                                                <?php
                                                $infrastructureImages = is_string($implementationData['infrastructure_images'])
                                                    ? json_decode($implementationData['infrastructure_images'], true)
                                                    : $implementationData['infrastructure_images'];

                                                foreach ($infrastructureImages as $image):
                                                ?>
                                                <div class="col-md-3 mb-3">
                                                    <div class="card">
                                                        <img src="<?= base_url($image) ?>" class="card-img-top img-fluid" alt="Infrastructure Image">
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
