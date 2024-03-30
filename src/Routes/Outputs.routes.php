<?php

class Outputs_routes extends BaseRoutes{
  private $outputsControllers = null;
  
  public function __construct($object) {
    parent::__construct($object);
    $outputsService = new Outputs_services();
    $this->outputsControllers = new Outputs_controllers($outputsService);
  }

  public function loadOutputsRoutes () {
    $path_exploded = explode("/", $this->path);
    switch ($this->path) {
      // outputs/emit/:vehicle_id
      case $path_exploded[0] == "outputs" && $path_exploded[1]== "emit" && $path_exploded[2] != "":
        
        if($this->method == "POST") {
          $vehicle_id = $path_exploded[2];
          $this->outputsControllers->emitOutput(vehicle_id: $vehicle_id);
        }
        break;
    }
    }
  }
