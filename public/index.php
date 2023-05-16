<?php

use App\Core\Renderer;
use App\Core\Router;

require_once '../vendor/autoload.php';

$routes = require_once '../routes.php';
$response = Router::route($routes);

$renderer = new Renderer();

echo $renderer->render($response);

