<?php
use App\Helpers\ViewHelper;
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-light mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-patch-question-fill text-cyan"></i>
            <span>Knowledge Assessments & Quizzes</span>
        </h1>
        <p class="text-secondary small mb-0">Evaluate student threat recognition, URL inspection skills, and incident response readiness</p>
    </div>
    <a href="<?= ViewHelper::url('academy') ?>" class="btn btn-outline-cyan btn-sm">
        <i class="bi bi-book me-1"></i> Review Academy Lessons
    </a>
</div>

<div class="card card-soc">
    <div class="table-responsive">
        <table class="table table-soc align-middle">
            <thead>
                <tr>
                    <th>Assessment Title</th>
                    <th>Linked Module</th>
                    <th>Questions</th>
                    <th>Passing Score</th>
                    <th>Cohort Attempts</th>
                    <th>My Best Score</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quizzes as $q): ?>
                    <tr>
                        <td>
                            <div class="fw-bold text-light"><?= ViewHelper::e($q['title']) ?></div>
                            <div class="text-muted" style="font-size: 0.72rem;"><?= ViewHelper::e($q['description']) ?></div>
                        </td>
                        <td>
                            <span class="badge bg-surface border border-navy text-cyan small">
                                Module <?= $q['module_number'] ?>: <?= ViewHelper::e($q['module_title']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="text-light fw-semibold small"><?= $q['question_count'] ?> Questions</span>
                        </td>
                        <td>
                            <span class="badge badge-soft-warning"><?= $q['passing_score'] ?>%</span>
                        </td>
                        <td>
                            <span class="small text-secondary"><?= $q['total_attempts'] ?> attempts</span>
                        </td>
                        <td>
                            <?php if ($q['my_high_score'] !== null): ?>
                                <span class="badge <?= ($q['my_high_score'] >= $q['passing_score']) ? 'badge-soft-success' : 'badge-soft-danger' ?>">
                                    <?= round($q['my_high_score']) ?>%
                                </span>
                            <?php else: ?>
                                <span class="badge badge-soft-secondary">Not Attempted</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="<?= ViewHelper::url('quizzes/' . $q['id']) ?>" class="btn btn-cyan btn-sm">
                                <span>Take Quiz</span> <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
