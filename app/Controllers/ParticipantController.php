<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Logger;
use App\Helpers\ViewHelper;
use App\Helpers\CsvHelper;

/**
 * Participant Management Controller
 */
class ParticipantController extends Controller {
    public function index(): void {
        $this->requireRole(['super_admin', 'campaign_manager', 'instructor']);

        $groupFilter   = trim($_GET['group_id'] ?? '');
        $consentFilter = trim($_GET['consent'] ?? '');
        $search        = trim($_GET['search'] ?? '');
        $page          = max(1, (int)($_GET['page'] ?? 1));
        $perPage       = 15;
        $offset        = ($page - 1) * $perPage;

        $sql = "
            SELECT p.*, g.group_name
            FROM participants p
            LEFT JOIN training_groups g ON p.training_group_id = g.id
            WHERE 1=1
        ";
        $countSql = "
            SELECT COUNT(*)
            FROM participants p
            LEFT JOIN training_groups g ON p.training_group_id = g.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($groupFilter)) {
            $sql .= " AND p.training_group_id = ?";
            $countSql .= " AND p.training_group_id = ?";
            $params[] = (int)$groupFilter;
        }

        if (!empty($consentFilter)) {
            $sql .= " AND p.consent_status = ?";
            $countSql .= " AND p.consent_status = ?";
            $params[] = $consentFilter;
        }

        if (!empty($search)) {
            $sql .= " AND (p.display_name LIKE ? OR p.pseudonym LIKE ? OR p.email LIKE ? OR p.institutional_id LIKE ?)";
            $countSql .= " AND (p.display_name LIKE ? OR p.pseudonym LIKE ? OR p.email LIKE ? OR p.institutional_id LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $total = (int)Database::fetchColumn($countSql, $params);
        $totalPages = max(1, ceil($total / $perPage));

        $sql .= " ORDER BY p.id ASC LIMIT {$perPage} OFFSET {$offset}";
        $participants = Database::fetchAll($sql, $params);

        $groups = Database::fetchAll("SELECT * FROM training_groups ORDER BY group_name ASC");

        $this->render('participants/index', [
            'pageTitle'     => 'Authorized Participants',
            'participants'  => $participants,
            'groups'        => $groups,
            'groupFilter'   => $groupFilter,
            'consentFilter' => $consentFilter,
            'search'        => $search,
            'page'          => $page,
            'totalPages'    => $totalPages,
            'total'         => $total,
        ], 'main');
    }

    public function store(): void {
        $this->requireRole(['super_admin', 'campaign_manager']);
        $this->validateCsrf();

        $name            = trim($_POST['display_name'] ?? '');
        $email           = trim($_POST['email'] ?? '');
        $institutionalId = trim($_POST['institutional_id'] ?? '');
        $groupId         = !empty($_POST['training_group_id']) ? (int)$_POST['training_group_id'] : null;

        if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flash('error', 'Valid name and university email address are required.');
            $this->redirect('participants');
        }

        // Generate non-reversible pseudonym & unique public token
        $token = 'tok_' . bin2hex(random_bytes(16));
        $pseudonym = 'Participant-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));

        Database::query("
            INSERT INTO participants (participant_token, display_name, pseudonym, email, institutional_id, training_group_id, consent_status, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, 'consented', 'active', NOW())
        ", [$token, $name, $pseudonym, $email, $institutionalId ?: null, $groupId]);

        Logger::log('participant_added', 'participant', Database::lastInsertId(), [
            'pseudonym' => $pseudonym,
            'group_id'  => $groupId,
        ]);

        $this->flash('success', 'Participant enrolled successfully with pseudonym: ' . htmlspecialchars($pseudonym));
        $this->redirect('participants');
    }

    public function toggleStatus(string $id): void {
        $this->requireRole(['super_admin', 'campaign_manager']);
        $this->validateCsrf();

        $p = Database::fetchOne("SELECT status FROM participants WHERE id = ?", [(int)$id]);
        if ($p) {
            $newStatus = ($p['status'] === 'active') ? 'inactive' : 'active';
            Database::query("UPDATE participants SET status = ? WHERE id = ?", [$newStatus, (int)$id]);
            Logger::log('participant_status_changed', 'participant', $id, ['new_status' => $newStatus]);
            $this->flash('success', "Participant status changed to {$newStatus}.");
        }

        $this->redirect('participants');
    }

    public function importCsv(): void {
        $this->requireRole(['super_admin', 'campaign_manager']);
        $this->validateCsrf();

        if (empty($_FILES['csv_file']['tmp_name'])) {
            $this->flash('error', 'Please select a CSV file to upload.');
            $this->redirect('participants');
        }

        $groupId = !empty($_POST['target_group_id']) ? (int)$_POST['target_group_id'] : null;
        $result = CsvHelper::parseParticipantsCsv($_FILES['csv_file']['tmp_name']);

        if (!$result['success']) {
            $this->flash('error', $result['error']);
            $this->redirect('participants');
        }

        $imported = 0;
        foreach ($result['participants'] as $p) {
            try {
                Database::query("
                    INSERT INTO participants (participant_token, display_name, pseudonym, email, institutional_id, training_group_id, consent_status, status, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, 'consented', 'active', NOW())
                ", [
                    $p['participant_token'],
                    $p['display_name'],
                    $p['pseudonym'],
                    $p['email'],
                    $p['institutional_id'],
                    $groupId,
                ]);
                $imported++;
            } catch (\Throwable $e) {}
        }

        Logger::log('participants_csv_imported', 'participant', null, [
            'total_imported' => $imported,
            'group_id'       => $groupId,
        ]);

        $this->flash('success', "Successfully imported {$imported} authorized participants from CSV.");
        $this->redirect('participants');
    }

    public function exportCsv(): void {
        $this->requireRole(['super_admin', 'campaign_manager']);

        $participants = Database::fetchAll("
            SELECT p.id, p.participant_token, p.pseudonym, p.display_name, p.email, p.institutional_id, g.group_name, p.consent_status, p.status, p.created_at
            FROM participants p
            LEFT JOIN training_groups g ON p.training_group_id = g.id
            ORDER BY p.id ASC
        ");

        $headers = ['ID', 'Participant Token', 'Pseudonym', 'Name', 'Email', 'Student ID', 'Training Group', 'Consent Status', 'Account Status', 'Created At'];
        $rows = [];

        foreach ($participants as $p) {
            $rows[] = [
                $p['id'],
                $p['participant_token'],
                $p['pseudonym'],
                $p['display_name'],
                $p['email'],
                $p['institutional_id'] ?? '—',
                $p['group_name'] ?? 'Unassigned',
                $p['consent_status'],
                $p['status'],
                $p['created_at'],
            ];
        }

        CsvHelper::download('cybershield_participants_' . date('Ymd_His') . '.csv', $headers, $rows);
    }
}
