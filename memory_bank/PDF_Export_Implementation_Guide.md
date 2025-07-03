# PDF Export Implementation Guide

## Overview
This document outlines the implementation of PDF export functionality for the AMIS system, covering both activity pages and all report types.

## Features Implemented

### 1. PDF Export for Activity Pages
**Location:** Activities → View Activity → Export PDF  
**Controller:** `ActivitiesController::exportPdf()`  
**Route:** `activities/(:num)/export-pdf`

**Features:**
- Complete activity details with implementation data
- GPS coordinates and location information
- File attachments information
- Proposal and assignment details
- Professional formatting with headers and footers
- Authorization checks (users can only export activities they have access to)

### 2. PDF Export for All Reports
**Locations:** All report pages have "Export PDF" buttons  
**Controllers:** Various report controllers with `exportPdf()` methods

**Report Types:**
- **Workplan Reports** (`reports/workplan/export-pdf`)
- **NASP Reports** (`reports/nasp/export-pdf`)
- **MTDP Reports** (`reports/mtdp/export-pdf`)
- **Commodity Reports** (`reports/commodity/export-pdf`)
- **Activity Maps Reports** (`reports/activities-map/export-pdf`)

## Technical Implementation

### Core Components

#### 1. PdfHelper Class (`app/Helpers/PdfHelper.php`)
- Base PDF generation functionality using TCPDF
- Standardized formatting, headers, and footers
- Utility methods for titles, tables, text, and HTML content
- Consistent styling across all PDF exports

#### 2. PdfService Class (`app/Services/PdfService.php`)
- High-level PDF generation service
- Activity PDF generation with comprehensive details
- Report PDF generation with charts and data tables
- Handles different activity types (training, inputs, infrastructure, output)

#### 3. Controller Integration
All relevant controllers now include PDF export methods:
- `ActivitiesController::exportPdf()`
- `WorkplanReportsController::exportPdf()`
- `NASPReportsController::exportPdf()`
- `MTDReportsController::exportPdf()`
- `CommodityReportsController::exportPdf()`
- `ActivityMapsReportsController::exportPdf()`

### Database Integration
Enhanced model methods for comprehensive data retrieval:
- `WorkplanModel::getWorkplansWithDetails()`
- `WorkplanActivityModel::getActivitiesWithDetails()`
- `ProposalModel::getProposalsWithDetails()`
- Link models with `getLinksWithDetails()` methods

### UI Integration
PDF export buttons added to:
- Activity detail pages (top-right corner)
- All report pages (header section)
- Consistent styling with Font Awesome PDF icons
- Opens in new tab/window for better user experience

## Usage Instructions

### For Activity PDFs:
1. Navigate to any activity detail page
2. Click the "Export PDF" button in the top-right corner
3. PDF will open in a new tab with complete activity information

### For Report PDFs:
1. Navigate to any report page (Workplan, NASP, MTDP, etc.)
2. Click the "Export PDF" button in the header section
3. PDF will generate with current report data and charts

## PDF Content Structure

### Activity PDFs Include:
- **Header:** Department logo and title
- **Activity Information:** Title, type, workplan, branch, supervisor
- **Description:** Full activity description
- **Implementation Details:** Based on activity type (training/inputs/infrastructure/output)
- **Plan Links:** NASP, MTDP, Corporate Plan connections
- **Proposal Information:** Assignment details, location, costs
- **Footer:** Page numbers and generation timestamp

### Report PDFs Include:
- **Header:** Department logo and report title
- **Summary Section:** Key statistics and metrics
- **Data Tables:** Detailed information in tabular format
- **Charts Information:** Summary of chart data (note: actual charts require additional implementation)
- **Footer:** Page numbers and generation timestamp

## Testing

### Test Routes Available:
- `/test-pdf/basic` - Basic PDF functionality test
- `/test-pdf/activity/1` - Test activity PDF generation
- `/test-pdf/report` - Test report PDF generation

### Manual Testing:
1. Test activity PDF export with different activity types
2. Test report PDF export for all report types
3. Verify authorization (users should only access their assigned activities)
4. Test with different data scenarios (with/without implementation data)

## Configuration

### TCPDF Library:
- Installed via Composer: `tecnickcom/tcpdf`
- Configured for UTF-8 support
- A4 page format with standard margins
- Professional header/footer styling

### File Paths:
- Logo path: `public/assets/images/logo.png` (optional)
- Upload directories: Various under `public/uploads/`
- PDF output: Direct browser display (inline)

## Error Handling
- Try-catch blocks in all PDF generation methods
- Graceful fallback for missing data
- User-friendly error messages
- Detailed error logging for debugging

## Security Considerations
- Authorization checks before PDF generation
- User can only export activities they have access to
- Admin users can export all activities
- No sensitive data exposure in error messages

## Future Enhancements
1. **Chart Integration:** Include actual charts in report PDFs
2. **Batch Export:** Export multiple activities/reports at once
3. **Custom Templates:** Allow customization of PDF layouts
4. **Email Integration:** Send PDFs via email
5. **Scheduled Reports:** Automated PDF generation and distribution

## Troubleshooting

### Common Issues:
1. **Missing TCPDF:** Run `composer require tecnickcom/tcpdf`
2. **Memory Issues:** Increase PHP memory limit for large reports
3. **Font Issues:** Ensure proper UTF-8 encoding
4. **Permission Errors:** Check file system permissions for uploads

### Debug Mode:
Enable detailed error logging in development:
```php
log_message('error', 'PDF Export Error: ' . $e->getMessage());
```

## Maintenance
- Regular testing of PDF generation functionality
- Monitor file system usage for uploaded attachments
- Update PDF templates as needed for new features
- Keep TCPDF library updated for security patches

---

*Implementation completed: 2025-06-26*  
*Status: Ready for production use*
