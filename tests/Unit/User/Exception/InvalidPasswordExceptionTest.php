<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Exception;

use App\Component\User\Domain\Exception\InvalidPasswordException;
use PHPUnit\Framework\TestCase;

final class InvalidPasswordExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new InvalidPasswordException();

        $this->assertEquals('Password must be at least 8 characters long.', $exception->getMessage());
        $this->assertEquals(InvalidPasswordException::MESSAGE, $exception->getMessage());
    }

    public function testExceptionType(): void
    {
        $exception = new InvalidPasswordException();

        $this->assertInstanceOf(\DomainException::class, $exception);
        $this->assertInstanceOf(InvalidPasswordException::class, $exception);
    }

    public function testExceptionConstant(): void
    {
        $this->assertEquals('Password must be at least 8 characters long.', InvalidPasswordException::MESSAGE);
    }
}