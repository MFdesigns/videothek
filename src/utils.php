<?php

/**
 * Returns 404 status and displays custom 404 page
 *
 * @return void
 */
function pageNotFound() {
  http_response_code(404);
  die();
}

/**
 * Sends HTTP response code and exits programm
 *
 * @param int $responseCode
 * @return void
 */
function panic($responseCode) {
  http_response_code($responseCode);
  die();
}

?>
