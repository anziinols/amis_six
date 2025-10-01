# Government Structure Pages - Testing Guide

**Date:** September 30, 2025  
**Base URL:** http://localhost/amis_six/admin/gov-structure

---

## Quick Test Checklist

### Visual Inspection

#### 1. Provinces Page
**URL:** `http://localhost/amis_six/admin/gov-structure/provinces`

- [ ] Breadcrumb shows "Provinces" (active, not clickable)
- [ ] Page title "Provinces" is on the left side of card header
- [ ] Three buttons on the right: [Add Province] [Download CSV Template] [Import CSV]
- [ ] Buttons are properly aligned horizontally
- [ ] Table displays with proper borders and striping
- [ ] Action column has: [View Districts] [Edit] [Delete] buttons
- [ ] All action buttons use outline styles (not solid)
- [ ] Icons display correctly on all buttons

#### 2. Districts Page
**URL:** `http://localhost/amis_six/admin/gov-structure/provinces/{province_id}/districts`

- [ ] Breadcrumb shows: Provinces > Districts in [Province Name] (active)
- [ ] "Provinces" link in breadcrumb is clickable
- [ ] Page title shows "Districts in [Province Name]"
- [ ] Four buttons on right: [Back to Provinces] [Add District] [Download CSV] [Import CSV]
- [ ] Back button uses solid gray color (btn-secondary)
- [ ] Table action buttons: [View LLGs] [Edit] [Delete]
- [ ] Delete button is disabled if district has LLGs

#### 3. LLGs Page
**URL:** `http://localhost/amis_six/admin/gov-structure/districts/{district_id}/llgs`

- [ ] Breadcrumb shows: Provinces > Districts in [Province] > LLGs in [District] (active)
- [ ] All parent links in breadcrumb are clickable
- [ ] Page title shows "LLGs in [District Name] District"
- [ ] Four buttons on right: [Back to Districts] [Add LLG] [Download CSV] [Import CSV]
- [ ] Table action buttons: [View Wards] [Edit] [Delete]
- [ ] Delete button is disabled if LLG has wards

#### 4. Wards Page
**URL:** `http://localhost/amis_six/admin/gov-structure/llgs/{llg_id}/wards`

- [ ] Breadcrumb shows full hierarchy: Provinces > Districts > LLGs > Wards (active)
- [ ] All parent links in breadcrumb are clickable
- [ ] Page title shows "Wards in [LLG Name] LLG"
- [ ] Four buttons on right: [Back to LLGs] [Add Ward] [Download CSV] [Import CSV]
- [ ] Table action buttons: [Edit] [Delete] (no View button - wards are leaf nodes)

---

## Functional Testing

### Navigation Flow

1. **Start at Provinces:**
   - [ ] Click "View Districts" on any province
   - [ ] Verify you land on Districts page with correct breadcrumb

2. **From Districts:**
   - [ ] Click "Provinces" in breadcrumb → returns to Provinces page
   - [ ] Click "Back to Provinces" button → returns to Provinces page
   - [ ] Click "View LLGs" on any district → goes to LLGs page

3. **From LLGs:**
   - [ ] Click "Provinces" in breadcrumb → returns to Provinces page
   - [ ] Click "Districts in [Province]" in breadcrumb → returns to Districts page
   - [ ] Click "Back to Districts" button → returns to Districts page
   - [ ] Click "View Wards" on any LLG → goes to Wards page

4. **From Wards:**
   - [ ] Click "Provinces" in breadcrumb → returns to Provinces page
   - [ ] Click "Districts in [Province]" in breadcrumb → returns to Districts page
   - [ ] Click "LLGs in [District]" in breadcrumb → returns to LLGs page
   - [ ] Click "Back to LLGs" button → returns to LLGs page

### CRUD Operations

#### Add Functionality
- [ ] Click "Add Province" → modal opens
- [ ] Fill form and submit → province is added, success message shows
- [ ] Repeat for Districts, LLGs, and Wards

#### Edit Functionality
- [ ] Click "Edit" button on any row → modal opens with pre-filled data
- [ ] Modify data and submit → changes are saved, success message shows
- [ ] Test on all four levels

#### Delete Functionality
- [ ] Click "Delete" on a province without districts → confirmation modal opens
- [ ] Confirm deletion → province is deleted, success message shows
- [ ] Try to delete province with districts → button should be disabled
- [ ] Test delete restrictions on all levels

#### CSV Import/Export
- [ ] Click "Download CSV Template" → CSV file downloads
- [ ] Click "Import CSV" → modal opens
- [ ] Upload valid CSV → data is imported, success message shows
- [ ] Test on all four levels

---

## Responsive Testing

### Desktop (1920x1080)
- [ ] All buttons fit on one line in card header
- [ ] Table displays all columns without scrolling
- [ ] Breadcrumb displays on one line

### Tablet (768x1024)
- [ ] Buttons may wrap to multiple lines (acceptable)
- [ ] Table becomes horizontally scrollable
- [ ] Breadcrumb may wrap (acceptable)

### Mobile (375x667)
- [ ] Buttons stack vertically or wrap
- [ ] Table scrolls horizontally
- [ ] Breadcrumb wraps to multiple lines
- [ ] All functionality remains accessible

---

## Browser Compatibility

Test in the following browsers:

- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Edge (latest)
- [ ] Safari (if available)

---

## Accessibility Testing

### Keyboard Navigation
- [ ] Tab through all buttons and links
- [ ] Enter key activates buttons
- [ ] Escape key closes modals
- [ ] Focus indicators are visible

### Screen Reader
- [ ] Breadcrumb announces "breadcrumb navigation"
- [ ] Active breadcrumb item announces "current page"
- [ ] Button tooltips are read correctly
- [ ] Table headers are properly announced

### ARIA Attributes
- [ ] Breadcrumb has `aria-label="breadcrumb"`
- [ ] Active breadcrumb item has `aria-current="page"`
- [ ] Modals have proper ARIA labels
- [ ] Buttons have descriptive `title` attributes

---

## Performance Testing

### Page Load
- [ ] Provinces page loads in < 2 seconds
- [ ] Districts page loads in < 2 seconds
- [ ] LLGs page loads in < 2 seconds
- [ ] Wards page loads in < 2 seconds

### AJAX Operations
- [ ] Add operation completes in < 3 seconds
- [ ] Edit operation completes in < 3 seconds
- [ ] Delete operation completes in < 3 seconds
- [ ] No console errors during operations

---

## Error Handling

### Validation Errors
- [ ] Submit empty form → validation errors display
- [ ] Submit invalid data → appropriate error messages show
- [ ] Errors display using Toastr notifications

### Network Errors
- [ ] Simulate network failure → error message displays
- [ ] CSRF token errors are handled gracefully
- [ ] User is informed of the issue

### Permission Errors
- [ ] Test with restricted user (if applicable)
- [ ] Appropriate error messages for unauthorized actions

---

## Visual Consistency

### Colors
- [ ] Primary buttons are green (#6ba84f)
- [ ] Success buttons are green (#43a047)
- [ ] Info buttons are light blue
- [ ] Secondary buttons are gray
- [ ] Outline buttons match their solid counterparts

### Typography
- [ ] Page titles use Inter font, semi-bold (600)
- [ ] Consistent font sizes across all pages
- [ ] Proper text alignment

### Spacing
- [ ] Consistent padding in card headers (1.25rem 1.5rem)
- [ ] Consistent padding in card bodies (1.5rem)
- [ ] Proper spacing between buttons (me-2 for header, margin-right: 5px for table)
- [ ] Breadcrumb has bottom margin (mb-2)

### Icons
- [ ] All icons are FontAwesome
- [ ] Icons have proper spacing (me-1 for table buttons)
- [ ] Icons are consistent across similar actions

---

## Common Issues to Check

### Layout Issues
- [ ] No horizontal scrolling on desktop (except table)
- [ ] No overlapping elements
- [ ] Buttons don't overflow container
- [ ] Card shadows display correctly

### JavaScript Issues
- [ ] No console errors on page load
- [ ] No console errors during CRUD operations
- [ ] Modals open and close smoothly
- [ ] AJAX calls complete successfully

### Data Issues
- [ ] Proper escaping of special characters
- [ ] No XSS vulnerabilities
- [ ] CSRF protection working
- [ ] Data validation working correctly

---

## Test Data Recommendations

### Create Test Hierarchy
1. Add test province: "Test Province"
2. Add test district: "Test District" under Test Province
3. Add test LLG: "Test LLG" under Test District
4. Add test ward: "Test Ward" under Test LLG

### Test Edge Cases
- [ ] Very long names (50+ characters)
- [ ] Special characters in names (apostrophes, hyphens)
- [ ] Empty optional fields (map_center, map_zoom)
- [ ] Duplicate codes (should be prevented)

---

## Sign-off Checklist

Before marking as complete:

- [ ] All visual elements match the style guide
- [ ] All functional tests pass
- [ ] No console errors
- [ ] Responsive design works on all screen sizes
- [ ] Accessibility requirements met
- [ ] Cross-browser compatibility verified
- [ ] Documentation is up to date

---

## Reporting Issues

If you find any issues during testing:

1. **Document the issue:**
   - Page/URL where issue occurs
   - Steps to reproduce
   - Expected behavior
   - Actual behavior
   - Screenshots (if applicable)

2. **Check console for errors:**
   - Open browser DevTools (F12)
   - Check Console tab for JavaScript errors
   - Check Network tab for failed requests

3. **Report to developer:**
   - Include all documentation from step 1
   - Include console errors from step 2
   - Specify browser and version

---

**Testing Status:** Ready for Testing  
**Tester:** _________________  
**Date Tested:** _________________  
**Result:** [ ] Pass [ ] Fail [ ] Pass with Issues

