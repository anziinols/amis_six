<?php
// app/Views/documents/documents_folder_create.php
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
            <li class="breadcrumb-item active" aria-current="page">Create New Folder</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-folder-plus me-2"></i> Create New Folder</h5>
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

                    <form action="<?= base_url('documents/create') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <?php if ($parent_id): ?>
                            <input type="hidden" name="parent_id" value="<?= $parent_id ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Folder Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= (isset(session('errors')['name'])) ? 'is-invalid' : '' ?>" 
                                id="name" name="name" value="<?= old('name') ?>" required>
                            <?php if (isset(session('errors')['name'])): ?>
                                <div class="invalid-feedback"><?= session('errors')['name'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="branch_id" class="form-label">Branch <span class="text-danger">*</span></label>
                            <select class="form-select <?= (isset(session('errors')['branch_id'])) ? 'is-invalid' : '' ?>" 
                                id="branch_id" name="branch_id" required>
                                <option value="" selected disabled>Select Branch</option>
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?= $branch['id'] ?>" <?= old('branch_id') == $branch['id'] ? 'selected' : '' ?>>
                                        <?= esc($branch['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset(session('errors')['branch_id'])): ?>
                                <div class="invalid-feedback"><?= session('errors')['branch_id'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control <?= (isset(session('errors')['description'])) ? 'is-invalid' : '' ?>" 
                                id="description" name="description" rows="3"><?= old('description') ?></textarea>
                            <?php if (isset(session('errors')['description'])): ?>
                                <div class="invalid-feedback"><?= session('errors')['description'] ?></div>
                            <?php endif; ?>
                            <div class="form-text">Briefly describe the purpose of this folder.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Access Level <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="access" id="access_private" 
                                        value="private" <?= old('access', 'private') == 'private' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="access_private">
                                        <span class="badge bg-danger">Private</span>
                                        <small class="d-block text-muted">Only you can view</small>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="access" id="access_internal" 
                                        value="internal" <?= old('access') == 'internal' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="access_internal">
                                        <span class="badge bg-warning text-dark">Internal</span>
                                        <small class="d-block text-muted">Only staff can view</small>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="access" id="access_public" 
                                        value="public" <?= old('access') == 'public' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="access_public">
                                        <span class="badge bg-success">Public</span>
                                        <small class="d-block text-muted">Everyone can view</small>
                                    </label>
                                </div>
                            </div>
                            <?php if (isset(session('errors')['access'])): ?>
                                <div class="text-danger mt-1"><?= session('errors')['access'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <?php if ($parent_id): ?>
                                <a href="<?= base_url('documents?parent_id=' . $parent_id) ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Folder
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('documents') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Documents
                                </a>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Create Folder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 