<?php
use App\Helpers\ViewHelper;
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark" id="csHtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberShield — Cybersecurity Awareness & Phishing Simulation Platform | Hormuud University</title>
    <meta name="description" content="Authorized cybersecurity awareness, educational social engineering simulations, and defensive readiness platform for Computer Science and IT students at Hormuud University.">
    
    <link rel="stylesheet" href="<?= ViewHelper::asset('css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= ViewHelper::asset('css/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= ViewHelper::asset('css/cybershield.css') ?>?v=<?= time() ?>">
    
    <link rel="icon" type="image/svg+xml" href="<?= ViewHelper::asset('images/logo.svg') ?>">
    <style>
        .text-light { color: var(--cs-text-primary) !important; }
        .text-secondary { color: var(--cs-text-secondary) !important; }
        .metric-label { color: var(--cs-metric-label) !important; font-weight: 800 !important; font-size: 0.82rem !important; text-transform: uppercase !important; letter-spacing: 0.06em !important; text-shadow: 0 1px 4px rgba(0,0,0,0.7); display: block; }
        .metric-sub  { color: var(--cs-metric-sub) !important; font-size: 0.7rem !important; font-weight: 600 !important; display: inline-block; background: rgba(56,189,248,0.1); border: 1px solid rgba(56,189,248,0.2); border-radius: 4px; padding: 0.12rem 0.4rem; margin-top: 0.3rem; }
        [data-theme="light"] .metric-sub { background: rgba(2,132,199,0.1); border-color: rgba(2,132,199,0.25); }
    </style>
</head>
<body class="bg-navy text-light">

<!-- =========================================================================
     1. Sticky Navigation Bar
     ========================================================================= -->
<nav class="navbar navbar-expand-lg sticky-top py-3 cs-landing-nav" id="landingNavbar">
    <div class="container">
        <!-- Logo + Univ Badge -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= ViewHelper::url('') ?>">
            <img src="<?= ViewHelper::asset('images/logo.svg') ?>" alt="CyberShield Logo" style="height: 44px;">
            <div class="d-none d-xl-flex flex-column" style="line-height:1.1;">
                <span style="font-size:0.7rem; color:var(--cs-cyan); font-weight:700; letter-spacing:0.04em;">
                    <span class="lang-en">HORMUUD UNIVERSITY</span>
                    <span class="lang-so">JAAMACADDA HORMUUD</span>
                </span>
                <span style="font-size:0.62rem; color:var(--cs-text-muted); font-weight:500;">
                    <span class="lang-en">Faculty of CS &amp; Cyber Security</span>
                    <span class="lang-so">Kuliyada CS &amp; Amniga</span>
                </span>
            </div>
        </a>

        <button class="navbar-toggler border-navy" style="color:var(--cs-cyan);" type="button" data-bs-toggle="collapse" data-bs-target="#landingNav">
            <i class="bi bi-list fs-3"></i>
        </button>

        <div class="collapse navbar-collapse" id="landingNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-2 small fw-semibold">
                <li class="nav-item"><a class="nav-link hover-cyan" style="color:var(--cs-text-secondary);" href="#overview"><span class="lang-en">Overview</span><span class="lang-so">Guud Ahaan</span></a></li>
                <li class="nav-item"><a class="nav-link hover-cyan" style="color:var(--cs-text-secondary);" href="#phishing-lab"><span class="lang-en">Phishing Anatomy</span><span class="lang-so">Cilmiga Phishing-ka</span></a></li>
                <li class="nav-item"><a class="nav-link hover-cyan" style="color:var(--cs-text-secondary);" href="#url-inspector"><span class="lang-en">URL Inspector</span><span class="lang-so">Baadhida URL-ka</span></a></li>
                <li class="nav-item"><a class="nav-link hover-cyan" style="color:var(--cs-text-secondary);" href="#academy"><span class="lang-en">Academy</span><span class="lang-so">Akademiyada</span></a></li>
                <li class="nav-item"><a class="nav-link hover-cyan" style="color:var(--cs-text-secondary);" href="#psychology"><span class="lang-en">Attack Psychology</span><span class="lang-so">Naftaanka Weerarada</span></a></li>
                <li class="nav-item"><a class="nav-link hover-cyan" style="color:var(--cs-text-secondary);" href="#soc-metrics"><span class="lang-en">SOC Metrics</span><span class="lang-so">Xogta SOC-ka</span></a></li>
            </ul>

            <div class="d-flex align-items-center gap-2">
                <!-- Language toggle -->
                <button class="btn-lang-toggle" id="langToggle" onclick="toggleLang()" title="Switch Language / Bedel Luqadda">
                    <span id="langFlag">&#127474;&#127476;</span>
                    <span id="langLabel">SO</span>
                </button>
                <!-- Theme toggle -->
                <button class="btn-theme-toggle" id="themeToggle" onclick="toggleTheme()" title="Toggle Light / Dark">
                    <i class="bi bi-moon-fill" id="themeIcon"></i>
                </button>

                <a href="<?= ViewHelper::url('report-message') ?>" class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1">
                    <i class="bi bi-shield-exclamation"></i>
                    <span class="lang-en">Report Threat</span>
                    <span class="lang-so">Gudbi Khatar</span>
                </a>

                <?php if ($currentUser): ?>
                    <a href="<?= ViewHelper::url($currentUser['role'] === 'student' ? 'student/dashboard' : 'dashboard') ?>" class="btn btn-cyan btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-speedometer2"></i>
                        <span class="lang-en">Dashboard</span>
                        <span class="lang-so">Koontaradhada</span>
                    </a>
                <?php else: ?>
                    <a href="<?= ViewHelper::url('login') ?>" class="btn btn-cyan btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span class="lang-en">Sign In</span>
                        <span class="lang-so">Gal</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- =========================================================================
     2. Hero Section
     ========================================================================= -->
<section class="hero-cyber-section text-center position-relative" id="overview">
    <div class="container position-relative" style="z-index: 2;">
        <!-- University Badge -->
        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-surface border border-cyan-subtle mb-4">
            <span class="pulse-indicator"></span>
            <span class="small text-cyan fw-bold">
                <span class="lang-en">Hormuud University</span>
                <span class="lang-so">Jaamacadda Hormuud</span>
            </span>
            <span class="text-muted small">|</span>
            <span class="small text-secondary">
                <span class="lang-en">Faculty of Computer Science &amp; IT — Cyber Security Lab</span>
                <span class="lang-so">Kulliyadda CS &amp; IT — Shaybaadhka Amniga Dijitaalka</span>
            </span>
        </div>

        <!-- Main Title -->
        <h1 class="display-4 fw-bolder text-light mb-3" style="letter-spacing: -0.02em;">
            <span class="lang-en">Detect. Educate. <span class="text-cyan text-glow">Defend.</span></span>
            <span class="lang-so">Ogow. Baro. <span class="text-cyan text-glow">Difaac.</span></span>
        </h1>
        <div class="somali-sub mb-3 fs-5">
            <span class="lang-en">Barta Wacyigelinta Amniga Dijitaalka ah iyo Tababarka Ka Hortagga Weerarada Phishing-ka.</span>
            <span class="lang-so">Platform-ka Casriga ah ee Barashada Amniga Macluumaadka iyo Ka Hortagga Khiyaanooyinka Internet-ka.</span>
        </div>

        <!-- Subtitle -->
        <p class="lead text-secondary mx-auto mb-4" style="max-width: 820px; font-size: 1.15rem; line-height: 1.6;">
            <span class="lang-en">A premier Security Operations Center (SOC) awareness platform engineered for students studying Computer Science and Cyber Security at Hormuud University. Discover how attackers manipulate human psychology, dissect deceptive URLs in real time, and reinforce organizational defense readiness.</span>
            <span class="lang-so">Nidaam heer sare ah oo loogu talagalay ardayda barata Computer Science iyo Cyber Security ee Jaamacadda Hormuud. Baro sida weeraryahannadu u adeegsadaan khiyaanooyinka nafsiga ah, u baadh linkiyada been abuurka ah, una adkee difaaca macluumaadka jaamacadda.</span>
        </p>

        <!-- CTA Buttons -->
        <div class="d-flex flex-wrap justify-content-center gap-3 mb-5">
            <?php if ($currentUser): ?>
                <a href="<?= ViewHelper::url($currentUser['role'] === 'student' ? 'student/dashboard' : 'dashboard') ?>" class="btn btn-cyan px-4 py-3 fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock-fill fs-5"></i>
                    <span><span class="lang-en">Access SOC Operations Console</span><span class="lang-so">Gal Barta Howlgalka SOC</span></span>
                </a>
            <?php else: ?>
                <a href="<?= ViewHelper::url('login') ?>" class="btn btn-cyan px-4 py-3 fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-box-arrow-in-right fs-5"></i>
                    <span><span class="lang-en">Sign In to Console</span><span class="lang-so">Gal Barta / Soo Gal</span></span>
                </a>
            <?php endif; ?>
            <a href="#url-inspector" class="btn btn-outline-cyan px-4 py-3 fw-bold d-flex align-items-center gap-2">
                <i class="bi bi-search fs-5"></i>
                <span><span class="lang-en">Test Interactive URL Inspector</span><span class="lang-so">Baaraha URL-yada Tooska ah</span></span>
            </a>
            <a href="#phishing-lab" class="btn btn-surface px-4 py-3 fw-semibold d-flex align-items-center gap-2">
                <i class="bi bi-binoculars text-warning fs-5"></i>
                <span><span class="lang-en">Phishing Anatomy Lab</span><span class="lang-so">Shaybaadhka Phishing-ka</span></span>
            </a>
        </div>

        <!-- Live Database Telemetry Metrics -->
        <div class="row g-3 justify-content-center" id="soc-metrics">
            <div class="col-6 col-md-2">
                <div class="card-glass p-3 text-center h-100 d-flex flex-column justify-content-center">
                    <div class="h3 fw-bold text-cyan mb-1"><?= $stats['participants'] ?></div>
                    <span class="metric-label">
                        <span class="lang-en">Active Students</span>
                        <span class="lang-so">Ardayda Firfircoon</span>
                    </span>
                    <span class="metric-sub">
                        <span class="lang-en">Enrolled</span>
                        <span class="lang-so">Diwaangashan</span>
                    </span>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card-glass p-3 text-center h-100 d-flex flex-column justify-content-center">
                    <div class="h3 fw-bold text-light mb-1"><?= $stats['campaigns'] ?></div>
                    <span class="metric-label">
                        <span class="lang-en">Simulations</span>
                        <span class="lang-so">Dhoolatuska</span>
                    </span>
                    <span class="metric-sub">
                        <span class="lang-en">Campaigns</span>
                        <span class="lang-so">Olalayaasha</span>
                    </span>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card-glass p-3 text-center h-100 d-flex flex-column justify-content-center">
                    <div class="h3 fw-bold text-emerald mb-1"><?= $stats['modules'] ?></div>
                    <span class="metric-label">
                        <span class="lang-en">Academy Modules</span>
                        <span class="lang-so">Cutubada Akademiyada</span>
                    </span>
                    <span class="metric-sub">
                        <span class="lang-en">10 Lessons</span>
                        <span class="lang-so">10-ka Casharro</span>
                    </span>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card-glass p-3 text-center h-100 d-flex flex-column justify-content-center">
                    <div class="h3 fw-bold text-warning mb-1"><?= $stats['reports'] ?></div>
                    <span class="metric-label">
                        <span class="lang-en">Threats Reported</span>
                        <span class="lang-so">Weerarada La Sheegay</span>
                    </span>
                    <span class="metric-sub">
                        <span class="lang-en">Caught</span>
                        <span class="lang-so">La Qabtay</span>
                    </span>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card-glass p-3 text-center h-100 d-flex flex-column justify-content-center">
                    <div class="h3 fw-bold text-cyan mb-1"><?= round($stats['quiz_avg']) ?>%</div>
                    <span class="metric-label">
                        <span class="lang-en">Avg Quiz Score</span>
                        <span class="lang-so">Celceliska Imtixaanka</span>
                    </span>
                    <span class="metric-sub">
                        <span class="lang-en">Accuracy</span>
                        <span class="lang-so">Saxnaanta</span>
                    </span>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="card-glass p-3 text-center h-100 d-flex flex-column justify-content-center">
                    <div class="h3 fw-bold text-emerald mb-1">0</div>
                    <span class="metric-label">
                        <span class="lang-en">Passwords Stolen</span>
                        <span class="lang-so">Furayaal La Xaday</span>
                    </span>
                    <span class="metric-sub">
                        <span class="lang-en">Zero Harvested</span>
                        <span class="lang-so">Wax La'aan</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     3. Interactive Phishing Anatomy Lab
     ========================================================================= -->
<section class="py-5 border-bottom border-navy bg-surface" id="phishing-lab">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge badge-soft-warning px-3 py-1 mb-2">
                <i class="bi bi-eye-fill me-1"></i> Interactive Security Demonstration
            </span>
            <h2 class="h2 fw-bold text-light mb-2">Anatomy of a Phishing Attack</h2>
            <div class="somali-sub mb-2">Falanqaynta Qaab-dhismeedka Weerarka Phishing-ka iyo Barashada Calaamadaha Halista Ah</div>
            <p class="text-secondary mx-auto small" style="max-width: 650px;">
                Click any of the highlighted <strong class="text-danger">Red Flag Hotspots</strong> on the simulated lure below to reveal how attackers engineer psychological traps.
            </p>
        </div>

        <div class="row g-4 align-items-center">
            <!-- Left: Simulated Email Client Interface -->
            <div class="col-lg-7">
                <div class="card card-soc shadow-lg border-navy">
                    <!-- Client Header Bar -->
                    <div class="p-3 bg-navy border-bottom border-navy d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle bg-danger" style="width: 10px; height: 10px;"></span>
                            <span class="rounded-circle bg-warning" style="width: 10px; height: 10px;"></span>
                            <span class="rounded-circle bg-success" style="width: 10px; height: 10px;"></span>
                            <span class="small text-secondary ms-2">Mail Client Preview · Inbound Message</span>
                        </div>
                        <span class="badge bg-secondary-subtle text-secondary small">Simulation Mode</span>
                    </div>

                    <!-- Email Metadata -->
                    <div class="p-3 bg-surface border-bottom border-navy">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                            <div>
                                <span class="text-secondary small fw-bold">From:</span>
                                <span class="text-light small">Hormuud Innovation Foundation</span>
                                <span class="hotspot-pill ms-1" data-hotspot="sender">
                                    <i class="bi bi-exclamation-circle-fill"></i> &lt;rewards@grants-hormuud-awards.org&gt;
                                </span>
                            </div>
                            <span class="text-muted small">Today, 09:42 AM</span>
                        </div>
                        <div>
                            <span class="text-secondary small fw-bold">Subject:</span>
                            <span class="text-light fw-bold small">Congratulations! You have been selected to receive a $500 research grant</span>
                            <span class="hotspot-pill ms-1" data-hotspot="urgency">
                                <i class="bi bi-hourglass-split"></i> [Action Required: 4h Left]
                            </span>
                        </div>
                    </div>

                    <!-- Email Body -->
                    <div class="p-4 bg-white text-dark small" style="line-height: 1.6;">
                        <p class="mb-2">Dear Student,</p>
                        <p class="mb-3">
                            Congratulations! Your institutional registration was selected in the East African University Research Grant Pool. You are eligible to receive a 
                            <span class="hotspot-pill text-dark" data-hotspot="reward" style="background-color: #fee2e2; border-color: #ef4444;">
                                <strong>$500 Academic Tech Grant</strong>
                            </span>.
                        </p>
                        <p class="mb-3 text-muted">
                            Due to quota restrictions, awards not confirmed within <strong class="text-danger">4 hours</strong> will be permanently reallocated to an alternate candidate.
                        </p>
                        <div class="text-center my-4">
                            <span class="hotspot-pill p-2" data-hotspot="link" style="font-size: 0.9rem;">
                                <i class="bi bi-box-arrow-up-right me-1"></i> [Claim $500 Grant via Portal]
                            </span>
                            <div class="text-muted mt-1" style="font-size: 0.72rem;">
                                Target: <code>http://portal.hu.edu.so.verify-portal.net/auth</code>
                            </div>
                        </div>
                        <p class="mb-0 text-muted" style="font-size: 0.74rem;">
                            University Grants Registry · Non-transferable Student Notification
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right: Interactive Dynamic Red Flag Analysis Drawer -->
            <div class="col-lg-5">
                <div class="card card-soc h-100 border-cyan-subtle">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="fw-bold text-light"><i class="bi bi-cpu text-cyan me-2"></i>Live Threat Inspector</span>
                        <span class="badge badge-soft-cyan">Interactive</span>
                    </div>
                    <div class="card-body p-4">
                        <div id="inspectorDisplay">
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-hand-index-thumb fs-1 text-cyan d-block mb-2"></i>
                                <h3 class="h6 text-light fw-bold">Select a Red Flag on the Email</h3>
                                <p class="small text-secondary mb-0">Click any red indicator to see the defensive explanation and the psychological principle exploited.</p>
                            </div>
                        </div>

                        <!-- Hotspot Data Templates (Rendered by JS) -->
                        <div class="d-none" id="hotspotData">
                            <div data-id="sender">
                                <div class="badge badge-soft-danger mb-2"><i class="bi bi-shield-slash me-1"></i>Lookalike Domain Spoofing</div>
                                <h3 class="h5 fw-bold text-light mb-2">Sender Domain Mismatch</h3>
                                <div class="somali-sub mb-2">Cinwaanka Soo Diray Oo Been Abuur Ah</div>
                                <p class="small text-secondary mb-3">
                                    The sender address is <code class="text-cyan">@grants-hormuud-awards.org</code>. Notice that this is NOT the genuine university domain (<strong class="text-light">hu.edu.so</strong>). Attackers register similar sounding domains to deceive distracted readers.
                                </p>
                                <div class="p-2 rounded bg-surface border border-navy small text-light">
                                    <strong class="text-success"><i class="bi bi-shield-check me-1"></i>Defense Rule:</strong> Always inspect the root domain after the <code>@</code> symbol before trusting any campus message.
                                </div>
                            </div>

                            <div data-id="urgency">
                                <div class="badge badge-soft-warning mb-2"><i class="bi bi-hourglass-split me-1"></i>Psychological Trigger: Urgency</div>
                                <h3 class="h5 fw-bold text-light mb-2">Manufactured Time Panic</h3>
                                <div class="somali-sub mb-2">Degdegsiinyo Been Ah Oo Loo Sameeyay In Lagu Jahawareeriyo</div>
                                <p class="small text-secondary mb-3">
                                    Limiting the offer to <strong class="text-warning">4 hours</strong> forces an adrenaline response. Psychological urgency bypasses critical thinking and prevents the victim from independently verifying the claim with faculty advisors.
                                </p>
                                <div class="p-2 rounded bg-surface border border-navy small text-light">
                                    <strong class="text-success"><i class="bi bi-shield-check me-1"></i>Defense Rule:</strong> Legitimate academic grants never expire in 4 hours via unverified email links.
                                </div>
                            </div>

                            <div data-id="reward">
                                <div class="badge badge-soft-danger mb-2"><i class="bi bi-gift-fill me-1"></i>Psychological Trigger: Greed / FOMO</div>
                                <h3 class="h5 fw-bold text-light mb-2">Unrealistic Financial Incentive</h3>
                                <div class="somali-sub mb-2">Abaalmarin Lacageed Oo Aan Lagu Shaqaysan Oo Been Ah</div>
                                <p class="small text-secondary mb-3">
                                    Promises of <strong class="text-danger">$500 free grants</strong> trigger optimism bias. In advance-fee schemes, victims are lured to a fake portal where they are tricked into providing account credentials or paying a "verification fee."
                                </p>
                                <div class="p-2 rounded bg-surface border border-navy small text-light">
                                    <strong class="text-success"><i class="bi bi-shield-check me-1"></i>Defense Rule:</strong> If an unsolicited offer seems too good to be true, it is almost certainly a trap.
                                </div>
                            </div>

                            <div data-id="link">
                                <div class="badge badge-soft-danger mb-2"><i class="bi bi-link-45deg me-1"></i>Technical Exploit: Subdomain Masquerading</div>
                                <h3 class="h5 fw-bold text-light mb-2">Deceptive URL Prepending</h3>
                                <div class="somali-sub mb-2">Link Been Abuur Ah Oo Lagu Qariyay Magaca Jaamacadda</div>
                                <p class="small text-secondary mb-3">
                                    The link displays <code>portal.hu.edu.so.verify-portal.net</code>. The genuine destination domain is <strong class="text-danger">verify-portal.net</strong>, NOT Hormuud University! The university string is simply a subdomain trick.
                                </p>
                                <div class="p-2 rounded bg-surface border border-navy small text-light">
                                    <strong class="text-success"><i class="bi bi-shield-check me-1"></i>Defense Rule:</strong> Read URLs from right to left starting before the first single forward slash <code>/</code>.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     4. Live Interactive URL Anatomy Inspector Tool
     ========================================================================= -->
<section class="py-5 border-bottom border-navy bg-navy" id="url-inspector">
    <div class="container" style="max-width: 920px;">
        <div class="text-center mb-5">
            <span class="badge badge-soft-cyan px-3 py-1 mb-2">
                <i class="bi bi-terminal-fill me-1"></i> Live Cybersecurity Tool
            </span>
            <h2 class="h2 fw-bold text-light mb-2">Interactive URL Anatomy Inspector</h2>
            <div class="somali-sub mb-2">Qalabka Tooska Ah Ee Lagu Baaro Qaab-dhismeedka URL-yada Shaki Leh</div>
            <p class="text-secondary mx-auto small" style="max-width: 650px;">
                Enter any web address below or select a preset to analyze deceptive subdomain prepending, typosquatting, and true destination domains.
            </p>
        </div>

        <div class="card card-soc p-4 mb-4 border-cyan-subtle shadow-lg">
            <!-- URL Input and Preset Buttons -->
            <div class="mb-3">
                <label for="urlInput" class="form-label text-secondary small fw-semibold">Enter Web Address to Dissect:</label>
                <div class="input-group">
                    <span class="input-group-text bg-surface border-navy text-cyan"><i class="bi bi-link-45deg"></i></span>
                    <input type="text" class="form-control form-control-soc font-monospace" id="urlInput" value="https://portal.hu.edu.so.security-verify.net/auth/login" placeholder="https://...">
                    <button class="btn btn-cyan px-4 fw-bold" type="button" id="inspectBtn">
                        <i class="bi bi-cpu me-1"></i> Inspect Anatomy
                    </button>
                </div>
            </div>

            <!-- Quick Presets -->
            <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                <span class="text-muted small" style="font-size: 0.72rem;">Try Common Test Scenarios:</span>
                <button type="button" class="btn btn-surface btn-sm py-0 px-2 url-preset" data-url="https://portal.hu.edu.so.security-verify.net/auth/login" style="font-size: 0.75rem;">
                    Subdomain Prepending Scam
                </button>
                <button type="button" class="btn btn-surface btn-sm py-0 px-2 url-preset" data-url="https://hormud-university-awards.cc/claim" style="font-size: 0.75rem;">
                    Typosquatting Lookalike
                </button>
                <button type="button" class="btn btn-surface btn-sm py-0 px-2 url-preset" data-url="https://portal.hu.edu.so/student/dashboard" style="font-size: 0.75rem;">
                    Authentic Campus Domain
                </button>
            </div>

            <!-- Inspector Result Output Card -->
            <div id="inspectorResults" class="p-3 rounded bg-surface border border-navy">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="small fw-bold text-secondary text-uppercase" style="font-size: 0.72rem;">Dissection Analysis</span>
                    <span id="riskBadge" class="badge badge-danger-glow">High Phishing Risk</span>
                </div>

                <!-- URL Visual Tokenizer -->
                <div class="url-breakdown-box mb-3 d-flex flex-wrap gap-1 align-items-center" id="urlTokenizer">
                    <!-- Populated dynamically by JS -->
                </div>

                <!-- Technical Parameter Grid -->
                <div class="row g-2 text-secondary small">
                    <div class="col-md-4">
                        <div class="p-2 rounded bg-navy border border-navy">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">Protocol:</span>
                            <strong class="text-light" id="resProtocol">https://</strong>
                            <div class="text-muted mt-1" style="font-size: 0.68rem;">Encryption ≠ Authenticity</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-2 rounded bg-navy border border-navy">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">Misleading Subdomain Prefix:</span>
                            <strong class="text-warning" id="resSubdomain">portal.hu.edu.so</strong>
                            <div class="text-muted mt-1" style="font-size: 0.68rem;">Attacker lure string</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-2 rounded bg-navy border border-navy">
                            <span class="text-muted d-block" style="font-size: 0.7rem;">Actual Destination Host (Root):</span>
                            <strong class="text-danger" id="resHost">security-verify.net</strong>
                            <div class="text-muted mt-1" style="font-size: 0.68rem;">Where data is truly sent!</div>
                        </div>
                    </div>
                </div>

                <!-- Guidance Note -->
                <div class="p-2 rounded bg-navy border border-navy mt-3 small text-secondary" id="resAdvice">
                    <i class="bi bi-exclamation-triangle text-warning me-1"></i>
                    <strong>Risk Warning:</strong> The domain you are connecting to is <code>security-verify.net</code>. The preceding text <code>portal.hu.edu.so</code> is merely a subdomain belonging to the attacker.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     5. The 10 Cybersecurity Core Pillars (Academy Showcase)
     ========================================================================= -->
<section class="py-5 border-bottom border-navy bg-surface" id="academy">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge badge-soft-cyan px-3 py-1 mb-2">
                <i class="bi bi-mortarboard-fill me-1"></i> Full Academic Curriculum
            </span>
            <h2 class="h2 fw-bold text-light mb-2">The 10 Pillars of Cybersecurity Awareness</h2>
            <div class="somali-sub mb-2">10-ka Tiir Ee Wacyigelinta Amniga Dijitaalka Ah — Manhajka Jaamacadda Hormuud</div>
            <p class="text-secondary mx-auto small" style="max-width: 700px;">
                Comprehensive lessons, threat red-flag checklists, real-world case studies, and server-evaluated multiple choice assessments.
            </p>
        </div>

        <div class="row g-4">
            <?php foreach ($modules as $m): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card card-soc h-100 d-flex flex-column">
                        <div class="card-body p-4 flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge badge-soft-cyan">Pillar 0<?= $m['module_number'] ?></span>
                                <span class="badge bg-surface border border-navy text-secondary small">
                                    <i class="bi bi-clock me-1"></i><?= $m['estimated_minutes'] ?> mins
                                </span>
                            </div>

                            <h3 class="h5 fw-bold text-light mb-2"><?= ViewHelper::e($m['title']) ?></h3>

                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="badge badge-soft-primary small"><?= ucfirst($m['difficulty_level']) ?></span>
                                <span class="badge bg-surface border border-navy text-muted small">Knowledge Check Included</span>
                            </div>

                            <p class="text-secondary small mb-0" style="line-height: 1.5;">
                                <?= ViewHelper::truncate(strip_tags($m['learning_objectives']), 110) ?>
                            </p>
                        </div>

                        <div class="card-footer bg-surface border-top border-navy p-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted small" style="font-size: 0.72rem;">Hormuud CS-IT Lab</span>
                            <a href="<?= ViewHelper::url('academy/' . $m['slug']) ?>" class="btn btn-outline-cyan btn-sm">
                                <span>Inspect Lesson</span> <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- =========================================================================
     6. Social Engineering Psychology Matrix
     ========================================================================= -->
<section class="py-5 border-bottom border-navy bg-navy" id="psychology">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge badge-soft-warning px-3 py-1 mb-2">
                <i class="bi bi-diagram-3-fill me-1"></i> Human Defense Layer
            </span>
            <h2 class="h2 fw-bold text-light mb-2">Psychological Levers of Social Engineering</h2>
            <div class="somali-sub mb-2">Falanqaynta Sida Qaladka Aadanaha Looga Faa'iidaysto (Manipulative Levers)</div>
            <p class="text-secondary mx-auto small" style="max-width: 680px;">
                Attackers target human cognitive vulnerabilities rather than breaking encryption. Recognizing these psychological levers prevents successful manipulation.
            </p>
        </div>

        <div class="row g-4">
            <!-- Lever 1: Urgency -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-soc h-100 p-4 border-warning-subtle">
                    <div class="display-6 text-warning mb-3"><i class="bi bi-hourglass-split"></i></div>
                    <h3 class="h5 fw-bold text-light mb-1">1. Urgency (Degdegsiinyo)</h3>
                    <p class="text-muted small mb-2">"Act within 30 minutes or face deactivation!"</p>
                    <p class="small text-secondary mb-3">
                        Artificial deadlines stimulate adrenaline, impairing the prefrontal cortex where logical skepticism takes place.
                    </p>
                    <div class="p-2 rounded bg-surface border border-navy small text-light mt-auto">
                        <strong>Countermeasure:</strong> Take a deep breath. Stop and verify before clicking.
                    </div>
                </div>
            </div>

            <!-- Lever 2: Fear / Coercion -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-soc h-100 p-4 border-danger-subtle">
                    <div class="display-6 text-danger mb-3"><i class="bi bi-shield-x"></i></div>
                    <h3 class="h5 fw-bold text-light mb-1">2. Fear & Coercion (Cabsi)</h3>
                    <p class="text-muted small mb-2">"Legal action pending / Examination cancelled"</p>
                    <p class="small text-secondary mb-3">
                        Threats of disciplinary action or institutional suspension compel obedience to avoid consequences.
                    </p>
                    <div class="p-2 rounded bg-surface border border-navy small text-light mt-auto">
                        <strong>Countermeasure:</strong> Disciplinary notices are delivered in person or official portals.
                    </div>
                </div>
            </div>

            <!-- Lever 3: Greed & Rewards -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-soc h-100 p-4 border-cyan-subtle">
                    <div class="display-6 text-cyan mb-3"><i class="bi bi-gift-fill"></i></div>
                    <h3 class="h5 fw-bold text-light mb-1">3. Greed / Reward (Damac)</h3>
                    <p class="text-muted small mb-2">"Claim your $500 student tech grant"</p>
                    <p class="small text-secondary mb-3">
                        Exploits the fear of missing out (FOMO) and the human desire for unexpected financial gain.
                    </p>
                    <div class="p-2 rounded bg-surface border border-navy small text-light mt-auto">
                        <strong>Countermeasure:</strong> Never pay fees or submit credentials for unearned prizes.
                    </div>
                </div>
            </div>

            <!-- Lever 4: Authority -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-soc h-100 p-4 border-primary-subtle">
                    <div class="display-6 text-primary mb-3"><i class="bi bi-person-badge-fill"></i></div>
                    <h3 class="h5 fw-bold text-light mb-1">4. Authority (Awood / Maamul)</h3>
                    <p class="text-muted small mb-2">"Urgent request from the Dean / Department Head"</p>
                    <p class="small text-secondary mb-3">
                        Impersonates leadership figures to leverage workplace or university hierarchies.
                    </p>
                    <div class="p-2 rounded bg-surface border border-navy small text-light mt-auto">
                        <strong>Countermeasure:</strong> Verify out-of-band using known campus phone extensions.
                    </div>
                </div>
            </div>

            <!-- Lever 5: Curiosity -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-soc h-100 p-4 border-info-subtle">
                    <div class="display-6 text-info mb-3"><i class="bi bi-box-seam-fill"></i></div>
                    <h3 class="h5 fw-bold text-light mb-1">5. Curiosity (Xiiso)</h3>
                    <p class="text-muted small mb-2">"Parcel delivery exception / Confidential score sheet"</p>
                    <p class="small text-secondary mb-3">
                        Piques interest about unseen shipments, salary comparisons, or examination leaks.
                    </p>
                    <div class="p-2 rounded bg-surface border border-navy small text-light mt-auto">
                        <strong>Countermeasure:</strong> Delete unsolicited tracking attachments (.zip, .iso, .exe).
                    </div>
                </div>
            </div>

            <!-- Lever 6: Social Proof -->
            <div class="col-md-6 col-lg-4">
                <div class="card card-soc h-100 p-4 border-success-subtle">
                    <div class="display-6 text-success mb-3"><i class="bi bi-people-fill"></i></div>
                    <h3 class="h5 fw-bold text-light mb-1">6. Social Proof (Kalsooni Kooxeed)</h3>
                    <p class="text-muted small mb-2">"Your classmates already signed this petition"</p>
                    <p class="small text-secondary mb-3">
                        Leverages group conformity to convince users that an action is safe because peers performed it.
                    </p>
                    <div class="p-2 rounded bg-surface border border-navy small text-light mt-auto">
                        <strong>Countermeasure:</strong> Each individual must validate technical integrity independently.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     7. Ethical Declaration & Call to Action
     ========================================================================= -->
<section class="py-5 bg-surface border-bottom border-navy text-center">
    <div class="container" style="max-width: 800px;">
        <div class="display-5 text-cyan mb-3"><i class="bi bi-shield-check"></i></div>
        <h2 class="h2 fw-bold text-light mb-2">Authorized Educational Simulation Guarantee</h2>
        <div class="somali-sub mb-3">Ballanqaadka Amniga iyo Tababarka Sharciyaysan Ee Jaamacadda Hormuud</div>
        <p class="text-secondary small mb-4" style="line-height: 1.7;">
            CyberShield is an educational platform designed strictly for authorized cybersecurity training at <strong>Hormuud University</strong>. It contains zero credential-harvesting mechanisms, does not store passwords, and strictly complies with ethical cybersecurity research principles.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="<?= ViewHelper::url('login') ?>" class="btn btn-cyan px-4 py-2 fw-semibold">
                <i class="bi bi-box-arrow-in-right me-1"></i> Access Operations Console
            </a>
            <a href="<?= ViewHelper::url('report-message') ?>" class="btn btn-outline-warning px-4 py-2 fw-semibold">
                <i class="bi bi-shield-exclamation me-1"></i> Report Suspicious Threat
            </a>
            <a href="<?= ViewHelper::url('setup.php') ?>" class="btn btn-surface px-4 py-2 fw-semibold">
                <i class="bi bi-sliders me-1"></i> Setup Status
            </a>
        </div>
    </div>
</section>

<!-- =========================================================================
     8. Footer
     ========================================================================= -->
<footer class="py-5 bg-navy text-secondary small border-top border-navy">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-5">
                <img src="<?= ViewHelper::asset('images/logo.svg') ?>" alt="CyberShield Logo" style="height: 48px;" class="mb-3">
                <p class="small text-muted mb-2">
                    <strong>CyberShield</strong> — Cybersecurity Awareness & Phishing Simulation Platform. Developed for students and instructors at Hormuud University Faculty of Computer Science & IT.
                </p>
                <div class="somali-sub mb-3">Detect. Educate. Defend. · Ogow. Baro. Difaac.</div>
                <div class="text-muted" style="font-size: 0.72rem;">
                    Local XAMPP Environment · Windows 10/11 Compatible · MariaDB Engine
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <h3 class="h6 fw-bold text-light mb-3">Quick Navigation</h3>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-2">
                    <li><a href="#overview" class="text-secondary text-decoration-none hover-cyan">Overview & Metrics</a></li>
                    <li><a href="#phishing-lab" class="text-secondary text-decoration-none hover-cyan">Phishing Anatomy Lab</a></li>
                    <li><a href="#url-inspector" class="text-secondary text-decoration-none hover-cyan">Interactive URL Inspector</a></li>
                    <li><a href="#academy" class="text-secondary text-decoration-none hover-cyan">10 Academy Pillars</a></li>
                    <li><a href="<?= ViewHelper::url('quizzes') ?>" class="text-secondary text-decoration-none hover-cyan">Quiz Center</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-4">
                <h3 class="h6 fw-bold text-light mb-3">Institutional SOC Desk</h3>
                <ul class="list-unstyled mb-0 d-flex flex-column gap-2 text-muted">
                    <li><strong class="text-light">Institution:</strong> Hormuud University</li>
                    <li><strong class="text-light">Faculty:</strong> Computer Science & Information Technology</li>
                    <li><strong class="text-light">Lab:</strong> Cyber Security & Digital Forensics Unit</li>
                    <li><strong class="text-light">Contact:</strong> cybersecurity-lab@hu.edu.so</li>
                    <li><strong class="text-light">Ethical Policy:</strong> Strictly Educational & Non-Stealing</li>
                </ul>
            </div>
        </div>

        <div class="pt-3 border-top border-navy d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 text-muted" style="font-size: 0.75rem;">
            <div>&copy; 2026 CyberShield · Hormuud University Faculty of Computer Science & IT. All rights reserved.</div>
            <div class="d-flex align-items-center gap-3">
                <a href="<?= ViewHelper::url('login') ?>" class="text-cyan text-decoration-none">SOC Portal</a>
                <span>·</span>
                <a href="<?= ViewHelper::url('report-message') ?>" class="text-warning text-decoration-none">Report Phishing</a>
                <span>·</span>
                <a href="<?= ViewHelper::url('setup.php') ?>" class="text-secondary text-decoration-none">Setup Guard</a>
            </div>
        </div>
    </div>
</footer>

<script src="<?= ViewHelper::asset('js/bootstrap.bundle.min.js') ?>"></script>

<!-- Interactive Phishing Lab & URL Inspector Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Phishing Anatomy Lab Hotspots
    const inspectorDisplay = document.getElementById('inspectorDisplay');
    const hotspotData = document.getElementById('hotspotData');
    const pills = document.querySelectorAll('.hotspot-pill');

    pills.forEach(pill => {
        pill.addEventListener('click', function(e) {
            e.preventDefault();
            pills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');

            const id = this.getAttribute('data-hotspot');
            const content = hotspotData.querySelector('[data-id="' + id + '"]');
            if (content) {
                inspectorDisplay.innerHTML = content.innerHTML;
            }
        });
    });

    // 2. Interactive Live URL Anatomy Inspector Tool
    const urlInput = document.getElementById('urlInput');
    const inspectBtn = document.getElementById('inspectBtn');
    const urlTokenizer = document.getElementById('urlTokenizer');
    const resProtocol = document.getElementById('resProtocol');
    const resSubdomain = document.getElementById('resSubdomain');
    const resHost = document.getElementById('resHost');
    const riskBadge = document.getElementById('riskBadge');
    const resAdvice = document.getElementById('resAdvice');

    function analyzeUrl(urlStr) {
        urlStr = urlStr.trim();
        if (!urlStr.startsWith('http://') && !urlStr.startsWith('https://')) {
            urlStr = 'https://' + urlStr;
        }

        try {
            const parsed = new URL(urlStr);
            const protocol = parsed.protocol + '//';
            const fullHost = parsed.hostname;
            const path = parsed.pathname + parsed.search;

            // Extract true root domain & subdomains
            const hostParts = fullHost.split('.');
            let rootDomain = fullHost;
            let subdomain = 'None';

            if (hostParts.length >= 2) {
                rootDomain = hostParts.slice(-2).join('.');
                if (hostParts.length > 2) {
                    subdomain = hostParts.slice(0, -2).join('.');
                }
            }

            resProtocol.textContent = protocol;
            resSubdomain.textContent = subdomain !== 'None' ? subdomain : '—';
            resHost.textContent = rootDomain;

            // Risk Assessment Logic
            const isHormuud = rootDomain.toLowerCase() === 'hu.edu.so';
            const isSuspiciousPrepending = subdomain.toLowerCase().includes('hu.edu.so') || subdomain.toLowerCase().includes('hormuud');
            const isKnownSafe = isHormuud;

            if (isKnownSafe) {
                riskBadge.className = 'badge badge-soft-success';
                riskBadge.textContent = 'Legitimate University Host';
                resHost.className = 'text-success fw-bold';
                resAdvice.innerHTML = '<i class="bi bi-shield-check text-success me-1"></i><strong>Safe Domain:</strong> The root domain matches the verified institutional domain for Hormuud University (<code>hu.edu.so</code>).';
            } else if (isSuspiciousPrepending) {
                riskBadge.className = 'badge badge-danger-glow';
                riskBadge.textContent = 'CRITICAL PHISHING RISK (98%)';
                resHost.className = 'text-danger fw-bold';
                resAdvice.innerHTML = '<i class="bi bi-exclamation-triangle-fill text-danger me-1"></i><strong>Severe Phishing Trap:</strong> The string <code>' + subdomain + '</code> is an attacker-created subdomain prefix designed to deceive you. The actual server receiving your traffic is <code>' + rootDomain + '</code>!';
            } else {
                riskBadge.className = 'badge badge-soft-warning';
                riskBadge.textContent = 'External Unverified Host (Medium Risk)';
                resHost.className = 'text-warning fw-bold';
                resAdvice.innerHTML = '<i class="bi bi-info-circle text-warning me-1"></i><strong>Notice:</strong> This is an external domain (<code>' + rootDomain + '</code>). Always confirm authentication requests out-of-band.';
            }

            // Build Visual Tokenizer HTML
            urlTokenizer.innerHTML = `
                <span class="badge bg-secondary-subtle text-secondary">${protocol}</span>
                ${subdomain !== 'None' ? `<span class="badge ${isSuspiciousPrepending ? 'bg-danger-subtle text-danger border border-danger' : 'bg-warning-subtle text-warning'}">${subdomain}.</span>` : ''}
                <span class="badge ${isKnownSafe ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger fw-bold'}">${rootDomain}</span>
                <span class="badge bg-secondary-subtle text-muted">${path}</span>
            `;
        } catch (err) {
            console.error('URL parse exception:', err);
        }
    }

    if (inspectBtn) {
        inspectBtn.addEventListener('click', function() {
            analyzeUrl(urlInput.value);
        });
    }

    document.querySelectorAll('.url-preset').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            urlInput.value = url;
            analyzeUrl(url);
        });
    });

    // Run initial inspection on default URL
    if (urlInput) {
        analyzeUrl(urlInput.value);
    }
});
</script>

<script>
/* Theme toggle */
(function(){
    var html=document.getElementById('csHtml');
    var icon=document.getElementById('themeIcon');
    var saved=localStorage.getItem('cs_theme')||'dark';
    applyTheme(saved);
    function applyTheme(t){
        html.setAttribute('data-theme',t);
        if(icon) icon.className=t==='dark'?'bi bi-sun-fill':'bi bi-moon-fill';
        localStorage.setItem('cs_theme',t);
    }
    window.toggleTheme=function(){applyTheme(html.getAttribute('data-theme')==='dark'?'light':'dark');};
})();
/* Language toggle */
(function(){
    var html=document.getElementById('csHtml');
    var flag=document.getElementById('langFlag');
    var lbl=document.getElementById('langLabel');
    var saved=localStorage.getItem('cs_lang')||'en';
    applyLang(saved);
    function applyLang(l){
        html.setAttribute('lang',l);
        if(flag&&lbl){
            if(l==='so'){flag.innerHTML='&#127468;&#127463;';lbl.textContent='EN';}
            else{flag.innerHTML='&#127474;&#127476;';lbl.textContent='SO';}
        }
        localStorage.setItem('cs_lang',l);
    }
    window.toggleLang=function(){applyLang(html.getAttribute('lang')==='en'?'so':'en');};
})();
</script>

</body>
</html>
