<?php
use App\Helpers\ViewHelper;
?>
<div class="container py-5 text-center" style="max-width: 600px;">
    <div class="card card-soc p-5 shadow-lg">
        <div class="display-3 text-cyan mb-3"><i class="bi bi-shield-slash"></i></div>
        <h1 class="h4 fw-bold text-light mb-2">Simulation Scenario Inactive or Not Found</h1>
        <p class="text-secondary small mb-4">
            The requested simulation token <code class="text-cyan"><?= htmlspecialchars($token) ?></code> is either expired, completed, or invalid.
        </p>
        <div>
            <a href="<?= ViewHelper::url('login') ?>" class="btn btn-outline-cyan px-4">
                <i class="bi bi-arrow-left me-1"></i> Return to CyberShield
            </a>
        </div>
    </div>
</div>
