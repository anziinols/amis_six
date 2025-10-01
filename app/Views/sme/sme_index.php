<?php
// app/Views/sme/sme_index.php
?>
<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">SMEs</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">SME List</h3>
                    <a href="<?= base_url('smes/new') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New SME
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="smeTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>SME Name</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($smes as $index => $sme): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($sme['sme_name']) ?></td>
                            <td>
                                <?php
                                $location = [];
                                if (!empty($sme['province_name'])) $location[] = esc($sme['province_name']);
                                if (!empty($sme['district_name'])) $location[] = esc($sme['district_name']);
                                if (!empty($sme['llg_name'])) $location[] = esc($sme['llg_name']);
                                echo implode(' / ', $location) ?: 'No location set';
                                ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $sme['status'] === 'active' ? 'success' : 'secondary' ?>"><?= ucfirst($sme['status']) ?></span>
                            </td>
                            <td>
                                <a href="<?= base_url('smes/' . $sme['id']) ?>" class="btn btn-outline-primary" title="View SME" style="margin-right: 5px;">
                                    <i class="fas fa-eye me-1"></i> View
                                </a>
                                <a href="<?= base_url('smes/' . $sme['id'] . '/edit') ?>" class="btn btn-outline-warning" title="Edit SME" style="margin-right: 5px;">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#statusModal" data-id="<?= $sme['id'] ?>" data-status="<?= $sme['status'] ?>" title="Toggle Status" style="margin-right: 5px;">
                                    <i class="fas fa-sync me-1"></i> Status
                                </button>
                                <a href="<?= base_url('smes/staff/' . $sme['id']) ?>" class="btn btn-outline-success" title="View Staff">
                                    <i class="fas fa-users me-1"></i> Staff
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

<!-- Status Toggle Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="statusForm" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Toggle SME Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="smeIdField">
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Status Remarks</label>
                        <textarea name="status_remarks" id="remarks" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
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

        <?php if (session()->getFlashdata('errors')): ?>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                toastr.error('<?= esc($error) ?>');
            <?php endforeach; ?>
        <?php endif; ?>
    });

    $(function(){
        $('#smeTable').DataTable({
            // Configure the counter column to work with pagination
            "columnDefs": [
                {
                    "searchable": false,
                    "orderable": false,
                    "targets": 0,
                    "width": "5%"
                },
                { "width": "30%", "targets": 1 }, // SME Name
                { "width": "30%", "targets": 2 }, // Location
                { "width": "10%", "targets": 3 }, // Status
                { "width": "25%", "targets": 4 }  // Action
            ],
            "order": [[1, 'asc']], // Sort by SME Name (second column) by default
        });

        // Update counter column numbers when drawing the table
        $('#smeTable').on('draw.dt', function() {
            let info = $('#smeTable').DataTable().page.info();
            $('#smeTable tbody tr').each(function(index) {
                // Update the counter column with the correct number
                // (page number * page length) + row index + 1
                $(this).find('td:first').html(info.start + index + 1);
            });
        });

        // Populate modal form action dynamically
        $('#statusModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const smeId  = button.data('id');
            $(this).find('#smeIdField').val(smeId);
            $(this).find('form').attr('action', '<?= base_url('smes') ?>/' + smeId + '/toggle-status');
        });
    });

    /*
    ===============================================
    AJAX Example for toggling status (using jQuery)
    ===============================================
    $('#statusForm').on('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res){
                if(res.status === 'success'){
                    toastr.success(res.message);
                    location.reload();
                } else {
                    toastr.error(res.message || 'Failed');
                }
            },
            error: function(xhr){
                toastr.error('Error ' + xhr.status);
            }
        });
    });
    */
</script>
<?= $this->endSection() ?>