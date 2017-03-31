<?php

namespace Madewithlove\Jenga;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\ServerMiddleware\MiddlewareInterface;

class ResolvingDelegate implements DelegateInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var string
     */
    private $middleware;

    /**
     * @var DelegateInterface
     */
    private $next;

    /**
     * @param ContainerInterface $container
     * @param string $middleware
     * @param DelegateInterface $next
     */
    public function __construct(ContainerInterface $container, $middleware, DelegateInterface $next)
    {
        $this->container = $container;
        $this->middleware = $middleware;
        $this->next = $next;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request)
    {
        /** @var MiddlewareInterface $middleware */
        $middleware = $this->container->get($this->middleware);

        return $middleware->process($request, $this->next);
    }
}
