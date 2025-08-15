<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><?= $title ?></h5>
                <small class="text-muted">Performance Indicator: <?= esc($indicator['item']) ?></small>
                <br><small class="text-muted">KRA: <?= esc($kra['item']) ?></small>
            </div>
            <div class="btn-group">
                <a href="<?= base_url('performance-output/kra/' . $kra['id'] . '/indicators') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Indicators
                </a>
                <a href="<?= base_url('performance-output/indicators/' . $indicator['id'] . '/outputs/new') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Performance Output
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Output</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Description</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($outputs)): ?>
                            <?php foreach ($outputs as $key => $output): ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td>
                                        <strong><?= esc($output['output']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?= esc($output['quantity']) ?></span>
                                    </td>
                                    <td>
                                        <span class="text-muted"><?= esc($output['unit_of_measurement']) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($output['description']): ?>
                                            <?= esc(substr($output['description'], 0, 100)) ?><?= strlen($output['description']) > 100 ? '...' : '' ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?= esc($output['user_id']) ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = match($output['status']) {
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            'pending' => 'bg-warning',
                                            default => 'bg-secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $statusClass ?>"><?= ucfirst(esc($output['status'])) ?></span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($output['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('performance-outputs/' . $output['id']) ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('performance-outputs/' . $output['id'] . '/edit') ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('performance-outputs/' . $output['id'] . '/delete') ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this performance output?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No Performance Outputs found for this Performance Indicator.</p>
                                        <a href="<?= base_url('performance-output/indicators/' . $indicator['id'] . '/outputs/new') ?>" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Create First Performance Output
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
