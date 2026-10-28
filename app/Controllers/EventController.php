<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Helpers\ViewHelper;

/**
 * Event Monitor Controller
 * Real-time event tracking, filtering, details, and live polling
 */
class EventController extends Controller {
    public function index(): void {
        $this->requireRole(['super_admin', 'campaign_manager', 'instructor']);

        $campaignFilter = trim($_GET['campaign_id'] ?? '');
        $typeFilter     = trim($_GET['event_type'] ?? '');
        $search         = trim($_GET['search'] ?? '');
        $page           = max(1, (int)($_GET['page'] ?? 1));
        $perPage        = 20;
        $offset         = ($page - 1) * $perPage;

        $sql = "
            SELECT e.*, c.campaign_name, c.fictional_scenario, p.pseudonym, p.display_name
            FROM simulation_events e
            JOIN campaigns c ON e.campaign_id = c.id
            LEFT JOIN participants p ON e.participant_token = p.participant_token
            WHERE 1=1
        ";
        $countSql = "
            SELECT COUNT(*)
            FROM simulation_events e
            JOIN campaigns c ON e.campaign_id = c.id
            LEFT JOIN participants p ON e.participant_token = p.participant_token
            WHERE 1=1
        ";
        $params = [];

        if (!empty($campaignFilter)) {
            $sql .= " AND e.campaign_id = ?";
            $countSql .= " AND e.campaign_id = ?";
            $params[] = (int)$campaignFilter;
        }

        if (!empty($typeFilter)) {
            $sql .= " AND e.event_type = ?";
            $countSql .= " AND e.event_type = ?";
            $params[] = $typeFilter;
        }

        if (!empty($search)) {
            $sql .= " AND (e.participant_token LIKE ? OR p.pseudonym LIKE ? OR c.campaign_name LIKE ?)";
            $countSql .= " AND (e.participant_token LIKE ? OR p.pseudonym LIKE ? OR c.campaign_name LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $totalEvents = (int)Database::fetchColumn($countSql, $params);
        $totalPages  = max(1, ceil($totalEvents / $perPage));

        $sql .= " ORDER BY e.id DESC LIMIT {$perPage} OFFSET {$offset}";
        $events = Database::fetchAll($sql, $params);

        $campaigns = Database::fetchAll("SELECT id, campaign_name FROM campaigns ORDER BY id DESC");

        $this->render('events/index', [
            'pageTitle'      => 'Event Monitor',
            'events'         => $events,
            'campaigns'      => $campaigns,
            'campaignFilter' => $campaignFilter,
            'typeFilter'     => $typeFilter,
            'search'         => $search,
            'page'           => $page,
            'totalPages'     => $totalPages,
            'totalEvents'    => $totalEvents,
        ], 'main');
    }

    /**
     * JSON endpoint for live polling
     */
    public function live(): void {
        $this->requireRole(['super_admin', 'campaign_manager', 'instructor']);

        $sinceId = (int)($_GET['since_id'] ?? 0);

        $events = Database::fetchAll("
            SELECT e.id, e.event_type, e.created_at, e.participant_token, e.metadata_json,
                   c.campaign_name, p.pseudonym
            FROM simulation_events e
            JOIN campaigns c ON e.campaign_id = c.id
            LEFT JOIN participants p ON e.participant_token = p.participant_token
            WHERE e.id > ?
            ORDER BY e.id ASC
            LIMIT 25
        ", [$sinceId]);

        $this->json([
            'success' => true,
            'events'  => $events,
            'count'   => count($events),
            'latest'  => !empty($events) ? end($events)['id'] : $sinceId,
        ]);
    }
}
