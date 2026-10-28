<?php
use App\Helpers\ViewHelper;
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-light mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-broadcast text-cyan"></i>
            <span>Phishing Awareness Campaigns</span>
        </h1>
        <p class="text-secondary small mb-0">Authorized educational scenarios for Hormuud University student cohorts</p>
    </div>
    <?php if ($user['role'] !== 'instructor'): ?>
        <a href="<?= ViewHelper::url('campaigns/create') ?>" class="btn btn-cyan btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Launch Campaign Builder</span>
        </a>
    <?php endif; ?>
</div>

<!-- Filters Bar -->
<div class="card card-soc mb-4">
    <div class="card-body p-3">
        <form method="GET" action="<?= ViewHelper::url('campaigns') ?>" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-surface border-navy text-secondary"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control form-control-soc" name="search" value="<?= ViewHelper::e($search) ?>" placeholder="Search campaigns, scenarios, or subjects...">
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select form-select-soc" name="status">
                    <option value="">All Statuses</option>
                    <option value="draft" <?= ($statusFilter === 'draft') ? 'selected' : '' ?>>Draft</option>
                    <option value="pending_authorization" <?= ($statusFilter === 'pending_authorization') ? 'selected' : '' ?>>Pending Authorization</option>
                    <option value="authorized" <?= ($statusFilter === 'authorized') ? 'selected' : '' ?>>Authorized</option>
                    <option value="running" <?= ($statusFilter === 'running') ? 'selected' : '' ?>>Running</option>
                    <option value="completed" <?= ($statusFilter === 'completed') ? 'selected' : '' ?>>Completed</option>
                    <option value="cancelled" <?= ($statusFilter === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-surface border-navy w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <?php if (!empty($statusFilter) || !empty($search)): ?>
                    <a href="<?= ViewHelper::url('campaigns') ?>" class="btn btn-outline-danger">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Campaigns Table -->
<div class="card card-soc">
    <div class="table-responsive">
        <table class="table table-soc align-middle">
            <thead>
                <tr>
                    <th>Campaign ID</th>
                    <th>Name & Educational Scenario</th>
                    <th>Status</th>
                    <th>Target Cohort</th>
                    <th>Clicks / Views</th>
                    <th>Reports</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($campaigns)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-broadcast fs-2 d-block mb-2"></i>
                            No campaigns match the selected criteria.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($campaigns as $c): ?>
                        <tr>
                            <td>
                                <span class="badge bg-surface border border-navy text-secondary">#<?= $c['id'] ?></span>
                            </td>
                            <td>
                                <div class="fw-bold text-light"><?= ViewHelper::e($c['campaign_name']) ?></div>
                                <div class="text-secondary small d-flex align-items-center gap-1">
                                    <i class="bi bi-tag text-cyan"></i>
                                    <span><?= ViewHelper::e($c['fictional_scenario']) ?></span>
                                </div>
                            </td>
                            <td>
                                <?= ViewHelper::badgeStatus($c['status']) ?>
                            </td>
                            <td>
                                <span class="small text-light d-block"><?= ViewHelper::e($c['group_name'] ?: 'All Participants') ?></span>
                                <span class="text-muted" style="font-size: 0.72rem;"><?= $c['target_count'] ?> Students</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge badge-soft-danger" title="Link Clicks" data-bs-toggle="tooltip">
                                        <i class="bi bi-cursor-fill me-1"></i><?= $c['click_count'] ?>
                                    </span>
                                    <span class="badge badge-soft-warning" title="Training Views" data-bs-toggle="tooltip">
                                        <i class="bi bi-book me-1"></i><?= $c['training_count'] ?>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-soft-success">
                                    <i class="bi bi-shield-check me-1"></i><?= $c['report_count'] ?>
                                </span>
                            </td>
                            <td class="small text-secondary">
                                <?= ViewHelper::timeAgo($c['created_at']) ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= ViewHelper::url('campaigns/' . $c['id']) ?>" class="btn btn-outline-cyan btn-sm">
                                    <span>Inspect</span> <i class="bi bi-chevron-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
