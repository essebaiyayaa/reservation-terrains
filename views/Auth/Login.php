<?php 
$title = 'Connexion - FootBooking';

?>

<style>
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .login-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        max-width: 450px;
        width: 100%;
        padding: 2.5rem;
    }

    .login-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .logo {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 2rem;
        font-weight: bold;
        color: #16a34a;
        margin-bottom: 0.5rem;
    }

    .login-header h1 {
        font-size: 1.8rem;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .login-header p {
        color: #6b7280;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #374151;
        font-weight: 500;
    }

    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    input:focus {
        outline: none;
        border-color: #16a34a;
    }

    .password-wrapper {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6b7280;
        cursor: pointer;
        padding: 0.5rem;
    }

    .toggle-password:hover {
        color: #16a34a;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .alert-error {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .alert ul {
        margin-left: 1.5rem;
    }

    .forgot-password {
        text-align: right;
        margin-top: 0.5rem;
    }

    .forgot-password a {
        color: #16a34a;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .forgot-password a:hover {
        text-decoration: underline;
    }

    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: #16a34a;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-submit:hover {
        background: #15803d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
    }

    .btn-submit:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
    }

    .register-link {
        text-align: center;
        margin-top: 1.5rem;
        color: #6b7280;
    }

    .register-link a {
        color: #16a34a;
        text-decoration: none;
        font-weight: 600;
    }

    .register-link a:hover {
        text-decoration: underline;
    }

    .back-home {
        text-align: center;
        margin-top: 1rem;
    }

    .back-home a {
        color: #6b7280;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .back-home a:hover {
        color: #16a34a;
    }

    @media (max-width: 768px) {
        .login-container {
            padding: 1.5rem;
        }
    }
</style>

<div class="login-container">
    <div class="login-header">
        <div class="logo">
            <i class="fas fa-futbol"></i>
            FootBooking
        </div>
        <h1>Connexion</h1>
        <p>Connectez-vous à votre compte</p>
    </div>

    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Erreur(s) détectée(s):</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= UrlHelper::url('login') ?>">
        <div class="form-group">
            <label for="email">Email</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                required 
            />
        </div>

        <div class="form-group">
            <label for="mot_de_passe">Mot de passe</label>
            <div class="password-wrapper">
                <input 
                    type="password" 
                    id="mot_de_passe" 
                    name="mot_de_passe" 
                    required 
                />
                <button type="button" class="toggle-password" onclick="togglePassword('mot_de_passe')">
                    <i class="fas fa-eye" id="eye-mot_de_passe"></i>
                </button>
            </div>
            <div class="forgot-password">
                <a href="<?= UrlHelper::url('forgot-password') ?>">Mot de passe oublié ?</a>
            </div>
        </div>

        <button type="submit" class="btn-submit">
            <i class="fas fa-sign-in-alt"></i> Se connecter
        </button>
    </form>

    <div class="register-link">
        <p>Vous n'avez pas de compte ? <a href="<?= UrlHelper::url('register') ?>">Créer un compte</a></p>
    </div>

    <div class="back-home">
        <a href="<?= UrlHelper::url('/') ?>">
            <i class="fas fa-arrow-left"></i> Retour à l'accueil
        </a>
    </div>
</div>

<script>
    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const eyeIcon = document.getElementById('eye-' + fieldId);
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>

