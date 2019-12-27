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
        $this->searchCustomers();
      } else if(preg_match("/^[0-9]+$/", $custId)) {
        pageNotFound();
      } else {
        pageNotFound();
      }
    } else {
      $this->getCustomerList();
    }
  }

  function validateOrderParams() {
    // Query parameter to DB Attribute lookup table
    $orderAttrLookup = [
      "id" => "CustId",
      "name" => "CustName",
      "surname" => "CustSurname"
    ];

    // Default parameters
    $orderBy = "CustId";
    $orderDirection = "ASC";

    // Validate order parameter
    if (isset($_GET["order"]) && array_key_exists($_GET["order"], $orderAttrLookup)) {
      $orderBy = $orderAttrLookup[$_GET["order"]];
    }

    // Validate order direction parameter
    if (isset($_GET["direction"]) && strtoupper($_GET["direction"]) === "DESC") {
      $orderDirection = "DESC";
    }

    return [
      "order" => $orderBy,
      "direction" => $orderDirection
    ];
  }

  function getCustomerList() {
    $orderParams = $this->validateOrderParams();

    $result = $this->model->getList($orderParams["order"], $orderParams["direction"]);

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

  function searchCustomers() {
    $validKeyword = "/^[a-zA-ZÀ-ÿä-ü\w]+$/";
    if ($_GET["keyword"] && preg_match($validKeyword, $_GET["keyword"])) {

      $orderParams = $this->validateOrderParams();

      $result = $this->model->search($_GET["keyword"], $orderParams["order"], $orderParams["direction"]);

      if (!$result) {
        http_response_code(400);
        die();
      }

      $customers = [];
      foreach ($result as $customer) {
        array_push($customers, [
          "id" => $customer["CustId"],
          "name" => $customer["CustName"],
          "surname" => $customer["CustSurname"]
        ]);
      }

      header('Content-Type: text/json; charset=UTF-8');
      echo json_encode($customers);

    } else {
      http_response_code(400);
      die();
    }
  }
}

?>
