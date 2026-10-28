<?php
use App\Core\CSRF;
use App\Helpers\ViewHelper;
?>
<div class="container py-3" style="max-width: 800px;">
    <!-- Assessment Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="<?= ViewHelper::url('quizzes') ?>" class="text-secondary text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i>Quiz Center
                </a>
                <span class="text-muted">/</span>
                <span class="text-cyan small fw-semibold">Assessment #<?= $quiz['id'] ?></span>
            </div>
            <h1 class="h3 fw-bold text-light mb-1"><?= ViewHelper::e($quiz['title']) ?></h1>
            <p class="text-secondary small mb-0">Module <?= $quiz['module_number'] ?>: <?= ViewHelper::e($quiz['module_title']) ?></p>
        </div>
        <div class="badge badge-soft-warning px-3 py-2 small">
            Passing Criteria: <?= $quiz['passing_score'] ?>%
        </div>
    </div>

    <?php if (empty($questions)): ?>
        <div class="card card-soc p-5 text-center text-muted">
            <i class="bi bi-patch-question fs-2 d-block mb-2"></i>
            No questions configured for this quiz yet.
        </div>
    <?php else: ?>
        <form method="POST" action="<?= ViewHelper::url('quizzes/' . $quiz['id'] . '/submit') ?>">
            <?= CSRF::field() ?>

            <?php foreach ($questions as $idx => $q): ?>
                <div class="card card-soc mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="badge badge-soft-cyan">Question <?= $idx + 1 ?> of <?= count($questions) ?></span>
                        <span class="text-muted small">Single Choice</span>
                    </div>
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold text-light mb-4" style="line-height: 1.5;">
                            <?= ViewHelper::e($q['question_text']) ?>
                        </h2>

                        <div class="d-flex flex-column gap-2">
                            <?php foreach ($q['options'] as $opt): ?>
                                <label class="p-3 rounded bg-surface border border-navy d-flex align-items-center gap-3 cursor-pointer option-label">
                                    <input class="form-check-input flex-shrink-0" type="radio" name="answers[<?= $q['id'] ?>]" value="<?= $opt['id'] ?>" required>
                                    <span class="small text-light"><?= ViewHelper::e($opt['option_text']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="card card-soc p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                <span class="text-secondary small">
                    <i class="bi bi-shield-check text-cyan me-1"></i>
                    Answers will be scored on the server. Correct answers are verified against database records.
                </span>
                <button type="submit" class="btn btn-cyan px-4 py-2 fw-semibold flex-shrink-0">
                    <i class="bi bi-check2-circle me-1"></i> Submit Assessment
                </button>
            </div>
        </form>
    <?php endif; ?>
</div>

<style>
.cursor-pointer { cursor: pointer; }
.option-label:hover {
    background-color: var(--cs-surface-hover) !important;
    border-color: var(--cs-border-glow) !important;
}
.option-label:has(input:checked) {
    background: linear-gradient(90deg, rgba(2, 132, 199, 0.25) 0%, rgba(0, 229, 255, 0.1) 100%) !important;
    border-color: var(--cs-cyan) !important;
}
</style>
