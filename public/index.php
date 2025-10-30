<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../core/BaseController.php";
require_once __DIR__ . "/../core/BaseModel.php";
require_once __DIR__ . "/../database/PDODatabase.php";
require_once __DIR__ . "/../App/App.php";

// echo "Hello!";


$app = new App;


?>