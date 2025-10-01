# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

AMIS (Agricultural Management Information System) - a CodeIgniter 4 web application managing agricultural planning, activities, workplans, and reporting for commodity boards, SMEs, and government agencies.

## Critical Development Rules

**IMPORTANT - Database:**
- **Database modifications (CREATE, ALTER, DROP) are ONLY allowed when explicitly instructed by the user**
- **NEVER modify existing database structure or create migration files to change tables without explicit user instruction**
- When database conflicts occur, adapt Controllers, Views, and Models to match the existing database
- Use existing models only - do not create new models or tables without explicit permission
- When database changes are needed:
  - Document the required changes in `dev_guide/DB_tables_updates_01_10_2025.md`
  - Create SQL scripts for the user to review and execute manually
  - Wait for explicit user approval before proceeding with any database modifications

**Code Style:**
- Use standard CodeIgniter 4 RESTful approach - separate GET and PUT methods
- **DO NOT use AJAX form submissions** - use standard CodeIgniter 4 form submission
- Keep code simple and straightforward
- View file naming: prefix with folder name (e.g., `workplan_report/workplan_report_create.php`)

**File Modification Policy:**
- Focus changes on Models, Views, Controllers, and `app/Config/Routes.php`
- View other interface files to maintain consistency in design and layout
- Do not edit other files without explicit permission

## Development Environment

### Requirements
- PHP 8.1 or higher
- MySQL database
- Web server (Apache/Nginx) with document root pointing to `public/` folder
- Composer for dependency management

### Common Commands
```bash
# Setup
composer install
cp env .env                    # Configure database credentials and base URL
php spark migrate
chmod -R 755 public/uploads/

# Development
composer test                  # Run tests
php spark migrate              # Run new migrations
php spark migrate:rollback     # Rollback last batch
php spark migrate:status       # Check migration status
php spark cache:clear          # Clear caches

# Code generation (use sparingly per project rules)
php spark make:migration CreateTableName
php spark make:model ModelName
php spark make:controller ControllerName

# Running the application
# Web server must point document root to public/ folder
# Development: Use PHP built-in server or XAMPP/WAMP
# The application runs at the baseURL configured in .env
```

## Architecture Overview

### Core System Components

**Multi-Tenant Architecture:**
- Regular users (organization staff) with role-based permissions
- Dakoii super-admin users with cross-organization access
- Hierarchical government structure (Province → District → LLG → Ward)

**Main Functional Areas:**
1. **Planning Systems**: MTDP, NASP, Corporate Plans with hierarchical structures
2. **Workplan Management**: Activities linked to strategic plans
3. **Activities Implementation**: Detailed tracking of meetings, trainings, agreements, etc.
4. **Performance Management**: KRAs, indicators, and outputs tracking
5. **Reporting & Analytics**: Multi-dimensional reporting across all modules

### Database Design Patterns

**Planning Hierarchy Pattern:**
```
MTDP Plans → SPAs → DIPs → Specific Areas → Investments → KRAs → Strategies → Indicators
NASP Plans → APAs → DIPs → Specific Areas → Objectives → Outputs → Indicators
Corporate Plans → Objectives → KRAs → Strategies
```

**Activity Implementation Pattern:**
- Core `activities` table with type field
- Type-specific tables (e.g., `activities_meetings`, `activities_trainings`)
- JSON fields for complex data (participants, files, etc.)
- Junction tables for linking activities to plans

**Key Tables:**
- `activities` - Main activities registry
- `activities_*` - Type-specific implementation data (meetings, trainings, etc.)
- `workplan_activities` - Workplan items
- `workplan_output_activities` - Output-based activities
- `*_junction_*` - Linking tables between plans and activities

### Controller Architecture

**RESTful Route Patterns:**
- Admin controllers in `app/Controllers/Admin/` namespace
- Resource routes follow REST conventions (index, show, new, create, edit, update, delete)
- Nested resources for hierarchical data (e.g., `/admin/mtdp-plans/{id}/spas`)

**Key Controllers:**
- `ActivitiesController` - Main activities CRUD and implementation
- `WorkplanActivitiesController` - Workplan activity management
- `WorkplanPeriodController` - Performance period management
- `EvaluationController` - Activity evaluation and rating
- Admin controllers for all planning systems

### Model Patterns

**JSON Field Handling:**
Models use `beforeInsert/beforeUpdate` callbacks to encode arrays to JSON and `afterFind` callbacks to decode JSON back to arrays.

```php
protected $beforeInsert = ['encodeJsonFields'];
protected $afterFind = ['decodeJsonFields'];
```

**Common Model Features:**
- Soft deletes via status fields
- Created/updated by user tracking
- Validation rules for data integrity

### View Structure

**Layout Pattern:**
- Base layouts in `app/Views/layouts/`
- Feature-specific views organized by controller
- Reusable components for forms and data display

**Activity Implementation Views:**
- Form views: `app/Views/activities/implementations/{type}_implementation.php`
- Detail views: `app/Views/activities/implementation/{type}_details.php`
- Modular design allows easy addition of new activity types

## Key Development Patterns

### Activity Implementation System

When adding new activity types, ensure all three controller methods are updated:
1. `implement()` method - Load existing data for forms
2. `saveImplementation()` method - Process form submissions
3. `show()` method - Display implementation details

**Critical Pattern:**
```php
// In ActivitiesController
} elseif ($activity['type'] === 'new_type') {
    $implementationData = $this->activitiesNewTypeModel
        ->where('activity_id', $activity['id'])
        ->first();
}
```

### File Upload Handling
- Files stored in `public/uploads/{type}_files/` directories
- Metadata stored in JSON fields in database
- Security validation for file types and sizes

### Form Validation Patterns
- Use CodeIgniter validation rules
- `permit_empty` for optional fields
- File upload validation with size and type restrictions

### AJAX Integration
- **DO NOT use AJAX for form submissions** - use standard CodeIgniter form submission
- API routes in `/api` group for dynamic data loading only
- Common pattern for hierarchical dropdowns (Province → District → LLG → Ward)
- JSON responses for form population and dynamic data

## Testing Guidelines

### Manual Testing Checklist for New Features
1. Form functionality (display, validation, submission)
2. Data persistence (save, update, retrieve)
3. File uploads and downloads
4. User permissions and access control
5. Responsive design on mobile devices

### Common Test Scenarios
- Empty form submission
- Invalid data submission
- File upload edge cases
- User role permissions
- Data pre-population on edit forms

## Deployment Notes

### File Permissions
```bash
# Set correct permissions
chmod -R 755 public/uploads/
chmod -R 755 writable/
```

### Environment Configuration
- Set `CI_ENVIRONMENT` to 'production' for live deployment
- Configure proper database credentials in `.env`
- Set correct `app.baseURL` in environment config

### Security Considerations
- File upload validation and secure storage
- CSRF protection enabled by default
- SQL injection protection via Query Builder
- XSS protection via output escaping

## Common Issues & Solutions

### Time Field Issues
When saving datetime fields, ensure proper format conversion:
```php
$formattedTime = date('Y-m-d H:i:s', strtotime("$date $time"));
```

### JSON Field Problems
- Check field name consistency between database and code
- Verify model callbacks for encoding/decoding
- Ensure array data before JSON encoding

### File Upload Issues
- Verify directory exists and has write permissions
- Check file size limits in PHP configuration
- Validate file types for security

### Form Data Pre-population
- Ensure exact field name matches between form and database
- Convert datetime values to proper format for HTML inputs
- Handle empty/null values gracefully

## Development Best Practices

1. **Follow CodeIgniter conventions** - Use framework patterns for consistency
2. **Modular view design** - Separate complex views into smaller components
3. **JSON field validation** - Always validate JSON data before encoding
4. **File security** - Implement proper validation and storage for uploads
5. **User experience** - Pre-populate forms with existing data on edit
6. **Error handling** - Provide clear feedback for validation errors
7. **Database relationships** - Use junction tables for many-to-many relationships
8. **Simple CRUD operations** - Keep operations straightforward, no over-engineering

## Key File Locations

**Configuration:**
- `app/Config/Routes.php` - Route definitions
- `.env` - Environment configuration

**Core Models:**
- `app/Models/ActivitiesModel.php` - Main activities
- `app/Models/Activities*Model.php` - Activity type implementations
- `app/Models/Workplan*Model.php` - Workplan system models

**Critical Controllers:**
- `app/Controllers/ActivitiesController.php` - Activity management
- `app/Controllers/WorkplanPeriodController.php` - Performance periods
- `app/Controllers/EvaluationController.php` - Activity evaluation

**Database:**
- `app/Database/Migrations/` - Database schema definitions
- Migration files follow timestamp naming for proper ordering

**Uploads:**
- `public/uploads/` - File storage directory with subdirectories by type

## Reference Documentation

Development guides and feature documentation are available in:
- `dev_guide/` - Implementation plans and guides for activities features
- Root directory - Feature-specific markdown files (e.g., `EVALUATION_FEATURE.md`, `SUPERVISED_ACTIVITIES_FEATURE.md`)

Common activity implementation types:
- Meetings (`activities_meetings`)
- Trainings (`activities_training`)
- Agreements (`activities_agreements`)
- Infrastructure (`activities_infrastructure`)
- Inputs (`activities_inputs`)
- Outputs (`activities_outputs`)