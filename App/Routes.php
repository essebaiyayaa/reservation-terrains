<?php


$routes = [
    
    //TODO: To be deleted. Just an example
    '' => ['controller' => 'BookController', 'method' => 'index'],

    'register' => ['controller' => 'AuthController', 'method' => 'create'],

   
    'books' => ['controller' => 'BookController', 'method' => 'index'],

    
    'book/id' => ['controller' => 'BookController', 'method' => 'show'],

    
    'book/add' => ['controller' => 'BookController', 'method' => 'create'],

    
    'book/delete' => ['controller' => 'BookController', 'method' => 'delete'],

    
    'book/update' => ['controller' => 'BookController', 'method' => 'edit']
];

?>