<?php
use App\Core\CSRF;
use App\Helpers\ViewHelper;
?>
<div class="card card-soc border-navy shadow-lg">
    <div class="card-header border-bottom border-navy text-center py-3">
        <h1 class="h5 fw-bold text-light mb-1">
            <span class="lang-en">Security Operations Center Login</span>
            <span class="lang-so">Gal Barta Xarunta Amniga (SOC)</span>
        </h1>
        <p class="text-secondary small mb-0">
            <span class="lang-en">Authorized personnel &amp; students please sign in</span>
            <span class="lang-so">Shaqaalaha &amp; ardayda la oggol yahay fadlan soo gala</span>
        </p>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="<?= ViewHelper::url('login') ?>" autocomplete="off">
            <?= CSRF::field() ?>

            <div class="mb-3">
                <label for="username" class="form-label text-secondary small fw-semibold">
                    <span class="lang-en">Username or Institutional Email</span>
                    <span class="lang-so">Magaca Isticmaalaha ama Email-ka Jaamacadda</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-surface border-navy text-cyan"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control form-control-soc" id="username" name="username" placeholder="e.g. superadmin or student" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label text-secondary small fw-semibold">
                    <span class="lang-en">Password</span>
                    <span class="lang-so">Furaha Sirta ah (Password)</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-surface border-navy text-cyan"><i class="bi bi-shield-lock-fill"></i></span>
                    <input type="password" class="form-control form-control-soc" id="password" name="password" placeholder="••••••••••••" required>
                    <button class="btn btn-surface border-navy" type="button" id="togglePasswordBtn">
                        <i class="bi bi-eye text-secondary" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-cyan w-100 py-2 d-flex align-items-center justify-content-center gap-2">
                <i class="bi bi-box-arrow-in-right fs-5"></i>
                <span>
                    <span class="lang-en">Sign In to CyberShield</span>
                    <span class="lang-so">Soo Gal CyberShield</span>
                </span>
            </button>
        </form>

        <!-- Quick Demo Credentials for Hormuud University Demo -->
        <div class="mt-4 pt-3 border-top border-navy">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.72rem;">
                    <span class="lang-en">Demo Access Quick-Fill</span>
                    <span class="lang-so">Akoonnada Tusaalaha (Quick-Fill)</span>
                </span>
                <span class="badge badge-soft-cyan">
                    <span class="lang-en">Lab Testing</span>
                    <span class="lang-so">Shaybaadh</span>
                </span>
            </div>
            <div class="row g-2">
                <div class="col-6">
                    <button type="button" class="btn btn-surface btn-sm w-100 text-start quick-fill" data-user="superadmin" data-pass="CyberShield@2026!">
                        <span class="text-danger fw-bold d-block" style="font-size: 0.76rem;"><i class="bi bi-shield-shaded me-1"></i>Super Admin</span>
                        <span class="text-muted" style="font-size: 0.7rem;">Dr. Abdirahman</span>
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-surface btn-sm w-100 text-start quick-fill" data-user="manager" data-pass="CyberShield@2026!">
                        <span class="text-info fw-bold d-block" style="font-size: 0.76rem;"><i class="bi bi-send me-1"></i>Campaign Mgr</span>
                        <span class="text-muted" style="font-size: 0.7rem;">Amina Hassan</span>
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-surface btn-sm w-100 text-start quick-fill" data-user="instructor" data-pass="CyberShield@2026!">
                        <span class="text-primary fw-bold d-block" style="font-size: 0.76rem;"><i class="bi bi-mortarboard me-1"></i>Instructor</span>
                        <span class="text-muted" style="font-size: 0.7rem;">Eng. Mohamed</span>
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-surface btn-sm w-100 text-start quick-fill" data-user="student" data-pass="CyberShield@2026!">
                        <span class="text-success fw-bold d-block" style="font-size: 0.76rem;"><i class="bi bi-person me-1"></i>Student</span>
                        <span class="text-muted" style="font-size: 0.7rem;">Farah (CS-Y3)</span>
                    </button>
                </div>
            </div>
            <div class="text-muted text-center mt-2" style="font-size: 0.7rem;">
                <span class="lang-en">Default Demo Password:</span>
                <span class="lang-so">Furaha Tusaalaha:</span>
                <code>CyberShield@2026!</code>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    if (toggleBtn && passInput) {
        toggleBtn.addEventListener('click', function() {
            if (passInput.type === 'password') {
                passInput.type = 'text';
                toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passInput.type = 'password';
                toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    }

    // Quick Fill Buttons for Demo
    document.querySelectorAll('.quick-fill').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const user = this.getAttribute('data-user');
            const pass = this.getAttribute('data-pass');
            document.getElementById('username').value = user;
            document.getElementById('password').value = pass;
        });
    });
});
</script>
