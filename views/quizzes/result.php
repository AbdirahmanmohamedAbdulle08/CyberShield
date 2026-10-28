<?php
use App\Helpers\ViewHelper;
?>
<div class="container py-3" style="max-width: 800px;">
    <!-- Result Score Card -->
    <div class="card card-soc mb-4 text-center p-4 <?= $passed ? 'border-success' : 'border-danger' ?>">
        <div class="mb-2">
            <?php if ($passed): ?>
                <div class="display-3 text-success mb-2"><i class="bi bi-award-fill"></i></div>
                <h1 class="h3 fw-bold text-light mb-1">Knowledge Check Passed!</h1>
                <p class="text-secondary small mb-0">Outstanding work! You demonstrated strong threat recognition competence.</p>
            <?php else: ?>
                <div class="display-3 text-danger mb-2"><i class="bi bi-exclamation-octagon-fill"></i></div>
                <h1 class="h3 fw-bold text-light mb-1">Knowledge Check Needs Review</h1>
                <p class="text-secondary small mb-0">You scored below the <?= $quiz['passing_score'] ?>% threshold. Review the explanations below and retake the assessment.</p>
            <?php endif; ?>
        </div>

        <div class="d-flex justify-content-center align-items-baseline gap-2 my-3">
            <span class="display-4 fw-bold <?= $passed ? 'text-success' : 'text-danger' ?>"><?= round($percentage) ?>%</span>
            <span class="text-secondary">Score: <strong><?= $score ?></strong> of <?= $totalQuestions ?> Correct</span>
        </div>

        <div class="d-flex justify-content-center gap-2">
            <a href="<?= ViewHelper::url('quizzes/' . $quiz['id']) ?>" class="btn btn-surface btn-sm">
                <i class="bi bi-arrow-repeat me-1"></i> Retake Assessment
            </a>
            <a href="<?= ViewHelper::url('academy') ?>" class="btn btn-outline-cyan btn-sm">
                <i class="bi bi-book me-1"></i> Awareness Academy
            </a>
            <a href="<?= ViewHelper::url('student/dashboard') ?>" class="btn btn-cyan btn-sm">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Question Explanations Breakdown -->
    <h2 class="h5 fw-bold text-light mb-3 d-flex align-items-center gap-2">
        <i class="bi bi-journal-check text-cyan"></i>
        <span>Assessment Question Review & Explanations</span>
    </h2>

    <?php foreach ($reviewDetails as $idx => $r): ?>
        <div class="card card-soc mb-3 <?= $r['is_correct'] ? 'border-success-subtle' : 'border-danger-subtle' ?>">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="badge badge-soft-cyan">Question <?= $idx + 1 ?></span>
                <?php if ($r['is_correct']): ?>
                    <span class="badge badge-soft-success"><i class="bi bi-check-circle me-1"></i>Correct</span>
                <?php else: ?>
                    <span class="badge badge-soft-danger"><i class="bi bi-x-circle me-1"></i>Incorrect</span>
                <?php endif; ?>
            </div>
            <div class="card-body p-3">
                <h3 class="h6 fw-bold text-light mb-3"><?= ViewHelper::e($r['question_text']) ?></h3>

                <div class="p-2 rounded bg-surface border border-navy small mb-2">
                    <span class="text-secondary">Your Selection:</span>
                    <strong class="<?= $r['is_correct'] ? 'text-success' : 'text-danger' ?> ms-1">
                        <?= ViewHelper::e($r['selected_option']) ?>
                    </strong>
                </div>

                <?php if (!$r['is_correct']): ?>
                    <div class="p-2 rounded bg-surface border border-navy small mb-2">
                        <span class="text-secondary">Correct Answer:</span>
                        <strong class="text-success ms-1"><?= ViewHelper::e($r['correct_option']) ?></strong>
                    </div>
                <?php endif; ?>

                <div class="p-3 rounded bg-navy border border-navy text-secondary small">
                    <div class="text-cyan fw-bold mb-1"><i class="bi bi-info-circle me-1"></i>Cybersecurity Rationale:</div>
                    <div><?= ViewHelper::e($r['explanation']) ?></div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
