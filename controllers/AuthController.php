<?php

class AuthController extends BaseController {
    public function index(): void {
        $this->renderView('Auth/Register', []);
    }

    public function show(string $id): void {
        
    }


    public function create(): void{
        $this->index();

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            
            $userModel = $this->loadModel("UserModel");

            $nom            = trim($_POST["nom"] ?? '');
            $prenom         = trim($_POST["prenom"] ?? '');
            $email          = trim($_POST["email"] ?? '');
            $telephone      = trim($_POST["telephone"] ?? '');
            $mot_de_passe   = $_POST["mot_de_passe"] ?? '';
            
            $errors = [];

            if (empty($nom))         $errors[] = "Le nom est obligatoire.";
            if (empty($prenom))      $errors[] = "Le prénom est obligatoire.";
            if (empty($email))       $errors[] = "L'adresse e-mail est obligatoire.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "L'adresse e-mail n'est pas valide.";
            if (empty($telephone))   $errors[] = "Le numéro de téléphone est obligatoire.";
            if (!preg_match('/^[0-9]{8,15}$/', $telephone)) $errors[] = "Le numéro de téléphone doit contenir entre 8 et 15 chiffres.";
            if (empty($mot_de_passe)) $errors[] = "Le mot de passe est obligatoire.";
            if (strlen($mot_de_passe) < 6) $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";


            if (!empty($errors)) {
                $this->renderView('Auth/Register', ["errors" => $errors]);
                exit;
            }

            $hashedPassword = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            $verification_token = Utils::generateRandomInt(8);
            $token_expiry_minutes = 30;

            
            $data = [
                "nom" => htmlspecialchars($nom),
                "prenom" => htmlspecialchars($prenom),
                "email" => htmlspecialchars($email),
                "telephone" => htmlspecialchars($telephone),
                "mot_de_passe" => $hashedPassword,
                "verification_token" => $verification_token,
                "token_expiry" => Utils::generateVerificationTokenExpiry($token_expiry_minutes)
            ];
            /** @var UserModel $userModel */
           
            $exec = $userModel->add($data); 
            
            if($exec){

                Utils::sendVerificationEmail(
                    htmlspecialchars($email),
                    htmlspecialchars($prenom),
                    $verification_token, 
                    $token_expiry_minutes
                );

                session_start();

                $_SESSION['account_email'] = htmlspecialchars($email);

                //TODO: Redirection to Verification page    

                exit;
            }else{
                $this->renderView('Errors/Error', [
                    "message" => "Impossible de créer le compte utilisateur.",
                    "errors" => $errors
                ]);
            }
        
        }
        
    }

    public function login(): void {
        $this->renderView('Auth/Login', []);

        if ($_SERVER['REQUEST_METHOD'] == "POST"){

            $userModel = $this->loadModel("UserModel");

            $email          = trim($_POST["email"] ?? '');
            $mot_de_passe   = $_POST["mot_de_passe"] ?? '';



            $errors = [];

            
            if (empty($email))       $errors[] = "L'adresse e-mail est obligatoire.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "L'adresse e-mail n'est pas valide.";
            
            if (empty($mot_de_passe)) $errors[] = "Le mot de passe est obligatoire.";

            if (!empty($errors)) {
                $this->renderView('Auth/Register', ["errors" => $errors]);
                exit;
            }

            // echo "Hello";
            /** @var UserModel $userModel */
            /** @var object|null $user */
            $user = $userModel->getById(htmlspecialchars($email));
            
            // echo var_dump($user);

            if($user){
                if(password_verify($mot_de_passe, $user->mot_de_passe)){
                    $token_duration_seconds = 3600;
                    // JWT Token

                    $payload = [
                        'user_id' => $user->id_utilisateur,
                        'email' => $user->email,
                        'role' => $user->role
                    ];
                    $token = Utils::generateJWT($payload, JWT_SECRET_KEY, $token_duration_seconds);
                    Utils::setCookieSafe('auth_token', $token, $token_duration_seconds);

                    echo "All good!";
                    
                }else{
                    $errors[] = "Veuillez saisir les bonnes informations";
                }
            }else{
                $errors[] = "Veuillez saisir les bonnes informations";
            }    

        }
    }

    public function logout(): void {

    }

    public function edit(string $id): void{
       
    }

    public function delete(string $id): void{
        
    }
}



?>