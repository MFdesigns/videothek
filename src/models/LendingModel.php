<?php

require_once ROOT . "/DB.php";

class LendingModel {

  private $conn;

  function __construct() {
    $this->conn = new DB();
  }

  /**
   * Returns a list of all lendings belonging to a customer
   *
   * @param int $customerId
   * @param string $orderBy
   * @param string $orderDirection
   * @return PDOStatement
   */
  function getLendingsFromCustomer($customerId, $orderBy, $orderDirection) {
    $sql = "SELECT TVideos.VidNumber, VidTitle, VidPricePerDay, VidPrice, LendFrom, LendUntil FROM TLendings, TVideos WHERE TLendings.VidNumber =  TVideos.VidNumber AND LendDeleted = false AND CustId = $customerId ORDER BY $orderBy $orderDirection";
    $result = $this->conn->query($sql);
    return $result;
  }
}

?>
