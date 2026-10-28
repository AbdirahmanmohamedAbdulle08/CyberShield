<?php
use App\Helpers\ViewHelper;
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-light mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-bar-chart-line-fill text-cyan"></i>
            <span>Educational Analytics & Defense Metrics</span>
        </h1>
        <p class="text-secondary small mb-0">Transparent calculations based strictly on live database telemetry records</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?= ViewHelper::url('reports/export') ?>" class="btn btn-cyan btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-download"></i>
            <span>Export Metrics (CSV)</span>
        </a>
    </div>
</div>

<!-- Methodology Disclosure Banner -->
<div class="card card-soc border-navy mb-4">
    <div class="card-body p-3">
        <div class="d-flex align-items-center gap-2 text-secondary small">
            <i class="bi bi-calculator text-cyan fs-5"></i>
            <div>
                <strong class="text-light">Methodology & Denominator Rules:</strong>
                All percentages use verified unique participants per campaign cohort as the denominator (e.g. <code>Click Rate = unique_clickers / eligible_students</code>; <code>Report Rate = unique_reporters / eligible_students</code>). Messages are never assumed delivered without recorded interaction.
            </div>
        </div>
    </div>
</div>

<!-- Campaign Performance Table -->
<div class="card card-soc mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-table text-cyan me-2"></i>Campaign Performance Breakdown</span>
        <span class="badge badge-soft-cyan">Mathematical Telemetry</span>
    </div>
    <div class="table-responsive">
        <table class="table table-soc align-middle">
            <thead>
                <tr>
                    <th>Campaign ID</th>
                    <th>Name & Scenario</th>
                    <th>Target Cohort</th>
                    <th>Eligible</th>
                    <th>Unique Clicks</th>
                    <th>Click Rate</th>
                    <th>Training Views</th>
                    <th>Reports</th>
                    <th>Reporting Rate</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($campaignReports as $cr): ?>
                    <tr>
                        <td><span class="badge bg-surface border border-navy text-secondary">#<?= $cr['id'] ?></span></td>
                        <td>
                            <div class="fw-bold text-light small"><?= ViewHelper::e($cr['campaign_name']) ?></div>
                            <div class="text-muted" style="font-size: 0.72rem;"><?= ViewHelper::e($cr['fictional_scenario']) ?></div>
                        </td>
                        <td><span class="badge bg-surface border border-navy text-light small"><?= ViewHelper::e($cr['group_name'] ?: 'All Cohorts') ?></span></td>
                        <td><strong class="text-light"><?= $cr['eligible_participants'] ?></strong></td>
                        <td><span class="text-danger fw-bold"><?= $cr['unique_clicks'] ?></span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress bg-surface border border-navy flex-grow-1" style="height: 6px; width: 60px;">
                                    <div class="progress-bar bg-danger" style="width: <?= min(100, $cr['click_rate']) ?>%;"></div>
                                </div>
                                <span class="small text-danger fw-bold"><?= $cr['click_rate'] ?>%</span>
                            </div>
                        </td>
                        <td><span class="text-warning fw-bold"><?= $cr['unique_training_views'] ?></span></td>
                        <td><span class="text-success fw-bold"><?= $cr['unique_reports'] ?></span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress bg-surface border border-navy flex-grow-1" style="height: 6px; width: 60px;">
                                    <div class="progress-bar bg-success" style="width: <?= min(100, $cr['report_rate']) ?>%;"></div>
                                </div>
                                <span class="small text-success fw-bold"><?= $cr['report_rate'] ?>%</span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Cohort Comparison Row -->
<div class="row g-4 mb-4">
    <!-- Cohort Comparison Table -->
    <div class="col-lg-6">
        <div class="card card-soc h-100">
            <div class="card-header fw-semibold">
                <i class="bi bi-diagram-3 text-cyan me-2"></i>Cohort Performance Comparison
            </div>
            <div class="table-responsive">
                <table class="table table-soc align-middle">
                    <thead>
                        <tr>
                            <th>Cohort Group</th>
                            <th>Enrolled</th>
                            <th>Total Clicks</th>
                            <th>Total Reports</th>
                            <th>Avg Quiz</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($groupComparisons as $gc): ?>
                            <tr>
                                <td class="fw-semibold text-light small"><?= ViewHelper::e($gc['group_name']) ?></td>
                                <td><?= $gc['enrolled_students'] ?></td>
                                <td><span class="text-danger fw-bold"><?= $gc['group_clicks'] ?></span></td>
                                <td><span class="text-success fw-bold"><?= $gc['group_reports'] ?></span></td>
                                <td>
                                    <span class="badge badge-soft-cyan"><?= $gc['group_avg_score'] ?>%</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart: Group Comparison Chart -->
    <div class="col-lg-6">
        <div class="card card-soc h-100">
            <div class="card-header fw-semibold">
                <i class="bi bi-bar-chart-steps text-cyan me-2"></i>Cohort Comparison Graph
            </div>
            <div class="card-body">
                <div style="height: 240px; position: relative;">
                    <canvas id="groupComparisonChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('groupComparisonChart');
    if (ctx) {
        <?php
            $groupLabels = [];
            $groupClicks = [];
            $groupReports = [];
            foreach ($groupComparisons as $gc) {
                $groupLabels[] = $gc['group_name'];
                $groupClicks[] = (int)$gc['group_clicks'];
                $groupReports[] = (int)$gc['group_reports'];
            }
        ?>

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($groupLabels) ?>,
                datasets: [
                    {
                        label: 'Simulated Clicks',
                        data: <?= json_encode($groupClicks) ?>,
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: '#ef4444',
                        borderWidth: 1,
                    },
                    {
                        label: 'Threat Reports',
                        data: <?= json_encode($groupReports) ?>,
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: '#10b981',
                        borderWidth: 1,
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
                    x: { ticks: { color: '#94a3b8' }, grid: { display: false } },
                    y: { beginAtZero: true, ticks: { color: '#94a3b8', stepSize: 1 }, grid: { color: 'rgba(30, 44, 77, 0.4)' } }
                }
            }
        });
    }
});
</script>
