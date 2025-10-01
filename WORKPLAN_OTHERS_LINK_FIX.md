# Workplan Others Link - Database Column Fix

## Overview
This document describes the fix for the database error "Unknown column 'link_type' in 'where clause'" that occurred when accessing the Workplan Others links page at `/workplans/{id}/activities/{id}/others`.

## Issue Date
January 10, 2025

## Problem Description

### Error Message
```
CodeIgniter\Database\Exceptions\DatabaseException #1054
Unknown column 'link_type' in 'where clause'
```

### Root Cause
A migration file (`2025-10-01-000001_DropFieldsFromWorkplanOthersLinkTable.php`) dropped several columns from the `workplan_others_link` table:
- `link_type`
- `category`
- `priority_level`
- `duration_months`

However, the model (`WorkplanOthersLinkModel.php`) and controller (`WorkplanOthersController.php`) still referenced these dropped columns, causing database errors when queries attempted to use them.

### Affected URL
- `/workplans/{workplanId}/activities/{activityId}/others`

## Database Schema Changes

### Columns Dropped by Migration
The following columns were removed from the `workplan_others_link` table:

1. **link_type** - ENUM('recurrent', 'special_project', 'emergency', 'other')
2. **category** - VARCHAR(100)
3. **priority_level** - ENUM('low', 'medium', 'high', 'critical')
4. **duration_months** - INT(11)

### Current Table Structure
After the migration, the `workplan_others_link` table has the following columns:
- `id` (Primary Key, AUTO_INCREMENT)
- `workplan_activity_id` (INT, NOT NULL)
- `title` (VARCHAR(255), NOT NULL)
- `description` (TEXT, NULL)
- `justification` (TEXT, NOT NULL)
- `expected_outcome` (TEXT, NULL)
- `target_beneficiaries` (TEXT, NULL)
- `budget_estimate` (DECIMAL(15,2), NULL)
- `start_date` (DATE, NULL)
- `end_date` (DATE, NULL)
- `status` (ENUM('active', 'inactive', 'completed', 'cancelled'), DEFAULT 'active')
- `remarks` (TEXT, NULL)
- `created_at` (DATETIME, DEFAULT CURRENT_TIMESTAMP)
- `created_by` (INT, NULL)
- `updated_at` (DATETIME, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)
- `updated_by` (INT, NULL)
- `deleted_at` (DATETIME, NULL)
- `deleted_by` (INT, NULL)

## Changes Made

### 1. Model Updates - `app/Models/WorkplanOthersLinkModel.php`

#### A. Updated `$allowedFields` Array
**Before:**
```php
protected $allowedFields = [
    'workplan_activity_id',
    'link_type',
    'title',
    'description',
    'justification',
    'category',
    'priority_level',
    'expected_outcome',
    'target_beneficiaries',
    'budget_estimate',
    'duration_months',
    'start_date',
    'end_date',
    'status',
    'remarks',
    'created_by',
    'updated_by',
    'deleted_by'
];
```

**After:**
```php
protected $allowedFields = [
    'workplan_activity_id',
    'title',
    'description',
    'justification',
    'expected_outcome',
    'target_beneficiaries',
    'budget_estimate',
    'start_date',
    'end_date',
    'status',
    'remarks',
    'created_by',
    'updated_by',
    'deleted_by'
];
```

**Removed Fields:**
- `link_type`
- `category`
- `priority_level`
- `duration_months`

#### B. Updated `$validationRules` Array
**Before:**
```php
protected $validationRules = [
    'workplan_activity_id' => 'required|integer',
    'link_type' => 'required|in_list[recurrent,special_project,emergency,other]',
    'title' => 'required|max_length[255]',
    'description' => 'permit_empty',
    'justification' => 'required',
    'category' => 'permit_empty|max_length[100]',
    'priority_level' => 'permit_empty|in_list[low,medium,high,critical]',
    'expected_outcome' => 'permit_empty',
    'target_beneficiaries' => 'permit_empty',
    'budget_estimate' => 'permit_empty|decimal',
    'duration_months' => 'permit_empty|integer',
    'start_date' => 'permit_empty|valid_date',
    'end_date' => 'permit_empty|valid_date',
    'status' => 'permit_empty|in_list[active,inactive,completed,cancelled]',
    'remarks' => 'permit_empty',
    'created_by' => 'permit_empty|integer',
    'updated_by' => 'permit_empty|integer',
    'deleted_by' => 'permit_empty|integer'
];
```

**After:**
```php
protected $validationRules = [
    'workplan_activity_id' => 'required|integer',
    'title' => 'required|max_length[255]',
    'description' => 'permit_empty',
    'justification' => 'required',
    'expected_outcome' => 'permit_empty',
    'target_beneficiaries' => 'permit_empty',
    'budget_estimate' => 'permit_empty|decimal',
    'start_date' => 'permit_empty|valid_date',
    'end_date' => 'permit_empty|valid_date',
    'status' => 'permit_empty|in_list[active,inactive,completed,cancelled]',
    'remarks' => 'permit_empty',
    'created_by' => 'permit_empty|integer',
    'updated_by' => 'permit_empty|integer',
    'deleted_by' => 'permit_empty|integer'
];
```

**Removed Validation Rules:**
- `link_type`
- `category`
- `priority_level`
- `duration_months`

#### C. Updated `$validationMessages` Array
**Before:**
```php
protected $validationMessages = [
    'workplan_activity_id' => [
        'required' => 'Workplan Activity ID is required',
        'integer' => 'Workplan Activity ID must be a valid integer'
    ],
    'link_type' => [
        'required' => 'Link type is required',
        'in_list' => 'Link type must be one of: recurrent, special_project, emergency, other'
    ],
    'title' => [
        'required' => 'Title is required',
        'max_length' => 'Title cannot exceed 255 characters'
    ],
    'justification' => [
        'required' => 'Justification is required'
    ]
];
```

**After:**
```php
protected $validationMessages = [
    'workplan_activity_id' => [
        'required' => 'Workplan Activity ID is required',
        'integer' => 'Workplan Activity ID must be a valid integer'
    ],
    'title' => [
        'required' => 'Title is required',
        'max_length' => 'Title cannot exceed 255 characters'
    ],
    'justification' => [
        'required' => 'Justification is required'
    ]
];
```

**Removed Validation Messages:**
- `link_type` validation messages

#### D. Removed `getRecurrentActivities()` Method
This method used the `link_type` column to filter recurrent activities:

**Removed Method:**
```php
public function getRecurrentActivities()
{
    return $this->where('workplan_activity_id', 0)
               ->where('link_type', 'recurrent')
               ->where('deleted_at', null)
               ->orderBy('title', 'ASC')
               ->findAll();
}
```

**Reason:** The `link_type` column no longer exists in the database.

### 2. Controller Updates - `app/Controllers/WorkplanOthersController.php`

#### A. Updated `index()` Method
**Before:**
```php
// Get existing others links for this activity
$othersLinks = $this->workplanOthersLinkModel->getOthersLinksForActivity($activityId);

// Get recurrent activities (templates)
$recurrentActivities = $this->workplanOthersLinkModel->getRecurrentActivities();

$data = [
    'title' => 'Others Links - ' . $activity['title'],
    'workplan' => $workplan,
    'activity' => $activity,
    'othersLinks' => $othersLinks,
    'recurrentActivities' => $recurrentActivities
];
```

**After:**
```php
// Get existing others links for this activity
$othersLinks = $this->workplanOthersLinkModel->getOthersLinksForActivity($activityId);

$data = [
    'title' => 'Others Links - ' . $activity['title'],
    'workplan' => $workplan,
    'activity' => $activity,
    'othersLinks' => $othersLinks
];
```

**Changes:**
- Removed call to `getRecurrentActivities()` method
- Removed `recurrentActivities` from data array

#### B. Updated `create()` Method
**Before:**
The method had complex logic to handle both recurrent activities and custom others links, including all dropped fields.

**After:**
```php
$userId = session()->get('user_id');

// Create others link
$data = [
    'workplan_activity_id' => $activityId,
    'title' => $this->request->getPost('title'),
    'description' => $this->request->getPost('description'),
    'justification' => $this->request->getPost('justification'),
    'expected_outcome' => $this->request->getPost('expected_outcome'),
    'target_beneficiaries' => $this->request->getPost('target_beneficiaries'),
    'budget_estimate' => $this->request->getPost('budget_estimate'),
    'start_date' => $this->request->getPost('start_date'),
    'end_date' => $this->request->getPost('end_date'),
    'remarks' => $this->request->getPost('remarks'),
    'created_by' => $userId,
    'updated_by' => $userId
];
```

**Changes:**
- Removed recurrent activity linking logic
- Removed references to dropped fields: `link_type`, `category`, `priority_level`, `duration_months`
- Simplified to only handle custom others links

#### C. Updated `update()` Method
**Before:**
```php
$data = [
    'id' => $id,
    'link_type' => $this->request->getPost('link_type'),
    'title' => $this->request->getPost('title'),
    'description' => $this->request->getPost('description'),
    'justification' => $this->request->getPost('justification'),
    'category' => $this->request->getPost('category'),
    'priority_level' => $this->request->getPost('priority_level'),
    'expected_outcome' => $this->request->getPost('expected_outcome'),
    'target_beneficiaries' => $this->request->getPost('target_beneficiaries'),
    'budget_estimate' => $this->request->getPost('budget_estimate'),
    'duration_months' => $this->request->getPost('duration_months'),
    'start_date' => $this->request->getPost('start_date'),
    'end_date' => $this->request->getPost('end_date'),
    'status' => $this->request->getPost('status'),
    'remarks' => $this->request->getPost('remarks'),
    'updated_by' => $userId
];
```

**After:**
```php
$data = [
    'id' => $id,
    'title' => $this->request->getPost('title'),
    'description' => $this->request->getPost('description'),
    'justification' => $this->request->getPost('justification'),
    'expected_outcome' => $this->request->getPost('expected_outcome'),
    'target_beneficiaries' => $this->request->getPost('target_beneficiaries'),
    'budget_estimate' => $this->request->getPost('budget_estimate'),
    'start_date' => $this->request->getPost('start_date'),
    'end_date' => $this->request->getPost('end_date'),
    'status' => $this->request->getPost('status'),
    'remarks' => $this->request->getPost('remarks'),
    'updated_by' => $userId
];
```

**Changes:**
- Removed references to dropped fields: `link_type`, `category`, `priority_level`, `duration_months`

## Testing Recommendations

### Test Cases

1. **Access Others Links Page:**
   - Navigate to `/workplans/{workplanId}/activities/{activityId}/others`
   - Verify page loads without database errors
   - Verify existing others links are displayed

2. **Create New Others Link:**
   - Click "Create New Others Link" button
   - Fill in the form with valid data
   - Submit the form
   - Verify the others link is created successfully

3. **Edit Existing Others Link:**
   - Click "Edit" on an existing others link
   - Modify the data
   - Submit the form
   - Verify the others link is updated successfully

4. **Delete Others Link:**
   - Click "Delete" on an existing others link
   - Confirm deletion
   - Verify the others link is soft-deleted

## Related Files

- **Model:** `app/Models/WorkplanOthersLinkModel.php`
- **Controller:** `app/Controllers/WorkplanOthersController.php`
- **Migration:** `app/Database/Migrations/2025-10-01-000001_DropFieldsFromWorkplanOthersLinkTable.php`
- **Views:**
  - `app/Views/workplan_others/workplan_others_index.php`
  - `app/Views/workplan_others/workplan_others_create.php`
  - `app/Views/workplan_others/workplan_others_edit.php`

## Notes

- The migration that dropped these columns was intentional, suggesting a simplification of the Others links feature
- The recurrent activities functionality has been removed
- Forms and views may need to be updated to remove input fields for the dropped columns
- Any existing data in the dropped columns was lost when the migration ran

