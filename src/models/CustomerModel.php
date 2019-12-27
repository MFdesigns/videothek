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
   * Returns a list of customers
   *
   * @param string $orderBy valid customer column
   * @param string $ascending ASC or DESC
   * @return void
   */
  function getList($orderBy = "CustId", $direction = "ASC") {
    $sql = "SELECT CustId, CustName, CustSurname FROM TCustomers WHERE CustDeleted = false ORDER BY $orderBy $direction";
    $result = $this->conn->query($sql);
    return $result;
  }

  function search($keyword, $orderBy = "CustId", $direction = "ASC") {
    $sql = "SELECT CustId, CustName, CustSurname FROM TCustomers WHERE CustDeleted = false AND CustId LIKE '$keyword' OR CustName LIKE '%$keyword%' OR CustSurname LIKE '%$keyword%' ORDER BY $orderBy $direction";
    $result = $this->conn->query($sql);
    return $result;
  }

  function getById($custId) {
    $sql = "SELECT * FROM TCustomers WHERE CustId = $custId AND CustDeleted = false";
    $result = $this->conn->query($sql);

    return $result;
  }

}

?>
