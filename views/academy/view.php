<?php
use App\Core\CSRF;
use App\Helpers\ViewHelper;

// Basic markdown to HTML converter for lesson text
function renderMarkdown(string $text): string {
    // Escape HTML first
    $html = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    // Headers
    $html = preg_replace('/^### (.*?)$/m', '<h3 class="h5 fw-bold text-cyan mt-4 mb-2">$1</h3>', $html);
    $html = preg_replace('/^## (.*?)$/m', '<h2 class="h4 fw-bold text-light mt-4 mb-2">$1</h2>', $html);
    // Bold
    $html = preg_replace('/\*\*(.*?)\*\*/', '<strong class="text-light">$1</strong>', $html);
    // Code blocks
    $html = preg_replace('/```(.*?)```/s', '<pre class="p-3 rounded bg-navy border border-navy text-cyan font-monospace small my-2">$1</pre>', $html);
    // Inline code
    $html = preg_replace('/`(.*?)`/', '<code class="text-cyan">$1</code>', $html);
    // Bullet points
    $html = preg_replace('/^\- (.*?)$/m', '<li class="mb-1 text-secondary">$1</li>', $html);
    // Line breaks
    return nl2br($html);
}
?>
<!-- Breadcrumb Header -->
<div class="d-flex align-items-center gap-2 mb-2">
    <a href="<?= ViewHelper::url('academy') ?>" class="text-secondary text-decoration-none small">
        <i class="bi bi-arrow-left me-1"></i>Academy
    </a>
    <span class="text-muted">/</span>
    <span class="text-cyan small fw-semibold">Module <?= $module['module_number'] ?></span>
</div>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-light mb-1"><?= ViewHelper::e($module['title']) ?></h1>
        <div class="d-flex align-items-center gap-2 text-secondary small">
            <span class="badge bg-surface border border-navy text-cyan">Module #<?= $module['module_number'] ?></span>
            <span class="badge badge-soft-primary"><?= ucfirst($module['difficulty_level']) ?></span>
            <span><i class="bi bi-clock me-1"></i><?= $module['estimated_minutes'] ?> minutes reading</span>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <?php if (!empty($module['quiz_id'])): ?>
            <a href="<?= ViewHelper::url('quizzes/' . $module['quiz_id']) ?>" class="btn btn-cyan btn-sm d-flex align-items-center gap-1">
                <i class="bi bi-patch-question-fill"></i>
                <span>Take Knowledge Check</span>
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- 1. Learning Objectives -->
        <div class="card card-soc mb-4">
            <div class="card-header fw-semibold text-cyan">
                <i class="bi bi-bullseye me-2"></i>Learning Objectives
            </div>
            <div class="card-body">
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    <?= nl2br(ViewHelper::e($module['learning_objectives'])) ?>
                </p>
            </div>
        </div>

        <!-- 2. Detailed Lesson Content -->
        <div class="card card-soc mb-4">
            <div class="card-header fw-semibold text-light">
                <i class="bi bi-journal-text text-cyan me-2"></i>Curriculum Lesson
            </div>
            <div class="card-body p-4" style="line-height: 1.7; font-size: 0.94rem;">
                <?= renderMarkdown($module['lesson_content']) ?>
            </div>
        </div>

        <!-- 3. Real-world Suspicious Example -->
        <div class="card card-soc border-danger-subtle mb-4">
            <div class="card-header bg-surface border-bottom border-danger-subtle text-danger fw-semibold">
                <i class="bi bi-bug-fill me-2"></i>Anatomy of an Attack: Suspicious Message Case Study
            </div>
            <div class="card-body p-3">
                <div class="p-3 rounded bg-navy border border-danger-subtle font-monospace text-secondary small" style="white-space: pre-wrap; font-size: 0.82rem;">
<?= ViewHelper::e($module['suspicious_example']) ?>
                </div>
            </div>
        </div>

        <!-- 4. Warning-Sign Checklist -->
        <div class="card card-soc mb-4 border-warning-subtle">
            <div class="card-header bg-surface border-bottom border-warning-subtle text-warning fw-semibold">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>Warning-Sign Detection Checklist
            </div>
            <div class="card-body p-3">
                <div class="small text-secondary" style="line-height: 1.6;">
                    <?= renderMarkdown($module['warning_checklist']) ?>
                </div>
            </div>
        </div>

        <!-- 5. Recommended Defense Protocol -->
        <div class="card card-soc mb-4 border-success-subtle">
            <div class="card-header bg-surface border-bottom border-success-subtle text-success fw-semibold">
                <i class="bi bi-shield-fill-check me-2"></i>Recommended Defense Protocol
            </div>
            <div class="card-body p-3">
                <div class="small text-secondary" style="line-height: 1.6;">
                    <?= renderMarkdown($module['recommended_response']) ?>
                </div>
            </div>
        </div>

        <!-- Completion Form -->
        <div class="card card-soc p-4 text-center">
            <h2 class="h6 fw-bold text-light mb-2">Ready to complete this awareness module?</h2>
            <p class="text-secondary small mb-3">Marking the module as completed records your progress in the Hormuud University student cohort database.</p>
            <div class="d-flex justify-content-center gap-2">
                <form method="POST" action="<?= ViewHelper::url('academy/complete/' . $module['id']) ?>">
                    <?= CSRF::field() ?>
                    <button type="submit" class="btn btn-outline-success px-4 py-2 fw-semibold">
                        <i class="bi bi-check2-circle me-1"></i> Mark Lesson as Completed
                    </button>
                </form>
                <?php if (!empty($module['quiz_id'])): ?>
                    <a href="<?= ViewHelper::url('quizzes/' . $module['quiz_id']) ?>" class="btn btn-cyan px-4 py-2 fw-semibold">
                        <i class="bi bi-patch-question-fill me-1"></i> Take Quiz Now
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar: Course Outline & Quick Links -->
    <div class="col-lg-4">
        <div class="sticky-top" style="top: 90px;">
            <div class="card card-soc mb-3">
                <div class="card-header fw-semibold small text-uppercase text-secondary" style="font-size: 0.72rem;">
                    <i class="bi bi-bookmark-check text-cyan me-1"></i> Student Progress Status
                </div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small text-secondary">My Status:</span>
                        <?php if (($progress['status'] ?? '') === 'completed'): ?>
                            <span class="badge badge-soft-success"><i class="bi bi-check2 me-1"></i>Completed</span>
                        <?php else: ?>
                            <span class="badge badge-soft-warning"><i class="bi bi-hourglass me-1"></i>In Progress</span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($progress['completed_at'])): ?>
                        <div class="text-muted small" style="font-size: 0.72rem;">
                            Finished on: <?= ViewHelper::formatDate($progress['completed_at']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card card-soc">
                <div class="card-header fw-semibold small text-uppercase text-secondary" style="font-size: 0.72rem;">
                    <i class="bi bi-shield-alert text-warning me-1"></i> Encountered a Threat?
                </div>
                <div class="card-body p-3">
                    <p class="small text-secondary mb-3">
                        If you receive an unexpected message during class or on campus, practice reporting it in CyberShield:
                    </p>
                    <a href="<?= ViewHelper::url('report-message') ?>" class="btn btn-outline-warning btn-sm w-100">
                        <i class="bi bi-shield-exclamation me-1"></i> Report Suspicious Message
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
