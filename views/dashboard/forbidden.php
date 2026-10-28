<?php
use App\Helpers\ViewHelper;
?>
<div class="card card-soc border-danger shadow-lg my-5 mx-auto" style="max-width: 620px;">
    <div class="card-body p-5 text-center">
        <div class="display-3 text-danger mb-3"><i class="bi bi-shield-x"></i></div>
        <h1 class="h3 fw-bold text-light mb-2">403 — Restricted Operations Zone</h1>
        <p class="text-secondary small mb-4">
            Your authenticated role (<strong class="text-warning"><?= ViewHelper::e($userRole ?? 'Unknown') ?></strong>) does not hold authorization tokens for this administrative control plane.
        </p>
        <div class="p-3 rounded bg-surface border border-navy text-start small mb-4">
            <div class="text-muted mb-1"><i class="bi bi-info-circle me-1"></i>Access Control Policy:</div>
            <div>This incident has been recorded in the CyberShield Security Operations audit log. If you believe this is an error, please request administrative elevation from your course instructor or SOC administrator.</div>
        </div>
        <div class="d-flex justify-content-center gap-2">
            <a href="javascript:history.back()" class="btn btn-surface px-4">
                <i class="bi bi-arrow-left me-1"></i> Return to Previous Page
            </a>
            <a href="<?= ViewHelper::url($userRole === 'student' ? 'student/dashboard' : 'dashboard') ?>" class="btn btn-cyan px-4">
                <i class="bi bi-house-door me-1"></i> My Dashboard
            </a>
        </div>
    </div>
</div>
