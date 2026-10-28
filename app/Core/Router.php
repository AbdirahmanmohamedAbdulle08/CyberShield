<?php
namespace App\Core;

use App\Helpers\ViewHelper;

/**
 * CyberShield Lightweight Route Dispatcher
 * Supports parameter matching, GET/POST routing, and fallback query strings
 */
class Router {
    private array $routes = [];

    public function get(string $pattern, $handler): void {
        $this->addRoute('GET', $pattern, $handler);
    }

    public function post(string $pattern, $handler): void {
        $this->addRoute('POST', $pattern, $handler);
    }

    private function addRoute(string $method, string $pattern, $handler): void {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'pattern' => '/' . trim($pattern, '/'),
            'handler' => $handler,
        ];
    }

    public function dispatch(): void {
        Auth::initSession();

        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $this->resolveRequestUri();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            // Convert route pattern with {param} into regex
            $patternRegex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['pattern']);
            $patternRegex = '#^' . $patternRegex . '$#';

            if (preg_match($patternRegex, $uri, $matches)) {
                // Filter string keys for parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $this->executeHandler($route['handler'], $params);
                return;
            }
        }

        // Route not found -> 404
        $this->handle404($uri);
    }

    private function resolveRequestUri(): string {
        // Check query parameter fallback ?r=... or ?route=...
        if (!empty($_GET['r'])) {
            return '/' . trim($_GET['r'], '/');
        }
        if (!empty($_GET['route'])) {
            return '/' . trim($_GET['route'], '/');
        }

        $requestUri = rawurldecode($_SERVER['REQUEST_URI'] ?? '/');
        $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $baseDir    = str_replace('\\', '/', dirname($scriptName));

        // Strip query string
        if (false !== ($pos = strpos($requestUri, '?'))) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        // Strip baseDir from request URI
        if ($baseDir !== '/' && strpos($requestUri, $baseDir) === 0) {
            $requestUri = substr($requestUri, strlen($baseDir));
        }

        // Strip scriptName if directly accessing /index.php/...
        if (strpos($requestUri, '/index.php') === 0) {
            $requestUri = substr($requestUri, strlen('/index.php'));
        }

        $cleanUri = '/' . trim($requestUri, '/');
        return $cleanUri === '//' ? '/' : $cleanUri;
    }

    private function executeHandler($handler, array $params): void {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return;
        }

        if (is_string($handler) && strpos($handler, '@') !== false) {
            [$class, $method] = explode('@', $handler);
            $fullClass = 'App\\Controllers\\' . $class;
            if (!class_exists($fullClass)) {
                die("Controller class not found: " . htmlspecialchars($fullClass));
            }
            $controller = new $fullClass();
            if (!method_exists($controller, $method)) {
                die("Controller method not found: " . htmlspecialchars($fullClass . '@' . $method));
            }
            call_user_func_array([$controller, $method], $params);
            return;
        }

        die("Invalid route handler configured.");
    }

    private function handle404(string $uri): void {
        http_response_code(404);
        $user = Auth::user();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 — Endpoint Not Found | CyberShield</title>
            <link rel="stylesheet" href="<?= ViewHelper::asset('css/bootstrap.min.css') ?>">
            <link rel="stylesheet" href="<?= ViewHelper::asset('css/bootstrap-icons.min.css') ?>">
            <link rel="stylesheet" href="<?= ViewHelper::asset('css/cybershield.css') ?>">
        </head>
        <body class="bg-navy text-light d-flex align-items-center justify-content-center min-vh-100">
            <div class="card bg-surface border-cyan-subtle shadow-lg p-5 text-center" style="max-width: 540px;">
                <div class="display-1 text-cyan mb-3"><i class="bi bi-shield-slash"></i></div>
                <h1 class="h3 fw-bold text-light mb-2">404: Endpoint Not Found</h1>
                <p class="text-secondary small mb-4">The requested target <code class="text-cyan"><?= htmlspecialchars($uri) ?></code> does not exist on this CyberShield node.</p>
                <div>
                    <a href="<?= ViewHelper::url($user ? ($user['role'] === 'student' ? 'student/dashboard' : 'dashboard') : 'login') ?>" class="btn btn-outline-cyan px-4">
                        <i class="bi bi-arrow-left me-1"></i> Return to CyberShield
                    </a>
                </div>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
}
