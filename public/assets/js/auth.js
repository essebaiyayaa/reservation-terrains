/**
 * auth.js
 * Fonctions utilitaires pour l'authentification
 */

/**
 * Toggle password visibility
 * @param {string} fieldId - ID du champ de mot de passe
 */
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(`eye-icon-${fieldId}`);
    
    if (field && eye) {
        if (field.type === 'password') {
            field.type = 'text';
            eye.classList.remove('fa-eye');
            eye.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            eye.classList.remove('fa-eye-slash');
            eye.classList.add('fa-eye');
        }
    }
}

/**
 * Récupère le token JWT depuis localStorage
 * @returns {string|null}
 */
function getAuthToken() {
    return localStorage.getItem('jwt_token');
}

/**
 * Enregistre le token JWT dans localStorage
 * @param {string} token
 */
function setAuthToken(token) {
    localStorage.setItem('jwt_token', token);
}

/**
 * Supprime le token JWT de localStorage
 */
function removeAuthToken() {
    localStorage.removeItem('jwt_token');
    localStorage.removeItem('user');
}

/**
 * Récupère les informations utilisateur depuis localStorage
 * @returns {object|null}
 */
function getCurrentUser() {
    const userJson = localStorage.getItem('user');
    return userJson ? JSON.parse(userJson) : null;
}

/**
 * Vérifie si l'utilisateur est authentifié
 * @returns {boolean}
 */
function isAuthenticated() {
    return !!getAuthToken();
}

/**
 * Vérifie si l'utilisateur a un rôle spécifique
 * @param {string} role
 * @returns {boolean}
 */
function hasRole(role) {
    const user = getCurrentUser();
    return user && user.role === role;
}

/**
 * Déconnecte l'utilisateur
 */
async function logout() {
    try {
        const token = getAuthToken();
        
        if (token) {
            await fetch('/api/auth/logout', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
        }
    } catch (error) {
        console.error('Logout error:', error);
    } finally {
        removeAuthToken();
        window.location.href = '/auth/login';
    }
}

/**
 * Rafraîchit le token JWT
 * @returns {Promise<boolean>}
 */
async function refreshToken() {
    try {
        const token = getAuthToken();
        
        if (!token) {
            return false;
        }

        const response = await fetch('/api/auth/refresh-token', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        if (response.ok) {
            const data = await response.json();
            setAuthToken(data.token);
            return true;
        }
        
        return false;
    } catch (error) {
        console.error('Token refresh error:', error);
        return false;
    }
}

/**
 * Fait une requête API authentifiée
 * @param {string} url
 * @param {object} options
 * @returns {Promise<Response>}
 */
async function authenticatedFetch(url, options = {}) {
    const token = getAuthToken();
    
    if (!token) {
        throw new Error('No authentication token');
    }

    const headers = {
        ...options.headers,
        'Authorization': `Bearer ${token}`
    };

    const response = await fetch(url, {
        ...options,
        headers
    });

    // Si le token est expiré, essayer de le rafraîchir
    if (response.status === 401) {
        const refreshed = await refreshToken();
        
        if (refreshed) {
            // Réessayer la requête avec le nouveau token
            const newToken = getAuthToken();
            headers.Authorization = `Bearer ${newToken}`;
            return fetch(url, { ...options, headers });
        } else {
            // Rediriger vers la page de connexion
            removeAuthToken();
            window.location.href = '/auth/login';
            throw new Error('Authentication failed');
        }
    }

    return response;
}

/**
 * Valide un email
 * @param {string} email
 * @returns {boolean}
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Valide un mot de passe
 * @param {string} password
 * @returns {object} {valid: boolean, errors: string[]}
 */
function validatePassword(password) {
    const errors = [];
    
    if (password.length < 8) {
        errors.push('Le mot de passe doit contenir au moins 8 caractères');
    }
    
    if (!/[A-Z]/.test(password)) {
        errors.push('Le mot de passe doit contenir au moins une majuscule');
    }
    
    if (!/[a-z]/.test(password)) {
        errors.push('Le mot de passe doit contenir au moins une minuscule');
    }
    
    if (!/[0-9]/.test(password)) {
        errors.push('Le mot de passe doit contenir au moins un chiffre');
    }
    
    return {
        valid: errors.length === 0,
        errors
    };
}

/**
 * Affiche une alerte
 * @param {string} message
 * @param {string} type - 'success', 'error', 'warning', 'info'
 * @param {number} duration - Durée en ms (0 = permanent)
 */
function showAlert(message, type = 'info', duration = 5000) {
    const alertContainer = document.getElementById('alert-container');
    
    if (!alertContainer) {
        console.error('Alert container not found');
        return;
    }

    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.innerHTML = message;
    
    alertContainer.appendChild(alert);

    if (duration > 0) {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 300);
        }, duration);
    }
}

/**
 * Protection des pages nécessitant une authentification
 */
function requireAuth() {
    if (!isAuthenticated()) {
        window.location.href = '/auth/login';
        return false;
    }
    return true;
}

/**
 * Protection des pages nécessitant un rôle spécifique
 * @param {string} requiredRole
 */
function requireRole(requiredRole) {
    if (!isAuthenticated()) {
        window.location.href = '/auth/login';
        return false;
    }
    
    if (!hasRole(requiredRole)) {
        window.location.href = '/';
        return false;
    }
    
    return true;
}

// Export pour utilisation dans d'autres scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        togglePassword,
        getAuthToken,
        setAuthToken,
        removeAuthToken,
        getCurrentUser,
        isAuthenticated,
        hasRole,
        logout,
        refreshToken,
        authenticatedFetch,
        validateEmail,
        validatePassword,
        showAlert,
        requireAuth,
        requireRole
    };
}