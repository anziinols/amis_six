<!-- Agreements Implementation Details -->
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <strong>Agreement Title:</strong>
            <p class="text-muted"><?= esc($implementationData['title'] ?? 'N/A') ?></p>
        </div>
        <div class="mb-3">
            <strong>Agreement Type:</strong>
            <p class="text-muted"><?= esc($implementationData['agreement_type'] ?? 'N/A') ?></p>
        </div>
        <div class="mb-3">
            <strong>Effective Date:</strong>
            <p class="text-muted"><?= !empty($implementationData['effective_date']) ? date('d M Y', strtotime($implementationData['effective_date'])) : 'N/A' ?></p>
        </div>
        <div class="mb-3">
            <strong>Expiry Date:</strong>
            <p class="text-muted"><?= !empty($implementationData['expiry_date']) ? date('d M Y', strtotime($implementationData['expiry_date'])) : 'No expiry date' ?></p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <strong>Status:</strong>
            <p class="text-muted">
                <span class="badge bg-<?=
                    ($implementationData['status'] ?? 'draft') === 'active' ? 'success' :
                    (($implementationData['status'] ?? 'draft') === 'expired' ? 'warning' :
                    (($implementationData['status'] ?? 'draft') === 'terminated' ? 'danger' : 'secondary'))
                ?>">
                    <?= ucfirst($implementationData['status'] ?? 'draft') ?>
                </span>
            </p>
        </div>

    </div>
</div>

<div class="mb-3">
    <strong>Agreement Description:</strong>
    <p class="text-muted"><?= nl2br(esc($implementationData['description'] ?? 'N/A')) ?></p>
</div>

<!-- Parties Involved Section -->
<?php if (!empty($implementationData['parties'])): ?>
<div class="mb-3">
    <strong>Parties Involved (<?= count($implementationData['parties']) ?> parties):</strong>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Organization</th>
                    <th>Role</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($implementationData['parties'] as $index => $party): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($party['name'] ?? 'N/A') ?></td>
                    <td><?= esc($party['organization'] ?? 'N/A') ?></td>
                    <td><?= esc($party['role'] ?? 'N/A') ?></td>
                    <td><?= esc($party['contact'] ?? 'N/A') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>



<?php if (!empty($implementationData['attachments'])): ?>
<div class="mb-3">
    <strong>Agreement Documents (<?= count($implementationData['attachments']) ?> files):</strong>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>File Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($implementationData['attachments'] as $index => $attachment): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($attachment['filename'] ?? $attachment['original_name'] ?? 'Unknown') ?></td>
                    <td><?= esc($attachment['description'] ?? 'N/A') ?></td>
                    <td>
                        <a href="<?= base_url($attachment['path'] ?? '') ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>



<?php if (!empty($implementationData['remarks'])): ?>
<div class="mb-3">
    <strong>Remarks:</strong>
    <p class="text-muted"><?= nl2br(esc($implementationData['remarks'])) ?></p>
</div>
<?php endif; ?>
