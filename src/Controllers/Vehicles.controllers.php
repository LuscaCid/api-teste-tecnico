<?php 

class Vehicles_controllers {

  private $vehiclesService;

  public function __construct(Vehicles_service $vehiclesService) {
    $this->vehiclesService = $vehiclesService;
  }

  public function view($page) {
    if(is_numeric($page)){
      $res = $this->vehiclesService->getPaginatedVehicles(intval($page));
    } 
    echo json_encode(["response" => $res]);
  }

  public function getVehiclesByLicensePlate($license_plate) {
    $response = $this->vehiclesService->getVehicleByLicensePlate($license_plate);
    echo json_encode(["response"=> $response]);
  }

  public function createVehicle($post_form){

    if(array_key_exists("license_plate", $post_form)) {
      $licensePlate = $post_form["license_plate"];
    } 
    $licensePlateAlreadyInUse = $this->vehiclesService->getVehicleByLicensePlate($licensePlate); 
    if($licensePlateAlreadyInUse){
      http_response_code(401);
      echo json_encode(["error" => "Ja existe um carro registrado com esta placa"]);
      return;
    } 
    $this->vehiclesService->insertVehicle($post_form);
    
    http_response_code(201);
    echo json_encode(["response"=> "Veículo criado."]);
  }

  public function vehicleReport($license_plate) {
    $response = $this->vehiclesService->getVehicleReport($license_plate);
    http_response_code(200);
    echo json_encode(["Historico_relatorio" => $response]);

  }

  public function vehiclesReportsPaginated($page) {

    $response = $this->vehiclesService->getVehiclesReports(intval($page));
    http_response_code(200);
    echo json_encode(["Historico_veiculos" => $response]);

  }
  public function setVehicleImage(int $vehicle_id) {
    isset($_FILES["vehicle_image"]) && $file = $_FILES["vehicle_image"];

    $permittedExtensions = array('pdf','jpg','png', "gif", "svg", "psd", "webp");

    $file_name = uniqid() . '-' . basename($file['name']);

    $fileExtension = pathinfo($file_name, PATHINFO_EXTENSION);

    $uploadPath = addslashes('C:/xampp/htdocs/api_parking/uploads/') . $file_name;
    if($file["size"] > 3000000) {
      http_response_code(401);
      echo json_encode(["error" => "Arquivo pesado demais para sofrer upload."]);
      exit;
    }
    if(!in_array(strtolower($fileExtension), $permittedExtensions)) {
      http_response_code(401);
      echo json_encode(["error" => "Apenas arquivos de imagem podem sofrer upload."]);
      exit;
    } 
    
    if(!move_uploaded_file($file['tmp_name'], $uploadPath)) {
      http_response_code(500);
      json_encode(["error" => "Não foi possivel salvar o arquivo"]);
      exit;
    }
    $this->saveInDatabase(intval($vehicle_id), $uploadPath, $file_name );
    http_response_code(200);
    echo json_encode(["response" => "Arquivo salvo com sucesso!"]);
    exit;
   }

   private function saveInDatabase(int $vehicle_id, string $vehicle_image_path, string $vehicle_name) {
    $vehicle_exists = $this->vehiclesService->getVehicleById($vehicle_id);
    if(!$vehicle_exists){
      http_response_code(404);
      echo json_encode(["error"=> "Veículo nao encontrado."]);
      exit;
    }
    // now its the time to save the image unique path inside db
    $serviceResponse = $this->vehiclesService->insertImageForAVehicle($vehicle_id,$vehicle_name, $vehicle_image_path);

    if($serviceResponse){
      http_response_code(200);
      echo json_encode(["response"=> "Imagem inserida com sucesso."]);
    }
   }
  
}