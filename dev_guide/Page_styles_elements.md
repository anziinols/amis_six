# AMIS Admin Panel - Page Layout & Style Guide

**Version:** 1.0  
**Last Updated:** September 30, 2025  
**Reference Page:** Government Structure - LLGs List (`/admin/gov-structure/districts/48/llgs`)  
**Source File:** `app/Views/admin/gov_structure/gov_structure_llgs_list.php`

---

## Table of Contents

1. [Overall Page Layout](#1-overall-page-layout)
2. [Breadcrumb Navigation](#2-breadcrumb-navigation)
3. [Page Header & Title](#3-page-header--title)
4. [Action Buttons](#4-action-buttons)
5. [Table/List Layout](#5-tablelist-layout)
6. [Action Column Buttons](#6-action-column-buttons)
7. [CSS Variables & Color Scheme](#7-css-variables--color-scheme)
8. [Responsive Design](#8-responsive-design)
9. [Modal Dialogs](#9-modal-dialogs)
10. [Form Elements](#10-form-elements)
11. [Flash Messages & Notifications](#11-flash-messages--notifications)
12. [Data Attributes for JavaScript](#12-data-attributes-for-javascript)
13. [Conditional Button States](#13-conditional-button-states)

---

## 1. Overall Page Layout

### Container Structure

All admin pages use a **fluid container** layout that extends the `system_template` and wraps content in the `content` section.

```php
<?= $this->extend('templates/system_template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page content here -->
</div>
<?= $this->endSection() ?>
```

### Layout Hierarchy

```
container-fluid
├── row mb-2 (Breadcrumb section)
│   └── col-12
│       └── nav (breadcrumb)
├── row (Main content section)
│   └── col-12
│       └── card
│           ├── card-header
│           └── card-body
│               └── table-responsive
│                   └── table
```

### Key Classes

- **`container-fluid`**: Full-width container with responsive padding
- **`row`**: Bootstrap grid row
- **`col-12`**: Full-width column
- **`mb-2`**: Bottom margin (0.5rem) for spacing between sections

---

## 2. Breadcrumb Navigation

### Structure & Positioning

Breadcrumbs are placed at the **top of the page**, before the main content card, wrapped in a row with bottom margin.

```html
<div class="row mb-2">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?= base_url('admin/gov-structure/provinces') ?>">Provinces</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?= base_url('admin/gov-structure/provinces/'.$province['id'].'/districts') ?>">
                        Districts in <?= esc($province['name']) ?>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    LLGs in <?= esc($district['name']) ?>
                </li>
            </ol>
        </nav>
    </div>
</div>
```

### Styling Details

- **Classes Used**: `breadcrumb`, `breadcrumb-item`, `breadcrumb-item active`
- **Accessibility**: Uses `aria-label="breadcrumb"` and `aria-current="page"`
- **Links**: All parent levels are clickable links; current page is plain text with `active` class
- **Separator**: Bootstrap default separator (automatically added via CSS)
- **Font**: Inherits from system (Inter font family)
- **Color**: Links use default Bootstrap link color; active item is muted

### Visual Appearance

- Background: Light gray (Bootstrap default)
- Padding: Default Bootstrap breadcrumb padding
- Border-radius: Slightly rounded corners
- Margin: Bottom margin of 0.5rem (`mb-2`)

---

## 3. Page Header & Title

### Card Header Structure

The page title and action buttons are contained within a **card header** using flexbox for alignment.

```html
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">LLGs in <?= esc($district['name']) ?> District</h3>
        <div>
            <!-- Action buttons here -->
        </div>
    </div>
    <div class="card-body">
        <!-- Content here -->
    </div>
</div>
```

### Styling Details

- **Card Header Classes**: `card-header`, `d-flex`, `justify-content-between`, `align-items-center`
- **Title Classes**: `card-title`, `mb-0` (removes bottom margin)
- **Title Element**: `<h3>` for semantic hierarchy
- **Layout**: Flexbox with space-between (title on left, buttons on right)
- **Vertical Alignment**: Center-aligned items

### CSS Properties (from system_template.php)

```css
.card-header {
    background-color: white;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    padding: 1.25rem 1.5rem;
    font-weight: 600;
    color: var(--navy-blue);
}

.card {
    border: none;
    border-radius: 0.75rem;
    box-shadow: var(--card-shadow);
    transition: all 0.3s ease;
    background: white;
    margin-bottom: 1.5rem;
}
```

### Typography

- **Font Family**: Inter (from Google Fonts)
- **Font Weight**: 600 (semi-bold)
- **Color**: Navy blue (`--navy-blue: #1a237e`)
- **Size**: Default h3 size (approximately 1.75rem)

---

## 4. Action Buttons

### Primary Action Buttons (Header)

Action buttons in the card header are grouped in a `<div>` and aligned to the right.

```html
<div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title mb-0">LLGs in <?= esc($district['name']) ?> District</h3>
    <div>
        <a href="<?= base_url('admin/gov-structure/provinces/'.$province['id'].'/districts') ?>" 
           class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left"></i> Back to Districts
        </a>
        <button type="button" class="btn btn-primary" 
                data-bs-toggle="modal" data-bs-target="#addLLGModal">
            <i class="fas fa-plus"></i> Add LLG
        </button>
        <a href="<?= base_url('admin/gov-structure/districts/'.$district['id'].'/llgs/csv-template') ?>" 
           class="btn btn-success">
            <i class="fas fa-download"></i> Download CSV Template
        </a>
        <button type="button" class="btn btn-info" 
                data-bs-toggle="modal" data-bs-target="#importLlgModal">
            <i class="fas fa-upload"></i> Import CSV
        </button>
    </div>
</div>
```

### Button Types & Color Coding

| Button Type | Class | Color | Icon | Purpose |
|-------------|-------|-------|------|---------|
| **Back** | `btn-secondary` | Gray | `fa-arrow-left` | Navigation back to parent |
| **Add/Create** | `btn-primary` | Green | `fa-plus` | Create new record |
| **Download** | `btn-success` | Green | `fa-download` | Download files/templates |
| **Import** | `btn-info` | Light blue | `fa-upload` | Import data |

### Button Styling

- **Base Class**: `btn` (Bootstrap button)
- **Spacing**: `me-2` (margin-end: 0.5rem) between buttons
- **Icon + Text**: All buttons combine FontAwesome icon with descriptive text
- **Icon Spacing**: Icons are followed by a space before text
- **Solid Colors**: Header buttons use solid colors (not outline)

### CSS Properties (from system_template.php)

```css
.btn-primary {
    background-color: var(--primary-green);
    border-color: var(--primary-green);
}

.btn-primary:hover {
    background-color: var(--dark-green);
    border-color: var(--dark-green);
}
```

### Back Button Positioning

**Important**: Back buttons should be placed on the **right side** of the header with **solid color styling** (e.g., `btn-secondary`), not on the left as outline buttons.

---

## 5. Table/List Layout

### Table Container Structure

Tables are wrapped in responsive containers within the card body.

```html
<div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Map Center</th>
                    <th>Map Zoom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Table rows here -->
            </tbody>
        </table>
    </div>
</div>
```

### Table Classes

- **`table`**: Base Bootstrap table class
- **`table-bordered`**: Adds borders to all cells
- **`table-striped`**: Alternating row colors for readability
- **`table-responsive`**: Enables horizontal scrolling on small screens

### Card Body Styling

```css
.card-body {
    padding: 1.5rem;
}
```

### Empty State

```html
<?php if (empty($llgs)): ?>
<tr>
    <td colspan="6" class="text-center">No LLGs found</td>
</tr>
<?php endif; ?>
```

- **Colspan**: Matches total number of columns
- **Alignment**: `text-center` for centered message
- **Message**: Clear, user-friendly text

---

## 6. Action Column Buttons

### Button Structure in Table Rows

Action buttons in table rows use **outline styles** with icons and text, following the style guide in `dev_guide/Styles_elements_updates.md`.

```html
<td>
    <a href="<?= base_url('admin/gov-structure/llgs/'.$llg['id'].'/wards') ?>" 
       class="btn btn-outline-primary" 
       title="View Wards" 
       style="margin-right: 5px;">
        <i class="fas fa-eye me-1"></i> View Wards
    </a>
    <button type="button" class="btn btn-outline-warning edit-llg-btn"
            data-id="<?= $llg['id'] ?>"
            data-bs-toggle="modal"
            data-bs-target="#editLLGModal"
            title="Edit"
            style="margin-right: 5px;">
        <i class="fas fa-edit me-1"></i> Edit
    </button>
    <button type="button" class="btn btn-outline-danger delete-llg-btn"
            data-id="<?= $llg['id'] ?>"
            data-name="<?= esc($llg['name']) ?>"
            data-bs-toggle="modal"
            data-bs-target="#deleteLLGModal"
            <?= $hasWards ? 'disabled' : '' ?>
            title="<?= $hasWards ? 'Cannot delete LLG with wards' : 'Delete LLG' ?>">
        <i class="fas fa-trash me-1"></i> Delete
    </button>
</td>
```

### Action Button Color Coding

| Action | Class | Color | Icon | Purpose |
|--------|-------|-------|------|---------|
| **View** | `btn-outline-primary` | Blue outline | `fa-eye` | View details/navigate |
| **Edit** | `btn-outline-warning` | Yellow outline | `fa-edit` | Modify record |
| **Delete** | `btn-outline-danger` | Red outline | `fa-trash` | Remove record |
| **Success Actions** | `btn-outline-success` | Green outline | `fa-list`, `fa-check` | Positive actions |
| **Info Actions** | `btn-outline-info` | Light blue outline | `fa-tasks`, `fa-info` | Information actions |

### Design Principles

1. **Outline Style**: Use `btn-outline-*` classes (not solid) for table action buttons
2. **Consistent Spacing**: `margin-right: 5px` between buttons
3. **Icon + Text**: Combine FontAwesome icon with descriptive text
4. **Icon Spacing**: `me-1` class on icon for spacing before text
5. **Tooltips**: Include `title` attribute for accessibility
6. **Conditional States**: Use `disabled` attribute when action is not available
7. **Data Attributes**: Store IDs and other data in `data-*` attributes for JavaScript

---

## 7. CSS Variables & Color Scheme

### Color Variables (from system_template.php)

```css
:root {
    --primary-green: #6ba84f;
    --light-green: #8bc34a;
    --dark-green: #558b2f;
    --navy-blue: #1a237e;
    --light-navy: #283593;
    --dark-navy: #0d47a1;
    --accent-color: var(--primary-green);
    --success-color: #43a047;
    --warning-color: #fdd835;
    --danger-color: #e53935;
    --sidebar-width: 250px;
    --sidebar-collapsed-width: 70px;
    --header-height: 60px;
    --transition-speed: 0.3s;
    --light-bg: #f5f7fa;
    --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
```

### Typography

- **Font Family**: 'Inter', sans-serif (Google Fonts)
- **Font Weights**: 300, 400, 500, 600, 700
- **Base Color**: #2c3e50

---

## 8. Responsive Design

### Container Behavior

- **Desktop**: Full-width with sidebar (margin-left: 250px)
- **Tablet**: Collapsible sidebar
- **Mobile**: Sidebar hidden by default, toggleable

### Table Responsiveness

```html
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <!-- Table content -->
    </table>
</div>
```

The `table-responsive` class enables horizontal scrolling on screens smaller than 768px.

---

## 9. Modal Dialogs

### Modal Structure

Modals follow Bootstrap 5 structure and are placed at the end of the page content, before the closing `<?= $this->endSection() ?>`.

```html
<!-- Add Modal -->
<div class="modal fade" id="addLLGModal" tabindex="-1" role="dialog"
     aria-labelledby="addLLGModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="addLLGForm" action="<?= base_url('admin/gov-structure/districts/'.$district['id'].'/llgs') ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLLGModalLabel">Add LLG to <?= esc($district['name']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <!-- Form fields here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save LLG</button>
                </div>
            </form>
        </div>
    </div>
</div>
```

### Modal Components

1. **Modal Header**
   - Title: `<h5 class="modal-title">`
   - Close button: `<button class="btn-close">`

2. **Modal Body**
   - Always include `<?= csrf_field() ?>` for security
   - Form fields with `form-group mb-3` wrapper
   - Labels and inputs follow standard form structure

3. **Modal Footer**
   - Cancel/Close button: `btn btn-secondary`
   - Submit button: `btn btn-primary` (or appropriate color)

### Modal Trigger Buttons

```html
<button type="button" class="btn btn-primary"
        data-bs-toggle="modal" data-bs-target="#addLLGModal">
    <i class="fas fa-plus"></i> Add LLG
</button>
```

---

## 10. Form Elements

### Form Group Structure

```html
<div class="form-group mb-3">
    <label for="name">LLG Name</label>
    <input type="text" class="form-control" id="name" name="name" required>
</div>
```

### Form Classes

- **Form Group**: `form-group mb-3` (bottom margin for spacing)
- **Label**: Plain `<label>` with `for` attribute matching input ID
- **Input**: `form-control` class for all text inputs, selects, and textareas
- **Select**: `form-control` or `form-control select2-*` for enhanced dropdowns
- **Help Text**: `<small class="form-text text-muted">` for hints

### Select2 Integration

```html
<select class="form-control select2-llg" id="json_id" name="json_id">
    <option value="">-- Select LLG --</option>
</select>
```

Initialize in JavaScript:
```javascript
$('.select2-llg').select2({
    width: '100%',
    dropdownParent: $('#addLLGModal')
});
```

---

## 11. Flash Messages & Notifications

### Toastr Notifications

Flash messages are displayed using Toastr library, initialized at the end of the view file.

```html
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (session()->getFlashdata('success')): ?>
            toastr.success('<?= session()->getFlashdata('success') ?>');
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            toastr.error('<?= session()->getFlashdata('error') ?>');
        <?php endif; ?>
    });
</script>
```

### Toastr Types

- **Success**: `toastr.success('message')`
- **Error**: `toastr.error('message')`
- **Warning**: `toastr.warning('message')`
- **Info**: `toastr.info('message')`

### Setting Flash Messages in Controller

```php
// Success message
return redirect()->to('/admin/gov-structure/llgs')->with('success', 'LLG added successfully');

// Error message
return redirect()->back()->with('error', 'Failed to add LLG')->withInput();
```

---

## 12. Data Attributes for JavaScript

### Common Data Attributes

Action buttons in tables use data attributes to pass information to JavaScript handlers.

```html
<button type="button" class="btn btn-outline-warning edit-llg-btn"
        data-id="<?= $llg['id'] ?>"
        data-bs-toggle="modal"
        data-bs-target="#editLLGModal"
        title="Edit">
    <i class="fas fa-edit me-1"></i> Edit
</button>
```

### Standard Data Attributes

- **`data-id`**: Record ID for CRUD operations
- **`data-name`**: Record name for confirmation messages
- **`data-bs-toggle`**: Bootstrap modal/dropdown trigger
- **`data-bs-target`**: Target modal/dropdown ID
- **`title`**: Tooltip text for accessibility

### JavaScript Event Handlers

```javascript
$('.edit-llg-btn').on('click', function() {
    var llgId = $(this).data('id');
    // Fetch and populate modal with data
});

$('.delete-llg-btn').on('click', function() {
    var llgId = $(this).data('id');
    var llgName = $(this).data('name');
    $('#delete_id').val(llgId);
    $('#deleteLLGName').text(llgName);
});
```

---

## 13. Conditional Button States

### Disabled State

Buttons can be conditionally disabled based on business logic.

```php
<?php
$wardCount = model('GovStructureModel')->where('parent_id', $llg['id'])->where('level', 'ward')->countAllResults();
$hasWards = $wardCount > 0;
?>
<button type="button" class="btn btn-outline-danger delete-llg-btn"
        data-id="<?= $llg['id'] ?>"
        data-name="<?= esc($llg['name']) ?>"
        data-bs-toggle="modal"
        data-bs-target="#deleteLLGModal"
        <?= $hasWards ? 'disabled' : '' ?>
        title="<?= $hasWards ? 'Cannot delete LLG with wards' : 'Delete LLG' ?>">
    <i class="fas fa-trash me-1"></i> Delete
</button>
```

### Visual Feedback

- **Disabled Attribute**: Prevents click and grays out button
- **Dynamic Title**: Explains why button is disabled
- **Conditional Logic**: Check dependencies before allowing destructive actions

---

## Implementation Checklist

When creating a new admin page, ensure:

### Structure
- [ ] Page extends `templates/system_template`
- [ ] Content wrapped in `container-fluid`
- [ ] Breadcrumb navigation included (if hierarchical)
- [ ] Card structure with header and body
- [ ] Page title in `<h3 class="card-title mb-0">`

### Buttons
- [ ] Action buttons in header (right-aligned)
- [ ] Back button uses `btn-secondary` (solid, not outline)
- [ ] Table wrapped in `table-responsive` div
- [ ] Table uses `table table-bordered table-striped` classes
- [ ] Action column buttons use outline styles (`btn-outline-*`)
- [ ] Icons use FontAwesome with `me-1` spacing
- [ ] Proper color coding for button actions
- [ ] Tooltips (`title` attribute) on all buttons
- [ ] Consistent spacing (`me-2` for header buttons, `margin-right: 5px` for table buttons)

### Forms & Modals
- [ ] CSRF field included in all forms
- [ ] Form groups use `form-group mb-3`
- [ ] Inputs use `form-control` class
- [ ] Modal structure follows Bootstrap 5 pattern
- [ ] Modal footer has cancel and submit buttons

### Data & Feedback
- [ ] Empty state message for no data
- [ ] Flash messages displayed with Toastr
- [ ] Data attributes on action buttons
- [ ] Conditional disabled states where appropriate
- [ ] Proper escaping with `esc()` function

### Accessibility
- [ ] ARIA labels on navigation elements
- [ ] Title attributes on buttons
- [ ] Semantic HTML structure
- [ ] Keyboard navigation support

---

## Quick Reference: Button Color Guide

| Action Type | Header Button | Table Button | Icon |
|-------------|---------------|--------------|------|
| **Back/Cancel** | `btn-secondary` | `btn-outline-secondary` | `fa-arrow-left` |
| **Add/Create** | `btn-primary` | `btn-outline-primary` | `fa-plus` |
| **View/Details** | `btn-primary` | `btn-outline-primary` | `fa-eye` |
| **Edit/Modify** | `btn-warning` | `btn-outline-warning` | `fa-edit` |
| **Delete/Remove** | `btn-danger` | `btn-outline-danger` | `fa-trash` |
| **Download** | `btn-success` | `btn-outline-success` | `fa-download` |
| **Upload/Import** | `btn-info` | `btn-outline-info` | `fa-upload` |
| **List/View Items** | `btn-success` | `btn-outline-success` | `fa-list` |
| **Tasks/Outputs** | `btn-info` | `btn-outline-info` | `fa-tasks` |

---

**Reference Files:**
- View: `app/Views/admin/gov_structure/gov_structure_llgs_list.php`
- Template: `app/Views/templates/system_template.php`
- Related Guide: `dev_guide/Styles_elements_updates.md`

**External Libraries:**
- Bootstrap 5.3.0: https://getbootstrap.com/docs/5.3/
- Font Awesome 6.4.0: https://fontawesome.com/icons
- Toastr: https://github.com/CodeSeven/toastr
- Select2: https://select2.org/
- DataTables: https://datatables.net/

