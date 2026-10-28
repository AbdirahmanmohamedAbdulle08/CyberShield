# CyberShield — Security & Quality Assurance Verification Checklist

This checklist verifies all security controls, architectural safety rules, and functional modules required for the CyberShield platform at Hormuud University.

---

## Security & Architectural Assurance Matrix

| Item # | Verification Test | Expected Security Behavior | Status | Evidence / Notes |
| :---: | :--- | :--- | :---: | :--- |
| **01** | **Successful Login** | Authenticates valid credentials, updates `last_login_at`, regenerates session ID (`session_regenerate_id`), redirects based on role. | **PASS** | Verified with Bcrypt `password_verify()` |
| **02** | **Invalid Login** | Returns generic error ("Invalid credentials") without revealing if username exists (prevents user enumeration). | **PASS** | Evaluated with invalid credentials |
| **03** | **Login Rate Limiting** | Temporarily throttles IP after 5 consecutive failed attempts within 15 minutes. | **PASS** | Tracked in `login_attempts` table |
| **04** | **Safe Logout** | Unsets `$_SESSION`, expires session cookie with past timestamp, destroys session, redirects to login. | **PASS** | Session cleared completely |
| **05** | **Session Expiration** | Inactive sessions terminate according to `session_lifetime` (default: 3600s). | **PASS** | Managed via PHP session configuration |
| **06** | **RBAC Enforcement** | Students cannot view admin dashboard; instructors cannot edit system settings or create Super Admins. | **PASS** | Server-side `requireRole()` enforcement |
| **07** | **Direct URL Access** | Accessing `/settings` or `/audit` directly as a student yields HTTP 403 Forbidden with audit logging. | **PASS** | Intercepted in Controller middleware |
| **08** | **CSRF Defense** | State-changing POST requests require matching cryptographic token; forged requests are rejected with 403. | **PASS** | `CSRF::validate()` enforced on all POST forms |
| **09** | **SQL Injection Resistance** | Single quotes `' OR 1=1 --` in inputs treated as literal string parameters; zero raw query concatenation. | **PASS** | 100% PDO prepared statements with `?` parameters |
| **10** | **XSS Output Escaping** | Injected `<script>` tags in participant names or campaign bodies escaped as `&lt;script&gt;` via `ViewHelper::e()`. | **PASS** | `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` |
| **11** | **Campaign Builder** | Educational objectives, fictional scenarios, and message bodies created and validated. | **PASS** | Stored in `campaigns` table |
| **12** | **Campaign Authorization** | Super Admin must explicitly review and record formal safety authorization notes before launching. | **PASS** | Audit trail in `campaign_authorizations` |
| **13** | **Safe Simulation Lures** | Non-guessable public token `/simulation/{token}`; no credential input fields; no university login cloning. | **PASS** | Strict educational demonstration page |
| **14** | **Telemetry Tracking** | Records `simulation_link_clicked` and `training_page_viewed` with anonymized IP subnet and browser metadata. | **PASS** | Recorded in `simulation_events` table |
| **15** | **Awareness Academy** | 10 comprehensive cybersecurity modules with learning objectives, case studies, checklists, and responses. | **PASS** | Seeded in `learning_modules` table |
| **16** | **Quiz Engine** | Scoring computed strictly on the server; answers hidden from client until submission; explanations provided. | **PASS** | Server-evaluated in `QuizController` |
| **17** | **Threat Reporting** | Students can report suspicious lures with reason and notes; admins triage in resolution queue. | **PASS** | Recorded in `suspicious_message_reports` |
| **18** | **Audit Logging** | Administrative actions (campaigns, authorisations, user changes, reports) recorded immutably. | **PASS** | Stored in `audit_logs` table |
| **19** | **CSV Export Defense** | Formula triggers (`=`, `+`, `-`, `@`, `\t`, `\r`) prepended with single quote `'` to prevent spreadsheet injection. | **PASS** | CWE-1236 mitigation in `CsvHelper` |
| **20** | **CSV Import Validation** | Verifies column headers (`name`, `email`), checks email formats, generates pseudonymous tokens. | **PASS** | Validated in `CsvHelper::parseParticipantsCsv` |
| **21** | **Zero Password Harvesting**| No database table or column exists anywhere in the application to capture participant passwords. | **PASS** | Zero credential theft architecture |
| **22** | **Mobile Responsiveness** | Dark SOC theme adapts to mobile and tablet viewports with offcanvas navigation. | **PASS** | Tested with Bootstrap 5.3 responsive grid |

---

## Sign-off & Verification

- **Lead Examiner:** Faculty of Computer Science & IT, Hormuud University  
- **System Version:** CyberShield v1.0.0  
- **Verification Date:** Academic Year 2026  
- **Result:** **ALL 22 TESTS VERIFIED & APPROVED**
