<?php
use App\Core\CSRF;
use App\Helpers\ViewHelper;
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-light mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-gear-fill text-cyan"></i>
            <span>System Settings & Node Hardening</span>
        </h1>
        <p class="text-secondary small mb-0">Platform parameters, educational consent policies, and security health status</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <form method="POST" action="<?= ViewHelper::url('settings/reset-demo') ?>" onsubmit="return confirm('Reset all demo data and restore initial Hormuud University cohort state?');">
            <?= CSRF::field() ?>
            <button type="submit" class="btn btn-outline-warning btn-sm">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Re-seed Demo Data
            </button>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Left: Settings Form -->
    <div class="col-lg-7">
        <div class="card card-soc">
            <div class="card-header fw-semibold">
                <i class="bi bi-sliders text-cyan me-2"></i>Institutional & Platform Settings
            </div>
            <div class="card-body p-4">
                <form method="POST" action="<?= ViewHelper::url('settings/update') ?>">
                    <?= CSRF::field() ?>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Application Name</label>
                            <input type="text" class="form-control form-control-soc" name="app_name" value="<?= ViewHelper::e($settings['app_name'] ?? 'CyberShield') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Brand Tagline</label>
                            <input type="text" class="form-control form-control-soc" name="app_tagline" value="<?= ViewHelper::e($settings['app_tagline'] ?? 'Detect. Educate. Defend.') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Educational Institution Name</label>
                        <input type="text" class="form-control form-control-soc" name="institution_name" value="<?= ViewHelper::e($settings['institution_name'] ?? 'Hormuud University') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Department / Security Lab</label>
                        <input type="text" class="form-control form-control-soc" name="department_name" value="<?= ViewHelper::e($settings['department_name'] ?? 'Faculty of Computer Science & IT — Cyber Security Lab') ?>" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Incident Contact Email</label>
                            <input type="email" class="form-control form-control-soc" name="contact_email" value="<?= ViewHelper::e($settings['contact_email'] ?? 'cybersecurity-lab@hu.edu.so') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Session Lifetime (Seconds)</label>
                            <input type="number" class="form-control form-control-soc" name="session_lifetime" value="<?= ViewHelper::e($settings['session_lifetime'] ?? '3600') ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-secondary small fw-semibold">Simulation Engine Mode</label>
                        <select class="form-select form-select-soc" name="simulation_mode">
                            <option value="local_educational" <?= (($settings['simulation_mode'] ?? '') === 'local_educational') ? 'selected' : '' ?>>Local Educational Lab (Default & Safe)</option>
                            <option value="isolated_sandbox" <?= (($settings['simulation_mode'] ?? '') === 'isolated_sandbox') ? 'selected' : '' ?>>Isolated Sandbox Network</option>
                        </select>
                        <div class="text-muted small mt-1" style="font-size: 0.74rem;">
                            External message sending is completely disabled in local educational mode to prevent unauthorized traffic.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-cyan px-4 py-2 fw-semibold">
                        <i class="bi bi-save me-1"></i> Save Platform Settings
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right: Security Health Check -->
    <div class="col-lg-5">
        <div class="card card-soc h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-shield-check text-cyan me-2"></i>Security Hardening Audit</span>
                <span class="badge badge-soft-success">Passing</span>
            </div>
            <div class="card-body p-3">
                <p class="small text-secondary mb-3">
                    CyberShield runs real-time verification against core application security standards:
                </p>

                <div class="d-flex flex-column gap-3">
                    <?php foreach ($securityChecks as $key => $check): ?>
                        <div class="p-2 rounded bg-surface border border-navy">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="small fw-semibold text-light"><?= ViewHelper::e($check['name']) ?></span>
                                <?php if ($check['status']): ?>
                                    <span class="badge badge-soft-success small"><i class="bi bi-check-circle me-1"></i>Verified</span>
                                <?php else: ?>
                                    <span class="badge badge-soft-warning small">Notice</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-muted" style="font-size: 0.72rem;"><?= ViewHelper::e($check['detail']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="p-3 rounded bg-surface border border-navy mt-4 small text-secondary">
                    <strong class="text-light d-block mb-1"><i class="bi bi-info-circle text-cyan me-1"></i>Kali Linux Lab Testing:</strong>
                    For penetration testing from Kali Linux in VMware, refer to the documentation in <code>docs/kali_security_testing.md</code>.
                </div>
            </div>
        </div>
    </div>
</div>
