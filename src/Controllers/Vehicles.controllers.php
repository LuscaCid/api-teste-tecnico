<?php 

class Vehicles_controllers {

  private $vehiclesService;

  public function __construct(Vehicles_service $vehiclesService) {
    $this->vehiclesService = $vehiclesService;
  }

  public function view($page) {
    if(is_numeric($page)){
      $res = $this->vehiclesService->getPaginatedVehicles(intval($page));
    } 
    echo json_encode(["response" => $res]);
  }

  public function getVehiclesByLicensePlate($license_plate) {
    $response = $this->vehiclesService->getVehicleByLicensePlate($license_plate);
    echo json_encode(["response"=> $response]);
  }

  public function createVehicle($post_form){

    if(array_key_exists("license_plate", $post_form)) {
      $licensePlate = $post_form["license_plate"];
    } 
    $licensePlateAlreadyInUse = $this->vehiclesService->getVehicleByLicensePlate($licensePlate); 
    if($licensePlateAlreadyInUse){
      http_response_code(401);
      echo json_encode(["error" => "Ja existe um carro registrado com esta placa"]);
      return;
    } 
    $this->vehiclesService->insertVehicle($post_form);
    echo json_encode(["response"=> "created"]);
    http_response_code(201);
  }
}