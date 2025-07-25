# 🔒 File Permissions and Configuration Security Audit Report

**Date:** 2025-07-17  
**System:** AMIS Five (CodeIgniter 4)  
**Location:** c:\xampp\htdocs\amis_five  
**Audit Type:** File Permissions and Configuration Security  

---

## 📋 Executive Summary

This security audit examined file permissions, configuration security, and environment settings for the AMIS Five application. **CRITICAL SECURITY VULNERABILITIES** were identified that require immediate attention before production deployment.

### 🚨 Critical Findings Summary
- **Environment set to DEVELOPMENT** in production-ready codebase
- **Overly permissive file permissions** on sensitive files
- **Unprotected .env file** accessible via web
- **Database credentials exposed** in configuration files
- **Error reporting enabled** revealing system information
- **Default security tokens** in use

---

## 🔍 Detailed Security Assessment

### 1. ❌ **CRITICAL: Environment Configuration**

**Current Status:** FAILED  
**Risk Level:** HIGH

```
File: .env (Line 17)
Current Setting: CI_ENVIRONMENT = development
Required Setting: CI_ENVIRONMENT = production
```

**Issues Identified:**
- Environment is set to 'development' which enables debug mode
- Error reporting is fully enabled (E_ALL with display_errors = '1')
- Debug backtraces are shown (SHOW_DEBUG_BACKTRACE = true)
- CI_DEBUG is enabled, exposing system internals

**Security Impact:**
- Detailed error messages expose file paths and system information
- Stack traces reveal application structure to attackers
- Debug information can be used for reconnaissance

### 2. ❌ **CRITICAL: File Permissions**

**Current Status:** FAILED  
**Risk Level:** HIGH

**index.php Permissions:**
```
BUILTIN\Administrators:(I)(F)
NT AUTHORITY\SYSTEM:(I)(F)
BUILTIN\Users:(I)(RX)
NT AUTHORITY\Authenticated Users:(I)(M)  ← SECURITY RISK
```

**Issues Identified:**
- Authenticated Users have Modify (M) permissions on index.php
- Should be Read/Execute only for web server user
- Overly permissive permissions allow unauthorized modifications

**Recommended Permissions:**
- Owner: Read/Write (644 equivalent)
- Group: Read only
- Others: Read only
- Web server: Read/Execute only

### 3. ❌ **CRITICAL: .env File Protection**

**Current Status:** FAILED  
**Risk Level:** CRITICAL

**Issues Identified:**
- .env file exists in web-accessible directory
- No .htaccess protection specifically for .env file
- Contains sensitive configuration data
- Default example configuration still present

**Current .env Content Analysis:**
- Environment setting exposed
- Database configuration templates visible
- Encryption key template present
- Session configuration exposed

**Required Protection:**
```apache
<Files ".env">
    Order allow,deny
    Deny from all
</Files>
```

### 4. ⚠️ **WARNING: Sensitive Configuration Files Location**

**Current Status:** PARTIAL RISK  
**Risk Level:** MEDIUM

**Files in Web-Accessible Location:**
- `app/Config/Database.php` - Contains database credentials
- `app/Config/Security.php` - Contains security settings
- `app/Config/App.php` - Contains application settings

**Issues Identified:**
- Configuration files are within web root
- Database.php contains hardcoded credentials:
  ```php
  'hostname' => 'localhost',
  'username' => 'root',
  'password' => '',  ← Empty password
  'database' => 'amis_db',
  ```

**Recommendation:** Move sensitive configs outside webroot

### 5. ❌ **CRITICAL: Error Reporting in Production**

**Current Status:** FAILED  
**Risk Level:** HIGH

**Development Boot Configuration:**
```php
error_reporting(E_ALL);
ini_set('display_errors', '1');
defined('SHOW_DEBUG_BACKTRACE') || define('SHOW_DEBUG_BACKTRACE', true);
defined('CI_DEBUG') || define('CI_DEBUG', true);
```

**Production Boot Configuration (Correct):**
```php
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', '0');
defined('CI_DEBUG') || define('CI_DEBUG', false);
```

### 6. ⚠️ **WARNING: Directory Permissions**

**Current Status:** NEEDS REVIEW  
**Risk Level:** MEDIUM

**Writable Directory Permissions:**
```
writable BUILTIN\Administrators:(I)(OI)(CI)(F)
         NT AUTHORITY\SYSTEM:(I)(OI)(CI)(F)
         BUILTIN\Users:(I)(OI)(CI)(RX)
         NT AUTHORITY\Authenticated Users:(I)(M)
```

**Config Directory Permissions:**
```
app\Config BUILTIN\Administrators:(I)(OI)(CI)(F)
           NT AUTHORITY\SYSTEM:(I)(OI)(CI)(F)
           BUILTIN\Users:(I)(OI)(CI)(RX)
           NT AUTHORITY\Authenticated Users:(I)(M)
```

### 7. ⚠️ **WARNING: Security Configuration**

**Current Status:** NEEDS IMPROVEMENT  
**Risk Level:** MEDIUM

**CSRF Protection Analysis:**
- CSRF protection enabled ✓
- Using cookie-based protection ✓
- Default token names in use ⚠️
- Token randomization disabled ⚠️

**Default Security Settings:**
```php
public string $tokenName = 'csrf_test_name';        // Should be randomized
public string $cookieName = 'csrf_cookie_name';     // Should be randomized
public bool $tokenRandomize = false;                // Should be true
```

### 8. ✅ **PASSED: .htaccess Configuration**

**Current Status:** GOOD  
**Risk Level:** LOW

**Positive Security Features:**
- Directory browsing disabled (`Options -Indexes`)
- Server signature disabled (`ServerSignature Off`)
- Proper URL rewriting configured
- Authorization header preservation enabled

---

## 🛠️ **IMMEDIATE ACTION REQUIRED**

### Priority 1 (Critical - Fix Immediately)

1. **Set Production Environment:**
   ```bash
   # In .env file
   CI_ENVIRONMENT = production
   ```

2. **Secure .env File:**
   ```apache
   # Add to .htaccess
   <Files ".env">
       Order allow,deny
       Deny from all
   </Files>
   ```

3. **Fix File Permissions:**
   ```bash
   # Set index.php to 644 equivalent
   icacls index.php /grant:r "IIS_IUSRS:(R)"
   icacls index.php /remove "NT AUTHORITY\Authenticated Users"
   ```

### Priority 2 (High - Fix Before Production)

4. **Move Sensitive Files Outside Webroot**
5. **Randomize Security Tokens**
6. **Implement Database Password**
7. **Review Directory Permissions**

### Priority 3 (Medium - Security Hardening)

8. **Implement Additional .htaccess Protections**
9. **Configure Security Headers**
10. **Audit Shared Hosting Configuration**

---

## 📊 **Security Score: 3/10 (CRITICAL)**

**Breakdown:**
- Environment Security: 1/10 (Critical vulnerabilities)
- File Permissions: 2/10 (Overly permissive)
- Configuration Security: 3/10 (Exposed credentials)
- Error Handling: 1/10 (Full disclosure enabled)
- Access Controls: 4/10 (Basic protections present)

---

## 🎯 **Compliance Status**

| Security Task | Status | Priority |
|---------------|--------|----------|
| Set index.php permissions to 644 | ❌ FAILED | Critical |
| Protect .env file with .htaccess | ❌ FAILED | Critical |
| Move sensitive configs outside webroot | ❌ FAILED | High |
| Set production environment | ❌ FAILED | Critical |
| Disable error reporting in production | ❌ FAILED | Critical |
| Secure sensitive directory permissions | ⚠️ PARTIAL | High |
| Audit shared hosting configurations | ⚠️ PENDING | Medium |

---

## 📝 **Recommendations for Production Deployment**

1. **Immediate Security Fixes Required Before Go-Live**
2. **Implement Comprehensive Security Review Process**
3. **Regular Security Audits and Monitoring**
4. **Staff Security Training on Configuration Management**
5. **Automated Security Scanning Integration**

---

**Report Generated By:** Augment Agent Security Audit  
**Next Review Date:** 30 days from implementation  
**Contact:** System Administrator for immediate action on critical findings
