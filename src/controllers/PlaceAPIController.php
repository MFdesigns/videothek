<?php

require_once ROOT . "/utils.php";
require_once ROOT . "/controllers/APIController.php";
require_once ROOT . "/models/PlaceModel.php";

class PlaceAPIController extends APIController {

  private $model;

  function __construct($url, $method) {
    $this->model = new PlaceModel();

    $requesRoute = isset($url[0]) ? $url[0] : NULL;
    $validPLZ = "/^[0-9]{4}$/";

    switch ($requesRoute) {
      case 'plz':
        if (isset($url[1]) && preg_match($validPLZ, $url[1]) !== 0) {
          $this->getAllPlacesByPLZ($url[1]);
        }
      break;

      default:
        panic(404);
      break;
    }
  }

  /**
   * Returns a list with all Places matching the PLZ
   *
   * @param int $plz
   * @return void
   */
  function getAllPlacesByPLZ($plz) {
    $result = $this->model->getAllByPLZ($plz);

    if (!$result) {
      panic(400);
    }

    $places = [];
    foreach ($result as $place) {
      array_push($places, [
        "onrp" => $place["PlaceONRP"],
        "plz" => $place["PlacePLZ"],
        "city" => $place["PlaceCity"],
      ]);
    }

    parent::returnJSON($places);
  }
}
?>
