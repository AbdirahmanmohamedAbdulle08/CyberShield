<?php
use App\Helpers\ViewHelper;
?>
<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-light mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-shield-shaded text-cyan"></i>
            <span>
                <span class="lang-en">Security Operations Center Dashboard</span>
                <span class="lang-so">Koontaroolka Xarunta Amniga (SOC)</span>
            </span>
        </h1>
        <p class="text-secondary small mb-0">
            <span class="lang-en">Real-time awareness telemetry, phishing simulation telemetry, and defense readiness metrics &mdash; Hormuud University</span>
            <span class="lang-so">Xogta tooska ah ee wacyigelinta, dhoolatuska phishing-ka, iyo cabbiraadda difaaca &mdash; Jaamacadda Hormuud</span>
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?= ViewHelper::url('events') ?>" class="btn btn-surface btn-sm d-flex align-items-center gap-1">
            <span class="pulse-indicator"></span>
            <span class="ms-1">
                <span class="lang-en">Event Monitor</span>
                <span class="lang-so">Daawashada Dhacdooyinka</span>
            </span>
        </a>
        <a href="<?= ViewHelper::url('campaigns/create') ?>" class="btn btn-cyan btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-plus-circle-fill"></i>
            <span>
                <span class="lang-en">New Campaign</span>
                <span class="lang-so">Ololo Cusub</span>
            </span>
        </a>
    </div>
</div>

<!-- 8 Live Database KPI Metric Cards -->
<div class="row g-3 mb-4">
    <!-- 1. Participants -->
    <div class="col-6 col-lg-3">
        <div class="cs-kpi-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-light small fw-bold text-uppercase" style="font-size: 0.76rem; letter-spacing: 0.05em;">
                    <span class="lang-en">Participants</span>
                    <span class="lang-so">Ardayda</span>
                </span>
                <div class="cs-kpi-icon bg-surface text-cyan"><i class="bi bi-people"></i></div>
            </div>
            <div class="cs-kpi-val"><?= number_format($kpis['totalParticipants']) ?></div>
            <div class="small mt-1 text-secondary" style="font-size: 0.74rem;">
                <span class="lang-en">Authorized student cohort</span>
                <span class="lang-so">Dufcadda ardayda ee diiwaangashan</span>
            </div>
        </div>
    </div>

    <!-- 2. Campaigns -->
    <div class="col-6 col-lg-3">
        <div class="cs-kpi-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-light small fw-bold text-uppercase" style="font-size: 0.76rem; letter-spacing: 0.05em;">
                    <span class="lang-en">Simulations</span>
                    <span class="lang-so">Dhoolatusyada</span>
                </span>
                <div class="cs-kpi-icon bg-surface text-blue-light"><i class="bi bi-broadcast"></i></div>
            </div>
            <div class="cs-kpi-val d-flex align-items-baseline gap-2">
                <span><?= number_format($kpis['totalCampaigns']) ?></span>
                <span class="badge badge-soft-cyan small fw-semibold" style="font-size: 0.72rem;">
                    <?= $kpis['activeCampaigns'] ?> 
                    <span class="lang-en">Active</span><span class="lang-so">Socda</span>
                </span>
            </div>
            <div class="small mt-1 text-secondary" style="font-size: 0.74rem;">
                <span class="lang-en">Total educational scenarios</span>
                <span class="lang-so">Wadarta xaaladaha tababarka</span>
            </div>
        </div>
    </div>

    <!-- 3. Link Clicks -->
    <div class="col-6 col-lg-3">
        <div class="cs-kpi-card kpi-crimson">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-light small fw-bold text-uppercase" style="font-size: 0.76rem; letter-spacing: 0.05em;">
                    <span class="lang-en">Simulation Clicks</span>
                    <span class="lang-so">Riixitaanka Lures-ka</span>
                </span>
                <div class="cs-kpi-icon bg-surface text-danger"><i class="bi bi-cursor-fill"></i></div>
            </div>
            <div class="cs-kpi-val text-danger"><?= number_format($kpis['totalClicks']) ?></div>
            <div class="small mt-1 text-secondary" style="font-size: 0.74rem;">
                <span class="lang-en">Educational lures triggered</span>
                <span class="lang-so">Khiyaanooyinka la tijaabiyay</span>
            </div>
        </div>
    </div>

    <!-- 4. Training Visited -->
    <div class="col-6 col-lg-3">
        <div class="cs-kpi-card kpi-amber">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-light small fw-bold text-uppercase" style="font-size: 0.76rem; letter-spacing: 0.05em;">
                    <span class="lang-en">Training Views</span>
                    <span class="lang-so">Daawashada Tababarka</span>
                </span>
                <div class="cs-kpi-icon bg-surface text-amber"><i class="bi bi-book"></i></div>
            </div>
            <div class="cs-kpi-val text-amber"><?= number_format($kpis['totalTrainingVisits']) ?></div>
            <div class="small mt-1 text-secondary" style="font-size: 0.74rem;">
                <span class="lang-en">Post-lure disclosures viewed</span>
                <span class="lang-so">Dersiyada xogta la akhriyay</span>
            </div>
        </div>
    </div>

    <!-- 5. Suspicious Reports -->
    <div class="col-6 col-lg-3">
        <div class="cs-kpi-card kpi-emerald">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-light small fw-bold text-uppercase" style="font-size: 0.76rem; letter-spacing: 0.05em;">
                    <span class="lang-en">Reported Attacks</span>
                    <span class="lang-so">Farriimaha La Soo Sheegay</span>
                </span>
                <div class="cs-kpi-icon bg-surface text-emerald"><i class="bi bi-shield-fill-check"></i></div>
            </div>
            <div class="cs-kpi-val text-emerald"><?= number_format($kpis['totalReports']) ?></div>
            <div class="small mt-1 text-secondary" style="font-size: 0.74rem;">
                <span class="lang-en">Student incident reports</span>
                <span class="lang-so">Warbixinnada digniinta ardayda</span>
            </div>
        </div>
    </div>

    <!-- 6. Completed Lessons -->
    <div class="col-6 col-lg-3">
        <div class="cs-kpi-card kpi-emerald">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-light small fw-bold text-uppercase" style="font-size: 0.76rem; letter-spacing: 0.05em;">
                    <span class="lang-en">Lessons Completed</span>
                    <span class="lang-so">Casharada Dhamaaday</span>
                </span>
                <div class="cs-kpi-icon bg-surface text-emerald"><i class="bi bi-mortarboard"></i></div>
            </div>
            <div class="cs-kpi-val"><?= number_format($kpis['completedLessons']) ?></div>
            <div class="small mt-1 text-secondary" style="font-size: 0.74rem;">
                <span class="lang-en">Modules fully finished</span>
                <span class="lang-so">Cutubyada guusha leh</span>
            </div>
        </div>
    </div>

    <!-- 7. Average Quiz Score -->
    <div class="col-6 col-lg-3">
        <div class="cs-kpi-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-light small fw-bold text-uppercase" style="font-size: 0.76rem; letter-spacing: 0.05em;">
                    <span class="lang-en">Avg Quiz Score</span>
                    <span class="lang-so">Celceliska Imtixaanka</span>
                </span>
                <div class="cs-kpi-icon bg-surface text-cyan"><i class="bi bi-patch-check"></i></div>
            </div>
            <div class="cs-kpi-val text-cyan"><?= $kpis['avgQuizScore'] ?>%</div>
            <div class="small mt-1 text-secondary" style="font-size: 0.74rem;">
                <span class="lang-en">Knowledge check average</span>
                <span class="lang-so">Heerka celceliska imtixaanka</span>
            </div>
        </div>
    </div>

    <!-- 8. Defense Ratio -->
    <div class="col-6 col-lg-3">
        <div class="cs-kpi-card">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-light small fw-bold text-uppercase" style="font-size: 0.76rem; letter-spacing: 0.05em;">
                    <span class="lang-en">Reporting Ratio</span>
                    <span class="lang-so">Heerka Soo Sheegista</span>
                </span>
                <div class="cs-kpi-icon bg-surface text-blue-light"><i class="bi bi-bar-chart-steps"></i></div>
            </div>
            <?php 
                $ratio = ($kpis['totalClicks'] > 0) ? round(($kpis['totalReports'] / $kpis['totalClicks']) * 100, 1) : 0;
            ?>
            <div class="cs-kpi-val"><?= $ratio ?>%</div>
            <div class="small mt-1 text-secondary" style="font-size: 0.74rem;">
                <span class="lang-en">Reports vs Clicks metric</span>
                <span class="lang-so">Soo sheegid vs riixid</span>
            </div>
        </div>
    </div>
</div>

<!-- SOC Charts Row -->
<div class="row g-3 mb-4">
    <!-- Chart 1: Activity Timeline -->
    <div class="col-lg-8">
        <div class="card card-soc h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-graph-up text-cyan"></i>
                    <span>Simulation Telemetry Timeline</span>
                </div>
                <span class="badge badge-soft-cyan">Live Event Stream</span>
            </div>
            <div class="card-body">
                <div style="height: 280px; position: relative;">
                    <canvas id="timelineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart 2: Knowledge Assessment Distribution -->
    <div class="col-lg-4">
        <div class="card card-soc h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-pie-chart-fill text-emerald"></i>
                    <span>Quiz Pass / Fail Ratio</span>
                </div>
                <span class="badge badge-soft-success">Exam Mastery</span>
            </div>
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <div style="width: 220px; height: 220px; position: relative;">
                    <canvas id="quizDistributionChart"></canvas>
                </div>
                <div class="d-flex justify-content-center gap-4 mt-3 small">
                    <div class="d-flex align-items-center gap-2">
                        <span style="width: 12px; height: 12px; background: #10b981; border-radius: 3px; display: inline-block;"></span>
                        <span class="text-secondary">Passed (<?= $quizStats['passed'] ?>)</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span style="width: 12px; height: 12px; background: #ef4444; border-radius: 3px; display: inline-block;"></span>
                        <span class="text-secondary">Failed (<?= $quizStats['failed'] ?>)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Event Stream Table -->
<div class="card card-soc mb-4">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-activity text-cyan"></i>
            <span>Recent Simulation Activity Log</span>
        </div>
        <a href="<?= ViewHelper::url('events') ?>" class="btn btn-outline-cyan btn-sm">
            <span>View All Events</span> <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-soc align-middle">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Event Type</th>
                    <th>Campaign Scenario</th>
                    <th>Pseudonymous Participant</th>
                    <th>Telemetry Summary</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentActivity)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            No simulation activity recorded yet. Run a campaign to view telemetry.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentActivity as $act): ?>
                        <tr>
                            <td class="text-nowrap small text-secondary">
                                <i class="bi bi-clock me-1"></i><?= ViewHelper::formatDate($act['created_at']) ?>
                            </td>
                            <td>
                                <?php
                                    $evMap = [
                                        'simulation_link_clicked'     => ['class' => 'badge-soft-danger', 'icon' => 'bi-cursor-fill', 'label' => 'Link Clicked'],
                                        'training_page_viewed'        => ['class' => 'badge-soft-warning', 'icon' => 'bi-book-half', 'label' => 'Training Viewed'],
                                        'lesson_started'              => ['class' => 'badge-soft-primary', 'icon' => 'bi-play-circle', 'label' => 'Lesson Started'],
                                        'lesson_completed'            => ['class' => 'badge-soft-success', 'icon' => 'bi-check2-circle', 'label' => 'Lesson Finished'],
                                        'quiz_started'                => ['class' => 'badge-soft-primary', 'icon' => 'bi-question-circle', 'label' => 'Quiz Started'],
                                        'quiz_completed'              => ['class' => 'badge-soft-cyan', 'icon' => 'bi-award-fill', 'label' => 'Quiz Passed'],
                                        'suspicious_message_reported' => ['class' => 'badge-soft-emerald', 'icon' => 'bi-shield-check', 'label' => 'Reported'],
                                    ];
                                    $b = $evMap[$act['event_type']] ?? ['class' => 'badge-soft-secondary', 'icon' => 'bi-dot', 'label' => $act['event_type']];
                                ?>
                                <span class="badge <?= $b['class'] ?> d-inline-flex align-items-center gap-1">
                                    <i class="bi <?= $b['icon'] ?>"></i><?= $b['label'] ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-light small"><?= ViewHelper::e($act['campaign_name']) ?></div>
                                <div class="text-muted" style="font-size: 0.72rem;"><?= ViewHelper::e($act['fictional_scenario']) ?></div>
                            </td>
                            <td>
                                <code class="text-cyan small"><?= ViewHelper::e($act['pseudonym'] ?: 'Anonymous-' . substr($act['participant_token'] ?? 'anon', -6)) ?></code>
                            </td>
                            <td class="small text-secondary">
                                <?php 
                                    $meta = json_decode($act['metadata_json'] ?? '{}', true);
                                    if (!empty($meta['reason'])) {
                                        echo 'Report reason: <strong class="text-light">' . ViewHelper::e($meta['reason']) . '</strong>';
                                    } elseif (!empty($meta['duration_sec'])) {
                                        echo 'Read duration: <strong class="text-light">' . ViewHelper::e($meta['duration_sec']) . 's</strong>';
                                    } elseif (!empty($meta['browser'])) {
                                        echo 'Agent: ' . ViewHelper::e($meta['browser']);
                                    } else {
                                        echo 'Standard event recorded';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js Initialization Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Timeline Chart Data
    <?php
        $dates = [];
        $clicks = [];
        $views = [];
        $reports = [];
        foreach ($dailyEvents as $row) {
            $dates[] = date('M d', strtotime($row['event_date']));
            $clicks[] = (int)$row['clicks'];
            $views[] = (int)$row['training_views'];
            $reports[] = (int)$row['reports'];
        }
        if (empty($dates)) {
            $dates = ['Today'];
            $clicks = [0];
            $views = [0];
            $reports = [0];
        }
    ?>

    const ctxTimeline = document.getElementById('timelineChart');
    if (ctxTimeline) {
        new Chart(ctxTimeline, {
            type: 'line',
            data: {
                labels: <?= json_encode($dates) ?>,
                datasets: [
                    {
                        label: 'Simulated Clicks',
                        data: <?= json_encode($clicks) ?>,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        tension: 0.35,
                        fill: true,
                        pointBackgroundColor: '#ef4444',
                    },
                    {
                        label: 'Training Views',
                        data: <?= json_encode($views) ?>,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.08)',
                        tension: 0.35,
                        fill: true,
                        pointBackgroundColor: '#f59e0b',
                    },
                    {
                        label: 'Reported Threats',
                        data: <?= json_encode($reports) ?>,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.35,
                        fill: true,
                        pointBackgroundColor: '#10b981',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { color: '#94a3b8', boxWidth: 12 }
                    }
                },
                scales: {
                    x: { grid: { color: 'rgba(30, 44, 77, 0.4)' }, ticks: { color: '#94a3b8' } },
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(30, 44, 77, 0.4)' }, 
                        ticks: { color: '#94a3b8', stepSize: 1 } 
                    }
                }
            }
        });
    }

    // Quiz Doughnut Chart
    const ctxQuiz = document.getElementById('quizDistributionChart');
    if (ctxQuiz) {
        new Chart(ctxQuiz, {
            type: 'doughnut',
            data: {
                labels: ['Passed', 'Failed'],
                datasets: [{
                    data: [<?= $quizStats['passed'] ?>, <?= $quizStats['failed'] ?>],
                    backgroundColor: ['#10b981', '#ef4444'],
                    borderColor: '#111e3b',
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
</script>
