<!-- Documents Implementation Details -->
<div class="mb-3">
    <strong>General Remarks:</strong>
    <p class="text-muted"><?= nl2br(esc($implementationData['remarks'] ?? 'N/A')) ?></p>
</div>

<?php if (!empty($implementationData['document_files'])): ?>
<div class="mb-3">
    <strong>Uploaded Documents (<?= count($implementationData['document_files']) ?> files):</strong>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Caption</th>
                    <th>Original Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($implementationData['document_files'] as $index => $document): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($document['caption']) ?></td>
                    <td><?= esc($document['original_name']) ?></td>
                    <td>
                        <a href="<?= base_url($document['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
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
