<?php

require_once ROOT . "/utils.php";
require_once ROOT . "/models/CustomerModel.php";

class CustomerAPIController {

  private $model;

  function __construct($url, $method) {
    $this->model = new CustomerModel();

    $requesRoute = isset($url[0]) ? $url[0] : NULL;

    // Determines what kind of customer data the user requests
    $validId = "/^[0-9]+$/";
    if (preg_match($validId, $requesRoute)) {
      switch ($method) {
        case "GET":
          $this->getCustomerById($requesRoute);
        break;

        case "PUT":
          $this->updateCustomerById($requesRoute);
        break;
      }
    } else if ($requesRoute === "search" && $method === "GET") {
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

  function updateCustomerById($id) {
    // Get PUT data
    parse_str(file_get_contents("php://input"), $_PUT);

    // Check if all data is set
    if (
      isset($_PUT["title"]) &&
      isset($_PUT["name"]) &&
      isset($_PUT["surname"]) &&
      isset($_PUT["birthday"]) &&
      isset($_PUT["phone"]) &&
      isset($_PUT["street"]) &&
      isset($_PUT["streetNumber"])
    ) {
      // Validate customer data
      $validTitle = "/^(Frau|Herr)$/";
      $validName = "/^[^0-9]+$/";
      $validBirthday = "/^[0-9]{4}-(0[1-9]{1}|1[0-2]{1})-(0[1-9]{1}|1[0-2]{1})$/";
      $validPhone = "/^[0-9]{10}$/";
      $validStreetNumber = "/^[0-9]+[a-zA-Z]*$/";

      if (
        preg_match($validTitle, $_PUT["title"]) === 0 ||
        preg_match($validName, $_PUT["name"]) === 0 ||
        preg_match($validName, $_PUT["surname"]) === 0 ||
        preg_match($validBirthday, $_PUT["birthday"]) === 0 ||
        preg_match($validPhone, $_PUT["phone"]) === 0 ||
        preg_match($validName, $_PUT["street"]) === 0 ||
        preg_match($validStreetNumber, $_PUT["streetNumber"]) === 0
      ) {
        http_response_code(400);
        die();
      }

      $response = $this->model->updateById(125, $_PUT);

      if(!$response) {
        http_response_code(400);
        die();
      } else {
        http_response_code(200);
        die();
      }
    } else {
      http_response_code(400);
      die();
    }
  }

}

?>
