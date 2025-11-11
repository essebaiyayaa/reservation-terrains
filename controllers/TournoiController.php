<?php

require_once __DIR__ . '/../models/TournoiModel.php';

/**
 * Class TournoiController
 * 
 * Handles all tournament-related operations with role-based access control.
 * 
 * Access Levels:
 * - Public: View all available tournaments (index)
 * - Client: Register for tournaments, view participations
 * - Gerant: Full CRUD operations on their tournaments
 * - Admin: View all tournaments
 * 
 * @package Controllers
 * @extends BaseController
 */
class TournoiController extends BaseController
{
    /**
     * @var object|null Current authenticated user (from JWT)
     */
    private ?object $currentUser = null;

    /**
     * @var TournoiModel Tournament model instance
     */
    private TournoiModel $tournoiModel;
    
    /**
     * Constructor - Initialize models and check authentication
     */
    public function __construct()
    {
        $this->tournoiModel = new TournoiModel();
        
        // Check if user is authenticated (optional for public routes)
        $token = $_COOKIE['auth_token'] ?? null;
        if ($token) {
            try {
                $decoded = Utils::verifyJWT($token, JWT_SECRET_KEY);
                if ($decoded !== null) {
                    $this->currentUser = $decoded;
                }
            } catch (Exception $e) {
                $this->currentUser = null;
            }
        }
    }
    
    // ============================================================
    // BASE CONTROLLER ABSTRACT METHODS IMPLEMENTATION
    // ============================================================
    
    /**
     * Display all available tournaments (Public access - requires authentication)
     * 
     * @return void
     */
    public function index(): void
    {
        // Check authentication
        if (!$this->currentUser) {
            $this->setFlashMessage("Vous devez être connecté pour voir les tournois.", "error");
            $this->redirect('login');
            return;
        }
        
        // Get all available tournaments
        $tournois = $this->tournoiModel->getTournoisDisponibles();
        
        // Check if client is already registered for each tournament
        foreach ($tournois as $tournoi) {
            $tournoi->dejainscrit = false;
            if ($this->currentUser->role === 'client') {
                $tournoi->dejainscrit = $this->tournoiModel->isClientDejaInscrit(
                    $tournoi->id_tournoi, 
                    $this->currentUser->user_id
                );
            }
        }
        
        $this->renderView('Tournoi/Liste', [
            'tournois' => $tournois,
            'currentUser' => $this->currentUser
        ], 'Tournois disponibles');
    }
    
    /**
     * Display single tournament details
     * 
     * @param string $id Tournament ID
     * @return void
     */
    public function show(string $id): void
    {
        if (!$this->currentUser) {
            $this->setFlashMessage("Vous devez être connecté.", "error");
            $this->redirect('login');
            return;
        }
        
        $tournoi = $this->tournoiModel->getTournoiById((int)$id);
        
        if (!$tournoi) {
            $this->setFlashMessage("Ce tournoi n'existe pas.", "error");
            $this->redirect('tournoi/index');
            return;
        }
        
        // Get teams registered for this tournament
        $equipes = $this->tournoiModel->getEquipesByTournoi((int)$id);
        
        $this->renderView('Tournoi/Details', [
            'tournoi' => $tournoi,
            'equipes' => $equipes,
            'currentUser' => $this->currentUser
        ], 'Détails du tournoi');
    }
    
    /**
     * Show create tournament form (Gerant only)
     * 
     * @return void
     */
    public function create(): void
    {
        // Check authentication and role
        if (!$this->currentUser || $this->currentUser->role !== 'gerant_terrain') {
            $this->setFlashMessage("Accès refusé. Seuls les gérants peuvent créer des tournois.", "error");
            $this->redirect('dashboard/index');
            return;
        }
        
        // GET request: Show form
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Get gerant's terrains
            require_once __DIR__ . '/../models/TerrainModel.php';
            $terrainModel = new TerrainModel();
            $mesTerrains = $terrainModel->getTerrainsByGerant($this->currentUser->user_id);
            
            if (empty($mesTerrains)) {
                $this->setFlashMessage("Vous devez avoir au moins un terrain pour créer un tournoi.", "error");
                $this->redirect('dashboard/gerant');
                return;
            }
            
            $this->renderView('Tournoi/Create', [
                'mesTerrains' => $mesTerrains,
                'currentUser' => $this->currentUser
            ], 'Créer un tournoi');
            return;
        }
        
        // POST request: Process form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
            return;
        }
    }
    
    /**
     * Store new tournament (Process POST from create form)
     * 
     * @return void
     */
    private function store(): void
    {
        // Validate gerant role
        if (!$this->currentUser || $this->currentUser->role !== 'gerant_terrain') {
            $this->setFlashMessage("Accès refusé.", "error");
            $this->redirect('login');
            return;
        }
        
        // Get and validate form data
        $nom_tournoi = trim($_POST['nom_tournoi'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $date_debut = $_POST['date_debut'] ?? '';
        $date_fin = $_POST['date_fin'] ?? '';
        $id_terrain = intval($_POST['id_terrain'] ?? 0);
        $nombre_max_equipes = intval($_POST['nombre_max_equipes'] ?? 8);
        $prix_inscription = floatval($_POST['prix_inscription'] ?? 0);
        
        // Validation
        $errors = [];
        
        if (empty($nom_tournoi)) {
            $errors[] = "Le nom du tournoi est obligatoire.";
        } elseif (strlen($nom_tournoi) < 3) {
            $errors[] = "Le nom du tournoi doit contenir au moins 3 caractères.";
        }
        
        if (empty($date_debut)) {
            $errors[] = "La date de début est obligatoire.";
        }
        
        if (empty($date_fin)) {
            $errors[] = "La date de fin est obligatoire.";
        }
        
        if ($date_debut && $date_fin && $date_debut > $date_fin) {
            $errors[] = "La date de fin doit être après la date de début.";
        }
        
        if ($id_terrain <= 0) {
            $errors[] = "Veuillez sélectionner un terrain.";
        }
        
        if ($nombre_max_equipes < 2 || $nombre_max_equipes > 32) {
            $errors[] = "Le nombre maximum d'équipes doit être entre 2 et 32.";
        }
        
        if ($prix_inscription < 0) {
            $errors[] = "Le prix d'inscription ne peut pas être négatif.";
        }
        
        // Check if terrain belongs to gerant
        require_once __DIR__ . '/../models/TerrainModel.php';
        $terrainModel = new TerrainModel();
        $terrain = $terrainModel->getById((string)$id_terrain);
        
        if (!$terrain || (is_object($terrain) && (int)$terrain->id_utilisateur != (int)$this->currentUser->user_id)) {
            $errors[] = "Ce terrain ne vous appartient pas.";
        }
        
        if (!empty($errors)) {
            $this->setFlashMessage(implode(' ', $errors), "error");
            $this->redirect('tournoi/create');
            return;
        }
        
        // Prepare data
        $data = [
            'nom_tournoi' => $nom_tournoi,
            'description' => $description,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'id_terrain' => $id_terrain,
            'id_gerant' => $this->currentUser->user_id,
            'nombre_max_equipes' => $nombre_max_equipes,
            'prix_inscription' => $prix_inscription,
            'statut' => 'En préparation'
        ];
        
        // Create tournament
        if ($this->tournoiModel->creerTournoi($data)) {
            $this->setFlashMessage("Tournoi « $nom_tournoi » créé avec succès !", "success");
            $this->redirect('tournoi/mestournois');
        } else {
            $this->setFlashMessage("Erreur lors de la création du tournoi.", "error");
            $this->redirect('tournoi/create');
        }
    }
    
    /**
     * Show edit tournament form (Gerant only)
     * 
     * @param string $id Tournament ID
     * @return void
     */
    public function edit(string $id): void
    {
        // Check authentication and role
        if (!$this->currentUser || $this->currentUser->role !== 'gerant_terrain') {
            $this->setFlashMessage("Accès refusé.", "error");
            $this->redirect('dashboard/index');
            return;
        }
        
        $tournoi = $this->tournoiModel->getTournoiById((int)$id);
        
        if (!$tournoi) {
            $this->setFlashMessage("Ce tournoi n'existe pas.", "error");
            $this->redirect('tournoi/mestournois');
            return;
        }
        
        // Check if gerant owns this tournament
        if ($tournoi->id_gerant != $this->currentUser->user_id) {
            $this->setFlashMessage("Vous n'êtes pas autorisé à modifier ce tournoi.", "error");
            $this->redirect('tournoi/mestournois');
            return;
        }
        
        // GET request: Show form
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Get gerant's terrains
            require_once __DIR__ . '/../models/TerrainModel.php';
            $terrainModel = new TerrainModel();
            $mesTerrains = $terrainModel->getTerrainsByGerant($this->currentUser->user_id);
            
            $this->renderView('Tournoi/Edit', [
                'tournoi' => $tournoi,
                'mesTerrains' => $mesTerrains,
                'currentUser' => $this->currentUser
            ], 'Modifier le tournoi');
            return;
        }
        
        // POST request: Process update
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processUpdate($id);
            return;
        }
    }
    
    /**
     * Process tournament update
     * 
     * @param string $id Tournament ID
     * @return void
     */
    private function processUpdate(string $id): void
    {
        // Get and validate form data
        $nom_tournoi = trim($_POST['nom_tournoi'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $date_debut = $_POST['date_debut'] ?? '';
        $date_fin = $_POST['date_fin'] ?? '';
        $id_terrain = intval($_POST['id_terrain'] ?? 0);
        $nombre_max_equipes = intval($_POST['nombre_max_equipes'] ?? 8);
        $prix_inscription = floatval($_POST['prix_inscription'] ?? 0);
        $statut = $_POST['statut'] ?? 'En préparation';
        
        // Validation
        $errors = [];
        
        if (empty($nom_tournoi)) {
            $errors[] = "Le nom du tournoi est obligatoire.";
        }
        
        if ($date_debut && $date_fin && $date_debut > $date_fin) {
            $errors[] = "La date de fin doit être après la date de début.";
        }
        
        if (!empty($errors)) {
            $this->setFlashMessage(implode(' ', $errors), "error");
            $this->redirect('tournoi/edit/' . $id);
            return;
        }
        
        // Prepare data
        $data = [
            'nom_tournoi' => $nom_tournoi,
            'description' => $description,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'id_terrain' => $id_terrain,
            'nombre_max_equipes' => $nombre_max_equipes,
            'prix_inscription' => $prix_inscription,
            'statut' => $statut
        ];
        
        // Update tournament
        if ($this->tournoiModel->modifierTournoi((int)$id, $data)) {
            $this->setFlashMessage("Tournoi mis à jour avec succès !", "success");
            $this->redirect('tournoi/mestournois');
        } else {
            $this->setFlashMessage("Erreur lors de la mise à jour.", "error");
            $this->redirect('tournoi/edit/' . $id);
        }
    }
    
    /**
     * Delete/Cancel tournament (Gerant only)
     * 
     * @param string $id Tournament ID
     * @return void
     */
    public function delete(string $id): void
    {
        // Check authentication and role
        if (!$this->currentUser || $this->currentUser->role !== 'gerant_terrain') {
            $this->setFlashMessage("Accès refusé.", "error");
            $this->redirect('dashboard/index');
            return;
        }
        
        $tournoi = $this->tournoiModel->getTournoiById((int)$id);
        
        if (!$tournoi) {
            $this->setFlashMessage("Ce tournoi n'existe pas.", "error");
            $this->redirect('tournoi/mestournois');
            return;
        }
        
        // Check ownership
        if ($tournoi->id_gerant != $this->currentUser->user_id) {
            $this->setFlashMessage("Vous n'êtes pas autorisé à supprimer ce tournoi.", "error");
            $this->redirect('tournoi/mestournois');
            return;
        }
        
        // Cancel tournament instead of deleting
        if ($this->tournoiModel->annulerTournoi((int)$id)) {
            $this->setFlashMessage("Tournoi annulé avec succès.", "success");
        } else {
            $this->setFlashMessage("Erreur lors de l'annulation.", "error");
        }
        
        $this->redirect('tournoi/mestournois');
    }
    
    // ============================================================
    // TOURNAMENT-SPECIFIC METHODS
    // ============================================================
    
    /**
     * Display gerant's tournaments with participants (Gerant only)
     * URL: /tournoi/mestournois
     * 
     * @return void
     */
    public function mestournois(): void
    {
        // Check authentication and role
        if (!$this->currentUser || $this->currentUser->role !== 'gerant_terrain') {
            $this->setFlashMessage("Accès refusé. Cette page est réservée aux gérants.", "error");
            $this->redirect('dashboard/index');
            return;
        }
        
        // Get gerant's tournaments
        $mesTournois = $this->tournoiModel->getTournoisByGerant($this->currentUser->user_id);
        
        $this->renderView('Tournoi/MesTournois', [
            'mesTournois' => $mesTournois,
            'currentUser' => $this->currentUser
        ], 'Mes tournois');
    }
    
    /**
     * Display tournament registration form (Client only)
     * URL: /tournoi/participer?id=X
     * 
     * @return void
     */
    public function participer(): void
    {
        // Check authentication
        if (!$this->currentUser) {
            $this->setFlashMessage("Vous devez être connecté pour participer.", "error");
            $this->redirect('login');
            return;
        }
        
        // Check if user is a client
        if ($this->currentUser->role !== 'client') {
            $this->setFlashMessage("Seuls les clients peuvent participer aux tournois.", "error");
            $this->redirect('tournoi/index');
            return;
        }
        
        // Get tournament ID
        $idTournoi = $_GET['id'] ?? null;
        
        if (!$idTournoi) {
            $this->setFlashMessage("Tournoi non trouvé.", "error");
            $this->redirect('tournoi/index');
            return;
        }
        
        // Get tournament details
        $tournoi = $this->tournoiModel->getTournoiById($idTournoi);
        
        if (!$tournoi) {
            $this->setFlashMessage("Ce tournoi n'existe pas.", "error");
            $this->redirect('tournoi/index');
            return;
        }
        
        // Check if client is already registered
        if ($this->tournoiModel->isClientDejaInscrit($idTournoi, $this->currentUser->user_id)) {
            $this->setFlashMessage("Vous êtes déjà inscrit à ce tournoi.", "error");
            $this->redirect('tournoi/index');
            return;
        }
        
        // Check if places are available
        if (!$this->tournoiModel->hasPlacesDisponibles($idTournoi)) {
            $this->setFlashMessage("Ce tournoi est complet. Il n'y a plus de places disponibles.", "error");
            $this->redirect('tournoi/index');
            return;
        }
        
        $this->renderView('Tournoi/Participer', [
            'tournoi' => $tournoi,
            'currentUser' => $this->currentUser
        ], 'Participer au tournoi');
    }
    
    /**
     * Process tournament registration form submission (Client only)
     * URL: /tournoi/inscrire (POST)
     * 
     * @return void
     */
    public function inscrire(): void
    {
        // Check if POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('tournoi/index');
            return;
        }
        
        // Check authentication
        if (!$this->currentUser) {
            $this->setFlashMessage("Vous devez être connecté.", "error");
            $this->redirect('login');
            return;
        }
        
        // Check if user is a client
        if ($this->currentUser->role !== 'client') {
            $this->setFlashMessage("Seuls les clients peuvent participer aux tournois.", "error");
            $this->redirect('tournoi/index');
            return;
        }
        
        // Get form data
        $idTournoi = intval($_POST['id_tournoi'] ?? 0);
        $nomEquipe = trim($_POST['nom_equipe'] ?? '');
        $nombreJoueurs = intval($_POST['nombre_joueurs'] ?? 0);
        
        // Validation
        $errors = [];
        
        if (empty($nomEquipe)) {
            $errors[] = "Le nom de l'équipe est obligatoire.";
        } elseif (strlen($nomEquipe) < 3) {
            $errors[] = "Le nom de l'équipe doit contenir au moins 3 caractères.";
        } elseif (strlen($nomEquipe) > 100) {
            $errors[] = "Le nom de l'équipe ne doit pas dépasser 100 caractères.";
        }
        
        if ($nombreJoueurs < 5 || $nombreJoueurs > 11) {
            $errors[] = "Le nombre de joueurs doit être entre 5 et 11.";
        }
        
        if (!empty($errors)) {
            $this->setFlashMessage(implode(' ', $errors), "error");
            $this->redirect('tournoi/participer?id=' . $idTournoi);
            return;
        }
        
        // Check if tournament exists
        $tournoi = $this->tournoiModel->getTournoiById($idTournoi);
        if (!$tournoi) {
            $this->setFlashMessage("Ce tournoi n'existe pas.", "error");
            $this->redirect('tournoi/index');
            return;
        }
        
        // Check if client is already registered
        if ($this->tournoiModel->isClientDejaInscrit($idTournoi, $this->currentUser->user_id)) {
            $this->setFlashMessage("Vous êtes déjà inscrit à ce tournoi.", "error");
            $this->redirect('tournoi/index');
            return;
        }
        
        // Check if places are available
        if (!$this->tournoiModel->hasPlacesDisponibles($idTournoi)) {
            $this->setFlashMessage("Désolé, ce tournoi est maintenant complet.", "error");
            $this->redirect('tournoi/index');
            return;
        }
        
        // Prepare registration data
        $data = [
            'nom_equipe' => $nomEquipe,
            'id_responsable' => $this->currentUser->user_id,
            'id_tournoi' => $idTournoi,
            'nombre_joueurs' => $nombreJoueurs
        ];
        
        // Attempt registration
        if ($this->tournoiModel->inscrireEquipe($data)) {
            $this->setFlashMessage("Félicitations ! Votre équipe « $nomEquipe » a été inscrite avec succès au tournoi « {$tournoi->nom_tournoi} » !", "success");
            $this->redirect('tournoi/mesparticipations');
        } else {
            $this->setFlashMessage("Une erreur est survenue. Ce nom d'équipe existe peut-être déjà pour ce tournoi.", "error");
            $this->redirect('tournoi/participer?id=' . $idTournoi);
        }
    }
    
    /**
     * Display user's tournament participations (Client only)
     * URL: /tournoi/mesparticipations
     * 
     * @return void
     */
    public function mesparticipations(): void
    {
        // Check authentication
        if (!$this->currentUser) {
            $this->setFlashMessage("Vous devez être connecté.", "error");
            $this->redirect('login');
            return;
        }
        
        // Check if user is a client
        if ($this->currentUser->role !== 'client') {
            $this->setFlashMessage("Cette page est réservée aux clients.", "error");
            $this->redirect('dashboard/index');
            return;
        }
        
        // Get tournaments the client is participating in
        $mesTournois = $this->tournoiModel->getMesTournois($this->currentUser->user_id);
        
        $this->renderView('Tournoi/MesParticipations', [
            'mesTournois' => $mesTournois,
            'currentUser' => $this->currentUser
        ], 'Mes participations');
    }
    
    // ============================================================
    // HELPER METHODS
    // ============================================================
    
    /**
     * Set a flash message in session (temporary message for next page load)
     * Note: Uses minimal session only for flash messages, not for authentication
     * 
     * @param string $message The message to display
     * @param string $type The message type (success, error, info, warning)
     * @return void
     */
    private function setFlashMessage(string $message, string $type = 'info'): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION[$type] = $message;
    }
    
    /**
     * Redirect to another page
     * 
     * @param string $url The URL path to redirect to
     * @return void
     */
    private function redirect(string $url): void
    {
        header('Location: ' . UrlHelper::url($url));
        exit;
    }
}

?>