<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - FootBooking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/public/assets/css/auth.css">
    <link rel="stylesheet" href="/public/assets/css/profile.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h1 id="profile-name">Chargement...</h1>
                <p id="profile-email"></p>
                <span class="profile-badge" id="profile-role"></span>
            </div>

            <div id="alert-container"></div>

            <div class="profile-tabs">
                <button class="tab-button active" onclick="showTab('info')">
                    <i class="fas fa-info-circle"></i> Informations
                </button>
                <button class="tab-button" onclick="showTab('edit')">
                    <i class="fas fa-edit"></i> Modifier
                </button>
                <button class="tab-button" onclick="showTab('security')">
                    <i class="fas fa-lock"></i> Sécurité
                </button>
            </div>

            <!-- Onglet Informations -->
            <div id="tab-info" class="tab-content active">
                <div class="info-section">
                    <h3><i class="fas fa-user"></i> Informations personnelles</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Nom</label>
                            <p id="info-nom">-</p>
                        </div>
                        <div class="info-item">
                            <label>Prénom</label>
                            <p id="info-prenom">-</p>
                        </div>
                        <div class="info-item">
                            <label>Email</label>
                            <p id="info-email">-</p>
                        </div>
                        <div class="info-item">
                            <label>Téléphone</label>
                            <p id="info-telephone">-</p>
                        </div>
                        <div class="info-item">
                            <label>Rôle</label>
                            <p id="info-role">-</p>
                        </div>
                        <div class="info-item">
                            <label>Email vérifié</label>
                            <p id="info-verified">-</p>
                        </div>
                    </div>
                </div>

                <div class="info-section">
                    <h3><i class="fas fa-clock"></i> Activité</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Membre depuis</label>
                            <p id="info-created">-</p>
                        </div>
                        <div class="info-item">
                            <label>Dernière connexion</label>
                            <p id="info-last-login">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Onglet Modifier -->
            <div id="tab-edit" class="tab-content">
                <form id="edit-profile-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit-nom">Nom <span class="required">*</span></label>
                            <input type="text" id="edit-nom" name="nom" required minlength="2">
                        </div>
                        <div class="form-group">
                            <label for="edit-prenom">Prénom <span class="required">*</span></label>
                            <input type="text" id="edit-prenom" name="prenom" required minlength="2">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit-email">Email <span class="required">*</span></label>
                        <input type="email" id="edit-email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="edit-telephone">Téléphone</label>
                        <input type="tel" id="edit-telephone" name="telephone" placeholder="+212 6XX XXX XXX">
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </form>
            </div>

            <!-- Onglet Sécurité -->
            <div id="tab-security" class="tab-content">
                <form id="change-password-form">
                    <h3><i class="fas fa-key"></i> Changer le mot de passe</h3>
                    
                    <div class="form-group">
                        <label for="current-password">Mot de passe actuel <span class="required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" id="current-password" name="current_password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword('current-password')">
                                <i class="fas fa-eye" id="eye-icon-current-password"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="new-password">Nouveau mot de passe <span class="required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" id="new-password" name="mot_de_passe" required minlength="8">
                            <button type="button" class="toggle-password" onclick="togglePassword('new-password')">
                                <i class="fas fa-eye" id="eye-icon-new-password"></i>
                            </button>
                        </div>
                        <small class="form-hint">Au moins 8 caractères</small>
                    </div>

                    <div class="form-group">
                        <label for="confirm-password">Confirmer le nouveau mot de passe <span class="required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" id="confirm-password" name="confirmer_mot_de_passe" required minlength="8">
                            <button type="button" class="toggle-password" onclick="togglePassword('confirm-password')">
                                <i class="fas fa-eye" id="eye-icon-confirm-password"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit btn-danger">
                        <i class="fas fa-lock"></i> Changer le mot de passe
                    </button>
                </form>

                <div class="danger-zone">
                    <h3><i class="fas fa-exclamation-triangle"></i> Zone dangereuse</h3>
                    <p>La suppression de votre compte est irréversible. Toutes vos données seront perdues.</p>
                    <button class="btn-danger" onclick="confirmDeleteAccount()">
                        <i class="fas fa-trash"></i> Supprimer mon compte
                    </button>
                </div>
            </div>

            <div class="back-home">
                <a href="/">
                    <i class="fas fa-arrow-left"></i> Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

    <script src="/public/assets/js/auth.js"></script>
    <script src="/public/assets/js/profile.js"></script>
    <script>
        // Charger les informations du profil au chargement de la page
        document.addEventListener('DOMContentLoaded', loadProfile);

        async function loadProfile() {
            const token = localStorage.getItem('jwt_token');
            
            if (!token) {
                window.location.href = '/auth/login';
                return;
            }

            try {
                const response = await fetch('/api/auth/me', {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    displayProfile(data.user);
                } else {
                    localStorage.removeItem('jwt_token');
                    window.location.href = '/auth/login';
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Erreur lors du chargement du profil', 'error');
            }
        }

        function displayProfile(user) {
            // Header
            document.getElementById('profile-name').textContent = `${user.prenom} ${user.nom}`;
            document.getElementById('profile-email').textContent = user.email;
            document.getElementById('profile-role').textContent = getRoleLabel(user.role);

            // Info tab
            document.getElementById('info-nom').textContent = user.nom;
            document.getElementById('info-prenom').textContent = user.prenom;
            document.getElementById('info-email').textContent = user.email;
            document.getElementById('info-telephone').textContent = user.telephone || 'Non renseigné';
            document.getElementById('info-role').textContent = getRoleLabel(user.role);
            document.getElementById('info-verified').innerHTML = user.email_verified ? 
                '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Vérifié</span>' : 
                '<span class="badge badge-warning"><i class="fas fa-exclamation-circle"></i> Non vérifié</span>';
            
            if (user.date_creation) {
                document.getElementById('info-created').textContent = formatDate(user.date_creation);
            }
            if (user.date_derniere_connexion) {
                document.getElementById('info-last-login').textContent = formatDate(user.date_derniere_connexion);
            }

            // Edit form
            document.getElementById('edit-nom').value = user.nom;
            document.getElementById('edit-prenom').value = user.prenom;
            document.getElementById('edit-email').value = user.email;
            document.getElementById('edit-telephone').value = user.telephone || '';
        }

        function getRoleLabel(role) {
            const roles = {
                'admin': 'Administrateur',
                'gerant_terrain': 'Gérant de terrain',
                'client': 'Client'
            };
            return roles[role] || role;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(`tab-${tabName}`).classList.add('active');
            event.target.closest('.tab-button').classList.add('active');
        }

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