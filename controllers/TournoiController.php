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
 * - Admin: Full CRUD operations (future implementation)
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
     * Display single tournament details (Not implemented)
     * 
     * @param string $id Tournament ID
     * @return void
     */
    public function show(string $id): void
    {
        // TODO: Implement if needed
    }
    
    /**
     * Show create form (Not implemented - Admin only)
     * 
     * @return void
     */
    public function create(): void
    {
        // TODO: Implement if needed
    }
    
    /**
     * Show edit form (Not implemented - Admin only)
     * 
     * @param string $id Tournament ID
     * @return void
     */
    public function edit(string $id): void
    {
        // TODO: Implement if needed
    }
    
    /**
     * Delete tournament (Not implemented - Admin only)
     * 
     * @param string $id Tournament ID
     * @return void
     */
    public function delete(string $id): void
    {
        // TODO: Implement if needed
    }
    
    // ============================================================
    // TOURNAMENT-SPECIFIC METHODS
    // ============================================================
    
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