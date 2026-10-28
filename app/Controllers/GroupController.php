<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Logger;
use App\Helpers\ViewHelper;

/**
 * Training Groups Controller
 */
class GroupController extends Controller {
    public function index(): void {
        $user = $this->requireRole(['super_admin', 'campaign_manager', 'instructor']);

        $groups = Database::fetchAll("
            SELECT g.*, 
                   u.full_name as instructor_name,
                   (SELECT COUNT(*) FROM participants p WHERE p.training_group_id = g.id) as participant_count,
                   (SELECT COUNT(*) FROM campaigns c WHERE c.training_group_id = g.id) as campaign_count
            FROM training_groups g
            LEFT JOIN users u ON g.instructor_id = u.id
            ORDER BY g.id ASC
        ");

        $instructors = Database::fetchAll("
            SELECT id, full_name, username FROM users 
            WHERE role IN ('instructor', 'super_admin') 
            ORDER BY full_name ASC
        ");

        $this->render('groups/index', [
            'pageTitle'   => 'Training Groups & Student Cohorts',
            'groups'      => $groups,
            'instructors' => $instructors,
            'user'        => $user,
        ], 'main');
    }

    public function store(): void {
        $this->requireRole(['super_admin', 'campaign_manager']);
        $this->validateCsrf();

        $name         = trim($_POST['group_name'] ?? '');
        $description  = trim($_POST['description'] ?? '');
        $instructorId = !empty($_POST['instructor_id']) ? (int)$_POST['instructor_id'] : null;

        if (empty($name)) {
            $this->flash('error', 'Group name is required.');
            $this->redirect('groups');
        }

        Database::query("
            INSERT INTO training_groups (group_name, description, instructor_id, status, created_at)
            VALUES (?, ?, ?, 'active', NOW())
        ", [$name, $description, $instructorId]);

        Logger::log('training_group_created', 'training_group', Database::lastInsertId(), [
            'name'          => $name,
            'instructor_id' => $instructorId,
        ]);

        $this->flash('success', 'Training group created successfully.');
        $this->redirect('groups');
    }
}
