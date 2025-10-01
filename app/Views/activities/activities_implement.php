<?php
// app/Views/activities/activities_implement.php
?>
<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="mb-3">
        <a href="<?= base_url('activities/' . $activity['id']) ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Activity Details
        </a>
    </div>

    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Implement Activity: <?= esc($activity['activity_title']) ?></h5>
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
                                            <p class="mb-1"><strong>Title:</strong> <?= esc($activity['activity_title']) ?></p>
                                            <p class="mb-1"><strong>Type:</strong> <span class="badge bg-info"><?= ucfirst(esc($activity['type'])) ?></span></p>
                                        </div>
                                        <div class="col-md-4">
                                            
                                            <p class="mb-1"><strong>Location:</strong> <?= esc($activity['location'] ?? 'N/A') ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Supervisor:</strong> <?= esc($activity['supervisor_name'] ?? 'N/A') ?></p>
                                            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-<?= $activity['status'] === 'approved' ? 'success' : 'warning' ?>"><?= ucfirst(esc($activity['status'])) ?></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Implementation Form Based on Activity Type -->
                    <?php if ($activity['type'] === 'documents'): ?>
                        <?= $this->include('activities/implementations/documents_implementation') ?>
                    <?php elseif ($activity['type'] === 'trainings'): ?>
                        <?= $this->include('activities/implementations/trainings_implementation') ?>
                    <?php elseif ($activity['type'] === 'inputs'): ?>
                        <?= $this->include('activities/implementations/inputs_implementation') ?>
                    <?php elseif ($activity['type'] === 'infrastructures'): ?>
                        <?= $this->include('activities/implementations/infrastructures_implementation') ?>
                    <?php elseif ($activity['type'] === 'meetings'): ?>
                        <?= $this->include('activities/implementations/meetings_implementation') ?>
                    <?php elseif ($activity['type'] === 'agreements'): ?>
                        <?= $this->include('activities/implementations/agreements_implementation') ?>
                    <?php elseif ($activity['type'] === 'outputs'): ?>
                        <?= $this->include('activities/implementations/outputs_implementation') ?>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Implementation form for activity type "<?= esc($activity['type']) ?>" is not available yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
