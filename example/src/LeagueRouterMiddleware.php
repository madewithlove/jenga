<?php

namespace Madewithlove\Jenga\Example;

use League\Route\RouteCollection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\ServerMiddleware\MiddlewareInterface;
use Zend\Diactoros\Response;

class LeagueRouterMiddleware implements MiddlewareInterface
{
    /**
     * @var RouteCollection
     */
    private $router;

    /**
     * @param RouteCollection $router
     */
    public function __construct(RouteCollection $router)
    {
        $this->router = $router;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        DelegateInterface $delegate
    ) {
        return $this->router->dispatch($request, new Response());
    }
}
