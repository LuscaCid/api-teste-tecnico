<?php
// application initialization class

require_once "src/bootstrap.php";

// config and initial loading

include_once "src/Config/AuthJwt.php";
include_once "src/Config/DbConnection.php";

// routes included

include_once "src/Routes/Users.routes.php";
include_once "src/Routes/Categories.routes.php";
include_once "src/Routes/Vehicles.routes.php";
include_once "src/Routes/IO/Inputs.routes.php";
include_once "src/Routes/IO/Outputs.routes.php";

// controllers

include_once "src/Controllers/Categories.controllers.php";
include_once "src/Controllers/Users.controllers.php";
include_once "src/Controllers/Vehicles.controllers.php";
include_once "src/Controllers/IO/Inputs.controllers.php";
include_once "src/Controllers/IO/Outputs.controllers.php";

// services == models

include_once "src/Services/Users.services.php";
include_once "src/Services/Categories.services.php";
include_once "src/Services/Vehicles.services.php";
include_once "src/Services/IO/Inputs.services.php";
include_once "src/Services/IO/Outputs.services.php";

// model service

include_once "src/Services/IO/FindByLicense.php";

header("Allow-Control-Access-Origin: *");
header("Content-type: Apliccation/json");

date_default_timezone_set("America/Sao_Paulo");

$path = $_GET["path"];
$requestMethod = $_SERVER["REQUEST_METHOD"];
$post = $_POST;
$auth = $_SERVER["HTTP_AUTHORIZATION"];

$app = new Bootstrap($path, $requestMethod, $post, $auth);

$app->load(); 

//vou trabalhar os controllers de forma injetando o service para trabalhar com testes unitarios depois 
