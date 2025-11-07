<?php

/**
 * Class DashboardController
 * 
 * Handles role-based dashboard pages
 * 
 * @package Controllers
 * @version 1.0
 * @author  Jihane Chouhe
 */
class DashboardController extends BaseController
{
    private ?object $currentUser = null;

    public function __construct()
    {
        // Check authentication
        $token = Utils::getCookieValue('auth_token');
        if (!$token) {
            header('Location:' . SITE_URL .'/login');
            exit;
        }

        $decoded = Utils::verifyJWT($token, JWT_SECRET_KEY);
        if ($decoded === false) {
            header('Location:' . SITE_URL .'/login');
            exit;
        }

        $this->currentUser = $decoded;
    }

    /**
     * Admin Dashboard
     */
    public function admin(): void
    {
        // Check if user is admin
        if ($this->currentUser->role !== 'admin') {
            http_response_code(403);
            $this->renderView('Errors/403', [], 'Accès interdit');
            return;
        }

        // Load necessary data
        /** @var TerrainModel $terrainModel */
        $terrainModel = $this->loadModel('TerrainModel');
        /** @var UserModel $userModel */
        $userModel = $this->loadModel('UserModel');

        $terrains = $terrainModel->getAll();
        $users = $userModel->getAll();

        $this->renderView('Dashboard/Admin', [
            'currentUser' => $this->currentUser,
            'terrains' => $terrains,
            'users' => $users
        ], 'Dashboard Admin');
    }

    /**
     * Gerant Dashboard
     */
    public function gerant(): void
    {
        // Check if user is gerant
        if ($this->currentUser->role !== 'gerant_terrain') {
            http_response_code(403);
            $this->renderView('Errors/403', [], 'Accès interdit');
            return;
        }

        // Load gerant's terrain
        /** @var TerrainModel $terrainModel */
        $terrainModel = $this->loadModel('TerrainModel');
        
        $terrains = $terrainModel->getByGerantId($this->currentUser->user_id);

        $this->renderView('Dashboard/Gerant', [
            'currentUser' => $this->currentUser,
            'terrains' => $terrains
        ], 'Dashboard Gérant');
    }

    /**
     * Client Dashboard
     */
    public function client(): void
    {
        // Check if user is client
        if ($this->currentUser->role !== 'client') {
            http_response_code(403);
            $this->renderView('Errors/403', [], 'Accès interdit');
            return;
        }

        // TODO: Load client's reservations
        $this->renderView('Dashboard/Client', [
            'currentUser' => $this->currentUser
        ], 'Mes Réservations');
    }

    /**
     * Default index action: route to the dashboard that matches the current user's role.
     */
    public function index(): void
    {
        switch ($this->currentUser->role ?? '') {
            case 'admin':
                $this->admin();
                return;
            case 'gerant_terrain':
                $this->gerant();
                return;
            case 'client':
                $this->client();
                return;
            default:
                http_response_code(403);
                $this->renderView('Errors/403', [], 'Accès interdit');
                return;
        }
    }

    /**
     * Show a single resource (not implemented for dashboards).
     *
     * @param mixed $id
     */
    public function show($id): void
    {
        http_response_code(404);
        $this->renderView('Errors/404', [], 'Page non trouvée');
    }

    /**
     * Create resource (not supported on dashboard controller).
     */
    public function create(): void
    {
        http_response_code(405);
        $this->renderView('Errors/405', [], 'Méthode non autorisée');
    }

    /**
     * Edit resource (not supported on dashboard controller).
     *
     * @param mixed $id
     */
    public function edit($id): void
    {
        http_response_code(405);
        $this->renderView('Errors/405', [], 'Méthode non autorisée');
    }

    /**
     * Delete resource (not supported on dashboard controller).
     *
     * @param mixed $id
     */
    public function delete($id): void
    {
        http_response_code(405);
        $this->renderView('Errors/405', [], 'Méthode non autorisée');
    }
}