# AMIS Five - System Architecture Documentation

## Table of Contents
1. [System Overview](#system-overview)
2. [Technical Architecture](#technical-architecture)
3. [Application Structure](#application-structure)
4. [Database Architecture](#database-architecture)
5. [Security Architecture](#security-architecture)
6. [Deployment Architecture](#deployment-architecture)
7. [Integration Architecture](#integration-architecture)
8. [Performance & Scalability](#performance--scalability)

## System Overview

### Application Identity
- **Name**: AMIS Five - Agricultural Management Information System
- **Version**: 5.0
- **Purpose**: Comprehensive agricultural management system for Papua New Guinea's Department of Agriculture and Livestock
- **Target Users**: Government officials, supervisors, agricultural officers, M&E evaluators
- **Primary Domain**: Agricultural workplan management, activity tracking, proposal processing, and reporting

### Core Business Functions
- **Workplan Management**: Create, manage, and track agricultural workplans and activities
- **Proposal Processing**: Handle activity proposals with approval workflows
- **Strategic Planning**: Link activities to NASP, MTDP, and Corporate Plans
- **Monitoring & Evaluation**: Track activity implementation and performance
- **Reporting & Analytics**: Generate comprehensive reports with data visualization
- **User Management**: Role-based access control and user administration
- **Document Management**: Handle file uploads, documents, and meeting records

## Technical Architecture

### Framework & Technology Stack

#### Backend Architecture
```
Framework: CodeIgniter 4 (PHP 8+)
├── Architecture Pattern: Model-View-Controller (MVC)
├── Database: MySQL 8.0
├── Web Server: Apache (XAMPP)
├── Session Management: File-based sessions
└── Email System: SMTP (dakoiims.com:465)
```

#### Frontend Architecture
```
UI Framework: Bootstrap 5.3.0
├── JavaScript Libraries:
│   ├── jQuery 3.6.0 (DOM manipulation)
│   ├── DataTables 1.13.6 (table management)
│   ├── Select2 4.1.0 (enhanced dropdowns)
│   ├── Chart.js (data visualization)
│   └── Toastr (notifications)
├── Icons: Font Awesome 6.4.0
├── Fonts: Google Fonts - Inter (300,400,500,600,700)
└── Maps: OpenStreetMap integration
```

#### Development Environment
```
Local Development: XAMPP
├── Base URL: http://localhost/amis_five/
├── Database: amis_db (MySQL)
├── File Storage: public/uploads/
└── Session Storage: writable/session/
```

## Application Structure

### Directory Organization
```
amis_five/
├── app/
│   ├── Config/           # Configuration files
│   ├── Controllers/      # Business logic controllers
│   │   └── Admin/        # Administrative controllers
│   ├── Models/           # Data access layer
│   ├── Views/            # Presentation layer
│   │   ├── templates/    # Layout templates
│   │   └── [modules]/    # Module-specific views
│   ├── Filters/          # Security filters
│   └── Database/         # Migrations and seeds
├── public/
│   ├── assets/           # Static assets (CSS, JS, images)
│   ├── uploads/          # User-uploaded files
│   └── index.php         # Application entry point
├── backup_dp/            # Database backups and documentation
└── memory_bank/          # System profiles and configurations
```

### MVC Architecture Pattern

#### Controllers Layer
- **Naming Convention**: PascalCase with 'Controller' suffix
- **Structure**: RESTful approach with separate GET/POST methods
- **Location**: `app/Controllers/` and `app/Controllers/Admin/`
- **Responsibilities**: Handle HTTP requests, business logic, and response generation

#### Models Layer
- **Naming Convention**: PascalCase with 'Model' suffix
- **Structure**: CodeIgniter 4 Model class with validation rules
- **Location**: `app/Models/`
- **Responsibilities**: Data access, validation, and business rules

#### Views Layer
- **Naming Convention**: `module_prefix + action` (e.g., workplan_index.php)
- **Structure**: Organized by module folders
- **Template**: `app/Views/templates/system_template.php`
- **Responsibilities**: User interface presentation and user interaction

## Database Architecture

### Database Configuration
```sql
Database Name: amis_db
Engine: MySQL 8.0
Character Set: utf8mb4_general_ci
Connection: MySQLi Driver
Host: localhost:3306
```

### Core Database Tables

#### User Management
- **users**: User accounts and profiles
- **branches**: Organizational structure
- **gov_structure**: Hierarchical government structure (provinces, districts, LLGs, wards)

#### Planning & Strategy
- **plans_nasp**: National Agriculture Sector Plan
- **plans_mtdp**: Medium Term Development Plan
- **plans_corporate**: Corporate planning data
- **commodities**: Agricultural commodity management

#### Workplan Management
- **workplans**: Main workplan records
- **workplan_activities**: Activity definitions
- **workplan_[type]_activities**: Specific activity types (training, infrastructure, inputs, output)
- **proposals**: Activity proposals and approvals

#### Supporting Systems
- **meetings**: Meeting management
- **documents**: Document storage
- **agreements**: Agreement tracking
- **sme**: Small and Medium Enterprise data

### Data Relationships
```
Users ──┐
        ├── Workplans ──── Activities ──── Proposals
        └── Branches ──── Gov_Structure
                     
Strategic Plans:
├── NASP (Plans → APAs → DIPs → Specific Areas → Objectives → Outputs → Indicators)
├── MTDP (Plans → SPAs → DIPs → Specific Areas → Investments → KRAs → Strategies → Indicators)
└── Corporate Plans (Plans → Objectives → Strategies → KRAs → Indicators)
```

## Security Architecture

### Authentication System
```
Authentication Method: Session-based
├── Password Policy: Minimum 4 characters
├── Session Storage: File-based sessions
├── Remember Me: Cookie-based persistence
└── Multi-system Support: AMIS + Dakoii systems
```

### Authorization Framework
```
Role-Based Access Control (RBAC):
├── admin: Full system access
├── supervisor: Workplan supervision and approval
├── user: Standard user operations
├── guest: Read-only limited access
└── commodity: Specialized commodity management
```

### Security Filters & Middleware
```php
Security Filters:
├── AuthFilter: Authentication verification
├── CSRF Protection: Cross-site request forgery prevention
├── SecureHeaders: HTTP security headers
└── InvalidChars: Input sanitization
```

### Data Protection
- **Input Validation**: Model-level validation rules
- **SQL Injection Prevention**: CodeIgniter Query Builder
- **XSS Protection**: Built-in output escaping
- **File Upload Security**: Restricted file types and locations
- **Session Security**: Secure session configuration

## Deployment Architecture

### Environment Configuration
```
Development Environment:
├── XAMPP Stack (Apache + MySQL + PHP)
├── Base URL: http://localhost/amis_five/
├── Debug Mode: Enabled
├── Error Reporting: Full error display
└── Database Debug: Enabled

Production Environment:
├── Error Reporting: Disabled
├── Debug Mode: Disabled
├── HTTPS: Force HTTPS enabled
├── Performance: Optimized caching
└── Security: Enhanced security headers
```

### File Structure & Permissions
```
File Organization:
├── Application Files: app/ (protected)
├── Public Assets: public/ (web accessible)
├── Uploads: public/uploads/ (controlled access)
├── Logs: writable/logs/ (system logs)
├── Cache: writable/cache/ (performance cache)
└── Sessions: writable/session/ (session data)
```

## Integration Architecture

### Email Integration
```
SMTP Configuration:
├── Server: mail.dakoiims.com:465
├── Authentication: SMTP with SSL
├── From Address: test-email@dakoiims.com
└── Notifications: Automated system notifications
```

### External Services
- **OpenStreetMap**: Geographic mapping services
- **Chart.js**: Data visualization and reporting
- **DataTables**: Advanced table functionality with PDF export
- **Select2**: Enhanced dropdown interfaces

### API Architecture
```
Internal APIs:
├── AJAX Endpoints: /api/* routes
├── Plan Data APIs: MTDP, NASP, Corporate plan data
├── File Upload APIs: Document and image handling
└── Reporting APIs: Data export and visualization
```

## Performance & Scalability

### Caching Strategy
```
Caching Configuration:
├── Primary Handler: File-based caching
├── Backup Handler: Dummy handler
├── Cache Location: writable/cache/
└── Config Caching: Disabled (development)
```

### Database Optimization
- **Query Optimization**: CodeIgniter Query Builder
- **Indexing Strategy**: Primary keys and foreign key indexes
- **Connection Pooling**: MySQLi persistent connections
- **Data Archiving**: Soft delete implementation

### Performance Features
- **Asset Optimization**: Minified CSS/JS in production
- **Image Optimization**: Controlled file upload sizes
- **Session Optimization**: File-based session storage
- **Database Connection**: Optimized MySQLi configuration

### Scalability Considerations
- **Modular Architecture**: Separated concerns for easy scaling
- **Database Design**: Normalized structure for data integrity
- **File Storage**: Organized upload directory structure
- **Session Management**: Scalable file-based sessions
- **API Design**: RESTful endpoints for future integrations

---

**Document Version**: 1.0  
**Last Updated**: July 2025  
**Maintained By**: AMIS Development Team  
**Review Cycle**: Quarterly
