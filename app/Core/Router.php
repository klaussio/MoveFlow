<?php
/**
 * Router — Simple URL routing system
 */
class Router
{
    private static array $routes = [];

    /**
     * Register a GET route
     */
    public static function get(string $path, string $controller, string $method): void
    {
        self::$routes['GET'][$path] = ['controller' => $controller, 'method' => $method];
    }

    /**
     * Register a POST route
     */
    public static function post(string $path, string $controller, string $method): void
    {
        self::$routes['POST'][$path] = ['controller' => $controller, 'method' => $method];
    }

    /**
     * Dispatch the current request to the appropriate controller
     */
    public static function dispatch(): void
    {
        $url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';
        $httpMethod = $_SERVER['REQUEST_METHOD'];

        // Check for exact match first
        if (isset(self::$routes[$httpMethod][$url])) {
            $route = self::$routes[$httpMethod][$url];
            self::callAction($route['controller'], $route['method']);
            return;
        }

        // Check for parameterized routes
        foreach (self::$routes[$httpMethod] ?? [] as $pattern => $route) {
            $regex = self::patternToRegex($pattern);
            if (preg_match($regex, $url, $matches)) {
                array_shift($matches); // Remove full match
                self::callAction($route['controller'], $route['method'], $matches);
                return;
            }
        }

        // 404
        http_response_code(404);
        if (self::isAjax()) {
            Response::json(['error' => 'Route not found'], 404);
        } else {
            echo '<h1>404 — Page Not Found</h1>';
        }
    }

    /**
     * Convert route pattern to regex (e.g., "rides/{id}" → "^rides/([^/]+)$")
     */
    private static function patternToRegex(string $pattern): string
    {
        $regex = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $pattern);
        return '#^' . $regex . '$#';
    }

    /**
     * Instantiate controller and call method
     */
    private static function callAction(string $controller, string $method, array $params = []): void
    {
        if (!class_exists($controller)) {
            throw new RuntimeException("Controller {$controller} not found");
        }

        $instance = new $controller();

        if (!method_exists($instance, $method)) {
            throw new RuntimeException("Method {$method} not found in {$controller}");
        }

        call_user_func_array([$instance, $method], $params);
    }

    /**
     * Check if request is AJAX
     */
    private static function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
