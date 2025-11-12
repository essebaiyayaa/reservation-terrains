<?php

/**
 * Application Routes Configuration
 * 
 * Format: 'route' => ['controller' => 'ControllerName', 'method' => 'methodName']
 * 
 * Notes:
 * - Les routes avec {id} sont des routes dynamiques
 * - Les méthodes create/store suivent le pattern RESTful
 * - Les routes admin nécessitent une authentification et rôle admin
 * - Les routes gerant nécessitent une authentification et rôle gerant_terrain
 */

$routes = [
    
    // ==================== HOME ROUTE ====================
    '' => ['controller' => 'HomeController', 'method' => 'index'],
    'home' => ['controller' => 'HomeController', 'method' => 'index'],
    
    // ==================== AUTH ROUTES ====================
    // Registration
    'register' => ['controller' => 'AuthController', 'method' => 'create'],
    
    // Login
    'login' => ['controller' => 'AuthController', 'method' => 'login'],
    
    // Logout
    'logout' => ['controller' => 'AuthController', 'method' => 'logout'],
    
    // Email verification
    'verify' => ['controller' => 'AuthController', 'method' => 'verify'],
    'verify/submit' => ['controller' => 'AuthController', 'method' => 'verifySubmit'],
// ==================== NEWSLETTER ROUTES ====================
'newsletter' => ['controller' => 'NewsletterController', 'method' => 'index'],
'newsletter/create' => ['controller' => 'NewsletterController', 'method' => 'create'],
'newsletter/show/{id}' => ['controller' => 'NewsletterController', 'method' => 'show'],
'newsletter/delete/{id}' => ['controller' => 'NewsletterController', 'method' => 'delete'],
    // ==================== ADMIN ROUTES ====================
    // Dashboard principal admin
    'admin' => ['controller' => 'AdminController', 'method' => 'index'],
    'admin/dashboard' => ['controller' => 'AdminController', 'method' => 'index'],
    
    // Gestion des terrains (vue admin)
    'admin/terrains' => ['controller' => 'AdminController', 'method' => 'terrains'],
    
    // Gestion des réservations (vue admin)
    'admin/reservations' => ['controller' => 'AdminController', 'method' => 'reservations'],
    
    // Gestion des gérants
    'admin/gerants' => ['controller' => 'AdminController', 'method' => 'gerants'],
    'admin/gerant/create' => ['controller' => 'AdminController', 'method' => 'createGerant'],
     'admin/tournois' => ['controller' => 'AdminController', 'method' => 'tournois'],
    'admin/gerant/delete/{id}' => ['controller' => 'AdminController', 'method' => 'deleteGerant'],



    // ==================== TERRAIN ROUTES ====================
    
    // Public routes - Consultation des terrains
    'terrains' => ['controller' => 'TerrainController', 'method' => 'index'],
    'terrain/id/{id}' => ['controller' => 'TerrainController', 'method' => 'show'],
    'terrain/id' => ['controller' => 'TerrainController', 'method' => 'show'],
    'terrain/edit' => ['controller' => 'TerrainController', 'method' => 'edit'],
    // Admin/Gerant routes - Gestion CRUD des terrains
    // CREATE: Afficher le formulaire et traiter la soumission
    'terrain/create' => ['controller' => 'TerrainController', 'method' => 'create'],
    
    // EDIT: Afficher le formulaire et traiter la modification
    'terrain/edit/{id}' => ['controller' => 'TerrainController', 'method' => 'edit'],
    
    // DELETE: Supprimer un terrain
    'terrain/delete/{id}' => ['controller' => 'TerrainController', 'method' => 'delete'],
    
    // Gerant routes - Gestion des options supplémentaires
    'terrain/options/{id}' => ['controller' => 'TerrainController', 'method' => 'manageOptions'],
    'terrain/option/delete/{id}' => ['controller' => 'TerrainController', 'method' => 'deleteOption'],

    // ==================== RESERVATION ROUTES ====================
    // CREATE: Afficher le formulaire de réservation
    'reservation/create/{terrain_id}' => ['controller' => 'ReservationController', 'method' => 'create'],
    
    // STORE: Traiter la soumission de réservation
    'reservation/store' => ['controller' => 'ReservationController', 'method' => 'store'],
    
    // SHOW: Afficher les détails d'une réservation
    'reservation/{id}' => ['controller' => 'ReservationController', 'method' => 'show'],
    
    // CANCEL: Annuler une réservation
    'reservation/cancel/{id}' => ['controller' => 'ReservationController', 'method' => 'cancel'],
    
    // Liste des réservations par utilisateur
    'mes-reservations' => ['controller' => 'ClientController', 'method' => 'myReservations'],
    'reservation' => ['controller' => 'ClientController', 'method' => 'faireReservation'],
    'facture/id' =>['controller' => 'ClientController', 'method' => 'facturer'],
    'search/terrains' =>['controller' => 'ClientController', 'method' => 'searchTerrains'],
    'available/slots' => ['controller' => 'ClientController', 'method' => 'getAvailableSlots'],
    'modifier/id' => ['controller' => 'ClientController', 'method' => 'modifierReservation'],
    'annuler/id' => ['controller' => 'ClientController', 'method' => 'annulerReservation'],

    // ==================== DASHBOARD ROUTES ====================
    // Dashboards spécifiques par rôle
    'dashboard' => ['controller' => 'DashboardController', 'method' => 'index'],
    'dashboard/admin' => ['controller' => 'DashboardController', 'method' => 'admin'],
    'dashboard/gerant' => ['controller' => 'DashboardController', 'method' => 'gerant'],
    'dashboard/client' => ['controller' => 'DashboardController', 'method' => 'client'],

    // ==================== GERANT ROUTES (optionnel) ====================
    // Routes spécifiques gérant si besoin
    'gerant/terrains' => ['controller' => 'GerantController', 'method' => 'terrains'],

    'gerant/dashboard' => ['controller' => 'GerantController', 'method' => 'index'],

    'gerant/reservations' => ['controller' => 'GerantController', 'method' => 'reservations'],

    // ==================== TOURNOI ROUTES ====================
    
    // Public / Client Routes
    'tournoi' => ['controller' => 'TournoiController', 'method' => 'index'],
    'tournoi/index' => ['controller' => 'TournoiController', 'method' => 'index'],
    'tournoi/participer' => ['controller' => 'TournoiController', 'method' => 'participer'],
    'tournoi/inscrire' => ['controller' => 'TournoiController', 'method' => 'inscrire'],
    'tournoi/mesparticipations' => ['controller' => 'TournoiController', 'method' => 'mesparticipations'],
// ==================== NEWSLETTER ROUTES ====================
// Accessible par Admin et Gérant uniquement
'newsletter' => ['controller' => 'NewsletterController', 'method' => 'index'],
'newsletter/create' => ['controller' => 'NewsletterController', 'method' => 'create'],
'newsletter/show/{id}' => ['controller' => 'NewsletterController', 'method' => 'show'],
'newsletter/delete/{id}' => ['controller' => 'NewsletterController', 'method' => 'delete'],
    // Gerant Routes - CRUD Operations
    'tournoi/mestournois' => ['controller' => 'TournoiController', 'method' => 'mestournois'],
    'tournoi/create' => ['controller' => 'TournoiController', 'method' => 'create'],
    'tournoi/show' => ['controller' => 'TournoiController', 'method' => 'show'],
    'tournoi/edit' => ['controller' => 'TournoiController', 'method' => 'edit'],
    'tournoi/delete' => ['controller' => 'TournoiController', 'method' => 'delete'],

    // ==================== TEST ROUTES (Development) ====================
    // À supprimer en production
    'books' => ['controller' => 'BookController', 'method' => 'index'],
    'book/{id}' => ['controller' => 'BookController', 'method' => 'show'],
    'book/add' => ['controller' => 'BookController', 'method' => 'create'],
    'book/delete/{id}' => ['controller' => 'BookController', 'method' => 'delete'],
    'book/update/{id}' => ['controller' => 'BookController', 'method' => 'edit'],
    
    // ==================== ERROR ROUTES ====================
    '404' => ['controller' => 'ErrorController', 'method' => 'notFound'],
    '403' => ['controller' => 'ErrorController', 'method' => 'forbidden'],
    '500' => ['controller' => 'ErrorController', 'method' => 'serverError'],
];

return $routes;