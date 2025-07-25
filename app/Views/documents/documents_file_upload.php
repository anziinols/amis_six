<?php
// app/Views/documents/documents_file_upload.php
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
            <li class="breadcrumb-item active" aria-current="page">Upload Document</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-file-upload me-2"></i> Upload Document to "<?= esc($folder['name']) ?>"</h5>
                </div>
                <div class="card-body">
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('documents/file/create') ?>" method="post" enctype="multipart/form-data" id="uploadForm">
                        <?= csrf_field() ?>
                        <input type="hidden" name="folder_id" value="<?= $folder['id'] ?>">
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Document Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?= (isset(session('errors')['title'])) ? 'is-invalid' : '' ?>" 
                                        id="title" name="title" value="<?= old('title') ?>" required>
                                    <?php if (isset(session('errors')['title'])): ?>
                                        <div class="invalid-feedback"><?= session('errors')['title'] ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($user_branch_id): ?>
                                    <div class="mb-3">
                                        <label class="form-label">Branch</label>
                                        <div class="form-control-plaintext bg-light p-2 rounded">
                                            <i class="fas fa-building me-2"></i>
                                            <span class="text-muted">Automatically assigned to your branch</span>
                                        </div>
                                        <input type="hidden" name="branch_id" value="<?= $user_branch_id ?>">
                                    </div>
                                <?php else: ?>
                                    <div class="mb-3">
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            No branch assigned to your account. Please contact administrator.
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <label for="doc_date" class="form-label">Document Date</label>
                                    <input type="date" class="form-control <?= (isset(session('errors')['doc_date'])) ? 'is-invalid' : '' ?>" 
                                        id="doc_date" name="doc_date" value="<?= old('doc_date') ?>">
                                    <?php if (isset(session('errors')['doc_date'])): ?>
                                        <div class="invalid-feedback"><?= session('errors')['doc_date'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="authors" class="form-label">Authors</label>
                                    <input type="text" class="form-control <?= (isset(session('errors')['authors'])) ? 'is-invalid' : '' ?>" 
                                        id="authors" name="authors" value="<?= old('authors') ?>">
                                    <?php if (isset(session('errors')['authors'])): ?>
                                        <div class="invalid-feedback"><?= session('errors')['authors'] ?></div>
                                    <?php endif; ?>
                                    <div class="form-text">Separate multiple authors with commas</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Classification <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="classification" id="classification_private" 
                                                value="private" <?= old('classification', 'private') == 'private' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="classification_private">
                                                <span class="badge bg-danger">Private</span>
                                                <small class="d-block text-muted">Only you can view</small>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="classification" id="classification_internal" 
                                                value="internal" <?= old('classification') == 'internal' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="classification_internal">
                                                <span class="badge bg-warning text-dark">Internal</span>
                                                <small class="d-block text-muted">Only staff can view</small>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="classification" id="classification_public" 
                                                value="public" <?= old('classification') == 'public' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="classification_public">
                                                <span class="badge bg-success">Public</span>
                                                <small class="d-block text-muted">Everyone can view</small>
                                            </label>
                                        </div>
                                    </div>
                                    <?php if (isset(session('errors')['classification'])): ?>
                                        <div class="text-danger mt-1"><?= session('errors')['classification'] ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control <?= (isset(session('errors')['description'])) ? 'is-invalid' : '' ?>" 
                                        id="description" name="description" rows="2"><?= old('description') ?></textarea>
                                    <?php if (isset(session('errors')['description'])): ?>
                                        <div class="invalid-feedback"><?= session('errors')['description'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Document File <span class="text-danger">*</span></label>
                            <div class="upload-area p-4 border border-2 border-dashed rounded text-center" id="dropArea">
                                <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-muted"></i>
                                <h5>Drag & Drop your file here</h5>
                                <p class="text-muted mb-3">or click to browse for a file</p>
                                <input type="file" id="document_file" name="document_file" class="d-none">
                                <button type="button" id="browseFiles" class="btn btn-outline-primary">Browse Files</button>
                                <div class="mt-3 text-muted small">
                                    Allowed files: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, JPEG, PNG<br>
                                    Maximum file size: 10MB
                                </div>
                                <div id="file-preview" class="mt-3 d-none">
                                    <div class="alert alert-success d-flex align-items-center">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <div class="file-info flex-grow-1"></div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="removeFile">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php if (isset(session('errors')['document_file'])): ?>
                                <div class="text-danger mt-1"><?= session('errors')['document_file'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= base_url('documents?parent_id=' . $folder['id']) ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Folder
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i> Upload Document
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Variables
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('document_file');
        const browseBtn = document.getElementById('browseFiles');
        const removeBtn = document.getElementById('removeFile');
        const filePreview = document.getElementById('file-preview');
        const fileInfo = document.querySelector('.file-info');
        
        // Open file browser when clicking the browse button
        browseBtn.addEventListener('click', () => {
            fileInput.click();
        });
        
        // File select handler
        fileInput.addEventListener('change', handleFiles);
        
        // Prevent defaults for drag events
        ['dragover', 'dragleave', 'dragend'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });
        
        // Highlight drop area when dragging over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });
        
        // Handle dropped files
        dropArea.addEventListener('drop', handleDrop, false);
        
        // Remove file handler
        removeBtn.addEventListener('click', removeFile);
        
        // Functions
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        function highlight() {
            dropArea.classList.add('bg-light');
        }
        
        function unhighlight() {
            dropArea.classList.remove('bg-light');
        }
        
        function handleDrop(e) {
            preventDefaults(e);
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files;
                updateFilePreview(files[0]);
            }
        }
        
        function handleFiles() {
            const files = this.files;
            if (files.length > 0) {
                updateFilePreview(files[0]);
            }
        }
        
        function updateFilePreview(file) {
            // Show file preview section
            filePreview.classList.remove('d-none');
            
            // Format file size
            let fileSize = formatFileSize(file.size);
            
            // Update file info
            fileInfo.innerHTML = `
                <div><strong>${file.name}</strong></div>
                <div class="small">${fileSize} | ${file.type || 'Unknown'}</div>
            `;
        }
        
        function removeFile() {
            fileInput.value = '';
            filePreview.classList.add('d-none');
        }
        
        function formatFileSize(bytes) {
            if (bytes < 1024) {
                return bytes + ' bytes';
            } else if (bytes < 1048576) {
                return (bytes / 1024).toFixed(2) + ' KB';
            } else {
                return (bytes / 1048576).toFixed(2) + ' MB';
            }
        }
    });
</script>
<?= $this->endSection() ?> 