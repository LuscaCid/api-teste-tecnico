<?php

class Users_services {
  private $dbInstance;
  public function __construct() { 
    $this->dbInstance = new DbConnectionService();
  }
  public function insertUser(string $email, string $hashedPass) {
    $sql = "INSERT INTO users (email, pass) VALUES ('{$email}', '{$hashedPass}')";
    try {
      $result = $this->dbInstance->queryExec($sql);
      $response = $result->fetch(PDO::FETCH_ASSOC);
      return $response;
    } catch (PDOException $e) {
      http_response_code(500);  
      echo json_encode(["error"=> $e->getMessage()]);
      exit;
    }
  }
  public function checkUserExists(string $email) {
    $sql = "SELECT * FROM users WHERE email = '{$email}'";
    try {
      $result = $this->dbInstance->queryExec($sql);
      $response = $result->fetch(PDO::FETCH_ASSOC);
      return $response;
    } catch (PDOException $e) {  
      http_response_code(500);
      echo json_encode(["error" =>  $e->getMessage()] );
      exit;
    }

  }
}

// logica para trabalhar com a autenticacao do usuario na aplicacao

// registro aqui, login (username, password) --> tudo ok, retorna um 200 e o token para inserir nas requests

// aqui se vai implementar muito da logica presente em jwt