<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="mb-3">
        <a href="<?= base_url('activities/' . $activity['id']) ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Activity Details
        </a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Implement Meeting Activity: <?= esc($activity['activity_title']) ?></h5>
                </div>
                <div class="card-body">
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
                                        <?= $this->include('activities/implementation/meetings_details') ?>
                                        <div class="text-muted">
                                            <small>Implemented on: <?= date('d M Y H:i', strtotime($implementationData['created_at'])) ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Implementation Form -->
                    <form action="<?= base_url('activities/' . $activity['id'] . '/save-implementation') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <h6 class="fw-bold mb-3">Meeting Implementation</h6>

                        <!-- Meeting Basic Information -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Meeting Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" required value="<?= old('title', $implementationData['title'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Meeting Date <span class="text-danger">*</span></label>
                                <?php
                                $meetingDateValue = old('meeting_date');
                                if (!$meetingDateValue && !empty($implementationData['meeting_date'])) {
                                    $meetingDateValue = date('Y-m-d', strtotime($implementationData['meeting_date']));
                                }
                                ?>
                                <input type="date" class="form-control" name="meeting_date" required value="<?= $meetingDateValue ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Start Time</label>
                                <?php
                                $startTimeValue = old('start_time');
                                if (!$startTimeValue && !empty($implementationData['start_time']) && $implementationData['start_time'] !== '0000-00-00 00:00:00') {
                                    $startTimeValue = date('H:i', strtotime($implementationData['start_time']));
                                }
                                ?>
                                <input type="time" class="form-control" name="start_time" value="<?= $startTimeValue ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">End Time</label>
                                <?php
                                $endTimeValue = old('end_time');
                                if (!$endTimeValue && !empty($implementationData['end_time']) && $implementationData['end_time'] !== '0000-00-00 00:00:00') {
                                    $endTimeValue = date('H:i', strtotime($implementationData['end_time']));
                                }
                                ?>
                                <input type="time" class="form-control" name="end_time" value="<?= $endTimeValue ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" name="location" placeholder="Meeting venue/location" value="<?= old('location', $implementationData['location'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Agenda <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="agenda" rows="4" required placeholder="Enter meeting agenda"><?= old('agenda', $implementationData['agenda'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">GPS Coordinates</label>
                            <input type="text" class="form-control" name="gps_coordinates" placeholder="e.g., -1.2921, 36.8219" value="<?= old('gps_coordinates', $implementationData['gps_coordinates'] ?? '') ?>">
                            <div class="form-text">Optional: GPS coordinates of the meeting location</div>
                        </div>

                        <!-- Participants Section -->
                        <div class="mb-3">
                            <label class="form-label">Participants Information</label>
                            <div id="participantsContainer">
                                <?php
                                $existingParticipants = old('participant_name') ? array_map(null,
                                    old('participant_name'), old('participant_organization')
                                ) : ($implementationData['participants'] ?? []);

                                if (empty($existingParticipants)):
                                    $existingParticipants = [['', '']]; // Add one empty row
                                endif;
                                ?>

                                <?php foreach ($existingParticipants as $index => $participant): ?>
                                <div class="participant-item border p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="participant_name[]" placeholder="Participant name" value="<?= esc($participant['name'] ?? $participant[0] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Organization</label>
                                            <input type="text" class="form-control" name="participant_organization[]" placeholder="Organization" value="<?= esc($participant['organization'] ?? $participant[1] ?? '') ?>">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-participant" style="display: none;">
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
                                    old('minute_topic'), old('minute_discussion')
                                ) : ($implementationData['minutes'] ?? []);

                                if (empty($existingMinutes)):
                                    $existingMinutes = [['', '']]; // Add one empty row
                                endif;
                                ?>

                                <?php foreach ($existingMinutes as $index => $minute): ?>
                                <div class="minute-item border p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Topic</label>
                                            <input type="text" class="form-control" name="minute_topic[]" placeholder="Discussion topic" value="<?= esc($minute['topic'] ?? $minute[0] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Discussion</label>
                                            <textarea class="form-control" name="minute_discussion[]" rows="2" placeholder="Key discussion points"><?= esc($minute['discussion'] ?? $minute[1] ?? '') ?></textarea>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-minute" style="display: none;">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" id="addMinute" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> Add Another Minute Item
                            </button>
                        </div>

                        <!-- Signing Sheet Upload -->
                        <div class="mb-3">
                            <label class="form-label">Signing Sheet</label>
                            <?php if (!empty($implementationData['signing_sheet_filepath'])): ?>
                            <div class="mb-2">
                                <small class="text-muted">Current file: </small>
                                <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i> Download Current Signing Sheet
                                </a>
                            </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" name="signing_sheet" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text">Upload the signed attendance sheet (PDF or Image format)</div>
                        </div>

                        <!-- Meeting Attachments Section -->
                        <div class="mb-3">
                            <label class="form-label">Meeting Attachments</label>

                            <!-- Display existing attachments -->
                            <?php if (!empty($implementationData['attachments'])): ?>
                            <div class="mb-3">
                                <strong>Current Attachments:</strong>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>File Name</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($implementationData['attachments'] as $index => $attachment): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= esc($attachment['filename'] ?? $attachment['original_name'] ?? 'Unknown') ?></td>
                                                <td>
                                                    <a href="<?= base_url($attachment['path'] ?? $attachment['filepath'] ?? '') ?>" target="_blank" class="btn btn-sm btn-outline-primary">
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

                            <!-- Upload new attachments -->
                            <div id="attachmentsContainer">
                                <div class="attachment-item border p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label class="form-label">Meeting Attachment</label>
                                            <input type="file" class="form-control" name="meeting_attachments[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Description</label>
                                            <input type="text" class="form-control" name="attachment_descriptions[]" placeholder="File description">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-attachment" style="display: none;">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                            <button type="button" id="addAttachment" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> Add Another Attachment
                            </button>
                            <div class="form-text">Upload meeting-related documents, presentations, or other files</div>
                        </div>

                        <!-- Remarks -->
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea class="form-control" name="remarks" rows="3" placeholder="Any additional remarks or notes"><?= old('remarks', $implementationData['remarks'] ?? '') ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('activities/' . $activity['id']) ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Implementation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update remove buttons visibility for participants
    function updateParticipantRemoveButtons() {
        const participantItems = document.querySelectorAll('.participant-item');
        participantItems.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-participant');
            if (removeBtn) {
                removeBtn.style.display = participantItems.length > 1 ? 'inline-block' : 'none';
            }
        });
    }

    // Update remove buttons visibility for minutes
    function updateMinuteRemoveButtons() {
        const minuteItems = document.querySelectorAll('.minute-item');
        minuteItems.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-minute');
            if (removeBtn) {
                removeBtn.style.display = minuteItems.length > 1 ? 'inline-block' : 'none';
            }
        });
    }

    // Update remove buttons visibility for attachments
    function updateAttachmentRemoveButtons() {
        const attachmentItems = document.querySelectorAll('.attachment-item');
        attachmentItems.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-attachment');
            if (removeBtn) {
                removeBtn.style.display = attachmentItems.length > 1 ? 'inline-block' : 'none';
            }
        });
    }

    // Initialize remove buttons
    updateParticipantRemoveButtons();
    updateMinuteRemoveButtons();
    updateAttachmentRemoveButtons();

    // Add participant functionality
    document.getElementById('addParticipant').addEventListener('click', function() {
        const container = document.getElementById('participantsContainer');
        const newItem = document.createElement('div');
        newItem.className = 'participant-item border p-3 mb-3';
        newItem.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="participant_name[]" placeholder="Participant name">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Organization</label>
                    <input type="text" class="form-control" name="participant_organization[]" placeholder="Organization">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-danger mt-2 remove-participant">
                <i class="fas fa-trash"></i> Remove
            </button>
        `;
        container.appendChild(newItem);
        updateParticipantRemoveButtons();
    });

    // Remove participant functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-participant') || e.target.closest('.remove-participant')) {
            const item = e.target.closest('.participant-item');
            if (item) {
                item.remove();
                updateParticipantRemoveButtons();
            }
        }
    });

    // Add minute functionality
    document.getElementById('addMinute').addEventListener('click', function() {
        const container = document.getElementById('minutesContainer');
        const newItem = document.createElement('div');
        newItem.className = 'minute-item border p-3 mb-3';
        newItem.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Topic</label>
                    <input type="text" class="form-control" name="minute_topic[]" placeholder="Discussion topic">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Discussion</label>
                    <textarea class="form-control" name="minute_discussion[]" rows="2" placeholder="Key discussion points"></textarea>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-danger mt-2 remove-minute">
                <i class="fas fa-trash"></i> Remove
            </button>
        `;
        container.appendChild(newItem);
        updateMinuteRemoveButtons();
    });

    // Remove minute functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-minute') || e.target.closest('.remove-minute')) {
            const item = e.target.closest('.minute-item');
            if (item) {
                item.remove();
                updateMinuteRemoveButtons();
            }
        }
    });

    // Add attachment functionality
    document.getElementById('addAttachment').addEventListener('click', function() {
        const container = document.getElementById('attachmentsContainer');
        const newItem = document.createElement('div');
        newItem.className = 'attachment-item border p-3 mb-3';
        newItem.innerHTML = `
            <div class="row">
                <div class="col-md-8">
                    <label class="form-label">Meeting Attachment</label>
                    <input type="file" class="form-control" name="meeting_attachments[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="attachment_descriptions[]" placeholder="File description">
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-danger mt-2 remove-attachment">
                <i class="fas fa-trash"></i> Remove
            </button>
        `;
        container.appendChild(newItem);
        updateAttachmentRemoveButtons();
    });

    // Remove attachment functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-attachment') || e.target.closest('.remove-attachment')) {
            const item = e.target.closest('.attachment-item');
            if (item) {
                item.remove();
                updateAttachmentRemoveButtons();
            }
        }
    });
});
</script>

<?= $this->endSection() ?>
