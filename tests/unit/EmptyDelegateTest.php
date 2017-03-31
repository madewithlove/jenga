<?php

namespace Madewithlove\Jenga\Unit;

use Madewithlove\Jenga\EmptyDelegate;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\ServerMiddleware\DelegateInterface;

class EmptyDelegateTest extends TestCase
{
    /** @test */
    public function implements_interface()
    {
        // Arrange
        $delegate = new EmptyDelegate();
        $request = $this->prophesize(ServerRequestInterface::class);

        // Act
        $result = $delegate->process($request->reveal());

        // Assert
        self::assertNull($result);
        self::assertInstanceOf(DelegateInterface::class, $delegate);
    }
}
