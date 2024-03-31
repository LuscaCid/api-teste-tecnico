<?php

class Outputs_controllers {
  private $outputs_services;
  public function __construct(Outputs_services $outputs_services) {
    $this->outputs_services = $outputs_services;
  }

  public function emitOutput($license_plate) {

    $isExistsPreviousInput = $this->outputs_services->ensurePreviousInput($license_plate);
    if(!$isExistsPreviousInput) {
      echo json_encode(["error" => "Não se pode emitir uma saída para um veículo que não deu uma entrada anteriormente."]);
      exit;
    }
    
    if(array_key_exists("inputted_at", $isExistsPreviousInput) && array_key_exists("link_code", $isExistsPreviousInput)) {
      $inputted_at = $isExistsPreviousInput["inputted_at"];
      $input_link_code = $isExistsPreviousInput["link_code"];
    }
    //calc the difference between when the input was made and the output is genereted (now)
    
    $outputted_at = new DateTime();
    $outputted_at->format("Y-m-d H:i:s");
    
    $inputted_at_emit = new DateTime($inputted_at);
    
    $difference_between_input_output = $inputted_at_emit->diff($outputted_at);

    $hours = $difference_between_input_output->h + ($difference_between_input_output->days * 24);

    $vehicle_category_fee = $this->outputs_services->getVehicleCaregoryFee($license_plate);

    echo json_encode([
      "vehicle_fee" => $vehicle_category_fee,
      "total hours" => $hours
    ]);
    $price = intval($vehicle_category_fee["parking_fee"]) * $hours;
    // with $hours, i will take the parking fee inside category and only multiply 
    $outputted_at = $outputted_at->format("Y-m-d H:i:s");

    $response = $this->outputs_services->emitOutput($license_plate, $outputted_at, $price, $hours, $input_link_code );
    
    echo json_encode(["response" => "Saída emitida com sucesso"]);
    exit;
  } // ao final preciso excluir a entrada feita anteriormente
}