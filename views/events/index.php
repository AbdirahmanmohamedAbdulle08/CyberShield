<?php
use App\Helpers\ViewHelper;
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-light mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-activity text-cyan"></i>
            <span>Real-Time Event Monitor</span>
        </h1>
        <p class="text-secondary small mb-0">Telemetry stream of participant interactions, simulated link clicks, and threat reports</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-surface btn-sm d-flex align-items-center gap-2" id="livePollingToggle">
            <span class="pulse-indicator" id="pollDot"></span>
            <span id="pollText">Live Stream: ON</span>
        </button>
        <a href="<?= ViewHelper::url('events') ?>" class="btn btn-outline-cyan btn-sm">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
        </a>
    </div>
</div>

<!-- Filters Bar -->
<div class="card card-soc mb-4">
    <div class="card-body p-3">
        <form method="GET" action="<?= ViewHelper::url('events') ?>" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-surface border-navy text-secondary"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control form-control-soc" name="search" value="<?= ViewHelper::e($search) ?>" placeholder="Search token, pseudonym, or campaign...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select form-select-soc" name="campaign_id">
                    <option value="">All Campaigns</option>
                    <?php foreach ($campaigns as $camp): ?>
                        <option value="<?= $camp['id'] ?>" <?= ($campaignFilter == $camp['id']) ? 'selected' : '' ?>>
                            #<?= $camp['id'] ?> <?= ViewHelper::e($camp['campaign_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select form-select-soc" name="event_type">
                    <option value="">All Event Types</option>
                    <option value="simulation_link_clicked" <?= ($typeFilter === 'simulation_link_clicked') ? 'selected' : '' ?>>Simulated Link Clicked</option>
                    <option value="training_page_viewed" <?= ($typeFilter === 'training_page_viewed') ? 'selected' : '' ?>>Training Page Viewed</option>
                    <option value="lesson_started" <?= ($typeFilter === 'lesson_started') ? 'selected' : '' ?>>Lesson Started</option>
                    <option value="lesson_completed" <?= ($typeFilter === 'lesson_completed') ? 'selected' : '' ?>>Lesson Completed</option>
                    <option value="quiz_started" <?= ($typeFilter === 'quiz_started') ? 'selected' : '' ?>>Quiz Started</option>
                    <option value="quiz_completed" <?= ($typeFilter === 'quiz_completed') ? 'selected' : '' ?>>Quiz Completed</option>
                    <option value="suspicious_message_reported" <?= ($typeFilter === 'suspicious_message_reported') ? 'selected' : '' ?>>Suspicious Message Reported</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-surface border-navy w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <?php if (!empty($campaignFilter) || !empty($typeFilter) || !empty($search)): ?>
                    <a href="<?= ViewHelper::url('events') ?>" class="btn btn-outline-danger">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Event Monitor Table -->
<div class="card card-soc mb-4">
    <div class="table-responsive">
        <table class="table table-soc align-middle" id="eventsTable">
            <thead>
                <tr>
                    <th>Event ID</th>
                    <th>Timestamp</th>
                    <th>Event Classification</th>
                    <th>Campaign Scenario</th>
                    <th>Pseudonymous Participant</th>
                    <th>Anonymized Subnet</th>
                    <th class="text-end">Telemetry Data</th>
                </tr>
            </thead>
            <tbody id="eventsTbody">
                <?php if (empty($events)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            No events found matching the criteria.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($events as $ev): ?>
                        <tr data-id="<?= $ev['id'] ?>">
                            <td><span class="badge bg-surface border border-navy text-secondary">#<?= $ev['id'] ?></span></td>
                            <td class="small text-secondary text-nowrap"><?= ViewHelper::formatDate($ev['created_at']) ?></td>
                            <td>
                                <?php
                                    $map = [
                                        'simulation_link_clicked'     => ['class' => 'badge-soft-danger', 'icon' => 'bi-cursor-fill', 'label' => 'Link Clicked'],
                                        'training_page_viewed'        => ['class' => 'badge-soft-warning', 'icon' => 'bi-book-half', 'label' => 'Training Viewed'],
                                        'lesson_started'              => ['class' => 'badge-soft-primary', 'icon' => 'bi-play-circle', 'label' => 'Lesson Started'],
                                        'lesson_completed'            => ['class' => 'badge-soft-success', 'icon' => 'bi-check2-circle', 'label' => 'Lesson Completed'],
                                        'quiz_started'                => ['class' => 'badge-soft-primary', 'icon' => 'bi-question-circle', 'label' => 'Quiz Started'],
                                        'quiz_completed'              => ['class' => 'badge-soft-cyan', 'icon' => 'bi-award-fill', 'label' => 'Quiz Completed'],
                                        'suspicious_message_reported' => ['class' => 'badge-soft-emerald', 'icon' => 'bi-shield-check', 'label' => 'Message Reported'],
                                    ];
                                    $badge = $map[$ev['event_type']] ?? ['class' => 'badge-soft-secondary', 'icon' => 'bi-dot', 'label' => $ev['event_type']];
                                ?>
                                <span class="badge <?= $badge['class'] ?> d-inline-flex align-items-center gap-1">
                                    <i class="bi <?= $badge['icon'] ?>"></i><?= $badge['label'] ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-light small"><?= ViewHelper::e($ev['campaign_name']) ?></div>
                                <div class="text-muted" style="font-size: 0.72rem;"><?= ViewHelper::e($ev['fictional_scenario']) ?></div>
                            </td>
                            <td>
                                <code class="text-cyan small"><?= ViewHelper::e($ev['pseudonym'] ?: ($ev['participant_token'] ?? 'anon')) ?></code>
                            </td>
                            <td>
                                <span class="badge bg-surface border border-navy text-secondary small">
                                    <i class="bi bi-hdd-network me-1"></i><?= ViewHelper::e($ev['ip_anonymized'] ?: '127.0.0.0/24') ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-surface btn-sm py-1 view-meta-btn" data-meta="<?= htmlspecialchars($ev['metadata_json'] ?? '{}') ?>" data-id="<?= $ev['id'] ?>">
                                    <i class="bi bi-code-slash text-cyan me-1"></i> Details
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="card-footer bg-surface border-top border-navy p-3 d-flex justify-content-between align-items-center">
            <span class="text-muted small">Showing page <?= $page ?> of <?= $totalPages ?> (<?= $totalEvents ?> total events)</span>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                            <a class="page-link bg-surface border-navy text-light" href="<?= ViewHelper::url('events?page=' . $i . '&campaign_id=' . urlencode($campaignFilter) . '&event_type=' . urlencode($typeFilter) . '&search=' . urlencode($search)) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<!-- Modal: Event Details / Metadata -->
<div class="modal fade" id="metaModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-soc">
            <div class="modal-header">
                <h5 class="modal-title text-light fw-bold" id="metaModalTitle">Event Technical Metadata</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="small text-secondary mb-2">Safe non-sensitive telemetry recorded for educational troubleshooting:</p>
                <pre class="p-3 rounded bg-navy border border-navy text-cyan small mb-0 font-monospace" id="metaPre" style="max-height: 300px; overflow-y: auto;"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-surface" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Metadata modal handler
    const metaModal = new bootstrap.Modal(document.getElementById('metaModal'));
    const metaPre = document.getElementById('metaPre');
    const metaTitle = document.getElementById('metaModalTitle');

    document.querySelectorAll('.view-meta-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const raw = this.getAttribute('data-meta');
            const id = this.getAttribute('data-id');
            metaTitle.textContent = 'Event #' + id + ' Technical Metadata';
            try {
                const parsed = JSON.parse(raw);
                metaPre.textContent = JSON.stringify(parsed, null, 2);
            } catch (e) {
                metaPre.textContent = raw;
            }
            metaModal.show();
        });
    });

    // Optional safe live polling
    let pollingActive = true;
    const pollBtn = document.getElementById('livePollingToggle');
    const pollDot = document.getElementById('pollDot');
    const pollText = document.getElementById('pollText');

    pollBtn.addEventListener('click', function () {
        pollingActive = !pollingActive;
        if (pollingActive) {
            pollDot.style.display = 'inline-block';
            pollText.textContent = 'Live Stream: ON';
            pollBtn.classList.remove('btn-outline-secondary');
            pollBtn.classList.add('btn-surface');
        } else {
            pollDot.style.display = 'none';
            pollText.textContent = 'Live Stream: OFF';
            pollBtn.classList.remove('btn-surface');
            pollBtn.classList.add('btn-outline-secondary');
        }
    });

    // Poll every 12 seconds if active
    let latestId = <?= !empty($events[0]['id']) ? (int)$events[0]['id'] : 0 ?>;
    setInterval(() => {
        if (!pollingActive) return;
        fetch('<?= ViewHelper::url("events/live?since_id=") ?>' + latestId)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.count > 0) {
                    latestId = data.latest;
                    // Trigger a smooth table refresh notification or reload
                    console.log('Received ' + data.count + ' new events from CyberShield telemetry node.');
                }
            })
            .catch(err => console.error('Telemetry polling exception:', err));
    }, 12000);
});
</script>
