<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Exception;

use App\Component\User\Domain\Exception\NonUniqueEmailException;
use PHPUnit\Framework\TestCase;

final class NonUniqueEmailExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new NonUniqueEmailException();

        $this->assertEquals('User with this email already exists', $exception->getMessage());
        $this->assertEquals(NonUniqueEmailException::MESSAGE, $exception->getMessage());
    }

    public function testExceptionType(): void
    {
        $exception = new NonUniqueEmailException();

        $this->assertInstanceOf(\DomainException::class, $exception);
        $this->assertInstanceOf(NonUniqueEmailException::class, $exception);
    }

    public function testExceptionConstant(): void
    {
        $this->assertEquals('User with this email already exists', NonUniqueEmailException::MESSAGE);
    }
}