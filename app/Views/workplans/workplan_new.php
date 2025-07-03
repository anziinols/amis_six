<?php
$csrf_token_name = csrf_token();
$csrf_hash = csrf_hash();
?>
<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="mb-3">
    <a href="<?= base_url('workplans') ?>" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left"></i> Back to Workplan List
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Create New Workplan</h5>
            </div>
    <?= form_open('workplans/create', ['id' => 'workplan-form']) ?>
                <?= csrf_field() ?>
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

                <?= $this->include('workplans/workplan_form') ?>



        </div> <!-- End Card Body -->
        <!-- Card Footer is now included via _workplan_form -->
            <?= form_close() ?>
</div>




<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Include base Select2 styles -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    /* Additional styling improvements from previous steps */
    .card { box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
    .card-header { background-color: #fff; border-bottom: 1px solid rgba(0, 0, 0, 0.125); }
    .form-label { font-weight: 500; margin-bottom: 0.5rem; }
    .btn-outline-primary { border-width: 1px; }
    .btn-outline-primary:hover { background-color: #0d6efd; color: #fff; }
    .table thead th { background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; }
    .table-sm td, .table-sm th { padding: 0.4rem; }
    .select2-container--bootstrap-5 .select2-selection { min-height: calc(1.5em + 0.75rem + 2px); padding: 0.375rem 0.75rem; font-size: 1rem; }
    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow { height: calc(1.5em + 0.75rem); } /* Adjust arrow height */
</style>

<script>
$(document).ready(function() {
    // Initialize Select2 on all select elements
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
});
</script>
<?= $this->endSection() ?>