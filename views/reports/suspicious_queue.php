<?php
use App\Core\CSRF;
use App\Helpers\ViewHelper;
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-light mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-shield-exclamation text-warning"></i>
            <span>Suspicious Message Reports Queue</span>
        </h1>
        <p class="text-secondary small mb-0">SOC triage desk: inspect, review, and resolve phishing reports submitted by students</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?= ViewHelper::url('report-message') ?>" class="btn btn-warning btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Submit Test Report
        </a>
    </div>
</div>

<!-- Queue Filter -->
<div class="card card-soc mb-4">
    <div class="card-body p-3">
        <form method="GET" action="<?= ViewHelper::url('reports/suspicious') ?>" class="d-flex align-items-center gap-3">
            <span class="text-secondary small fw-semibold text-nowrap">Filter Status:</span>
            <select class="form-select form-select-soc" name="status" onchange="this.form.submit()" style="max-width: 240px;">
                <option value="">All Reports (<?= count($reports) ?>)</option>
                <option value="submitted" <?= ($statusFilter === 'submitted') ? 'selected' : '' ?>>Submitted (New)</option>
                <option value="under_review" <?= ($statusFilter === 'under_review') ? 'selected' : '' ?>>Under Review</option>
                <option value="reviewed" <?= ($statusFilter === 'reviewed') ? 'selected' : '' ?>>Reviewed</option>
                <option value="resolved" <?= ($statusFilter === 'resolved') ? 'selected' : '' ?>>Resolved</option>
            </select>
            <?php if (!empty($statusFilter)): ?>
                <a href="<?= ViewHelper::url('reports/suspicious') ?>" class="btn btn-outline-danger btn-sm">Reset</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Reports Table -->
<div class="card card-soc">
    <div class="table-responsive">
        <table class="table table-soc align-middle">
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>Reported Timestamp</th>
                    <th>Reporter Name</th>
                    <th>Primary Indicator</th>
                    <th>Subject / Campaign</th>
                    <th>Status</th>
                    <th>Reviewer</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reports)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-shield-check fs-2 d-block mb-2 text-success"></i>
                            No suspicious reports in this queue filter.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reports as $r): ?>
                        <tr>
                            <td><span class="badge bg-surface border border-navy text-secondary">#<?= $r['id'] ?></span></td>
                            <td class="small text-secondary text-nowrap"><?= ViewHelper::formatDate($r['created_at']) ?></td>
                            <td>
                                <div class="fw-semibold text-light small"><?= ViewHelper::e($r['reporter_name']) ?></div>
                                <code class="text-muted" style="font-size: 0.72rem;"><?= ViewHelper::e($r['participant_token'] ?: 'anon') ?></code>
                            </td>
                            <td>
                                <span class="badge badge-soft-warning small">
                                    <?= ucfirst(str_replace('_', ' ', $r['reason'])) ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-light small"><?= ViewHelper::e($r['message_subject']) ?></div>
                                <?php if (!empty($r['campaign_name'])): ?>
                                    <div class="text-cyan" style="font-size: 0.72rem;"><i class="bi bi-tag me-1"></i><?= ViewHelper::e($r['campaign_name']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= ViewHelper::badgeStatus($r['status']) ?></td>
                            <td class="small text-secondary">
                                <?= ViewHelper::e($r['reviewer_name'] ?: 'Pending Review') ?>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-outline-cyan btn-sm" data-bs-toggle="modal" data-bs-target="#reviewModal<?= $r['id'] ?>">
                                    <i class="bi bi-pencil-square me-1"></i> Triage
                                </button>
                            </td>
                        </tr>

                        <!-- Modal: Triage & Resolution -->
                        <div class="modal fade" id="reviewModal<?= $r['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content modal-soc">
                                    <form method="POST" action="<?= ViewHelper::url('reports/suspicious/update/' . $r['id']) ?>">
                                        <?= CSRF::field() ?>
                                        <div class="modal-header">
                                            <h5 class="modal-title text-light fw-bold">Triage Incident Report #<?= $r['id'] ?></h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <div class="p-3 rounded bg-surface border border-navy mb-3 small">
                                                <div class="text-secondary mb-1"><strong>Subject:</strong> <?= ViewHelper::e($r['message_subject']) ?></div>
                                                <div class="text-secondary mb-1"><strong>Reporter Reason:</strong> <span class="badge badge-soft-warning"><?= ViewHelper::e($r['reason']) ?></span></div>
                                                <div class="text-secondary"><strong>Student Explanation:</strong></div>
                                                <p class="text-light mb-0 mt-1 font-monospace" style="font-size: 0.8rem;"><?= ViewHelper::e($r['explanation'] ?: 'No additional notes provided.') ?></p>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label text-secondary small fw-semibold">Update Status</label>
                                                <select class="form-select form-select-soc" name="status" required>
                                                    <option value="under_review" <?= ($r['status'] === 'under_review') ? 'selected' : '' ?>>Under Review</option>
                                                    <option value="reviewed" <?= ($r['status'] === 'reviewed') ? 'selected' : '' ?>>Reviewed</option>
                                                    <option value="resolved" <?= ($r['status'] === 'resolved') ? 'selected' : '' ?>>Resolved (Confirmed Simulation / Defense Badge Awarded)</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label text-secondary small fw-semibold">Internal Resolution Notes</label>
                                                <textarea class="form-control form-control-soc" name="resolution_notes" rows="3" placeholder="Add analyst resolution notes..."><?= ViewHelper::e($r['resolution_notes'] ?? '') ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-surface" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-cyan">Save Resolution</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
