<?php

require_once "utils.php";
require_once ROOT . "/DB.php";

class CustomerModel {
  private $conn;
  private $searchPrepStmt;

  function __construct() {
    $this->conn = new DB();
  }

  /**
   * Returns a list of all customers
   *
   * @param string $orderBy valid customer column
   * @param string $direction ASC or DESC
   * @return PDOStatement
   */
  function getList($orderBy = "CustId", $direction = "ASC") {
    $sql = "SELECT CustId, CustName, CustSurname FROM TCustomers WHERE CustDeleted = false ORDER BY $orderBy $direction";
    $result = $this->conn->query($sql);
    return $result;
  }

  /**
   * Searches for the keyword in customers table
   *
   * @param string $keyword
   * @param string $orderBy
   * @param string $direction
   * @return PDOStatement
   */
  function search($keyword, $orderBy = "CustId", $direction = "ASC") {
    $sql = "SELECT CustId, CustName, CustSurname FROM TCustomers WHERE CustDeleted = false AND CustId LIKE '$keyword' OR CustName LIKE '%$keyword%' OR CustSurname LIKE '%$keyword%' ORDER BY $orderBy $direction";
    $result = $this->conn->query($sql);
    return $result;
  }

  /**
   * Returns an associated array with the customer data
   *
   * @param int $custId
   * @return string[]
   */
  function getById($custId) {
    $sql = "SELECT CustId, CustTitle, CustName, CustSurname, CustBirthday, CustPhoneNumber, CustStreet, CustStreetNumber, TPlaces.PlaceONRP, PlaceCity FROM TCustomers, TPlaces WHERE TCustomers.PlaceONRP = TPlaces.PlaceONRP AND CustId = $custId AND CustDeleted = false";
    $result = $this->conn->query($sql);
    return $result->fetch(PDO::FETCH_ASSOC);
  }

  /**
   * Updates customer data
   *
   * @param int $id
   * @param string[] $data
   * @return PDOStatement
   */
  function updateById($id, $data) {
    $sql = "UPDATE TCustomers SET CustTitle='" . $data["title"] . "', CustName='" . $data["name"] . "', CustSurname='" . $data["surname"] . "', CustBirthday='" . $data["birthday"] . "', CustPhoneNumber='" . $data["phone"] . "', CustStreet='" . $data["street"] . "', CustStreetNumber='" . $data["streetNumber"] . "' WHERE CustId=$id";
    $result = $this->conn->query($sql);
    return $result;
  }

  /**
   * Creates new customer
   *
   * @param string[] $data
   * @return PDOStatement
   */
  function create($data) {
    $sql = "INSERT INTO TCustomers (CustTitle, CustName, CustSurname, CustBirthday, CustPhoneNumber, CustStreet, CustStreetNumber, CustDeleted, PlaceONRP)
            VALUES ('" . $data["title"] . "',
            '" . $data["name"] . "',
            '" . $data["surname"] . "',
            '" . $data["birthday"] . "',
            '" . $data["phone"] . "',
            '" . $data["street"] . "',
            '" . $data["streetNumber"] . "',
             false, 4805)";
    $result = $this->conn->query($sql);
    $id = $this->conn->lastInsertId();

    return [
      "result" => $result,
      "id" => $id
    ];
  }

  /**
   * Sets the deleted attribute to true of selected customer
   *
   * @param int $id
   * @return PDOStatement
   */
  function delete($id) {
    $sql = "UPDATE TCustomers SET CustDeleted=true WHERE CustId=$id";
    $result = $this->conn->query($sql);
    return $result;
  }
}

?>
