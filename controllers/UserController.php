<?php

/**
 * Class UserController
 * 
 * Handles all user-related operations including:
 * - User listing and management (admin)
 * - User profile viewing and editing
 * - User deletion
 * 
 * This controller is primarily for managing users after authentication.
 * 
 * @package Controllers
 * @author  Amos Nyirenda
 * @version 1.0
 */
class UserController extends BaseController
{
    private User $userModel;

    /**
     * UserController constructor.
     * 
     * Initializes the User model.
     */
    public function __construct()
    {
        $this->userModel = $this->loadModel('User');
    }

    /**
     * Display a list of all users.
     * Typically accessible by admins only.
     * 
     * @return void
     */
    protected function index(): void
    {
        // Check if user is admin (you should implement session/auth check)
        $this->checkAdminAccess();
        
        $users = $this->userModel->getAll();
        
        $this->renderView('users/index', [
            'users' => $users
        ], 'Gestion des Utilisateurs');
    }

    /**
     * Display a single user's profile.
     * 
     * @param string $id The user ID.
     * @return void
     */
    protected function show(string $id): void
    {
        // Check if user is viewing their own profile or is admin
        $this->checkUserAccess($id);
        
        $user = $this->userModel->getById($id);
        
        if (!$user) {
            $this->redirectWithError('Utilisateur introuvable', '/users');
            return;
        }
        
        $this->renderView('users/show', [
            'user' => $user
        ], 'Profil Utilisateur');
    }

    /**
     * Show the form for creating a new user.
     * Admin only feature.
     * 
     * @return void
     */
    protected function create(): void
    {
        $this->checkAdminAccess();
        
        $this->renderView('users/create', [], 'Créer un Utilisateur');
    }

   /**
 * Process the creation of a new user.
 * 
 * @return void
 */
public function store(): void
{
    $this->checkAdminAccess();
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->redirect('/users/create');
        return;
    }
    
    // Validate input
    $errors = $this->validateUserData($_POST);
    
    if (!empty($errors)) {
        $this->renderView('users/create', [
            'errors' => $errors,
            'old' => $_POST
        ], 'Créer un Utilisateur');
        return;
    }
    
    // Check if email already exists
    if ($this->userModel->emailExists($_POST['email'])) {
        $this->renderView('users/create', [
            'errors' => ['email' => 'Cet email est déjà utilisé'],
            'old' => $_POST
        ], 'Créer un Utilisateur');
        return;
    }
    
    // Prepare user data array
    $userData = [
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'email' => $_POST['email'],
        'telephone' => $_POST['telephone'] ?? null,
        'mot_de_passe' => $_POST['mot_de_passe'], // Will be hashed in add()
        'role' => $_POST['role'] ?? 'client',
        'verification_token' => null // Manually created users don't need verification
    ];
    
    // Add user with data array
    if ($this->userModel->add($userData)) {
        $this->redirectWithSuccess('Utilisateur créé avec succès', '/users');
    } else {
        $this->redirectWithError('Erreur lors de la création de l\'utilisateur', '/users/create');
    }
}
    /**
     * Show the form for editing a user.
     * 
     * @param string $id The user ID.
     * @return void
     */
    protected function edit(string $id): void
    {
        $this->checkUserAccess($id);
        
        $user = $this->userModel->getById($id);
        
        if (!$user) {
            $this->redirectWithError('Utilisateur introuvable', '/users');
            return;
        }
        
        $this->renderView('users/edit', [
            'user' => $user
        ], 'Modifier le Profil');
    }

    /**
     * Process the update of a user.
     * 
     * @param string $id The user ID.
     * @return void
     */
    public function update(string $id): void
    {
        $this->checkUserAccess($id);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect("/users/edit/{$id}");
            return;
        }
        
        // Validate input
        $errors = $this->validateUserData($_POST, $id);
        
        if (!empty($errors)) {
            $this->renderView('users/edit', [
                'errors' => $errors,
                'user' => $_POST,
                'user' => array_merge($this->userModel->getById($id), $_POST)
            ], 'Modifier le Profil');
            return;
        }
        
        // Check if email already exists (excluding current user)
        if ($this->userModel->emailExists($_POST['email'], (int)$id)) {
            $this->renderView('users/edit', [
                'errors' => ['email' => 'Cet email est déjà utilisé'],
                'user' => array_merge($this->userModel->getById($id), $_POST)
            ], 'Modifier le Profil');
            return;
        }
        
        // Prepare update data
        $updateData = [
            'nom' => $_POST['nom'],
            'prenom' => $_POST['prenom'],
            'email' => $_POST['email'],
            'telephone' => $_POST['telephone'] ?? null
        ];
        
        // Only admin can change roles
        if ($this->isAdmin() && isset($_POST['role'])) {
            $updateData['role'] = $_POST['role'];
        }
        
        // Update password if provided
        if (!empty($_POST['mot_de_passe'])) {
            $updateData['mot_de_passe'] = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT);
        }
        
        if ($this->userModel->update($id, $updateData)) {
            $this->redirectWithSuccess('Profil mis à jour avec succès', "/users/show/{$id}");
        } else {
            $this->redirectWithError('Erreur lors de la mise à jour', "/users/edit/{$id}");
        }
    }

    /**
     * Delete a user.
     * 
     * @param string $id The user ID.
     * @return void
     */
    protected function delete(string $id): void
    {
        $this->checkAdminAccess();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/users');
            return;
        }
        
        // Prevent self-deletion
        if ($this->getCurrentUserId() === (int)$id) {
            $this->redirectWithError('Vous ne pouvez pas supprimer votre propre compte', '/users');
            return;
        }
        
        if ($this->userModel->delete($id)) {
            $this->redirectWithSuccess('Utilisateur supprimé avec succès', '/users');
        } else {
            $this->redirectWithError('Erreur lors de la suppression', '/users');
        }
    }

    /**
     * Validates user data.
     * 
     * @param array $data The data to validate.
     * @param string|null $userId Optional user ID for updates.
     * @return array Array of validation errors.
     */
    private function validateUserData(array $data, ?string $userId = null): array
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
        
        // Validate password (only for new users or when password is provided)
        if (!$userId || !empty($data['mot_de_passe'])) {
            if (empty($data['mot_de_passe']) || strlen($data['mot_de_passe']) < 8) {
                $errors['mot_de_passe'] = 'Le mot de passe doit contenir au moins 8 caractères';
            }
            
            // Confirm password
            if (isset($data['confirmer_mot_de_passe']) && $data['mot_de_passe'] !== $data['confirmer_mot_de_passe']) {
                $errors['confirmer_mot_de_passe'] = 'Les mots de passe ne correspondent pas';
            }
        }
        
        // Validate telephone (optional but format if provided)
        if (!empty($data['telephone']) && !preg_match('/^[0-9\s\+\-\(\)]+$/', $data['telephone'])) {
            $errors['telephone'] = 'Numéro de téléphone invalide';
        }
        
        // Validate role
        if (isset($data['role']) && !in_array($data['role'], ['client', 'admin', 'gerant_terrain'])) {
            $errors['role'] = 'Rôle invalide';
        }
        
        return $errors;
    }

    /**
     * Helper methods for access control using JWT
     */
    private function checkAdminAccess(): void
    {
        if (!$this->isAdmin()) {
            $this->jsonResponse(['error' => 'Accès refusé'], 403);
        }
    }

    private function checkUserAccess(string $userId): void
    {
        if (!$this->isAdmin() && $this->getCurrentUserId() !== (int)$userId) {
            $this->jsonResponse(['error' => 'Accès refusé'], 403);
        }
    }

    private function isAdmin(): bool
    {
        return JWTManager::hasRole('admin');
    }

    private function getCurrentUserId(): ?int
    {
        $user = JWTManager::getCurrentUser();
        return $user ? $user['user_id'] : null;
    }

    private function isAuthenticated(): bool
    {
        return JWTManager::isAuthenticated();
    }

    /**
     * Redirect helpers
     */
    private function redirect(string $path): void
    {
        header("Location: {$path}");
        exit;
    }

    private function redirectWithSuccess(string $message, string $path): void
    {
        $_SESSION['success'] = $message;
        $this->redirect($path);
    }

    private function redirectWithError(string $message, string $path): void
    {
        $_SESSION['error'] = $message;
        $this->redirect($path);
    }

/**
 * Change user password.
 * 
 * @param string $id The user ID.
 * @return void
 */
public function changePassword(string $id): void
{
    $this->checkUserAccess($id);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->jsonResponse(['error' => 'Méthode non autorisée'], 405);
        return;
    }

    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    $currentPassword = $input['current_password'] ?? '';
    $newPassword = $input['mot_de_passe'] ?? '';
    $confirmPassword = $input['confirmer_mot_de_passe'] ?? '';

    // Validation
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $this->jsonResponse(['error' => 'Tous les champs sont requis'], 400);
        return;
    }

    if ($newPassword !== $confirmPassword) {
        $this->jsonResponse(['error' => 'Les mots de passe ne correspondent pas'], 400);
        return;
    }

    if (strlen($newPassword) < 8) {
        $this->jsonResponse(['error' => 'Le nouveau mot de passe doit contenir au moins 8 caractères'], 400);
        return;
    }

    // Récupérer l'utilisateur
    $user = $this->userModel->getById($id);
    
    if (!$user) {
        $this->jsonResponse(['error' => 'Utilisateur introuvable'], 404);
        return;
    }

    // Vérifier le mot de passe actuel
    $userWithPassword = $this->userModel->findByEmail($user['email']);
    
    if (!$userWithPassword || !password_verify($currentPassword, $userWithPassword['mot_de_passe'])) {
        $this->jsonResponse(['error' => 'Mot de passe actuel incorrect'], 401);
        return;
    }

    // Mettre à jour le mot de passe
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    
    if ($this->userModel->update($id, ['mot_de_passe' => $hashedPassword])) {
        $this->jsonResponse(['message' => 'Mot de passe modifié avec succès'], 200);
    } else {
        $this->jsonResponse(['error' => 'Erreur lors du changement de mot de passe'], 500);
    }
}
}

?>