<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <a href="<?= base_url('activities') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Activities
            </a>
        </div>
        <div class="card-body">
            <!-- Success/Error Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Activity Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Activity Information</h6>
                            <p class="card-text">
                                <strong>Title:</strong> <?= esc($activity['activity_title']) ?><br>
                                <strong>Type:</strong> <span class="badge bg-primary"><?= ucfirst(esc($activity['type'])) ?></span><br>
                                <strong>Description:</strong> <?= esc($activity['activity_description']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Form 1: Link to Duty Instruction Items -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-link me-2"></i>Link to Duty Instruction Items
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Link Form -->
                            <form action="<?= base_url('activities/' . $activity['id'] . '/link-duty-instruction') ?>" method="post" class="mb-4" id="duty-link-form">
                                <?= csrf_field() ?>

                                <div class="mb-3">
                                    <label for="duty_instruction_select" class="form-label">Select Duty Instruction <span class="text-danger">*</span></label>
                                    <select class="form-select" id="duty_instruction_select" name="duty_instruction_id" required>
                                        <option value="">Select duty instruction...</option>
                                        <?php foreach ($dutyInstructions as $instruction): ?>
                                            <option value="<?= $instruction['id'] ?>">
                                                <?= esc($instruction['duty_instruction_title']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="duty_instruction_item_id" class="form-label">Select Duty Instruction Item <span class="text-danger">*</span></label>
                                    <select class="form-select select2-duty-instruction-items" id="duty_instruction_item_id" name="duty_instruction_item_id" required disabled>
                                        <option value="">Select duty instruction first...</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary" id="create-duty-link-btn" disabled>
                                    <i class="fas fa-link"></i> Create Link
                                </button>
                            </form>

                            <!-- Existing Links -->
                            <h6 class="mt-4 mb-3">Existing Duty Instruction Links</h6>
                            <?php if (!empty($linkedDutyInstructions)): ?>
                                <div class="list-group">
                                    <?php foreach ($linkedDutyInstructions as $link): ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><?= esc($link['item']) ?></strong><br>
                                                <small class="text-muted"><?= esc($link['duty_instruction_title']) ?></small>
                                            </div>
                                            <?= form_open('activities/' . $activity['id'] . '/remove-duty-instruction-link', ['class' => 'd-inline', 'onsubmit' => 'return confirm("Are you sure you want to remove this link?")']) ?>
                                                <input type="hidden" name="duty_instruction_item_id" value="<?= $link['duty_instructions_id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Link">
                                                    <i class="fas fa-unlink"></i>
                                                </button>
                                            <?= form_close() ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No duty instruction links found. Use the form above to create links.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Form 2: Link to Workplan Activities -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-link me-2"></i>Link to Workplan Activities
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Link Form -->
                            <form action="<?= base_url('activities/' . $activity['id'] . '/link-workplan-activity') ?>" method="post" class="mb-4" id="workplan-link-form">
                                <?= csrf_field() ?>

                                <div class="mb-3">
                                    <label for="workplan_id" class="form-label">Select Workplan <span class="text-danger">*</span></label>
                                    <select class="form-select" id="workplan_id" name="workplan_id" required>
                                        <option value="">Select workplan...</option>
                                        <?php foreach ($workplans as $workplan): ?>
                                            <option value="<?= $workplan['id'] ?>">
                                                <?= esc($workplan['title']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="workplan_activity_id" class="form-label">Select Workplan Activity <span class="text-danger">*</span></label>
                                    <select class="form-select" id="workplan_activity_id" name="workplan_activity_id" required disabled>
                                        <option value="">Select workplan first...</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary" id="create-workplan-link-btn" disabled>
                                    <i class="fas fa-link"></i> Create Link
                                </button>
                            </form>

                            <!-- Existing Links -->
                            <h6 class="mt-4 mb-3">Existing Workplan Activity Links</h6>
                            <?php if (!empty($linkedWorkplanActivities)): ?>
                                <div class="list-group">
                                    <?php foreach ($linkedWorkplanActivities as $link): ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong><?= esc($link['activity_title']) ?></strong><br>
                                                <small class="text-muted"><?= esc($link['workplan_title']) ?></small>
                                            </div>
                                            <?= form_open('activities/' . $activity['id'] . '/remove-workplan-activity-link', ['class' => 'd-inline', 'onsubmit' => 'return confirm("Are you sure you want to remove this link?")']) ?>
                                                <input type="hidden" name="workplan_activity_id" value="<?= $link['workplan_activities_id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Link">
                                                    <i class="fas fa-unlink"></i>
                                                </button>
                                            <?= form_close() ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No workplan activity links found. Use the form above to create links.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Link Summary</h6>
                            <div class="row text-center">
                                <div class="col-md-6">
                                    <div class="border-end">
                                        <h4 class="text-primary"><?= count($linkedDutyInstructions) ?></h4>
                                        <p class="mb-0">Duty Instruction Links</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-success"><?= count($linkedWorkplanActivities) ?></h4>
                                    <p class="mb-0">Workplan Activity Links</p>
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

<?= $this->section('scripts') ?>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Global CSRF token variables for refreshing
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';

    // Function to update CSRF token in forms and global variables
    function updateCSRFToken(newHash) {
        if (newHash) {
            csrfHash = newHash;
            // Update all CSRF hidden inputs in forms
            $('input[name="' + csrfName + '"]').val(csrfHash);
        }
    }

    // Initialize Select2 for duty instruction items dropdown
    $('.select2-duty-instruction-items').select2({
        theme: 'bootstrap-5',
        placeholder: 'Select duty instruction item...',
        allowClear: true,
        width: '100%'
    });

    // Handle duty instruction change to populate duty instruction items via AJAX
    $('#duty_instruction_select').change(function() {
        var dutyInstructionId = $(this).val();
        var dutyInstructionItemsSelect = $('#duty_instruction_item_id');
        var createLinkBtn = $('#create-duty-link-btn');
        var activityId = <?= $activity['id'] ?>; // Get current activity ID

        // Clear duty instruction items
        dutyInstructionItemsSelect.html('<option value="">Loading...</option>');
        dutyInstructionItemsSelect.prop('disabled', true);
        createLinkBtn.prop('disabled', true);

        if (dutyInstructionId) {
            // Use POST request with CSRF token and activity ID
            $.ajax({
                url: '<?= base_url('activities/get-duty-instruction-items') ?>',
                type: 'POST',
                data: {
                    duty_instruction_id: dutyInstructionId,
                    activity_id: activityId,
                    [csrfName]: csrfHash
                },
                success: function(response) {
                    // Update CSRF token
                    updateCSRFToken(response.csrf_hash);

                    dutyInstructionItemsSelect.html('<option value="">Select duty instruction item...</option>');

                    if (response.success && response.items && response.items.length > 0) {
                        $.each(response.items, function(index, item) {
                            dutyInstructionItemsSelect.append(
                                '<option value="' + item.id + '">' + escapeHtml(item.instruction) + '</option>'
                            );
                        });
                        dutyInstructionItemsSelect.prop('disabled', false);
                    } else {
                        dutyInstructionItemsSelect.html('<option value="">No available items for this duty instruction</option>');
                    }
                },
                error: function() {
                    console.error('Failed to load duty instruction items');
                    dutyInstructionItemsSelect.html('<option value="">Error loading items</option>');
                }
            });
        } else {
            dutyInstructionItemsSelect.html('<option value="">Select duty instruction first...</option>');
            dutyInstructionItemsSelect.prop('disabled', true);
        }
    });

    // Enable create button when duty instruction item is selected
    $('#duty_instruction_item_id').change(function() {
        var createLinkBtn = $('#create-duty-link-btn');
        if ($(this).val()) {
            createLinkBtn.prop('disabled', false);
        } else {
            createLinkBtn.prop('disabled', true);
        }
    });

    // Handle workplan selection change to populate workplan activities via AJAX
    $('#workplan_id').change(function() {
        const workplanId = $(this).val();
        const $workplanActivitySelect = $('#workplan_activity_id');
        const $createLinkBtn = $('#create-workplan-link-btn');
        const activityId = <?= $activity['id'] ?>; // Get current activity ID

        // Clear workplan activities
        $workplanActivitySelect.html('<option value="">Loading...</option>');
        $workplanActivitySelect.prop('disabled', true);
        $createLinkBtn.prop('disabled', true);

        if (workplanId) {
            // Fetch workplan activities via AJAX
            $.ajax({
                url: '<?= base_url('activities/get-workplan-activities') ?>',
                type: 'POST',
                data: {
                    workplan_id: workplanId,
                    activity_id: activityId,
                    [csrfName]: csrfHash
                },
                dataType: 'json',
                success: function(response) {
                    // Update CSRF token
                    updateCSRFToken(response.csrf_hash);

                    if (response.success) {
                        // Clear and populate activities dropdown
                        $workplanActivitySelect.html('<option value="">Select workplan activity...</option>');

                        if (response.activities.length > 0) {
                            $.each(response.activities, function(index, activity) {
                                $workplanActivitySelect.append(
                                    '<option value="' + activity.id + '">' +
                                    escapeHtml(activity.title) +
                                    '</option>'
                                );
                            });
                            $workplanActivitySelect.prop('disabled', false);
                        } else {
                            $workplanActivitySelect.html('<option value="">No available activities for this workplan</option>');
                        }
                    } else {
                        $workplanActivitySelect.html('<option value="">Error loading activities</option>');
                        console.error('Error: ' + (response.message || 'Failed to load activities'));
                    }
                },
                error: function() {
                    $workplanActivitySelect.html('<option value="">Error loading activities</option>');
                    console.error('Failed to load workplan activities. Please try again.');
                }
            });
        } else {
            // Reset activities dropdown when no workplan is selected
            $workplanActivitySelect.html('<option value="">Select workplan first...</option>');
            $workplanActivitySelect.prop('disabled', true);
        }
    });

    // Enable create button when workplan activity is selected
    $('#workplan_activity_id').change(function() {
        var createLinkBtn = $('#create-workplan-link-btn');
        if ($(this).val()) {
            createLinkBtn.prop('disabled', false);
        } else {
            createLinkBtn.prop('disabled', true);
        }
    });

    // Helper function to escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Helper function to show alerts
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        // Remove existing alerts
        $('.alert').remove();

        // Add new alert at the top of the card body
        $('.card-body').first().prepend(alertHtml);

        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            $('.alert').fadeOut();
        }, 5000);
    }

    // Handle duty instruction link form submission
    $('#duty-link-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = $('#create-duty-link-btn');
        const originalBtnText = submitBtn.html();

        // Show loading state
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating Link...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: {
                duty_instruction_item_id: $('#duty_instruction_item_id').val(),
                [csrfName]: csrfHash
            },
            success: function(response) {
                // Update CSRF token
                updateCSRFToken(response.csrf_hash);

                if (response.success) {
                    showAlert('success', response.message || 'Link created successfully!');

                    // Reset form
                    form[0].reset();
                    $('#duty_instruction_item_id').prop('disabled', true).html('<option value="">Select duty instruction first...</option>');
                    submitBtn.prop('disabled', true);

                    // Reload page after short delay to show updated links
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert('danger', response.message || 'Failed to create link. Please try again.');
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert('danger', errorMessage);
            },
            complete: function() {
                // Restore button state
                submitBtn.prop('disabled', false).html(originalBtnText);
            }
        });
    });

    // Handle workplan activity link form submission
    $('#workplan-link-form').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = $('#create-workplan-link-btn');
        const originalBtnText = submitBtn.html();

        // Show loading state
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating Link...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: {
                workplan_activity_id: $('#workplan_activity_id').val(),
                [csrfName]: csrfHash
            },
            success: function(response) {
                // Update CSRF token
                updateCSRFToken(response.csrf_hash);

                if (response.success) {
                    showAlert('success', response.message || 'Link created successfully!');

                    // Reset form
                    form[0].reset();
                    $('#workplan_activity_id').prop('disabled', true).html('<option value="">Select workplan first...</option>');
                    submitBtn.prop('disabled', true);

                    // Reload page after short delay to show updated links
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert('danger', response.message || 'Failed to create link. Please try again.');
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert('danger', errorMessage);
            },
            complete: function() {
                // Restore button state
                submitBtn.prop('disabled', false).html(originalBtnText);
            }
        });
    });

    // Handle remove link buttons (both duty instruction and workplan activity)
    $(document).on('click', '.btn-outline-danger', function(e) {
        e.preventDefault();

        const button = $(this);
        const form = button.closest('form');
        const listItem = button.closest('.list-group-item');

        // Show confirmation dialog
        if (!confirm('Are you sure you want to remove this link?')) {
            return;
        }

        const originalBtnHtml = button.html();

        // Show loading state
        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize() + '&' + csrfName + '=' + csrfHash,
            success: function(response) {
                // Update CSRF token
                updateCSRFToken(response.csrf_hash);

                if (response.success) {
                    showAlert('success', response.message || 'Link removed successfully!');

                    // Remove the list item with animation
                    listItem.fadeOut(300, function() {
                        $(this).remove();

                        // Check if this was the last item and show "no links" message
                        const listGroup = listItem.parent();
                        if (listGroup.children().length === 0) {
                            listGroup.replaceWith(`
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No links found. Use the form above to create links.
                                </div>
                            `);
                        }
                    });

                    // Update link summary counts
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showAlert('danger', response.message || 'Failed to remove link. Please try again.');
                }
            },
            error: function(xhr) {
                let errorMessage = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                showAlert('danger', errorMessage);
            },
            complete: function() {
                // Restore button state
                button.prop('disabled', false).html(originalBtnHtml);
            }
        });
    });

    console.log('Activities links page loaded - all forms now use AJAX with CSRF token refresh');
});
</script>
<?= $this->endSection() ?>
