<?php

/**
 * Class App
 * 
 * Core application class for the Football Pitch Booking Management System.
 * 
 * Responsibilities:
 * - Parse incoming URLs
 * - Match the URL to routes defined in `Routes.php`
 * - Instantiate the appropriate controller
 * - Call the appropriate method on the controller with any parameters
 * 
 * This class serves as the central router and dispatcher of the application.
 * 
 * @package Core
 * @author  Amos Nyirenda
 * @version 1.0
 */
class App
{
    /**
     * The controller to be used for the current request.
     * Defaults to 'HomeController'.
     * 
     * @var string|BaseController
     */
    protected string|BaseController $controller = 'BookController';

    /**
     * The method of the controller to call.
     * Defaults to 'index'.
     * 
     * @var string
     */
    protected string $method = 'index';

    /**
     * Parameters to pass to the controller method.
     * 
     * @var array
     */
    protected array $params = [];

    /**
     * App constructor.
     * 
     * Parses the URL, matches it to routes, instantiates the controller,
     * and calls the controller method with any parameters.
     */
    public function __construct()
    {
        $urlParts = $this->parseUrl();
        
        // Charger les routes
        require_once 'Routes.php';
        
        // Construire l'URL complète
        $url = implode('/', $urlParts);
        
        // Chercher une correspondance exacte d'abord
        if (isset($routes[$url])) {
            $this->controller = $routes[$url]['controller'];
            $this->method = $routes[$url]['method'];
            $this->params = [];
        } 
        // Sinon, chercher une correspondance avec paramètres dynamiques
        else {
            $matched = false;
            
            foreach ($routes as $route => $config) {
                // Si la route contient {id} ou autre paramètre dynamique
                if (strpos($route, '{') !== false) {
                    // Convertir la route en pattern regex
                    // Exemple: 'gerant/terrain/{id}' devient '#^gerant/terrain/([^/]+)$#'
                    $pattern = $this->convertRouteToRegex($route);
                    
                    // Tester si l'URL correspond au pattern
                    if (preg_match($pattern, $url, $matches)) {
                        $this->controller = $config['controller'];
                        $this->method = $config['method'];
                        
                        // Récupérer les paramètres capturés (sans le match complet)
                        array_shift($matches);
                        $this->params = $matches;
                        
                        $matched = true;
                        break;
                    }
                }
            }
            
            // Si aucune route ne correspond
            if (!$matched) {
                http_response_code(404);
                echo "404 - Route Not Found!";
                exit;
            }
        }
        
        // Charger le controller
        $controllerFile = __DIR__ . '/../controllers/' . $this->controller . '.php';
        
        if (!file_exists($controllerFile)) {
            http_response_code(404);
            echo "404 - Controller Not Found: " . $this->controller;
            exit;
        }
        
        require_once $controllerFile;
        
        // Instancier le controller
        $this->controller = new $this->controller;
        
        // Vérifier que la méthode existe
        if (!method_exists($this->controller, $this->method)) {
            http_response_code(404);
            echo "404 - Method Not Found: " . $this->method;
            exit;
        }
        
        // Appeler la méthode avec les paramètres
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Parses the URL from the GET request and returns its components as an array.
     * 
     * Example:
     * - URL: /reservation/id/5
     * - Returns: ['reservation', 'id', '5']
     * 
     * @return array Array of URL components
     */
    private function parseUrl(): array
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }

        return [''];
    }

    /**
     * Convertit une route avec paramètres dynamiques en pattern regex
     * 
     * Exemple:
     * - Input: 'gerant/terrain/{id}'
     * - Output: '#^gerant/terrain/([^/]+)$#'
     * 
     * @param string $route La route à convertir
     * @return string Le pattern regex
     */
    private function convertRouteToRegex(string $route): string
    {
        // Remplacer {param} par ([^/]+) pour capturer n'importe quelle valeur
        $pattern = preg_replace('/\{[a-zA-Z_]+\}/', '([^/]+)', $route);
        
        // Ajouter les délimiteurs et ancres regex
        return '#^' . $pattern . '$#';
    }
}

?>