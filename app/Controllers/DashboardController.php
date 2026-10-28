<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Helpers\ViewHelper;

/**
 * Operations Center & Student Dashboard Controller
 */
class DashboardController extends Controller {
    public function index(): void {
        $user = $this->requireAuth();

        // If logged-in user is a student, route to student dashboard
        if ($user['role'] === 'student') {
            $this->redirect('student/dashboard');
        }

        // Live Database KPI Calculations
        $totalParticipants = (int)Database::fetchColumn("SELECT COUNT(*) FROM participants WHERE status = 'active'");
        $totalCampaigns    = (int)Database::fetchColumn("SELECT COUNT(*) FROM campaigns");
        $activeCampaigns   = (int)Database::fetchColumn("SELECT COUNT(*) FROM campaigns WHERE status = 'running'");
        $totalClicks       = (int)Database::fetchColumn("SELECT COUNT(*) FROM simulation_events WHERE event_type = 'simulation_link_clicked'");
        $totalTrainingVisits = (int)Database::fetchColumn("SELECT COUNT(*) FROM simulation_events WHERE event_type = 'training_page_viewed'");
        $totalReports      = (int)Database::fetchColumn("SELECT COUNT(*) FROM suspicious_message_reports");
        $completedLessons  = (int)Database::fetchColumn("SELECT COUNT(*) FROM participant_learning_progress WHERE status = 'completed'");
        $avgQuizScore      = (float)Database::fetchColumn("SELECT IFNULL(ROUND(AVG(percentage), 1), 0) FROM quiz_attempts");

        // Recent Activity Table (Last 10 events)
        $recentActivity = Database::fetchAll("
            SELECT e.id, e.event_type, e.created_at, e.participant_token, e.metadata_json,
                   c.campaign_name, c.fictional_scenario,
                   p.pseudonym
            FROM simulation_events e
            JOIN campaigns c ON e.campaign_id = c.id
            LEFT JOIN participants p ON e.participant_token = p.participant_token
            ORDER BY e.created_at DESC
            LIMIT 10
        ");

        // Chart 1: Activity Over Time (last 7 days or all recent days)
        $dailyEvents = Database::fetchAll("
            SELECT DATE(created_at) as event_date,
                   SUM(CASE WHEN event_type = 'simulation_link_clicked' THEN 1 ELSE 0 END) as clicks,
                   SUM(CASE WHEN event_type = 'training_page_viewed' THEN 1 ELSE 0 END) as training_views,
                   SUM(CASE WHEN event_type = 'suspicious_message_reported' THEN 1 ELSE 0 END) as reports
            FROM simulation_events
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 14 DAY)
            GROUP BY DATE(created_at)
            ORDER BY event_date ASC
        ");

        // Chart 2: Campaign Outcomes
        $campaignBreakdown = Database::fetchAll("
            SELECT c.campaign_name,
                   COUNT(CASE WHEN e.event_type = 'simulation_link_clicked' THEN 1 END) as clicks,
                   COUNT(CASE WHEN e.event_type = 'training_page_viewed' THEN 1 END) as training,
                   COUNT(CASE WHEN e.event_type = 'suspicious_message_reported' THEN 1 END) as reports
            FROM campaigns c
            LEFT JOIN simulation_events e ON c.id = e.campaign_id
            GROUP BY c.id
            ORDER BY c.id DESC
            LIMIT 5
        ");

        // Chart 3: Quiz Score Distribution (Pass vs Fail)
        $quizPassed = (int)Database::fetchColumn("SELECT COUNT(*) FROM quiz_attempts WHERE passed = 1");
        $quizFailed = (int)Database::fetchColumn("SELECT COUNT(*) FROM quiz_attempts WHERE passed = 0");

        $this->render('dashboard/index', [
            'pageTitle'          => 'SOC Operations Center',
            'kpis'               => [
                'totalParticipants'   => $totalParticipants,
                'totalCampaigns'      => $totalCampaigns,
                'activeCampaigns'     => $activeCampaigns,
                'totalClicks'         => $totalClicks,
                'totalTrainingVisits' => $totalTrainingVisits,
                'totalReports'        => $totalReports,
                'completedLessons'    => $completedLessons,
                'avgQuizScore'        => $avgQuizScore,
            ],
            'recentActivity'     => $recentActivity,
            'dailyEvents'        => $dailyEvents,
            'campaignBreakdown'  => $campaignBreakdown,
            'quizStats'          => ['passed' => $quizPassed, 'failed' => $quizFailed],
        ], 'main');
    }

    public function student(): void {
        $user = $this->requireAuth();

        // Fetch student's assigned learning modules and completion status
        $modules = Database::fetchAll("
            SELECT m.*, 
                   IFNULL(p.status, 'not_started') as progress_status,
                   p.completed_at as progress_completed_at
            FROM learning_modules m
            LEFT JOIN participant_learning_progress p 
                   ON m.id = p.module_id AND (p.user_id = ? OR p.participant_token = ?)
            ORDER BY m.module_number ASC
        ", [$user['id'], 'tok_hu_cs_001_kestrel']);

        // Student's personal quiz attempts
        $quizAttempts = Database::fetchAll("
            SELECT a.*, q.title as quiz_title, m.title as module_title
            FROM quiz_attempts a
            JOIN quizzes q ON a.quiz_id = q.id
            JOIN learning_modules m ON q.module_id = m.id
            WHERE a.user_id = ? OR a.participant_token = ?
            ORDER BY a.completed_at DESC
        ", [$user['id'], 'tok_hu_cs_001_kestrel']);

        // Active simulation notice (educational)
        $activeSimulations = Database::fetchAll("
            SELECT c.id, c.campaign_name, c.fictional_scenario, c.public_token, c.started_at
            FROM campaigns c
            WHERE c.status = 'running'
            ORDER BY c.started_at DESC
            LIMIT 3
        ");

        $this->render('dashboard/student', [
            'pageTitle'         => 'Student Cyber Awareness Portal',
            'user'              => $user,
            'modules'           => $modules,
            'quizAttempts'      => $quizAttempts,
            'activeSimulations' => $activeSimulations,
        ], 'main');
    }

    public function forbidden(): void {
        $this->render('dashboard/forbidden', [
            'pageTitle' => '403 — Unauthorized Access',
        ], 'main');
    }
}
