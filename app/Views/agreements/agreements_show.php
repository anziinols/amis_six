<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Agreement Details: <?= esc($agreement['title']) ?></h5>
        <div>
            <a href="<?= base_url('agreements/edit/' . $agreement['id']) ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="<?= base_url('agreements') ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>Title:</strong> <?= esc($agreement['title']) ?></p>
                <p><strong>Branch:</strong> <?= esc($agreement['branch_name'] ?? 'N/A') ?></p>
                <p><strong>Agreement Type:</strong> <?= esc(ucfirst($agreement['agreement_type'] ?? 'N/A')) ?></p>
                 <p><strong>Status:</strong> <span class="badge bg-<?= $agreement['status'] == 'active' ? 'success' : ($agreement['status'] == 'draft' ? 'secondary' : 'warning') ?>"><?= esc(ucfirst($agreement['status'])) ?></span></p>
             </div>
            <div class="col-md-6">
                <p><strong>Effective Date:</strong> <?= esc(date('F j, Y', strtotime($agreement['effective_date']))) ?></p>
                <p><strong>Expiry Date:</strong> <?= !empty($agreement['expiry_date']) ? esc(date('F j, Y', strtotime($agreement['expiry_date']))) : 'N/A' ?></p>
                <p><strong>Parties Involved:</strong> 
                    <?php 
                        $partiesValue = $agreement['parties'];
                        if (is_array($partiesValue)) {
                            echo esc(implode(', ', $partiesValue));
                        } elseif (is_string($partiesValue)) {
                            echo esc($partiesValue);
                        } else {
                            echo 'N/A';
                        }
                    ?>
                </p>
            </div>
        </div>

        <hr>

        <div class="mb-3">
            <h5>Description</h5>
            <p><?= !empty($agreement['description']) ? nl2br(esc($agreement['description'])) : 'No description provided.' ?></p>
        </div>

        <div class="mb-3">
            <h5>Terms</h5>
            <p><?= !empty($agreement['terms']) ? nl2br(esc($agreement['terms'])) : 'No terms specified.' ?></p>
        </div>

        <div class="mb-3">
            <h5>Conditions</h5>
            <p><?= !empty($agreement['conditions']) ? nl2br(esc($agreement['conditions'])) : 'No conditions specified.' ?></p>
        </div>

        <div class="mb-3">
            <h5>Attachments</h5>
            <?php if (!empty($agreement['attachments']) && is_array($agreement['attachments'])): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($agreement['attachments'] as $index => $attachment): ?>
                        <li class="list-group-item">
                            <a href="<?= base_url('agreements/download/' . $agreement['id'] . '/' . $index) ?>" target="_blank">
                                <i class="fas fa-download me-2"></i><?= esc($attachment['original_name']) ?> 
                                <small>(<?= number_format($attachment['size'] / 1024, 2) ?> KB)</small>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No attachments found.</p>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <h5>Remarks</h5>
            <p><?= !empty($agreement['remarks']) ? nl2br(esc($agreement['remarks'])) : 'No remarks provided.' ?></p>
        </div>

        <hr>

        <div class="text-muted small">
            <p>Created At: <?= esc(date('F j, Y, g:i a', strtotime($agreement['created_at']))) ?></p>
            <p>Last Updated At: <?= esc(date('F j, Y, g:i a', strtotime($agreement['updated_at']))) ?></p>
            <!-- You might want to fetch user names based on created_by/updated_by IDs -->
        </div>

    </div>
</div>
<?= $this->endSection() ?> 