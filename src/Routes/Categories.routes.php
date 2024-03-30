<?php

class Categories_routes extends BaseRoutes {
  
  private $categoriesController = null;
  
  public function __construct($object) {
    parent::__construct($object);
    $categoriesService = new Categories_service();
    $this->categoriesController = new Categories_controller($categoriesService);
  }

  public function loadCategoriesRoutes() { // controllers its called here
    $path_exploded = explode("/", $this->path);
    switch ($this->path) {
      case "categories/view":
        
        if($this->method == "GET") {
          $this->categoriesController->getCategories();
        } else { echo json_encode(["error"=> "Not found"]); }

        break;
      case "categories/create":
        if($this->method == "POST") {
          http_response_code(201);
          $this->categoriesController->createCategory($this->post_form);
        }

        break;
      case $path_exploded[0] == "categories" && $path_exploded[1]== "view" && $path_exploded[2] != "":
        if($this->method == "GET") {  
          http_response_code(200);
          $this->categoriesController->getCategoryByType($path_exploded[2]);
        }
        break;
      default :
        http_response_code(404);
        echo json_encode(["message"=> "Not found"]);
    }
  }
}