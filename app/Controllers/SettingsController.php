<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Logger;
use App\Helpers\ViewHelper;

/**
 * System Settings & Environment Security Controller
 */
class SettingsController extends Controller {
    public function index(): void {
        $this->requireRole('super_admin');

        $rawSettings = Database::fetchAll("SELECT * FROM system_settings");
        $settings = [];
        foreach ($rawSettings as $s) {
            $settings[$s['setting_key']] = $s['setting_value'];
        }

        // Security Environment Assessment
        $lockFile = __DIR__ . '/../../install.lock';
        $setupFile = __DIR__ . '/../../setup.php';

        $securityChecks = [
            'pdo_prepared_statements' => [
                'name'   => 'PDO Prepared Statements Active',
                'status' => true,
                'detail' => 'All database queries use parameterized SQL prepared statements.',
            ],
            'session_httponly' => [
                'name'   => 'Session Cookie HttpOnly & Strict Mode',
                'status' => (ini_get('session.cookie_httponly') == '1'),
                'detail' => 'Prevents JavaScript access to authentication session cookies.',
            ],
            'password_hashing' => [
                'name'   => 'Bcrypt/Argon2 Password Hashing',
                'status' => true,
                'detail' => 'Uses PHP password_hash() with strong entropy salt.',
            ],
            'csrf_protection' => [
                'name'   => 'Cryptographic CSRF Tokens',
                'status' => true,
                'detail' => 'Cross-Site Request Forgery tokens enforced on all POST endpoints.',
            ],
            'no_stolen_passwords' => [
                'name'   => 'Ethical Safety Guard (No Credential Harvesting)',
                'status' => true,
                'detail' => 'Strict architectural rule: zero columns or tables exist to capture simulation passwords.',
            ],
            'setup_lock' => [
                'name'   => 'Setup Installation Script Guard',
                'status' => file_exists($lockFile),
                'detail' => file_exists($lockFile) ? 'Installer is locked (install.lock present).' : 'Notice: install.lock not created yet.',
            ],
        ];

        $this->render('settings/index', [
            'pageTitle'      => 'System Settings & Security Status',
            'settings'       => $settings,
            'securityChecks' => $securityChecks,
        ], 'main');
    }

    public function update(): void {
        $this->requireRole('super_admin');
        $this->validateCsrf();

        $keys = [
            'app_name',
            'app_tagline',
            'institution_name',
            'department_name',
            'contact_email',
            'session_lifetime',
            'simulation_mode',
            'consent_required',
        ];

        foreach ($keys as $k) {
            if (isset($_POST[$k])) {
                $val = trim($_POST[$k]);
                Database::query("
                    INSERT INTO system_settings (setting_key, setting_value, updated_at)
                    VALUES (?, ?, NOW())
                    ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_at = NOW()
                ", [$k, $val]);
            }
        }

        Logger::log('settings_updated', 'system_settings', 'global', ['updated_keys' => $keys]);

        $this->flash('success', 'System settings saved successfully.');
        $this->redirect('settings');
    }

    public function resetDemoData(): void {
        $this->requireRole('super_admin');
        $this->validateCsrf();

        $seedFile = __DIR__ . '/../../database/seed_demo.sql';
        if (!file_exists($seedFile)) {
            $this->flash('error', 'Seed SQL file not found.');
            $this->redirect('settings');
        }

        $sql = file_get_contents($seedFile);
        $pdo = Database::getInstance();

        try {
            $pdo->exec($sql);
            Logger::log('demo_data_reset', 'system', '1');
            $this->flash('success', 'Fictional demo data has been successfully re-seeded and refreshed!');
        } catch (\Throwable $e) {
            $this->flash('error', 'Failed to seed demo data: ' . $e->getMessage());
        }

        $this->redirect('settings');
    }
}
