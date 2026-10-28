<?php
use App\Helpers\ViewHelper;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ViewHelper::e($pageTitle ?? 'Simulation Notice') ?> | CyberShield Lab</title>
    
    <link rel="stylesheet" href="<?= ViewHelper::asset('css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= ViewHelper::asset('css/bootstrap-icons.min.css') ?>">
    <link rel="stylesheet" href="<?= ViewHelper::asset('css/cybershield.css') ?>">
    
    <link rel="icon" type="image/svg+xml" href="<?= ViewHelper::asset('images/logo.svg') ?>">
</head>
<body class="bg-navy text-light d-flex flex-column min-vh-100">

<!-- Simulation Top Banner -->
<header class="py-2 px-3 bg-surface border-bottom border-navy d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
        <img src="<?= ViewHelper::asset('images/logo.svg') ?>" alt="CyberShield" style="height: 32px;">
        <span class="badge badge-soft-warning px-2 py-1 small">
            <i class="bi bi-shield-shaded me-1"></i> AUTHORIZED SIMULATION LAB
        </span>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="text-secondary small d-none d-md-inline">Hormuud University · Cyber Security Training</span>
        <a href="<?= ViewHelper::url('report-message') ?>" class="btn btn-sm btn-outline-warning">
            <i class="bi bi-shield-exclamation me-1"></i> Report Suspicious
        </a>
    </div>
</header>

<main class="flex-grow-1 py-4 px-3">
    <?= $content ?>
</main>

<footer class="py-3 px-4 border-top border-navy bg-surface text-center text-muted small">
    <div><strong>CyberShield Simulation Engine</strong> — Authorized Educational Demonstration for Hormuud University CS & IT Students.</div>
    <div class="mt-1" style="font-size: 0.72rem;">Safe Educational Environment — No credentials or secrets are harvested or stored.</div>
</footer>

<script src="<?= ViewHelper::asset('js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
