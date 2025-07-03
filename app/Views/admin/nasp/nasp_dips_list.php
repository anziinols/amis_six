<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans') ?>">NASP Plans</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/nasp-plans/' . $plan['id'] . '/apas') ?>"><?= esc($plan['title']) ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">DIPs in <?= esc($apa['title']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Display flash messages -->
    <?php if (session()->has('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">DIPs in <?= esc($apa['title']) ?> (<?= esc($apa['code']) ?>)</h3>
                    <div class="card-tools">
                        <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/new') ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New DIP
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <strong>NASP Plan:</strong> <?= esc($plan['title']) ?> (<?= esc($plan['code']) ?>)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dipsTable">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($dips)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No DIPs found for this APA</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($dips as $dip): ?>
                                        <tr>
                                            <td><?= esc($dip['code']) ?></td>
                                            <td><?= esc($dip['title']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $dip['nasp_status'] == 1 ? 'success' : 'danger' ?>">
                                                    <?= $dip['nasp_status'] == 1 ? 'Active' : 'Inactive' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="DIP actions">
                                                    <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id']) ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/specific-areas') ?>" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-list"></i> Manage Specific Area
                                                    </a>
                                                    <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/edit') ?>" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="<?= base_url('admin/nasp-plans/apas/' . $apa['id'] . '/dips/' . $dip['id'] . '/toggle-status') ?>" class="btn btn-<?= $dip['nasp_status'] == 1 ? 'danger' : 'success' ?> btn-sm">
                                                        <i class="fas fa-<?= $dip['nasp_status'] == 1 ? 'ban' : 'check-circle' ?>"></i>
                                                        <?= $dip['nasp_status'] == 1 ? 'Deactivate' : 'Activate' ?>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
