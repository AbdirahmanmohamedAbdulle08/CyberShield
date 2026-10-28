<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Helpers\ViewHelper;
use App\Helpers\CsvHelper;

/**
 * Analytics and Educational Reports Controller
 */
class AnalyticsController extends Controller {
    public function index(): void {
        $this->requireRole(['super_admin', 'campaign_manager', 'instructor']);

        // 1. Campaign Metrics & Rate Calculations
        $campaignReports = Database::fetchAll("
            SELECT c.id, c.campaign_name, c.fictional_scenario, c.status, c.created_at,
                   g.group_name,
                   (SELECT COUNT(*) FROM participants p WHERE p.training_group_id = c.training_group_id) as eligible_participants,
                   (SELECT COUNT(DISTINCT participant_token) FROM simulation_events e WHERE e.campaign_id = c.id AND e.event_type = 'simulation_link_clicked') as unique_clicks,
                   (SELECT COUNT(DISTINCT participant_token) FROM simulation_events e WHERE e.campaign_id = c.id AND e.event_type = 'training_page_viewed') as unique_training_views,
                   (SELECT COUNT(DISTINCT participant_token) FROM suspicious_message_reports r WHERE r.campaign_id = c.id) as unique_reports
            FROM campaigns c
            LEFT JOIN training_groups g ON c.training_group_id = g.id
            ORDER BY c.id DESC
        ");

        // Calculate accurate percentage rates
        foreach ($campaignReports as &$cr) {
            $denom = max(1, (int)$cr['eligible_participants']);
            $cr['click_rate']    = round(($cr['unique_clicks'] / $denom) * 100, 1);
            $cr['training_rate'] = round(($cr['unique_training_views'] / $denom) * 100, 1);
            $cr['report_rate']   = round(($cr['unique_reports'] / $denom) * 100, 1);
        }
        unset($cr);

        // 2. Training Group Comparative Performance
        $groupComparisons = Database::fetchAll("
            SELECT g.id, g.group_name,
                   COUNT(DISTINCT p.id) as enrolled_students,
                   (SELECT COUNT(DISTINCT e.participant_token) 
                    FROM simulation_events e 
                    JOIN participants p2 ON e.participant_token = p2.participant_token 
                    WHERE p2.training_group_id = g.id AND e.event_type = 'simulation_link_clicked') as group_clicks,
                   (SELECT COUNT(DISTINCT r.participant_token) 
                    FROM suspicious_message_reports r 
                    JOIN participants p3 ON r.participant_token = p3.participant_token 
                    WHERE p3.training_group_id = g.id) as group_reports,
                   (SELECT IFNULL(ROUND(AVG(a.percentage), 1), 0)
                    FROM quiz_attempts a
                    JOIN participants p4 ON a.participant_token = p4.participant_token
                    WHERE p4.training_group_id = g.id) as group_avg_score
            FROM training_groups g
            LEFT JOIN participants p ON g.id = p.training_group_id
            GROUP BY g.id
            ORDER BY g.id ASC
        ");

        // 3. Module Completion Summary
        $moduleStats = Database::fetchAll("
            SELECT m.module_number, m.title,
                   COUNT(p.id) as completed_count
            FROM learning_modules m
            LEFT JOIN participant_learning_progress p ON m.id = p.module_id AND p.status = 'completed'
            GROUP BY m.id
            ORDER BY m.module_number ASC
        ");

        $this->render('reports/index', [
            'pageTitle'        => 'Analytics & Defense Reports',
            'campaignReports'  => $campaignReports,
            'groupComparisons' => $groupComparisons,
            'moduleStats'      => $moduleStats,
        ], 'main');
    }

    public function exportCampaignMetrics(): void {
        $this->requireRole(['super_admin', 'campaign_manager']);

        $campaigns = Database::fetchAll("
            SELECT c.id, c.campaign_name, c.fictional_scenario, c.status, g.group_name,
                   (SELECT COUNT(*) FROM participants p WHERE p.training_group_id = c.training_group_id) as eligible,
                   (SELECT COUNT(DISTINCT participant_token) FROM simulation_events e WHERE e.campaign_id = c.id AND e.event_type = 'simulation_link_clicked') as clicks,
                   (SELECT COUNT(DISTINCT participant_token) FROM simulation_events e WHERE e.campaign_id = c.id AND e.event_type = 'training_page_viewed') as training,
                   (SELECT COUNT(DISTINCT participant_token) FROM suspicious_message_reports r WHERE r.campaign_id = c.id) as reports,
                   c.created_at
            FROM campaigns c
            LEFT JOIN training_groups g ON c.training_group_id = g.id
            ORDER BY c.id ASC
        ");

        $headers = ['Campaign ID', 'Campaign Name', 'Scenario', 'Status', 'Target Cohort', 'Eligible Participants', 'Unique Clicks', 'Click Rate (%)', 'Training Views', 'Threat Reports', 'Reporting Rate (%)', 'Created At'];
        $rows = [];

        foreach ($campaigns as $c) {
            $denom = max(1, (int)$c['eligible']);
            $clickRate = round(($c['clicks'] / $denom) * 100, 1) . '%';
            $reportRate = round(($c['reports'] / $denom) * 100, 1) . '%';

            $rows[] = [
                $c['id'],
                $c['campaign_name'],
                $c['fictional_scenario'],
                $c['status'],
                $c['group_name'] ?? 'All',
                $c['eligible'],
                $c['clicks'],
                $clickRate,
                $c['training'],
                $c['reports'],
                $reportRate,
                $c['created_at'],
            ];
        }

        CsvHelper::download('cybershield_analytics_' . date('Ymd_His') . '.csv', $headers, $rows);
    }
}
