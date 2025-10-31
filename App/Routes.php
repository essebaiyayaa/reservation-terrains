<?php


$routes = [
    
    // Auth Routes

    'register' => ['controller' => 'AuthController', 'method' => 'create'],

    'login' => ['controller' => 'AuthController', 'method' => 'login'],




    // Test Routes

    '' => ['controller' => 'BookController', 'method' => 'index'],
   
    'books' => ['controller' => 'BookController', 'method' => 'index'],

    
    'book/id' => ['controller' => 'BookController', 'method' => 'show'],

    
    'book/add' => ['controller' => 'BookController', 'method' => 'create'],

    
    'book/delete' => ['controller' => 'BookController', 'method' => 'delete'],

    
    'book/update' => ['controller' => 'BookController', 'method' => 'edit']
];

?>