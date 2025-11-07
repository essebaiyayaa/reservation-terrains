<?php

/**
 * Application Routes Configuration
 * 
 * Format: 'route' => ['controller' => 'ControllerName', 'method' => 'methodName']
 */

$routes = [
    
    // ==================== HOME ROUTE ====================
    '' => ['controller' => 'HomeController', 'method' => 'index'],
    'home' => ['controller' => 'HomeController', 'method' => 'index'],
    
    // ==================== AUTH ROUTES ====================
    'register' => ['controller' => 'AuthController', 'method' => 'create'],
    'login' => ['controller' => 'AuthController', 'method' => 'login'],
    'logout' => ['controller' => 'AuthController', 'method' => 'logout'],
    'verify' => ['controller' => 'AuthController', 'method' => 'verify'],
    'verify/submit' => ['controller' => 'AuthController', 'method' => 'verifySubmit'],

    // ==================== TERRAIN ROUTES ====================
    
    // Public routes
    'terrains' => ['controller' => 'TerrainController', 'method' => 'index'],
    'terrain/id' => ['controller' => 'TerrainController', 'method' => 'show'],
    
    // Admin routes
    'terrain/add' => ['controller' => 'TerrainController', 'method' => 'create'],
    'terrain/edit' => ['controller' => 'TerrainController', 'method' => 'edit'],
    'terrain/delete' => ['controller' => 'TerrainController', 'method' => 'delete'],
    
    // Gerant routes
    'terrain/options' => ['controller' => 'TerrainController', 'method' => 'manageOptions'],
    'terrain/option/delete' => ['controller' => 'TerrainController', 'method' => 'deleteOption'],

    // ==================== DASHBOARD ROUTES ====================
    'dashboard/admin' => ['controller' => 'DashboardController', 'method' => 'admin'],
    'dashboard/gerant' => ['controller' => 'DashboardController', 'method' => 'gerant'],
    'dashboard/client' => ['controller' => 'DashboardController', 'method' => 'client'],

    // ==================== TEST ROUTES (Book) ====================
    'books' => ['controller' => 'BookController', 'method' => 'index'],
    'book/id' => ['controller' => 'BookController', 'method' => 'show'],
    'book/add' => ['controller' => 'BookController', 'method' => 'create'],
    'book/delete' => ['controller' => 'BookController', 'method' => 'delete'],
    'book/update' => ['controller' => 'BookController', 'method' => 'edit']
];