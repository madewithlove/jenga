<?php

namespace Madewithlove\Jenga\Unit;

use Interop\Container\ContainerInterface;
use Madewithlove\Jenga\EmptyDelegate;
use Madewithlove\Jenga\Stack;
use Madewithlove\Jenga\Stubs\InspectionMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\ServerMiddleware\MiddlewareInterface;

class StackTest extends TestCase
{
    /** @test */
    public function implements_interface()
    {
        // Arrange
        $container = $this->prophesize(ContainerInterface::class);
        $stack = new Stack($container->reveal(), []);

        // Act

        // Assert
        self::assertInstanceOf(MiddlewareInterface::class, $stack);
    }

    /** @test */
    public function calls_middlewares()
    {
        // Arrange
        $container = $this->prophesize(ContainerInterface::class);
        $request = $this->prophesize(ServerRequestInterface::class);
        $response = $this->prophesize(ResponseInterface::class);
        $delegate = $this->prophesize(DelegateInterface::class);
        $middleware = $this->prophesize(MiddlewareInterface::class);

        $middleware->process($request->reveal(), $delegate->reveal())
            ->shouldBeCalled()
            ->willReturn($response->reveal());

        $middlewares = [
            $inspectionMiddleware = new InspectionMiddleware(),
            $middleware->reveal(),
        ];

        $stack = new Stack($container->reveal(), $middlewares);

        // Act
        $result = $stack->process($request->reveal(), $delegate->reveal());

        // Assert
        self::assertEquals(1, $inspectionMiddleware->getNumberOfPasses());
        self::assertEquals($response->reveal(), $result);
    }

    /** @test */
    public function calls_resolved_middleware()
    {
        // Arrange
        $container = $this->prophesize(ContainerInterface::class);
        $request = $this->prophesize(ServerRequestInterface::class);
        $response = $this->prophesize(ResponseInterface::class);
        $delegate = $this->prophesize(DelegateInterface::class);
        $middleware = $this->prophesize(MiddlewareInterface::class);
        $inspectionMiddleware = new InspectionMiddleware();

        $middleware->process($request->reveal(), $delegate->reveal())
            ->shouldBeCalled()
            ->willReturn($response->reveal());

        $container->get(InspectionMiddleware::class)->shouldBeCalled()->willReturn($inspectionMiddleware);

        $middlewares = [
            InspectionMiddleware::class,
            InspectionMiddleware::class,
            $middleware->reveal(),
        ];

        $stack = new Stack($container->reveal(), $middlewares);

        // Act
        $result = $stack->process($request->reveal(), $delegate->reveal());

        // Assert
        self::assertEquals(2, $inspectionMiddleware->getNumberOfPasses());
        self::assertEquals($response->reveal(), $result);
    }

    /** @test */
    public function calls()
    {
        // Arrange
        $container = $this->prophesize(ContainerInterface::class);
        $request = $this->prophesize(ServerRequestInterface::class);
        $response = $this->prophesize(ResponseInterface::class);
        $middleware = $this->prophesize(MiddlewareInterface::class);

        $middleware->process($request->reveal(), new EmptyDelegate())
            ->shouldBeCalled()
            ->willReturn($response->reveal());

        $stack = new Stack($container->reveal(), [$middleware->reveal()]);

        // Act
        $result = $stack->call($request->reveal());

        // Assert
        self::assertEquals($response->reveal(), $result);
    }
}
