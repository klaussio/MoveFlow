<?php
/**
 * Base Controller — Shared functionality for all controllers
 */
class Controller
{
    /**
     * Render a view with layout
     */
    protected function view(string $view, array $data = [], bool $withLayout = true): void
    {
        extract($data);
        $viewFile = APP_PATH . '/Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            throw new RuntimeException("View {$view} not found");
        }

        if ($withLayout) {
            ob_start();
            require $viewFile;
            $content = ob_get_clean();
            require APP_PATH . '/Views/layouts/main.php';
        } else {
            require $viewFile;
        }
    }

    /**
     * Redirect to a URL
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . APP_URL . '/' . ltrim($url, '/'));
        exit;
    }

    /**
     * Send JSON response
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        Response::json($data, $statusCode);
    }

    /**
     * Require authentication
     */
    protected function requireAuth(): void
    {
        if (!Session::isLoggedIn()) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Unauthorized'], 401);
            } else {
                Session::setFlash('error', 'Você precisa estar logado para acessar esta página.');
                $this->redirect('login');
            }
            exit;
        }
    }

    /**
     * Get POST data with optional sanitization
     */
    protected function input(string $key, $default = null)
    {
        $value = $_POST[$key] ?? $_GET[$key] ?? $default;
        if (is_string($value)) {
            return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
        }
        return $value;
    }

    /**
     * Get raw POST data (for fields that need HTML)
     */
    protected function rawInput(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    /**
     * Validate CSRF token
     */
    protected function validateCsrf(): bool
    {
        $token = $_POST['_csrf_token'] ?? '';
        if (!Session::validateCsrf($token)) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Invalid CSRF token'], 403);
            } else {
                Session::setFlash('error', 'Token de segurança inválido. Tente novamente.');
                $this->redirect($_SERVER['HTTP_REFERER'] ?? '');
            }
            exit;
        }
        return true;
    }

    /**
     * Check if request is AJAX
     */
    protected function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
