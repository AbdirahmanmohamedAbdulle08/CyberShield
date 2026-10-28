# Kali Linux & VMware Defensive Security Testing Guide for CyberShield

**Audience:** Cybersecurity students, instructors, and security researchers at Hormuud University.  
**Scope:** Authorized defensive assessment of local CyberShield instance hosted on Windows XAMPP from a Kali Linux virtual machine inside VMware Workstation / Player.

---

## 1. Ethical Authorization & Rules of Engagement

1. **Authorization Prerequisite:** Testing must only be conducted against a locally owned and authorized instance of CyberShield. Never test systems without written authorization.
2. **Target Isolation:** Only test `http://<WINDOWS_HOST_IP>/cybershield/`. Never scan public IP addresses, third-party services, university production portals, or external networks.
3. **Safe Assessment Policy:** Do not perform Denial of Service (DoS) attacks, brute-force saturations that exhaust system memory, or introduce persistent malware.

---

## 2. VMware Virtual Networking Architecture

When running Kali Linux inside VMware on a Windows host, the two operating systems communicate across virtual network adapters.

### Network Modes:
- **Host-Only Networking (`vmnet1`):** Completely isolated from the physical local network and internet. Recommended for maximum lab containment.
- **NAT Mode (`vmnet8`):** Kali shares the Windows host's physical connection to reach the internet, but Windows and Kali can still communicate directly through virtual adapter gateways.

### Identifying the Windows Host IP Address:

> **CRITICAL CONCEPT:** Inside your Kali Linux terminal, typing `http://localhost/` refers to **Kali Linux itself (127.0.0.1)**, NOT your Windows host running XAMPP!

#### Step A: On the Windows Host (Command Prompt / PowerShell)
Run:
```cmd
ipconfig
```
Locate the VMware adapter IPv4 address:
- Look for **IPv4 Address** under `Ethernet adapter VMware Network Adapter VMnet8` (e.g. `192.168.174.1`) or your local Wi-Fi/Ethernet IP (e.g. `192.168.1.105`).

#### Step B: From Kali Linux Terminal
Verify network connectivity to the Windows host:
```bash
ping -c 3 192.168.174.1
```
*(Replace `192.168.174.1` with your actual Windows host IP).*

If ping succeeds, verify the web server is responding:
```bash
curl -I http://192.168.174.1/cybershield/
```
You should observe an `HTTP/1.1 200 OK` or `HTTP/1.1 302 Found` response from Apache.

---

## 3. Manual Defensive Testing via Browser Developer Tools

Open Firefox inside Kali Linux and browse to:
```
http://<WINDOWS_HOST_IP>/cybershield/login
```

### Test 1: Authentication & Session Cookie Security
1. Press `F12` to open Developer Tools &rarr; navigate to the **Storage** tab &rarr; **Cookies**.
2. Sign in as `superadmin` / `CyberShield@2026!`.
3. Inspect `CYBERSHIELD_SESSID`:
   - **HttpOnly:** Must be set to `true` (prevents JavaScript `document.cookie` theft via XSS).
   - **SameSite:** Must be set to `Lax` or `Strict` (mitigates CSRF via third-party contexts).
   - **Session Fixation Check:** Note the cookie value prior to login, then sign in. Verify that `CYBERSHIELD_SESSID` regenerates upon authentication.

### Test 2: Role-Based Access Control (RBAC) Enforcement
1. Sign in as student: `student` / `CyberShield@2026!`.
2. Attempt to directly access privileged administrative routes:
   - `http://<WINDOWS_HOST_IP>/cybershield/settings`
   - `http://<WINDOWS_HOST_IP>/cybershield/audit`
   - `http://<WINDOWS_HOST_IP>/cybershield/campaigns/create`
3. Verify that access is strictly denied with an `HTTP 403 Forbidden` response and an unauthorized attempt entry is recorded in the audit log.

### Test 3: CSRF Token Integrity
1. Open the Network tab in DevTools.
2. Submit a state-changing form (e.g., enroll participant or report suspicious message).
3. Inspect the POST body to verify `csrf_token` is present.
4. Tamper with the token value or omit it using an interceptor (such as Burp Suite or OWASP ZAP).
5. Verify that the server rejects the request with an `HTTP 403 CSRF Validation Failed` error.

---

## 4. Defensive Scanning with OWASP ZAP (Zed Attack Proxy)

OWASP ZAP is an industry-standard open-source web application security testing tool pre-installed in Kali Linux.

### Launching OWASP ZAP in Kali:
```bash
zaproxy &
```

### Performing an Authorized Baseline Scan:
1. Select **Automated Scan** in the ZAP main window.
2. Enter the URL of your local Windows CyberShield instance:
   ```
   http://192.168.174.1/cybershield/
   ```
3. Set the attack mode to **Safe / Baseline** or **Standard Spider**.
4. Click **Attack**.

### Key Checks to Review in ZAP Alerts:
- **Anti-CSRF Tokens:** Ensure all state-changing `<form>` elements have valid anti-CSRF protections detected.
- **Content Security Policy & X-Content-Type-Options:** Verify `nosniff` header is present.
- **SQL Injection:** Confirm that standard SQL payloads (e.g. `' OR 1=1 --`) on login inputs return standard authentication failures without database syntax errors.
- **Cross-Site Scripting (Reflected & Stored):** Confirm that `<script>alert(1)</script>` injected into participant names or search fields is cleanly escaped to `&lt;script&gt;`.

---

## 5. Security Vulnerability Documentation & Remediation

When documenting findings during laboratory testing, utilize the following format:

### Vulnerability Reporting Template:
```markdown
### Finding ID: VULN-01
- **Vulnerability Title:** Missing HTTP Security Header / Parameter
- **Severity:** Informational / Low / Medium / High
- **CWE:** CWE-352 (CSRF), CWE-79 (XSS), CWE-89 (SQLi)
- **Target Endpoint:** /cybershield/campaigns/create
- **Steps to Reproduce:**
  1. Intercept POST request.
  2. Modify parameter X.
  3. Observe server response.
- **Evidence / Screenshot:** [Attach ZAP request/response log]
- **Remediation Recommendation:** Apply htmlspecialchars() / Prepared Statements / CSRF token validation.
```

---

## 6. Post-Assessment Hygiene

1. Return any modified test configuration to baseline.
2. Review the CyberShield **Audit Logs** (`http://<WINDOWS_HOST_IP>/cybershield/audit`) to verify all testing actions were accurately logged by the monitoring subsystem.
3. Keep virtual lab networks isolated when not actively testing.
