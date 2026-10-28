<?php
use App\Helpers\ViewHelper;
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-light mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-book-half text-cyan"></i>
            <span>Awareness Academy</span>
        </h1>
        <p class="text-secondary small mb-0">Hormuud University 10-module cybersecurity curriculum and defense training</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?= ViewHelper::url('quizzes') ?>" class="btn btn-outline-cyan btn-sm">
            <i class="bi bi-patch-question-fill me-1"></i> Quiz Center
        </a>
    </div>
</div>

<div class="row g-4">
    <?php foreach ($modules as $m): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card card-soc h-100 d-flex flex-column">
                <div class="card-body p-4 flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge badge-soft-cyan">Module 0<?= $m['module_number'] ?></span>
                        <div class="d-flex align-items-center gap-1">
                            <?php if ($m['my_progress'] === 'completed'): ?>
                                <span class="badge badge-soft-success"><i class="bi bi-check2-circle me-1"></i>Completed</span>
                            <?php elseif ($m['my_progress'] === 'in_progress'): ?>
                                <span class="badge badge-soft-warning"><i class="bi bi-hourglass me-1"></i>In Progress</span>
                            <?php else: ?>
                                <span class="badge badge-soft-secondary">Not Started</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <h2 class="h5 fw-bold text-light mb-2"><?= ViewHelper::e($m['title']) ?></h2>

                    <div class="d-flex align-items-center gap-2 mb-3 small">
                        <span class="badge bg-surface border border-navy text-secondary">
                            <i class="bi bi-bar-chart me-1"></i><?= ucfirst($m['difficulty_level']) ?>
                        </span>
                        <span class="text-muted" style="font-size: 0.74rem;">
                            <i class="bi bi-clock me-1"></i><?= $m['estimated_minutes'] ?> mins
                        </span>
                    </div>

                    <p class="text-secondary small mb-0" style="line-height: 1.5;">
                        <?= ViewHelper::truncate(strip_tags($m['learning_objectives']), 110) ?>
                    </p>
                </div>

                <div class="card-footer bg-surface border-top border-navy p-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small" style="font-size: 0.72rem;">
                        <i class="bi bi-person-check me-1"></i><?= $m['completed_by_count'] ?> completions
                    </span>
                    <a href="<?= ViewHelper::url('academy/' . $m['slug']) ?>" class="btn btn-outline-cyan btn-sm">
                        <span><?= ($m['my_progress'] === 'completed') ? 'Review Lesson' : 'Start Lesson' ?></span>
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
