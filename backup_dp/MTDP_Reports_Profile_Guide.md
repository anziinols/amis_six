# MTDP Reports Page - Comprehensive Profile Guide

## Overview
**Route:** `http://localhost/amis_five/reports/mtdp`  
**Controller:** `MTDReportsController`  
**View:** `reports_mtdp/reports_mtdp_index.php`  
**Purpose:** Comprehensive read-only reporting dashboard for Medium Term Development Plan (MTDP) data with advanced filtering, visualization, and export capabilities.

## Page Structure & Sections

### 1. Date Filter Section
- **Location:** Top of page in card container
- **Functionality:** Global date range filtering for all data and charts
- **Components:**
  - Date From input field
  - Date To input field  
  - Filter button (applies filters)
  - Clear Filters button (resets to show all data)
- **Filter Logic:** Filters data based on MTDP plan year ranges and completed proposal dates

### 2. Charts Section (4 Interactive Charts)
- **Yearly Investment Distribution** (Bar Chart)
  - Shows investment amounts across 5-year MTDP periods
  - Copy-to-clipboard functionality
- **Status Distribution** (Stacked Bar Chart)
  - Active vs Inactive counts for DIPs, KRAs, Specific Areas, Strategies, Indicators
- **Investment by DIP** (Pie Chart)
  - Investment distribution across Development Investment Plans
  - Currency formatting in tooltips
- **Entities by MTDP Plan** (Stacked Bar Chart)
  - Entity counts (SPAs, DIPs, KRAs, etc.) grouped by MTDP plan

### 3. Data Tables Section (8 Tables)
Each table includes:
- Counter numbers (#) in first column
- PDF export functionality via DataTables
- Search functionality
- No pagination (shows all data)
- Responsive design

**Tables (Updated Order Following Correct Hierarchy):**
1. **MTDP Plans** - Main plans with dates and status
2. **Strategic Priority Areas (SPAs)** - With workplan counts
3. **Development Investment Plans (DIPs)** - With workplan counts
4. **Specific Areas** - Detailed area information under DIPs
5. **Investments** - 5-year investment breakdown with funding sources under Specific Areas
6. **Key Result Areas (KRAs)** - With workplan counts under Investments
7. **Strategies** - Policy references and workplan counts under KRAs
8. **Indicators** - Performance indicators with 5-year targets under Strategies

### 4. Table Display Enhancements
**Full Text Display & Horizontal Scrolling:**
- All tables now display complete text content without truncation
- Horizontal scrolling enabled with `overflow-x: auto`
- Tables use `white-space: nowrap` to prevent text wrapping
- `min-width: 100%` ensures proper table layout
- Removed text truncation from KRA and Strategy columns

**Updated Table Columns (Following Hierarchy):**
- **Investments Table**: MTDP, SPA, DIP, **Specific Area**, Investment, Year 1-5, Total Amount, Funding Sources, Status
- **KRAs Table**: MTDP, SPA, DIP, **Specific Area**, **Investment**, KPI, Year 1-5, Responsible Agencies, Status, Workplans
- **Strategies Table**: MTDP, SPA, DIP, **Specific Area**, **Investment**, **KRA**, Strategy, Status, Workplans
- **Indicators Table**: MTDP, SPA, DIP, **Specific Area**, **Investment**, **KRA**, **Strategy**, Indicator, Source, Baseline, Year 1-5, Status

### 5. MTDP Hierarchy Mind Map
**Updated Hierarchical Visualization:**
- **Structure**: Plans → SPAs → DIPs → Specific Areas → Investments → KRAs → Strategies → Indicators
- **Display Format**: Nested unordered list with proper indentation
- **Visual Elements**: Bold labels for each hierarchy level (SPA:, DIP:, Specific Area:, etc.)
- **Data Relationships**: Shows complete parent-child relationships across all levels
- **Comprehensive View**: Displays full hierarchy from top-level plans down to individual indicators

## Tech Stack

### Backend Framework
- **CodeIgniter 4** - PHP framework
- **RESTful Architecture** - Single GET method for page display
- **Model-View-Controller (MVC)** pattern

### Frontend Technologies
- **Bootstrap 5.3.0** - UI framework and responsive design
- **Chart.js** - Interactive charts and visualizations
- **DataTables** - Table enhancement with search, export, responsive features
- **jQuery 3.6.0** - DOM manipulation and AJAX
- **Font Awesome 6.4.0** - Icons
- **Google Fonts (Inter)** - Typography

### Data Export & Visualization
- **DataTables Buttons** - PDF export functionality
- **PDFMake** - PDF generation library
- **JSZip** - File compression for exports
- **Canvas API** - Chart image copying functionality

### Notification System
- **Toastr.js** - Toast notifications for user feedback

## Database Models & Structure

### Core Models Used
1. **MtdpModel** (`plans_mtdp`) - Main MTDP plans
2. **MtdpSpaModel** (`plans_mtdp_spa`) - Strategic Priority Areas
3. **MtdpDipModel** (`plans_mtdp_dip`) - Development Investment Plans
4. **MtdpKraModel** (`plans_mtdp_kra`) - Key Result Areas
5. **MtdpSpecificAreaModel** (`plans_mtdp_specific_area`) - Specific Areas
6. **MtdpInvestmentsModel** (`plans_mtdp_investments`) - Investment data
7. **MtdpStrategiesModel** (`plans_mtdp_strategies`) - Strategies
8. **MtdpIndicatorsModel** (`plans_mtdp_indicators`) - Performance indicators
9. **WorkplanMtdpLinkModel** (`workplan_mtdp_link`) - Workplan linkages
10. **ProposalModel** - For completed proposal filtering

### Data Relationships
- **Updated Hierarchical structure**: MTDP → SPA → DIP → Specific Area → Investments → KRA → Strategies → Indicators
- **Hierarchy Abbreviations**:
  - SPA = Strategic Priority Areas
  - DIP = Deliberate Intervention Program
  - KRAs = Key Result Areas
- Workplan linkages track connections between activities and MTDP components
- Status tracking for each entity type with user attribution and timestamps

## Key Features

### 1. Advanced Filtering System
- **Date Range Filtering:** Filters based on MTDP plan years and proposal completion dates
- **Global Filter Application:** Affects both charts and tables simultaneously
- **Smart Date Logic:** Overlaps filter dates with 5-year MTDP plan periods

### 2. Interactive Data Visualization
- **Responsive Charts:** All charts adapt to screen size
- **Copy Chart Functionality:** One-click chart copying to clipboard
- **Dynamic Data Loading:** Charts update based on applied filters
- **Color Consistency:** Standardized color palette across all visualizations

### 3. Comprehensive Data Export
- **PDF Export per Table:** Individual table exports with custom titles
- **Landscape Orientation:** Optimized for wide data tables
- **Custom PDF Styling:** Branded headers and consistent formatting
- **No Pagination Export:** Exports complete datasets

### 5. Workplan Integration
- **Linkage Tracking:** Shows workplan connections for strategies, KRAs, DIPs, SPAs
- **Completion Metrics:** Counts based on rated/completed proposals
- **Cross-Reference Data:** Links between planning and implementation

## UI/UX Design Elements

### Design System
- **Color Palette:**
  - Primary Green: `#6ba84f`
  - Navy Blue: `#1a237e`
  - Light Background: `#f5f7fa`
  - Card Shadow: `0 4px 6px rgba(0, 0, 0, 0.1)`

### Layout Patterns
- **Card-Based Design:** All sections wrapped in Bootstrap cards
- **Responsive Grid:** Bootstrap grid system for layout
- **Consistent Spacing:** Standardized margins and padding
- **Visual Hierarchy:** Clear section separation and typography

### Interactive Elements
- **Copy Buttons:** Chart copy functionality with icon indicators
- **Filter Controls:** Intuitive date range selection
- **Export Buttons:** Prominent PDF export options
- **Search Functionality:** Built-in table search capabilities

### Accessibility Features
- **Keyboard Navigation:** Full keyboard accessibility
- **Screen Reader Support:** Proper ARIA labels and semantic HTML
- **High Contrast:** Sufficient color contrast ratios
- **Responsive Design:** Mobile-friendly interface

## Implementation Patterns

### Controller Pattern
```php
// Single method handling all data preparation
public function index() {
    // Initialize all required models
    // Get filter parameters
    // Fetch and filter data
    // Prepare chart data
    // Pass to view
}
```

### View Structure Pattern
```php
// Extend system template
// Define head section for additional CSS/JS
// Date filter form
// Charts section with copy buttons
// Tables section with DataTables
// JavaScript section for charts and interactions
```

### JavaScript Organization
- **Chart Configuration:** Centralized chart setup with consistent options
- **DataTables Setup:** Reusable configuration object
- **Utility Functions:** Copy functionality, toast notifications
- **Event Handlers:** Filter submissions, button clicks

## Performance Considerations

### Data Loading Strategy
- **Single Page Load:** All data fetched on initial request
- **Client-Side Filtering:** Charts update without server requests
- **Optimized Queries:** Efficient database joins and filtering

### Caching Strategy
- **Model-Level Caching:** Potential for query result caching
- **Static Asset Caching:** CDN-delivered libraries
- **Browser Caching:** Proper cache headers for static resources

## Security Features

### Data Protection
- **Input Sanitization:** All user inputs properly escaped
- **SQL Injection Prevention:** Parameterized queries via CodeIgniter ORM
- **XSS Protection:** Output escaping in views
- **CSRF Protection:** Form token validation

### Access Control
- **Authentication Required:** User session validation
- **Role-Based Access:** Potential for role-specific data filtering
- **Read-Only Interface:** No data modification capabilities

## Responsive Design Implementation

### Breakpoint Strategy
- **Desktop (≥992px):** Full sidebar, all features visible
- **Tablet (768px-991px):** Collapsed sidebar, maintained functionality
- **Mobile (<768px):** Hidden sidebar, optimized touch interface

### Mobile Optimizations
- **Touch-Friendly Controls:** Larger buttons and form elements
- **Simplified Navigation:** Streamlined interface elements
- **Optimized Charts:** Responsive chart sizing and interaction

## File Naming Conventions

### View Files
- **Prefix Pattern:** `reports_mtdp_` for all MTDP report views
- **Descriptive Names:** Clear indication of functionality
- **Consistent Structure:** Standardized across report modules

### Asset Organization
- **CSS:** Integrated in system template
- **JavaScript:** Inline for page-specific functionality
- **External Libraries:** CDN-delivered for performance

## Integration Points

### System Template Integration
- **Extends:** `templates/system_template.php`
- **Head Section:** Additional CSS/JS libraries
- **Footer Section:** Page-specific JavaScript

### Navigation Integration
- **Menu Structure:** Reports → MTDP Reports
- **Breadcrumb Support:** Hierarchical navigation
- **Back Button Functionality:** Return to reports index

## Error Handling

### Data Validation
- **Date Format Validation:** Proper date input handling
- **Empty Data Handling:** Graceful handling of missing data
- **Chart Error Handling:** Fallbacks for chart rendering issues

### User Feedback
- **Toast Notifications:** Success/error message display
- **Loading States:** Visual feedback during data processing
- **Fallback Messages:** Clear communication of issues

## Recent Updates (2025-07-08)

### Hierarchy Restructuring
- **Updated MTDP Hierarchy**: Changed from previous structure to correct hierarchy: Plans → SPAs → DIPs → Specific Areas → Investments → KRAs → Strategies → Indicators
- **Table Reordering**: Moved Specific Areas table to position 4 (after DIPs), Investments to position 5, KRAs to position 6
- **Mind Map Update**: Updated hierarchical visualization to reflect correct parent-child relationships

### Table Display Improvements
- **Full Text Display**: Removed all text truncation from tables (KRA and Strategy columns now show complete text)
- **Horizontal Scrolling**: Added `overflow-x: auto` to all table containers for better usability
- **Text Wrapping Prevention**: Implemented `white-space: nowrap` to maintain table structure
- **Enhanced Columns**: Added hierarchy columns (Specific Area, Investment) to KRAs, Strategies, and Indicators tables

### User Experience Enhancements
- **Better Readability**: Users can now see complete text content without truncation
- **Improved Navigation**: Horizontal scrolling allows viewing of wide tables without layout issues
- **Consistent Hierarchy**: All tables and visualizations now follow the same hierarchical structure

This profile serves as a comprehensive guide for implementing similar reporting functionality across other modules in the AMIS system, ensuring consistency in design, functionality, and user experience.
