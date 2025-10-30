<?php

/**
 * Class BaseController
 * 
 * Abstract base class for all controllers in the Football Pitch Booking
 * Management System.
 * 
 * This class provides a consistent structure for handling HTTP requests, 
 * interacting with models, and rendering views. All controllers should
 * extend this class and implement the abstract CRUD methods.
 * 
 * Responsibilities:
 * - Define standard RESTful actions (index, show, create, edit, delete)
 * - Load models dynamically
 * - Render views with optional data and page title
 * 
 * @package Core\BaseController
 * @author  Amos Nyirenda
 * @version 1.0
 */
abstract class BaseController
{
    /**
     * Display a list of all resources.
     * 
     * Typically used for listing data on an index page.
     * 
     * @return void
     */
    abstract protected function index(): void;

    /**
     * Display a single resource by its unique ID.
     * 
     * @param string $id The unique identifier of the resource.
     * @return void
     */
    abstract protected function show(string $id): void;

    /**
     * Show the form for creating a new resource.
     * 
     * 
     * @return void
     */
    abstract protected function create(): void;

    /**
     * Show the form for editing an existing resource.
     * 
     * 
     * @param string $id The unique identifier of the resource to edit.
     * @return void
     */
    abstract protected function edit(string $id): void;

    /**
     * Delete a resource by its unique ID.
     * 
     * Usually responds to a POST or DELETE request.
     * 
     * @param string $id The unique identifier of the resource to delete.
     * @return void
     */
    abstract protected function delete(string $id): void;

    /**
     * Dynamically loads a model class.
     * 
     * @param string $model The name of the model class to load.
     * @return object       An instance of the requested model.
     */
    protected function loadModel(string $model)
    {
        require_once __DIR__ . "/../models/" . $model . '.php';
        return new $model;
    }

    /**
     * Renders a view with optional data and page title.
     * 
     * Extracts the data array into individual variables for use in the view.
     * 
     * @param string $viewName The name of the view file (without extension).
     * @param array  $data     Associative array of data to pass to the view.
     * @param string $title    Optional page title to display in the template.
     * @return void
     */
    protected function renderView(string $viewName, array $data = [], string $title = ""): void
    {
        extract($data);
        require_once __DIR__ . '/../views/main.php'; 
    }

     /**
     * Send JSON response.
     * 
     * @param array $data Response data.
     * @param int $statusCode HTTP status code.
     * @return void
     */

    protected function jsonResponse(array $data, int $statusCode = 200): void
{
    if (headers_sent() === false) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
}

?>
