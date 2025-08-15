<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0"><?= $title ?></h5>
                <small class="text-muted">Performance Period: <?= esc($performance_period['title']) ?></small>
            </div>
            <div class="btn-group">
                <a href="<?= base_url('performance-output') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Periods
                </a>
                <a href="<?= base_url('performance-output/' . $performance_period['id'] . '/kra/new') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New KRA
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
                            <th>Code</th>
                            <th>KRA Item</th>
                            <th>Description</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($kras)): ?>
                            <?php foreach ($kras as $key => $kra): ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td>
                                        <?php if ($kra['code']): ?>
                                            <span class="badge bg-secondary"><?= esc($kra['code']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= esc($kra['item']) ?></strong>
                                    </td>
                                    <td>
                                        <?php if ($kra['description']): ?>
                                            <?= esc(substr($kra['description'], 0, 100)) ?><?= strlen($kra['description']) > 100 ? '...' : '' ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($kra['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('performance-indicators-kra/' . $kra['id']) ?>" class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('performance-output/kra/' . $kra['id'] . '/indicators') ?>" class="btn btn-sm btn-outline-success" title="View Performance Indicators">
                                                <i class="fas fa-list"></i>
                                            </a>
                                            <a href="<?= base_url('performance-indicators-kra/' . $kra['id'] . '/edit') ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('performance-indicators-kra/' . $kra['id'] . '/delete') ?>" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this KRA?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No KRAs found for this performance period.</p>
                                        <a href="<?= base_url('performance-output/' . $performance_period['id'] . '/kra/new') ?>" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Create First KRA
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
