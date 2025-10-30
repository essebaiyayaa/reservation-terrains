<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


/**
 * Class AuthentificationController
 * 
 * Handles all authentication-related operations using JWT tokens:
 * - User registration with email verification
 * - User login (returns JWT token)
 * - Email verification
 * - Token refresh
 * - Token-based authentication
 * 
 * @package Controllers
 * @author  Aya Essebaiy
 * @version 1.0
 */
class AuthentificationController extends BaseController
{
    private User $userModel;

    /**
     * AuthentificationController constructor.
     * 
     * Initializes the User model.
     */
    public function __construct()
    {
        $this->userModel = $this->loadModel('User');
    }

    /**
     * Not used in authentication context.
     */
    protected function index(): void
    {
        $this->jsonResponse(['message' => 'Authentication API'], 200);
    }

    /**
     * Not used in authentication context.
     */
    protected function show(string $id): void
    {
        $this->jsonResponse(['error' => 'Invalid endpoint'], 404);
    }

    /**
     * Not used in authentication context.
     */
    protected function create(): void
    {
        $this->register();
    }

    /**
     * Not used in authentication context.
     */
    protected function edit(string $id): void
    {
        $this->jsonResponse(['error' => 'Invalid endpoint'], 404);
    }

    /**
     * Not used in authentication context.
     */
    protected function delete(string $id): void
    {
        $this->logout();
    }

    /**
     * Process login request and return JWT token.
     * 
     * @return void
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        
        $email = $input['email'] ?? '';
        $password = $input['mot_de_passe'] ?? '';

        // Validate input
        if (empty($email) || empty($password)) {
            $this->jsonResponse([
                'error' => 'Email et mot de passe requis'
            ], 400);
            return;
        }

        // Find user by email
        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            $this->jsonResponse([
                'error' => 'Email ou mot de passe incorrect'
            ], 401);
            return;
        }

        // Verify password
        if (!password_verify($password, $user['mot_de_passe'])) {
            $this->jsonResponse([
                'error' => 'Email ou mot de passe incorrect'
            ], 401);
            return;
        }

        // Check if email is verified
        if (!$user['email_verified']) {
            $this->jsonResponse([
                'error' => 'Veuillez vérifier votre email avant de vous connecter',
                'email_verified' => false
            ], 403);
            return;
        }

        // Update last login
        $this->userModel->updateLastLogin($user['id_utilisateur']);

        // Generate JWT token
        $token = JWTManager::createToken($user);

        $this->jsonResponse([
            'message' => 'Connexion réussie',
            'token' => $token,
            'user' => [
                'id' => $user['id_utilisateur'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ], 200);
    }

   /**
 * Process registration request.
 * 
 * @return void
 */
public function register(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
        return;
    }

    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate input
    $errors = $this->validateRegistration($input);

    if (!empty($errors)) {
        $this->jsonResponse([
            'error' => 'Validation échouée',
            'details' => $errors
        ], 400);
        return;
    }

    // Check if email already exists
    if ($this->userModel->emailExists($input['email'])) {
        $this->jsonResponse([
            'error' => 'Cet email est déjà utilisé'
        ], 409);
        return;
    }

    // Generate verification token
    $verificationToken = $this->userModel->generateVerificationToken();

    // Prepare user data array
    $userData = [
        'nom' => $input['nom'],
        'prenom' => $input['prenom'],
        'email' => $input['email'],
        'telephone' => $input['telephone'] ?? null,
        'mot_de_passe' => $input['mot_de_passe'], // Will be hashed in add()
        'role' => 'client',
        'verification_token' => $verificationToken
    ];

    // Create user with data array
    if ($this->userModel->add($userData)) {
        // Send verification email
        $this->sendVerificationEmail($input['email'], $verificationToken);

        $this->jsonResponse([
            'message' => 'Inscription réussie. Veuillez vérifier votre email.',
            'email' => $input['email']
        ], 201);
    } else {
        $this->jsonResponse([
            'error' => 'Erreur lors de l\'inscription'
        ], 500);
    }
}

    /**
     * Verify user email with token.
     * 
     * @return void
     */
    public function verifyEmail(): void
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            $this->jsonResponse([
                'error' => 'Token de vérification manquant'
            ], 400);
            return;
        }

        $user = $this->userModel->findByVerificationToken($token);

        if (!$user) {
            $this->jsonResponse([
                'error' => 'Token de vérification invalide ou expiré'
            ], 404);
            return;
        }

        if ($this->userModel->verifyEmail($token)) {
            $this->jsonResponse([
                'message' => 'Email vérifié avec succès'
            ], 200);
        } else {
            $this->jsonResponse([
                'error' => 'Erreur lors de la vérification'
            ], 500);
        }
    }

    /**
     * Resend verification email.
     * 
     * @return void
     */
    public function resendVerification(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? '';

        if (empty($email)) {
            $this->jsonResponse(['error' => 'Email requis'], 400);
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || $user['email_verified']) {
            $this->jsonResponse(['error' => 'Demande invalide'], 400);
            return;
        }

        // Generate new token
        $verificationToken = $this->userModel->generateVerificationToken();
        $this->userModel->update($user['id_utilisateur'], [
            'verification_token' => $verificationToken
        ]);

        // Send email
        $this->sendVerificationEmail($email, $verificationToken);

        $this->jsonResponse([
            'message' => 'Email de vérification renvoyé'
        ], 200);
    }

    /**
     * Refresh JWT token.
     * 
     * @return void
     */
    public function refreshToken(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
            return;
        }

        $currentToken = JWTManager::getTokenFromHeader();

        if (!$currentToken) {
            $this->jsonResponse(['error' => 'Token manquant'], 401);
            return;
        }

        $newToken = JWTManager::refreshToken($currentToken);

        if ($newToken) {
            $this->jsonResponse([
                'message' => 'Token rafraîchi',
                'token' => $newToken
            ], 200);
        } else {
            $this->jsonResponse(['error' => 'Token invalide ou expiré'], 401);
        }
    }

    /**
     * Get current authenticated user info.
     * 
     * @return void
     */
    public function me(): void
    {
        $user = JWTManager::getCurrentUser();

        if (!$user) {
            $this->jsonResponse(['error' => 'Non authentifié'], 401);
            return;
        }

        $this->jsonResponse([
            'user' => [
                'id' => $user['user_id'],
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ], 200);
    }

    /**
     * Logout (client-side should delete token).
     * 
     * @return void
     */
    public function logout(): void
    {
        // With JWT, logout is handled client-side by deleting the token
        // Server can optionally blacklist tokens (requires database)
        $this->jsonResponse([
            'message' => 'Déconnexion réussie. Supprimez le token côté client.'
        ], 200);
    }

    /**
     * Validate registration data.
     * 
     * @param array $data Registration data.
     * @return array Array of validation errors.
     */
    private function validateRegistration(array $data): array
    {
        $errors = [];

        // Validate nom
        if (empty($data['nom']) || strlen(trim($data['nom'])) < 2) {
            $errors['nom'] = 'Le nom doit contenir au moins 2 caractères';
        }

        // Validate prenom
        if (empty($data['prenom']) || strlen(trim($data['prenom'])) < 2) {
            $errors['prenom'] = 'Le prénom doit contenir au moins 2 caractères';
        }

        // Validate email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Adresse email invalide';
        }

        // Validate password
        if (empty($data['mot_de_passe']) || strlen($data['mot_de_passe']) < 8) {
            $errors['mot_de_passe'] = 'Le mot de passe doit contenir au moins 8 caractères';
        }

        // Validate password confirmation
        if (empty($data['confirmer_mot_de_passe']) || $data['mot_de_passe'] !== $data['confirmer_mot_de_passe']) {
            $errors['confirmer_mot_de_passe'] = 'Les mots de passe ne correspondent pas';
        }

        // Validate telephone (optional but format if provided)
        if (!empty($data['telephone']) && !preg_match('/^[0-9\s\+\-\(\)]+$/', $data['telephone'])) {
            $errors['telephone'] = 'Numéro de téléphone invalide';
        }

        return $errors;
    }

   /**
 * Send verification email to user using PHPMailer.
 * 
 * @param string $email User email.
 * @param string $token Verification token.
 * @return void
 */
private function sendVerificationEmail(string $email, string $token): void
{
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;
        $mail->SMTPSecure = MAIL_ENCRYPTION;
        $mail->Port = MAIL_PORT;
        $mail->CharSet = 'UTF-8';
        
        // Recipients
        $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
        $mail->addAddress($email);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Vérification de votre compte - ' . MAIL_FROM_NAME;
        
        $verificationLink = "http://" . $_SERVER['HTTP_HOST'] . "/api/auth/verify-email?token=" . $token;
        
        $mail->Body = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4; }
                    .container { max-width: 600px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                    h2 { color: #4CAF50; margin-top: 0; }
                    .button { display: inline-block; background-color: #4CAF50; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                    .link-box { background-color: #f9f9f9; padding: 10px; border-left: 3px solid #4CAF50; word-break: break-all; margin: 20px 0; }
                    .footer { color: #999; font-size: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h2>Bienvenue sur " . MAIL_FROM_NAME . "!</h2>
                    <p>Merci de vous être inscrit. Veuillez cliquer sur le bouton ci-dessous pour vérifier votre adresse email:</p>
                    <div style='text-align: center;'>
                        <a href='{$verificationLink}' class='button'>Vérifier mon email</a>
                    </div>
                    <p style='color: #666; font-size: 14px;'>Ou copiez ce lien dans votre navigateur:</p>
                    <div class='link-box'>{$verificationLink}</div>
                    <div class='footer'>
                        <p>Ce lien est valide pendant 24 heures.</p>
                        <p>Si vous n'avez pas créé de compte, vous pouvez ignorer cet email.</p>
                    </div>
                </div>
            </body>
            </html>
        ";
        
        $mail->AltBody = "Bienvenue sur " . MAIL_FROM_NAME . "!\n\n"
                       . "Vérifiez votre email en visitant: {$verificationLink}\n\n"
                       . "Ce lien est valide pendant 24 heures.";
        
        $mail->send();
        error_log("✓ Email de vérification envoyé à: {$email}");
        
    } catch (Exception $e) {
        error_log("✗ Erreur d'envoi d'email à {$email}: {$mail->ErrorInfo}");
        // Ne pas bloquer l'inscription si l'email échoue
    }
}
/**
 * Display the login form page.
 */
public function showLoginForm(): void
{
    require_once __DIR__ . '/../../views/authentifications/login.php';
}

/**
 * Display the registration form page.
 */
public function showRegisterForm(): void
{
    require_once __DIR__ . '/../../views/authentifications/register.php';
}

/**
 * Verify reCAPTCHA response.
 */
private function verifyRecaptcha(string $token): bool
{
    if (empty($token)) {
        return false;
    }
    
    $secret = RECAPTCHA_SECRET_KEY;
    $response = @file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$token}"
    );
    
    if ($response === false) {
        return false;
    }
    
    $responseKeys = json_decode($response, true);
    return $responseKeys["success"] ?? false;
}
}
?>