# SUPERVISOR ROLE TO CAPABILITY MIGRATION PLAN

## Overview
This document outlines the comprehensive plan to migrate the supervisor functionality from a role-based system to a capability-based system in the AMIS Five application. Currently, supervisor is defined as a role (`role = 'supervisor'`), but it will be changed to a capability using the existing `is_supervisor` field.

## Current State Analysis

### Database Structure
- **Table**: `users`
- **Current Fields**:
  - `role` enum('admin','supervisor','user','guest') - Contains supervisor as role
  - `is_supervisor` tinyint(1) - Already exists but not utilized
  - `is_evaluator` tinyint(1) - Similar capability field (M&E)

### Current Implementation Issues
1. **Dual System**: Both `role` and `is_supervisor` fields exist
2. **Inconsistent Usage**: Code uses `getUsersByRole('supervisor')` instead of `is_supervisor`
3. **Role Validation**: Supervisor included in role enum validation
4. **Session Checks**: Role-based supervisor checks in controllers

## Migration Strategy

### Phase 1: Model Updates

#### 1.1 UserModel.php Updates
**File**: `app/Models/UserModel.php`

**Changes Required**:
- **Line 76**: Update role validation rule
  ```php
  // FROM:
  'role' => 'required|in_list[admin,supervisor,user,guest,commodity]',
  
  // TO:
  'role' => 'required|in_list[admin,user,guest,commodity]',
  ```

- **Add New Method**: Create `getUsersBySupervisorCapability()` method
  ```php
  /**
   * Get users with supervisor capability
   */
  public function getUsersBySupervisorCapability()
  {
      return $this->where('is_supervisor', 1)
                 ->where('user_status', 1)
                 ->findAll();
  }
  ```

#### 1.2 Database Migration Considerations
- **No database structure changes needed** - `is_supervisor` field already exists
- **Data Migration Required**: Update existing users with `role = 'supervisor'`
  ```sql
  UPDATE users SET is_supervisor = 1 WHERE role = 'supervisor';
  UPDATE users SET role = 'user' WHERE role = 'supervisor';
  ```

### Phase 2: Controller Updates

#### 2.1 Admin/UsersController.php
**File**: `app/Controllers/Admin/UsersController.php`

**Lines to Update**:
- **Line 40**: `$supervisors = $this->userModel->getUsersByRole('supervisor');`
- **Line 61**: `$supervisors = $this->userModel->getUsersByRole('supervisor');`
- **Line 244**: `$supervisors = $this->userModel->getUsersByRole('supervisor');`

**Change to**:
```php
$supervisors = $this->userModel->getUsersBySupervisorCapability();
```

#### 2.2 WorkplanController.php
**File**: `app/Controllers/WorkplanController.php`

**Line 55**: Update supervisor query
```php
// FROM:
'supervisors' => $this->userModel->where('role','supervisor')->findAll(),

// TO:
'supervisors' => $this->userModel->getUsersBySupervisorCapability(),
```

#### 2.3 DashboardController.php
**File**: `app/Controllers/DashboardController.php`

**Line 67**: Update role check logic
```php
// FROM:
if ($userRole == 'admin' || $userRole == 'supervisor') {

// TO:
$user = $this->userModel->find($userId);
if ($userRole == 'admin' || $user['is_supervisor'] == 1) {
```

#### 2.4 ProposalsController.php
**File**: `app/Controllers/ProposalsController.php`

**Review and Update**:
- Check supervisor-related logic in proposal creation and supervision
- Ensure supervisor_id assignments work with capability-based system

### Phase 3: View Updates

#### 3.1 User Creation/Edit Forms
**Files**:
- `app/Views/admin/users/admin_users_create.php`
- `app/Views/admin/users/admin_users_edit.php`

**Updates**:
- Add checkbox for `is_supervisor` capability
- Remove supervisor from role dropdown options
- Update form validation and display logic

#### 3.2 Workplan Forms
**Files**:
- `app/Views/workplans/workplan_form.php`
- `app/Views/workplans/workplan_activities_form.php`

**Updates**:
- Supervisor dropdown will now use capability-based data
- No visual changes needed, just data source change

#### 3.3 Proposal Views
**File**: `app/Views/proposals/proposals_supervise.php`

**Updates**:
- Verify supervisor display logic works with new system
- Update any supervisor-specific UI elements

### Phase 4: Authorization & Session Updates

#### 4.1 Session Management
**Review Required**:
- Check how supervisor role is stored in sessions
- Update login logic to handle capability-based supervision
- Ensure proper authorization checks

#### 4.2 Route Protection
**File**: `app/Config/Routes.php`

**Review**:
- No direct supervisor route protection found
- Verify middleware and filters handle capability-based checks

### Phase 5: Documentation Updates

#### 5.1 System Documentation
**Files to Update**:
- `memory_bank/amis_system_profile.json`
- `backup_dp/AMIS_System_Architecture.md`
- `backup_dp/AMIS_User_Navigation.puml`

**Changes**:
- Remove supervisor from roles list
- Add supervisor as capability
- Update user permission documentation

#### 5.2 Security Documentation
**File**: `backup_dp/SEC_Privilege_Escalation_Security_Report.md`

**Updates**:
- Update role validation examples
- Remove supervisor from role-based security checks

## Implementation Tasks

### Task 1: Model Layer Updates
- [ ] Update UserModel.php validation rules
- [ ] Add getUsersBySupervisorCapability() method
- [ ] Test model changes

### Task 2: Data Migration
- [ ] Create backup of users table
- [ ] Run data migration SQL
- [ ] Verify data integrity

### Task 3: Controller Updates
- [ ] Update Admin/UsersController.php (3 locations)
- [ ] Update WorkplanController.php
- [ ] Update DashboardController.php
- [ ] Update ProposalsController.php
- [ ] Test all controller changes

### Task 4: View Updates
- [ ] Update user creation/edit forms
- [ ] Update workplan forms
- [ ] Update proposal views
- [ ] Test all view changes

### Task 5: Authorization Updates
- [ ] Review session management
- [ ] Update authorization logic
- [ ] Test access control

### Task 6: Documentation Updates
- [ ] Update system profile JSON
- [ ] Update architecture documentation
- [ ] Update security documentation

### Task 7: Testing & Validation
- [ ] Unit tests for model changes
- [ ] Integration tests for controller changes
- [ ] UI tests for view changes
- [ ] End-to-end testing
- [ ] Security testing

## Risk Assessment

### High Risk Areas
1. **Data Migration**: Risk of data loss during role conversion
2. **Authorization**: Risk of access control bypass
3. **Session Management**: Risk of authentication issues

### Mitigation Strategies
1. **Database Backup**: Full backup before migration
2. **Staged Deployment**: Test in development environment first
3. **Rollback Plan**: Ability to revert changes if issues occur

## Success Criteria
1. All users with supervisor role converted to supervisor capability
2. All supervisor-related functionality works with is_supervisor field
3. No supervisor role references remain in codebase
4. All tests pass
5. Documentation updated and accurate

## Timeline Estimate
- **Phase 1-2**: 2-3 days (Model and Controller updates)
- **Phase 3**: 1-2 days (View updates)
- **Phase 4**: 1 day (Authorization updates)
- **Phase 5**: 1 day (Documentation)
- **Testing**: 2-3 days
- **Total**: 7-10 days

## Dependencies
- Access to development database
- Ability to run database migrations
- Testing environment availability
- Stakeholder approval for changes

---

**Document Version**: 1.0  
**Created**: 2025-01-18  
**Author**: System Analysis  
**Status**: Planning Phase
