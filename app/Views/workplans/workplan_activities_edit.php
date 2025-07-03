<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="mb-3">
    <a href="<?= base_url('workplans/' . $workplan['id'] . '/activities/' . $activity['id']) ?>" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left"></i> Back to Activity Details
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Activity: <?= esc($activity['title']) ?></h5>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?= form_open_multipart('workplans/' . $workplan['id'] . '/activities/' . $activity['id'] . '/update', ['method' => 'post']) ?>
            <?= csrf_field() ?>

            <?= $this->include('workplans/workplan_activities_form') ?>

        <?= form_close() ?>
    </div>
</div>
<?= $this->endSection() ?>
