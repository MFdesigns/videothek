<?php

class APIController {
  function __construct() {}

  protected static function returnJSON($data) {
    header('Content-Type: text/json; charset=UTF-8');
    echo json_encode($data);
    die();
  }
}

?>
