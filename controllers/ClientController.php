<?php

class ClientController extends BaseController{

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
        if ($decoded === false || $decoded->role !== 'client') {
            http_response_code(403);
            $this->renderView('Errors/403', [], 'Accès interdit');
            exit;
        }

        $this->currentUser = $decoded;
        $this->userModel = $this->loadModel('UserModel');
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


}


?>