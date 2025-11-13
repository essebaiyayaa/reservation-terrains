<?php

/**
 * Class GerantController
 * 
 * Handles all gerant-related operations including terrain and option management.
 * 
 * @package Controllers
 * @author  System
 * @version 1.2
 */
class GerantController extends BaseController {

    private ReservationModel $reservationModel;
    private TerrainModel $terrainModel;
    private OptionSupplementaireModel $optionModel;
    private ?object $currentUser = null;
    private UserModel $userModel;
    private PromotionModel $promotionModel;

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
        $this->userModel = $this->loadModel('UserModel');
        $this->promotionModel = $this->loadModel('PromotionModel');
    }

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

        $terrains = $this->terrainModel->getByGerantId($this->currentUser->user_id);

        $this->renderView('Gerant/Dashboard', [
            'currentUser' => $this->currentUser,
            'gerant' => $gerant,
            'stats' => $stats,
            'terrains' => $terrains
        ], 'Tableau de Bord Gérant');
    }

    public function mesTerrains(): void
    {
        $terrains = $this->terrainModel->getByGerantId($this->currentUser->user_id);

        $this->renderView('Gerant/MesTerrains', [
            'terrains' => $terrains,
            'currentUser' => $this->currentUser
        ], 'Mes Terrains');
    }

    public function show(string $id): void
    {
        $terrain = $this->terrainModel->getById($id);
        if (!$terrain) {
            $this->renderView('Errors/404', [], '404 - Terrain non trouvé');
            return;
        }

        $terrainOwnerId = is_object($terrain) ? $terrain->id_utilisateur : $terrain['id_utilisateur'];
        if ($terrainOwnerId != $this->currentUser->user_id) {
            http_response_code(403);
            $this->renderView('Errors/403', [], 'Accès interdit');
            return;
        }

        $options = $this->optionModel->getByTerrainId($id);

        $this->renderView('Gerant/TerrainDetails', [
            'terrain' => $terrain,
            'options' => $options ?: [],
            'currentUser' => $this->currentUser
        ], 'Détails du Terrain');
    }

    public function create(): void
    {
        http_response_code(403);
        $this->renderView('Errors/403', [], 'Accès interdit');
    }

    public function edit(string $id): void
    {
        $terrain = $this->terrainModel->getById($id);
        if (!$terrain) {
            $this->renderView('Errors/404', [], '404 - Terrain non trouvé');
            return;
        }

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

        $ancienPrix = is_object($terrain) ? floatval($terrain->prix_heure) : floatval($terrain['prix_heure']);
        $nouveauPrix = floatval($updateData['prix_heure']);

        if ($this->terrainModel->update($id, $updateData)) {

            // Si baisse de prix, créer promo et envoyer mail
            if ($nouveauPrix < $ancienPrix) {

                $reduction = ($ancienPrix > 0) ? round((($ancienPrix - $nouveauPrix) / $ancienPrix) * 100, 2) : 0;
                $promoData = [
                    'id_terrain' => (int)$id,
                    'description' => "Baisse du prix de $ancienPrix à $nouveauPrix DH (-$reduction%)",
                    'pourcentage_remise' => floatval($reduction),
                    'date_debut' => date('Y-m-d'),
                    'date_fin' => date('Y-m-d', strtotime('+7 days')),
                    'actif' => 1
                ];

                $result = $this->promotionModel->add($promoData);
                if (!$result) {
                    echo "Erreur d'insertion promotion !";
                    var_dump($promoData);
                    exit;
                }
                
                $clients = $this->userModel->getAllClients();
                $terrainName = is_object($terrain) ? $terrain->nom_terrain : $terrain['nom_terrain'];

                foreach ($clients as $client) {
                    $subject = "🏷️ Nouvelle promotion sur le terrain {$terrainName}";
                    $body = "Bonjour {$client->prenom},\n\n"
                        . "Le terrain '{$terrainName}' vient de baisser son prix :\n"
                        . "Ancien prix : {$ancienPrix} DH\n"
                        . "Nouveau prix : {$nouveauPrix} DH\n"
                        . "Profitez de cette promotion valable jusqu'au " . date('d/m/Y', strtotime('+7 days')) . " !\n\n"
                        . "L'équipe FootBooking ⚽";

                    Utils::sendEmail($client->email, $subject, nl2br($body));
                }
            }

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

    public function delete(string $id): void
    {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Accès interdit']);
    }

    public function gererOptions(string $terrainId): void
    {
        $terrain = $this->terrainModel->getById($terrainId);
        if (!$terrain) {
            $this->renderView('Errors/404', [], '404 - Terrain non trouvé');
            return;
        }

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
 * Update reservation payment status - FOR GERANT ONLY
 * Route: gerant/update-reservation-status
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

    /**
     * View all reservations for gerant's terrains
     */
    public function reservations(): void
    {
        // Get all terrains owned by this gerant
        $terrains = $this->terrainModel->getByGerantId($this->currentUser->user_id);
        
        if (empty($terrains)) {
            $this->renderView('Gerant/Reservations', [
                'currentUser' => $this->currentUser,
                'reservations' => [],
                'terrains' => [],
                'filters' => []
            ], 'Mes Réservations');
            return;
        }
        
        // Get filters
        $filters = [
            'statut' => $_GET['statut'] ?? '',
            'date' => $_GET['date'] ?? '',
            'terrainId' => $_GET['terrain'] ?? ''
        ];
        
        // Get all reservations for gerant's terrains
        $allReservations = [];
        
        foreach ($terrains as $terrain) {
            $terrainId = is_object($terrain) ? $terrain->id_terrain : $terrain['id_terrain'];
            $reservations = $this->reservationModel->getByTerrainId($terrainId);
            $allReservations = array_merge($allReservations, $reservations);
        }
        
        // Apply filters
        $filteredReservations = $allReservations;
        
        // Filter by status
        if (!empty($filters['statut'])) {
            $filteredReservations = array_filter($filteredReservations, function($r) use ($filters) {
                return $r->statut_paiement === $filters['statut'];
            });
        }
        
        // Filter by date
        if (!empty($filters['date'])) {
            $filteredReservations = array_filter($filteredReservations, function($r) use ($filters) {
                return $r->date_reservation === $filters['date'];
            });
        }
        
        // Filter by terrain
        if (!empty($filters['terrainId'])) {
            $filteredReservations = array_filter($filteredReservations, function($r) use ($filters) {
                return $r->id_terrain == $filters['terrainId'];
            });
        }
        
        // Sort by date descending
        usort($filteredReservations, function($a, $b) {
            $dateCompare = strcmp($b->date_reservation, $a->date_reservation);
            if ($dateCompare !== 0) {
                return $dateCompare;
            }
            return strcmp($b->heure_debut, $a->heure_debut);
        });
        
        $this->renderView('Gerant/Reservations', [
            'currentUser' => $this->currentUser,
            'reservations' => $filteredReservations,
            'terrains' => $terrains,
            'filters' => $filters
        ], 'Mes Réservations - Gérant');
    }

    /**
     * Update reservation payment status - FOR GERANT ONLY
     * Route: gerant/update-reservation-status
     */
    public function updateReservationStatus(): void
    {
        // Only accept POST requests
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }
        
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id']) || !isset($input['status'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
            return;
        }
        
        $reservationId = $input['id'];
        $newStatus = $input['status'];
        
        // Validate status - ONLY PAYMENT STATUS
        $validStatuses = ['paye', 'en_attente', 'annule'];
        if (!in_array($newStatus, $validStatuses)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Statut invalide']);
            return;
        }
        
        // Get reservation
        $reservation = $this->reservationModel->getById($reservationId);
        
        if (!$reservation) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Réservation non trouvée']);
            return;
        }
        
        // Verify ownership of the terrain
        $terrain = $this->terrainModel->getById($reservation->id_terrain);
        
        if (!$terrain) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Terrain non trouvé']);
            return;
        }
        
        $terrainOwnerId = is_object($terrain) ? $terrain->id_utilisateur : $terrain['id_utilisateur'];
        
        if ($terrainOwnerId != $this->currentUser->user_id) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Vous ne pouvez modifier que les réservations de vos propres terrains']);
            return;
        }
        
        // Update status
        if ($this->reservationModel->updateStatus($reservationId, $newStatus)) {
            // Send email notification to client
            try {
                $client = $this->userModel->getById($reservation->id_utilisateur);
                
                if ($client && !empty($client->email)) {
                    $clientPrenom = is_object($client) ? $client->prenom : $client['prenom'];
                    $clientEmail = is_object($client) ? $client->email : $client['email'];
                    
                    $statusLabel = match($newStatus) {
                        'paye' => 'confirmée et payée',
                        'annule' => 'annulée',
                        'en_attente' => 'en attente de paiement',
                        default => 'mise à jour'
                    };
                    
                    $subject = "Mise à jour de votre réservation #" . $reservationId;
                    $body = "Bonjour {$clientPrenom},\n\n" .
                           "Le statut de votre réservation pour le terrain '{$reservation->nom_terrain}' " .
                           "le " . date('d/m/Y', strtotime($reservation->date_reservation)) . " " .
                           "a été mis à jour : {$statusLabel}.\n\n" .
                           "Pour plus de détails, consultez votre espace personnel.\n\n" .
                           "Cordialement,\n" .
                           "L'équipe FootBooking";
                    
                    Utils::sendEmail($clientEmail, $subject, nl2br($body));
                }
            } catch (Exception $e) {
                error_log("Erreur envoi email notification: " . $e->getMessage());
                // Continue even if email fails
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Statut mis à jour avec succès',
                'new_status' => $newStatus
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Erreur lors de la mise à jour du statut'
            ]);
        }
    }
}
?>