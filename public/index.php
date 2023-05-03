<?php

use App\Core\Renderer;
use App\Core\Router;

require_once '../vendor/autoload.php';

$response = Router::route();

$renderer = new Renderer();

echo $renderer->render($response);

