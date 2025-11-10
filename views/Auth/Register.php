<?php 
$title = 'Inscription - FootBooking';

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

    .register-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        max-width: 500px;
        width: 100%;
        padding: 2.5rem;
    }

    .register-header {
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

    .register-header h1 {
        font-size: 1.8rem;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .register-header p {
        color: #6b7280;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #374151;
        font-weight: 500;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
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

    .alert-success {
        background: #d1fae5;
        border: 1px solid #a7f3d0;
        color: #065f46;
    }

    .alert ul {
        margin-left: 1.5rem;
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

    .login-link {
        text-align: center;
        margin-top: 1.5rem;
        color: #6b7280;
    }

    .login-link a {
        color: #16a34a;
        text-decoration: none;
        font-weight: 600;
    }

    .login-link a:hover {
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
        .form-row {
            grid-template-columns: 1fr;
        }

        .register-container {
            padding: 1.5rem;
        }
    }
</style>

<div class="register-container">
    <div class="register-header">
        <div class="logo">
            <i class="fas fa-futbol"></i>
            FootBooking
        </div>
        <h1>Créer un compte</h1>
        <p>Rejoignez la communauté FootBooking</p>
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

    <form method="POST" action="<?= UrlHelper::url('register') ?>">
        <div class="form-row">
            <div class="form-group">
                <label for="nom">Nom *</label>
                <input
                    type="text"
                    id="nom"
                    name="nom"
                    value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                    required
                />
            </div>

            <div class="form-group">
                <label for="prenom">Prénom *</label>
                <input
                    type="text"
                    id="prenom"
                    name="prenom"
                    value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"
                    required
                />
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                required
            />
        </div>

        <div class="form-group">
            <label for="telephone">Téléphone *</label>
            <input
                type="tel"
                id="telephone"
                name="telephone"
                value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>"
                required
            />
        </div>

        <div class="form-group">
            <label for="mot_de_passe">Mot de passe * (min. 6 caractères)</label>
            <div class="password-wrapper">
                <input 
                    type="password" 
                    id="mot_de_passe" 
                    name="mot_de_passe" 
                    required 
                />
                <button
                    type="button"
                    class="toggle-password"
                    onclick="togglePassword('mot_de_passe')"
                >
                    <i class="fas fa-eye" id="eye-mot_de_passe"></i>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label for="confirmer_mot_de_passe">Confirmer le mot de passe *</label>
            <div class="password-wrapper">
                <input
                    type="password"
                    id="confirmer_mot_de_passe"
                    name="confirmer_mot_de_passe"
                    required
                />
                <button
                    type="button"
                    class="toggle-password"
                    onclick="togglePassword('confirmer_mot_de_passe')"
                >
                    <i class="fas fa-eye" id="eye-confirmer_mot_de_passe"></i>
                </button>
            </div>
        </div>

        <div class="recaptcha-wrapper">
            <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
        </div>

        <button type="submit" class="btn-submit">
            <i class="fas fa-user-plus"></i> Créer mon compte
        </button>
    </form>

    <div class="login-link">
        <p>Vous avez déjà un compte ? <a href="<?= UrlHelper::url('login') ?>">Se connecter</a></p>
    </div>

    <div class="back-home">
        <a href="<?= UrlHelper::url('/') ?>">
            <i class="fas fa-arrow-left"></i> Retour à l'accueil
        </a>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
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

    // Validation côté client pour la confirmation du mot de passe
    document.querySelector('form').addEventListener('submit', function(e) {
        const password = document.getElementById('mot_de_passe').value;
        const confirmPassword = document.getElementById('confirmer_mot_de_passe').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Les mots de passe ne correspondent pas.');
            return false;
        }
        
        if (password.length < 6) {
            e.preventDefault();
            alert('Le mot de passe doit contenir au moins 6 caractères.');
            return false;
        }
    });
</script>
