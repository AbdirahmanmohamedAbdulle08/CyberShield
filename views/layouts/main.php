<?php
use App\Core\Auth;
use App\Helpers\ViewHelper;
use App\Core\CSRF;

$user = Auth::user();
$role = $user['role'] ?? 'student';
$currentPath = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
$scriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($scriptDir && strpos($currentPath, $scriptDir) === 0) {
    $currentPath = trim(substr($currentPath, strlen($scriptDir)), '/');
}
if (!empty($_GET['r'])) {
    $currentPath = trim($_GET['r'], '/');
}
if ($currentPath === '') {
    $currentPath = ($role === 'student') ? 'student/dashboard' : 'dashboard';
}

function navActive(string $route, string $current): string {
    return ($route === $current || strpos($current, $route) === 0) ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark" id="csHtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars(CSRF::getToken()) ?>">
    <title><?= ViewHelper::e($pageTitle ?? 'Operations Console') ?> | CyberShield — Hormuud University</title>
    <link rel="stylesheet" href="<?= ViewHelper::asset('css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= ViewHelper::asset('css/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= ViewHelper::asset('css/cybershield.css') ?>?v=<?= time() ?>">
    <link rel="icon" type="image/svg+xml" href="<?= ViewHelper::asset('images/logo.svg') ?>">
    <style>
        .text-light  { color: var(--cs-text-primary) !important; }
        .text-secondary { color: var(--cs-text-secondary) !important; }
        .card-soc .card-body { color: var(--cs-text-body); }
        .table-soc td { color: var(--cs-text-body) !important; }
        .table-soc th { color: var(--cs-text-muted) !important; }
        small, .small { color: var(--cs-text-secondary); }
    </style>
</head>
<body class="bg-navy">

<div class="cs-app-wrapper">
    <aside class="cs-sidebar" id="csSidebar">
        <div class="cs-sidebar-brand">
            <a href="<?= ViewHelper::url($role === 'student' ? 'student/dashboard' : 'dashboard') ?>" class="text-decoration-none d-flex align-items-center gap-2">
                <img src="<?= ViewHelper::asset('images/logo.svg') ?>" alt="CyberShield Logo">
            </a>
        </div>
        <div class="px-3 pt-3 pb-1">
            <div class="univ-banner">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-building text-cyan fs-5"></i>
                    <span class="univ-name">
                        <span class="lang-en">Hormuud University</span>
                        <span class="lang-so">Jaamacadda Hormuud</span>
                    </span>
                </div>
                <div class="univ-dept">
                    <span class="lang-en">Faculty of CS &amp; Cyber Security</span>
                    <span class="lang-so">Kuliyada Teknolojiyada Macluumaadka</span>
                </div>
                <div class="univ-detail mt-1">
                    <i class="bi bi-geo-alt-fill me-1"></i>
                    <span class="lang-en">Mogadishu, Somalia &middot; Est. 2002</span>
                    <span class="lang-so">Muqdisho, Soomaaliya &middot; 2002</span>
                </div>
            </div>
        </div>
        <div class="px-3 pt-2 pb-2">
            <div class="p-2 rounded bg-surface border border-navy d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="pulse-indicator-emerald"></span>
                    <span class="small fw-semibold" style="color: var(--cs-text-secondary);">
                        <span class="lang-en">Role:</span>
                        <span class="lang-so">Doorka:</span>
                    </span>
                </div>
                <?= ViewHelper::badgeRole($role) ?>
            </div>
        </div>
        <nav class="cs-sidebar-nav">
            <?php if ($role === 'super_admin'): ?>
                <div class="cs-nav-header"><span class="lang-en">Command &amp; Control</span><span class="lang-so">Amarka &amp; Xukumida</span></div>
                <a href="<?= ViewHelper::url('dashboard') ?>" class="cs-nav-link <?= navActive('dashboard', $currentPath) ?>"><i class="bi bi-grid-1x2-fill"></i><span class="lang-en">Overview</span><span class="lang-so">Guud Ahaan</span></a>
                <a href="<?= ViewHelper::url('campaigns') ?>" class="cs-nav-link <?= ($currentPath === 'campaigns') ? 'active' : '' ?>"><i class="bi bi-broadcast"></i><span class="lang-en">Campaigns</span><span class="lang-so">Olalayaasha</span></a>
                <a href="<?= ViewHelper::url('campaigns/create') ?>" class="cs-nav-link <?= navActive('campaigns/create', $currentPath) ?>"><i class="bi bi-plus-circle-fill"></i><span class="lang-en">Campaign Builder</span><span class="lang-so">Samee Ololaha</span></a>
                <a href="<?= ViewHelper::url('events') ?>" class="cs-nav-link <?= navActive('events', $currentPath) ?>"><i class="bi bi-activity"></i><span class="lang-en">Event Monitor</span><span class="lang-so">Daawashada Dhacdooyinka</span></a>
                <div class="cs-nav-header"><span class="lang-en">Targets &amp; Groups</span><span class="lang-so">Ardayda &amp; Kooxaha</span></div>
                <a href="<?= ViewHelper::url('participants') ?>" class="cs-nav-link <?= navActive('participants', $currentPath) ?>"><i class="bi bi-people-fill"></i><span class="lang-en">Participants</span><span class="lang-so">Ka-qaybgalayaasha</span></a>
                <a href="<?= ViewHelper::url('groups') ?>" class="cs-nav-link <?= navActive('groups', $currentPath) ?>"><i class="bi bi-diagram-3-fill"></i><span class="lang-en">Training Groups</span><span class="lang-so">Kooxaha Tababarka</span></a>
                <div class="cs-nav-header"><span class="lang-en">Education &amp; Academy</span><span class="lang-so">Waxbarasho &amp; Akademiyada</span></div>
                <a href="<?= ViewHelper::url('academy') ?>" class="cs-nav-link <?= navActive('academy', $currentPath) ?>"><i class="bi bi-book-fill"></i><span class="lang-en">Awareness Academy</span><span class="lang-so">Akademiyada Wacyigelinta</span></a>
                <a href="<?= ViewHelper::url('quizzes') ?>" class="cs-nav-link <?= navActive('quizzes', $currentPath) ?>"><i class="bi bi-patch-question-fill"></i><span class="lang-en">Quiz Management</span><span class="lang-so">Maaraynta Imtixaanka</span></a>
                <a href="<?= ViewHelper::url('reports/suspicious') ?>" class="cs-nav-link <?= navActive('reports/suspicious', $currentPath) ?>"><i class="bi bi-shield-exclamation"></i><span class="lang-en">Suspicious Reports</span><span class="lang-so">Warbixinnada Shaki Leh</span></a>
                <div class="cs-nav-header"><span class="lang-en">Intelligence &amp; Governance</span><span class="lang-so">Xogta &amp; Xukumida</span></div>
                <a href="<?= ViewHelper::url('reports') ?>" class="cs-nav-link <?= ($currentPath === 'reports') ? 'active' : '' ?>"><i class="bi bi-bar-chart-line-fill"></i><span class="lang-en">Reports &amp; Analytics</span><span class="lang-so">Warbixinnada &amp; Falanqaynta</span></a>
                <a href="<?= ViewHelper::url('audit') ?>" class="cs-nav-link <?= navActive('audit', $currentPath) ?>"><i class="bi bi-journal-text"></i><span class="lang-en">Audit Logs</span><span class="lang-so">Diiwaanka Hubinta</span></a>
                <a href="<?= ViewHelper::url('users') ?>" class="cs-nav-link <?= navActive('users', $currentPath) ?>"><i class="bi bi-person-gear"></i><span class="lang-en">User Management</span><span class="lang-so">Maaraynta Isticmaalayaasha</span></a>
                <a href="<?= ViewHelper::url('settings') ?>" class="cs-nav-link <?= navActive('settings', $currentPath) ?>"><i class="bi bi-gear-fill"></i><span class="lang-en">System Settings</span><span class="lang-so">Dejinta Nidaamka</span></a>
            <?php elseif ($role === 'campaign_manager'): ?>
                <div class="cs-nav-header"><span class="lang-en">Campaign Center</span><span class="lang-so">Xarunta Olalayaasha</span></div>
                <a href="<?= ViewHelper::url('dashboard') ?>" class="cs-nav-link <?= navActive('dashboard', $currentPath) ?>"><i class="bi bi-grid-1x2-fill"></i><span class="lang-en">Overview</span><span class="lang-so">Guud Ahaan</span></a>
                <a href="<?= ViewHelper::url('campaigns') ?>" class="cs-nav-link <?= ($currentPath === 'campaigns') ? 'active' : '' ?>"><i class="bi bi-broadcast"></i><span class="lang-en">My Campaigns</span><span class="lang-so">Olalaygii</span></a>
                <a href="<?= ViewHelper::url('campaigns/create') ?>" class="cs-nav-link <?= navActive('campaigns/create', $currentPath) ?>"><i class="bi bi-plus-circle-fill"></i><span class="lang-en">Campaign Builder</span><span class="lang-so">Samee Ololaha</span></a>
                <a href="<?= ViewHelper::url('participants') ?>" class="cs-nav-link <?= navActive('participants', $currentPath) ?>"><i class="bi bi-people-fill"></i><span class="lang-en">Participants</span><span class="lang-so">Ka-qaybgalayaasha</span></a>
                <a href="<?= ViewHelper::url('events') ?>" class="cs-nav-link <?= navActive('events', $currentPath) ?>"><i class="bi bi-activity"></i><span class="lang-en">Event Monitor</span><span class="lang-so">Daawashada Dhacdooyinka</span></a>
                <a href="<?= ViewHelper::url('reports') ?>" class="cs-nav-link <?= navActive('reports', $currentPath) ?>"><i class="bi bi-bar-chart-line-fill"></i><span class="lang-en">Campaign Reports</span><span class="lang-so">Warbixinnada Ololaha</span></a>
                <a href="<?= ViewHelper::url('reports/suspicious') ?>" class="cs-nav-link <?= navActive('reports/suspicious', $currentPath) ?>"><i class="bi bi-shield-exclamation"></i><span class="lang-en">Suspicious Reports</span><span class="lang-so">Warbixinnada Shaki Leh</span></a>
            <?php elseif ($role === 'instructor'): ?>
                <div class="cs-nav-header"><span class="lang-en">Instructional Operations</span><span class="lang-so">Hawlaha Bare-nimo</span></div>
                <a href="<?= ViewHelper::url('dashboard') ?>" class="cs-nav-link <?= navActive('dashboard', $currentPath) ?>"><i class="bi bi-grid-1x2-fill"></i><span class="lang-en">Overview</span><span class="lang-so">Guud Ahaan</span></a>
                <a href="<?= ViewHelper::url('campaigns') ?>" class="cs-nav-link <?= navActive('campaigns', $currentPath) ?>"><i class="bi bi-broadcast"></i><span class="lang-en">Assigned Campaigns</span><span class="lang-so">Olalayaashii La Qoondeeyay</span></a>
                <a href="<?= ViewHelper::url('groups') ?>" class="cs-nav-link <?= navActive('groups', $currentPath) ?>"><i class="bi bi-diagram-3-fill"></i><span class="lang-en">Training Groups</span><span class="lang-so">Kooxaha Tababarka</span></a>
                <a href="<?= ViewHelper::url('academy') ?>" class="cs-nav-link <?= navActive('academy', $currentPath) ?>"><i class="bi bi-book-fill"></i><span class="lang-en">Learning Modules</span><span class="lang-so">Cutubada Barashada</span></a>
                <a href="<?= ViewHelper::url('quizzes') ?>" class="cs-nav-link <?= navActive('quizzes', $currentPath) ?>"><i class="bi bi-patch-question-fill"></i><span class="lang-en">Quiz Results</span><span class="lang-so">Natiijada Imtixaanka</span></a>
                <a href="<?= ViewHelper::url('reports') ?>" class="cs-nav-link <?= navActive('reports', $currentPath) ?>"><i class="bi bi-bar-chart-line-fill"></i><span class="lang-en">Student Reports</span><span class="lang-so">Warbixinnada Ardayda</span></a>
                <a href="<?= ViewHelper::url('reports/suspicious') ?>" class="cs-nav-link <?= navActive('reports/suspicious', $currentPath) ?>"><i class="bi bi-shield-exclamation"></i><span class="lang-en">Student Reports Queue</span><span class="lang-so">Taxanaha Warbixinnada</span></a>
            <?php else: ?>
                <div class="cs-nav-header"><span class="lang-en">Student Learning Portal</span><span class="lang-so">Bawabadda Waxbarasho Ee Ardayga</span></div>
                <a href="<?= ViewHelper::url('student/dashboard') ?>" class="cs-nav-link <?= navActive('student/dashboard', $currentPath) ?>"><i class="bi bi-mortarboard-fill"></i><span class="lang-en">My Learning</span><span class="lang-so">Waxbarashadayda</span></a>
                <a href="<?= ViewHelper::url('academy') ?>" class="cs-nav-link <?= navActive('academy', $currentPath) ?>"><i class="bi bi-book-half"></i><span class="lang-en">Awareness Academy</span><span class="lang-so">Akademiyada Wacyigelinta</span></a>
                <a href="<?= ViewHelper::url('student/quizzes') ?>" class="cs-nav-link <?= navActive('student/quizzes', $currentPath) ?>"><i class="bi bi-patch-check-fill"></i><span class="lang-en">Quiz Center</span><span class="lang-so">Xarunta Imtixaanaadka</span></a>
                <a href="<?= ViewHelper::url('report-message') ?>" class="cs-nav-link <?= navActive('report-message', $currentPath) ?>"><i class="bi bi-shield-fill-exclamation text-amber"></i><span class="lang-en">Report Suspicious Message</span><span class="lang-so">Gudbi Farriin Shaki Leh</span></a>
                <a href="<?= ViewHelper::url('student/progress') ?>" class="cs-nav-link <?= navActive('student/progress', $currentPath) ?>"><i class="bi bi-trophy-fill"></i><span class="lang-en">My Progress</span><span class="lang-so">Horumarkayga</span></a>
            <?php endif; ?>
        </nav>
        <div class="cs-sidebar-footer">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <div class="rounded-circle bg-surface border border-cyan text-cyan d-flex align-items-center justify-content-center" style="width: 34px; height: 34px; flex-shrink: 0;"><i class="bi bi-person-fill"></i></div>
                    <div class="overflow-hidden">
                        <div class="text-truncate fw-semibold small" style="color: var(--cs-text-primary);"><?= ViewHelper::e($user['full_name'] ?? 'User') ?></div>
                        <div class="text-truncate" style="font-size: 0.72rem; color: var(--cs-text-secondary);">@<?= ViewHelper::e($user['username'] ?? 'user') ?></div>
                    </div>
                </div>
                <a href="<?= ViewHelper::url('logout') ?>" class="btn btn-sm btn-outline-danger" title="Logout"><i class="bi bi-box-arrow-right"></i></a>
            </div>
        </div>
    </aside>

    <div class="cs-main-content">
        <header class="cs-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-surface btn-sm d-lg-none" id="csSidebarToggle"><i class="bi bi-list fs-5"></i></button>
                <div class="d-none d-md-flex align-items-center gap-2">
                    <span class="badge bg-surface border border-navy px-2 py-1" style="color: var(--cs-cyan);"><i class="bi bi-building me-1"></i><span class="lang-en">Hormuud University</span><span class="lang-so">Jaamacadda Hormuud</span></span>
                    <span style="color: var(--cs-text-muted);">/</span>
                    <span class="small fw-medium" style="color: var(--cs-text-secondary);"><span class="lang-en">Faculty of CS &amp; IT</span><span class="lang-so">Kuliyada CS &amp; IT</span></span>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="d-none d-sm-flex align-items-center gap-2 px-2 py-1 rounded bg-surface border border-navy">
                    <span class="pulse-indicator"></span>
                    <span class="small" style="font-size: 0.76rem; color: var(--cs-text-secondary);"><span class="lang-en">Simulation Engine: <strong style="color:var(--cs-cyan)">LOCAL LAB</strong></span><span class="lang-so">Injineerka Dhoolatuska: <strong style="color:var(--cs-cyan)">SHAYBAADHKA</strong></span></span>
                </div>
                <button class="btn-lang-toggle" id="langToggle" title="Switch Language / Bedel Luqadda" onclick="toggleLang()">
                    <span id="langFlag">&#127468;&#127463;</span>
                    <span id="langLabel">SO</span>
                </button>
                <button class="btn-theme-toggle" id="themeToggle" title="Toggle Light/Dark Theme" onclick="toggleTheme()">
                    <i class="bi bi-moon-fill" id="themeIcon"></i>
                </button>
                <a href="<?= ViewHelper::url('') ?>" class="btn btn-surface btn-sm d-none d-md-flex align-items-center gap-1"><i class="bi bi-globe2 text-cyan"></i><span class="lang-en">Public Hub</span><span class="lang-so">Bogga Guud</span></a>
                <a href="<?= ViewHelper::url('report-message') ?>" class="btn btn-outline-warning btn-sm d-flex align-items-center gap-1"><i class="bi bi-shield-exclamation"></i><span class="d-none d-sm-inline lang-en">Report Message</span><span class="d-none d-sm-inline lang-so">Gudbi Farriin</span></a>
                <div class="dropdown">
                    <button class="btn btn-surface btn-sm dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle text-cyan"></i>
                        <span class="d-none d-md-inline small fw-semibold"><?= ViewHelper::e($user['username'] ?? 'Account') ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end bg-surface border-navy shadow">
                        <li class="px-3 py-2 border-bottom border-navy">
                            <div class="fw-bold small" style="color: var(--cs-text-primary);"><?= ViewHelper::e($user['full_name'] ?? 'User') ?></div>
                            <div style="font-size: 0.72rem; color: var(--cs-text-secondary);"><?= ViewHelper::e($user['email'] ?? '') ?></div>
                        </li>
                        <li><a class="dropdown-item py-2" href="<?= ViewHelper::url($role === 'student' ? 'student/dashboard' : 'dashboard') ?>"><i class="bi bi-speedometer2 me-2"></i><span class="lang-en">My Dashboard</span><span class="lang-so">Koontaradhada</span></a></li>
                        <li><a class="dropdown-item py-2" href="<?= ViewHelper::url('academy') ?>"><i class="bi bi-book me-2"></i><span class="lang-en">Cyber Academy</span><span class="lang-so">Akademiyada Amniga</span></a></li>
                        <li><hr class="dropdown-divider border-navy"></li>
                        <li><a class="dropdown-item text-danger py-2" href="<?= ViewHelper::url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i><span class="lang-en">Sign Out</span><span class="lang-so">Ka Bax</span></a></li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="p-3 p-md-4">
            <?php if (!empty($flashSuccess)): ?>
                <div class="alert alert-success bg-surface-card border-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <div><?= ViewHelper::e($flashSuccess) ?></div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (!empty($flashError)): ?>
                <div class="alert alert-danger bg-surface-card border-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                    <div><?= ViewHelper::e($flashError) ?></div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?= $content ?>
        </main>

        <footer class="mt-auto py-3 px-4 border-top border-navy bg-surface small d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2" style="color: var(--cs-text-secondary);">
            <div>
                <strong style="color: var(--cs-text-primary);">CyberShield</strong> &mdash;
                <span class="lang-en">Detect. Educate. Defend.</span><span class="lang-so">Ogow. Baro. Difaac.</span>
                &nbsp;&middot;&nbsp;
                <span style="color: var(--cs-cyan);"><span class="lang-en">Hormuud University CS &amp; Cyber Security Lab</span><span class="lang-so">Shaybaadhka CS &amp; Amniga Jaamacadda Hormuud</span></span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="lang-en">Educational Simulation Platform</span><span class="lang-so">Barnamijka Tababarka Amniga</span>
                <span style="color: var(--cs-text-muted);">|</span>
                <a href="<?= ViewHelper::url('academy') ?>" class="text-decoration-none hover-cyan" style="color: var(--cs-text-secondary);"><span class="lang-en">Academy</span><span class="lang-so">Akademiyada</span></a>
                <span style="color: var(--cs-text-muted);">|</span>
                <a href="<?= ViewHelper::url('report-message') ?>" class="text-warning text-decoration-none"><span class="lang-en">Report Phish</span><span class="lang-so">Gudbi Phishing</span></a>
            </div>
        </footer>
    </div>
</div>

<script src="<?= ViewHelper::asset('js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= ViewHelper::asset('js/chart.umd.min.js') ?>"></script>
<script src="<?= ViewHelper::asset('js/cybershield.js') ?>"></script>
<script>
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