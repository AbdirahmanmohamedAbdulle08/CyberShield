<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Core\Logger;
use App\Helpers\ViewHelper;

/**
 * Quiz Management and Assessment Controller
 */
class QuizController extends Controller {
    public function index(): void {
        $user = $this->requireAuth();

        $quizzes = Database::fetchAll("
            SELECT q.*, m.title as module_title, m.slug as module_slug, m.module_number,
                   (SELECT COUNT(*) FROM quiz_questions k WHERE k.quiz_id = q.id) as question_count,
                   (SELECT COUNT(*) FROM quiz_attempts a WHERE a.quiz_id = q.id) as total_attempts,
                   (SELECT MAX(percentage) FROM quiz_attempts a WHERE a.quiz_id = q.id AND (a.user_id = ? OR a.participant_token = ?)) as my_high_score
            FROM quizzes q
            JOIN learning_modules m ON q.module_id = m.id
            ORDER BY m.module_number ASC
        ", [$user['id'], 'tok_hu_cs_001_kestrel']);

        $this->render('quizzes/index', [
            'pageTitle' => 'Quiz Center & Knowledge Assessments',
            'quizzes'   => $quizzes,
            'user'      => $user,
        ], 'main');
    }

    public function take(string $id): void {
        $user = $this->requireAuth();

        $quiz = Database::fetchOne("
            SELECT q.*, m.title as module_title, m.slug as module_slug, m.module_number
            FROM quizzes q
            JOIN learning_modules m ON q.module_id = m.id
            WHERE q.id = ?
        ", [(int)$id]);

        if (!$quiz) {
            $this->flash('error', 'Quiz not found.');
            $this->redirect('quizzes');
        }

        // Fetch questions without leaking is_correct flag to the client template!
        $questions = Database::fetchAll("
            SELECT id, question_text, sort_order 
            FROM quiz_questions 
            WHERE quiz_id = ? 
            ORDER BY sort_order ASC
        ", [$quiz['id']]);

        foreach ($questions as &$q) {
            $q['options'] = Database::fetchAll("
                SELECT id, option_text, sort_order 
                FROM quiz_options 
                WHERE question_id = ? 
                ORDER BY sort_order ASC
            ", [$q['id']]);
        }
        unset($q);

        // Record telemetry: quiz_started
        try {
            Database::query("
                INSERT INTO simulation_events (campaign_id, participant_token, event_type, metadata_json, ip_anonymized, created_at)
                VALUES (1, ?, 'quiz_started', ?, '127.0.0.0/24', NOW())
            ", ['tok_hu_cs_001_kestrel', json_encode(['quiz_id' => $quiz['id']])]);
        } catch (\Throwable $e) {}

        $this->render('quizzes/take', [
            'pageTitle' => 'Assessment: ' . $quiz['title'],
            'quiz'      => $quiz,
            'questions' => $questions,
        ], 'main');
    }

    public function submit(string $id): void {
        $user = $this->requireAuth();
        $this->validateCsrf();

        $quizId = (int)$id;
        $quiz = Database::fetchOne("SELECT * FROM quizzes WHERE id = ?", [$quizId]);

        if (!$quiz) {
            $this->flash('error', 'Quiz not found.');
            $this->redirect('quizzes');
        }

        $submittedAnswers = $_POST['answers'] ?? []; // question_id => option_id
        $questions = Database::fetchAll("SELECT id, question_text, explanation FROM quiz_questions WHERE quiz_id = ?", [$quizId]);

        $score = 0;
        $totalQuestions = count($questions);
        $reviewDetails = [];

        foreach ($questions as $q) {
            $qid = $q['id'];
            $selectedOptId = isset($submittedAnswers[$qid]) ? (int)$submittedAnswers[$qid] : null;

            // Fetch correct option from DB
            $correctOpt = Database::fetchOne("SELECT id, option_text FROM quiz_options WHERE question_id = ? AND is_correct = 1 LIMIT 1", [$qid]);
            $selectedOpt = $selectedOptId ? Database::fetchOne("SELECT id, option_text FROM quiz_options WHERE id = ?", [$selectedOptId]) : null;

            $isCorrect = ($selectedOptId !== null && $correctOpt && $selectedOptId === (int)$correctOpt['id']);
            if ($isCorrect) {
                $score++;
            }

            $reviewDetails[] = [
                'question_id'     => $qid,
                'question_text'   => $q['question_text'],
                'explanation'     => $q['explanation'],
                'selected_option' => $selectedOpt['option_text'] ?? 'No answer provided',
                'correct_option'  => $correctOpt['option_text'] ?? 'Unknown',
                'is_correct'      => $isCorrect,
            ];
        }

        $percentage = ($totalQuestions > 0) ? round(($score / $totalQuestions) * 100, 2) : 0;
        $passed = ($percentage >= $quiz['passing_score']) ? 1 : 0;

        // Save attempt in database
        Database::query("
            INSERT INTO quiz_attempts (quiz_id, user_id, participant_token, score, total_questions, percentage, passed, answers_json, completed_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ", [
            $quizId,
            $user['id'],
            'tok_hu_cs_001_kestrel',
            $score,
            $totalQuestions,
            $percentage,
            $passed,
            json_encode($submittedAnswers),
        ]);

        $attemptId = Database::lastInsertId();

        // Record telemetry: quiz_completed
        try {
            Database::query("
                INSERT INTO simulation_events (campaign_id, participant_token, event_type, metadata_json, ip_anonymized, created_at)
                VALUES (1, ?, 'quiz_completed', ?, '127.0.0.0/24', NOW())
            ", [
                'tok_hu_cs_001_kestrel',
                json_encode(['quiz_id' => $quizId, 'score' => $score, 'passed' => $passed]),
            ]);
        } catch (\Throwable $e) {}

        Logger::log('quiz_completed', 'quiz', (string)$quizId, [
            'score'      => $score,
            'percentage' => $percentage,
            'passed'     => $passed,
        ]);

        $this->render('quizzes/result', [
            'pageTitle'      => 'Assessment Result: ' . $quiz['title'],
            'quiz'           => $quiz,
            'score'          => $score,
            'totalQuestions' => $totalQuestions,
            'percentage'     => $percentage,
            'passed'         => $passed,
            'reviewDetails'  => $reviewDetails,
        ], 'main');
    }
}
