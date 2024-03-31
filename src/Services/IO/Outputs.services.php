<?php
include_once "FindByLicense.php";

class Outputs_services extends FindVehicleByLicensePlateService{
  private $dbInstance;
  public function __construct() {
    $this->dbInstance = new DbConnectionService();
    parent::__construct();
  }
 
  public function emitOutput ($license_plate, $outputted_at, $price, $hours, $input_link_code) {
    //getting vehicle id by the license plate
    $vehicle = $this->findVehivleByLicensePlate($license_plate);
   
    if(array_key_exists("id", $vehicle)) {
      $vehicle_id = $vehicle["id"];
    }
    // vehicle_id will be used for another fetch to catch the hash code present inside the input

    var_dump($input_link_code);
   $sql = "INSERT INTO outputs (vehicle_id, outputted_at, final_price, permanence_time, input_link_code) 
   VALUES (
      {$vehicle_id}, '{$outputted_at}', {$price}, '{$hours} horas.', '{$input_link_code}'
    );";
   
   try {
      $result = $this->dbInstance->queryExec(sql: $sql);
      $response = $result->fetch(PDO::FETCH_ASSOC);
      return $response;
    } catch (Exception $e) { 
      echo json_encode(["error" => $e->getMessage()]);
    }
   
  }

  public function ensurePreviousInput($license_plate) {

    $vehicle = $this->findVehivleByLicensePlate($license_plate);
   
    if(array_key_exists("id", $vehicle)) {
      $vehicle_id = $vehicle["id"];
    }

    $sql = "SELECT * FROM inputs WHERE vehicle_id = '{$vehicle_id}'";
    try {
      $result = $this->dbInstance->queryExec($sql);
      $response = $result->fetch(PDO::FETCH_ASSOC);
      return $response;
    } catch (Exception $e) {
      echo $e->getMessage();
    }
      
  }

  public function getVehicleCaregoryFee($license_plate) {
    $sql = "SELECT parking_fee FROM vehicles
      INNER JOIN categories ON vehicles.category_id = categories.id
      WHERE license_plate = '{$license_plate}'
      
    ";
    try {
      $result = $this->dbInstance->queryExec($sql);
      $response = $result->fetch(PDO::FETCH_ASSOC);
      return $response;
    } catch (Exception $e) { 
      echo json_encode(["error2" => $e->getMessage()]);
      exit;
    }
  }
}