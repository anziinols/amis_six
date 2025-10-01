<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="mb-3">
        <a href="<?= base_url('activities/' . $activity['id']) ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Activity Details
        </a>
    </div>

    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Implement Document Activity: <?= esc($activity['activity_title']) ?></h5>
                </div>
                <div class="card-body">
                    <!-- Activity Reference Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Activity Reference Information</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Title:</strong> <?= esc($activity['activity_title']) ?></p>
                                            <p class="mb-1"><strong>Type:</strong> <span class="badge bg-info"><?= ucfirst(esc($activity['type'])) ?></span></p>
                                        </div>
                                        <div class="col-md-4">
                                            
                                            <p class="mb-1"><strong>Location:</strong> <?= esc($activity['location'] ?? 'N/A') ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1"><strong>Supervisor:</strong> <?= esc($activity['supervisor_name'] ?? 'N/A') ?></p>
                                            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-<?= $activity['status'] === 'approved' ? 'success' : 'warning' ?>"><?= ucfirst(esc($activity['status'])) ?></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Remarks Section -->
                    <?php if (!empty($activity['status_remarks'])): ?>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <h6 class="alert-heading">
                                    <i class="fas fa-comment-alt me-2"></i>Status Remarks
                                </h6>
                                <p class="mb-2"><?= nl2br(esc($activity['status_remarks'])) ?></p>
                                <?php if (!empty($activity['status_by_name'])): ?>
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>By: <?= esc($activity['status_by_name']) ?>
                                    <?php if (!empty($activity['status_at'])): ?>
                                    <i class="fas fa-clock ms-2 me-1"></i>On: <?= date('d M Y H:i', strtotime($activity['status_at'])) ?>
                                    <?php endif; ?>
                                </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Show existing implementation data if exists -->
                    <?php if ($implementationData): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This activity has already been implemented. You can view the details below or update the implementation.
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Current Implementation</h6>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <strong>General Remarks:</strong>
                                            <p class="text-muted"><?= nl2br(esc($implementationData['remarks'])) ?></p>
                                        </div>

                                        <?php if (!empty($implementationData['document_files'])): ?>
                                        <div class="mb-3">
                                            <strong>Uploaded Documents:</strong>
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
                                                                <a href="<?= base_url($document['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                                                    <i class="fas fa-download"></i> Download
                                                                </a>
                                                                <button type="button" class="btn btn-sm btn-outline-warning me-1 edit-document"
                                                                        data-index="<?= $index ?>"
                                                                        data-caption="<?= esc($document['caption']) ?>"
                                                                        data-original="<?= esc($document['original_name']) ?>">
                                                                    <i class="fas fa-edit"></i> Edit
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-outline-danger remove-existing-document"
                                                                        data-index="<?= $index ?>">
                                                                    <i class="fas fa-trash"></i> Remove
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <div class="text-muted">
                                            <small>Implemented on: <?= date('d M Y H:i', strtotime($implementationData['created_at'])) ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Document Implementation Form -->
                    <form action="<?= base_url('activities/' . $activity['id'] . '/save-implementation') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <!-- Hidden inputs for tracking document changes -->
                        <input type="hidden" id="documentsToRemove" name="documents_to_remove" value="">
                        <input type="hidden" id="documentsToUpdate" name="documents_to_update" value="">
    
    <div class="mb-3">
        <label class="form-label">Upload Documents <?= !$implementationData ? '<span class="text-danger">*</span>' : '<span class="text-muted">(Optional - only if adding new documents)</span>' ?></label>
        <div id="documentsContainer">
            <div class="document-item border p-3 mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Document File</label>
                        <input type="file" class="form-control" name="document_files[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" <?= !$implementationData ? 'required' : '' ?>>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Caption</label>
                        <input type="text" class="form-control" name="document_captions[]" placeholder="Enter caption for this document" <?= !$implementationData ? 'required' : '' ?>>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-danger mt-2 remove-document" style="display: none;">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
        </div>
        <button type="button" id="addDocument" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-plus"></i> Add Another Document
        </button>
        <div class="form-text">Supported formats: PDF, Word, Excel, PowerPoint</div>
    </div>

    <div class="mb-3">
        <label for="remarks" class="form-label">General Remarks <span class="text-danger">*</span></label>
        <textarea class="form-control" id="remarks" name="remarks" rows="4" required placeholder="Enter general remarks about the document implementation"><?= old('remarks', $implementationData['remarks'] ?? '') ?></textarea>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i> Save Implementation
        </button>
        <a href="<?= base_url('activities/' . $activity['id']) ?>" class="btn btn-secondary">
            <i class="fas fa-times me-1"></i> Cancel
        </a>
    </div>
</form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Caption Modal -->
<div class="modal fade" id="editCaptionModal" tabindex="-1" aria-labelledby="editCaptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editCaptionModalLabel">Edit Document Caption</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editCaptionInput" class="form-label">Document Name</label>
                    <input type="text" class="form-control" id="editCaptionOriginalName" readonly>
                </div>
                <div class="mb-3">
                    <label for="editCaptionInput" class="form-label">Caption <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editCaptionInput" placeholder="Enter new caption">
                    <div class="form-text">Enter a descriptive caption for this document.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-warning" id="confirmEditCaption">
                    <i class="fas fa-save me-1"></i> Update Caption
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Remove Document Modal -->
<div class="modal fade" id="removeDocumentModal" tabindex="-1" aria-labelledby="removeDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="removeDocumentModalLabel">Remove Document</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone.
                </div>
                <p>Are you sure you want to remove the following document?</p>
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title mb-1" id="removeDocumentName"></h6>
                        <small class="text-muted" id="removeDocumentCaption"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmRemoveDocument">
                    <i class="fas fa-trash me-1"></i> Remove Document
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Add Document button click
        $('#addDocument').click(function(e) {
            e.preventDefault();
            const newDocumentItem = `
                <div class="document-item border p-3 mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Document File</label>
                            <input type="file" class="form-control" name="document_files[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Caption</label>
                            <input type="text" class="form-control" name="document_captions[]" placeholder="Enter caption for this document" required>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger mt-2 remove-document">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            `;
            $('#documentsContainer').append(newDocumentItem);
            updateRemoveButtons();
        });

        // Remove Document button click
        $(document).on('click', '.remove-document', function(e) {
            e.preventDefault();
            $(this).closest('.document-item').remove();
            updateRemoveButtons();
        });

        // Update remove buttons visibility
        function updateRemoveButtons() {
            const documentItems = $('.document-item');
            if (documentItems.length > 1) {
                $('.remove-document').show();
            } else {
                $('.remove-document').hide();
            }
        }

        // Edit existing document caption
        let currentEditIndex = null;
        let currentEditButton = null;

        $(document).on('click', '.edit-document', function(e) {
            e.preventDefault();
            currentEditIndex = $(this).data('index');
            currentEditButton = $(this);
            const currentCaption = $(this).data('caption');
            const originalName = $(this).data('original');

            // Populate modal
            $('#editCaptionOriginalName').val(originalName);
            $('#editCaptionInput').val(currentCaption);

            // Show modal
            $('#editCaptionModal').modal('show');
        });

        // Confirm edit caption
        $('#confirmEditCaption').click(function() {
            const newCaption = $('#editCaptionInput').val().trim();
            if (newCaption !== '') {
                // Update the display
                currentEditButton.closest('tr').find('td:eq(1)').text(newCaption);
                currentEditButton.data('caption', newCaption);

                // Track the update
                let updatesData = JSON.parse($('#documentsToUpdate').val() || '{}');
                updatesData[currentEditIndex] = { caption: newCaption };
                $('#documentsToUpdate').val(JSON.stringify(updatesData));

                // Hide modal
                $('#editCaptionModal').modal('hide');
            } else {
                // Show validation error
                $('#editCaptionInput').addClass('is-invalid');
                if (!$('#editCaptionInput').next('.invalid-feedback').length) {
                    $('#editCaptionInput').after('<div class="invalid-feedback">Caption is required.</div>');
                }
            }
        });

        // Remove validation error when user types
        $('#editCaptionInput').on('input', function() {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });

        // Handle Enter key in caption input
        $('#editCaptionInput').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                $('#confirmEditCaption').click();
            }
        });

        // Remove existing document
        let currentRemoveIndex = null;
        let currentRemoveRow = null;

        $(document).on('click', '.remove-existing-document', function(e) {
            e.preventDefault();
            currentRemoveIndex = $(this).data('index');
            currentRemoveRow = $(this).closest('tr');
            const originalName = currentRemoveRow.find('td:eq(2)').text();
            const caption = currentRemoveRow.find('td:eq(1)').text();

            // Populate modal
            $('#removeDocumentName').text(originalName);
            $('#removeDocumentCaption').text(caption);

            // Show modal
            $('#removeDocumentModal').modal('show');
        });

        // Confirm remove document
        $('#confirmRemoveDocument').click(function() {
            // Remove the row
            currentRemoveRow.remove();

            // Track the removal
            let removalsData = JSON.parse($('#documentsToRemove').val() || '[]');
            removalsData.push(currentRemoveIndex);
            $('#documentsToRemove').val(JSON.stringify(removalsData));

            // Hide modal
            $('#removeDocumentModal').modal('hide');
        });
    });
</script>
<?= $this->endSection() ?>
