# Infrastructure Implementation Feature - Complete Implementation Plan

## Table of Contents
1. [Current State Analysis](#current-state-analysis)
2. [Critical Issues Identified](#critical-issues-identified)
3. [Architecture Overview](#architecture-overview)
4. [Implementation Roadmap](#implementation-roadmap)
5. [Detailed Implementation Steps](#detailed-implementation-steps)
6. [Code Examples](#code-examples)
7. [Testing Strategy](#testing-strategy)
8. [File Structure](#file-structure)

## Current State Analysis

### ✅ What Already Exists
- **Model**: `ActivitiesInfrastructureModel.php` - Fully implemented with JSON field handling
- **Database**: `activities_infrastructure` table with proper schema
- **Details View**: `infrastructures_details.php` exists but has schema mismatches
- **Controller Structure**: Basic infrastructure cases exist but with critical issues

### ❌ What's Missing/Broken
- **Model Integration**: ActivitiesInfrastructureModel not loaded in controller
- **Implementation View**: `infrastructures_implementation.php` doesn't exist
- **Controller Logic**: Wrong model usage and method signatures
- **Data Flow**: Infrastructure cases use wrong model throughout

## Critical Issues Identified

### 🚨 Issue 1: Missing Model in Controller Constructor
**Problem**: ActivitiesInfrastructureModel is not initialized in ActivitiesController
**Impact**: Cannot use the correct model for infrastructure operations
**Location**: `app/Controllers/ActivitiesController.php` line 68

### 🚨 Issue 2: Wrong Model Usage Throughout Controller
**Problem**: All infrastructure cases use `workplanInfrastructureActivityModel` instead of `activitiesInfrastructureModel`
**Impact**: Data operations target wrong table/model
**Locations**: Lines 291, 672, 1311, 1462, 1558

### 🚨 Issue 3: Missing Implementation View
**Problem**: `infrastructures_implementation.php` view file doesn't exist
**Impact**: Cannot display implementation form for infrastructure activities
**Expected Location**: `app/Views/activities/implementations/infrastructures_implementation.php`

### 🚨 Issue 4: Wrong Method Signature
**Problem**: `saveInfrastructureImplementation()` has wrong parameters
**Current**: `saveInfrastructureImplementation($proposal, $activity)`
**Expected**: `saveInfrastructureImplementation($activity)`
**Impact**: Method signature doesn't match other activity types

### 🚨 Issue 5: Schema Mismatch in Details View
**Problem**: Details view expects fields not in database schema
**Database Fields**: infrastructure, gps_coordinates, infrastructure_images, infrastructure_files, signing_scheet_filepath
**View Expects**: title, infrastructure_type, location, contractor, total_cost, completion_date, description, specifications

## Architecture Overview

### MVC Pattern for Infrastructure Implementation
```
Controller (ActivitiesController)
├── implement() - Display implementation form with existing data
├── saveInfrastructureImplementation() - Process form submission
└── show() - Display activity with implementation details

Model (ActivitiesInfrastructureModel)
├── JSON field handling (infrastructure_images, infrastructure_files)
├── Data validation rules
└── Callback methods for encoding/decoding

View Structure
├── implementations/
│   └── infrastructures_implementation.php - Form view (MISSING)
└── implementation/
    └── infrastructures_details.php - Details view (NEEDS UPDATE)
```

### Database Schema
```sql
CREATE TABLE activities_infrastructure (
    id INT PRIMARY KEY AUTO_INCREMENT,
    activity_id INT,
    infrastructure VARCHAR(255) NOT NULL,
    gps_coordinates VARCHAR(100),
    infrastructure_images LONGTEXT, -- JSON field
    infrastructure_files LONGTEXT,  -- JSON field
    signing_scheet_filepath VARCHAR(500),
    created_at DATETIME,
    created_by INT,
    updated_at DATETIME,
    updated_by INT,
    deleted_at DATETIME,
    deleted_by INT
);
```

## Implementation Roadmap

### Phase 1: Fix Controller Foundation (Priority: Critical)
1. Add ActivitiesInfrastructureModel to controller constructor
2. Fix all infrastructure cases to use correct model
3. Fix saveInfrastructureImplementation method signature
4. Add infrastructure case to implement() method

### Phase 2: Create Implementation View (Priority: High)
1. Create infrastructures_implementation.php view file
2. Implement form with proper field mapping
3. Add file upload functionality
4. Add dynamic sections if needed

### Phase 3: Update Details View (Priority: Medium)
1. Update infrastructures_details.php to match database schema
2. Fix field mappings
3. Ensure proper JSON data display
4. Add image gallery functionality

### Phase 4: Testing & Validation (Priority: High)
1. Test form submission and data saving
2. Test file uploads
3. Test data display in details view
4. Test edit functionality

## Detailed Implementation Steps

### Step 1: Fix Controller Model Integration

#### 1.1 Add Model to Constructor
**File**: `app/Controllers/ActivitiesController.php`
**Location**: After line 68
**Action**: Add model initialization

#### 1.2 Fix Infrastructure Cases in Methods
**Methods to Update**:
- `show()` method (around line 291)
- `saveImplementation()` method (around line 672)
- `implement()` method (missing infrastructure case)
- `supervise()` method (around line 1311)
- `viewActivity()` method (around lines 1462, 1558)

### Step 2: Create Implementation View

#### 2.1 Create View File
**File**: `app/Views/activities/implementations/infrastructures_implementation.php`
**Pattern**: Follow meetings_implementation.php structure
**Key Features**:
- Infrastructure description field
- GPS coordinates input
- Multiple image upload
- Multiple file upload
- Signing sheet upload
- Remarks field

### Step 3: Fix Method Implementation

#### 3.1 Update saveInfrastructureImplementation Method
**Current Issues**:
- Wrong signature: `($proposal, $activity)` → `($activity)`
- Uses wrong model: `workplanInfrastructureActivityModel` → `activitiesInfrastructureModel`
- Wrong data structure for activities table

### Step 4: Update Details View

#### 4.1 Fix Field Mappings
**File**: `app/Views/activities/implementation/infrastructures_details.php`
**Changes Needed**:
- Replace non-existent fields with actual database fields
- Fix JSON data display
- Update image gallery to use correct field names

## Code Examples

### Controller Constructor Fix
```php
// Add to ActivitiesController constructor after line 68
$this->activitiesInfrastructureModel = new ActivitiesInfrastructureModel();
```

### Infrastructure Case for implement() Method
```php
// Add to implement() method around line 540
} elseif ($activity['type'] === 'infrastructures') {
    $implementationData = $this->activitiesInfrastructureModel
        ->where('activity_id', $activity['id'])
        ->first();
    
    // ActivitiesInfrastructureModel automatically decodes JSON fields via afterFind callback
    // No manual JSON decoding needed
```

### Fixed saveInfrastructureImplementation Method Signature
```php
// Replace existing method with correct signature
private function saveInfrastructureImplementation($activity)
{
    $userId = session()->get('user_id');
    
    // Validation rules
    $validationRules = [
        'infrastructure' => 'required|max_length[255]',
        'gps_coordinates' => 'permit_empty|max_length[100]',
        'signing_sheet' => 'permit_empty|uploaded[signing_sheet]|max_size[signing_sheet,5120]'
    ];
    
    // ... rest of implementation
}
```

## Testing Strategy

### Manual Testing Checklist
- [ ] Infrastructure activity form loads correctly
- [ ] Existing data pre-populates in edit mode
- [ ] Image uploads work and store correctly
- [ ] File uploads work and store correctly
- [ ] GPS coordinates save properly
- [ ] Signing sheet upload functions
- [ ] Form validation works
- [ ] Data saves to correct table
- [ ] Details view displays all data correctly
- [ ] Image gallery works
- [ ] File downloads work

### Test Data Requirements
- Create test infrastructure activity
- Upload test images (JPG, PNG)
- Upload test files (PDF, DOC)
- Test with and without GPS coordinates
- Test with and without signing sheet

## File Structure

### Files to Create
```
app/Views/activities/implementations/
└── infrastructures_implementation.php  # NEW - Implementation form

public/uploads/
└── infrastructure_files/               # NEW - File upload directory
└── infrastructure_images/              # NEW - Image upload directory
└── signing_sheets/                     # EXISTING - Signing sheet directory
```

### Files to Modify
```
app/Controllers/
└── ActivitiesController.php            # MODIFY - Add model, fix methods

app/Views/activities/implementation/
└── infrastructures_details.php         # MODIFY - Fix field mappings
```

### Critical Success Factors
1. **Model Integration**: Ensure ActivitiesInfrastructureModel is properly loaded and used
2. **Data Consistency**: All infrastructure operations must use the same model
3. **Field Mapping**: Views must match actual database schema
4. **File Handling**: Proper file upload and storage implementation
5. **User Experience**: Consistent interface with other activity types

This implementation plan provides a complete roadmap for implementing the Infrastructure Implementation Feature while addressing all identified critical issues and following established patterns from the existing codebase.

## Detailed Code Implementation

### Complete Controller Fixes

#### Fix 1: Constructor Update
```php
// File: app/Controllers/ActivitiesController.php
// Location: After line 68, add:
$this->activitiesInfrastructureModel = new ActivitiesInfrastructureModel();
```

#### Fix 2: implement() Method - Add Infrastructure Case
```php
// File: app/Controllers/ActivitiesController.php
// Location: Around line 540, add after inputs case:
} elseif ($activity['type'] === 'infrastructures') {
    $implementationData = $this->activitiesInfrastructureModel
        ->where('activity_id', $activity['id'])
        ->first();

    // ActivitiesInfrastructureModel automatically decodes JSON fields via afterFind callback
    // No manual JSON decoding needed
```

#### Fix 3: show() Method - Fix Infrastructure Case
```php
// File: app/Controllers/ActivitiesController.php
// Location: Around line 291, replace:
} elseif ($activity['type'] === 'infrastructures') {
    $implementationData = $this->activitiesInfrastructureModel
        ->where('activity_id', $activity['id'])
        ->first();

    // ActivitiesInfrastructureModel automatically decodes JSON fields via afterFind callback
    // No manual JSON decoding needed
```

#### Fix 4: Complete saveInfrastructureImplementation Method
```php
// File: app/Controllers/ActivitiesController.php
// Replace existing method completely:
private function saveInfrastructureImplementation($activity)
{
    $userId = session()->get('user_id');

    // Validation rules
    $validationRules = [
        'infrastructure' => 'required|max_length[255]',
        'gps_coordinates' => 'permit_empty|max_length[100]',
        'signing_sheet' => 'permit_empty|uploaded[signing_sheet]|max_size[signing_sheet,5120]'
    ];

    if (!$this->validate($validationRules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Check for existing record
    $existingRecord = $this->activitiesInfrastructureModel
        ->where('activity_id', $activity['id'])
        ->first();

    // Handle infrastructure images
    $infrastructureImages = [];
    if ($existingRecord && !empty($existingRecord['infrastructure_images'])) {
        $infrastructureImages = is_array($existingRecord['infrastructure_images']) ?
            $existingRecord['infrastructure_images'] :
            (json_decode($existingRecord['infrastructure_images'], true) ?: []);
    }

    $imageFiles = $this->request->getFiles();
    if (isset($imageFiles['infrastructure_images'])) {
        foreach ($imageFiles['infrastructure_images'] as $file) {
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $uploadPath = ROOTPATH . 'public/uploads/infrastructure_images';

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $file->move($uploadPath, $newName);
                $infrastructureImages[] = 'public/uploads/infrastructure_images/' . $newName;
            }
        }
    }

    // Handle infrastructure files
    $infrastructureFiles = [];
    if ($existingRecord && !empty($existingRecord['infrastructure_files'])) {
        $infrastructureFiles = is_array($existingRecord['infrastructure_files']) ?
            $existingRecord['infrastructure_files'] :
            (json_decode($existingRecord['infrastructure_files'], true) ?: []);
    }

    $documentFiles = $this->request->getFiles();
    if (isset($documentFiles['infrastructure_files'])) {
        $fileDescriptions = $this->request->getPost('file_descriptions') ?: [];

        foreach ($documentFiles['infrastructure_files'] as $index => $file) {
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $uploadPath = ROOTPATH . 'public/uploads/infrastructure_files';

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $file->move($uploadPath, $newName);

                $infrastructureFiles[] = [
                    'filename' => $fileDescriptions[$index] ?? $file->getClientName(),
                    'original_name' => $file->getClientName(),
                    'path' => 'public/uploads/infrastructure_files/' . $newName
                ];
            }
        }
    }

    // Handle signing sheet
    $signingSheetFilepath = $existingRecord['signing_scheet_filepath'] ?? null;
    $signingSheetFile = $this->request->getFile('signing_sheet');
    if ($signingSheetFile && $signingSheetFile->isValid() && !$signingSheetFile->hasMoved()) {
        $newName = $signingSheetFile->getRandomName();
        $uploadPath = ROOTPATH . 'public/uploads/signing_sheets';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $signingSheetFile->move($uploadPath, $newName);
        $signingSheetFilepath = 'public/uploads/signing_sheets/' . $newName;
    }

    // Prepare data
    $data = [
        'activity_id' => $activity['id'],
        'infrastructure' => $this->request->getPost('infrastructure'),
        'gps_coordinates' => $this->request->getPost('gps_coordinates'),
        'infrastructure_images' => $infrastructureImages,
        'infrastructure_files' => $infrastructureFiles,
        'signing_scheet_filepath' => $signingSheetFilepath,
        'created_by' => $userId,
        'updated_by' => $userId
    ];

    if ($existingRecord) {
        $data['id'] = $existingRecord['id'];
    }

    // Save data
    if ($this->activitiesInfrastructureModel->save($data)) {
        $this->activitiesModel->update($activity['id'], [
            'status' => 'active',
            'updated_by' => $userId
        ]);

        return redirect()->to('/activities/' . $activity['id'])
            ->with('success', 'Infrastructure implementation saved successfully.');
    } else {
        return redirect()->back()->withInput()
            ->with('error', 'Failed to save infrastructure implementation: ' .
                implode(', ', $this->activitiesInfrastructureModel->errors()));
    }
}
```

### Implementation View Template

#### Complete infrastructures_implementation.php
```php
<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="mb-3">
        <a href="<?= base_url('activities/' . $activity['id']) ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Activity Details
        </a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Implement Infrastructure Activity: <?= esc($activity['activity_title']) ?></h5>
                </div>
                <div class="card-body">
                    <!-- Show existing implementation data if exists -->
                    <?php if ($implementationData): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This infrastructure activity has already been implemented. You can view the details below or update the implementation.
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h6 class="fw-bold">Current Implementation</h6>
                                <div class="card">
                                    <div class="card-body">
                                        <?= $this->include('activities/implementation/infrastructures_details') ?>
                                        <div class="text-muted">
                                            <small>Implemented on: <?= date('d M Y H:i', strtotime($implementationData['created_at'])) ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Implementation Form -->
                    <form action="<?= base_url('activities/' . $activity['id'] . '/save-implementation') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <h6 class="fw-bold mb-3">Infrastructure Implementation</h6>

                        <!-- Infrastructure Description -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Infrastructure Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="infrastructure" rows="3" required><?= old('infrastructure', $implementationData['infrastructure'] ?? '') ?></textarea>
                                <div class="form-text">Provide detailed description of the infrastructure implemented</div>
                            </div>
                        </div>

                        <!-- GPS Coordinates -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">GPS Coordinates</label>
                                <input type="text" class="form-control" name="gps_coordinates"
                                       value="<?= old('gps_coordinates', $implementationData['gps_coordinates'] ?? '') ?>"
                                       placeholder="e.g., -1.2921, 36.8219">
                                <div class="form-text">Optional: Latitude, Longitude coordinates</div>
                            </div>
                        </div>

                        <!-- Infrastructure Images -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Infrastructure Images</label>
                                <input type="file" class="form-control" name="infrastructure_images[]" multiple accept="image/*">
                                <div class="form-text">Upload multiple images of the infrastructure (JPG, PNG, GIF)</div>

                                <!-- Show existing images -->
                                <?php if (!empty($implementationData['infrastructure_images'])): ?>
                                <div class="mt-2">
                                    <small class="text-muted">Existing images:</small>
                                    <div class="row">
                                        <?php foreach ($implementationData['infrastructure_images'] as $index => $image): ?>
                                        <div class="col-md-2 mb-2">
                                            <img src="<?= base_url($image) ?>" class="img-thumbnail" style="height: 80px; object-fit: cover;">
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Infrastructure Files -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Infrastructure Documents</label>
                                <div id="infrastructureFilesContainer">
                                    <div class="infrastructure-file-item mb-2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="file" class="form-control" name="infrastructure_files[]" accept=".pdf,.doc,.docx,.xls,.xlsx">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="file_descriptions[]" placeholder="File description">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-outline-danger remove-file-btn" style="display: none;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="addInfrastructureFileBtn">
                                    <i class="fas fa-plus"></i> Add Another File
                                </button>

                                <!-- Show existing files -->
                                <?php if (!empty($implementationData['infrastructure_files'])): ?>
                                <div class="mt-2">
                                    <small class="text-muted">Existing files:</small>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($implementationData['infrastructure_files'] as $file): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= esc($file['filename']) ?>
                                            <a href="<?= base_url($file['path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Signing Sheet -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Signing Sheet</label>
                                <input type="file" class="form-control" name="signing_sheet" accept=".pdf,.jpg,.jpeg,.png">
                                <div class="form-text">Upload signed attendance/completion sheet</div>

                                <!-- Show existing signing sheet -->
                                <?php if (!empty($implementationData['signing_scheet_filepath'])): ?>
                                <div class="mt-2">
                                    <small class="text-muted">Current signing sheet:</small>
                                    <a href="<?= base_url($implementationData['signing_scheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Infrastructure Implementation
                                </button>
                                <a href="<?= base_url('activities/' . $activity['id']) ?>" class="btn btn-secondary ms-2">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add infrastructure file functionality
    const addFileBtn = document.getElementById('addInfrastructureFileBtn');
    const filesContainer = document.getElementById('infrastructureFilesContainer');

    addFileBtn.addEventListener('click', function() {
        const fileItem = document.createElement('div');
        fileItem.className = 'infrastructure-file-item mb-2';
        fileItem.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <input type="file" class="form-control" name="infrastructure_files[]" accept=".pdf,.doc,.docx,.xls,.xlsx">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="file_descriptions[]" placeholder="File description">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger remove-file-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        filesContainer.appendChild(fileItem);
        updateRemoveFileButtons();
    });

    // Remove file functionality
    filesContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-file-btn')) {
            e.target.closest('.infrastructure-file-item').remove();
            updateRemoveFileButtons();
        }
    });

    function updateRemoveFileButtons() {
        const fileItems = document.querySelectorAll('.infrastructure-file-item');
        fileItems.forEach((item) => {
            const removeBtn = item.querySelector('.remove-file-btn');
            if (removeBtn) {
                removeBtn.style.display = fileItems.length > 1 ? 'inline-block' : 'none';
            }
        });
    }

    // Initialize remove buttons
    updateRemoveFileButtons();
});
</script>

<?= $this->endSection() ?>
```

### Updated Details View

#### Fixed infrastructures_details.php
```php
<!-- Infrastructure Implementation Details -->
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <strong>Infrastructure Description:</strong>
            <p class="text-muted"><?= nl2br(esc($implementationData['infrastructure'] ?? 'N/A')) ?></p>
        </div>
    </div>
    <div class="col-md-6">
        <?php if (!empty($implementationData['gps_coordinates'])): ?>
        <div class="mb-3">
            <strong>GPS Coordinates:</strong>
            <p class="text-muted"><?= esc($implementationData['gps_coordinates']) ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($implementationData['infrastructure_files'])): ?>
<div class="mb-3">
    <strong>Infrastructure Documents (<?= count($implementationData['infrastructure_files']) ?> files):</strong>
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
                <?php foreach ($implementationData['infrastructure_files'] as $index => $file): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($file['filename']) ?></td>
                    <td><?= esc($file['original_name']) ?></td>
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

<?php if (!empty($implementationData['infrastructure_images'])): ?>
<div class="mb-3">
    <strong>Infrastructure Images (<?= count($implementationData['infrastructure_images']) ?> images):</strong>
    <div class="row">
        <?php foreach ($implementationData['infrastructure_images'] as $index => $image): ?>
        <div class="col-md-3 mb-2">
            <div class="card">
                <img src="<?= base_url($image) ?>" class="card-img-top infrastructure-image"
                     style="height: 150px; object-fit: cover; cursor: pointer;"
                     alt="Infrastructure Image"
                     data-bs-toggle="modal"
                     data-bs-target="#infrastructureImageModal"
                     data-image-src="<?= base_url($image) ?>">
                <div class="card-body p-2">
                    <small class="text-muted">Image <?= $index + 1 ?></small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($implementationData['signing_scheet_filepath'])): ?>
<div class="mb-3">
    <strong>Signing Sheet:</strong>
    <div>
        <a href="<?= base_url($implementationData['signing_scheet_filepath']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-download"></i> Download Signing Sheet
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Infrastructure Image Modal -->
<div class="modal fade" id="infrastructureImageModal" tabindex="-1" aria-labelledby="infrastructureImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infrastructureImageModalLabel">Infrastructure Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="infrastructureModalImage" src="" class="img-fluid" alt="Infrastructure Image">
            </div>
        </div>
    </div>
</div>

<script>
// Handle infrastructure image modal
document.addEventListener('DOMContentLoaded', function() {
    const infrastructureImageModal = document.getElementById('infrastructureImageModal');
    if (infrastructureImageModal) {
        infrastructureImageModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const imageSrc = button.getAttribute('data-image-src');
            const modalImage = document.getElementById('infrastructureModalImage');
            modalImage.src = imageSrc;
        });
    }
});
</script>
```

## Implementation Priority Matrix

### Critical (Must Fix First)
1. **Controller Model Integration** - Without this, nothing works
2. **Method Signature Fix** - Prevents fatal errors
3. **Implementation View Creation** - Enables form display

### High Priority (Fix Next)
1. **Details View Update** - Ensures proper data display
2. **File Upload Directories** - Enables file storage
3. **All Controller Method Updates** - Ensures consistency

### Medium Priority (Polish)
1. **Error Handling Enhancement** - Better user experience
2. **Validation Improvements** - Data integrity
3. **UI/UX Refinements** - Professional appearance

## Success Metrics

### Technical Metrics
- [ ] All infrastructure controller methods use correct model
- [ ] Implementation form loads without errors
- [ ] Data saves to activities_infrastructure table
- [ ] File uploads work correctly
- [ ] Details view displays all data properly

### User Experience Metrics
- [ ] Form is intuitive and easy to use
- [ ] Existing data pre-populates correctly
- [ ] File uploads provide clear feedback
- [ ] Error messages are helpful
- [ ] Success confirmations are clear

### Data Integrity Metrics
- [ ] JSON fields encode/decode properly
- [ ] File paths store correctly with public/ prefix
- [ ] GPS coordinates validate properly
- [ ] Activity status updates correctly
- [ ] Audit fields populate correctly

This comprehensive implementation plan addresses all critical issues and provides a clear path to successful infrastructure implementation feature completion.
