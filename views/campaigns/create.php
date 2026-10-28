<?php
use App\Core\CSRF;
use App\Helpers\ViewHelper;
?>
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h3 fw-bold text-light mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-plus-circle text-cyan"></i>
            <span>Campaign Builder</span>
        </h1>
        <p class="text-secondary small mb-0">Construct and configure an authorized educational phishing simulation</p>
    </div>
    <a href="<?= ViewHelper::url('campaigns') ?>" class="btn btn-surface btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Campaigns
    </a>
</div>

<form method="POST" action="<?= ViewHelper::url('campaigns/store') ?>" id="campaignForm">
    <?= CSRF::field() ?>

    <div class="row g-4">
        <!-- Left: Configuration Form -->
        <div class="col-lg-7">
            <!-- 1. Scenario Template Selector -->
            <div class="card card-soc mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="bi bi-magic text-cyan me-2"></i>Select Fictional Scenario Template</span>
                    <span class="badge badge-soft-cyan">Preset Scenarios</span>
                </div>
                <div class="card-body">
                    <select class="form-select form-select-soc mb-2" id="scenarioTemplateSelect">
                        <option value="">-- Choose a Pre-Engineered Educational Scenario --</option>
                        <?php foreach ($templates as $key => $tpl): ?>
                            <option value="<?= $key ?>" 
                                    data-name="<?= ViewHelper::e($tpl['name']) ?>"
                                    data-scenario="<?= ViewHelper::e($tpl['fictional_scenario']) ?>"
                                    data-objective="<?= ViewHelper::e($tpl['objective']) ?>"
                                    data-sender-name="<?= ViewHelper::e($tpl['sender_name']) ?>"
                                    data-sender-email="<?= ViewHelper::e($tpl['sender_email']) ?>"
                                    data-subject="<?= ViewHelper::e($tpl['subject']) ?>"
                                    data-body="<?= ViewHelper::e($tpl['body']) ?>">
                                <?= ViewHelper::e($tpl['name']) ?> (<?= ViewHelper::e($tpl['fictional_scenario']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="text-muted small" style="font-size: 0.74rem;">
                        Selecting a template auto-populates all educational fields with realistic, non-credential-stealing indicators.
                    </div>
                </div>
            </div>

            <!-- 2. Campaign Parameters -->
            <div class="card card-soc mb-3">
                <div class="card-header fw-semibold">
                    <i class="bi bi-sliders text-cyan me-2"></i>Campaign Parameters
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="campaign_name" class="form-label text-secondary small fw-semibold">Campaign Name</label>
                        <input type="text" class="form-control form-control-soc" id="campaign_name" name="campaign_name" placeholder="e.g. Simulation 6: Tech Grant Reward" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="fictional_scenario" class="form-label text-secondary small fw-semibold">Fictional Scenario Type</label>
                            <input type="text" class="form-control form-control-soc" id="fictional_scenario" name="fictional_scenario" value="Fake $500 reward" required>
                        </div>
                        <div class="col-md-6">
                            <label for="training_group_id" class="form-label text-secondary small fw-semibold">Target Training Cohort</label>
                            <select class="form-select form-select-soc" id="training_group_id" name="training_group_id" required>
                                <option value="">-- Select Student Group --</option>
                                <?php foreach ($groups as $g): ?>
                                    <option value="<?= $g['id'] ?>"><?= ViewHelper::e($g['group_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="educational_objective" class="form-label text-secondary small fw-semibold">Educational Learning Objective</label>
                        <textarea class="form-control form-control-soc" id="educational_objective" name="educational_objective" rows="2" placeholder="Explain the attack principles this simulation teaches students to spot..."></textarea>
                    </div>
                </div>
            </div>

            <!-- 3. Message Crafting -->
            <div class="card card-soc mb-3">
                <div class="card-header fw-semibold">
                    <i class="bi bi-envelope-paper text-cyan me-2"></i>Simulated Message Payload
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="sender_name" class="form-label text-secondary small fw-semibold">Sender Display Name</label>
                            <input type="text" class="form-control form-control-soc" id="sender_name" name="sender_name" placeholder="e.g. Hormuud Innovation Foundation" required>
                        </div>
                        <div class="col-md-6">
                            <label for="sender_email" class="form-label text-secondary small fw-semibold">Sender Email (Lookalike Domain)</label>
                            <input type="email" class="form-control form-control-soc" id="sender_email" name="sender_email" placeholder="e.g. rewards@grants-hormuud-awards.org" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="message_subject" class="form-label text-secondary small fw-semibold">Email Subject</label>
                        <input type="text" class="form-control form-control-soc" id="message_subject" name="message_subject" placeholder="e.g. Congratulations! You have been selected to receive a $500 reward" required>
                    </div>

                    <div class="mb-3">
                        <label for="message_body" class="form-label text-secondary small fw-semibold">Email Message Body</label>
                        <textarea class="form-control form-control-soc font-monospace" id="message_body" name="message_body" rows="6" placeholder="Enter the educational message lure..." required></textarea>
                    </div>
                </div>
            </div>

            <!-- 4. Ethical Authorization Confirmation -->
            <div class="card card-soc border-warning mb-4">
                <div class="card-body p-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="authConfirm" required>
                        <label class="form-check-label text-light small fw-semibold" for="authConfirm">
                            I verify and confirm this simulation is strictly for authorized educational awareness training for Hormuud University students. It will NOT harvest real credentials, will not clone third-party login pages, and will provide an educational disclosure upon interaction.
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-cyan px-4 py-2 fw-semibold">
                    <i class="bi bi-shield-check me-1"></i> Save & Submit Campaign
                </button>
                <a href="<?= ViewHelper::url('campaigns') ?>" class="btn btn-surface px-3 py-2">Cancel</a>
            </div>
        </div>

        <!-- Right: Live Simulation Message Preview -->
        <div class="col-lg-5">
            <div class="sticky-top" style="top: 90px;">
                <div class="card card-soc shadow">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="small fw-bold text-uppercase text-secondary" style="font-size: 0.72rem;">Live Participant Preview</span>
                        <span class="badge badge-soft-warning"><i class="bi bi-eye me-1"></i>Client Simulation</span>
                    </div>
                    <div class="card-body p-0">
                        <!-- Simulated Email Client Header -->
                        <div class="p-3 bg-surface border-bottom border-navy">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-secondary-subtle text-secondary small">Inbox</span>
                                <span class="text-muted small">Just now</span>
                            </div>
                            <h2 class="h6 fw-bold text-light mb-2" id="previewSubject">
                                (Subject will appear here)
                            </h2>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-surface border border-cyan text-cyan d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div>
                                    <div class="text-light small fw-bold" id="previewSenderName">Sender Display Name</div>
                                    <div class="text-muted" style="font-size: 0.72rem;" id="previewSenderEmail">&lt;sender@domain.org&gt;</div>
                                </div>
                            </div>
                        </div>

                        <!-- Simulated Email Body -->
                        <div class="p-3 text-secondary small" style="white-space: pre-wrap; min-height: 180px; font-size: 0.82rem;" id="previewBody">
(Message body will appear here...)
                        </div>

                        <!-- Simulated Action Button -->
                        <div class="p-3 bg-surface border-top border-navy text-center">
                            <button type="button" class="btn btn-primary btn-sm px-4 shadow-sm" disabled>
                                Simulated Action Button
                            </button>
                            <div class="text-muted mt-2" style="font-size: 0.68rem;">
                                Note: This button safely routes participants to the educational training page.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('scenarioTemplateSelect');
    const nameInput = document.getElementById('campaign_name');
    const scenarioInput = document.getElementById('fictional_scenario');
    const objectiveInput = document.getElementById('educational_objective');
    const senderNameInput = document.getElementById('sender_name');
    const senderEmailInput = document.getElementById('sender_email');
    const subjectInput = document.getElementById('message_subject');
    const bodyInput = document.getElementById('message_body');

    const previewSubject = document.getElementById('previewSubject');
    const previewSenderName = document.getElementById('previewSenderName');
    const previewSenderEmail = document.getElementById('previewSenderEmail');
    const previewBody = document.getElementById('previewBody');

    function updatePreview() {
        previewSubject.textContent = subjectInput.value || '(Subject will appear here)';
        previewSenderName.textContent = senderNameInput.value || 'Sender Display Name';
        previewSenderEmail.textContent = '<' + (senderEmailInput.value || 'sender@domain.org') + '>';
        previewBody.textContent = bodyInput.value || '(Message body will appear here...)';
    }

    [subjectInput, senderNameInput, senderEmailInput, bodyInput].forEach(el => {
        el.addEventListener('input', updatePreview);
    });

    select.addEventListener('change', function() {
        const opt = select.options[select.selectedIndex];
        if (!opt || !opt.value) return;

        nameInput.value = opt.getAttribute('data-name');
        scenarioInput.value = opt.getAttribute('data-scenario');
        objectiveInput.value = opt.getAttribute('data-objective');
        senderNameInput.value = opt.getAttribute('data-sender-name');
        senderEmailInput.value = opt.getAttribute('data-sender-email');
        subjectInput.value = opt.getAttribute('data-subject');
        bodyInput.value = opt.getAttribute('data-body');

        updatePreview();
    });
});
</script>
