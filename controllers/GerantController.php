<?php

class GerantController extends BaseController {

    private ReservationModel $reservationModel;
    private UserModel $userModel;
    private ?object $currentUser = null;


    public function __construct()
    {
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

        $this->reservationModel = $this->loadModel('ReservationModel');
    }

    public function index(): void{


        $stats = [
          'total_reservations' => $this->reservationModel->getTotalCount(), 
          'reservations_aujourdhui' =>$this->reservationModel->getTodaysReservations($this->currentUser->user_id),
          'ca_mois'=> $this->reservationModel->getChiffreAffairesMoisGerant($this->currentUser->user_id)
        ];

        $gerant = [
            'prenom' => $this->currentUser->prenom,
            'email' => $this->currentUser->email,
            'role' => $this->currentUser->role,
            'nom' => $this->currentUser->nom
        ];




        $this->renderView('Gerant/Dashboard',  [
            'currentUser' => $this->currentUser,
            'gerant' => $gerant,
            'stats' => $stats
        ],);
    }

   
    public function show(string $id): void{

    }

   
    public function create(): void{

    }

    
    public function edit(string $id): void{

    }

  
    public function delete(string $id): void{

    }
}


?>