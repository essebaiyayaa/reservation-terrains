<?php

/**
 * Class GerantController
 * 
 * Handles all gerant-related operations including terrain and option management.
 * 
 * @package Controllers
 * @author  System
 * @version 1.0
 */
class GerantController extends BaseController {

    private ReservationModel $reservationModel;
    private TerrainModel $terrainModel;
    private OptionSupplementaireModel $optionModel;
    private ?object $currentUser = null;

    public function __construct()
    {
        // Vérifier l'authentification
        $token = Utils::getCookieValue('auth_token');
        if (!$token) {
            UrlHelper::redirect('login');
        }

        $decoded = Utils::verifyJWT($token, JWT_SECRET_KEY);
        if ($decoded === false || $decoded->role !== 'gerant_terrain') {
            http_response_code(403);
            $this->renderView('Errors/403', [], 'Accès interdit');
            exit;
        }
        $this->currentUser = $decoded;

        // Charger les modèles
        $this->reservationModel = $this->loadModel('ReservationModel');
        $this->terrainModel = $this->loadModel('TerrainModel');
        $this->optionModel = $this->loadModel('OptionSupplementaireModel');
    }

    /**
     * Dashboard principal du gérant
     */
    public function index(): void
    {
        $stats = [
            'total_reservations' => $this->reservationModel->getTotalCount(), 
            'reservations_aujourdhui' => $this->reservationModel->getTodaysReservations($this->currentUser->user_id),
            'ca_mois' => $this->reservationModel->getChiffreAffairesMoisGerant($this->currentUser->user_id)
        ];

        $gerant = [
            'prenom' => $this->currentUser->prenom,
            'nom' => $this->currentUser->nom,
            'email' => $this->currentUser->email,
            'role' => $this->currentUser->role
        ];

        // Récupérer les terrains du gérant
        $terrains = $this->terrainModel->getByGerantId($this->currentUser->user_id);

        $this->renderView('Gerant/Dashboard', [
            'currentUser' => $this->currentUser,
            'gerant' => $gerant,
            'stats' => $stats,
            'terrains' => $terrains
        ], 'Tableau de Bord Gérant');
    }

    /**
     * Liste tous les terrains du gérant
     */
    public function mesTerrains(): void
    {
        $terrains = $this->terrainModel->getByGerantId($this->currentUser->user_id);

        $this->renderView('Gerant/MesTerrains', [
            'terrains' => $terrains,
            'currentUser' => $this->currentUser
        ], 'Mes Terrains');
    }

    /**
     * Afficher les détails d'un terrain
     */
    public function show(string $id): void
    {
        $terrain = $this->terrainModel->getById($id);
        
        if (!$terrain) {
            $this->renderView('Errors/404', [], '404 - Terrain non trouvé');
            return;
        }

        // Vérifier que le terrain appartient au gérant
        $terrainOwnerId = is_object($terrain) ? $terrain->id_utilisateur : $terrain['id_utilisateur'];
        if ($terrainOwnerId != $this->currentUser->user_id) {
            http_response_code(403);
            $this->renderView('Errors/403', [], 'Accès interdit');
            return;
        }

        // Récupérer les options
        $options = $this->optionModel->getByTerrainId($id);

        $this->renderView('Gerant/TerrainDetails', [
            'terrain' => $terrain,
            'options' => $options ?: [],
            'currentUser' => $this->currentUser
        ], 'Détails du Terrain');
    }

    /**
     * Non utilisé pour gérant (créé par admin)
     */
    public function create(): void
    {
        http_response_code(403);
        $this->renderView('Errors/403', [], 'Accès interdit');
    }

    /**
     * Modifier un terrain du gérant
     */
    public function edit(string $id): void
    {
        $terrain = $this->terrainModel->getById($id);
        
        if (!$terrain) {
            $this->renderView('Errors/404', [], '404 - Terrain non trouvé');
            return;
        }

        // Vérifier propriété
        $terrainOwnerId = is_object($terrain) ? $terrain->id_utilisateur : $terrain['id_utilisateur'];
        if ($terrainOwnerId != $this->currentUser->user_id) {
            http_response_code(403);
            $this->renderView('Errors/403', [], 'Accès interdit');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->renderView('Gerant/EditTerrain', [
                'terrain' => $terrain,
                'currentUser' => $this->currentUser
            ], 'Modifier le Terrain');
            return;
        }

        // Traiter POST
        $updateData = [
            'nom_terrain' => htmlspecialchars(trim($_POST['nom_terrain'] ?? '')),
            'adresse' => htmlspecialchars(trim($_POST['adresse'] ?? '')),
            'prix_heure' => floatval($_POST['prix_heure'] ?? 0)
        ];

        $errors = [];
        if (empty($updateData['nom_terrain'])) $errors[] = "Le nom est obligatoire";
        if (empty($updateData['adresse'])) $errors[] = "L'adresse est obligatoire";
        if ($updateData['prix_heure'] <= 0) $errors[] = "Le prix doit être supérieur à 0";

        if (!empty($errors)) {
            $this->renderView('Gerant/EditTerrain', [
                'terrain' => $terrain,
                'errors' => $errors,
                'currentUser' => $this->currentUser
            ], 'Modifier le Terrain');
            return;
        }

        if ($this->terrainModel->update($id, $updateData)) {
            $_SESSION['success'] = "Terrain modifié avec succès !";
            UrlHelper::redirect("gerant/terrain/$id");
        } else {
            $errors[] = "Erreur lors de la modification";
            $this->renderView('Gerant/EditTerrain', [
                'terrain' => $terrain,
                'errors' => $errors,
                'currentUser' => $this->currentUser
            ], 'Modifier le Terrain');
        }
    }

    /**
     * Gérant ne peut pas supprimer (admin only)
     */
    public function delete(string $id): void
    {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Accès interdit']);
    }

    /**
     * Gérer les options d'un terrain
     */
    public function gererOptions(string $terrainId): void
    {
        $terrain = $this->terrainModel->getById($terrainId);
        
        if (!$terrain) {
            $this->renderView('Errors/404', [], '404 - Terrain non trouvé');
            return;
        }

        // Vérifier propriété
        $terrainOwnerId = is_object($terrain) ? $terrain->id_utilisateur : $terrain['id_utilisateur'];
        if ($terrainOwnerId != $this->currentUser->user_id) {
            http_response_code(403);
            $this->renderView('Errors/403', [], 'Accès interdit');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $options = $this->optionModel->getByTerrainId($terrainId);
            
            $this->renderView('Gerant/GererOptions', [
                'terrain' => $terrain,
                'options' => $options ?: [],
                'currentUser' => $this->currentUser
            ], 'Gérer les Options');
            return;
        }

        // Traiter POST - Ajouter une option
        $nom_option = trim($_POST['nom_option'] ?? '');
        $prix = floatval($_POST['prix'] ?? 0);

        $errors = [];
        if (empty($nom_option)) $errors[] = "Le nom de l'option est obligatoire";
        if ($prix < 0) $errors[] = "Le prix ne peut pas être négatif";

        if (!empty($errors)) {
            $options = $this->optionModel->getByTerrainId($terrainId);
            
            $this->renderView('Gerant/GererOptions', [
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
            UrlHelper::redirect("gerant/options/$terrainId");
        } else {
            $errors[] = "Erreur lors de l'ajout de l'option";
            $options = $this->optionModel->getByTerrainId($terrainId);
            
            $this->renderView('Gerant/GererOptions', [
                'terrain' => $terrain,
                'options' => $options ?: [],
                'errors' => $errors,
                'currentUser' => $this->currentUser
            ], 'Gérer les Options');
        }
    }

    /**
     * Supprimer une option
     */
    public function supprimerOption(string $optionId): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        $option = $this->optionModel->getById($optionId);
        
        if (!$option) {
            echo json_encode(['success' => false, 'message' => 'Option non trouvée']);
            return;
        }

        // Vérifier que le gérant possède le terrain
        $terrain = $this->terrainModel->getById(
            is_object($option) ? $option->id_terrain : $option['id_terrain']
        );
        
        if (!$terrain) {
            echo json_encode(['success' => false, 'message' => 'Terrain non trouvé']);
            return;
        }

        $terrainOwnerId = is_object($terrain) ? $terrain->id_utilisateur : $terrain['id_utilisateur'];
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

?>