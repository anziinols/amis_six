# Duty Instructions Modal Implementation Guide

## Quick Reference

### User Flow - Before vs After

#### BEFORE (Inline/Separate Page Approach):
```
1. Click "Add Item" → Navigate to /duty-instructions/{id}/items/new
2. Fill form on separate page
3. Submit → Redirect back to detail page
4. Click "Edit" → Table row becomes editable form
5. Save → Page reload
```

#### AFTER (Modal Approach):
```
1. Click "Add Item" → Modal opens on same page
2. Fill form in modal
3. Submit → AJAX request → Toastr notification → Auto-refresh
4. Click "Edit" → Modal opens with pre-filled data
5. Save → AJAX request → Toastr notification → Auto-refresh
```

## Modal Structure

### Add Item Modal (`#addItemModal`)
```html
<div class="modal fade" id="addItemModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Add New Instruction Item</h5>
            </div>
            <div class="modal-body">
                <form id="addItemForm">
                    <!-- Item Number (auto-generated) -->
                    <!-- Instruction (required) -->
                    <!-- Status (dropdown) -->
                    <!-- Remarks (optional) -->
                </form>
            </div>
            <div class="modal-footer">
                <button data-bs-dismiss="modal">Cancel</button>
                <button id="saveNewItemBtn">Save Item</button>
            </div>
        </div>
    </div>
</div>
```

### Edit Item Modal (`#editItemModal`)
```html
<div class="modal fade" id="editItemModal">
    <!-- Similar structure to Add Item Modal -->
    <!-- Pre-populated with existing data -->
    <input type="hidden" id="editItemId">
</div>
```

## JavaScript Event Handlers

### 1. Modal Show Event (Auto-generate Item Number)
```javascript
$('#addItemModal').on('shown.bs.modal', function() {
    const existingItems = document.querySelectorAll('[data-item-id]');
    const nextNumber = existingItems.length + 1;
    $('#addItemNumber').val(nextNumber);
    $('#addInstruction').focus();
});
```

### 2. Save New Item
```javascript
$('#saveNewItemBtn').on('click', function() {
    // Validate
    // Show loading state
    // AJAX POST to /duty-instructions/{id}/items/create
    // Show toastr notification
    // Close modal and reload page
});
```

### 3. Edit Item Button Click
```javascript
$(document).on('click', '.edit-item-btn', function() {
    // Get data from button attributes
    const itemId = $(this).data('item-id');
    const itemNumber = $(this).data('item-number');
    // ... etc
    
    // Populate form
    $('#editItemId').val(itemId);
    $('#editItemNumber').val(itemNumber);
    // ... etc
    
    // Show modal
    editItemModal.show();
});
```

### 4. Save Edited Item
```javascript
$('#saveEditItemBtn').on('click', function() {
    // Validate
    // Show loading state
    // AJAX POST to /duty-instructions/items/{id}/update
    // Show toastr notification
    // Close modal and reload page
});
```

### 5. Delete Item
```javascript
$(document).on('click', '.delete-item-btn', function() {
    // Confirm deletion
    // AJAX POST to /duty-instructions/items/{id}/delete
    // Show toastr notification
    // Reload page
});
```

## AJAX Request Pattern

### Standard AJAX Call Structure
```javascript
$.ajax({
    url: `<?= base_url('duty-instructions/') ?>${dutyInstructionId}/items/create`,
    method: 'POST',
    contentType: 'application/json',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken
    },
    data: JSON.stringify({
        instruction_number: itemNumber,
        instruction: instruction,
        status: status,
        remarks: remarks,
        [csrfName]: csrfToken
    }),
    success: function(response) {
        if (response.success) {
            toastr.success('Success message');
            modal.hide();
            setTimeout(() => window.location.reload(), 1500);
        } else {
            toastr.error(response.message);
        }
    },
    error: function(xhr, status, error) {
        toastr.error('Error message');
    },
    complete: function() {
        // Reset button state
    }
});
```

## Button States

### Loading State Pattern
```javascript
// Before AJAX
const $btn = $(this);
const originalHtml = $btn.html();
$btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...').prop('disabled', true);

// In complete callback
$btn.html(originalHtml).prop('disabled', false);
```

## Data Attributes on Edit Buttons

```html
<button class="btn btn-outline-warning btn-sm edit-item-btn"
        data-item-id="<?= $item['id'] ?>"
        data-item-number="<?= esc($item['instruction_number']) ?>"
        data-instruction="<?= esc($item['instruction']) ?>"
        data-status="<?= esc($item['status']) ?>"
        data-remarks="<?= esc($item['remarks'] ?? '') ?>">
    <i class="fas fa-edit"></i> Edit
</button>
```

## Toastr Notifications

### Success
```javascript
toastr.success('Instruction item added successfully!');
```

### Error
```javascript
toastr.error('Failed to add instruction item');
```

### Configuration (Already in template)
```javascript
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right",
    timeOut: 15000
};
```

## Controller Endpoints

### Create Item
- **URL**: `POST /duty-instructions/{id}/items/create`
- **Content-Type**: `application/json`
- **Response**: `{success: true/false, message: string, item: object}`

### Update Item
- **URL**: `POST /duty-instructions/items/{id}/update`
- **Content-Type**: `application/json`
- **Response**: `{success: true/false, message: string}`

### Delete Item
- **URL**: `POST /duty-instructions/items/{id}/delete`
- **Content-Type**: `application/json`
- **Response**: `{success: true/false, message: string}`

## Validation

### Client-Side
```javascript
if (!itemNumber || !instruction) {
    toastr.error('Please fill in the required fields');
    return;
}
```

### Server-Side (Controller)
```php
$rules = [
    'instruction_number' => 'required|max_length[50]',
    'instruction' => 'required'
];

if (!$validation->run($json)) {
    return $this->response->setJSON([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $validation->getErrors()
    ]);
}
```

## Mobile Responsiveness

The existing mobile styles in the `<head>` section are preserved:
- Table converts to card layout on mobile
- Modals are responsive by default (Bootstrap 5)
- Buttons stack properly on small screens

## Troubleshooting

### Modal doesn't open
- Check Bootstrap 5 is loaded
- Verify modal ID matches trigger button's `data-bs-target`
- Check browser console for JavaScript errors

### AJAX request fails
- Verify CSRF token is included
- Check `X-Requested-With: XMLHttpRequest` header
- Ensure controller method checks `$this->request->isAJAX()`

### Toastr doesn't show
- Verify Toastr library is loaded in template
- Check `toastr.options` is configured
- Look for JavaScript errors in console

### Page doesn't refresh after success
- Check `setTimeout(() => window.location.reload(), 1500)` is called
- Verify modal is hidden before reload
- Ensure success callback is executed

## Best Practices

1. **Always validate** both client-side and server-side
2. **Show loading states** to prevent double-submission
3. **Use toastr** for user feedback (not alerts)
4. **Handle errors gracefully** with try-catch and error callbacks
5. **Reset button states** in AJAX complete callback
6. **Close modals** before page reload
7. **Use data attributes** for passing data to modals
8. **Auto-focus** on first input when modal opens
9. **Clear forms** when modal is hidden
10. **Test on mobile** devices for responsiveness

