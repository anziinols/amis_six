<!-- Infrastructure Implementation Details -->
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <strong>Infrastructure Description:</strong>
            <p class="text-muted"><?= nl2br(esc($implementationData['infrastructure'] ?? 'N/A')) ?></p>
        </div>
    </div>
    <div class="col-md-6">
        <?php if (!empty($implementationData['gps_coordinates'])): ?>
        <div class="mb-3">
            <strong>GPS Coordinates:</strong>
            <p class="text-muted"><?= esc($implementationData['gps_coordinates']) ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($implementationData['infrastructure_files'])): ?>
<div class="mb-3">
    <strong>Infrastructure Documents (<?= count($implementationData['infrastructure_files']) ?> files):</strong>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Original Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($implementationData['infrastructure_files'] as $index => $file): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($file['filename']) ?></td>
                    <td><?= esc($file['original_name']) ?></td>
                    <td>
                        <a href="<?= base_url($file['path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
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

<?php if (!empty($implementationData['infrastructure_images'])): ?>
<div class="mb-3">
    <strong>Infrastructure Images (<?= count($implementationData['infrastructure_images']) ?> images):</strong>
    <div class="row">
        <?php foreach ($implementationData['infrastructure_images'] as $index => $image): ?>
        <div class="col-md-3 mb-2">
            <div class="card">
                <img src="<?= base_url($image) ?>" class="card-img-top infrastructure-image" style="height: 150px; object-fit: cover; cursor: pointer;" alt="Infrastructure Image" data-bs-toggle="modal" data-bs-target="#infrastructureImageModal" data-image-src="<?= base_url($image) ?>">
                <div class="card-body p-2">
                    <small class="text-muted">Image <?= $index + 1 ?></small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($implementationData['signing_scheet_filepath'])): ?>
<div class="mb-3">
    <strong>Signing Sheet:</strong>
    <div>
        <a href="<?= base_url($implementationData['signing_scheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-download"></i> Download Signing Sheet
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Infrastructure Image Modal -->
<div class="modal fade" id="infrastructureImageModal" tabindex="-1" aria-labelledby="infrastructureImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infrastructureImageModalLabel">Infrastructure Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="infrastructureModalImage" src="" class="img-fluid" alt="Infrastructure Image">
            </div>
        </div>
    </div>
</div>

<script>
// Handle infrastructure image modal
document.addEventListener('DOMContentLoaded', function() {
    const infrastructureImageModal = document.getElementById('infrastructureImageModal');
    if (infrastructureImageModal) {
        infrastructureImageModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const imageSrc = button.getAttribute('data-image-src');
            const modalImage = document.getElementById('infrastructureModalImage');
            modalImage.src = imageSrc;
        });
    }
});
</script>
