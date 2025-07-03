# AMIS Five System: Current Features & Functions

## System Overview
- **Framework**: CodeIgniter 4 with MVC architecture
- **Frontend**: Bootstrap 5.3.0, jQuery 3.6.0, DataTables, Select2, Font Awesome 6.4.0
- **Database**: MySQL with comprehensive schema
- **Deployment**: XAMPP environment (Apache, PHP 8+, MySQL)
- **Status**: Production-ready with active development

## Core Modules & Features

### 1. User Management System
**Status**: ✅ Complete
- **User Roles**: admin, supervisor, user, guest, commodity
- **Special Flags**: is_evaluator (M&E), report_to_id (hierarchical reporting)
- **Features**:
  - CRUD operations for user accounts
  - Role-based access control
  - Photo uploads with file management
  - Status tracking and email notifications
  - Password reset via email with temporary passwords
  - Hierarchical reporting structure

### 2. Workplan Management System
**Status**: ✅ Complete
- **Core Features**:
  - Workplan CRUD operations
  - Activity management with three types: training, infrastructure, inputs
  - Supervisor assignment and notifications
  - Status tracking (draft, in_progress, completed, on_hold, cancelled)
  - Plan linking to NASP, MTDP, and Corporate Plans
  - **NEW**: Visual plan link indicators in activities list (✓ checkmarks)
  - **NEW**: Auto-redirect to plans linking page after activity creation

### 3. Activity Management System
**Status**: ✅ Complete
- **Activity Types**:
  - **Training**: Trainers, trainees, topics, training materials
  - **Infrastructure**: Construction/development projects
  - **Inputs**: Agricultural input distribution
- **Features**:
  - GPS coordinates tracking
  - File uploads (images, documents, signing sheets)
  - Implementation tracking forms
  - Proposal generation workflow
  - Location-based filtering (province, district, LLG, ward)
  - Cost tracking and budget management

### 4. Proposal Workflow System
**Status**: ✅ Complete
- **Workflow States**: pending → submitted → approved → rated → rejected
- **Features**:
  - Supervisor approval process
  - M&E (Monitoring & Evaluation) rating system
  - Email notifications for status changes
  - Activity implementation tracking
  - GPS coordinates for infrastructure/input activities
  - Signing sheet uploads for verification

### 5. Planning Frameworks Integration
**Status**: ✅ Complete
- **NASP (National Agriculture Strategic Plan)**:
  - Hierarchy: Plans → APAs → DIPs → Specific Areas → Outputs → Indicators
  - Activity linking with detailed tracking
- **MTDP (Medium Term Development Plan)**:
  - Hierarchy: Plan → SPA → DIP → Specific Area → Investments → KRA → Strategies → Indicators
  - Comprehensive linking system
- **Corporate Plan**:
  - Hierarchy: Plan → Overarching Objectives → Objectives → KRAs → Strategy
  - Strategic alignment tracking
### 6. Document Management System
**Status**: ✅ Complete
- **Features**:
  - Hierarchical folder structure
  - File uploads with drag-and-drop interface
  - Access control and permissions
  - File metadata tracking
  - Preview capabilities (PDF, images)
  - File type validation and security
  - Branch-based organization

### 7. Meeting Management System
**Status**: ✅ Complete
- **Features**:
  - Meeting scheduling and CRUD operations
  - Participant tracking and management
  - Branch-based filtering
  - Status tracking and notifications
  - Integration with user management

### 8. Agreement Management System
**Status**: ✅ Complete
- **Features**:
  - Agreement/contract CRUD operations
  - File attachment capabilities
  - Status tracking and workflow
  - Document version control
  - Approval processes

### 9. SME (Small & Medium Enterprise) Management
**Status**: ✅ Complete
- **Features**:
  - SME registration and profiles
  - Staff management with photos
  - Logo uploads and branding
  - GPS location tracking
  - **Map Integration**: OpenStreetMap with SME locations
  - District and regional filtering

### 10. Commodity Management System
**Status**: ✅ Complete
- **Features**:
  - Commodity production tracking
  - Specialized dashboard interface
  - Role-based access (commodity role)
  - Production data management
  - Board management functionality

### 11. Government Structure Management
**Status**: ✅ Complete
- **Hierarchy**: Province → District → LLG → Ward
- **Features**:
  - Hierarchical data management
  - CSV import functionality with templates
  - Code and name field management
  - Administrative structure alignment

### 12. Reporting System
**Status**: ✅ Complete
- **Report Types**:
  - Workplan Reports with filtering
  - Activity Reports with GPS mapping
  - NASP Reports with hierarchical data
  - MTDP Reports with comprehensive filtering
  - Commodity Reports with trends and analytics
  - Activity Maps using OpenStreetMap
- **Features**:
  - Charts and data visualization
  - Export capabilities
  - Interactive mapping
  - Dynamic filtering systems

### 13. Dashboard System
**Status**: ✅ Complete
- **Main Dashboard**: Role-based content display
- **Dakoii Portal**: Administrative dashboard
- **Features**:
  - Personalized user experience
  - Quick access to key functions
  - Statistics and metrics display
  - Recent activity tracking

### 14. Email Notification System
**Status**: ✅ Complete
- **SMTP Configuration**: dakoiims.com (port 465)
- **Notification Types**:
  - User account updates and status changes
  - Workplan supervisor notifications
  - Proposal status changes and approvals
  - Activity submissions and M&E ratings
  - Password reset and temporary passwords

### 15. Authentication & Security
**Status**: ✅ Complete
- **Session-based authentication**
- **Password policy**: Minimum 4 characters (configurable)
- **Role-based access control**
- **CSRF protection**
- **File upload security**
- **SQL injection prevention**

## Technical Implementation Functions

### Core Activity Functions
```php
// Activity Management
WorkplanActivitiesController::create($workplanId)
WorkplanActivitiesController::index($workplanId) // with plan link indicators
WorkplanActivitiesController::show($workplanId, $activityId)
WorkplanActivitiesController::update($workplanId, $activityId)

// Plan Linking
WorkplanController::activityPlans($workplanId, $activityId)
WorkplanController::linkActivityPlan($workplanId, $activityId)
WorkplanController::deleteActivityPlan($workplanId, $activityId, $planId)

// Implementation Tracking
ActivitiesController::implement($activityId)
ActivitiesController::saveImplementation($activityId)
ActivitiesController::submitForSupervision($activityId)
```

### Proposal Workflow Functions
```php
ProposalsController::create() // Generate from activities
ProposalsController::supervise($proposalId) // Supervisor approval
ProposalsController::rate($proposalId) // M&E rating
ProposalsController::updateStatus($proposalId, $status)
```

### User Management Functions
```php
UsersController::create() // with email notifications
UsersController::toggleStatus($userId) // with email alerts
UsersController::resetPassword($userId) // temporary password generation
UserModel::authenticate($email, $password)
UserModel::getSubordinates($userId) // hierarchical structure
```

### Document Management Functions
```php
DocumentsController::uploadFile($folderId)
DocumentsController::createFolder($parentId)
DocumentsController::viewFile($documentId) // with preview
FolderModel::getFolderHierarchy($folderId)
```

### Reporting Functions
```php
WorkplanReportsController::index() // with filtering
NASPReportsController::index() // hierarchical reports
MTDReportsController::index() // comprehensive analytics
ActivityMapsReportsController::index() // GPS mapping
CommodityReportsController::index() // production analytics
```

### Email Notification Functions
```php
sendActivityCreationNotification($workplanId, $activityId, $data)
sendActivityUpdateNotification($workplanId, $activityId, $data)
sendProposalStatusNotification($proposalId, $status)
sendUserStatusNotification($userId, $status)
sendPasswordResetNotification($userId, $tempPassword)
```

### File Management Functions
```php
handleFileUpload($file, $destination) // with 'public/' prefix
validateFileType($file, $allowedTypes)
generateFilePath($file, $folder)
deleteFile($filePath) // with database cleanup
```

### Data Validation Functions
```php
validateActivityData($data, $activityType)
validatePlanLinking($activityId, $planType, $planId)
validateUserPermissions($userId, $action, $resource)
validateFileUpload($file, $maxSize, $allowedTypes)
```