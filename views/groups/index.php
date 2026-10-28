<?php
use App\Core\CSRF;
use App\Helpers\ViewHelper;
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-light mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-diagram-3-fill text-cyan"></i>
            <span>Training Groups & Cohorts</span>
        </h1>
        <p class="text-secondary small mb-0">Hormuud University academic groups enrolled in cybersecurity awareness programs</p>
    </div>
    <?php if ($user['role'] !== 'instructor'): ?>
        <button type="button" class="btn btn-cyan btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addGroupModal">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Create New Cohort</span>
        </button>
    <?php endif; ?>
</div>

<!-- Groups Cards -->
<div class="row g-3 mb-4">
    <?php foreach ($groups as $g): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card card-soc h-100 d-flex flex-column">
                <div class="card-body p-4 flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-surface border border-navy text-cyan">Group #<?= $g['id'] ?></span>
                        <?= ViewHelper::badgeStatus($g['status']) ?>
                    </div>
                    <h2 class="h5 fw-bold text-light mb-2"><?= ViewHelper::e($g['group_name']) ?></h2>
                    <p class="text-secondary small mb-3">
                        <?= ViewHelper::e($g['description'] ?: 'No description provided.') ?>
                    </p>
                    <div class="p-2 rounded bg-surface border border-navy small text-secondary mb-2">
                        <i class="bi bi-person-badge text-cyan me-1"></i>
                        Instructor: <strong class="text-light"><?= ViewHelper::e($g['instructor_name'] ?: 'Unassigned') ?></strong>
                    </div>
                </div>
                <div class="card-footer bg-surface border-top border-navy p-3 d-flex justify-content-between align-items-center">
                    <span class="text-secondary small">
                        <strong class="text-light"><?= $g['participant_count'] ?></strong> Students Enrolled
                    </span>
                    <a href="<?= ViewHelper::url('participants?group_id=' . $g['id']) ?>" class="btn btn-outline-cyan btn-sm">
                        <span>View Students</span> <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal: Add Group -->
<div class="modal fade" id="addGroupModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-soc">
            <form method="POST" action="<?= ViewHelper::url('groups/store') ?>">
                <?= CSRF::field() ?>
                <div class="modal-header">
                    <h5 class="modal-title text-light fw-bold">Create Training Cohort</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Group / Cohort Name</label>
                        <input type="text" class="form-control form-control-soc" name="group_name" placeholder="e.g. HU Cyber Security Cohort 2026" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Description</label>
                        <textarea class="form-control form-control-soc" name="description" rows="2" placeholder="e.g. Undergraduate juniors majoring in network security..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Assigned Faculty Instructor</label>
                        <select class="form-select form-select-soc" name="instructor_id">
                            <option value="">-- Select Instructor --</option>
                            <?php foreach ($instructors as $inst): ?>
                                <option value="<?= $inst['id'] ?>"><?= ViewHelper::e($inst['full_name']) ?> (@<?= ViewHelper::e($inst['username']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-surface" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan">Create Group</button>
                </div>
            </form>
        </div>
    </div>
</div>
