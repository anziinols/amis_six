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
            <strong>Signing Date:</strong>
            <p class="text-muted"><?= !empty($implementationData['signing_date']) ? date('d M Y', strtotime($implementationData['signing_date'])) : 'N/A' ?></p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <strong>Parties Involved:</strong>
            <p class="text-muted"><?= nl2br(esc($implementationData['parties_involved'] ?? 'N/A')) ?></p>
        </div>
        <div class="mb-3">
            <strong>Location:</strong>
            <p class="text-muted"><?= esc($implementationData['location'] ?? 'N/A') ?></p>
        </div>
        <?php if (!empty($implementationData['gps_coordinates'])): ?>
        <div class="mb-3">
            <strong>GPS Coordinates:</strong>
            <p class="text-muted"><?= esc($implementationData['gps_coordinates']) ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="mb-3">
    <strong>Agreement Description:</strong>
    <p class="text-muted"><?= nl2br(esc($implementationData['description'] ?? 'N/A')) ?></p>
</div>

<?php if (!empty($implementationData['terms_conditions'])): ?>
<div class="mb-3">
    <strong>Terms & Conditions:</strong>
    <p class="text-muted"><?= nl2br(esc($implementationData['terms_conditions'])) ?></p>
</div>
<?php endif; ?>

<?php if (!empty($implementationData['agreement_files'])): ?>
<div class="mb-3">
    <strong>Agreement Documents (<?= count($implementationData['agreement_files']) ?> files):</strong>
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
                <?php foreach ($implementationData['agreement_files'] as $index => $file): ?>
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

<?php if (!empty($implementationData['agreement_images'])): ?>
<div class="mb-3">
    <strong>Agreement Images (<?= count($implementationData['agreement_images']) ?> images):</strong>
    <div class="row">
        <?php foreach ($implementationData['agreement_images'] as $index => $image): ?>
        <div class="col-md-3 mb-2">
            <div class="card">
                <img src="<?= base_url($image) ?>" class="card-img-top agreement-image" style="height: 150px; object-fit: cover; cursor: pointer;" alt="Agreement Image" data-bs-toggle="modal" data-bs-target="#agreementImageModal" data-image-src="<?= base_url($image) ?>">
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

<!-- Agreement Image Modal -->
<div class="modal fade" id="agreementImageModal" tabindex="-1" aria-labelledby="agreementImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agreementImageModalLabel">Agreement Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="agreementModalImage" src="" class="img-fluid" alt="Agreement Image">
            </div>
        </div>
    </div>
</div>

<script>
// Handle agreement image modal
document.addEventListener('DOMContentLoaded', function() {
    const agreementImageModal = document.getElementById('agreementImageModal');
    if (agreementImageModal) {
        agreementImageModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const imageSrc = button.getAttribute('data-image-src');
            const modalImage = document.getElementById('agreementModalImage');
            modalImage.src = imageSrc;
        });
    }
});
</script>
