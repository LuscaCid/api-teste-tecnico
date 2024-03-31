<?php

class Categories_service {
  private $dbInstance;
  public function __construct() {
    $this->dbInstance = new DbConnectionService();
  }

  public function getAllCategories() {
    $sql = "SELECT * FROM categories;";
    try {
      $result = $this->dbInstance->queryExec( $sql );
      $response = $result->fetchAll( PDO::FETCH_ASSOC );
      return $response;
    } catch (Exception $e) {
      return $e->getMessage(); 
    }
    
  }
  public function getCategoryByType($type) {
    //podem haver categorias nomes semelhantes
    $sql = "SELECT * FROM categories WHERE type LIKE '%{$type}%';";
    try {
      $result = $this->dbInstance->queryExec( $sql );
      $response = $result->fetchAll( PDO::FETCH_ASSOC );
      return $response;
    } catch ( Exception $e ) {
      return $e->getMessage();
    }
  }

  public function createCategory ($type, $parking_fee) {
    $sql = "INSERT INTO Categories (type, parking_fee) values ('{$type}', {$parking_fee})";
    try {
      $result = $this->dbInstance->queryExec( $sql );
      $response = $result->fetchAll( PDO::FETCH_ASSOC );
      return $response;
    } catch ( Exception $e ) {
      return $e->getMessage();
    }
  }
  
}