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
    border-bottom: 3px solid #0d6efd;
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
    color: #0d6efd;
    border-bottom: 2px solid #0d6efd;
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
    border-left: 4px solid #0d6efd;
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
                    <li class="breadcrumb-item active" aria-current="page"><?= esc($activity['activity_code'] ?? 'Training Activity') ?></li>
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
        <h1 style="color: #0d6efd; margin-bottom: 10px;">TRAINING ACTIVITY EVALUATION REPORT</h1>
        <h2 style="color: #6c757d; font-size: 1.5rem; margin-bottom: 5px;"><?= esc($activity['title']) ?></h2>
        <p style="color: #6c757d; margin-bottom: 0;">Activity Code: <strong><?= esc($activity['activity_code'] ?? 'N/A') ?></strong></p>
        <p style="color: #6c757d; margin-bottom: 0;">Generated on: <strong><?= date('F d, Y') ?></strong></p>
    </div>

    <!-- Activity Overview -->
    <div class="info-section">
        <h3 class="section-title">Activity Information</h3>
        <div class="row">
            <!-- Basic Information -->
            <div class="col-md-6">
                <table class="info-table">
                    <tr>
                        <th>Activity Code</th>
                        <td><strong><?= esc($activity['activity_code'] ?? 'N/A') ?></strong></td>
                    </tr>
                    <tr>
                        <th>Training Title</th>
                        <td><strong><?= esc($activity['title']) ?></strong></td>
                    </tr>
                    <tr>
                        <th>Activity Type</th>
                        <td>Training</td>
                    </tr>
                    <tr>
                        <th>Workplan</th>
                        <td>
                            <strong><?= esc($workplan['title'] ?? 'N/A') ?></strong>
                            <?php if (!empty($workplan['start_date'])): ?>
                                <br><small style="color: #6c757d;">
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
                    <tr>
                        <th>Total Budget</th>
                        <td><strong><?= CURRENCY_SYMBOL ?> <?= $activity['total_budget'] ? number_format($activity['total_budget'], 2) : '0.00' ?></strong></td>
                    </tr>
                </table>
            </div>

            <!-- Performance & Evaluation -->
            <div class="col-md-6">
                <table class="info-table">
                    <tr>
                        <th colspan="2" style="text-align: center; background-color: #e3f2fd;">Quarterly Performance</th>
                    </tr>
                    <tr>
                        <th>Quarter</th>
                        <th>Target / Achieved / Percentage</th>
                    </tr>
                    <tr>
                        <td><strong>Q1</strong></td>
                        <td>
                            <?php
                            $q1_target = $activity['q_one_target'] ? number_format($activity['q_one_target'], 0) : 'N/A';
                            $q1_achieved = $activity['q_one_achieved'] ? number_format($activity['q_one_achieved'], 0) : 'N/A';
                            $q1_percent = ($activity['q_one_target'] && $activity['q_one_achieved'])
                                ? round(($activity['q_one_achieved'] / $activity['q_one_target']) * 100, 1)
                                : 0;
                            ?>
                            <?= $q1_target ?> / <?= $q1_achieved ?> / <strong><?= $q1_percent ?>%</strong>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Q2</strong></td>
                        <td>
                            <?php
                            $q2_target = $activity['q_two_target'] ? number_format($activity['q_two_target'], 0) : 'N/A';
                            $q2_achieved = $activity['q_two_achieved'] ? number_format($activity['q_two_achieved'], 0) : 'N/A';
                            $q2_percent = ($activity['q_two_target'] && $activity['q_two_achieved'])
                                ? round(($activity['q_two_achieved'] / $activity['q_two_target']) * 100, 1)
                                : 0;
                            ?>
                            <?= $q2_target ?> / <?= $q2_achieved ?> / <strong><?= $q2_percent ?>%</strong>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Q3</strong></td>
                        <td>
                            <?php
                            $q3_target = $activity['q_three_target'] ? number_format($activity['q_three_target'], 0) : 'N/A';
                            $q3_achieved = $activity['q_three_achieved'] ? number_format($activity['q_three_achieved'], 0) : 'N/A';
                            $q3_percent = ($activity['q_three_target'] && $activity['q_three_achieved'])
                                ? round(($activity['q_three_achieved'] / $activity['q_three_target']) * 100, 1)
                                : 0;
                            ?>
                            <?= $q3_target ?> / <?= $q3_achieved ?> / <strong><?= $q3_percent ?>%</strong>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Q4</strong></td>
                        <td>
                            <?php
                            $q4_target = $activity['q_four_target'] ? number_format($activity['q_four_target'], 0) : 'N/A';
                            $q4_achieved = $activity['q_four_achieved'] ? number_format($activity['q_four_achieved'], 0) : 'N/A';
                            $q4_percent = ($activity['q_four_target'] && $activity['q_four_achieved'])
                                ? round(($activity['q_four_achieved'] / $activity['q_four_target']) * 100, 1)
                                : 0;
                            ?>
                            <?= $q4_target ?> / <?= $q4_achieved ?> / <strong><?= $q4_percent ?>%</strong>
                        </td>
                    </tr>
                    <tr style="background-color: #f8f9fa;">
                        <td><strong>TOTAL</strong></td>
                        <td>
                            <?php
                            $total_target = ($activity['q_one_target'] ?? 0) + ($activity['q_two_target'] ?? 0) +
                                           ($activity['q_three_target'] ?? 0) + ($activity['q_four_target'] ?? 0);
                            $total_achieved = ($activity['q_one_achieved'] ?? 0) + ($activity['q_two_achieved'] ?? 0) +
                                             ($activity['q_three_achieved'] ?? 0) + ($activity['q_four_achieved'] ?? 0);
                            $total_percent = ($total_target && $total_achieved)
                                ? round(($total_achieved / $total_target) * 100, 1)
                                : 0;
                            ?>
                            <strong><?= $total_target ? number_format($total_target, 0) : 'N/A' ?> /
                            <?= $total_achieved ? number_format($total_achieved, 0) : 'N/A' ?> /
                            <?= $total_percent ?>%</strong>
                        </td>
                    </tr>
                    <?php if (!empty($activity['rating']) && $activity['rating'] > 0): ?>
                    <tr>
                        <th>Activity Rating</th>
                        <td>
                            <strong><?= $activity['rating'] ?>/5</strong>
                            <?php if (!empty($activity['rated_at'])): ?>
                                <br><small style="color: #6c757d;">Rated on <?= date('M d, Y', strtotime($activity['rated_at'])) ?></small>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if (!empty($activity['reated_remarks'])): ?>
                    <tr>
                        <th>Evaluation Remarks</th>
                        <td><?= nl2br(esc($activity['reated_remarks'])) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php else: ?>
                    <tr>
                        <th>Activity Rating</th>
                        <td><em>Not yet rated</em></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="info-section">
        <h3 class="section-title">Summary Statistics</h3>
        <div class="performance-grid">
            <div class="metric-card">
                <h4 style="color: #0d6efd; margin-bottom: 10px;"><?= CURRENCY_SYMBOL ?> <?= $activity['total_budget'] ? number_format($activity['total_budget'], 2) : '0.00' ?></h4>
                <p style="margin: 0; color: #6c757d;">Training Budget</p>
            </div>
            <div class="metric-card">
                <h4 style="color: #0d6efd; margin-bottom: 10px;"><?= $proposalSummary['total'] ?></h4>
                <p style="margin: 0; color: #6c757d;">Total Proposals</p>
            </div>
            <div class="metric-card">
                <h4 style="color: #0d6efd; margin-bottom: 10px;"><?= $proposalSummary['approved'] ?></h4>
                <p style="margin: 0; color: #6c757d;">Approved Proposals</p>
            </div>
            <div class="metric-card">
                <h4 style="color: #0d6efd; margin-bottom: 10px;"><?= count($proposals) ?></h4>
                <p style="margin: 0; color: #6c757d;">For Evaluation</p>
            </div>
        </div>
    </div>

    <!-- Description -->
    <?php if (!empty($activity['description'])): ?>
    <div class="info-section">
        <h3 class="section-title">Training Description</h3>
        <div style="padding: 20px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;">
            <?= nl2br(esc($activity['description'])) ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Training Proposals and Implementation Details -->
    <div class="info-section page-break">
        <h3 class="section-title">Training Proposals - Implementation Details</h3>
        <p style="color: #6c757d; margin-bottom: 30px;">Approved or rated training proposals with full implementation details</p>

        <?php if (empty($proposals)): ?>
            <div style="padding: 20px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; color: #856404;">
                <strong>Notice:</strong> No approved or rated training proposals found for this activity.
            </div>
        <?php else: ?>
            <?php foreach ($proposals as $index => $proposal): ?>
                <div class="proposal-section">
                    <!-- Proposal Header -->
                    <div class="proposal-header">
                        <h4 style="margin: 0; color: #0d6efd;">
                            Training Proposal #<?= $index + 1 ?>
                            <span style="font-size: 0.8em; padding: 4px 8px; border: 1px solid #0d6efd; border-radius: 4px;">
                                <?= ucfirst(esc($proposal['status'] ?? 'N/A')) ?>
                            </span>
                        </h4>
                        <p style="margin: 5px 0 0 0; color: #6c757d;">Created: <?= date('M d, Y', strtotime($proposal['created_at'])) ?></p>
                    </div>

                    <!-- Proposal Details -->
                    <div style="padding: 20px;">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 style="color: #0d6efd; margin-bottom: 15px;">Training Information</h5>
                                <table class="info-table">
                                    <tr>
                                        <th>Training Period</th>
                                        <td>
                                            <?= $proposal['date_start'] ? date('M d, Y', strtotime($proposal['date_start'])) : 'N/A' ?>
                                            <?php if ($proposal['date_end']): ?>
                                                - <?= date('M d, Y', strtotime($proposal['date_end'])) ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Training Venue</th>
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
                                        <th>Training Cost</th>
                                        <td><strong><?= CURRENCY_SYMBOL ?> <?= $proposal['total_cost'] ? number_format($proposal['total_cost'], 2) : '0.00' ?></strong></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5 style="color: #0d6efd; margin-bottom: 15px;">Personnel & Rating</h5>
                                <table class="info-table">
                                    <tr>
                                        <th>Supervisor</th>
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
                                            <strong><?= esc($proposal['rating_score']) ?>/10</strong>
                                            <?php if ($proposal['rated_at']): ?>
                                                <br><small style="color: #6c757d;">Rated: <?= date('M d, Y', strtotime($proposal['rated_at'])) ?></small>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if (!empty($proposal['rate_remarks'])): ?>
                                    <tr>
                                        <th>Rating Remarks</th>
                                        <td><?= esc($proposal['rate_remarks']) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Training Implementation Activities -->
                    <?php if (!empty($proposal['implementations'])): ?>
                        <div style="margin-top: 30px;">
                            <h5 style="color: #0d6efd; margin-bottom: 20px;">
                                Training Implementation Details
                                <span style="font-size: 0.8em; padding: 4px 8px; border: 1px solid #0d6efd; border-radius: 4px;">
                                    <?= count($proposal['implementations']) ?> Sessions
                                </span>
                            </h5>

                            <?php foreach ($proposal['implementations'] as $impl_index => $implementation): ?>
                                <?php if ($implementation['type'] === 'Training'): ?>
                                    <div class="implementation-item">
                                        <h6 style="color: #0d6efd; margin-bottom: 15px;">
                                            Training Session #<?= $impl_index + 1 ?>
                                            <span style="float: right; color: #6c757d; font-size: 0.9em;">
                                                <?= date('M d, Y', strtotime($implementation['created_at'])) ?>
                                            </span>
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <!-- Training Details -->
                                                <?php if (!empty($implementation['trainers'])): ?>
                                                    <div style="margin-bottom: 20px;">
                                                        <h6 style="color: #0d6efd; margin-bottom: 10px;">Trainers</h6>
                                                        <div style="padding: 15px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;">
                                                            <?= nl2br(esc($implementation['trainers'])) ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if (!empty($implementation['topics'])): ?>
                                                    <div style="margin-bottom: 20px;">
                                                        <h6 style="color: #0d6efd; margin-bottom: 10px;">Topics Covered</h6>
                                                        <div style="padding: 15px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;">
                                                            <?= nl2br(esc($implementation['topics'])) ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- Trainees List -->
                                                <?php if (!empty($implementation['trainees'])): ?>
                                                    <div style="margin-bottom: 20px;">
                                                        <h6 style="color: #0d6efd; margin-bottom: 10px;">Trainees</h6>
                                                        <div style="padding: 15px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6;">
                                                            <?php
                                                            // Handle trainees data - could be JSON string or already parsed array
                                                            $trainees = $implementation['trainees'];
                                                            if (is_string($trainees)) {
                                                                $trainees = json_decode($trainees, true);
                                                            }

                                                            if (is_array($trainees) && !empty($trainees)) {
                                                                echo '<table style="width: 100%; border-collapse: collapse; font-size: 12px;">';
                                                                echo '<thead>';
                                                                echo '<tr style="background-color: #e3f2fd;">';
                                                                echo '<th style="border: 1px solid #dee2e6; padding: 8px; text-align: left;">#</th>';
                                                                echo '<th style="border: 1px solid #dee2e6; padding: 8px; text-align: left;">Full Name</th>';
                                                                echo '<th style="border: 1px solid #dee2e6; padding: 8px; text-align: left;">Age</th>';
                                                                echo '<th style="border: 1px solid #dee2e6; padding: 8px; text-align: left;">Gender</th>';
                                                                echo '<th style="border: 1px solid #dee2e6; padding: 8px; text-align: left;">Phone</th>';
                                                                echo '<th style="border: 1px solid #dee2e6; padding: 8px; text-align: left;">Email</th>';
                                                                echo '<th style="border: 1px solid #dee2e6; padding: 8px; text-align: left;">Remarks</th>';
                                                                echo '</tr>';
                                                                echo '</thead>';
                                                                echo '<tbody>';
                                                                foreach ($trainees as $index => $trainee) {
                                                                    echo '<tr>';
                                                                    echo '<td style="border: 1px solid #dee2e6; padding: 8px;">' . ($index + 1) . '</td>';
                                                                    if (is_array($trainee)) {
                                                                        echo '<td style="border: 1px solid #dee2e6; padding: 8px;"><strong>' . esc($trainee['full_name'] ?? $trainee['name'] ?? 'N/A') . '</strong></td>';
                                                                        echo '<td style="border: 1px solid #dee2e6; padding: 8px;">' . esc($trainee['age'] ?? 'N/A') . '</td>';
                                                                        echo '<td style="border: 1px solid #dee2e6; padding: 8px;">' . esc($trainee['gender'] ?? 'N/A') . '</td>';
                                                                        echo '<td style="border: 1px solid #dee2e6; padding: 8px;">' . esc($trainee['phone'] ?? $trainee['contact'] ?? 'N/A') . '</td>';
                                                                        echo '<td style="border: 1px solid #dee2e6; padding: 8px;">' . esc($trainee['email'] ?? 'N/A') . '</td>';
                                                                        echo '<td style="border: 1px solid #dee2e6; padding: 8px;">' . esc($trainee['remarks'] ?? $trainee['notes'] ?? 'N/A') . '</td>';
                                                                    } else {
                                                                        echo '<td colspan="6" style="border: 1px solid #dee2e6; padding: 8px;">' . esc($trainee) . '</td>';
                                                                    }
                                                                    echo '</tr>';
                                                                }
                                                                echo '</tbody></table>';
                                                                echo '<p style="margin-top: 15px; margin-bottom: 0;"><strong>Total Trainees: ' . count($trainees) . '</strong></p>';
                                                            } else {
                                                                echo '<p>' . esc($implementation['trainees']) . '</p>';
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="col-md-4">
                                                <!-- Training Information -->
                                                <div style="padding: 15px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6; margin-bottom: 20px;">
                                                    <h6 style="color: #0d6efd; margin-bottom: 15px;">Training Information</h6>

                                                    <?php if (!empty($implementation['gps_coordinates'])): ?>
                                                        <p style="margin-bottom: 10px;"><strong>GPS Location:</strong><br>
                                                        <small style="font-family: monospace; background-color: #fff; padding: 4px; border-radius: 4px; border: 1px solid #dee2e6;"><?= esc($implementation['gps_coordinates']) ?></small></p>
                                                    <?php endif; ?>

                                                    <p style="margin-bottom: 10px;"><strong>Implementation Date:</strong><br>
                                                    <small><?= date('M d, Y H:i', strtotime($implementation['created_at'])) ?></small></p>

                                                    <?php if (!empty($implementation['signing_sheet_filepath'])): ?>
                                                        <p style="margin-bottom: 10px;"><strong>Signing Sheet:</strong><br>
                                                        <a href="<?= base_url($implementation['signing_sheet_filepath']) ?>" target="_blank" style="color: #0d6efd; text-decoration: none;">
                                                            📄 Download Signing Sheet
                                                        </a></p>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Training Images -->
                                                <?php if (!empty($implementation['training_images'])): ?>
                                                    <div style="padding: 15px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6; margin-bottom: 20px;">
                                                        <h6 style="color: #0d6efd; margin-bottom: 15px;">Training Photos</h6>
                                                        <?php
                                                        $images = is_string($implementation['training_images']) ? json_decode($implementation['training_images'], true) : $implementation['training_images'];
                                                        if (is_array($images) && !empty($images)) {
                                                            echo '<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 10px;">';
                                                            foreach ($images as $image) {
                                                                $imagePath = is_array($image) ? ($image['path'] ?? $image['url'] ?? '') : $image;
                                                                if (!empty($imagePath)) {
                                                                    echo '<div>';
                                                                    echo '<img src="' . base_url($imagePath) . '" style="width: 100%; max-height: 120px; object-fit: cover; border-radius: 4px; border: 1px solid #dee2e6;" onclick="window.open(this.src)" title="Click to view full size">';
                                                                    echo '</div>';
                                                                }
                                                            }
                                                            echo '</div>';
                                                        }
                                                        ?>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- Training Files -->
                                                <?php if (!empty($implementation['training_files'])): ?>
                                                    <div style="padding: 15px; background-color: #f8f9fa; border-radius: 8px; border: 1px solid #dee2e6; margin-bottom: 20px;">
                                                        <h6 style="color: #0d6efd; margin-bottom: 15px;">Training Files</h6>
                                                        <?php
                                                        $files = is_string($implementation['training_files']) ? json_decode($implementation['training_files'], true) : $implementation['training_files'];
                                                        if (is_array($files) && !empty($files)) {
                                                            foreach ($files as $file) {
                                                                $filePath = is_array($file) ? ($file['path'] ?? $file['url'] ?? '') : $file;
                                                                $fileName = is_array($file) ? ($file['name'] ?? basename($filePath)) : basename($filePath);
                                                                if (!empty($filePath)) {
                                                                    echo '<div style="margin-bottom: 8px;">';
                                                                    echo '<a href="' . base_url($filePath) . '" target="_blank" style="color: #0d6efd; text-decoration: none;">';
                                                                    echo '📁 ' . esc($fileName);
                                                                    echo '</a></div>';
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <?php if (!empty($implementation['remarks'])): ?>
                                            <div style="margin-top: 20px;">
                                                <h6 style="color: #0d6efd; margin-bottom: 10px;">Training Remarks</h6>
                                                <div style="padding: 15px; background-color: #e3f2fd; border-radius: 8px; border: 1px solid #bbdefb;">
                                                    <?= nl2br(esc($implementation['remarks'])) ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="padding: 20px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; color: #856404;">
                            <strong>Notice:</strong> No training implementation activities recorded for this proposal yet.
                        </div>
                    <?php endif; ?>

                    <?php if ($index < count($proposals) - 1): ?>
                        <div style="border-top: 2px solid #dee2e6; margin: 40px 0;"></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
