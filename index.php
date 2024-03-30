<?php
// application initialization class

require_once "src/bootstrap.php";

// config and initial loading

include_once "src/Config/DbConnection.php";

// routes included

include_once "src/Routes/Categories.routes.php";
include_once "src/Routes/Vehicles.routes.php";
include_once "src/Routes/Inputs.routes.php";

// controllers

include_once "src/Controllers/Categories.controllers.php";
include_once "src/Controllers/Vehicles.controllers.php";
include_once "src/Controllers/Inputs.controllers.php";
// services == models

include_once "src/Services/Categories.services.php";
include_once "src/Services/Vehicles.services.php";
include_once "src/Services/Inputs.services.php";


header("Allow-Control-Access-Origin: *");
header("Content-type: Apliccation/json");

$path = $_GET["path"];
$requestMethod = $_SERVER["REQUEST_METHOD"];
$post = $_POST;
$auth = $_SERVER["HTTP_AUTHORIZATION"];

$app = new Bootstrap($path, $requestMethod, $post, $auth);

$app->load(); 

//vou trabalhar os controllers de forma injetando o service para trabalhar com testes unitarios depois 
