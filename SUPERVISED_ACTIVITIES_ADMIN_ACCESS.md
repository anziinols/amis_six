# Supervised Activities - Admin Privilege Access Control

## Overview
This document describes the implementation of admin privilege access control for the Supervised Activities feature. Admin users (users with `is_admin = 1`) now have cross-organization access to view and manage all supervised activities in the system, while regular users continue to see only activities they supervise.

## Implementation Date
January 10, 2025

## Changes Made

### 1. Controller Updates - `app/Controllers/SupervisedActivitiesController.php`

#### A. `index()` Method
**Purpose:** Display list of supervised activities

**Changes:**
- Added `$isAdmin` variable from session to check admin status
- Modified query to conditionally filter activities:
  - **Admin users (`is_admin = 1`)**: No filter applied - see ALL supervised activities from all organizations
  - **Regular users**: Filter by `supervisor_id` - see only activities they supervise
- Added `supervisor_name` to the SELECT query (JOIN with users table)
- Updated page title dynamically:
  - Admin users: "All Supervised Activities"
  - Regular users: "My Supervised Activities"
- Passed `isAdmin` flag to the view for conditional rendering

**Code Pattern:**
```php
// Get admin status from session
$isAdmin = session()->get('is_admin');

// Build query
$query = $this->workplanActivityModel->select('...')
    ->join('users as supervisors', 'supervisors.id = workplan_activities.supervisor_id', 'left');

// Apply filter based on admin status
if ($isAdmin != 1) {
    // Regular users: only see activities they supervise
    $query->where('workplan_activities.supervisor_id', $currentUserId);
}
// Admin users: no filter applied, see all activities
```

#### B. `viewOutputs()` Method
**Purpose:** View linked activities/outputs for a supervised activity

**Changes:**
- Added `$isAdmin` variable from session
- Added `supervisor_name` to the SELECT query
- Modified authorization check:
  - **Admin users**: Can view outputs for ANY supervised activity
  - **Regular users**: Can only view outputs for activities they supervise
- Passed `isAdmin` flag to the view

**Authorization Pattern:**
```php
// Verify authorization: admin users can view all, regular users only their supervised activities
if ($isAdmin != 1 && $workplanActivity['supervisor_id'] != $currentUserId) {
    return redirect()->to(base_url('supervised-activities'))
        ->with('error', 'You are not authorized to view this activity');
}
```

#### C. `markComplete()` Method
**Purpose:** Mark a supervised activity as complete

**Changes:**
- Added `$isAdmin` variable from session
- Modified authorization check:
  - **Admin users**: Can mark ANY supervised activity as complete
  - **Regular users**: Can only mark activities they supervise as complete
- Updated status remarks to be more generic (removed "by supervisor" text)

**Authorization Pattern:**
```php
// Verify authorization: admin users can update all, regular users only their supervised activities
if ($isAdmin != 1 && $activity['supervisor_id'] != $currentUserId) {
    return redirect()->to(base_url('supervised-activities'))
        ->with('error', 'You are not authorized to update this activity');
}
```

### 2. View Updates - `app/Views/supervised_activities/supervised_activities_index.php`

**Changes:**
- Updated card header title to be dynamic based on `$isAdmin` flag
- Added conditional "Supervisor" column in the table header (only shown for admin users)
- Added supervisor name data in table rows (only shown for admin users)
- Updated empty state message to be contextual:
  - Admin users: "No supervised activities found in the system."
  - Regular users: "No supervised activities assigned to you at this time."

**Conditional Column Pattern:**
```php
<!-- In table header -->
<?php if ($isAdmin): ?>
    <th style="width: 10%;">Supervisor</th>
<?php endif; ?>

<!-- In table body -->
<?php if ($isAdmin): ?>
    <td><?= esc($activity['supervisor_name'] ?? 'N/A') ?></td>
<?php endif; ?>
```

## User Experience

### For Admin Users (`is_admin = 1`)
1. **Page Title:** "All Supervised Activities"
2. **Visibility:** Can see ALL supervised activities from all organizations/branches
3. **Table Columns:** Includes an additional "Supervisor" column showing who supervises each activity
4. **Actions:**
   - Can view outputs/linked activities for ANY supervised activity
   - Can mark ANY supervised activity as complete
5. **Empty State:** "No supervised activities found in the system."

### For Regular Users (`is_admin = 0`)
1. **Page Title:** "My Supervised Activities"
2. **Visibility:** Can only see activities where they are assigned as the supervisor
3. **Table Columns:** Standard columns without the "Supervisor" column
4. **Actions:**
   - Can view outputs/linked activities only for activities they supervise
   - Can mark only their supervised activities as complete
5. **Empty State:** "No supervised activities assigned to you at this time."

## Security Considerations

1. **Session-Based Authorization:** All admin checks use `session()->get('is_admin')` which is set during login
2. **Consistent Checks:** Admin status is checked in all three controller methods (index, viewOutputs, markComplete)
3. **Authorization Before Action:** All modification actions verify user authorization before proceeding
4. **No Direct Database Modification:** Admin users cannot bypass the authorization checks through URL manipulation

## Database Schema Reference

### Users Table
- `is_admin` field: TINYINT(1), values 0 or 1
- Set during user creation/update
- Stored in session during login as `session()->get('is_admin')`

### Workplan Activities Table
- `supervisor_id` field: Foreign key to users table
- Used to filter activities for regular users
- Not used for filtering when user is admin

## Testing Recommendations

1. **Admin User Testing:**
   - Login as a user with `is_admin = 1`
   - Verify all supervised activities from all organizations are visible
   - Verify "Supervisor" column is displayed
   - Test viewing outputs for activities supervised by other users
   - Test marking activities as complete that are supervised by other users

2. **Regular User Testing:**
   - Login as a user with `is_admin = 0` and `is_supervisor = 1`
   - Verify only activities where user is the supervisor are visible
   - Verify "Supervisor" column is NOT displayed
   - Verify cannot access outputs for activities supervised by others (via URL manipulation)
   - Verify cannot mark activities as complete that are supervised by others

3. **Edge Cases:**
   - User with no supervised activities (both admin and regular)
   - Activities with no assigned supervisor
   - Activities from different branches/organizations

## Related Files

- **Controller:** `app/Controllers/SupervisedActivitiesController.php`
- **View:** `app/Views/supervised_activities/supervised_activities_index.php`
- **View:** `app/Views/supervised_activities/supervised_activities_outputs.php`
- **Model:** `app/Models/WorkplanActivityModel.php`
- **Model:** `app/Models/UserModel.php`
- **Routes:** `app/Config/Routes.php` (lines 493-497)

## Multi-Tenant Architecture Alignment

This implementation aligns with the AMIS multi-tenant architecture where:
- Regular users have organization-based access (filtered by their supervisor assignments)
- Admin users (similar to Dakoii super-admin) have cross-organization access
- The system maintains data isolation for regular users while providing full visibility for admin users

## Future Enhancements

Potential improvements for consideration:
1. Add organization/branch filter dropdown for admin users to narrow down the view
2. Add export functionality for admin users to export all supervised activities
3. Add bulk actions for admin users to mark multiple activities as complete
4. Add activity logs to track when admin users perform actions on activities they don't supervise

