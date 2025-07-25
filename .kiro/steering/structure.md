# Project Structure & Conventions

## Directory Organization

### Core Application Structure
```
app/
├── Controllers/           # Main controllers
│   └── Admin/            # Admin-specific controllers
├── Models/               # Data models
├── Views/                # View templates
│   └── templates/        # Shared templates (system_template.php)
├── Config/               # Configuration files
├── Database/             # Database migrations
├── Filters/              # Request filters
├── Helpers/              # Helper functions
├── Libraries/            # Custom libraries
└── Routes.php           # Route definitions
```

### Public Assets
```
public/
├── assets/              # CSS, JS, images
├── uploads/             # User uploaded files
└── index.php           # Application entry point
```

### Development Files
```
memory_bank/             # Project documentation and context
backup_dp/               # Database backups and documentation
tests/                   # PHPUnit tests
vendor/                  # Composer dependencies
```

## Naming Conventions

### Controllers
- **Location**: `app/Controllers/` or `app/Controllers/Admin/`
- **Pattern**: PascalCase with 'Controller' suffix
- **Example**: `WorkplanController.php`, `ActivitiesController.php`

### Models
- **Location**: `app/Models/`
- **Pattern**: PascalCase with 'Model' suffix
- **Example**: `WorkplanModel.php`, `UserModel.php`

### Views
- **Location**: `app/Views/`
- **Pattern**: `{module}_{action}.php`
- **Examples**: 
  - `workplan_index.php`
  - `activities_create.php`
  - `proposals_edit.php`

### Database Tables
- **Pattern**: snake_case
- **Examples**: `workplan_activities`, `gov_structure`, `mtdp_strategies`

### CSS Classes
- **Status badges**: `status-{status_name}`
- **Custom styles**: kebab-case

## File Organization Patterns

### Controllers
- RESTful methods (index, show, create, store, edit, update, delete)
- Separate GET/POST methods
- Standard CRUD operations
- Admin controllers in separate namespace

### Models
- Extend CodeIgniter 4 Model class
- Include validation rules
- Relationship methods for associated data
- Soft deletes using `deleted_at` timestamps

### Views
- One folder per module
- Consistent template usage (`system_template.php`)
- Bootstrap 5 styling throughout
- Counter columns (#) instead of ID display in tables

### Routes
- RESTful routing patterns
- Resource controllers
- Admin route groups
- Clean URL structure

## Development Guidelines

### Database Operations
- Use standard CodeIgniter 4 CRUD operations
- Model methods for related data
- **CRITICAL**: Do not modify existing database structure
- Use existing models and follow established patterns

### UI Consistency
- Maintain Bootstrap 5 design system
- Follow established naming conventions
- Use existing UI patterns and components
- Dark text on light backgrounds for status badges

### Form Handling
- Standard CodeIgniter form submission (avoid AJAX unless specified)
- CodeIgniter form helpers with Bootstrap styling
- Simple validation functions preferred
- Frontend validation for dates and basic fields

### File Management
- Store uploads in `public/uploads/` folder
- Always include 'public/' prefix for database file paths
- Support images, documents, signing sheets, user photos

### Code Style
- Simple, straightforward CodeIgniter 4 patterns
- Minimal complexity in validation rules
- Follow existing architectural decisions
- Test in XAMPP environment