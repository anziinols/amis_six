<?php
// app/Views/documents/documents_folders_list.php
?>
<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white p-3 mb-4 rounded shadow-sm">
            <li class="breadcrumb-item"><a href="<?= base_url('documents') ?>"><i class="fas fa-folder-open"></i> Documents</a></li>
            <?php foreach ($folder_path as $path_item): ?>
                <?php if ($path_item['id'] == ($current_folder['id'] ?? 0)): ?>
                    <li class="breadcrumb-item active" aria-current="page"><?= esc($path_item['name']) ?></li>
                <?php else: ?>
                    <li class="breadcrumb-item"><a href="<?= base_url('documents?parent_id=' . $path_item['id']) ?>"><?= esc($path_item['name']) ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h3>
                <?php if (isset($current_folder)): ?>
                    <i class="fas fa-folder-open text-warning"></i> <?= esc($current_folder['name']) ?>
                <?php else: ?>
                    <i class="fas fa-folder-open text-warning"></i> Document Management
                <?php endif; ?>
            </h3>
            <?php if (isset($current_folder) && !empty($current_folder['description'])): ?>
                <p class="text-muted mb-0"><?= esc($current_folder['description']) ?></p>
            <?php endif; ?>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= base_url('documents/new' . ($parent_id ? '?parent_id=' . $parent_id : '')) ?>" class="btn btn-success">
                <i class="fas fa-folder-plus"></i> New Folder
            </a>
            <?php if (isset($current_folder)): ?>
                <a href="<?= base_url('documents/file/new/' . $current_folder['id']) ?>" class="btn btn-primary ms-2">
                    <i class="fas fa-file-upload"></i> Upload Document
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Folders Grid -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-folder me-2"></i> Folders</h5>
        </div>
        <div class="card-body">
            <?php if (empty($folders)): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> No folders found. Create a new folder to get started.
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    <?php foreach ($folders as $folder): ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm hover-shadow">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-warning rounded p-2 me-3">
                                            <i class="fas fa-folder text-white fa-2x"></i>
                                        </div>
                                        <h5 class="mb-0">
                                            <a href="<?= base_url('documents?parent_id=' . $folder['id']) ?>" class="text-decoration-none text-dark">
                                                <?= esc($folder['name']) ?>
                                            </a>
                                        </h5>
                                    </div>
                                    <p class="text-muted small mb-2 text-truncate">
                                        <?= !empty($folder['description']) ? esc($folder['description']) : 'No description' ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-info"><?= $folder['document_count'] ?> file(s)</span>
                                        <span class="badge bg-secondary"><?= $folder['subfolder_count'] ?> folder(s)</span>
                                    </div>
                                </div>
                                <div class="card-footer bg-white border-0">
                                    <div class="btn-group w-100">
                                        <a href="<?= base_url('documents?parent_id=' . $folder['id']) ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-folder-open"></i> Open
                                        </a>
                                        <a href="<?= base_url('documents/edit/' . $folder['id']) ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <?php if ($folder['document_count'] == 0 && $folder['subfolder_count'] == 0): ?>
                                            <a href="<?= base_url('documents/delete/' . $folder['id']) ?>" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this folder?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-danger" disabled title="Cannot delete folders with contents">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Documents List -->
    <?php if (isset($current_folder)): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i> Documents</h5>
            </div>
            <div class="card-body">
                <?php if (empty($documents)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> No documents found in this folder. Upload documents to get started.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover" id="documentsTable">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>File Type</th>
                                    <th>Size</th>
                                    <th>Classification</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documents as $document): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= base_url('documents/file/view/' . $document['id']) ?>" class="text-decoration-none fw-bold">
                                                <?= esc($document['title']) ?>
                                            </a>
                                            <?php if (!empty($document['description'])): ?>
                                                <div class="small text-muted"><?= esc($document['description']) ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $fileType = 'unknown';
                                            $iconClass = 'fa-file';

                                            // Check if file_path exists in the document array
                                            if (isset($document['file_path']) && !empty($document['file_path'])) {
                                                $fileType = strtolower(pathinfo($document['file_path'], PATHINFO_EXTENSION));

                                                if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                    $iconClass = 'fa-file-image';
                                                } elseif (in_array($fileType, ['pdf'])) {
                                                    $iconClass = 'fa-file-pdf';
                                                } elseif (in_array($fileType, ['doc', 'docx'])) {
                                                    $iconClass = 'fa-file-word';
                                                } elseif (in_array($fileType, ['xls', 'xlsx'])) {
                                                    $iconClass = 'fa-file-excel';
                                                } elseif (in_array($fileType, ['ppt', 'pptx'])) {
                                                    $iconClass = 'fa-file-powerpoint';
                                                } elseif (in_array($fileType, ['zip', 'rar', '7z'])) {
                                                    $iconClass = 'fa-file-archive';
                                                }
                                            }
                                            // Fallback to original_name if available (for backward compatibility)
                                            elseif (isset($document['original_name']) && !empty($document['original_name'])) {
                                                $fileType = strtolower(pathinfo($document['original_name'], PATHINFO_EXTENSION));

                                                if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                    $iconClass = 'fa-file-image';
                                                } elseif (in_array($fileType, ['pdf'])) {
                                                    $iconClass = 'fa-file-pdf';
                                                } elseif (in_array($fileType, ['doc', 'docx'])) {
                                                    $iconClass = 'fa-file-word';
                                                } elseif (in_array($fileType, ['xls', 'xlsx'])) {
                                                    $iconClass = 'fa-file-excel';
                                                } elseif (in_array($fileType, ['ppt', 'pptx'])) {
                                                    $iconClass = 'fa-file-powerpoint';
                                                } elseif (in_array($fileType, ['zip', 'rar', '7z'])) {
                                                    $iconClass = 'fa-file-archive';
                                                }
                                            }
                                            ?>
                                            <i class="fas <?= $iconClass ?> me-2"></i> <?= strtoupper($fileType) ?>
                                        </td>
                                        <td>
                                            <?php
                                            if (isset($document['file_size']) && !empty($document['file_size'])) {
                                                $size = $document['file_size'];
                                                if ($size < 1024) {
                                                    echo $size . ' B';
                                                } elseif ($size < 1048576) {
                                                    echo round($size / 1024, 2) . ' KB';
                                                } else {
                                                    echo round($size / 1048576, 2) . ' MB';
                                                }
                                            } else {
                                                echo 'N/A';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeClass = 'bg-secondary';
                                            if ($document['classification'] == 'private') {
                                                $badgeClass = 'bg-danger';
                                            } elseif ($document['classification'] == 'internal') {
                                                $badgeClass = 'bg-warning text-dark';
                                            } elseif ($document['classification'] == 'public') {
                                                $badgeClass = 'bg-success';
                                            }
                                            ?>
                                            <span class="badge <?= $badgeClass ?>"><?= ucfirst($document['classification']) ?></span>
                                        </td>
                                        <td>
                                            <?= !empty($document['doc_date']) ? date('M d, Y', strtotime($document['doc_date'])) : 'N/A' ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= base_url('documents/file/view/' . $document['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= base_url('documents/file/download/' . $document['id']) ?>" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="<?= base_url('documents/file/delete/' . $document['id']) ?>" class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this document?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Drag and Drop Upload Modal (for future implementation) -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Documents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="upload-area p-5 border-2 border-dashed rounded text-center">
                    <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-muted"></i>
                    <h5>Drag & Drop files here</h5>
                    <p class="text-muted mb-3">or click to browse files</p>
                    <input type="file" id="fileUpload" class="d-none" multiple>
                    <button type="button" id="browseFiles" class="btn btn-outline-primary">Browse Files</button>
                </div>
                <div class="upload-preview mt-4 d-none">
                    <h6>Upload Queue</h6>
                    <ul class="list-group" id="uploadList"></ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="startUpload">Start Upload</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#documentsTable').DataTable({
            responsive: true,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
        });
    });
</script>
<?= $this->endSection() ?>