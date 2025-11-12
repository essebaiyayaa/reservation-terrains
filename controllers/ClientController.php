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
            UrlHelper::redirect('login');
        }

        $decoded = Utils::verifyJWT($token, JWT_SECRET_KEY);
        if ($decoded === false || $decoded->role !== 'client') {
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
        $terrain_id = $_GET['terrain_id'] ?? 22;
        $date = $_GET['date'] ?? '11/22/2025';

        $slots = $this->terrainModel->getBookedSlots($terrain_id, $date);

        

        echo json_encode([
            'success' => true,
            'booked_slots' => $slots,
            'date' => $date,
            'terrain_id' => $terrain_id,
            'timestamp' => time()
        ]);
    }

    public function faireReservation(){


        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            
                $date_reservation = $_POST['date_reservation'];
                $heure_debut = $_POST['heure_debut'];
                $heure_fin = date('H:i:s', strtotime($heure_debut) + 3600); 
                $id_terrain = $_POST['id_terrain'] ?? $_GET['id'];
                $options_selectionnees = $_POST['options'] ?? [];
                $commentaires = $_POST['commentaires'] ?? '';

                $ret = $this->terrainModel->reserverTerrain(
                    $id_terrain, 
                    $date_reservation, 
                    $heure_debut, 
                    $heure_fin, 
                    $commentaires, 
                    $options_selectionnees 
                );

                if (is_int($ret) && $ret > 0) {
                    UrlHelper::url('facture/id/' . $ret);
                    exit;

                } else {
        
                    
                }
        }

        

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

        $reservationObj = $this->reservationModel->getReservationDetails((int)$id, (int)$this->currentUser->user_id);
        $options = $this->reservationModel->getReservationOptions((int)$id);

        $all_options = [];

        foreach ($options as $option) {
            $all_options[] = (array)$option;
        }

        $totals = $this->reservationModel->calculateTotal($reservationObj, $options);


        $reservation = (array)$reservationObj;

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            Utils::generateInvoicePDF($reservation, $all_options, $totals['total_options'], $totals['total_general'] );
        }
        
        
        
        $this->renderView('Client/Facture', [
            'currentUser' => $this->currentUser,
            'reservation' => $reservation,
            'total_general' => $totals['total_general'],
            'total_options' => $totals['total_options'],
            'options' => $all_options
        ]);
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


}


?>