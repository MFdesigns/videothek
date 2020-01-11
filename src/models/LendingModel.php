<?php

require_once ROOT . "/DB.php";

class LendingModel {

  private $conn;

  function __construct() {
    $this->conn = new DB();
  }

  function add($data) {
    $sql = "INSERT INTO TLendings (VidNumber, CustId, LendFrom, LendDeleted) VALUES ($data[vidId], $data[custId], '$data[from]', false);";
    $result = $this->conn->query($sql);
    $id = $this->conn->lastInsertId();
    return [
      "result" => $result,
      "id" => $id
    ];
  }

  function delete($id) {
    $sql = "UPDATE TLendings SET LendDeleted = true WHERE LendId = $id;";
    $result = $this->conn->query($sql);
    return $result;
  }

  function update($id, $data) {
    if (empty($data["until"])) {
      $sql = "UPDATE TLendings SET LendFrom='$data[from]', LendUntil=NULL, VidNumber = $data[vidId] WHERE LendId = $id";
    } else {
      $sql = "UPDATE TLendings SET LendFrom='$data[from]', LendUntil='$data[until]', VidNumber = $data[vidId] WHERE LendId = $id";
    }
    $result = $this->conn->query($sql);
    return $result;
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
    $sql = "SELECT TVideos.VidNumber, LendId, VidTitle, VidPricePerDay, VidPrice, LendFrom, LendUntil FROM TLendings, TVideos WHERE TLendings.VidNumber = TVideos.VidNumber AND LendDeleted = false AND CustId = $customerId ORDER BY $orderBy $orderDirection";
    $result = $this->conn->query($sql);
    return $result;
  }
}

?>
