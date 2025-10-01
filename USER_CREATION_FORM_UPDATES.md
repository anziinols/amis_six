# User Creation Form Updates

## Overview
This document describes the modifications made to the user creation form at `http://localhost/amis_six/admin/users/create` to improve usability and implement automatic user code generation.

## Changes Implemented

### 1. User Code Field - Auto-Generation ✅

**Changes Made:**
- **View (`app/Views/admin/users/admin_users_create.php`):**
  - Removed the "User Code" input field from the form (lines 49-59 removed)
  - User code is no longer visible or editable by users

- **Model (`app/Models/UserModel.php`):**
  - Changed validation rule for `ucode` from `required` to `permit_empty`
  - Removed validation error message for required ucode
  - User code validation now only checks max length (200 characters)

- **Controller (`app/Controllers/Admin/UsersController.php`):**
  - User code is automatically generated using `$this->generateUniqueUserCode()` method
  - Generated code follows pattern: `USR-YYYYMMDD-####` (e.g., USR-20250130-0001)
  - Auto-generation happens before validation and database insertion

**Result:** Users cannot see or input the user code field. The system automatically generates a unique code for each new user.

---

### 2. Reports To Field - Select2 Implementation ✅

**Changes Made:**
- **View (`app/Views/admin/users/admin_users_create.php`):**
  - Select2 library is initialized only for the "Reports To" dropdown
  - Configuration includes:
    - `placeholder: 'Select Supervisor'`
    - `allowClear: true` (allows clearing selection)
    - `width: '100%'` (responsive width)

**JavaScript Code:**
```javascript
$('#report_to_id').select2({
    placeholder: 'Select Supervisor',
    allowClear: true,
    width: '100%'
});
```

**Result:** The "Reports To" dropdown now has searchable functionality with better UX.

---

### 3. Branch Field - Removed Select2 ✅

**Changes Made:**
- **View (`app/Views/admin/users/admin_users_create.php`):**
  - Removed Select2 initialization for the "Branch" dropdown
  - Branch field now uses standard HTML `<select>` element
  - No JavaScript enhancement applied to this field

**Result:** Branch dropdown is now a standard HTML select without Select2 functionality.

---

### 4. Phone Field - Made Optional ✅

**Changes Made:**
- **View (`app/Views/admin/users/admin_users_create.php`):**
  - Removed red asterisk (`<span class="text-danger">*</span>`) from label
  - Removed `required` attribute from input field
  - Label now shows: `<label for="phone" class="form-label">Phone</label>`

- **Model (`app/Models/UserModel.php`):**
  - Validation rule already set to `'phone' => 'permit_empty'`
  - No backend validation errors if phone is left empty

**Result:** Phone field is now optional. Users can leave it blank without validation errors.

---

### 5. User Capabilities Based on Role ✅

**Changes Made:**
- **View (`app/Views/admin/users/admin_users_create.php`):**
  - Added `id="capabilitiesSection"` to the capabilities container
  - Added class `capability-checkbox` to all capability checkboxes
  - Implemented JavaScript to monitor role selection

**JavaScript Logic:**
```javascript
function toggleCapabilities() {
    const selectedRole = roleSelect.value;
    
    if (selectedRole === 'guest') {
        // Hide capabilities section for guest role
        capabilitiesSection.style.display = 'none';
        
        // Uncheck all capability checkboxes
        capabilityCheckboxes.forEach(function(checkbox) {
            checkbox.checked = false;
        });
    } else {
        // Show capabilities section for user role
        capabilitiesSection.style.display = 'block';
    }
}
```

- **Controller (`app/Controllers/Admin/UsersController.php`):**
  - Added backend logic to force capabilities to 0 for guest role:
  ```php
  if (isset($userData['role']) && $userData['role'] === 'guest') {
      $userData['is_evaluator'] = '0';
      $userData['is_supervisor'] = '0';
      $userData['is_admin'] = '0';
  }
  ```

**Behavior:**
- **When "Guest" is selected:**
  - Capabilities section is hidden (`display: none`)
  - All capability checkboxes are automatically unchecked
  - Backend forces all capabilities to 0 regardless of form submission

- **When "User" is selected:**
  - Capabilities section is visible
  - Admin can select appropriate capabilities (Admin, Supervisor, Evaluator)

**Result:** Guest users cannot have any capabilities. The UI prevents selection and backend enforces this rule.

---

## Files Modified

### 1. `app/Views/admin/users/admin_users_create.php`
**Lines Modified:**
- Lines 45-79: Removed User Code field, made Phone optional
- Lines 171-192: Added ID and class to capabilities section
- Lines 229-281: Updated JavaScript for Select2 and role-based capabilities

**Key Changes:**
- Removed user code input field
- Removed required attribute and asterisk from phone field
- Removed Select2 from branch dropdown
- Added Select2 to reports_to dropdown only
- Added role change handler for capabilities visibility

### 2. `app/Models/UserModel.php`
**Lines Modified:**
- Lines 64-121: Updated validation rules and messages

**Key Changes:**
- Changed `ucode` validation from `required` to `permit_empty`
- Removed required validation message for ucode
- Phone already set to `permit_empty` (no change needed)

### 3. `app/Controllers/Admin/UsersController.php`
**Lines Modified:**
- Lines 143-171: Updated user code generation and capability handling

**Key Changes:**
- User code auto-generation maintained
- Added guest role check to force capabilities to 0
- Cleaned up duplicate capability checks

---

## Testing Checklist

### Test Case 1: User Code Auto-Generation
- [ ] Navigate to `/admin/users/create`
- [ ] Verify "User Code" field is NOT visible in the form
- [ ] Fill in required fields and submit
- [ ] Check database - user should have auto-generated code like `USR-20250130-0001`

### Test Case 2: Phone Field Optional
- [ ] Navigate to `/admin/users/create`
- [ ] Verify phone field label has NO red asterisk
- [ ] Leave phone field empty
- [ ] Submit form with other required fields filled
- [ ] Verify no validation error for phone field

### Test Case 3: Reports To Select2
- [ ] Navigate to `/admin/users/create`
- [ ] Click on "Reports To" dropdown
- [ ] Verify Select2 search functionality works
- [ ] Type to search for a supervisor
- [ ] Verify placeholder text shows "Select Supervisor"
- [ ] Verify you can clear selection with X button

### Test Case 4: Branch Standard Dropdown
- [ ] Navigate to `/admin/users/create`
- [ ] Click on "Branch" dropdown
- [ ] Verify it's a standard HTML select (no search functionality)
- [ ] Verify no Select2 styling applied

### Test Case 5: Guest Role Capabilities
- [ ] Navigate to `/admin/users/create`
- [ ] Select "Guest" from Role dropdown
- [ ] Verify capabilities section is hidden
- [ ] Change role to "User"
- [ ] Verify capabilities section is visible
- [ ] Change back to "Guest"
- [ ] Submit form
- [ ] Check database - all capability fields should be 0

### Test Case 6: User Role Capabilities
- [ ] Navigate to `/admin/users/create`
- [ ] Select "User" from Role dropdown
- [ ] Verify capabilities section is visible
- [ ] Check some capabilities (Admin, Supervisor, Evaluator)
- [ ] Submit form
- [ ] Check database - selected capabilities should be 1

---

## Database Schema Reference

The `users` table has the following relevant fields:
```sql
`ucode` varchar(200) NOT NULL,
`phone` text NOT NULL,
`role` enum('user','guest') NOT NULL,
`is_evaluator` tinyint(1) NOT NULL DEFAULT 0,
`is_supervisor` tinyint(1) NOT NULL DEFAULT 0,
`is_admin` tinyint(3) NOT NULL DEFAULT 0,
`report_to_id` int(11) DEFAULT NULL,
`branch_id` int(11) DEFAULT NULL,
```

---

## Security Considerations

1. **User Code Generation:**
   - Auto-generated on server side
   - Cannot be manipulated by users
   - Unique constraint enforced by generation logic

2. **Guest Role Enforcement:**
   - Frontend hides capabilities for guest role
   - Backend enforces capabilities = 0 for guest role
   - Double validation prevents privilege escalation

3. **Phone Field:**
   - Optional field reduces friction in user creation
   - Can be added later during profile update

---

## Future Enhancements

1. **User Code Format:**
   - Consider adding organization prefix (e.g., `ORG-USR-20250130-0001`)
   - Add configuration for custom code patterns

2. **Select2 Enhancements:**
   - Add AJAX loading for large supervisor lists
   - Add user photos in Select2 dropdown

3. **Role Management:**
   - Add more granular role types
   - Implement role-based field visibility

4. **Validation:**
   - Add email uniqueness check with better error messages
   - Add phone format validation when provided

---

## Notes

- All changes maintain backward compatibility with existing user records
- The `generateUniqueUserCode()` method already exists in the controller
- Select2 library must be loaded in the template for the Reports To field to work
- JavaScript runs on DOMContentLoaded to ensure proper initialization

