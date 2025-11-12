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
    // ==================== PUBLIC ROUTES ====================
    
    // === Home ===
    '' => ['controller' => 'HomeController', 'method' => 'index'],
    'home' => ['controller' => 'HomeController', 'method' => 'index'],
    
    // === Authentication ===
    'register' => ['controller' => 'AuthController', 'method' => 'create'],
    'login' => ['controller' => 'AuthController', 'method' => 'login'],
    'logout' => ['controller' => 'AuthController', 'method' => 'logout'],
    'verify' => ['controller' => 'AuthController', 'method' => 'verify'],
    'verify/submit' => ['controller' => 'AuthController', 'method' => 'verifySubmit'],
    
    // === Terrains (Public) ===
    'terrains' => ['controller' => 'TerrainController', 'method' => 'index'],
    'terrain/id/{id}' => ['controller' => 'TerrainController', 'method' => 'show'],
    'terrain/id' => ['controller' => 'TerrainController', 'method' => 'show'],
    
    // === Tournois (Public) ===
    'tournoi' => ['controller' => 'TournoiController', 'method' => 'index'],
    'tournoi/index' => ['controller' => 'TournoiController', 'method' => 'index'],
    
    // === Search ===
    'search/terrains' => ['controller' => 'ClientController', 'method' => 'searchTerrains'],
    'available/slots' => ['controller' => 'ClientController', 'method' => 'getAvailableSlots'],

    // ==================== CLIENT ROUTES ====================
    
    // === Reservations ===
    'reservation' => ['controller' => 'ClientController', 'method' => 'faireReservation'],
    'reservation/create/{terrain_id}' => ['controller' => 'ReservationController', 'method' => 'create'],
    'reservation/store' => ['controller' => 'ReservationController', 'method' => 'store'],
    'reservation/{id}' => ['controller' => 'ReservationController', 'method' => 'show'],
    'reservation/cancel/{id}' => ['controller' => 'ReservationController', 'method' => 'cancel'],
    
    // === Gestion des réservations client ===
    'mes-reservations' => ['controller' => 'ClientController', 'method' => 'myReservations'],
    'modifier/id' => ['controller' => 'ClientController', 'method' => 'modifierReservation'],
    'annuler/id' => ['controller' => 'ClientController', 'method' => 'annulerReservation'],
    'facture/id' => ['controller' => 'ClientController', 'method' => 'facturer'],
    
    // === Tournois client ===
    'tournoi/participer' => ['controller' => 'TournoiController', 'method' => 'participer'],
    'tournoi/inscrire' => ['controller' => 'TournoiController', 'method' => 'inscrire'],
    'tournoi/mesparticipations' => ['controller' => 'TournoiController', 'method' => 'mesparticipations'],
    
    // === Dashboard client ===
    'dashboard' => ['controller' => 'DashboardController', 'method' => 'index'],
    'dashboard/client' => ['controller' => 'DashboardController', 'method' => 'client'],

    // ==================== GÉRANT ROUTES ====================
    
    // === Dashboard gérant ===
    'gerant' => ['controller' => 'GerantController', 'method' => 'index'],
    'gerant/dashboard' => ['controller' => 'GerantController', 'method' => 'index'],
    
    // === Gestion des terrains ===
    'gerant/terrains' => ['controller' => 'GerantController', 'method' => 'mesTerrains'],
    'gerant/terrain/{id}' => ['controller' => 'GerantController', 'method' => 'show'],
    'gerant/edit-terrain/{id}' => ['controller' => 'GerantController', 'method' => 'edit'],
    
    // === Gestion des options ===
    'gerant/options/{id}' => ['controller' => 'GerantController', 'method' => 'gererOptions'],
    'gerant/delete-option/{id}' => ['controller' => 'GerantController', 'method' => 'supprimerOption'],
    
    // === Gestion des réservations ===
    'gerant/reservations' => ['controller' => 'GerantController', 'method' => 'reservations'],
    
    // === Tournois gérant ===
    'tournoi/mestournois' => ['controller' => 'TournoiController', 'method' => 'mestournois'],
    'tournoi/create' => ['controller' => 'TournoiController', 'method' => 'create'],
    'tournoi/show' => ['controller' => 'TournoiController', 'method' => 'show'],
    'tournoi/edit' => ['controller' => 'TournoiController', 'method' => 'edit'],
    'tournoi/delete' => ['controller' => 'TournoiController', 'method' => 'delete'],
    
    // === Dashboard gérant ===
    'dashboard/gerant' => ['controller' => 'DashboardController', 'method' => 'gerant'],

    // ==================== ADMIN ROUTES ====================
    
    // === Dashboard admin ===
    'admin' => ['controller' => 'AdminController', 'method' => 'index'],
    'admin/dashboard' => ['controller' => 'AdminController', 'method' => 'index'],
    
    // === Gestion des terrains ===
    'admin/terrains' => ['controller' => 'AdminController', 'method' => 'terrains'],
    'terrain/create' => ['controller' => 'TerrainController', 'method' => 'create'],
    'terrain/edit/{id}' => ['controller' => 'TerrainController', 'method' => 'edit'],
    'terrain/delete/{id}' => ['controller' => 'TerrainController', 'method' => 'delete'],
    'terrain/options/{id}' => ['controller' => 'TerrainController', 'method' => 'manageOptions'],
    'terrain/option/delete/{id}' => ['controller' => 'TerrainController', 'method' => 'deleteOption'],
    
    // === Gestion des réservations ===
    'admin/reservations' => ['controller' => 'AdminController', 'method' => 'reservations'],
    
    // === Gestion des gérants ===
    'admin/gerants' => ['controller' => 'AdminController', 'method' => 'gerants'],
    'admin/gerant/create' => ['controller' => 'AdminController', 'method' => 'createGerant'],
    'admin/gerant/delete/{id}' => ['controller' => 'AdminController', 'method' => 'deleteGerant'],
    
    // === Gestion des tournois ===
    'admin/tournois' => ['controller' => 'AdminController', 'method' => 'tournois'],
    
    // === Newsletter ===
    'newsletter' => ['controller' => 'NewsletterController', 'method' => 'index'],
    'newsletter/create' => ['controller' => 'NewsletterController', 'method' => 'create'],
    'newsletter/show/{id}' => ['controller' => 'NewsletterController', 'method' => 'show'],
    'newsletter/delete/{id}' => ['controller' => 'NewsletterController', 'method' => 'delete'],
    
    // === Dashboard admin ===
    'dashboard/admin' => ['controller' => 'DashboardController', 'method' => 'admin'],

    // ==================== DEVELOPMENT ROUTES ====================
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