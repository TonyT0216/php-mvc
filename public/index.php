<?php

/**
 * Front Controller PHP8.0
 */

require '../vendor/autoload.php';

$router = new Mvc\Library\Router\Router();

echo get_class($router);

//echo 'Requested URL = "' . $_SERVER['QUERY_STRING'] . '"';