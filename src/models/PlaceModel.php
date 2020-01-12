<?php

require_once "utils.php";
require_once ROOT . "/DB.php";

class PlaceModel {
  private $conn;

  function __construct() {
    $this->conn = new DB();
  }

  /**
   * Gets all places matching plz
   *
   * @param int $plz
   * @return PDOStatement
   */
  function getAllByPLZ($plz) {
    $sql = "SELECT PlaceONRP, PlacePLZ, PlaceCity FROM TPlaces WHERE PlacePLZ = '$plz';";
    $result = $this->conn->query($sql);
    return $result;
  }
}

?>
