# Activity Links Dropdown Filter Fix

## Issue
At `http://localhost/amis_six/activities/{id}/links`, the dropdown lists for linking activities were showing unfiltered data:

1. **Duty Instructions Dropdown**: Showed ALL duty instructions from all users
2. **Workplans Dropdown**: Showed ALL workplans regardless of branch or status

## Requirements
1. **Duty Instructions**: Should only display duty instructions where the logged-in user is assigned as `user_id` OR `supervisor_id`
2. **Workplans**: Should only display workplans where:
   - `branch_id` matches the logged-in user's branch
   - `status` = 'in_progress'

## Solution

### File: `app/Controllers/ActivitiesController.php`

Updated the `manageLinks($id)` method to filter both duty instructions and workplans based on the logged-in user's context.

#### Changes Made (Lines 544-583)

**Before:**
```php
// Get existing links
$linkedDutyInstructions = $myActivitiesDutyModel->getDutyInstructionsByMyActivity($id);
$linkedWorkplanActivities = $myActivitiesWorkplanModel->getWorkplanActivitiesByMyActivity($id);

// Get available duty instructions
$dutyInstructions = $dutyInstructionsModel->findAll();

// Get all duty instruction items (for dropdown and existing links display)
$dutyInstructionItems = $dutyInstructionItemsModel
    ->select('duty_instruction_items.*, duty_instructions.duty_instruction_title')
    ->join('duty_instructions', 'duty_instructions.id = duty_instruction_items.duty_instruction_id')
    ->findAll();

// Get available workplan activities
$workplanActivities = $workplanActivityModel->findAll();

// Get available workplans
$workplans = $workplanModel->where('deleted_at', null)->findAll();
```

**After:**
```php
// Get existing links
$linkedDutyInstructions = $myActivitiesDutyModel->getDutyInstructionsByMyActivity($id);
$linkedWorkplanActivities = $myActivitiesWorkplanModel->getWorkplanActivitiesByMyActivity($id);

// Get logged-in user ID and branch
$loggedInUserId = session()->get('user_id');
$userModel = new \App\Models\UserModel();
$user = $userModel->find($loggedInUserId);
$userBranchId = $user['branch_id'] ?? null;

// Get available duty instructions - only for logged-in user (assigned or supervisor)
$dutyInstructions = $dutyInstructionsModel
    ->groupStart()
        ->where('user_id', $loggedInUserId)
        ->orWhere('supervisor_id', $loggedInUserId)
    ->groupEnd()
    ->where('deleted_at', null)
    ->findAll();

// Get all duty instruction items (for dropdown and existing links display)
// Filter to only items from duty instructions assigned to logged-in user
$dutyInstructionItems = $dutyInstructionItemsModel
    ->select('duty_instruction_items.*, duty_instructions.duty_instruction_title')
    ->join('duty_instructions', 'duty_instructions.id = duty_instruction_items.duty_instruction_id')
    ->groupStart()
        ->where('duty_instructions.user_id', $loggedInUserId)
        ->orWhere('duty_instructions.supervisor_id', $loggedInUserId)
    ->groupEnd()
    ->where('duty_instruction_items.deleted_at', null)
    ->findAll();

// Get available workplan activities
$workplanActivities = $workplanActivityModel->findAll();

// Get available workplans - only from user's branch and status = 'in_progress'
$workplans = $workplanModel
    ->where('branch_id', $userBranchId)
    ->where('status', 'in_progress')
    ->where('deleted_at', null)
    ->findAll();
```

## Key Changes

### 1. User Context Retrieval
```php
$loggedInUserId = session()->get('user_id');
$userModel = new \App\Models\UserModel();
$user = $userModel->find($loggedInUserId);
$userBranchId = $user['branch_id'] ?? null;
```

### 2. Duty Instructions Filter
```php
$dutyInstructions = $dutyInstructionsModel
    ->groupStart()
        ->where('user_id', $loggedInUserId)
        ->orWhere('supervisor_id', $loggedInUserId)
    ->groupEnd()
    ->where('deleted_at', null)
    ->findAll();
```
- Only shows duty instructions where user is assigned as user OR supervisor
- Excludes soft-deleted records

### 3. Duty Instruction Items Filter
```php
$dutyInstructionItems = $dutyInstructionItemsModel
    ->select('duty_instruction_items.*, duty_instructions.duty_instruction_title')
    ->join('duty_instructions', 'duty_instructions.id = duty_instruction_items.duty_instruction_id')
    ->groupStart()
        ->where('duty_instructions.user_id', $loggedInUserId)
        ->orWhere('duty_instructions.supervisor_id', $loggedInUserId)
    ->groupEnd()
    ->where('duty_instruction_items.deleted_at', null)
    ->findAll();
```
- Filters items to only those from duty instructions assigned to the user
- Ensures consistency with the duty instructions dropdown

### 4. Workplans Filter
```php
$workplans = $workplanModel
    ->where('branch_id', $userBranchId)
    ->where('status', 'in_progress')
    ->where('deleted_at', null)
    ->findAll();
```
- Only shows workplans from the user's branch
- Only shows workplans with status 'in_progress'
- Excludes soft-deleted records

## Impact

### Before the Fix
- ❌ Users could see and link to duty instructions from other users
- ❌ Users could see and link to workplans from other branches
- ❌ Users could see and link to workplans with any status (pending, completed, etc.)
- ⚠️ Potential data privacy and organizational issues

### After the Fix
- ✅ Users only see duty instructions where they are assigned or supervising
- ✅ Users only see workplans from their own branch
- ✅ Users only see active workplans (status = 'in_progress')
- ✅ Improved data privacy and organizational boundaries
- ✅ Better user experience with relevant data only

## Testing Recommendations

### Test 1: Duty Instructions Dropdown
1. Log in as a regular user
2. Navigate to any activity: `http://localhost/amis_six/activities/{id}/links`
3. Check the "Select Duty Instruction" dropdown
4. ✅ Verify it only shows duty instructions where you are assigned as user or supervisor
5. ✅ Verify it does NOT show duty instructions from other users

### Test 2: Duty Instruction Items Dropdown
1. Select a duty instruction from the dropdown
2. Check the "Select Duty Instruction Item" dropdown
3. ✅ Verify it only shows items from the selected duty instruction
4. ✅ Verify items are from duty instructions you have access to

### Test 3: Workplans Dropdown
1. Check the "Select Workplan" dropdown
2. ✅ Verify it only shows workplans from your branch
3. ✅ Verify all workplans shown have status = 'in_progress'
4. ✅ Verify it does NOT show workplans from other branches
5. ✅ Verify it does NOT show workplans with status 'pending', 'completed', etc.

### Test 4: Cross-User Verification
1. Log in as User A (Branch 1)
2. Note the duty instructions and workplans available
3. Log out and log in as User B (Branch 2)
4. Navigate to the same activity links page
5. ✅ Verify User B sees different duty instructions (their own)
6. ✅ Verify User B sees different workplans (from Branch 2)

### Test 5: Supervisor Access
1. Log in as a user who is a supervisor for some duty instructions
2. Navigate to activity links page
3. ✅ Verify you see duty instructions where you are the supervisor
4. ✅ Verify you see duty instructions where you are the assigned user

## Related Files

- **Controller:** `app/Controllers/ActivitiesController.php`
  - Method: `manageLinks($id)` (Lines 527-598)
  
- **View:** `app/Views/activities/activities_links.php`
  - Duty Instructions dropdown (Lines 74-82)
  - Workplans dropdown (Lines 138-146)

- **Models:**
  - `app/Models/DutyInstructionsModel.php`
  - `app/Models/DutyInstructionItemsModel.php`
  - `app/Models/WorkplanModel.php`
  - `app/Models/UserModel.php`

- **Routes:** `app/Config/Routes.php`
  - Activity links routes (Lines 518-522)

## Database Tables

### duty_instructions
- `id` - Primary key
- `user_id` - Assigned user (FK to users)
- `supervisor_id` - Supervisor user (FK to users)
- `workplan_id` - Related workplan (FK to workplans)
- `deleted_at` - Soft delete timestamp

### workplans
- `id` - Primary key
- `branch_id` - Branch assignment (FK to branches)
- `status` - Workplan status (pending, in_progress, completed, etc.)
- `deleted_at` - Soft delete timestamp

### users
- `id` - Primary key
- `branch_id` - User's branch assignment (FK to branches)

## Security Considerations

### Data Privacy
- Users can only link activities to duty instructions they have access to
- Users cannot see or link to duty instructions from other users
- Maintains organizational boundaries between branches

### Access Control
- Filtering is done at the query level (server-side)
- No client-side filtering that could be bypassed
- Consistent with the access control pattern used in `DutyInstructionsController`

### Audit Trail
- All links are still tracked in junction tables
- No changes to existing link removal or creation logic
- Maintains data integrity

## Notes

- The filtering logic matches the pattern used in `DutyInstructionsController::index()` and `show()` methods
- Workplan filtering follows the same pattern as `DutyInstructionsController::new()` method
- No changes were needed to the view files - they already use the filtered data correctly
- The AJAX endpoints for loading duty instruction items and workplan activities remain unchanged
- This fix improves data privacy and user experience without breaking existing functionality

