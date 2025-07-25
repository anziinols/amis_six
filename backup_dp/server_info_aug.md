# AMIS Five - Server Hosting Specifications

## Table of Contents
1. [Server Requirements](#server-requirements)
2. [Software Stack](#software-stack)
3. [Database Configuration](#database-configuration)
4. [Web Server Configuration](#web-server-configuration)
5. [PHP Configuration](#php-configuration)
6. [Security Requirements](#security-requirements)
7. [File System & Permissions](#file-system--permissions)
8. [Email Configuration](#email-configuration)
9. [Performance & Scalability](#performance--scalability)
10. [Backup & Monitoring](#backup--monitoring)

---

## Server Requirements

### Minimum Hardware Specifications
- **CPU**: 2 cores, 2.4 GHz minimum
- **RAM**: 4 GB minimum (8 GB recommended)
- **Storage**: 50 GB SSD minimum (100 GB recommended)
- **Network**: 100 Mbps connection minimum

### Recommended Hardware Specifications
- **CPU**: 4 cores, 3.0 GHz or higher
- **RAM**: 8 GB minimum (16 GB for high traffic)
- **Storage**: 100 GB SSD with backup storage
- **Network**: 1 Gbps connection

### Operating System
- **Linux**: Ubuntu 20.04 LTS or higher, CentOS 8+, RHEL 8+
- **Windows**: Windows Server 2019 or higher
- **Alternative**: Any modern Linux distribution with long-term support

---

## Software Stack

### Core Requirements
```
Web Server: Apache 2.4+ (with mod_rewrite enabled)
PHP: 8.1+ (Required minimum version)
Database: MySQL 8.0+ or MariaDB 10.6+
Composer: Latest version for dependency management
```

### PHP Version & Extensions
**Required PHP Version**: 8.1 or higher

**Required PHP Extensions**:
- `intl` (Internationalization support)
- `mbstring` (Multi-byte string handling)
- `json` (JSON support - enabled by default)
- `mysqlnd` (MySQL Native Driver)
- `libcurl` (cURL support for HTTP requests)
- `gd` or `imagick` (Image processing)
- `zip` (Archive handling)
- `xml` (XML processing)
- `openssl` (SSL/TLS support)

**Optional but Recommended**:
- `opcache` (Performance optimization)
- `redis` (Caching - if using Redis)
- `memcached` (Caching - if using Memcached)

---

## Database Configuration

### MySQL/MariaDB Setup
```sql
Database Name: amis_db
Character Set: utf8mb4
Collation: utf8mb4_general_ci
Engine: InnoDB (default)
```

### Database User Permissions
```sql
-- Create dedicated database user
CREATE USER 'amis_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER 
ON amis_db.* TO 'amis_user'@'localhost';
FLUSH PRIVILEGES;
```

### Database Configuration Settings
```ini
# MySQL Configuration (my.cnf)
[mysqld]
max_allowed_packet = 64M
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
query_cache_size = 128M
max_connections = 200
```

---

## Web Server Configuration

### Apache Requirements
**Required Apache Modules**:
- `mod_rewrite` (URL rewriting - CRITICAL)
- `mod_ssl` (HTTPS support)
- `mod_headers` (HTTP headers manipulation)
- `mod_expires` (Cache control)
- `mod_deflate` (Compression)

### Apache Virtual Host Configuration
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/amis_five/public
    
    # Redirect HTTP to HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>

<VirtualHost *:443>
    ServerName yourdomain.com
    DocumentRoot /var/www/amis_five/public
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    # Security Headers
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    
    # Directory Configuration
    <Directory /var/www/amis_five/public>
        AllowOverride All
        Require all granted
        Options -Indexes
    </Directory>
    
    # Protect sensitive directories
    <Directory /var/www/amis_five/app>
        Require all denied
    </Directory>
    
    <Directory /var/www/amis_five/writable>
        Require all denied
    </Directory>
</VirtualHost>
```

---

## PHP Configuration

### Critical PHP Settings
```ini
# php.ini Configuration
memory_limit = 256M
max_execution_time = 300
max_input_time = 300
post_max_size = 64M
upload_max_filesize = 32M
max_file_uploads = 20

# Session Configuration
session.save_handler = files
session.save_path = "/var/lib/php/sessions"
session.gc_maxlifetime = 7200
session.cookie_secure = 1
session.cookie_httponly = 1
session.use_strict_mode = 1

# Security Settings
expose_php = Off
display_errors = Off
log_errors = On
error_log = /var/log/php/error.log

# OPcache (Recommended)
opcache.enable = 1
opcache.memory_consumption = 128
opcache.max_accelerated_files = 4000
opcache.revalidate_freq = 60
```

---

## Security Requirements

### SSL/TLS Configuration
- **SSL Certificate**: Valid SSL certificate (Let's Encrypt recommended)
- **TLS Version**: TLS 1.2 minimum, TLS 1.3 preferred
- **HTTPS**: Force HTTPS for all connections

### Firewall Configuration
```bash
# UFW Firewall Rules (Ubuntu)
ufw allow 22/tcp    # SSH
ufw allow 80/tcp    # HTTP (redirect to HTTPS)
ufw allow 443/tcp   # HTTPS
ufw allow 3306/tcp from localhost  # MySQL (local only)
ufw enable
```

### File Upload Security
- **Upload Directory**: `public/uploads/` with restricted access
- **File Types**: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, JPEG, PNG, GIF, SVG
- **File Size Limit**: 32MB maximum per file
- **Security**: `.htaccess` protection against script execution

---

## File System & Permissions

### Directory Structure & Permissions
```bash
# Application root
/var/www/amis_five/                 # 755
├── app/                           # 755 (protected by .htaccess)
├── public/                        # 755
│   ├── uploads/                   # 755
│   └── assets/                    # 755
├── writable/                      # 755 (protected by .htaccess)
│   ├── cache/                     # 755
│   ├── logs/                      # 755
│   └── session/                   # 755
└── vendor/                        # 755
```

### Required Writable Directories
```bash
# Set proper permissions
chown -R www-data:www-data /var/www/amis_five/
chmod -R 755 /var/www/amis_five/
chmod -R 755 /var/www/amis_five/writable/
chmod -R 755 /var/www/amis_five/public/uploads/
```

---

## Email Configuration

### SMTP Server Requirements
```
SMTP Server: mail.dakoiims.com
Port: 465 (SSL)
Authentication: Required
From Address: test-email@dakoiims.com
```

### Email Server Setup (Alternative)
If using different email provider:
```
Supported Protocols: SMTP, Sendmail
Encryption: SSL/TLS required
Authentication: Username/Password
```

---

## Performance & Scalability

### Caching Configuration
```
File-based Caching: writable/cache/
Session Storage: File-based (writable/session/)
Database Connection: MySQLi with persistent connections
```

### Performance Optimizations
- **OPcache**: Enable PHP OPcache
- **Gzip Compression**: Enable mod_deflate
- **Browser Caching**: Configure expires headers
- **Database Indexing**: Ensure proper indexes on foreign keys

### Monitoring Requirements
- **Log Files**: Monitor Apache error logs, PHP error logs
- **Database**: Monitor MySQL slow query log
- **Disk Space**: Monitor upload directory growth
- **Memory Usage**: Monitor PHP memory consumption

---

## Backup & Monitoring

### Backup Strategy
```bash
# Database Backup (Daily)
mysqldump -u amis_user -p amis_db > /backup/amis_db_$(date +%Y%m%d).sql

# File Backup (Daily)
tar -czf /backup/amis_files_$(date +%Y%m%d).tar.gz /var/www/amis_five/

# Upload Directory Backup (Weekly)
rsync -av /var/www/amis_five/public/uploads/ /backup/uploads/
```

### Log Monitoring
- **Apache Access/Error Logs**: `/var/log/apache2/`
- **PHP Error Logs**: `/var/log/php/`
- **Application Logs**: `/var/www/amis_five/writable/logs/`
- **MySQL Logs**: `/var/log/mysql/`

---

## Deployment Checklist

### Pre-Deployment
- [ ] Server meets minimum requirements
- [ ] PHP 8.1+ installed with required extensions
- [ ] Apache with mod_rewrite enabled
- [ ] MySQL 8.0+ configured
- [ ] SSL certificate installed
- [ ] Firewall configured

### Post-Deployment
- [ ] Database imported and configured
- [ ] File permissions set correctly
- [ ] Email configuration tested
- [ ] HTTPS redirection working
- [ ] File uploads functional
- [ ] Backup scripts configured
- [ ] Monitoring tools setup

---

**Document Version**: 1.0  
**Created**: July 2025  
**For**: AMIS Five Production Deployment  
**Contact**: AMIS Development Team
