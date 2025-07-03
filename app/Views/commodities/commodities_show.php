<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <div>
                <a href="<?= base_url('commodity-boards/' . $production['id'] . '/edit') ?>" class="btn btn-primary me-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="<?= base_url('commodity-boards') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Production Details -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-seedling text-success"></i> Production Details
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Commodity</label>
                                        <div>
                                            <span class="badge bg-secondary fs-6"><?= esc($production['commodity_code']) ?></span>
                                            <div class="mt-1">
                                                <strong><?= esc($production['commodity_name']) ?></strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted">Item/Product</label>
                                        <div>
                                            <strong><?= esc($production['item']) ?></strong>
                                        </div>
                                    </div>

                                    <?php if (!empty($production['description'])): ?>
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Description</label>
                                        <div>
                                            <?= nl2br(esc($production['description'])) ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Production Period</label>
                                        <div>
                                            <i class="fas fa-calendar text-info"></i>
                                            <strong><?= date('M d, Y', strtotime($production['date_from'])) ?></strong>
                                            to
                                            <strong><?= date('M d, Y', strtotime($production['date_to'])) ?></strong>
                                        </div>
                                        <small class="text-muted">
                                            Duration: <?= abs(strtotime($production['date_to']) - strtotime($production['date_from'])) / (60*60*24) + 1 ?> days
                                        </small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted">Quantity</label>
                                        <div>
                                            <span class="fs-4 text-primary">
                                                <strong><?= number_format($production['quantity'], 2) ?></strong>
                                            </span>
                                            <?php if (!empty($production['unit_of_measurement'])): ?>
                                                <span class="text-muted"><?= esc($production['unit_of_measurement']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted">Export Status</label>
                                        <div>
                                            <?php if ($production['is_exported']): ?>
                                                <span class="badge bg-success fs-6">
                                                    <i class="fas fa-shipping-fast"></i> For Export
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-info fs-6">
                                                    <i class="fas fa-home"></i> Domestic Market
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Audit Information -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle text-info"></i> Record Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted">Record ID</label>
                                <div>
                                    <span class="badge bg-dark">#<?= $production['id'] ?></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted">Created</label>
                                <div>
                                    <i class="fas fa-clock text-muted"></i>
                                    <?= date('M d, Y \a\t g:i A', strtotime($production['created_at'])) ?>
                                </div>
                                <?php if (!empty($production['created_by_name'])): ?>
                                    <small class="text-muted">
                                        By: <?= esc($production['created_by_name']) ?>
                                    </small>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($production['updated_at']) && $production['updated_at'] != $production['created_at']): ?>
                            <div class="mb-3">
                                <label class="form-label text-muted">Last Updated</label>
                                <div>
                                    <i class="fas fa-edit text-muted"></i>
                                    <?= date('M d, Y \a\t g:i A', strtotime($production['updated_at'])) ?>
                                </div>
                                <?php if (!empty($production['updated_by_name'])): ?>
                                    <small class="text-muted">
                                        By: <?= esc($production['updated_by_name']) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <hr>

                            <div class="d-grid gap-2">
                                <a href="<?= base_url('commodity-boards/' . $production['id'] . '/edit') ?>" 
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit Record
                                </a>
                                <a href="<?= base_url('commodity-boards/' . $production['id'] . '/delete') ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this production record? This action cannot be undone.')">
                                    <i class="fas fa-trash"></i> Delete Record
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-chart-pie text-success"></i> Quick Stats
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h5 class="text-primary mb-0"><?= number_format($production['quantity'], 0) ?></h5>
                                        <small class="text-muted">Quantity</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h5 class="text-success mb-0">
                                        <?= abs(strtotime($production['date_to']) - strtotime($production['date_from'])) / (60*60*24) + 1 ?>
                                    </h5>
                                    <small class="text-muted">Days</small>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="text-center">
                                <div class="mb-2">
                                    <span class="text-muted">Daily Average:</span>
                                </div>
                                <h6 class="text-info">
                                    <?php 
                                    $days = abs(strtotime($production['date_to']) - strtotime($production['date_from'])) / (60*60*24) + 1;
                                    echo number_format($production['quantity'] / $days, 2);
                                    ?>
                                    <?php if (!empty($production['unit_of_measurement'])): ?>
                                        <small><?= esc($production['unit_of_measurement']) ?>/day</small>
                                    <?php endif; ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
