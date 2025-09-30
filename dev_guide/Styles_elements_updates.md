# AMIS Six - UI Styles & Elements Documentation

## Action Buttons Style Guide

### Workplan Period Action Buttons

The workplan period index page (`/workplan-period`) features a well-designed action button layout that provides clear visual hierarchy and user-friendly interactions.

#### Button Group Structure

```html
<div class="btn-group" role="group">
    <!-- Action buttons here -->
</div>
```

#### Individual Button Styles

**1. View Button (Primary Action)**
```html
<a href="<?= base_url('workplan-period/' . $period['id']) ?>" 
   class="btn btn-outline-primary" 
   title="View Details" 
   style="margin-right: 5px;">
    <i class="fas fa-eye me-1"></i> View
</a>
```
- **Color**: Blue outline (`btn-outline-primary`)
- **Icon**: Eye icon (`fas fa-eye`)
- **Purpose**: View detailed information

**2. KRAs Button (Success Action)**
```html
<a href="<?= base_url('workplan-period/' . $period['id'] . '/kra') ?>" 
   class="btn btn-outline-success" 
   title="View KRAs" 
   style="margin-right: 5px;">
    <i class="fas fa-list me-1"></i> KRAs
</a>
```
- **Color**: Green outline (`btn-outline-success`)
- **Icon**: List icon (`fas fa-list`)
- **Purpose**: Access Key Result Areas

**3. Outputs Button (Info Action)**
```html
<a href="<?= base_url('workplan-period/' . $period['id'] . '/outputs') ?>" 
   class="btn btn-outline-info" 
   title="View Outputs" 
   style="margin-right: 5px;">
    <i class="fas fa-tasks me-1"></i> Outputs
</a>
```
- **Color**: Light blue outline (`btn-outline-info`)
- **Icon**: Tasks icon (`fas fa-tasks`)
- **Purpose**: View outputs and deliverables

**4. Edit Button (Warning Action)**
```html
<a href="<?= base_url('workplan-period/' . $period['id'] . '/edit') ?>" 
   class="btn btn-outline-warning" 
   title="Edit" 
   style="margin-right: 5px;">
    <i class="fas fa-edit me-1"></i> Edit
</a>
```
- **Color**: Yellow/orange outline (`btn-outline-warning`)
- **Icon**: Edit icon (`fas fa-edit`)
- **Purpose**: Modify existing record

**5. Delete Button (Danger Action)**
```html
<a href="<?= base_url('workplan-period/' . $period['id'] . '/delete') ?>" 
   class="btn btn-outline-danger" 
   title="Delete" 
   onclick="return confirm('Are you sure you want to delete this workplan period?')">
    <i class="fas fa-trash me-1"></i> Delete
</a>
```
- **Color**: Red outline (`btn-outline-danger`)
- **Icon**: Trash icon (`fas fa-trash`)
- **Purpose**: Delete record (with confirmation)

#### Design Principles

1. **Consistent Spacing**: Each button has `margin-right: 5px` for proper spacing
2. **Icon + Text**: All buttons combine FontAwesome icons with descriptive text
3. **Color Coding**: 
   - Blue (Primary): View/Read actions
   - Green (Success): Positive/completion actions
   - Light Blue (Info): Information/data actions
   - Yellow (Warning): Modification actions
   - Red (Danger): Destructive actions
4. **Tooltips**: Each button includes a `title` attribute for accessibility
5. **Confirmation**: Destructive actions include JavaScript confirmation dialogs

#### CSS Classes Used

- `btn`: Base Bootstrap button class
- `btn-outline-*`: Outline button variants for lighter appearance
- `btn-group`: Groups related buttons together
- `fas fa-*`: FontAwesome icons for visual clarity
- `me-1`: Bootstrap margin-end utility for icon spacing

#### Implementation Notes

- Uses Bootstrap 5 button classes and utilities
- FontAwesome icons for consistent iconography
- Inline styles for fine-tuned spacing
- Semantic color coding for intuitive user experience
- Accessibility considerations with title attributes

#### Usage Recommendations

This button style should be replicated across similar data tables in the AMIS system for consistency. The color-coding system provides users with immediate visual feedback about the nature of each action.

**Best Practices:**
- Maintain the same color scheme across all modules
- Always include both icon and text for clarity
- Use confirmation dialogs for destructive actions
- Ensure proper spacing between buttons
- Include tooltips for accessibility

---

*Last Updated: September 29, 2025*
*Source: `/app/Views/workplan_period/workplan_period_index.php` lines 66-82*
