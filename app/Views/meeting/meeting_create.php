<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Create New Meeting</h5>
                <a href="<?= base_url('meetings') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Meetings
                </a>
            </div>
            <div class="card-body">
                <?= form_open_multipart('meetings', ['id' => 'meetingForm']) ?>

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
                                    <option value="<?= $branch['id'] ?>" <?= set_select('branch_id', $branch['id']) ?>>
                                        <?= esc($branch['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="title" class="form-label">Meeting Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="<?= set_value('title') ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="meeting_date" class="form-label">Meeting Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="meeting_date" name="meeting_date" value="<?= set_value('meeting_date') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="start_time" name="start_time" value="<?= set_value('start_time') ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="end_time" name="end_time" value="<?= set_value('end_time') ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" value="<?= set_value('location') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="access_type" class="form-label">Access Type <span class="text-danger">*</span></label>
                            <select name="access_type" id="access_type" class="form-control" required>
                                <option value="">Select Access Type</option>
                                <option value="private" <?= set_select('access_type', 'private') ?>>Private (Branch Only)</option>
                                <option value="internal" <?= set_select('access_type', 'internal') ?>>Internal (Logged In Users)</option>
                                <option value="public" <?= set_select('access_type', 'public') ?>>Public</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="agenda" class="form-label">Agenda</label>
                        <textarea class="form-control" id="agenda" name="agenda" rows="5"><?= set_value('agenda') ?></textarea>
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
                                    <tr id="participantRow0">
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
                                            <button type="button" class="btn btn-sm btn-danger remove-participant" disabled>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
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

                    <div class="mb-3">
                        <label for="attachments" class="form-label">Attachments</label>
                        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                        <small class="text-muted">You can upload multiple files (PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, etc.)</small>
                    </div>

                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3"><?= set_value('remarks') ?></textarea>
                    </div>

                    <div class="text-end">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Create Meeting</button>
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
        // Set default date to today
        if ($('#meeting_date').val() === '') {
            const today = new Date().toISOString().split('T')[0];
            $('#meeting_date').val(today);
        }

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
        let participantCounter = 1;

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

            // Enable the first row's remove button if we have more than one row
            if ($('#participantsTable tbody tr').length > 1) {
                $('#participantRow0 .remove-participant').prop('disabled', false);
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
    });
</script>
<?= $this->endSection() ?>