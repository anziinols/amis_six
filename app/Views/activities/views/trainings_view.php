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
                <div class="card-header">
                    <h5 class="card-title mb-0">Training Activity: <?= esc($activity['activity_title']) ?></h5>
                </div>
                <div class="card-body">
                    <!-- Activity Reference Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Activity Information</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Title:</strong> <?= esc($activity['activity_title']) ?></p>
                                            <p class="mb-1"><strong>Type:</strong> <span class="badge bg-info"><?= ucfirst(esc($activity['type'])) ?></span></p>
                                            <p class="mb-1"><strong>Description:</strong></p>
                                            <p class="text-muted"><?= nl2br(esc($activity['activity_description'])) ?></p>
                                        </div>
                                        <div class="col-md-4">

                                            <p class="mb-1"><strong>Location:</strong> <?= esc($activity['location'] ?? 'N/A') ?></p>
                                            <p class="mb-1"><strong>Province:</strong> <?= esc($activity['province_name'] ?? 'N/A') ?></p>
                                            <p class="mb-1"><strong>District:</strong> <?= esc($activity['district_name'] ?? 'N/A') ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Action Officer:</strong> <?= esc($activity['action_officer_name'] ?? 'N/A') ?></p>
                                            <p class="mb-1"><strong>Supervisor:</strong> <?= esc($activity['supervisor_name'] ?? 'N/A') ?></p>
                                            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-success"><?= ucfirst(esc($activity['status'])) ?></span></p>
                                            <p class="mb-1"><strong>Date Range:</strong></p>
                                            <p class="text-muted"><?= date('d M Y', strtotime($activity['date_start'])) ?> - <?= date('d M Y', strtotime($activity['date_end'])) ?></p>
                                            <p class="mb-1"><strong>Total Cost:</strong> <?= !empty($activity['total_cost']) ? CURRENCY_SYMBOL . ' ' . number_format($activity['total_cost'], 2) : 'N/A' ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Information -->
                    <?php if (!empty($activity['status_remarks']) || !empty($activity['status_by_name']) || !empty($activity['status_at'])): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-check-circle me-2"></i>Approval Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($activity['status_remarks'])): ?>
                                    <p class="mb-2"><strong>Remarks:</strong></p>
                                    <p class="mb-3"><?= nl2br(esc($activity['status_remarks'])) ?></p>
                                    <?php endif; ?>
                                    <div class="row">
                                        <?php if (!empty($activity['status_by_name'])): ?>
                                        <div class="col-md-6">
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>Approved by: <?= esc($activity['status_by_name']) ?>
                                            </small>
                                        </div>
                                        <?php endif; ?>
                                        <?php if (!empty($activity['status_at'])): ?>
                                        <div class="col-md-6">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>Approved on: <?= date('d M Y H:i', strtotime($activity['status_at'])) ?>
                                            </small>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Rating Information -->
                    <?php if (!empty($activity['rating_score']) || !empty($activity['rate_remarks'])): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-star me-2"></i>Activity Rating
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($activity['rating_score'])): ?>
                                    <div class="mb-3">
                                        <strong>Rating Score:</strong>
                                        <span class="fs-4 fw-bold text-warning ms-2">
                                            <?= esc($activity['rating_score']) ?>/5
                                            <?php
                                            $score = floatval($activity['rating_score']);
                                            if ($score >= 4) {
                                                echo '<i class="fas fa-star text-success ms-1" title="Excellent"></i>';
                                            } elseif ($score >= 3) {
                                                echo '<i class="fas fa-star text-warning ms-1" title="Good"></i>';
                                            } else {
                                                echo '<i class="fas fa-star text-danger ms-1" title="Needs Improvement"></i>';
                                            }
                                            ?>
                                        </span>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (!empty($activity['rate_remarks'])): ?>
                                    <div class="mb-3">
                                        <strong>Rating Remarks:</strong>
                                        <p class="mt-2 mb-0"><?= nl2br(esc($activity['rate_remarks'])) ?></p>
                                    </div>
                                    <?php endif; ?>

                                    <div class="row">
                                        <?php if (!empty($activity['rated_by_name'])): ?>
                                        <div class="col-md-6">
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>Rated by: <?= esc($activity['rated_by_name']) ?>
                                            </small>
                                        </div>
                                        <?php endif; ?>
                                        <?php if (!empty($activity['rated_at'])): ?>
                                        <div class="col-md-6">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>Rated on: <?= date('d M Y H:i', strtotime($activity['rated_at'])) ?>
                                            </small>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Implementation Details -->
                    <?php if ($implementationData): ?>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Implementation Details</h6>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <strong>Trainers:</strong>
                                                    <p class="text-muted"><?= nl2br(esc($implementationData['trainers'])) ?></p>
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Topics:</strong>
                                                    <p class="text-muted"><?= nl2br(esc($implementationData['topics'])) ?></p>
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

                                        <div class="text-muted">
                                            <small>Implemented on: <?= date('d M Y H:i', strtotime($implementationData['created_at'])) ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No implementation data found for this activity.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Make images clickable for larger view
        $('.card-img-top').css('cursor', 'pointer');
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
