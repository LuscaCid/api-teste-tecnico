<?php

class Vehicles_service {

  private $dbInstance;
  public function __construct() {
    $this->dbInstance = new DbConnectionService();
    session_start();
  }

  public function getPaginatedVehicles($page) {
    //pagination calc based in limit
    $limit = 5;
    $begin = ($page * $limit) - $limit;

    $sql = "SELECT * FROM vehicles  
    INNER JOIN categories ON vehicles.category_id = categories.id
    ORDER BY model 
    LIMIT {$begin}, {$limit} ;";

    try {
      $response = $this->dbInstance->queryExec($sql)->fetchAll(PDO::FETCH_ASSOC);
      return $response;
    } catch ( PDOException $e ) { 
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
  private function verifyCategoryExists($form) {
    if(array_key_exists("category_id", $form)) {
      $category_id = $form["category_id"];
    }
    $sql = "SELECT * FROM categories WHERE id = {$category_id};";
    $results = $this->dbInstance->queryExec($sql)->fetch(PDO::FETCH_ASSOC);
    return $results;
  }
  public function insertVehicle($form){
    $userID = $_SESSION['user_id'];
    // taking user_id inside session and creating a field to insert in the table
    $form["created_by"] = $userID;
    if(!$this->verifyCategoryExists($form)) {
      echo json_encode(["error" => "A categoria passada não foi criada."]);
      exit;
    };
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

  public function getVehicleReport($license_plate) {

    $sql = "SELECT id FROM vehicles WHERE license_plate = '{$license_plate}'";
    $result = $this->dbInstance->queryExec($sql)->fetchAll(PDO::FETCH_ASSOC);
    if(array_key_exists("id", $result)) { $vehicle_id = $result["id"]; }

    $sql = "SELECT v.license_plate, v.model, i.inputted_at, o.outputted_at, o.final_price, o.permanence_time, c.type FROM vehicles as v
    INNER JOIN categories as c ON v.category_id = c.id
    INNER JOIN inputs_history as i ON i.vehicle_id = v.id
    INNER JOIN outputs_history as o ON o.input_link_code = i.link_code
    WHERE license_plate = '{$license_plate}';";

    try {
      $result = $this->dbInstance->queryExec($sql)->fetchAll(PDO::FETCH_ASSOC);
      return $result;
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode(["error"=> $e->getMessage()] );
      exit;
    }
  }
}
