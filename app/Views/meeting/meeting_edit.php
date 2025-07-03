<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Meeting</h5>
                <div>
                    <a href="<?= base_url('meetings/' . $meeting['id']) ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Details
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?= form_open_multipart('meetings/update/' . $meeting['id'], ['id' => 'meetingForm']) ?>

                    <?php if (isset($validation)) : ?>
                        <div class="alert alert-danger">
                            <?= $validation->listErrors() ?>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" id="branch_id" class="form-control" required>
                                <option value="">Select Branch</option>
                                <?php foreach ($branches as $branch) : ?>
                                    <option value="<?= $branch['id'] ?>" <?= ($branch['id'] == $meeting['branch_id']) ? 'selected' : '' ?>>
                                        <?= esc($branch['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="title" class="form-label">Meeting Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="<?= esc($meeting['title']) ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="meeting_date" class="form-label">Meeting Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="meeting_date" name="meeting_date" value="<?= date('Y-m-d', strtotime($meeting['meeting_date'])) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="start_time" name="start_time" value="<?= date('H:i', strtotime($meeting['start_time'])) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="end_time" name="end_time" value="<?= date('H:i', strtotime($meeting['end_time'])) ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" value="<?= esc($meeting['location'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="access_type" class="form-label">Access Type <span class="text-danger">*</span></label>
                            <select name="access_type" id="access_type" class="form-control" required>
                                <option value="">Select Access Type</option>
                                <option value="private" <?= ($meeting['access_type'] ?? '') == 'private' ? 'selected' : '' ?>>Private (Branch Only)</option>
                                <option value="internal" <?= ($meeting['access_type'] ?? '') == 'internal' ? 'selected' : '' ?>>Internal (Logged In Users)</option>
                                <option value="public" <?= ($meeting['access_type'] ?? '') == 'public' ? 'selected' : '' ?>>Public</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="agenda" class="form-label">Agenda</label>
                        <textarea class="form-control" id="agenda" name="agenda" rows="5"><?= esc($meeting['agenda'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Participants</label>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="participantsTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Contacts</th>
                                        <th>Remarks</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $participants = [];
                                    if (!empty($meeting['participants'])) {
                                        if (is_array($meeting['participants'])) {
                                            $participants = $meeting['participants'];
                                        } else {
                                            // Handle old format (comma-separated string)
                                            $names = explode(',', $meeting['participants']);
                                            foreach ($names as $name) {
                                                if (trim($name) !== '') {
                                                    $participants[] = [
                                                        'name' => trim($name),
                                                        'position' => '',
                                                        'contacts' => '',
                                                        'remarks' => ''
                                                    ];
                                                }
                                            }
                                        }
                                    }

                                    // Ensure we have at least one row
                                    if (empty($participants)) {
                                        $participants[] = [
                                            'name' => '',
                                            'position' => '',
                                            'contacts' => '',
                                            'remarks' => ''
                                        ];
                                    }

                                    foreach ($participants as $index => $participant) :
                                        // Handle both formats (array of strings or array of objects)
                                        $name = is_array($participant) ? ($participant['name'] ?? '') : $participant;
                                        $position = is_array($participant) ? ($participant['position'] ?? '') : '';
                                        $contacts = is_array($participant) ? ($participant['contacts'] ?? '') : '';
                                        $remarks = is_array($participant) ? ($participant['remarks'] ?? '') : '';
                                    ?>
                                        <tr id="participantRow<?= $index ?>">
                                            <td>
                                                <input type="text" class="form-control form-control-sm" name="participant_name[]" value="<?= esc($name) ?>" required>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" name="participant_position[]" value="<?= esc($position) ?>">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" name="participant_contacts[]" value="<?= esc($contacts) ?>">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" name="participant_remarks[]" value="<?= esc($remarks) ?>">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger remove-participant" <?= count($participants) === 1 ? 'disabled' : '' ?>>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <button type="button" class="btn btn-sm btn-success" id="addParticipant">
                                                <i class="fas fa-plus"></i> Add Participant
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Current Attachments -->
                    <?php if (!empty($meeting['attachments']) && is_array($meeting['attachments'])) : ?>
                        <div class="mb-3">
                            <label class="form-label">Current Attachments</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th>Filename</th>
                                            <th width="20%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($meeting['attachments'] as $index => $attachment) : ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= esc($attachment['filename']) ?></td>
                                                <td>
                                                    <a href="<?= base_url('meetings/download/' . $meeting['id'] . '/' . $index) ?>" class="btn btn-sm btn-info">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger delete-attachment" data-id="<?= $meeting['id'] ?>" data-index="<?= $index ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="attachments" class="form-label">Add New Attachments</label>
                        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                        <small class="text-muted">You can upload multiple files (PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, etc.)</small>
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3"><?= esc($meeting['remarks'] ?? '') ?></textarea>
                    </div>

                    <div class="text-end">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Update Meeting</button>
                    </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Validate end time is after start time
        $('#meetingForm').submit(function(e) {
            const startTime = $('#start_time').val();
            const endTime = $('#end_time').val();

            if (startTime && endTime && startTime >= endTime) {
                e.preventDefault();
                toastr.error('End time must be after start time');
                return false;
            }

            return true;
        });

        // Participant management
        let participantCounter = $('#participantsTable tbody tr').length;

        // Add new participant row
        $('#addParticipant').click(function() {
            const newRow = `
                <tr id="participantRow${participantCounter}">
                    <td>
                        <input type="text" class="form-control form-control-sm" name="participant_name[]" required>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="participant_position[]">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="participant_contacts[]">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm" name="participant_remarks[]">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-participant">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            $('#participantsTable tbody').append(newRow);
            participantCounter++;

            // Enable all remove buttons if we have more than one row
            if ($('#participantsTable tbody tr').length > 1) {
                $('.remove-participant').prop('disabled', false);
            }
        });

        // Remove participant row
        $(document).on('click', '.remove-participant', function() {
            $(this).closest('tr').remove();

            // If only one row remains, disable its remove button
            if ($('#participantsTable tbody tr').length === 1) {
                $('#participantsTable tbody tr:first .remove-participant').prop('disabled', true);
            }
        });

        // Handle attachment deletion
        $('.delete-attachment').click(function() {
            const meetingId = $(this).data('id');
            const attachmentIndex = $(this).data('index');
            const row = $(this).closest('tr');

            if (confirm('Are you sure you want to delete this attachment? This action cannot be undone.')) {
                $.ajax({
                    url: '<?= base_url('meetings/deleteAttachment') ?>/' + meetingId + '/' + attachmentIndex,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            row.fadeOut(300, function() {
                                $(this).remove();
                            });
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while deleting the attachment');
                    }
                });
            }
        });
    });
</script>
<?= $this->endSection() ?>