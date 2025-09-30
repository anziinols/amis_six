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

    .activity-images {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .activity-image {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 5px;
        border: 1px solid #ddd;
    }
</style>
<div class="mb-3">
    <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities') ?>" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left"></i> Back to Activities List
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Activity Details</h5>
    </div>
    <div class="card-header">
        <ul class="nav nav-tabs" id="activityTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">
                    <i class="fas fa-info-circle"></i> Details
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="plans-tab" data-bs-toggle="tab" data-bs-target="#plans" type="button" role="tab" aria-controls="plans" aria-selected="false">
                    <i class="fas fa-link"></i> Plan Links
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/others') ?>" id="others-tab" type="button">
                    <i class="fas fa-plus-circle"></i> Others
                </a>
            </li>
        </ul>
    </div>
    <div class="card-header d-flex justify-content-between align-items-center">
        <div></div>
        <div>
            <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/edit') ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/delete') ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this activity?');">
                <i class="fas fa-trash"></i> Delete
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="tab-content" id="activityTabContent">
            <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%">Activity Code</th>
                        <td><span class="badge bg-primary"><?= esc($activity['activity_code'] ?? 'N/A') ?></span></td>
                    </tr>
                    <tr>
                        <th>Activity Title</th>
                        <td><?= esc($activity['title']) ?></td>
                    </tr>
                    <tr>
                        <th>Target Output</th>
                        <td><?= esc($activity['target_output'] ?? 'Not specified') ?></td>
                    </tr>
                    <tr>
                        <th>Branch</th>
                        <td><?= esc($activity['branch_name'] ?? 'Not assigned') ?></td>
                    </tr>
                    <tr>
                        <th>Supervisor</th>
                        <td><?= esc($activity['supervisor_name'] ?? 'Not assigned') ?></td>
                    </tr>
                    <tr>
                        <th>Action Officer</th>
                        <td><?= esc($activity['officer_name'] ?? 'Not assigned') ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if (!empty($activity['status'])): ?>
                                <span class="badge bg-<?= $activity['status'] === 'active' ? 'success' : ($activity['status'] === 'pending' ? 'warning' : 'secondary') ?>">
                                    <?= ucfirst(esc($activity['status'])) ?>
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Not Set</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <h6>Description</h6>
            <div class="p-3 bg-light rounded">
                <?= nl2br(esc($activity['description'] ?? 'No description provided.')) ?>
            </div>
        </div>



        <!-- Budget and Achievement Summary -->
        <div class="mt-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title">Total Budget</h6>
                            <p class="card-text fs-4 fw-bold text-success"><?= $activity['total_budget'] ? CURRENCY_SYMBOL . ' ' . number_format($activity['total_budget'], 2) : 'N/A' ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h6 class="card-title">Activity Status</h6>
                            <p class="card-text fs-4 fw-bold text-info">
                                <span class="badge bg-<?= $activity['status'] == 'completed' ? 'success' : ($activity['status'] == 'in_progress' ? 'warning' : 'secondary') ?> fs-6">
                                    <?= ucfirst(str_replace('_', ' ', $activity['status'] ?? 'pending')) ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rating Section -->
        <?php if (!empty($activity['rating']) || !empty($activity['rated_at'])): ?>
        <div class="mt-4">
            <h6>Activity Rating</h6>
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Rating:</strong>
                                <?php if (!empty($activity['rating'])): ?>
                                    <span class="badge bg-warning fs-6"><?= esc($activity['rating']) ?>/10</span>
                                <?php else: ?>
                                    <span class="text-muted">Not rated</span>
                                <?php endif; ?>
                            </p>
                            <?php if (!empty($activity['rated_at'])): ?>
                                <p><strong>Rated Date:</strong> <?= date('M d, Y H:i', strtotime($activity['rated_at'])) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if (!empty($activity['rated_by'])): ?>
                                <p><strong>Rated By:</strong> <?= esc($activity['rated_by_name'] ?? 'N/A') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($activity['reated_remarks'])): ?>
                        <div class="mt-3">
                            <strong>Rating Remarks:</strong>
                            <div class="p-2 bg-white rounded border">
                                <?= nl2br(esc($activity['reated_remarks'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    </div>
                    <div class="tab-pane fade" id="plans" role="tabpanel" aria-labelledby="plans-tab">
                        <!-- Plan Links Section -->
                        <h5>Plan Links</h5>
                        
                        <!-- NASP Links -->
                        <div class="card mt-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">NASP Links</h6>
                            </div>
                            <div class="card-body">
                                <?php if (empty($naspLinks)): ?>
                                    <div class="alert alert-info">
                                        No NASP plans linked to this activity yet.
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>NASP Code</th>
                                                    <th>Output Code</th>
                                                    <th>Output Title</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($naspLinks as $index => $link): ?>
                                                    <tr>
                                                        <td><?= esc($index + 1) ?></td>
                                                        <td><?= esc($link['nasp_code']) ?></td>
                                                        <td><?= esc($link['output_code']) ?></td>
                                                        <td><?= esc($link['output_title']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- MTDP Links -->
                        <div class="card mt-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">MTDP Links</h6>
                            </div>
                            <div class="card-body">
                                <?php if (empty($mtdpLinks)): ?>
                                    <div class="alert alert-info">
                                        No MTDP plans linked to this activity yet.
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>MTDP Code</th>
                                                    <th>SPA Code</th>
                                                    <th>DIP Code</th>
                                                    <th>Strategy</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($mtdpLinks as $index => $link): ?>
                                                    <tr>
                                                        <td><?= esc($index + 1) ?></td>
                                                        <td><?= esc($link['mtdp_code']) ?></td>
                                                        <td><?= esc($link['spa_code']) ?></td>
                                                        <td><?= esc($link['dip_code']) ?></td>
                                                        <td>
                                                            <?= esc($link['strategy']) ?>
                                                            <?php if (!empty($link['strategy_full']) && $link['strategy_full'] != $link['strategy'] && $link['strategy_full'] != 'N/A'): ?>
                                                                <button type="button" class="btn btn-sm btn-info ms-2"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="<?= esc($link['strategy_full']) ?>">
                                                                    <i class="fas fa-info-circle"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Corporate Plan Links -->
                        <div class="card mt-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Corporate Plan Links</h6>
                            </div>
                            <div class="card-body">
                                <?php if (empty($corporateLinks)): ?>
                                    <div class="alert alert-info">
                                        No Corporate plans linked to this activity yet.
                                    </div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Plan Code</th>
                                                    <th>Objective</th>
                                                    <th>Strategy</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($corporateLinks as $index => $link): ?>
                                                    <tr>
                                                        <td><?= esc($index + 1) ?></td>
                                                        <td><?= esc($link['plan_code']) ?></td>
                                                        <td>
                                                            <?= esc($link['objective_code']) ?>
                                                            <?php if (!empty($link['objective_full']) && $link['objective_full'] != $link['objective_code'] && $link['objective_full'] != 'N/A'): ?>
                                                                <button type="button" class="btn btn-sm btn-info ms-2"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="<?= esc($link['objective_full']) ?>">
                                                                    <i class="fas fa-info-circle"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?= esc($link['strategy_title']) ?>
                                                            <?php if (!empty($link['strategy_full']) && $link['strategy_full'] != $link['strategy_title'] && $link['strategy_full'] != 'N/A'): ?>
                                                                <button type="button" class="btn btn-sm btn-info ms-2"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="<?= esc($link['strategy_full']) ?>">
                                                                    <i class="fas fa-info-circle"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                                </div>
                                <div class="tab-pane fade" id="others" role="tabpanel" aria-labelledby="others-tab">
                                    <!-- Others Links Section -->
                                    <h5>Others Links</h5>
                                    
                                    <!-- Others Links -->
                                    <div class="card mt-3">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">Others Links</h6>
                                        </div>
                                        <div class="card-body">
                                            <?php if (empty($othersLinks)): ?>
                                                <div class="alert alert-info">
                                                    No Others links found for this activity yet.
                                                </div>
                                            <?php else: ?>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Link Type</th>
                                                                <th>Title</th>
                                                                <th>Description</th>
                                                                <th>URL</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($othersLinks as $index => $link): ?>
                                                                <tr>
                                                                    <td><?= esc($index + 1) ?></td>
                                                                    <td><?= esc($link['link_type'] ?? 'N/A') ?></td>
                                                                    <td><?= esc($link['title'] ?? 'N/A') ?></td>
                                                                    <td><?= esc($link['description'] ?? 'N/A') ?></td>
                                                                    <td>
                                                                        <?php if (!empty($link['url'])): ?>
                                                                            <a href="<?= esc($link['url']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                                <i class="fas fa-external-link-alt"></i> View
                                                                            </a>
                                                                        <?php else: ?>
                                                                            <span class="text-muted">N/A</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        <?php endif; ?>

        <!-- Status Information -->
        <?php if (!empty($activity['status_remarks']) || !empty($activity['status_at'])): ?>
        <div class="mt-4">
            <h6>Status Information</h6>
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?php if (!empty($activity['status_at'])): ?>
                                <p><strong>Status Updated:</strong> <?= date('M d, Y H:i', strtotime($activity['status_at'])) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <?php if (!empty($activity['status_by'])): ?>
                                <p><strong>Updated By:</strong> <?= esc($activity['status_by_name'] ?? 'N/A') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($activity['status_remarks'])): ?>
                        <div class="mt-3">
                            <strong>Status Remarks:</strong>
                            <div class="p-2 bg-white rounded border">
                                <?= nl2br(esc($activity['status_remarks'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Plan Links Section -->
        <div class="mt-4">
            <h5>Plan Links</h5>

            <!-- NASP Links -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">NASP Links</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($naspLinks)): ?>
                        <div class="alert alert-info">
                            No NASP plans linked to this activity yet.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>NASP Code</th>
                                        <th>Output Code</th>
                                        <th>Output Title</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($naspLinks as $index => $link): ?>
                                        <tr>
                                            <td><?= esc($index + 1) ?></td>
                                            <td><?= esc($link['nasp_code']) ?></td>
                                            <td><?= esc($link['output_code']) ?></td>
                                            <td><?= esc($link['output_title']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- MTDP Links -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">MTDP Links</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($mtdpLinks)): ?>
                        <div class="alert alert-info">
                            No MTDP plans linked to this activity yet.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>MTDP Code</th>
                                        <th>SPA Code</th>
                                        <th>DIP Code</th>
                                        <th>Strategy</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($mtdpLinks as $index => $link): ?>
                                        <tr>
                                            <td><?= esc($index + 1) ?></td>
                                            <td><?= esc($link['mtdp_code']) ?></td>
                                            <td><?= esc($link['spa_code']) ?></td>
                                            <td><?= esc($link['dip_code']) ?></td>
                                            <td>
                                                <?= esc($link['strategy']) ?>
                                                <?php if (!empty($link['strategy_full']) && $link['strategy_full'] != $link['strategy'] && $link['strategy_full'] != 'N/A'): ?>
                                                    <button type="button" class="btn btn-sm btn-info ms-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="<?= esc($link['strategy_full']) ?>">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Corporate Plan Links -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Corporate Plan Links</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($corporateLinks)): ?>
                        <div class="alert alert-info">
                            No Corporate plans linked to this activity yet.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Plan Code</th>
                                        <th>Objective</th>
                                        <th>Strategy</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($corporateLinks as $index => $link): ?>
                                        <tr>
                                            <td><?= esc($index + 1) ?></td>
                                            <td><?= esc($link['plan_code']) ?></td>
                                            <td>
                                                <?= esc($link['objective_code']) ?>
                                                <?php if (!empty($link['objective_full']) && $link['objective_full'] != $link['objective_code'] && $link['objective_full'] != 'N/A'): ?>
                                                    <button type="button" class="btn btn-sm btn-info ms-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="<?= esc($link['objective_full']) ?>">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?= esc($link['strategy_title']) ?>
                                                <?php if (!empty($link['strategy_full']) && $link['strategy_full'] != $link['strategy_title'] && $link['strategy_full'] != 'N/A'): ?>
                                                    <button type="button" class="btn btn-sm btn-info ms-2"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="<?= esc($link['strategy_full']) ?>">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Others Links -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Others Links</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($othersLinks)): ?>
                        <div class="alert alert-info">
                            No Others links found for this activity yet.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Link Type</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($othersLinks as $index => $link): ?>
                                        <tr>
                                            <td><?= esc($index + 1) ?></td>
                                            <td><?= esc($link['link_type'] ?? 'N/A') ?></td>
                                            <td><?= esc($link['title'] ?? 'N/A') ?></td>
                                            <td><?= esc($link['description'] ?? 'N/A') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?= $this->endSection() ?>
