<?php

require_once ROOT . "/utils.php";
require_once ROOT . "/controllers/APIController.php";
require_once ROOT . "/models/LendingModel.php";

class LendingAPIController extends APIController {

  private $lendingModel;

  function __construct($url, $method) {
    $this->lendingModel = new LendingModel();

    $route = isset($url[0]) ? $url[0] : NULL;
    $customerIdParam = isset($url[1]) ? $url[1] : NULL;

    // Determines what kind of customer data the user requests
    $validId = "/^[0-9]+$/";

    // Lendings root => list all lendings
    if (!$route) {
      switch ($method) {
        case "POST":
          $this->addLending();
          break;

        default:
          panic(501);
      }

      // Return single lending resource by id
    } else if (preg_match($validId, $route)) {
      switch ($method) {
        case "PUT":
          $this->updateLending($route);
          break;

        case "DELETE":
          $this->deleteLending($route);
          break;

        default:
          panic(501);
      }

      // Return lendings from specific customer id with video information
    } else if ($route === "customer" && preg_match($validId, $customerIdParam)) {
      switch ($method) {
        case "GET":
          $this->getLendingsFromCustomerList($customerIdParam);
          break;

        default:
          panic(501);
      }

    } else {
      panic(404);
    }
  }

  /**
   * Handles GET Lendings from specifig customer by id request.
   * Returns a list with lendings and video information.
   *
   * @param int $customerId
   * @return void
   */
  private function getLendingsFromCustomerList($customerId) {

    // URL param to DB Attribute lookup table
    $dbAttributes = [
      "vidId" => "VidNumber",
      "title" => "VidTitle",
      "pricePerDay" => "VidPricePerDay",
      "price" => "VidPrice",
      "from" => "LendFrom",
      "until" => "LendUntil",
    ];

    $order = "VidNumber"; // Default value
    if (isset($_GET["order"])) {
      // Check if URL param is valid, if not return default parameter
      // If param is valid return DB Attribute of param
      $order = array_key_exists($_GET["order"], $dbAttributes) ? $dbAttributes[$_GET["order"]] : "VidNumber";
    }

    // Validate order direction param
    $direction = "ASC"; // Default value
    if (isset($_GET["direction"])) {
      $direction = $_GET["direction"] === "desc" ? "DESC" : "ASC";
    }

    $result = $this->lendingModel->getLendingsFromCustomer($customerId, $order, $direction);

    if (!$result) {
      panic(400);
    }

    $lendings = [];
    foreach ($result as $lending) {
      array_push($lendings, [
        "lendId" => $lending["LendId"],
        "vidId" => $lending["VidNumber"],
        "title" => $lending["VidTitle"],
        "pricePerDay" => $lending["VidPricePerDay"],
        "price" => $lending["VidPrice"],
        "from" => $lending["LendFrom"],
        "until" => $lending["LendUntil"],
      ]);
    }

    parent::returnJSON($lendings);
  }

  function validateLendingsInput($data) {
    if (
      isset($data["vidId"]) &&
      isset($data["custId"]) &&
      isset($data["from"]) &&
      isset($data["until"])
    ) {
      $validId = "/^[0-9]+$/";
      $validDate = "/(^$|^[0-9]{4}-(0[1-9]{1}|1[0-2]{1})-(0[1-9]{1}|1[0-9]{1}|2[0-9]{1}|3[01]{1})$)/";

      if (
        preg_match($validId, $data["vidId"]) === 0 ||
        preg_match($validId, $data["custId"])  === 0 ||
        preg_match($validDate, $data["from"]) === 0 ||
        preg_match($validDate, $data["until"]) === 0
      ) {
        return false;
      } else {
        return true;
      }
    } else {
      return false;
    }
  }

  function addLending() {
    $phpInput = file_get_contents("php://input");
    if (!$phpInput) {
      panic(500);
    }

    $POST = json_decode($phpInput, true);

    $validInput = $this->validateLendingsInput($POST);

    if (!$validInput) {
      panic(400);
    }

    $result = $this->lendingModel->add($POST);

    if (!$result["result"]) {
      panic(400);
    }

    $json["id"] = $result["id"];
    $json["href"] = parent::getProtocol() . "://" . $_SERVER["SERVER_NAME"] . "/api/lendings/" . $result["id"];

    parent::returnJSON($json);
  }

  function updateLending($id) {
    $phpInput = file_get_contents("php://input");
    if (!$phpInput) {
      panic(500);
    }

    $PUT = json_decode($phpInput, true);

    $validInput = $this->validateLendingsInput($PUT);
    if (!$validInput) {
      panic(400);
    }

    $result = $this->lendingModel->update($id, $PUT);
    if (!$result) {
      panic(400);
    }
  }

  function deleteLending($id) {
    $result = $this->lendingModel->delete($id);

    if (!$result) {
      panic(400);
    }
  }
}

?>
