<?php

/**
 * Class AuthController
 * 
 * Handles authentication operations including:
 * - User registration
 * - User login with role-based redirection
 * - Email verification
 * - Logout
 * 
 * @package Controllers
 * @author  Amos & Jihane
 * @version 1.2
 */
class AuthController extends BaseController {
    
    /**
     * Override renderView to use custom view path for Auth
     * 
     * @param string $viewName View file name
     * @param array $data Data to pass to view
     * @param string $title Page title
     * @return void
     */
    protected function renderView(string $viewName, array $data = [], string $title = ""): void
    {
        extract($data);
        $file = __DIR__ . '/../views/' . $viewName . '.php';
        if (file_exists($file)) {
            require $file;
        } else {
            echo "View not found: " . htmlspecialchars($viewName);
        }
    }

    /**
     * Display registration page (GET)
     * 
     * @return void
     */
    public function index(): void {
        $this->renderView('Auth/Register', []);
    }

    /**
     * Not used
     */
    public function show(string $id): void {
        // Not implemented
    }

    /**
     * Handle user registration
     * - Validates user input
     * - Creates new user account
     * - Sends verification email
     * - Redirects to verification page
     * 
     * @return void
     */
    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            Utils::verifyRecaptcha($_POST['g-recaptcha-response'], RECAPTCHA_SECRET_KEY);
            
            /** @var UserModel $userModel */
            $userModel = $this->loadModel("UserModel");

            // Récupérer et nettoyer les données du formulaire
            $nom            = trim($_POST["nom"] ?? '');
            $prenom         = trim($_POST["prenom"] ?? '');
            $email          = trim($_POST["email"] ?? '');
            $telephone      = trim($_POST["telephone"] ?? '');
            $mot_de_passe   = $_POST["mot_de_passe"] ?? '';
            $confirmer_mot_de_passe = $_POST["confirmer_mot_de_passe"] ?? '';
            
            $errors = [];

            // Validation des champs
            if (empty($nom))         $errors[] = "Le nom est obligatoire.";
            if (empty($prenom))      $errors[] = "Le prénom est obligatoire.";
            if (empty($email))       $errors[] = "L'adresse e-mail est obligatoire.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "L'adresse e-mail n'est pas valide.";
            if (empty($telephone))   $errors[] = "Le numéro de téléphone est obligatoire.";
            if (!preg_match('/^[0-9]{8,15}$/', $telephone)) $errors[] = "Le numéro de téléphone doit contenir entre 8 et 15 chiffres.";
            if (empty($mot_de_passe)) $errors[] = "Le mot de passe est obligatoire.";
            if (strlen($mot_de_passe) < 6) $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
            if ($mot_de_passe !== $confirmer_mot_de_passe) $errors[] = "Les mots de passe ne correspondent pas.";

            // Si des erreurs existent, afficher le formulaire avec les erreurs
            if (!empty($errors)) {
                $this->renderView('Auth/Register', ["errors" => $errors]);
                exit;
            }

            // Vérifier si l'email existe déjà
            $existingUser = $userModel->getById($email);
            if ($existingUser) {
                $errors[] = "Un compte avec cet email existe déjà.";
                $this->renderView('Auth/Register', ["errors" => $errors]);
                exit;
            }

            // Hacher le mot de passe
            $hashedPassword = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            // Générer le token de vérification
            $verification_token = Utils::generateRandomInt(8);
            $token_expiry_minutes = 30;

            // Préparer les données pour l'insertion
            $data = [
                "nom" => htmlspecialchars($nom),
                "prenom" => htmlspecialchars($prenom),
                "email" => htmlspecialchars($email),
                "telephone" => htmlspecialchars($telephone),
                "mot_de_passe" => $hashedPassword,
                "verification_token" => $verification_token,
                "token_expiry" => Utils::generateVerificationTokenExpiry($token_expiry_minutes),
                "role" => "client" // Par défaut
            ];
            
            // Ajouter l'utilisateur à la base de données
            $exec = $userModel->add($data); 
            
            if($exec){
                // Envoyer l'email de vérification
                Utils::sendVerificationEmail(
                    htmlspecialchars($email),
                    htmlspecialchars($prenom),
                    $verification_token, 
                    $token_expiry_minutes
                );

                // Sauvegarder l'email en session pour la vérification
                session_start();
                $_SESSION['verification_email'] = htmlspecialchars($email);

                // Rediriger vers la page de vérification
                header('Location: ' . UrlHelper::url('verify'));
                exit;
            } else {
                $errors[] = "Impossible de créer le compte utilisateur.";
                $this->renderView('Auth/Register', ["errors" => $errors]);
            }
        } else {
            // Afficher le formulaire pour GET
            $this->renderView('Auth/Register', []);
        }
    }

    /**
     * Handle user login
     * - Validates credentials
     * - Checks email verification status
     * - Creates JWT token
     * - Redirects based on user role
     * 
     * @return void
     */
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] == "POST"){

            Utils::verifyRecaptcha($_POST['g-recaptcha-response'], RECAPTCHA_SECRET_KEY);

            /** @var UserModel $userModel */
            $userModel = $this->loadModel("UserModel");

            

            $email          = trim($_POST["email"] ?? '');
            $mot_de_passe   = $_POST["mot_de_passe"] ?? '';

            $errors = [];

            // Validation
            if (empty($email))       $errors[] = "L'adresse e-mail est obligatoire.";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "L'adresse e-mail n'est pas valide.";
            if (empty($mot_de_passe)) $errors[] = "Le mot de passe est obligatoire.";

            if (!empty($errors)) {
                $this->renderView('Auth/Login', ["errors" => $errors]);
                exit;
            }

            /** @var object|null $user */
            $user = $userModel->getById(htmlspecialchars($email));

            if($user){
                // Vérifier le mot de passe
                if(password_verify($mot_de_passe, $user->mot_de_passe)){
                    
                    // Vérifier si le compte est vérifié
                    if (!$user->compte_verifie) {
                        $errors[] = "Veuillez vérifier votre adresse email avant de vous connecter.";
                        $this->renderView('Auth/Login', ["errors" => $errors]);
                        exit;
                    }

                    $token_duration_seconds = 3600; // 1 heure
                    
                    // Créer le JWT Token
                    $payload = [
                        'user_id' => $user->id_utilisateur,
                        'email' => $user->email,
                        'role' => $user->role,
                        'prenom' => $user->prenom,
                        'nom' => $user->nom
                    ];
                    $token = Utils::generateJWT($payload, JWT_SECRET_KEY, $token_duration_seconds);
                    Utils::setCookieSafe('auth_token', $token, $token_duration_seconds);

                    // Redirection basée sur le rôle
                    switch($user->role) {
                        case 'admin':
                            header('Location: ' . UrlHelper::url('dashboard/admin'));
                            break;
                        case 'gerant_terrain':
                            header('Location: ' . UrlHelper::url('gerant/dashboard'));
                            break;
                        case 'client':
                        default:
                            header('Location: ' . UrlHelper::url('/'));
                            break;
                    }
                    exit;
                    
                } else {
                    $errors[] = "Email ou mot de passe incorrect.";
                    $this->renderView('Auth/Login', ["errors" => $errors]);
                    exit;
                }
            } else {
                $errors[] = "Email ou mot de passe incorrect.";
                $this->renderView('Auth/Login', ["errors" => $errors]);
                exit;
            }    
        }
        
        // Affichage du formulaire pour GET
        $this->renderView('Auth/Login', []);
    }

    /**
     * Display verification page
     * Shows form to enter verification code
     * 
     * @return void
     */
    public function verify(): void {
        session_start();
        
        // Vérifier si l'email est en session
        if (!isset($_SESSION['verification_email'])) {
            header('Location: ' . UrlHelper::url('register'));
            exit;
        }

        $email = $_SESSION['verification_email'];

        // Afficher la page de vérification
        $this->renderView('Auth/Verify', [
            'email' => $email
        ]);
    }

    /**
     * Handle verification code submission
     * - Validates the verification code
     * - Checks expiration
     * - Activates user account
     * - Redirects to login
     * 
     * @return void
     */
    public function verifySubmit(): void {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . UrlHelper::url('verify'));
            exit;
        }

        // Vérifier si l'email est en session
        if (!isset($_SESSION['verification_email'])) {
            header('Location: ' . UrlHelper::url('register'));
            exit;
        }

        $email = $_SESSION['verification_email'];
        $code = trim($_POST['verification_code'] ?? '');

        $errors = [];

        // Validation du code
        if (empty($code)) {
            $errors[] = "Le code de vérification est obligatoire.";
        }

        if (strlen($code) !== 8) {
            $errors[] = "Le code de vérification doit contenir 8 chiffres.";
        }

        if (!empty($errors)) {
            $this->renderView('Auth/Verify', [
                'email' => $email,
                'errors' => $errors
            ]);
            exit;
        }

        /** @var UserModel $userModel */
        $userModel = $this->loadModel("UserModel");

        // Vérifier le code
        $verified = $userModel->verifyAccount($email, $code);

        if ($verified) {
            // Nettoyer la session
            unset($_SESSION['verification_email']);

            // Message de succès
            $_SESSION['success'] = "Votre compte a été vérifié avec succès ! Vous pouvez maintenant vous connecter.";

            // Rediriger vers la page de connexion
            header('Location: ' . UrlHelper::url('login'));
            exit;
        } else {
            $errors[] = "Code de vérification invalide ou expiré.";
            $this->renderView('Auth/Verify', [
                'email' => $email,
                'errors' => $errors
            ]);
        }
    }

    /**
     * Handle user logout
     * - Clears JWT cookie
     * - Destroys session
     * - Redirects to home
     * 
     * @return void
     */
    public function logout(): void {
        // Supprimer le cookie JWT
        Utils::setCookieSafe('auth_token', '', -1);
        
        // Détruire la session
        session_start();
        session_destroy();
        
        // Rediriger vers l'accueil
        header('Location: ' . UrlHelper::url('/'));
        exit;
    }

    /**
     * Edit user (not implemented yet)
     */
    public function edit(string $id): void {
        // TODO: Implémenter l'édition de profil
    }

    /**
     * Delete user (not implemented yet)
     */
    public function delete(string $id): void {
        // TODO: Implémenter la suppression de compte
    }
}