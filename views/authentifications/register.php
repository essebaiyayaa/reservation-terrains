<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - FootBooking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../../public/assets/css/auth.css">
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
                <h1>Créer un compte</h1>
                <p>Rejoignez FootBooking dès aujourd'hui</p>
            </div>

            <div id="alert-container"></div>

            <form id="register-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">Nom <span class="required">*</span></label>
                        <input type="text" id="nom" name="nom" required minlength="2">
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom <span class="required">*</span></label>
                        <input type="text" id="prenom" name="prenom" required minlength="2">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" placeholder="+212 6XX XXX XXX">
                </div>

                <div class="form-group">
                    <label for="mot_de_passe">Mot de passe <span class="required">*</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="mot_de_passe" name="mot_de_passe" required minlength="8">
                        <button type="button" class="toggle-password" onclick="togglePassword('mot_de_passe')">
                            <i class="fas fa-eye" id="eye-icon-mot_de_passe"></i>
                        </button>
                    </div>
                    <small class="form-hint">Au moins 8 caractères</small>
                </div>

                <div class="form-group">
                    <label for="confirmer_mot_de_passe">Confirmer le mot de passe <span class="required">*</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe" required minlength="8">
                        <button type="button" class="toggle-password" onclick="togglePassword('confirmer_mot_de_passe')">
                            <i class="fas fa-eye" id="eye-icon-confirmer_mot_de_passe"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="terms" required>
                        <span>J'accepte les <a href="/terms" target="_blank">conditions d'utilisation</a> et la <a href="/privacy" target="_blank">politique de confidentialité</a></span>
                    </label>
                </div>

                <div class="recaptcha-wrapper">
                    <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-user-plus"></i> Créer mon compte
                </button>
            </form>

            <div class="auth-links">
                <p>Vous avez déjà un compte ? <a href="/auth/login">Se connecter</a></p>
            </div>

            <div class="back-home">
                <a href="/">
                    <i class="fas fa-arrow-left"></i> Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

    <script src="../../public/assets/js/auth.js"></script>
    <script>
        document.getElementById('register-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = {
                nom: document.getElementById('nom').value,
                prenom: document.getElementById('prenom').value,
                email: document.getElementById('email').value,
                telephone: document.getElementById('telephone').value,
                mot_de_passe: document.getElementById('mot_de_passe').value,
                confirmer_mot_de_passe: document.getElementById('confirmer_mot_de_passe').value,
                'g-recaptcha-response': grecaptcha.getResponse()
            };

            // Validation reCAPTCHA
            if (!formData['g-recaptcha-response']) {
                showAlert('Veuillez cocher la case reCAPTCHA.', 'error');
                return;
            }

            // Validation des mots de passe
            if (formData.mot_de_passe !== formData.confirmer_mot_de_passe) {
                showAlert('Les mots de passe ne correspondent pas.', 'error');
                return;
            }

            try {
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.ok) {
                    showAlert(data.message, 'success');
                    
                    // Redirection vers la page de connexion après 2 secondes
                    setTimeout(() => {
                        window.location.href = '/auth/login';
                    }, 2000);
                } else {
                    if (data.details) {
                        // Afficher les erreurs de validation
                        let errorMessages = Object.values(data.details).join('<br>');
                        showAlert(errorMessages, 'error');
                    } else {
                        showAlert(data.error || 'Erreur lors de l\'inscription', 'error');
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