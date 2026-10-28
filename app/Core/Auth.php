<?php
namespace App\Core;

/**
 * CyberShield Authentication and Session Manager
 * Enforces Role-Based Access Control (RBAC), Rate Limiting, and Session Security
 */
class Auth {
    private const SESSION_USER_KEY = 'cybershield_auth_user';
    private const MAX_ATTEMPTS = 5;
    private const LOCKOUT_MINUTES = 15;

    public static function initSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || ($_SERVER['SERVER_PORT'] ?? '') == 443;

            ini_set('session.use_strict_mode', '1');
            ini_set('session.cookie_httponly', '1');
            ini_set('session.use_only_cookies', '1');

            session_name('CYBERSHIELD_SESSID');
            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'domain'   => '',
                'secure'   => $isHttps,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);

            session_start();
        }
    }

    public static function check(): bool {
        self::initSession();
        return !empty($_SESSION[self::SESSION_USER_KEY]);
    }

    public static function user(): ?array {
        self::initSession();
        return $_SESSION[self::SESSION_USER_KEY] ?? null;
    }

    public static function id(): ?int {
        $u = self::user();
        return $u ? (int)$u['id'] : null;
    }

    public static function role(): ?string {
        $u = self::user();
        return $u['role'] ?? null;
    }

    public static function hasRole($roles): bool {
        if (!self::check()) {
            return false;
        }
        $userRole = self::role();
        if (is_array($roles)) {
            return in_array($userRole, $roles, true);
        }
        return $userRole === $roles;
    }

    public static function isSuperAdmin(): bool {
        return self::role() === 'super_admin';
    }

    public static function isCampaignManager(): bool {
        return in_array(self::role(), ['super_admin', 'campaign_manager'], true);
    }

    public static function isInstructor(): bool {
        return in_array(self::role(), ['super_admin', 'instructor'], true);
    }

    public static function isStudent(): bool {
        return self::role() === 'student';
    }

    /**
     * Check if client IP is currently rate-limited
     */
    public static function isRateLimited(string $ip): bool {
        try {
            $since = date('Y-m-d H:i:s', strtotime("-" . self::LOCKOUT_MINUTES . " minutes"));
            $count = Database::fetchColumn(
                "SELECT COUNT(*) FROM login_attempts WHERE ip_address = ? AND attempted_at > ?",
                [$ip, $since]
            );
            return (int)$count >= self::MAX_ATTEMPTS;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function recordFailedAttempt(string $ip, string $username): void {
        try {
            Database::query(
                "INSERT INTO login_attempts (ip_address, username, attempted_at) VALUES (?, ?, NOW())",
                [$ip, substr($username, 0, 50)]
            );
        } catch (\Throwable $e) {}
    }

    public static function clearFailedAttempts(string $ip): void {
        try {
            Database::query("DELETE FROM login_attempts WHERE ip_address = ?", [$ip]);
        } catch (\Throwable $e) {}
    }

    /**
     * Attempt login with rate-limiting, status checks, and session regeneration
     */
    public static function attempt(string $usernameOrEmail, string $password): array {
        self::initSession();
        $ip = Logger::getClientIp();

        if (self::isRateLimited($ip)) {
            return [
                'success' => false,
                'error'   => 'Too many failed login attempts. Account temporarily locked for ' . self::LOCKOUT_MINUTES . ' minutes for security.',
            ];
        }

        $user = Database::fetchOne(
            "SELECT * FROM users WHERE (username = ? OR email = ?) LIMIT 1",
            [$usernameOrEmail, $usernameOrEmail]
        );

        if (!$user || !password_verify($password, $user['password_hash'])) {
            self::recordFailedAttempt($ip, $usernameOrEmail);
            Logger::log('login_failed', 'user', null, ['attempted_identifier' => $usernameOrEmail]);
            return [
                'success' => false,
                'error'   => 'Invalid credentials. Please verify your username and password.',
            ];
        }

        if ($user['status'] !== 'active') {
            return [
                'success' => false,
                'error'   => 'This account is currently ' . htmlspecialchars($user['status']) . '. Please contact the SOC administrator.',
            ];
        }

        // Reset rate limiter on successful authentication
        self::clearFailedAttempts($ip);

        // Regenerate session ID to prevent Session Fixation attacks
        session_regenerate_id(true);

        // Update last login timestamp
        Database::query("UPDATE users SET last_login_at = NOW() WHERE id = ?", [$user['id']]);

        // Store non-sensitive user data in session
        $_SESSION[self::SESSION_USER_KEY] = [
            'id'            => (int)$user['id'],
            'username'      => $user['username'],
            'email'         => $user['email'],
            'full_name'     => $user['full_name'],
            'role'          => $user['role'],
            'last_login_at' => $user['last_login_at'] ?? date('Y-m-d H:i:s'),
        ];

        // Regenerate CSRF token
        CSRF::regenerate();

        Logger::log('login_success', 'user', (string)$user['id'], [
            'role' => $user['role'],
        ]);

        return [
            'success'  => true,
            'user'     => $_SESSION[self::SESSION_USER_KEY],
            'redirect' => self::getRedirectForRole($user['role']),
        ];
    }

    public static function getRedirectForRole(string $role): string {
        switch ($role) {
            case 'student':
                return 'student/dashboard';
            case 'instructor':
                return 'dashboard';
            case 'campaign_manager':
            case 'super_admin':
            default:
                return 'dashboard';
        }
    }

    public static function logout(): void {
        self::initSession();
        $user = self::user();
        if ($user) {
            Logger::log('logout', 'user', (string)$user['id']);
        }
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
}
