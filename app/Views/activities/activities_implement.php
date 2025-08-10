<?php
// app/Views/activities/activities_implement.php
?>
<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="mb-3">
        <a href="<?= base_url('activities/' . $proposal['id']) ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Activity Details
        </a>
    </div>

    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Implement Activity: <?= esc($proposal['activity_title']) ?></h5>
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
                                            <p class="mb-1"><strong>Title:</strong> <?= esc($proposal['activity_title']) ?></p>
                                            <p class="mb-1"><strong>Type:</strong> <span class="badge bg-info"><?= ucfirst(esc($proposal['activity_type'])) ?></span></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Workplan:</strong> <?= esc($proposal['workplan_title']) ?></p>
                                            <p class="mb-1"><strong>Location:</strong> <?= esc($proposal['location']) ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Supervisor:</strong> <?= esc($proposal['supervisor_name']) ?></p>
                                            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-<?= $proposal['status'] === 'approved' ? 'success' : 'warning' ?>"><?= ucfirst(esc($proposal['status'])) ?></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Implementation Form -->
                    <form action="<?= base_url('activities/' . $proposal['id'] . '/save-implementation') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <?php if ($proposal['activity_type'] === 'training'): ?>
                            <!-- Training Implementation Form -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="trainers" class="form-label">Trainers <span class="text-danger">*</span></label>
                                        <textarea name="trainers" id="trainers" class="form-control" rows="3" required><?= old('trainers', $implementationData['trainers'] ?? '') ?></textarea>
                                        <small class="text-muted">Enter the names of trainers, one per line or separated by commas.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="topics" class="form-label">Topics Covered <span class="text-danger">*</span></label>
                                        <textarea name="topics" id="topics" class="form-control" rows="3" required><?= old('topics', $implementationData['topics'] ?? '') ?></textarea>
                                        <small class="text-muted">Enter the topics covered in the training, one per line or separated by commas.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Trainees</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="traineesTable">
                                        <thead>
                                            <tr>
                                                <th>Full Name</th>
                                                <th>Age</th>
                                                <th>Gender</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $trainees = $implementationData['trainees'] ?? [];
                                            if (empty($trainees)) {
                                                // Add at least one empty row
                                                $trainees = [['name' => '', 'age' => '', 'gender' => '', 'phone' => '', 'email' => '', 'remarks' => '']];
                                            }
                                            ?>
                                            <?php foreach ($trainees as $index => $trainee): ?>
                                            <tr>
                                                <td>
                                                    <input type="text" name="trainee_name[]" class="form-control" value="<?= esc($trainee['name'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <input type="number" name="trainee_age[]" class="form-control" value="<?= esc($trainee['age'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <select name="trainee_gender[]" class="form-select">
                                                        <option value="">Select</option>
                                                        <option value="Male" <?= isset($trainee['gender']) && $trainee['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                                                        <option value="Female" <?= isset($trainee['gender']) && $trainee['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="trainee_phone[]" class="form-control" value="<?= esc($trainee['phone'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <input type="email" name="trainee_email[]" class="form-control" value="<?= esc($trainee['email'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="trainee_remarks[]" class="form-control" value="<?= esc($trainee['remarks'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7">
                                                    <button type="button" class="btn btn-success btn-sm" id="addTraineeRow">
                                                        <i class="fas fa-plus"></i> Add Trainee
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gps_coordinates" class="form-label">GPS Coordinates <span class="text-danger">*</span></label>
                                        <input type="text" name="gps_coordinates" id="gps_coordinates" class="form-control" value="<?= old('gps_coordinates', $implementationData['gps_coordinates'] ?? '') ?>" required>
                                        <small class="text-muted">Enter the GPS coordinates in format: latitude, longitude (e.g., -9.4438, 147.1803)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="signing_sheet" class="form-label">Signing Sheet <span class="text-danger">*</span></label>
                                        <input type="file" name="signing_sheet" id="signing_sheet" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                                        <small class="text-muted">Upload the signing sheet document. Accepted formats: PDF, DOC, DOCX, JPG, PNG.</small>
                                        <?php if (!empty($implementationData['signing_sheet_filepath'])): ?>
                                            <div class="mt-2">
                                                <p class="mb-0">Current file: <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank"><?= basename($implementationData['signing_sheet_filepath']) ?></a></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="training_images" class="form-label">Training Images</label>
                                <div class="mb-3">
                                    <input type="file" name="training_images[]" id="training_images" class="form-control" multiple accept="image/*">
                                    <small class="text-muted">You can select multiple images. Accepted formats: JPG, PNG, GIF.</small>
                                </div>

                                <?php if (!empty($implementationData['training_images'])): ?>
                                <div class="mt-3">
                                    <h6>Existing Images</h6>
                                    <div class="row">
                                        <?php foreach ($implementationData['training_images'] as $index => $image): ?>
                                        <div class="col-md-3 mb-3">
                                            <div class="card">
                                                <img src="<?= base_url($image) ?>" class="card-img-top img-fluid" alt="Training Image">
                                                <div class="card-body p-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="keep_training_images[]" value="<?= $image ?>" id="keep_training_image_<?= $index ?>" checked>
                                                        <label class="form-check-label" for="keep_training_image_<?= $index ?>">Keep this image</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                        <?php elseif ($proposal['activity_type'] === 'inputs'): ?>
                            <!-- Inputs Implementation Form -->
                            <div class="mb-3">
                                <label class="form-label">Inputs</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="inputsTable">
                                        <thead>
                                            <tr>
                                                <th>Input Name</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $inputs = $implementationData['inputs'] ?? [];
                                            if (empty($inputs)) {
                                                // Add at least one empty row
                                                $inputs = [['name' => '', 'quantity' => '', 'unit' => '', 'remarks' => '']];
                                            }
                                            ?>
                                            <?php foreach ($inputs as $index => $input): ?>
                                            <tr>
                                                <td>
                                                    <input type="text" name="input_name[]" class="form-control" value="<?= esc($input['name'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <input type="number" name="input_quantity[]" class="form-control" value="<?= esc($input['quantity'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="input_unit[]" class="form-control" value="<?= esc($input['unit'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="input_remarks[]" class="form-control" value="<?= esc($input['remarks'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5">
                                                    <button type="button" class="btn btn-success btn-sm" id="addInputRow">
                                                        <i class="fas fa-plus"></i> Add Input
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gps_coordinates" class="form-label">GPS Coordinates <span class="text-danger">*</span></label>
                                        <input type="text" name="gps_coordinates" id="gps_coordinates" class="form-control" value="<?= old('gps_coordinates', $implementationData['gps_coordinates'] ?? '') ?>" required>
                                        <small class="text-muted">Enter the GPS coordinates in format: latitude, longitude (e.g., -9.4438, 147.1803)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="signing_sheet" class="form-label">Signing Sheet <span class="text-danger">*</span></label>
                                        <input type="file" name="signing_sheet" id="signing_sheet" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                                        <small class="text-muted">Upload the signing sheet document. Accepted formats: PDF, DOC, DOCX, JPG, PNG.</small>
                                        <?php if (!empty($implementationData['signing_sheet_filepath'])): ?>
                                            <div class="mt-2">
                                                <p class="mb-0">Current file: <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank"><?= basename($implementationData['signing_sheet_filepath']) ?></a></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="input_images" class="form-label">Input Images</label>
                                <div class="mb-3">
                                    <input type="file" name="input_images[]" id="input_images" class="form-control" multiple accept="image/*">
                                    <small class="text-muted">You can select multiple images. Accepted formats: JPG, PNG, GIF.</small>
                                </div>

                                <?php if (!empty($implementationData['input_images'])): ?>
                                <div class="mt-3">
                                    <h6>Existing Images</h6>
                                    <div class="row">
                                        <?php foreach ($implementationData['input_images'] as $index => $image): ?>
                                        <div class="col-md-3 mb-3">
                                            <div class="card">
                                                <img src="<?= base_url($image) ?>" class="card-img-top img-fluid" alt="Input Image">
                                                <div class="card-body p-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="keep_input_images[]" value="<?= $image ?>" id="keep_input_image_<?= $index ?>" checked>
                                                        <label class="form-check-label" for="keep_input_image_<?= $index ?>">Keep this image</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                        <?php elseif ($proposal['activity_type'] === 'infrastructure'): ?>
                            <!-- Infrastructure Implementation Form -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="infrastructure" class="form-label">Infrastructure Description <span class="text-danger">*</span></label>
                                        <textarea name="infrastructure" id="infrastructure" class="form-control" rows="3" required><?= old('infrastructure', $implementationData['infrastructure'] ?? '') ?></textarea>
                                        <small class="text-muted">Provide a detailed description of the infrastructure.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gps_coordinates" class="form-label">GPS Coordinates <span class="text-danger">*</span></label>
                                        <input type="text" name="gps_coordinates" id="gps_coordinates" class="form-control" value="<?= old('gps_coordinates', $implementationData['gps_coordinates'] ?? '') ?>" required>
                                        <small class="text-muted">Enter the GPS coordinates in format: latitude, longitude (e.g., -9.4438, 147.1803)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="signing_sheet" class="form-label">Signing Sheet <span class="text-danger">*</span></label>
                                <input type="file" name="signing_sheet" id="signing_sheet" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                                <small class="text-muted">Upload the signing sheet document. Accepted formats: PDF, DOC, DOCX, JPG, PNG.</small>
                                <?php if (!empty($implementationData['signing_sheet_filepath'])): ?>
                                    <div class="mt-2">
                                        <p class="mb-0">Current file: <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank"><?= basename($implementationData['signing_sheet_filepath']) ?></a></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="infrastructure_images" class="form-label">Infrastructure Images</label>
                                <div class="mb-3">
                                    <input type="file" name="infrastructure_images[]" id="infrastructure_images" class="form-control" multiple accept="image/*">
                                    <small class="text-muted">You can select multiple images. Accepted formats: JPG, PNG, GIF.</small>
                                </div>

                                <?php if (!empty($implementationData['infrastructure_images'])): ?>
                                <div class="mt-3">
                                    <h6>Existing Images</h6>
                                    <div class="row">
                                        <?php foreach ($implementationData['infrastructure_images'] as $index => $image): ?>
                                        <div class="col-md-3 mb-3">
                                            <div class="card">
                                                <img src="<?= base_url($image) ?>" class="card-img-top img-fluid" alt="Infrastructure Image">
                                                <div class="card-body p-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="keep_infrastructure_images[]" value="<?= $image ?>" id="keep_infrastructure_image_<?= $index ?>" checked>
                                                        <label class="form-check-label" for="keep_infrastructure_image_<?= $index ?>">Keep this image</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                        <?php elseif ($proposal['activity_type'] === 'output'): ?>
                            <!-- Output Implementation Form -->
                            <div class="mb-3">
                                <label class="form-label">Outputs</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="outputsTable">
                                        <thead>
                                            <tr>
                                                <th>Output Name</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $outputs = $implementationData['outputs'] ?? [];
                                            if (empty($outputs)) {
                                                // Add at least one empty row
                                                $outputs = [['name' => '', 'quantity' => '', 'unit' => '', 'remarks' => '']];
                                            }
                                            ?>
                                            <?php foreach ($outputs as $index => $output): ?>
                                            <tr>
                                                <td>
                                                    <input type="text" name="output_name[]" class="form-control" value="<?= esc($output['name'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <input type="number" name="output_quantity[]" class="form-control" value="<?= esc($output['quantity'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="output_unit[]" class="form-control" value="<?= esc($output['unit'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="output_remarks[]" class="form-control" value="<?= esc($output['remarks'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5">
                                                    <button type="button" class="btn btn-success btn-sm" id="addOutputRow">
                                                        <i class="fas fa-plus"></i> Add Output
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="delivery_date" class="form-label">Delivery Date</label>
                                        <input type="date" name="delivery_date" id="delivery_date" class="form-control" value="<?= old('delivery_date', $implementationData['delivery_date'] ?? '') ?>">
                                        <small class="text-muted">Date when outputs were delivered.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="delivery_location" class="form-label">Delivery Location</label>
                                        <input type="text" name="delivery_location" id="delivery_location" class="form-control" value="<?= old('delivery_location', $implementationData['delivery_location'] ?? '') ?>">
                                        <small class="text-muted">Location where outputs were delivered.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="total_value" class="form-label">Total Value (K)</label>
                                        <input type="number" step="0.01" name="total_value" id="total_value" class="form-control" value="<?= old('total_value', $implementationData['total_value'] ?? '') ?>">
                                        <small class="text-muted">Total value of outputs delivered.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gps_coordinates" class="form-label">GPS Coordinates <span class="text-danger">*</span></label>
                                        <input type="text" name="gps_coordinates" id="gps_coordinates" class="form-control" value="<?= old('gps_coordinates', $implementationData['gps_coordinates'] ?? '') ?>" required>
                                        <small class="text-muted">Enter the GPS coordinates in format: latitude, longitude (e.g., -9.4438, 147.1803)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Beneficiaries</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="beneficiariesTable">
                                        <thead>
                                            <tr>
                                                <th>Full Name</th>
                                                <th>Age</th>
                                                <th>Gender</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $beneficiaries = $implementationData['beneficiaries'] ?? [];
                                            if (empty($beneficiaries)) {
                                                // Add at least one empty row
                                                $beneficiaries = [['name' => '', 'age' => '', 'gender' => '', 'phone' => '', 'email' => '', 'remarks' => '']];
                                            }
                                            ?>
                                            <?php foreach ($beneficiaries as $index => $beneficiary): ?>
                                            <tr>
                                                <td>
                                                    <input type="text" name="beneficiary_name[]" class="form-control" value="<?= esc($beneficiary['name'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <input type="number" name="beneficiary_age[]" class="form-control" value="<?= esc($beneficiary['age'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <select name="beneficiary_gender[]" class="form-select">
                                                        <option value="">Select</option>
                                                        <option value="Male" <?= isset($beneficiary['gender']) && $beneficiary['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                                                        <option value="Female" <?= isset($beneficiary['gender']) && $beneficiary['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="beneficiary_phone[]" class="form-control" value="<?= esc($beneficiary['phone'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <input type="email" name="beneficiary_email[]" class="form-control" value="<?= esc($beneficiary['email'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="beneficiary_remarks[]" class="form-control" value="<?= esc($beneficiary['remarks'] ?? '') ?>">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7">
                                                    <button type="button" class="btn btn-success btn-sm" id="addBeneficiaryRow">
                                                        <i class="fas fa-plus"></i> Add Beneficiary
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="signing_sheet" class="form-label">Signing Sheet <span class="text-danger">*</span></label>
                                <input type="file" name="signing_sheet" id="signing_sheet" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png">
                                <small class="text-muted">Upload the signing sheet document. Accepted formats: PDF, DOC, DOCX, JPG, PNG.</small>
                                <?php if (!empty($implementationData['signing_sheet_filepath'])): ?>
                                    <div class="mt-2">
                                        <p class="mb-0">Current file: <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank"><?= basename($implementationData['signing_sheet_filepath']) ?></a></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="output_images" class="form-label">Output Images</label>
                                <div class="mb-3">
                                    <input type="file" name="output_images[]" id="output_images" class="form-control" multiple accept="image/*">
                                    <small class="text-muted">You can select multiple images. Accepted formats: JPG, PNG, GIF.</small>
                                </div>

                                <?php if (!empty($implementationData['output_images'])): ?>
                                <div class="mt-3">
                                    <h6>Existing Images</h6>
                                    <div class="row">
                                        <?php foreach ($implementationData['output_images'] as $index => $image): ?>
                                        <div class="col-md-3 mb-3">
                                            <div class="card">
                                                <img src="<?= base_url($image) ?>" class="card-img-top img-fluid" alt="Output Image">
                                                <div class="card-body p-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="keep_output_images[]" value="<?= $image ?>" id="keep_output_image_<?= $index ?>" checked>
                                                        <label class="form-check-label" for="keep_output_image_<?= $index ?>">Keep this image</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea name="remarks" id="remarks" class="form-control" rows="3"><?= old('remarks', $implementationData['remarks'] ?? '') ?></textarea>
                                <small class="text-muted">Any additional remarks or notes about the output delivery.</small>
                            </div>
                        <?php endif; ?>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Implementation
                            </button>
                            <a href="<?= base_url('activities/' . $proposal['id']) ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* Add any custom styles here */
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {

        // Add trainee row
        $('#addTraineeRow').click(function() {
            var newRow = `
                <tr>
                    <td>
                        <input type="text" name="trainee_name[]" class="form-control">
                    </td>
                    <td>
                        <input type="number" name="trainee_age[]" class="form-control">
                    </td>
                    <td>
                        <select name="trainee_gender[]" class="form-select">
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="trainee_phone[]" class="form-control">
                    </td>
                    <td>
                        <input type="email" name="trainee_email[]" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="trainee_remarks[]" class="form-control">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
            $('#traineesTable tbody').append(newRow);
        });

        // Add input row
        $('#addInputRow').click(function() {
            var newRow = `
                <tr>
                    <td>
                        <input type="text" name="input_name[]" class="form-control">
                    </td>
                    <td>
                        <input type="number" name="input_quantity[]" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="input_unit[]" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="input_remarks[]" class="form-control">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
            $('#inputsTable tbody').append(newRow);
        });

        // Add output row
        $('#addOutputRow').click(function() {
            var newRow = `
                <tr>
                    <td>
                        <input type="text" name="output_name[]" class="form-control">
                    </td>
                    <td>
                        <input type="number" name="output_quantity[]" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="output_unit[]" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="output_remarks[]" class="form-control">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
            $('#outputsTable tbody').append(newRow);
        });

        // Add beneficiary row
        $('#addBeneficiaryRow').click(function() {
            var newRow = `
                <tr>
                    <td>
                        <input type="text" name="beneficiary_name[]" class="form-control">
                    </td>
                    <td>
                        <input type="number" name="beneficiary_age[]" class="form-control">
                    </td>
                    <td>
                        <select name="beneficiary_gender[]" class="form-select">
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="beneficiary_phone[]" class="form-control">
                    </td>
                    <td>
                        <input type="email" name="beneficiary_email[]" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="beneficiary_remarks[]" class="form-control">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `;
            $('#beneficiariesTable tbody').append(newRow);
        });

        // Remove row
        $(document).on('click', '.remove-row', function() {
            var tableId = $(this).closest('table').attr('id');
            var rowCount = $(`#${tableId} tbody tr`).length;

            // Don't remove the last row
            if (rowCount > 1) {
                $(this).closest('tr').remove();
            } else {
                alert('You cannot remove the last row.');
            }
        });


    });
</script>
<?= $this->endSection() ?>
