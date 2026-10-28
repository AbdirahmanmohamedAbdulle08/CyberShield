<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Core\Logger;
use App\Helpers\ViewHelper;

/**
 * Awareness Academy Controller
 */
class AcademyController extends Controller {
    public function index(): void {
        $user = $this->requireAuth();

        $modules = Database::fetchAll("
            SELECT m.*, 
                   (SELECT COUNT(*) FROM quizzes q WHERE q.module_id = m.id) as quiz_count,
                   (SELECT COUNT(*) FROM participant_learning_progress p WHERE p.module_id = m.id AND p.status = 'completed') as completed_by_count,
                   p.status as my_progress,
                   p.completed_at as my_completed_at
            FROM learning_modules m
            LEFT JOIN participant_learning_progress p 
                   ON m.id = p.module_id AND (p.user_id = ? OR p.participant_token = ?)
            ORDER BY m.module_number ASC
        ", [$user['id'], 'tok_hu_cs_001_kestrel']);

        $this->render('academy/index', [
            'pageTitle' => 'Awareness Academy',
            'modules'   => $modules,
            'user'      => $user,
        ], 'main');
    }

    public function view(string $slug): void {
        $user = $this->requireAuth();

        $module = Database::fetchOne("
            SELECT m.*, q.id as quiz_id, q.title as quiz_title
            FROM learning_modules m
            LEFT JOIN quizzes q ON m.id = q.module_id
            WHERE m.slug = ?
        ", [$slug]);

        if (!$module) {
            $this->flash('error', 'Learning module not found.');
            $this->redirect('academy');
        }

        // Student's progress record
        $progress = Database::fetchOne("
            SELECT * FROM participant_learning_progress
            WHERE module_id = ? AND (user_id = ? OR participant_token = ?)
        ", [$module['id'], $user['id'], 'tok_hu_cs_001_kestrel']);

        // Log lesson started if not recorded yet
        if (!$progress) {
            try {
                Database::query("
                    INSERT INTO participant_learning_progress (module_id, user_id, participant_token, status, updated_at)
                    VALUES (?, ?, ?, 'in_progress', NOW())
                    ON DUPLICATE KEY UPDATE status = 'in_progress'
                ", [$module['id'], $user['id'], 'tok_hu_cs_001_kestrel']);

                Database::query("
                    INSERT INTO simulation_events (campaign_id, participant_token, event_type, metadata_json, ip_anonymized, created_at)
                    VALUES (1, ?, 'lesson_started', ?, '127.0.0.0/24', NOW())
                ", ['tok_hu_cs_001_kestrel', json_encode(['module_slug' => $slug])]);
            } catch (\Throwable $e) {}
        }

        $this->render('academy/view', [
            'pageTitle' => $module['title'],
            'module'    => $module,
            'progress'  => $progress,
            'user'      => $user,
        ], 'main');
    }

    public function markComplete(string $id): void {
        $user = $this->requireAuth();
        $this->validateCsrf();

        $moduleId = (int)$id;

        Database::query("
            INSERT INTO participant_learning_progress (module_id, user_id, participant_token, status, completed_at, updated_at)
            VALUES (?, ?, ?, 'completed', NOW(), NOW())
            ON DUPLICATE KEY UPDATE status = 'completed', completed_at = NOW()
        ", [$moduleId, $user['id'], 'tok_hu_cs_001_kestrel']);

        Database::query("
            INSERT INTO simulation_events (campaign_id, participant_token, event_type, metadata_json, ip_anonymized, created_at)
            VALUES (1, ?, 'lesson_completed', ?, '127.0.0.0/24', NOW())
        ", ['tok_hu_cs_001_kestrel', json_encode(['module_id' => $moduleId])]);

        Logger::log('lesson_completed', 'learning_module', (string)$moduleId, [
            'user_id' => $user['id'],
        ]);

        $this->flash('success', 'Lesson marked as completed! You can now take the knowledge check quiz.');
        $this->redirect('academy');
    }
}
