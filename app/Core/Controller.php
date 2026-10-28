<?php
namespace App\Core;

use App\Helpers\ViewHelper;

/**
 * Base Application Controller
 */
abstract class Controller {
    /**
     * Render view wrapped inside layout
     */
    protected function render(string $viewPath, array $data = [], string $layout = 'main'): void {
        extract($data);
        
        // Flash messages
        $flashSuccess = $_SESSION['flash_success'] ?? null;
        $flashError   = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        // Current authenticated user
        $currentUser = Auth::user();

        // Render inner view
        ob_start();
        $viewFile = __DIR__ . '/../../views/' . ltrim($viewPath, '/') . '.php';
        if (!file_exists($viewFile)) {
            die("View template not found: " . htmlspecialchars($viewPath));
        }
        require $viewFile;
        $content = ob_get_clean();

        // If no layout requested, output view directly
        if ($layout === 'none' || empty($layout)) {
            echo $content;
            return;
        }

        // Render layout
        $layoutFile = __DIR__ . '/../../views/layouts/' . $layout . '.php';
        if (!file_exists($layoutFile)) {
            die("Layout template not found: " . htmlspecialchars($layout));
        }
        require $layoutFile;
    }

    /**
     * JSON output helper
     */
    protected function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Safe internal redirect
     */
    protected function redirect(string $path): void {
        header('Location: ' . ViewHelper::url($path));
        exit;
    }

    /**
     * Enforce authentication
     */
    protected function requireAuth(): array {
        if (!Auth::check()) {
            $this->flash('error', 'Please log in to access this page.');
            $this->redirect('login');
        }
        return Auth::user();
    }

    /**
     * Enforce specific user roles (RBAC)
     */
    protected function requireRole($roles): array {
        $user = $this->requireAuth();
        if (!Auth::hasRole($roles)) {
            http_response_code(403);
            Logger::log('unauthorized_access_attempt', 'route', null, [
                'user_role'       => $user['role'],
                'required_roles'  => (array)$roles,
                'requested_route' => $_SERVER['REQUEST_URI'] ?? '',
            ]);
            $this->render('dashboard/forbidden', [
                'requiredRoles' => (array)$roles,
                'userRole'      => $user['role'],
            ], 'main');
            exit;
        }
        return $user;
    }

    /**
     * Enforce CSRF token verification on state-changing requests
     */
    protected function validateCsrf(): void {
        if (!CSRF::validate()) {
            http_response_code(403);
            Logger::log('csrf_validation_failed', 'security', null, [
                'route' => $_SERVER['REQUEST_URI'] ?? '',
            ]);
            die("<div style='background:#11192e;color:#f87171;padding:30px;font-family:sans-serif;border:1px solid #ef4444;border-radius:8px;max-width:600px;margin:50px auto;'>"
                . "<h2 style='margin-top:0;'>⚠️ Security Error: CSRF Validation Failed</h2>"
                . "<p>Your security token has expired or is invalid. Please go back and refresh the page.</p>"
                . "<p><a href='javascript:history.back()' style='color:#38bdf8;'>&larr; Go Back</a></p>"
                . "</div>");
        }
    }

    /**
     * Session flash message
     */
    protected function flash(string $type, string $message): void {
        Auth::initSession();
        $_SESSION['flash_' . $type] = $message;
    }
}
