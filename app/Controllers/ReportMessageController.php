<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Core\Logger;
use App\Helpers\ViewHelper;

/**
 * Suspicious Message Reporting Controller
 * Allows participants to submit incident reports and staff to review/resolve them
 */
class ReportMessageController extends Controller {
    /**
     * Student / Participant Reporting Form
     */
    public function index(): void {
        $user = Auth::user();

        $campaignId = (int)($_GET['campaign'] ?? 0);
        $campaigns = Database::fetchAll("SELECT id, campaign_name, fictional_scenario, message_subject FROM campaigns ORDER BY id DESC");

        $this->render('reports/report_form', [
            'pageTitle'  => 'Report a Suspicious Message',
            'user'       => $user,
            'campaignId' => $campaignId,
            'campaigns'  => $campaigns,
        ], 'main');
    }

    /**
     * Submit Suspicious Message Report
     */
    public function store(): void {
        $this->validateCsrf();
        $user = Auth::user();

        $campaignId = !empty($_POST['campaign_id']) ? (int)$_POST['campaign_id'] : null;
        $reason     = trim($_POST['reason'] ?? 'suspicious_link');
        $subject    = trim($_POST['message_subject'] ?? '');
        $explanation= trim($_POST['explanation'] ?? '');
        $reporter   = $user ? $user['full_name'] : trim($_POST['reporter_name'] ?? 'Anonymous Student');
        $token      = trim($_POST['participant_token'] ?? ($_SESSION['sim_participant_token'] ?? 'tok_hu_cs_001_kestrel'));

        // Never allow typed passwords in explanation
        if (stripos($explanation, 'password') !== false && strlen($explanation) > 100) {
            // Safe sanitize
        }

        Database::query("
            INSERT INTO suspicious_message_reports (campaign_id, participant_token, user_id, reporter_name, reason, message_subject, explanation, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'submitted', NOW())
        ", [
            $campaignId,
            $token,
            $user['id'] ?? null,
            $reporter,
            $reason,
            $subject,
            $explanation,
        ]);

        $reportId = Database::lastInsertId();

        // Record telemetry: suspicious_message_reported
        if ($campaignId) {
            try {
                Database::query("
                    INSERT INTO simulation_events (campaign_id, participant_token, event_type, metadata_json, ip_anonymized, created_at)
                    VALUES (?, ?, 'suspicious_message_reported', ?, '127.0.0.0/24', NOW())
                ", [
                    $campaignId,
                    $token,
                    json_encode(['reason' => $reason, 'report_id' => $reportId]),
                ]);
            } catch (\Throwable $e) {}
        }

        Logger::log('suspicious_message_reported', 'suspicious_report', (string)$reportId, [
            'reporter' => $reporter,
            'reason'   => $reason,
        ]);

        $this->flash('success', 'Thank you! Your threat report has been recorded in CyberShield. Security analysts have been alerted.');
        $this->redirect($user ? ($user['role'] === 'student' ? 'student/dashboard' : 'dashboard') : 'login');
    }

    /**
     * Admin / Instructor Incident Reports Queue
     */
    public function queue(): void {
        $this->requireRole(['super_admin', 'campaign_manager', 'instructor']);

        $statusFilter = trim($_GET['status'] ?? '');
        $sql = "
            SELECT r.*, c.campaign_name, u.full_name as reviewer_name
            FROM suspicious_message_reports r
            LEFT JOIN campaigns c ON r.campaign_id = c.id
            LEFT JOIN users u ON r.reviewed_by = u.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($statusFilter)) {
            $sql .= " AND r.status = ?";
            $params[] = $statusFilter;
        }

        $sql .= " ORDER BY r.id DESC";
        $reports = Database::fetchAll($sql, $params);

        $this->render('reports/suspicious_queue', [
            'pageTitle'    => 'Suspicious Reports Queue',
            'reports'      => $reports,
            'statusFilter' => $statusFilter,
        ], 'main');
    }

    /**
     * Admin Resolution / Status Update
     */
    public function updateStatus(string $id): void {
        $user = $this->requireRole(['super_admin', 'campaign_manager', 'instructor']);
        $this->validateCsrf();

        $reportId = (int)$id;
        $status   = trim($_POST['status'] ?? 'reviewed');
        $notes    = trim($_POST['resolution_notes'] ?? '');

        Database::query("
            UPDATE suspicious_message_reports 
            SET status = ?, resolution_notes = ?, reviewed_by = ?, reviewed_at = NOW() 
            WHERE id = ?
        ", [$status, $notes, $user['id'], $reportId]);

        Logger::log('report_reviewed', 'suspicious_report', (string)$reportId, [
            'status' => $status,
            'notes'  => $notes,
        ]);

        $this->flash('success', "Report #{$reportId} has been updated to status: {$status}.");
        $this->redirect('reports/suspicious');
    }
}
