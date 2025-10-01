<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="mb-3">
        <a href="<?= base_url('activities/' . $activity['id']) ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Activity Details
        </a>
    </div>

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
                    <h5 class="card-title mb-0">Implement Training Activity: <?= esc($activity['activity_title']) ?></h5>
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
                                            
                                            <p class="mb-1"><strong>Location:</strong> <?= esc($activity['location'] ?? 'N/A') ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Supervisor:</strong> <?= esc($activity['supervisor_name'] ?? 'N/A') ?></p>
                                            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-<?= $activity['status'] === 'approved' ? 'success' : 'warning' ?>"><?= ucfirst(esc($activity['status'])) ?></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Remarks Section -->
                    <?php if (!empty($activity['status_remarks'])): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <h6 class="alert-heading">
                                    <i class="fas fa-comment-alt me-2"></i>Status Remarks
                                </h6>
                                <p class="mb-2"><?= nl2br(esc($activity['status_remarks'])) ?></p>
                                <?php if (!empty($activity['status_by_name'])): ?>
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>By: <?= esc($activity['status_by_name']) ?>
                                    <?php if (!empty($activity['status_at'])): ?>
                                    <i class="fas fa-clock ms-2 me-1"></i>On: <?= date('d M Y H:i', strtotime($activity['status_at'])) ?>
                                    <?php endif; ?>
                                </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Show existing implementation data if exists -->
                    <?php if ($implementationData): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This training activity has already been implemented. You can view the details below or update the implementation.
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Current Implementation</h6>
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
                                                                <a href="<?= base_url($file['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                                                    <i class="fas fa-download"></i> Download
                                                                </a>
                                                                <button type="button" class="btn btn-sm btn-outline-warning me-1 edit-training-file"
                                                                        data-index="<?= $index ?>"
                                                                        data-caption="<?= esc($file['caption']) ?>"
                                                                        data-original="<?= esc($file['original_name']) ?>">
                                                                    <i class="fas fa-edit"></i> Edit
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-outline-danger remove-existing-training-file"
                                                                        data-index="<?= $index ?>">
                                                                    <i class="fas fa-trash"></i> Remove
                                                                </button>
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
                    <?php endif; ?>

                    <!-- Training Implementation Form -->
                    <form action="<?= base_url('activities/' . $activity['id'] . '/save-implementation') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <!-- Hidden inputs for tracking training file changes -->
                        <input type="hidden" id="trainingFilesToRemove" name="training_files_to_remove" value="">
                        <input type="hidden" id="trainingFilesToUpdate" name="training_files_to_update" value="">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="trainers" class="form-label">Trainers <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="trainers" name="trainers" rows="3" required placeholder="Enter names and details of trainers"><?= old('trainers', $implementationData['trainers'] ?? '') ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="topics" class="form-label">Training Topics <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="topics" name="topics" rows="3" required placeholder="Enter training topics covered"><?= old('topics', $implementationData['topics'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="gps_coordinates" class="form-label">GPS Coordinates <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="gps_coordinates" name="gps_coordinates" required placeholder="e.g., -1.2921, 36.8219" value="<?= old('gps_coordinates', $implementationData['gps_coordinates'] ?? '') ?>">
                            <div class="form-text">Enter GPS coordinates of the training location (latitude, longitude)</div>
                        </div>

                        <!-- Trainees Section -->
                        <div class="mb-3">
                            <label class="form-label">Trainees Information</label>
                            <div id="traineesContainer">
                                <?php 
                                $existingTrainees = old('trainee_name') ? array_map(null, 
                                    old('trainee_name'), old('trainee_age'), old('trainee_gender'), 
                                    old('trainee_phone'), old('trainee_email'), old('trainee_remarks')
                                ) : ($implementationData['trainees'] ?? []);
                                
                                if (empty($existingTrainees)): 
                                    $existingTrainees = [['', '', '', '', '', '']]; // Add one empty row
                                endif;
                                ?>
                                
                                <?php foreach ($existingTrainees as $index => $trainee): ?>
                                <div class="trainee-item border p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="trainee_name[]" placeholder="Full name" value="<?= esc($trainee['name'] ?? $trainee[0] ?? '') ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Age</label>
                                            <input type="number" class="form-control" name="trainee_age[]" placeholder="Age" value="<?= esc($trainee['age'] ?? $trainee[1] ?? '') ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Gender</label>
                                            <select class="form-control" name="trainee_gender[]">
                                                <option value="">Select</option>
                                                <option value="Male" <?= ($trainee['gender'] ?? $trainee[2] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                                                <option value="Female" <?= ($trainee['gender'] ?? $trainee[2] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Phone</label>
                                            <input type="text" class="form-control" name="trainee_phone[]" placeholder="Phone number" value="<?= esc($trainee['phone'] ?? $trainee[3] ?? '') ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="trainee_email[]" placeholder="Email address" value="<?= esc($trainee['email'] ?? $trainee[4] ?? '') ?>">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-10">
                                            <label class="form-label">Remarks</label>
                                            <input type="text" class="form-control" name="trainee_remarks[]" placeholder="Additional remarks" value="<?= esc($trainee['remarks'] ?? $trainee[5] ?? '') ?>">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-sm btn-danger remove-trainee" style="display: none;">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" id="addTrainee" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> Add Another Trainee
                            </button>
                        </div>

                        <!-- Training Images Section -->
                        <div class="mb-3">
                            <label class="form-label">Training Images</label>
                            
                            <!-- Keep existing images -->
                            <?php if (!empty($implementationData['training_images'])): ?>
                            <div class="mb-3">
                                <label class="form-label">Existing Images (check to keep):</label>
                                <div class="row">
                                    <?php foreach ($implementationData['training_images'] as $index => $image): ?>
                                    <div class="col-md-3 mb-2">
                                        <div class="card">
                                            <img src="<?= base_url($image) ?>" class="card-img-top" style="height: 100px; object-fit: cover;" alt="Training Image">
                                            <div class="card-body p-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="keep_training_images[]" value="<?= esc($image) ?>" id="keepImage<?= $index ?>" checked>
                                                    <label class="form-check-label" for="keepImage<?= $index ?>">
                                                        <small>Keep this image</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label class="form-label">Upload New Images <?= !$implementationData ? '<span class="text-danger">*</span>' : '<span class="text-muted">(Optional)</span>' ?></label>
                                <input type="file" class="form-control" name="training_images[]" accept="image/*" multiple <?= !$implementationData ? 'required' : '' ?>>
                                <div class="form-text">Select multiple images from the training session. Supported formats: JPG, PNG, GIF</div>
                            </div>
                        </div>

                        <!-- Training Files Upload Section -->
                        <div class="mb-3">
                            <label class="form-label">Upload Training Files <?= !$implementationData ? '<span class="text-danger">*</span>' : '<span class="text-muted">(Optional - only if adding new files)</span>' ?></label>
                            <div id="trainingFilesContainer">
                                <div class="training-file-item border p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Training File</label>
                                            <input type="file" class="form-control" name="training_files[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" <?= !$implementationData ? 'required' : '' ?>>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Caption</label>
                                            <input type="text" class="form-control" name="training_file_captions[]" placeholder="Enter caption for this file" <?= !$implementationData ? 'required' : '' ?>>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-training-file" style="display: none;">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                            <button type="button" id="addTrainingFile" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> Add Another Training File
                            </button>
                            <div class="form-text">Supported formats: PDF, Word, Excel, PowerPoint</div>
                        </div>

                        <!-- Signing Sheet Upload -->
                        <div class="mb-3">
                            <label for="signing_sheet" class="form-label">Signing Sheet</label>
                            <?php if (!empty($implementationData['signing_sheet_filepath'])): ?>
                            <div class="mb-2">
                                <small class="text-muted">Current file: </small>
                                <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> Download Current Signing Sheet
                                </a>
                            </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="signing_sheet" name="signing_sheet" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <div class="form-text">Upload the signed attendance sheet (PDF, Word, or Image format)</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Save Implementation
                            </button>
                            <a href="<?= base_url('activities/' . $activity['id']) ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Training File Caption Modal -->
<div class="modal fade" id="editTrainingFileCaptionModal" tabindex="-1" aria-labelledby="editTrainingFileCaptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editTrainingFileCaptionModalLabel">Edit Training File Caption</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editTrainingFileCaptionOriginalName" class="form-label">File Name</label>
                    <input type="text" class="form-control" id="editTrainingFileCaptionOriginalName" readonly>
                </div>
                <div class="mb-3">
                    <label for="editTrainingFileCaptionInput" class="form-label">Caption <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editTrainingFileCaptionInput" placeholder="Enter new caption">
                    <div class="form-text">Enter a descriptive caption for this training file.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-warning" id="confirmEditTrainingFileCaption">
                    <i class="fas fa-save me-1"></i> Update Caption
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Remove Training File Modal -->
<div class="modal fade" id="removeTrainingFileModal" tabindex="-1" aria-labelledby="removeTrainingFileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="removeTrainingFileModalLabel">Remove Training File</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone.
                </div>
                <p>Are you sure you want to remove the following training file?</p>
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title mb-1" id="removeTrainingFileName"></h6>
                        <small class="text-muted" id="removeTrainingFileCaption"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmRemoveTrainingFile">
                    <i class="fas fa-trash me-1"></i> Remove File
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Add Trainee button click
        $('#addTrainee').click(function(e) {
            e.preventDefault();
            const newTraineeItem = `
                <div class="trainee-item border p-3 mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="trainee_name[]" placeholder="Full name">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Age</label>
                            <input type="number" class="form-control" name="trainee_age[]" placeholder="Age">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Gender</label>
                            <select class="form-control" name="trainee_gender[]">
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="trainee_phone[]" placeholder="Phone number">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="trainee_email[]" placeholder="Email address">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-10">
                            <label class="form-label">Remarks</label>
                            <input type="text" class="form-control" name="trainee_remarks[]" placeholder="Additional remarks">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-sm btn-danger remove-trainee">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            `;
            $('#traineesContainer').append(newTraineeItem);
            updateRemoveButtons();
        });

        // Remove Trainee button click
        $(document).on('click', '.remove-trainee', function(e) {
            e.preventDefault();
            $(this).closest('.trainee-item').remove();
            updateRemoveButtons();
        });

        // Update remove buttons visibility
        function updateRemoveButtons() {
            const traineeItems = $('.trainee-item');
            if (traineeItems.length > 1) {
                $('.remove-trainee').show();
            } else {
                $('.remove-trainee').hide();
            }
        }

        // Initialize remove buttons
        updateRemoveButtons();

        // Training Files functionality
        // Add Training File button click
        $('#addTrainingFile').click(function(e) {
            e.preventDefault();
            const newTrainingFileItem = `
                <div class="training-file-item border p-3 mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Training File</label>
                            <input type="file" class="form-control" name="training_files[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Caption</label>
                            <input type="text" class="form-control" name="training_file_captions[]" placeholder="Enter caption for this file" required>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-training-file">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            `;
            $('#trainingFilesContainer').append(newTrainingFileItem);
            updateTrainingFileRemoveButtons();
        });

        // Remove Training File button click
        $(document).on('click', '.remove-training-file', function(e) {
            e.preventDefault();
            $(this).closest('.training-file-item').remove();
            updateTrainingFileRemoveButtons();
        });

        // Update training file remove buttons visibility
        function updateTrainingFileRemoveButtons() {
            const trainingFileItems = $('.training-file-item');
            if (trainingFileItems.length > 1) {
                $('.remove-training-file').show();
            } else {
                $('.remove-training-file').hide();
            }
        }

        // Initialize training file remove buttons
        updateTrainingFileRemoveButtons();

        // Edit existing training file caption
        let currentEditTrainingFileIndex = null;
        let currentEditTrainingFileButton = null;

        $(document).on('click', '.edit-training-file', function(e) {
            e.preventDefault();
            currentEditTrainingFileIndex = $(this).data('index');
            currentEditTrainingFileButton = $(this);
            const currentCaption = $(this).data('caption');
            const originalName = $(this).data('original');

            // Populate modal
            $('#editTrainingFileCaptionOriginalName').val(originalName);
            $('#editTrainingFileCaptionInput').val(currentCaption);

            // Show modal
            $('#editTrainingFileCaptionModal').modal('show');
        });

        // Confirm edit training file caption
        $('#confirmEditTrainingFileCaption').click(function() {
            const newCaption = $('#editTrainingFileCaptionInput').val().trim();
            if (newCaption !== '') {
                // Update the display
                currentEditTrainingFileButton.closest('tr').find('td:eq(1)').text(newCaption);
                currentEditTrainingFileButton.data('caption', newCaption);

                // Track the update
                let updatesData = JSON.parse($('#trainingFilesToUpdate').val() || '{}');
                updatesData[currentEditTrainingFileIndex] = { caption: newCaption };
                $('#trainingFilesToUpdate').val(JSON.stringify(updatesData));

                // Hide modal
                $('#editTrainingFileCaptionModal').modal('hide');
            } else {
                // Show validation error
                $('#editTrainingFileCaptionInput').addClass('is-invalid');
                if (!$('#editTrainingFileCaptionInput').next('.invalid-feedback').length) {
                    $('#editTrainingFileCaptionInput').after('<div class="invalid-feedback">Caption is required.</div>');
                }
            }
        });

        // Remove validation error when user types
        $('#editTrainingFileCaptionInput').on('input', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });

        // Handle Enter key in training file caption input
        $('#editTrainingFileCaptionInput').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                $('#confirmEditTrainingFileCaption').click();
            }
        });

        // Remove existing training file
        let currentRemoveTrainingFileIndex = null;
        let currentRemoveTrainingFileRow = null;

        $(document).on('click', '.remove-existing-training-file', function(e) {
            e.preventDefault();
            currentRemoveTrainingFileIndex = $(this).data('index');
            currentRemoveTrainingFileRow = $(this).closest('tr');
            const originalName = currentRemoveTrainingFileRow.find('td:eq(2)').text();
            const caption = currentRemoveTrainingFileRow.find('td:eq(1)').text();

            // Populate modal
            $('#removeTrainingFileName').text(originalName);
            $('#removeTrainingFileCaption').text(caption);

            // Show modal
            $('#removeTrainingFileModal').modal('show');
        });

        // Confirm remove training file
        $('#confirmRemoveTrainingFile').click(function() {
            // Remove the row
            currentRemoveTrainingFileRow.remove();

            // Track the removal
            let removalsData = JSON.parse($('#trainingFilesToRemove').val() || '[]');
            removalsData.push(currentRemoveTrainingFileIndex);
            $('#trainingFilesToRemove').val(JSON.stringify(removalsData));

            // Hide modal
            $('#removeTrainingFileModal').modal('hide');
        });
    });
</script>
<?= $this->endSection() ?>
