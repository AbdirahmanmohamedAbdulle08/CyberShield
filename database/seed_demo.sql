-- ============================================================================
-- CyberShield — Cybersecurity Awareness & Phishing Simulation Platform
-- Demo Seed Data: database/seed_demo.sql
-- Fictional data for Hormuud University CS & Cyber Security demonstrations
-- Contains NO real credentials, NO stolen passwords, NO real university logins
-- ============================================================================

USE `cybershield_db`;

-- System Settings
INSERT INTO `system_settings` (`setting_key`, `setting_value`, `setting_group`, `description`) VALUES
('app_name', 'CyberShield', 'general', 'Application Display Name'),
('app_tagline', 'Detect. Educate. Defend.', 'general', 'Platform Tagline'),
('institution_name', 'Hormuud University', 'education', 'University / Institution Name'),
('department_name', 'Faculty of Computer Science & IT — Cyber Security Lab', 'education', 'Department or Unit Name'),
('contact_email', 'cybersecurity-lab@hu.edu.so', 'general', 'Support and Incident Response Email'),
('simulation_mode', 'local_educational', 'security', 'Active simulation engine mode (local_educational)'),
('consent_required', '1', 'compliance', 'Require explicit participant consent before simulation'),
('session_lifetime', '3600', 'security', 'Session expiration timeout in seconds'),
('rate_limit_max_attempts', '5', 'security', 'Max failed login attempts before lockout'),
('rate_limit_lockout_minutes', '15', 'security', 'Lockout duration in minutes'),
('demo_data_installed', '1', 'system', 'Indicator that demo data is loaded')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);

-- Users (Demo accounts with password: CyberShield@2026!)
-- Hash: $2y$10$idHxojbp6Hgzs2j4.w59BeeBUjatmSRJ/GTALNH6W2OIosy7ViHnu
INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `full_name`, `role`, `status`) VALUES
(1, 'superadmin', 'admin@cybershield.hu.edu.so', '$2y$10$idHxojbp6Hgzs2j4.w59BeeBUjatmSRJ/GTALNH6W2OIosy7ViHnu', 'Dr. Abdirahman Omar (SOC Lead)', 'super_admin', 'active'),
(2, 'manager', 'manager@cybershield.hu.edu.so', '$2y$10$idHxojbp6Hgzs2j4.w59BeeBUjatmSRJ/GTALNH6W2OIosy7ViHnu', 'Amina Hassan (Campaign Lead)', 'campaign_manager', 'active'),
(3, 'instructor', 'instructor@cybershield.hu.edu.so', '$2y$10$idHxojbp6Hgzs2j4.w59BeeBUjatmSRJ/GTALNH6W2OIosy7ViHnu', 'Eng. Mohamed Liban (Lecturer)', 'instructor', 'active'),
(4, 'student', 'student@cybershield.hu.edu.so', '$2y$10$idHxojbp6Hgzs2j4.w59BeeBUjatmSRJ/GTALNH6W2OIosy7ViHnu', 'Farah Abdullahi (CS-Y3 Student)', 'student', 'active')
ON DUPLICATE KEY UPDATE `full_name` = VALUES(`full_name`);

-- Training Groups (Hormuud University cohorts)
INSERT INTO `training_groups` (`id`, `group_name`, `description`, `instructor_id`, `status`) VALUES
(1, 'HU Cyber Security Cohort 2026', 'Undergraduate students majoring in Cybersecurity & Digital Forensics', 3, 'active'),
(2, 'HU Computer Science Year 3', 'Computer Science Juniors - Network Security & Software Systems Lab', 3, 'active'),
(3, 'HU IT & Network Engineering', 'IT Senior Section - Infrastructure & Cloud Defense Fundamentals', 3, 'active')
ON DUPLICATE KEY UPDATE `group_name` = VALUES(`group_name`);

-- Participants (Pseudonymous student records)
INSERT INTO `participants` (`id`, `participant_token`, `display_name`, `pseudonym`, `email`, `institutional_id`, `training_group_id`, `consent_status`, `status`) VALUES
(1, 'tok_hu_cs_001_kestrel', 'Farah Abdullahi', 'Participant-Kestrel-01', 'farah.cs26@student.hu.edu.so', 'HU-CS-2023-0142', 1, 'consented', 'active'),
(2, 'tok_hu_cs_002_falcon', 'Khadija Shire', 'Participant-Falcon-02', 'khadija.s26@student.hu.edu.so', 'HU-CS-2023-0189', 1, 'consented', 'active'),
(3, 'tok_hu_cs_003_shield', 'Ahmed Nur', 'Participant-Shield-03', 'ahmed.nur26@student.hu.edu.so', 'HU-CS-2023-0205', 1, 'consented', 'active'),
(4, 'tok_hu_cs_004_vanguard', 'Safia Mahamud', 'Participant-Vanguard-04', 'safia.m26@student.hu.edu.so', 'HU-CS-2023-0271', 1, 'consented', 'active'),
(5, 'tok_hu_cs_005_phoenix', 'Guled Farah', 'Participant-Phoenix-05', 'guled.f26@student.hu.edu.so', 'HU-CS-2023-0312', 2, 'consented', 'active'),
(6, 'tok_hu_cs_006_sentinel', 'Hodan Warsame', 'Participant-Sentinel-06', 'hodan.w26@student.hu.edu.so', 'HU-CS-2023-0348', 2, 'consented', 'active'),
(7, 'tok_hu_cs_007_cyberhawk', 'Yasin Barre', 'Participant-CyberHawk-07', 'yasin.b26@student.hu.edu.so', 'HU-CS-2023-0390', 2, 'consented', 'active'),
(8, 'tok_hu_cs_008_aegis', 'Zahra Osman', 'Participant-Aegis-08', 'zahra.o26@student.hu.edu.so', 'HU-CS-2023-0411', 3, 'consented', 'active'),
(9, 'tok_hu_cs_009_citadel', 'Mustafa Ali', 'Participant-Citadel-09', 'mustafa.a26@student.hu.edu.so', 'HU-CS-2023-0455', 3, 'consented', 'active'),
(10, 'tok_hu_cs_010_warden', 'Filsan Hassan', 'Participant-Warden-10', 'filsan.h26@student.hu.edu.so', 'HU-CS-2023-0498', 3, 'consented', 'active')
ON DUPLICATE KEY UPDATE `display_name` = VALUES(`display_name`);

-- Campaigns (Fictional educational simulation scenarios)
INSERT INTO `campaigns` (`id`, `campaign_name`, `educational_objective`, `fictional_scenario`, `sender_name`, `sender_email`, `message_subject`, `message_body`, `training_group_id`, `public_token`, `status`, `created_by`, `started_at`, `completed_at`) VALUES
(1, 'Simulation 1: The $500 Tech Grant Lure', 'Teach students to detect unrealistic financial incentives, urgency tactics, and lookalike domains.', 'Fake $500 reward', 'Hormuud Innovation Foundation', 'rewards@grants-hormuud-awards.org', 'Congratulations! You have been selected to receive a $500 research grant', 'Dear Student,\r\n\r\nYou have been preliminarily approved to receive an educational stipend of $500 under the 2026 Academic Research Stimulus. Due to quota limitations, awards not confirmed within 4 hours will be reallocated to the next applicant.\r\n\r\nClick below to verify your eligibility and claim your funds immediately.\r\n\r\n[Claim $500 Reward Now]', 1, 'sim_reward_500_tok9283f', 'running', 1, NOW() - INTERVAL 5 DAY, NULL),

(2, 'Simulation 2: Emergency IT Password Reset Warning', 'Demonstrate fake urgent security alerts that cause panic and trick users into clicking unverified links.', 'Urgent account-security warning', 'Campus IT Security Desk', 'security-alerts@campus-auth-check.net', 'URGENT: Unauthorized access detected on your student portal account', 'ATTENTION: A login attempt from an unrecognized IP address (Eastern Europe Subnet) was blocked for your campus account. If this was not you, you must re-verify your identity within 30 minutes or your account will be suspended permanently.\r\n\r\nVerify your account immediately below:\r\n\r\n[Secure Account Now]', 1, 'sim_it_alert_tok4821a', 'running', 2, NOW() - INTERVAL 3 DAY, NULL),

(3, 'Simulation 3: Fake Express Parcel Delivery Notice', 'Examine consumer-focused smishing/phishing lures involving fake package deliveries and tracking links.', 'Fake delivery notification', 'Express Express Delivery Dispatch', 'tracking-support@fastdelivery-hub.cc', 'Package Delivery Exception: Address clarification required for your package', 'Dear Customer,\r\n\r\nYour shipment #HU-883921 could not be delivered to the university reception due to an incomplete delivery code. A redelivery fee of $1.50 or address confirmation is required to avoid return to sender.\r\n\r\nView shipment status and confirm destination:\r\n\r\n[Confirm Delivery Address]', 2, 'sim_delivery_tok1948c', 'completed', 2, NOW() - INTERVAL 12 DAY, NOW() - INTERVAL 2 DAY),

(4, 'Simulation 4: Cybersecurity Intern Remote Job Offer', 'Train students to identify high-paying fake job opportunities that solicit sensitive data.', 'Suspicious job opportunity', 'Global CyberTalent Recruiter', 'careers@cybersec-talentrecruits.com', 'Job Offer: Remote Cybersecurity Analyst Intern ($45/hr) - Immediate Placement', 'Hello,\r\n\r\nWe noticed your high performance in your computer science coursework. Our international cybersecurity consulting partner is looking for 5 student interns for remote threat assessment.\r\n\r\nRate: $45.00/hour. Flexible schedule. No prior experience required.\r\n\r\nReview job requirements and submit your application:\r\n\r\n[Apply for Position]', 3, 'sim_job_offer_tok7712d', 'authorized', 1, NULL, NULL),

(5, 'Simulation 5: Urgent Examination Schedule Update', 'Test resilience against authority-based lures mimicking academic administration offices.', 'Suspicious university announcement', 'Academic Affairs Office', 'academics@exam-schedules-verify.info', 'FINAL NOTICE: Revised Midterm Examination Schedule & Seat Allocations', 'All Students Please Note:\r\n\r\nDue to infrastructure upgrades, the upcoming Cybersecurity and Networking exam dates and room numbers have been adjusted. Every student must download and sign their revised admit card.\r\n\r\nView revised examination timetable:\r\n\r\n[Download Exam Schedule]', 1, 'sim_exam_sched_tok6640e', 'draft', 3, NULL, NULL)
ON DUPLICATE KEY UPDATE `campaign_name` = VALUES(`campaign_name`);

-- Campaign Authorizations
INSERT INTO `campaign_authorizations` (`id`, `campaign_id`, `authorized_by`, `authorization_notes`, `authorized_at`, `status`) VALUES
(1, 1, 1, 'Authorized for live educational training in Hormuud University Cyber Security Cohort 2026. Strictly educational simulation.', NOW() - INTERVAL 5 DAY, 'approved'),
(2, 2, 1, 'Approved by SOC Lead for CS Year 3 Security Lab practical demonstration.', NOW() - INTERVAL 3 DAY, 'approved'),
(3, 3, 1, 'Delivery scam awareness module approved for Group 2.', NOW() - INTERVAL 12 DAY, 'approved'),
(4, 4, 1, 'Job lure simulation approved for IT & Networks group.', NOW() - INTERVAL 1 DAY, 'approved')
ON DUPLICATE KEY UPDATE `authorization_notes` = VALUES(`authorization_notes`);

-- Simulation Events (Educational telemetry)
INSERT INTO `simulation_events` (`id`, `campaign_id`, `participant_token`, `event_type`, `metadata_json`, `ip_anonymized`, `created_at`) VALUES
(1, 1, 'tok_hu_cs_001_kestrel', 'simulation_link_clicked', '{"referrer":"email_client","browser":"Chrome","simulated_lure":"$500_reward"}', '192.168.1.0/24', NOW() - INTERVAL 5 DAY + INTERVAL 2 HOUR),
(2, 1, 'tok_hu_cs_001_kestrel', 'training_page_viewed', '{"duration_sec":95,"training_module":"fake_reward_detection"}', '192.168.1.0/24', NOW() - INTERVAL 5 DAY + INTERVAL 2 HOUR + INTERVAL 1 MINUTE),
(3, 1, 'tok_hu_cs_001_kestrel', 'lesson_started', '{"module_slug":"fake-rewards-and-prize-scams"}', '192.168.1.0/24', NOW() - INTERVAL 5 DAY + INTERVAL 2 HOUR + INTERVAL 3 MINUTE),
(4, 1, 'tok_hu_cs_001_kestrel', 'lesson_completed', '{"module_slug":"fake-rewards-and-prize-scams","time_spent_min":8}', '192.168.1.0/24', NOW() - INTERVAL 5 DAY + INTERVAL 2 HOUR + INTERVAL 12 MINUTE),
(5, 1, 'tok_hu_cs_002_falcon', 'simulation_link_clicked', '{"referrer":"email_client","browser":"Firefox","simulated_lure":"$500_reward"}', '192.168.1.0/24', NOW() - INTERVAL 4 DAY),
(6, 1, 'tok_hu_cs_002_falcon', 'training_page_viewed', '{"duration_sec":140,"training_module":"fake_reward_detection"}', '192.168.1.0/24', NOW() - INTERVAL 4 DAY + INTERVAL 1 MINUTE),
(7, 1, 'tok_hu_cs_003_shield', 'suspicious_message_reported', '{"reason":"unrealistic_reward","reported_via":"student_portal"}', '192.168.1.0/24', NOW() - INTERVAL 4 DAY + INTERVAL 4 HOUR),
(8, 1, 'tok_hu_cs_004_vanguard', 'suspicious_message_reported', '{"reason":"suspicious_link","reported_via":"outlook_addin_sim"}', '192.168.1.0/24', NOW() - INTERVAL 3 DAY),
(9, 2, 'tok_hu_cs_001_kestrel', 'suspicious_message_reported', '{"reason":"urgent_threat","reported_via":"student_portal"}', '192.168.1.0/24', NOW() - INTERVAL 2 DAY),
(10, 2, 'tok_hu_cs_002_falcon', 'simulation_link_clicked', '{"referrer":"email_client","browser":"Edge"}', '192.168.1.0/24', NOW() - INTERVAL 2 DAY + INTERVAL 30 MINUTE),
(11, 2, 'tok_hu_cs_002_falcon', 'training_page_viewed', '{"duration_sec":110}', '192.168.1.0/24', NOW() - INTERVAL 2 DAY + INTERVAL 31 MINUTE),
(12, 3, 'tok_hu_cs_005_phoenix', 'simulation_link_clicked', '{"referrer":"mobile_safari"}', '192.168.1.0/24', NOW() - INTERVAL 10 DAY),
(13, 3, 'tok_hu_cs_005_phoenix', 'training_page_viewed', '{"duration_sec":85}', '192.168.1.0/24', NOW() - INTERVAL 10 DAY + INTERVAL 2 MINUTE),
(14, 3, 'tok_hu_cs_006_sentinel', 'suspicious_message_reported', '{"reason":"unknown_sender"}', '192.168.1.0/24', NOW() - INTERVAL 9 DAY),
(15, 3, 'tok_hu_cs_007_cyberhawk', 'suspicious_message_reported', '{"reason":"suspicious_link"}', '192.168.1.0/24', NOW() - INTERVAL 9 DAY + INTERVAL 1 HOUR)
ON DUPLICATE KEY UPDATE `event_type` = VALUES(`event_type`);

-- 10 Required Learning Modules (Full Educational Curricula)
INSERT INTO `learning_modules` (`id`, `module_number`, `title`, `slug`, `difficulty_level`, `estimated_minutes`, `learning_objectives`, `lesson_content`, `suspicious_example`, `warning_checklist`, `recommended_response`) VALUES
(1, 1, 'Introduction to Social Engineering', 'intro-social-engineering', 'beginner', 10,
'Understand social engineering concepts, cognitive vulnerabilities exploited by adversaries, and the psychological principles behind deception.',
'### What is Social Engineering?\r\n\r\nSocial engineering is the psychological manipulation of people into performing actions or divulging confidential information. Rather than finding a zero-day exploit in software or breaking 256-bit AES encryption, attackers target the most flexible component in any security perimeter: **the human being**.\r\n\r\n### Psychological Principles Exploited:\r\n\r\n1. **Authority**: Attackers impersonate deans, department heads, police officers, or system administrators to leverage obedience.\r\n2. **Urgency**: Creating panic (e.g. \"Account locked in 15 minutes!\") impairs rational decision-making and bypassed critical thinking.\r\n3. **Greed / Reward**: Offering cash prizes, fake grants, or free gadgets activates optimism bias.\r\n4. **Social Proof & Familiarity**: Mentioning classmates or university departments builds false trust.\r\n5. **Fear / Coercion**: Threats of legal action, grade cancellation, or account suspension compel compliance.\r\n\r\nDefenders must recognize that social engineering can happen through email (phishing), voice calls (vishing), SMS (smishing), physical impersonation, or social media messaging.',
'\"From: Dean of Student Affairs <dean-office@university-portal-verify.com>\r\nSubject: Urgent disciplinary review regarding your enrollment status\r\n\r\nPlease log in immediately to confirm you did not commit academic misconduct, otherwise your registration for the semester will be cancelled by 5:00 PM today.\r\n[Verify Identity Now]\"',
'- Manufactured extreme time pressure (e.g. 5:00 PM deadline).\r\n- Emotional leverage via fear and anxiety.\r\n- Discrepancy between sender display name and actual sender domain.\r\n- Unofficial domain name instead of official .edu.so or official campus portal.\r\n- Request to enter credentials on an external landing page.',
'Pause, breathe, and never click links sent under duress. Verify the communication independently using trusted university channels or visit the registrar in person.')

ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

INSERT INTO `learning_modules` (`id`, `module_number`, `title`, `slug`, `difficulty_level`, `estimated_minutes`, `learning_objectives`, `lesson_content`, `suspicious_example`, `warning_checklist`, `recommended_response`) VALUES
(2, 2, 'How Phishing Works', 'how-phishing-works', 'beginner', 12,
'Analyze the lifecycle of a phishing attack, from reconnaissance and weaponization to lure delivery, credential harvesting, and post-exploitation.',
'### The Phishing Attack Lifecycle\r\n\r\nPhishing remains the primary initial attack vector responsible for over 80% of reported enterprise security incidents according to cybersecurity industry research.\r\n\r\n### Phishing Stages:\r\n\r\n1. **Reconnaissance**: The adversary gathers target information using Open Source Intelligence (OSINT), such as student names on LinkedIn, university faculty listings, and campus email formats.\r\n2. **Weaponization**: The attacker constructs a convincing lure, buys lookalike domain names (e.g. `hu-edu.so.net` instead of `hu.edu.so`), and sets up a capture server.\r\n3. **Delivery**: The message is dispatched via bulk email servers or hijacked legitimate mail accounts.\r\n4. **Exploitation / Deception**: The victim clicks the embedded link or downloads an attachment containing malicious macros.\r\n5. **Action on Objectives**: If credentials are submitted, attackers use them for account takeover, lateral movement, or data exfiltration.',
'\"From: IT Helpdesk <admin@it-support-notice.net>\r\nSubject: Storage Quota Exceeded (98% Full)\r\n\r\nYour university mailbox has reached 98% of its storage capacity. Inbound emails are being queued. Please validate your account to expand storage quota.\r\n[Upgrade Storage Allowance]\"',
'- Generic greeting without personal student name.\r\n- Artificial resource limitation designed to provoke hasty action.\r\n- External domain (`it-support-notice.net`) claiming to manage internal university infrastructure.\r\n- Hyperlink hovering reveals a destination different from the displayed text.',
'Inspect the URL by hovering. Check if your mail storage is actually full inside your verified campus client. Report the message to CyberShield.')

ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

INSERT INTO `learning_modules` (`id`, `module_number`, `title`, `slug`, `difficulty_level`, `estimated_minutes`, `learning_objectives`, `lesson_content`, `suspicious_example`, `warning_checklist`, `recommended_response`) VALUES
(3, 3, 'Recognizing Suspicious URLs', 'recognizing-suspicious-urls', 'intermediate', 15,
'Master the anatomy of Uniform Resource Locators (URLs), detect typosquatting, IDN homograph attacks, subdomain tricks, and deceptive path formatting.',
'### Anatomy of a URL\r\n\r\nUnderstanding URL structure is your primary defense against fraudulent links:\r\n\r\n```\r\nhttps://  portal.hu.edu.so  :443  /student/login  ?ref=dashboard  #main\r\nProtocol   Host (Domain)    Port   Path            Query Params   Fragment\r\n```\r\n\r\n### Common Attacker Deceptions:\r\n\r\n1. **Subdomain Prepending**: `hu.edu.so.attacker-site.com`. The true domain is `attacker-site.com`, NOT `hu.edu.so`!\r\n2. **Typosquatting**: `hormud-university.com` or `hu-edu-so.org` (missing letters, swapped vowels, or added hyphens).\r\n3. **Homograph Attacks**: Using Cyrillic or Unicode characters that look identical to Latin letters (e.g. Cyrillic `a` vs Latin `a`).\r\n4. **Open Redirects**: `https://legit-site.com/redirect?to=https://evil.com`.\r\n5. **URL Shorteners**: bit.ly or tinyurl hiding the true destination.',
'\"Please update your student profile at:\r\nhttps://portal.hu.edu.so.verification-campus.com/auth/login\"',
'- Look at the domain immediately preceding the first single slash `/`.\r\n- `portal.hu.edu.so` is merely a subdomain belonging to `verification-campus.com`.\r\n- The genuine root domain is NOT Hormuud University.\r\n- Presence of HTTPS (padlock) only means encryption between browser and server, NOT that the website is trustworthy!',
'Always read URLs from right to left starting before the first single forward slash to identify the genuine Top-Level Domain (TLD) and Second-Level Domain.')

ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

INSERT INTO `learning_modules` (`id`, `module_number`, `title`, `slug`, `difficulty_level`, `estimated_minutes`, `learning_objectives`, `lesson_content`, `suspicious_example`, `warning_checklist`, `recommended_response`) VALUES
(4, 4, 'Email and Message Red Flags', 'email-message-red-flags', 'beginner', 10,
'Learn the top 7 red flags in digital messages, inspect email header anomalies, and spot spoofed sender identities.',
'### The 7 Critical Email Red Flags\r\n\r\nWhenever you inspect an unexpected email, scan for these indicators:\r\n\r\n1. **Mismatched Sender Address**: Display name says \"University Finance\" but actual email address is `finance-team882@gmail.com`.\r\n2. **Artificial Urgency & Threats**: Words like \"Immediate Action Required\", \"24 Hours Left\", or \"Legal Action Pending\".\r\n3. **Unsolicited Attachments**: Files with extensions like `.iso`, `.vbs`, `.exe`, `.scr`, or password-protected `.zip` files.\r\n4. **Generic Greetings**: \"Dear Customer\", \"Dear Student\", or \"Valued Member\" instead of your personalized name.\r\n5. **Grammar and Typographical Oddities**: Odd phrasing, strange capitalization, or machine-translated sentences.\r\n6. **Requests for Sensitive Action**: Asking you to verify your password, click an unprompted reset link, or purchase gift cards.\r\n7. **Inconsistent Hyperlinks**: The text says one domain, but hovering reveals a completely different target address.',
'\"From: IT Support <noreply-support@outlook-security-desk99.info>\r\nSubject: Account Will Be Terminated in 2 Hours\r\n\r\nDear User,\r\nWe found suspicious activities on your mailbox. Confirm your password now to avoid immediate termination.\r\n[Click Here to Prevent Deactivation]\"',
'- Vague recipient name (\"Dear User\").\r\n- Arbitrary 2-hour deadline designed to induce anxiety.\r\n- Sender domain has no relation to the university IT department.\r\n- Legitimate IT departments will NEVER ask you to click a link to prevent deactivation.',
'Flag and report the email immediately. Do not reply or forward to peers.')

ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

INSERT INTO `learning_modules` (`id`, `module_number`, `title`, `slug`, `difficulty_level`, `estimated_minutes`, `learning_objectives`, `lesson_content`, `suspicious_example`, `warning_checklist`, `recommended_response`) VALUES
(5, 5, 'Fake Rewards and Prize Scams', 'fake-rewards-and-prize-scams', 'beginner', 10,
'Identify advance-fee fraud, fake student grants, lottery lures, and fraudulent hardware giveaways.',
'### The Psychology of \"Free Money\"\r\n\r\nOne of the most persistent phishing vectors targeting students is the promise of unexpected financial gain: scholarship grants, government stimulus rewards, research awards, or cryptocurrency giveaways.\r\n\r\n### How Fake Reward Scams Function:\r\n\r\n1. **The Hook**: You receive a notification claiming you won or were selected for an award you never applied for.\r\n2. **The Verification Trap**: You are asked to \"verify eligibility\" by submitting your student ID, national ID, or phone number.\r\n3. **The Advance Fee or Credential Harvest**: You are told to pay a \"processing fee\" or log in with your primary student account to release funds.\r\n4. **The Disappearance**: The attacker captures your credentials or money and ceases contact.\r\n\r\n**Golden Rule of Cybersecurity**: If it seems too good to be true, it almost certainly is a trap.',
'\"Subject: Congratulations! Selected for $500 Tech Grant\r\n\r\nYou have been chosen by the East African Tech Committee to receive $500. Only 10 students were picked from Hormuud University. Claim before midnight.\r\n[Claim $500 Reward Now]\"',
'- You never applied for this grant or contest.\r\n- Strict deadline creates fear of missing out (FOMO).\r\n- The organization is fictional or unaffiliated with the university.\r\n- Genuine university grants are credited directly to tuition ledgers through the official finance office.',
'Recognize that authentic organizations do not disburse cash awards via random unsolicited email links. Report to CyberShield.')

ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

INSERT INTO `learning_modules` (`id`, `module_number`, `title`, `slug`, `difficulty_level`, `estimated_minutes`, `learning_objectives`, `lesson_content`, `suspicious_example`, `warning_checklist`, `recommended_response`) VALUES
(6, 6, 'Password Safety and Multi-Factor Authentication', 'password-safety-mfa', 'intermediate', 15,
'Learn modern password hygiene, passphrases, credential stuffing defense, and how Multi-Factor Authentication (MFA) neutralizes stolen credentials.',
'### Beyond Simple Passwords\r\n\r\nWeak, reused passwords remain the easiest path into protected networks. Once an attacker obtains a single password from a breach, they conduct **Credential Stuffing**—automating login attempts across hundreds of other services.\r\n\r\n### Best Practices for Password Defense:\r\n\r\n- **Use Long Passphrases**: Use four or more random words (e.g. `banana-bicycle-monsoon-galaxy`). Length beats arbitrary complexity.\r\n- **Never Reuse Passwords**: Each university, banking, and personal account must possess a unique password.\r\n- **Deploy a Password Manager**: Let software generate and store 20+ character random strings.\r\n\r\n### Multi-Factor Authentication (MFA):\r\n\r\nMFA requires two or more evidence factors: something you know (password), something you have (hardware key or authenticator app), and something you are (biometrics). Even if an attacker captures your password through phishing, MFA blocks unauthorized access in 99% of automated attacks!\r\n\r\n**Warning on MFA Fatigue**: If your phone buzzes repeatedly with authentication prompts you did not trigger, reject them and change your password immediately.',
'\"Please authenticate your campus account. Type your password and current SMS OTP code to confirm system migration.\"',
'- Legitimate systems never ask you to divulge OTP codes in plain emails or chats.\r\n- Attackers use Reverse Proxies (e.g. Evilginx) to intercept session cookies in real-time if users do not use FIDO2/WebAuthn keys.',
'Use authenticator apps (TOTP) or hardware tokens instead of SMS where possible. Never share verification codes with anyone, including IT staff.')

ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

INSERT INTO `learning_modules` (`id`, `module_number`, `title`, `slug`, `difficulty_level`, `estimated_minutes`, `learning_objectives`, `lesson_content`, `suspicious_example`, `warning_checklist`, `recommended_response`) VALUES
(7, 7, 'Account Impersonation', 'account-impersonation', 'intermediate', 12,
'Recognize Business Email Compromise (BEC), spear phishing against campus leadership, and social media clone accounts.',
'### Impersonation and Spear Phishing\r\n\r\nUnlike mass spam, **Spear Phishing** is tailored specifically to an individual or small group. Attackers impersonate individuals in positions of leadership or trust.\r\n\r\n### Common Impersonation Scenarios:\r\n\r\n1. **Professor / Dean Impersonation**: An email pretending to be your professor asking for \"a quick favor\" or requesting you purchase electronic vouchers.\r\n2. **Student Class Representative Clone**: A message on WhatsApp or Telegram pretending to be the class rep sharing \"exam leaks\" or \"notes\" packed with malware.\r\n3. **Vendor / Contractor Impersonation**: Fraudulent payment instructions asking finance staff to update banking details.',
'\"Subject: Quick favor from Dr. Liban\r\nFrom: liban.mohamed.hu@gmail.com\r\n\r\nHi Farah, I am currently in an urgent academic conference and cannot make calls. I need you to purchase three Google Play cards for guest speakers. I will reimburse you first thing tomorrow morning.\"',
'- External free webmail provider (`@gmail.com`) rather than the official university domain (`@hu.edu.so`).\r\n- Request for immediate financial action or non-traceable gift cards.\r\n- Claim of being unavailable by voice call to discourage verification.',
'Always verify out-of-band: call the individual using a known telephone number or verify in person before taking financial or administrative action.')

ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

INSERT INTO `learning_modules` (`id`, `module_number`, `title`, `slug`, `difficulty_level`, `estimated_minutes`, `learning_objectives`, `lesson_content`, `suspicious_example`, `warning_checklist`, `recommended_response`) VALUES
(8, 8, 'Safe Mobile Phone Practices', 'safe-mobile-phone-practices', 'beginner', 10,
'Defend against smishing (SMS phishing), malicious mobile apps, rogue Wi-Fi access points, and QR code manipulation (quishing).',
'### Mobile Threat Vectors\r\n\r\nSmartphones are our primary computing devices, yet they have smaller screens that make inspecting URLs and certificates more challenging.\r\n\r\n### Primary Mobile Attack Vectors:\r\n\r\n1. **Smishing (SMS Phishing)**: Messages claiming your mobile money wallet (e.g. EVC Plus, Zaad, Sahal) is frozen or received unauthorized funds.\r\n2. **Quishing (QR Code Phishing)**: Attackers paste fraudulent QR code stickers over authentic cafeteria or campus payment codes, redirecting users to malicious harvesting pages.\r\n3. **Rogue Wi-Fi Access Points**: Connecting to open, unencrypted networks like \"Free_Campus_WiFi\" allows attackers to perform Man-in-the-Middle (MitM) packet inspection.\r\n4. **Sideloaded Applications**: Installing `.apk` files from third-party websites bypassing Google Play or App Store vetting.',
'\"SMS from +252-61-XXXXXXX:\r\nYour mobile money account has been temporarily restricted due to suspicious activity. Verify your PIN at: https://evc-verify-portal.net/pin\"',
'- Requests for PINs, OTPs, or financial secrets via web links.\r\n- Unofficial domain name mimicking official telecom operators.\r\n- Sent from a standard individual mobile number rather than an official alphanumeric shortcode.',
'Never enter banking or mobile money PINs on web pages. Official telecom operators will never ask for your PIN via SMS link.')

ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

INSERT INTO `learning_modules` (`id`, `module_number`, `title`, `slug`, `difficulty_level`, `estimated_minutes`, `learning_objectives`, `lesson_content`, `suspicious_example`, `warning_checklist`, `recommended_response`) VALUES
(9, 9, 'Reporting Suspicious Messages', 'reporting-suspicious-messages', 'beginner', 8,
'Understand why rapid reporting matters, how security analysts process alerts, and how to use the CyberShield reporting tool.',
'### The Power of the Human Sensor Network\r\n\r\nIn a modern Security Operations Center (SOC), automated tools (firewalls, spam filters) catch only a portion of malicious messages. The fastest detection for new, customized attacks comes from **alert users who report suspicious activity**.\r\n\r\n### What Happens When You Report:\r\n\r\n1. **Triage**: CyberShield alerts the SOC analysts with metadata and sender indicators.\r\n2. **Blast Radius Analysis**: Analysts search email logs to see if other students or faculty received identical lures.\r\n3. **Defensive Containment**: The malicious domain is blocked on campus DNS resolvers and the message is purged from other mailboxes.\r\n4. **Attribution & Education**: The incident becomes a teaching case study for future awareness simulations.\r\n\r\nReporting a message does not get you into trouble; it helps protect your entire university community.',
'\"When you encounter any message that triggers your intuition or exhibits red flags, do not delete it in silence. Use the CyberShield Report feature!\"',
'- Fast reporting protects peers who might not spot the warning signs.\r\n- Include the email subject, reason for suspicion, and sender address.\r\n- Never forward malicious attachments to friends or group chats.',
'Click \"Report a Suspicious Message\" in your CyberShield portal or use the quick report action on the simulation banner.')

ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

INSERT INTO `learning_modules` (`id`, `module_number`, `title`, `slug`, `difficulty_level`, `estimated_minutes`, `learning_objectives`, `lesson_content`, `suspicious_example`, `warning_checklist`, `recommended_response`) VALUES
(10, 10, 'Incident Response Fundamentals', 'incident-response-fundamentals', 'intermediate', 15,
'Learn standard Incident Response phases (NIST / SANS), containment strategies, evidence preservation, and student reporting responsibilities.',
'### The 6 Phases of Incident Response (NIST SP 800-61)\r\n\r\nWhen a security breach or accidental link click occurs, following a structured process prevents panic:\r\n\r\n1. **Preparation**: Maintaining tools, backups, security training, and incident handling guidelines.\r\n2. **Identification / Detection**: Recognizing that an anomaly or security event has occurred (e.g. unexpected password reset notification).\r\n3. **Containment**: Isolating affected systems (e.g. disconnecting from Wi-Fi, terminating active sessions) to prevent lateral spread.\r\n4. **Eradication**: Removing artifacts, terminating unauthorized sessions, resetting compromised credentials.\r\n5. **Recovery**: Restoring systems to normal operational state and verifying monitoring.\r\n6. **Lessons Learned**: Conducting a post-incident review to strengthen defenses.\r\n\r\n### What to do if you accidentally clicked a malicious link:\r\n\r\n- Do not cover it up. Security teams appreciate immediate, honest disclosure.\r\n- Immediately disconnect your device from the campus network.\r\n- Change your account password from a different, uncompromised device.\r\n- Notify the university IT Helpdesk / SOC.',
'\"I clicked a link in an email 10 minutes ago, realized it was fake, and closed the tab. Should I just pretend nothing happened?\"',
'- Failing to report gives attackers time to exploit harvested sessions.\r\n- Modern session theft attacks can capture session tokens even without typing passwords if malware is downloaded.',
'Immediately notify the SOC or your instructor. Timely reporting turns a potential catastrophic breach into a minor handled incident.')

ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- Quizzes (Linked 1-to-1 with the 10 learning modules)
INSERT INTO `quizzes` (`id`, `module_id`, `title`, `description`, `passing_score`) VALUES
(1, 1, 'Knowledge Check: Social Engineering', 'Evaluate your understanding of social engineering tactics and emotional exploitation vectors.', 70),
(2, 2, 'Knowledge Check: Phishing Anatomy', 'Test your ability to identify stages in the phishing attack lifecycle.', 70),
(3, 3, 'Knowledge Check: URL Inspection Mastery', 'Test your skill in dissecting deceptive URLs and spotting domain masquerading.', 70),
(4, 4, 'Knowledge Check: Email Red Flags', 'Evaluate your ability to detect sender spoofing and suspicious message attributes.', 70),
(5, 5, 'Knowledge Check: Reward & Grant Scams', 'Identify advance-fee fraud and deceptive financial lures.', 70),
(6, 6, 'Knowledge Check: Password Defense & MFA', 'Verify your knowledge of strong passphrases and multi-factor authentication.', 70),
(7, 7, 'Knowledge Check: Account Impersonation', 'Detect spear phishing and executive impersonation techniques.', 70),
(8, 8, 'Knowledge Check: Mobile Security & Smishing', 'Recognize mobile money scams, rogue Wi-Fi, and QR code manipulation.', 70),
(9, 9, 'Knowledge Check: Suspicious Message Reporting', 'Understand incident triage and the responsibilities of a cybersecurity defender.', 70),
(10, 10, 'Knowledge Check: Incident Response Fundamentals', 'Test your understanding of the NIST Incident Response lifecycle and containment procedures.', 70)
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- Quiz Questions and Options for Module 1: Social Engineering
INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question_text`, `explanation`, `sort_order`) VALUES
(1, 1, 'Which psychological trigger is most commonly exploited when an email demands you act within 15 minutes or face account termination?', 'Urgency and fear are used to bypass critical thinking and prevent users from verifying the claim.', 1),
(2, 1, 'Why do social engineers target humans rather than attempting to break cryptographic algorithms directly?', 'Human cognitive biases and social habits are significantly easier to exploit than mathematically proven encryption standards like AES-256.', 2),
(3, 1, 'What is the best immediate response when an email from your university president urgently asks you to purchase gift cards?', 'Recognize this as a classic impersonation scam. Never buy gift cards for administrative requests and verify independently via official channels.', 3)
ON DUPLICATE KEY UPDATE `question_text` = VALUES(`question_text`);

INSERT INTO `quiz_options` (`id`, `question_id`, `option_text`, `is_correct`, `sort_order`) VALUES
(1, 1, 'Urgency and Fear', 1, 1),
(2, 1, 'Curiosity and Compassion', 0, 2),
(3, 1, 'Flattery and Politeness', 0, 3),
(4, 1, 'Technical Complexity', 0, 4),

(5, 2, 'Humans are the flexible element and often bypass procedures when pressured', 1, 1),
(6, 2, 'Modern encryption algorithms are broken by common desktop computers', 0, 2),
(7, 2, 'Firewalls cannot inspect human beings', 0, 3),
(8, 2, 'Operating systems prevent all network-level attacks automatically', 0, 4),

(9, 3, 'Recognize it as an impersonation scam and verify through an independent known channel', 1, 1),
(10, 3, 'Comply immediately because the president is in authority', 0, 2),
(11, 3, 'Reply to the email asking for confirmation of the card serial numbers', 0, 3),
(12, 3, 'Forward the email to fellow students asking if they received it', 0, 4)
ON DUPLICATE KEY UPDATE `option_text` = VALUES(`option_text`);

-- Quiz Questions and Options for Module 3: Recognizing Suspicious URLs
INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question_text`, `explanation`, `sort_order`) VALUES
(4, 3, 'In the URL \"https://portal.hu.edu.so.security-verify.net/login\", what is the actual primary domain you are connecting to?', 'The domain right before the first path slash is \"security-verify.net\". The string \"portal.hu.edu.so\" is just a misleading subdomain prefix.', 1),
(5, 3, 'Does the presence of HTTPS (the green padlock) guarantee that a website is genuine and safe?', 'No. HTTPS only proves that the connection between your browser and the server is encrypted. Anyone, including attackers, can obtain free SSL/TLS certificates.', 2),
(6, 3, 'What technique involves replacing Latin letters with visually identical characters from other alphabets?', 'An IDN Homograph attack uses Unicode characters from alphabets like Cyrillic to deceive the human eye.', 3)
ON DUPLICATE KEY UPDATE `question_text` = VALUES(`question_text`);

INSERT INTO `quiz_options` (`id`, `question_id`, `option_text`, `is_correct`, `sort_order`) VALUES
(13, 4, 'security-verify.net', 1, 1),
(14, 4, 'hu.edu.so', 0, 2),
(15, 4, 'portal.hu.edu.so', 0, 3),
(16, 4, 'login.hu.edu.so', 0, 4),

(17, 5, 'No, HTTPS only encrypts communication; attackers frequently use SSL certificates on phishing domains', 1, 1),
(18, 5, 'Yes, only verified university and banking websites can obtain HTTPS certificates', 0, 2),
(19, 5, 'Yes, the browser blocks phishing websites automatically whenever HTTPS is active', 0, 3),
(20, 5, 'No, HTTPS is an outdated protocol replaced by FTPS', 0, 4),

(21, 6, 'Homograph Attack', 1, 1),
(22, 6, 'SQL Injection', 0, 2),
(23, 6, 'Cross-Site Scripting (XSS)', 0, 3),
(24, 6, 'Man-in-the-Middle (MitM)', 0, 4)
ON DUPLICATE KEY UPDATE `option_text` = VALUES(`option_text`);

-- Quiz Questions for Module 5: Fake Rewards
INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question_text`, `explanation`, `sort_order`) VALUES
(7, 5, 'An email congratulates you on receiving a $500 university tech innovation reward you never applied for. What is the safest assumption?', 'Legitimate awards are not distributed randomly via unsolicited email links requiring urgent external clicks.', 1),
(8, 5, 'What is the primary indicator of an advance-fee fraud scheme?', 'Demanding an upfront fee, deposit, or credential login before \"releasing\" a supposed prize.', 2)
ON DUPLICATE KEY UPDATE `question_text` = VALUES(`question_text`);

INSERT INTO `quiz_options` (`id`, `question_id`, `option_text`, `is_correct`, `sort_order`) VALUES
(25, 7, 'It is a phishing simulation or scam lure exploiting optimism and reward anticipation', 1, 1),
(26, 7, 'You should quickly claim the money before someone else takes your spot', 0, 2),
(27, 7, 'You should send your bank account details right away', 0, 3),
(28, 7, 'Forward it to your friends so they can claim $500 as well', 0, 4),

(29, 8, 'Requiring a processing fee or credential verification before releasing a prize', 1, 1),
(30, 8, 'Sending a check through the physical postal service', 0, 2),
(31, 8, 'Requiring an in-person interview at the faculty dean office', 0, 3),
(32, 8, 'Publishing winner names on the official university portal', 0, 4)
ON DUPLICATE KEY UPDATE `option_text` = VALUES(`option_text`);

-- Suspicious Message Reports (Sample reports from participants)
INSERT INTO `suspicious_message_reports` (`id`, `campaign_id`, `participant_token`, `user_id`, `reporter_name`, `reason`, `message_subject`, `explanation`, `status`, `resolution_notes`, `reviewed_by`, `reviewed_at`) VALUES
(1, 1, 'tok_hu_cs_003_shield', 4, 'Ahmed Nur (Participant-Shield-03)', 'unrealistic_reward', 'Congratulations! You have been selected to receive a $500 research grant', 'Sender domain is grants-hormuud-awards.org which is not our official university domain. Also promises $500 for no reason.', 'resolved', 'Verified educational phishing simulation. Good catch by student on spotting anomalous domain and unrealistic reward lure.', 1, NOW() - INTERVAL 4 DAY),
(2, 2, 'tok_hu_cs_001_kestrel', 4, 'Farah Abdullahi (Participant-Kestrel-01)', 'urgent_threat', 'URGENT: Unauthorized access detected on your student portal account', 'The email uses urgent threats (account deactivation in 30 mins) and points to campus-auth-check.net instead of hu.edu.so.', 'reviewed', 'Student identified the urgency lever and unverified external domain. Flagged as confirmed simulation report.', 1, NOW() - INTERVAL 2 DAY),
(3, 3, 'tok_hu_cs_006_sentinel', NULL, 'Hodan Warsame (Participant-Sentinel-06)', 'unknown_sender', 'Package Delivery Exception: Address clarification required for your package', 'I did not order any international parcels to the university reception. The link points to fastdelivery-hub.cc.', 'under_review', NULL, NULL, NULL)
ON DUPLICATE KEY UPDATE `reporter_name` = VALUES(`reporter_name`);

-- Participant Learning Progress (Demo student progress)
INSERT INTO `participant_learning_progress` (`id`, `module_id`, `participant_token`, `user_id`, `status`, `completed_at`) VALUES
(1, 1, 'tok_hu_cs_001_kestrel', 4, 'completed', NOW() - INTERVAL 5 DAY),
(2, 5, 'tok_hu_cs_001_kestrel', 4, 'completed', NOW() - INTERVAL 5 DAY),
(3, 2, 'tok_hu_cs_001_kestrel', 4, 'completed', NOW() - INTERVAL 4 DAY),
(4, 3, 'tok_hu_cs_001_kestrel', 4, 'in_progress', NULL),
(5, 1, 'tok_hu_cs_002_falcon', NULL, 'completed', NOW() - INTERVAL 3 DAY),
(6, 1, 'tok_hu_cs_003_shield', NULL, 'completed', NOW() - INTERVAL 4 DAY),
(7, 3, 'tok_hu_cs_003_shield', NULL, 'completed', NOW() - INTERVAL 3 DAY),
(8, 4, 'tok_hu_cs_004_vanguard', NULL, 'completed', NOW() - INTERVAL 2 DAY)
ON DUPLICATE KEY UPDATE `status` = VALUES(`status`);

-- Quiz Attempts (Realistic student quiz scores)
INSERT INTO `quiz_attempts` (`id`, `quiz_id`, `participant_token`, `user_id`, `score`, `total_questions`, `percentage`, `passed`, `answers_json`, `completed_at`) VALUES
(1, 1, 'tok_hu_cs_001_kestrel', 4, 3, 3, 100.00, 1, '{"1":1,"2":5,"3":9}', NOW() - INTERVAL 5 DAY),
(2, 5, 'tok_hu_cs_001_kestrel', 4, 2, 2, 100.00, 1, '{"7":25,"8":29}', NOW() - INTERVAL 5 DAY),
(3, 3, 'tok_hu_cs_001_kestrel', 4, 2, 3, 66.67, 0, '{"4":13,"5":17,"6":22}', NOW() - INTERVAL 4 DAY),
(4, 1, 'tok_hu_cs_002_falcon', NULL, 3, 3, 100.00, 1, '{"1":1,"2":5,"3":9}', NOW() - INTERVAL 3 DAY),
(5, 3, 'tok_hu_cs_003_shield', NULL, 3, 3, 100.00, 1, '{"4":13,"5":17,"6":21}', NOW() - INTERVAL 3 DAY)
ON DUPLICATE KEY UPDATE `percentage` = VALUES(`percentage`);

-- Audit Logs (Administrative trail)
INSERT INTO `audit_logs` (`id`, `user_id`, `username`, `action`, `entity_type`, `entity_id`, `details_json`, `ip_address`, `created_at`) VALUES
(1, 1, 'superadmin', 'system_initialized', 'system', '1', '{"version":"1.0.0","environment":"local_xampp"}', '127.0.0.1', NOW() - INTERVAL 14 DAY),
(2, 1, 'superadmin', 'campaign_created', 'campaign', '1', '{"campaign_name":"Simulation 1: The $500 Tech Grant Lure","scenario":"Fake $500 reward"}', '127.0.0.1', NOW() - INTERVAL 5 DAY),
(3, 1, 'superadmin', 'campaign_authorized', 'campaign', '1', '{"authorized_by":"superadmin","target_group":"HU Cyber Security Cohort 2026"}', '127.0.0.1', NOW() - INTERVAL 5 DAY),
(4, 1, 'superadmin', 'campaign_started', 'campaign', '1', '{"started_by":"superadmin","participants_count":4}', '127.0.0.1', NOW() - INTERVAL 5 DAY),
(5, 2, 'manager', 'campaign_created', 'campaign', '2', '{"campaign_name":"Simulation 2: Emergency IT Password Reset Warning"}', '127.0.0.1', NOW() - INTERVAL 3 DAY),
(6, 1, 'superadmin', 'campaign_authorized', 'campaign', '2', '{"authorized_by":"superadmin"}', '127.0.0.1', NOW() - INTERVAL 3 DAY),
(7, 2, 'manager', 'campaign_started', 'campaign', '2', '{"started_by":"manager"}', '127.0.0.1', NOW() - INTERVAL 3 DAY),
(8, 1, 'superadmin', 'report_reviewed', 'suspicious_report', '1', '{"status":"resolved","notes":"Verified educational simulation."}', '127.0.0.1', NOW() - INTERVAL 4 DAY),
(9, 1, 'superadmin', 'participant_imported', 'participant', '10', '{"imported_count":10,"group_id":1}', '127.0.0.1', NOW() - INTERVAL 6 DAY)
ON DUPLICATE KEY UPDATE `action` = VALUES(`action`);
