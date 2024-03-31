<?php 

class Categories_controller {
  private $categoriesService;
  public function __construct(Categories_service $categoriesService) {
    $this->categoriesService = $categoriesService;
  }

  public function getCategories() {
    
    $categories = $this->categoriesService->getAllCategories();
    http_response_code(202);
    echo json_encode($categories);  
  }

  public function getCategoryByType($param) {
    $category = $this->categoriesService->getCategoryByType($param);
    if(empty($category)) {
      http_response_code(404);
      echo json_encode(array("error"=> "Nenhuma categoria encontrada."));
      exit;
    }
    http_response_code(200);
    echo json_encode($category);
  }

  public function createCategory($post_form) {
   
    if(
      array_key_exists("type", $post_form) && 
      array_key_exists("parking_fee", $post_form)
      ){ 
      $type = $post_form["type"]; 
      $parking_fee = $post_form["parking_fee"];
    } else { 
      http_response_code(401);
      json_encode(["error" => "Deve-se passar o tipo e a taxa de estacionamento."]);
      exit;
    }

    $categoryAlreadyCreated = $this->categoriesService->getCategoryByType($type);
    if(!empty( $categoryAlreadyCreated )) { // isso porque as vezes podem haver categorias com nomes semelhantnes 
      http_response_code(401);
      echo json_encode(["error" => "Já existe uma categoria com este nome."]);
      exit;
    }
    $response = $this->categoriesService->createCategory($type, $parking_fee);
    http_response_code(201);
    echo json_encode(["response" => $response]); 
  }
  
}