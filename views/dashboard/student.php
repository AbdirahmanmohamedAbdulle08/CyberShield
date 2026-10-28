<?php
use App\Helpers\ViewHelper;

$totalModules = count($modules);
$completedCount = 0;
foreach ($modules as $m) {
    if (($m['progress_status'] ?? '') === 'completed') {
        $completedCount++;
    }
}
$completionRate = ($totalModules > 0) ? round(($completedCount / $totalModules) * 100) : 0;
?>
<!-- Student Portal Welcome Header -->
<div class="card card-soc mb-4 border-cyan-subtle">
    <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <div class="badge badge-soft-cyan mb-2">
                <i class="bi bi-mortarboard-fill me-1"></i>
                <span class="lang-en">Hormuud University &middot; Cybersecurity Student Portal</span>
                <span class="lang-so">Jaamacadda Hormuud &middot; Barta Ardayda Amniga Dijitaalka</span>
            </div>
            <h1 class="h3 fw-bold text-light mb-1">
                <span class="lang-en">Welcome, <?= ViewHelper::e($user['full_name']) ?></span>
                <span class="lang-so">Ku soo dhowow, <?= ViewHelper::e($user['full_name']) ?></span>
            </h1>
            <p class="text-secondary small mb-0">
                <span class="lang-en">Learn to detect social engineering lures, inspect deceptive URLs, and defend campus systems.</span>
                <span class="lang-so">Baro sida loo aqoonsado khiyaanooyinka phishing-ka, loo baaro linkiyada khatarta ah, loona difaaco nidaamyada jaamacadda.</span>
            </p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="text-end d-none d-sm-block">
                <div class="text-secondary small">
                    <span class="lang-en">Curriculum Completion</span>
                    <span class="lang-so">Heerka Dhameystirka</span>
                </div>
                <div class="h4 fw-bold text-cyan mb-0"><?= $completionRate ?>%</div>
            </div>
            <a href="<?= ViewHelper::url('report-message') ?>" class="btn btn-warning px-3 py-2 fw-semibold d-flex align-items-center gap-2">
                <i class="bi bi-shield-exclamation fs-5"></i>
                <span>
                    <span class="lang-en">Report Suspicious Message</span>
                    <span class="lang-so">Gudbi Farriin Shaki Leh</span>
                </span>
            </a>
        </div>
    </div>
</div>

<!-- Awareness Progress Bar -->
<div class="card card-soc mb-4">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="text-secondary small fw-semibold">
                <span class="lang-en">Cyber Awareness Curriculum Progress</span>
                <span class="lang-so">Horumarka Manhajka Wacyigelinta Amniga</span>
            </span>
            <span class="text-light small fw-bold">
                <?= $completedCount ?> <span class="lang-en">of</span><span class="lang-so">ka mid ah</span> <?= $totalModules ?> 
                <span class="lang-en">Lessons Completed</span><span class="lang-so">Cashar La Dhammeeyay</span>
            </span>
        </div>
        <div class="progress bg-surface border border-navy" style="height: 10px;">
            <div class="progress-bar bg-cyan" role="progressbar" style="width: <?= $completionRate ?>%;" aria-valuenow="<?= $completionRate ?>" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>

<!-- Active Simulation Advisory -->
<?php if (!empty($activeSimulations)): ?>
<div class="alert alert-warning bg-surface-card border-warning-subtle text-warning d-flex align-items-start gap-3 mb-4 p-3 rounded">
    <i class="bi bi-broadcast fs-3 flex-shrink-0 mt-1"></i>
    <div>
        <h2 class="h6 fw-bold mb-1">
            <span class="lang-en">Active Security Simulation Demonstration Underway</span>
            <span class="lang-so">Dhoolatus Amni Oo Firfircoon Ayaa Socda</span>
        </h2>
        <p class="small mb-2 text-secondary">
            <span class="lang-en">
                Your instructor or department has launched authorized educational simulations (e.g. 
                <?php foreach ($activeSimulations as $s): ?>
                    <strong class="text-light">"<?= ViewHelper::e($s['campaign_name']) ?>"</strong>,
                <?php endforeach; ?>
                ). Inspect every inbound email closely! If you suspect a simulated phishing message, use the <strong>Report</strong> button immediately to earn defense badges.
            </span>
            <span class="lang-so">
                Macallinkaaga ama waaxda ayaa bilaabay tababar dhoolatus ah oo sharciyeysan. Si fiican u baadh farriimahaaga! Haddii aad aragto farriin shaki leh, riix badhanka <strong>Gudbi</strong> si aad u hesho dhibco difaac.
            </span>
        </p>
        <a href="<?= ViewHelper::url('simulation/' . $activeSimulations[0]['public_token']) ?>" class="btn btn-outline-warning btn-sm" target="_blank">
            <i class="bi bi-box-arrow-up-right me-1"></i>
            <span class="lang-en">View Sample Simulation Lure Page</span>
            <span class="lang-so">Eeg Tusaale Bogga Phishing-ka</span>
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Assigned Awareness Modules -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 fw-bold text-light mb-0 d-flex align-items-center gap-2">
        <i class="bi bi-book-half text-cyan"></i>
        <span>
            <span class="lang-en">Security Awareness Curriculum</span>
            <span class="lang-so">Manhajka Wacyigelinta Amniga</span>
        </span>
    </h2>
    <a href="<?= ViewHelper::url('academy') ?>" class="btn btn-surface btn-sm">
        <span><span class="lang-en">View Full Academy</span><span class="lang-so">Dhammaan Casharada</span></span> <i class="bi bi-arrow-right ms-1"></i>
    </a>
</div>

<div class="row g-3 mb-4">
    <?php foreach (array_slice($modules, 0, 6) as $mod): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card card-soc h-100 d-flex flex-column">
                <div class="card-body p-3 flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge badge-soft-cyan"><span class="lang-en">Module</span><span class="lang-so">Cutubka</span> <?= $mod['module_number'] ?></span>
                        <?php if ($mod['progress_status'] === 'completed'): ?>
                            <span class="badge badge-soft-success"><i class="bi bi-check2-circle me-1"></i><span class="lang-en">Completed</span><span class="lang-so">Waa La Dhameeyay</span></span>
                        <?php else: ?>
                            <span class="badge badge-soft-secondary"><span class="lang-en">Ready to Start</span><span class="lang-so">Diyaar U Ah</span></span>
                        <?php endif; ?>
                    </div>
                    <h3 class="h6 fw-bold text-light mb-2"><?= ViewHelper::e($mod['title']) ?></h3>
                    <p class="text-secondary small mb-3">
                        <?= ViewHelper::truncate(strip_tags($mod['learning_objectives']), 90) ?>
                    </p>
                </div>
                <div class="card-footer bg-surface border-top border-navy p-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small" style="font-size: 0.72rem;">
                        <i class="bi bi-clock me-1"></i><?= $mod['estimated_minutes'] ?> mins
                    </span>
                    <a href="<?= ViewHelper::url('academy/' . $mod['slug']) ?>" class="btn btn-outline-cyan btn-sm">
                        <span>
                            <?php if ($mod['progress_status'] === 'completed'): ?>
                                <span class="lang-en">Review Lesson</span><span class="lang-so">Dib U Eeg</span>
                            <?php else: ?>
                                <span class="lang-en">Start Lesson</span><span class="lang-so">Bilow Casharka</span>
                            <?php endif; ?>
                        </span>
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Recent Quiz Scores Table -->
<div class="card card-soc">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-patch-question-fill text-cyan"></i>
            <span>
                <span class="lang-en">My Knowledge Assessment Attempts</span>
                <span class="lang-so">Taariikhda Imtixaanaadka Aqoonta</span>
            </span>
        </div>
        <a href="<?= ViewHelper::url('student/quizzes') ?>" class="btn btn-surface btn-sm">
            <span><span class="lang-en">Quiz Center</span><span class="lang-so">Xarunta Imtixaanka</span></span> <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-soc align-middle">
            <thead>
                <tr>
                    <th><span class="lang-en">Date Taken</span><span class="lang-so">Taariikhda</span></th>
                    <th><span class="lang-en">Quiz Title</span><span class="lang-so">Magaca Imtixaanka</span></th>
                    <th><span class="lang-en">Module</span><span class="lang-so">Cutubka</span></th>
                    <th><span class="lang-en">Score</span><span class="lang-so">Dhibcaha</span></th>
                    <th><span class="lang-en">Outcome</span><span class="lang-so">Natiijada</span></th>
                    <th><span class="lang-en">Action</span><span class="lang-so">Tallaabada</span></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($quizAttempts)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="bi bi-award fs-3 d-block mb-1"></i>
                            <span class="lang-en">You have not completed any quizzes yet. Complete a lesson in the Academy to take a knowledge check.</span>
                            <span class="lang-so">Weli ma aadan gelin wax imtixaan ah. Dhammee cashar ka mid ah Akademiyada si aad aqoontaada u tijaabiso.</span>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($quizAttempts as $att): ?>
                        <tr>
                            <td class="small text-secondary"><?= ViewHelper::formatDate($att['completed_at']) ?></td>
                            <td class="fw-semibold text-light small"><?= ViewHelper::e($att['quiz_title']) ?></td>
                            <td class="small text-secondary"><?= ViewHelper::e($att['module_title']) ?></td>
                            <td>
                                <span class="fw-bold <?= ($att['passed']) ? 'text-success' : 'text-danger' ?>">
                                    <?= $att['score'] ?> / <?= $att['total_questions'] ?> (<?= round($att['percentage']) ?>%)
                                </span>
                            </td>
                            <td>
                                <?php if ($att['passed']): ?>
                                    <span class="badge badge-soft-success"><i class="bi bi-check-circle me-1"></i><span class="lang-en">Passed</span><span class="lang-so">Waad Gudubtay</span></span>
                                <?php else: ?>
                                    <span class="badge badge-soft-danger"><i class="bi bi-x-circle me-1"></i><span class="lang-en">Retake Advised</span><span class="lang-so">Ku Celis</span></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= ViewHelper::url('quizzes/' . $att['quiz_id']) ?>" class="btn btn-surface btn-sm py-1">
                                    <i class="bi bi-arrow-repeat me-1"></i><span class="lang-en">Retake</span><span class="lang-so">Ku Celi</span>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
