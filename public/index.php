<?php

use App\Core\Renderer;
use App\Core\Router;

require_once '../vendor/autoload.php';

$router = new Router();

$response = $router::route();

$renderer = new Renderer();

echo $renderer->render($response);

