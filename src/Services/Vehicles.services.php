<?php

class Vehicles_service {

  private $dbInstance;
  public function __construct() {
    $this->dbInstance = new DbConnectionService();
  }

  public function getPaginatedVehicles($page) {
    //pagination calc based in limit
    $limit = 5;
    $begin = ($page * $limit) - $limit;

    $sql = "SELECT * FROM vehicles  
    INNER JOIN categories ON vehicles.category_id = categories.id ;
    LIMIT {$begin}, {$limit} 
    ORDER BY model";

    try {
      $response = $this->dbInstance->queryExec($sql)->fetchAll(PDO::FETCH_ASSOC);
      return $response;
    }catch (PDOException $e) {
      return $e->getMessage();
    }
  }

  public function getVehicleByLicensePlate( $licensePlate ) {
    $sql = "SELECT * from vehicles 
    INNER JOIN users ON users.id = vehicles.created_by
    INNER JOIN categories ON vehicles.category_id = categories.id
    WHERE license_plate = '{$licensePlate}' ;";

    try {
    $result = $this->dbInstance->queryExec($sql)->fetch(PDO::FETCH_ASSOC);
    
    return $result;

    } catch (PDOException $e) {
      return $e->getMessage();
    }
  }

  public function insertVehicle($form){
    $sql = "INSERT INTO vehicles (";
 
    $counter = 1;
    foreach(array_keys($form) as $key) { 
      if($counter < count(array_keys($form))) {
        $sql .= "{$key}, ";
      } else { 
        $sql .= " {$key} ";
      }
      $counter++;
    } // if its at last position of the array, can only return the key without ','
  
    $sql .= ") VALUES (";
  
    $counter = 1;
    foreach(array_values($form) as $value) { 
      if($counter < count(array_keys($form))) {
        $sql .= " '{$value}', ";
      } else { 
        $sql .= " '{$value}'";
      }
      $counter++;
    }
    $sql .= ");";

    try {
      $response = $this->dbInstance->queryExec($sql)->fetchAll(PDO::FETCH_ASSOC);
      return $response;
    } catch (PDOException $e) {
      return $e->getMessage();
    }

  }

}
