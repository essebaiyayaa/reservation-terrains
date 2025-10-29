<?php

require_once 'load.php';

define('JWT_SECRET_KEY', $_ENV['JWT_SECRET_KEY']);
define('JWT_EXPIRY_MINUTES', $_ENV['JWT_EXPIRY_MINUTES']);

?>