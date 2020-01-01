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
      echo "List all lendings";

      // Return single lending resource by id
    } else if (preg_match($validId, $route)) {
      echo "List single lending";

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
}

?>
