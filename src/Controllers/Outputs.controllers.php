<?php

class Outputs_controllers {
  private $outputs_services;
  public function __construct(Outputs_services $outputs_services) {
    $this->outputs_services = $outputs_services;
  }

  public function emitOutput($vehicle_id) {}
}