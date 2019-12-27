<?php

function pageNotFound() {
  http_response_code(404);
  // TODO: custom 404 page
  die();
}

?>
