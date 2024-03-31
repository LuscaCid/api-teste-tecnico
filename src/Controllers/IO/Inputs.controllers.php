<?php

class Inputs_controllers {
  private $inputsService;
  public function __construct(Inputs_services $inputs_services) {
    $this->inputsService = $inputs_services;
  }

  public function emitInput($license_plate){
    $inputAlreadyExists = $this->inputsService->verifyItAlreadyInputted($license_plate);
    if($inputAlreadyExists){
      http_response_code(401);
      echo json_encode(["error" => "Entrada já feita para este veiculo sem uma saída respectiva."]);
      exit;// it cannot add another input 
      // add log message inside logs to control all process inside application 
    }

    $this->inputsService->emitInput($license_plate);
    
    echo json_encode( ["response" => "Entrada gerada." ] );
    exit;
  }
}