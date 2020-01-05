<?php

define("ROOT", dirname(__FILE__));

require_once "Router.php";

$router = new Router();

$router->registerRoute("kunden", "CustomerController");

$router->route($_SERVER["REQUEST_URI"]);

?>
