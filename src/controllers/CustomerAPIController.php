<?php

require_once ROOT . "/utils.php";
require_once ROOT . "/models/CustomerModel.php";

class CustomerAPIController {

  private $model;

  function __construct($url, $method) {
    $this->model = new CustomerModel();

    $requestType = isset($url[0]) ? $url[0] : NULL;

    // Determines what kind of customer data the user requests
    $validId = "/^[0-9]+$/";
    if (preg_match($validId, $requestType)) {
      $this->getCustomerById($requestType);
    } else if ($requestType === "search") {
      $this->searchCustomers();
    } else {
      $this->getCustomerList();
    }
  }

  /**
   * Checks if the paramters to order the data are valid
   * if not returns default order params
   *
   * @return string[]
   */
  function validateOrderParams() {
    // URL parameter to DB attribute lookup table
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
    if (isset($_GET["keyword"]) && preg_match($validKeyword, $_GET["keyword"])) {

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

  function getCustomerById($id) {
    $result = $this->model->getById($id);

    if (!$result) {
      http_response_code(404);
      die();
    }

    // Translate DB attribute names into API naming schema
    $customer["id"] = $result["CustId"];
    $customer["title"] = $result["CustTitle"];
    $customer["name"] = $result["CustName"];
    $customer["surname"] = $result["CustSurname"];
    $customer["birthday"] = $result["CustBirthday"];
    $customer["phone"] = $result["CustPhoneNumber"];
    $customer["street"] = $result["CustStreet"];
    $customer["streetNumber"] = $result["CustStreetNumber"];
    $customer["onrp"] = $result["PlaceONRP"];
    $customer["city"] = $result["PlaceCity"];

    header('Content-Type: text/json; charset=UTF-8');
    echo json_encode($customer);
  }
}

?>
