# User Accessibility Flags Implementation

## Overview
This document describes the implementation of user accessibility flags display in the AMIS system. The flags indicate special permissions/capabilities that users have beyond their basic role.

## Accessibility Flags
The system tracks three boolean flags for each user:
- **`is_admin`** - Administrator privileges
- **`is_supervisor`** - Supervisor privileges  
- **`is_evaluator`** - Evaluator privileges

## Implementation Details

### 1. Database Structure
The flags are stored in the `users` table:
```sql
`is_evaluator` tinyint(1) NOT NULL DEFAULT 0,
`is_supervisor` tinyint(1) NOT NULL DEFAULT 0,
`is_admin` tinyint(3) NOT NULL DEFAULT 0,
```

### 2. Session Storage
During login (`app/Controllers/Home.php`), these flags are stored in the session:
```php
session()->set([
    'user_id' => $user['id'],
    'email' => $user['email'],
    'role' => $user['role'],
    'fname' => $user['fname'],
    'lname' => $user['lname'],
    'is_admin' => $user['is_admin'] ?? 0,
    'is_supervisor' => $user['is_supervisor'] ?? 0,
    'is_evaluator' => $user['is_evaluator'] ?? 0,
    // ... other session data
]);
```

### 3. UserModel Helper Methods
Added four new methods to `app/Models/UserModel.php`:

#### a) `getUserAccessibilityBadges($user = null): string`
Returns HTML badges for display in views.
- Uses session data if no user array provided
- Returns formatted HTML with Bootstrap badges and Font Awesome icons
- Example output: `<span class="badge bg-danger"><i class="fas fa-user-shield me-1"></i>Admin</span>`

#### b) `getUserAccessibilityFlags($user = null): array`
Returns boolean array of flags.
- Uses session data if no user array provided
- Returns: `['is_admin' => true/false, 'is_supervisor' => true/false, 'is_evaluator' => true/false]`

#### c) `getRoleWithAccessibilityText($user = null): string`
Returns formatted text combining role and flags.
- Uses session data if no user array provided
- Example output: "User (Admin, Supervisor)"

#### d) `hasAccessibilityFlag(string $flag, $user = null): bool`
Checks if user has a specific flag.
- Accepts 'admin', 'supervisor', or 'evaluator' as flag parameter
- Uses session data if no user array provided

### 4. View Updates

#### Dashboard Landing Page (`app/Views/dashboard/dashboard_landing.php`)
Updated the welcome section to display accessibility badges next to the user role:

```php
<span class="badge bg-light text-primary"><?= ucfirst(esc($user['role'])) ?></span>

<?php 
// Display user accessibility flags from session
$isAdmin = session()->get('is_admin') ?? 0;
$isSupervisor = session()->get('is_supervisor') ?? 0;
$isEvaluator = session()->get('is_evaluator') ?? 0;
?>

<?php if($isAdmin == 1): ?>
    <span class="badge bg-danger">
        <i class="fas fa-user-shield me-1"></i>Admin
    </span>
<?php endif; ?>

<?php if($isSupervisor == 1): ?>
    <span class="badge bg-warning text-dark">
        <i class="fas fa-user-tie me-1"></i>Supervisor
    </span>
<?php endif; ?>

<?php if($isEvaluator == 1): ?>
    <span class="badge bg-info">
        <i class="fas fa-clipboard-check me-1"></i>Evaluator
    </span>
<?php endif; ?>
```

#### Dashboard Profile Page (`app/Views/dashboard/dashboard_profile.php`)
Added the same badge display below the user's role in the profile card.

### 5. Badge Styling
Each flag has a distinct color scheme:
- **Admin**: Red badge (`bg-danger`) with shield icon
- **Supervisor**: Yellow/Orange badge (`bg-warning`) with user-tie icon
- **Evaluator**: Blue badge (`bg-info`) with clipboard-check icon

## Usage Examples

### In Views (Using Session Data)
```php
<?php 
$isAdmin = session()->get('is_admin') ?? 0;
$isSupervisor = session()->get('is_supervisor') ?? 0;
$isEvaluator = session()->get('is_evaluator') ?? 0;
?>

<?php if($isAdmin == 1): ?>
    <span class="badge bg-danger">
        <i class="fas fa-user-shield me-1"></i>Admin
    </span>
<?php endif; ?>
```

### Using UserModel Helper Methods
```php
// In controller
$userModel = new \App\Models\UserModel();

// Get badges HTML (from session)
$badges = $userModel->getUserAccessibilityBadges();

// Get badges HTML (from user array)
$badges = $userModel->getUserAccessibilityBadges($userData);

// Get flags as array
$flags = $userModel->getUserAccessibilityFlags();

// Get role with flags text
$roleText = $userModel->getRoleWithAccessibilityText(); // "User (Admin, Supervisor)"

// Check specific flag
if ($userModel->hasAccessibilityFlag('admin')) {
    // User has admin privileges
}
```

### In Views (Using Helper Methods)
```php
<?php 
$userModel = new \App\Models\UserModel();
echo $userModel->getUserAccessibilityBadges(); // Displays all applicable badges
?>
```

## Security Considerations

1. **Session-Based**: Flags are stored in session during login, ensuring they reflect the user's current permissions
2. **Filter Protection**: The `AdminFilter` checks `session()->get('is_admin')` to protect admin routes
3. **Database Source**: All flags originate from the database and are set during user creation/update
4. **No Client-Side Modification**: Flags cannot be modified from the client side

## Files Modified

1. **`app/Models/UserModel.php`**
   - Added 4 new helper methods for accessibility flag handling

2. **`app/Views/dashboard/dashboard_landing.php`**
   - Added badge display in welcome section

3. **`app/Views/dashboard/dashboard_profile.php`**
   - Added badge display in profile card

4. **`app/Controllers/DashboardController.php`**
   - Fixed error by removing non-existent MeetingsModel and DocumentModel references

## Testing

To test the implementation:

1. **Login as a user** with different flag combinations
2. **Check the dashboard** - badges should appear next to the role
3. **Check the profile page** - badges should appear below the role
4. **Verify session data** - Use browser dev tools to check session contains the flags

## Future Enhancements

Potential improvements:
1. Add tooltips to badges explaining what each privilege grants
2. Create a dedicated "My Permissions" section in the profile
3. Add permission management interface for admins
4. Implement role-based menu filtering based on flags
5. Add audit logging when flags are changed

## Notes

- The implementation uses session data for performance (no database queries needed)
- Badges only display if the flag is set to 1 (true)
- The basic `role` field (user/guest) is still displayed separately
- Icons use Font Awesome 5+ classes
- Bootstrap 5 badge classes are used for styling

