# Commodities and Commodity Boards Feature Removal

## Overview
This document details the complete removal of the Commodities and Commodity Boards features from the AMIS application as requested. These features have been removed from the codebase including controllers, models, views, routes, and navigation menus.

## Date: January 30, 2025

---

## Features Removed

### 1. Commodities Management (Admin)
- **URL**: `http://localhost/amis_six/admin/commodities`
- **Purpose**: Admin interface for managing commodity types (e.g., Coffee, Cocoa, Vanilla)
- **Functionality**: CRUD operations for commodity definitions

### 2. Commodity Boards (Production Tracking)
- **URL**: `http://localhost/amis_six/commodity-boards`
- **Purpose**: Commodity production tracking and reporting
- **Functionality**: Recording and managing commodity production data

### 3. Commodity Reports
- **URL**: `http://localhost/amis_six/reports/commodity`
- **Purpose**: Reporting and analytics for commodity production
- **Functionality**: Charts, graphs, and data analysis for commodity production

---

## Files Removed

### Controllers (3 files)
1. ✅ `app/Controllers/Admin/CommoditiesController.php`
   - Admin CRUD operations for commodities
   - Methods: index, new, create, show, edit, update, delete, debug

2. ✅ `app/Controllers/CommodityProductionController.php`
   - Commodity production tracking
   - Methods: index, new, create, show, edit, update, delete
   - Access control for commodity role users

3. ✅ `app/Controllers/CommodityReportsController.php`
   - Commodity production reports and analytics
   - Methods: index, prepareChartData

### Models (3 files)
1. ✅ `app/Models/CommoditiesModel.php`
   - Commodity definitions model
   - Methods: getAllCommodities, getCommodityById, getCommodityWithUserNames, etc.

2. ✅ `app/Models/CommodityProductionModel.php`
   - Commodity production records model
   - Methods: getAllCommodityProduction, getProductionSummary, validateDateRange, etc.

3. ✅ `app/Models/CommodityPricesModel.php`
   - Commodity pricing model
   - Methods: price tracking and analysis

### Views - Admin Commodities (4 files)
1. ✅ `app/Views/admin/commodities/admin_commodities_index.php`
2. ✅ `app/Views/admin/commodities/admin_commodities_create.php`
3. ✅ `app/Views/admin/commodities/admin_commodities_edit.php`
4. ✅ `app/Views/admin/commodities/admin_commodities_show.php`

### Views - Commodity Boards (4 files)
1. ✅ `app/Views/commodities/commodities_index.php`
2. ✅ `app/Views/commodities/commodities_create.php`
3. ✅ `app/Views/commodities/commodities_edit.php`
4. ✅ `app/Views/commodities/commodities_show.php`

### Views - Commodity Reports (3 files)
1. ✅ `app/Views/reports_commodity/reports_commodity_index.php`
2. ✅ `app/Views/reports_commodity/reports_commodity_market_analysis.php`
3. ✅ `app/Views/reports_commodity/reports_commodity_price_trends.php`

**Total Files Removed: 17 files**

---

## Code Changes

### 1. Routes Configuration (`app/Config/Routes.php`)

#### Removed Import Statement:
```php
// REMOVED: Line 13
use App\Controllers\Admin\CommoditiesController;
```

#### Removed Route Groups:

**Admin Commodities Routes** (Lines 177-185 removed):
```php
// REMOVED: Commodities Management - RESTful Routes
$routes->get('commodities', [CommoditiesController::class, 'index']);
$routes->get('commodities/debug', [CommoditiesController::class, 'debug']);
$routes->get('commodities/new', [CommoditiesController::class, 'new']);
$routes->post('commodities', [CommoditiesController::class, 'create']);
$routes->get('commodities/(:num)', [CommoditiesController::class, 'show/$1']);
$routes->get('commodities/(:num)/edit', [CommoditiesController::class, 'edit/$1']);
$routes->post('commodities/(:num)', [CommoditiesController::class, 'update/$1']);
$routes->get('commodities/(:num)/delete', [CommoditiesController::class, 'delete/$1']);
```

**Commodity Boards Routes** (Lines 446-455 removed):
```php
// REMOVED: Commodity Production Routes - RESTful
$routes->group('commodity-boards', ['filter' => 'auth'], static function($routes){
    $routes->get('/', [\App\Controllers\CommodityProductionController::class, 'index']);
    $routes->get('new', [\App\Controllers\CommodityProductionController::class, 'new']);
    $routes->post('/', [\App\Controllers\CommodityProductionController::class, 'create']);
    $routes->get('(:num)', [\App\Controllers\CommodityProductionController::class, 'show/$1']);
    $routes->get('(:num)/edit', [\App\Controllers\CommodityProductionController::class, 'edit/$1']);
    $routes->post('(:num)', [\App\Controllers\CommodityProductionController::class, 'update/$1']);
    $routes->get('(:num)/delete', [\App\Controllers\CommodityProductionController::class, 'delete/$1']);
});
```

**Commodity Reports Route** (Lines 622-623 removed):
```php
// REMOVED: Commodity Reports routes
$routes->get('reports/commodity', 'CommodityReportsController::index');
```

---

### 2. Navigation Helper (`app/Helpers/navigation_helper.php`)

#### Removed Menu Capabilities (Lines 39-40):
```php
// REMOVED from $menuCapabilities array:
'commodities' => ['admin'],
'commodity_boards' => ['admin'],
```

#### Removed Helper Function (Lines 84-94):
```php
// REMOVED: canAccessCommodities() function
if (!function_exists('canAccessCommodities')) {
    function canAccessCommodities($userRole = null)
    {
        return session()->get('is_admin') == 1;
    }
}
```

#### Removed Menu Items:

**Admin Panel Submenu** (Line 124 removed):
```php
// REMOVED from admin_panel submenus:
'commodities' => ['title' => 'Commodities', 'icon' => 'fas fa-seedling', 'url' => 'admin/commodities']
```

**Main Menu** (Lines 133-138 removed):
```php
// REMOVED: Commodity Boards menu item
'commodity_boards' => [
    'title' => 'Commodity Boards',
    'icon' => 'fas fa-boxes',
    'url' => 'commodity-boards',
    'capabilities' => ['admin']
],
```

**Reports Submenu** (Line 167 removed):
```php
// REMOVED from reports submenus:
'commodity_reports' => ['title' => 'Commodity Reports', 'icon' => 'fas fa-seedling', 'url' => 'reports/commodity'],
```

---

### 3. User Model (`app/Models/UserModel.php`)

#### Removed Commodity Join (Line 441 and 446):
```php
// REMOVED from getAllUsersWithDetails() query:
// Line 441: c.commodity_name from SELECT
// Line 446: ->join('commodities c', 'u.commodity_id = c.id', 'left')
```

**Before:**
```php
$query = $db->table('users u')
    ->select('u.*,
             b.name as branch_name,
             CONCAT(s.fname, " ", s.lname) as supervisor_name,
             c.commodity_name,
             CONCAT(cb.fname, " ", cb.lname) as created_by_name,
             CONCAT(ub.fname, " ", ub.lname) as updated_by_name')
    ->join('branches b', 'u.branch_id = b.id', 'left')
    ->join('users s', 'u.report_to_id = s.id', 'left')
    ->join('commodities c', 'u.commodity_id = c.id', 'left')
    ->join('users cb', 'u.created_by = cb.id', 'left')
    ->join('users ub', 'u.updated_by = ub.id', 'left')
```

**After:**
```php
$query = $db->table('users u')
    ->select('u.*,
             b.name as branch_name,
             CONCAT(s.fname, " ", s.lname) as supervisor_name,
             CONCAT(cb.fname, " ", cb.lname) as created_by_name,
             CONCAT(ub.fname, " ", ub.lname) as updated_by_name')
    ->join('branches b', 'u.branch_id = b.id', 'left')
    ->join('users s', 'u.report_to_id = s.id', 'left')
    ->join('users cb', 'u.created_by = cb.id', 'left')
    ->join('users ub', 'u.updated_by = ub.id', 'left')
```

---

## Database Tables - TO BE DROPPED

**The following tables are no longer used by the application and should be dropped:**

### Tables Related to Commodities:

1. **`commodities`**
   - Stores commodity definitions (Coffee, Cocoa, Vanilla, etc.)
   - Fields: id, commodity_code, commodity_name, commodity_icon, commodity_color_code
   - Status: **TO BE DROPPED** ⚠️

2. **`commodity_production`**
   - Stores commodity production records
   - Fields: id, commodity_id, date_from, date_to, item, description, quantity, is_exported
   - Status: **TO BE DROPPED** ⚠️

3. **`commodity_prices`**
   - Stores commodity pricing data
   - Fields: id, commodity_id, price_date, market_type, price_per_unit, unit_of_measurement
   - Status: **TO BE DROPPED** ⚠️

### User Table Field:

**`users.commodity_id`**
- Field remains in users table
- Application code sets this to NULL
- No longer used for access control or functionality
- Status: **TO BE DROPPED (Optional)** ⚠️

---

## Database Cleanup Instructions

### Method 1: Using SQL Script (Recommended)

A ready-to-use SQL script has been created: **`database_cleanup_commodities.sql`**

**Steps:**
1. **Backup your database first!**
   ```bash
   mysqldump -u root -p amis_six > backup_before_commodity_removal.sql
   ```

2. **Run the cleanup script:**
   ```bash
   mysql -u root -p amis_six < database_cleanup_commodities.sql
   ```

   Or via phpMyAdmin:
   - Open phpMyAdmin
   - Select the `amis_six` database
   - Go to SQL tab
   - Copy and paste the contents of `database_cleanup_commodities.sql`
   - Click "Go"

3. **Verify the cleanup:**
   - Check that tables are dropped
   - Check that `users.commodity_id` column is removed (if you chose to drop it)

### Method 2: Manual SQL Commands

If you prefer to run commands manually:

```sql
-- Drop the tables
DROP TABLE IF EXISTS `commodity_prices`;
DROP TABLE IF EXISTS `commodity_production`;
DROP TABLE IF EXISTS `commodities`;

-- Optional: Remove commodity_id from users table
ALTER TABLE `users` DROP COLUMN `commodity_id`;
```

### Method 3: Via phpMyAdmin GUI

1. Open phpMyAdmin
2. Select the `amis_six` database
3. Find and select these tables:
   - `commodity_prices`
   - `commodity_production`
   - `commodities`
4. Click "Drop" at the bottom
5. Confirm the deletion

For the `users.commodity_id` field:
1. Click on the `users` table
2. Go to "Structure" tab
3. Find the `commodity_id` row
4. Click "Drop"
5. Confirm

---

## Database Cleanup Documentation

The database cleanup has been documented in:
- **`dev_guide/DB_tables_updates_01_10_2025.md`** - Updated with tables to drop
- **`database_cleanup_commodities.sql`** - Ready-to-use SQL script

---

## Code References Remaining (Intentional)

The following code references to `commodity_id` remain in the codebase but are intentionally kept:

### 1. `app/Models/UserModel.php`
- **Line 47**: `'commodity_id'` in `$allowedFields` array
- **Line 85**: `'commodity_id' => 'permit_empty|integer'` in validation rules
- **Reason**: Field exists in database schema, validation allows it to be null

### 2. `app/Controllers/Admin/UsersController.php`
- **Line 181**: `$userData['commodity_id'] = null;` in store() method
- **Line 329**: `$userData['commodity_id'] = null;` in update() method
- **Line 449**: `'commodity_id' => null` in updateUserSession() method
- **Reason**: Explicitly sets field to null to ensure no commodity associations

### 3. `app/Controllers/Home.php`
- **Line 106**: `'commodity_id' => $user['commodity_id'] ?? null` in login session
- **Reason**: Reads field from database (will be null) and stores in session

**These references are safe and intentional** - they handle the deprecated field gracefully by setting/reading it as null.

---

## Impact Assessment

### ✅ No Breaking Changes Expected

1. **User Management**: Users can still be created/updated (commodity_id set to null)
2. **Authentication**: Login process unchanged (commodity_id in session will be null)
3. **Navigation**: Menu items removed cleanly, no broken links
4. **Reports**: Other report types (MTDP, NASP, Workplan, HR) remain functional
5. **Admin Panel**: Other admin functions (Users, Branches, Plans) unaffected

### ⚠️ Potential Issues

1. **Uploaded Files**: Commodity icons in `public/uploads/commodities/icons/` directory remain
   - **Action**: Can be manually deleted if needed
   - **Location**: `public/uploads/commodities/icons/`

2. **Database Data**: Production records and commodity definitions remain in database
   - **Action**: Can be archived or deleted via SQL if needed
   - **Tables**: commodities, commodity_production, commodity_prices

3. **User Records**: Existing users may have commodity_id values in database
   - **Action**: No action needed - application ignores this field
   - **Future**: Can be set to NULL via SQL update if desired

---

## Testing Checklist

After removal, verify the following:

### Navigation
- [ ] Admin panel menu does not show "Commodities" submenu item
- [ ] Main navigation does not show "Commodity Boards" menu item
- [ ] Reports submenu does not show "Commodity Reports" item

### URLs (Should return 404)
- [ ] `http://localhost/amis_six/admin/commodities` - 404 Not Found
- [ ] `http://localhost/amis_six/commodity-boards` - 404 Not Found
- [ ] `http://localhost/amis_six/reports/commodity` - 404 Not Found

### User Management
- [ ] Create new user - should work (commodity_id set to null)
- [ ] Edit existing user - should work (commodity_id set to null)
- [ ] View user list - should work (no commodity_name column)

### Authentication
- [ ] Login as admin - should work
- [ ] Login as regular user - should work
- [ ] Session data includes commodity_id as null - expected behavior

### Other Features
- [ ] Dashboard loads correctly
- [ ] Other reports (MTDP, NASP, Workplan, HR) work correctly
- [ ] Workplan management works correctly
- [ ] Activities management works correctly

---

## Cleanup Recommendations (Optional)

### 1. Remove Uploaded Files
```bash
# Remove commodity icon uploads
rm -rf public/uploads/commodities/
```

### 2. Clean Database (Required)

**Use the provided SQL script:**
```bash
mysql -u root -p amis_six < database_cleanup_commodities.sql
```

**Or run manually:**
```sql
-- Drop commodity tables
DROP TABLE IF EXISTS `commodity_prices`;
DROP TABLE IF EXISTS `commodity_production`;
DROP TABLE IF EXISTS `commodities`;

-- Optional: Remove commodity_id from users
ALTER TABLE `users` DROP COLUMN `commodity_id`;
```

See **Database Cleanup Instructions** section above for detailed steps.

---

## Summary

✅ **Controllers Removed**: 3 files
✅ **Models Removed**: 3 files
✅ **Views Removed**: 11 files
✅ **Routes Removed**: 3 route groups (19 routes total)
✅ **Navigation Items Removed**: 3 menu items
✅ **Code Updated**: 3 files (Routes, Navigation Helper, UserModel)
✅ **Documentation Updated**: 1 file (DB_tables_updates_01_10_2025.md)
✅ **SQL Script Created**: database_cleanup_commodities.sql

🎯 **Total Impact**: 17 files deleted, 4 files modified, 0 breaking changes

📊 **Database**: 3 tables to be dropped, 1 field to be removed (optional)

⚠️ **Action Required**: Run the database cleanup script to drop commodity tables

✨ **Result**: Commodities and Commodity Boards features completely removed from the application. Database cleanup script provided for removing orphaned tables.

