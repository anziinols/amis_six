Common hacking techniques targeting CodeIgniter 4 applications typically exploit poor coding practices, misconfigurations, or unpatched vulnerabilities. Below is a concise overview of prevalent attack vectors and how they relate to CodeIgniter 4, based on general web application security principles and specific references to the framework:

1. **SQL Injection**:
   - **Description**: Attackers inject malicious SQL queries through user inputs (e.g., forms, URL parameters) to manipulate or extract data from the database.
   - **CodeIgniter 4 Context**: CodeIgniter 4’s Query Builder and Active Record features automatically escape queries, reducing SQL injection risks. However, vulnerabilities arise if developers use raw SQL queries without proper sanitization or fail to validate inputs.
   - **Example**: An attacker might exploit an unescaped query like `SELECT * FROM users WHERE username = '$input'` by injecting `' OR '1'='1` to bypass authentication.
   - **Prevention**: Use CodeIgniter’s Query Builder, validate inputs, and avoid raw SQL unless necessary. Set `db_debug` to `FALSE` in production to prevent error leaks.[](https://www.getastra.com/blog/911/laravel-codeigniter-hacked-find-and-fix/)[](https://codeigniter.com/userguide3/general/security.html)

2. **Cross-Site Scripting (XSS)**:
   - **Description**: Malicious JavaScript is injected into web pages, executed in users’ browsers to steal cookies, session data, or deface pages.
   - **CodeIgniter 4 Context**: CodeIgniter 4 includes a built-in XSS filter (`xss_clean()` or context-sensitive escaping) to sanitize output. However, improper handling of user inputs or bypassing the filter can lead to reflected or stored XSS attacks.
   - **Example**: Submitting `<script>alert('Hacked');</script>` in a form that isn’t filtered can trigger malicious scripts on the admin panel.[](https://medium.com/retedys/security-standards-in-codeigniter-3c02585de17d)
   - **Prevention**: Enable XSS filtering on output, use `esc()` for context-sensitive escaping, and sanitize inputs with the Security Helper. Avoid unfiltered JavaScript in views.[](https://codeigniter.com/userguide3/general/security.html)[](https://codeigniter.com/)

3. **Cross-Site Request Forgery (CSRF)**:
   - **Description**: Attackers trick users into performing unintended actions (e.g., deleting data) by forging requests, often via malicious links.
   - **CodeIgniter 4 Context**: CodeIgniter 4 has built-in CSRF protection, enabled by default in forms. However, disabling CSRF or misconfiguring it can expose applications.
   - **Example**: A malicious link could trigger a POST request to delete a user account if CSRF tokens are not validated.
   - **Prevention**: Ensure CSRF protection is enabled (`$csrfProtection` in `app/Config/Security.php`), use CodeIgniter’s form helper, and validate tokens on every state-changing request.[](https://codeigniter.com/)[](https://codeigniter4.github.io/CodeIgniter4/concepts/security.html)

4. **File Upload Vulnerabilities**:
   - **Description**: Attackers upload malicious files (e.g., PHP scripts) through upload forms, potentially executing code on the server.
   - **CodeIgniter 4 Context**: The Upload library requires explicit file type restrictions. Without proper validation, attackers can upload executable scripts, leading to Remote Code Execution (RCE).
   - **Example**: A misconfigured upload form allowing `*.php` files could let an attacker upload a shell script.[](https://code.tutsplus.com/6-codeigniter-hacks-for-the-masters--net-8308t)[](https://stackoverflow.com/questions/12264125/codeigniter-application-getting-hacked-code-injected-in-index-php)
   - **Prevention**: Restrict allowed file types, validate MIME types, store uploads outside the webroot, and use CodeIgniter’s File Upload class with strict settings. Set permissions to 775 for upload directories instead of 777.[](https://stackoverflow.com/questions/12264125/codeigniter-application-getting-hacked-code-injected-in-index-php)

5. **Privilege Escalation**:
   - **Description**: Attackers exploit weak access controls to gain unauthorized permissions, such as admin access.
   - **CodeIgniter 4 Context**: A known vulnerability (CVE-2020-10793) in CodeIgniter 4.0.0 allowed privilege escalation via a modified Email ID on the “Select Role of the User” page. This was patched in later versions.
   - **Prevention**: Enforce the principle of least privilege, use CodeIgniter Shield for robust authentication/authorization, and keep the framework updated.[](https://www.getastra.com/blog/911/laravel-codeigniter-hacked-find-and-fix/)[](https://codeigniter4.github.io/userguide/concepts/security.html)

6. **Server-Side Request Forgery (SSRF)**:
   - **Description**: Attackers manipulate the application to make unauthorized requests to internal or external resources.
   - **CodeIgniter 4 Context**: SSRF risks increase if applications fetch user-supplied URLs without validation, especially in APIs or integrations.
   - **Prevention**: Validate and sanitize URLs, use allowlists for permitted domains, and implement defense-in-depth controls as per OWASP recommendations.[](https://codeigniter4.github.io/userguide/concepts/security.html)

7. **Insecure File Permissions and Misconfigurations**:
   - **Description**: Improper file permissions or exposed configuration files (e.g., `.env`) allow attackers to access sensitive data or execute code.
   - **CodeIgniter 4 Context**: Exposing `.env` or leaving `index.php` writable (e.g., CHMOD 777) can lead to code injection or data leaks. Shared hosting environments are particularly vulnerable.
   - **Example**: Attackers overwrote `index.php` with malicious code on a CodeIgniter 2.0.2 site due to lax permissions.[](https://stackoverflow.com/questions/12264125/codeigniter-application-getting-hacked-code-injected-in-index-php)[](https://stackoverflow.com/questions/18673425/codeigniter-2-1-4-application-got-hacked)
   - **Prevention**: Set `index.php` to 644, protect `.env` with `.htaccess`, and move sensitive files outside the webroot. Use production mode (`ENVIRONMENT = 'production'`) to disable error reporting.[](https://codeigniter.com/userguide3/general/security.html)[](https://codeigniter4.github.io/userguide/installation/running.html)

8. **Insecure Deserialization**:
   - **Description**: Attackers exploit untrusted data deserialization to execute code or manipulate application logic.
   - **CodeIgniter 4 Context**: While not specific to CodeIgniter, deserialization vulnerabilities can occur if developers use custom serialization logic or third-party libraries improperly.
   - **Prevention**: Avoid deserializing untrusted data, use safe formats (e.g., JSON), and validate inputs strictly.

**General Mitigation Strategies**:
- **Keep CodeIgniter Updated**: Regularly patch to address vulnerabilities like CVE-2020-10793.[](https://www.getastra.com/blog/911/laravel-codeigniter-hacked-find-and-fix/)
- **Secure Development Practices**: Follow OWASP guidelines, validate/sanitize all inputs, and use CodeIgniter’s security features (e.g., Security Helper, Shield).[](https://codeigniter4.github.io/userguide/concepts/security.html)[](https://codeigniter4.github.io/CodeIgniter4/concepts/security.html)
- **Use Firewalls and Monitoring**: Implement tools like ModSecurity or Astra Security to detect and block attacks.[](https://www.getastra.com/blog/911/laravel-codeigniter-hacked-find-and-fix/)[](https://stackoverflow.com/questions/12264125/codeigniter-application-getting-hacked-code-injected-in-index-php)
- **Audit Code**: Regularly check for backdoors (e.g., `base64_decode`, `eval`) and insecure functions in code.[](https://stackoverflow.com/questions/12264125/codeigniter-application-getting-hacked-code-injected-in-index-php)
- **Secure Hosting**: Avoid shared hosting misconfigurations, use proper file permissions, and restrict access to non-public files.[](https://stackoverflow.com/questions/18673425/codeigniter-2-1-4-application-got-hacked)[](https://codeigniter4.github.io/userguide/installation/running.html)

**Note**: CodeIgniter 4 incorporates robust security features, but vulnerabilities often stem from developer errors or outdated setups. Always test and audit applications thoroughly. If you need specific code examples or mitigation steps, let me know!