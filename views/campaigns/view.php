<?php
use App\Core\Auth;
use App\Core\CSRF;
use App\Helpers\ViewHelper;

$currentUser = Auth::user();
$simUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . ViewHelper::url('simulation/' . $campaign['public_token']);
?>
<!-- Campaign Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="<?= ViewHelper::url('campaigns') ?>" class="text-secondary text-decoration-none small">
                <i class="bi bi-arrow-left me-1"></i>Campaigns
            </a>
            <span class="text-muted">/</span>
            <span class="text-secondary small">Simulation #<?= $campaign['id'] ?></span>
        </div>
        <h1 class="h3 fw-bold text-light mb-1 d-flex align-items-center gap-2">
            <span><?= ViewHelper::e($campaign['campaign_name']) ?></span>
            <?= ViewHelper::badgeStatus($campaign['status']) ?>
        </h1>
        <div class="text-secondary small">
            Fictional Scenario: <span class="text-cyan fw-semibold"><?= ViewHelper::e($campaign['fictional_scenario']) ?></span> · Target Cohort: <span class="text-light"><?= ViewHelper::e($campaign['group_name'] ?: 'All Cohorts') ?></span>
        </div>
    </div>

    <!-- State-Changing Workflow Actions -->
    <div class="d-flex align-items-center gap-2">
        <?php if ($campaign['status'] === 'pending_authorization' && $currentUser['role'] === 'super_admin'): ?>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#authorizeModal">
                <i class="bi bi-shield-check me-1"></i> Authorize Campaign
            </button>
        <?php endif; ?>

        <?php if (in_array($campaign['status'], ['draft', 'authorized'], true)): ?>
            <form method="POST" action="<?= ViewHelper::url('campaigns/start/' . $campaign['id']) ?>" onsubmit="return confirm('Launch this educational simulation to active status?');">
                <?= CSRF::field() ?>
                <button type="submit" class="btn btn-cyan btn-sm">
                    <i class="bi bi-play-circle-fill me-1"></i> Start Simulation
                </button>
            </form>
        <?php endif; ?>

        <?php if ($campaign['status'] === 'running'): ?>
            <form method="POST" action="<?= ViewHelper::url('campaigns/complete/' . $campaign['id']) ?>" onsubmit="return confirm('Conclude and archive this simulation campaign?');">
                <?= CSRF::field() ?>
                <button type="submit" class="btn btn-outline-warning btn-sm">
                    <i class="bi bi-check-circle me-1"></i> Complete & Archive
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<!-- Safe Public Simulation URL Banner -->
<div class="card card-soc border-cyan-subtle mb-4">
    <div class="card-body p-3">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <div class="overflow-hidden">
                <span class="text-secondary small fw-bold text-uppercase d-block mb-1" style="font-size: 0.72rem;">
                    <i class="bi bi-link-45deg text-cyan me-1"></i>Safe Educational Simulation URL (Public Non-Guessable Token)
                </span>
                <code class="text-cyan text-break" style="font-size: 0.88rem;"><?= htmlspecialchars($simUrl) ?></code>
            </div>
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <button type="button" class="btn btn-outline-cyan btn-sm" data-copy="<?= htmlspecialchars($simUrl) ?>">
                    <i class="bi bi-clipboard me-1"></i> Copy Link
                </button>
                <a href="<?= ViewHelper::url('simulation/' . $campaign['public_token']) ?>" class="btn btn-cyan btn-sm" target="_blank">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Test Simulation
                </a>
            </div>
        </div>
    </div>
</div>

<!-- 4 Outcomes Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="cs-kpi-card">
            <div class="text-secondary small fw-semibold text-uppercase" style="font-size: 0.72rem;">Target Students</div>
            <div class="cs-kpi-val text-light mt-1"><?= $stats['targets'] ?></div>
            <div class="text-muted small mt-1" style="font-size: 0.72rem;">Eligible participants</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="cs-kpi-card kpi-crimson">
            <div class="text-secondary small fw-semibold text-uppercase" style="font-size: 0.72rem;">Lures Triggered (Clicks)</div>
            <div class="cs-kpi-val text-danger mt-1"><?= $stats['clicks'] ?></div>
            <?php $clickRate = ($stats['targets'] > 0) ? round(($stats['clicks'] / $stats['targets']) * 100, 1) : 0; ?>
            <div class="text-muted small mt-1" style="font-size: 0.72rem;"><?= $clickRate ?>% click-through rate</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="cs-kpi-card kpi-amber">
            <div class="text-secondary small fw-semibold text-uppercase" style="font-size: 0.72rem;">Training Disclosures</div>
            <div class="cs-kpi-val text-amber mt-1"><?= $stats['training'] ?></div>
            <div class="text-muted small mt-1" style="font-size: 0.72rem;">Educational views</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="cs-kpi-card kpi-emerald">
            <div class="text-secondary small fw-semibold text-uppercase" style="font-size: 0.72rem;">Threat Reports</div>
            <div class="cs-kpi-val text-emerald mt-1"><?= $stats['reports'] ?></div>
            <?php $repRate = ($stats['targets'] > 0) ? round(($stats['reports'] / $stats['targets']) * 100, 1) : 0; ?>
            <div class="text-muted small mt-1" style="font-size: 0.72rem;"><?= $repRate ?>% reporting defense rate</div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Left: Simulated Message Specifications -->
    <div class="col-lg-6">
        <div class="card card-soc h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-envelope-open text-cyan me-2"></i>Simulated Lure Specifications</span>
                <span class="badge bg-surface border border-navy text-secondary">Fictional Template</span>
            </div>
            <div class="card-body p-3">
                <div class="mb-3">
                    <span class="text-secondary small fw-bold d-block">Educational Objective:</span>
                    <p class="small text-light mb-0"><?= ViewHelper::e($campaign['educational_objective']) ?></p>
                </div>
                <div class="p-3 rounded bg-surface border border-navy mb-3">
                    <div class="small text-secondary mb-1">
                        <strong>Sender:</strong> <span class="text-light"><?= ViewHelper::e($campaign['sender_name']) ?></span> 
                        <code class="text-cyan">&lt;<?= ViewHelper::e($campaign['sender_email']) ?>&gt;</code>
                    </div>
                    <div class="small text-secondary mb-2">
                        <strong>Subject:</strong> <span class="text-light fw-bold"><?= ViewHelper::e($campaign['message_subject']) ?></span>
                    </div>
                    <hr class="border-navy my-2">
                    <div class="small text-light font-monospace" style="white-space: pre-wrap; font-size: 0.82rem;"><?= ViewHelper::e($campaign['message_body']) ?></div>
                </div>
                <div class="text-muted small" style="font-size: 0.74rem;">
                    <i class="bi bi-info-circle text-cyan me-1"></i>
                    This message was created by <strong><?= ViewHelper::e($campaign['creator_name']) ?></strong> on <?= ViewHelper::formatDate($campaign['created_at']) ?>.
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Authorization History -->
    <div class="col-lg-6">
        <div class="card card-soc h-100">
            <div class="card-header fw-semibold">
                <i class="bi bi-journal-check text-cyan me-2"></i>Safety Authorizations & Audit Trail
            </div>
            <div class="card-body p-3">
                <?php if (empty($authorizations)): ?>
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-shield-exclamation fs-3 d-block mb-1"></i>
                        No formal authorization records recorded yet.
                    </div>
                <?php else: ?>
                    <div class="timeline">
                        <?php foreach ($authorizations as $auth): ?>
                            <div class="p-2 mb-2 rounded bg-surface border border-navy small">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="text-light"><?= ViewHelper::e($auth['authorizer_name']) ?></strong>
                                    <span class="badge badge-soft-success">Approved</span>
                                </div>
                                <div class="text-secondary"><?= ViewHelper::e($auth['authorization_notes']) ?></div>
                                <div class="text-muted text-end mt-1" style="font-size: 0.68rem;"><?= ViewHelper::formatDate($auth['authorized_at']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Target Participant Cohort Outcomes Table -->
<div class="card card-soc">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-people text-cyan me-2"></i>Target Participant Cohort Outcomes</span>
        <span class="badge badge-soft-cyan"><?= count($participants) ?> Enrolled</span>
    </div>
    <div class="table-responsive">
        <table class="table table-soc align-middle">
            <thead>
                <tr>
                    <th>Participant Token</th>
                    <th>Pseudonym</th>
                    <th>Student Name</th>
                    <th>Clicked Simulated Lure</th>
                    <th>Viewed Training Page</th>
                    <th>Reported Suspicious</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($participants)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            No participants assigned to this training group yet.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($participants as $p): ?>
                        <tr>
                            <td><code class="text-cyan small"><?= ViewHelper::e($p['participant_token']) ?></code></td>
                            <td><span class="badge bg-surface border border-navy text-light"><?= ViewHelper::e($p['pseudonym']) ?></span></td>
                            <td class="small text-secondary"><?= ViewHelper::e($p['display_name']) ?></td>
                            <td>
                                <?php if ($p['clicked'] > 0): ?>
                                    <span class="badge badge-soft-danger"><i class="bi bi-exclamation-triangle me-1"></i>Clicked (<?= $p['clicked'] ?>)</span>
                                <?php else: ?>
                                    <span class="badge badge-soft-secondary"><i class="bi bi-dash"></i></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['viewed_training'] > 0): ?>
                                    <span class="badge badge-soft-warning"><i class="bi bi-book-half me-1"></i>Viewed</span>
                                <?php else: ?>
                                    <span class="badge badge-soft-secondary"><i class="bi bi-dash"></i></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['reported'] > 0): ?>
                                    <span class="badge badge-soft-success"><i class="bi bi-shield-fill-check me-1"></i>Reported!</span>
                                <?php else: ?>
                                    <span class="badge badge-soft-secondary"><i class="bi bi-dash"></i></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Super Admin Authorization -->
<div class="modal fade" id="authorizeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-soc">
            <form method="POST" action="<?= ViewHelper::url('campaigns/authorize/' . $campaign['id']) ?>">
                <?= CSRF::field() ?>
                <div class="modal-header">
                    <h5 class="modal-title text-light fw-bold">Formal Campaign Authorization</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-secondary">
                        As a Super Admin, please review the educational objective and fictional scenario to ensure full compliance with the ethical training guidelines of Hormuud University.
                    </p>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Authorization Notes / Justification</label>
                        <textarea class="form-control form-control-soc" name="authorization_notes" rows="3" required>Authorized for live educational training in Hormuud University CS Cyber Security cohort. Strictly educational simulation with zero credential harvesting.</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-surface" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan">
                        <i class="bi bi-shield-check me-1"></i> Approve & Authorize
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
