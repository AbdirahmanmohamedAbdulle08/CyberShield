<?php
/**
 * CyberShield — Cybersecurity Awareness & Phishing Simulation Platform
 * Main Front Controller & Application Router
 * Author: Faculty of CS & IT, Hormuud University
 */

declare(strict_types=1);

// Error Reporting (Display errors in local development, log in production)
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

// Set default timezone
date_default_timezone_set('Africa/Mogadishu');

// Simple PSR-4 Autoloader
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Load helper functions
require_once __DIR__ . '/app/Helpers/LangHelper.php';

use App\Core\Router;
use App\Core\Auth;
use App\Helpers\ViewHelper;

$router = new Router();

// ==========================================
// 1. Root & Authentication Routes
// ==========================================
$router->get('/', 'HomeController@index');


$router->get('login', 'AuthController@showLogin');
$router->post('login', 'AuthController@login');
$router->get('logout', 'AuthController@logout');

// ==========================================
// 2. Dashboards
// ==========================================
$router->get('dashboard', 'DashboardController@index');
$router->get('student/dashboard', 'DashboardController@student');
$router->get('student/progress', 'DashboardController@student');
$router->get('student/activities', 'DashboardController@student');

// ==========================================
// 3. Campaigns
// ==========================================
$router->get('campaigns', 'CampaignController@index');
$router->get('campaigns/create', 'CampaignController@create');
$router->post('campaigns/store', 'CampaignController@store');
$router->get('campaigns/{id}', 'CampaignController@view');
$router->post('campaigns/authorize/{id}', 'CampaignController@authorize');
$router->post('campaigns/start/{id}', 'CampaignController@start');
$router->post('campaigns/complete/{id}', 'CampaignController@complete');

// ==========================================
// 4. Safe Simulation Endpoints
// ==========================================
$router->get('simulation/{token}', 'SimulationController@show');
$router->get('simulation/{token}/training', 'SimulationController@training');
$router->post('simulation/{token}/complete', 'SimulationController@completeLesson');

// ==========================================
// 5. Event Monitor
// ==========================================
$router->get('events', 'EventController@index');
$router->get('events/live', 'EventController@live');

// ==========================================
// 6. Participants
// ==========================================
$router->get('participants', 'ParticipantController@index');
$router->post('participants/store', 'ParticipantController@store');
$router->post('participants/toggle-status/{id}', 'ParticipantController@toggleStatus');
$router->post('participants/import', 'ParticipantController@importCsv');
$router->get('participants/export', 'ParticipantController@exportCsv');

// ==========================================
// 7. Training Groups
// ==========================================
$router->get('groups', 'GroupController@index');
$router->post('groups/store', 'GroupController@store');

// ==========================================
// 8. Awareness Academy
// ==========================================
$router->get('academy', 'AcademyController@index');
$router->get('academy/{slug}', 'AcademyController@view');
$router->post('academy/complete/{id}', 'AcademyController@markComplete');

// ==========================================
// 9. Quizzes
// ==========================================
$router->get('quizzes', 'QuizController@index');
$router->get('student/quizzes', 'QuizController@index');
$router->get('quizzes/{id}', 'QuizController@take');
$router->post('quizzes/{id}/submit', 'QuizController@submit');

// ==========================================
// 10. Threat Reporting (Report a Suspicious Message)
// ==========================================
$router->get('report-message', 'ReportMessageController@index');
$router->post('report-message/store', 'ReportMessageController@store');
$router->get('reports/suspicious', 'ReportMessageController@queue');
$router->post('reports/suspicious/update/{id}', 'ReportMessageController@updateStatus');

// ==========================================
// 11. Reports & Analytics
// ==========================================
$router->get('reports', 'AnalyticsController@index');
$router->get('reports/export', 'AnalyticsController@exportCampaignMetrics');

// ==========================================
// 12. Audit Logs
// ==========================================
$router->get('audit', 'AuditController@index');

// ==========================================
// 13. System Settings & Node Hardening
// ==========================================
$router->get('settings', 'SettingsController@index');
$router->post('settings/update', 'SettingsController@update');
$router->post('settings/reset-demo', 'SettingsController@resetDemoData');

// ==========================================
// 14. User Management (Super Admin)
// ==========================================
$router->get('users', 'UserController@index');
$router->post('users/store', 'UserController@store');
$router->post('users/toggle-status/{id}', 'UserController@toggleStatus');

// Dispatch incoming request
$router->dispatch();
