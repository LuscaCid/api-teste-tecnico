<?php

class Outputs_services {
  private $dbInstance;
  public function __construct() {
    $this->dbInstance = new DbConnectionService();
  }
  public function emitOutput ($vehicle_id) {

  }
}