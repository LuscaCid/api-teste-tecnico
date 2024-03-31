<?php
class Users_routes extends BaseRoutes{
  private $usersControllers = null;

  public function __construct($object) {
    parent::__construct($object);
    $userService = new Users_services();
    $this->usersControllers = new Users_controllers($userService);
  }

  public function loadUsersRoutes() {
    switch ($this->path) { 
      case "users/signup":
        if($this->method == "POST") { 
          $this->usersControllers->signUp($this->post_form);
        }
      break;
      case "users/signin":
        if($this->method == "POST") { 
          $this->usersControllers->signIn(post_form: $this->post_form);
        }
      break;
      
      default:
        http_response_code(404);
        json_encode(["Error" => "Not Found"]);
    }
  }
}