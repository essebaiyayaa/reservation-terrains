<?php

/**
 * Class Router
 * 
 *router for handling HTTP requests and dispatching them to appropriate
 * controllers and methods in the Football Pitch Booking Management System.
 * 
 * @package Core
 * @author  Aya 
 * @version 1.0
 */
class Router
{
    /**
     * @var array $routes Array of registered routes
     */
    private array $routes = [];

    /**
     * @var string $basePath Base path for the application
     */
    private string $basePath = '';

    /**
     * Router constructor.
     * 
     * @param string $basePath Optional base path for the application
     */
    public function __construct(string $basePath = '')
    {
        $this->basePath = $basePath;
    }

    /**
     * Register a GET route.
     * 
     * @param string $path The URL path
     * @param string $controller The controller class name
     * @param string $method The method to call
     * @return void
     */
    public function get(string $path, string $controller, string $method): void
    {
        $this->addRoute('GET', $path, $controller, $method);
    }

    /**
     * Register a POST route.
     * 
     * @param string $path The URL path
     * @param string $controller The controller class name
     * @param string $method The method to call
     * @return void
     */
    public function post(string $path, string $controller, string $method): void
    {
        $this->addRoute('POST', $path, $controller, $method);
    }

    /**
     * Add a route to the routes array.
     * 
     * @param string $httpMethod HTTP method (GET, POST, etc.)
     * @param string $path The URL path
     * @param string $controller The controller class name
     * @param string $method The method to call
     * @return void
     */
    private function addRoute(string $httpMethod, string $path, string $controller, string $method): void
    {
        $this->routes[] = [
            'http_method' => $httpMethod,
            'path' => $this->basePath . $path,
            'controller' => $controller,
            'method' => $method
        ];
    }

    /**
     * Dispatch the request to the appropriate controller and method.
     * 
     * @return void
     */
    public function dispatch(): void
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            // Check if HTTP method matches
            if ($route['http_method'] !== $requestMethod) {
                continue;
            }

            // Convert route path to regex pattern
            $pattern = $this->convertToRegex($route['path']);

            // Check if path matches
            if (preg_match($pattern, $requestUri, $matches)) {
                // Remove full match from matches array
                array_shift($matches);

                // Load controller
                $controllerFile = __DIR__ . '/../controllers/' . $route['controller'] . '.php';
                
                if (!file_exists($controllerFile)) {
                    $this->handleError(404, "Controller file not found: " . $route['controller']);
                    return;
                }

                require_once $controllerFile;

                // Instantiate controller
                if (!class_exists($route['controller'])) {
                    $this->handleError(500, "Controller class not found: " . $route['controller']);
                    return;
                }

                $controller = new $route['controller']();

                // Check if method exists
                if (!method_exists($controller, $route['method'])) {
                    $this->handleError(500, "Method not found: " . $route['method']);
                    return;
                }

                // Call controller method with parameters
                call_user_func_array([$controller, $route['method']], $matches);
                return;
            }
        }

        // No route matched
        $this->handleError(404, "Page not found");
    }

    /**
     * Convert route path to regex pattern.
     * 
     * Converts paths like /users/{id} to regex patterns.
     * 
     * @param string $path The route path
     * @return string The regex pattern
     */
    private function convertToRegex(string $path): string
    {
        // Escape forward slashes
        $pattern = str_replace('/', '\/', $path);
        
        // Replace {param} with regex capture group
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $pattern);
        
        // Add start and end anchors
        return '/^' . $pattern . '$/';
    }

    /**
     * Handle errors by displaying an error page.
     * 
     * @param int $code HTTP status code
     * @param string $message Error message
     * @return void
     */
    private function handleError(int $code, string $message): void
    {
        http_response_code($code);
        
        if (file_exists(__DIR__ . '/../views/errors/' . $code . '.php')) {
            require_once __DIR__ . '/../views/errors/' . $code . '.php';
        } else {
            echo "Error {$code}: {$message}";
        }
    }
}

?>