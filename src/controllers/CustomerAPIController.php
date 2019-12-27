<?php

require_once ROOT . "/utils.php";
require_once ROOT . "/models/CustomerModel.php";

class CustomerAPIController {

  private $model;

  function __construct($url, $method) {
    $this->model = new CustomerModel();

    // Check if there is a second url parameter
    if (array_key_exists(0, $url)) {
      if ($url[0] === "search") {
        pageNotFound();
      } else if(preg_match("/^[0-9]+$/", $custId)) {
        pageNotFound();
      } else {
        pageNotFound();
      }
    } else {
      $this->getCustomerList();
    }
  }

  function getCustomerList() {
    // Default parameters
    $orderBy = "CustId";
    $orderDirection = "ASC";

    // Query parameter to DB Attribute lookup table
    $validColumns = [
      "id" => "CustId",
      "name" => "CustName",
      "surname" => "CustSurname"
    ];

    // Validate order parameter
    if (isset($_GET["order"]) && array_key_exists($_GET["order"], $validColumns)) {
      $orderBy = $validColumns[$_GET["order"]];
    }

    // Validate order direction parameter
    if (isset($_GET["direction"]) && strtoupper($_GET["direction"]) === "DESC") {
      $orderDirection = "DESC";
    }

    $result = $this->model->getList($orderBy, $orderDirection);

    // If there is a query error return 400 Bad request
    if (!$result) {
      http_response_code(400);
      die();
    }

    $customers = [];
    foreach ($result as $customer) {
      $custData = [
        "id" => $customer["CustId"],
        "name" => $customer["CustName"],
        "surname" => $customer["CustSurname"]
      ];
      array_push($customers, $custData);
    }

    header('Content-Type: text/json; charset=UTF-8');
    echo json_encode($customers);
  }
}

?>
