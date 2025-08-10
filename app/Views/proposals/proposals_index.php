<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Proposals</h5>
                    <a href="<?= base_url('proposals/new') ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Create New Proposal
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($proposals)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No proposals found.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="proposalsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Workplan</th>
                                        <th>Activity</th>
                                        <th>Action Officer</th>
                                        <th>Location</th>
                                        <th>Date Range</th>
                                        <th>Cost</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $counter = 1; ?>
                                    <?php foreach ($proposals as $proposal): ?>
                                        <tr>
                                            <td><?= $counter++ ?></td>
                                            <td><?= esc($proposal['workplan_title']) ?></td>
                                            <td>
                                                <?= esc($proposal['activity_title']) ?>
                                                <span class="badge bg-info"><?= ucfirst($proposal['activity_type']) ?></span>
                                            </td>
                                            <td>
                                                <?= !empty($proposal['action_officer_name']) ? esc($proposal['action_officer_name']) : '<span class="text-muted">Not Assigned</span>' ?>
                                            </td>
                                            <td>
                                                <?= esc($proposal['location']) ?><br>
                                                <small class="text-muted"><?= esc($proposal['district_name']) ?>, <?= esc($proposal['province_name']) ?></small>
                                            </td>
                                            <td>
                                                <?= date('d M Y', strtotime($proposal['date_start'])) ?> -
                                                <?= date('d M Y', strtotime($proposal['date_end'])) ?>
                                            </td>
                                            <td>
                                                <?= !empty($proposal['total_cost']) ? CURRENCY_SYMBOL . ' ' . number_format($proposal['total_cost'], 2) : 'N/A' ?>
                                            </td>
                                            <td>
                                                <?php
                                                $statusBadgeClass = 'bg-secondary';
                                                switch ($proposal['status']) {
                                                    case 'pending':
                                                        $statusBadgeClass = 'bg-warning text-dark';
                                                        break;
                                                    case 'submitted':
                                                        $statusBadgeClass = 'bg-info text-dark';
                                                        break;
                                                    case 'approved':
                                                        $statusBadgeClass = 'bg-success';
                                                        break;
                                                    case 'rated':
                                                        $statusBadgeClass = 'bg-primary';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?= $statusBadgeClass ?>"><?= ucfirst($proposal['status']) ?></span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('proposals/' . $proposal['id']) ?>" class="btn btn-info btn-sm" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($proposal['status'] === 'pending'): ?>
                                                <a href="<?= base_url('proposals/edit/' . $proposal['id']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php endif; ?>
                                                <?php if ($proposal['status'] === 'submitted'): ?>
                                                <a href="<?= base_url('proposals/supervise/' . $proposal['id']) ?>" class="btn btn-primary btn-sm" title="Supervise">
                                                   Supervise <i class="fas fa-clipboard-check"></i>
                                                </a>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#proposalsTable').DataTable({
            "responsive": true,
            "order": [[0, 'asc']],
            "pageLength": 25,
            "columnDefs": [
                { "orderable": false, "targets": [8] }
            ]
        });
    });
</script>
<?= $this->endSection() ?>
