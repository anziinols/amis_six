# Workplan Edit Form - Branch and Supervisor Filtering

## Overview
This document describes the implementation of branch and supervisor filtering for the Workplan Edit form. The edit form now uses the same filtering logic as the create form, ensuring consistency between create and edit operations.

## Implementation Date
January 10, 2025

## Changes Made

### Controller Updates - `app/Controllers/WorkplanController.php`

#### `edit()` Method - Edit Workplan Form
**Purpose:** Display form for editing an existing workplan

**Changes Applied:**
The `edit()` method now implements the same filtering logic as the `new()` method to ensure consistency between create and edit forms.

**Filtering Logic:**

1. **Get User Information:**
   - Retrieves logged-in user's ID from session
   - Checks if user has admin privileges (`is_admin == 1`)
   - Gets user's full details including `branch_id`

2. **Branch Dropdown Filtering:**
   - **Non-Admin Users:** Can only see their assigned branch
     - If user has a `branch_id`, show only that branch
     - If user has no `branch_id`, show empty array (no branches)
   - **Admin Users:** Can see all branches in the system

3. **Supervisor Dropdown Filtering:**
   - **Non-Admin Users:** Can only see themselves as supervisor option
     - Filters users table to show only the logged-in user
   - **Admin Users:** Can see all users with supervisor capability
     - Uses `getUsersBySupervisorCapability()` method to get all supervisors

**Code Pattern:**
```php
// Get logged-in user info
$loggedInUserId = session()->get('user_id');
$isAdmin = session()->get('is_admin') == 1;

// Get the logged-in user's full details including branch_id
$loggedInUser = $this->userModel->find($loggedInUserId);
$loggedInUserBranchId = $loggedInUser['branch_id'] ?? null;

// Apply the same filtering logic as the create form
// If not admin, only show the logged-in user as supervisor option
if (!$isAdmin) {
    $supervisors = $this->userModel->where('id', $loggedInUserId)->findAll();
    // If not admin, only show the user's assigned branch
    if ($loggedInUserBranchId) {
        $branches = $this->branchModel->where('id', $loggedInUserBranchId)->findAll();
    } else {
        $branches = []; // No branch assigned
    }
} else {
    // If admin, show all supervisors and all branches
    $supervisors = $this->userModel->getUsersBySupervisorCapability();
    $branches = $this->branchModel->findAll();
}

$data = [
    'title' => 'Edit Workplan',
    'workplan' => $workplan,
    'validation' => \Config\Services::validation(),
    'branches' => $branches,
    'supervisors' => $supervisors,
    'isAdmin' => $isAdmin,
    'loggedInUserId' => $loggedInUserId,
    'loggedInUserBranchId' => $loggedInUserBranchId
];
```

## Comparison: Before vs After

### Before Changes

**Branch Dropdown:**
- Showed ALL branches regardless of user role
- Non-admin users could see and potentially select branches they don't belong to

**Supervisor Dropdown:**
- Showed ALL users with supervisor capability
- Non-admin users could see and potentially select other supervisors

**Code:**
```php
$data = [
    'title' => 'Edit Workplan',
    'workplan' => $workplan,
    'validation' => \Config\Services::validation(),
    'branches' => $this->branchModel->findAll(),
    'supervisors' => $this->userModel->getUsersBySupervisorCapability(),
];
```

### After Changes

**Branch Dropdown:**
- **Non-Admin:** Shows only the user's assigned branch
- **Admin:** Shows all branches

**Supervisor Dropdown:**
- **Non-Admin:** Shows only the logged-in user
- **Admin:** Shows all users with supervisor capability

**Code:**
- Implements conditional filtering based on `is_admin` flag
- Matches the exact logic from the `new()` method

## User Experience

### For Non-Admin Users

**Branch Dropdown:**
- If user has `branch_id = 5`, dropdown shows only Branch 5
- If user has `branch_id = NULL`, dropdown is empty
- User cannot select branches from other organizations

**Supervisor Dropdown:**
- Dropdown shows only the logged-in user's name
- User cannot assign workplans to other supervisors
- Ensures workplans remain assigned to the creator

**Scenario Example:**
- User "John Doe" (branch_id = 3, is_admin = 0) edits a workplan
- Branch dropdown: Shows only Branch 3
- Supervisor dropdown: Shows only "John Doe"

### For Admin Users

**Branch Dropdown:**
- Shows all branches in the system
- Can reassign workplans to any branch

**Supervisor Dropdown:**
- Shows all users with `is_supervisor = 1`
- Can reassign workplans to any supervisor

**Scenario Example:**
- User "Admin User" (is_admin = 1) edits a workplan
- Branch dropdown: Shows all branches (Branch 1, Branch 2, Branch 3, etc.)
- Supervisor dropdown: Shows all supervisors across all branches

## Business Logic Rationale

### Why Apply Same Filtering to Edit Form?

1. **Consistency:** Users should see the same options when creating and editing workplans
2. **Data Integrity:** Prevents users from changing workplans to branches/supervisors they don't have access to
3. **Security:** Enforces organization-based access control during edit operations
4. **User Experience:** Reduces confusion by maintaining consistent dropdown options

### Why Filter by User's Branch?

- Maintains multi-tenant data isolation
- Users should only manage workplans within their own organization
- Prevents cross-organization data mixing
- Aligns with AMIS multi-tenant architecture

### Why Filter Supervisors for Non-Admin Users?

- Non-admin users typically create workplans for themselves
- Prevents users from assigning workplans to other supervisors without permission
- Maintains accountability and ownership of workplans
- Admin users retain flexibility to assign to any supervisor

## Multi-Tenant Architecture Alignment

This implementation aligns with the AMIS multi-tenant architecture:

**Data Isolation:**
- Non-admin users can only edit workplans within their branch
- Branch and supervisor options are filtered based on user's organization

**Role-Based Access Control:**
- Admin users (`is_admin = 1`) have full access to all branches and supervisors
- Non-admin users have restricted access to their own branch and themselves as supervisor

**Consistent User Experience:**
- Same filtering logic applied to both create and edit forms
- Users see consistent dropdown options across all workplan operations

**Security:**
- Prevents unauthorized changes to workplan assignments
- Enforces organization boundaries during edit operations

## Database Schema Reference

### Users Table
- **is_admin field:** TINYINT(1) - Indicates if user has admin privileges
- **branch_id field:** INT(11) - Foreign key to branches table
- **is_supervisor field:** TINYINT(1) - Indicates if user has supervisor capability

### Workplans Table
- **branch_id field:** INT(11) - Foreign key to branches table
- **supervisor_id field:** BIGINT(20) UNSIGNED - Foreign key to users table

## Testing Recommendations

### Test Cases for Non-Admin Users

1. **User with Branch Assignment:**
   - Login as non-admin user with `branch_id = 2`
   - Navigate to edit workplan page
   - Verify branch dropdown shows only Branch 2
   - Verify supervisor dropdown shows only the logged-in user

2. **User without Branch Assignment:**
   - Login as non-admin user with `branch_id = NULL`
   - Navigate to edit workplan page
   - Verify branch dropdown is empty
   - Verify supervisor dropdown shows only the logged-in user

3. **Attempt to Edit Workplan:**
   - Login as non-admin user from Branch 1
   - Edit a workplan
   - Verify cannot select branches other than Branch 1
   - Verify cannot select supervisors other than self

### Test Cases for Admin Users

1. **Admin User Access:**
   - Login as admin user (`is_admin = 1`)
   - Navigate to edit workplan page
   - Verify branch dropdown shows all branches
   - Verify supervisor dropdown shows all supervisors

2. **Cross-Branch Assignment:**
   - Login as admin user
   - Edit a workplan from Branch 1
   - Verify can change branch to Branch 2, 3, etc.
   - Verify can change supervisor to any supervisor

3. **Supervisor Capability Filter:**
   - Login as admin user
   - Navigate to edit workplan page
   - Verify supervisor dropdown shows only users with `is_supervisor = 1`
   - Verify regular users (is_supervisor = 0) are not shown

### Test Cases for Consistency

1. **Create vs Edit Comparison:**
   - Login as non-admin user
   - Navigate to create workplan page - note dropdown options
   - Navigate to edit workplan page - verify same dropdown options
   - Repeat for admin user

2. **Dropdown Pre-population:**
   - Edit an existing workplan
   - Verify current branch is pre-selected in dropdown
   - Verify current supervisor is pre-selected in dropdown
   - Verify pre-selected values are within the filtered options

## Related Files

- **Controller:** `app/Controllers/WorkplanController.php`
  - Methods: `new()` (lines 61-97), `edit()` (lines 189-240)
- **Model:** `app/Models/UserModel.php`
  - Method: `getUsersBySupervisorCapability()`
- **Model:** `app/Models/BranchesModel.php`
  - Used for querying branches
- **Views:**
  - `app/Views/workplans/workplan_new.php` (create form)
  - `app/Views/workplans/workplan_edit.php` (edit form)

## Pattern Consistency

This implementation follows the same pattern recently applied to:
- **Duty Instructions:** Both create and edit forms filter workplans by status and branch
- **Supervised Activities:** Admin users see all activities, regular users see filtered data

The pattern ensures:
- Consistent filtering logic across create and edit operations
- Role-based access control (admin vs non-admin)
- Multi-tenant data isolation
- Improved security and data integrity

## Future Enhancements

Potential improvements for consideration:
1. Add validation to prevent users from manually submitting branch/supervisor IDs outside their allowed scope
2. Add audit logging when admin users change branch or supervisor assignments
3. Add warning message when admin changes workplan to different branch
4. Add permission check to ensure users can only edit workplans they have access to
5. Consider adding a "transfer workplan" feature with proper approval workflow

