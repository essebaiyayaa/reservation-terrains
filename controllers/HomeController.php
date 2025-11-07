<?php

/**
 * Class HomeController
 * 
 * Handles the main landing page
 * 
 * @package Controllers
 * @version 1.0
 * @author  Jihane Chouhe
 */
class HomeController extends BaseController
{
    private ?object $currentUser = null;

    public function __construct()
    {
        // Check if user is authenticated
        $token = Utils::getCookieValue('auth_token');
        if ($token) {
            $decoded = Utils::verifyJWT($token, JWT_SECRET_KEY);
            if ($decoded !== false) {
                $this->currentUser = $decoded;
            }
        }
    }

    /**
     * Display the home page
     */
    public function index(): void
    {
        $this->renderView('Home/Index', [
            'currentUser' => $this->currentUser
        ], 'FootBooking - Réservation de Terrains de Foot');
    }

    /**
     * Show a single resource (not applicable for home page)
     */
    public function show($id = null): void
    {
        // HomeController has no individual resource to show; return 404
        http_response_code(404);
        echo 'Not Found';
    }

    /**
     * Create a new resource (not applicable for home page)
     */
    public function create(): void
    {
        // Method not allowed for home controller
        http_response_code(405);
        header('Allow: GET');
        echo 'Method Not Allowed';
    }

    /**
     * Edit a resource (not applicable for home page)
     */
    public function edit($id = null): void
    {
        // Method not allowed for home controller
        http_response_code(405);
        header('Allow: GET');
        echo 'Method Not Allowed';
    }

    /**
     * Delete a resource (not applicable for home page)
     */
    public function delete($id = null): void
    {
        // Method not allowed for home controller
        http_response_code(405);
        header('Allow: GET');
        echo 'Method Not Allowed';
    }
}