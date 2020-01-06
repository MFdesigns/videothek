<?php

require_once ROOT . "/utils.php";
require_once ROOT . "/controllers/APIController.php";
require_once ROOT . "/models/CustomerModel.php";

class CustomerAPIController extends APIController {

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

        case "DELETE":
          $this->deleteCustomerById($requesRoute);
          break;

        default:
          panic(501);
          break;
      }
    } else if ($requesRoute === "search") {
      switch ($method) {
        case "GET":
          $this->searchCustomers();
          break;

        default:
          panic(501);
      }
    } else if (!$requesRoute) {
      switch ($method) {
        case "GET":
          $this->getCustomerList();
          break;

        case "POST":
          $this->addCustomer();
          break;

        default:
          panic(501);
      }
    } else {
      panic(404);
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

  // TODO!: Docs
  function getCustomerList() {
    $orderParams = $this->validateOrderParams();

    $result = $this->model->getList($orderParams["order"], $orderParams["direction"]);

    // If there is a query error return 400 Bad request
    if (!$result) {
      panic(400);
    }

    $customers = [];
    foreach ($result as $customer) {
      $href = parent::getProtocol() . "://" . $_SERVER["SERVER_NAME"] . "/api/customers/" . $customer["CustId"];
      $custData = [
        "id" => $customer["CustId"],
        "name" => $customer["CustName"],
        "surname" => $customer["CustSurname"],
        "href" => $href
      ];
      array_push($customers, $custData);
    }

    parent::returnJSON($customers);
  }

  // TODO!: Docs
  function searchCustomers() {
    $validKeyword = "/^[a-zA-ZÀ-ÿä-ü\w]+$/"; // TODO: Fix this!
    if (isset($_GET["keyword"]) && preg_match($validKeyword, $_GET["keyword"])) {

      $orderParams = $this->validateOrderParams();

      $result = $this->model->search($_GET["keyword"], $orderParams["order"], $orderParams["direction"]);

      if (!$result) {
        panic(400);
      }

      $customers = [];
      foreach ($result as $customer) {
        $href = parent::getProtocol() . "://" . $_SERVER["SERVER_NAME"] . "/api/customers/" . $customer["CustId"];
        array_push($customers, [
          "id" => $customer["CustId"],
          "name" => $customer["CustName"],
          "surname" => $customer["CustSurname"],
          "href" => $href
        ]);
      }

      parent::returnJSON($customers);

    } else {
      panic(400);
    }
  }

  /**
   * Handles customer entity request (GET)
   *
   * @param int $id
   * @return void
   */
  function getCustomerById($id) {
    $result = $this->model->getById($id);

    if (!$result) {
      panic(404);
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

    parent::returnJSON($customer);
  }

  /**
   * Checks if customer data is valid to UPDATE/CREATE customer resource
   *
   * @param string[]
   * @return bool
   */
    // Check if all data is set
  function validateCustomerResourceData($data) {
    // Check if all data is set
    if (
      isset($data["title"]) &&
      isset($data["name"]) &&
      isset($data["surname"]) &&
      isset($data["birthday"]) &&
      isset($data["phone"]) &&
      isset($data["street"]) &&
      isset($data["streetNumber"])
    ) {
      // Validate customer data
      $validTitle = "/^(Frau|Herr)$/";
      $validName = "/^[^0-9]+$/"; // Same for name, surname and street
      $validBirthday = "/^[0-9]{4}-(0[1-9]{1}|1[0-2]{1})-(0[1-9]{1}|1[0-2]{1})$/";
      $validPhone = "/^[0-9]{10}$/";
      $validStreetNumber = "/^[0-9]+[a-zA-Z]*$/";

      if (
        preg_match($validTitle, $data["title"]) === 0 ||
        preg_match($validName, $data["name"]) === 0 ||
        preg_match($validName, $data["surname"]) === 0 ||
        preg_match($validBirthday, $data["birthday"]) === 0 ||
        preg_match($validPhone, $data["phone"]) === 0 ||
        preg_match($validName, $data["street"]) === 0 ||
        preg_match($validStreetNumber, $data["streetNumber"]) === 0
      ) {
        return false;
      }

      return true;
    } else {
      return false;
    }
  }

  /**
   * Handles the UPDATE (PUT) request, performs validation and
   * updates the model
   *
   * @return void
   */
  function updateCustomerById($id) {
    // Get PUT data
    $phpInput = file_get_contents("php://input");
    if (!$phpInput) {
      panic(500);
    }

    parse_str($phpInput, $_PUT);

    // Check if input data is valid
    if (!$this->validateCustomerResourceData($_PUT)) {
      panic(400);
    }

    $response = $this->model->updateById($id, $_PUT);

    if(!$response) {
      panic(400);
    } else {
      panic(200);
    }
  }

  /**
   * Handles the CREATE (POST) request, performs validation and
   * adds new entity to model. Returns JSON containing the AUTO_INCREMENT
   * CustId and API URL to request customer information
   *
   * @return void
   */
  function addCustomer() {
    // Check if input data is valid
    if (!$this->validateCustomerResourceData($_POST)) {
      panic(400);
    }

    $response = $this->model->create($_POST);

    if(!$response["result"]) {
      panic(400);
    } else {
      // Create JSON response
      $json; // Response JSON
      $json["id"] = $response["id"];
      $json["href"] = parent::getProtocol() . "://" . $_SERVER["SERVER_NAME"] . "/api/customers/" . $response["id"];

      // Return newly created customer id and (GET) API url
      parent::returnJSON($json);
    }
  }

  /**
   * Handles the DELETE customer request
   * Does not actually perform a DELETE just marks the Entity as "deleted"
   *
   * @param int $id
   * @return void
   */
  function deleteCustomerById($id) {
    $response = $this->model->delete($id);

    if (!$response) {
      panic(400);
    }
  }

}

?>
