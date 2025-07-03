# System Update 13 - Pending Features Implementation

## Overview
This document outlines all features from System Update 13 that are yet to be implemented in the AMIS system. Each feature includes its purpose, objective, navigation path, and necessary context for implementation.

---

## **1. PDF Export and Print Functionality**

**Feature:** PDF Print Function for Activity Pages  
**Purpose:** Enable users to generate professional PDF documents of activity details for printing and archival.  
**Objective:** Provide standardized document layout for activity reports, proposals, and implementation records.  
**Navigation:** Activities → View Activity → Print PDF / Export PDF  
**Necessary Context:** Must include proper formatting, logos, headers, and all activity details including implementation data, GPS coordinates, and file attachments.

**Feature:** PDF Export for All Reports  
**Purpose:** Allow users to export all system reports in PDF format for offline viewing and sharing.  
**Objective:** Enable report distribution and archival in standardized PDF format with professional layout.  
**Navigation:** Reports → [Any Report Type] → Export PDF  
**Necessary Context:** Include charts, graphs, filtered data, and maintain visual formatting. Support for large datasets with pagination.

---

## **2. Enhanced Report Filtering System**

**Feature:** MTDP Report Advanced Filters  
**Purpose:** Provide dynamic filtering capabilities for MTDP reports by SPA, DIP, Specific Area, and date ranges.  
**Objective:** Enable users to generate targeted MTDP reports based on specific criteria with responsive charts and graphs.  
**Navigation:** Reports → MTDP Reports → Apply Filters → View Dynamic Results  
**Necessary Context:** Filters should update charts in real-time, support multiple selection criteria, and maintain filter state across sessions.

**Feature:** NASP Report Advanced Filters  
**Purpose:** Implement comprehensive filtering for NASP reports with dynamic visualization updates.  
**Objective:** Allow detailed analysis of NASP data through customizable filters affecting charts and data display.  
**Navigation:** Reports → NASP Reports → Apply Filters → View Dynamic Results  
**Necessary Context:** Similar filtering capabilities as MTDP with hierarchical plan structure consideration.

**Feature:** Dynamic Charts and Graphs Based on Filters  
**Purpose:** Make all report visualizations responsive to applied filters for real-time data analysis.  
**Objective:** Provide interactive reporting experience where charts automatically update based on user-selected criteria.  
**Navigation:** All Report Pages → Apply Filters → Auto-Updated Visualizations  
**Necessary Context:** Charts should refresh without page reload, maintain performance with large datasets, and support multiple chart types.

---

## **3. New Report Types**

**Feature:** HR Reports - Gender and Demographics  
**Purpose:** Provide comprehensive human resources analytics including gender distribution and employment statistics.  
**Objective:** Enable HR management and compliance reporting with demographic breakdowns and trend analysis.  
**Navigation:** Reports → HR Reports → Gender Analysis / Date Joined Statistics  
**Necessary Context:** Include gender ratios, hiring trends, department distributions, and role-based analytics with visual charts.

**Feature:** HR Reports - Date Joined Analytics  
**Purpose:** Track employee onboarding patterns and tenure analysis across the organization.  
**Objective:** Provide insights into hiring patterns, retention rates, and workforce growth over time.  
**Navigation:** Reports → HR Reports → Employment Timeline / Tenure Analysis  
**Necessary Context:** Show hiring trends by month/year, average tenure, department-wise onboarding, and retention metrics.

**Feature:** Government Structure Reports  
**Purpose:** Generate analytical reports on the PNG administrative hierarchy and structure utilization.  
**Objective:** Provide insights into government structure coverage, activity distribution, and administrative efficiency.  
**Navigation:** Reports → Government Structure Reports → Hierarchy Analytics  
**Necessary Context:** Include coverage maps, activity distribution by administrative level, and structure utilization statistics.

**Feature:** Enhanced SME Reports with Advanced Mapping  
**Purpose:** Comprehensive SME analytics with GPS-based mapping and district-level filtering capabilities.  
**Objective:** Provide detailed SME analysis with geographical distribution and performance metrics.  
**Navigation:** Reports → SME Reports → Map View / District Analysis  
**Necessary Context:** Interactive maps with SME locations, district-based filtering, performance metrics, and geographical clustering.

**Feature:** Price Report Trends for Commodity Boards  
**Purpose:** Track and analyze commodity price trends and market patterns for agricultural products.  
**Objective:** Provide market intelligence and price forecasting for commodity board decision-making.  
**Navigation:** Reports → Commodity Reports → Price Trends / Market Analysis  
**Necessary Context:** Historical price data, trend analysis, seasonal patterns, and comparative market analysis with forecasting capabilities.

---

## **4. Map Integration Enhancements**

**Feature:** SME Display on Activity Maps  
**Purpose:** Integrate SME locations into the existing activity mapping system for comprehensive geographical view.  
**Objective:** Provide unified mapping interface showing both activities and SME locations with filtering capabilities.  
**Navigation:** Reports → Activity Maps → Toggle SME Display / SME Layer  
**Necessary Context:** SME markers should be visually distinct from activity markers, include popup information, and support clustering for dense areas.

**Feature:** District Filtering for SME Maps  
**Purpose:** Enable users to filter SME displays by district and other administrative boundaries.  
**Objective:** Allow focused analysis of SME distribution within specific geographical areas.  
**Navigation:** Reports → Activity Maps → SME Filters → District Selection  
**Necessary Context:** Hierarchical filtering (Province → District → LLG), multiple selection support, and filter persistence across map interactions.

---

## **5. Structural Updates**

**Feature:** Corporate Plan Structure Update  
**Purpose:** Enhance the existing corporate plan framework to align with updated organizational requirements.  
**Objective:** Improve corporate plan management and integration with activity linking system.  
**Navigation:** Admin → Corporate Plans → Structure Management  
**Necessary Context:** Review current corporate plan structure, identify enhancement requirements, and ensure compatibility with existing activity linking system.

---

## **Implementation Priority Recommendations**

### **High Priority:**
1. PDF Export functionality (most requested by users)
2. Enhanced report filtering with dynamic charts
3. SME map integration

### **Medium Priority:**
1. HR Reports implementation
2. Government Structure Reports
3. Price trend reports for commodities

### **Low Priority:**
1. Corporate Plan Structure updates (pending requirements clarification)

---

## **Technical Considerations**

- **PDF Generation:** Implement using libraries like TCPDF or mPDF for CodeIgniter 4
- **Dynamic Charts:** Use Chart.js or similar library with AJAX for real-time updates
- **Map Integration:** Extend existing OpenStreetMap implementation
- **Performance:** Implement caching for large datasets and complex reports
- **User Experience:** Ensure responsive design and intuitive filter interfaces

---

*Document Created: 2025-06-25*  
*Status: Pending Implementation*  
*Source: System Update 13.md*
