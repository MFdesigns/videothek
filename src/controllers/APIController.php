<?php

// API controllers
require_once ROOT . "/controllers/CustomerAPIController.php";

class APIController {
  function __construct($url) {

    $method = $_SERVER["REQUEST_METHOD"];

    switch ($url[0]) {
      case "customers":
        array_shift($url);
        new CustomerAPIController($url, $method);
      break;

      case "lendings":
      break;

      case "videos":
      break;
    }
  }
}

?>
