<!-- Inputs Implementation Details -->
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <strong>Input Title:</strong>
            <p class="text-muted"><?= esc($implementationData['title'] ?? 'N/A') ?></p>
        </div>
        <div class="mb-3">
            <strong>Input Type:</strong>
            <p class="text-muted"><?= esc($implementationData['input_type'] ?? 'N/A') ?></p>
        </div>
        <div class="mb-3">
            <strong>Quantity:</strong>
            <p class="text-muted"><?= esc($implementationData['quantity'] ?? 'N/A') ?></p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <strong>Unit:</strong>
            <p class="text-muted"><?= esc($implementationData['unit'] ?? 'N/A') ?></p>
        </div>
        <div class="mb-3">
            <strong>Cost per Unit:</strong>
            <p class="text-muted"><?= !empty($implementationData['cost_per_unit']) ? 'KES ' . number_format($implementationData['cost_per_unit'], 2) : 'N/A' ?></p>
        </div>
        <div class="mb-3">
            <strong>Total Cost:</strong>
            <p class="text-muted"><?= !empty($implementationData['total_cost']) ? 'KES ' . number_format($implementationData['total_cost'], 2) : 'N/A' ?></p>
        </div>
    </div>
</div>

<div class="mb-3">
    <strong>Description:</strong>
    <p class="text-muted"><?= nl2br(esc($implementationData['description'] ?? 'N/A')) ?></p>
</div>

<?php if (!empty($implementationData['supplier_details'])): ?>
<div class="mb-3">
    <strong>Supplier Details:</strong>
    <p class="text-muted"><?= nl2br(esc($implementationData['supplier_details'])) ?></p>
</div>
<?php endif; ?>

<?php if (!empty($implementationData['delivery_date'])): ?>
<div class="mb-3">
    <strong>Delivery Date:</strong>
    <p class="text-muted"><?= date('d M Y', strtotime($implementationData['delivery_date'])) ?></p>
</div>
<?php endif; ?>

<?php if (!empty($implementationData['gps_coordinates'])): ?>
<div class="mb-3">
    <strong>GPS Coordinates:</strong>
    <p class="text-muted"><?= esc($implementationData['gps_coordinates']) ?></p>
</div>
<?php endif; ?>

<?php if (!empty($implementationData['input_files'])): ?>
<div class="mb-3">
    <strong>Input Documents (<?= count($implementationData['input_files']) ?> files):</strong>
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
                <?php foreach ($implementationData['input_files'] as $index => $file): ?>
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

<?php if (!empty($implementationData['input_images'])): ?>
<div class="mb-3">
    <strong>Input Images (<?= count($implementationData['input_images']) ?> images):</strong>
    <div class="row">
        <?php foreach ($implementationData['input_images'] as $index => $image): ?>
        <div class="col-md-3 mb-2">
            <div class="card">
                <img src="<?= base_url($image) ?>" class="card-img-top input-image" style="height: 150px; object-fit: cover; cursor: pointer;" alt="Input Image" data-bs-toggle="modal" data-bs-target="#inputImageModal" data-image-src="<?= base_url($image) ?>">
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

<!-- Input Image Modal -->
<div class="modal fade" id="inputImageModal" tabindex="-1" aria-labelledby="inputImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inputImageModalLabel">Input Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="inputModalImage" src="" class="img-fluid" alt="Input Image">
            </div>
        </div>
    </div>
</div>

<script>
// Handle input image modal
document.addEventListener('DOMContentLoaded', function() {
    const inputImageModal = document.getElementById('inputImageModal');
    if (inputImageModal) {
        inputImageModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const imageSrc = button.getAttribute('data-image-src');
            const modalImage = document.getElementById('inputModalImage');
            modalImage.src = imageSrc;
        });
    }
});
</script>
