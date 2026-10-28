<?php
use App\Helpers\ViewHelper;
?>
<div class="container py-4" style="max-width: 720px;">
    <!-- Fictional Simulated Lure Box -->
    <div class="card shadow-lg border-0 mb-4 overflow-hidden" style="border-radius: 12px;">
        <div class="p-4 bg-primary text-white text-center">
            <div class="display-6 mb-2"><i class="bi bi-gift-fill text-warning"></i></div>
            <h1 class="h4 fw-bold mb-1"><?= ViewHelper::e($campaign['message_subject']) ?></h1>
            <p class="mb-0 text-white-50 small">Official Notification Dispatch · Reference ID: <?= strtoupper(substr($campaign['public_token'], 0, 12)) ?></p>
        </div>

        <div class="card-body p-4 bg-white text-dark">
            <div class="p-3 mb-4 rounded bg-light border">
                <div class="small text-muted mb-1">
                    <strong>From:</strong> <?= ViewHelper::e($campaign['sender_name']) ?> &lt;<?= ViewHelper::e($campaign['sender_email']) ?>&gt;
                </div>
                <div class="small text-muted">
                    <strong>Recipient:</strong> Student Participant
                </div>
            </div>

            <div class="fs-6 mb-4 text-secondary" style="white-space: pre-wrap; line-height: 1.6;">
<?= ViewHelper::e($campaign['message_body']) ?>
            </div>

            <!-- Demonstration Action: Safely Leads to Educational Disclosure -->
            <div class="p-4 rounded text-center mb-3" style="background-color: #f8fafc; border: 2px dashed #cbd5e1;">
                <p class="text-muted small mb-3">
                    <strong>Interactive Simulation Choice:</strong><br>
                    To test your instinctive response in a simulated environment, choose the action you would typically take:
                </p>
                <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                    <a href="<?= ViewHelper::url('simulation/' . $campaign['public_token'] . '/training?p=' . urlencode($participantToken)) ?>" class="btn btn-danger py-2 px-4 fw-semibold shadow-sm">
                        <i class="bi bi-cursor-fill me-1"></i> I would normally click or enter my credentials
                    </a>
                    <a href="<?= ViewHelper::url('report-message?campaign=' . $campaign['id'] . '&token=' . urlencode($participantToken)) ?>" class="btn btn-success py-2 px-4 fw-semibold shadow-sm">
                        <i class="bi bi-shield-check me-1"></i> I recognize this as suspicious (Report)
                    </a>
                </div>
            </div>
        </div>

        <div class="card-footer bg-light p-3 text-center text-muted small">
            <i class="bi bi-info-circle text-primary me-1"></i>
            This is an authorized educational exercise for Hormuud University. No credentials will ever be asked for or captured.
        </div>
    </div>
</div>
