<?php

namespace Madewithlove\Jenga;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\ServerMiddleware\MiddlewareInterface;

class Stack implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $middlewares;

    /**
     * @param ContainerInterface $container
     * @param array $middlewares
     */
    public function __construct(ContainerInterface $container, array $middlewares)
    {
        $this->container = $container;
        $this->middlewares = $middlewares;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function call(ServerRequestInterface $request)
    {
        return $this->process($request, new EmptyDelegate());
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * to the next middleware component to create the response.
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $previous
     *
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        DelegateInterface $previous
    ) {
        foreach (array_reverse($this->middlewares) as $middleware) {
            if ($middleware instanceof MiddlewareInterface) {
                $previous = new Delegate($middleware, $previous);
            } else {
                $previous = new ResolvingDelegate($this->container, $middleware, $previous);
            }
        }

        return $previous->process($request);
    }
}
