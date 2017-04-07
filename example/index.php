<?php

require 'vendor/autoload.php';

use League\Container\Container;
use League\Container\ReflectionContainer;
use League\Route\RouteCollection;
use Madewithlove\Jenga\Example\LeagueRouterMiddleware;
use Madewithlove\Jenga\Example\RobotsMiddleware;
use Madewithlove\Jenga\Stack;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;

$router = new RouteCollection();
$router->get('/{any}', function (ServerRequestInterface $request, ResponseInterface $response) {
    $response->getBody()->write('<h1>'.$request->getUri()->getPath().'</h1>');

    return $response;
});

$container = new Container();
$container->delegate(new ReflectionContainer());
$container->share(RouteCollection::class, $router);

$middleware = [
    new RobotsMiddleware(),
    LeagueRouterMiddleware::class,
];

$stack = new Stack($container, $middleware);

(new SapiEmitter())->emit($stack->call(ServerRequestFactory::fromGlobals()));
