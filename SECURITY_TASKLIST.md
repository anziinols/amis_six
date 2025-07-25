# Security Vulnerability Task Checklist for AMIS Application

This checklist outlines security tasks to identify and fix vulnerabilities in the AMIS CodeIgniter 4 application based on the security analysis provided.

## 🔍 SQL Injection Prevention Tasks

- [ ] **Review all database queries** for raw SQL usage
- [ ] **Replace raw SQL queries** with CodeIgniter 4's Query Builder/Active Record
- [ ] **Enable query escaping** for all user inputs in database operations
- [ ] **Set `db_debug` to `FALSE`** in production configuration
- [ ] **Audit all form inputs** that interact with the database
- [ ] **Test SQL injection attempts** on vulnerable endpoints (safely)
- [ ] **Validate and sanitize** all user inputs before database queries
- [ ] **Check admin panel** for unescaped admin queries

## 🔍 Cross-Site Scripting (XSS) Prevention Tasks

- [ ] **Enable XSS filtering** on all form inputs and outputs
- [ ] **Use `esc()` function** for all user-generated content displayed in views
- [ ] **Sanitize inputs** with Security Helper for all form submissions
- [ ] **Review admin panel** for XSS vulnerabilities in user management
- [ ] **Check comments sections** for stored XSS possibilities
- [ ] **Implement context-sensitive escaping** for JavaScript outputs
- [ ] **Audit message display areas** for potential XSS vectors

## 🔍 Cross-Site Request Forgery (CSRF) Prevention Tasks

- [ ] **Enable CSRF protection** in `app/Config/Security.php` (`$csrfProtection = true`)
- [ ] **Verify CSRF tokens** on all POST/PUT/DELETE requests
- [ ] **Use CodeIgniter's form helper** for all forms
- [ ] **Check all admin operations** for CSRF token validation
- [ ] **Review user management forms** for missing CSRF tokens
- [ ] **Test CSRF attacks** on state-changing endpoints

## 🔍 File Upload Security Tasks

- [ ] **Review file upload configurations** for allowed file types
- [ ] **Implement strict MIME type validation** for uploads
- [ ] **Set file type restrictions** to block executable files (`.php`, `.js`, etc.)
- [ ] **Move upload directory** outside the webroot
- [ ] **Set upload directory permissions** to 775 (NOT 777)
- [ ] **Implement file size limits** for uploads
- [ ] **Check for malicious file detection** mechanisms
- [ ] **Test uploading dangerous files** to verify protections

## 🔍 Privilege Escalation Prevention Tasks

- [ ] **Update CodeIgniter** to latest stable version (CVE-2020-10793 patch)
- [ ] **Review user role management** functionality
- [ ] **Implement proper authentication/authorization** using CodeIgniter Shield
- [ ] **Check for privilege escalation** in role selection pages
- [ ] **Audit admin user creation** processes
- [ ] **Implement principle of least privilege** across all user roles
- [ ] **Review email-based user role assignment** for vulnerabilities

## 🔍 Server-Side Request Forgery (SSRF) Prevention Tasks

- [ ] **Review all URL fetching functionality** in the application
- [ ] **Validate and sanitize** all user-supplied URLs
- [ ] **Implement domain allowlist** for external resource access
- [ ] **Audit API integrations** for SSRF vulnerabilities
- [ ] **Check URL validation** in forms that accept web addresses

## 🔍 File Permissions and Configuration Security Tasks

- [ ] **Set `index.php` permissions** to 644 (readable by owner/group only)
- [ ] **Protect `.env` file** with proper `.htaccess` restrictions
- [ ] **Move sensitive configuration files** outside the webroot
- [ ] **Set production environment** (`ENVIRONMENT = 'production'`)
- [ ] **Disable error reporting** in production
- [ ] **Secure sensitive directory permissions** across the application
- [ ] **Audit shared hosting configurations** for security risks

## 🔍 Insecure Deserialization Prevention Tasks

- [ ] **Review all serialization/deserialization code** for untrusted data
- [ ] **Use JSON instead** of PHP serialization for user data
- [ ] **Implement input validation** for all deserialized data
- [ ] **Avoid deserializing** data from untrusted sources

## 🔍 General Security Hardening Tasks

### Code Audit and Maintenance
- [ ] **Update CodeIgniter framework** to the latest stable version
- [ ] **Perform comprehensive security audit** using security tools
- [ ] **Scan codebase** for dangerous functions like `base64_decode`, `eval`, `exec`
- [ ] **Review error handling** to prevent information disclosure
- [ ] **Test application** with penetration testing tools

### Monitoring and Prevention
- [ ] **Install ModSecurity** or web application firewall
- [ ] **Set up security monitoring** for unusual activities
- [ ] **Implement logging** for security-related events
- [ ] **Configure automatic security scanning** tools
- [ ] **Set up intrusion detection** mechanisms

### Development Practices
- [ ] **Follow OWASP guidelines** for secure development
- [ ] **Train development team** on security best practices
- [ ] **Establish security review process** for new features
- [ ] **Create security testing** procedures

## 📋 Usage Instructions

1. **Start with Critical Items**: Focus on SQL injection, XSS, and CSRF bulletproofing first
2. **Test Changes**: After implementing each fix, test the vulnerability with safe penetration testing
3. **Document Findings**: Keep records of vulnerabilities found and fixes applied
4. **Regular Updates**: Schedule weekly security reviews using this checklist
5. **Team Review**: Have security reviews done by multiple developers

## 🎯 Quick Start Priority Matrix

**High Priority (Week 1)**:
- SQL injection fixes
- XSS prevention
- CSRF protection
- File upload security

**Medium Priority (Week 2)**:
- Privilege escalation prevention
- File permissions audit
- Framework update

**Low Priority (Week 3-4)**:
- SSRF protection
- Deserialization hardening
- General monitoring setup

## 🔐 Security Testing Commands

After implementing fixes, run these tests:

```bash
# SQL Injection test
# Check for safe query patterns in codebase

# XSS test with payload: <script>alert('XSS')</script>
# Test on input fields and comment sections

# CSRF test: Attempt state changes without tokens
# Use browser developer tools to modify requests

# File upload test: Try uploading malicious PHP files
# Verify restrictions prevent execution
```

## 🚨 Emergency Response

If vulnerabilities are discovered:
1. **Immediate Isolation**: Take affected systems offline
2. **Backup Verification**: Ensure clean backups exist
3. **Vulnerability Assessment**: Use this checklist to identify extent
4. **Apply Patches**: Implement fixes from this list
5. **Monitor**: Watch for follow-up attacks

Last updated: [Current Date] - Review and update this checklist monthly