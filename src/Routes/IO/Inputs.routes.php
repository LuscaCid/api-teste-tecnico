<?php

class Inputs_routes extends BaseRoutes {
  
  private $inputsControllers = null;
  
  public function __construct($object) {
    parent::__construct($object);
    $inputsService = new Inputs_services();
    $this->inputsControllers = new Inputs_controllers($inputsService);
    
  }

  public function loadInputsRoutes() {
    $path_exploded = explode("/", $this->path);
    switch($this->path) {
      // inputs/emit/:license_plate
      case $path_exploded[0] == "inputs" && $path_exploded[1]== "emit" && $path_exploded[2] != "":
        
        if($this->method == "POST") {
          $license_plate = $path_exploded[2];
          $this->inputsControllers->emitInput(license_plate: $license_plate );
        }
        break;
    }
  }
}