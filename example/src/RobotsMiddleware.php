<?php

namespace Madewithlove\Jenga\Example;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\ServerMiddleware\MiddlewareInterface;
use Zend\Diactoros\Response;

class RobotsMiddleware implements MiddlewareInterface
{
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
        if ($request->getMethod() === 'GET' && $request->getUri()->getPath() === '/robots.txt') {
            $response = new Response();
            $response->getBody()->write('User-agent: *');

            return $response;
        } else {
            return $delegate->process($request);
        }
    }
}
