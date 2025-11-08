<?php

/**
 * Class TerrainController
 * 
 * Handles all terrain-related operations with role-based access control.
 * 
 * Access Levels:
 * - Public: View all terrains (index, show)
 * - Admin: Create, delete terrains
 * - Gerant: Update their assigned terrain and manage options
 * 
 * @package Controllers
 * @author  Jihane Chouhe
 * @version 1.1
 */
class TerrainController extends BaseController
{
    /**
     * @var object|null Current authenticated user (from JWT)
     */
    private ?object $currentUser = null;

    /**
     * @var TerrainModel Terrain model instance
     */
    private TerrainModel $terrainModel;

    /**
     * @var OptionSupplementaireModel Option model instance
     */
    private OptionSupplementaireModel $optionModel;

    /**
     * Constructor - Initialize models and check authentication
     */
    public function __construct()
    {
        /** @var TerrainModel $terrainModel */
        $this->terrainModel = $this->loadModel('TerrainModel');
        
        /** @var OptionSupplementaireModel $optionModel */
        $this->optionModel = $this->loadModel('OptionSupplementaireModel');
        
        // Check if user is authenticated (optional for public routes)
        $token = Utils::getCookieValue('auth_token');
        if ($token) {
            $decoded = Utils::verifyJWT($token, JWT_SECRET_KEY);
            if ($decoded !== false) {
                $this->currentUser = $decoded;
            }
        }
    }

    /**
     * Display all terrains (Public access)
     * 
     * @return void
     */
    public function index(): void
    {
        // Get filter parameters
        $ville = $_GET['ville'] ?? '';
        $type = $_GET['type'] ?? '';
        $taille = $_GET['taille'] ?? '';

        $filters = [
            'ville' => $ville,
            'type' => $type,
            'taille' => $taille
        ];

        $terrains = $this->terrainModel->getAll($filters);
        $villes = $this->terrainModel->getDistinctVilles();

        $this->renderView('Terrain/Index', [
            'terrains' => $terrains ?: [],
            'villes' => $villes ?: [],
            'filters' => $filters,
            'currentUser' => $this->currentUser
        ], 'Nos Terrains');
    }

    /**
     * Display single terrain details (Public access)
     * 
     * @param string $id Terrain ID
     * @return void
     */
    public function show(string $id): void
    {
        $terrain = $this->terrainModel->getById($id);
        
        if (!$terrain) {
            $this->renderView('Errors/404', [], '404 - Terrain non trouvé');
            return;
        }

        // Get options supplementaires for this terrain
        $options = $this->optionModel->getByTerrainId($id);

        $this->renderView('Terrain/Show', [
            'terrain' => $terrain,
            'options' => $options ?: [],
            'currentUser' => $this->currentUser
        ], is_object($terrain) ? $terrain->nom_terrain : ($terrain['nom_terrain'] ?? 'Terrain'));
    }

    /**
     * Show create form (Admin only)
     * Handles both GET (display form) and POST (submit form)
     * 
     * @return void
     */
/**
 * Show create form (Admin only)
 * Handles both GET (display form) and POST (submit form)
 * 
 * @return void
 */
public function create(): void
{
    // Check authentication and admin role
    if (!$this->currentUser || $this->currentUser->role !== 'admin') {
        http_response_code(403);
        $this->renderView('Errors/403', [], 'Accès interdit');
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Load gerants for assignment
        /** @var UserModel $userModel */
        $userModel = $this->loadModel('UserModel');
        $gerants = $userModel->getAllByRole('gerant_terrain');

        $this->renderView('Terrain/Create', [
            'gerants' => $gerants ?: [],
            'currentUser' => $this->currentUser
        ], 'Ajouter un Terrain');
        return;
    }

    // Handle POST request
    $nom_terrain = trim($_POST['nom_terrain'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    $taille = $_POST['taille'] ?? '';
    $type = $_POST['type'] ?? '';
    $prix_heure = floatval($_POST['prix_heure'] ?? 0);
    $id_gerant = !empty($_POST['id_gerant']) ? intval($_POST['id_gerant']) : null;

    // Validation
    $errors = [];
    
    if (empty($nom_terrain)) $errors[] = "Le nom du terrain est obligatoire";
    if (empty($adresse)) $errors[] = "L'adresse est obligatoire";
    if (empty($ville)) $errors[] = "La ville est obligatoire";
    if (empty($taille)) $errors[] = "La taille est obligatoire";
    if (empty($type)) $errors[] = "Le type est obligatoire";
    if ($prix_heure <= 0) $errors[] = "Le prix doit être supérieur à 0";

    if (!empty($errors)) {
        /** @var UserModel $userModel */
        $userModel = $this->loadModel('UserModel');
        $gerants = $userModel->getAllByRole('gerant_terrain');
        
        $this->renderView('Terrain/Create', [
            'errors' => $errors,
            'gerants' => $gerants ?: [],
            'formData' => $_POST,
            'currentUser' => $this->currentUser
        ], 'Ajouter un Terrain');
        return;
    }

    // Create terrain
    $data = [
        'nom_terrain' => htmlspecialchars($nom_terrain),
        'adresse' => htmlspecialchars($adresse),
        'ville' => htmlspecialchars($ville),
        'taille' => $taille,
        'type' => $type,
        'prix_heure' => $prix_heure,
        'id_utilisateur' => $id_gerant
    ];

    if ($this->terrainModel->add($data)) {
        $_SESSION['success'] = "Terrain ajouté avec succès !";
        // CORRECTION : Utiliser UrlHelper au lieu de redirection absolue
        UrlHelper::redirect('admin/terrains');
    } else {
        $errors[] = "Erreur lors de l'ajout du terrain";
        /** @var UserModel $userModel */
        $userModel = $this->loadModel('UserModel');
        $gerants = $userModel->getAllByRole('gerant_terrain');
        
        $this->renderView('Terrain/Create', [
            'errors' => $errors,
            'gerants' => $gerants ?: [],
            'formData' => $_POST,
            'currentUser' => $this->currentUser
        ], 'Ajouter un Terrain');
    }
}

    /**
     * Show edit form (Gerant for their terrain, Admin for all)
     * 
     * @param string $id Terrain ID
     * @return void
     */
    public function edit(string $id): void
    {
        // Check authentication
        if (!$this->currentUser) {
            http_response_code(403);
            $this->renderView('Errors/403', [], 'Accès interdit');
            return;
        }

        $terrain = $this->terrainModel->getById($id);
        
        if (!$terrain) {
            $this->renderView('Errors/404', [], '404 - Terrain non trouvé');
            return;
        }

        // Check permissions
        $isAdmin = $this->currentUser->role === 'admin';
        
        // Support terrain returned as object or array
        $terrainOwnerId = is_object($terrain) ? $terrain->id_utilisateur : 
                         (is_array($terrain) ? ($terrain['id_utilisateur'] ?? null) : null);
        
        $isGerant = $this->currentUser->role === 'gerant_terrain' && 
                    $terrainOwnerId == $this->currentUser->user_id;

        if (!$isAdmin && !$isGerant) {
            http_response_code(403);
            $this->renderView('Errors/403', [], 'Accès interdit');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $options = $this->optionModel->getByTerrainId($id);
            
            $this->renderView('Terrain/Edit', [
                'terrain' => $terrain,
                'options' => $options ?: [],
                'isAdmin' => $isAdmin,
                'currentUser' => $this->currentUser
            ], 'Modifier le Terrain');
            return;
        }

        // Handle POST request
        $errors = [];
        $updateData = [];

        // Gerants can only update certain fields
        if ($isGerant) {
            $updateData = [
                'nom_terrain' => htmlspecialchars(trim($_POST['nom_terrain'] ?? '')),
                'adresse' => htmlspecialchars(trim($_POST['adresse'] ?? '')),
                'prix_heure' => floatval($_POST['prix_heure'] ?? 0)
            ];

            if (empty($updateData['nom_terrain'])) $errors[] = "Le nom est obligatoire";
            if (empty($updateData['adresse'])) $errors[] = "L'adresse est obligatoire";
            if ($updateData['prix_heure'] <= 0) $errors[] = "Le prix doit être supérieur à 0";
        } 
        // Admins can update all fields
        else if ($isAdmin) {
            $updateData = [
                'nom_terrain' => htmlspecialchars(trim($_POST['nom_terrain'] ?? '')),
                'adresse' => htmlspecialchars(trim($_POST['adresse'] ?? '')),
                'ville' => htmlspecialchars(trim($_POST['ville'] ?? '')),
                'taille' => $_POST['taille'] ?? '',
                'type' => $_POST['type'] ?? '',
                'prix_heure' => floatval($_POST['prix_heure'] ?? 0),
                'id_utilisateur' => !empty($_POST['id_gerant']) ? intval($_POST['id_gerant']) : null
            ];

            if (empty($updateData['nom_terrain'])) $errors[] = "Le nom est obligatoire";
            if (empty($updateData['adresse'])) $errors[] = "L'adresse est obligatoire";
            if (empty($updateData['ville'])) $errors[] = "La ville est obligatoire";
            if ($updateData['prix_heure'] <= 0) $errors[] = "Le prix doit être supérieur à 0";
        }

        if (!empty($errors)) {
            $options = $this->optionModel->getByTerrainId($id);
            
            $this->renderView('Terrain/Edit', [
                'terrain' => $terrain,
                'options' => $options ?: [],
                'errors' => $errors,
                'isAdmin' => $isAdmin,
                'currentUser' => $this->currentUser
            ], 'Modifier le Terrain');
            return;
        }

        if ($this->terrainModel->update($id, $updateData)) {
            $_SESSION['success'] = "Terrain modifié avec succès !";
            header("Location: /terrain/id/$id");
            exit;
        } else {
            $errors[] = "Erreur lors de la modification";
            $options = $this->optionModel->getByTerrainId($id);
            
            $this->renderView('Terrain/Edit', [
                'terrain' => $terrain,
                'options' => $options ?: [],
                'errors' => $errors,
                'isAdmin' => $isAdmin,
                'currentUser' => $this->currentUser
            ], 'Modifier le Terrain');
        }
    }

    /**
     * Delete terrain (Admin only)
     * Returns JSON response
     * 
     * @param string $id Terrain ID
     * @return void
     */
    public function delete(string $id): void
    {
        // Check authentication and admin role
        if (!$this->currentUser || $this->currentUser->role !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès interdit']);
            return;
        }

        if ($this->terrainModel->delete($id)) {
            $_SESSION['success'] = "Terrain supprimé avec succès !";
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }

    /**
     * Manage options supplementaires (Gerant only for their terrain)
     * 
     * @param string $terrainId Terrain ID
     * @return void
     */
    public function manageOptions(string $terrainId): void
    {
        // Check authentication
        if (!$this->currentUser || $this->currentUser->role !== 'gerant_terrain') {
            http_response_code(403);
            $this->renderView('Errors/403', [], 'Accès interdit');
            return;
        }

        $terrain = $this->terrainModel->getById($terrainId);
        
        if (!$terrain) {
            $this->renderView('Errors/404', [], '404 - Terrain non trouvé');
            return;
        }

        // Support terrain returned as object or array
        $terrainOwnerId = is_object($terrain) ? $terrain->id_utilisateur : 
                         (is_array($terrain) ? ($terrain['id_utilisateur'] ?? null) : null);
        
        if ($terrainOwnerId != $this->currentUser->user_id) {
            http_response_code(403);
            $this->renderView('Errors/403', [], 'Accès interdit');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $options = $this->optionModel->getByTerrainId($terrainId);
            
            $this->renderView('Terrain/ManageOptions', [
                'terrain' => $terrain,
                'options' => $options ?: [],
                'currentUser' => $this->currentUser
            ], 'Gérer les Options');
            return;
        }

        // Handle POST - Add new option
        $nom_option = trim($_POST['nom_option'] ?? '');
        $prix = floatval($_POST['prix'] ?? 0);

        $errors = [];
        if (empty($nom_option)) $errors[] = "Le nom de l'option est obligatoire";
        if ($prix < 0) $errors[] = "Le prix ne peut pas être négatif";

        if (!empty($errors)) {
            $options = $this->optionModel->getByTerrainId($terrainId);
            
            $this->renderView('Terrain/ManageOptions', [
                'terrain' => $terrain,
                'options' => $options ?: [],
                'errors' => $errors,
                'currentUser' => $this->currentUser
            ], 'Gérer les Options');
            return;
        }

        $data = [
            'id_terrain' => $terrainId,
            'nom_option' => htmlspecialchars($nom_option),
            'prix' => $prix
        ];

        if ($this->optionModel->add($data)) {
            $_SESSION['success'] = "Option ajoutée avec succès !";
            header("Location: /terrain/options/$terrainId");
            exit;
        } else {
            $errors[] = "Erreur lors de l'ajout de l'option";
            $options = $this->optionModel->getByTerrainId($terrainId);
            
            $this->renderView('Terrain/ManageOptions', [
                'terrain' => $terrain,
                'options' => $options ?: [],
                'errors' => $errors,
                'currentUser' => $this->currentUser
            ], 'Gérer les Options');
        }
    }

    /**
     * Delete option supplementaire (Gerant only)
     * Returns JSON response
     * 
     * @param string $optionId Option ID
     * @return void
     */
    public function deleteOption(string $optionId): void
    {
        if (!$this->currentUser || $this->currentUser->role !== 'gerant_terrain') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès interdit']);
            return;
        }

        $option = $this->optionModel->getById($optionId);
        
        if (!$option) {
            echo json_encode(['success' => false, 'message' => 'Option non trouvée']);
            return;
        }

        // Verify the gerant owns the terrain
        $terrain = $this->terrainModel->getById(is_object($option) ? $option->id_terrain : $option['id_terrain']);
        
        if (!$terrain) {
            echo json_encode(['success' => false, 'message' => 'Terrain non trouvé']);
            return;
        }

        // Support terrain returned as object or array
        $terrainOwnerId = is_object($terrain) ? $terrain->id_utilisateur : 
                         (is_array($terrain) ? ($terrain['id_utilisateur'] ?? null) : null);
        
        if ($terrainOwnerId != $this->currentUser->user_id) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès interdit']);
            return;
        }

        if ($this->optionModel->delete($optionId)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
        }
    }
}