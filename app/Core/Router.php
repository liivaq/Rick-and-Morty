<?php declare(strict_types=1);

namespace App\Core;

use FastRoute;

class Router
{
    public static function route()
    {
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
            $r->addRoute('GET', '/', ['App\Controllers\Controller', 'characters']);
            $r->addRoute('GET', '/characters[/{page}]', ['App\Controllers\Controller', 'characters']);
            $r->addRoute('GET', '/episodes[/{page}]', ['App\Controllers\Controller', 'episodes']);
            $r->addRoute('GET', '/locations[/{page}]', ['App\Controllers\Controller', 'locations']);
            $r->addRoute('GET', '/character[/{page}]', ['App\Controllers\Controller', 'singleCharacter']);
            $r->addRoute('GET', '/episode[/{page}]', ['App\Controllers\Controller', 'singleEpisode']);
            $r->addRoute('GET', '/location[/{page}]', ['App\Controllers\Controller', 'singleLocation']);
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];


        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);


        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                return new View('notFound', []);
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                [$controller, $method] = $handler;
                return (new $controller)->{$method}($vars);
        }
        return null;
    }
}