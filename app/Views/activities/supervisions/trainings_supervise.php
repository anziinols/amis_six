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

    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Supervise Training Activity: <?= esc($activity['activity_title']) ?></h5>
                </div>
                <div class="card-body">
                    <!-- Activity Reference Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Activity Reference Information</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Title:</strong> <?= esc($activity['activity_title']) ?></p>
                                            <p class="mb-1"><strong>Type:</strong> <span class="badge bg-info"><?= ucfirst(esc($activity['type'])) ?></span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Performance Output:</strong> <?= esc($activity['performance_output_title'] ?? 'N/A') ?></p>
                                            <p class="mb-1"><strong>Location:</strong> <?= esc($activity['location'] ?? 'N/A') ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Action Officer:</strong> <?= esc($activity['action_officer_name'] ?? 'N/A') ?></p>
                                            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-info text-dark"><?= ucfirst(esc($activity['status'])) ?></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                            <strong>Trainees:</strong>
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
                                            <strong>Training Images:</strong>
                                            <div class="row">
                                                <?php foreach ($implementationData['training_images'] as $index => $image): ?>
                                                <div class="col-md-3 mb-2">
                                                    <div class="card">
                                                        <img src="<?= base_url($image) ?>" class="card-img-top" style="height: 150px; object-fit: cover;" alt="Training Image">
                                                        <div class="card-body p-2">
                                                            <small class="text-muted">Image <?= $index + 1 ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <?php if (!empty($implementationData['training_files'])): ?>
                                        <div class="mb-3">
                                            <strong>Training Files:</strong>
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

                    <!-- Supervision Decision Form -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="card-title mb-0">Supervision Decision</h6>
                                </div>
                                <div class="card-body">
                                    <form action="<?= base_url('activities/' . $activity['id'] . '/process-supervision') ?>" method="post">
                                        <?= csrf_field() ?>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Decision <span class="text-danger">*</span></label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="supervision_decision" id="approve" value="approve" required>
                                                <label class="form-check-label" for="approve">
                                                    <i class="fas fa-check-circle text-success me-1"></i> <strong>Approve</strong> - Activity implementation is satisfactory
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="supervision_decision" id="resend" value="resend" required>
                                                <label class="form-check-label" for="resend">
                                                    <i class="fas fa-redo text-warning me-1"></i> <strong>Resend</strong> - Activity needs to be re-implemented
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="status_remarks" class="form-label">Remarks</label>
                                            <textarea class="form-control" id="status_remarks" name="status_remarks" rows="3" placeholder="Enter your supervision remarks (optional)"></textarea>
                                            <div class="form-text">Provide feedback or reasons for your decision</div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="fas fa-paper-plane me-1"></i> Submit Decision
                                            </button>
                                            <a href="<?= base_url('activities') ?>" class="btn btn-secondary">
                                                <i class="fas fa-times me-1"></i> Cancel
                                            </a>
                                        </div>
                                    </form>
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
