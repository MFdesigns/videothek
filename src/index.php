<?php

define("DEBUG", true);
define("ROOT", dirname(__FILE__));

if (DEBUG) {
  error_reporting(-1);
}

require_once "Router.php";

$router = new Router();

$router->registerRoute("kunden", "CustomerController");

$router->route($_SERVER["REQUEST_URI"]);

?>
