# CyberShield — Cybersecurity Awareness & Phishing Simulation Platform

[![Platform](https://img.shields.io/badge/Platform-Windows%2010%2F11%20%7C%20XAMPP-blue)](#)
[![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4)](#)
[![Database](https://img.shields.io/badge/Database-MySQL%20%2F%20MariaDB-4479A1)](#)
[![Institution](https://img.shields.io/badge/Institution-Hormuud%20University-00E5FF)](#)
[![Security](https://img.shields.io/badge/Ethics-Authorized%20Simulation%20Only-emerald)](#)

> **Tagline:** Detect. Educate. Defend.  
> **Target Audience:** Computer Science and IT students, particularly students studying Cyber Security at **Hormuud University**.

---

## 1. Executive Summary & Educational Purpose

**CyberShield** is an authorized cybersecurity awareness and educational simulation platform engineered to demonstrate how social engineering, phishing messages, suspicious links, fake offers, and impersonation attacks manipulate human cognitive biases, and how students can identify, report, and prevent these attacks.

### Mandatory Ethical Safeguards:
1. **Zero Credential Theft:** CyberShield never collects, transmits, or stores real passwords, OTPs, recovery codes, or session tokens.
2. **No Cloning:** The application does not clone university login pages, banking portals, or third-party authentication services.
3. **Immediate Disclosure:** Interacting with a simulated lure immediately routes the student to a comprehensive educational training disclosure explaining the warning signs.
4. **Consent & Authorization:** Campaigns require explicit administrative approval and record all administrative actions in an audit log.

---

## 2. Technology Stack & Requirements

- **Operating System:** Windows 10 or Windows 11
- **Server Environment:** XAMPP (Apache 2.4+ and MySQL 5.7+ / MariaDB 10.4+)
- **PHP Version:** PHP 8.0 or PHP 8.1+ (Uses PDO with prepared statements, session security)
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5.3 (Self-contained locally), Bootstrap Icons, Chart.js 4.4
- **Local URL:** `http://localhost/cybershield/` or `http://localhost/cyber%20security/`
- **Database Name:** `cybershield_db`

---

## 3. Quick Windows & XAMPP Installation Guide

### Step 1: Start XAMPP Services
1. Open the **XAMPP Control Panel**.
2. Click **Start** for **Apache**.
3. Click **Start** for **MySQL**.
4. Confirm both services show green status indicators.

### Step 2: Verify Project Location
Ensure the project files reside in your XAMPP web root:
```
C:\xampp\htdocs\cybershield\
```
*(If your folder is named `cyber security`, a directory junction or alias maps it to `cybershield` automatically).*

### Step 3: Run the Setup Wizard
Open your web browser and navigate to:
```
http://localhost/cybershield/setup.php
```
1. Verify MySQL connection parameters (Default: Host `127.0.0.1`, Port `3306`, User `root`, Password empty).
2. Keep the checkbox **"Install Hormuud University CS Cyber Security Demo Dataset"** checked.
3. Review or customize the initial Super Admin account:
   - **Username:** `superadmin`
   - **Password:** `CyberShield@2026!`
4. Click **Initialize CyberShield Platform**.
5. The wizard imports `database/schema.sql`, populates `database/seed_demo.sql`, creates the Super Admin, and generates `install.lock` to seal the setup against re-execution.

---

## 4. Default Demo Accounts

For educational evaluation and defense lab demonstrations, CyberShield includes 4 pre-configured role accounts:

| Role | Username | Password | Full Name | Primary Responsibilities |
| :--- | :--- | :--- | :--- | :--- |
| **Super Admin** | `superadmin` | `CyberShield@2026!` | Dr. Abdirahman Omar (SOC Lead) | Global dashboard, user RBAC, system settings, authorizations, audit logs |
| **Campaign Manager** | `manager` | `CyberShield@2026!` | Amina Hassan (Campaign Lead) | Campaign builder, scenario preview, event monitor, telemetry |
| **Instructor** | `instructor` | `CyberShield@2026!` | Eng. Mohamed Liban (Lecturer) | Cohort supervision, student performance, quiz results, reports queue |
| **Student** | `student` | `CyberShield@2026!` | Farah Abdullahi (CS-Y3) | Student learning portal, 10 modules, quizzes, report suspicious lures |

*Tip: The login page includes 1-click demo quick-fill buttons for instant testing.*

---

## 5. Architectural Directory Layout

```
cybershield/
├── index.php                 # Front Controller & Route Dispatcher
├── setup.php                 # One-Time Self-Locking Setup Wizard
├── install.lock              # Setup Lock File (Generated upon installation)
├── README.md                 # System Guide & Documentation
├── .htaccess                 # Apache mod_rewrite, directory blocking & headers
├── config/
│   ├── app.php               # Global app configuration & dynamic base URL
│   └── database.php          # Database PDO connection settings
├── app/
│   ├── Core/
│   │   ├── Database.php      # PDO Singleton & query helper
│   │   ├── Router.php        # URL router with parameter matching & 404 handler
│   │   ├── Controller.php    # Base controller (render, json, RBAC, CSRF)
│   │   ├── Auth.php          # Session security, rate limiter, role checks
│   │   ├── CSRF.php          # Cryptographic token protection (CWE-352)
│   │   └── Logger.php        # Audit logging service (zero credential logging)
│   ├── Controllers/          # MVC Controllers (Auth, Dashboard, Campaign, Simulation...)
│   └── Helpers/              # ViewHelper (XSS escaping), CsvHelper (CWE-1236 defense)
├── database/
│   ├── schema.sql            # Normalized MySQL schema with foreign keys & indexes
│   └── seed_demo.sql         # Fictional demo dataset for Hormuud University
├── views/
│   ├── layouts/              # SOC Dark Theme Shell, Auth layout, Simulation layout
│   ├── dashboard/            # Super Admin SOC console & Student learning portal
│   ├── campaigns/            # Campaign list, builder with live preview, campaign view
│   ├── simulation/           # Safe lure landing page & educational disclosure
│   ├── events/               # Real-time event monitor with live stream polling
│   ├── academy/              # 10 comprehensive cybersecurity awareness lessons
│   ├── quizzes/              # Server-evaluated quizzes with explanations
│   ├── reports/              # Mathematical analytics, threat reports triage queue
│   ├── audit/                # Immutable administrative audit log viewer
│   ├── settings/             # System settings & security hardening checks
│   └── users/                # Super Admin user management & RBAC
├── public/assets/            # Self-contained offline CSS, JS, Fonts & SVG logo
├── storage/                  # Logs and exports
└── docs/
    ├── kali_security_testing.md  # Defensive testing guide from Kali in VMware
    └── security_checklist.md     # 20-point security QA testing checklist
```

---

## 6. Testing Phishing Simulations Safely

1. Log in as **Super Admin** or **Campaign Manager**.
2. Navigate to **Campaigns** &rarr; select **Simulation 1: The $500 Tech Grant Lure**.
3. Copy the **Safe Educational Simulation URL**:
   ```
   http://localhost/cybershield/simulation/sim_reward_500_tok9283f
   ```
4. Open the link in a private browser window:
   - Observe the simulated email grant lure.
   - Click **"I would normally click or enter my credentials"**.
   - Notice that **no password is requested**.
   - Observe the immediate educational disclosure explaining the 4 red flags (Unrealistic reward, urgency lever, lookalike domain, external link).
5. Return to the SOC Dashboard or **Event Monitor**:
   - The event `simulation_link_clicked` and `training_page_viewed` will appear in the live telemetry stream.
6. Test threat reporting: Click **"Report Suspicious Message"** to log a report and verify it appears in **Suspicious Reports Queue**.

---

## 7. Troubleshooting Common Issues

### Issue 1: Apache Port 80 / 443 Conflict (Skype / IIS / VMware)
- **Cause:** Another service on Windows is using port 80 or 443.
- **Fix:** In XAMPP Control Panel &rarr; Apache &rarr; **Config** &rarr; `httpd.conf` &rarr; change `Listen 80` to `Listen 8080`. Then access `http://localhost:8080/cybershield/`.

### Issue 2: MySQL Connection Refused
- **Cause:** MySQL service is not running or has an altered root password.
- **Fix:** Open XAMPP Control Panel and start MySQL. If you have set a MySQL root password, update `config/database.php` accordingly.

### Issue 3: Clean URLs Return 404
- **Cause:** Apache `mod_rewrite` is not enabled or `.htaccess` is ignored.
- **Fix:** In `C:\xampp\apache\conf\httpd.conf`, ensure `LoadModule rewrite_module modules/mod_rewrite.so` is uncommented, and `AllowOverride All` is set for the `htdocs` directory. CyberShield also supports fallback query strings (e.g. `index.php?r=dashboard`).

---

## 8. License & Institutional Attribution

Developed for academic and cybersecurity awareness purposes at **Hormuud University**, Faculty of Computer Science & IT. Strictly intended for defensive training and authorized educational simulations.
