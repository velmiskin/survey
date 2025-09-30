<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Exception;

use App\Component\User\Domain\Exception\InvalidEmailException;
use PHPUnit\Framework\TestCase;

final class InvalidEmailExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new InvalidEmailException();

        $this->assertEquals('Invalid email format', $exception->getMessage());
        $this->assertEquals(InvalidEmailException::MESSAGE, $exception->getMessage());
    }

    public function testExceptionType(): void
    {
        $exception = new InvalidEmailException();

        $this->assertInstanceOf(\DomainException::class, $exception);
        $this->assertInstanceOf(InvalidEmailException::class, $exception);
    }

    public function testExceptionConstant(): void
    {
        $this->assertEquals('Invalid email format', InvalidEmailException::MESSAGE);
    }
}