# Role-Based Permissions Implementation

## Overview
This document outlines the implementation of role-based permissions for the AMIS system, controlling navigation menu visibility and access based on user roles.

## User Roles and Permissions

### 1. Admin Role
**Access Level:** Full system access
**Navigation Menu Access:**
- ✅ Dashboard
- ✅ Admin Panel (with all submenus)
  - Users
  - Regions
  - Gov. Structure
  - Branches
  - MTDP Plans
  - NASP Plans
  - Corporate Plans
  - Org.Settings
  - Commodities
- ✅ SMEs
- ✅ Commodity Boards
- ✅ Workplans
- ✅ Proposals
- ✅ Activities (with all submenus)
  - My Activities (shows ALL activities)
  - Documents
  - Meetings
  - Agreements
- ✅ Reports (with all submenus)
- ✅ Profile
- ✅ Logout

**Special Permissions:**
- Can view and manage all activities
- Can implement any activity
- Can access all administrative functions

### 2. Supervisor Role
**Access Level:** Supervision and management
**Navigation Menu Access:**
- ✅ Dashboard
- ❌ Admin Panel
- ❌ SMEs
- ❌ Commodity Boards
- ✅ Workplans
- ✅ Proposals
- ✅ Activities (with all submenus)
  - My Activities (shows only activities they supervise)
  - Documents
  - Meetings
  - Agreements
- ✅ Reports (with all submenus)
- ✅ Profile
- ✅ Logout

**Special Permissions:**
- Can view activities they supervise
- Can create and manage proposals
- Can supervise workplans

### 3. User Role (Regular Users)
**Access Level:** Limited to own work
**Navigation Menu Access:**
- ✅ Dashboard
- ❌ Admin Panel
- ❌ SMEs
- ❌ Commodity Boards
- ❌ Workplans
- ❌ Proposals
- ✅ Activities (with all submenus)
  - My Activities (shows only activities assigned to them as action officer)
  - Documents
  - Meetings
  - Agreements
- ✅ Reports (with all submenus)
- ✅ Profile
- ✅ Logout

**Special Permissions:**
- Can only view and implement activities assigned to them
- Can access reports for viewing

### 4. Commodity Role
**Access Level:** Commodity-specific access
**Navigation Menu Access:**
- ✅ Dashboard
- ❌ Admin Panel
- ❌ SMEs
- ✅ Commodity Boards
- ❌ Workplans
- ❌ Proposals
- ❌ Activities
- ✅ Reports (with all submenus)
- ✅ Profile
- ✅ Logout

**Special Permissions:**
- Can access commodity-related functions
- Can view reports

## Implementation Details

### Files Modified/Created

1. **app/Helpers/navigation_helper.php** (NEW)
   - Contains role-based navigation helper functions
   - `canAccessMenu()` - Check menu access by role
   - `canAccessAdminPanel()` - Admin panel access check
   - `canAccessCommodities()` - Commodities access check
   - `getNavigationMenus()` - Get allowed menus by role
   - `shouldShowActivitiesSubmenu()` - Activities submenu visibility
   - `shouldShowReportsSubmenu()` - Reports submenu visibility

2. **app/Config/Autoload.php** (MODIFIED)
   - Added 'navigation' helper to auto-load helpers

3. **app/Views/templates/system_template.php** (MODIFIED)
   - Updated navigation menu to use role-based visibility
   - Added PHP conditionals around menu items
   - Integrated helper functions for menu access control

4. **app/Controllers/ActivitiesController.php** (MODIFIED)
   - Updated `index()` method to filter activities by role:
     - Admin: sees all activities
     - Supervisor: sees activities they supervise
     - User: sees activities assigned to them
   - Updated authorization checks in `show()`, `implement()`, `saveImplementation()`, `submit()`, and `exportPdf()` methods
   - Added supervisor access to view activities they supervise

### Key Functions

#### Navigation Helper Functions
```php
canAccessMenu($menuItem, $userRole = null)
canAccessAdminPanel($userRole = null)
canAccessCommodities($userRole = null)
getNavigationMenus($userRole = null)
shouldShowActivitiesSubmenu($userRole = null)
shouldShowReportsSubmenu($userRole = null)
```

#### Activities Controller Logic
```php
// Role-based activity filtering
if ($userRole === 'admin') {
    // Show all activities
} elseif ($userRole === 'supervisor') {
    // Show activities they supervise
} else {
    // Show only assigned activities
}
```

## Testing

### Manual Testing Steps
1. **Test Admin User:**
   - Login as admin
   - Verify all menus are visible
   - Check access to admin panel and commodities

2. **Test Supervisor User:**
   - Login as supervisor
   - Verify only allowed menus are visible
   - Check activities show only supervised items

3. **Test Regular User:**
   - Login as regular user
   - Verify limited menu access
   - Check activities show only assigned items

4. **Test Commodity User:**
   - Login as commodity user
   - Verify commodity boards access
   - Check limited menu visibility

### Test Script
A test script `test_role_permissions.php` has been created to verify the helper functions work correctly for all roles.

## Security Considerations

1. **Server-side Validation:** All permission checks are performed server-side
2. **Session-based:** Uses CodeIgniter session management
3. **Controller Protection:** Activities controller has proper authorization checks
4. **Database Filtering:** Queries are filtered based on user role and ID

## Future Enhancements

1. **Database-driven Permissions:** Consider moving permissions to database tables
2. **Granular Permissions:** Add more specific permission levels
3. **Audit Logging:** Track permission-based access attempts
4. **Role Hierarchy:** Implement role inheritance

## Notes

- All navigation changes are backward compatible
- Existing functionality remains intact for authorized users
- Helper functions use session data for role determination
- Menu visibility is controlled at the view level
- Data access is controlled at the controller level
