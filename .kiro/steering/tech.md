# Technology Stack

## Framework & Backend
- **Framework**: CodeIgniter 4 (MVC architecture)
- **Language**: PHP 8.1+ (minimum requirement)
- **Database**: MySQL
- **Server**: Apache (XAMPP deployment environment)
- **PDF Generation**: TCPDF library

## Frontend Stack
- **CSS Framework**: Bootstrap 5.3.0
- **JavaScript Libraries**:
  - jQuery 3.6.0
  - DataTables 1.13.6 (for table management)
  - Select2 4.1.0 (enhanced dropdowns)
  - Toastr (notifications)
  - Chart.js (for reports and visualization)
- **Icons**: Font Awesome 6.4.0
- **Fonts**: Google Fonts - Inter (300,400,500,600,700)
- **Maps**: OpenStreetMap integration

## Development Tools
- **Dependency Management**: Composer
- **Testing**: PHPUnit 10.5.16+
- **Development Dependencies**: Faker, VfsStream

## Common Commands

### Setup & Installation
```bash
composer install
composer update
```

### Testing
```bash
composer test
# or
phpunit
```

### Development Server
- Use XAMPP for local development
- Point web server to `public/` directory (not project root)
- Copy `env` to `.env` and configure database settings

## Key Libraries & Dependencies
- **codeigniter4/framework**: Core framework
- **tecnickcom/tcpdf**: PDF generation for reports
- **fakerphp/faker**: Test data generation (dev)
- **mikey179/vfsstream**: Virtual filesystem for testing (dev)

## Architecture Notes
- **Entry Point**: `public/index.php` (security-focused structure)
- **Autoloading**: PSR-4 compliant with App\ and Config\ namespaces
- **Email System**: CodeIgniter built-in email with SMTP (port 465)
- **File Uploads**: Stored in `public/uploads/` with database path tracking
- **Session Management**: CodeIgniter session-based authentication