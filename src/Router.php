<?php

require_once ROOT . "/utils.php";
require_once ROOT . "/APIRouter.php";

class Router {

  private $Routes = [];

  function __construct() { }

  function route($url) {
    $URLPath = parse_url($url, PHP_URL_PATH);
    $pathSegments = explode("/", $URLPath);

    // Check if route is root
    if ($URLPath === "/") {
      pageNotFound();
    } else if ($pathSegments[1] === "api") {
      array_shift($pathSegments);
      array_shift($pathSegments);
      new APIRouter($pathSegments);
    } else {
      $routerURL = $pathSegments[1];

      // Check if route exists
      if (array_key_exists($routerURL, $this->Routes)) {
        include_once(ROOT . "/controllers/" . $this->Routes[$routerURL] .  ".php");

        if (class_exists($this->Routes[$routerURL])) {
          $routerClass = $this->Routes[$routerURL];
          array_shift($pathSegments);
          array_shift($pathSegments);
          $rt = new $routerClass($pathSegments);
        } else {
          pageNotFound();
        }
      } else {
        pageNotFound();
      }
    }
  }

  function registerRoute($path, $routerClass) {
    $this->Routes[$path] = $routerClass;
  }
}

?>
