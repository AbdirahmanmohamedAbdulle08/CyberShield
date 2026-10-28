<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Helpers\ViewHelper;

/**
 * Audit Logs Controller
 */
class AuditController extends Controller {
    public function index(): void {
        $this->requireRole('super_admin');

        $actionFilter = trim($_GET['action'] ?? '');
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 25;
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM audit_logs WHERE 1=1";
        $countSql = "SELECT COUNT(*) FROM audit_logs WHERE 1=1";
        $params = [];

        if (!empty($actionFilter)) {
            $sql .= " AND action LIKE ?";
            $countSql .= " AND action LIKE ?";
            $params[] = "%{$actionFilter}%";
        }

        $totalLogs = (int)Database::fetchColumn($countSql, $params);
        $totalPages = max(1, ceil($totalLogs / $perPage));

        $sql .= " ORDER BY id DESC LIMIT {$perPage} OFFSET {$offset}";
        $logs = Database::fetchAll($sql, $params);

        $this->render('audit/index', [
            'pageTitle'    => 'Administrative Audit Logs',
            'logs'         => $logs,
            'actionFilter' => $actionFilter,
            'page'         => $page,
            'totalPages'   => $totalPages,
            'totalLogs'    => $totalLogs,
        ], 'main');
    }
}
