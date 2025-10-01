# Duty Instructions - Workplan Dropdown Filter & Default Status

## Overview
This document describes the implementation of workplan dropdown filtering and default status for the Duty Instructions creation and edit pages. The workplan dropdown now shows only "in progress" workplans from the user's branch/organization, and new duty instructions are created with an "active" status by default.

## Implementation Date
January 10, 2025

## Changes Made

### 1. Controller Updates - `app/Controllers/DutyInstructionsController.php`

#### A. `new()` Method - Create Duty Instruction Form
**Purpose:** Display form for creating a new duty instruction

**Changes:**
- Added logic to get the logged-in user's `branch_id` from the users table
- Modified workplan query to filter by:
  - `status = 'in_progress'` - Only show workplans that are currently in progress
  - `branch_id` matching the user's branch - Only show workplans from the same organization
  - `deleted_at IS NULL` - Exclude soft-deleted workplans
- Added `orderBy('title', 'ASC')` for alphabetical sorting

**Code Pattern:**
```php
// Get logged-in user's branch_id
$userId = session()->get('user_id');
$user = $this->userModel->find($userId);
$userBranchId = $user['branch_id'] ?? null;

// Get workplans filtered by status 'in_progress' and user's branch
$workplans = $this->workplanModel
    ->where('status', 'in_progress')
    ->where('branch_id', $userBranchId)
    ->where('deleted_at IS NULL')
    ->orderBy('title', 'ASC')
    ->findAll();
```

#### B. `edit()` Method - Edit Duty Instruction Form
**Purpose:** Display form for editing an existing duty instruction

**Changes:**
- Applied the same workplan filtering logic as the `new()` method
- Ensures consistency between create and edit forms
- Users can only select from "in progress" workplans in their branch when editing

#### C. `create()` Method - Save New Duty Instruction
**Purpose:** Process and save a new duty instruction

**Changes:**
- Added `'status' => 'active'` to the data array
- This explicitly sets the status to 'active' when creating a new duty instruction
- Overrides the model's default callback to ensure 'active' status

**Code Pattern:**
```php
$data = [
    'workplan_id' => $this->request->getPost('workplan_id'),
    'user_id' => session()->get('user_id'),
    'supervisor_id' => $this->request->getPost('supervisor_id'),
    'duty_instruction_number' => $this->request->getPost('duty_instruction_number'),
    'duty_instruction_title' => $this->request->getPost('duty_instruction_title'),
    'duty_instruction_description' => $this->request->getPost('duty_instruction_description'),
    'status' => 'active', // Set default status to 'active'
    'created_by' => session()->get('user_id')
];
```

### 2. Model Updates - `app/Models/DutyInstructionsModel.php`

#### A. `setDefaultStatus()` Callback
**Purpose:** Set default status when creating a new duty instruction (fallback)

**Changes:**
- Changed default status from `'pending'` to `'active'`
- This callback serves as a fallback if status is not explicitly set in the controller
- Ensures consistency across the application

**Before:**
```php
if (!isset($data['data']['status'])) {
    $data['data']['status'] = 'pending';
    // ...
}
```

**After:**
```php
if (!isset($data['data']['status'])) {
    $data['data']['status'] = 'active';
    // ...
}
```

## Database Schema Reference

### Workplans Table
- **status field:** ENUM('draft', 'in_progress', 'completed', 'on_hold', 'cancelled')
- **branch_id field:** INT(11) - Foreign key to branches table
- **deleted_at field:** DATETIME - Used for soft deletes

### Duty Instructions Table
- **status field:** VARCHAR(50) - Stores status values like 'active', 'pending', 'completed', etc.
- **workplan_id field:** BIGINT(20) UNSIGNED - Foreign key to workplans table
- **user_id field:** BIGINT(20) UNSIGNED - Foreign key to users table (assigned user)

### Users Table
- **branch_id field:** INT(11) - Foreign key to branches table
- Links users to their organization/branch

## User Experience

### Workplan Dropdown Behavior

**Before Changes:**
- Dropdown showed ALL workplans regardless of status or branch
- Users could select workplans from other organizations
- Users could select draft, completed, or on-hold workplans

**After Changes:**
- Dropdown shows ONLY workplans that are:
  1. In "in_progress" status
  2. From the same branch/organization as the logged-in user
  3. Not soft-deleted
- Workplans are sorted alphabetically by title
- If no workplans match the criteria, the dropdown will be empty

### Empty Dropdown Scenarios

The workplan dropdown will be empty when:
1. User's branch has no workplans with "in_progress" status
2. User's `branch_id` is NULL or invalid
3. All workplans in the user's branch are in other statuses (draft, completed, on_hold, cancelled)

**Recommendation:** Display a helpful message in the view when no workplans are available:
```php
<?php if (empty($workplans)): ?>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i> 
        No in-progress workplans available for your branch. 
        Please create or activate a workplan first.
    </div>
<?php endif; ?>
```

### Default Status Behavior

**Before Changes:**
- New duty instructions were created with status = 'pending'

**After Changes:**
- New duty instructions are created with status = 'active'
- This indicates the duty instruction is immediately active and ready to be worked on

## Business Logic Rationale

### Why Filter by "in_progress" Status?
- Duty instructions should only be linked to active workplans
- Prevents users from creating duty instructions for draft or completed workplans
- Ensures data integrity and logical workflow progression

### Why Filter by User's Branch?
- Maintains multi-tenant data isolation
- Users should only work with workplans from their own organization
- Prevents cross-organization data mixing
- Aligns with the AMIS multi-tenant architecture

### Why Default Status is "active"?
- Duty instructions are typically ready to be acted upon immediately after creation
- Reduces an extra step of manually activating the duty instruction
- Aligns with the typical workflow where duty instructions are created when they need to be executed

## Testing Recommendations

### Test Cases for Workplan Filtering

1. **User with In-Progress Workplans:**
   - Login as a user with `branch_id = 1`
   - Create workplans with status 'in_progress' for branch 1
   - Navigate to duty instructions creation page
   - Verify only in-progress workplans from branch 1 are shown

2. **User with No In-Progress Workplans:**
   - Login as a user with `branch_id = 2`
   - Ensure all workplans for branch 2 are in 'draft' or 'completed' status
   - Navigate to duty instructions creation page
   - Verify dropdown is empty or shows appropriate message

3. **User with NULL branch_id:**
   - Login as a user with `branch_id = NULL`
   - Navigate to duty instructions creation page
   - Verify dropdown is empty (no workplans shown)

4. **Cross-Branch Verification:**
   - Create workplans for multiple branches (1, 2, 3)
   - Login as user from branch 1
   - Verify only branch 1 workplans are shown
   - Login as user from branch 2
   - Verify only branch 2 workplans are shown

### Test Cases for Default Status

1. **Create New Duty Instruction:**
   - Navigate to duty instructions creation page
   - Fill in all required fields
   - Submit the form
   - Verify the created duty instruction has status = 'active'

2. **Database Verification:**
   - After creating a duty instruction
   - Check the `duty_instructions` table
   - Verify `status` field = 'active'
   - Verify `status_at` and `status_by` are set correctly

## Related Files

- **Controller:** `app/Controllers/DutyInstructionsController.php`
  - Methods: `new()`, `edit()`, `create()`
- **Model:** `app/Models/DutyInstructionsModel.php`
  - Callback: `setDefaultStatus()`
- **Model:** `app/Models/WorkplanModel.php`
  - Used for querying workplans
- **Model:** `app/Models/UserModel.php`
  - Used for getting user's branch_id
- **Views:** 
  - `app/Views/duty_instructions/duty_instructions_create.php`
  - `app/Views/duty_instructions/duty_instructions_edit.php`

## Multi-Tenant Architecture Alignment

This implementation aligns with the AMIS multi-tenant architecture:
- **Data Isolation:** Users can only see workplans from their own branch/organization
- **Status-Based Filtering:** Ensures logical workflow by showing only active workplans
- **Consistent User Experience:** Same filtering logic applied to both create and edit forms
- **Security:** Prevents users from accessing or linking to workplans outside their organization

## Future Enhancements

Potential improvements for consideration:
1. Add a "No workplans available" message in the view when dropdown is empty
2. Add a quick link to create a new workplan if none are available
3. Add admin override to allow admin users to see all workplans across branches
4. Add status filter dropdown to allow users to optionally view workplans in other statuses
5. Add workplan status indicator in the dropdown (e.g., "Workplan Title (In Progress)")

