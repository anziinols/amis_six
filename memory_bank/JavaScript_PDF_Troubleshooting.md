# JavaScript PDF Troubleshooting Guide

## Common Issues and Solutions

### 1. **404 Error - JavaScript File Not Found**
**Error:** `Failed to load resource: the server responded with a status of 404 (Not Found)`

**Solution:**
- ✅ **Fixed:** Updated file path to `public/assets/js/pdf-generator.js`
- ✅ **Fallback:** Added inline fallback PDF generator in system template
- ✅ **Debug:** Added console logging to verify loading

**File Location:** `public/assets/js/pdf-generator.js`
**Template Path:** `<?= base_url('public/assets/js/pdf-generator.js') ?>`

### 2. **AMISPdf is not defined**
**Error:** `Uncaught ReferenceError: AMISPdf is not defined`

**Solution:**
- ✅ **Fallback Created:** Inline fallback PDF generator automatically creates `window.AMISPdf`
- ✅ **Debug Logging:** Console shows whether main or fallback generator is used
- ✅ **Graceful Handling:** System works regardless of external file loading

### 3. **html2pdf is not defined**
**Error:** `html2pdf is not defined`

**Solution:**
- ✅ **Library Added:** html2pdf.js loaded via CDN in system template
- ✅ **Error Handling:** Fallback checks for library availability
- ✅ **User Notification:** Alert shown if library not available

**CDN Link:** `https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js`

### 4. **PDF Generation Fails Silently**
**Problem:** Button clicks but no PDF generates

**Solution:**
- Check browser console for errors
- Verify html2pdf.js library loaded
- Ensure content element exists
- Check for JavaScript errors

**Debug Steps:**
```javascript
// Open browser console and check:
console.log(typeof html2pdf); // Should show 'function'
console.log(typeof AMISPdf);  // Should show 'object'
```

### 5. **PDF Content Missing or Malformed**
**Problem:** PDF generates but content is incomplete

**Solution:**
- Wait for charts to render before generating PDF
- Check CSS styling for PDF-specific rules
- Verify element visibility and positioning
- Test with different content types

### 6. **Charts Not Appearing in PDF**
**Problem:** Charts visible on page but missing in PDF

**Solution:**
- ✅ **Chart.js Integration:** html2pdf.js captures canvas elements automatically
- ✅ **Timing:** Fallback includes delay for chart rendering
- ✅ **Canvas Support:** Modern browsers support canvas-to-PDF conversion

**Chart Requirements:**
- Charts must be rendered before PDF generation
- Canvas elements should be visible (not hidden)
- Chart.js library should be loaded

### 7. **Styling Issues in PDF**
**Problem:** PDF styling differs from web page

**Solution:**
- ✅ **PDF-Specific CSS:** Added comprehensive PDF styling rules
- ✅ **Color Preservation:** Enabled print-color-adjust for backgrounds
- ✅ **Element Hiding:** Automatic hiding of navigation elements

**CSS Rules Applied:**
```css
.pdf-generating .btn,
.pdf-generating .dropdown,
.pdf-generating .navbar,
.pdf-generating .sidebar {
    display: none !important;
}
```

## Testing Procedures

### 1. **Basic Functionality Test**
```
1. Open any report page
2. Click "Export PDF" button
3. Verify PDF downloads
4. Check console for success/error messages
```

### 2. **Chart Integration Test**
```
1. Open page with charts (MTDP, NASP, Commodity reports)
2. Wait for charts to load completely
3. Generate PDF
4. Verify charts appear in PDF
```

### 3. **Content Verification Test**
```
1. Generate PDF from report page
2. Verify all tables and data included
3. Check that navigation elements are hidden
4. Confirm styling is preserved
```

### 4. **Error Handling Test**
```
1. Disable JavaScript in browser
2. Try to generate PDF
3. Verify graceful error handling
4. Re-enable JavaScript and test again
```

## Debug Information

### **Console Messages:**
- ✅ `"AMIS PDF Generator loaded successfully"` - Main file loaded
- ✅ `"Fallback PDF generator created"` - Using fallback
- ❌ `"PDF generation library not loaded"` - html2pdf.js missing

### **Browser Developer Tools:**
1. **Network Tab:** Check if pdf-generator.js loads (should be 200, not 404)
2. **Console Tab:** Look for error messages and debug logs
3. **Elements Tab:** Verify html2pdf.js script tag exists

### **File Verification:**
```
File: public/assets/js/pdf-generator.js
Size: ~10KB
Status: Should return 200 OK when accessed directly
URL: http://localhost/amis_five/public/assets/js/pdf-generator.js
```

## Performance Optimization

### **Loading Speed:**
- html2pdf.js loaded from CDN for faster delivery
- Fallback code is lightweight and inline
- PDF generation happens client-side (no server delay)

### **Memory Management:**
- Elements restored after PDF generation
- Event listeners properly managed
- No memory leaks in PDF generation process

### **User Experience:**
- Loading indicators during PDF generation
- Button state management (disabled during generation)
- Error notifications for failed attempts

## Browser Compatibility

### **Supported Browsers:**
- ✅ Chrome 60+ (recommended)
- ✅ Firefox 55+
- ✅ Safari 11+
- ✅ Edge 79+

### **Required Features:**
- Canvas API support
- File download capabilities
- Modern JavaScript (ES6+)
- CSS3 support

## Maintenance

### **Regular Checks:**
- Monitor html2pdf.js CDN availability
- Test PDF generation across different browsers
- Verify new UI elements work with PDF generation
- Update fallback code if main library changes

### **Updates:**
- html2pdf.js library updates via CDN
- PDF styling rules in system template
- Fallback functionality improvements
- Browser compatibility testing

---

*Last Updated: 2025-06-26*  
*Status: Production Ready with Fallback*  
*Solution: Dual-layer approach (main + fallback)*
