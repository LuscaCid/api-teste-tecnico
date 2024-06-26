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

    $sql = "SELECT v.license_plate, u.email as created_by, c.type, c.parking_fee, v.model, v.created_at FROM vehicles as v 
    INNER JOIN categories as c ON v.category_id = c.id
    INNER JOIN users as u  ON v.created_by = u.id
    ORDER BY model 
    LIMIT {$begin}, {$limit} ;";

    try {
      $response = $this->dbInstance->queryExec($sql)->fetchAll(PDO::FETCH_ASSOC);
      return $response;
    } catch ( PDOException $e ) { 
      http_response_code(500);
      echo json_encode(["error" =>$e->getMessage()]) ;
      exit;
    } 
  }

  public function getVehicleById ($id) {
    $sql = "SELECT * FROM vehicles WHERE id = {$id}";
    try {
      $result = $this->dbInstance->queryExec($sql);
      $response = $result->fetch();
      return $response;
    } catch (PDOException $e){
      http_response_code(500);
      json_encode(array("error"=>$e->getMessage())) ;
    }
 
  }
  public function getVehicleByLicensePlate( $licensePlate ) {
    $sql = "SELECT u.email as created_by, c.type, c.parking_fee, v.model, v.license_plate, v.created_at from vehicles as v 
    INNER JOIN users as u ON u.id = v.created_by
    INNER JOIN categories as c ON v.category_id = c.id
    WHERE license_plate = '{$licensePlate}' ;";

    try {
    $result = $this->dbInstance->queryExec($sql)->fetch(PDO::FETCH_ASSOC);
    
    return $result;

    } catch (PDOException $e) {
      http_response_code(500);
      echo json_encode(["error" =>$e->getMessage()]);
      exit;
    }
  }
  private function verifyCategoryExists($form) {
    if(array_key_exists("category_id", $form)) {
      $category_id = $form["category_id"];
    }
    $sql = "SELECT * FROM categories WHERE id = {$category_id};";
    try {
      $results = $this->dbInstance->queryExec($sql)->fetch(PDO::FETCH_ASSOC);
      return $results;
    }  catch ( PDOException $e ) {
      http_response_code(500);
      echo json_encode(array("error"=> $e->getMessage()));
      exit;
    }
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
      http_response_code(500);
      echo json_encode(["error" => $e->getMessage()]) ;
      exit;
    }
  }

  public function getVehicleReport($license_plate) {

    $sql = "SELECT id FROM vehicles WHERE license_plate = '{$license_plate}'";
    $result = $this->dbInstance->queryExec($sql)->fetchAll(PDO::FETCH_ASSOC);
    if(array_key_exists("id", $result)) { $vehicle_id = $result["id"]; }

    $sql = "SELECT i.id as record_id, v.license_plate, v.model, i.inputted_at, o.outputted_at, o.final_price, o.permanence_time, c.type, vi.image_path, vi.image_name FROM vehicles as v
    INNER JOIN categories as c ON v.category_id = c.id
    INNER JOIN inputs_history as i ON i.vehicle_id = v.id
    LEFT JOIN outputs_history as o ON o.input_link_code = i.link_code
    LEFT JOIN vehicle_has_images as vi ON vi.vehicle_id = v.id
    AND o.ouputted_at IS NOT NULL
    AND o.final_price IS NOT NULL
    AND o.permanence_time IS NOT NULL
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

  public function getVehiclesReports(int $page) {
    $limit = 5;
    $begin = ($page * $limit) - $limit;

    $sql = "SELECT i.id as record_id, v.license_plate, v.model, i.inputted_at, o.outputted_at, o.final_price, o.permanence_time, c.type, vi.image_path, vi.image_name FROM vehicles as v
    INNER JOIN categories as c ON v.category_id = c.id
    INNER JOIN inputs_history as i ON i.vehicle_id = v.id
    LEFT JOIN outputs_history as o ON o.input_link_code = i.link_code
    LEFT JOIN vehicle_has_images as vi ON vi.vehicle_id = v.id
    LIMIT {$begin}, {$limit}  ;";

    try {
      $result = $this->dbInstance->queryExec($sql)->fetchAll(PDO::FETCH_ASSOC);
      return $result;
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode(["error"=> $e->getMessage()] );
      exit;
    }
  }
  public function insertImageForAVehicle($vehicle_id, $file_name, $file_path){
    var_dump($vehicle_id, $file_name, $file_path);
    $sql = "INSERT INTO vehicle_has_images (vehicle_id, image_name, image_path ) 
    VALUES
    ({$vehicle_id}, '{$file_name}', '{$file_path}');
    ";
    try {
      $result = $this->dbInstance->queryExec($sql);
      return $result;
    } catch (PDOException $e) {
      http_response_code(500);
      echo json_encode(["error"=> $e->getMessage()] );
      exit;
    }
  }
}
