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

       
            require_once 'Routes.php';

            

            
            $route = $urlParts[0] ?? '';

            

            if (isset($urlParts[1])) {
                $route = $urlParts[0] . '/' . $urlParts[1];
            }

            

            if (isset($routes[$route])) {
                $this->controller = $routes[$route]['controller'];
              
                $this->method = $routes[$route]['method'];
                
                $this->params = array_slice($urlParts, 2);
                
            } else {
            
                http_response_code(404);
                echo "404 - Route Not Found!";
                exit;
            }

            
            
            require_once  __DIR__ . '/../controllers/' . $this->controller . '.php';;

            
            $this->controller = new $this->controller;

        

            
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


    // private function handleError(int $code): void
    // {
    //     http_response_code($code);
    //     $errorPage = __DIR__ . "/../views/errors/{$code}.php";
    //     if (file_exists($errorPage)) {
    //         require $errorPage;
    //     } else {
    //         echo "$code - Error";
    //     }
    //     exit;
    // }
}

?>
