<!-- Inputs Implementation Details -->

<!-- GPS Coordinates -->
<?php if (!empty($implementationData['gps_coordinates'])): ?>
<div class="mb-3">
    <strong>GPS Coordinates:</strong>
    <p class="text-muted"><?= esc($implementationData['gps_coordinates']) ?></p>
</div>
<?php endif; ?>

<!-- Input Items Table -->
<?php if (!empty($implementationData['inputs'])): ?>
<div class="mb-3">
    <strong>Input Items (<?= count($implementationData['inputs']) ?> items):</strong>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Input Name</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($implementationData['inputs'] as $index => $input): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($input['name'] ?? 'N/A') ?></td>
                    <td><?= esc($input['quantity'] ?? 'N/A') ?></td>
                    <td><?= esc($input['unit'] ?? 'N/A') ?></td>
                    <td><?= esc($input['remarks'] ?? 'N/A') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Signing Sheet -->
<?php if (!empty($implementationData['signing_sheet_filepath'])): ?>
<div class="mb-3">
    <strong>Signing Sheet:</strong>
    <p>
        <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-download"></i> Download Signing Sheet
        </a>
    </p>
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
