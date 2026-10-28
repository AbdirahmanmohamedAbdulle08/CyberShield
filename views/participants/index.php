<?php
use App\Core\CSRF;
use App\Helpers\ViewHelper;
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-light mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-people text-cyan"></i>
            <span>Authorized Training Participants</span>
        </h1>
        <p class="text-secondary small mb-0">Hormuud University students participating in authorized security awareness training</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?= ViewHelper::url('participants/export') ?>" class="btn btn-surface btn-sm">
            <i class="bi bi-download me-1"></i> Export CSV
        </a>
        <button type="button" class="btn btn-surface btn-sm" data-bs-toggle="modal" data-bs-target="#importCsvModal">
            <i class="bi bi-upload me-1"></i> Import CSV
        </button>
        <button type="button" class="btn btn-cyan btn-sm" data-bs-toggle="modal" data-bs-target="#addParticipantModal">
            <i class="bi bi-person-plus-fill me-1"></i> Enroll Student
        </button>
    </div>
</div>

<!-- Filters Bar -->
<div class="card card-soc mb-4">
    <div class="card-body p-3">
        <form method="GET" action="<?= ViewHelper::url('participants') ?>" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-surface border-navy text-secondary"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control form-control-soc" name="search" value="<?= ViewHelper::e($search) ?>" placeholder="Search name, pseudonym, student ID, or token...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select form-select-soc" name="group_id">
                    <option value="">All Cohorts / Groups</option>
                    <?php foreach ($groups as $g): ?>
                        <option value="<?= $g['id'] ?>" <?= ($groupFilter == $g['id']) ? 'selected' : '' ?>>
                            <?= ViewHelper::e($g['group_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select form-select-soc" name="consent">
                    <option value="">All Consent</option>
                    <option value="consented" <?= ($consentFilter === 'consented') ? 'selected' : '' ?>>Consented</option>
                    <option value="pending" <?= ($consentFilter === 'pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="opted_out" <?= ($consentFilter === 'opted_out') ? 'selected' : '' ?>>Opted Out</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-surface border-navy w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <?php if (!empty($groupFilter) || !empty($consentFilter) || !empty($search)): ?>
                    <a href="<?= ViewHelper::url('participants') ?>" class="btn btn-outline-danger">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Participants Table -->
<div class="card card-soc mb-4">
    <div class="table-responsive">
        <table class="table table-soc align-middle">
            <thead>
                <tr>
                    <th>Token</th>
                    <th>Pseudonym</th>
                    <th>Display Name</th>
                    <th>Student ID</th>
                    <th>Cohort / Group</th>
                    <th>Consent</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($participants)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-2 d-block mb-2"></i>
                            No participants match the search criteria.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($participants as $p): ?>
                        <tr>
                            <td><code class="text-cyan small"><?= ViewHelper::e($p['participant_token']) ?></code></td>
                            <td><span class="badge bg-surface border border-navy text-light"><?= ViewHelper::e($p['pseudonym']) ?></span></td>
                            <td class="small fw-semibold text-light"><?= ViewHelper::e($p['display_name']) ?></td>
                            <td class="small text-secondary"><?= ViewHelper::e($p['institutional_id'] ?: '—') ?></td>
                            <td>
                                <span class="badge bg-surface border border-navy text-cyan small"><?= ViewHelper::e($p['group_name'] ?: 'Unassigned') ?></span>
                            </td>
                            <td>
                                <?php if ($p['consent_status'] === 'consented'): ?>
                                    <span class="badge badge-soft-success"><i class="bi bi-check-circle me-1"></i>Consented</span>
                                <?php else: ?>
                                    <span class="badge badge-soft-warning"><?= ucfirst($p['consent_status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= ViewHelper::badgeStatus($p['status']) ?>
                            </td>
                            <td class="text-end">
                                <form method="POST" action="<?= ViewHelper::url('participants/toggle-status/' . $p['id']) ?>" class="d-inline" onsubmit="return confirm('Change status for this participant?');">
                                    <?= CSRF::field() ?>
                                    <button type="submit" class="btn btn-surface btn-sm py-1" title="Toggle Active / Inactive">
                                        <?php if ($p['status'] === 'active'): ?>
                                            <i class="bi bi-pause-circle text-warning me-1"></i> Disable
                                        <?php else: ?>
                                            <i class="bi bi-play-circle text-success me-1"></i> Enable
                                        <?php endif; ?>
                                    </button>
                                </form>
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
            <span class="text-muted small">Showing page <?= $page ?> of <?= $totalPages ?> (<?= $total ?> participants)</span>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                            <a class="page-link bg-surface border-navy text-light" href="<?= ViewHelper::url('participants?page=' . $i . '&group_id=' . urlencode($groupFilter) . '&consent=' . urlencode($consentFilter) . '&search=' . urlencode($search)) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<!-- Modal: Enroll Participant -->
<div class="modal fade" id="addParticipantModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-soc">
            <form method="POST" action="<?= ViewHelper::url('participants/store') ?>">
                <?= CSRF::field() ?>
                <div class="modal-header">
                    <h5 class="modal-title text-light fw-bold">Enroll Training Participant</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Student Full Name</label>
                        <input type="text" class="form-control form-control-soc" name="display_name" placeholder="e.g. Marian Ali" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">University Email Address</label>
                        <input type="email" class="form-control form-control-soc" name="email" placeholder="e.g. marian.ali@student.hu.edu.so" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Institutional Student ID (Optional)</label>
                        <input type="text" class="form-control form-control-soc" name="institutional_id" placeholder="e.g. HU-CS-2023-0512">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Assign to Training Cohort</label>
                        <select class="form-select form-select-soc" name="training_group_id">
                            <option value="">-- No Cohort (Unassigned) --</option>
                            <?php foreach ($groups as $g): ?>
                                <option value="<?= $g['id'] ?>"><?= ViewHelper::e($g['group_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="p-2 rounded bg-surface border border-navy small text-muted">
                        <i class="bi bi-shield-check text-cyan me-1"></i>
                        A random pseudonymous identifier and private token will be generated automatically. Passwords are never collected or stored.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-surface" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan">Enroll Participant</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Import CSV -->
<div class="modal fade" id="importCsvModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-soc">
            <form method="POST" action="<?= ViewHelper::url('participants/import') ?>" enctype="multipart/form-data">
                <?= CSRF::field() ?>
                <div class="modal-header">
                    <h5 class="modal-title text-light fw-bold">Import Participants from CSV</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small text-secondary mb-3">
                        Upload a CSV file containing columns: <code class="text-cyan">name, email, student_id</code>.
                    </p>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Target Training Cohort</label>
                        <select class="form-select form-select-soc" name="target_group_id">
                            <option value="">-- Unassigned Cohort --</option>
                            <?php foreach ($groups as $g): ?>
                                <option value="<?= $g['id'] ?>"><?= ViewHelper::e($g['group_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">CSV File</label>
                        <input type="file" class="form-control form-control-soc" name="csv_file" accept=".csv" required>
                    </div>
                    <div class="p-2 rounded bg-surface border border-navy small text-muted">
                        <i class="bi bi-info-circle text-cyan me-1"></i>
                        Uploaded entries are sanitized against spreadsheet formula injection (CWE-1236).
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-surface" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
