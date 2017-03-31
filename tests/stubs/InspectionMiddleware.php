<?php

namespace Madewithlove\Jenga\Stubs;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\ServerMiddleware\MiddlewareInterface;

class InspectionMiddleware implements MiddlewareInterface
{
    /**
     * @var int
     */
    private $passes = 0;

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate) {
        $this->passes++;

        return $delegate->process($request);
    }

    /**
     * @return int
     */
    public function getNumberOfPasses()
    {
        return $this->passes;
    }
}
