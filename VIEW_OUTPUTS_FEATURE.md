# View Outputs Feature - Implementation Summary

## Overview
Added a "View Outputs" button next to the "Mark Complete" button in the Supervised Activities page. This feature allows supervisors to view all activities (outputs) that are linked to a specific supervised activity.

## Feature Description
When a supervisor clicks the "View Outputs" button for a supervised activity, they will see a detailed page showing:
1. The supervised activity details (code, title, workplan, branch, target output, budget, description)
2. A list of all activities linked to this supervised activity through the `myactivities_workplan_activities` junction table
3. Details for each linked activity including: title, type, location, date range, action officer, cost, and status

## Files Modified/Created

### 1. Controller - Modified
**File:** `app/Controllers/SupervisedActivitiesController.php`

**Changes:**
- Added `MyActivitiesWorkplanActivitiesModel` and `ActivitiesModel` imports
- Added model properties to constructor
- Added new method: `viewOutputs($id)` - Displays linked activities for a supervised activity

**Method Details:**
```php
public function viewOutputs($id)
```
- Validates user is logged in
- Retrieves workplan activity with details
- Verifies user is the supervisor of the activity
- Fetches all linked activities with comprehensive details (title, type, location, dates, officer, cost, status)
- Joins with activities, gov_structure, and users tables for complete information
- Returns view with workplan activity and linked activities data

### 2. Index View - Modified
**File:** `app/Views/supervised_activities/supervised_activities_index.php`

**Changes:**
- Modified the Actions column to use a button group
- Added "View Outputs" button before "Mark Complete" button
- Changed "Completed" status from text to a disabled button for consistency
- "View Outputs" button links to: `/supervised-activities/{id}/view-outputs`

**Button Layout:**
```
[View Outputs] [Mark Complete]  (for pending activities)
[View Outputs] [Completed]      (for completed activities)
```

### 3. Outputs View - Created
**File:** `app/Views/supervised_activities/supervised_activities_outputs.php`

**Features:**
- Breadcrumb navigation (Dashboard → Supervised Activities → Linked Activities)
- Supervised Activity Details card showing:
  - Activity code, title, workplan name
  - Branch, target output, budget
  - Description (if available)
- Linked Activities table with columns:
  - # (row number)
  - Activity Title (with truncated description)
  - Type (color-coded badge)
  - Location (with province/district)
  - Date Range (start and end dates)
  - Action Officer
  - Cost
  - Status (color-coded badge with date)
  - Actions (View button - opens activity in new tab)
- DataTables integration for search, sort, and pagination
- "Back to Supervised Activities" button in footer
- Responsive design

**Type Color Coding:**
- Documents: Blue (primary)
- Trainings: Green (success)
- Meetings: Light Blue (info)
- Agreements: Yellow (warning)
- Inputs: Gray (secondary)
- Infrastructures: Dark (dark)
- Outputs: Red (danger)

**Status Color Coding:**
- Pending: Yellow (warning)
- Active: Light Blue (info)
- Submitted: Blue (primary)
- Approved: Green (success)
- Rated: Dark (dark)

### 4. Routes - Modified
**File:** `app/Config/Routes.php`

**Changes:**
- Added route: `GET /supervised-activities/{id}/view-outputs`
- Route calls: `SupervisedActivitiesController::viewOutputs/$1`

## Database Tables Used

### Primary Tables:
1. **workplan_activities** - The main supervised activity
2. **myactivities_workplan_activities** - Junction table linking activities to supervised activities
3. **activities** - The actual activities (outputs) linked to supervised activities

### Joined Tables:
4. **workplans** - For workplan title
5. **branches** - For branch name
6. **gov_structure** - For province and district names
7. **users** - For action officer names

## Data Flow

1. User clicks "View Outputs" button on supervised activity
2. System validates user is logged in and is the supervisor
3. System retrieves supervised activity details
4. System queries `myactivities_workplan_activities` table for all links where `workplan_activities_id` matches
5. System joins with `activities` table to get full activity details
6. System joins with related tables for location and user information
7. System displays results in a formatted table

## Security Features

- ✅ User authentication check
- ✅ Supervisor authorization check (only assigned supervisor can view)
- ✅ Activity existence validation
- ✅ CSRF protection on forms
- ✅ SQL injection protection via Query Builder
- ✅ XSS protection via output escaping

## Testing Instructions

### Prerequisites
1. Log in as a supervisor user
2. Ensure you have supervised activities assigned to you
3. Ensure some activities are linked to your supervised activities via the `myactivities_workplan_activities` table

### Test Steps

#### Test 1: View Outputs Button Visibility
1. Navigate to: `http://localhost/amis_six/supervised-activities`
2. **Expected:** Each activity row shows a "View Outputs" button
3. **Expected:** Button appears before "Mark Complete" or "Completed" button

#### Test 2: View Linked Activities
1. Click "View Outputs" button on any supervised activity
2. **Expected:**
   - Page displays supervised activity details at the top
   - Shows count of linked activities in badge
   - Lists all linked activities in a table
   - Each activity shows complete information

#### Test 3: No Linked Activities
1. Click "View Outputs" on a supervised activity with no linked activities
2. **Expected:**
   - Supervised activity details still display
   - Info message: "No activities have been linked to this supervised activity yet."

#### Test 4: Activity Details
1. On the outputs page, verify each linked activity shows:
   - Activity title and description (truncated)
   - Type badge with appropriate color
   - Location with province/district
   - Date range formatted correctly
   - Action officer name
   - Cost formatted as currency
   - Status badge with appropriate color
   - View button (eye icon)

#### Test 5: View Activity Link
1. Click the "View" (eye icon) button on a linked activity
2. **Expected:** 
   - Opens activity details page in new tab
   - Shows full activity information

#### Test 6: DataTable Features
1. Use the search box to filter activities
2. Click column headers to sort
3. Change number of entries displayed
4. Navigate through pages if multiple activities
5. **Expected:** All DataTable features work correctly

#### Test 7: Navigation
1. Click "Back to Supervised Activities" button
2. **Expected:** Returns to supervised activities list
3. Use breadcrumb navigation
4. **Expected:** Can navigate to Dashboard or back to Supervised Activities

#### Test 8: Security
1. Try to access outputs page for activity not assigned to you (modify URL)
2. **Expected:** Error message "You are not authorized to view this activity"
3. Try to access without logging in
4. **Expected:** Redirected to login page

#### Test 9: Responsive Design
1. View the outputs page on different screen sizes
2. **Expected:** Table is responsive and scrollable on mobile devices

### Sample Test Data

If you need to create test data:

```sql
-- Link an activity to a workplan activity
INSERT INTO myactivities_workplan_activities (
    my_activities_id,
    workplan_activities_id,
    created_by,
    created_at,
    updated_at
) VALUES (
    1, -- Replace with valid activity ID from activities table
    1, -- Replace with valid workplan activity ID
    YOUR_USER_ID,
    NOW(),
    NOW()
);
```

## Troubleshooting

### No Activities Showing
- Verify records exist in `myactivities_workplan_activities` table
- Check that `workplan_activities_id` matches the workplan activity ID
- Ensure activities are not soft-deleted (`deleted_at IS NULL`)

### View Button Not Working
- Check browser console for JavaScript errors
- Verify activity ID exists in activities table
- Check that route is properly configured

### Authorization Errors
- Verify you are logged in as the correct supervisor
- Check that `supervisor_id` in workplan_activities matches your user ID

## Technical Notes

### Models Used
- `WorkplanActivityModel` - For workplan activity data
- `MyActivitiesWorkplanActivitiesModel` - For junction table queries
- `ActivitiesModel` - For activity details

### Query Optimization
- Uses LEFT JOINs to handle missing related data gracefully
- Single query retrieves all linked activities with related information
- Efficient use of CodeIgniter Query Builder

### UI/UX Considerations
- Color-coded badges for quick visual identification
- Truncated descriptions to keep table compact
- Responsive table with horizontal scroll on mobile
- Opens activity details in new tab to preserve context
- Clear breadcrumb navigation

## Future Enhancements (Not Implemented)

Potential features that could be added:
- Filter linked activities by type or status
- Export linked activities to PDF/Excel
- Add/remove activity links from this page
- Bulk actions on linked activities
- Activity statistics/summary
- Timeline view of linked activities

---

**Implementation Date:** 2025-09-30
**Developer:** Augment Agent
**Status:** Ready for Testing

