<?php
// app/Views/sme/sme_staff_index.php
?>
<?= $this->extend('templates/system_template') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('smes') ?>">SMEs</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('smes/' . $sme['id']) ?>"><?= esc($sme['sme_name']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Staff</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Staff for <?= esc($sme['sme_name']) ?></h3>
                    <div>
                        <a href="<?= base_url('smes/' . $sme['id']) ?>" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Back to SME
                        </a>
                        <a href="<?= base_url('smes/staff/' . $sme['id'] . '/new') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Staff
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="staffTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Designation</th>
                <th>Contacts</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($staff as $index => $s): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($s['fname'] . ' ' . $s['lname']) ?></td>
                    <td><?= ucfirst(esc($s['gender'] ?? 'Not specified')) ?></td>
                    <td><?= !empty($s['dobirth']) ? date('d M Y', strtotime($s['dobirth'])) : 'Not specified' ?></td>
                    <td><?= esc($s['designation']) ?></td>
                    <td><?= esc($s['contacts']) ?></td>
                    <td><span class="badge bg-<?= $s['status'] == 'active' ? 'success' : 'secondary' ?>"><?= esc($s['status']) ?></span></td>
                    <td>
                        <a href="<?= base_url('smes/staff/' . $sme['id'] . '/' . $s['id'] . '/edit') ?>" class="btn btn-outline-warning" title="Edit Staff" style="margin-right: 5px;">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <a href="<?= base_url('smes/staff/' . $sme['id'] . '/' . $s['id'] . '/delete') ?>" class="btn btn-outline-danger" title="Delete Staff" onclick="return confirm('Are you sure you want to delete this staff member?')">
                            <i class="fas fa-trash me-1"></i> Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (session()->getFlashdata('success')): ?>
            toastr.success('<?= session()->getFlashdata('success') ?>');
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            toastr.error('<?= session()->getFlashdata('error') ?>');
        <?php endif; ?>
    });

$(function(){
    $('#staffTable').DataTable({
        // Configure the counter column to work with pagination
        "columnDefs": [{
            "searchable": false,
            "orderable": false,
            "targets": 0
        }],
        "order": [[1, 'asc']], // Sort by Name (second column) by default
        "columnDefs": [
            { "width": "5%", "targets": 0 }, // #
            { "width": "20%", "targets": 1 }, // Name
            { "width": "10%", "targets": 2 }, // Gender
            { "width": "12%", "targets": 3 }, // Date of Birth
            { "width": "15%", "targets": 4 }, // Designation
            { "width": "15%", "targets": 5 }, // Contacts
            { "width": "8%", "targets": 6 }, // Status
            { "width": "15%", "targets": 7 }  // Action
        ]
    });

    // Update counter column numbers when drawing the table
    $('#staffTable').on('draw.dt', function() {
        let info = $('#staffTable').DataTable().page.info();
        $('#staffTable tbody tr').each(function(index) {
            // Update the counter column with the correct number
            // (page number * page length) + row index + 1
            $(this).find('td:first').html(info.start + index + 1);
        });
    });
});
</script>
<?= $this->endSection() ?>