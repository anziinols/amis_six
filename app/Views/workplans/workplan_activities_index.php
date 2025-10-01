<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<style>
    /* Status badge styles with dark text on light backgrounds */
    .status-badge {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.85em;
        font-weight: 600;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
        border: 1px solid transparent;
    }

    .status-pending {
        color: #084298;
        background-color: #cfe2ff;
        border-color: #b6d4fe;
    }

    .status-submitted {
        color: #664d03;
        background-color: #fff3cd;
        border-color: #ffecb5;
    }

    .status-approved {
        color: #0f5132;
        background-color: #d1e7dd;
        border-color: #badbcc;
    }

    .status-rated {
        color: #842029;
        background-color: #f8d7da;
        border-color: #f5c2c7;
    }

    .status-default {
        color: #41464b;
        background-color: #e2e3e5;
        border-color: #d3d6d8;
    }

    /* Plan link indicators */
    .plan-check {
        font-size: 1.1em;
        margin-right: 0.25rem;
    }

    .plan-check.nasp {
        color: #28a745;
        title: "NASP Plan Linked";
    }

    .plan-check.mtdp {
        color: #007bff;
        title: "MTDP Plan Linked";
    }

    .plan-check.corporate {
        color: #6f42c1;
        title: "Corporate Plan Linked";
    }

    .plan-check.others {
        color: #fd7e14;
        title: "Others Link";
    }
</style>
<div class="mb-3">
    <a href="<?= base_url('workplans/' . $workplan['id']) ?>" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left"></i> Back to Workplan Details
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Activities for: <?= esc($workplan['title']) ?></h5>
        <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/new') ?>" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New Activity
        </a>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Activity Code</th>
                        <th>Title</th>
                        <th>Target Output</th>
                        <th>Budget</th>
                        <th>Supervisor</th>
                        <th>Plans Linked</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($activities)): ?>
                        <?php foreach ($activities as $index => $activity): ?>
                            <tr>
                                <td><?= esc($index + 1) ?></td>
                                <td><span class="badge bg-primary"><?= esc($activity['activity_code'] ?? 'N/A') ?></span></td>
                                <td><?= esc($activity['title']) ?></td>
                                <td><?= esc($activity['target_output'] ?? 'N/A') ?></td>
                                <td><?= !empty($activity['total_budget']) ? number_format($activity['total_budget'], 2) : 'N/A' ?></td>
                                <td><?= esc($activity['supervisor_name'] ?? 'N/A') ?></td>
                                <td class="text-center">
                                    <?php
                                    $hasNasp = $activity['nasp_linked'] ?? false;
                                    $hasMtdp = $activity['mtdp_linked'] ?? false;
                                    $hasCorporate = $activity['corporate_linked'] ?? false;
                                    $hasOthers = $activity['others_linked'] ?? false;
                                    $totalLinked = $activity['total_plans_linked'] ?? 0;

                                    if ($hasNasp) {
                                        echo '<i class="fas fa-check plan-check nasp" title="NASP Plan Linked" style="color: #28a745;"></i>';
                                    }
                                    if ($hasMtdp) {
                                        echo '<i class="fas fa-check plan-check mtdp" title="MTDP Plan Linked" style="color: #007bff;"></i>';
                                    }
                                    if ($hasCorporate) {
                                        echo '<i class="fas fa-check plan-check corporate" title="Corporate Plan Linked" style="color: #6f42c1;"></i>';
                                    }
                                    if ($hasOthers) {
                                        echo '<i class="fas fa-check plan-check others" title="Others Link" style="color: #fd7e14;"></i>';
                                    }

                                    if ($totalLinked == 0) {
                                        echo '<span class="text-muted small">No plans linked</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/plans') ?>" class="btn btn-outline-success" title="Link to Plans" style="margin-right: 5px;">
                                            <i class="fas fa-link me-1"></i> Plans
                                        </a>
                                        <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id']) ?>" class="btn btn-outline-primary" title="View Details" style="margin-right: 5px;">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                        <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/edit') ?>" class="btn btn-outline-warning" title="Edit" style="margin-right: 5px;">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <?php if (!isset($activity['has_myactivities_links']) || !$activity['has_myactivities_links']): ?>
                                        <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/delete') ?>" class="btn btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this activity?');">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </a>
                                        <?php else: ?>
                                        <button class="btn btn-outline-secondary" title="Cannot delete - Activity is linked to My Activities" disabled>
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No activities found for this workplan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
