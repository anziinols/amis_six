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

    .status-draft {
        color: #495057;
        background-color: #e9ecef;
        border-color: #ced4da;
    }

    .status-in-progress {
        color: #084298;
        background-color: #cfe2ff;
        border-color: #b6d4fe;
    }

    .status-completed {
        color: #0f5132;
        background-color: #d1e7dd;
        border-color: #badbcc;
    }

    .status-on-hold {
        color: #664d03;
        background-color: #fff3cd;
        border-color: #ffecb5;
    }

    .status-cancelled {
        color: #842029;
        background-color: #f8d7da;
        border-color: #f5c2c7;
    }

    .status-default {
        color: #41464b;
        background-color: #e2e3e5;
        border-color: #d3d6d8;
    }
</style>
<div class="mb-3">
    <a href="<?= base_url('workplans/' . $workplan['id']) ?>" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left"></i> Back to Workplan Details
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Workplan Details</h5>
    </div>
    <div class="card-body">
        <?= form_open('workplans/update/' . $workplan['id'], ['method' => 'post']) ?>
            <?= csrf_field() ?>

            <?= $this->include('workplans/workplan_form') ?>

        <?= form_close() ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Add Select2 CSS fix for Bootstrap 5 -->
<style>
    .select2-container--default .select2-selection--single {
        height: calc(2.25rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #495057;
        line-height: 1.5;
        padding-left: 0;
        padding-right: 0;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
    }

    .select2-container--default .select2-selection--single:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #80bdff;
    }
</style>

<!-- Select2 Initialization and Modal Script -->
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            width: '100%', // Important for proper sizing
            dropdownAutoWidth: true
        });



        // AJAX Setup for CSRF
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '<?= csrf_hash() ?>' // Use the hash value directly
            },
            data: {
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>' // Add CSRF token to data as well
            }
        });
    });
</script>
<?= $this->endSection() ?>