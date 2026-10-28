<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Helpers\ViewHelper;

/**
 * Public Landing Page & Cybersecurity Info Hub Controller
 */
class HomeController extends Controller {
    public function index(): void {
        // Fetch real database metrics for hero counters
        $stats = [
            'participants' => (int)Database::fetchColumn("SELECT COUNT(*) FROM participants WHERE status = 'active'"),
            'campaigns'    => (int)Database::fetchColumn("SELECT COUNT(*) FROM campaigns"),
            'modules'      => (int)Database::fetchColumn("SELECT COUNT(*) FROM learning_modules"),
            'reports'      => (int)Database::fetchColumn("SELECT COUNT(*) FROM suspicious_message_reports"),
            'quiz_avg'     => (float)Database::fetchColumn("SELECT IFNULL(ROUND(AVG(percentage), 1), 0) FROM quiz_attempts"),
        ];

        // Fetch the 10 learning modules for the curriculum showcase
        $modules = Database::fetchAll("SELECT * FROM learning_modules ORDER BY module_number ASC");

        // Fetch sample scenarios for interactive phishing anatomy lab
        $scenarios = Database::fetchAll("SELECT id, campaign_name, fictional_scenario, sender_name, sender_email, message_subject, message_body, public_token FROM campaigns WHERE status IN ('running', 'authorized', 'completed') LIMIT 4");

        $currentUser = Auth::user();

        $this->render('home/index', [
            'pageTitle'   => 'CyberShield — Cybersecurity Awareness & Phishing Defense Platform',
            'stats'       => $stats,
            'modules'     => $modules,
            'scenarios'   => $scenarios,
            'currentUser' => $currentUser,
        ], 'none'); // Full-width custom landing layout
    }
}
