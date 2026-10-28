<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Logger;
use App\Helpers\ViewHelper;

/**
 * Safe Educational Simulation Controller
 * Handles /simulation/{token} and /simulation/{token}/training
 * Strictly educational — zero credential harvesting
 */
class SimulationController extends Controller {
    public function show(string $token): void {
        $campaign = Database::fetchOne(
            "SELECT * FROM campaigns WHERE public_token = ? LIMIT 1",
            [$token]
        );

        if (!$campaign) {
            http_response_code(404);
            $this->render('simulation/not_found', [
                'token' => $token,
            ], 'simulation');
            return;
        }

        // Anonymize IP (CIDR /24) to protect participant privacy
        $rawIp = Logger::getClientIp();
        $ipParts = explode('.', $rawIp);
        $anonymizedIp = (count($ipParts) === 4) ? "{$ipParts[0]}.{$ipParts[1]}.{$ipParts[2]}.0/24" : '127.0.0.0/24';

        // Extract participant token if passed as query parameter (?p=tok_...)
        $participantToken = trim($_GET['p'] ?? '');
        if (empty($participantToken)) {
            // Assign a pseudonymous session participant identifier
            if (empty($_SESSION['sim_participant_token'])) {
                $_SESSION['sim_participant_token'] = 'sim_student_' . substr(bin2hex(random_bytes(6)), 0, 8);
            }
            $participantToken = $_SESSION['sim_participant_token'];
        }

        // Record telemetry: simulation_link_clicked
        try {
            $metadata = json_encode([
                'browser'         => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                'lure_scenario'   => $campaign['fictional_scenario'],
                'campaign_id'     => $campaign['id'],
                'interaction_time'=> date('Y-m-d H:i:s'),
            ]);

            Database::query("
                INSERT INTO simulation_events (campaign_id, participant_token, event_type, metadata_json, ip_anonymized, created_at)
                VALUES (?, ?, 'simulation_link_clicked', ?, ?, NOW())
            ", [$campaign['id'], $participantToken, $metadata, $anonymizedIp]);
        } catch (\Throwable $e) {
            error_log("Simulation Telemetry Error: " . $e->getMessage());
        }

        $this->render('simulation/landing', [
            'pageTitle'        => 'Notice: ' . $campaign['message_subject'],
            'campaign'         => $campaign,
            'participantToken' => $participantToken,
        ], 'simulation');
    }

    public function training(string $token): void {
        $campaign = Database::fetchOne(
            "SELECT * FROM campaigns WHERE public_token = ? LIMIT 1",
            [$token]
        );

        if (!$campaign) {
            http_response_code(404);
            $this->render('simulation/not_found', ['token' => $token], 'simulation');
            return;
        }

        $participantToken = trim($_GET['p'] ?? ($_SESSION['sim_participant_token'] ?? ''));

        // Anonymize IP
        $rawIp = Logger::getClientIp();
        $ipParts = explode('.', $rawIp);
        $anonymizedIp = (count($ipParts) === 4) ? "{$ipParts[0]}.{$ipParts[1]}.{$ipParts[2]}.0/24" : '127.0.0.0/24';

        // Record telemetry: training_page_viewed
        try {
            $metadata = json_encode([
                'training_module' => 'immediate_phishing_disclosure',
                'viewed_at'       => date('Y-m-d H:i:s'),
            ]);

            Database::query("
                INSERT INTO simulation_events (campaign_id, participant_token, event_type, metadata_json, ip_anonymized, created_at)
                VALUES (?, ?, 'training_page_viewed', ?, ?, NOW())
            ", [$campaign['id'], $participantToken, $metadata, $anonymizedIp]);
        } catch (\Throwable $e) {}

        // Related learning module (e.g. module 5 for fake reward or module 3 for URLs)
        $relatedModule = Database::fetchOne(
            "SELECT * FROM learning_modules WHERE id = 5 LIMIT 1"
        ) ?: Database::fetchOne("SELECT * FROM learning_modules ORDER BY id ASC LIMIT 1");

        $this->render('simulation/training', [
            'pageTitle'        => 'Security Awareness Disclosure',
            'campaign'         => $campaign,
            'participantToken' => $participantToken,
            'relatedModule'    => $relatedModule,
        ], 'simulation');
    }

    public function completeLesson(string $token): void {
        $campaign = Database::fetchOne("SELECT * FROM campaigns WHERE public_token = ? LIMIT 1", [$token]);
        $participantToken = trim($_POST['participant_token'] ?? ($_SESSION['sim_participant_token'] ?? ''));

        if ($campaign && $participantToken) {
            try {
                Database::query("
                    INSERT INTO simulation_events (campaign_id, participant_token, event_type, metadata_json, ip_anonymized, created_at)
                    VALUES (?, ?, 'lesson_completed', '{\"source\":\"simulation_disclosure\"}', '127.0.0.0/24', NOW())
                ", [$campaign['id'], $participantToken]);
            } catch (\Throwable $e) {}
        }

        $this->flash('success', 'Thank you for completing this security awareness exercise! Your defensive readiness has been updated.');
        $this->redirect('academy');
    }
}
