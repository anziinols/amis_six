<?php
// app/Views/sme/sme_staff_index.php
?>
<?= $this->extend('templates/system_template') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Staff List for SME: <?= esc($sme['sme_name']) ?></h4>
            <div>
                <a href="<?= base_url('smes/staff/' . $sme['id'] . '/new') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Staff
                </a>
                <a href="<?= base_url('smes/' . $sme['id']) ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to SME
                </a>
            </div>
        </div>
        <div class="card-body">
    <table id="staffTable" class="table table-striped table-bordered">
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
                        <a href="<?= base_url('smes/staff/' . $sme['id'] . '/' . $s['id'] . '/edit') ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="<?= base_url('smes/staff/' . $sme['id'] . '/' . $s['id'] . '/delete') ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
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