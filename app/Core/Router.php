<?php declare(strict_types=1);

namespace App\Core;

use FastRoute;

class Router
{
    public static function route()
    {
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $router) {
            $router->addRoute(
                'GET', '/', ['App\Controllers\CharacterController', 'characters']
            );
            $router->addRoute(
                'GET', '/search', ['App\Controllers\CharacterController', 'search']
            );

            $router->addRoute(
                'GET', '/characters[/{page}]', ['App\Controllers\CharacterController', 'characters']
            );

            $router->addRoute(
                'GET', '/episodes', ['App\Controllers\EpisodeController', 'allEpisodes']
            );
            $router->addRoute(
                'GET', '/locations[/{page}]', ['App\Controllers\LocationController', 'locations']
            );
            $router->addRoute(
                'GET', '/character[/{page}]', ['App\Controllers\CharacterController', 'singleCharacter']
            );
            $router->addRoute(
                'GET', '/episode[/{page}]', ['App\Controllers\EpisodeController', 'singleEpisode']
            );
            $router->addRoute(
                'GET', '/location[/{page}]', ['App\Controllers\LocationController', 'singleLocation']
            );
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