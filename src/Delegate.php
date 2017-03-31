<?php

namespace Madewithlove\Jenga;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\ServerMiddleware\MiddlewareInterface;

class Delegate implements DelegateInterface
{
    /**
     * @var MiddlewareInterface
     */
    private $middleware;

    /**
     * @var DelegateInterface
     */
    private $next;

    /**
     * @param MiddlewareInterface $middleware
     * @param DelegateInterface $next
     */
    public function __construct(MiddlewareInterface $middleware, DelegateInterface $next)
    {
        $this->middleware = $middleware;
        $this->next = $next;
    }

    /**
     * Dispatch the next available middleware and return the response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request)
    {
        return $this->middleware->process($request, $this->next);
    }
}
