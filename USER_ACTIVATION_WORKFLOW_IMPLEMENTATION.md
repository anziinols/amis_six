# User Activation Workflow Implementation

## Overview
This document outlines the complete implementation of the email-based user activation workflow for the AMIS Five system. The workflow replaces direct password creation with a secure activation process.

## Database Changes

### SQL Schema Updates
Execute the following SQL statements to add activation fields to the users table:

```sql
-- Add activation workflow fields to users table
ALTER TABLE `users` 
ADD COLUMN `activation_token` VARCHAR(255) NULL COMMENT 'Secure token for account activation',
ADD COLUMN `activation_expires_at` DATETIME NULL COMMENT 'Expiration timestamp for activation token',
ADD COLUMN `activated_at` DATETIME NULL COMMENT 'Timestamp when user completed activation',
ADD COLUMN `is_activated` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Activation status flag (0=pending, 1=activated)';

-- Add indexes for performance optimization
ALTER TABLE `users`
ADD INDEX `idx_activation_token` (`activation_token`),
ADD INDEX `idx_is_activated` (`is_activated`),
ADD INDEX `idx_activation_expires` (`activation_expires_at`);
```

### Optional: Update Existing Users
To mark existing users as activated (since they were created before the activation workflow):

```sql
UPDATE users 
SET is_activated = 1, 
    activated_at = created_at 
WHERE is_activated = 0 AND password IS NOT NULL AND password != '';
```

## Implementation Details

### 1. Form Changes
- **File**: `app/Views/admin/users/admin_users_create.php`
- **Changes**: 
  - Removed password field from user creation form
  - Added informational alert about activation process
  - Maintained all other existing fields

### 2. UserModel Updates
- **File**: `app/Models/UserModel.php`
- **Changes**:
  - Added activation fields to `$allowedFields`
  - Removed password requirement from validation rules
  - Added activation helper methods:
    - `generateActivationToken()`: Creates secure 64-character token
    - `setActivationToken()`: Sets token with expiration (default 48 hours)
    - `validateActivationToken()`: Validates token and checks expiration
    - `activateUser()`: Completes activation and sets temporary password
    - `needsActivation()`: Checks if user needs activation
    - `getPendingActivationUsers()`: Gets users awaiting activation
    - `isRecentlyCreated()`: Checks 24-hour window for delete permission

### 3. User Creation Process
- **File**: `app/Controllers/Admin/UsersController.php`
- **Changes**:
  - Modified `store()` method to generate activation tokens instead of passwords
  - Users created with `user_status = 0` (inactive) and `is_activated = 0`
  - Sends activation email instead of creation notification
  - Added `sendActivationEmail()` method with secure HTML template

### 4. Activation Controller
- **File**: `app/Controllers/ActivationController.php`
- **Features**:
  - Handles activation link clicks from emails
  - Validates tokens and expiration times
  - Generates secure 4-digit temporary passwords
  - Activates user accounts and sets status to active
  - Sends temporary password email after successful activation

### 5. Admin Management Features
- **File**: `app/Views/admin/users/admin_users_index.php`
- **Features**:
  - Added "Activation" column showing activation status
  - "Resend Activation" button for pending users
  - "Delete" button visible only within 24 hours of creation
  - Enhanced action buttons with proper grouping and tooltips

### 6. Routes Configuration
- **File**: `app/Config/Routes.php`
- **Added Routes**:
  - `GET activate/(:any)` - Handles activation links
  - `POST admin/users/(:num)/resend-activation` - Resends activation emails
  - `DELETE admin/users/(:num)` - Deletes users (24-hour window)

## Security Features

### Token Security
- Uses `bin2hex(random_bytes(32))` for cryptographically secure tokens
- Tokens are hashed with SHA-256 before database storage
- 48-hour expiration window for activation links
- Single-use tokens (cleared after activation)

### Email Security
- Professional HTML email templates
- Clear activation instructions and security warnings
- Temporary password delivery only after successful activation
- Proper error handling and logging

### Admin Controls
- Delete permission limited to 24-hour window after creation
- Resend activation only available for unactivated users
- Proper CSRF protection on all forms
- Confirmation dialogs for destructive actions

## Workflow Process

### 1. Admin Creates User
1. Admin fills out user creation form (no password field)
2. System generates unique user code automatically
3. User created with inactive status and pending activation
4. Secure activation token generated and stored (hashed)
5. Activation email sent to user with 48-hour expiration

### 2. User Activation
1. User clicks activation link in email
2. System validates token and expiration
3. If valid, generates 4-digit temporary password
4. User account activated and status set to active
5. Temporary password email sent to user

### 3. Admin Management
- View activation status in users list
- Resend activation emails for pending users
- Delete users within 24-hour creation window
- Standard edit and status toggle functionality preserved

## Email Templates

### Activation Email
- Professional design with clear call-to-action button
- 48-hour expiration notice
- Security warnings and contact information
- Fallback text link for email clients

### Temporary Password Email
- Secure password display
- Login instructions with direct link
- Password change reminders
- Security best practices

## Testing Instructions

### 1. Database Setup
```bash
# Execute the SQL schema updates in your MySQL database
mysql -u your_username -p your_database < database_activation_schema_updates.sql
```

### 2. Create Test User
1. Navigate to: `http://localhost/amis_five/admin/users/create`
2. Fill out form (note: no password field)
3. Submit form
4. Check email for activation link

### 3. Test Activation
1. Click activation link from email
2. Check for success message and temporary password email
3. Login with temporary password
4. Verify user is marked as activated in admin panel

### 4. Test Admin Features
1. View users list to see activation status
2. Test "Resend Activation" for pending users
3. Test "Delete" button within 24-hour window
4. Verify delete button disappears after 24 hours

## Error Handling

### Common Scenarios
- **Expired activation links**: Clear error message with support contact
- **Invalid tokens**: Redirect to login with error message
- **Email delivery failures**: Graceful degradation with admin notifications
- **Database errors**: Proper logging and user-friendly messages

### Logging
- All activation attempts logged with user email
- Email delivery status tracked
- Admin actions (resend, delete) logged for audit trail
- Error conditions logged with detailed context

## Backward Compatibility

### Existing Users
- Users created before activation workflow are marked as "Legacy"
- No disruption to existing user accounts
- Standard login process unchanged for activated users
- Admin can still manage existing users normally

### Migration Path
- Optional SQL script to mark existing users as activated
- Gradual rollout possible (new users use activation, existing users unchanged)
- No breaking changes to existing functionality

## Security Considerations

### Best Practices Implemented
- Cryptographically secure token generation
- Proper token hashing before storage
- Time-limited activation windows
- Single-use activation tokens
- Secure temporary password generation
- Comprehensive input validation
- CSRF protection on all forms
- Proper error message handling (no information disclosure)

### Recommendations
- Monitor activation email delivery rates
- Set up email delivery monitoring
- Regular cleanup of expired activation tokens
- Consider implementing rate limiting for activation attempts
- Monitor for suspicious activation patterns

## Maintenance

### Regular Tasks
- Clean up expired activation tokens (optional automated job)
- Monitor email delivery success rates
- Review activation completion rates
- Update email templates as needed

### Monitoring
- Track activation email delivery
- Monitor activation completion rates
- Log and review failed activation attempts
- Monitor admin management actions

---

**Implementation Status**: Complete ✅  
**Testing Required**: Database setup + functional testing  
**Security Review**: Implemented with best practices  
**Documentation**: Complete with examples and troubleshooting
