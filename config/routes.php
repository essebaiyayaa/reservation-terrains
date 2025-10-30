<?php

/**
 * Application Routes Configuration
 * 
 * This file defines all the routes for the Football Pitch Booking Management System.
 * Routes are organized by functionality (authentication, users, terrains, etc.)
 * 
 * @package Config
 */

// Initialize router
$router = new Router();

// ==========================================
// AUTHENTICATION ROUTES
// ==========================================

// Registration
$router->get('/register', 'AuthentificationController', 'showRegisterForm');
$router->post('/register', 'AuthentificationController', 'register');

// Login
$router->get('/login', 'AuthentificationController', 'showLoginForm');
$router->post('/login', 'AuthentificationController', 'login');

// Logout
$router->get('/logout', 'AuthentificationController', 'logout');
$router->post('/logout', 'AuthentificationController', 'logout');

// Email Verification
$router->get('/verify-email/{token}', 'AuthentificationController', 'verifyEmail');

// Password Reset
$router->get('/forgot-password', 'AuthentificationController', 'showForgotPasswordForm');
$router->post('/forgot-password', 'AuthentificationController', 'forgotPassword');
$router->get('/reset-password/{token}', 'AuthentificationController', 'showResetPasswordForm');
$router->post('/reset-password', 'AuthentificationController', 'resetPassword');

// ==========================================
// USER MANAGEMENT ROUTES
// ==========================================

// List all users
$router->get('/utilisateurs', 'UtilisateurController', 'index');

// Create user
$router->get('/utilisateurs/create', 'UtilisateurController', 'create');
$router->post('/utilisateurs/store', 'UtilisateurController', 'store');

// Filter users by role (must be before {id} routes)
$router->get('/utilisateurs/role/{role}', 'UtilisateurController', 'byRole');

// Edit user
$router->get('/utilisateurs/edit/{id}', 'UtilisateurController', 'edit');
$router->post('/utilisateurs/update/{id}', 'UtilisateurController', 'update');

// Delete user
$router->post('/utilisateurs/delete/{id}', 'UtilisateurController', 'delete');

// View single user (must be last)
$router->get('/utilisateurs/{id}', 'UtilisateurController', 'show');

// ==========================================
// DASHBOARD ROUTES
// ==========================================

// Home page
$router->get('/', 'HomeController', 'index');

// User dashboard
$router->get('/dashboard', 'DashboardController', 'index');

// Admin dashboard
$router->get('/admin/dashboard', 'AdminController', 'dashboard');

// Manager dashboard
$router->get('/gerant/dashboard', 'GerantController', 'dashboard');

// ==========================================
// TERRAIN ROUTES (À ajouter plus tard)
// ==========================================
// $router->get('/terrains', 'TerrainController', 'index');
// $router->get('/terrains/{id}', 'TerrainController', 'show');
// etc...

// ==========================================
// RESERVATION ROUTES (À ajouter plus tard)
// ==========================================
// $router->get('/reservations', 'ReservationController', 'index');
// $router->post('/reservations/create', 'ReservationController', 'store');
// etc...

// Dispatch the request
$router->dispatch();

?>