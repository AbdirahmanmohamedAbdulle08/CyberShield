<?php
use App\Helpers\ViewHelper;
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-light mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-journal-text text-cyan"></i>
            <span>Administrative Audit Logs</span>
        </h1>
        <p class="text-secondary small mb-0">Immutable record of administrative, campaign, and security operations on this CyberShield instance</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge badge-soft-success px-3 py-2 small">
            <i class="bi bi-shield-check me-1"></i> Audit Trail Active (<?= $totalLogs ?> Events)
        </span>
    </div>
</div>

<!-- Filter Bar -->
<div class="card card-soc mb-4">
    <div class="card-body p-3">
        <form method="GET" action="<?= ViewHelper::url('audit') ?>" class="row g-2 align-items-center">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-surface border-navy text-secondary"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control form-control-soc" name="action" value="<?= ViewHelper::e($actionFilter) ?>" placeholder="Filter by action keyword (e.g. campaign_created, login_success, report_reviewed)...">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-surface border-navy w-100">Filter Logs</button>
                <?php if (!empty($actionFilter)): ?>
                    <a href="<?= ViewHelper::url('audit') ?>" class="btn btn-outline-danger">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Logs Table -->
<div class="card card-soc mb-4">
    <div class="table-responsive">
        <table class="table table-soc align-middle">
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>Timestamp</th>
                    <th>Operator</th>
                    <th>Action</th>
                    <th>Entity</th>
                    <th>Origin IP</th>
                    <th class="text-end">Payload</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            No audit logs matching this search.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><span class="badge bg-surface border border-navy text-secondary">#<?= $log['id'] ?></span></td>
                            <td class="small text-secondary text-nowrap"><?= ViewHelper::formatDate($log['created_at']) ?></td>
                            <td>
                                <span class="badge bg-surface border border-navy text-cyan small">
                                    <i class="bi bi-person me-1"></i><?= ViewHelper::e($log['username']) ?>
                                </span>
                            </td>
                            <td>
                                <code class="text-light small"><?= ViewHelper::e($log['action']) ?></code>
                            </td>
                            <td>
                                <span class="small text-secondary"><?= ViewHelper::e($log['entity_type']) ?> #<?= ViewHelper::e($log['entity_id'] ?? '—') ?></span>
                            </td>
                            <td>
                                <span class="small text-secondary"><?= ViewHelper::e($log['ip_address'] ?? '127.0.0.1') ?></span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-surface btn-sm py-1 view-audit-btn" data-json="<?= htmlspecialchars($log['details_json'] ?? '{}') ?>" data-id="<?= $log['id'] ?>">
                                    <i class="bi bi-eye text-cyan me-1"></i> View
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
            <span class="text-muted small">Showing page <?= $page ?> of <?= $totalPages ?></span>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                            <a class="page-link bg-surface border-navy text-light" href="<?= ViewHelper::url('audit?page=' . $i . '&action=' . urlencode($actionFilter)) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<!-- Modal: Audit Details -->
<div class="modal fade" id="auditModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-soc">
            <div class="modal-header">
                <h5 class="modal-title text-light fw-bold" id="auditModalTitle">Audit Log Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre class="p-3 rounded bg-navy border border-navy text-cyan small mb-0 font-monospace" id="auditPre" style="max-height: 300px; overflow-y: auto;"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-surface" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('auditModal'));
    const pre = document.getElementById('auditPre');
    const title = document.getElementById('auditModalTitle');

    document.querySelectorAll('.view-audit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const raw = this.getAttribute('data-json');
            const id = this.getAttribute('data-id');
            title.textContent = 'Audit Log #' + id + ' Event Data';
            try {
                pre.textContent = JSON.stringify(JSON.parse(raw), null, 2);
            } catch (e) {
                pre.textContent = raw;
            }
            modal.show();
        });
    });
});
</script>
