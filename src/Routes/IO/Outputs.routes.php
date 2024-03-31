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
      // outputs/emit/:license_plate
      case $path_exploded[0] == "outputs" && $path_exploded[1]== "emit" && $path_exploded[2] != "":
        
        if($this->method == "POST") {
          $license_plate = $path_exploded[2];
          $this->outputsControllers->emitOutput(license_plate: $license_plate);
        }
        break;
      default:
        http_response_code(404);
        echo json_encode(["error" => "Not found"]);
    }
  }
}
