<?php
class FindVehicleByLicensePlateService {
  private $dbInstance;
  public function __construct() { 
    $this->dbInstance = new DbConnectionService();
  }
  protected function findVehivleByLicensePlate($license_plate) {
    $sql = "SELECT * FROM vehicles WHERE license_plate = '{$license_plate}'";
    
    try {
      $result = $this->dbInstance->queryExec($sql);
      $object = $result->fetch( PDO::FETCH_ASSOC);
      return $object; // return vehicle with vehicle_id
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode(['error'=> $e->getMessage()]);
      exit;
    }
  }
}