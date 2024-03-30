<?php

class Inputs_services {
  private $dbInstance;
  public function __construct() {
    $this->dbInstance = new DbConnectionService();
  }
  public function emitInput ($vehicle_id) {
    $sql = "INSERT INTO inputs (vehicle_id) value ({$vehicle_id})";
    $result = $this->dbInstance->queryExec($sql);
    $response = $result->fetch(PDO::FETCH_ASSOC);
    return $response;
  }

  public function verifyItAlreadyInputted ($vehicle_id) {
    $sql = "SELECT * FROM inputs WHERE vehicle_id = {$vehicle_id}";
    try {
      $result = $this->dbInstance->queryExec($sql);
      $response = $result->fetch(PDO::FETCH_ASSOC);
      return $response;
    } catch (PDOException $e) {
      return $e->getMessage();
    }
  }
}