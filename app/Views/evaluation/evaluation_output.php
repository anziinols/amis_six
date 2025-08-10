<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<style>
/* Print-specific styles */
@media print {
    .no-print, .btn, .breadcrumb, .navbar, .sidebar, .footer {
        display: none !important;
    }

    .container-fluid {
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
        page-break-inside: avoid;
        margin-bottom: 20px !important;
    }

    .card-header {
        background-color: #f8f9fa !important;
        color: #000 !important;
        border-bottom: 1px solid #ddd !important;
    }

    .table {
        font-size: 12px !important;
    }

    .badge {
        border: 1px solid #000 !important;
        color: #000 !important;
        background-color: transparent !important;
    }

    .text-primary, .text-success, .text-warning, .text-info {
        color: #000 !important;
    }

    .bg-light, .bg-primary, .bg-success, .bg-warning, .bg-info {
        background-color: #f8f9fa !important;
        color: #000 !important;
    }

    h1, h2, h3, h4, h5, h6 {
        color: #000 !important;
    }

    .page-break {
        page-break-before: always;
    }
}

/* Professional layout styles */
.evaluation-header {
    text-align: center;
    border-bottom: 3px solid #198754;
    padding-bottom: 20px;
    margin-bottom: 30px;
}

.info-section {
    margin-bottom: 25px;
}

.info-table {
    width: 100%;
    border-collapse: collapse;
}

.info-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    padding: 12px;
    border: 1px solid #dee2e6;
    width: 30%;
}

.info-table td {
    padding: 12px;
    border: 1px solid #dee2e6;
}

.performance-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.metric-card {
    text-align: center;
    padding: 20px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background-color: #fff;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #198754;
    border-bottom: 2px solid #198754;
    padding-bottom: 8px;
    margin-bottom: 20px;
}

.proposal-section {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 30px;
    overflow: hidden;
}

.proposal-header {
    background-color: #f8f9fa;
    padding: 15px 20px;
    border-bottom: 1px solid #dee2e6;
}

.implementation-item {
    border-left: 4px solid #198754;
    margin: 20px 0;
    padding: 20px;
    background-color: #f8f9fa;
}
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><?= esc($title) ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('evaluation') ?>">Evaluation</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= esc($activity['activity_code'] ?? 'Output Activity') ?></li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?= base_url('evaluation') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Evaluation
            </a>
            <a href="<?= base_url('evaluation/' . $activity['id'] . '/rate') ?>" class="btn btn-success">
                <i class="fas fa-star"></i> Evaluate and Rate
            </a>
        </div>
    </div>

    <!-- Professional Header for Print -->
    <div class="evaluation-header">
        <h1 style="color: #198754; margin-bottom: 10px;">OUTPUT ACTIVITY EVALUATION REPORT</h1>
        <h2 style="color: #6c757d; font-size: 1.5rem; margin-bottom: 5px;"><?= esc($activity['title']) ?></h2>
        <p style="color: #6c757d; margin-bottom: 0;">Activity Code: <strong><?= esc($activity['activity_code'] ?? 'N/A') ?></strong></p>
        <p style="color: #6c757d; margin-bottom: 0;">Generated on: <strong><?= date('F d, Y') ?></strong></p>
    </div>

    <!-- Activity Overview Card -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-chart-line"></i> Output Activity Overview</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Basic Information -->
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 40%">Activity Code</th>
                            <td><span class="badge bg-info fs-6"><?= esc($activity['activity_code'] ?? 'N/A') ?></span></td>
                        </tr>
                        <tr>
                            <th>Output Activity Title</th>
                            <td><strong><?= esc($activity['title']) ?></strong></td>
                        </tr>
                        <tr>
                            <th>Activity Type</th>
                            <td><span class="badge bg-info fs-6">Output</span></td>
                        </tr>
                        <tr>
                            <th>Workplan</th>
                            <td>
                                <strong><?= esc($workplan['title'] ?? 'N/A') ?></strong>
                                <?php if (!empty($workplan['start_date'])): ?>
                                    <br><small class="text-muted">
                                        <?= date('Y', strtotime($workplan['start_date'])) ?>
                                        <?php if (!empty($workplan['end_date']) && date('Y', strtotime($workplan['start_date'])) != date('Y', strtotime($workplan['end_date']))): ?>
                                            - <?= date('Y', strtotime($workplan['end_date'])) ?>
                                        <?php endif; ?>
                                    </small>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Branch</th>
                            <td><?= esc($branch['name'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <th>Supervisor</th>
                            <td><?= esc($activity['supervisor_name'] ?? 'Not assigned') ?></td>
                        </tr>
                    </table>
                </div>

                <!-- Output Summary & Stats -->
                <div class="col-md-6">
                    <h6><i class="fas fa-chart-bar"></i> Output Targets</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-info">Quarter 1</h6>
                                    <p class="card-text fs-5 fw-bold"><?= $activity['q_one_target'] ? number_format($activity['q_one_target'], 0) : 'N/A' ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-info">Quarter 2</h6>
                                    <p class="card-text fs-5 fw-bold"><?= $activity['q_two_target'] ? number_format($activity['q_two_target'], 0) : 'N/A' ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-info">Quarter 3</h6>
                                    <p class="card-text fs-5 fw-bold"><?= $activity['q_three_target'] ? number_format($activity['q_three_target'], 0) : 'N/A' ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-info">Quarter 4</h6>
                                    <p class="card-text fs-5 fw-bold"><?= $activity['q_four_target'] ? number_format($activity['q_four_target'], 0) : 'N/A' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Summary Stats -->
                    <div class="mt-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6><i class="fas fa-chart-pie"></i> Output Summary</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Output Budget:</strong> <?= $activity['total_budget'] ? number_format($activity['total_budget'], 2) : 'N/A' ?></p>
                                        <p><strong>Total Proposals:</strong> <span class="badge bg-info"><?= $proposalSummary['total'] ?></span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Approved:</strong> <span class="badge bg-success"><?= $proposalSummary['approved'] ?></span></p>
                                        <p><strong>For Evaluation:</strong> <span class="badge bg-info"><?= count($proposals) ?></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <?php if (!empty($activity['description'])): ?>
            <div class="mt-4">
                <h6><i class="fas fa-file-text"></i> Output Activity Description</h6>
                <div class="p-3 bg-light rounded">
                    <?= nl2br(esc($activity['description'])) ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Output Proposals and Implementation Details -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-chart-line"></i> Output Proposals - Implementation Details</h5>
            <small>Approved or rated output proposals with full implementation details</small>
        </div>
        <div class="card-body">
            <?php if (empty($proposals)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> No approved or rated output proposals found for this activity.
                </div>
            <?php else: ?>
                <?php foreach ($proposals as $index => $proposal): ?>
                    <div class="proposal-section mb-5">
                        <!-- Proposal Header -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-info">
                                <i class="fas fa-chart-line"></i> Output Proposal #<?= $index + 1 ?>
                                <span class="badge bg-<?= $proposal['status'] === 'approved' ? 'success' : ($proposal['status'] === 'pending' ? 'warning' : 'secondary') ?>">
                                    <?= ucfirst(esc($proposal['status'] ?? 'N/A')) ?>
                                </span>
                            </h5>
                            <small class="text-muted">Created: <?= date('M d, Y', strtotime($proposal['created_at'])) ?></small>
                        </div>

                        <!-- Proposal Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Output Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tr>
                                                <th style="width: 40%">Output Period</th>
                                                <td>
                                                    <?= $proposal['date_start'] ? date('M d, Y', strtotime($proposal['date_start'])) : 'N/A' ?>
                                                    <?php if ($proposal['date_end']): ?>
                                                        - <?= date('M d, Y', strtotime($proposal['date_end'])) ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Output Location</th>
                                                <td><?= esc($proposal['location'] ?? 'N/A') ?></td>
                                            </tr>
                                            <tr>
                                                <th>Province</th>
                                                <td><?= esc($proposal['province_name'] ?? 'N/A') ?></td>
                                            </tr>
                                            <tr>
                                                <th>District</th>
                                                <td><?= esc($proposal['district_name'] ?? 'N/A') ?></td>
                                            </tr>
                                            <tr>
                                                <th>Total Cost</th>
                                                <td><strong><?= $proposal['total_cost'] ? number_format($proposal['total_cost'], 2) : 'N/A' ?></strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="fas fa-users"></i> Personnel & Rating</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm">
                                            <tr>
                                                <th style="width: 40%">Supervisor</th>
                                                <td><?= esc($proposal['supervisor_name'] ?? 'Not assigned') ?></td>
                                            </tr>
                                            <tr>
                                                <th>Action Officer</th>
                                                <td><?= esc($proposal['action_officer_name'] ?? 'Not assigned') ?></td>
                                            </tr>
                                            <?php if (!empty($proposal['rating_score'])): ?>
                                            <tr>
                                                <th>Rating</th>
                                                <td>
                                                    <span class="badge bg-warning fs-6"><?= esc($proposal['rating_score']) ?>/10</span>
                                                    <?php if ($proposal['rated_at']): ?>
                                                        <br><small class="text-muted">Rated: <?= date('M d, Y', strtotime($proposal['rated_at'])) ?></small>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if (!empty($proposal['rate_remarks'])): ?>
                                            <tr>
                                                <th>Rating Remarks</th>
                                                <td><small><?= esc($proposal['rate_remarks']) ?></small></td>
                                            </tr>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Output Implementation Activities -->
                        <?php if (!empty($proposal['implementations'])): ?>
                            <div class="implementations-section">
                                <h6 class="text-info mb-3">
                                    <i class="fas fa-chart-line"></i> Output Implementation Details
                                    <span class="badge bg-info"><?= count($proposal['implementations']) ?></span>
                                </h6>

                                <?php foreach ($proposal['implementations'] as $impl_index => $implementation): ?>
                                    <?php if ($implementation['type'] === 'Output'): ?>
                                        <div class="card mb-4 border-info">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">
                                                    <span class="badge bg-info">Output Production</span>
                                                    Implementation #<?= $impl_index + 1 ?>
                                                    <small class="text-muted float-end">
                                                        <?= date('M d, Y', strtotime($implementation['created_at'])) ?>
                                                    </small>
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <!-- Output Items Details -->
                                                        <?php if (!empty($implementation['outputs'])): ?>
                                                            <div class="mb-3">
                                                                <h6><i class="fas fa-chart-bar"></i> Output Items Produced</h6>
                                                                <div class="bg-light p-3 rounded">
                                                                    <?php
                                                                    $outputs = is_string($implementation['outputs']) ? json_decode($implementation['outputs'], true) : $implementation['outputs'];
                                                                    if (is_array($outputs) && !empty($outputs)) {
                                                                        echo '<div class="table-responsive">';
                                                                        echo '<table class="table table-sm table-bordered">';
                                                                        echo '<thead class="table-info"><tr><th>#</th><th>Output Description</th><th>Quantity</th><th>Unit</th><th>Unit Value</th><th>Total Value</th><th>Quality</th></tr></thead>';
                                                                        echo '<tbody>';
                                                                        $totalValue = 0;
                                                                        foreach ($outputs as $index => $output) {
                                                                            $quantity = floatval($output['quantity'] ?? 0);
                                                                            $unitValue = floatval($output['unit_value'] ?? $output['value'] ?? 0);
                                                                            $itemTotal = $quantity * $unitValue;
                                                                            $totalValue += $itemTotal;

                                                                            echo '<tr>';
                                                                            echo '<td>' . ($index + 1) . '</td>';
                                                                            echo '<td><strong>' . esc($output['description'] ?? $output['name'] ?? 'N/A') . '</strong></td>';
                                                                            echo '<td>' . number_format($quantity, 2) . '</td>';
                                                                            echo '<td>' . esc($output['unit'] ?? 'N/A') . '</td>';
                                                                            echo '<td>' . number_format($unitValue, 2) . '</td>';
                                                                            echo '<td><strong>' . number_format($itemTotal, 2) . '</strong></td>';
                                                                            echo '<td>' . esc($output['quality'] ?? $output['grade'] ?? 'N/A') . '</td>';
                                                                            echo '</tr>';
                                                                        }
                                                                        echo '<tr class="table-warning">';
                                                                        echo '<td colspan="5"><strong>Total Output Value</strong></td>';
                                                                        echo '<td><strong>' . number_format($totalValue, 2) . '</strong></td>';
                                                                        echo '<td></td>';
                                                                        echo '</tr>';
                                                                        echo '</tbody></table>';
                                                                        echo '</div>';
                                                                        echo '<p class="mt-2"><strong>Total Output Items: </strong><span class="badge bg-info">' . count($outputs) . '</span></p>';
                                                                    } else {
                                                                        echo '<p>' . nl2br(esc($implementation['outputs'])) . '</p>';
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                        <!-- Beneficiaries List -->
                                                        <?php if (!empty($implementation['beneficiaries'])): ?>
                                                            <div class="mb-3">
                                                                <h6><i class="fas fa-users"></i> Output Beneficiaries</h6>
                                                                <div class="bg-light p-3 rounded">
                                                                    <?php
                                                                    $beneficiaries = is_string($implementation['beneficiaries']) ? json_decode($implementation['beneficiaries'], true) : $implementation['beneficiaries'];
                                                                    if (is_array($beneficiaries) && !empty($beneficiaries)) {
                                                                        echo '<div class="table-responsive">';
                                                                        echo '<table class="table table-sm table-bordered">';
                                                                        echo '<thead class="table-info"><tr><th>#</th><th>Beneficiary Name</th><th>Organization</th><th>Contact</th><th>Benefit Received</th></tr></thead>';
                                                                        echo '<tbody>';
                                                                        foreach ($beneficiaries as $index => $beneficiary) {
                                                                            echo '<tr>';
                                                                            echo '<td>' . ($index + 1) . '</td>';
                                                                            if (is_array($beneficiary)) {
                                                                                echo '<td><strong>' . esc($beneficiary['name'] ?? $beneficiary['full_name'] ?? 'N/A') . '</strong></td>';
                                                                                echo '<td>' . esc($beneficiary['organization'] ?? 'N/A') . '</td>';
                                                                                echo '<td>' . esc($beneficiary['contact'] ?? $beneficiary['phone'] ?? 'N/A') . '</td>';
                                                                                echo '<td>' . esc($beneficiary['benefit'] ?? $beneficiary['description'] ?? 'N/A') . '</td>';
                                                                            } else {
                                                                                echo '<td colspan="4">' . esc($beneficiary) . '</td>';
                                                                            }
                                                                            echo '</tr>';
                                                                        }
                                                                        echo '</tbody></table>';
                                                                        echo '</div>';
                                                                        echo '<p class="mt-2"><strong>Total Beneficiaries: </strong><span class="badge bg-info">' . count($beneficiaries) . '</span></p>';
                                                                    } else {
                                                                        echo '<p>' . nl2br(esc($implementation['beneficiaries'])) . '</p>';
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <!-- Output Information -->
                                                        <div class="card bg-light">
                                                            <div class="card-body">
                                                                <h6><i class="fas fa-info-circle"></i> Output Info</h6>

                                                                <?php if (!empty($implementation['delivery_date'])): ?>
                                                                    <p><strong><i class="fas fa-calendar"></i> Delivery Date:</strong><br>
                                                                    <span class="badge bg-info"><?= date('M d, Y', strtotime($implementation['delivery_date'])) ?></span></p>
                                                                <?php endif; ?>

                                                                <?php if (!empty($implementation['delivery_location'])): ?>
                                                                    <p><strong><i class="fas fa-map-marker-alt"></i> Delivery Location:</strong><br>
                                                                    <?= esc($implementation['delivery_location']) ?></p>
                                                                <?php endif; ?>

                                                                <?php if (!empty($implementation['total_value'])): ?>
                                                                    <p><strong><i class="fas fa-dollar-sign"></i> Total Value:</strong><br>
                                                                    <span class="badge bg-success fs-6"><?= number_format($implementation['total_value'], 2) ?></span></p>
                                                                <?php endif; ?>

                                                                <?php if (!empty($implementation['gps_coordinates'])): ?>
                                                                    <p><strong><i class="fas fa-map-pin"></i> GPS Location:</strong><br>
                                                                    <small class="font-monospace bg-white p-1 rounded"><?= esc($implementation['gps_coordinates']) ?></small></p>
                                                                <?php endif; ?>

                                                                <p><strong><i class="fas fa-clock"></i> Production Date:</strong><br>
                                                                <small><?= date('M d, Y H:i', strtotime($implementation['created_at'])) ?></small></p>

                                                                <?php if (!empty($implementation['signing_sheet_filepath'])): ?>
                                                                    <p><strong><i class="fas fa-file-signature"></i> Signing Sheet:</strong><br>
                                                                    <a href="<?= base_url($implementation['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                                                        <i class="fas fa-download"></i> Download
                                                                    </a></p>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>

                                                        <!-- Output Images -->
                                                        <?php if (!empty($implementation['output_images'])): ?>
                                                            <div class="card bg-light mt-3">
                                                                <div class="card-body">
                                                                    <h6><i class="fas fa-images"></i> Output Photos</h6>
                                                                    <?php
                                                                    $images = is_string($implementation['output_images']) ? json_decode($implementation['output_images'], true) : $implementation['output_images'];
                                                                    if (is_array($images) && !empty($images)) {
                                                                        echo '<div class="row">';
                                                                        foreach ($images as $image) {
                                                                            $imagePath = is_array($image) ? ($image['path'] ?? $image['url'] ?? '') : $image;
                                                                            if (!empty($imagePath)) {
                                                                                echo '<div class="col-6 mb-2">';
                                                                                echo '<img src="' . base_url($imagePath) . '" class="img-fluid rounded" style="max-height: 100px; cursor: pointer;" onclick="window.open(this.src)" title="Click to view full size">';
                                                                                echo '</div>';
                                                                            }
                                                                        }
                                                                        echo '</div>';
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                        <!-- Output Files -->
                                                        <?php if (!empty($implementation['output_files'])): ?>
                                                            <div class="card bg-light mt-3">
                                                                <div class="card-body">
                                                                    <h6><i class="fas fa-file-alt"></i> Output Documents</h6>
                                                                    <?php
                                                                    $files = is_string($implementation['output_files']) ? json_decode($implementation['output_files'], true) : $implementation['output_files'];
                                                                    if (is_array($files) && !empty($files)) {
                                                                        foreach ($files as $file) {
                                                                            $filePath = is_array($file) ? ($file['path'] ?? $file['url'] ?? '') : $file;
                                                                            $fileName = is_array($file) ? ($file['name'] ?? basename($filePath)) : basename($filePath);
                                                                            if (!empty($filePath)) {
                                                                                echo '<div class="mb-2">';
                                                                                echo '<a href="' . base_url($filePath) . '" target="_blank" class="btn btn-sm btn-outline-secondary">';
                                                                                echo '<i class="fas fa-download"></i> ' . esc($fileName);
                                                                                echo '</a></div>';
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <?php if (!empty($implementation['remarks'])): ?>
                                                    <div class="mt-3">
                                                        <h6><i class="fas fa-comment"></i> Output Remarks</h6>
                                                        <div class="alert alert-info">
                                                            <?= nl2br(esc($implementation['remarks'])) ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> No output implementation activities recorded for this proposal yet.
                            </div>
                        <?php endif; ?>

                        <?php if ($index < count($proposals) - 1): ?>
                            <hr class="my-5">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
