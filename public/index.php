<?php declare(strict_types=1);

/**
 * Front Controller PHP 8.0 version
 */
require dirname(__DIR__) . '/vendor/autoload.php';

$router = new Core\Router\Router();
// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');

$router->dispatch($_SERVER['QUERY_STRING']);