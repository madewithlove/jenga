<?php

namespace Madewithlove\Jenga\Unit;

use Madewithlove\Jenga\ResolvingDelegate;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\ServerMiddleware\MiddlewareInterface;

class ResolvingDelegateTest extends TestCase
{
    /** @test */
    public function implements_interface()
    {
        // Arrange
        $container = $this->prophesize(ContainerInterface::class);
        $delegate = $this->prophesize(DelegateInterface::class);
        $delegate = new ResolvingDelegate($container->reveal(), MiddlewareInterface::class, $delegate->reveal());

        // Act

        // Assert
        self::assertInstanceOf(DelegateInterface::class, $delegate);
    }

    /** @test */
    public function resolves_middleware_from_container()
    {
        // Arrange
        $container = $this->prophesize(ContainerInterface::class);
        $middleware = $this->prophesize(MiddlewareInterface::class);
        $request = $this->prophesize(ServerRequestInterface::class);
        $response = $this->prophesize(ResponseInterface::class);
        $delegate = $this->prophesize(DelegateInterface::class);

        $container->get(MiddlewareInterface::class)->shouldBeCalled()->willReturn($middleware->reveal());
        $middleware->process($request->reveal(), $delegate->reveal())->shouldBeCalled()->willReturn($response->reveal());

        $delegate = new ResolvingDelegate($container->reveal(), MiddlewareInterface::class, $delegate->reveal());

        // Act
        $result = $delegate->process($request->reveal());

        // Assert
        self::assertEquals($response->reveal(), $result);
    }
}
