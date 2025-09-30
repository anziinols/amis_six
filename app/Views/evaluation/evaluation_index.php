<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2><?= esc($title) ?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Evaluation</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Workplans Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-clipboard-list"></i> Workplans for Evaluation
                <span class="badge bg-info ms-2"><?= count($workplans) ?></span>
            </h5>
        </div>
        <div class="card-body">
            <?php if (!empty($workplans)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="workplansTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 25%;">Title</th>
                                <th style="width: 15%;">Branch</th>
                                <th style="width: 15%;">Date Range</th>
                                <th style="width: 10%;">Activities</th>
                                <th style="width: 10%;">Status</th>
                                <th style="width: 10%;">Created By</th>
                                <th style="width: 10%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($workplans as $index => $workplan): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <strong><?= esc($workplan['title']) ?></strong>
                                        <?php if (!empty($workplan['description'])): ?>
                                            <br>
                                            <small class="text-muted">
                                                <?= esc(substr($workplan['description'], 0, 100)) ?>
                                                <?= strlen($workplan['description']) > 100 ? '...' : '' ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($workplan['branch_name'] ?? 'N/A') ?></td>
                                    <td>
                                        <?php if (!empty($workplan['start_date']) && !empty($workplan['end_date'])): ?>
                                            <small>
                                                <?= date('M d, Y', strtotime($workplan['start_date'])) ?><br>
                                                to<br>
                                                <?= date('M d, Y', strtotime($workplan['end_date'])) ?>
                                            </small>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?= $workplan['activities_count'] ?? 0 ?> Activities
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $statusColors = [
                                            'draft' => 'secondary',
                                            'active' => 'success',
                                            'completed' => 'primary',
                                            'archived' => 'dark'
                                        ];
                                        $statusColor = $statusColors[$workplan['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $statusColor ?>">
                                            <?= ucfirst(esc($workplan['status'])) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($workplan['created_by_name'] ?? 'N/A') ?></td>
                                    <td>
                                        <a href="<?= base_url('evaluation/workplan/' . $workplan['id'] . '/activities') ?>" 
                                           class="btn btn-sm btn-primary" 
                                           title="Open Workplan">
                                            <i class="fas fa-folder-open"></i> Open Workplan
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No workplans available for evaluation at this time.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize DataTables if the table exists and has data
        if ($('#workplansTable').length > 0 && $('#workplansTable tbody tr').length > 0) {
            try {
                $('#workplansTable').DataTable({
                    responsive: true,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    columnDefs: [
                        { orderable: false, targets: -1 } // Disable sorting on the actions column
                    ],
                    order: [[1, 'asc']] // Default sort by title
                });
            } catch (e) {
                console.error("DataTables initialization error:", e);
            }
        }
    });
</script>
<?= $this->endSection() ?>

