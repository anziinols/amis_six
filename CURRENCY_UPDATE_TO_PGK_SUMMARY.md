# Currency Update to PGK (Papua New Guinea Kina) - Summary

## Date: January 30, 2025

---

## Overview

This document summarizes the comprehensive update of all currency references throughout the AMIS application to use Papua New Guinea Kina (PGK) as the standard currency.

---

## Currency Configuration

### Constants File
**File**: `app/Config/Constants.php`

The application already had PGK configured as the default currency:

```php
/*
 |--------------------------------------------------------------------------
 | Currency Constants
 |--------------------------------------------------------------------------
 |
 | Application currency settings for Papua New Guinea Kina (PGK)
 */
defined('CURRENCY_SYMBOL') || define('CURRENCY_SYMBOL', 'PGK');
defined('CURRENCY_CODE') || define('CURRENCY_CODE', 'PGK');
defined('CURRENCY_NAME') || define('CURRENCY_NAME', 'Papua New Guinea Kina');
```

**Currency Symbol**: PGK  
**Currency Code**: PGK  
**Currency Name**: Papua New Guinea Kina

---

## Files Updated

### 1. View Files - Currency Display Updates

All hardcoded currency references (USD, KES, K) were replaced with the `CURRENCY_SYMBOL` constant to ensure consistency.

#### Updated Files:

1. **`app/Views/activities/implementations/outputs_implementation.php`**
   - **Line 160**: Changed from `(KES)` to `(<?= CURRENCY_SYMBOL ?>)`
   - **Context**: Total Value input field label

2. **`app/Views/output_activities/output_activities_new.php`**
   - **Line 93**: Changed from `(Kina)` to `(<?= CURRENCY_SYMBOL ?>)`
   - **Context**: Total Value input field label

3. **`app/Views/evaluation/evaluation_outputs.php`**
   - **Line 198**: Changed from `K` to `<?= CURRENCY_SYMBOL ?>`
   - **Context**: Total cost display in linked activities table

4. **`app/Views/activities/activities_show.php`**
   - **Line 123**: Changed from `'USD '` to `CURRENCY_SYMBOL . ' '`
   - **Line 146**: Changed from `'USD '` to `CURRENCY_SYMBOL . ' '`
   - **Context**: Total cost display in activity details

5. **`app/Views/activities/implementation/outputs_details.php`**
   - **Line 8**: Changed from `KES` to `<?= CURRENCY_SYMBOL ?>`
   - **Context**: Total value display in implementation details

6. **`app/Views/activities/views/agreements_view.php`**
   - **Line 43**: Changed from `'USD '` to `CURRENCY_SYMBOL . ' '`
   - **Context**: Total cost display in activity card

7. **`app/Views/activities/views/documents_view.php`**
   - **Line 57**: Changed from `'USD '` to `CURRENCY_SYMBOL . ' '`
   - **Context**: Total cost display in activity card

8. **`app/Views/activities/views/infrastructures_view.php`**
   - **Line 43**: Changed from `'USD '` to `CURRENCY_SYMBOL . ' '`
   - **Context**: Total cost display in activity card

9. **`app/Views/activities/views/inputs_view.php`**
   - **Line 43**: Changed from `'USD '` to `CURRENCY_SYMBOL . ' '`
   - **Context**: Total cost display in activity card

10. **`app/Views/activities/views/meetings_view.php`**
    - **Line 43**: Changed from `'USD '` to `CURRENCY_SYMBOL . ' '`
    - **Context**: Total cost display in activity card

11. **`app/Views/activities/views/outputs_view.php`**
    - **Line 43**: Changed from `'USD '` to `CURRENCY_SYMBOL . ' '`
    - **Context**: Total cost display in activity card

12. **`app/Views/activities/views/trainings_view.php`**
    - **Line 57**: Changed from `'USD '` to `CURRENCY_SYMBOL . ' '`
    - **Context**: Total cost display in activity card

---

## Files Already Using PGK

The following files were already correctly using the PGK currency constants:

### View Files:
- `app/Views/evaluation/evaluation_training.php` - Uses `CURRENCY_SYMBOL`
- `app/Views/workplans/workplan_activities_show.php` - Uses `CURRENCY_SYMBOL`
- `app/Views/report_workplan/report_workplan_index.php` - Uses `CURRENCY_SYMBOL`
- `app/Views/evaluation/evaluation_inputs.php` - Uses `CURRENCY_SYMBOL`
- `app/Views/reports_mtdp/reports_mtdp_index.php` - Uses `CURRENCY_CODE` in JavaScript

### Database Files:
- `app/Database/Migrations/2025-01-20-000010_CreateRemainingTables.php` - Default: 'PGK'
- Database schema files in `dev_guide/` - Default: 'PGK'

### Seeder Files:
- `app/Database/Seeds/CommodityPricesSeeder.php` - Uses `CURRENCY_CODE` constant

---

## JavaScript Chart Formatting

The application uses `Intl.NumberFormat` for currency formatting in charts, which correctly uses the `CURRENCY_CODE` constant passed from PHP:

```javascript
new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: '<?= CURRENCY_CODE ?>',  // Outputs: 'PGK'
    minimumFractionDigits: 2
}).format(value);
```

**Files using this pattern:**
- `app/Views/reports_mtdp/reports_mtdp_index.php`
- `app/Views/report_workplan/report_workplan_index.php`

---

## Database Schema

All database tables with currency fields have 'PGK' as the default value:

### `commodity_prices` table:
```sql
`currency` varchar(10) DEFAULT 'PGK'
```

---

## Summary of Changes

### Total Files Modified: 12

**Before:**
- Mixed currency references: USD, KES, K, Kina
- Inconsistent currency display across the application

**After:**
- All currency references now use `CURRENCY_SYMBOL` constant (PGK)
- Consistent currency display throughout the application
- Easy to update currency in the future by changing only the Constants.php file

---

## Benefits

1. **Consistency**: All currency displays now show "PGK" uniformly
2. **Maintainability**: Single point of configuration in `app/Config/Constants.php`
3. **Flexibility**: Easy to change currency in the future if needed
4. **Correctness**: Proper currency for Papua New Guinea context

---

## Testing Recommendations

Test the following areas to ensure currency displays correctly:

1. **Activities Module**:
   - Activity creation and editing forms
   - Activity detail views
   - Activity implementation forms
   - Activity cards in list views

2. **Workplan Module**:
   - Workplan activity forms
   - Budget displays

3. **Evaluation Module**:
   - Training evaluations
   - Output evaluations
   - Input evaluations

4. **Reports**:
   - MTDP reports with investment charts
   - Workplan reports with cost summaries
   - HR reports with budget allocations

5. **Output Activities**:
   - Output activity creation forms
   - Output activity detail views

---

## Notes

- The currency symbol "PGK" is used instead of "K" to avoid confusion
- All number formatting uses `number_format()` with 2 decimal places for consistency
- JavaScript chart tooltips properly format currency using Intl.NumberFormat
- Database default values ensure new records use PGK automatically

---

## Conclusion

All currency references throughout the AMIS application have been successfully updated to use Papua New Guinea Kina (PGK). The application now has a consistent, maintainable currency system that can be easily updated from a single configuration file.

