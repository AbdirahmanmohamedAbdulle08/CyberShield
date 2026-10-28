<?php
use App\Core\CSRF;
use App\Helpers\ViewHelper;
?>
<div class="container py-4" style="max-width: 860px;">
    <!-- Disclosure Hero Header -->
    <div class="card card-soc border-warning mb-4 shadow-lg overflow-hidden">
        <div class="card-header bg-warning text-dark py-3 px-4 d-flex align-items-center gap-3">
            <i class="bi bi-shield-exclamation fs-2"></i>
            <div>
                <h1 class="h4 fw-bold mb-0">Security Awareness Simulation Disclosure</h1>
                <p class="small mb-0">You interacted with an authorized educational cybersecurity simulation</p>
            </div>
        </div>

        <div class="card-body p-4">
            <div class="alert alert-info bg-surface border-navy text-light d-flex align-items-start gap-3 mb-4 p-3 rounded">
                <i class="bi bi-info-circle-fill text-cyan fs-4 flex-shrink-0 mt-1"></i>
                <div class="small">
                    <strong class="text-cyan">Don't worry — your account is safe!</strong> This was an authorized cybersecurity demonstration designed for students studying Cyber Security and Computer Science at Hormuud University. No real passwords or personal information were requested, transmitted, or stored.
                </div>
            </div>

            <h2 class="h5 fw-bold text-light mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-binoculars-fill text-cyan"></i>
                <span>Why Was This Message Suspicious? (The Red Flags)</span>
            </h2>

            <div class="row g-3 mb-4">
                <!-- Red Flag 1 -->
                <div class="col-md-6">
                    <div class="p-3 rounded bg-surface border border-navy h-100">
                        <div class="d-flex align-items-center gap-2 text-danger fw-bold small mb-2">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span>1. Unrealistic Reward or Incentive</span>
                        </div>
                        <p class="small text-secondary mb-0">
                            The lure promised a <strong class="text-light">$500 tech grant or cash prize</strong> you never applied for. Authentic university financial disbursements are processed through the official university finance registry, never via unsolicited emails.
                        </p>
                    </div>
                </div>

                <!-- Red Flag 2 -->
                <div class="col-md-6">
                    <div class="p-3 rounded bg-surface border border-navy h-100">
                        <div class="d-flex align-items-center gap-2 text-warning fw-bold small mb-2">
                            <i class="bi bi-hourglass-split"></i>
                            <span>2. Manufactured Urgency</span>
                        </div>
                        <p class="small text-secondary mb-0">
                            Phrases such as <strong class="text-light">"4 hours remaining"</strong> or <strong class="text-light">"account suspension in 30 minutes"</strong> are psychological levers engineered to induce panic and prevent you from thinking critically.
                        </p>
                    </div>
                </div>

                <!-- Red Flag 3 -->
                <div class="col-md-6">
                    <div class="p-3 rounded bg-surface border border-navy h-100">
                        <div class="d-flex align-items-center gap-2 text-info fw-bold small mb-2">
                            <i class="bi bi-globe2"></i>
                            <span>3. Deceptive Lookalike Domain</span>
                        </div>
                        <p class="small text-secondary mb-0">
                            The sender address was <code class="text-cyan"><?= ViewHelper::e($campaign['sender_email']) ?></code>. Notice that this domain is NOT the genuine university domain (<strong class="text-light">hu.edu.so</strong>).
                        </p>
                    </div>
                </div>

                <!-- Red Flag 4 -->
                <div class="col-md-6">
                    <div class="p-3 rounded bg-surface border border-navy h-100">
                        <div class="d-flex align-items-center gap-2 text-cyan fw-bold small mb-2">
                            <i class="bi bi-link-45deg"></i>
                            <span>4. External Unverified Link</span>
                        </div>
                        <p class="small text-secondary mb-0">
                            Hovering over the link revealed a destination outside the campus network. Always inspect URLs right-to-left before clicking!
                        </p>
                    </div>
                </div>
            </div>

            <!-- How to Respond in the Future -->
            <div class="p-3 rounded bg-surface border border-navy mb-4">
                <h3 class="h6 fw-bold text-light mb-2 d-flex align-items-center gap-2">
                    <i class="bi bi-shield-check text-emerald"></i>
                    <span>Recommended Defensive Response</span>
                </h3>
                <ul class="small text-secondary mb-0 ps-3">
                    <li class="mb-1"><strong class="text-light">Stop and verify:</strong> If an email creates panic or promises unexpected prizes, pause and verify out-of-band via official campus contact numbers.</li>
                    <li class="mb-1"><strong class="text-light">Inspect sender headers:</strong> Ensure the domain accurately matches the institution.</li>
                    <li><strong class="text-light">Report immediately:</strong> Use the CyberShield "Report Suspicious Message" button to alert the SOC.</li>
                </ul>
            </div>

            <!-- Interactive Educational Actions -->
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 pt-3 border-top border-navy">
                <a href="<?= ViewHelper::url('quizzes/5') ?>" class="btn btn-cyan px-4 py-2 fw-semibold d-flex align-items-center gap-2 w-100 w-sm-auto justify-content-center">
                    <i class="bi bi-patch-question-fill fs-5"></i>
                    <span>Take Awareness Knowledge Quiz</span>
                </a>

                <form method="POST" action="<?= ViewHelper::url('simulation/' . $campaign['public_token'] . '/complete') ?>" class="w-100 w-sm-auto">
                    <?= CSRF::field() ?>
                    <input type="hidden" name="participant_token" value="<?= htmlspecialchars($participantToken) ?>">
                    <button type="submit" class="btn btn-outline-success px-4 py-2 fw-semibold d-flex align-items-center gap-2 w-100 justify-content-center">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <span>Acknowledge & Complete Lesson</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
