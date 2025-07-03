<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0"><?= $title ?></h5>
                        <small class="text-muted">
                            <span class="badge bg-secondary"><?= esc($user_commodity['commodity_code']) ?></span>
                            Managing production records for <?= esc($user_commodity['commodity_name']) ?>
                        </small>
                    </div>
                    <a href="<?= base_url('commodity-boards/new') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Production Record
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Total Records</h6>
                                            <h3><?= count($productions) ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-chart-bar fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Exported</h6>
                                            <h3><?= count(array_filter($productions, function($p) { return $p['is_exported'] == 1; })) ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-shipping-fast fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Domestic</h6>
                                            <h3><?= count(array_filter($productions, function($p) { return $p['is_exported'] == 0; })) ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-home fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">This Month</h6>
                                            <h3><?= count(array_filter($productions, function($p) { return date('Y-m', strtotime($p['created_at'])) == date('Y-m'); })) ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-calendar fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Production Summary -->
    <?php if (!empty($summary)): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Production Summary by Commodity</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Commodity</th>
                                    <th>Code</th>
                                    <th>Unit</th>
                                    <th>Total Quantity</th>
                                    <th>Records</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 1; ?>
                                <?php foreach ($summary as $item): ?>
                                    <tr>
                                        <td><?= $counter++ ?></td>
                                        <td><?= esc($item['commodity_name']) ?></td>
                                        <td>
                                            <span class="badge bg-secondary"><?= esc($item['commodity_code']) ?></span>
                                        </td>
                                        <td><?= esc($item['unit_of_measurement']) ?></td>
                                        <td><strong><?= number_format($item['total_quantity'], 2) ?></strong></td>
                                        <td><?= $item['record_count'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Recent Production Records -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Recent Production Records</h6>
            <div>
                <a href="<?= base_url('commodity-boards/new') ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Add New
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

            <?php if (empty($productions)): ?>
                <div class="text-center py-4">
                    <i class="fas fa-seedling fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Production Records Found</h5>
                    <p class="text-muted">Start by adding your first production record.</p>
                    <a href="<?= base_url('commodity-boards/new') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Production Record
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Commodity</th>
                                <th>Item</th>
                                <th>Period</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Export Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $counter = 1; ?>
                            <?php foreach ($productions as $production): ?>
                                <tr>
                                    <td><?= $counter++ ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?= esc($production['commodity_code']) ?></span>
                                        <br><small><?= esc($production['commodity_name']) ?></small>
                                    </td>
                                    <td>
                                        <strong><?= esc($production['item']) ?></strong>
                                        <?php if (!empty($production['description'])): ?>
                                            <br><small class="text-muted"><?= esc($production['description']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small>
                                            <?= date('M d, Y', strtotime($production['date_from'])) ?><br>
                                            to <?= date('M d, Y', strtotime($production['date_to'])) ?>
                                        </small>
                                    </td>
                                    <td><strong><?= number_format($production['quantity'], 2) ?></strong></td>
                                    <td><?= esc($production['unit_of_measurement']) ?></td>
                                    <td>
                                        <?php if ($production['is_exported']): ?>
                                            <span class="badge bg-success">Exported</span>
                                        <?php else: ?>
                                            <span class="badge bg-info">Domestic</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small>
                                            <?= date('M d, Y', strtotime($production['created_at'])) ?><br>
                                            <span class="text-muted">By: <?= esc($production['created_by_name']) ?></span>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= base_url('commodity-boards/' . $production['id']) ?>"
                                               class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?= base_url('commodity-boards/' . $production['id'] . '/edit') ?>"
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('commodity-boards/' . $production['id'] . '/delete') ?>"
                                               class="btn btn-sm btn-danger" title="Delete"
                                               onclick="return confirm('Are you sure you want to delete this production record?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
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
<?= $this->endSection() ?>
