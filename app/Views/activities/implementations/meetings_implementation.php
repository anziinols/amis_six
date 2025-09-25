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
                    <!-- Implementation Form -->
                    <form action="<?= base_url('activities/' . $activity['id'] . '/save-implementation') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <h6 class="fw-bold mb-3">Meeting Implementation</h6>
                        
                        <!-- Meeting Basic Information -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Meeting Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Meeting Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="meeting_date" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Start Time</label>
                                <input type="time" class="form-control" name="start_time">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">End Time</label>
                                <input type="time" class="form-control" name="end_time">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" name="location" placeholder="Meeting venue/location">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Agenda <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="agenda" rows="4" required placeholder="Enter meeting agenda"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">GPS Coordinates</label>
                            <input type="text" class="form-control" name="gps_coordinates" placeholder="e.g., -1.2921, 36.8219">
                            <div class="form-text">Optional: GPS coordinates of the meeting location</div>
                        </div>

                        <!-- Participants Section -->
                        <div class="mb-3">
                            <label class="form-label">Participants Information</label>
                            <div id="participantsContainer">
                                <div class="participant-item border p-3 mb-3">
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
                                </div>
                            </div>
                            <button type="button" id="addParticipant" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> Add Another Participant
                            </button>
                        </div>

                        <!-- Meeting Minutes Section -->
                        <div class="mb-3">
                            <label class="form-label">Meeting Minutes</label>
                            <div id="minutesContainer">
                                <div class="minute-item border p-3 mb-3">
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
                                </div>
                            </div>
                            <button type="button" id="addMinute" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> Add Another Minute Item
                            </button>
                        </div>

                        <!-- Signing Sheet Upload -->
                        <div class="mb-3">
                            <label class="form-label">Signing Sheet</label>
                            <input type="file" class="form-control" name="signing_sheet" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text">Upload the signed attendance sheet (PDF or Image format)</div>
                        </div>

                        <!-- Remarks -->
                        <div class="mb-3">
                            <label class="form-label">Remarks</label>
                            <textarea class="form-control" name="remarks" rows="3" placeholder="Any additional remarks or notes"></textarea>
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
    });

    // Remove participant functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-participant') || e.target.closest('.remove-participant')) {
            const item = e.target.closest('.participant-item');
            if (item) {
                item.remove();
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
    });

    // Remove minute functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-minute') || e.target.closest('.remove-minute')) {
            const item = e.target.closest('.minute-item');
            if (item) {
                item.remove();
            }
        }
    });
});
</script>

<?= $this->endSection() ?>
