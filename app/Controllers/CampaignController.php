<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Core\Logger;
use App\Helpers\ViewHelper;

/**
 * Campaign Management Controller
 */
class CampaignController extends Controller {
    /**
     * Built-in Fictional Scenario Templates (Educational and Safe)
     */
    public static function getScenarioTemplates(): array {
        return [
            'reward_500' => [
                'name'             => 'Fake $500 Tech Grant Reward',
                'fictional_scenario' => 'Fake $500 reward',
                'objective'        => 'Train students to identify unrealistic financial rewards, artificial urgency, and lookalike domain senders.',
                'sender_name'      => 'Hormuud Innovation Foundation',
                'sender_email'     => 'grants-dept@tech-awards-foundation.org',
                'subject'          => 'Congratulations! You have been selected for a $500 student research grant',
                'body'             => "Dear Student,\r\n\r\nCongratulations! Your student ID was randomly selected in the 2026 Academic Research Grant Pool. You are eligible to receive a $500 direct educational stipend.\r\n\r\nDue to funding constraints, you must confirm your acceptance within 4 hours, or the funds will be transferred to an alternate candidate.\r\n\r\nClick below to verify your eligibility and claim your funds immediately:\r\n\r\n[Claim $500 Reward Now]",
            ],
            'urgent_security' => [
                'name'             => 'Emergency Campus IT Security Alert',
                'fictional_scenario' => 'Urgent account-security warning',
                'objective'        => 'Demonstrate how panic and fear-based lures manipulate users into clicking unverified security links.',
                'sender_name'      => 'Campus IT Operations Desk',
                'sender_email'     => 'security-alerts@campus-auth-system.net',
                'subject'          => 'URGENT: Unauthorized login attempt detected from unrecognized subnet',
                'body'             => "SECURITY ALERT:\r\n\r\nA login attempt to your university account from an unknown IP address was flagged by the firewall. Your account has been scheduled for suspension in 30 minutes to protect institutional data.\r\n\r\nIf this was not you, you must re-authenticate your identity immediately:\r\n\r\n[Verify Identity & Prevent Suspension]",
            ],
            'delivery_scam' => [
                'name'             => 'Package Delivery Exception Notification',
                'fictional_scenario' => 'Fake delivery notification',
                'objective'        => 'Educate students on common parcel delivery smishing/phishing lures requesting address updates.',
                'sender_name'      => 'Global Express Parcel Logistics',
                'sender_email'     => 'tracking@express-parcels-hub.cc',
                'subject'          => 'Delivery Exception: Incomplete address code on shipment #HU-9921',
                'body'             => "Notice: Your parcel shipment could not be deposited at the university campus desk due to an unreadable suite number.\r\n\r\nPlease confirm your delivery details online within 24 hours to schedule re-delivery:\r\n\r\n[Confirm Campus Delivery Address]",
            ],
            'job_offer' => [
                'name'             => 'High-Paying Remote Cyber Intern Offer',
                'fictional_scenario' => 'Suspicious job opportunity',
                'objective'        => 'Expose fraudulent recruitment schemes promising excessive hourly compensation without interviews.',
                'sender_name'      => 'Global CyberTalent Recruiter',
                'sender_email'     => 'careers@cybersec-talentrecruits.com',
                'subject'          => 'Job Offer: Remote Cybersecurity Analyst Intern ($45/hr) - Immediate Start',
                'body'             => "Hello,\r\n\r\nWe noticed your high academic performance. Our international cyber firm is recruiting 5 student interns for remote threat assessment.\r\n\r\nRate: $45.00/hour. Flexible schedule. No prior experience required.\r\n\r\nReview job requirements and submit your application:\r\n\r\n[Apply for Position]",
            ],
            'exam_schedule' => [
                'name'             => 'Urgent Examination Schedule Adjustment',
                'fictional_scenario' => 'Suspicious university announcement',
                'objective'        => 'Teach students to verify institutional schedule announcements via official portal links rather than email attachments.',
                'sender_name'      => 'Academic Examination Board',
                'sender_email'     => 'examination-office@exam-portal-verify.info',
                'subject'          => 'CRITICAL: Revised Midterm Examination Schedule & Seating Allocation',
                'body'             => "All Students Notice:\r\n\r\nDue to auditorium renovations, the upcoming Cybersecurity and IT midterm exam dates and room assignments have been revised. Every student must download and sign their revised admit card.\r\n\r\nView updated examination timetable:\r\n\r\n[Download Revised Exam Schedule]",
            ],
            'scholarship' => [
                'name'             => 'East Africa Cyber Leadership Scholarship',
                'fictional_scenario' => 'Fake scholarship offer',
                'objective'        => 'Detect scholarship advance-fee fraud and requests for sensitive background details.',
                'sender_name'      => 'Regional Technology Scholarship Committee',
                'sender_email'     => 'awards@regional-scholarships-eastafrica.org',
                'subject'          => 'Full Tuition Scholarship: East Africa Cyber Leadership Award 2026',
                'body'             => "Dear Applicant,\r\n\r\nYou have qualified for the 2026 Cyber Leadership Full-Tuition Scholarship. To finalize the award allocation, complete the verification form before the quota closes:\r\n\r\n[Complete Scholarship Acceptance]",
            ],
        ];
    }

    public function index(): void {
        $user = $this->requireRole(['super_admin', 'campaign_manager', 'instructor']);

        $statusFilter = trim($_GET['status'] ?? '');
        $search = trim($_GET['search'] ?? '');

        $sql = "
            SELECT c.*, 
                   u.full_name as creator_name,
                   g.group_name,
                   (SELECT COUNT(*) FROM participants p WHERE p.training_group_id = c.training_group_id) as target_count,
                   (SELECT COUNT(*) FROM simulation_events e WHERE e.campaign_id = c.id AND e.event_type = 'simulation_link_clicked') as click_count,
                   (SELECT COUNT(*) FROM simulation_events e WHERE e.campaign_id = c.id AND e.event_type = 'training_page_viewed') as training_count,
                   (SELECT COUNT(*) FROM suspicious_message_reports r WHERE r.campaign_id = c.id) as report_count
            FROM campaigns c
            LEFT JOIN users u ON c.created_by = u.id
            LEFT JOIN training_groups g ON c.training_group_id = g.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($statusFilter)) {
            $sql .= " AND c.status = ?";
            $params[] = $statusFilter;
        }

        if (!empty($search)) {
            $sql .= " AND (c.campaign_name LIKE ? OR c.fictional_scenario LIKE ? OR c.message_subject LIKE ?)";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }

        $sql .= " ORDER BY c.id DESC";

        $campaigns = Database::fetchAll($sql, $params);

        $this->render('campaigns/index', [
            'pageTitle'    => 'Campaign Management',
            'campaigns'    => $campaigns,
            'statusFilter' => $statusFilter,
            'search'       => $search,
            'user'         => $user,
        ], 'main');
    }

    public function create(): void {
        $this->requireRole(['super_admin', 'campaign_manager']);

        $groups = Database::fetchAll("SELECT * FROM training_groups WHERE status = 'active' ORDER BY group_name ASC");
        $templates = self::getScenarioTemplates();

        $this->render('campaigns/create', [
            'pageTitle' => 'Campaign Builder',
            'groups'    => $groups,
            'templates' => $templates,
        ], 'main');
    }

    public function store(): void {
        $user = $this->requireRole(['super_admin', 'campaign_manager']);
        $this->validateCsrf();

        $name       = trim($_POST['campaign_name'] ?? '');
        $scenario   = trim($_POST['fictional_scenario'] ?? 'Fake $500 reward');
        $objective  = trim($_POST['educational_objective'] ?? '');
        $senderName = trim($_POST['sender_name'] ?? '');
        $senderMail = trim($_POST['sender_email'] ?? '');
        $subject    = trim($_POST['message_subject'] ?? '');
        $body       = trim($_POST['message_body'] ?? '');
        $groupId    = !empty($_POST['training_group_id']) ? (int)$_POST['training_group_id'] : null;
        $status     = ($user['role'] === 'super_admin') ? 'authorized' : 'pending_authorization';

        if (empty($name) || empty($subject) || empty($body)) {
            $this->flash('error', 'Campaign name, subject, and message body are mandatory fields.');
            $this->redirect('campaigns/create');
        }

        // Generate non-guessable entropy token for simulation URL
        $publicToken = 'sim_' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', substr($scenario, 0, 10))) . '_' . bin2hex(random_bytes(10));

        Database::query("
            INSERT INTO campaigns (campaign_name, educational_objective, fictional_scenario, sender_name, sender_email, message_subject, message_body, training_group_id, public_token, status, created_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ", [
            $name, $objective, $scenario, $senderName, $senderMail, $subject, $body, $groupId, $publicToken, $status, $user['id']
        ]);

        $campaignId = Database::lastInsertId();

        // If created by Super Admin, also insert authorization record automatically
        if ($user['role'] === 'super_admin') {
            Database::query("
                INSERT INTO campaign_authorizations (campaign_id, authorized_by, authorization_notes, authorized_at, status)
                VALUES (?, ?, 'Auto-authorized upon creation by Super Admin', NOW(), 'approved')
            ", [$campaignId, $user['id']]);
        }

        Logger::log('campaign_created', 'campaign', (string)$campaignId, [
            'name'     => $name,
            'scenario' => $scenario,
            'status'   => $status,
        ]);

        $this->flash('success', 'Campaign created successfully! ' . ($status === 'authorized' ? 'It is authorized and ready to launch.' : 'Awaiting Super Admin authorization.'));
        $this->redirect('campaigns/' . $campaignId);
    }

    public function view(string $id): void {
        $this->requireRole(['super_admin', 'campaign_manager', 'instructor']);

        $campaign = Database::fetchOne("
            SELECT c.*, 
                   u.full_name as creator_name,
                   g.group_name
            FROM campaigns c
            LEFT JOIN users u ON c.created_by = u.id
            LEFT JOIN training_groups g ON c.training_group_id = g.id
            WHERE c.id = ?
        ", [(int)$id]);

        if (!$campaign) {
            $this->flash('error', 'Campaign not found.');
            $this->redirect('campaigns');
        }

        // Campaign stats
        $stats = [
            'clicks'    => (int)Database::fetchColumn("SELECT COUNT(*) FROM simulation_events WHERE campaign_id = ? AND event_type = 'simulation_link_clicked'", [$campaign['id']]),
            'training'  => (int)Database::fetchColumn("SELECT COUNT(*) FROM simulation_events WHERE campaign_id = ? AND event_type = 'training_page_viewed'", [$campaign['id']]),
            'reports'   => (int)Database::fetchColumn("SELECT COUNT(*) FROM suspicious_message_reports WHERE campaign_id = ?", [$campaign['id']]),
            'targets'   => (int)Database::fetchColumn("SELECT COUNT(*) FROM participants WHERE training_group_id = ?", [$campaign['training_group_id']]),
        ];

        // Authorizations history
        $authorizations = Database::fetchAll("
            SELECT a.*, u.full_name as authorizer_name
            FROM campaign_authorizations a
            JOIN users u ON a.authorized_by = u.id
            WHERE a.campaign_id = ?
            ORDER BY a.authorized_at DESC
        ", [$campaign['id']]);

        // Target participants in group
        $participants = [];
        if (!empty($campaign['training_group_id'])) {
            $participants = Database::fetchAll("
                SELECT p.*,
                       (SELECT COUNT(*) FROM simulation_events e WHERE e.campaign_id = ? AND e.participant_token = p.participant_token AND e.event_type = 'simulation_link_clicked') as clicked,
                       (SELECT COUNT(*) FROM simulation_events e WHERE e.campaign_id = ? AND e.participant_token = p.participant_token AND e.event_type = 'training_page_viewed') as viewed_training,
                       (SELECT COUNT(*) FROM suspicious_message_reports r WHERE r.campaign_id = ? AND r.participant_token = p.participant_token) as reported
                FROM participants p
                WHERE p.training_group_id = ?
                ORDER BY p.id ASC
            ", [$campaign['id'], $campaign['id'], $campaign['id'], $campaign['training_group_id']]);
        }

        $this->render('campaigns/view', [
            'pageTitle'      => 'Campaign: ' . $campaign['campaign_name'],
            'campaign'       => $campaign,
            'stats'          => $stats,
            'authorizations' => $authorizations,
            'participants'   => $participants,
        ], 'main');
    }

    public function authorize(string $id): void {
        $user = $this->requireRole('super_admin');
        $this->validateCsrf();

        $campaignId = (int)$id;
        $notes = trim($_POST['authorization_notes'] ?? 'Authorized for educational demonstration at Hormuud University.');

        Database::query("
            INSERT INTO campaign_authorizations (campaign_id, authorized_by, authorization_notes, authorized_at, status)
            VALUES (?, ?, ?, NOW(), 'approved')
        ", [$campaignId, $user['id'], $notes]);

        Database::query("UPDATE campaigns SET status = 'authorized' WHERE id = ?", [$campaignId]);

        Logger::log('campaign_authorized', 'campaign', (string)$campaignId, ['notes' => $notes]);
        $this->flash('success', 'Campaign has been formally authorized.');
        $this->redirect('campaigns/' . $campaignId);
    }

    public function start(string $id): void {
        $this->requireRole(['super_admin', 'campaign_manager']);
        $this->validateCsrf();

        $campaignId = (int)$id;
        $campaign = Database::fetchOne("SELECT * FROM campaigns WHERE id = ?", [$campaignId]);

        if (!$campaign) {
            $this->flash('error', 'Campaign not found.');
            $this->redirect('campaigns');
        }

        if ($campaign['status'] !== 'authorized' && $campaign['status'] !== 'draft') {
            $this->flash('error', 'Only authorized or draft campaigns can be started.');
            $this->redirect('campaigns/' . $campaignId);
        }

        Database::query("UPDATE campaigns SET status = 'running', started_at = NOW() WHERE id = ?", [$campaignId]);

        Logger::log('campaign_started', 'campaign', (string)$campaignId, [
            'name' => $campaign['campaign_name'],
        ]);

        $this->flash('success', 'Simulation campaign is now RUNNING! Participants can safely interact with the simulation link.');
        $this->redirect('campaigns/' . $campaignId);
    }

    public function complete(string $id): void {
        $this->requireRole(['super_admin', 'campaign_manager']);
        $this->validateCsrf();

        $campaignId = (int)$id;
        Database::query("UPDATE campaigns SET status = 'completed', completed_at = NOW() WHERE id = ?", [$campaignId]);

        Logger::log('campaign_completed', 'campaign', (string)$campaignId);
        $this->flash('success', 'Campaign has been marked as Completed. Telemetry is archived.');
        $this->redirect('campaigns/' . $campaignId);
    }
}
