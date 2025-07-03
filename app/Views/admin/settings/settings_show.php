<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>View Setting: <?= esc($setting['settings_name']) ?></h5>
                <div>
                    <a href="<?= base_url('admin/org-settings/' . $setting['id'] . '/edit') ?>" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="<?= base_url('admin/org-settings') ?>" class="btn btn-secondary btn-sm ms-2">
                        <i class="fas fa-arrow-left"></i> Back to Settings
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Setting Details</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 30%">ID</th>
                                <td><?= $setting['id'] ?></td>
                            </tr>
                            <tr>
                                <th>Code</th>
                                <td><?= esc($setting['settings_code']) ?></td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td><?= esc($setting['settings_name']) ?></td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td><?= date('Y-m-d H:i:s', strtotime($setting['created_at'])) ?></td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td><?= date('Y-m-d H:i:s', strtotime($setting['updated_at'])) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h6 class="text-muted">Settings Content</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <?php if (!empty($setting['settings_formatted']) && is_array($setting['settings_formatted'])): ?>
                                    <!-- If it's JSON, display as formatted JSON -->
                                    <pre class="mb-0"><code><?= json_encode($setting['settings_formatted'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?></code></pre>
                                <?php else: ?>
                                    <!-- Otherwise show as plain text -->
                                    <pre class="mb-0"><code><?= esc($setting['settings']) ?></code></pre>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 