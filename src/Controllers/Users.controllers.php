<?php

class Users_controllers {
  private $userService;
  public function __construct(Users_services $users_services) {
    $this->userService = $users_services;
    session_start();
  }

  public function signIn($post_form) {
    if(array_key_exists("email", $post_form) && array_key_exists("password", $post_form)) { 
      $email = $post_form["email"];
      $password = $post_form["password"];
    }
    $userDataFromDb = $this->userService->checkUserExists($email);

    if(!$userDataFromDb){
      http_response_code(401);
      echo json_encode(["E-mail ou senha inválidos."]);
      exit;
    }

    if(
      array_key_exists("pass", $userDataFromDb) && 
      array_key_exists("id", $userDataFromDb) &&
      array_key_exists("email", $userDataFromDb)
      ){
      $dbPassword = $userDataFromDb["pass"];
      $user_id = $userDataFromDb["id"];
      $email = $userDataFromDb["email"];
    }

    $passwordIsCorrect = password_verify($password, $dbPassword);

    if(!$passwordIsCorrect){
      http_response_code(401);
      echo json_encode(["E-mail ou senha inválidos."]);
      exit;
    }
    
    $token = AuthJwtService::encodeToken(array("email" => $email));
    
    $_SESSION["user_id"] = $user_id;
    $_SESSION["email"] = $email;

    http_response_code(200);
    echo json_encode([
      "access_token"=> $token, 
      "message" => "Login feito com sucesso."
    ]);
  }
  public function signUp($post_form) {
    if(array_key_exists("email", $post_form) && array_key_exists("password", $post_form)) { 
      $email = $post_form["email"];
      $password = $post_form["password"];
    }
    $userExists = $this->userService->checkUserExists($email);
    if($userExists) { 
      echo json_encode(["Error" => "E-mail já em uso."]);
      exit;
    }
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $this->userService->insertUser($email, $hashedPassword);
  
    http_response_code(201);
    echo json_encode([
      "message" => "Usuário cadastrado."
    ]);
    exit;
  }
  
}