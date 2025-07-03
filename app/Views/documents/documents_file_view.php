<?php
// app/Views/documents/documents_file_view.php
?>
<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-white p-3 mb-4 rounded shadow-sm">
            <li class="breadcrumb-item"><a href="<?= base_url('documents') ?>"><i class="fas fa-folder-open"></i> Documents</a></li>
            <?php foreach ($folder_path as $path_item): ?>
                <li class="breadcrumb-item">
                    <a href="<?= base_url('documents?parent_id=' . $path_item['id']) ?>">
                        <?= esc($path_item['name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <li class="breadcrumb-item active" aria-current="page"><?= esc($document['title']) ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8">
            <!-- Document Preview -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-file me-2"></i> Document Preview</h5>
                    <div>
                        <a href="<?= base_url('documents/file/download/' . $document['id']) ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php
                    $fileExtension = strtolower(pathinfo($document['original_name'], PATHINFO_EXTENSION));
                    $filePath = base_url('public/uploads/documents/' . $document['folder_id'] . '/' . $document['file_name']);
                    
                    if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        // Image preview
                        echo '<div class="text-center p-3">';
                        echo '<img src="' . $filePath . '" class="img-fluid" alt="' . esc($document['title']) . '">';
                        echo '</div>';
                    } elseif ($fileExtension === 'pdf') {
                        // PDF preview
                        echo '<div class="ratio ratio-16x9" style="min-height: 500px;">';
                        echo '<iframe src="' . $filePath . '" allowfullscreen></iframe>';
                        echo '</div>';
                    } else {
                        // File type not previewable
                        echo '<div class="text-center p-5">';
                        echo '<div class="display-1 text-muted mb-3">';
                        
                        // Set icon based on file type
                        $iconClass = 'fa-file';
                        if (in_array($fileExtension, ['doc', 'docx'])) {
                            $iconClass = 'fa-file-word';
                        } elseif (in_array($fileExtension, ['xls', 'xlsx'])) {
                            $iconClass = 'fa-file-excel';
                        } elseif (in_array($fileExtension, ['ppt', 'pptx'])) {
                            $iconClass = 'fa-file-powerpoint';
                        } elseif (in_array($fileExtension, ['txt'])) {
                            $iconClass = 'fa-file-alt';
                        } elseif (in_array($fileExtension, ['zip', 'rar', '7z'])) {
                            $iconClass = 'fa-file-archive';
                        }
                        
                        echo '<i class="fas ' . $iconClass . '"></i>';
                        echo '</div>';
                        echo '<h4 class="mb-3">' . strtoupper($fileExtension) . ' File</h4>';
                        echo '<p class="text-muted mb-3">Preview not available for this file type.</p>';
                        echo '<a href="' . base_url('documents/file/download/' . $document['id']) . '" class="btn btn-primary">';
                        echo '<i class="fas fa-download me-2"></i> Download to View';
                        echo '</a>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Document Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Document Information</h5>
                </div>
                <div class="card-body">
                    <h4 class="mb-3"><?= esc($document['title']) ?></h4>
                    
                    <?php if (!empty($document['description'])): ?>
                        <p class="text-muted"><?= esc($document['description']) ?></p>
                        <hr>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-5 text-muted">Classification</div>
                            <div class="col-7">
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
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-5 text-muted">Folder</div>
                            <div class="col-7">
                                <a href="<?= base_url('documents?parent_id=' . $document['folder_id']) ?>">
                                    <?= esc($document['folder_name']) ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-5 text-muted">Branch</div>
                            <div class="col-7"><?= esc($document['branch_name'] ?? 'N/A') ?></div>
                        </div>
                    </div>
                    
                    <?php if (!empty($document['authors'])): ?>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-5 text-muted">Authors</div>
                                <div class="col-7"><?= esc($document['authors']) ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($document['doc_date'])): ?>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-5 text-muted">Document Date</div>
                                <div class="col-7"><?= date('F j, Y', strtotime($document['doc_date'])) ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-5 text-muted">File Type</div>
                            <div class="col-7">
                                <?php 
                                $fileType = strtolower(pathinfo($document['original_name'], PATHINFO_EXTENSION));
                                $iconClass = 'fa-file';
                                
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
                                ?>
                                <i class="fas <?= $iconClass ?> me-1"></i> <?= strtoupper($fileType) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-5 text-muted">File Size</div>
                            <div class="col-7">
                                <?php
                                $size = $document['file_size'];
                                if ($size < 1024) {
                                    echo $size . ' B';
                                } elseif ($size < 1048576) {
                                    echo round($size / 1024, 2) . ' KB';
                                } else {
                                    echo round($size / 1048576, 2) . ' MB';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-5 text-muted">Original Name</div>
                            <div class="col-7 text-truncate" title="<?= esc($document['original_name']) ?>">
                                <?= esc($document['original_name']) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-5 text-muted">Upload Date</div>
                            <div class="col-7"><?= date('F j, Y', strtotime($document['created_at'])) ?></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="<?= base_url('documents?parent_id=' . $document['folder_id']) ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Folder
                        </a>
                        <a href="<?= base_url('documents/file/delete/' . $document['id']) ?>" class="btn btn-danger" 
                            onclick="return confirm('Are you sure you want to delete this document?')">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 