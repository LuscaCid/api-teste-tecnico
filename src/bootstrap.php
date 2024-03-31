<?php
include_once "Classes/Routes.php";

// bootstrap acts like an front controller, dispatch others requests

class Bootstrap extends BaseRoutes{ 
  public $object;
  //use Routes_trait;
  public function __construct($path, $requestMethod, $post, $auth) {
    $this->object = (object) [
      "path" => $path,
      "method"=> $requestMethod,
      "post_form"=> $post, 
      "authToken"=> $auth
    ];
    parent::__construct($this->object);
     // endpoint para lidar no load e conseguir trabalhar em um controller dentro do switch
  }

  public function load() {
    $api_segment = explode("/", $this->path); //selecting the section
    $AuthGuard = new AuthJwtService($this->object);
    
    switch ($api_segment[0]) {
      case "users":
        $usersRoutes = new Users_routes($this->object);
        $usersRoutes->loadUsersRoutes();
        break;
      case "vehicles":
        $AuthGuard->verify();
        $vehiclesRoutes = new Vehicles_routes( $this->object ); 
        $vehiclesRoutes->loadVehiclesRoutes();
        break;
      case "categories":
        $AuthGuard->verify();
        $categoriesRoutes = new Categories_routes( $this->object );
        $categoriesRoutes->loadCategoriesRoutes();
        break;
      case "inputs":
        $AuthGuard->verify();
        $inputsRoutes = new Inputs_routes( $this->object );
        $inputsRoutes->loadInputsRoutes();
        break;
      case "outputs" :
        $AuthGuard->verify();
        $outputsRoutes = new Outputs_routes( $this->object );
        $outputsRoutes->loadOutputsRoutes();
        break;
      default :
        http_response_code(404);
        echo json_encode(["message"=> "Not found"]);
    }
  }
 }
 //in index.php its happening the initial configuration, the principle idea its to connect main with all, 
 //taking the path from server with index, method type and injecting all these inside main 