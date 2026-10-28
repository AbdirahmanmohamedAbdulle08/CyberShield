<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Logger;
use App\Helpers\ViewHelper;

/**
 * User Management Controller (Super Admin only)
 */
class UserController extends Controller {
    public function index(): void {
        $this->requireRole('super_admin');

        $users = Database::fetchAll("SELECT * FROM users ORDER BY id ASC");

        $this->render('users/index', [
            'pageTitle' => 'User Management & Access Control',
            'users'     => $users,
        ], 'main');
    }

    public function store(): void {
        $this->requireRole('super_admin');
        $this->validateCsrf();

        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $fullname = trim($_POST['full_name'] ?? '');
        $role     = trim($_POST['role'] ?? 'student');
        $password = (string)($_POST['password'] ?? '');

        if (empty($username) || empty($email) || empty($password)) {
            $this->flash('error', 'Username, email, and password are required.');
            $this->redirect('users');
        }

        if (strlen($password) < 8) {
            $this->flash('error', 'Password must be at least 8 characters.');
            $this->redirect('users');
        }

        $validRoles = ['super_admin', 'campaign_manager', 'instructor', 'student'];
        if (!in_array($role, $validRoles, true)) {
            $role = 'student';
        }

        $existing = Database::fetchOne("SELECT id FROM users WHERE username = ? OR email = ?", [$username, $email]);
        if ($existing) {
            $this->flash('error', 'A user with this username or email already exists.');
            $this->redirect('users');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        Database::query("
            INSERT INTO users (username, email, password_hash, full_name, role, status, created_at)
            VALUES (?, ?, ?, ?, ?, 'active', NOW())
        ", [$username, $email, $hash, $fullname, $role]);

        $newUserId = Database::lastInsertId();

        Logger::log('user_created', 'user', (string)$newUserId, [
            'username' => $username,
            'role'     => $role,
        ]);

        $this->flash('success', "User account for {$username} created successfully with role: {$role}.");
        $this->redirect('users');
    }

    public function toggleStatus(string $id): void {
        $currentUser = $this->requireRole('super_admin');
        $this->validateCsrf();

        $userId = (int)$id;
        if ($userId === (int)$currentUser['id']) {
            $this->flash('error', 'You cannot disable your own Super Admin account.');
            $this->redirect('users');
        }

        $target = Database::fetchOne("SELECT status, username FROM users WHERE id = ?", [$userId]);
        if ($target) {
            $newStatus = ($target['status'] === 'active') ? 'inactive' : 'active';
            Database::query("UPDATE users SET status = ? WHERE id = ?", [$newStatus, $userId]);
            Logger::log('user_status_changed', 'user', (string)$userId, ['new_status' => $newStatus]);
            $this->flash('success', "Account @{$target['username']} status set to {$newStatus}.");
        }

        $this->redirect('users');
    }
}
