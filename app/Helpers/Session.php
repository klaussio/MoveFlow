<?php
/**
 * Session — Wrapper for PHP session management
 */
class Session
{
    /**
     * Start session with secure settings
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_strict_mode', 1);
            ini_set('session.cookie_samesite', 'Lax');
            session_start();
        }
    }

    /**
     * Set a session value
     */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session value
     */
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if key exists
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a session value
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroy the session
     */
    public static function destroy(): void
    {
        session_unset();
        session_destroy();
    }

    /**
     * Set a flash message (available only on next request)
     */
    public static function setFlash(string $type, string $message): void
    {
        $_SESSION['_flash'][$type] = $message;
    }

    /**
     * Get and clear flash message
     */
    public static function getFlash(string $type): ?string
    {
        $message = $_SESSION['_flash'][$type] ?? null;
        unset($_SESSION['_flash'][$type]);
        return $message;
    }

    /**
     * Check if there are any flash messages
     */
    public static function hasFlash(string $type): bool
    {
        return isset($_SESSION['_flash'][$type]);
    }

    /**
     * Get all flash messages and clear them
     */
    public static function getAllFlash(): array
    {
        $messages = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $messages;
    }

    /**
     * Login user
     */
    public static function login(array $user): void
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        session_regenerate_id(true);
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Get logged-in user data
     */
    public static function user(): ?array
    {
        if (!self::isLoggedIn()) return null;
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role'],
        ];
    }

    /**
     * Generate CSRF token
     */
    public static function csrfToken(): string
    {
        if (!isset($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    /**
     * Validate CSRF token
     */
    public static function validateCsrf(string $token): bool
    {
        return hash_equals($_SESSION['_csrf_token'] ?? '', $token);
    }

    /**
     * Get CSRF hidden input field
     */
    public static function csrfField(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . self::csrfToken() . '">';
    }
}
