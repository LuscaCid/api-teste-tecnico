<?php
include_once "FindByLicense.php";
class Inputs_services extends FindVehicleByLicensePlateService {
  private $dbInstance;
  public function __construct() {
    $this->dbInstance = new DbConnectionService();
    parent::__construct();
  }
  public function emitInput ($license_plate) {

    $vehicle = $this->findVehivleByLicensePlate($license_plate);
    if(array_key_exists("id", $vehicle)) $vehicle_id = $vehicle["id"];

    $hash = md5(uniqid()); // when the output will be emitted, this hash 'll be used

    $sql = "INSERT INTO inputs (vehicle_id, link_code) value ({$vehicle_id}, '{$hash}'  )";
    try {
      $result = $this->dbInstance->queryExec($sql);
      $response = $result->fetch(PDO::FETCH_ASSOC);
      return $response;
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode(["error" => $e->getMessage()]);
      exit;
    }
  }

  public function verifyItAlreadyInputted ($license_plate) {

    $vehicle = $this->findVehivleByLicensePlate($license_plate);
    if(array_key_exists("id", $vehicle)) $vehicle_id = $vehicle["id"];

    $sql = "SELECT * FROM inputs WHERE vehicle_id = {$vehicle_id}";
    try {
      $result = $this->dbInstance->queryExec($sql);
      $response = $result->fetch(PDO::FETCH_ASSOC);
      return $response;
    } catch (PDOException $e) {
      http_response_code(500);
      echo $e->getMessage();
      exit;
    }
  }
}