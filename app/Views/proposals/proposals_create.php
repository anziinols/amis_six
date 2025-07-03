<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Create New Proposal</h5>
                    <a href="<?= base_url('proposals') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Proposals
                    </a>
                </div>
                <div class="card-body">
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Activity Linking Information -->
                    <?php if (!empty($unlinkedActivities)): ?>
                        <div class="alert alert-warning">
                            <h6 class="alert-heading">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Activities Requiring Plan Links
                            </h6>
                            <p class="mb-2">
                                The following activities cannot be used for proposals because they are not linked to any plan.
                                Please link these activities to at least one plan (NASP, MTDP, Corporate, or Others) before creating proposals:
                            </p>
                            <ul class="mb-2">
                                <?php foreach ($unlinkedActivities as $activity): ?>
                                    <li>
                                        <strong><?= esc($activity['title']) ?></strong>
                                        (<?= ucfirst($activity['activity_type']) ?>) -
                                        <?= esc($activity['workplan_title']) ?>
                                        <a href="<?= base_url('workplans/' . $activity['workplan_id'] . '/activities/' . $activity['id'] . '/plans') ?>"
                                           class="btn btn-sm btn-outline-primary ms-2" target="_blank">
                                            <i class="fas fa-link me-1"></i>Link Plans
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Activities must be linked to plans to ensure proper alignment with organizational objectives.
                            </small>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($activities)): ?>
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i>
                                No Activities Available for Proposals
                            </h6>
                            <p class="mb-0">
                                You currently have no activities that are linked to plans and available for creating proposals.
                                Please ensure your activities are linked to at least one plan (NASP, MTDP, Corporate, or Others)
                                before creating proposals.
                            </p>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('proposals/create') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="activity_id" class="form-label">Activity <span class="text-danger">*</span></label>
                                <select name="activity_id" id="activity_id" class="form-select" required>
                                    <option value="">Select Activity</option>
                                    <?php foreach ($activities as $activity): ?>
                                        <option value="<?= $activity['id'] ?>" <?= old('activity_id') == $activity['id'] ? 'selected' : '' ?>>
                                            <?= esc($activity['title']) ?> (<?= ucfirst($activity['activity_type']) ?>) -
                                            <?= esc($activity['workplan_title']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="action_officer_id" class="form-label">Action Officer</label>
                                <select name="action_officer_id" id="action_officer_id" class="form-select">
                                    <option value="">Select Action Officer (Optional)</option>
                                    <?php foreach ($actionOfficers as $officer): ?>
                                        <option value="<?= $officer['id'] ?>" <?= old('action_officer_id') == $officer['id'] ? 'selected' : '' ?>>
                                            <?= esc($officer['full_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="province_id" class="form-label">Province <span class="text-danger">*</span></label>
                                <select name="province_id" id="province_id" class="form-select" required>
                                    <option value="">Select Province</option>
                                    <?php foreach ($provinces as $province): ?>
                                        <option value="<?= $province['id'] ?>" <?= old('province_id') == $province['id'] ? 'selected' : '' ?>>
                                            <?= esc($province['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="district_id" class="form-label">District <span class="text-danger">*</span></label>
                                <select name="district_id" id="district_id" class="form-select" required>
                                    <option value="">Select Province First</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="location" class="form-label">Specific Location</label>
                                <input type="text" name="location" id="location" class="form-control" value="<?= old('location') ?>" placeholder="Enter specific location details">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="date_start" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="date_start" id="date_start" class="form-control" value="<?= old('date_start') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="date_end" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" name="date_end" id="date_end" class="form-control" value="<?= old('date_end') ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="total_cost" class="form-label">Total Cost</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="total_cost" id="total_cost" class="form-control" value="<?= old('total_cost') ?>" step="0.01" min="0" placeholder="Enter total cost">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Create Proposal
                                </button>
                                <a href="<?= base_url('proposals') ?>" class="btn btn-secondary ms-2">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </a>
                            </div>
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
        // Load districts when province changes
        $('#province_id').change(function() {
            const provinceId = $(this).val();
            if (provinceId) {
                $.ajax({
                    url: '<?= base_url('proposals/get-districts') ?>',
                    type: 'GET',
                    data: { province_id: provinceId },
                    dataType: 'json',
                    success: function(response) {
                        let options = '<option value="">Select District</option>';
                        if (response.districts && response.districts.length > 0) {
                            $.each(response.districts, function(index, district) {
                                options += `<option value="${district.id}">${district.name}</option>`;
                            });
                        }
                        $('#district_id').html(options);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading districts:', error);
                        toastr.error('Failed to load districts. Please try again.');
                    }
                });
            } else {
                $('#district_id').html('<option value="">Select Province First</option>');
            }
        });

        // Set up date validation and constraints
        const dateStart = $('#date_start');
        const dateEnd = $('#date_end');

        // Set min date for start date (can't select dates in the past)
        const today = new Date();
        const todayFormatted = today.toISOString().split('T')[0];
        dateStart.attr('min', todayFormatted);

        // Update end date min value when start date changes
        dateStart.on('change', function() {
            const startVal = $(this).val();

            if (startVal) {
                // Set the minimum end date to be the same as start date
                dateEnd.attr('min', startVal);

                // If end date is already set and now invalid, clear it
                const endVal = dateEnd.val();
                if (endVal && endVal < startVal) {
                    dateEnd.val('');
                    toastr.warning('End date has been cleared as it was before the new start date');
                }
            }
        });

        // Initialize on page load if dates are pre-filled
        if (dateStart.val()) {
            dateStart.trigger('change');
        }

        // Trigger change events if values are pre-selected (for edit form)
        if ($('#province_id').val()) {
            $('#province_id').trigger('change');
        }
    });
</script>
<?= $this->endSection() ?>
