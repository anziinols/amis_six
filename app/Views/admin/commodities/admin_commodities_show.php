<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $title ?></h5>
            <div>
                <a href="<?= base_url('admin/commodities/' . $commodity['id'] . '/edit') ?>" class="btn btn-primary me-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="<?= base_url('admin/commodities') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Commodities
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
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Commodity Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Commodity Code:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-secondary fs-6"><?= esc($commodity['commodity_code']) ?></span>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Commodity Name:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <?= esc($commodity['commodity_name']) ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Color Code:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <?php if (!empty($commodity['commodity_color_code'])): ?>
                                        <div class="d-flex align-items-center">
                                            <div class="color-preview me-3" style="width: 30px; height: 30px; background-color: <?= esc($commodity['commodity_color_code']) ?>; border: 1px solid #ccc; border-radius: 5px;"></div>
                                            <span class="badge bg-light text-dark"><?= esc($commodity['commodity_color_code']) ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">No color assigned</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Icon:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <?php if (!empty($commodity['commodity_icon'])): ?>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= base_url($commodity['commodity_icon']) ?>" alt="<?= esc($commodity['commodity_name']) ?>" class="me-3" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                                            
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">No icon uploaded</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Visual Preview</h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="p-4" style="background-color: <?= esc($commodity['commodity_color_code'] ?: '#f8f9fa') ?>; border-radius: 10px; margin-bottom: 20px;">
                                <?php if (!empty($commodity['commodity_icon'])): ?>
                                    <img src="<?= base_url($commodity['commodity_icon']) ?>" alt="<?= esc($commodity['commodity_name']) ?>" style="width: 80px; height: 80px; object-fit: cover; border-radius: 10px; border: 2px solid rgba(255,255,255,0.3); box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                <?php else: ?>
                                    <div style="width: 80px; height: 80px; background-color: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                        <i class="fas fa-image" style="font-size: 2rem; color: rgba(255,255,255,0.7);"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="mt-3">
                                    <h5 class="text-white" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.5);"><?= esc($commodity['commodity_name']) ?></h5>
                                    <small class="text-white-50"><?= esc($commodity['commodity_code']) ?></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">Audit Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted">
                                    <strong>Created:</strong><br>
                                    <?= date('M d, Y H:i', strtotime($commodity['created_at'])) ?><br>
                                    <strong>By:</strong> <?= esc($commodity['created_by_name'] ?: 'System') ?>
                                </small>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted">
                                    <strong>Last Updated:</strong><br>
                                    <?= date('M d, Y H:i', strtotime($commodity['updated_at'])) ?><br>
                                    <strong>By:</strong> <?= esc($commodity['updated_by_name'] ?: 'System') ?>
                                </small>
                            </div>
                            <?php if (!empty($commodity['deleted_at'])): ?>
                                <div class="alert alert-warning">
                                    <small>
                                        <strong>Deleted:</strong><br>
                                        <?= date('M d, Y H:i', strtotime($commodity['deleted_at'])) ?><br>
                                        <strong>By:</strong> <?= esc($commodity['deleted_by_name'] ?: 'System') ?>
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
