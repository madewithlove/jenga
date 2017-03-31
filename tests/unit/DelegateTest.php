<?php

namespace Madewithlove\Jenga\Unit;

use Madewithlove\Jenga\Delegate;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\ServerMiddleware\MiddlewareInterface;

class DelegateTest extends TestCase
{
    /** @test */
    public function implements_interface()
    {
        // Arrange
        $middleware = $this->prophesize(MiddlewareInterface::class);
        $delegate = $this->prophesize(DelegateInterface::class);
        $delegate = new Delegate($middleware->reveal(), $delegate->reveal());

        // Act

        // Assert
        self::assertInstanceOf(DelegateInterface::class, $delegate);
    }

    /** @test */
    public function delegates_to_middleware()
    {
        // Arrange
        $middleware = $this->prophesize(MiddlewareInterface::class);
        $delegate = $this->prophesize(DelegateInterface::class);
        $request = $this->prophesize(ServerRequestInterface::class);
        $response = $this->prophesize(ResponseInterface::class);

        $middleware->process($request->reveal(), $delegate->reveal())->shouldBeCalled()->willReturn($response->reveal());

        $delegate = new Delegate($middleware->reveal(), $delegate->reveal());

        // Act
        $result = $delegate->process($request->reveal());

        // Assert
        self::assertEquals($response->reveal(), $result);
    }
}
