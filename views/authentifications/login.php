<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - FootBooking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/public/assets/css/auth.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo">
                    <i class="fas fa-futbol"></i>
                    FootBooking
                </div>
                <h1>Connexion</h1>
                <p>Accédez à votre compte FootBooking</p>
            </div>

            <div id="alert-container"></div>

            <form id="login-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="mot_de_passe">Mot de passe</label>
                    <div class="password-wrapper">
                        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                        <button type="button" class="toggle-password" onclick="togglePassword('mot_de_passe')">
                            <i class="fas fa-eye" id="eye-icon-mot_de_passe"></i>
                        </button>
                    </div>
                    <div class="forgot-password">
                        <a href="/auth/forgot-password">Mot de passe oublié ?</a>
                    </div>
                </div>

                <div class="recaptcha-wrapper">
                    <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-sign-in-alt"></i> Se connecter
                </button>
            </form>

            <div class="auth-links">
                <p>Vous n'avez pas de compte ? <a href="/auth/register">Créer un compte</a></p>
            </div>

            <div class="back-home">
                <a href="/">
                    <i class="fas fa-arrow-left"></i> Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

    <script src="/public/assets/js/auth.js"></script>
    <script>
        document.getElementById('login-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = {
                email: document.getElementById('email').value,
                mot_de_passe: document.getElementById('mot_de_passe').value,
                'g-recaptcha-response': grecaptcha.getResponse()
            };

            // Validation reCAPTCHA
            if (!formData['g-recaptcha-response']) {
                showAlert('Veuillez cocher la case reCAPTCHA.', 'error');
                return;
            }

            try {
                const response = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.ok) {
                    // Stocker le token JWT
                    localStorage.setItem('jwt_token', data.token);
                    localStorage.setItem('user', JSON.stringify(data.user));
                    
                    showAlert(data.message, 'success');
                    
                    // Redirection selon le rôle
                    setTimeout(() => {
                        switch(data.user.role) {
                            case 'admin':
                                window.location.href = '/admin/dashboard';
                                break;
                            case 'gerant_terrain':
                                window.location.href = '/gerant/dashboard';
                                break;
                            default:
                                window.location.href = '/';
                        }
                    }, 1000);
                } else {
                    if (data.email_verified === false) {
                        showAlert(data.error + ' <a href="/auth/resend-verification">Renvoyer l\'email</a>', 'error');
                    } else {
                        showAlert(data.error || 'Erreur de connexion', 'error');
                    }
                    grecaptcha.reset();
                }
            } catch (error) {
                showAlert('Erreur de connexion au serveur', 'error');
                console.error('Error:', error);
            }
        });

        function showAlert(message, type) {
            const alertContainer = document.getElementById('alert-container');
            alertContainer.innerHTML = `
                <div class="alert alert-${type}">
                    ${message}
                </div>
            `;
            
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }
    </script>
</body>
</html>