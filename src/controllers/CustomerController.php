<?php

require_once ROOT . "/models/CustomerModel.php";

class CustomerController {
  private $customerModel;
  private static $pageTitle = "Kunden";

  function __construct() {
    $customerModel = new CustomerModel();

    $pageTitle = self::$pageTitle;
    $customerList = $customerModel->getList("CustId", "asc");

    // Return customer view
    include_once(ROOT . "/views/CustomerView.php");
  }
}

?>
