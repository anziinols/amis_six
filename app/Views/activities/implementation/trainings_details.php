<!-- Training Implementation Details -->
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <strong>Trainers:</strong>
            <p class="text-muted"><?= nl2br(esc($implementationData['trainers'] ?? 'N/A')) ?></p>
        </div>
        <div class="mb-3">
            <strong>Topics:</strong>
            <p class="text-muted"><?= nl2br(esc($implementationData['topics'] ?? 'N/A')) ?></p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <strong>GPS Coordinates:</strong>
            <p class="text-muted"><?= esc($implementationData['gps_coordinates'] ?? 'N/A') ?></p>
        </div>
        <?php if (!empty($implementationData['signing_sheet_filepath'])): ?>
        <div class="mb-3">
            <strong>Signing Sheet:</strong><br>
            <a href="<?= base_url($implementationData['signing_sheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-download"></i> Download Signing Sheet
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($implementationData['trainees'])): ?>
<div class="mb-3">
    <strong>Trainees (<?= count($implementationData['trainees']) ?> participants):</strong>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($implementationData['trainees'] as $index => $trainee): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($trainee['name']) ?></td>
                    <td><?= esc($trainee['age']) ?></td>
                    <td><?= esc($trainee['gender']) ?></td>
                    <td><?= esc($trainee['phone']) ?></td>
                    <td><?= esc($trainee['email']) ?></td>
                    <td><?= esc($trainee['remarks']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($implementationData['training_images'])): ?>
<div class="mb-3">
    <strong>Training Images (<?= count($implementationData['training_images']) ?> images):</strong>
    <div class="row">
        <?php foreach ($implementationData['training_images'] as $index => $image): ?>
        <div class="col-md-3 mb-2">
            <div class="card">
                <img src="<?= base_url($image) ?>" class="card-img-top training-image" style="height: 150px; object-fit: cover; cursor: pointer;" alt="Training Image" data-bs-toggle="modal" data-bs-target="#imageModal" data-image-src="<?= base_url($image) ?>">
                <div class="card-body p-2">
                    <small class="text-muted">Image <?= $index + 1 ?></small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($implementationData['training_files'])): ?>
<div class="mb-3">
    <strong>Training Files (<?= count($implementationData['training_files']) ?> files):</strong>
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
                <?php foreach ($implementationData['training_files'] as $index => $file): ?>
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

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Training Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Training Image">
            </div>
        </div>
    </div>
</div>

<script>
// Handle image modal
document.addEventListener('DOMContentLoaded', function() {
    const imageModal = document.getElementById('imageModal');
    if (imageModal) {
        imageModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const imageSrc = button.getAttribute('data-image-src');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageSrc;
        });
    }
});
</script>
