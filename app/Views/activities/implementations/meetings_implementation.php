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
                    <h5 class="card-title mb-0">Implement Meeting Activity: <?= esc($activity['activity_title']) ?></h5>
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
                            This meeting activity has already been implemented. You can view the details below or update the implementation.
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Current Implementation</h6>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <strong>Meeting Title:</strong>
                                                    <p class="text-muted"><?= esc($implementationData['meeting_title']) ?></p>
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Agenda:</strong>
                                                    <p class="text-muted"><?= nl2br(esc($implementationData['agenda'])) ?></p>
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Meeting Date:</strong>
                                                    <p class="text-muted"><?= date('d M Y', strtotime($implementationData['meeting_date'])) ?></p>
                                                </div>
                                                <div class="mb-3">
                                                    <strong>Time:</strong>
                                                    <p class="text-muted">
                                                        <?= $implementationData['start_time'] ? date('H:i', strtotime($implementationData['start_time'])) : 'N/A' ?>
                                                        <?= $implementationData['end_time'] ? ' - ' . date('H:i', strtotime($implementationData['end_time'])) : '' ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <strong>Location:</strong>
                                                    <p class="text-muted"><?= esc($implementationData['location'] ?? 'N/A') ?></p>
                                                </div>
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
                                            <strong>Participants:</strong>
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

                                        <?php if (!empty($implementationData['meeting_minutes'])): ?>
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
                                                        <?php foreach ($implementationData['meeting_minutes'] as $index => $minute): ?>
                                                        <tr>
                                                            <td><?= $index + 1 ?></td>
                                                            <td><?= esc($minute['topic']) ?></td>
                                                            <td><?= esc($minute['discussion']) ?></td>
                                                            <td><?= esc($minute['action_items']) ?></td>
                                                            <td><?= esc($minute['responsible_person']) ?></td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <?php if (!empty($implementationData['meeting_images'])): ?>
                                        <div class="mb-3">
                                            <strong>Meeting Images:</strong>
                                            <div class="row">
                                                <?php foreach ($implementationData['meeting_images'] as $index => $image): ?>
                                                <div class="col-md-3 mb-3">
                                                    <div class="card">
                                                        <img src="<?= base_url($image['file_path']) ?>" class="card-img-top" alt="Meeting Image" style="height: 150px; object-fit: cover;">
                                                        <div class="card-body p-2">
                                                            <small class="text-muted"><?= esc($image['caption']) ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <?php if (!empty($implementationData['meeting_files'])): ?>
                                        <div class="mb-3">
                                            <strong>Meeting Files:</strong>
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
                                                        <?php foreach ($implementationData['meeting_files'] as $index => $file): ?>
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
                    <?php endif; ?>

                    <!-- Implementation Form -->
                    <form action="<?= base_url('activities/' . $activity['id'] . '/save-implementation') ?>" method="post" enctype="multipart/form-data" id="meetingImplementationForm">
                        <?= csrf_field() ?>
                        
                        <h6 class="fw-bold mb-3"><?= $implementationData ? 'Update' : 'Add' ?> Meeting Implementation</h6>
                        
                        <!-- Meeting Basic Information -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Meeting Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meeting_title" 
                                       value="<?= old('meeting_title', $implementationData['meeting_title'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Meeting Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="meeting_date" 
                                       value="<?= old('meeting_date', isset($implementationData['meeting_date']) ? date('Y-m-d', strtotime($implementationData['meeting_date'])) : '') ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Start Time</label>
                                <input type="time" class="form-control" name="start_time" 
                                       value="<?= old('start_time', $implementationData['start_time'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">End Time</label>
                                <input type="time" class="form-control" name="end_time" 
                                       value="<?= old('end_time', $implementationData['end_time'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" name="location" 
                                       value="<?= old('location', $implementationData['location'] ?? '') ?>" 
                                       placeholder="Meeting venue/location">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Agenda <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="agenda" rows="4" required 
                                      placeholder="Enter meeting agenda"><?= old('agenda', $implementationData['agenda'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">GPS Coordinates</label>
                            <input type="text" class="form-control" name="gps_coordinates"
                                   value="<?= old('gps_coordinates', $implementationData['gps_coordinates'] ?? '') ?>"
                                   placeholder="e.g., -1.2921, 36.8219">
                            <div class="form-text">Optional: GPS coordinates of the meeting location</div>
                        </div>

                        <!-- Participants Section -->
                        <div class="mb-3">
                            <label class="form-label">Participants Information</label>
                            <div id="participantsContainer">
                                <?php
                                $existingParticipants = old('participant_name') ? array_map(null,
                                    old('participant_name'), old('participant_organization'), old('participant_position'),
                                    old('participant_phone'), old('participant_email')
                                ) : ($implementationData['participants'] ?? []);

                                if (empty($existingParticipants)):
                                    $existingParticipants = [['', '', '', '', '']]; // Add one empty row
                                endif;
                                ?>

                                <?php foreach ($existingParticipants as $index => $participant): ?>
                                <div class="participant-item border p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="participant_name[]"
                                                   value="<?= esc($participant['name'] ?? $participant[0] ?? '') ?>"
                                                   placeholder="Participant name" <?= !$implementationData ? 'required' : '' ?>>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Organization</label>
                                            <input type="text" class="form-control" name="participant_organization[]"
                                                   value="<?= esc($participant['organization'] ?? $participant[1] ?? '') ?>"
                                                   placeholder="Organization">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Position</label>
                                            <input type="text" class="form-control" name="participant_position[]"
                                                   value="<?= esc($participant['position'] ?? $participant[2] ?? '') ?>"
                                                   placeholder="Position">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Phone</label>
                                            <input type="text" class="form-control" name="participant_phone[]"
                                                   value="<?= esc($participant['phone'] ?? $participant[3] ?? '') ?>"
                                                   placeholder="Phone number">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="participant_email[]"
                                                   value="<?= esc($participant['email'] ?? $participant[4] ?? '') ?>"
                                                   placeholder="Email address">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-participant" style="<?= $index === 0 ? 'display: none;' : '' ?>">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" id="addParticipant" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> Add Another Participant
                            </button>
                        </div>

                        <!-- Meeting Minutes Section -->
                        <div class="mb-3">
                            <label class="form-label">Meeting Minutes</label>
                            <div id="minutesContainer">
                                <?php
                                $existingMinutes = old('minute_topic') ? array_map(null,
                                    old('minute_topic'), old('minute_discussion'), old('minute_action_items'),
                                    old('minute_responsible_person')
                                ) : ($implementationData['meeting_minutes'] ?? []);

                                if (empty($existingMinutes)):
                                    $existingMinutes = [['', '', '', '']]; // Add one empty row
                                endif;
                                ?>

                                <?php foreach ($existingMinutes as $index => $minute): ?>
                                <div class="minute-item border p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label">Topic</label>
                                            <input type="text" class="form-control" name="minute_topic[]"
                                                   value="<?= esc($minute['topic'] ?? $minute[0] ?? '') ?>"
                                                   placeholder="Discussion topic">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Discussion</label>
                                            <textarea class="form-control" name="minute_discussion[]" rows="2"
                                                      placeholder="Key discussion points"><?= esc($minute['discussion'] ?? $minute[1] ?? '') ?></textarea>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Action Items</label>
                                            <textarea class="form-control" name="minute_action_items[]" rows="2"
                                                      placeholder="Action items/decisions"><?= esc($minute['action_items'] ?? $minute[2] ?? '') ?></textarea>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Responsible Person</label>
                                            <input type="text" class="form-control" name="minute_responsible_person[]"
                                                   value="<?= esc($minute['responsible_person'] ?? $minute[3] ?? '') ?>"
                                                   placeholder="Person responsible">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-minute" style="<?= $index === 0 ? 'display: none;' : '' ?>">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" id="addMinute" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> Add Another Minute Item
                            </button>
                        </div>

                        <!-- Meeting Images Section -->
                        <div class="mb-3">
                            <label class="form-label">Upload Meeting Images <?= !$implementationData ? '<span class="text-muted">(Optional)</span>' : '<span class="text-muted">(Optional - only if adding new images)</span>' ?></label>
                            <div id="meetingImagesContainer">
                                <div class="meeting-image-item border p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Image File</label>
                                            <input type="file" class="form-control" name="meeting_images[]" accept="image/*">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Caption</label>
                                            <input type="text" class="form-control" name="meeting_image_captions[]" placeholder="Enter caption for this image">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-meeting-image" style="display: none;">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                            <button type="button" id="addMeetingImage" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> Add Another Image
                            </button>
                            <div class="form-text">Supported formats: JPG, PNG, GIF</div>
                        </div>

                        <!-- Meeting Files Section -->
                        <div class="mb-3">
                            <label class="form-label">Upload Meeting Files <?= !$implementationData ? '<span class="text-muted">(Optional)</span>' : '<span class="text-muted">(Optional - only if adding new files)</span>' ?></label>
                            <div id="meetingFilesContainer">
                                <div class="meeting-file-item border p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">File</label>
                                            <input type="file" class="form-control" name="meeting_files[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Caption</label>
                                            <input type="text" class="form-control" name="meeting_file_captions[]" placeholder="Enter caption for this file">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-meeting-file" style="display: none;">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                            <button type="button" id="addMeetingFile" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> Add Another File
                            </button>
                            <div class="form-text">Supported formats: PDF, Word, Excel, PowerPoint</div>
                        </div>

                        <!-- Signing Sheet Upload -->
                        <div class="mb-3">
                            <label class="form-label">Signing Sheet <?= !$implementationData ? '<span class="text-danger">*</span>' : '<span class="text-muted">(Optional - only if updating)</span>' ?></label>
                            <input type="file" class="form-control" name="signing_sheet" accept=".pdf,.jpg,.jpeg,.png" <?= !$implementationData ? 'required' : '' ?>>
                            <div class="form-text">Upload the signed attendance sheet (PDF or Image format)</div>
                        </div>

                        <!-- Remarks -->
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea class="form-control" name="remarks" rows="3"
                                      placeholder="Any additional remarks or notes"><?= old('remarks', $implementationData['remarks'] ?? '') ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('activities/' . $activity['id']) ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?= $implementationData ? 'Update' : 'Save' ?> Implementation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Add Participant button click
        $('#addParticipant').click(function(e) {
            e.preventDefault();
            const newParticipantItem = `
                <div class="participant-item border p-3 mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="participant_name[]" placeholder="Participant name" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Organization</label>
                            <input type="text" class="form-control" name="participant_organization[]" placeholder="Organization">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Position</label>
                            <input type="text" class="form-control" name="participant_position[]" placeholder="Position">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="participant_phone[]" placeholder="Phone number">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="participant_email[]" placeholder="Email address">
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-participant">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            `;
            $('#participantsContainer').append(newParticipantItem);
            updateRemoveButtons();
        });

        // Remove participant button click
        $(document).on('click', '.remove-participant', function(e) {
            e.preventDefault();
            $(this).closest('.participant-item').remove();
            updateRemoveButtons();
        });

        // Add Minute button click
        $('#addMinute').click(function(e) {
            e.preventDefault();
            const newMinuteItem = `
                <div class="minute-item border p-3 mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Topic</label>
                            <input type="text" class="form-control" name="minute_topic[]" placeholder="Discussion topic">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Discussion</label>
                            <textarea class="form-control" name="minute_discussion[]" rows="2" placeholder="Key discussion points"></textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Action Items</label>
                            <textarea class="form-control" name="minute_action_items[]" rows="2" placeholder="Action items/decisions"></textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Responsible Person</label>
                            <input type="text" class="form-control" name="minute_responsible_person[]" placeholder="Person responsible">
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-minute">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            `;
            $('#minutesContainer').append(newMinuteItem);
            updateMinuteRemoveButtons();
        });

        // Remove minute button click
        $(document).on('click', '.remove-minute', function(e) {
            e.preventDefault();
            $(this).closest('.minute-item').remove();
            updateMinuteRemoveButtons();
        });

        // Add Meeting Image button click
        $('#addMeetingImage').click(function(e) {
            e.preventDefault();
            const newImageItem = `
                <div class="meeting-image-item border p-3 mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Image File</label>
                            <input type="file" class="form-control" name="meeting_images[]" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Caption</label>
                            <input type="text" class="form-control" name="meeting_image_captions[]" placeholder="Enter caption for this image">
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-meeting-image">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            `;
            $('#meetingImagesContainer').append(newImageItem);
            updateImageRemoveButtons();
        });

        // Remove meeting image button click
        $(document).on('click', '.remove-meeting-image', function(e) {
            e.preventDefault();
            $(this).closest('.meeting-image-item').remove();
            updateImageRemoveButtons();
        });

        // Add Meeting File button click
        $('#addMeetingFile').click(function(e) {
            e.preventDefault();
            const newFileItem = `
                <div class="meeting-file-item border p-3 mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">File</label>
                            <input type="file" class="form-control" name="meeting_files[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Caption</label>
                            <input type="text" class="form-control" name="meeting_file_captions[]" placeholder="Enter caption for this file">
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-meeting-file">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            `;
            $('#meetingFilesContainer').append(newFileItem);
            updateFileRemoveButtons();
        });

        // Remove meeting file button click
        $(document).on('click', '.remove-meeting-file', function(e) {
            e.preventDefault();
            $(this).closest('.meeting-file-item').remove();
            updateFileRemoveButtons();
        });

        // Update remove buttons visibility
        function updateRemoveButtons() {
            const participantItems = $('.participant-item');
            participantItems.each(function(index) {
                const removeBtn = $(this).find('.remove-participant');
                if (index === 0 && participantItems.length === 1) {
                    removeBtn.hide();
                } else {
                    removeBtn.show();
                }
            });
        }

        function updateMinuteRemoveButtons() {
            const minuteItems = $('.minute-item');
            minuteItems.each(function(index) {
                const removeBtn = $(this).find('.remove-minute');
                if (index === 0 && minuteItems.length === 1) {
                    removeBtn.hide();
                } else {
                    removeBtn.show();
                }
            });
        }

        function updateImageRemoveButtons() {
            const imageItems = $('.meeting-image-item');
            imageItems.each(function(index) {
                const removeBtn = $(this).find('.remove-meeting-image');
                if (index === 0 && imageItems.length === 1) {
                    removeBtn.hide();
                } else {
                    removeBtn.show();
                }
            });
        }

        function updateFileRemoveButtons() {
            const fileItems = $('.meeting-file-item');
            fileItems.each(function(index) {
                const removeBtn = $(this).find('.remove-meeting-file');
                if (index === 0 && fileItems.length === 1) {
                    removeBtn.hide();
                } else {
                    removeBtn.show();
                }
            });
        }

        // Form validation
        $('#meetingImplementationForm').on('submit', function(e) {
            let isValid = true;
            let errorMessage = '';

            // Check if at least one participant is provided
            const participantNames = $('input[name="participant_name[]"]').map(function() {
                return $(this).val().trim();
            }).get();

            if (participantNames.every(name => name === '')) {
                isValid = false;
                errorMessage += 'At least one participant is required.\n';
            }

            if (!isValid) {
                e.preventDefault();
                alert(errorMessage);
                return false;
            }
        });
    });
</script>
<?= $this->endSection() ?>
