# Input Activities Implementation Plan

## Table of Contents
1. [Overview](#overview)
2. [Current State Analysis](#current-state-analysis)
3. [Database Structure](#database-structure)
4. [Implementation Architecture](#implementation-architecture)
5. [Required Changes](#required-changes)
6. [Implementation Steps](#implementation-steps)
7. [File Structure](#file-structure)
8. [Technical Specifications](#technical-specifications)
9. [Testing Plan](#testing-plan)
10. [Integration Points](#integration-points)

## Overview

This document provides a comprehensive implementation plan for the Input Activities Feature based on the existing Activities implementation guide. The plan addresses the current gaps in the input activities implementation and provides a roadmap for completing the feature.

### Key Objectives
- Complete the missing input activities implementation functionality
- Fix existing controller issues with input activities
- Ensure consistency with other activity types (meetings, trainings, etc.)
- Maintain compatibility with existing database structure
- Follow CodeIgniter 4 best practices and RESTful conventions

## Current State Analysis

### ✅ What Exists
1. **ActivitiesInputModel.php** - Fully implemented with proper JSON field handling
2. **inputs_details.php** - Implementation details view (needs updates)
3. **Database Table** - `activities_input` table with proper structure
4. **Controller Structure** - Basic framework exists but has critical issues

### ❌ What's Missing/Broken
1. **inputs_implementation.php** - Form view for implementing input activities
2. **Controller Issues:**
   - `saveInputImplementation()` method has wrong signature
   - Missing case in `implement()` method for fetching existing data
   - Missing case in `show()` method for displaying implementation
   - Using wrong model (WorkplanInputActivityModel instead of ActivitiesInputModel)
3. **View Inconsistencies** - inputs_details.php expects fields not in database

## Database Structure

### activities_input Table Schema
```sql
CREATE TABLE `activities_input` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `activity_id` int(11) DEFAULT NULL,
    `input_images` longtext DEFAULT NULL,      -- JSON field
    `input_files` longtext DEFAULT NULL,       -- JSON field  
    `inputs` longtext DEFAULT NULL,            -- JSON field
    `gps_coordinates` varchar(255) DEFAULT NULL,
    `signing_sheet_filepath` varchar(255) DEFAULT NULL,
    `created_at` datetime DEFAULT current_timestamp(),
    `created_by` int(11) DEFAULT NULL,
    `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `updated_by` int(11) DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    `deleted_by` int(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
);
```

### JSON Field Structures

#### inputs Field
```json
[
    {
        "name": "Fertilizer",
        "quantity": "100",
        "unit": "bags",
        "remarks": "NPK fertilizer for maize"
    },
    {
        "name": "Seeds",
        "quantity": "50",
        "unit": "kg",
        "remarks": "Hybrid maize seeds"
    }
]
```

#### input_files Field
```json
[
    {
        "caption": "Purchase Receipt",
        "original_name": "receipt_001.pdf",
        "file_path": "public/uploads/input_files/random_name.pdf"
    }
]
```

#### input_images Field
```json
[
    "public/uploads/input_images/image1.jpg",
    "public/uploads/input_images/image2.jpg"
]
```

## Implementation Architecture

### Controller Architecture Decision
**Decision: Extend existing ActivitiesController**

**Rationale:**
- Maintains consistency with other activity types
- Leverages existing authorization and validation patterns
- Follows the established pattern used by meetings, trainings, etc.
- Simplifies maintenance and reduces code duplication

### MVC Pattern Implementation
```
Controller (ActivitiesController)
├── implement() - Display implementation form (ADD inputs case)
├── saveImplementation() - Process form submission (FIX inputs case)
├── show() - Display activity with implementation details (ADD inputs case)
└── saveInputImplementation() - FIX method signature and logic

Model (ActivitiesInputModel) - ✅ Already complete
├── JSON field handling (inputs, input_files, input_images)
├── Data validation rules
└── Callback methods for encoding/decoding

View Structure
├── implementations/
│   └── inputs_implementation.php - CREATE new form view
└── implementation/
    └── inputs_details.php - UPDATE to match database structure
```

## Required Changes

### 1. Controller Changes (ActivitiesController.php)

#### A. Add Missing Case in implement() Method
**Location:** Around line 593 (after agreements case)
```php
} elseif ($activity['type'] === 'inputs') {
    $implementationData = $this->activitiesInputModel
        ->where('activity_id', $activity['id'])
        ->first();
    
    // ActivitiesInputModel automatically decodes JSON fields via afterFind callback
    // No manual JSON decoding needed
}
```

#### B. Fix saveInputImplementation() Method
**Current Issues:**
- Wrong signature: `($proposal, $activity)` should be `($activity)`
- Uses WorkplanInputActivityModel instead of ActivitiesInputModel
- Wrong data structure for saving

**Required Fix:** Complete method rewrite following the pattern of other activity types

#### C. Add Missing Case in show() Method
**Location:** Around line 1437 (after agreements case)
```php
} elseif ($activity['type'] === 'inputs') {
    $implementationData = $this->activitiesInputModel
        ->where('activity_id', $activity['id'])
        ->first();
}
```

#### D. Add ActivitiesInputModel to Constructor
**Location:** Around line 65
```php
protected $activitiesInputModel;

// In constructor:
$this->activitiesInputModel = new ActivitiesInputModel();
```

### 2. View Changes

#### A. Create inputs_implementation.php
**Location:** `app/Views/activities/implementations/inputs_implementation.php`
**Pattern:** Follow meetings_implementation.php structure
**Key Features:**
- Dynamic input items section (add/remove functionality)
- File upload for input documents
- Image upload for input photos
- GPS coordinates field
- Signing sheet upload
- Form validation and error handling

#### B. Update inputs_details.php
**Location:** `app/Views/activities/implementation/inputs_details.php`
**Changes Required:**
- Remove non-existent fields (title, input_type, quantity, unit, cost_per_unit, etc.)
- Display inputs JSON data in table format
- Fix file structure expectations
- Ensure image display works correctly

### 3. Route Verification
**Verify existing routes support input activities:**
- `/activities/{id}/implement` - Should work
- `/activities/{id}/save-implementation` - Should work
- `/activities/{id}` - Should work

## Implementation Steps

### Phase 1: Controller Fixes (Priority: HIGH)
1. **Add ActivitiesInputModel to constructor**
2. **Add inputs case to implement() method**
3. **Add inputs case to show() method** 
4. **Rewrite saveInputImplementation() method**

### Phase 2: Create Implementation Form (Priority: HIGH)
1. **Create inputs_implementation.php view**
2. **Implement dynamic input items section**
3. **Add file upload functionality**
4. **Add form validation**

### Phase 3: Update Details View (Priority: MEDIUM)
1. **Update inputs_details.php structure**
2. **Fix data display logic**
3. **Test image and file display**

### Phase 4: Testing & Validation (Priority: HIGH)
1. **Test form submission**
2. **Test data persistence**
3. **Test file uploads**
4. **Test edit functionality**

## File Structure

### New Files to Create
```
app/Views/activities/implementations/
└── inputs_implementation.php          # NEW - Main implementation form

public/uploads/
├── input_images/                      # Directory for input images
├── input_files/                       # Directory for input documents
└── signing_sheets/                    # Directory for signing sheets
```

### Files to Modify
```
app/Controllers/
└── ActivitiesController.php           # Add missing cases and fix methods

app/Views/activities/implementation/
└── inputs_details.php                 # Update to match database structure
```

## Technical Specifications

### Form Fields Structure
```php
// Basic Information
- GPS Coordinates (required)
- Remarks (optional)

// Dynamic Input Items Section
- Input Name (required)
- Quantity (optional)
- Unit (optional) 
- Remarks (optional)

// File Uploads
- Input Images (multiple files, optional)
- Input Documents (multiple files, optional)
- Signing Sheet (single file, optional)
```

### Validation Rules
```php
$validationRules = [
    'gps_coordinates' => 'required|max_length[255]',
    'remarks' => 'permit_empty',
    'input_images.*' => 'permit_empty|uploaded[input_images]|max_size[input_images,5120]|is_image[input_images]',
    'input_files.*' => 'permit_empty|uploaded[input_files]|max_size[input_files,5120]',
    'signing_sheet' => 'permit_empty|uploaded[signing_sheet]|max_size[signing_sheet,5120]'
];
```

### File Upload Configuration
```php
// Upload directories
$uploadPaths = [
    'input_images' => ROOTPATH . 'public/uploads/input_images',
    'input_files' => ROOTPATH . 'public/uploads/input_files', 
    'signing_sheets' => ROOTPATH . 'public/uploads/signing_sheets'
];

// File size limits
$maxFileSize = 5120; // 5MB

// Allowed file types
$allowedImageTypes = ['jpg', 'jpeg', 'png', 'gif'];
$allowedFileTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
```

## Testing Plan

### Unit Testing
1. **Model Testing**
   - JSON encoding/decoding
   - Data validation
   - CRUD operations

2. **Controller Testing**
   - Form submission handling
   - File upload processing
   - Authorization checks

### Integration Testing
1. **Form Submission Flow**
   - Create new implementation
   - Update existing implementation
   - File upload handling

2. **Data Display Flow**
   - Implementation details view
   - File download functionality
   - Image display modal

### User Acceptance Testing
1. **Action Officer Workflow**
   - Access implementation form
   - Submit implementation data
   - View implementation details

2. **Supervisor Workflow**
   - Review implementation
   - Approve/reject implementation

## Integration Points

### Activity Supervision Workflow
- Submit button changes activity status to 'submitted'
- Only supervise button shows in actions column for submitted activities
- Other buttons (edit, implement) are hidden

### File Management
- Files stored in `public/uploads/` with proper path prefixes
- Download functionality for all file types
- Image preview modal for uploaded images

### User Interface Consistency
- Follow existing view file naming conventions
- Maintain consistent styling with other activity types
- Use standard CodeIgniter 4 form submission (no AJAX)

### Security Considerations
- File upload validation and sanitization
- Proper authorization checks
- SQL injection prevention through model usage
- XSS prevention through proper escaping

## Code Examples

### 1. Complete saveInputImplementation() Method
```php
/**
 * Save input implementation data
 *
 * @param array $activity Activity data
 * @return mixed
 */
private function saveInputImplementation($activity)
{
    $userId = session()->get('user_id');

    // Validation rules
    $validationRules = [
        'gps_coordinates' => 'required|max_length[255]',
        'remarks' => 'permit_empty',
        'input_images.*' => 'permit_empty|uploaded[input_images]|max_size[input_images,5120]|is_image[input_images]',
        'input_files.*' => 'permit_empty|uploaded[input_files]|max_size[input_files,5120]',
        'signing_sheet' => 'permit_empty|uploaded[signing_sheet]|max_size[signing_sheet,5120]'
    ];

    if (!$this->validate($validationRules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Check for existing record
    $existingRecord = $this->activitiesInputModel
        ->where('activity_id', $activity['id'])
        ->first();

    // Process input items
    $inputs = [];
    $inputNames = $this->request->getPost('input_name') ?: [];
    $inputQuantities = $this->request->getPost('input_quantity') ?: [];
    $inputUnits = $this->request->getPost('input_unit') ?: [];
    $inputRemarks = $this->request->getPost('input_remarks') ?: [];

    foreach ($inputNames as $index => $name) {
        if (!empty(trim($name))) {
            $inputs[] = [
                'name' => trim($name),
                'quantity' => trim($inputQuantities[$index] ?? ''),
                'unit' => trim($inputUnits[$index] ?? ''),
                'remarks' => trim($inputRemarks[$index] ?? '')
            ];
        }
    }

    // Process file uploads
    $inputImages = [];
    $inputFiles = [];
    $signingSheetFilepath = $existingRecord['signing_sheet_filepath'] ?? null;

    // Handle input images
    $imageFiles = $this->request->getFiles();
    if (isset($imageFiles['input_images'])) {
        foreach ($imageFiles['input_images'] as $file) {
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $uploadPath = ROOTPATH . 'public/uploads/input_images';

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $file->move($uploadPath, $newName);
                $inputImages[] = 'public/uploads/input_images/' . $newName;
            }
        }
    }

    // Handle input files
    if (isset($imageFiles['input_files'])) {
        $fileCaptions = $this->request->getPost('file_captions') ?: [];

        foreach ($imageFiles['input_files'] as $index => $file) {
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $uploadPath = ROOTPATH . 'public/uploads/input_files';

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $file->move($uploadPath, $newName);

                $inputFiles[] = [
                    'caption' => $fileCaptions[$index] ?? $file->getClientName(),
                    'original_name' => $file->getClientName(),
                    'file_path' => 'public/uploads/input_files/' . $newName
                ];
            }
        }
    }

    // Handle signing sheet
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
        'inputs' => $inputs,
        'input_images' => $inputImages,
        'input_files' => $inputFiles,
        'gps_coordinates' => $this->request->getPost('gps_coordinates'),
        'signing_sheet_filepath' => $signingSheetFilepath,
        'created_by' => $userId,
        'updated_by' => $userId
    ];

    if ($existingRecord) {
        $data['id'] = $existingRecord['id'];
    }

    // Save data
    if ($this->activitiesInputModel->save($data)) {
        // Update activity status to 'active'
        $this->activitiesModel->update($activity['id'], [
            'status' => 'active',
            'updated_by' => $userId
        ]);

        return redirect()->to('/activities/' . $activity['id'])
            ->with('success', 'Input implementation saved successfully.');
    } else {
        return redirect()->back()->withInput()
            ->with('error', 'Failed to save input implementation: ' .
                implode(', ', $this->activitiesInputModel->errors()));
    }
}
```

### 2. Form Structure for inputs_implementation.php
```php
<!-- Dynamic Input Items Section -->
<div class="mb-4">
    <h6 class="fw-bold mb-3">Input Items</h6>
    <div id="inputItemsContainer">
        <?php if (!empty($implementationData['inputs'])): ?>
            <?php foreach ($implementationData['inputs'] as $index => $input): ?>
                <div class="input-item-row border rounded p-3 mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Input Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="input_name[]"
                                   value="<?= esc($input['name']) ?>" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Quantity</label>
                            <input type="text" class="form-control" name="input_quantity[]"
                                   value="<?= esc($input['quantity']) ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Unit</label>
                            <input type="text" class="form-control" name="input_unit[]"
                                   value="<?= esc($input['unit']) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Remarks</label>
                            <input type="text" class="form-control" name="input_remarks[]"
                                   value="<?= esc($input['remarks']) ?>">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-input-item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="input-item-row border rounded p-3 mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Input Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="input_name[]" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantity</label>
                        <input type="text" class="form-control" name="input_quantity[]">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Unit</label>
                        <input type="text" class="form-control" name="input_unit[]">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Remarks</label>
                        <input type="text" class="form-control" name="input_remarks[]">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-input-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <button type="button" class="btn btn-outline-primary btn-sm" id="addInputItem">
        <i class="fas fa-plus"></i> Add Input Item
    </button>
</div>
```

### 3. JavaScript for Dynamic Sections
```javascript
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add input item functionality
    document.getElementById('addInputItem').addEventListener('click', function() {
        const container = document.getElementById('inputItemsContainer');
        const newRow = document.createElement('div');
        newRow.className = 'input-item-row border rounded p-3 mb-3';
        newRow.innerHTML = `
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Input Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="input_name[]" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Quantity</label>
                    <input type="text" class="form-control" name="input_quantity[]">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit</label>
                    <input type="text" class="form-control" name="input_unit[]">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Remarks</label>
                    <input type="text" class="form-control" name="input_remarks[]">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-input-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newRow);
        updateRemoveButtons();
    });

    // Remove input item functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-input-item')) {
            e.target.closest('.input-item-row').remove();
            updateRemoveButtons();
        }
    });

    function updateRemoveButtons() {
        const items = document.querySelectorAll('.input-item-row');
        items.forEach((item) => {
            const removeBtn = item.querySelector('.remove-input-item');
            if (removeBtn) {
                removeBtn.style.display = items.length > 1 ? 'inline-block' : 'none';
            }
        });
    }

    // Initialize remove buttons
    updateRemoveButtons();
});
</script>
```

## Implementation Checklist

### ✅ Pre-Implementation Verification
- [ ] Verify ActivitiesInputModel exists and is properly configured
- [ ] Confirm activities_input table structure matches expectations
- [ ] Check existing routes support input activities
- [ ] Verify upload directories exist or can be created

### 🔧 Phase 1: Controller Fixes
- [ ] Add ActivitiesInputModel to constructor
- [ ] Add inputs case to implement() method (line ~593)
- [ ] Add inputs case to show() method (line ~1437)
- [ ] Rewrite saveInputImplementation() method
- [ ] Test controller changes

### 📝 Phase 2: Create Implementation Form
- [ ] Create inputs_implementation.php view file
- [ ] Implement basic form structure
- [ ] Add dynamic input items section
- [ ] Add file upload sections
- [ ] Add JavaScript functionality
- [ ] Test form rendering

### 🎨 Phase 3: Update Details View
- [ ] Update inputs_details.php structure
- [ ] Fix inputs data display (table format)
- [ ] Update file display logic
- [ ] Test image modal functionality
- [ ] Test file download links

### 🧪 Phase 4: Testing & Validation
- [ ] Test new implementation creation
- [ ] Test existing implementation editing
- [ ] Test file upload functionality
- [ ] Test data persistence and retrieval
- [ ] Test authorization and validation
- [ ] Test integration with activity workflow

---

**Implementation Priority:** HIGH - This feature is critical for completing the activities implementation workflow.

**Estimated Development Time:** 2-3 days for a skilled CodeIgniter developer

**Dependencies:** None - All required components exist

**Next Steps:** Begin implementation with Phase 1 (Controller Fixes) as it's the foundation for all other functionality.
