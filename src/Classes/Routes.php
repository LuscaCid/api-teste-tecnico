<?php

class BaseRoutes {
  protected $path;
  protected $method;
  protected $post_form;
  protected $authToken;

  public function __construct($object) {
    if($object) {
      $this->path = $object->path ?? null;
      $this->method = $object->method ?? null ;
      $this->post_form = $object->post_form ?? null;
      $this->authToken = $object->authToken ?? null;
    }
    
  }
}

// THIS IS LIKE AN DTO TO TRANSFER DATA ALONG APPLICATION THROUGH THE ROUTES