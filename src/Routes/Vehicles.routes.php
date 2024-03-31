<?php
class Vehicles_routes extends BaseRoutes {
  private $vehilesControllers = null;

  public function __construct($object) {
    parent::__construct($object);
    $vehiclesService = new Vehicles_service();
    $this->vehilesControllers = new Vehicles_controllers($vehiclesService);
  }
  public function loadVehiclesRoutes() {
    
    $path_exploded = explode("/", $this->path);

    switch ($this->path) {
      // vehicles/view/:page
      case $path_exploded[0] == "vehicles" && $path_exploded[1] == "view":
        
        if($this->method == "GET") {
          $page = 1;
          isset( $path_exploded[2] ) && $page = $path_exploded[2];
          $this->vehilesControllers->view( $page );
        } 
        break;
        // vehicles/license/:license_plate
      case $path_exploded[0] == "vehicles" && $path_exploded[1] == "license" : 
        if($this->method == "GET") {
          if(isset($path_exploded[2])) {
            $license_plate = $path_exploded[2];
            $this->vehilesControllers->getVehiclesByLicensePlate( $license_plate );
          } else {
            echo json_encode(["error"=> "O Registro da placa deve ser passado."]);
          }
        }
        
        break;
      case "vehicles/create":
        if($this->method == "POST") {
          $this->vehilesControllers->createVehicle($this->post_form);
        }
        break;
        //vehicles/vehicle_report/:license_plate
      case $path_exploded[0] == "vehicles" && $path_exploded[1] == "vehicle_report":
        if($this->method == "GET") {
          $license_plate = $path_exploded[2];
          $this->vehilesControllers->vehicleReport($license_plate);
        }
        break;
        // vehicles/vehicles_reports/:page
        case $path_exploded[0] == "vehicles" && $path_exploded[1] == "vehicles_reports":
          if($this->method == "GET") {
            isset( $path_exploded[2] ) && $page = $path_exploded[2];
            $this->vehilesControllers->vehiclesReportsPaginated( $page );
          }
          break;
      default: 
        http_response_code(404);
        echo json_encode( ["error"=> "Not Found."] );
        exit;
    }
  }
}