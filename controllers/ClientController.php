<?php

class ClientController extends BaseController{

    private ReservationModel $reservationModel;
    private UserModel $userModel;
    private OptionSupplementaireModel $optionsSuppModel;
    private TerrainModel $terrainModel;
    private ?object $currentUser = null;


public function __construct()
{
    $token = Utils::getCookieValue('auth_token');
    if (!$token) {
        // Pour les requêtes AJAX, renvoyer JSON au lieu de rediriger
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Non authentifié']);
            exit;
        }
        UrlHelper::redirect('login');
    }

    $decoded = Utils::verifyJWT($token, JWT_SECRET_KEY);
    if ($decoded === false || $decoded->role !== 'client') {
        // Pour les requêtes AJAX
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Accès refusé']);
            exit;
        }
        
        http_response_code(403);
        $this->renderView('Errors/403', [], 'Accès interdit');
        exit;
    }

    $this->currentUser = $decoded;
    $this->userModel = $this->loadModel('UserModel');
    $this->terrainModel = $this->loadModel('TerrainModel');
    $this->optionsSuppModel = $this->loadModel('OptionSupplementaireModel');
    $this->reservationModel = $this->loadModel('ReservationModel');
}

    public function index(): void{

    }

   
    public function show(string $id): void{

    }

   
    public function create(): void{

    }

    
    public function edit(string $id): void{

    }

  
    public function delete(string $id): void{

    }

    public function myReservations(){
        // $message = '';
        // $message_type = '';

        // if (isset($_SESSION['message'])) {
        //     $message = $_SESSION['message'];
        //     $message_type = $_SESSION['message_type'];
        //     unset($_SESSION['message']);
        //     unset($_SESSION['message_type']);
        // }

        $reservations = $this->reservationModel->getUserReservations($this->currentUser->user_id);

        $reservationsArray = array_map(fn($r) => (array) $r, $reservations);

        

        $this->renderView('Client/MesReservations', [
            'reservations' => $reservationsArray,
            'currentUser' => $this->currentUser
        ]);
    }


    public function searchTerrains(){
        $taille = $_GET['taille'] ?? 'Grand terrain';
        $type = $_GET['type'] ?? 'Gazon naturel';
        $terrains = $this->terrainModel->getTerrainsByTypeAndTaille($type, $taille);
        echo json_encode($terrains);
    }

public function getAvailableSlots(){
    // IMPORTANT: Désactiver l'affichage des erreurs PHP pour ne pas polluer le JSON
    error_reporting(0);
    ini_set('display_errors', 0);
    
    // Nettoyer tout buffer de sortie existant
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Démarrer un nouveau buffer
    ob_start();
    
    // Définir le header JSON
    header('Content-Type: application/json; charset=utf-8');
    
    try {
        $terrain_id = isset($_GET['terrain_id']) ? (int)$_GET['terrain_id'] : null;
        $date = isset($_GET['date']) ? $_GET['date'] : null;

        if (!$terrain_id || !$date) {
            $response = [
                'success' => false,
                'message' => 'Paramètres manquants',
                'terrain_id' => $terrain_id,
                'date' => $date
            ];
            
            // Nettoyer le buffer et envoyer
            ob_clean();
            echo json_encode($response);
            exit;
        }

        // Validation du format de date
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);
        if (!$dateObj || $dateObj->format('Y-m-d') !== $date) {
            $response = [
                'success' => false,
                'message' => 'Format de date invalide'
            ];
            
            ob_clean();
            echo json_encode($response);
            exit;
        }

        $slots = $this->terrainModel->getBookedSlots($terrain_id, $date);

        $response = [
            'success' => true,
            'booked_slots' => $slots,
            'date' => $date,
            'terrain_id' => $terrain_id,
            'timestamp' => time()
        ];
        
        // Nettoyer le buffer et envoyer
        ob_clean();
        echo json_encode($response);
        
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => 'Erreur serveur',
            'error' => $e->getMessage()
        ];
        
        ob_clean();
        echo json_encode($response);
    }
    
    exit;
}

public function faireReservation(){
    error_log("=== METHODE APPELEE ===");
    error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
    error_log("POST data: " . print_r($_POST, true));
    error_log("SESSION user_id: " . ($this->currentUser->user_id ?? 'NULL'));
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        
        try {
            $date_reservation = $_POST['date_reservation'] ?? '';
            $heure_debut = $_POST['heure_debut'] ?? '';
            $heure_fin = date('H:i:s', strtotime($heure_debut) + 3600); 
            $id_terrain = $_POST['id_terrain'] ?? $_GET['id'] ?? null;
            $options_selectionnees = $_POST['options'] ?? [];
            $commentaires = $_POST['commentaires'] ?? '';

            // Validation
            if (!$id_terrain || !$date_reservation || !$heure_debut) {
                $_SESSION['message'] = "Tous les champs obligatoires doivent être remplis";
                $_SESSION['message_type'] = "error";
                UrlHelper::redirect('reservation');
                exit;
            }

            // Appel avec le paramètre user_id
            $ret = $this->terrainModel->reserverTerrain(
                (int)$id_terrain,
                (int)$this->currentUser->user_id,
                $date_reservation, 
                $heure_debut, 
                $heure_fin, 
                $commentaires, 
                $options_selectionnees 
            );

            if (is_int($ret) && $ret > 0) {
                // ✅ CORRECTION: Utiliser redirect au lieu de url
                $_SESSION['message'] = "Réservation effectuée avec succès!";
                $_SESSION['message_type'] = "success";
                UrlHelper::redirect('facture/id/' . $ret);
                exit;
            } else {
                $_SESSION['message'] = "Ce créneau est déjà réservé";
                $_SESSION['message_type'] = "error";
            }
            
        } catch (Exception $e) {
            error_log("Erreur réservation: " . $e->getMessage());
            $_SESSION['message'] = "Erreur lors de la réservation: " . $e->getMessage();
            $_SESSION['message_type'] = "error";
        }
    }

    // Préparer les données pour la vue
    $user = [
        'prenom' => $this->currentUser->prenom,
        'email' => $this->currentUser->email,
        'role' => $this->currentUser->role,
        'nom' => $this->currentUser->nom,
        'telephone' => $this->currentUser->telephone
    ];
    
    $types = $this->terrainModel->getTypes();
    $tailles = $this->terrainModel->getTailles();
    $options = $this->optionsSuppModel->getAllOptions();

    $all_options = [];
    foreach ($options as $option) {
        $all_options[] = (array)$option;
    }

    $this->renderView('Client/Reservation', [
        'currentUser' => $this->currentUser,
        'user'=> $user,
        'tailles' => $tailles,
        'types' => $types,
        'options' => $all_options
    ]);
}

public function facturer($id){
    // IMPORTANT : Nettoyer tout buffer de sortie avant génération PDF
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    try {
        $reservationObj = $this->reservationModel->getReservationDetails((int)$id, (int)$this->currentUser->user_id);
        
        // Vérifier que la réservation existe
        if (!$reservationObj) {
            $_SESSION['message'] = "Réservation introuvable";
            $_SESSION['message_type'] = "error";
            UrlHelper::redirect('mes-reservations');
            exit;
        }
        
        $options = $this->reservationModel->getReservationOptions((int)$id);

        $all_options = [];
        foreach ($options as $option) {
            $all_options[] = (array)$option;
        }

        $totals = $this->reservationModel->calculateTotal($reservationObj, $options);
        $reservation = (array)$reservationObj;
        
        // ✅ S'assurer que tous les champs nécessaires existent
        if (!isset($reservation['prenom']) || !isset($reservation['nom'])) {
            $reservation['prenom'] = $this->currentUser->prenom ?? 'N/A';
            $reservation['nom'] = $this->currentUser->nom ?? 'N/A';
        }
        
        if (!isset($reservation['email'])) {
            $reservation['email'] = $this->currentUser->email ?? 'N/A';
        }
        
        if (!isset($reservation['telephone'])) {
            $reservation['telephone'] = $this->currentUser->telephone ?? 'Non renseigné';
        }

        // Vérifier si c'est une demande de téléchargement PDF
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['download_pdf'])) {
            // ✅ Désactiver complètement les erreurs pour la génération PDF
            error_reporting(0);
            ini_set('display_errors', '0');
            
            // Générer le PDF
            Utils::generateInvoicePDF(
                $reservation, 
                $all_options, 
                $totals['total_options'], 
                $totals['total_general']
            );
            
            exit; // Le script s'arrête dans generateInvoicePDF
        }
        
        // Afficher la vue HTML de la facture
        $this->renderView('Client/Facture', [
            'currentUser' => $this->currentUser,
            'reservation' => $reservation,
            'total_general' => $totals['total_general'],
            'total_options' => $totals['total_options'],
            'options' => $all_options
        ]);
        
    } catch (Exception $e) {
        error_log("Erreur facturation: " . $e->getMessage());
        $_SESSION['message'] = "Erreur lors de la génération de la facture";
        $_SESSION['message_type'] = "error";
        UrlHelper::redirect('mes-reservations');
        exit;
    }
}


    public function modifierReservation($id){
        $errors = [];


        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modifier_reservation'])) {

            

            $date_reservation = $_POST['date_reservation'] ?? '';
            $heure_debut = $_POST['heure_debut'] ?? '';
            $heure_fin = $_POST['heure_fin'] ?? '';
            $id_terrain = (int)($_POST['id_terrain'] ?? 0);
            $commentaires = trim($_POST['commentaires'] ?? '');
            $selected_options = $_POST['options'] ?? [];


            
            $has_conflict = $this->reservationModel->hasTimeConflict(
                (int)$id_terrain,
                $date_reservation,
                $heure_debut,
                $heure_fin,
                (int)$id
            );

            

            if($has_conflict){
                $errors[] = "Le terrain n'est pas disponible pour ce créneau horaire.";
            }else{
                $updated = $this->reservationModel->updateReservation(
                    (int)$id,
                    (int)$this->currentUser->user_id,
                    $date_reservation,
                    $heure_debut,
                    $heure_fin,
                    (int)$id_terrain,
                    $commentaires
                );

                if($updated){
                    $this->reservationModel->resetReservationOptions((int)$id, $selected_options);
                }else{
                    $errors[] = "Erreur lors de la modification : ";
                }
            }
            
        }
        

        $reservationObj = $this->reservationModel->getReservationWithTerrain((int)$id, (int)$this->currentUser->user_id);

        $reservation = (array)$reservationObj;
        $current_options = $this->reservationModel->getReservationOptionsSupp((int)$id);
        $terrains = (array)$this->reservationModel->getAllTerrains();
        $options = $this->reservationModel->getAllSuppOptions();

        $all_options = [];
        $all_current_options = [];
        $all_terrains = [];

        foreach ($options as $option) {
            $all_options[] = (array)$option;
        }

        foreach ($current_options as $option) {
            $all_current_options[] = (array)$option;
        }

        foreach ($terrains as $t) {
            $all_terrains[] = (array)$t;
        }

        

        // echo var_dump($all_terrains[0]['id_terrain']);

        $this->renderView('Client/ModifierReservation', [
            'currentUser' => $this->currentUser,
            'reservation' => $reservation,
            'options' => $all_options,
            'current_options' => $all_current_options,
            'terrains' => $all_terrains,
            'errors' => $errors
        ]);
    }

    public function annulerReservation($id){
        $errors = [];
        $reservationObj = $this->reservationModel->getReservationWithTerrain((int)$id, (int)$this->currentUser->user_id);

        if($reservationObj == null){
            $errors[] = "Réservation introuvable ou vous n'avez pas les droits pour l'annuler.";
            UrlHelper::redirect('mes-reservations');
            exit();
        }

        $reservation = (array)$reservationObj;

        if ($reservation['statut'] === 'Annulée') {
            $_SESSION['message'] = "Cette réservation est déjà annulée.";
            $_SESSION['message_type'] = "error";
            UrlHelper::redirect('mes-reservations');
            exit();
        }

        $reservation_datetime = new DateTime($reservation['date_reservation'] . ' ' . $reservation['heure_debut']);

        $now = new DateTime();
        
        if ($reservation_datetime < $now) {
            $_SESSION['message'] = "Impossible d'annuler une réservation passée.";
            $_SESSION['message_type'] = "error";
            UrlHelper::redirect('mes-reservations');
            exit();
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer_annulation'])) {
            $success = $this->reservationModel->cancelReservation((int)$id, (int)$this->currentUser->user_id);

            if($success){
                UrlHelper::redirect('mes-reservations');
            }else{

            }
        }


        $this->renderView('Client/AnnulerReservation', [
            'currentUser' => $this->currentUser,
            'reservation' => $reservation,
            // 'options' => $all_options,
            // 'current_options' => $all_current_options,
            // 'terrains' => $all_terrains,
            'errors' => $errors
        ]);
    }


}


?>