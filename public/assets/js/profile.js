/**
 * profile.js
 * Gestion du profil utilisateur
 */

// Vérifier l'authentification au chargement
document.addEventListener('DOMContentLoaded', () => {
    if (!requireAuth()) {
        return;
    }
    
    initializeProfileHandlers();
});

/**
 * Initialise les gestionnaires d'événements
 */
function initializeProfileHandlers() {
    // Formulaire de modification du profil
    const editForm = document.getElementById('edit-profile-form');
    if (editForm) {
        editForm.addEventListener('submit', handleProfileUpdate);
    }

    // Formulaire de changement de mot de passe
    const passwordForm = document.getElementById('change-password-form');
    if (passwordForm) {
        passwordForm.addEventListener('submit', handlePasswordChange);
    }
}

/**
 * Gère la mise à jour du profil
 */
async function handleProfileUpdate(e) {
    e.preventDefault();
    
    const user = getCurrentUser();
    if (!user) return;

    const formData = {
        nom: document.getElementById('edit-nom').value,
        prenom: document.getElementById('edit-prenom').value,
        email: document.getElementById('edit-email').value,
        telephone: document.getElementById('edit-telephone').value
    };

    // Validation
    if (!formData.nom || formData.nom.length < 2) {
        showAlert('Le nom doit contenir au moins 2 caractères', 'error');
        return;
    }

    if (!formData.prenom || formData.prenom.length < 2) {
        showAlert('Le prénom doit contenir au moins 2 caractères', 'error');
        return;
    }

    if (!validateEmail(formData.email)) {
        showAlert('Adresse email invalide', 'error');
        return;
    }

    try {
        const response = await authenticatedFetch(`/api/users/update/${user.id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (response.ok) {
            showAlert('Profil mis à jour avec succès', 'success');
            
            // Mettre à jour les informations locales
            const updatedUser = {
                ...user,
                nom: formData.nom,
                prenom: formData.prenom,
                email: formData.email,
                telephone: formData.telephone
            };
            localStorage.setItem('user', JSON.stringify(updatedUser));
            
            // Recharger le profil
            setTimeout(() => {
                loadProfile();
            }, 1000);
        } else {
            if (data.details) {
                let errorMessages = Object.values(data.details).join('<br>');
                showAlert(errorMessages, 'error');
            } else {
                showAlert(data.error || 'Erreur lors de la mise à jour', 'error');
            }
        }
    } catch (error) {
        showAlert('Erreur de connexion au serveur', 'error');
        console.error('Error:', error);
    }
}

/**
 * Gère le changement de mot de passe
 */
async function handlePasswordChange(e) {
    e.preventDefault();
    
    const user = getCurrentUser();
    if (!user) return;

    const currentPassword = document.getElementById('current-password').value;
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    // Validation
    if (newPassword !== confirmPassword) {
        showAlert('Les mots de passe ne correspondent pas', 'error');
        return;
    }

    const passwordValidation = validatePassword(newPassword);
    if (!passwordValidation.valid) {
        showAlert(passwordValidation.errors.join('<br>'), 'error');
        return;
    }

    try {
        const response = await authenticatedFetch(`/api/users/change-password/${user.id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                current_password: currentPassword,
                mot_de_passe: newPassword,
                confirmer_mot_de_passe: confirmPassword
            })
        });

        const data = await response.json();

        if (response.ok) {
            showAlert('Mot de passe modifié avec succès', 'success');
            
            // Réinitialiser le formulaire
            document.getElementById('change-password-form').reset();
            
            // Forcer la reconnexion après 2 secondes
            setTimeout(() => {
                logout();
            }, 2000);
        } else {
            showAlert(data.error || 'Erreur lors du changement de mot de passe', 'error');
        }
    } catch (error) {
        showAlert('Erreur de connexion au serveur', 'error');
        console.error('Error:', error);
    }
}

/**
 * Confirme et supprime le compte
 */
async function confirmDeleteAccount() {
    const confirmed = confirm(
        'Êtes-vous sûr de vouloir supprimer votre compte ?\n\n' +
        'Cette action est irréversible et toutes vos données seront perdues.'
    );

    if (!confirmed) return;

    const doubleConfirm = confirm(
        'Dernière confirmation : voulez-vous vraiment supprimer votre compte ?'
    );

    if (!doubleConfirm) return;

    const user = getCurrentUser();
    if (!user) return;

    try {
        const response = await authenticatedFetch(`/api/users/delete/${user.id}`, {
            method: 'POST'
        });

        if (response.ok) {
            showAlert('Compte supprimé avec succès', 'success');
            
            setTimeout(() => {
                removeAuthToken();
                window.location.href = '/';
            }, 2000);
        } else {
            const data = await response.json();
            showAlert(data.error || 'Erreur lors de la suppression du compte', 'error');
        }
    } catch (error) {
        showAlert('Erreur de connexion au serveur', 'error');
        console.error('Error:', error);
    }
}

/**
 * Charge et affiche les informations du profil
 */
async function loadProfile() {
    const token = getAuthToken();
    
    if (!token) {
        window.location.href = '/auth/login';
        return;
    }

    try {
        const response = await authenticatedFetch('/api/auth/me');

        if (response.ok) {
            const data = await response.json();
            displayProfile(data.user);
        } else {
            removeAuthToken();
            window.location.href = '/auth/login';
        }
    } catch (error) {
        console.error('Error loading profile:', error);
        showAlert('Erreur lors du chargement du profil', 'error');
    }
}

/**
 * Affiche les informations du profil
 */
function displayProfile(user) {
    // Stocker dans localStorage pour usage local
    localStorage.setItem('user', JSON.stringify(user));

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
    
    const verifiedElement = document.getElementById('info-verified');
    if (user.email_verified) {
        verifiedElement.innerHTML = '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Vérifié</span>';
    } else {
        verifiedElement.innerHTML = '<span class="badge badge-warning"><i class="fas fa-exclamation-circle"></i> Non vérifié</span>';
    }
    
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

/**
 * Obtient le libellé du rôle
 */
function getRoleLabel(role) {
    const roles = {
        'admin': 'Administrateur',
        'gerant_terrain': 'Gérant de terrain',
        'client': 'Client'
    };
    return roles[role] || role;
}

/**
 * Formate une date
 */
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

/**
 * Change d'onglet
 */
function showTab(tabName) {
    // Masquer tous les onglets
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });
    
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });

    // Afficher l'onglet sélectionné
    const targetTab = document.getElementById(`tab-${tabName}`);
    if (targetTab) {
        targetTab.classList.add('active');
    }
    
    // Activer le bouton
    const buttons = document.querySelectorAll('.tab-button');
    buttons.forEach(btn => {
        if (btn.textContent.toLowerCase().includes(tabName) || 
            btn.onclick.toString().includes(tabName)) {
            btn.classList.add('active');
        }
    });
}