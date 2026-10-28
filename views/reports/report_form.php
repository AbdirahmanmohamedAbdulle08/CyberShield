<?php
use App\Core\CSRF;
use App\Helpers\ViewHelper;
?>
<div class="container py-3" style="max-width: 760px;">
    <div class="card card-soc border-warning-subtle shadow-lg">
        <div class="card-header bg-surface border-bottom border-warning-subtle p-4 d-flex align-items-center gap-3">
            <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                <i class="bi bi-shield-exclamation fs-3"></i>
            </div>
            <div>
                <h1 class="h4 fw-bold text-light mb-0">Report a Suspicious Message</h1>
                <p class="text-secondary small mb-0">Alert the CyberShield Security Operations Center and build your defense score</p>
            </div>
        </div>

        <div class="card-body p-4">
            <form method="POST" action="<?= ViewHelper::url('report-message/store') ?>">
                <?= CSRF::field() ?>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-semibold">Reporter Name</label>
                        <input type="text" class="form-control form-control-soc" name="reporter_name" value="<?= ViewHelper::e($user['full_name'] ?? 'Farah Abdullahi') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-semibold">Primary Suspicious Indicator</label>
                        <select class="form-select form-select-soc" name="reason" required>
                            <option value="suspicious_link">Suspicious or Mismatched Link</option>
                            <option value="unrealistic_reward">Unrealistic Reward / Prize Lure</option>
                            <option value="urgent_threat">Urgent Threat or Deadline Panic</option>
                            <option value="impersonation">Authority / Staff Impersonation</option>
                            <option value="unknown_sender">Unknown / Lookalike External Sender</option>
                            <option value="request_for_sensitive_info">Request for Sensitive Action</option>
                            <option value="other">Other Anomaly</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-semibold">Associated Simulation Campaign (If recognized)</label>
                    <select class="form-select form-select-soc" name="campaign_id">
                        <option value="">-- External or Unidentified Campaign --</option>
                        <?php foreach ($campaigns as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($campaignId === (int)$c['id']) ? 'selected' : '' ?>>
                                Simulation #<?= $c['id'] ?>: <?= ViewHelper::e($c['campaign_name']) ?> (<?= ViewHelper::e($c['fictional_scenario']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-semibold">Message Subject / Title</label>
                    <input type="text" class="form-control form-control-soc" name="message_subject" placeholder="e.g. Congratulations! You have been selected to receive a $500 reward" required>
                </div>

                <div class="mb-4">
                    <label class="form-label text-secondary small fw-semibold">Why did you find this message suspicious? (Explanation)</label>
                    <textarea class="form-control form-control-soc" name="explanation" rows="4" placeholder="Describe the red flags you identified (e.g. sender domain discrepancy, unnatural urgency, unexpected reward offer)..." required></textarea>
                </div>

                <div class="p-3 rounded bg-surface border border-navy small text-muted mb-4">
                    <i class="bi bi-shield-lock-fill text-cyan me-1"></i>
                    <strong>Safety First:</strong> Never paste passwords, OTPs, recovery tokens, or personal secrets in this report.
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="javascript:history.back()" class="btn btn-surface px-3">Cancel</a>
                    <button type="submit" class="btn btn-warning px-4 py-2 fw-semibold">
                        <i class="bi bi-send-check me-1"></i> Submit Threat Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
