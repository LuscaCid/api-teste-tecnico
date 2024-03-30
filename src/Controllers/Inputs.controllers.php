<?php

class Inputs_controllers {
  private $inputsService;
  public function __construct(Inputs_services $inputs_services) {
    $this->inputsService = $inputs_services;
  }

  public function emitInput($vehicle_id){
    $inputAlreadyExists = $this->inputsService->verifyItAlreadyInputted($vehicle_id);
    if($inputAlreadyExists){
      http_response_code(401);
      echo json_encode(["error" => "Entrada já feita para este veiculo"]);
    }

    $response = $this->inputsService->emitInput($vehicle_id);
    echo json_encode( ["response" => $response ] );
  }
}