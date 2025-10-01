# Activities Sorting and Status Workflow Implementation

## Overview
This document describes the implementation of the new sorting logic for activities and the corrected status workflow for the AMIS activities feature.

## Implementation Date
January 10, 2025

## Changes Made

### 1. Activity Status Workflow Fix

**File:** `app/Models/ActivitiesModel.php`

**Change:** Modified `setDefaultStatus()` method (line 93-105)

**Before:**
```php
$data['data']['status'] = 'active';
```

**After:**
```php
$data['data']['status'] = 'pending';
```

**Impact:** New activities now correctly start with 'pending' status instead of 'active'.

**Status Workflow:**
1. **pending** - When activity is first created
2. **active** - When activity is implemented (at implementation stage)
3. **submitted** - When activity is submitted for supervisor review
4. **approved** - When supervisor approves the activity
5. **rated** - When activity is rated/evaluated
6. **active** (resent) - When supervisor sends activity back for revision

### 2. Activities Sorting Logic

**Requirement:**
- Show 3 upcoming activities (date_start >= current date) in ascending order (closest to today first)
- Then show all past activities (date_start < current date) in descending order (most recent first)

**Files Modified:**

#### A. `app/Models/ActivitiesModel.php`

**Method 1: `getAllWithDetails()` (lines 169-233)**
- Replaced simple ORDER BY with UNION query approach
- First query: Gets 3 upcoming activities (date_start >= today) ordered ASC, LIMIT 3
- Second query: Gets all past activities (date_start < today) ordered DESC
- Results are merged with upcoming activities first

**Method 2: `getBySupervisor($supervisorId)` (lines 347-412)**
- Applied same sorting logic as getAllWithDetails()
- Filters by supervisor_id
- Returns 3 upcoming + all past activities in correct order

**Method 3: `getByActionOfficer($actionOfficerId)` (lines 414-479)**
- Applied same sorting logic as getAllWithDetails()
- Filters by action_officer_id
- Returns 3 upcoming + all past activities in correct order

#### B. `app/Controllers/ActivitiesController.php`

**Method: `index()` (lines 124-143)**
- Removed custom `usort()` sorting logic
- Sorting is now handled entirely in the model methods
- Added comment explaining that sorting is done in the model

**Before:**
```php
// Sort activities by start date (closest approaching first) and then by status priority
usort($activities, function($a, $b) {
    // ... complex sorting logic ...
});
```

**After:**
```php
// Note: Sorting is now handled in the model methods (getAllWithDetails, getBySupervisor, getByActionOfficer)
// Activities are sorted by: 3 upcoming activities (date_start >= today) in ASC order, then past activities in DESC order
```

#### C. `app/Controllers/SupervisedActivitiesController.php`

**Method: `index()` (lines 23-58)**
- Simplified to use ActivitiesModel methods directly
- Removed custom query builder code
- Now uses `getAllWithDetails()` for admin users
- Uses `getBySupervisor()` for regular supervisor users
- Inherits the correct sorting from model methods

**Before:**
```php
// Build query for activities with related details using ActivitiesModel
$db = \Config\Database::connect();
$builder = $db->table('activities a');
// ... complex query building ...
$activities = $builder->orderBy('a.created_at', 'DESC')->get()->getResultArray();
```

**After:**
```php
// Use ActivitiesModel methods which already implement the correct sorting
if ($isAdmin == 1) {
    $activities = $this->activitiesModel->getAllWithDetails();
} else {
    $activities = $this->activitiesModel->getBySupervisor($currentUserId);
}
```

## Technical Implementation Details

### SQL Query Structure

The new sorting uses a two-query approach with UNION:

```sql
-- Query 1: Upcoming activities (3 closest to today)
SELECT a.*, [user and location joins]
FROM activities a
[LEFT JOINS for users and gov_structure]
WHERE a.deleted_at IS NULL AND a.date_start >= ?
ORDER BY a.date_start ASC
LIMIT 3

-- Query 2: Past activities (all, most recent first)
SELECT a.*, [user and location joins]
FROM activities a
[LEFT JOINS for users and gov_structure]
WHERE a.deleted_at IS NULL AND a.date_start < ?
ORDER BY a.date_start DESC
```

Results are merged in PHP: `array_merge($upcoming, $past)`

### Benefits of This Approach

1. **Consistent Sorting:** All activity listing pages now use the same sorting logic
2. **Performance:** SQL handles the sorting and limiting efficiently
3. **Maintainability:** Sorting logic is centralized in the model
4. **User Experience:** Users see the most relevant activities first (upcoming ones they need to prepare for)

## Testing Checklist

### Status Workflow Testing
- [ ] Create a new activity and verify status is 'pending'
- [ ] Implement an activity and verify status changes to 'active'
- [ ] Submit activity for review and verify status changes to 'submitted'
- [ ] Approve activity as supervisor and verify status changes to 'approved'
- [ ] Resend activity for revision and verify status changes back to 'active'

### Sorting Testing
- [ ] Test activities listing page (http://localhost/amis_six/activities)
  - [ ] Verify 3 upcoming activities appear first in ascending order
  - [ ] Verify past activities appear after in descending order
  - [ ] Test with different user roles (admin, supervisor, action officer)

- [ ] Test supervised activities page (http://localhost/amis_six/supervised-activities)
  - [ ] Verify same sorting applies
  - [ ] Test as admin user (sees all activities)
  - [ ] Test as supervisor user (sees only their supervised activities)

### Edge Cases
- [ ] Test when there are fewer than 3 upcoming activities
- [ ] Test when there are no upcoming activities
- [ ] Test when there are no past activities
- [ ] Test when all activities are on the same date

## Database Schema Reference

### Activities Table - Status Field
```sql
`status` enum('pending','active','submitted','approved','rated') NOT NULL DEFAULT 'pending'
```

### Activities Table - Date Fields
```sql
`date_start` date NOT NULL
`date_end` date NOT NULL
```

## Notes for Developers

1. **Do not modify sorting in controllers** - All sorting logic is now in the model methods
2. **Status transitions** - Always use the `updateStatus()` method in ActivitiesModel to change status
3. **Adding new activity listing views** - Use the existing model methods (getAllWithDetails, getBySupervisor, getByActionOfficer) to ensure consistent sorting
4. **Date comparisons** - The sorting uses `date('Y-m-d')` for current date comparison, which is timezone-aware based on PHP configuration

## Related Files

- `app/Models/ActivitiesModel.php` - Main model with sorting logic
- `app/Controllers/ActivitiesController.php` - Activities listing controller
- `app/Controllers/SupervisedActivitiesController.php` - Supervised activities controller
- `app/Views/activities/activities_index.php` - Activities listing view
- `app/Views/supervised_activities/supervised_activities_index.php` - Supervised activities view

## Future Enhancements

Consider these potential improvements:
1. Make the "3 upcoming activities" limit configurable
2. Add filtering options (by status, date range, etc.)
3. Add pagination for large activity lists
4. Add visual indicators for upcoming vs past activities in the UI
5. Add date-based grouping in the view (e.g., "Upcoming", "This Week", "Past Activities")

