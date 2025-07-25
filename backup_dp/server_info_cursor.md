# AMIS Five System - Server Hosting Specifications

**Document:** Server Infrastructure Requirements  
**System:** Agricultural Management Information System (AMIS Five)  
**Framework:** CodeIgniter 4  
**Date:** January 2025  
**Version:** 1.0  

---

## 🏗️ System Overview

The AMIS Five system is a comprehensive Agricultural Management Information System built on CodeIgniter 4 framework. It manages workplans, activities, reports, documents, user management, and commodity tracking for agricultural planning and implementation.

---

## 🖥️ Core Server Requirements

### **Operating System**
- **Recommended:** Ubuntu 20.04 LTS / Ubuntu 22.04 LTS
- **Alternative:** CentOS 8+ / Rocky Linux 8+
- **Windows:** Windows Server 2019/2022 (if required)
- **Architecture:** x64 (64-bit)

### **Web Server**
- **Primary:** Apache 2.4+
  - **Required Modules:**
    - mod_rewrite (URL rewriting)
    - mod_ssl (HTTPS support)
    - mod_headers (Security headers)
    - mod_expires (Cache control)
- **Alternative:** Nginx 1.18+ (with PHP-FPM)

### **PHP Requirements**
- **Version:** PHP 8.1+ (minimum requirement from composer.json)
- **Recommended:** PHP 8.2 or PHP 8.3
- **Required Extensions:**
  ```bash
  php-cli
  php-fpm (if using Nginx)
  php-mysql (MySQLi driver)
  php-mbstring (Multibyte string support)
  php-xml (XML processing)
  php-zip (Composer dependency)
  php-curl (HTTP requests)
  php-gd (Image processing)
  php-json (JSON handling)
  php-fileinfo (File type detection)
  php-openssl (Encryption/HTTPS)
  php-intl (Internationalization)
  ```

### **Database Server**
- **Database:** MySQL 5.7+ / MySQL 8.0+ / MariaDB 10.3+
- **Database Name:** `amis_db`
- **Character Set:** UTF-8 (utf8_general_ci)
- **Required Features:**
  - InnoDB storage engine
  - ACID compliance
  - Foreign key constraints
  - Full-text search capabilities

---

## 💾 Hardware Specifications

### **Minimum Requirements (Development/Small Scale)**
- **CPU:** 2 vCPU cores (2.0 GHz)
- **RAM:** 4 GB
- **Storage:** 40 GB SSD
- **Network:** 100 Mbps

### **Recommended Production Requirements**
- **CPU:** 4 vCPU cores (2.4 GHz+)
- **RAM:** 8 GB
- **Storage:** 100 GB SSD (with expansion capability)
- **Network:** 1 Gbps
- **Backup Storage:** Additional 100 GB for backups

### **High-Load Production Environment**
- **CPU:** 8+ vCPU cores (3.0 GHz+)
- **RAM:** 16 GB+
- **Storage:** 200 GB+ NVMe SSD
- **Network:** 10 Gbps
- **Load Balancer:** If scaling horizontally

---

## 📁 File System & Storage Requirements

### **Directory Structure**
```
/var/www/amis_five/
├── app/ (protected, outside webroot)
├── public/ (webroot)
│   ├── uploads/ (file storage)
│   ├── assets/ (CSS, JS, images)
│   └── index.php (entry point)
├── writable/ (cache, logs, sessions)
└── vendor/ (Composer dependencies)
```

### **File Upload Storage**
- **Location:** `public/uploads/` (currently in webroot)
- **Security Note:** Consider moving outside webroot for enhanced security
- **Upload Categories:**
  ```
  uploads/
  ├── activities/          (Activity photos/documents)
  ├── agreements_attachments/  (Agreement files)
  ├── commodities/icons/   (Commodity icons)
  ├── documents/          (Document management files)
  ├── infrastructure/     (Infrastructure photos)
  ├── inputs/            (Input photos)
  ├── meeting_attachments/ (Meeting files)
  ├── profile/           (User profile photos)
  ├── signing_sheets/    (Signed documents)
  └── training/          (Training materials)
  ```

### **File Permissions**
- **Web Files:** 644 (readable by owner/group)
- **Directories:** 755 (secure permissions)
- **Upload Directories:** 755 (NOT 777 for security)
- **Writable Directory:** 755 with web server write access
- **Configuration Files:** 600 (owner read-only)

### **Storage Capacity Planning**
- **Initial:** 20 GB for uploads
- **Growth:** 5-10 GB per year (estimate based on usage)
- **Database:** 2-5 GB (depending on data volume)
- **Logs:** 1-2 GB (with log rotation)

---

## ⚙️ PHP Configuration

### **Required PHP Settings**
```ini
; Memory and Execution
memory_limit = 256M
max_execution_time = 300
max_input_time = 300

; File Uploads (Critical for AMIS functionality)
file_uploads = On
upload_max_filesize = 10M
post_max_size = 12M
max_file_uploads = 20

; Session Management
session.save_handler = files
session.gc_maxlifetime = 7200
session.cookie_httponly = 1
session.cookie_secure = 1 (if HTTPS)

; Security
expose_php = Off
allow_url_fopen = Off
allow_url_include = Off

; Error Reporting (Production)
display_errors = Off
log_errors = On
error_log = /var/log/php/error.log

; Date/Time
date.timezone = Pacific/Port_Moresby
```

### **Security Hardening**
```ini
; Disable dangerous functions
disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source

; Hide PHP version
expose_php = Off

; Limit script execution
max_execution_time = 300
max_input_time = 300
```

---

## 🗄️ Database Configuration

### **MySQL Settings**
```ini
[mysqld]
# Basic Settings
default-storage-engine = InnoDB
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci

# Performance Settings
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
query_cache_type = 1
query_cache_size = 64M

# Connection Settings
max_connections = 200
wait_timeout = 28800
interactive_timeout = 28800

# Security Settings
local_infile = 0
skip_show_database = 1
```

### **Database User Privileges**
```sql
-- Create dedicated database user
CREATE USER 'amis_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, INDEX, DROP 
ON amis_db.* TO 'amis_user'@'localhost';
FLUSH PRIVILEGES;
```

---

## 🔐 Security Requirements

### **SSL/HTTPS Configuration**
- **Certificate:** Valid SSL certificate (Let's Encrypt recommended)
- **Protocol:** TLS 1.2 minimum, TLS 1.3 preferred
- **Cipher Suites:** Strong encryption ciphers only
- **HSTS:** HTTP Strict Transport Security enabled

### **Firewall Configuration**
```bash
# Open required ports
22    - SSH (restrict to specific IPs)
80    - HTTP (redirect to HTTPS)
443   - HTTPS
3306  - MySQL (localhost only)

# Block all other ports
```

### **File Upload Security**
- **File Type Validation:** Whitelist approach (PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, JPG, JPEG, PNG)
- **File Size Limits:** 10MB maximum per file
- **MIME Type Validation:** Server-side validation required
- **Malware Scanning:** ClamAV or similar recommended
- **Upload Directory:** Move outside webroot if possible

### **Application Security Headers**
```apache
# Apache .htaccess security headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
Header always set Content-Security-Policy "default-src 'self'"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
```

---

## 📧 Email Configuration

### **SMTP Requirements**
- **SMTP Server:** Configured SMTP server (currently dakoiims.com)
- **Port:** 465 (SSL) or 587 (TLS)
- **Authentication:** SMTP authentication enabled
- **Encryption:** SSL/TLS encryption required

### **Email Functionality**
- User account notifications
- Workplan supervisor notifications
- Proposal status changes
- Activity submissions and ratings
- Password reset functionality

---

## 🔄 Backup & Maintenance

### **Backup Strategy**
```bash
# Database Backup (Daily)
mysqldump -u amis_user -p amis_db > /backup/amis_db_$(date +%Y%m%d).sql

# File Backup (Daily)
rsync -av /var/www/amis_five/public/uploads/ /backup/uploads/

# Full System Backup (Weekly)
tar -czf /backup/amis_full_$(date +%Y%m%d).tar.gz /var/www/amis_five/
```

### **Log Rotation**
```bash
# PHP Error Logs
/var/log/php/error.log {
    daily
    rotate 30
    compress
    delaycompress
    missingok
    notifempty
}

# Apache Logs
/var/log/apache2/*.log {
    daily
    rotate 30
    compress
    delaycompress
    missingok
    notifempty
}
```

### **Monitoring Requirements**
- **System Monitoring:** CPU, RAM, Disk usage
- **Application Monitoring:** Response times, error rates
- **Security Monitoring:** Failed login attempts, file upload anomalies
- **Database Monitoring:** Query performance, connection counts

---

## 📦 Deployment Dependencies

### **Composer Dependencies**
```json
{
    "require": {
        "php": "^8.1",
        "codeigniter4/framework": "^4.0",
        "tecnickcom/tcpdf": "^6.10"
    },
    "require-dev": {
        "fakerphp/faker": "^1.9",
        "mikey179/vfsstream": "^1.6",
        "phpunit/phpunit": "^10.5.16"
    }
}
```

### **System Dependencies**
```bash
# Ubuntu/Debian
apt-get install apache2 mysql-server php8.1 php8.1-mysql php8.1-cli
apt-get install php8.1-mbstring php8.1-xml php8.1-zip php8.1-curl
apt-get install php8.1-gd php8.1-json php8.1-fileinfo composer

# CentOS/RHEL
yum install httpd mysql-server php81 php81-php-mysql php81-php-cli
yum install php81-php-mbstring php81-php-xml php81-php-zip composer
```

---

## 🚀 Performance Optimization

### **Caching Strategy**
- **Application Cache:** File-based caching (CodeIgniter default)
- **Database Cache:** Query result caching
- **Static Assets:** Browser caching with appropriate headers
- **CDN:** Consider CDN for static assets in high-traffic scenarios

### **PHP OpCode Caching**
```ini
; Enable OpCache
opcache.enable = 1
opcache.memory_consumption = 128
opcache.interned_strings_buffer = 8
opcache.max_accelerated_files = 4000
opcache.revalidate_freq = 60
```

### **Database Optimization**
- Regular table optimization
- Index optimization based on query patterns
- Connection pooling for high-load scenarios
- Read replicas for scaling (if needed)

---

## 🌐 Network & DNS Requirements

### **Domain Configuration**
- **Domain:** Dedicated domain or subdomain
- **DNS Records:** A record pointing to server IP
- **Subdomain:** Consider separate subdomains for different environments

### **Load Balancing (If Required)**
- **Load Balancer:** Nginx or HAProxy for multiple servers
- **Session Affinity:** Sticky sessions or shared session storage
- **Health Checks:** Application health monitoring

---

## 📋 Deployment Checklist

### **Pre-Deployment**
- [ ] Server provisioning with required specifications
- [ ] Operating system installation and hardening
- [ ] Web server (Apache/Nginx) installation and configuration
- [ ] PHP installation with required extensions
- [ ] MySQL/MariaDB installation and configuration
- [ ] SSL certificate procurement and installation
- [ ] Firewall configuration

### **Application Deployment**
- [ ] Code deployment to `/var/www/amis_five/`
- [ ] Composer dependency installation
- [ ] Database creation and migration
- [ ] Environment configuration (`.env` file)
- [ ] File permissions configuration
- [ ] Web server virtual host configuration
- [ ] Upload directory creation and permissions

### **Security Configuration**
- [ ] File upload restrictions implementation
- [ ] Security headers configuration
- [ ] Database user creation with minimal privileges
- [ ] HTTPS enforcement
- [ ] Error logging configuration
- [ ] Backup system setup

### **Post-Deployment Testing**
- [ ] Application functionality testing
- [ ] File upload testing
- [ ] Email functionality testing
- [ ] Database connectivity testing
- [ ] Security testing (file upload, CSRF, XSS)
- [ ] Performance testing
- [ ] Backup and restore testing

---

## 📞 Support & Maintenance

### **Regular Maintenance Tasks**
- **Security Updates:** Monthly OS and application updates
- **Database Maintenance:** Weekly optimization and cleanup
- **Log Review:** Weekly log analysis
- **Backup Verification:** Monthly backup restore testing
- **Performance Monitoring:** Continuous monitoring setup

### **Support Requirements**
- **System Administrator:** For server maintenance and security
- **Database Administrator:** For database optimization and backup
- **Application Developer:** For application updates and bug fixes
- **Security Specialist:** For security audits and compliance

---

## 🎯 Production vs Development Environments

### **Development Environment**
- Lower resource requirements
- Error display enabled
- Debug mode enabled
- Sample data for testing
- Relaxed security settings for development

### **Production Environment**
- Full resource allocation
- Error display disabled
- Debug mode disabled
- Production data
- Maximum security hardening
- Monitoring and alerting enabled

---

## 📊 Estimated Costs (Monthly)

### **Cloud Hosting (AWS/DigitalOcean)**
- **Small Scale:** $20-50/month (2-4 GB RAM, 2 vCPU)
- **Medium Scale:** $50-100/month (8 GB RAM, 4 vCPU)
- **Large Scale:** $100-200/month (16 GB RAM, 8 vCPU)

### **Additional Services**
- **SSL Certificate:** $0-100/year (Let's Encrypt free)
- **Backup Storage:** $5-20/month
- **Monitoring:** $10-30/month
- **CDN:** $5-50/month (if required)

---

**Document Version:** 1.0  
**Last Updated:** January 2025  
**Next Review:** Quarterly or when system requirements change

---

*This document should be reviewed and updated regularly to reflect changes in system requirements, security standards, and infrastructure needs.* 