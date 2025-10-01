# Supervised Activities Feature - Model Fix

## Issue
The Supervised Activities feature at `/supervised-activities` was incorrectly using `WorkplanActivityModel` to fetch data from the `workplan_activities` table instead of using `ActivitiesModel` to fetch data from the `activities` table.

## Changes Made

### 1. Controller Updates (`app/Controllers/SupervisedActivitiesController.php`)

#### Removed Dependencies
- Removed `WorkplanActivityModel`
- Removed `WorkplanModel`
- Removed `MyActivitiesWorkplanActivitiesModel`
- Now only uses `ActivitiesModel`

#### Updated `index()` Method
**Before:** Queried `workplan_activities` table with joins to workplans and branches
**After:** Queries `activities` table with joins to:
- `users` (for supervisor and action officer names)
- `gov_structure` (for province and district names)

**Filtering Logic:**
- Regular users: Only see activities where `supervisor_id` matches their user ID
- Admin users: See all activities

#### Updated `markComplete()` Method
**Before:** 
- Used `WorkplanActivityModel->find()` to get activity
- Updated status to 'complete'

**After:**
- Uses `ActivitiesModel->find()` to get activity
- Uses `ActivitiesModel->updateStatus()` method
- Updates status to 'approved' (matching the activities table status workflow)
- Status workflow: pending → active → submitted → approved → rated

#### Updated `viewOutputs()` Method
**Before:** 
- Fetched workplan activity details
- Showed linked activities from junction table

**After:**
- Fetches activity details using `ActivitiesModel->getActivityWithDetails()`
- Redirects to the activity view page (`/activities/{id}/view`) which shows implementation details

### 2. View Updates (`app/Views/supervised_activities/supervised_activities_index.php`)

#### Updated Table Headers
**Before:**
- Activity Code
- Title
- Workplan
- Branch
- Target Output
- Budget

**After:**
- Activity Title
- Type
- Location
- Date Range
- Action Officer
- Total Cost

#### Updated Table Data Display
**Before:** Displayed fields from `workplan_activities` table:
- `activity_code`
- `title`
- `workplan_title`
- `branch_name`
- `target_output`
- `total_budget`
- `status` (complete/pending)

**After:** Displays fields from `activities` table:
- `activity_title`
- `activity_description`
- `type` (documents, trainings, meetings, agreements, inputs, infrastructures, outputs)
- `location`, `district_name`, `province_name`
- `date_start`, `date_end`
- `action_officer_name`
- `supervisor_name` (for admin users)
- `total_cost`
- `status` (pending, active, submitted, approved, rated)

#### Updated Status Display
Added color-coded badges for all activity statuses:
- **Pending**: Warning (yellow)
- **Active**: Info (blue)
- **Submitted**: Primary (blue)
- **Approved**: Success (green)
- **Rated**: Dark (black)

#### Updated Action Buttons
- Changed "View Outputs" to "View" (redirects to activity details page)
- Changed "Mark Complete" to "Approve" (changes status to 'approved')
- Buttons use outline style for consistency

#### Updated Modal
- Changed modal title from "Mark Activity as Complete" to "Approve Activity"
- Updated button text from "Mark as Complete" to "Approve Activity"
- Updated placeholder text to reflect approval action

## Database Schema Reference

### Activities Table Key Fields
```sql
- id (primary key)
- supervisor_id (links to users table)
- action_officer_id (links to users table)
- activity_title
- activity_description
- province_id (links to gov_structure)
- district_id (links to gov_structure)
- date_start
- date_end
- total_cost
- location
- type (enum: documents, trainings, meetings, agreements, inputs, infrastructures, outputs)
- status (enum: pending, active, submitted, approved, rated)
- status_by
- status_at
- status_remarks
```

## Activity Status Workflow

The `activities` table uses the following status workflow:

1. **pending** - Activity created but not yet implemented
2. **active** - Activity is being implemented
3. **submitted** - Activity submitted for supervision
4. **approved** - Activity approved by supervisor (this is what supervised activities feature sets)
5. **rated** - Activity has been evaluated/rated

## Access Control

- **Regular Users (Supervisors)**: Can only see activities where they are assigned as the supervisor (`supervisor_id` matches their user ID)
- **Admin Users**: Can see all supervised activities across all organizations

## Testing Recommendations

1. **Test as Regular Supervisor User:**
   - Verify only activities assigned to you are displayed
   - Test approving an activity
   - Test viewing activity details

2. **Test as Admin User:**
   - Verify all activities are displayed
   - Test approving any activity
   - Verify supervisor column is visible

3. **Test Activity Filtering:**
   - Verify activities are filtered by supervisor_id for non-admin users
   - Verify all statuses display correctly with proper color coding

4. **Test Activity Details:**
   - Click "View" button to ensure it redirects to activity details page
   - Verify implementation details are displayed correctly

## Related Files

- Controller: `app/Controllers/SupervisedActivitiesController.php`
- Model: `app/Models/ActivitiesModel.php`
- View: `app/Views/supervised_activities/supervised_activities_index.php`
- Routes: `app/Config/Routes.php` (no changes needed)

