<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800"><?= esc($title) ?></h1>
                    <p class="mb-0 text-muted">Output activity details and deliverables</p>
                </div>
                <div>
                    <a href="<?= base_url('output-activities') ?>" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                    <a href="<?= base_url('output-activities/' . $outputActivity['id'] . '/edit') ?>" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Basic Information -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>Basic Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Workplan:</strong><br>
                                    <span class="text-muted"><?= esc($outputActivity['workplan_title'] ?? 'N/A') ?></span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Related Activity:</strong><br>
                                    <span class="text-muted"><?= esc($outputActivity['activity_title'] ?? 'N/A') ?></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Delivery Date:</strong><br>
                                    <span class="text-muted">
                                        <?php if (!empty($outputActivity['delivery_date'])): ?>
                                            <?= date('F d, Y', strtotime($outputActivity['delivery_date'])) ?>
                                        <?php else: ?>
                                            Not set
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Delivery Location:</strong><br>
                                    <span class="text-muted"><?= esc($outputActivity['delivery_location'] ?? 'N/A') ?></span>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Total Value:</strong><br>
                                    <span class="text-muted">
                                        <?php if (!empty($outputActivity['total_value'])): ?>
                                            <strong>K <?= number_format($outputActivity['total_value'], 2) ?></strong>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <strong>GPS Coordinates:</strong><br>
                                    <span class="text-muted">
                                        <?php if (!empty($outputActivity['gps_coordinates'])): ?>
                                            <?= esc($outputActivity['gps_coordinates']) ?>
                                            <a href="https://www.google.com/maps?q=<?= urlencode($outputActivity['gps_coordinates']) ?>" 
                                               target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                                <i class="fas fa-map-marker-alt"></i> View on Map
                                            </a>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                            <?php if (!empty($outputActivity['remarks'])): ?>
                                <hr>
                                <div class="row">
                                    <div class="col-12">
                                        <strong>Remarks:</strong><br>
                                        <span class="text-muted"><?= nl2br(esc($outputActivity['remarks'])) ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Output Items -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-box me-2"></i>Output Items
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php 
                            $outputs = is_string($outputActivity['outputs']) ? json_decode($outputActivity['outputs'], true) : $outputActivity['outputs'];
                            if (!empty($outputs) && is_array($outputs)): 
                            ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Item</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Specifications</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($outputs as $index => $output): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><strong><?= esc($output['item'] ?? '') ?></strong></td>
                                                    <td><?= esc($output['description'] ?? '') ?></td>
                                                    <td><?= esc($output['quantity'] ?? '') ?></td>
                                                    <td><?= esc($output['unit'] ?? '') ?></td>
                                                    <td><?= esc($output['specifications'] ?? '') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No output items specified.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Beneficiaries -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-users me-2"></i>Beneficiaries
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php 
                            $beneficiaries = is_string($outputActivity['beneficiaries']) ? json_decode($outputActivity['beneficiaries'], true) : $outputActivity['beneficiaries'];
                            if (!empty($beneficiaries) && is_array($beneficiaries)): 
                            ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Organization/Group</th>
                                                <th>Contact Person</th>
                                                <th>Phone</th>
                                                <th>Members</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($beneficiaries as $index => $beneficiary): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><strong><?= esc($beneficiary['name'] ?? '') ?></strong></td>
                                                    <td><?= esc($beneficiary['contact'] ?? '') ?></td>
                                                    <td><?= esc($beneficiary['phone'] ?? '') ?></td>
                                                    <td><?= esc($beneficiary['members'] ?? '0') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No beneficiaries specified.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Status Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info me-2"></i>Status Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Proposal Status:</strong><br>
                                <?php
                                $status = $outputActivity['proposal_status'] ?? 'draft';
                                $statusClass = 'badge-secondary';
                                switch ($status) {
                                    case 'pending':
                                        $statusClass = 'badge-warning';
                                        break;
                                    case 'approved':
                                        $statusClass = 'badge-success';
                                        break;
                                    case 'rejected':
                                        $statusClass = 'badge-danger';
                                        break;
                                }
                                ?>
                                <span class="badge <?= $statusClass ?>"><?= ucfirst(esc($status)) ?></span>
                            </div>
                            <div class="mb-3">
                                <strong>Created:</strong><br>
                                <small class="text-muted">
                                    <?= date('M d, Y g:i A', strtotime($outputActivity['created_at'])) ?>
                                </small>
                            </div>
                            <div class="mb-3">
                                <strong>Last Updated:</strong><br>
                                <small class="text-muted">
                                    <?= date('M d, Y g:i A', strtotime($outputActivity['updated_at'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Files Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-file me-2"></i>Files & Documents
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Output Images -->
                            <?php 
                            $outputImages = is_string($outputActivity['output_images']) ? json_decode($outputActivity['output_images'], true) : $outputActivity['output_images'];
                            if (!empty($outputImages) && is_array($outputImages)): 
                            ?>
                                <div class="mb-3">
                                    <strong>Output Images:</strong><br>
                                    <div class="row">
                                        <?php foreach ($outputImages as $image): ?>
                                            <div class="col-6 mb-2">
                                                <a href="<?= base_url($image) ?>" target="_blank">
                                                    <img src="<?= base_url($image) ?>" class="img-thumbnail" style="height: 80px; object-fit: cover;">
                                                </a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Output Files -->
                            <?php 
                            $outputFiles = is_string($outputActivity['output_files']) ? json_decode($outputActivity['output_files'], true) : $outputActivity['output_files'];
                            if (!empty($outputFiles) && is_array($outputFiles)): 
                            ?>
                                <div class="mb-3">
                                    <strong>Documents:</strong><br>
                                    <?php foreach ($outputFiles as $file): ?>
                                        <a href="<?= base_url($file) ?>" target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                                            <i class="fas fa-file"></i> <?= basename($file) ?>
                                        </a><br>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Signing Sheet -->
                            <?php if (!empty($outputActivity['signing_sheet_filepath'])): ?>
                                <div class="mb-3">
                                    <strong>Signing Sheet:</strong><br>
                                    <a href="<?= base_url($outputActivity['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-file-signature"></i> View Signing Sheet
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if (empty($outputImages) && empty($outputFiles) && empty($outputActivity['signing_sheet_filepath'])): ?>
                                <p class="text-muted">No files uploaded.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Display flash messages
<?php if (session()->getFlashdata('success')): ?>
    toastr.success('<?= session()->getFlashdata('success') ?>');
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    toastr.error('<?= session()->getFlashdata('error') ?>');
<?php endif; ?>
</script>

<?= $this->endSection() ?>
