<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h4 class="card-title">Welcome, <?= esc($user['name']) ?>!</h4>
                            <p class="card-text">
                                <span class="badge bg-light text-primary"><?= ucfirst(esc($user['role'])) ?></span>
                                <?php if(isset($userData['branch_id']) && $userData['branch_id']): ?>
                                    <span class="badge bg-light text-primary">
                                        <?= isset($myWorkplans[0]['branch_name']) ? esc($myWorkplans[0]['branch_name']) : 'Branch' ?>
                                    </span>
                                <?php endif; ?>
                            </p>
                            <p class="card-text">Here's a summary of your activities and tasks. Your personalized dashboard shows what needs your attention.</p>
                        </div>
                        <div class="col-md-3 text-center d-none d-md-block">
                            <i class="fas fa-tachometer-alt fa-4x text-white-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">My Workplans</h5>
                    <h2 class="card-text text-primary"><?= count($myWorkplans) ?></h2>
                    <small class="text-muted">Active workplans</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pending Tasks</h5>
                    <h2 class="card-text text-warning"><?= $pendingTasks ?></h2>
                    <small class="text-muted">Tasks awaiting your attention</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Completed Tasks</h5>
                    <h2 class="card-text text-success"><?= $completedTasks ?></h2>
                    <small class="text-muted">Tasks completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Upcoming Meetings</h5>
                    <h2 class="card-text text-info"><?= count($upcomingMeetings) ?></h2>
                    <small class="text-muted">Scheduled meetings</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Workplans and Meetings -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">My Workplans</h5>
                    <a href="<?= base_url('workplans') ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if(empty($myWorkplans)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No workplans found.
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach($myWorkplans as $workplan): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= esc($workplan['title']) ?></h6>
                                        <small class="text-muted">
                                            <?php
                                                $status = $workplan['status'] ?? 'draft';
                                                $statusClass = [
                                                    'draft' => 'secondary',
                                                    'in_progress' => 'primary',
                                                    'completed' => 'success',
                                                    'on_hold' => 'warning',
                                                    'cancelled' => 'danger'
                                                ];
                                                $class = $statusClass[$status] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $class ?>"><?= ucfirst(str_replace('_', ' ', $status)) ?></span>
                                        </small>
                                    </div>
                                    <p class="mb-1 text-truncate"><?= esc($workplan['description'] ?? 'No description') ?></p>
                                    <small>
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        <?= date('M d, Y', strtotime($workplan['start_date'])) ?> -
                                        <?= date('M d, Y', strtotime($workplan['end_date'])) ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Upcoming Meetings</h5>
                    <a href="<?= base_url('meetings') ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if(empty($upcomingMeetings)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No upcoming meetings.
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach($upcomingMeetings as $meeting): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= esc($meeting['title']) ?></h6>
                                        <small class="text-muted">
                                            <?= date('M d, g:i a', strtotime($meeting['meeting_date'])) ?>
                                        </small>
                                    </div>
                                    <p class="mb-1 text-truncate"><?= esc($meeting['description'] ?? 'No description') ?></p>
                                    <small>
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?= esc($meeting['location'] ?? 'Location not specified') ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Documents</h5>
                    <a href="<?= base_url('documents') ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if(empty($recentDocuments)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No recent documents.
                        </div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach($recentDocuments as $document): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= esc($document['title']) ?></h6>
                                        <small class="text-muted">
                                            <?= date('M d, Y', strtotime($document['created_at'])) ?>
                                        </small>
                                    </div>
                                    <p class="mb-1 text-truncate"><?= esc($document['description'] ?? 'No description') ?></p>
                                    <small>
                                        <i class="fas fa-file me-1"></i>
                                        <?= esc($document['classification'] ?? 'Unclassified') ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>