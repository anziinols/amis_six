<!-- Outputs Implementation Details -->
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <strong>Total Value:</strong>
            <p class="text-muted">
                <?php if (!empty($implementationData['total_value'])): ?>
                    <?= CURRENCY_SYMBOL ?> <?= number_format($implementationData['total_value'], 2) ?>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <strong>Implementation Date:</strong>
            <p class="text-muted"><?= date('d M Y', strtotime($implementationData['created_at'])) ?></p>
        </div>
    </div>
</div>

<!-- Outputs Table -->
<?php if (!empty($implementationData['outputs'])): ?>
<div class="mb-4">
    <strong>Outputs Delivered (<?= count($implementationData['outputs']) ?> items):</strong>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Output Name</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($implementationData['outputs'] as $index => $output): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($output['name']) ?></td>
                    <td><?= esc($output['quantity'] ?? 'N/A') ?></td>
                    <td><?= esc($output['unit'] ?? 'N/A') ?></td>
                    <td><?= esc($output['description'] ?? 'N/A') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Beneficiaries Table -->
<?php if (!empty($implementationData['beneficiaries'])): ?>
<div class="mb-4">
    <strong>Beneficiaries (<?= count($implementationData['beneficiaries']) ?> beneficiaries):</strong>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Organization</th>
                    <th>Contact</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($implementationData['beneficiaries'] as $index => $beneficiary): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($beneficiary['name']) ?></td>
                    <td><?= esc($beneficiary['organization'] ?? 'N/A') ?></td>
                    <td><?= esc($beneficiary['contact'] ?? 'N/A') ?></td>
                    <td><?= ucfirst(esc($beneficiary['type'] ?? 'individual')) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($implementationData['gps_coordinates'])): ?>
<div class="mb-3">
    <strong>GPS Coordinates:</strong>
    <p class="text-muted"><?= esc($implementationData['gps_coordinates']) ?></p>
</div>
<?php endif; ?>

<?php if (!empty($implementationData['output_files'])): ?>
<div class="mb-4">
    <strong>Output Files (<?= count($implementationData['output_files']) ?> files):</strong>
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
                <?php foreach ($implementationData['output_files'] as $index => $file): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($file['filename'] ?? 'Output File') ?></td>
                    <td><?= esc($file['original_name'] ?? 'N/A') ?></td>
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

<?php if (!empty($implementationData['output_images'])): ?>
<div class="mb-4">
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

<!-- Signing Sheet -->
<?php if (!empty($implementationData['signing_sheet_filepath'])): ?>
<div class="mb-4">
    <strong>Signing Sheet:</strong>
    <div class="mt-2">
        <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-download"></i> Download Signing Sheet
        </a>
    </div>
</div>
<?php endif; ?>

<!-- GPS Coordinates -->
<?php if (!empty($implementationData['gps_coordinates'])): ?>
<div class="mb-4">
    <strong>GPS Coordinates:</strong>
    <p class="text-muted"><?= esc($implementationData['gps_coordinates']) ?></p>
</div>
<?php endif; ?>

<?php if (!empty($implementationData['remarks'])): ?>
<div class="mb-4">
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
