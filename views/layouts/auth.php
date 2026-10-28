<?php
use App\Helpers\ViewHelper;
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark" id="csHtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ViewHelper::e($pageTitle ?? 'Authentication') ?> | CyberShield — Hormuud University</title>
    
    <link rel="stylesheet" href="<?= ViewHelper::asset('css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= ViewHelper::asset('css/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= ViewHelper::asset('css/cybershield.css') ?>?v=<?= time() ?>">
    
    <link rel="icon" type="image/svg+xml" href="<?= ViewHelper::asset('images/logo.svg') ?>">
</head>
<body class="bg-navy text-light d-flex flex-column align-items-center justify-content-center min-vh-100 py-4">

<!-- Top Corner Controls -->
<div class="position-absolute top-0 end-0 p-3 d-flex align-items-center gap-2">
    <button class="btn-lang-toggle" id="langToggle" title="Switch Language / Bedel Luqadda" onclick="toggleLang()">
        <span id="langFlag">&#127468;&#127463;</span>
        <span id="langLabel">SO</span>
    </button>
    <button class="btn-theme-toggle" id="themeToggle" title="Toggle Light/Dark Theme" onclick="toggleTheme()">
        <i class="bi bi-moon-fill" id="themeIcon"></i>
    </button>
    <a href="<?= ViewHelper::url('') ?>" class="btn btn-surface btn-sm d-flex align-items-center gap-1">
        <i class="bi bi-arrow-left"></i>
        <span class="lang-en">Public Portal</span>
        <span class="lang-so">Bogga Hore</span>
    </a>
</div>

<div class="container" style="max-width: 520px;">
    <!-- Logo & Brand Header -->
    <div class="text-center mb-4">
        <a href="<?= ViewHelper::url('') ?>">
            <img src="<?= ViewHelper::asset('images/logo.svg') ?>" alt="CyberShield Logo" style="height: 60px;">
        </a>
        <div class="text-secondary small mt-2">
            <span class="text-cyan fw-bold">
                <span class="lang-en">Hormuud University</span>
                <span class="lang-so">Jaamacadda Hormuud</span>
            </span>
            &middot;
            <span class="lang-en">Faculty of Computer Science &amp; IT</span>
            <span class="lang-so">Kuliyadda CS &amp; IT</span>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (!empty($flashSuccess)): ?>
        <div class="alert alert-success bg-surface-card border-success text-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><?= ViewHelper::e($flashSuccess) ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($flashError)): ?>
        <div class="alert alert-danger bg-surface-card border-danger text-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= ViewHelper::e($flashError) ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Main Card Body -->
    <?= $content ?>

    <!-- Ethical Simulation Disclaimer -->
    <div class="text-center text-muted small mt-4">
        <i class="bi bi-shield-check text-cyan me-1"></i>
        <span>
            <span class="lang-en">Authorized Educational Simulation &amp; Defense Training Platform</span>
            <span class="lang-so">Platform-ka Waxbarashada &amp; Tababarka Amniga Ee Sharciyeysan</span>
        </span>
        <div class="mt-1" style="font-size: 0.72rem;">
            <span class="lang-en">No real credentials are harvested or stored on this server.</span>
            <span class="lang-so">Wax furaha sirta ah lama xado mana lagu keydiyo server-kan.</span>
        </div>
    </div>
</div>

<script src="<?= ViewHelper::asset('js/bootstrap.bundle.min.js') ?>"></script>
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
