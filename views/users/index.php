<?php
use App\Core\CSRF;
use App\Helpers\ViewHelper;
?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="h3 fw-bold text-light mb-1 d-flex align-items-center gap-2">
            <i class="bi bi-person-gear text-cyan"></i>
            <span>User Management & RBAC</span>
        </h1>
        <p class="text-secondary small mb-0">Manage administrative staff, instructors, and student user accounts</p>
    </div>
    <button type="button" class="btn btn-cyan btn-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus-fill"></i>
        <span>Create User Account</span>
    </button>
</div>

<!-- Users Table -->
<div class="card card-soc">
    <div class="table-responsive">
        <table class="table table-soc align-middle">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Account Holder</th>
                    <th>Email Address</th>
                    <th>Assigned Role</th>
                    <th>Status</th>
                    <th>Last Sign-In</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><span class="badge bg-surface border border-navy text-secondary">#<?= $u['id'] ?></span></td>
                        <td>
                            <div class="fw-bold text-light small"><?= ViewHelper::e($u['full_name']) ?></div>
                            <code class="text-cyan" style="font-size: 0.76rem;">@<?= ViewHelper::e($u['username']) ?></code>
                        </td>
                        <td class="small text-secondary"><?= ViewHelper::e($u['email']) ?></td>
                        <td><?= ViewHelper::badgeRole($u['role']) ?></td>
                        <td><?= ViewHelper::badgeStatus($u['status']) ?></td>
                        <td class="small text-secondary"><?= ViewHelper::timeAgo($u['last_login_at']) ?></td>
                        <td class="text-end">
                            <form method="POST" action="<?= ViewHelper::url('users/toggle-status/' . $u['id']) ?>" class="d-inline" onsubmit="return confirm('Toggle account status for @<?= ViewHelper::e($u['username']) ?>?');">
                                <?= CSRF::field() ?>
                                <button type="submit" class="btn btn-surface btn-sm py-1">
                                    <?php if ($u['status'] === 'active'): ?>
                                        <i class="bi bi-pause-circle text-warning me-1"></i> Disable
                                    <?php else: ?>
                                        <i class="bi bi-play-circle text-success me-1"></i> Enable
                                    <?php endif; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Create User Account -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-soc">
            <form method="POST" action="<?= ViewHelper::url('users/store') ?>">
                <?= CSRF::field() ?>
                <div class="modal-header">
                    <h5 class="modal-title text-light fw-bold">Create User Account</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Full Name</label>
                        <input type="text" class="form-control form-control-soc" name="full_name" placeholder="e.g. Marian Ali" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-semibold">Username</label>
                            <input type="text" class="form-control form-control-soc" name="username" placeholder="e.g. mali" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small fw-semibold">Role</label>
                            <select class="form-select form-select-soc" name="role" required>
                                <option value="student">Student</option>
                                <option value="instructor">Instructor</option>
                                <option value="campaign_manager">Campaign Manager</option>
                                <option value="super_admin">Super Admin</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Institutional Email</label>
                        <input type="email" class="form-control form-control-soc" name="email" placeholder="e.g. mali@hu.edu.so" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Temporary Password (Min 8 chars)</label>
                        <input type="password" class="form-control form-control-soc" name="password" placeholder="••••••••••••" required>
                    </div>
                    <div class="p-2 rounded bg-surface border border-navy small text-muted">
                        <i class="bi bi-shield-lock text-cyan me-1"></i> Passwords are salted and hashed using Bcrypt before database storage.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-surface" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-cyan">Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
