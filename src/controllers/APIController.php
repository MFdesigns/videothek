<?php

class APIController {
  function __construct() {}

  /**
   * JSON helper function
   *
   * @param [type] $data
   * @return void
   */
  protected static function returnJSON($data) {
    header('Content-Type: text/json; charset=UTF-8');
    echo json_encode($data);
    die();
  }

  /**
   * Returns current protocol
   *
   * @return string
   */
  protected static function getProtocol() {
    $protocol = "http";
    if(strpos($_SERVER["SERVER_PROTOCOL"], "https")) {
      $protocol = "https";
    }
    return $protocol;
  }
}

?>
