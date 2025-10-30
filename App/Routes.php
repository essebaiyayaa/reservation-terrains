<?php

$routes = [
    
    // ==========================================
    // PAGE D'ACCUEIL
    // ==========================================
    '' => ['controller' => 'HomeController', 'method' => 'index'],

    // ==========================================
    // AUTHENTICATION ROUTES
    // ==========================================
    
    // Registration
    'register' => [
        'GET' => ['controller' => 'AuthentificationController', 'method' => 'showRegisterForm'],
        'POST' => ['controller' => 'AuthentificationController', 'method' => 'register']
    ],

    // Login
    'login' => [
        'GET' => ['controller' => 'AuthentificationController', 'method' => 'showLoginForm'],
        'POST' => ['controller' => 'AuthentificationController', 'method' => 'login']
    ],

    // Logout
    'logout' => [
        'GET' => ['controller' => 'AuthentificationController', 'method' => 'logout'],
        'POST' => ['controller' => 'AuthentificationController', 'method' => 'logout']
    ],

    // Email Verification
    'verify-email' => ['controller' => 'AuthentificationController', 'method' => 'verifyEmail'], // {token} passé en paramètre

    // Password Reset
    'forgot-password' => [
        'GET' => ['controller' => 'AuthentificationController', 'method' => 'showForgotPasswordForm'],
        'POST' => ['controller' => 'AuthentificationController', 'method' => 'forgotPassword']
    ],
    'reset-password' => [
        'GET' => ['controller' => 'AuthentificationController', 'method' => 'showResetPasswordForm'], // {token} passé en paramètre
        'POST' => ['controller' => 'AuthentificationController', 'method' => 'resetPassword']
    ],

    // ==========================================
    // USER MANAGEMENT ROUTES
    // ==========================================

    // List all users
    'utilisateurs' => ['controller' => 'UtilisateurController', 'method' => 'index'],

    // Create user
    'utilisateurs/create' => ['controller' => 'UtilisateurController', 'method' => 'create'],
    'utilisateurs/store' => ['controller' => 'UtilisateurController', 'method' => 'store'],

    // Filter users by role
    'utilisateurs/role' => ['controller' => 'UtilisateurController', 'method' => 'byRole'], // {role} passé en paramètre

    // Edit user
    'utilisateurs/edit' => ['controller' => 'UtilisateurController', 'method' => 'edit'], // {id} passé en paramètre
    'utilisateurs/update' => ['controller' => 'UtilisateurController', 'method' => 'update'], // {id} passé en paramètre

    // Delete user
    'utilisateurs/delete' => ['controller' => 'UtilisateurController', 'method' => 'delete'], // {id} passé en paramètre

    // View single user
    'utilisateurs/show' => ['controller' => 'UtilisateurController', 'method' => 'show'], // {id} passé en paramètre

    // ==========================================
    // DASHBOARD ROUTES
    // ==========================================

    // User dashboard
    'dashboard' => ['controller' => 'DashboardController', 'method' => 'index'],

    // Admin dashboard
    'admin/dashboard' => ['controller' => 'AdminController', 'method' => 'dashboard'],

    // Manager dashboard
    'gerant/dashboard' => ['controller' => 'GerantController', 'method' => 'dashboard'],

];

?>