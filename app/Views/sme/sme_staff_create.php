<?php
// app/Views/sme/sme_staff_create.php
?>
<?= $this->extend('templates/system_template') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('smes') ?>">SMEs</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('smes/staff/' . $sme_id) ?>">Staff</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Staff</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Add Staff Member</h3>
                    <a href="<?= base_url('smes/staff/' . $sme_id) ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Staff
                    </a>
                </div>
        <div class="card-body">
            <form action="<?= base_url('smes/staff/' . $sme_id) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <!-- Personal Information -->
                <h5 class="border-bottom pb-2 mb-3">Personal Information</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="fname">First Name <span class="text-danger">*</span></label>
                        <input type="text" id="fname" name="fname" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="lname">Last Name <span class="text-danger">*</span></label>
                        <input type="text" id="lname" name="lname" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="gender">Gender <span class="text-danger">*</span></label>
                        <select id="gender" name="gender" class="form-select" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="dobirth">Date of Birth</label>
                        <input type="date" id="dobirth" name="dobirth" class="form-control">
                    </div>
                </div>

                <!-- Professional Information -->
                <h5 class="border-bottom pb-2 mb-3">Professional Information</h5>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="designation">Designation/Position</label>
                        <input type="text" id="designation" name="designation" class="form-control"
                               placeholder="e.g., Manager, Accountant, Sales Representative">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="contacts">Contact Information</label>
                        <textarea id="contacts" name="contacts" class="form-control" rows="2"
                                placeholder="Phone numbers, email addresses, etc."></textarea>
                    </div>
                </div>

                <!-- Additional Information -->
                <h5 class="border-bottom pb-2 mb-3">Additional Information</h5>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label" for="remarks">Remarks/Notes</label>
                        <textarea id="remarks" name="remarks" class="form-control" rows="3"
                                placeholder="Any additional information about this staff member"></textarea>
                    </div>
                </div>

                <!-- Photo Upload -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label class="form-label" for="id_photo">ID Photo</label>
                        <input type="file" id="id_photo" name="id_photo" class="form-control" accept="image/*">
                        <small class="text-muted">Maximum file size: 5MB. Accepted formats: JPG, PNG</small>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('smes/staff/' . $sme_id) ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Staff Member</button>
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
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (session()->getFlashdata('success')): ?>
            toastr.success('<?= session()->getFlashdata('success') ?>');
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            toastr.error('<?= session()->getFlashdata('error') ?>');
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                toastr.error('<?= esc($error) ?>');
            <?php endforeach; ?>
        <?php endif; ?>
    });
</script>
<?= $this->endSection() ?>