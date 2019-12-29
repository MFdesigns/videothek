<?php

// API controllers
require_once ROOT . "/controllers/CustomerAPIController.php";

class APIController {
  function __construct($url) {

    // Get the first URL dir and request method to determine
    // what controller is responsible to handle this request.
    $method = $_SERVER["REQUEST_METHOD"];
    $apiRoute = isset($url[0]) ? $url[0] : NULL;

    // Check if the first dir in URL is a valid API route.
    // If this is the case shift the URL and pass the
    // rest of path to the corresponding API Controller.
    if ($apiRoute) {
      array_shift($url);

      switch ($apiRoute) {
        case "customers":
          new CustomerAPIController($url, $method);
        break;

        case "lendings":
          http_response_code(404);
          die();
        break;

        case "videos":
          http_response_code(404);
          die();
        break;

        default:
          http_response_code(404);
          die();
        break;
      }
    } else {
      http_response_code(404);
      die();
    }

  }
}

?>
