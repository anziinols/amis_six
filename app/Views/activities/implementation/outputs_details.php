<!-- Outputs Implementation Details -->
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <strong>Output Title:</strong>
            <p class="text-muted"><?= esc($implementationData['title'] ?? 'N/A') ?></p>
        </div>
        <div class="mb-3">
            <strong>Output Type:</strong>
            <p class="text-muted"><?= esc($implementationData['output_type'] ?? 'N/A') ?></p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <strong>Quantity:</strong>
            <p class="text-muted"><?= esc($implementationData['quantity'] ?? 'N/A') ?></p>
        </div>
        <div class="mb-3">
            <strong>Unit:</strong>
            <p class="text-muted"><?= esc($implementationData['unit'] ?? 'N/A') ?></p>
        </div>
    </div>
</div>

<div class="mb-3">
    <strong>Description:</strong>
    <p class="text-muted"><?= nl2br(esc($implementationData['description'] ?? 'N/A')) ?></p>
</div>

<?php if (!empty($implementationData['gps_coordinates'])): ?>
<div class="mb-3">
    <strong>GPS Coordinates:</strong>
    <p class="text-muted"><?= esc($implementationData['gps_coordinates']) ?></p>
</div>
<?php endif; ?>

<?php if (!empty($implementationData['output_files'])): ?>
<div class="mb-3">
    <strong>Output Files (<?= count($implementationData['output_files']) ?> files):</strong>
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
                <?php foreach ($implementationData['output_files'] as $index => $file): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($file['caption']) ?></td>
                    <td><?= esc($file['original_name']) ?></td>
                    <td>
                        <a href="<?= base_url($file['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
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

<?php if (!empty($implementationData['output_images'])): ?>
<div class="mb-3">
    <strong>Output Images (<?= count($implementationData['output_images']) ?> images):</strong>
    <div class="row">
        <?php foreach ($implementationData['output_images'] as $index => $image): ?>
        <div class="col-md-3 mb-2">
            <div class="card">
                <img src="<?= base_url($image) ?>" class="card-img-top output-image" style="height: 150px; object-fit: cover; cursor: pointer;" alt="Output Image" data-bs-toggle="modal" data-bs-target="#outputImageModal" data-image-src="<?= base_url($image) ?>">
                <div class="card-body p-2">
                    <small class="text-muted">Image <?= $index + 1 ?></small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($implementationData['remarks'])): ?>
<div class="mb-3">
    <strong>Remarks:</strong>
    <p class="text-muted"><?= nl2br(esc($implementationData['remarks'])) ?></p>
</div>
<?php endif; ?>

<!-- Output Image Modal -->
<div class="modal fade" id="outputImageModal" tabindex="-1" aria-labelledby="outputImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="outputImageModalLabel">Output Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="outputModalImage" src="" class="img-fluid" alt="Output Image">
            </div>
        </div>
    </div>
</div>

<script>
// Handle output image modal
document.addEventListener('DOMContentLoaded', function() {
    const outputImageModal = document.getElementById('outputImageModal');
    if (outputImageModal) {
        outputImageModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const imageSrc = button.getAttribute('data-image-src');
            const modalImage = document.getElementById('outputModalImage');
            modalImage.src = imageSrc;
        });
    }
});
</script>
