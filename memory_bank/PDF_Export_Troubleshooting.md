# PDF Export Troubleshooting Guide

## Common Issues and Solutions

### 1. **404 Error - Route Not Found**
**Error:** `Can't find a route for 'GET: reports/mtdp/export-pdf'`

**Solution:**
- Check that all PDF export routes are properly defined in `app/Config/Routes.php`
- Ensure routes are outside of any route groups that might have filters
- Verify route syntax is correct

**Fixed Routes:**
```php
// MTDP Reports routes
$routes->get('reports/mtdp', 'MTDReportsController::index');
$routes->get('reports/mtdp/export-pdf', 'MTDReportsController::exportPdf');

// NASP Reports routes
$routes->get('reports/nasp', 'NASPReportsController::index');
$routes->get('reports/nasp/export-pdf', 'NASPReportsController::exportPdf');

// Workplan Reports routes
$routes->get('reports/workplan', 'WorkplanReportsController::index');
$routes->get('reports/workplan/export-pdf', 'WorkplanReportsController::exportPdf');

// Activities Map Reports routes
$routes->get('reports/activities-map', 'ActivityMapsReportsController::index');
$routes->get('reports/activities-map/export-pdf', 'ActivityMapsReportsController::exportPdf');

// Commodity Reports routes
$routes->get('reports/commodity', 'CommodityReportsController::index');
$routes->get('reports/commodity/export-pdf', 'CommodityReportsController::exportPdf');

// Activities PDF export (within activities group)
$routes->group('activities', ['filter' => 'auth'], function($routes) {
    // ... other routes ...
    $routes->get('(:num)/export-pdf', 'ActivitiesController::exportPdf/$1');
});
```

### 2. **Class Not Found Error**
**Error:** `Class 'App\Services\PdfService' not found`

**Solution:**
- Ensure PdfService import is added to controller:
```php
use App\Services\PdfService;
```
- Verify PdfService.php exists in `app/Services/` directory
- Check namespace declaration in PdfService.php

### 3. **TCPDF Library Error**
**Error:** `Class 'TCPDF' not found`

**Solution:**
- Install TCPDF via Composer:
```bash
composer require tecnickcom/tcpdf
```
- Verify composer autoload is working
- Check if vendor directory exists and contains TCPDF

### 4. **Method Not Found Error**
**Error:** `Call to undefined method MTDReportsController::exportPdf()`

**Solution:**
- Ensure exportPdf() method exists in the controller
- Check method visibility (should be public)
- Verify method signature matches route expectations

### 5. **Memory Limit Exceeded**
**Error:** `Fatal error: Allowed memory size exhausted`

**Solution:**
- Increase PHP memory limit in php.ini:
```ini
memory_limit = 256M
```
- Optimize PDF generation for large datasets
- Implement pagination for large reports

### 6. **Permission Denied Error**
**Error:** `Permission denied when creating PDF`

**Solution:**
- Check file system permissions for upload directories
- Ensure web server has write access to temp directories
- Verify TCPDF can create temporary files

### 7. **Missing Model Methods**
**Error:** `Call to undefined method WorkplanModel::getWorkplansWithDetails()`

**Solution:**
- Ensure all required model methods are implemented:
  - `WorkplanModel::getWorkplansWithDetails()`
  - `WorkplanActivityModel::getActivitiesWithDetails()`
  - `ProposalModel::getProposalsWithDetails()`
  - Link models: `getLinksWithDetails()` methods

### 8. **PDF Content Issues**
**Problem:** PDF generates but content is missing or malformed

**Solution:**
- Check data retrieval in controller methods
- Verify model relationships and joins
- Test with sample data first
- Check for null/empty data handling

### 9. **Authorization Issues**
**Error:** `You are not authorized to export this activity`

**Solution:**
- Verify user session and role
- Check authorization logic in controller
- Ensure proper user-activity relationships
- Admin users should have access to all activities

### 10. **Chart/Image Issues**
**Problem:** Charts or images not appearing in PDF

**Solution:**
- Verify image paths are correct
- Check if logo file exists in `public/assets/images/`
- Ensure proper image format (PNG, JPG)
- Test with text-only headers if images fail

## Testing Procedures

### 1. **Basic PDF Test**
```
URL: http://localhost/amis_five/test-pdf/basic
Expected: Simple PDF with test content
```

### 2. **Activity PDF Test**
```
URL: http://localhost/amis_five/test-pdf/activity/1
Expected: PDF with activity details
```

### 3. **Report PDF Test**
```
URL: http://localhost/amis_five/test-pdf/report
Expected: PDF with sample report data
```

### 4. **Production Tests**
- Test each report type PDF export
- Test activity PDF export with different activity types
- Test with different user roles (admin, supervisor, officer)
- Test with large datasets

## Debug Mode

### Enable Detailed Logging:
```php
// In controller methods
try {
    // PDF generation code
} catch (\Exception $e) {
    log_message('error', 'PDF Export Error: ' . $e->getMessage());
    log_message('error', 'Stack trace: ' . $e->getTraceAsString());
    return redirect()->back()->with('error', 'PDF generation failed. Please try again.');
}
```

### Check Logs:
- Location: `writable/logs/`
- Look for PDF-related errors
- Check for memory, permission, or library issues

## Performance Optimization

### For Large Reports:
1. **Limit Data:** Implement pagination or date ranges
2. **Optimize Queries:** Use efficient database queries
3. **Memory Management:** Unset large variables after use
4. **Caching:** Cache frequently accessed data

### Example Optimization:
```php
// Limit records for PDF export
$productions = $this->commodityProductionModel
    ->limit(100)  // Limit to 100 records
    ->findAll();
```

## Browser Compatibility

### Supported Browsers:
- Chrome (recommended)
- Firefox
- Safari
- Edge

### PDF Display Issues:
- Ensure browser PDF viewer is enabled
- Test with different browsers
- Provide download option if inline display fails

---

*Last Updated: 2025-06-26*  
*Status: Production Ready*
